# Gulpress

A classic WordPress theme with a Gulp 5 build pipeline — Sass, PostCSS, esbuild
and image optimisation. Every build task is about fifteen lines of plain
JavaScript, so the pipeline stays readable and easy to change.

- **PHP 8.0+**, **WordPress 6.6+**, **Node 20+** (WP 6.6 is the floor for
  `theme.json` version 3)
- No runtime npm dependencies — the build produces plain CSS, JS and images
- Passes `phpcs --standard=WordPress` with zero errors and zero warnings

## Quick start

Clone into your WordPress installation's theme directory, then build:

```bash
cd wp-content/themes
git clone https://github.com/your-username/gulpress.git
cd gulpress

npm install
npm run build
```

Activate **Gulpress** under *Appearance → Themes*.

> The compiled `assets/` directory is not committed. Until you run a build, the
> theme renders unstyled and shows an admin notice saying so.

## Commands

| Command | What it does |
| --- | --- |
| `npm start` | Builds, then watches and serves through BrowserSync on `:3000` |
| `npm run build` | Production build — minified, no sourcemaps |
| `npm run package` | Production build plus an installable `gulpress.zip` |
| `npm run clean` | Removes `assets/` |

### Pointing the dev server at your site

BrowserSync **proxies** your local WordPress rather than serving files, so it
needs to know where that site is. The default is `http://localhost:8000`:

```bash
WP_DEV_URL=http://mysite.test npm start
```

CSS changes are injected without a reload; PHP, JS and image changes trigger a
full reload.

## The pipeline

| Task | Input | Output |
| --- | --- | --- |
| `clean` | — | Empties `assets/` using `node:fs`, no extra dependency |
| `styles` | `src/scss/{main,editor}.scss` | Sass → Autoprefixer → cssnano → `assets/css/` |
| `scripts` | `src/js/main.js` | esbuild bundle → `assets/js/` |
| `images` | `src/images/**` | Sharp / SVGO → `assets/images/`, plus a WebP sibling per raster |
| `packageTheme` | the theme files | `gulpress.zip`, ready for *Appearance → Add New → Upload* |

`build` runs `clean`, then the rest in parallel. Production mode is switched on
by `NODE_ENV=production`, which is what `npm run build` sets.

## Layout

```
gulpress/
├── style.css              WordPress theme header only — not the stylesheet
├── functions.php          Loads inc/, defines constants
├── theme.json             Block editor palette, typography, layout
├── inc/
│   ├── setup.php          Theme supports, menus, widget areas
│   ├── enqueue.php        Asset loading with mtime cache-busting
│   └── template-tags.php  Small output helpers
├── template-parts/        content.php, content-single.php, …
├── src/                   Build sources (scss, js, images)
└── assets/                Build output — gitignored
```

`style.css` exists because WordPress requires it to detect the theme. The real
stylesheet is built to `assets/css/main.css` and enqueued from
`inc/enqueue.php`; do not add rules to `style.css`.

### Styles

`src/scss/main.scss` is the front-end entry point. Partials load the abstracts
they use via `@use`, so there are no hidden globals. `src/scss/editor.scss` is a
deliberately smaller entry point registered with `add_editor_style()` — it
carries typography and WordPress core classes but not the site chrome.

Colours are CSS custom properties defined in `src/scss/base/_theme.scss`, with a
dark set under `prefers-color-scheme: dark`. The same palette is mirrored in
`theme.json` so the block editor and the front end agree.

## Renaming the theme

The slug appears in four places. To rename `gulpress` to `mytheme`:

1. Rename the directory to `mytheme/`
2. `style.css` — the `Theme Name` and `Text Domain` headers
3. `gulpfile.js` — the `THEME_SLUG` constant
4. Prefixes throughout the PHP: `gulpress_` functions, `GULPRESS_` constants,
   and the `'gulpress'` text domain

## Security notes

- **Output is escaped at the point of output** — `esc_html()`, `esc_attr()`,
  `esc_url()` and `wp_kses_post()`, never trusting a caller to have done it.
- **Every PHP file starts with `defined( 'ABSPATH' ) || exit;`** so nothing is
  executable when requested directly.
- **The WordPress version is removed** from the document head and feeds. This is
  not a security boundary on its own, just one less thing advertised to
  opportunistic scanners.
- **CI runs `php -l`, `phpcs --standard=WordPress` and `npm audit`** on every
  push and pull request.

### Known advisory

`npm audit` reports a high-severity DoS advisory in `immutable@3.8.4`, pulled in
by `browser-sync`. It is left in place deliberately:

- `browser-sync` pins `immutable@^3.8.1` and **breaks on 5.x** — an override to
  the patched version makes the dev server fail with `server.get is not a
  function`.
- It is a **development-only dependency**. Nothing from `browser-sync` reaches
  `assets/`, the theme zip, or a production site.

CI therefore audits with `--omit=dev`. If you do not use the dev server, you can
remove `browser-sync` and the `serve` task entirely.

## License

GPL-2.0-or-later. See [LICENSE](LICENSE).
