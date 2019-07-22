<?php
/**
 * Plugin settings endpoint for WordPress REST API.
 *
 * @link       https://t.me/manzoorwanijk
 * @since      x.y.z
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/includes
 */

/**
 * Class to handle the settings endpoint.
 *
 * @since x.y.z
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/includes
 * @author     Manzoor Wani <@manzoorwanijk>
 */
class WPTelegram_Widget_Settings_Controller extends WPTelegram_Widget_REST_Controller {

	/**
	 * The plugin settings/options.
	 *
	 * @var string
	 */
	protected $settings;

	/**
	 * Constructor
	 *
	 * @since x.y.z
	 */
	public function __construct() {
		$this->rest_base = 'settings';
		$this->settings  = WPTG_Widget()->options();
	}

	/**
	 * Register the routes for the objects of the controller.
	 *
	 * @since x.y.z
	 */
	public function register_routes() {

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_settings' ),
					'permission_callback' => array( $this, 'settings_permissions' ),
					'args'                => self::get_settings_params( 'view' ),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'update_settings' ),
					'permission_callback' => array( $this, 'settings_permissions' ),
					'args'                => self::get_settings_params( 'edit' ),
				),
			)
		);
	}

	/**
	 * Check request permissions.
	 *
	 * @since x.y.z
	 *
	 * @return bool
	 */
	public function settings_permissions() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Get the default settings.
	 *
	 * @return array
	 */
	public static function get_default_settings() {

		$settings = WPTG_Widget()->options()->get_data();

		// If we have somethings saved.
		if ( ! empty( $settings ) ) {
			return $settings;
		}

		// Get the default values.
		$settings = self::get_settings_params();

		foreach ( $settings as $key => $args ) {
			$settings[ $key ] = isset( $args['default'] ) ? $args['default'] : '';
		}

		return $settings;
	}

	/**
	 * Get settings via API.
	 *
	 * @since x.y.z
	 */
	public function get_settings() {
		return rest_ensure_response( self::get_default_settings() );
	}

	/**
	 * Update settings.
	 *
	 * @since x.y.z
	 *
	 * @param WP_REST_Request $request WP REST API request.
	 */
	public function update_settings( WP_REST_Request $request ) {

		$settings = array();

		foreach ( self::get_settings_params() as $key => $args ) {
			$value = $request->get_param( $key );

			if ( null !== $value || isset( $args['default'] ) ) {

				$settings[ $key ] = null === $value ? $args['default'] : $value;
			}
		}

		WPTG_Widget()->options()->set_data( $settings )->update_data();

		return rest_ensure_response( $settings );
	}

	/**
	 * Retrieves the query params for the settings.
	 *
	 * @since x.y.z
	 *
	 * @param string $context The context for the values.
	 * @return array Query parameters for the settings.
	 */
	public static function get_settings_params( $context = 'edit' ) {
		return array(
			'username'          => array(
				'type'              => 'string',
				'required'          => ( 'edit' === $context ),
				'sanitize_callback' => 'sanitize_text_field',
				'validate_callback' => array( __CLASS__, 'validate_param' ),
			),
			'widget_width'      => array(
				'type'              => 'string',
				'default'           => '100%',
				'sanitize_callback' => 'sanitize_text_field',
				'validate_callback' => 'rest_validate_request_arg',
			),
			'widget_height'     => array(
				'type'              => 'string',
				'default'           => '600',
				'sanitize_callback' => 'sanitize_text_field',
				'validate_callback' => 'rest_validate_request_arg',
			),
			'bot_token'         => array(
				'type'              => 'string',
				'required'          => ( 'edit' === $context ),
				'sanitize_callback' => 'sanitize_text_field',
				'validate_callback' => array( __CLASS__, 'validate_param' ),
			),
			'author_photo'      => array(
				'type'              => 'string',
				'default'           => 'auto',
				'sanitize_callback' => 'sanitize_text_field',
				'validate_callback' => 'rest_validate_request_arg',
				'enum'              => array( 'auto', 'always_show', 'always_hide' ),
			),
			'num_messages'      => array(
				'type'              => 'string',
				'default'           => '5',
				'sanitize_callback' => 'sanitize_text_field',
				'validate_callback' => 'rest_validate_request_arg',
			),
			'telegram_blocked'  => array(
				'type'              => 'string',
				'default'           => 'no',
				'sanitize_callback' => 'sanitize_text_field',
				'validate_callback' => 'rest_validate_request_arg',
				'enum'              => array( 'yes', 'no' ),
			),
			'google_script_url' => array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'validate_callback' => 'rest_validate_request_arg',
			),
		);
	}

	/**
	 * Update settings.
	 *
	 * @since x.y.z
	 *
	 * @param mixed           $value   Value of the param.
	 * @param WP_REST_Request $request WP REST API request.
	 * @param string          $key     Param key.
	 */
	public static function validate_param( $value, WP_REST_Request $request, $key ) {
		switch ( $key ) {
			case 'bot_token':
				$pattern = '/\A\d{9}:[\w-]{35}\Z/';
				break;
			case 'username':
				$pattern = '/\A[a-z]\w{3,30}[^\W_]\Z/i';
				break;
		}

		return (bool) preg_match( $pattern, $value );
	}
}
