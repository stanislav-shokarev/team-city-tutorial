<?php
/**
 * The fallback template — used for the blog index and anything without a
 * more specific template in the hierarchy.
 *
 * @package Gulpress
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container layout">
	<div class="layout__content">
		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header class="page-header">
					<h1 class="page-header__title"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?>

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
