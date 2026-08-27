<?php
/**
 * Opening document markup, masthead and primary navigation.
 *
 * @package Gulpress
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="color-scheme" content="light dark">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main">
	<?php esc_html_e( 'Skip to content', 'gulpress' ); ?>
</a>

<header class="site-header">
	<div class="container site-header__inner">
		<div class="brand">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			}

			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="brand__name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="brand__name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;

			$gulpress_description = get_bloginfo( 'description', 'display' );

			if ( $gulpress_description || is_customize_preview() ) :
				?>
				<p class="brand__tagline"><?php echo esc_html( $gulpress_description ); ?></p>
				<?php
			endif;
			?>
		</div>

		<?php if ( has_nav_menu( 'primary' ) ) : ?>
			<button
				class="nav-toggle"
				type="button"
				aria-expanded="false"
				aria-controls="primary-menu"
				data-nav-toggle
			>
				<span class="nav-toggle__bars" aria-hidden="true"></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'gulpress' ); ?></span>
			</button>

			<nav class="nav" aria-label="<?php esc_attr_e( 'Primary', 'gulpress' ); ?>" data-nav>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'menu_class'     => 'nav__list',
						'container'      => false,
						'depth'          => 2,
					)
				);
				?>
			</nav>
		<?php endif; ?>
	</div>
</header>

<main id="main" class="site-main">
