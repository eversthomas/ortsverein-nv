<?php
/**
 * Template: Mitgliedschaft
 * Editor-Inhalt + darunter eingebettetes Online-Mitgliedschaftsformular (AWO Bundesverband).
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ortsverein_nv_mitgliedschaft_form_url = 'https://antrag.awo-zmav.de/AwoOnline';

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
		</div>

		<section id="formular" class="mitgliedschaft-form-section section-padding bg-grau" aria-labelledby="mitgliedschaft-form-heading">
			<div class="container">
				<h2 id="mitgliedschaft-form-heading" class="mitgliedschaft-form-title"><?php esc_html_e( 'Online-Mitgliedschaft beantragen', 'ortsverein-nv' ); ?></h2>
				<p class="mitgliedschaft-form-intro"><?php esc_html_e( 'Über das Formular des AWO-Bundesverbands kannst du dich direkt online zur Mitgliedschaft anmelden.', 'ortsverein-nv' ); ?></p>
				<div class="mitgliedschaft-form-wrapper">
					<iframe
						class="mitgliedschaft-form-embed"
						src="<?php echo esc_url( $ortsverein_nv_mitgliedschaft_form_url ); ?>"
						title="<?php esc_attr_e( 'AWO Online-Mitgliedschaftsantrag', 'ortsverein-nv' ); ?>"
						loading="lazy"
					></iframe>
				</div>
			</div>
		</section>
	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
