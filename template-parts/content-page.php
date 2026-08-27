<?php
/**
 * Static page content.
 *
 * @package Gulpress
 */

defined( 'ABSPATH' ) || exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--page' ); ?>>
	<header class="entry__header">
		<?php the_title( '<h1 class="entry__title">', '</h1>' ); ?>
	</header>

	<?php gulpress_post_thumbnail(); ?>

	<div class="entry__content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<nav class="page-links">' . esc_html__( 'Pages:', 'gulpress' ),
				'after'  => '</nav>',
			)
		);
		?>
	</div>

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry__footer">
			<?php
			edit_post_link(
				esc_html__( 'Edit', 'gulpress' ),
				'<span class="entry__edit">',
				'</span>'
			);
			?>
		</footer>
	<?php endif; ?>
</article>
