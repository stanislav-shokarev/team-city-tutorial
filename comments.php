<?php
/**
 * Comment list and form.
 *
 * @package Gulpress
 */

defined( 'ABSPATH' ) || exit;

// Bail on password-protected posts so comments are not leaked.
if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments__title">
			<?php
			$gulpress_count = (int) get_comments_number();

			printf(
				/* translators: %s: comment count. */
				esc_html( _n( '%s comment', '%s comments', $gulpress_count, 'gulpress' ) ),
				esc_html( number_format_i18n( $gulpress_count ) )
			);
			?>
		</h2>

		<ol class="comments__list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation(
			array(
				'prev_text' => esc_html__( 'Older comments', 'gulpress' ),
				'next_text' => esc_html__( 'Newer comments', 'gulpress' ),
			)
		);
		?>

		<?php if ( ! comments_open() ) : ?>
			<p class="comments__closed"><?php esc_html_e( 'Comments are closed.', 'gulpress' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'class_submit'  => 'button button--primary',
			'title_reply'   => esc_html__( 'Leave a comment', 'gulpress' ),
			'label_submit'  => esc_html__( 'Post comment', 'gulpress' ),
			'comment_field' => sprintf(
				'<p class="comment-form-comment"><label for="comment">%1$s</label><textarea id="comment" name="comment" cols="45" rows="6" required></textarea></p>',
				esc_html__( 'Comment', 'gulpress' )
			),
		)
	);
	?>
</section>
