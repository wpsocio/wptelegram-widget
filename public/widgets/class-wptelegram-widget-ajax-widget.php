<?php
/**
 * The ajax widget.
 *
 * @link       https://t.me/manzoorwanijk
 * @since      1.6.0
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/public
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
/**
 * Adds WP Telegram Widget widget.
 */
class WPTelegram_Widget_Ajax_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'wptelegram_widget_ajax_widget',
			esc_html__( 'WP Telegram Ajax Widget', 'wptelegram-widget' ),
			array(
				'description' => esc_html__( 'Display the Telegram Public Channel Feed in an ajax widget with infinite scroll.', 'wptelegram-widget' ),
			)
		);
	}

	/**
	 * Outputs the content for the widget.
	 *
	 * @since 1.6.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Pages widget instance.
	 */
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';

		/**
		 * Filters the widget title.
		 *
		 * @since 1.6.0
		 *
		 * @param string $title    The widget title. Default 'Pages'.
		 * @param array  $instance Array of settings for the current widget.
		 * @param mixed  $id_base  The widget ID.
		 */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		unset( $instance['title'] );

		$content = wptelegram_ajax_widget( $instance, false );

		if ( ! empty( $content ) ) {
			echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput
			} ?>
			<div>
				<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
			<?php
				echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput
		}
	}

	/**
	 * Handles updating settings for the widget instance.
	 *
	 * @since 1.6.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		$instance['widget_width'] = sanitize_text_field( $new_instance['widget_width'] );
		if ( ! empty( $instance['widget_width'] ) ) {
			$instance['widget_width'] = $instance['widget_width'];
		}

		$instance['widget_height'] = sanitize_text_field( $new_instance['widget_height'] );
		if ( ! empty( $instance['widget_height'] ) ) {
			$instance['widget_height'] = absint( $instance['widget_height'] );
		}

		return $instance;
	}

	/**
	 * Outputs the settings form for the widget.
	 *
	 * @since 1.6.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {

		$defaults = array(
			'title'         => '',
			'widget_width'  => '100%',
			'widget_height' => 600,
		);

		// use global options.
		foreach ( $defaults as $key => $value ) {
			$defaults[ $key ] = WPTG_Widget()->options()->get( $key, $value );
		}
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'wptelegram-widget' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'widget_width' ) ); ?>"><?php esc_html_e( 'Widget Width', 'wptelegram-widget' ); ?></label>
			<input type="text" value="<?php echo esc_attr( $instance['widget_width'] ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_width' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'widget_width' ) ); ?>" class="widefat" placeholder="300 <?php esc_html_e( 'or', 'wptelegram-widget' ); ?> 100%" />
			<br />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'widget_height' ) ); ?>"><?php esc_html_e( 'Widget Height', 'wptelegram-widget' ); ?></label>
			<input type="text" value="<?php echo esc_attr( $instance['widget_height'] ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_height' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'widget_height' ) ); ?>" class="widefat" placeholder="600" />
			<br />
		</p>
		<?php
	}
}
