<?php
/**
 * Post summary as shown in listings.
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

			<?php if ( 'post' === get_post_type() ) : ?>
				<div class="entry__meta">
					<?php
					gulpress_posted_on();
					gulpress_posted_by();
					?>
				</div>
			<?php endif; ?>
		</header>

		<div class="entry__excerpt">
			<?php the_excerpt(); ?>
		</div>

		<?php gulpress_read_more(); ?>
	</div>
</article>
