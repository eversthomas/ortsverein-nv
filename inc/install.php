<?php
/**
 * Install-/Initial-Logik – ortsverein_nv
 *
 * Läuft nur bei der allerersten Theme-Aktivierung auf einer leeren Site.
 * Bestehende Installationen (Produktivbetrieb) werden nicht verändert:
 * keine neuen Seiten, keine Menüs, keine überschriebenen Inhalte oder Optionen.
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Marker-Option, damit Initial-Setup nur einmal pro Installation läuft.
 */
const ORTSVEREIN_NV_INSTALLED_OPTION = 'ortsverein_nv_installed';

/**
 * Wird nach Aktivierung des Themes aufgerufen.
 *
 * @param string $old_name  Vorheriges Theme (unbenutzt).
 * @param string $old_theme Vorheriger Theme-Name (unbenutzt).
 */
function ortsverein_nv_after_switch_theme( $old_name, $old_theme = '' ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	if ( get_option( ORTSVEREIN_NV_INSTALLED_OPTION ) ) {
		return;
	}

	// Produktiv- oder bereits gepflegte Site: nur Marker setzen, nichts anlegen oder überschreiben.
	if ( ortsverein_nv_is_existing_installation() ) {
		update_option( ORTSVEREIN_NV_INSTALLED_OPTION, time() );
		return;
	}

	$page_ids = ortsverein_nv_ensure_core_pages();
	ortsverein_nv_ensure_front_page( $page_ids );
	ortsverein_nv_seed_theme_options( $page_ids );

	update_option( ORTSVEREIN_NV_INSTALLED_OPTION, time() );
}
add_action( 'after_switch_theme', 'ortsverein_nv_after_switch_theme', 10, 2 );

/**
 * Erkennt eine bereits genutzte WordPress-Site (nicht die frische Beispiel-Installation).
 *
 * @return bool
 */
function ortsverein_nv_is_existing_installation() {
	$options = get_option( 'ortsverein_nv_options', array() );
	if ( is_array( $options ) ) {
		foreach ( $options as $value ) {
			if ( '' !== $value && null !== $value && false !== $value && 0 !== $value && '0' !== $value ) {
				return true;
			}
		}
	}

	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( is_array( $locations ) ) {
		foreach ( $locations as $menu_id ) {
			if ( ! empty( $menu_id ) ) {
				return true;
			}
		}
	}

	if ( 'page' === get_option( 'show_on_front' ) ) {
		return true;
	}

	$pages = get_pages(
		array(
			'post_status' => array( 'publish', 'draft', 'private' ),
			'number'      => 5,
		)
	);

	return is_array( $pages ) && count( $pages ) > 1;
}

/**
 * Legt Kernseiten nur an, wenn der jeweilige Slug noch nicht existiert.
 * Vorhandene Seiten (Inhalt, Template, Status) bleiben unverändert.
 *
 * @return array Assoziatives Array von Slug => Page-ID.
 */
function ortsverein_nv_ensure_core_pages() {
	$core_pages = array(
		'startseite'     => array(
			'title'   => __( 'Startseite', 'ortsverein-nv' ),
			'content' => __( 'Dies ist die Startseite des AWO Ortsvereins. Du kannst diesen Text im Classic Editor anpassen.', 'ortsverein-nv' ),
		),
		'kalender'       => array(
			'title'   => __( 'Kalender', 'ortsverein-nv' ),
			'content' => __( 'Alle Termine des Ortsvereins auf einen Blick.', 'ortsverein-nv' ),
			'meta'    => array(
				'_wp_page_template' => 'page-kalender.php',
			),
		),
		'mitgliedschaft' => array(
			'title'   => __( 'Mitglied werden', 'ortsverein-nv' ),
			'content' => __( 'Informationen zur Mitgliedschaft im AWO Ortsverein Neukirchen-Vluyn.', 'ortsverein-nv' ),
			'meta'    => array(
				'_wp_page_template' => 'page-mitgliedschaft.php',
			),
		),
		'kontakt'        => array(
			'title'   => __( 'Kontakt', 'ortsverein-nv' ),
			'content' => __( 'Kontaktdaten des Ortsvereins. Du kannst hier Adresse, Telefon und E-Mail ergänzen.', 'ortsverein-nv' ),
			'meta'    => array(
				'_wp_page_template' => 'template-kontakt.php',
			),
		),
		'impressum'      => array(
			'title'   => __( 'Impressum', 'ortsverein-nv' ),
			'content' => __( 'Impressum des Ortsvereins. Bitte ergänze hier die rechtlich erforderlichen Angaben.', 'ortsverein-nv' ),
		),
		'datenschutz'    => array(
			'title'   => __( 'Datenschutz', 'ortsverein-nv' ),
			'content' => __( 'Informationen zum Datenschutz. Bitte ergänze hier eure Datenschutz-Hinweise.', 'ortsverein-nv' ),
		),
	);

	$page_ids = array();

	foreach ( $core_pages as $slug => $data ) {
		$existing = get_page_by_path( $slug );
		if ( $existing instanceof WP_Post ) {
			$page_ids[ $slug ] = (int) $existing->ID;
			continue;
		}

		$page_id = wp_insert_post(
			array(
				'post_title'   => $data['title'],
				'post_name'    => $slug,
				'post_content' => $data['content'],
				'post_status'  => 'publish',
				'post_type'    => 'page',
			),
			true
		);

		if ( ! is_wp_error( $page_id ) && $page_id ) {
			$page_ids[ $slug ] = (int) $page_id;

			if ( ! empty( $data['meta'] ) && is_array( $data['meta'] ) ) {
				foreach ( $data['meta'] as $meta_key => $meta_value ) {
					add_post_meta( $page_id, $meta_key, $meta_value, true );
				}
			}
		}
	}

	return $page_ids;
}

/**
 * Setzt eine statische Startseite nur, wenn WordPress noch die Beitragsansicht nutzt.
 *
 * @param array $page_ids Assoziatives Array von Slug => Page-ID.
 */
function ortsverein_nv_ensure_front_page( $page_ids ) {
	$show_on_front = get_option( 'show_on_front', 'posts' );
	if ( 'page' === $show_on_front ) {
		return;
	}

	if ( isset( $page_ids['startseite'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $page_ids['startseite'] );
	}
}

/**
 * Ergänzt Theme-Options nur dort, wo noch kein Wert steht.
 *
 * @param array $page_ids Assoziatives Array von Slug => Page-ID.
 */
function ortsverein_nv_seed_theme_options( $page_ids ) {
	$options = get_option( 'ortsverein_nv_options', array() );
	if ( ! is_array( $options ) ) {
		$options = array();
	}

	$map = array(
		'page_calendar'   => 'kalender',
		'page_membership' => 'mitgliedschaft',
		'page_contact'    => 'kontakt',
		'page_begegnung'  => '',
		'page_downloads'  => '',
		'page_intern'     => '',
	);

	$changed = false;
	foreach ( $map as $option_key => $slug ) {
		if ( ! empty( $options[ $option_key ] ) ) {
			continue;
		}
		if ( $slug && isset( $page_ids[ $slug ] ) ) {
			$options[ $option_key ] = (int) $page_ids[ $slug ];
			$changed                = true;
		}
	}

	if ( $changed ) {
		update_option( 'ortsverein_nv_options', $options );
	}
}
