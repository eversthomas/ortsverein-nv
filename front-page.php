<?php
/**
 * Front Page – Startseite ortsverein_nv
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main">
	<?php get_template_part( 'template-parts/hero' ); ?>
	<?php get_template_part( 'template-parts/angebot-kacheln' ); ?>
	<?php get_template_part( 'template-parts/begegnung-teaser' ); ?>
	<?php get_template_part( 'template-parts/mitgliedschaft-teaser' ); ?>
	<?php get_template_part( 'template-parts/termine-teaser' ); ?>
</main>

<?php get_footer(); ?>
