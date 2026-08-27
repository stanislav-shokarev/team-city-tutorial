<?php
/**
 * Gulpress theme bootstrap.
 *
 * Everything here is deliberately thin: this file only defines the theme
 * constants and pulls in the modules under inc/.
 *
 * @package Gulpress
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'GULPRESS_VERSION' ) ) {
	define( 'GULPRESS_VERSION', '1.0.0' );
}

if ( ! defined( 'GULPRESS_DIR' ) ) {
	define( 'GULPRESS_DIR', get_template_directory() );
}

if ( ! defined( 'GULPRESS_URI' ) ) {
	define( 'GULPRESS_URI', get_template_directory_uri() );
}

require_once GULPRESS_DIR . '/inc/setup.php';
require_once GULPRESS_DIR . '/inc/enqueue.php';
require_once GULPRESS_DIR . '/inc/template-tags.php';
