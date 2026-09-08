<?php
/**
 * Template Part: Termine auf der Startseite
 * Link zur Kalenderseite, aktueller Monat (Grid + Liste) im bestehenden Design.
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bs_ics_shortcode = ortsverein_nv_kalender_plugin_shortcode( 'home' );
$kalender_url     = ortsverein_nv_get_page_url_from_option( 'page_calendar', 'kalender' );

if ( ! $bs_ics_shortcode ) :
	$heute         = new DateTime( 'now', new DateTimeZone( 'Europe/Berlin' ) );
	$kal_year      = (int) $heute->format( 'Y' );
	$kal_month_num = (int) $heute->format( 'n' );
	$monate        = ortsverein_nv_get_month_names();
	$monat_name    = isset( $monate[ $kal_month_num ] ) ? $monate[ $kal_month_num ] : '';

	$heute_start = new DateTime( $heute->format( 'Y-m-d' ) . ' 00:00:00', new DateTimeZone( 'Europe/Berlin' ) );
	$termine = ortsverein_nv_get_events_for_month( $kal_year, $kal_month_num );
	$termine = array_values( array_filter( $termine, function ( $ev ) use ( $heute_start ) {
		return $ev['start'] >= $heute_start;
	} ) );
	$event_tage = array();
	foreach ( $termine as $ev ) {
		$event_tage[ (int) $ev['start']->format( 'j' ) ] = true;
	}
	$heute_tag = (int) $heute->format( 'j' );
	$tage_im_monat = (int) gmdate( 't', strtotime( $kal_year . '-' . $kal_month_num . '-01' ) );
	$erster = new DateTime( $kal_year . '-' . $kal_month_num . '-01', new DateTimeZone( 'Europe/Berlin' ) );
	$start_offset = (int) $erster->format( 'N' ) - 1; // Mo=0

	/** Auf Desktop: erste Termine neben Kalender, Rest darunter in 2 Spalten. */
	$termine_oben_max = 5;
	$termine_oben = array_slice( $termine, 0, $termine_oben_max );
	$termine_unten = array_slice( $termine, $termine_oben_max );
endif;
?>

<section class="section-padding" id="termine" aria-labelledby="termine-teaser-h2">
	<div class="container">
		<?php get_template_part( 'template-parts/section-header', null, array(
			'label'   => __( 'Termine', 'ortsverein-nv' ),
			'title'   => __( 'Nächste Termine', 'ortsverein-nv' ),
			'id'      => 'termine-teaser-h2',
			'content' => __( 'Aktuelle Veranstaltungen des Ortsvereins. Alle Termine findest du im Kalender.', 'ortsverein-nv' ),
		) ); ?>

		<?php if ( $kalender_url ) : ?>
			<p class="termine-link-wrap termine-link-above">
				<a href="<?php echo esc_url( $kalender_url ); ?>" class="btn btn-secondary"><?php esc_html_e( 'Zum Kalender – alle Monate', 'ortsverein-nv' ); ?></a>
			</p>
		<?php endif; ?>

		<?php if ( $bs_ics_shortcode ) : ?>
			<div class="kalender-layout kalender-teaser kalender-plugin-embed">
				<?php echo do_shortcode( $bs_ics_shortcode ); ?>
			</div>
		<?php else : ?>
			<div class="kalender-layout kalender-teaser">
				<div class="kalender-grid-wrapper">
					<div class="kalender-box">
						<h3 class="kalender-nav-title kalender-teaser-title"><?php echo esc_html( $monat_name . ' ' . $kal_year ); ?></h3>
						<?php
						get_template_part( 'template-parts/kalender-grid', null, array(
							'kal_year'      => $kal_year,
							'kal_month_num' => $kal_month_num,
							'event_tage'    => $event_tage,
							'monat_name'    => $monat_name,
							'heute_tag'     => $heute_tag,
							'tage_im_monat' => $tage_im_monat,
							'start_offset'  => $start_offset,
						) );
						?>
						<div class="kalender-legend">
							<span class="kalender-legend-item">
								<span class="kalender-legend-dot kalender-legend-today" aria-hidden="true"></span>
								<?php esc_html_e( 'Heute', 'ortsverein-nv' ); ?>
							</span>
							<span class="kalender-legend-item">
								<span class="kalender-legend-dot kalender-legend-event" aria-hidden="true"></span>
								<?php esc_html_e( 'Termin', 'ortsverein-nv' ); ?>
							</span>
							<span class="kalender-legend-item">
								<span class="kalender-legend-dot kalender-legend-weekend" aria-hidden="true"></span>
								<?php esc_html_e( 'Wochenende', 'ortsverein-nv' ); ?>
							</span>
							<span class="kalender-legend-item">
								<span class="kalender-legend-dot kalender-legend-holiday" aria-hidden="true"></span>
								<?php esc_html_e( 'Feiertag', 'ortsverein-nv' ); ?>
							</span>
						</div>
					</div>
				</div>
				<div class="kalender-termine">
					<h3 class="kalender-termine-title"><?php printf( esc_html__( 'Termine im %s', 'ortsverein-nv' ), esc_html( $monat_name ) ); ?></h3>
					<div class="termine-liste termine-liste-oben">
						<?php if ( empty( $termine ) ) : ?>
							<p class="kalender-keine-termine"><?php esc_html_e( 'Keine Termine in diesem Monat eingetragen.', 'ortsverein-nv' ); ?></p>
						<?php else : ?>
							<?php foreach ( $termine_oben as $ev ) : ?>
								<?php get_template_part( 'template-parts/termin-item', null, $ev ); ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<?php if ( ! empty( $termine_unten ) ) : ?>
				<div class="kalender-termine-unten" aria-labelledby="termine-unten-title">
					<h3 id="termine-unten-title" class="screen-reader-text"><?php printf( esc_html__( 'Weitere Termine im %s', 'ortsverein-nv' ), esc_html( $monat_name ) ); ?></h3>
					<div class="termine-liste termine-liste-unten">
						<?php foreach ( $termine_unten as $ev ) : ?>
							<?php get_template_part( 'template-parts/termin-item', null, $ev ); ?>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( $kalender_url ) : ?>
			<p class="termine-link-wrap">
				<a href="<?php echo esc_url( $kalender_url ); ?>" class="btn btn-secondary"><?php esc_html_e( 'Alle Termine im Kalender', 'ortsverein-nv' ); ?></a>
			</p>
		<?php endif; ?>
	</div>
</section>
