<?php
/**
 * Small output helpers used by the templates.
 *
 * Every function here escapes on output rather than trusting the caller.
 *
 * @package Gulpress
 */

defined( 'ABSPATH' ) || exit;

/**
 * Print the published / updated date as a <time> element.
 */
function gulpress_posted_on(): void {
	$posted = sprintf(
		/* translators: %s: post date. */
		esc_html__( 'Published %s', 'gulpress' ),
		sprintf(
			'<time class="entry__date" datetime="%1$s">%2$s</time>',
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() )
		)
	);

	echo '<span class="entry__meta-item">' . wp_kses_post( $posted ) . '</span>';
}

/**
 * Print the post author with a link to their archive.
 */
function gulpress_posted_by(): void {
	$author = sprintf(
		/* translators: %s: post author. */
		esc_html__( 'by %s', 'gulpress' ),
		sprintf(
			'<a class="link" href="%1$s">%2$s</a>',
			esc_url( get_author_posts_url( (int) get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		)
	);

	echo '<span class="entry__meta-item">' . wp_kses_post( $author ) . '</span>';
}

/**
 * Print the category and tag lists for the current post.
 */
function gulpress_entry_terms(): void {
	if ( 'post' !== get_post_type() ) {
		return;
	}

	$categories = get_the_category_list( ', ' );

	if ( $categories ) {
		printf(
			'<span class="entry__meta-item">%s</span>',
			wp_kses_post( $categories )
		);
	}

	$tags = get_the_tag_list( '', ', ' );

	if ( $tags && ! is_wp_error( $tags ) ) {
		printf(
			'<span class="entry__meta-item entry__meta-item--tags">%s</span>',
			wp_kses_post( $tags )
		);
	}
}

/**
 * Print the featured image, linked when not on the singular view.
 */
function gulpress_post_thumbnail(): void {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) {
		echo '<figure class="entry__media">';
		the_post_thumbnail( 'large', array( 'loading' => 'eager' ) );
		echo '</figure>';

		return;
	}

	printf(
		'<a class="entry__media" href="%1$s" aria-hidden="true" tabindex="-1">%2$s</a>',
		esc_url( get_permalink() ),
		wp_kses_post(
			get_the_post_thumbnail(
				null,
				'medium_large',
				array( 'loading' => 'lazy' )
			)
		)
	);
}

/**
 * Print the "continue reading" link with the post title for screen readers.
 */
function gulpress_read_more(): void {
	printf(
		'<a class="button entry__more" href="%1$s">%2$s<span class="screen-reader-text">: %3$s</span></a>',
		esc_url( get_permalink() ),
		esc_html__( 'Continue reading', 'gulpress' ),
		esc_html( get_the_title() )
	);
}
