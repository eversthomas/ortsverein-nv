<?php
/**
 * Template Part: Mitgliedschaft-Teaser (Startseite)
 * Gleicher Aufbau wie in der HTML-Vorlage, mit Link zur Unterseite „Mitgliedschaft“.
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ortsverein_nv_mitglied_page = get_page_by_path( 'mitgliedschaft' );
$ortsverein_nv_mitglied_url  = $ortsverein_nv_mitglied_page ? get_permalink( $ortsverein_nv_mitglied_page ) : '';
$ortsverein_nv_kontakt_page  = get_page_by_path( 'kontakt' );
$ortsverein_nv_kontakt_url   = $ortsverein_nv_kontakt_page ? get_permalink( $ortsverein_nv_kontakt_page ) : '';
?>

<section id="mitgliedschaft" class="section-padding" aria-labelledby="mitglied-teaser-h2">
	<div class="container">
		<div class="section-label"><?php esc_html_e( 'Dabei sein', 'ortsverein-nv' ); ?></div>
		<div class="two-col">
			<div>
				<h2 id="mitglied-teaser-h2"><?php esc_html_e( 'Mitglied werden', 'ortsverein-nv' ); ?></h2>
				<p class="mitglied-teaser-lead"><?php esc_html_e( 'Die AWO lebt von Menschen, die mitmachen. Als Mitglied stärkst du nicht nur deinen Ortsverein – du wirst Teil einer solidarischen Gemeinschaft, die füreinander da ist.', 'ortsverein-nv' ); ?></p>
				<h3 class="mitglied-benefits-title"><?php esc_html_e( 'Das bietet die Mitgliedschaft:', 'ortsverein-nv' ); ?></h3>
				<ul class="benefits-list">
					<li><?php esc_html_e( 'Teilnahme an allen Veranstaltungen und Angeboten', 'ortsverein-nv' ); ?></li>
					<li><?php esc_html_e( 'Vergünstigungen bei AWO-Angeboten und Ausflügen', 'ortsverein-nv' ); ?></li>
					<li><?php esc_html_e( 'Mitsprache und Mitgestaltung im Ortsverein', 'ortsverein-nv' ); ?></li>
					<li><?php esc_html_e( 'Zugang zu Beratungs- und Unterstützungsangeboten', 'ortsverein-nv' ); ?></li>
					<li><?php esc_html_e( 'Gemeinschaft und neue Kontakte knüpfen', 'ortsverein-nv' ); ?></li>
					<li><?php esc_html_e( 'Teil einer sozialen Bewegung mit langer Tradition', 'ortsverein-nv' ); ?></li>
				</ul>
				<div class="mitglied-teaser-actions">
					<?php if ( $ortsverein_nv_mitglied_url ) : ?>
						<a href="<?php echo esc_url( $ortsverein_nv_mitglied_url ); ?>" class="btn btn-primary"><?php esc_html_e( 'Mehr zur Mitgliedschaft', 'ortsverein-nv' ); ?></a>
						<a href="<?php echo esc_url( $ortsverein_nv_mitglied_url ); ?>#formular" class="btn btn-secondary btn-sm"><?php esc_html_e( 'Online-Mitgliedschaft beantragen', 'ortsverein-nv' ); ?></a>
					<?php endif; ?>
					<?php if ( $ortsverein_nv_kontakt_url ) : ?>
						<a href="<?php echo esc_url( $ortsverein_nv_kontakt_url ); ?>" class="btn btn-secondary btn-sm"><?php esc_html_e( 'Fragen? Kontakt aufnehmen', 'ortsverein-nv' ); ?></a>
					<?php endif; ?>
				</div>
			</div>
			<div>
				<div class="mitglied-beitrag-box">
					<h3 class="mitglied-beitrag-title"><?php esc_html_e( 'Beitrag', 'ortsverein-nv' ); ?></h3>
					<p class="mitglied-beitrag-hinweis"><?php esc_html_e( 'Platzhalter – bitte aktuellen Beitrag eintragen.', 'ortsverein-nv' ); ?></p>
					<div class="mitglied-beitrag-list">
						<div class="mitglied-beitrag-row">
							<span><?php esc_html_e( 'Regulärer Beitrag', 'ortsverein-nv' ); ?></span>
							<strong>[Betrag] €/<?php esc_html_e( 'Monat', 'ortsverein-nv' ); ?></strong>
						</div>
						<div class="mitglied-beitrag-row">
							<span><?php esc_html_e( 'Ermäßigt', 'ortsverein-nv' ); ?></span>
							<strong>[Betrag] €/<?php esc_html_e( 'Monat', 'ortsverein-nv' ); ?></strong>
						</div>
						<div class="mitglied-beitrag-row">
							<span><?php esc_html_e( 'Familienbeitrag', 'ortsverein-nv' ); ?></span>
							<strong>[Betrag] €/<?php esc_html_e( 'Monat', 'ortsverein-nv' ); ?></strong>
						</div>
					</div>
					<p class="mitglied-beitrag-note">* <?php esc_html_e( 'Beitragsangaben als Platzhalter', 'ortsverein-nv' ); ?></p>
				</div>
			</div>
		</div>
	</div>
</section>
