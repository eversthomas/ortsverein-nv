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
		<?php $ortsverein_nv_has_sidebar = ortsverein_nv_page_has_sidebar( get_the_ID() ); ?>
		<div class="container section-padding">
			<div class="page-layout<?php echo $ortsverein_nv_has_sidebar ? ' page-layout-with-sidebar' : ''; ?>">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</article>
				<?php if ( $ortsverein_nv_has_sidebar ) : ?>
					<aside class="page-sidebar" aria-label="<?php esc_attr_e( 'Seitenleiste', 'ortsverein-nv' ); ?>">
						<?php dynamic_sidebar( 'page-sidebar' ); ?>
					</aside>
				<?php endif; ?>
			</div>
		</div>
	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
