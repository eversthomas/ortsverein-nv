<?php
/**
 * WordPress Cleanup – ortsverein_nv
 * Gutenberg aus, Classic Editor, Kommentare, Emojis, Embeds, etc.
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gutenberg/Block-Editor deaktivieren, Classic Editor nutzen.
 */
function ortsverein_nv_disable_gutenberg() {
	add_filter( 'use_block_editor_for_post', '__return_false', 10 );
	add_filter( 'use_block_editor_for_post_type', '__return_false', 10 );
}

add_action( 'init', 'ortsverein_nv_disable_gutenberg', 9 );

/**
 * Classic Editor erzwingen (falls Plugin aktiv).
 */
add_filter( 'classic_editor_enabled', '__return_true' );

/**
 * Kommentare deaktivieren (keine Unterstützung im Theme).
 */
function ortsverein_nv_disable_comments() {
	remove_post_type_support( 'post', 'comments' );
	remove_post_type_support( 'page', 'comments' );
}

add_action( 'admin_init', 'ortsverein_nv_disable_comments' );

/**
 * Unnötige Dashboard-Widgets entfernen.
 */
function ortsverein_nv_remove_dashboard_widgets() {
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
}

add_action( 'wp_dashboard_setup', 'ortsverein_nv_remove_dashboard_widgets', 999 );

/**
 * Theme- und Plugin-Editor: Kann in wp-config.php mit
 * define( 'DISALLOW_FILE_EDIT', true ); deaktiviert werden.
 */

/**
 * Emojis deaktivieren.
 */
function ortsverein_nv_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'ortsverein_nv_disable_emojis_tinymce' );
}

function ortsverein_nv_disable_emojis_tinymce( $plugins ) {
	return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
}

add_action( 'init', 'ortsverein_nv_disable_emojis' );

/**
 * Embeds deaktivieren (oEmbed, etc.).
 */
function ortsverein_nv_disable_embeds() {
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
}

add_action( 'init', 'ortsverein_nv_disable_embeds', 9999 );

/**
 * REST-API nur für eingeloggte Administratoren zulassen.
 * Reduziert Angriffsfläche und verhindert unerwünschte Datenabfragen von außen.
 */
function ortsverein_nv_restrict_rest_api( $result ) {
	if ( ! is_wp_error( $result ) && ! current_user_can( 'manage_options' ) ) {
		return new WP_Error( 'rest_not_logged_in', __( 'REST-API nur für Administratoren.', 'ortsverein-nv' ), array( 'status' => 401 ) );
	}
	return $result;
}

add_filter( 'rest_authentication_errors', 'ortsverein_nv_restrict_rest_api', 99 );

/**
 * Global Styles an der Quelle abschalten (verhindert global-styles-inline-css im Frontend).
 * wp_dequeue_style('global-styles') reicht in einigen WP-Versionen nicht; die Inline-Ausgabe
 * bleibt. remove_action verhindert, dass wp_enqueue_global_styles überhaupt läuft.
 */
function ortsverein_nv_remove_global_styles() {
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
	remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
}
add_action( 'init', 'ortsverein_nv_remove_global_styles', 100 );

/**
 * Frontend: Block-Bibliothek, Classic-Theme-Styles, Dashicons und WP-Bild-Auto-Sizes abmelden.
 * Theme nutzt eigenes Vanilla-CSS; Classic-Editor-Inhalte werden über Theme-CSS gestylt.
 * wp-img-auto-sizes-contain: Core-Inline für img[sizes=auto]; Theme setzt das nicht ein.
 */
function ortsverein_nv_dequeue_frontend_assets() {
	if ( is_admin() ) {
		return;
	}
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
	wp_dequeue_style( 'wc-blocks-style' );
	wp_dequeue_style( 'dashicons' );
	wp_dequeue_style( 'wp-img-auto-sizes-contain' );
}
add_action( 'wp_enqueue_scripts', 'ortsverein_nv_dequeue_frontend_assets', 100 );
