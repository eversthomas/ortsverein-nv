<?php
/**
 * Template Part: Angebotskacheln (Startseite)
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ortsverein_nv_home = ortsverein_nv_get_home_url();
?>
<section id="angebote" class="section-padding" aria-labelledby="angebote-h2">
	<div class="container">
<?php

$ortsverein_nv_kacheln = array(
	array(
		'slug'  => 'begegnung',
		'title' => __( 'Begegnung', 'ortsverein-nv' ),
		'desc'  => __( 'Unsere Begegnungsstätte ist ein offener Ort zum Treffen, Austauschen und Wohlfühlen – für alle Generationen.', 'ortsverein-nv' ),
		'cta'   => __( 'Mehr erfahren', 'ortsverein-nv' ),
		'accent' => '#e2001a',
		'icon_bg' => '#fdf0f1',
	),
	array(
		'slug'  => 'mitgliedschaft',
		'title' => __( 'Mitgliedschaft', 'ortsverein-nv' ),
		'desc'  => __( 'Werde Teil unserer Gemeinschaft. Als Mitglied gestaltest du Soziales aktiv mit und unterstützt wichtige Arbeit vor Ort.', 'ortsverein-nv' ),
		'cta'   => __( 'Mehr erfahren', 'ortsverein-nv' ),
		'accent' => '#e2001a',
		'icon_bg' => '#fdf0f1',
	),
	array(
		'slug'  => 'beratung',
		'title' => __( 'Beratung', 'ortsverein-nv' ),
		'desc'  => __( 'Du hast Fragen zu sozialen Themen? Unser Büro steht dir für persönliche Beratung und Unterstützung offen.', 'ortsverein-nv' ),
		'cta'   => __( 'Mehr erfahren', 'ortsverein-nv' ),
		'accent' => '#009ee9',
		'icon_bg' => '#e8f6fd',
	),
	array(
		'slug'  => 'termine',
		'title' => __( 'Termine', 'ortsverein-nv' ),
		'desc'  => __( 'Unser Programm ist bunt und abwechslungsreich. Ausflüge, Vorträge, Feste, Gruppen – schau in den Kalender!', 'ortsverein-nv' ),
		'cta'   => __( 'Mehr erfahren', 'ortsverein-nv' ),
		'accent' => '#ec7405',
		'icon_bg' => '#fef5ec',
	),
);

get_template_part( 'template-parts/section-header', null, array(
	'label'   => __( 'Unsere Angebote', 'ortsverein-nv' ),
	'title'   => __( 'Was wir für dich tun können', 'ortsverein-nv' ),
	'content' => __( 'Von Begegnung über Beratung bis Mitmachen – hier findest du, was dich beschäftigt.', 'ortsverein-nv' ),
	'id'      => 'angebote-h2',
) );
?>

<div class="card-grid">
	<?php foreach ( $ortsverein_nv_kacheln as $k ) : ?>
		<?php
		$ortsverein_nv_page = get_page_by_path( $k['slug'] );
		$ortsverein_nv_url  = $ortsverein_nv_page ? get_permalink( $ortsverein_nv_page ) : $ortsverein_nv_home;
		$ortsverein_nv_class = isset( $k['css_class'] ) ? ' ' . esc_attr( $k['css_class'] ) : '';
		?>
		<a href="<?php echo esc_url( $ortsverein_nv_url ); ?>" class="card fade-in<?php echo $ortsverein_nv_class; ?>" style="--card-accent: <?php echo esc_attr( $k['accent'] ); ?>; --card-icon-bg: <?php echo esc_attr( $k['icon_bg'] ); ?>;">
			<div class="card-icon-wrap">
				<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="<?php echo esc_attr( $k['accent'] ); ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
			</div>
			<h3 class="card-title"><?php echo esc_html( $k['title'] ); ?></h3>
			<p class="card-desc"><?php echo esc_html( $k['desc'] ); ?></p>
			<span class="card-cta"><?php echo esc_html( $k['cta'] ); ?> <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></span>
		</a>
	<?php endforeach; ?>
</div>
	</div>
</section>
