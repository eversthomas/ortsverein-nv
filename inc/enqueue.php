<?php
/**
 * Enqueue Styles & Scripts – ortsverein_nv
 * Theme-CSS, kritisches Above-the-fold-CSS, JS.
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Kritisches CSS für Above-the-fold (Skip-Link, Header, Container, Hero-Basis).
 * Wird inline im Head ausgegeben, um First Paint zu stabilisieren und Layout Shift zu minimieren.
 */
function ortsverein_nv_critical_css() {
	if ( is_admin() ) {
		return;
	}

	$logo_size = function_exists( 'ortsverein_nv_get_header_logo_size' ) ? ortsverein_nv_get_header_logo_size() : 44;

	$critical = ':root{--header-logo-size:' . (int) $logo_size . 'px;--rot:#e2001a;--weiss:#fff;--schwarz:#1a1a1a;--grau-rand:#e0e0e0;--font-sans:-apple-system,"Segoe UI",Helvetica Neue,Arial,sans-serif;--breite:1140px;--abstand-m:24px;--radius-s:8px;}
.skip-link{position:absolute;top:-100%;left:1rem;background:var(--rot);color:#fff;padding:.6em 1.2em;border-radius:var(--radius-s);font-size:1rem;font-weight:600;z-index:9999;transition:top .2s;}
.skip-link:focus{top:1rem;}
#site-header{position:sticky;top:0;z-index:1000;background:var(--weiss);border-bottom:2px solid var(--grau-rand);}
.header-inner{display:flex;align-items:flex-end;justify-content:space-between;gap:24px;padding:max(14px,calc((var(--header-logo-size,44px) - 44px) / 2 + 14px)) var(--abstand-m);max-width:var(--breite);margin:0 auto;}
.container{width:100%;max-width:var(--breite);margin:0 auto;padding:0 var(--abstand-m);}
#hero{min-height:280px;background:var(--grau-hell,#f2f2f2);}
.hero-inner{display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;max-width:var(--breite);margin:0 auto;padding:48px var(--abstand-m);}
.brand{display:flex;align-items:center;gap:12px;text-decoration:none;color:inherit;}
.brand-icon{height:var(--header-logo-size,44px);width:auto;max-width:none;display:block;flex-shrink:0;}
@media(max-width:900px){.hero-inner{grid-template-columns:1fr;}}
@media(prefers-reduced-motion:reduce){.skip-link{transition:top .01ms;}}';
	echo '<style id="ortsverein-nv-critical-css">' . $critical . '</style>' . "\n";
}
add_action( 'wp_head', 'ortsverein_nv_critical_css', 1 );

/**
 * Logo-/Header-Höhe nach dem Stylesheet ausgeben, damit die Werte theme.css überschreiben.
 */
function ortsverein_nv_header_size_style() {
	if ( is_admin() ) {
		return;
	}

	$css = function_exists( 'ortsverein_nv_get_header_size_css' )
		? ortsverein_nv_get_header_size_css()
		: ':root{--header-logo-size:44px;}';

	echo '<style id="ortsverein-nv-header-size">' . $css . '</style>' . "\n";
}
add_action( 'wp_head', 'ortsverein_nv_header_size_style', 20 );

/**
 * Frontend CSS und JS laden.
 */
function ortsverein_nv_enqueue_assets() {
	$template_dir = get_template_directory();
	$template_uri = get_template_directory_uri();

	$css_path    = $template_dir . '/assets/css/theme.css';
	$css_version = file_exists( $css_path ) ? filemtime( $css_path ) : wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'ortsverein-nv-theme',
		$template_uri . '/assets/css/theme.css',
		array(),
		$css_version
	);

	$header_css = function_exists( 'ortsverein_nv_get_header_size_css' )
		? ortsverein_nv_get_header_size_css()
		: ':root { --header-logo-size: 44px; }';
	wp_add_inline_style( 'ortsverein-nv-theme', $header_css );

	$ortsverein_nv_hero_id = get_theme_mod( 'ortsverein_nv_hero_background', '' );
	if ( $ortsverein_nv_hero_id ) {
		$ortsverein_nv_hero_url = wp_get_attachment_image_url( (int) $ortsverein_nv_hero_id, 'full' );
		if ( $ortsverein_nv_hero_url ) {
			$ortsverein_nv_hero_css = '#hero { background-image: url(' . esc_url( $ortsverein_nv_hero_url ) . '); background-size: cover; background-position: center; }';
			wp_add_inline_style( 'ortsverein-nv-theme', $ortsverein_nv_hero_css );
		}
	}

	$js_path    = $template_dir . '/assets/js/theme.js';
	$js_version = file_exists( $js_path ) ? filemtime( $js_path ) : wp_get_theme()->get( 'Version' );

	wp_enqueue_script(
		'ortsverein-nv-theme',
		$template_uri . '/assets/js/theme.js',
		array(),
		$js_version,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'ortsverein_nv_enqueue_assets' );

/**
 * Script-Tag für das Theme-JS mit defer versehen, um die Render-Blockade zu minimieren.
 *
 * @param string $tag    Der ursprüngliche Script-Tag.
 * @param string $handle Das Script-Handle.
 * @param string $src    Die Script-URL.
 * @return string
 */
function ortsverein_nv_defer_theme_script( $tag, $handle, $src ) {
	if ( 'ortsverein-nv-theme' !== $handle ) {
		return $tag;
	}

	// defer nur ergänzen, wenn nicht bereits gesetzt.
	if ( false === strpos( $tag, ' defer' ) ) {
		$tag = str_replace( '<script ', '<script defer ', $tag );
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'ortsverein_nv_defer_theme_script', 10, 3 );
