import { rm } from 'node:fs/promises';
import { Transform } from 'node:stream';
import path from 'node:path';

import gulp from 'gulp';
import gulpSass from 'gulp-sass';
import * as dartSass from 'sass';
import postcss from 'gulp-postcss';
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';
import plumber from 'gulp-plumber';
import zip from 'gulp-zip';
import * as esbuild from 'esbuild';
import browserSyncLib from 'browser-sync';
import sharp from 'sharp';
import { optimize as optimizeSvg } from 'svgo';

const { src, dest, watch, series, parallel } = gulp;
const sass = gulpSass(dartSass);
const browserSync = browserSyncLib.create();

const isProd = process.env.NODE_ENV === 'production';

/** Theme slug — must match the directory name WordPress installs this under. */
const THEME_SLUG = 'gulpress';

/**
 * The local WordPress URL BrowserSync proxies. Override per machine:
 *   WP_DEV_URL=http://mysite.test npm start
 */
const WP_DEV_URL = process.env.WP_DEV_URL || 'http://localhost:8000';

const paths = {
  styles: {
    entries: ['src/scss/main.scss', 'src/scss/editor.scss'],
    watch: 'src/scss/**/*.scss',
    dest: 'assets/css',
  },
  scripts: {
    entries: ['src/js/main.js'],
    watch: 'src/js/**/*.js',
    dest: 'assets/js',
  },
  images: {
    src: 'src/images/**/*.{jpg,jpeg,png,webp,avif,svg,gif}',
    dest: 'assets/images',
  },
  php: ['*.php', 'inc/**/*.php', 'template-parts/**/*.php'],
  // Files that belong in a distributable theme zip.
  package: [
    '**/*',
    '!node_modules/**',
    '!src/**',
    '!.github/**',
    '!package.json',
    '!package-lock.json',
    '!gulpfile.js',
    '!.browserslistrc',
    '!.editorconfig',
    '!.gitignore',
    '!.nvmrc',
    '!*.zip',
  ],
};

/** Log the error and keep the watcher alive instead of killing the process. */
const onError = function handleError(error) {
  console.error(`\n${error.plugin ?? 'build'}: ${error.message}\n`);
  this.emit('end');
};

/**
 * Small helper so each asset task can express "transform this buffer"
 * without hand-rolling a stream every time.
 *
 * @param {(file: object, push: (file: object) => void) => Promise<void>} fn
 */
const transform = (fn) =>
  new Transform({
    objectMode: true,
    async transform(file, _encoding, callback) {
      if (file.isNull() || file.isDirectory()) {
        callback(null, file);
        return;
      }

      try {
        await fn(file, (extra) => this.push(extra));
        callback(null, file);
      } catch (error) {
        error.plugin = 'transform';
        callback(error);
      }
    },
  });

// ---------------------------------------------------------------------------
// Tasks
// ---------------------------------------------------------------------------

export async function clean() {
  await rm('assets', { recursive: true, force: true });
}

/**
 * Sass -> PostCSS -> assets/css. Autoprefixer always runs; cssnano only in
 * production, so development output stays readable in devtools.
 */
export function styles() {
  const plugins = [autoprefixer()];

  if (isProd) {
    plugins.push(cssnano({ preset: 'default' }));
  }

  return src(paths.styles.entries, { sourcemaps: !isProd })
    .pipe(plumber({ errorHandler: onError }))
    .pipe(sass.sync().on('error', sass.logError))
    .pipe(postcss(plugins))
    .pipe(dest(paths.styles.dest, { sourcemaps: isProd ? false : '.' }))
    .pipe(browserSync.stream({ match: '**/*.css' }));
}

export async function scripts() {
  await esbuild.build({
    entryPoints: paths.scripts.entries,
    outdir: paths.scripts.dest,
    bundle: true,
    format: 'iife',
    target: ['es2020'],
    minify: isProd,
    sourcemap: !isProd,
    drop: isProd ? ['console', 'debugger'] : [],
    logLevel: 'warning',
  });
}

/**
 * Re-encodes rasters with Sharp and cleans vectors with SVGO. Each raster
 * also gets a WebP sibling pushed into the same stream, so assets/images
 * ends up with both `photo.jpg` and `photo.webp`.
 */
export function images() {
  return src(paths.images.src, { encoding: false })
    .pipe(plumber({ errorHandler: onError }))
    .pipe(
      transform(async (file, push) => {
        const ext = path.extname(file.path).toLowerCase();

        if (ext === '.svg') {
          // preset-default keeps viewBox as of SVGO 4, so the SVG still
          // scales in CSS without an explicit override.
          const { data } = optimizeSvg(file.contents.toString(), {
            multipass: true,
            plugins: ['preset-default'],
          });
          file.contents = Buffer.from(data);
          return;
        }

        if (ext === '.gif') {
          return; // Sharp would drop the animation; pass it through untouched.
        }

        const image = sharp(file.contents);

        if (ext === '.jpg' || ext === '.jpeg') {
          file.contents = await image.jpeg({ quality: 80, mozjpeg: true }).toBuffer();
        } else if (ext === '.png') {
          file.contents = await image.png({ compressionLevel: 9, palette: true }).toBuffer();
        } else if (ext === '.webp') {
          file.contents = await image.webp({ quality: 80 }).toBuffer();
        } else if (ext === '.avif') {
          file.contents = await image.avif({ quality: 55 }).toBuffer();
        }

        if (ext === '.jpg' || ext === '.jpeg' || ext === '.png') {
          const webp = file.clone({ contents: false });
          webp.contents = await sharp(file.contents).webp({ quality: 80 }).toBuffer();
          webp.extname = '.webp';
          push(webp);
        }
      }),
    )
    .pipe(dest(paths.images.dest));
}

/**
 * Builds an installable theme zip whose top-level folder is the theme slug,
 * which is what WordPress expects from Appearance -> Add New -> Upload.
 */
export function packageTheme() {
  // Directory entries are dropped rather than renamed: the zip infers folders
  // from the file paths, and a stray bare directory would otherwise land at
  // the archive root, outside the theme folder.
  const nestUnderSlug = new Transform({
    objectMode: true,
    transform(file, _encoding, callback) {
      if (file.isDirectory()) {
        callback();
        return;
      }

      file.path = path.join(file.base, THEME_SLUG, path.relative(file.base, file.path));
      callback(null, file);
    },
  });

  return src(paths.package, { base: '.', encoding: false, dot: true })
    .pipe(nestUnderSlug)
    .pipe(zip(`${THEME_SLUG}.zip`))
    .pipe(dest('.'));
}

/**
 * Proxies the local WordPress install. CSS is injected without a reload;
 * PHP and JS changes trigger a full reload.
 */
export function serve() {
  browserSync.init({
    proxy: WP_DEV_URL,
    port: 3000,
    open: false,
    notify: false,
    ghostMode: false,
  });

  const reload = (done) => {
    browserSync.reload();
    done();
  };

  watch(paths.styles.watch, styles);
  watch(paths.scripts.watch, series(scripts, reload));
  watch(paths.images.src, series(images, reload));
  watch(paths.php, reload);
}

export const build = series(clean, parallel(styles, scripts, images));

// `package` is a reserved word in module scope, so the composed task that
// builds and then zips is exported as `bundle`.
export const bundle = series(build, packageTheme);

export default series(build, serve);
