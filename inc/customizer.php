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

	// --- Plugin-Kalender "Bezugssysteme ICS Feed" (nur sichtbar, wenn Plugin aktiv) ---
	if ( ortsverein_nv_bs_ics_plugin_active() ) {
		$bs_ics_locations = array(
			'home' => __( 'Startseite', 'ortsverein-nv' ),
			'page' => __( 'Kalenderseite', 'ortsverein-nv' ),
		);

		foreach ( $bs_ics_locations as $loc_key => $loc_label ) {
			$wp_customize->add_setting( 'ortsverein_nv_kalender_' . $loc_key . '_use_plugin', array(
				'default'           => false,
				'sanitize_callback' => 'ortsverein_nv_sanitize_checkbox',
				'validate_callback' => 'ortsverein_nv_validate_kalender_plugin_toggle',
			) );
			$wp_customize->add_control( 'ortsverein_nv_kalender_' . $loc_key . '_use_plugin', array(
				/* translators: %s: Startseite oder Kalenderseite. */
				'label'       => sprintf( __( '%s: Plugin-Kalender statt eigenem ICS-Kalender verwenden', 'ortsverein-nv' ), $loc_label ),
				'description' => __( 'Ersetzt die dortige Kalenderansicht vollständig durch den unten eingetragenen Shortcode des Plugins "Bezugssysteme ICS Feed".', 'ortsverein-nv' ),
				'section'     => 'ortsverein_nv_kalender',
				'type'        => 'checkbox',
			) );

			$wp_customize->add_setting( 'ortsverein_nv_kalender_' . $loc_key . '_shortcode', array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			) );
			$wp_customize->add_control( 'ortsverein_nv_kalender_' . $loc_key . '_shortcode', array(
				/* translators: %s: Startseite oder Kalenderseite. */
				'label'       => sprintf( __( '%s: Shortcode aus dem Plugin', 'ortsverein-nv' ), $loc_label ),
				'description' => __( 'Im Plugin-Backend unter "ICS Feeds" den gewünschten Feed öffnen und den dort angezeigten Shortcode (z. B. [bs_ics_calendar id="12"]) hier einfügen. Wird nur verwendet, wenn der Schalter oben aktiviert ist.', 'ortsverein-nv' ),
				'section'     => 'ortsverein_nv_kalender',
				'type'        => 'text',
			) );
		}
	}
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
 * Sanitize-Callback für Checkbox-Einstellungen (true/false).
 *
 * @param mixed $value Roher Customizer-Wert.
 * @return bool
 */
function ortsverein_nv_sanitize_checkbox( $value ) {
	return ( isset( $value ) && true == $value ); // phpcs:ignore Universal.Operators.StrictComparisons -- Checkbox liefert '1'/true.
}

/**
 * Verhindert das Aktivieren eines Plugin-Kalender-Schalters ohne gültigen Shortcode.
 *
 * Prüft sowohl den in diesem Speicherlauf mitgesendeten (noch ungesicherten) Shortcode-Wert
 * als auch – falls im selben Request nicht mitgesendet – den bereits gespeicherten Theme-Mod.
 *
 * @param WP_Error              $validity Validierungsobjekt.
 * @param mixed                 $value    Roher Checkbox-Wert.
 * @param WP_Customize_Setting  $setting  Die Checkbox-Einstellung.
 * @return WP_Error
 */
function ortsverein_nv_validate_kalender_plugin_toggle( $validity, $value, $setting ) {
	$enabled = ( isset( $value ) && true == $value ); // phpcs:ignore Universal.Operators.StrictComparisons -- Checkbox liefert '1'/true.
	if ( ! $enabled ) {
		return $validity;
	}

	if ( ! ortsverein_nv_bs_ics_plugin_active() ) {
		$validity->add( 'bs_ics_inactive', __( 'Das Plugin „Bezugssysteme ICS Feed" ist nicht aktiv.', 'ortsverein-nv' ) );
		return $validity;
	}

	$shortcode_setting_id = str_replace( '_use_plugin', '_shortcode', $setting->id );
	$posted_values        = $setting->manager->unsanitized_post_values();
	$shortcode            = array_key_exists( $shortcode_setting_id, $posted_values )
		? trim( (string) $posted_values[ $shortcode_setting_id ] )
		: trim( (string) get_theme_mod( $shortcode_setting_id, '' ) );

	if ( '' === $shortcode || ! has_shortcode( $shortcode, 'bs_ics_calendar' ) ) {
		$validity->add(
			'bs_ics_shortcode_required',
			__( 'Bitte hole dir zuerst den Shortcode aus dem Plugin-Backend („ICS Feeds" – Feed öffnen) und trage ihn im Feld darunter ein, bevor du diese Option aktivierst.', 'ortsverein-nv' )
		);
	}

	return $validity;
}

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
