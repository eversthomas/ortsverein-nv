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

$kal_month = isset( $_GET['kal_month'] ) ? sanitize_text_field( wp_unslash( $_GET['kal_month'] ) ) : '';
if ( ! preg_match( '/^\d{4}-\d{2}$/', $kal_month ) ) {
	$kal_month = gmdate( 'Y-m' );
}
$parts = explode( '-', $kal_month );
$kal_year = (int) $parts[0];
$kal_month_num = (int) $parts[1];
$monate = $GLOBALS['ortsverein_nv_monate'];
$monat_name = isset( $monate[ $kal_month_num ] ) ? $monate[ $kal_month_num ] : $kal_month;

$termine = ortsverein_nv_get_events_for_month( $kal_year, $kal_month_num );
$event_tage = array();
foreach ( $termine as $ev ) {
	$event_tage[ (int) $ev['start']->format( 'j' ) ] = true;
}

$heute = new DateTime( 'now', new DateTimeZone( 'Europe/Berlin' ) );
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
$kalender_url = add_query_arg( 'kal_month', '%s', $kalender_url );

get_header();
?>

<main id="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'template-parts/hero-subpage' ); ?>
		<section class="section-padding bg-grau" aria-labelledby="kalender-h2">
			<div class="container">
				<div class="section-label"><?php esc_html_e( 'Veranstaltungskalender', 'ortsverein-nv' ); ?></div>
				<h2 id="kalender-h2" class="screen-reader-text"><?php esc_html_e( 'Kalender', 'ortsverein-nv' ); ?></h2>
				<p class="kalender-intro"><?php esc_html_e( 'Alle Termine des Ortsvereins auf einen Blick. Tage mit Veranstaltungen sind markiert.', 'ortsverein-nv' ); ?></p>

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

				<?php get_template_part( 'template-parts/back-to-top' ); ?>
			</div>
		</section>
	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
