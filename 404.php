<?php
/**
 * Template for the 404 (not found) response.
 *
 * @package Gulpress
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container layout layout--full">
	<div class="layout__content">
		<section class="not-found">
			<header class="page-header">
				<p class="eyebrow"><?php esc_html_e( 'Error 404', 'gulpress' ); ?></p>
				<h1 class="page-header__title"><?php esc_html_e( 'That page is not here.', 'gulpress' ); ?></h1>
			</header>

			<p><?php esc_html_e( 'The link may be out of date, or the page may have moved. Try a search instead.', 'gulpress' ); ?></p>

			<?php get_search_form(); ?>
		</section>
	</div>
</div>

<?php
get_footer();
