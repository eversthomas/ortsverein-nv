<?php
/**
 * Footer – ortsverein_nv
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ortsverein_nv_email       = function_exists( 'ortsverein_nv_get_option' ) ? trim( (string) ortsverein_nv_get_option( 'contact_email', '' ) ) : '';
$ortsverein_nv_phone       = function_exists( 'ortsverein_nv_get_option' ) ? trim( (string) ortsverein_nv_get_option( 'contact_phone', '' ) ) : '';
$ortsverein_nv_phone_href  = function_exists( 'ortsverein_nv_get_phone_tel_href' ) ? ortsverein_nv_get_phone_tel_href( $ortsverein_nv_phone ) : '';
$ortsverein_nv_website     = function_exists( 'ortsverein_nv_get_option' ) ? trim( (string) ortsverein_nv_get_option( 'social_website', '' ) ) : '';
$ortsverein_nv_has_contact = ( '' !== $ortsverein_nv_email || '' !== $ortsverein_nv_phone || '' !== $ortsverein_nv_website );
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
		<?php if ( $ortsverein_nv_has_contact ) : ?>
			<div class="footer-contact">
				<?php if ( '' !== $ortsverein_nv_email ) : ?>
					<a href="<?php echo esc_url( 'mailto:' . $ortsverein_nv_email ); ?>"><?php echo esc_html( $ortsverein_nv_email ); ?></a>
				<?php endif; ?>
				<?php if ( '' !== $ortsverein_nv_phone && '' !== $ortsverein_nv_phone_href ) : ?>
					<a href="<?php echo esc_url( 'tel:' . $ortsverein_nv_phone_href ); ?>"><?php echo esc_html( $ortsverein_nv_phone ); ?></a>
				<?php endif; ?>
				<?php if ( '' !== $ortsverein_nv_website ) : ?>
					<a href="<?php echo esc_url( $ortsverein_nv_website ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Website', 'ortsverein-nv' ); ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<div class="footer-links">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'footer-links-list',
					'fallback_cb'    => false,
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
