<?php
/**
 * Search form.
 *
 * The unique id keeps the label association valid when more than one form
 * appears on the same page (for example a 404 body plus a sidebar widget).
 *
 * @package Gulpress
 */

defined( 'ABSPATH' ) || exit;

$gulpress_field_id = 'search-field-' . wp_unique_id();
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $gulpress_field_id ); ?>">
		<?php esc_html_e( 'Search for:', 'gulpress' ); ?>
	</label>

	<input
		type="search"
		id="<?php echo esc_attr( $gulpress_field_id ); ?>"
		class="search-form__field"
		name="s"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="<?php esc_attr_e( 'Search&hellip;', 'gulpress' ); ?>"
		required
	>

	<button type="submit" class="button button--primary search-form__submit">
		<?php esc_html_e( 'Search', 'gulpress' ); ?>
	</button>
</form>
