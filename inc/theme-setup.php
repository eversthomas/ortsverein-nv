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
		'primary' => __( 'Hauptmenü', 'ortsverein-nv' ),
		'service' => __( 'Schnellzugriff', 'ortsverein-nv' ),
		'footer'  => __( 'Footer-Menü', 'ortsverein-nv' ),
	) );
}

add_action( 'after_setup_theme', 'ortsverein_nv_setup_theme' );

/**
 * Widget-Bereiche registrieren.
 * Redakteure wählen im Seiten-Editor pro Seite aus, welche dieser Widgets dort
 * erscheinen (siehe inc/meta-boxes.php, ortsverein_nv_get_selected_sidebar_widgets()).
 */
function ortsverein_nv_register_sidebars() {
	register_sidebar( array(
		'name'          => __( 'Seiten-Sidebar', 'ortsverein-nv' ),
		'id'            => 'page-sidebar',
		'description'   => __( 'Widgets für Seiten. Im Seiten-Editor wählt jede Seite einzeln aus, welche dieser Widgets dort erscheinen.', 'ortsverein-nv' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}

add_action( 'widgets_init', 'ortsverein_nv_register_sidebars' );
