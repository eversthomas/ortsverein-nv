<?php
/**
 * Template Part: Hero (Startseite)
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ortsverein_nv_home_url = ortsverein_nv_get_home_url();

$ortsverein_nv_default_lead = __( 'Wir sind der AWO Ortsverein Neukirchen-Vluyn – ein Ort für Begegnung, gegenseitige Unterstützung und gelebte Gemeinschaft. Komm einfach vorbei.', 'ortsverein-nv' );

$ortsverein_nv_hero_eyebrow = get_theme_mod( 'ortsverein_nv_hero_eyebrow', __( 'Neukirchen-Vluyn', 'ortsverein-nv' ) );
if ( $ortsverein_nv_hero_eyebrow === '' ) {
	$ortsverein_nv_hero_eyebrow = __( 'Neukirchen-Vluyn', 'ortsverein-nv' );
}

$ortsverein_nv_hero_title = get_theme_mod( 'ortsverein_nv_hero_title', __( 'Schön, dass du da bist.', 'ortsverein-nv' ) );
if ( $ortsverein_nv_hero_title === '' ) {
	$ortsverein_nv_hero_title = __( 'Schön, dass du da bist.', 'ortsverein-nv' );
}

$ortsverein_nv_hero_lead = get_theme_mod( 'ortsverein_nv_hero_lead', $ortsverein_nv_default_lead );
if ( $ortsverein_nv_hero_lead === '' ) {
	$ortsverein_nv_hero_lead = $ortsverein_nv_default_lead;
}

$ortsverein_nv_hero_btn_primary = get_theme_mod( 'ortsverein_nv_hero_btn_primary', __( 'Termine & Programm', 'ortsverein-nv' ) );
if ( $ortsverein_nv_hero_btn_primary === '' ) {
	$ortsverein_nv_hero_btn_primary = __( 'Termine & Programm', 'ortsverein-nv' );
}

$ortsverein_nv_hero_btn_secondary = get_theme_mod( 'ortsverein_nv_hero_btn_secondary', __( 'Mitglied werden', 'ortsverein-nv' ) );
if ( $ortsverein_nv_hero_btn_secondary === '' ) {
	$ortsverein_nv_hero_btn_secondary = __( 'Mitglied werden', 'ortsverein-nv' );
}

// Button-URLs: Customizer-Seitenauswahl, sonst Fallback über Slug
$ortsverein_nv_btn_primary_page_id   = (int) get_theme_mod( 'ortsverein_nv_hero_btn_primary_page', 0 );
$ortsverein_nv_btn_secondary_page_id = (int) get_theme_mod( 'ortsverein_nv_hero_btn_secondary_page', 0 );

$ortsverein_nv_termine_url = $ortsverein_nv_home_url;
if ( $ortsverein_nv_btn_primary_page_id > 0 ) {
	$ortsverein_nv_p = get_permalink( $ortsverein_nv_btn_primary_page_id );
	if ( $ortsverein_nv_p ) {
		$ortsverein_nv_termine_url = $ortsverein_nv_p;
	}
} else {
	$ortsverein_nv_termine_page = get_page_by_path( 'termine' );
	if ( $ortsverein_nv_termine_page ) {
		$ortsverein_nv_termine_url = get_permalink( $ortsverein_nv_termine_page );
	}
}

$ortsverein_nv_mitglied_url = $ortsverein_nv_home_url;
if ( $ortsverein_nv_btn_secondary_page_id > 0 ) {
	$ortsverein_nv_p = get_permalink( $ortsverein_nv_btn_secondary_page_id );
	if ( $ortsverein_nv_p ) {
		$ortsverein_nv_mitglied_url = $ortsverein_nv_p;
	}
} else {
	$ortsverein_nv_mitglied_page = get_page_by_path( 'mitgliedschaft' );
	if ( $ortsverein_nv_mitglied_page ) {
		$ortsverein_nv_mitglied_url = get_permalink( $ortsverein_nv_mitglied_page );
	}
}
?>
<section id="hero" aria-labelledby="hero-h1">
	<div class="hero-bg-shape" aria-hidden="true"></div>
	<div class="hero-bg-shape2" aria-hidden="true"></div>
	<div class="hero-inner">
		<div class="hero-content fade-in visible">
			<div class="hero-eyebrow" aria-hidden="true">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
				<?php echo esc_html( $ortsverein_nv_hero_eyebrow ); ?>
			</div>
			<h1 id="hero-h1" class="hero-h1"><?php echo esc_html( $ortsverein_nv_hero_title ); ?></h1>
			<p class="hero-lead"><?php echo esc_html( $ortsverein_nv_hero_lead ); ?></p>
			<div class="hero-actions">
				<a href="<?php echo esc_url( $ortsverein_nv_termine_url ); ?>" class="btn btn-primary">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
					<?php echo esc_html( $ortsverein_nv_hero_btn_primary ); ?>
				</a>
				<a href="<?php echo esc_url( $ortsverein_nv_mitglied_url ); ?>" class="btn btn-secondary">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
					<?php echo esc_html( $ortsverein_nv_hero_btn_secondary ); ?>
				</a>
			</div>
		</div>
		<div class="hero-visual" aria-hidden="true">
			<div class="hero-card-stack">
				<?php
				$ortsverein_nv_hero_cards = array(
					array(
						'num'   => get_theme_mod( 'ortsverein_nv_hero_card1_num', __( '200+', 'ortsverein-nv' ) ),
						'label' => get_theme_mod( 'ortsverein_nv_hero_card1_label', __( 'Mitglieder', 'ortsverein-nv' ) ),
						'icon'  => 'rot',
						'svg'   => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
						'stroke' => '#e2001a',
					),
					array(
						'num'   => get_theme_mod( 'ortsverein_nv_hero_card2_num', __( '10+', 'ortsverein-nv' ) ),
						'label' => get_theme_mod( 'ortsverein_nv_hero_card2_label', __( 'Angebote monatlich', 'ortsverein-nv' ) ),
						'icon'  => 'gelb',
						'svg'   => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
						'stroke' => '#c89400',
					),
					array(
						'num'   => get_theme_mod( 'ortsverein_nv_hero_card3_num', __( 'Seit 1952', 'ortsverein-nv' ) ),
						'label' => get_theme_mod( 'ortsverein_nv_hero_card3_label', __( 'für Neukirchen-Vluyn', 'ortsverein-nv' ) ),
						'icon'  => 'blau',
						'svg'   => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
						'stroke' => '#009ee9',
					),
				);
				foreach ( $ortsverein_nv_hero_cards as $card ) :
					if ( $card['num'] === '' && $card['label'] === '' ) {
						continue;
					}
					?>
				<div class="hero-stat-card">
					<div class="stat-icon <?php echo esc_attr( $card['icon'] ); ?>">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="<?php echo esc_attr( $card['stroke'] ); ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?php echo $card['svg']; // SVG-Pfade aus Theme, kein User-Input ?></svg>
					</div>
					<div class="stat-text">
						<div class="num"><?php echo esc_html( $card['num'] ); ?></div>
						<div class="label"><?php echo esc_html( $card['label'] ); ?></div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
