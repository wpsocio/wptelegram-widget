<?php
/**
 * Plugin settings endpoint for WordPress REST API.
 *
 * @link       https://t.me/manzoorwanijk
 * @since      1.7.0
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/includes
 */

namespace WPTelegram\Widget\includes\restApi;

use WPTelegram\Widget\includes\Utils;
use WPTelegram\BotAPI\API;
use WP_REST_Request;
use WP_REST_Server;

/**
 * Class to handle the settings endpoint.
 *
 * @since 1.7.0
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/includes
 * @author     Manzoor Wani <@manzoorwanijk>
 */
class SettingsController extends RESTController {

	/**
	 * Pattern to match Telegram username.
	 *
	 * @var string Patern.
	 * @since x.y.x
	 */
	const TG_USERNAME_PATTERN = '[a-zA-Z][a-zA-Z0-9_]{3,30}[a-zA-Z0-9]';

	/**
	 * The base of this controller's route.
	 *
	 * @var string
	 */
	const REST_BASE = '/settings';

	/**
	 * The plugin settings/options.
	 *
	 * @var string
	 */
	protected $settings;

	/**
	 * Constructor
	 *
	 * @since 1.7.0
	 */
	public function __construct() {
		$this->settings = WPTG_Widget()->options();
	}

	/**
	 * Register the routes for the objects of the controller.
	 *
	 * @since 1.7.0
	 */
	public function register_routes() {

		register_rest_route(
			self::NAMESPACE,
			self::REST_BASE,
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
	 * @since 1.7.0
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
			unset( $settings['messages'] );
			unset( $settings['last_update_id'] );
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
	 * @since 1.7.0
	 */
	public function get_settings() {
		return rest_ensure_response( self::get_default_settings() );
	}

	/**
	 * Update settings.
	 *
	 * @since 1.7.0
	 *
	 * @param WP_REST_Request $request WP REST API request.
	 */
	public function update_settings( WP_REST_Request $request ) {

		$settings = WPTG_Widget()->options()->get_data();

		foreach ( self::get_settings_params() as $key => $args ) {
			$value = $request->get_param( $key );

			if ( null !== $value || isset( $args['default'] ) ) {

				$settings[ $key ] = null === $value ? $args['default'] : $value;
			}
		}

		WPTG_Widget()->options()->set_data( $settings )->update_data();

		unset( $settings['messages'] );
		unset( $settings['last_update_id'] );

		return rest_ensure_response( $settings );
	}

	/**
	 * Retrieves the query params for the settings.
	 *
	 * @since 1.7.0
	 *
	 * @param string $context The context for the values.
	 * @return array Query parameters for the settings.
	 */
	public static function get_settings_params( $context = 'edit' ) {
		$ajax_widget_props = array(
			'username' => array(
				'type'     => 'string',
				'required' => ( 'edit' === $context ),
				'pattern'  => Utils::enhance_regex(
					self::TG_USERNAME_PATTERN,
					true
				),
			),
			'width'    => array(
				'type'    => 'string',
				'default' => '100%',
			),
			'height'   => array(
				'type'    => 'string',
				'default' => '600px',
			),
		);

		return array(
			'ajax_widget'   => array(
				'type'       => 'object',
				'properties' => $ajax_widget_props,
			),
			'legacy_widget' => array(
				'type'       => 'object',
				'properties' => array_merge(
					$ajax_widget_props,
					array(
						'username'     => array_merge(
							$ajax_widget_props['username'],
							array( 'required' => false )
						),
						'bot_token'    => array(
							'type'    => 'string',
							'pattern' => Utils::enhance_regex(
								API::BOT_TOKEN_PATTERN,
								true
							),
						),
						'author_photo' => array(
							'type'    => 'string',
							'default' => 'auto',
							'enum'    => array( 'auto', 'always_show', 'always_hide' ),
						),
						'num_messages' => array(
							'type'    => 'string',
							'default' => '5',
						),
					)
				),
			),
			'join_link'     => array(
				'type'       => 'object',
				'properties' => array(
					'url'        => array(
						'type'   => 'string',
						'format' => 'uri',
					),
					'text'       => array(
						'type' => 'string',
					),
					'post_types' => array(
						'type'    => 'array',
						'default' => array( 'post' ),
						'items'   => array(
							'type' => 'string',
						),
					),
					'position'   => array(
						'type'    => 'string',
						'default' => 'after_content',
						'enum'    => array( 'before_content', 'after_content' ),
					),
					'priority'   => array(
						'type'    => 'string',
						'default' => '10',
					),
				),
			),
			'advanced'      => array(
				'type'       => 'object',
				'properties' => array(
					'telegram_blocked'  => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'google_script_url' => array(
						'type'   => 'string',
						'format' => 'uri',
					),
				),
			),
		);
	}
}
