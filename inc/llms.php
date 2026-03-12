<?php
/**
 * llms.txt – KI-/LLM-Hinweise – ortsverein_nv
 * Bietet unter /llms.txt eine einfache, maschinenlesbare Beschreibung
 * der Website-Struktur und wichtiger Ressourcentypen.
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Rewrite-Regel für /llms.txt registrieren.
 */
function ortsverein_nv_llms_add_rewrite_rule() {
	add_rewrite_rule( '^llms\.txt$', 'index.php?ortsverein_nv_llms=1', 'top' );
}
add_action( 'init', 'ortsverein_nv_llms_add_rewrite_rule' );

/**
 * Query-Var für llms.txt erlauben.
 *
 * @param array $vars Query-Variablen.
 * @return array
 */
function ortsverein_nv_llms_query_vars( $vars ) {
	$vars[] = 'ortsverein_nv_llms';
	return $vars;
}
add_filter( 'query_vars', 'ortsverein_nv_llms_query_vars' );

/**
 * llms.txt ausgeben, wenn /llms.txt abgefragt wird.
 */
function ortsverein_nv_maybe_output_llms() {
	if ( is_admin() ) {
		return;
	}

	$is_llms = (int) get_query_var( 'ortsverein_nv_llms' ) === 1;

	// Fallback: Erkenne /llms.txt auch dann, wenn Rewrite-Regeln noch nicht geflusht sind.
	if ( ! $is_llms ) {
		$request_path = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';
		$llms_path    = wp_parse_url( home_url( '/llms.txt' ), PHP_URL_PATH );

		if ( $request_path && $llms_path && rtrim( $request_path, '/' ) === rtrim( $llms_path, '/' ) ) {
			$is_llms = true;
		}
	}

	if ( ! $is_llms ) {
		return;
	}

	// Text-Header setzen.
	nocache_headers();
	header( 'Content-Type: text/plain; charset=' . esc_attr( get_option( 'blog_charset' ) ) );

	$home_url = home_url( '/' );

	// Grunddaten aus Theme-Options lesen.
	$org_name    = function_exists( 'ortsverein_nv_get_option' ) ? ortsverein_nv_get_option( 'org_name', get_bloginfo( 'name', 'display' ) ) : get_bloginfo( 'name', 'display' );
	$org_city    = function_exists( 'ortsverein_nv_get_option' ) ? ortsverein_nv_get_option( 'org_city', '' ) : '';
	$org_tagline = function_exists( 'ortsverein_nv_get_option' ) ? ortsverein_nv_get_option( 'org_tagline', get_bloginfo( 'description', 'display' ) ) : get_bloginfo( 'description', 'display' );

	echo "llms-spec: 1.0\n";
	echo "site-url: " . esc_url( $home_url ) . "\n";
	echo "site-name: " . wp_strip_all_tags( (string) $org_name ) . "\n";
	if ( $org_city ) {
		echo "site-city: " . wp_strip_all_tags( (string) $org_city ) . "\n";
	}
	if ( $org_tagline ) {
		echo "site-description: " . wp_strip_all_tags( (string) $org_tagline ) . "\n";
	}
	echo "\n";

	// Wichtige Ressourcentypen grob markieren.
	echo "# Content types\n";
	echo "resource: page type=static description=\"Normale Vereinsseiten (Inhalte im Classic Editor)\"\n";
	echo "resource: event type=ics-calendar description=\"Veranstaltungen aus ICS-Kalender, dargestellt auf Start- und Kalenderseite\"\n";
	echo "\n";

	// Zentrale Seiten (sofern konfiguriert) als Knoten referenzieren.
	if ( function_exists( 'ortsverein_nv_get_page_id_from_option' ) ) {
		$map = array(
			'home'        => array( 'option' => 'page_on_front',      'label' => 'Startseite' ),
			'calendar'    => array( 'option' => 'page_calendar',      'label' => 'Kalender' ),
			'membership'  => array( 'option' => 'page_membership',    'label' => 'Mitgliedschaft' ),
			'contact'     => array( 'option' => 'page_contact',       'label' => 'Kontakt' ),
			'begegnung'   => array( 'option' => 'page_begegnung',     'label' => 'Begegnungsstätte' ),
			'downloads'   => array( 'option' => 'page_downloads',     'label' => 'Downloads' ),
			'intern'      => array( 'option' => 'page_intern',        'label' => 'Interner Bereich' ),
		);

		echo "# Key pages\n";
		foreach ( $map as $key => $meta ) {
			if ( 'home' === $key ) {
				$page_id = (int) get_option( 'page_on_front' );
			} else {
				$page_id = ortsverein_nv_get_page_id_from_option( $meta['option'], '' );
			}
			if ( $page_id > 0 ) {
				$url = get_permalink( $page_id );
				if ( $url ) {
					echo 'page: ' . $key . ' url="' . esc_url( $url ) . '" label="' . wp_strip_all_tags( (string) $meta['label'] ) . "\"\n";
				}
			}
		}
		echo "\n";
	}

	// Events-Hinweis, falls ICS konfiguriert.
	if ( function_exists( 'ortsverein_nv_get_ics_events' ) ) {
		$ics_url = get_theme_mod( 'ortsverein_nv_ics_url', '' );
		if ( ! $ics_url && defined( 'ORTSVEREIN_NV_ICS_DEFAULT_URL' ) ) {
			$ics_url = ORTSVEREIN_NV_ICS_DEFAULT_URL;
		}
		if ( $ics_url ) {
			echo "# Event source\n";
			echo "ics-feed: " . esc_url_raw( $ics_url ) . "\n";
		}
	}

	// Hook für Erweiterungen (z. B. Child-Theme/Plugin).
	do_action( 'ortsverein_nv_llms_txt' );

	exit;
}
add_action( 'template_redirect', 'ortsverein_nv_maybe_output_llms' );

