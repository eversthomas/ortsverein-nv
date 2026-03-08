<?php
/**
 * Template Part: Section Header (Label + H2 + optionaler Text)
 *
 * @package Ortsverein_NV
 *
 * @param string $label   Optional. Section-Label (z. B. "Unsere Angebote").
 * @param string $title   Optional. H2-Überschrift.
 * @param string $content Optional. Beschreibungstext unter dem H2.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ortsverein_nv_label   = isset( $args['label'] ) ? $args['label'] : '';
$ortsverein_nv_title   = isset( $args['title'] ) ? $args['title'] : '';
$ortsverein_nv_content = isset( $args['content'] ) ? $args['content'] : '';
$ortsverein_nv_id      = isset( $args['id'] ) ? $args['id'] : '';
?>
<div class="section-header fade-in">
	<?php if ( $ortsverein_nv_label ) : ?>
		<div class="section-label"><?php echo esc_html( $ortsverein_nv_label ); ?></div>
	<?php endif; ?>
	<?php if ( $ortsverein_nv_title ) : ?>
		<h2<?php echo $ortsverein_nv_id ? ' id="' . esc_attr( $ortsverein_nv_id ) . '"' : ''; ?>><?php echo esc_html( $ortsverein_nv_title ); ?></h2>
	<?php endif; ?>
	<?php if ( $ortsverein_nv_content ) : ?>
		<p><?php echo esc_html( $ortsverein_nv_content ); ?></p>
	<?php endif; ?>
</div>
