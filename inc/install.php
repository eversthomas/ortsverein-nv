<?php
/**
 * Install-/Initial-Logik – ortsverein_nv
 * Legt bei der ersten Aktivierung sinnvolle Grundstrukturen an, ohne bestehende
 * Inhalte zu überschreiben oder Dubletten zu erzeugen.
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
 * @param string $old_name    Vorheriges Theme (unbenutzt).
 * @param string $old_theme   Vorheriger Theme-Name (unbenutzt).
 */
function ortsverein_nv_after_switch_theme( $old_name, $old_theme = '' ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	// Wenn bereits installiert, nichts mehr tun.
	if ( get_option( ORTSVEREIN_NV_INSTALLED_OPTION ) ) {
		return;
	}

	// 1. Wichtige Seiten anlegen (falls nicht vorhanden).
	$page_ids = ortsverein_nv_ensure_core_pages();

	// 2. Menüs anlegen und befüllen (falls nicht vorhanden).
	ortsverein_nv_ensure_menus( $page_ids );

	// 3. Statische Startseite setzen, falls noch nicht gesetzt.
	ortsverein_nv_ensure_front_page( $page_ids );

	// 4. Theme-Options initial befüllen (zentrale Seiten).
	ortsverein_nv_seed_theme_options( $page_ids );

	// Marker setzen, damit die Routine nicht erneut läuft.
	update_option( ORTSVEREIN_NV_INSTALLED_OPTION, time() );
}
add_action( 'after_switch_theme', 'ortsverein_nv_after_switch_theme', 10, 2 );

/**
 * Legt wichtige Seiten an, falls sie noch nicht existieren.
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
					update_post_meta( $page_id, $meta_key, $meta_value );
				}
			}
		}
	}

	return $page_ids;
}

/**
 * Legt Menüs an und ordnet sie Theme-Locations zu, falls noch nicht vorhanden.
 *
 * @param array $page_ids Assoziatives Array von Slug => Page-ID.
 */
function ortsverein_nv_ensure_menus( $page_ids ) {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( ! is_array( $locations ) ) {
		$locations = array();
	}

	// Hilfsfunktion: Menü nach Name holen oder erstellen.
	$get_or_create_menu = function ( $menu_name ) {
		$menu = wp_get_nav_menu_object( $menu_name );
		if ( ! $menu ) {
			$menu_id = wp_create_nav_menu( $menu_name );
		} else {
			$menu_id = (int) $menu->term_id;
		}
		return $menu_id;
	};

	// Hauptmenü.
	if ( empty( $locations['primary'] ) ) {
		$primary_id               = $get_or_create_menu( __( 'Hauptmenü', 'ortsverein-nv' ) );
		$locations['primary']     = $primary_id;

		// Falls frisch erstellt: einfache Struktur hinzufügen (Startseite, Kalender, Mitgliedschaft, Kontakt).
		if ( $primary_id && ! has_nav_menu_items( $primary_id ) ) {
			$primary_items = array( 'startseite', 'kalender', 'mitgliedschaft', 'kontakt' );
			foreach ( $primary_items as $slug ) {
				if ( isset( $page_ids[ $slug ] ) ) {
					wp_update_nav_menu_item(
						$primary_id,
						0,
						array(
							'menu-item-object-id' => $page_ids[ $slug ],
							'menu-item-object'    => 'page',
							'menu-item-type'      => 'post_type',
							'menu-item-status'    => 'publish',
						)
					);
				}
			}
		}
	}

	// Schnellzugriff (z. B. Kalender, Downloads, Kontakt).
	if ( empty( $locations['service'] ) ) {
		$service_id               = $get_or_create_menu( __( 'Schnellzugriff', 'ortsverein-nv' ) );
		$locations['service']     = $service_id;

		if ( $service_id && ! has_nav_menu_items( $service_id ) ) {
			$service_items = array( 'kalender', 'kontakt' );
			foreach ( $service_items as $slug ) {
				if ( isset( $page_ids[ $slug ] ) ) {
					wp_update_nav_menu_item(
						$service_id,
						0,
						array(
							'menu-item-object-id' => $page_ids[ $slug ],
							'menu-item-object'    => 'page',
							'menu-item-type'      => 'post_type',
							'menu-item-status'    => 'publish',
						)
					);
				}
			}
		}
	}

	// Footer-Menü (Impressum, Datenschutz, Kontakt).
	if ( empty( $locations['footer'] ) ) {
		$footer_id               = $get_or_create_menu( __( 'Footer', 'ortsverein-nv' ) );
		$locations['footer']     = $footer_id;

		if ( $footer_id && ! has_nav_menu_items( $footer_id ) ) {
			$footer_items = array( 'impressum', 'datenschutz', 'kontakt' );
			foreach ( $footer_items as $slug ) {
				if ( isset( $page_ids[ $slug ] ) ) {
					wp_update_nav_menu_item(
						$footer_id,
						0,
						array(
							'menu-item-object-id' => $page_ids[ $slug ],
							'menu-item-object'    => 'page',
							'menu-item-type'      => 'post_type',
							'menu-item-status'    => 'publish',
						)
					);
				}
			}
		}
	}

	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Helfer: Prüft, ob ein Menü bereits Einträge hat.
 *
 * @param int $menu_id Menü-ID.
 * @return bool
 */
function has_nav_menu_items( $menu_id ) {
	$items = wp_get_nav_menu_items( $menu_id );
	return ! empty( $items );
}

/**
 * Setzt eine statische Startseite, falls noch keine gesetzt ist.
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
 * Füllt zentrale Theme-Options mit den neu angelegten Seiten, falls dort noch nichts gesetzt ist.
 *
 * @param array $page_ids Assoziatives Array von Slug => Page-ID.
 */
function ortsverein_nv_seed_theme_options( $page_ids ) {
	$options = get_option( 'ortsverein_nv_options', array() );
	if ( ! is_array( $options ) ) {
		$options = array();
	}

	$map = array(
		'page_calendar'    => 'kalender',
		'page_membership'  => 'mitgliedschaft',
		'page_contact'     => 'kontakt',
		'page_begegnung'   => '', // Noch kein eigenes Template, später befüllbar.
		'page_downloads'   => '',
		'page_intern'      => '',
	);

	foreach ( $map as $option_key => $slug ) {
		if ( ! empty( $options[ $option_key ] ) ) {
			continue;
		}
		if ( $slug && isset( $page_ids[ $slug ] ) ) {
			$options[ $option_key ] = (int) $page_ids[ $slug ];
		}
	}

	update_option( 'ortsverein_nv_options', $options );
}

