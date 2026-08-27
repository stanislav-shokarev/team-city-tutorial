<?php
/**
 * A single result in the search listing.
 *
 * @package Gulpress
 */

defined( 'ABSPATH' ) || exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--summary' ); ?>>
	<?php gulpress_post_thumbnail(); ?>

	<div class="entry__body">
		<header class="entry__header">
			<?php the_title( '<h2 class="entry__title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

			<div class="entry__meta">
				<?php if ( 'post' === get_post_type() ) : ?>
					<?php gulpress_posted_on(); ?>
				<?php endif; ?>
				<span class="entry__meta-item"><?php echo esc_html( get_post_type() ); ?></span>
			</div>
		</header>

		<div class="entry__excerpt">
			<?php the_excerpt(); ?>
		</div>
	</div>
</article>
