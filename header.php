<?php
/**
 * Header – ortsverein_nv
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ortsverein_nv_home_url = ortsverein_nv_get_home_url();
$ortsverein_nv_logo_url = get_template_directory_uri() . '/assets/images/logo.svg';
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Zum Inhalt springen', 'ortsverein-nv' ); ?></a>

<header id="site-header" role="banner">
	<div class="header-inner">
		<a href="<?php echo esc_url( $ortsverein_nv_home_url ); ?>" class="brand" aria-label="<?php esc_attr_e( 'AWO Ortsverein Neukirchen-Vluyn – Startseite', 'ortsverein-nv' ); ?>">
			<img src="<?php echo esc_url( $ortsverein_nv_logo_url ); ?>" alt="<?php esc_attr_e( 'AWO Ortsverein Neukirchen-Vluyn', 'ortsverein-nv' ); ?>" width="128" height="60" class="brand-icon" decoding="async">
			<span class="brand-text">
				<span class="awo"><?php esc_html_e( 'AWO Team', 'ortsverein-nv' ); ?></span>
				<span class="ov"><?php esc_html_e( 'Neukirchen-Vluyn', 'ortsverein-nv' ); ?></span>
			</span>
		</a>

		<?php if ( has_nav_menu( 'primary' ) ) : ?>
			<nav class="main-nav" aria-label="<?php esc_attr_e( 'Hauptnavigation', 'ortsverein-nv' ); ?>">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'main-nav-list',
					'fallback_cb'    => false,
					'depth'          => 1,
				) );
				?>
			</nav>
			<?php if ( has_nav_menu( 'service' ) ) : ?>
				<div class="nav-divider" aria-hidden="true"></div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( has_nav_menu( 'service' ) ) : ?>
			<div class="service-links" aria-label="<?php esc_attr_e( 'Schnellzugriff', 'ortsverein-nv' ); ?>">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'service',
					'container'      => false,
					'menu_class'     => 'service-links-list',
					'fallback_cb'    => false,
					'depth'          => 1,
				) );
				?>
			</div>
		<?php endif; ?>

		<button type="button" class="burger-btn" aria-expanded="false" aria-controls="mobile-nav" aria-label="<?php esc_attr_e( 'Menü öffnen', 'ortsverein-nv' ); ?>">
			<span></span><span></span><span></span>
		</button>
	</div>
</header>

<?php if ( has_nav_menu( 'primary' ) || has_nav_menu( 'service' ) ) : ?>
<nav class="mobile-nav" id="mobile-nav" aria-label="<?php esc_attr_e( 'Mobile Navigation', 'ortsverein-nv' ); ?>" aria-hidden="true" hidden>
	<?php if ( has_nav_menu( 'primary' ) ) : ?>
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'mobile-nav-list', 'fallback_cb' => false, 'depth' => 1 ) ); ?>
	<?php endif; ?>
	<?php if ( has_nav_menu( 'primary' ) && has_nav_menu( 'service' ) ) : ?>
		<div class="mobile-divider" aria-hidden="true"></div>
		<div class="service-title" aria-hidden="true"><?php esc_html_e( 'Schnellzugriff', 'ortsverein-nv' ); ?></div>
	<?php endif; ?>
	<?php if ( has_nav_menu( 'service' ) ) : ?>
		<?php wp_nav_menu( array( 'theme_location' => 'service', 'container' => false, 'menu_class' => 'mobile-nav-list mobile-service-list', 'fallback_cb' => false, 'depth' => 1 ) ); ?>
	<?php endif; ?>
</nav>
<?php endif; ?>
