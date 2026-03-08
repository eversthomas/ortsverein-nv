<?php
/**
 * Template-Hilfsfunktionen – ortsverein_nv
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gibt die Home-URL für Breadcrumb/Links zurück (escaped).
 *
 * @return string
 */
function ortsverein_nv_get_home_url() {
	return esc_url( home_url( '/' ) );
}

/**
 * Prüft, ob die aktuelle Seite die Startseite ist.
 *
 * @return bool
 */
function ortsverein_nv_is_front_page() {
	return is_front_page();
}
