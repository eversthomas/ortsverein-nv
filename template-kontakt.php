<?php
/**
 * Template Name: Kontakt
 *
 * Optionales Seiten-Template: Editor-Inhalt plus Kontakt-Box aus den Theme-Optionen.
 * Wird nur verwendet, wenn Redakteurinnen und Redakteure es unter „Seitenattribute“ zuweisen.
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ortsverein_nv_address_lines = ortsverein_nv_get_org_address_lines();
$ortsverein_nv_email         = function_exists( 'ortsverein_nv_get_option' ) ? trim( (string) ortsverein_nv_get_option( 'contact_email', '' ) ) : '';
$ortsverein_nv_phone         = function_exists( 'ortsverein_nv_get_option' ) ? trim( (string) ortsverein_nv_get_option( 'contact_phone', '' ) ) : '';
$ortsverein_nv_phone_href    = ortsverein_nv_get_phone_tel_href( $ortsverein_nv_phone );
$ortsverein_nv_website       = function_exists( 'ortsverein_nv_get_option' ) ? trim( (string) ortsverein_nv_get_option( 'social_website', '' ) ) : '';

$ortsverein_nv_has_contact = (
	! empty( $ortsverein_nv_address_lines )
	|| '' !== $ortsverein_nv_email
	|| '' !== $ortsverein_nv_phone
	|| '' !== $ortsverein_nv_website
);

get_header();
?>

<main id="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'template-parts/hero-subpage' ); ?>
		<div class="container section-padding">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>

			<?php if ( $ortsverein_nv_has_contact ) : ?>
				<section class="kontakt-box" aria-labelledby="kontakt-box-heading">
					<h2 id="kontakt-box-heading"><?php esc_html_e( 'Kontaktdaten', 'ortsverein-nv' ); ?></h2>
					<div class="info-box">
						<?php if ( ! empty( $ortsverein_nv_address_lines ) ) : ?>
							<h3 class="kontakt-box-label"><?php esc_html_e( 'Adresse', 'ortsverein-nv' ); ?></h3>
							<address class="kontakt-address">
								<?php echo implode( '<br>', array_map( 'esc_html', $ortsverein_nv_address_lines ) ); ?>
							</address>
						<?php endif; ?>

						<?php if ( '' !== $ortsverein_nv_email || '' !== $ortsverein_nv_phone || '' !== $ortsverein_nv_website ) : ?>
							<ul class="kontakt-list">
								<?php if ( '' !== $ortsverein_nv_email ) : ?>
									<li>
										<span class="kontakt-list-label"><?php esc_html_e( 'E-Mail', 'ortsverein-nv' ); ?></span>
										<a href="<?php echo esc_url( 'mailto:' . $ortsverein_nv_email ); ?>"><?php echo esc_html( $ortsverein_nv_email ); ?></a>
									</li>
								<?php endif; ?>
								<?php if ( '' !== $ortsverein_nv_phone && '' !== $ortsverein_nv_phone_href ) : ?>
									<li>
										<span class="kontakt-list-label"><?php esc_html_e( 'Telefon', 'ortsverein-nv' ); ?></span>
										<a href="<?php echo esc_url( 'tel:' . $ortsverein_nv_phone_href ); ?>"><?php echo esc_html( $ortsverein_nv_phone ); ?></a>
									</li>
								<?php endif; ?>
								<?php if ( '' !== $ortsverein_nv_website ) : ?>
									<li>
										<span class="kontakt-list-label"><?php esc_html_e( 'Website', 'ortsverein-nv' ); ?></span>
										<a href="<?php echo esc_url( $ortsverein_nv_website ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $ortsverein_nv_website ); ?></a>
									</li>
								<?php endif; ?>
							</ul>
						<?php endif; ?>
					</div>
				</section>
			<?php endif; ?>
		</div>
	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
