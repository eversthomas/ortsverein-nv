<?php
/**
 * Page – Standard-Seiten ortsverein_nv
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'template-parts/hero-subpage' ); ?>
		<?php $ortsverein_nv_sidebar_widgets = ortsverein_nv_get_selected_sidebar_widgets( get_the_ID() ); ?>
		<div class="container section-padding">
			<div class="page-layout<?php echo ! empty( $ortsverein_nv_sidebar_widgets ) ? ' page-layout-with-sidebar' : ''; ?>">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</article>
				<?php if ( ! empty( $ortsverein_nv_sidebar_widgets ) ) : ?>
					<aside class="page-sidebar" aria-label="<?php esc_attr_e( 'Seitenleiste', 'ortsverein-nv' ); ?>">
						<?php ortsverein_nv_render_selected_sidebar_widgets( $ortsverein_nv_sidebar_widgets ); ?>
					</aside>
				<?php endif; ?>
			</div>
		</div>
	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
