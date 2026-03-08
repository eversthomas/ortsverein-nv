<?php
/**
 * Template Part: Reduzierter Hero für Unterseiten
 * Breadcrumb + H1, einheitlich für alle Seiten.
 *
 * @package Ortsverein_NV
 *
 * @param string $title Optional. Überschrift (sonst get_the_title()).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ortsverein_nv_hero_title = isset( $args['title'] ) ? $args['title'] : get_the_title();
?>
<section class="hero-subpage section-padding-sm" aria-labelledby="hero-subpage-title">
	<div class="container">
		<?php get_template_part( 'template-parts/breadcrumb' ); ?>
		<h1 id="hero-subpage-title" class="hero-subpage-h1"><?php echo esc_html( $ortsverein_nv_hero_title ); ?></h1>
	</div>
</section>
