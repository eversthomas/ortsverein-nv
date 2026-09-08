<?php
/**
 * Template: Kalender (Seiten-Slug: kalender)
 * Monatsansicht + Terminliste aus ICS, bestehendes Design.
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bs_ics_shortcode = ortsverein_nv_kalender_plugin_shortcode( 'page' );

if ( ! $bs_ics_shortcode ) :
	$kal_month = isset( $_GET['kal_month'] ) ? sanitize_text_field( wp_unslash( $_GET['kal_month'] ) ) : '';
	if ( ! preg_match( '/^\d{4}-\d{2}$/', $kal_month ) ) {
		$kal_month = gmdate( 'Y-m' );
	}
	$parts = explode( '-', $kal_month );
	$kal_year      = (int) $parts[0];
	$kal_month_num = (int) $parts[1];
	$monate        = ortsverein_nv_get_month_names();
	$monat_name    = isset( $monate[ $kal_month_num ] ) ? $monate[ $kal_month_num ] : $kal_month;

	$heute = new DateTime( 'now', new DateTimeZone( 'Europe/Berlin' ) );
	$heute_start = new DateTime( $heute->format( 'Y-m-d' ) . ' 00:00:00', new DateTimeZone( 'Europe/Berlin' ) );

	$termine = ortsverein_nv_get_events_for_month( $kal_year, $kal_month_num );
	$termine = array_values( array_filter( $termine, function ( $ev ) use ( $heute_start ) {
		return $ev['start'] >= $heute_start;
	} ) );
	$event_tage = array();
	foreach ( $termine as $ev ) {
		$event_tage[ (int) $ev['start']->format( 'j' ) ] = true;
	}

	$ist_aktueller_monat = ( (int) $heute->format( 'Y' ) === $kal_year && (int) $heute->format( 'n' ) === $kal_month_num );
	$heute_tag = $ist_aktueller_monat ? (int) $heute->format( 'j' ) : 0;

	$tage_im_monat = (int) gmdate( 't', strtotime( $kal_year . '-' . $kal_month_num . '-01' ) );
	$erster = new DateTime( $kal_year . '-' . $kal_month_num . '-01', new DateTimeZone( 'Europe/Berlin' ) );
	$erster_wochentag = (int) $erster->format( 'N' ); // 1=Mo .. 7=So
	$start_offset = $erster_wochentag - 1; // Mo=0

	$prev = new DateTime( $kal_year . '-' . $kal_month_num . '-01', new DateTimeZone( 'Europe/Berlin' ) );
	$prev->modify( '-1 month' );
	$next = new DateTime( $kal_year . '-' . $kal_month_num . '-01', new DateTimeZone( 'Europe/Berlin' ) );
	$next->modify( '+1 month' );
	$prev_param = $prev->format( 'Y-m' );
	$next_param = $next->format( 'Y-m' );
	$kalender_url = get_permalink();
	$kalender_url = add_query_arg( 'kal_month', '%s', $kalender_url ) . '#kalender-h2';
endif;

get_header();
?>

<main id="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'template-parts/hero-subpage' ); ?>
		<section class="section-padding kalender-section bg-grau" aria-labelledby="kalender-h2">
			<div class="container">
				<h2 id="kalender-h2" class="screen-reader-text"><?php esc_html_e( 'Kalender', 'ortsverein-nv' ); ?></h2>

				<?php
				// html_entity_decode() sorgt dafür, dass ein vom Classic Editor hinterlassener
				// leerer Absatz wie "<p>&nbsp;</p>" ebenfalls als leer erkannt wird (nach
				// strip_tags bliebe sonst der reine Text "&nbsp;" übrig, den trim() nicht entfernt).
				$kalender_content_text = trim( str_replace( "\xc2\xa0", '', wp_strip_all_tags( html_entity_decode( get_the_content(), ENT_QUOTES, 'UTF-8' ) ) ) );
				if ( '' !== $kalender_content_text ) :
					?>
					<div class="entry-content kalender-entry-content">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>

				<?php if ( $bs_ics_shortcode ) : ?>
					<div class="kalender-layout kalender-plugin-embed">
						<?php echo do_shortcode( $bs_ics_shortcode ); ?>
					</div>
				<?php else : ?>
					<div class="kalender-layout">
						<div class="kalender-grid-wrapper">
							<div class="kalender-box">
								<nav class="kalender-nav" aria-label="<?php esc_attr_e( 'Monat wechseln', 'ortsverein-nv' ); ?>">
									<a href="<?php echo esc_url( sprintf( $kalender_url, $prev_param ) ); ?>" class="kalender-nav-btn" aria-label="<?php esc_attr_e( 'Vorheriger Monat', 'ortsverein-nv' ); ?>">‹</a>
									<h3 class="kalender-nav-title"><?php echo esc_html( $monat_name . ' ' . $kal_year ); ?></h3>
									<a href="<?php echo esc_url( sprintf( $kalender_url, $next_param ) ); ?>" class="kalender-nav-btn" aria-label="<?php esc_attr_e( 'Nächster Monat', 'ortsverein-nv' ); ?>">›</a>
								</nav>
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
							<div class="termine-liste" id="kal-termine-liste">
								<?php if ( empty( $termine ) ) : ?>
									<p class="kalender-keine-termine"><?php esc_html_e( 'Keine Termine in diesem Monat eingetragen.', 'ortsverein-nv' ); ?></p>
								<?php else : ?>
									<?php foreach ( $termine as $ev ) : ?>
										<?php get_template_part( 'template-parts/termin-item', null, $ev ); ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<?php get_template_part( 'template-parts/back-to-top' ); ?>
			</div>
		</section>
	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
