<?php
/**
 * Theme-Options-Seite – ortsverein_nv
 * Zentrale, einfach verständliche Konfiguration für Vereinsdaten, Kontakt,
 * zentrale Seiten und SEO-Basiswerte.
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hilfsfunktion: Theme-Option aus dem gemeinsamen Options-Array lesen.
 *
 * @param string $key     Options-Schlüssel.
 * @param mixed  $default Standardwert.
 * @return mixed
 */
function ortsverein_nv_get_option( $key, $default = '' ) {
	$options = get_option( 'ortsverein_nv_options', array() );
	if ( ! is_array( $options ) ) {
		$options = array();
	}
	return array_key_exists( $key, $options ) ? $options[ $key ] : $default;
}

/**
 * Options registrieren (Settings API).
 */
function ortsverein_nv_register_theme_options() {
	register_setting(
		'ortsverein_nv_options_group',
		'ortsverein_nv_options',
		'ortsverein_nv_sanitize_theme_options'
	);

	// Sektion: Verein & Organisation.
	add_settings_section(
		'ortsverein_nv_section_org',
		__( 'Verein & Organisation', 'ortsverein-nv' ),
		'ortsverein_nv_section_org_cb',
		'ortsverein_nv_options'
	);

	add_settings_field(
		'org_name',
		__( 'Name des Ortsvereins', 'ortsverein-nv' ),
		'ortsverein_nv_field_org_name_cb',
		'ortsverein_nv_options',
		'ortsverein_nv_section_org'
	);

	add_settings_field(
		'org_tagline',
		__( 'Kurzbeschreibung', 'ortsverein-nv' ),
		'ortsverein_nv_field_org_tagline_cb',
		'ortsverein_nv_options',
		'ortsverein_nv_section_org'
	);

	add_settings_field(
		'org_address',
		__( 'Adresse', 'ortsverein-nv' ),
		'ortsverein_nv_field_org_address_cb',
		'ortsverein_nv_options',
		'ortsverein_nv_section_org'
	);

	// Sektion: Kontakt & Social.
	add_settings_section(
		'ortsverein_nv_section_contact',
		__( 'Kontakt & Online-Auftritt', 'ortsverein-nv' ),
		'ortsverein_nv_section_contact_cb',
		'ortsverein_nv_options'
	);

	add_settings_field(
		'contact_email',
		__( 'E-Mail-Adresse', 'ortsverein-nv' ),
		'ortsverein_nv_field_contact_email_cb',
		'ortsverein_nv_options',
		'ortsverein_nv_section_contact'
	);

	add_settings_field(
		'contact_phone',
		__( 'Telefonnummer', 'ortsverein-nv' ),
		'ortsverein_nv_field_contact_phone_cb',
		'ortsverein_nv_options',
		'ortsverein_nv_section_contact'
	);

	add_settings_field(
		'social_website',
		__( 'Website des Ortsvereins', 'ortsverein-nv' ),
		'ortsverein_nv_field_social_website_cb',
		'ortsverein_nv_options',
		'ortsverein_nv_section_contact'
	);

	add_settings_field(
		'social_awokv',
		__( 'Link zum AWO-Kreisverband / Verbund', 'ortsverein-nv' ),
		'ortsverein_nv_field_social_awokv_cb',
		'ortsverein_nv_options',
		'ortsverein_nv_section_contact'
	);

	// Sektion: Zentrale Seiten.
	add_settings_section(
		'ortsverein_nv_section_pages',
		__( 'Zentrale Seiten', 'ortsverein-nv' ),
		'ortsverein_nv_section_pages_cb',
		'ortsverein_nv_options'
	);

	add_settings_field(
		'page_calendar',
		__( 'Kalenderseite', 'ortsverein-nv' ),
		'ortsverein_nv_field_page_calendar_cb',
		'ortsverein_nv_options',
		'ortsverein_nv_section_pages'
	);

	add_settings_field(
		'page_membership',
		__( 'Mitgliedschaftsseite', 'ortsverein-nv' ),
		'ortsverein_nv_field_page_membership_cb',
		'ortsverein_nv_options',
		'ortsverein_nv_section_pages'
	);

	add_settings_field(
		'page_contact',
		__( 'Kontaktseite', 'ortsverein-nv' ),
		'ortsverein_nv_field_page_contact_cb',
		'ortsverein_nv_options',
		'ortsverein_nv_section_pages'
	);

	add_settings_field(
		'page_begegnung',
		__( 'Seite Begegnungsstätte', 'ortsverein-nv' ),
		'ortsverein_nv_field_page_begegnung_cb',
		'ortsverein_nv_options',
		'ortsverein_nv_section_pages'
	);

	add_settings_field(
		'page_downloads',
		__( 'Downloadseite (optional)', 'ortsverein-nv' ),
		'ortsverein_nv_field_page_downloads_cb',
		'ortsverein_nv_options',
		'ortsverein_nv_section_pages'
	);

	add_settings_field(
		'page_intern',
		__( 'Interner Bereich (optional)', 'ortsverein-nv' ),
		'ortsverein_nv_field_page_intern_cb',
		'ortsverein_nv_options',
		'ortsverein_nv_section_pages'
	);

	// Sektion: SEO-Basisdaten.
	add_settings_section(
		'ortsverein_nv_section_seo',
		__( 'SEO-Basis', 'ortsverein-nv' ),
		'ortsverein_nv_section_seo_cb',
		'ortsverein_nv_options'
	);

	add_settings_field(
		'seo_default_description',
		__( 'Standard-Meta-Description (Fallback)', 'ortsverein-nv' ),
		'ortsverein_nv_field_seo_default_description_cb',
		'ortsverein_nv_options',
		'ortsverein_nv_section_seo'
	);
}
add_action( 'admin_init', 'ortsverein_nv_register_theme_options' );

/**
 * Sanitize-Callback für alle Theme-Options.
 *
 * @param array $input Rohe Eingabewerte.
 * @return array
 */
function ortsverein_nv_sanitize_theme_options( $input ) {
	$output = array();

	// Verein & Organisation.
	$output['org_name']    = isset( $input['org_name'] ) ? sanitize_text_field( $input['org_name'] ) : '';
	$output['org_tagline'] = isset( $input['org_tagline'] ) ? sanitize_textarea_field( $input['org_tagline'] ) : '';

	$street  = isset( $input['org_street'] ) ? sanitize_text_field( $input['org_street'] ) : '';
	$zip     = isset( $input['org_zip'] ) ? sanitize_text_field( $input['org_zip'] ) : '';
	$city    = isset( $input['org_city'] ) ? sanitize_text_field( $input['org_city'] ) : '';
	$country = isset( $input['org_country'] ) ? sanitize_text_field( $input['org_country'] ) : '';

	$output['org_street']  = $street;
	$output['org_zip']     = $zip;
	$output['org_city']    = $city;
	$output['org_country'] = $country;

	// Kontakt & Social.
	$output['contact_email']   = isset( $input['contact_email'] ) ? sanitize_email( $input['contact_email'] ) : '';
	$output['contact_phone']   = isset( $input['contact_phone'] ) ? sanitize_text_field( $input['contact_phone'] ) : '';
	$output['social_website']  = isset( $input['social_website'] ) ? esc_url_raw( $input['social_website'] ) : '';
	$output['social_awokv']    = isset( $input['social_awokv'] ) ? esc_url_raw( $input['social_awokv'] ) : '';

	// Zentrale Seiten (Page-IDs).
	$page_keys = array(
		'page_calendar',
		'page_membership',
		'page_contact',
		'page_begegnung',
		'page_downloads',
		'page_intern',
	);

	foreach ( $page_keys as $key ) {
		$output[ $key ] = isset( $input[ $key ] ) ? absint( $input[ $key ] ) : 0;
	}

	// SEO-Basis.
	$output['seo_default_description'] = isset( $input['seo_default_description'] )
		? sanitize_textarea_field( $input['seo_default_description'] )
		: '';

	return $output;
}

/**
 * Options-Seite im Admin-Menü registrieren.
 */
function ortsverein_nv_add_options_page() {
	add_theme_page(
		__( 'Ortsverein NV – Einstellungen', 'ortsverein-nv' ),
		__( 'Ortsverein NV', 'ortsverein-nv' ),
		'manage_options',
		'ortsverein_nv_options',
		'ortsverein_nv_render_options_page'
	);
}
add_action( 'admin_menu', 'ortsverein_nv_add_options_page' );

/**
 * Callback: Beschreibung Sektion Organisation.
 */
function ortsverein_nv_section_org_cb() {
	echo '<p>' . esc_html__( 'Stammdaten des Ortsvereins. Diese Angaben werden z. B. für strukturierte Daten (Schema.org) und Kontaktinformationen genutzt.', 'ortsverein-nv' ) . '</p>';
}

/**
 * Callback: Beschreibung Sektion Kontakt.
 */
function ortsverein_nv_section_contact_cb() {
	echo '<p>' . esc_html__( 'Basis-Kontaktdaten und Links. Diese helfen Besucherinnen und Besuchern, euch schnell zu erreichen.', 'ortsverein-nv' ) . '</p>';
}

/**
 * Callback: Beschreibung Sektion Seiten.
 */
function ortsverein_nv_section_pages_cb() {
	echo '<p>' . esc_html__( 'Ordne hier die wichtigsten Seiten zu. So bleibt das Theme robust, auch wenn sich Slugs oder Strukturen ändern.', 'ortsverein-nv' ) . '</p>';
}

/**
 * Callback: Beschreibung Sektion SEO.
 */
function ortsverein_nv_section_seo_cb() {
	echo '<p>' . esc_html__( 'Einfache SEO-Basisdaten. Erweiterte SEO-Funktionen können später ergänzt oder über ein SEO-Plugin gesteuert werden.', 'ortsverein-nv' ) . '</p>';
}

/**
 * Feld: Name des Ortsvereins.
 */
function ortsverein_nv_field_org_name_cb() {
	$value = ortsverein_nv_get_option( 'org_name', get_bloginfo( 'name', 'display' ) );
	?>
	<input type="text" class="regular-text" name="ortsverein_nv_options[org_name]" value="<?php echo esc_attr( $value ); ?>">
	<p class="description"><?php esc_html_e( 'Offizieller Name des Ortsvereins, z. B. „AWO Ortsverein Neukirchen-Vluyn“.', 'ortsverein-nv' ); ?></p>
	<?php
}

/**
 * Feld: Kurzbeschreibung.
 */
function ortsverein_nv_field_org_tagline_cb() {
	$value = ortsverein_nv_get_option( 'org_tagline', get_bloginfo( 'description', 'display' ) );
	?>
	<textarea class="large-text" rows="3" name="ortsverein_nv_options[org_tagline]"><?php echo esc_textarea( $value ); ?></textarea>
	<p class="description"><?php esc_html_e( 'Kurze Beschreibung, was ihr macht. Diese kann z. B. als Fallback für Meta-Descriptions dienen.', 'ortsverein-nv' ); ?></p>
	<?php
}

/**
 * Feld: Adresse (Straße, PLZ, Ort, Land).
 */
function ortsverein_nv_field_org_address_cb() {
	$street  = ortsverein_nv_get_option( 'org_street', '' );
	$zip     = ortsverein_nv_get_option( 'org_zip', '' );
	$city    = ortsverein_nv_get_option( 'org_city', '' );
	$country = ortsverein_nv_get_option( 'org_country', 'DE' );
	?>
	<p>
		<label>
			<?php esc_html_e( 'Straße und Hausnummer', 'ortsverein-nv' ); ?><br>
			<input type="text" class="regular-text" name="ortsverein_nv_options[org_street]" value="<?php echo esc_attr( $street ); ?>">
		</label>
	</p>
	<p>
		<label>
			<?php esc_html_e( 'Postleitzahl', 'ortsverein-nv' ); ?><br>
			<input type="text" class="small-text" name="ortsverein_nv_options[org_zip]" value="<?php echo esc_attr( $zip ); ?>">
		</label>
		&nbsp;
		<label>
			<?php esc_html_e( 'Ort', 'ortsverein-nv' ); ?><br>
			<input type="text" class="regular-text" name="ortsverein_nv_options[org_city]" value="<?php echo esc_attr( $city ); ?>">
		</label>
	</p>
	<p>
		<label>
			<?php esc_html_e( 'Länderkürzel (z. B. DE)', 'ortsverein-nv' ); ?><br>
			<input type="text" class="small-text" name="ortsverein_nv_options[org_country]" value="<?php echo esc_attr( $country ); ?>">
		</label>
	</p>
	<p class="description"><?php esc_html_e( 'Diese Angaben werden später u. a. in strukturierten Daten (Schema.org) genutzt.', 'ortsverein-nv' ); ?></p>
	<?php
}

/**
 * Feld: Kontakt E-Mail.
 */
function ortsverein_nv_field_contact_email_cb() {
	$value = ortsverein_nv_get_option( 'contact_email', get_option( 'admin_email' ) );
	?>
	<input type="email" class="regular-text" name="ortsverein_nv_options[contact_email]" value="<?php echo esc_attr( $value ); ?>">
	<p class="description"><?php esc_html_e( 'Allgemeine Kontaktadresse des Ortsvereins.', 'ortsverein-nv' ); ?></p>
	<?php
}

/**
 * Feld: Kontakt Telefon.
 */
function ortsverein_nv_field_contact_phone_cb() {
	$value = ortsverein_nv_get_option( 'contact_phone', '' );
	?>
	<input type="text" class="regular-text" name="ortsverein_nv_options[contact_phone]" value="<?php echo esc_attr( $value ); ?>">
	<p class="description"><?php esc_html_e( 'Telefonnummer für Rückfragen, wie sie öffentlich erscheinen darf.', 'ortsverein-nv' ); ?></p>
	<?php
}

/**
 * Feld: Website des Ortsvereins.
 */
function ortsverein_nv_field_social_website_cb() {
	$default = home_url( '/' );
	$value   = ortsverein_nv_get_option( 'social_website', $default );
	?>
	<input type="url" class="regular-text" name="ortsverein_nv_options[social_website]" value="<?php echo esc_attr( $value ); ?>">
	<p class="description"><?php esc_html_e( 'Öffentliche Website des Ortsvereins (meist diese Seite).', 'ortsverein-nv' ); ?></p>
	<?php
}

/**
 * Feld: Link zum AWO-Kreisverband / Verbund.
 */
function ortsverein_nv_field_social_awokv_cb() {
	$default = 'https://www.awo-kv-wesel.de/';
	$value   = ortsverein_nv_get_option( 'social_awokv', $default );
	?>
	<input type="url" class="regular-text" name="ortsverein_nv_options[social_awokv]" value="<?php echo esc_attr( $value ); ?>">
	<p class="description"><?php esc_html_e( 'Link zur übergeordneten AWO-Struktur (Kreisverband, Bezirksverband o. ä.).', 'ortsverein-nv' ); ?></p>
	<?php
}

/**
 * Hilfsfunktion: Dropdown für Seitenfeld.
 *
 * @param string $key Options-Schlüssel.
 */
function ortsverein_nv_render_page_dropdown( $key ) {
	$current = (int) ortsverein_nv_get_option( $key, 0 );

	wp_dropdown_pages(
		array(
			'name'              => 'ortsverein_nv_options[' . esc_attr( $key ) . ']',
			'echo'              => 1,
			'show_option_none'  => __( 'Keine Seite ausgewählt', 'ortsverein-nv' ),
			'option_none_value' => '0',
			'selected'          => $current,
		)
	);
}

function ortsverein_nv_field_page_calendar_cb() {
	ortsverein_nv_render_page_dropdown( 'page_calendar' );
	echo '<p class="description">' . esc_html__( 'Seite mit dem Kalender-Template. Wird z. B. von Startseiten-Teaser und Schema genutzt.', 'ortsverein-nv' ) . '</p>';
}

function ortsverein_nv_field_page_membership_cb() {
	ortsverein_nv_render_page_dropdown( 'page_membership' );
	echo '<p class="description">' . esc_html__( 'Seite zur Mitgliedschaft (Infos + Online-Beitritt).', 'ortsverein-nv' ) . '</p>';
}

function ortsverein_nv_field_page_contact_cb() {
	ortsverein_nv_render_page_dropdown( 'page_contact' );
	echo '<p class="description">' . esc_html__( 'Kontaktseite ohne komplexes Formular. Kann später durch ein Formular-Plugin ergänzt werden.', 'ortsverein-nv' ) . '</p>';
}

function ortsverein_nv_field_page_begegnung_cb() {
	ortsverein_nv_render_page_dropdown( 'page_begegnung' );
	echo '<p class="description">' . esc_html__( 'Seite für die Begegnungsstätte. Startseiten-Teaser kann hierauf verlinken.', 'ortsverein-nv' ) . '</p>';
}

function ortsverein_nv_field_page_downloads_cb() {
	ortsverein_nv_render_page_dropdown( 'page_downloads' );
	echo '<p class="description">' . esc_html__( 'Optionale Downloadseite (z. B. Satzung, Formulare).', 'ortsverein-nv' ) . '</p>';
}

function ortsverein_nv_field_page_intern_cb() {
	ortsverein_nv_render_page_dropdown( 'page_intern' );
	echo '<p class="description">' . esc_html__( 'Optionaler interner Bereich für Ehrenamtliche.', 'ortsverein-nv' ) . '</p>';
}

/**
 * Feld: SEO-Standardbeschreibung.
 */
function ortsverein_nv_field_seo_default_description_cb() {
	$value = ortsverein_nv_get_option( 'seo_default_description', '' );
	?>
	<textarea class="large-text" rows="3" name="ortsverein_nv_options[seo_default_description]"><?php echo esc_textarea( $value ); ?></textarea>
	<p class="description"><?php esc_html_e( 'Kurze, menschenlesbare Beschreibung des Ortsvereins. Wird später als Fallback-Meta-Description genutzt, wenn sonst nichts passt und kein SEO-Plugin aktiv ist.', 'ortsverein-nv' ); ?></p>
	<?php
}

/**
 * Render-Funktion für die Options-Seite.
 */
function ortsverein_nv_render_options_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Ortsverein NV – Einstellungen', 'ortsverein-nv' ); ?></h1>
		<p><?php esc_html_e( 'Hier verwaltest du die wichtigsten Daten für den Ortsverein. Die meisten Felder haben sinnvolle Vorgaben und lassen sich später jederzeit anpassen.', 'ortsverein-nv' ); ?></p>

		<form action="options.php" method="post">
			<?php
			settings_fields( 'ortsverein_nv_options_group' );
			do_settings_sections( 'ortsverein_nv_options' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

