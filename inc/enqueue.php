<?php
/**
 * Asset loading.
 *
 * Everything enqueued here is produced by Gulp into assets/. Versions come
 * from the file's mtime so a rebuilt asset busts the browser cache without
 * anyone having to bump a number by hand.
 *
 * @package Gulpress
 */

defined( 'ABSPATH' ) || exit;

/**
 * Build a cache-busting version string for a theme-relative asset.
 *
 * @param string $relative_path Path relative to the theme root, e.g. 'assets/css/main.css'.
 * @return string File mtime when readable, otherwise the theme version.
 */
function gulpress_asset_version( string $relative_path ): string {
	$absolute = GULPRESS_DIR . '/' . ltrim( $relative_path, '/' );

	if ( ! is_readable( $absolute ) ) {
		return GULPRESS_VERSION;
	}

	return (string) filemtime( $absolute );
}

/**
 * Enqueue front-end styles and scripts.
 */
function gulpress_enqueue_assets(): void {
	// style.css carries the theme header only; register it so child themes
	// and plugins have the usual 'gulpress-style' handle to depend on.
	wp_enqueue_style(
		'gulpress-style',
		get_stylesheet_uri(),
		array(),
		gulpress_asset_version( 'style.css' )
	);

	wp_enqueue_style(
		'gulpress-main',
		GULPRESS_URI . '/assets/css/main.css',
		array( 'gulpress-style' ),
		gulpress_asset_version( 'assets/css/main.css' )
	);

	wp_enqueue_script(
		'gulpress-main',
		GULPRESS_URI . '/assets/js/main.js',
		array(),
		gulpress_asset_version( 'assets/js/main.js' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'gulpress_enqueue_assets' );

/**
 * Warn an administrator when the theme has not been built yet.
 *
 * Without this the theme silently renders unstyled, which is a confusing
 * first run for anyone who cloned the repository and skipped `npm run build`.
 */
function gulpress_build_notice(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( is_readable( GULPRESS_DIR . '/assets/css/main.css' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-warning"><p>%s</p></div>',
		esc_html__(
			'Gulpress: compiled assets are missing. Run "npm install && npm run build" in the theme directory.',
			'gulpress'
		)
	);
}
add_action( 'admin_notices', 'gulpress_build_notice' );
