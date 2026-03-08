<?php
/**
 * SEO-Basis – ortsverein_nv
 * Saubere Title-Struktur, Meta-Description-Hook, Canonical-Vorbereitung.
 * Keine Dummy-Logik; Theme vorbereitet für spätere Erweiterung.
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Title-Tag-Teile filtern (WP nutzt add_theme_support( 'title-tag' )).
 * Ermöglicht spätere Anpassung von Trennzeichen oder Reihenfolge.
 *
 * @param array $title_parts Parts von wp_get_document_title().
 * @return array
 */
function ortsverein_nv_document_title_parts( $title_parts ) {
	return $title_parts;
}
add_filter( 'document_title_parts', 'ortsverein_nv_document_title_parts', 10 );

/**
 * Trennzeichen für Title (Standard: »).
 *
 * @param string $sep Separator.
 * @return string
 */
function ortsverein_nv_document_title_separator( $sep ) {
	return ' » ';
}
add_filter( 'document_title_separator', 'ortsverein_nv_document_title_separator', 10 );

/**
 * Meta-Description ausgeben, wenn von Filter gesetzt.
 * Nichts ausgeben, wenn kein Inhalt – keine Platzhalter-Logik.
 * Später: Plugin oder Child-Theme kann ortsverein_nv_meta_description befüllen.
 */
function ortsverein_nv_meta_description() {
	$description = apply_filters( 'ortsverein_nv_meta_description', '' );
	if ( is_string( $description ) && $description !== '' ) {
		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $description ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'ortsverein_nv_meta_description', 2 );

/**
 * Canonical: WordPress liefert keinen Standard-Canonical im Head.
 * Theme gibt keinen aus, um Doppelung mit SEO-Plugins zu vermeiden.
 * Bei Bedarf kann hier ein Hook ergänzt werden, der nur ohne aktives SEO-Plugin feuert.
 */
