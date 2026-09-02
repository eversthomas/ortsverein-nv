<?php
/**
 * Meta-Boxen – ortsverein_nv
 * Seiten-Sidebar: Redakteure wählen pro Seite aus, welche der in
 * Design → Widgets („Seiten-Sidebar“) angelegten Widgets dort erscheinen.
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const ORTSVEREIN_NV_SIDEBAR_WIDGETS_META_KEY = '_ortsverein_nv_sidebar_widgets';

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
 * Widget-IDs, die aktuell im Bereich „Seiten-Sidebar“ liegen (Design → Widgets),
 * in ihrer dortigen Reihenfolge. Direkt aus der Rohoption gelesen, unabhängig
 * von Kontext-Filtern.
 *
 * @return string[]
 */
function ortsverein_nv_get_page_sidebar_widget_ids() {
	$sidebars_widgets = get_option( 'sidebars_widgets', array() );
	return isset( $sidebars_widgets['page-sidebar'] ) && is_array( $sidebars_widgets['page-sidebar'] )
		? $sidebars_widgets['page-sidebar']
		: array();
}

/**
 * Sprechendes Label für ein Widget in der Auswahlliste (Titel + Widget-Typ,
 * bzw. nur Widget-Typ, falls kein Titel gepflegt ist).
 *
 * @param string $widget_id z. B. "text-2".
 * @return string
 */
function ortsverein_nv_get_widget_label( $widget_id ) {
	global $wp_registered_widgets;

	$title = '';
	if ( preg_match( '/^(.+)-(\d+)$/', $widget_id, $matches ) ) {
		$instances = get_option( 'widget_' . $matches[1] );
		$number    = (int) $matches[2];
		if ( is_array( $instances ) && ! empty( $instances[ $number ]['title'] ) ) {
			$title = $instances[ $number ]['title'];
		}
	}

	$type_name = isset( $wp_registered_widgets[ $widget_id ]['name'] )
		? $wp_registered_widgets[ $widget_id ]['name']
		: $widget_id;

	return '' !== $title ? sprintf( '%s (%s)', $title, $type_name ) : $type_name;
}

/**
 * Meta-Box-Inhalt ausgeben: Checkliste aller Widgets in der Seiten-Sidebar.
 *
 * @param WP_Post $post Aktueller Seiten-Post.
 */
function ortsverein_nv_render_sidebar_meta_box( $post ) {
	wp_nonce_field( 'ortsverein_nv_save_sidebar_meta', 'ortsverein_nv_sidebar_nonce' );

	$widget_ids = ortsverein_nv_get_page_sidebar_widget_ids();

	if ( empty( $widget_ids ) ) {
		echo '<p class="description">' . esc_html__( 'Noch keine Widgets angelegt. Lege sie unter Design → Widgets im Bereich „Seiten-Sidebar“ an, dann kannst du sie hier für diese Seite auswählen.', 'ortsverein-nv' ) . '</p>';
		return;
	}

	$selected = get_post_meta( $post->ID, ORTSVEREIN_NV_SIDEBAR_WIDGETS_META_KEY, true );
	$selected = is_array( $selected ) ? $selected : array();
	?>
	<p class="description"><?php esc_html_e( 'Wähle aus, welche Widgets auf dieser Seite in der Sidebar erscheinen sollen.', 'ortsverein-nv' ); ?></p>
	<ul class="ortsverein-nv-sidebar-widget-list" style="margin:8px 0 0;padding:0;list-style:none;">
		<?php foreach ( $widget_ids as $widget_id ) : ?>
			<li style="margin-bottom:8px;">
				<label>
					<input type="checkbox" name="ortsverein_nv_sidebar_widgets[]" value="<?php echo esc_attr( $widget_id ); ?>" <?php checked( in_array( $widget_id, $selected, true ) ); ?>>
					<?php echo esc_html( ortsverein_nv_get_widget_label( $widget_id ) ); ?>
				</label>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
}

/**
 * Meta-Box-Wert speichern. Übernimmt nur Widget-IDs, die tatsächlich in der
 * Seiten-Sidebar existieren (schützt vor manipulierten Formularwerten).
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

	if ( empty( $_POST['ortsverein_nv_sidebar_widgets'] ) || ! is_array( $_POST['ortsverein_nv_sidebar_widgets'] ) ) {
		delete_post_meta( $post_id, ORTSVEREIN_NV_SIDEBAR_WIDGETS_META_KEY );
		return;
	}

	$submitted = array_map( 'sanitize_text_field', wp_unslash( $_POST['ortsverein_nv_sidebar_widgets'] ) );
	$available = ortsverein_nv_get_page_sidebar_widget_ids();
	$selected  = array_values( array_intersect( $submitted, $available ) );

	if ( empty( $selected ) ) {
		delete_post_meta( $post_id, ORTSVEREIN_NV_SIDEBAR_WIDGETS_META_KEY );
	} else {
		update_post_meta( $post_id, ORTSVEREIN_NV_SIDEBAR_WIDGETS_META_KEY, $selected );
	}
}
add_action( 'save_post_page', 'ortsverein_nv_save_sidebar_meta' );

/**
 * Für eine Seite ausgewählte, aktuell noch existierende Widget-IDs –
 * in der Reihenfolge, wie sie in der Seiten-Sidebar angeordnet sind.
 *
 * @param int $post_id Seiten-ID.
 * @return string[]
 */
function ortsverein_nv_get_selected_sidebar_widgets( $post_id ) {
	$selected = get_post_meta( $post_id, ORTSVEREIN_NV_SIDEBAR_WIDGETS_META_KEY, true );
	$selected = is_array( $selected ) ? $selected : array();
	if ( empty( $selected ) ) {
		return array();
	}

	$available = ortsverein_nv_get_page_sidebar_widget_ids();
	return array_values( array_intersect( $available, $selected ) );
}

/**
 * Ob eine Seite eine (nicht-leere) Sidebar-Auswahl hat.
 *
 * @param int $post_id Seiten-ID.
 * @return bool
 */
function ortsverein_nv_page_has_sidebar( $post_id ) {
	return ! empty( ortsverein_nv_get_selected_sidebar_widgets( $post_id ) );
}

/**
 * Gibt nur die für diese Seite ausgewählten Widgets aus der Seiten-Sidebar aus.
 *
 * @param string[] $widget_ids Ausgewählte Widget-IDs (siehe ortsverein_nv_get_selected_sidebar_widgets()).
 */
function ortsverein_nv_render_selected_sidebar_widgets( array $widget_ids ) {
	if ( empty( $widget_ids ) ) {
		return;
	}

	$filter = function ( $sidebars_widgets ) use ( $widget_ids ) {
		if ( isset( $sidebars_widgets['page-sidebar'] ) ) {
			$sidebars_widgets['page-sidebar'] = $widget_ids;
		}
		return $sidebars_widgets;
	};

	add_filter( 'sidebars_widgets', $filter, 20 );
	dynamic_sidebar( 'page-sidebar' );
	remove_filter( 'sidebars_widgets', $filter, 20 );
}
