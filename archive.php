<?php
/**
 * Archive template — categories, tags, authors, dates and custom taxonomies.
 *
 * @package Gulpress
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container layout">
	<div class="layout__content">
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				the_archive_title( '<h1 class="page-header__title">', '</h1>' );
				the_archive_description( '<div class="page-header__description">', '</div>' );
				?>
			</header>

			<div class="entry-list">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', get_post_type() );
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
