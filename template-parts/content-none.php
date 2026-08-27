<?php
/**
 * Shown when a query returns nothing.
 *
 * @package Gulpress
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="no-results">
	<header class="page-header">
		<h2 class="page-header__title"><?php esc_html_e( 'Nothing found', 'gulpress' ); ?></h2>
	</header>

	<?php if ( is_search() ) : ?>
		<p><?php esc_html_e( 'No results matched that search. Try different or fewer keywords.', 'gulpress' ); ?></p>
		<?php get_search_form(); ?>
	<?php elseif ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
		<p>
			<?php
			printf(
				/* translators: %s: link to the new post screen. */
				wp_kses_post( __( 'Ready to publish? <a href="%s">Write your first post</a>.', 'gulpress' ) ),
				esc_url( admin_url( 'post-new.php' ) )
			);
			?>
		</p>
	<?php else : ?>
		<p><?php esc_html_e( 'There is nothing here yet. Try a search.', 'gulpress' ); ?></p>
		<?php get_search_form(); ?>
	<?php endif; ?>
</section>
