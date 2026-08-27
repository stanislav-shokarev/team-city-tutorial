<?php
/**
 * Static page template.
 *
 * @package Gulpress
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container layout layout--full">
	<div class="layout__content">
		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile;
		?>
	</div>
</div>

<?php
get_footer();
