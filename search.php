<?php
/**
 * Search results template.
 *
 * @package Gulpress
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container layout">
	<div class="layout__content">
		<header class="page-header">
			<h1 class="page-header__title">
				<?php
				printf(
					/* translators: %s: search query. */
					esc_html__( 'Search results for %s', 'gulpress' ),
					'<span class="page-header__query">' . esc_html( get_search_query() ) . '</span>'
				);
				?>
			</h1>
		</header>

		<?php if ( have_posts() ) : ?>

			<div class="entry-list">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'search' );
				endwhile;
				?>
			</div>

			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 1,
					'prev_text' => esc_html__( 'Newer', 'gulpress' ),
					'next_text' => esc_html__( 'Older', 'gulpress' ),
				)
			);
			?>

		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>

	<?php get_sidebar(); ?>
</div>

<?php
get_footer();
