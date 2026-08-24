<?php
/**
 * Customizer – ortsverein_nv
 * Eine schlanke Option: Hero-Hintergrundbild (Inhaltsentscheidung, kein Layout).
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer-Registrierung.
 */
function ortsverein_nv_customize_register( WP_Customize_Manager $wp_customize ) {
	$wp_customize->add_section(
		'ortsverein_nv_header',
		array(
			'title'    => __( 'Header', 'ortsverein-nv' ),
			'priority' => 25,
		)
	);

	$wp_customize->add_setting( 'ortsverein_nv_header_height', array(
		'default'           => 44,
		'sanitize_callback' => 'ortsverein_nv_sanitize_header_height',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'ortsverein_nv_header_height', array(
		'label'       => __( 'Logo-/Header-Höhe (px)', 'ortsverein-nv' ),
		'description' => __( 'Höhe des Logos in der Kopfzeile. Der Header passt sich automatisch an.', 'ortsverein-nv' ),
		'section'     => 'ortsverein_nv_header',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 32,
			'max'  => 140,
			'step' => 2,
		),
	) );

	$wp_customize->add_section(
		'ortsverein_nv_hero',
		array(
			'title'    => __( 'Hero-Bereich (Startseite)', 'ortsverein-nv' ),
			'priority' => 30,
		)
	);

	// Hero-Texte
	$wp_customize->add_setting( 'ortsverein_nv_hero_eyebrow', array(
		'default'           => __( 'Neukirchen-Vluyn', 'ortsverein-nv' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'ortsverein_nv_hero_eyebrow', array(
		'label'   => __( 'Eyebrow-Text (kleine Zeile über der Überschrift)', 'ortsverein-nv' ),
		'section' => 'ortsverein_nv_hero',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'ortsverein_nv_hero_title', array(
		'default'           => __( 'Schön, dass du da bist.', 'ortsverein-nv' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'ortsverein_nv_hero_title', array(
		'label'   => __( 'Überschrift (H1)', 'ortsverein-nv' ),
		'section' => 'ortsverein_nv_hero',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'ortsverein_nv_hero_lead', array(
		'default'           => __( 'Wir sind der AWO Ortsverein Neukirchen-Vluyn – ein Ort für Begegnung, gegenseitige Unterstützung und gelebte Gemeinschaft. Komm einfach vorbei.', 'ortsverein-nv' ),
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'ortsverein_nv_hero_lead', array(
		'label'   => __( 'Einleitungstext (Absatz unter der Überschrift)', 'ortsverein-nv' ),
		'section' => 'ortsverein_nv_hero',
		'type'    => 'textarea',
		'rows'    => 4,
	) );

	$wp_customize->add_setting( 'ortsverein_nv_hero_btn_primary', array(
		'default'           => __( 'Termine & Programm', 'ortsverein-nv' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'ortsverein_nv_hero_btn_primary', array(
		'label'   => __( 'Button 1 – Text (roter Button)', 'ortsverein-nv' ),
		'section' => 'ortsverein_nv_hero',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'ortsverein_nv_hero_btn_secondary', array(
		'default'           => __( 'Mitglied werden', 'ortsverein-nv' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'ortsverein_nv_hero_btn_secondary', array(
		'label'   => __( 'Button 2 – Text (umrandeter Button)', 'ortsverein-nv' ),
		'section' => 'ortsverein_nv_hero',
		'type'    => 'text',
	) );

	// Button-Ziele (Seitenauswahl)
	$wp_customize->add_setting( 'ortsverein_nv_hero_btn_primary_page', array(
		'default'           => 0,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'ortsverein_nv_hero_btn_primary_page', array(
		'label'       => __( 'Button 1 – Zielseite', 'ortsverein-nv' ),
		'description' => __( 'Seite, auf die der rote Button verlinkt. Leer = Startseite.', 'ortsverein-nv' ),
		'section'     => 'ortsverein_nv_hero',
		'type'        => 'dropdown-pages',
		'allow_addition' => true,
	) );

	$wp_customize->add_setting( 'ortsverein_nv_hero_btn_secondary_page', array(
		'default'           => 0,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'ortsverein_nv_hero_btn_secondary_page', array(
		'label'       => __( 'Button 2 – Zielseite', 'ortsverein-nv' ),
		'description' => __( 'Seite, auf die der umrandete Button verlinkt. Leer = Startseite.', 'ortsverein-nv' ),
		'section'     => 'ortsverein_nv_hero',
		'type'        => 'dropdown-pages',
		'allow_addition' => true,
	) );

	$wp_customize->add_setting(
		'ortsverein_nv_hero_background',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'ortsverein_nv_hero_background',
			array(
				'label'       => __( 'Hintergrundbild', 'ortsverein-nv' ),
				'description' => __( 'Optional. Ohne Auswahl wird der Standard-Hintergrund (Grau) angezeigt.', 'ortsverein-nv' ),
				'section'     => 'ortsverein_nv_hero',
				'mime_type'   => 'image',
			)
		)
	);

	// Hero-Statistik-Kacheln (3 Karten: Zahl/Text + Beschriftung)
	$ortsverein_nv_card_defaults = array(
		1 => array( 'num' => '200+', 'label' => __( 'Mitglieder', 'ortsverein-nv' ) ),
		2 => array( 'num' => '10+', 'label' => __( 'Angebote monatlich', 'ortsverein-nv' ) ),
		3 => array( 'num' => __( 'Seit 1952', 'ortsverein-nv' ), 'label' => __( 'für Neukirchen-Vluyn', 'ortsverein-nv' ) ),
	);

	foreach ( $ortsverein_nv_card_defaults as $i => $defaults ) {
		$wp_customize->add_setting( 'ortsverein_nv_hero_card' . $i . '_num', array(
			'default'           => $defaults['num'],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'ortsverein_nv_hero_card' . $i . '_num', array(
			'label'   => sprintf( __( 'Kachel %d – Zahl/Text', 'ortsverein-nv' ), $i ),
			'section' => 'ortsverein_nv_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'ortsverein_nv_hero_card' . $i . '_label', array(
			'default'           => $defaults['label'],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'ortsverein_nv_hero_card' . $i . '_label', array(
			'label'   => sprintf( __( 'Kachel %d – Beschriftung', 'ortsverein-nv' ), $i ),
			'section' => 'ortsverein_nv_hero',
			'type'    => 'text',
		) );
	}

	// --- Kalender / ICS ---
	$wp_customize->add_section(
		'ortsverein_nv_kalender',
		array(
			'title'    => __( 'Kalender (ICS)', 'ortsverein-nv' ),
			'priority' => 35,
		)
	);
	$wp_customize->add_setting( 'ortsverein_nv_ics_url', array(
		'default'           => ORTSVEREIN_NV_ICS_DEFAULT_URL,
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'ortsverein_nv_ics_url', array(
		'label'       => __( 'ICS-Kalender-URL', 'ortsverein-nv' ),
		'description' => __( 'URL zum iCal-Feed (z. B. AWO-Veranstaltungskalender). Leer = Standard-URL.', 'ortsverein-nv' ),
		'section'     => 'ortsverein_nv_kalender',
		'type'        => 'url',
	) );
}

add_action( 'customize_register', 'ortsverein_nv_customize_register' );

/**
 * ICS-Cache leeren nach Customizer-Speicherung (damit neue ICS-URL sofort greift).
 */
function ortsverein_nv_customize_save_ics() {
	if ( defined( 'ORTSVEREIN_NV_ICS_TRANSIENT' ) ) {
		delete_transient( ORTSVEREIN_NV_ICS_TRANSIENT );
	}
}
add_action( 'customize_save_after', 'ortsverein_nv_customize_save_ics' );

/**
 * Header-Logo-Höhe auf 32–140 px begrenzen.
 *
 * @param mixed $value Roher Customizer-Wert.
 * @return int
 */
function ortsverein_nv_sanitize_header_height( $value ) {
	$value = absint( $value );
	if ( $value < 32 ) {
		return 32;
	}
	if ( $value > 140 ) {
		return 140;
	}
	return $value;
}

/**
 * Aktuelle Logo-/Header-Höhe in Pixeln.
 *
 * @return int
 */
function ortsverein_nv_get_header_logo_size() {
	return ortsverein_nv_sanitize_header_height( get_theme_mod( 'ortsverein_nv_header_height', 44 ) );
}

/**
 * CSS für Logo- und Header-Höhe (nach theme.css, damit es zuverlässig greift).
 *
 * @param int $size Logo-Höhe in Pixeln.
 * @return string
 */
function ortsverein_nv_get_header_size_css( $size = 0 ) {
	$size = $size > 0 ? ortsverein_nv_sanitize_header_height( $size ) : ortsverein_nv_get_header_logo_size();
	$pad  = max( 14, (int) round( ( $size - 44 ) / 2 + 14 ) );

	return sprintf(
		':root{--header-logo-size:%1$dpx;}#site-header .brand-icon{height:%1$dpx;width:auto;max-width:none;max-height:%1$dpx;}#site-header .header-inner{padding-top:%2$dpx;padding-bottom:%2$dpx;}',
		$size,
		$pad
	);
}

/**
 * Live-Vorschau-Skript für den Customizer.
 */
function ortsverein_nv_customize_preview_js() {
	wp_enqueue_script(
		'ortsverein-nv-customizer-preview',
		get_template_directory_uri() . '/assets/js/customizer-preview.js',
		array( 'customize-preview' ),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'customize_preview_init', 'ortsverein_nv_customize_preview_js' );
