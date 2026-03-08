<?php
/**
 * Footer – ortsverein_nv
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ortsverein_nv_home_url = ortsverein_nv_get_home_url();
?>
<footer id="site-footer" role="contentinfo">
	<div class="footer-inner">
		<div class="footer-brand">
			<svg width="32" height="32" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
				<rect width="44" height="44" rx="10" fill="#e2001a"/>
				<path d="M22 10 C18 10 13 14 13 19.5 C13 26 22 34 22 34 C22 34 31 26 31 19.5 C31 14 26 10 22 10Z" fill="white"/>
				<path d="M17 19 L22 14 L27 19 L25 19 L25 26 L19 26 L19 19 Z" fill="#e2001a"/>
			</svg>
			<span class="awo"><?php esc_html_e( 'AWO OV Neukirchen-Vluyn', 'ortsverein-nv' ); ?></span>
		</div>
		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<div class="footer-links">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'footer-links-list',
					'depth'          => 1,
				) );
				?>
			</div>
		<?php endif; ?>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
