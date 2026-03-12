<?php
/**
 * XML-Sitemap – ortsverein_nv
 * Stellt eine einfache, wartbare Sitemap unter /sitemap.xml bereit.
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Rewrite-Regel für /sitemap.xml registrieren.
 */
function ortsverein_nv_sitemap_add_rewrite_rule() {
	add_rewrite_rule( '^sitemap\.xml$', 'index.php?ortsverein_nv_sitemap=1', 'top' );
}
add_action( 'init', 'ortsverein_nv_sitemap_add_rewrite_rule' );

/**
 * Query-Var für Sitemap erlauben.
 *
 * @param array $vars Query-Variablen.
 * @return array
 */
function ortsverein_nv_sitemap_query_vars( $vars ) {
	$vars[] = 'ortsverein_nv_sitemap';
	return $vars;
}
add_filter( 'query_vars', 'ortsverein_nv_sitemap_query_vars' );

/**
 * Sitemap ausgeben, wenn /sitemap.xml abgefragt wird.
 */
function ortsverein_nv_maybe_output_sitemap() {
	if ( is_admin() ) {
		return;
	}

	// Wenn gängiges SEO-Plugin aktiv ist, dessen Sitemap-Logik Vorrang geben.
	if ( defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) ) {
		return;
	}

	$is_sitemap = (int) get_query_var( 'ortsverein_nv_sitemap' ) === 1;

	// Fallback: Erkenne /sitemap.xml auch dann, wenn Rewrite-Regeln noch nicht geflusht sind.
	if ( ! $is_sitemap ) {
		$request_path = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';
		$sitemap_path = wp_parse_url( home_url( '/sitemap.xml' ), PHP_URL_PATH );

		if ( $request_path && $sitemap_path && rtrim( $request_path, '/' ) === rtrim( $sitemap_path, '/' ) ) {
			$is_sitemap = true;
		}
	}

	if ( ! $is_sitemap ) {
		return;
	}

	// HTTP-Header für XML.
	nocache_headers();
	header( 'Content-Type: application/xml; charset=' . esc_attr( get_option( 'blog_charset' ) ) );

	$home_url = home_url( '/' );

	// Start XML.
	echo '<?xml version="1.0" encoding="' . esc_attr( get_option( 'blog_charset' ) ) . "\"?>\n";
	echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

	// Hilfsfunktion für lastmod im ISO-Format.
	$format_lastmod = function ( $gmt ) {
		if ( empty( $gmt ) ) {
			return '';
		}
		$ts = strtotime( $gmt );
		if ( ! $ts ) {
			return '';
		}
		return gmdate( 'Y-m-d\TH:i:s\Z', $ts );
	};

	// 1. Startseite.
	$front_id   = (int) get_option( 'page_on_front' );
	$front_url  = $front_id ? get_permalink( $front_id ) : $home_url;
	$front_post = $front_id ? get_post( $front_id ) : null;
	$front_mod  = ( $front_post instanceof WP_Post ) ? $format_lastmod( $front_post->post_modified_gmt ) : '';

	echo "\t<url>\n";
	echo "\t\t<loc>" . esc_url( $front_url ) . "</loc>\n";
	if ( $front_mod ) {
		echo "\t\t<lastmod>" . esc_html( $front_mod ) . "</lastmod>\n";
	}
	echo "\t\t<changefreq>weekly</changefreq>\n";
	echo "\t\t<priority>1.0</priority>\n";
	echo "\t</url>\n";

	// 2. Alle veröffentlichten Seiten (ohne Startseite, falls schon oben erfasst).
	$pages = get_pages(
		array(
			'sort_column' => 'post_modified',
			'sort_order'  => 'DESC',
			'post_status' => 'publish',
		)
	);

	foreach ( $pages as $page ) {
		if ( $front_id && (int) $page->ID === $front_id ) {
			continue;
		}
		$url     = get_permalink( $page );
		$lastmod = $format_lastmod( $page->post_modified_gmt );

		echo "\t<url>\n";
		echo "\t\t<loc>" . esc_url( $url ) . "</loc>\n";
		if ( $lastmod ) {
			echo "\t\t<lastmod>" . esc_html( $lastmod ) . "</lastmod>\n";
		}
		echo "\t\t<changefreq>weekly</changefreq>\n";
		echo "\t\t<priority>0.7</priority>\n";
		echo "\t</url>\n";
	}

	// 3. Optional: Beiträge (falls genutzt).
	$posts = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'modified',
			'order'          => 'DESC',
		)
	);

	foreach ( $posts as $post ) {
		$url     = get_permalink( $post );
		$lastmod = $format_lastmod( $post->post_modified_gmt );

		echo "\t<url>\n";
		echo "\t\t<loc>" . esc_url( $url ) . "</loc>\n";
		if ( $lastmod ) {
			echo "\t\t<lastmod>" . esc_html( $lastmod ) . "</lastmod>\n";
		}
		echo "\t\t<changefreq>weekly</changefreq>\n";
		echo "\t\t<priority>0.5</priority>\n";
		echo "\t</url>\n";
	}

	echo "</urlset>";

	exit;
}
add_action( 'template_redirect', 'ortsverein_nv_maybe_output_sitemap' );

