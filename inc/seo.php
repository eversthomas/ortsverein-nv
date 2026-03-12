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
	$site_name = get_bloginfo( 'name', 'display' );
	$site_tagline = get_bloginfo( 'description', 'display' );

	if ( is_front_page() || is_home() ) {
		// Startseite: "Seitentitel" oder nur "AWO Ortsverein …" + Unterzeile.
		if ( ! empty( $site_name ) ) {
			$title_parts['title'] = $site_name;
		}
		if ( ! empty( $site_tagline ) ) {
			$title_parts['tagline'] = $site_tagline;
		}
		return $title_parts;
	}

	// Unterseiten: "Seitentitel » AWO Ortsverein Neukirchen-Vluyn".
	if ( ! empty( $site_name ) ) {
		$title_parts['site'] = $site_name;
	}

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
	$description = apply_filters( 'ortsverein_nv_meta_description', '' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	if ( is_string( $description ) && $description !== '' ) {
		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $description ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'ortsverein_nv_meta_description', 2 );

/**
 * Einfache, sinnvolle Standard-Meta-Description generieren.
 * - Nutzt Auszug, falls vorhanden.
 * - Fällt ansonsten auf den Content der aktuellen Seite zurück.
 * - Gibt nichts zurück, wenn ein SEO-Plugin aktiv ist (Yoast/RankMath), damit es keine Doppelungen gibt.
 *
 * @param string $description Bisherige Description (Standard: leer).
 * @return string
 */
function ortsverein_nv_build_meta_description( $description ) {
	// Wenn bereits eine Description gesetzt wurde, nicht überschreiben (z. B. durch Plugin).
	if ( is_string( $description ) && $description !== '' ) {
		return $description;
	}

	// Wenn ein gängiges SEO-Plugin aktiv ist, übernimmt dieses die Meta-Description.
	if ( defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) ) {
		return '';
	}

	if ( is_singular() ) {
		global $post;
		if ( $post instanceof WP_Post ) {
			// 1. Priorität: manueller Auszug.
			if ( has_excerpt( $post ) ) {
				$excerpt = get_the_excerpt( $post );
				return wp_trim_words( wp_strip_all_tags( $excerpt ), 30, ' …' );
			}

			// 2. Priorität: Inhalt der Seite.
			$content = wp_strip_all_tags( $post->post_content );
			$content = preg_replace( '/\s+/', ' ', $content );
			$content = trim( $content );
			if ( $content !== '' ) {
				return wp_trim_words( $content, 30, ' …' );
			}
		}
	}

	if ( is_home() || is_front_page() ) {
		$site_desc = get_bloginfo( 'description', 'display' );
		if ( ! empty( $site_desc ) ) {
			return $site_desc;
		}
	}

	return '';
}
add_filter( 'ortsverein_nv_meta_description', 'ortsverein_nv_build_meta_description', 5 ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

/**
 * Open-Graph- und Twitter-Meta-Tags für bessere Vorschauen in sozialen Netzwerken.
 * Vermeidet Doppelungen, wenn ein gängiges SEO-Plugin (Yoast/RankMath) aktiv ist.
 */
function ortsverein_nv_output_social_meta() {
	if ( is_admin() ) {
		return;
	}

	// SEO-Plugins erzeugen in der Regel eigene OG-/Twitter-Tags.
	if ( defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) ) {
		return;
	}

	global $wp;

	$title       = wp_get_document_title();
	$description = apply_filters( 'ortsverein_nv_meta_description', '' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

	// Site-Name bevorzugt aus Theme-Options, sonst Blogname.
	if ( function_exists( 'ortsverein_nv_get_option' ) ) {
		$site_name = ortsverein_nv_get_option( 'org_name', get_bloginfo( 'name', 'display' ) );
	} else {
		$site_name = get_bloginfo( 'name', 'display' );
	}

	// Aktuelle URL ermitteln (analog Canonical).
	if ( is_singular() ) {
		$url = get_permalink();
	} elseif ( is_search() ) {
		$url = get_search_link();
	} elseif ( is_home() && ! is_front_page() ) {
		$page_for_posts = (int) get_option( 'page_for_posts' );
		$url            = $page_for_posts ? get_permalink( $page_for_posts ) : home_url( '/' );
	} elseif ( is_front_page() ) {
		$url = home_url( '/' );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		$url  = ( $term && ! is_wp_error( $term ) ) ? get_term_link( $term ) : home_url( '/' );
	} elseif ( is_post_type_archive() || is_author() || is_date() || is_archive() ) {
		if ( isset( $wp->request ) ) {
			$url = home_url( '/' . ltrim( $wp->request, '/' ) . '/' );
		} else {
			$url = home_url( '/' );
		}
	} else {
		$url = home_url( '/' );
	}

	// Bild: Priorität Feature Image > Hero-Bild > Custom Logo.
	$image = '';
	if ( is_singular() && has_post_thumbnail() ) {
		$image = wp_get_attachment_image_url( get_post_thumbnail_id(), 'large' );
	}
	if ( ! $image ) {
		$hero_id = (int) get_theme_mod( 'ortsverein_nv_hero_background', 0 );
		if ( $hero_id ) {
			$image = wp_get_attachment_image_url( $hero_id, 'large' );
		}
	}
	if ( ! $image ) {
		$logo_id = get_theme_mod( 'custom_logo' );
		if ( $logo_id ) {
			$image = wp_get_attachment_image_url( $logo_id, 'full' );
		}
	}

	// Open Graph.
	if ( is_singular( 'post' ) ) {
		$og_type = 'article';
	} elseif ( is_front_page() || is_home() || is_page() ) {
		$og_type = 'website';
	} else {
		$og_type = 'website';
	}

	echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( $description ) {
		echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	if ( $site_name ) {
		echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '">' . "\n";
	}
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	}

	// Twitter Cards (X).
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( $description ) {
		echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	if ( $image ) {
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'ortsverein_nv_output_social_meta', 3 );

/**
 * Canonical & robots:
 * - Canonical wird vom WordPress-Core über rel_canonical im Head ausgegeben.
 * - robots-Meta wird vom WordPress-Core über wp_robots ausgegeben.
 * Das Theme ergänzt hier bewusst keine eigenen Duplikate, um Mehrfachausgaben zu vermeiden.
 */
