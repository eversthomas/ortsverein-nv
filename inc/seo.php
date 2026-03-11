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

	$title       = wp_get_document_title();
	$description = apply_filters( 'ortsverein_nv_meta_description', '' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	$site_name   = get_bloginfo( 'name', 'display' );

	// Aktuelle URL ermitteln.
	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
	$url         = esc_url( home_url( $request_uri ) );

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
	echo '<meta property="og:type" content="' . esc_attr( is_singular() ? 'article' : 'website' ) . '">' . "\n";
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
 * Strukturierte Daten (Schema.org/JSON-LD) für Organisation, Website und Events.
 * Hilft Suchmaschinen und KI-Diensten, Inhalte besser zu verstehen.
 * Gibt nichts aus, wenn ein gängiges SEO-Plugin aktiv ist.
 */
function ortsverein_nv_output_schema_jsonld() {
	if ( is_admin() ) {
		return;
	}

	if ( defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) ) {
		return;
	}

	$graph = array();

	$home_url = home_url( '/' );

	// Organisation (Ortsverein).
	$org = array(
		'@type'           => 'NGO',
		'@id'             => $home_url . '#organisation',
		'name'            => 'AWO Ortsverein Neukirchen-Vluyn',
		'url'             => $home_url,
		'address'         => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => 'Max-von-Schenkendorf-Straße 9',
			'postalCode'      => '47506',
			'addressLocality' => 'Neukirchen-Vluyn',
			'addressCountry'  => 'DE',
		),
		'areaServed'      => array(
			'@type' => 'City',
			'name'  => 'Neukirchen-Vluyn',
		),
		'sameAs'          => array(
			'https://www.awo-kv-wesel.de/ueber-die-awo/awo-vor-ort/ortsvereine-und-awo-treffs/ortsverein-neukirchen-vluyn/',
		),
	);

	// Website.
	$website = array(
		'@type'           => 'WebSite',
		'@id'             => $home_url . '#website',
		'url'             => $home_url,
		'name'            => get_bloginfo( 'name', 'display' ),
		'description'     => get_bloginfo( 'description', 'display' ),
		'publisher'       => array( '@id' => $home_url . '#organisation' ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => $home_url . '?s={search_term_string}',
			'query-input' => 'required name=search_term_string',
		),
	);

	$graph[] = $org;
	$graph[] = $website;

	// Events aus ICS nur auf Startseite und Kalenderseite ausgeben (begrenzt auf einige kommende Termine).
	if ( is_front_page() || is_page( 'kalender' ) ) {
		if ( function_exists( 'ortsverein_nv_get_next_events' ) ) {
			$events = ortsverein_nv_get_next_events( 6 );
		} else {
			$events = array();
		}

		foreach ( $events as $event ) {
			if ( empty( $event['start'] ) || ! ( $event['start'] instanceof DateTime ) ) {
				continue;
			}
			$start_iso = $event['start']->format( 'c' );
			$end_iso   = ( ! empty( $event['end'] ) && $event['end'] instanceof DateTime ) ? $event['end']->format( 'c' ) : null;
			$name      = isset( $event['title'] ) ? $event['title'] : __( 'Termin', 'ortsverein-nv' );
			$location  = isset( $event['location'] ) && $event['location'] !== '' ? $event['location'] : 'Marie-Juchacz-Haus, Max-von-Schenkendorf-Straße 9, 47506 Neukirchen-Vluyn';

			$graph[] = array(
				'@type'     => 'Event',
				'name'      => $name,
				'startDate' => $start_iso,
				'endDate'   => $end_iso,
				'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
				'eventStatus'         => 'https://schema.org/EventScheduled',
				'location'  => array(
					'@type'   => 'Place',
					'name'    => 'Marie-Juchacz-Haus',
					'address' => array(
						'@type'           => 'PostalAddress',
						'streetAddress'   => 'Max-von-Schenkendorf-Straße 9',
						'postalCode'      => '47506',
						'addressLocality' => 'Neukirchen-Vluyn',
						'addressCountry'  => 'DE',
					),
				),
				'organizer' => array(
					'@id' => $home_url . '#organisation',
				),
			);
		}
	}

	if ( empty( $graph ) ) {
		return;
	}

	$schema = array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'ortsverein_nv_output_schema_jsonld', 4 );

/**
 * Canonical: WordPress liefert keinen Standard-Canonical im Head.
 * Theme gibt keinen aus, um Doppelung mit SEO-Plugins zu vermeiden.
 * Bei Bedarf kann hier ein Hook ergänzt werden, der nur ohne aktives SEO-Plugin feuert.
 */
