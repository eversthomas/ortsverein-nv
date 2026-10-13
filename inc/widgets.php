<?php
/**
 * Theme-Widgets – ortsverein_nv
 *
 * @package Ortsverein_NV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget: Social-Media-Icons (Links aus den Theme-Optionen).
 */
class Ortsverein_NV_Social_Links_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'ortsverein_nv_social_links',
			__( 'Social-Media-Links', 'ortsverein-nv' ),
			array(
				'description' => __( 'Zeigt die unter Design → Ortsverein NV hinterlegten Facebook- und Instagram-Icons.', 'ortsverein-nv' ),
			)
		);
	}

	/**
	 * Frontend-Ausgabe.
	 *
	 * @param array $args     Widget-Wrapper.
	 * @param array $instance Gespeicherte Einstellungen.
	 */
	public function widget( $args, $instance ) {
		if ( ! function_exists( 'ortsverein_nv_get_social_links' ) || empty( ortsverein_nv_get_social_links() ) ) {
			return;
		}

		$title = isset( $instance['title'] ) ? $instance['title'] : __( 'Social Media', 'ortsverein-nv' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		if ( is_string( $title ) && '' !== $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		ortsverein_nv_render_social_links( 'widget' );
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Widget-Formular im Backend.
	 *
	 * @param array $instance Gespeicherte Einstellungen.
	 */
	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : __( 'Social Media', 'ortsverein-nv' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Titel:', 'ortsverein-nv' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p class="description">
			<?php esc_html_e( 'Die Links selbst pflegst du unter Design → Ortsverein NV (Facebook und Instagram). Ohne hinterlegte Adresse erscheint das Widget nicht.', 'ortsverein-nv' ); ?>
		</p>
		<?php
	}

	/**
	 * Speichern.
	 *
	 * @param array $new_instance Neue Werte.
	 * @param array $old_instance Vorherige Werte.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		return $instance;
	}
}

/**
 * Theme-Widgets registrieren.
 */
function ortsverein_nv_register_widgets() {
	register_widget( 'Ortsverein_NV_Social_Links_Widget' );
}
add_action( 'widgets_init', 'ortsverein_nv_register_widgets' );
