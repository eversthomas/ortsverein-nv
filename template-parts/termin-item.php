<?php
/**
 * Template Part: Einzelner Termin (aus ICS)
 * Nutzt das bestehende Design: .termin-item, .termin-datum, .termin-info, .termin-uhrzeit
 *
 * @package Ortsverein_NV
 *
 * @param array $args Ein Termin: start (DateTime), end (DateTime), title, location.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $args['start'] ) || ! ( $args['start'] instanceof DateTime ) ) {
	return;
}

$start = $args['start'];
$title = isset( $args['title'] ) ? $args['title'] : __( 'Termin', 'ortsverein-nv' );
$location = isset( $args['location'] ) ? $args['location'] : '';
$tag = $start->format( 'd' );
$mon_short = ortsverein_nv_ics_month_short( $start );
$wochentag = ortsverein_nv_ics_weekday( $start );
$monat = isset( $GLOBALS['ortsverein_nv_monate'][ (int) $start->format( 'n' ) ] ) ? $GLOBALS['ortsverein_nv_monate'][ (int) $start->format( 'n' ) ] : $start->format( 'F' );
$datumzeile = $wochentag . ', ' . $start->format( 'j' ) . '. ' . $monat . ' ' . $start->format( 'Y' );
$uhrzeit = $start->format( 'H:i' ) . ' Uhr';
$datum_ymd = $start->format( 'Y-m-d' );
?>
<div class="termin-item" data-date="<?php echo esc_attr( $datum_ymd ); ?>">
	<div class="termin-datum" aria-hidden="true">
		<span class="termin-tag"><?php echo esc_html( $tag ); ?></span>
		<span class="termin-monat"><?php echo esc_html( $mon_short ); ?></span>
	</div>
	<div class="termin-info">
		<div class="termin-wochentag"><?php echo esc_html( $datumzeile ); ?></div>
		<div class="termin-titel"><?php echo esc_html( $title ); ?></div>
		<?php if ( $location !== '' ) : ?>
			<div class="termin-ort">
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
				<?php echo esc_html( $location ); ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="termin-uhrzeit"><?php echo esc_html( $uhrzeit ); ?></div>
</div>
