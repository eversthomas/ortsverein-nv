<?php
/**
 * Template Part: Kalender-Grid für einen Monat
 * Wird auf Kalenderseite und Startseite verwendet. Tage mit Termin haben data-date für Klick-Hervorhebung.
 *
 * @package Ortsverein_NV
 *
 * @param int    $kal_year      Jahr.
 * @param int    $kal_month_num  Monat 1–12.
 * @param array  $event_tage    Assoziativ: Tag (1–31) => true wenn Termin.
 * @param string $monat_name    Anzeigename des Monats.
 * @param int    $heute_tag     Tag des heutigen Datums (0 wenn anderer Monat).
 * @param int    $tage_im_monat Anzahl Tage im Monat.
 * @param int    $start_offset  Leere Zellen vor dem 1. (Mo=0).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wochentage_kurz = array( __( 'Mo', 'ortsverein-nv' ), __( 'Di', 'ortsverein-nv' ), __( 'Mi', 'ortsverein-nv' ), __( 'Do', 'ortsverein-nv' ), __( 'Fr', 'ortsverein-nv' ), __( 'Sa', 'ortsverein-nv' ), __( 'So', 'ortsverein-nv' ) );
?>
<div class="kalender-grid" role="grid" aria-label="<?php esc_attr_e( 'Monatskalender', 'ortsverein-nv' ); ?>">
	<?php foreach ( $wochentage_kurz as $wd ) : ?>
		<div class="kal-head" aria-hidden="true"><?php echo esc_html( $wd ); ?></div>
	<?php endforeach; ?>
	<?php for ( $i = 0; $i < $args['start_offset']; $i++ ) : ?>
		<div class="kal-day empty" aria-hidden="true"></div>
	<?php endfor; ?>
	<?php for ( $tag = 1; $tag <= $args['tage_im_monat']; $tag++ ) :
		$ist_heute = ( $args['heute_tag'] === $tag );
		$hat_event = ! empty( $args['event_tage'][ $tag ] );
		$cls = 'kal-day';
		if ( $ist_heute ) {
			$cls .= ' today';
		}
		if ( $hat_event ) {
			$cls .= ' has-event';
		}
		$datum_attr = $hat_event ? sprintf( '%04d-%02d-%02d', $args['kal_year'], $args['kal_month_num'], $tag ) : '';
		$label = $tag . '. ' . $args['monat_name'] . ( $hat_event ? ' – ' . __( 'Termin vorhanden', 'ortsverein-nv' ) : '' ) . ( $ist_heute ? ' (' . __( 'heute', 'ortsverein-nv' ) . ')' : '' );
		?>
		<div class="<?php echo esc_attr( $cls ); ?>" role="gridcell" aria-label="<?php echo esc_attr( $label ); ?>" <?php echo $hat_event ? ' data-date="' . esc_attr( $datum_attr ) . '" tabindex="0"' : ''; ?>><?php echo esc_html( (string) $tag ); ?></div>
	<?php endfor; ?>
</div>
