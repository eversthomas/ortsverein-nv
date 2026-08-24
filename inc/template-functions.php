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

/**
 * Holt eine Seiten-ID aus den Theme-Options mit optionalem Slug-Fallback.
 *
 * @param string $option_key   Schlüssel in ortsverein_nv_options (z. B. page_calendar).
 * @param string $fallback_slug Optionaler Slug für Fallback via get_page_by_path().
 * @return int Page-ID oder 0.
 */
function ortsverein_nv_get_page_id_from_option( $option_key, $fallback_slug = '' ) {
	$page_id = 0;

	if ( function_exists( 'ortsverein_nv_get_option' ) ) {
		$opt = (int) ortsverein_nv_get_option( $option_key, 0 );
		if ( $opt > 0 && get_post_status( $opt ) ) {
			$page_id = $opt;
		}
	}

	if ( 0 === $page_id && $fallback_slug !== '' ) {
		$page = get_page_by_path( $fallback_slug );
		if ( $page instanceof WP_Post ) {
			$page_id = (int) $page->ID;
		}
	}

	return $page_id;
}

/**
 * Holt eine Seiten-URL aus den Theme-Options mit optionalem Slug-Fallback.
 *
 * @param string $option_key   Schlüssel in ortsverein_nv_options.
 * @param string $fallback_slug Optionaler Slug für Fallback.
 * @return string URL oder leerer String.
 */
function ortsverein_nv_get_page_url_from_option( $option_key, $fallback_slug = '' ) {
	$page_id = ortsverein_nv_get_page_id_from_option( $option_key, $fallback_slug );
	return $page_id > 0 ? get_permalink( $page_id ) : '';
}

/**
 * Adresszeilen des Ortsvereins aus den Theme-Optionen.
 * Leer, wenn Straße, PLZ und Ort nicht gepflegt sind.
 *
 * @return string[] Nicht-leere Zeilen, z. B. ["Musterstraße 1", "12345 Musterstadt"].
 */
function ortsverein_nv_get_org_address_lines() {
	if ( ! function_exists( 'ortsverein_nv_get_option' ) ) {
		return array();
	}

	$street = trim( (string) ortsverein_nv_get_option( 'org_street', '' ) );
	$zip    = trim( (string) ortsverein_nv_get_option( 'org_zip', '' ) );
	$city   = trim( (string) ortsverein_nv_get_option( 'org_city', '' ) );

	$lines = array();
	if ( '' !== $street ) {
		$lines[] = $street;
	}

	$zip_city = trim( $zip . ' ' . $city );
	if ( '' !== $zip_city ) {
		$lines[] = $zip_city;
	}

	return $lines;
}

/**
 * Bereitet eine Telefonnummer für tel:-Links vor (Ziffern und optionales +).
 *
 * @param string $phone Öffentlich angezeigte Nummer.
 * @return string
 */
function ortsverein_nv_get_phone_tel_href( $phone ) {
	$phone = trim( (string) $phone );
	if ( '' === $phone ) {
		return '';
	}

	$href = preg_replace( '/[^\d+]/', '', $phone );
	return is_string( $href ) ? $href : '';
}
