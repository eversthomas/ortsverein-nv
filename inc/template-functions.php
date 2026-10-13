<?php
/**
 * Template-Hilfsfunktionen – ortsverein_nv
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gibt die Home-URL für Breadcrumb/Links zurück (escaped).
 *
 * @return string
 */
function ortsverein_nv_get_home_url() {
	return esc_url( home_url( '/' ) );
}

/**
 * Prüft, ob die aktuelle Seite die Startseite ist.
 *
 * @return bool
 */
function ortsverein_nv_is_front_page() {
	return is_front_page();
}

/**
 * Holt eine Seiten-ID aus den Theme-Options mit optionalem Slug-Fallback.
 *
 * @param string $option_key   Schlüssel in ortsverein_nv_options (z. B. page_calendar).
 * @param string $fallback_slug Optionaler Slug für Fallback via get_page_by_path().
 * @return int Page-ID oder 0.
 */
function ortsverein_nv_get_page_id_from_option( $option_key, $fallback_slug = '' ) {
	$page_id = 0;

	if ( function_exists( 'ortsverein_nv_get_option' ) ) {
		$opt = (int) ortsverein_nv_get_option( $option_key, 0 );
		if ( $opt > 0 && get_post_status( $opt ) ) {
			$page_id = $opt;
		}
	}

	if ( 0 === $page_id && $fallback_slug !== '' ) {
		$page = get_page_by_path( $fallback_slug );
		if ( $page instanceof WP_Post ) {
			$page_id = (int) $page->ID;
		}
	}

	return $page_id;
}

/**
 * Holt eine Seiten-URL aus den Theme-Options mit optionalem Slug-Fallback.
 *
 * @param string $option_key   Schlüssel in ortsverein_nv_options.
 * @param string $fallback_slug Optionaler Slug für Fallback.
 * @return string URL oder leerer String.
 */
function ortsverein_nv_get_page_url_from_option( $option_key, $fallback_slug = '' ) {
	$page_id = ortsverein_nv_get_page_id_from_option( $option_key, $fallback_slug );
	return $page_id > 0 ? get_permalink( $page_id ) : '';
}

/**
 * Adresszeilen des Ortsvereins aus den Theme-Optionen.
 * Leer, wenn Straße, PLZ und Ort nicht gepflegt sind.
 *
 * @return string[] Nicht-leere Zeilen, z. B. ["Musterstraße 1", "12345 Musterstadt"].
 */
function ortsverein_nv_get_org_address_lines() {
	if ( ! function_exists( 'ortsverein_nv_get_option' ) ) {
		return array();
	}

	$street = trim( (string) ortsverein_nv_get_option( 'org_street', '' ) );
	$zip    = trim( (string) ortsverein_nv_get_option( 'org_zip', '' ) );
	$city   = trim( (string) ortsverein_nv_get_option( 'org_city', '' ) );

	$lines = array();
	if ( '' !== $street ) {
		$lines[] = $street;
	}

	$zip_city = trim( $zip . ' ' . $city );
	if ( '' !== $zip_city ) {
		$lines[] = $zip_city;
	}

	return $lines;
}

/**
 * Bereitet eine Telefonnummer für tel:-Links vor (Ziffern und optionales +).
 *
 * @param string $phone Öffentlich angezeigte Nummer.
 * @return string
 */
function ortsverein_nv_get_phone_tel_href( $phone ) {
	$phone = trim( (string) $phone );
	if ( '' === $phone ) {
		return '';
	}

	$href = preg_replace( '/[^\d+]/', '', $phone );
	return is_string( $href ) ? $href : '';
}

/**
 * Gepflegte Social-Media-Links (nur Netzwerke mit gesetzter URL).
 *
 * @return array[] Liste mit keys network, label, url.
 */
function ortsverein_nv_get_social_links() {
	if ( ! function_exists( 'ortsverein_nv_get_option' ) ) {
		return array();
	}

	$networks = array(
		'facebook'  => __( 'Facebook', 'ortsverein-nv' ),
		'instagram' => __( 'Instagram', 'ortsverein-nv' ),
	);

	$links = array();
	foreach ( $networks as $network => $label ) {
		$url = trim( (string) ortsverein_nv_get_option( 'social_' . $network, '' ) );
		if ( '' === $url ) {
			continue;
		}
		$links[] = array(
			'network' => $network,
			'label'   => $label,
			'url'     => $url,
		);
	}

	return $links;
}

/**
 * SVG-Icon für ein Social-Netzwerk (dekorativ, aria-hidden).
 *
 * @param string $network facebook|instagram.
 * @return string
 */
function ortsverein_nv_get_social_icon_svg( $network ) {
	if ( 'facebook' === $network ) {
		return '<svg class="social-link-icon" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M22 12.07C22 6.48 17.52 2 11.93 2S1.86 6.48 1.86 12.07c0 5.02 3.66 9.18 8.44 9.93v-7.03H7.9v-2.9h2.4V9.84c0-2.38 1.42-3.69 3.6-3.69 1.04 0 2.13.18 2.13.18v2.35h-1.2c-1.18 0-1.55.73-1.55 1.48v1.78h2.64l-.42 2.9h-2.22V22c4.78-.75 8.44-4.91 8.44-9.93z"/></svg>';
	}

	if ( 'instagram' === $network ) {
		return '<svg class="social-link-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><rect x="3.25" y="3.25" width="17.5" height="17.5" rx="5" stroke="currentColor" stroke-width="1.75"/><circle cx="12" cy="12" r="4.15" stroke="currentColor" stroke-width="1.75"/><circle cx="17.35" cy="6.65" r="1.15" fill="currentColor"/></svg>';
	}

	return '';
}

/**
 * Gibt die Social-Icons als Navigation aus (Footer oder Widget).
 *
 * @param string $context footer|widget.
 */
function ortsverein_nv_render_social_links( $context = 'footer' ) {
	$links = ortsverein_nv_get_social_links();
	if ( empty( $links ) ) {
		return;
	}

	$context = in_array( $context, array( 'footer', 'widget' ), true ) ? $context : 'footer';
	?>
	<nav class="social-links social-links--<?php echo esc_attr( $context ); ?>" aria-label="<?php esc_attr_e( 'Soziale Netzwerke', 'ortsverein-nv' ); ?>">
		<ul class="social-links-list">
			<?php foreach ( $links as $link ) : ?>
				<li>
					<a
						href="<?php echo esc_url( $link['url'] ); ?>"
						class="social-link social-link--<?php echo esc_attr( $link['network'] ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						aria-label="<?php echo esc_attr( sprintf( __( '%s (öffnet in neuem Tab)', 'ortsverein-nv' ), $link['label'] ) ); ?>"
					>
						<?php echo ortsverein_nv_get_social_icon_svg( $link['network'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- festes SVG. ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php
}
