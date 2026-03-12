<?php
/**
 * Schema.org / JSON-LD – ortsverein_nv
 * Strukturierte Daten für Organisation, Website und Events.
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Strukturierte Daten (Schema.org/JSON-LD) für Organisation, Website und Events.
 * Hilft Suchmaschinen und KI-Diensten, Inhalte besser zu verstehen.
 * Gibt nichts aus, wenn ein gängiges SEO-Plugin aktiv ist.
 */
function ortsverein_nv_output_schema_jsonld() {
	if ( is_admin() ) {
		return;
	}

	// Wenn ein SEO-Plugin aktiv ist, dieses die Schema-Ausgabe übernehmen lassen.
	if ( defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) ) {
		return;
	}

	$graph = array();

	$home_url = home_url( '/' );

	// Organisationsdaten bevorzugt aus Theme-Options, sonst sinnvolle Defaults.
	if ( function_exists( 'ortsverein_nv_get_option' ) ) {
		$org_name    = ortsverein_nv_get_option( 'org_name', 'AWO Ortsverein Neukirchen-Vluyn' );
		$org_street  = ortsverein_nv_get_option( 'org_street', 'Max-von-Schenkendorf-Straße 9' );
		$org_zip     = ortsverein_nv_get_option( 'org_zip', '47506' );
		$org_city    = ortsverein_nv_get_option( 'org_city', 'Neukirchen-Vluyn' );
		$org_country = ortsverein_nv_get_option( 'org_country', 'DE' );
		$same_awokv  = ortsverein_nv_get_option( 'social_awokv', 'https://www.awo-kv-wesel.de/ueber-die-awo/awo-vor-ort/ortsvereine-und-awo-treffs/ortsverein-neukirchen-vluyn/' );
	} else {
		$org_name    = 'AWO Ortsverein Neukirchen-Vluyn';
		$org_street  = 'Max-von-Schenkendorf-Straße 9';
		$org_zip     = '47506';
		$org_city    = 'Neukirchen-Vluyn';
		$org_country = 'DE';
		$same_awokv  = 'https://www.awo-kv-wesel.de/ueber-die-awo/awo-vor-ort/ortsvereine-und-awo-treffs/ortsverein-neukirchen-vluyn/';
	}

	// Organisation (Ortsverein).
	$org = array(
		'@type'           => 'NGO',
		'@id'             => $home_url . '#organisation',
		'name'            => $org_name,
		'url'             => $home_url,
		'address'         => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $org_street,
			'postalCode'      => $org_zip,
			'addressLocality' => $org_city,
			'addressCountry'  => $org_country,
		),
		'areaServed'      => array(
			'@type' => 'City',
			'name'  => $org_city,
		),
		'sameAs'          => $same_awokv ? array( $same_awokv ) : array(),
	);

	// Website.
	$website = array(
		'@type'       => 'WebSite',
		'@id'         => $home_url . '#website',
		'url'         => $home_url,
		'name'        => get_bloginfo( 'name', 'display' ),
		'publisher'   => array( '@id' => $home_url . '#organisation' ),
	);

	$site_desc = get_bloginfo( 'description', 'display' );
	if ( is_string( $site_desc ) && '' !== trim( $site_desc ) ) {
		$website['description'] = $site_desc;
	}

	$graph[] = $org;
	$graph[] = $website;

	// Events aus ICS nur auf Startseite und Kalenderseite ausgeben (begrenzt auf einige kommende Termine).
	$calendar_page_id = function_exists( 'ortsverein_nv_get_page_id_from_option' )
		? ortsverein_nv_get_page_id_from_option( 'page_calendar', 'kalender' )
		: 0;

	$is_calendar_page = $calendar_page_id > 0 ? is_page( $calendar_page_id ) : is_page( 'kalender' );

	if ( is_front_page() || $is_calendar_page ) {
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
			$name      = isset( $event['title'] ) ? trim( (string) $event['title'] ) : __( 'Termin', 'ortsverein-nv' );
			$location  = isset( $event['location'] ) ? trim( (string) $event['location'] ) : '';

			// Nur sinnvolle Events ausgeben: Name und Ort müssen befüllt sein.
			if ( '' === $name || '' === $location ) {
				continue;
			}

			$graph[] = array(
				'@type'     => 'Event',
				'name'      => $name,
				'startDate' => $start_iso,
				'endDate'   => $end_iso,
				'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
				'eventStatus'         => 'https://schema.org/EventScheduled',
				'location'  => array(
					'@type'   => 'Place',
					'name'    => $org_name,
					'address' => array(
						'@type'           => 'PostalAddress',
						'streetAddress'   => $org_street,
						'postalCode'      => $org_zip,
						'addressLocality' => $org_city,
						'addressCountry'  => $org_country,
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

