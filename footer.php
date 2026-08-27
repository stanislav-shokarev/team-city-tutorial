<?php
/**
 * Closing document markup and site footer.
 *
 * @package Gulpress
 */

defined( 'ABSPATH' ) || exit;
?>
</main><!-- #main -->

<footer class="site-footer">
	<div class="container site-footer__inner">
		<p class="site-footer__note">
			<?php
			printf(
				/* translators: 1: current year, 2: site name. */
				esc_html__( '&copy; %1$s %2$s', 'gulpress' ),
				esc_html( (string) gmdate( 'Y' ) ),
				esc_html( get_bloginfo( 'name' ) )
			);
			?>
		</p>

		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<nav class="nav nav--footer" aria-label="<?php esc_attr_e( 'Footer', 'gulpress' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'menu_class'     => 'nav__list',
						'container'      => false,
						'depth'          => 1,
					)
				);
				?>
			</nav>
		<?php endif; ?>

		<p class="site-footer__note">
			<?php esc_html_e( 'Built with Gulpress', 'gulpress' ); ?>
		</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
