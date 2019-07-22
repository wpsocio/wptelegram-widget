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
class WPTelegram_Widget_Bot_API_Controller extends WPTelegram_Widget_REST_Controller {

	/**
	 * Constructor
	 *
	 * @since x.y.z
	 */
	public function __construct() {
		$this->rest_base = 'bot-api';
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
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'handle_test' ),
					'permission_callback' => array( $this, 'permissions_for_test' ),
					'args'                => self::get_test_params(),
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
	public function permissions_for_test() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Handle the test request for settings tests.
	 *
	 * @since x.y.z
	 *
	 * @param WP_REST_Request $request WP REST API request.
	 */
	public function handle_test( WP_REST_Request $request ) {

		$bot_token  = $request->get_param( 'bot_token' );
		$api_method = $request->get_param( 'api_method' );
		$api_params = $request->get_param( 'api_params' );

		$body = array();
		$code = 200;

		$bot_api = new WPTelegram_Bot_API( $bot_token );

		if ( empty( $api_params ) ) {
			$api_params = array();
		}

		$res = call_user_func( array( $bot_api, $api_method ), $api_params );

		if ( is_wp_error( $res ) ) {

			$body = array(
				'ok'          => false,
				'error_code'  => 500,
				'description' => $res->get_error_code() . ' - ' . $res->get_error_message(),
			);
			$code = $body['error_code'];

		} else {

			$body = $res->get_decoded_body();
			$code = $res->get_response_code();
		}

		return new WP_REST_Response( $body, $code );
	}

	/**
	 * Retrieves the query params for the settings.
	 *
	 * @since x.y.z
	 *
	 * @return array Query parameters for the settings.
	 */
	public static function get_test_params() {
		return array(
			'bot_token'  => array(
				'type'              => 'string',
				'required'          => true,
				'sanitize_callback' => 'sanitize_text_field',
				'validate_callback' => array( __CLASS__, 'validate_param' ),
			),
			'api_method' => array(
				'type'              => 'string',
				'required'          => true,
				'sanitize_callback' => 'sanitize_text_field',
				'validate_callback' => 'rest_validate_request_arg',
			),
			'api_params' => array(
				'required'          => true,
				'type'              => 'object',
				'sanitize_callback' => array( __CLASS__, 'sanitize_param' ),
				'validate_callback' => 'rest_validate_request_arg',
			),
		);
	}

	/**
	 * Validate params.
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
		}

		return (bool) preg_match( $pattern, $value );
	}

	/**
	 * Sanitize params.
	 *
	 * @since x.y.z
	 *
	 * @param mixed $input Value of the param.
	 */
	public static function sanitize_param( $input ) {
		if ( is_array( $input ) ) {

			foreach ( $input as $key => $value ) {

				$input[ sanitize_text_field( $key ) ] = self::sanitize_param( $value );
			}
		} else {
			$input = sanitize_text_field( $input );
		}
		return $input;
	}
}
