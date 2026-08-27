<?php
/**
 * Full post content on the singular view.
 *
 * @package Gulpress
 */

defined( 'ABSPATH' ) || exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--single' ); ?>>
	<header class="entry__header">
		<?php the_title( '<h1 class="entry__title">', '</h1>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry__meta">
				<?php
				gulpress_posted_on();
				gulpress_posted_by();
				?>
			</div>
		<?php endif; ?>
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

	<footer class="entry__footer">
		<?php gulpress_entry_terms(); ?>
	</footer>
</article>
