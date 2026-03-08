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
		<div class="container section-padding">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
		</div>
	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
