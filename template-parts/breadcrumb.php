<?php
/**
 * Template Part: Breadcrumb
 *
 * @package Ortsverein_NV
 *
 * @param string $current_title Optional. Aktueller Seitentitel (letzter Krümel).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ortsverein_nv_home_url = ortsverein_nv_get_home_url();
$ortsverein_nv_current  = isset( $args['current_title'] ) ? $args['current_title'] : get_the_title();
?>
<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'ortsverein-nv' ); ?>">
	<a href="<?php echo esc_url( $ortsverein_nv_home_url ); ?>"><?php esc_html_e( 'Startseite', 'ortsverein-nv' ); ?></a>
	<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
	<span><?php echo esc_html( $ortsverein_nv_current ); ?></span>
</nav>
