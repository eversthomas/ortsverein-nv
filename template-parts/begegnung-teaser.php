<?php
/**
 * Template Part: Begegnungsstätte-Teaser (Startseite)
 * Gleicher Aufbau wie in der HTML-Vorlage, mit Link zur Unterseite „Begegnung“.
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ortsverein_nv_begegnung_url   = ortsverein_nv_get_page_url_from_option( 'page_begegnung', 'begegnung' );
$ortsverein_nv_address_lines   = ortsverein_nv_get_org_address_lines();
?>

<section id="begegnung" class="section-padding bg-rot-hell" aria-labelledby="begegnung-teaser-h2">
	<div class="container">
		<div class="section-label"><?php esc_html_e( 'Gemeinschaft erleben', 'ortsverein-nv' ); ?></div>
		<div class="two-col">
			<div>
				<h2 id="begegnung-teaser-h2"><?php esc_html_e( 'Unsere Begegnungsstätte', 'ortsverein-nv' ); ?></h2>
				<p class="begegnung-teaser-lead"><?php esc_html_e( 'Die Begegnungsstätte ist das Herzstück unseres Ortsvereins. Hier kommen Menschen zusammen – zum Reden, Spielen, Lachen und zur gegenseitigen Unterstützung. Alle sind willkommen, egal welchen Alters oder welcher Herkunft.', 'ortsverein-nv' ); ?></p>
				<p class="begegnung-teaser-text"><?php esc_html_e( 'Ob du schon seit Jahren dabei bist oder das erste Mal vorbeischaust: Die Tür steht offen. Viele unserer regelmäßigen Gäste sind durch die Begegnungsstätte zu echten Freundschaften gelangt.', 'ortsverein-nv' ); ?></p>
				<div class="info-box begegnung-teaser-address">
					<h4><?php esc_html_e( 'Adresse der Begegnungsstätte', 'ortsverein-nv' ); ?></h4>
					<?php if ( ! empty( $ortsverein_nv_address_lines ) ) : ?>
						<p><?php echo implode( '<br>', array_map( 'esc_html', $ortsverein_nv_address_lines ) ); ?></p>
					<?php else : ?>
						<p><?php esc_html_e( '[Adresse der Begegnungsstätte – wird ergänzt]', 'ortsverein-nv' ); ?></p>
					<?php endif; ?>
				</div>
				<?php if ( $ortsverein_nv_begegnung_url ) : ?>
					<p class="begegnung-teaser-cta">
						<a href="<?php echo esc_url( $ortsverein_nv_begegnung_url ); ?>" class="btn btn-primary"><?php esc_html_e( 'Mehr zur Begegnungsstätte', 'ortsverein-nv' ); ?></a>
					</p>
				<?php endif; ?>
			</div>
			<div>
				<h3 class="begegnung-termine-title"><?php esc_html_e( 'Wöchentliche Standardtermine', 'ortsverein-nv' ); ?></h3>
				<div class="begegnung-termine-box">
					<table class="begegnung-termine-table">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Tag', 'ortsverein-nv' ); ?></th>
								<th><?php esc_html_e( 'Zeit', 'ortsverein-nv' ); ?></th>
								<th><?php esc_html_e( 'Angebot', 'ortsverein-nv' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php esc_html_e( 'Dienstag', 'ortsverein-nv' ); ?></td>
								<td>14:00 <?php esc_html_e( 'Uhr', 'ortsverein-nv' ); ?></td>
								<td><?php esc_html_e( 'Kaffeeklatsch & Gespräch', 'ortsverein-nv' ); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e( 'Mittwoch', 'ortsverein-nv' ); ?></td>
								<td>10:00 <?php esc_html_e( 'Uhr', 'ortsverein-nv' ); ?></td>
								<td><?php esc_html_e( 'Seniorenfrühstück', 'ortsverein-nv' ); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e( 'Donnerstag', 'ortsverein-nv' ); ?></td>
								<td>15:00 <?php esc_html_e( 'Uhr', 'ortsverein-nv' ); ?></td>
								<td><?php esc_html_e( 'Spielenachmittag', 'ortsverein-nv' ); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e( 'Freitag', 'ortsverein-nv' ); ?></td>
								<td>14:00 <?php esc_html_e( 'Uhr', 'ortsverein-nv' ); ?></td>
								<td><?php esc_html_e( 'Offener Treff', 'ortsverein-nv' ); ?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<p class="begegnung-termine-hinweis"><?php esc_html_e( 'Alle Termine findest du auch im Kalender.', 'ortsverein-nv' ); ?></p>
			</div>
		</div>
	</div>
</section>
