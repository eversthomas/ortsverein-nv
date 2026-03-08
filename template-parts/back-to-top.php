<?php
/**
 * Template Part: Back to top
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ortsverein_nv_home_url = ortsverein_nv_get_home_url();
?>
<a href="<?php echo esc_url( $ortsverein_nv_home_url ); ?>#main" class="back-to-top">
	<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="18 15 12 9 6 15"/></svg>
	<?php esc_html_e( 'Zurück nach oben', 'ortsverein-nv' ); ?>
</a>
