<?php
/**
 * Meta-Boxen – ortsverein_nv
 * Seiten-Sidebar: Redakteure entscheiden pro Seite, ob die Widget-Sidebar erscheint.
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const ORTSVEREIN_NV_SIDEBAR_META_KEY = '_ortsverein_nv_show_sidebar';

/**
 * Meta-Box im Seiten-Editor registrieren.
 */
function ortsverein_nv_register_sidebar_meta_box() {
	add_meta_box(
		'ortsverein_nv_sidebar',
		__( 'Seiten-Sidebar', 'ortsverein-nv' ),
		'ortsverein_nv_render_sidebar_meta_box',
		'page',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'ortsverein_nv_register_sidebar_meta_box' );

/**
 * Meta-Box-Inhalt ausgeben.
 *
 * @param WP_Post $post Aktueller Seiten-Post.
 */
function ortsverein_nv_render_sidebar_meta_box( $post ) {
	wp_nonce_field( 'ortsverein_nv_save_sidebar_meta', 'ortsverein_nv_sidebar_nonce' );

	$show_sidebar = '1' === get_post_meta( $post->ID, ORTSVEREIN_NV_SIDEBAR_META_KEY, true );
	?>
	<p>
		<label>
			<input type="checkbox" name="ortsverein_nv_show_sidebar" value="1" <?php checked( $show_sidebar ); ?>>
			<?php esc_html_e( 'Sidebar mit Widgets anzeigen', 'ortsverein-nv' ); ?>
		</label>
	</p>
	<p class="description">
		<?php esc_html_e( 'Aktiviert: Der Seiteninhalt teilt sich die Breite mit den Widgets aus Design → Widgets („Seiten-Sidebar“). Deaktiviert: Der Inhalt nutzt die volle Breite.', 'ortsverein-nv' ); ?>
	</p>
	<?php
}

/**
 * Meta-Box-Wert speichern.
 *
 * @param int $post_id Post-ID.
 */
function ortsverein_nv_save_sidebar_meta( $post_id ) {
	if ( ! isset( $_POST['ortsverein_nv_sidebar_nonce'] ) ||
		! wp_verify_nonce( wp_unslash( $_POST['ortsverein_nv_sidebar_nonce'] ), 'ortsverein_nv_save_sidebar_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}

	if ( ! empty( $_POST['ortsverein_nv_show_sidebar'] ) ) {
		update_post_meta( $post_id, ORTSVEREIN_NV_SIDEBAR_META_KEY, '1' );
	} else {
		delete_post_meta( $post_id, ORTSVEREIN_NV_SIDEBAR_META_KEY );
	}
}
add_action( 'save_post_page', 'ortsverein_nv_save_sidebar_meta' );

/**
 * Prüft, ob für eine Seite die Sidebar aktiviert ist und der Sidebar-Bereich Inhalt hat.
 *
 * @param int $post_id Seiten-ID.
 * @return bool
 */
function ortsverein_nv_page_has_sidebar( $post_id ) {
	$enabled = '1' === get_post_meta( $post_id, ORTSVEREIN_NV_SIDEBAR_META_KEY, true );
	return $enabled && is_active_sidebar( 'page-sidebar' );
}
