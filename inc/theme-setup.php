<?php
/**
 * Theme Setup – ortsverein_nv
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme-Setup (after_setup_theme).
 */
function ortsverein_nv_setup_theme() {
	load_theme_textdomain( 'ortsverein-nv', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'customize-selective-refresh-widgets' );

	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 120,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	register_nav_menus( array(
		'primary'  => __( 'Hauptmenü', 'ortsverein-nv' ),
		'service'  => __( 'Schnellzugriff (Kontakt, Downloads, Kalender)', 'ortsverein-nv' ),
		'footer'   => __( 'Footer (Impressum, Datenschutz, Barrierefreiheit, Kontakt)', 'ortsverein-nv' ),
	) );
}

add_action( 'after_setup_theme', 'ortsverein_nv_setup_theme' );
