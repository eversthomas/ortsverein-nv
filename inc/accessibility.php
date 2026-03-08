<?php
/**
 * Accessibility – ortsverein_nv
 * Landmarken, Skip-Link, Navigation ARIA, Fokus, reduced-motion.
 * Keine Über-Engineerung; klare, wartbare Anpassungen.
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Body-Klasse für „reduced motion“ (optional, für spätere Nutzung).
 * CSS nutzt bereits prefers-reduced-motion für Animationen.
 *
 * @param array $classes Body classes.
 * @return array
 */
function ortsverein_nv_body_class_accessibility( $classes ) {
	if ( ! is_admin() && isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
		// prefers-reduced-motion wird im CSS per Media Query gehandhabt; keine Klasse nötig.
	}
	return $classes;
}
add_filter( 'body_class', 'ortsverein_nv_body_class_accessibility', 10 );

/**
 * Nav-Menü: aria-current="page" für aktuelle Seite.
 * wp_nav_menu setzt .current-menu-item; ARIA für Screenreader ergänzen.
 *
 * @param array   $atts  Link attributes.
 * @param WP_Post $item  Menu item.
 * @param object  $args  Menu args.
 * @param int     $depth Depth.
 * @return array
 */
function ortsverein_nv_nav_menu_aria_current_attr( $atts, $item, $args, $depth ) {
	if ( in_array( 'current-menu-item', $item->classes, true ) ) {
		$atts['aria-current'] = 'page';
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'ortsverein_nv_nav_menu_aria_current_attr', 10, 4 );
