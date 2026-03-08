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
	$critical = ':root{--rot:#e2001a;--weiss:#fff;--schwarz:#1a1a1a;--grau-rand:#e0e0e0;--font-sans:-apple-system,"Segoe UI",Helvetica Neue,Arial,sans-serif;--breite:1140px;--abstand-m:24px;--radius-s:8px;}
.skip-link{position:absolute;top:-100%;left:1rem;background:var(--rot);color:#fff;padding:.6em 1.2em;border-radius:var(--radius-s);font-size:1rem;font-weight:600;z-index:9999;transition:top .2s;}
.skip-link:focus{top:1rem;}
#site-header{position:sticky;top:0;z-index:1000;background:var(--weiss);border-bottom:2px solid var(--grau-rand);}
.header-inner{display:flex;align-items:flex-end;justify-content:space-between;gap:24px;padding:14px var(--abstand-m);max-width:var(--breite);margin:0 auto;}
.container{width:100%;max-width:var(--breite);margin:0 auto;padding:0 var(--abstand-m);}
#hero{min-height:280px;background:var(--grau-hell,#f2f2f2);}
.hero-inner{display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;max-width:var(--breite);margin:0 auto;padding:48px var(--abstand-m);}
.brand{display:flex;align-items:center;gap:12px;text-decoration:none;color:inherit;}
.brand img{width:120px;height:auto;display:block;}
@media(max-width:900px){.hero-inner{grid-template-columns:1fr;}}
@media(prefers-reduced-motion:reduce){.skip-link{transition:top .01ms;}}';
	echo '<style id="ortsverein-nv-critical-css">' . $critical . '</style>' . "\n";
}
add_action( 'wp_head', 'ortsverein_nv_critical_css', 1 );

/**
 * Frontend CSS und JS laden.
 */
function ortsverein_nv_enqueue_assets() {
	$version     = wp_get_theme()->get( 'Version' );
	$template_uri = get_template_directory_uri();

	wp_enqueue_style(
		'ortsverein-nv-theme',
		$template_uri . '/assets/css/theme.css',
		array(),
		$version
	);

	$ortsverein_nv_hero_id = get_theme_mod( 'ortsverein_nv_hero_background', '' );
	if ( $ortsverein_nv_hero_id ) {
		$ortsverein_nv_hero_url = wp_get_attachment_image_url( (int) $ortsverein_nv_hero_id, 'full' );
		if ( $ortsverein_nv_hero_url ) {
			$ortsverein_nv_hero_css = '#hero { background-image: url(' . esc_url( $ortsverein_nv_hero_url ) . '); background-size: cover; background-position: center; }';
			wp_add_inline_style( 'ortsverein-nv-theme', $ortsverein_nv_hero_css );
		}
	}

	wp_enqueue_script(
		'ortsverein-nv-theme',
		$template_uri . '/assets/js/theme.js',
		array(),
		$version,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'ortsverein_nv_enqueue_assets' );
