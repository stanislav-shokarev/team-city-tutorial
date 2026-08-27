<?php
/**
 * Theme supports, menus and widget areas.
 *
 * @package Gulpress
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme features.
 */
function gulpress_setup(): void {
	load_theme_textdomain( 'gulpress', GULPRESS_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );

	// Let WordPress emit modern, valid markup instead of the legacy variants.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		)
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 64,
			'width'       => 64,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'gulpress' ),
			'footer'  => __( 'Footer Menu', 'gulpress' ),
		)
	);

	// Applies to the block editor; the file is produced by `gulp styles`.
	add_editor_style( 'assets/css/editor.css' );
}
add_action( 'after_setup_theme', 'gulpress_setup' );

/**
 * Set the content width used by oEmbed and wide images.
 */
function gulpress_content_width(): void {
	$GLOBALS['content_width'] = apply_filters( 'gulpress_content_width', 720 );
}
add_action( 'after_setup_theme', 'gulpress_content_width', 0 );

/**
 * Register the sidebar widget area.
 */
function gulpress_widgets_init(): void {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'gulpress' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Widgets shown beside post and archive listings.', 'gulpress' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget__title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'gulpress_widgets_init' );

/**
 * Remove the WordPress version from the document head and feeds.
 *
 * Not a security boundary on its own, but there is no reason to advertise
 * the exact version to opportunistic scanners.
 */
function gulpress_remove_version(): string {
	return '';
}
add_filter( 'the_generator', 'gulpress_remove_version' );
remove_action( 'wp_head', 'wp_generator' );
