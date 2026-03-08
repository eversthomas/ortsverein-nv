<?php
/**
 * Head-Cleanup – ortsverein_nv
 * Entfernt unnötige Head-Ausgaben (RSD, WLW, Generator, REST-Link etc.).
 * Nur Theme-relevante Steuerung; keine Plugin-Logik.
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RSD-Link entfernen (Really Simple Discovery).
 */
function ortsverein_nv_remove_rsd_link() {
	remove_action( 'wp_head', 'rsd_link' );
}
add_action( 'init', 'ortsverein_nv_remove_rsd_link' );

/**
 * WLW-Manifest-Link entfernen (Windows Live Writer).
 */
function ortsverein_nv_remove_wlw_link() {
	remove_action( 'wp_head', 'wlwmanifest_link' );
}
add_action( 'init', 'ortsverein_nv_remove_wlw_link' );

/**
 * Generator-Meta (WordPress-Version) aus Head entfernen.
 */
function ortsverein_nv_remove_generator() {
	remove_action( 'wp_head', 'wp_generator' );
}
add_action( 'init', 'ortsverein_nv_remove_generator' );

/**
 * Shortlink aus Head entfernen.
 */
function ortsverein_nv_remove_shortlink() {
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
}
add_action( 'init', 'ortsverein_nv_remove_shortlink' );

/**
 * REST-API-Link aus Head entfernen (Theme nutzt keine Frontend-REST für Darstellung).
 * REST bleibt für Admin/Editor nutzbar; nur die Discovery-Link-Zeile im Head entfällt.
 */
function ortsverein_nv_remove_rest_link() {
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
}
add_action( 'init', 'ortsverein_nv_remove_rest_link' );

/* oEmbed/Embeds: Siehe cleanup.php (ortsverein_nv_disable_embeds). */
