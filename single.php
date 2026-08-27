<?php
/**
 * Single post template.
 *
 * @package Gulpress
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container layout">
	<div class="layout__content">
		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'single' );

			the_post_navigation(
				array(
					'prev_text' => '<span class="post-nav__label">' . esc_html__( 'Previous', 'gulpress' ) . '</span><span class="post-nav__title">%title</span>',
					'next_text' => '<span class="post-nav__label">' . esc_html__( 'Next', 'gulpress' ) . '</span><span class="post-nav__title">%title</span>',
				)
			);

			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile;
		?>
	</div>

	<?php get_sidebar(); ?>
</div>

<?php
get_footer();
