<?php
/**
 * ICS-Kalender – Abruf, Parsing und Caching von iCal-Daten
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Standard-ICS-URL (AWO KV Wesel, Kategorie 23). */
define( 'ORTSVEREIN_NV_ICS_DEFAULT_URL', 'https://www.awo-kv-wesel.de/?rex-api-call=forcal_ical&category=23&filename=category-23' );

/** Transient-Key für gecachte Termine. */
define( 'ORTSVEREIN_NV_ICS_TRANSIENT', 'ortsverein_nv_ics_events' );

/** Cache-Dauer in Sekunden (30 Minuten). */
define( 'ORTSVEREIN_NV_ICS_CACHE_SECONDS', 30 * 60 );

/**
 * Holt alle Termine aus dem ICS-Feed (gecacht).
 *
 * @return array Liste von Termin-Arrays: start (DateTime), end (DateTime), title, description, location.
 */
function ortsverein_nv_get_ics_events() {
	$cached = get_transient( ORTSVEREIN_NV_ICS_TRANSIENT );
	if ( is_array( $cached ) ) {
		return $cached;
	}

	$url = get_theme_mod( 'ortsverein_nv_ics_url', ORTSVEREIN_NV_ICS_DEFAULT_URL );
	$url = $url ? $url : ORTSVEREIN_NV_ICS_DEFAULT_URL;
	$url = esc_url_raw( $url );
	if ( ! $url ) {
		return array();
	}

	$response = wp_remote_get( $url, array(
		'timeout' => 15,
		'sslverify' => true,
	) );
	if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
		return array();
	}

	$body = wp_remote_retrieve_body( $response );
	$events = ortsverein_nv_parse_ics( $body );
	if ( empty( $events ) ) {
		return array();
	}

	// Nach Start sortieren.
	usort( $events, function ( $a, $b ) {
		return $a['start']->getTimestamp() - $b['start']->getTimestamp();
	} );

	set_transient( ORTSVEREIN_NV_ICS_TRANSIENT, $events, ORTSVEREIN_NV_ICS_CACHE_SECONDS );
	return $events;
}

/**
 * Parst iCal-Text in eine Liste von Termin-Arrays.
 *
 * @param string $raw Roher ICS-Inhalt.
 * @return array Liste mit keys: start (DateTime), end (DateTime), title, description, location.
 */
function ortsverein_nv_parse_ics( $raw ) {
	$raw = trim( (string) $raw );
	if ( $raw === '' ) {
		return array();
	}

	// Zeilen entfalten (RFC 5545: Zeile mit Leerzeichen/Tab am Anfang = Fortsetzung).
	$raw = preg_replace( '/\r\n[ \t]/', '', $raw );
	$raw = preg_replace( '/\n[ \t]/', '', $raw );
	$lines = preg_split( '/\r\n|\n/', $raw );

	$events = array();
	$in_vevent = false;
	$current = array();

	foreach ( $lines as $line ) {
		$line = trim( $line );
		if ( $line === 'BEGIN:VEVENT' ) {
			$in_vevent = true;
			$current = array();
			continue;
		}
		if ( $line === 'END:VEVENT' ) {
			$in_vevent = false;
			$event = ortsverein_nv_ics_event_from_props( $current );
			if ( $event ) {
				$events[] = $event;
			}
			continue;
		}
		if ( ! $in_vevent ) {
			continue;
		}

		// Zeile "KEY;PARAMS:VALUE" oder "KEY:VALUE".
		if ( preg_match( '/^([A-Za-z0-9-]+)(?:;(.[^:]*))?:(.*)$/s', $line, $m ) ) {
			$key = strtoupper( $m[1] );
			$value = isset( $m[3] ) ? $m[3] : '';
			$params = isset( $m[2] ) ? $m[2] : '';
			$current[ $key ] = array( 'value' => $value, 'params' => $params );
		}
	}

	return $events;
}

/**
 * Baut ein Termin-Array aus geparsten VEVENT-Properties.
 *
 * @param array $props Assoziatives Array Key => array('value' => ..., 'params' => ...).
 * @return array|null Termin mit start, end, title, description, location oder null.
 */
function ortsverein_nv_ics_event_from_props( $props ) {
	$get = function ( $key ) use ( $props ) {
		if ( ! isset( $props[ $key ] ) ) {
			return null;
		}
		return isset( $props[ $key ]['value'] ) ? trim( $props[ $key ]['value'] ) : null;
	};

	$start_val = $get( 'DTSTART' );
	$start_params = isset( $props['DTSTART']['params'] ) ? $props['DTSTART']['params'] : '';
	$dt_start = ortsverein_nv_ics_parse_datetime( $start_val, $start_params );
	if ( ! $dt_start ) {
		return null;
	}
	$end_val = $get( 'DTEND' );
	$end_params = isset( $props['DTEND']['params'] ) ? $props['DTEND']['params'] : '';
	$dt_end = ortsverein_nv_ics_parse_datetime( $end_val, $end_params );
	if ( ! $dt_end ) {
		$dt_end = clone $dt_start;
		$dt_end->modify( '+1 hour' );
	}

	$title = $get( 'SUMMARY' );
	$title = $title !== null && $title !== '' ? $title : __( 'Termin', 'ortsverein-nv' );

	return array(
		'start'      => $dt_start,
		'end'        => $dt_end,
		'title'      => $title,
		'description' => $get( 'DESCRIPTION' ) ?: '',
		'location'   => $get( 'LOCATION' ) ?: '',
	);
}

/**
 * Parst ICS-Datumszeilen (DTSTART/DTEND).
 *
 * @param string|null $value  Wert (z. B. "20260309T131100" oder "20260309").
 * @param string      $params Optional. Parameter-String (z. B. "TZID=Europe/Berlin").
 * @return DateTime|null
 */
function ortsverein_nv_ics_parse_datetime( $value, $params = '' ) {
	if ( $value === null || $value === '' ) {
		return null;
	}
	$tz = new DateTimeZone( 'Europe/Berlin' );
	if ( $params && preg_match( '/TZID=([^;]+)/', $params, $m ) ) {
		try {
			$tz = new DateTimeZone( trim( $m[1] ) );
		} catch ( Exception $e ) {
			// Fallback Europe/Berlin
		}
	}
	// Nur Datum: 20260309.
	if ( preg_match( '/^(\d{4})(\d{2})(\d{2})$/', $value, $m ) ) {
		$str = $m[1] . '-' . $m[2] . '-' . $m[3] . ' 00:00:00';
		$dt = DateTime::createFromFormat( 'Y-m-d H:i:s', $str, $tz );
		return $dt ?: null;
	}
	// Datum + Zeit: 20260309T131100 oder 20260309T131100Z.
	if ( preg_match( '/^(\d{4})(\d{2})(\d{2})T(\d{2})(\d{2})(\d{2})(Z?)$/', $value, $m ) ) {
		$str = $m[1] . '-' . $m[2] . '-' . $m[3] . ' ' . $m[4] . ':' . $m[5] . ':' . $m[6];
		if ( isset( $m[7] ) && $m[7] === 'Z' ) {
			$tz = new DateTimeZone( 'UTC' );
		}
		$dt = DateTime::createFromFormat( 'Y-m-d H:i:s', $str, $tz );
		return $dt ?: null;
	}
	return null;
}

/**
 * Nächste N Termine ab jetzt (für Startseite).
 *
 * @param int $limit Maximal Anzahl.
 * @return array
 */
function ortsverein_nv_get_next_events( $limit = 6 ) {
	$events = ortsverein_nv_get_ics_events();
	$now = new DateTime( 'now', new DateTimeZone( 'Europe/Berlin' ) );
	$out = array();
	foreach ( $events as $ev ) {
		if ( $ev['start'] >= $now && count( $out ) < $limit ) {
			$out[] = $ev;
		}
	}
	return $out;
}

/**
 * Termine eines Monats (Jahr, Monat 1–12).
 *
 * @param int $year  Jahr.
 * @param int $month Monat 1–12.
 * @return array
 */
function ortsverein_nv_get_events_for_month( $year, $month ) {
	$events = ortsverein_nv_get_ics_events();
	$out = array();
	foreach ( $events as $ev ) {
		$y = (int) $ev['start']->format( 'Y' );
		$m = (int) $ev['start']->format( 'n' );
		if ( $y === $year && $m === $month ) {
			$out[] = $ev;
		}
	}
	return $out;
}

/** Deutsche Monatsnamen (voll). */
$GLOBALS['ortsverein_nv_monate'] = array(
	1 => __( 'Januar', 'ortsverein-nv' ), 2 => __( 'Februar', 'ortsverein-nv' ), 3 => __( 'März', 'ortsverein-nv' ),
	4 => __( 'April', 'ortsverein-nv' ), 5 => __( 'Mai', 'ortsverein-nv' ), 6 => __( 'Juni', 'ortsverein-nv' ),
	7 => __( 'Juli', 'ortsverein-nv' ), 8 => __( 'August', 'ortsverein-nv' ), 9 => __( 'September', 'ortsverein-nv' ),
	10 => __( 'Oktober', 'ortsverein-nv' ), 11 => __( 'November', 'ortsverein-nv' ), 12 => __( 'Dezember', 'ortsverein-nv' ),
);

/** Deutsche Wochentage (voll). */
$GLOBALS['ortsverein_nv_wochentage'] = array(
	1 => __( 'Montag', 'ortsverein-nv' ), 2 => __( 'Dienstag', 'ortsverein-nv' ), 3 => __( 'Mittwoch', 'ortsverein-nv' ),
	4 => __( 'Donnerstag', 'ortsverein-nv' ), 5 => __( 'Freitag', 'ortsverein-nv' ), 6 => __( 'Samstag', 'ortsverein-nv' ), 7 => __( 'Sonntag', 'ortsverein-nv' ),
);

/**
 * Monat kurz (z. B. JAN) für Termin-Datum.
 *
 * @param DateTime $dt Datum.
 * @return string
 */
function ortsverein_nv_ics_month_short( DateTime $dt ) {
	$m = (int) $dt->format( 'n' );
	$full = isset( $GLOBALS['ortsverein_nv_monate'][ $m ] ) ? $GLOBALS['ortsverein_nv_monate'][ $m ] : $dt->format( 'M' );
	return strtoupper( mb_substr( $full, 0, 3 ) );
}

/**
 * Wochentag (z. B. Montag) für Termin-Anzeige.
 *
 * @param DateTime $dt Datum.
 * @return string
 */
function ortsverein_nv_ics_weekday( DateTime $dt ) {
	$w = (int) $dt->format( 'N' ); // 1=Mo .. 7=So
	return isset( $GLOBALS['ortsverein_nv_wochentage'][ $w ] ) ? $GLOBALS['ortsverein_nv_wochentage'][ $w ] : $dt->format( 'l' );
}
