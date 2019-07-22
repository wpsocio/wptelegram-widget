<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://t.me/manzoorwanijk
 * @since      1.0.0
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two hooks to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/admin
 * @author     Manzoor Wani 
 */
class WPTelegram_Widget_Admin {

	/**
	 * Title of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $title    Title of the plugin
	 */
	protected $title;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The suffix to be used for JS and CSS files
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $suffix    The suffix to be used for JS and CSS files
	 */
	private $suffix;

	/**
	 * Messages WP_List_Table object
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public $list_table;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since	1.0.0
	 * @param 	string    $title		Title of the plugin
	 * @param	string    $plugin_name	The name of the plugin.
	 * @param	string    $version		The version of this plugin.
	 */
	public function __construct( $title, $plugin_name, $version ) {

		$this->title       = $title;
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Use minified libraries if SCRIPT_DEBUG is turned off
		$this->suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_styles( $hook_suffix ) {
		wp_enqueue_style(
			$this->plugin_name,
			WPTELEGRAM_WIDGET_URL . '/admin/css/wptelegram-widget-admin' . $this->suffix . '.css',
			array(),
			$this->version,
			'all'
		);

		// Load only on settings page.
		if ( $this->is_settings_page( $hook_suffix ) ) {
			wp_enqueue_style( $this->plugin_name . '-bootstrap', WPTELEGRAM_WIDGET_URL . '/admin/css/bootstrap/bootstrap' . $this->suffix . '.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_scripts( $hook_suffix ) {

		wp_enqueue_script(
			$this->plugin_name,
			WPTELEGRAM_WIDGET_URL . '/admin/js/wptelegram-widget-admin' . $this->suffix . '.js',
			array( 'jquery' ),
			$this->version,
			false
		);

		// script localization.
		$translation_array = array(
			'title'   => $this->title,
			'name'    => $this->plugin_name,
			'version' => $this->version,
			'api'     => array(
				'ajax' => array(
					'nonce' => wp_create_nonce( 'wptelegram-widget' ),
					'use'   => 'server', // or may be 'browser'?
					'url'   => admin_url( 'admin-ajax.php' ),
				),
				'rest' => array(
					'nonce' => wp_create_nonce( 'wp_rest' ),
					'url'   => esc_url_raw( rest_url( 'wptelegram-widget/v1' ) ),
				),
			),
		);

		wp_localize_script(
			$this->plugin_name,
			'wptelegram_widget',
			$translation_array
		);

		// Load only on settings page.
		if ( $this->is_settings_page( $hook_suffix ) ) {

			wp_enqueue_script( $this->plugin_name . '-settings', WPTELEGRAM_WIDGET_URL . '/admin/settings/settings-build' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );

			// Pass data to JS.
			$data = array(
				'settings' => array(
					'info'       => array(
						'use' => 'server', // or 'browser'.
					),
					'saved_opts' => current_user_can( 'manage_options' ) ? WPTelegram_Widget_Settings_Controller::get_default_settings() : array(), // Not to expose bot token to non-admins.
					'assets'     => array(
						'pull_updates_url' => add_query_arg( array( 'action' => 'wptelegram_widget_pull_updates' ), site_url() ),
						'admin_url'        => untrailingslashit( admin_url() ),
						'logo_url'         => WPTELEGRAM_WIDGET_URL . '/admin/icons/icon-100x100.svg',
						'tg_icon'          => WPTELEGRAM_WIDGET_URL . '/admin/icons/tg-icon.svg',
					),
					'i18n'       => wptelegram_get_jed_locale_data( 'wptelegram-widget' ),
				),
			);

			wp_add_inline_script(
				$this->plugin_name,
				sprintf( 'Object.assign(wptelegram_widget, %s);', json_encode( $data ) ),
				'before'
			);

			// For Facebook like button.
			wp_add_inline_script(
				$this->plugin_name . '-settings',
				'(function(d, s, id) {'
				. '  var js, fjs = d.getElementsByTagName(s)[0];'
				. '  if (d.getElementById(id)) return;'
				. '  js = d.createElement(s); js.id = id;'
				. '  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.9";'
				. '  fjs.parentNode.insertBefore(js, fjs);'
				. '}(document, "script", "facebook-jssdk"));',
				'after'
			);

			// For Twitter Follow button.
			wp_enqueue_script( $this->plugin_name . '-twitter', 'https://platform.twitter.com/widgets.js', array(), $this->version, true );
		}

		// If the block editor assets are loaded.
		if ( did_action( 'enqueue_block_editor_assets' ) ) {
			$data = array(
				'blocks' => array(
					'assets' => array(
						'message_view_url' => WPTelegram_Widget_Public::get_message_view_url( '%username%', '%message_id%', '%userpic%' ),
					),
				),
			);

			wp_add_inline_script(
				$this->plugin_name,
				sprintf( 'Object.assign(wptelegram_widget, %s);', json_encode( $data ) ),
				'before'
			);
		}
	}

	/**
	 * Format the Twitter script.
	 *
	 * @since 1.7.0
	 *
	 * @param string $tag    The `<script>` tag for the enqueued script.
	 * @param string $handle The script's registered handle.
	 * @param string $src    The script's source URL.
	 *
	 * @return string
	 */
	public function format_twitter_script_tag( $tag, $handle, $src ) {
		if ( $this->plugin_name . '-twitter' !== $handle ) {
			return $tag;
		}
		return '<script async src="' . $src . '" charset="utf-8"></script>' . PHP_EOL;
	}

	/**
	 * Enqueue assets for the Gutenberg block
	 *
	 * @since 1.7.0
	 * @param string $hook_suffix The current admin page.
	 */
	public function is_settings_page( $hook_suffix ) {
		return ( current_user_can( 'manage_options' ) && false !== strpos( $hook_suffix, '_page_' . $this->plugin_name ) );
	}

	/**
	 * Enqueue assets for the Gutenberg block.
	 *
	 * @since 1.5.0
	 */
	public function enqueue_block_editor_assets() {

		wp_enqueue_script(
			$this->plugin_name . '-block',
			WPTELEGRAM_WIDGET_URL . '/admin/blocks/blocks-build' . $this->suffix . '.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
			$this->version,
			true
		);

		wp_enqueue_style(
			$this->plugin_name . '-block',
			WPTELEGRAM_WIDGET_URL . '/admin/blocks/blocks-build' . $this->suffix . '.css',
			array( 'wp-edit-blocks' ),
			$this->version
		);
	}

	/**
	 * Register WP REST API routes.
	 *
	 * @since 1.7.0
	 */
	public function register_rest_routes() {
		$controller = new WPTelegram_Widget_Settings_Controller();
		$controller->register_routes();
		$controller = new WPTelegram_Widget_Bot_API_Controller();
		$controller->register_routes();
	}

	/**
	 * Register the admin menu.
	 *
	 * @since 1.7.0
	 */
	public function add_plugin_admin_menu() {

		if ( defined( 'WPTELEGRAM_LOADED' ) && WPTELEGRAM_LOADED ) {
			add_submenu_page(
				'wptelegram',
				esc_html( $this->title ),
				esc_html__( 'Telegram Widget', 'wptelegram-widget' ),
				'manage_options',
				$this->plugin_name,
				array( $this, 'display_plugin_admin_page' )
			);
		} else {
			add_menu_page(
				esc_html( $this->title ),
				esc_html( $this->title ),
				'manage_options',
				$this->plugin_name,
				array( $this, 'display_plugin_admin_page' ),
				WPTELEGRAM_WIDGET_URL . '/admin/icons/icon-16x16-white.svg'
			);
		}
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since 1.7.0
	 */
	public function display_plugin_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
			<div id="wptelegram-widget-settings"></div>
		<?php
	}

	/**
	 * Create our feed widget
	 *
	 * @since    1.0.0
	 */
	public function register_widgets() {

		register_widget( 'WPTelegram_Widget_Widget' );

		register_widget( 'WPTelegram_Widget_Ajax_Widget' );
	}

	/**
	 * Fire the pull updates action.
	 *
	 * @since    1.5.0
	 */
	public function fire_pull_updates() {
		do_action( 'wptelegram_widget_pull_the_updates' );
	}

	/**
	 * Pull the updates from Telegram.
	 *
	 * @since 1.5.0
	 */
	public function pull_the_updates() {

		/**
		 * Fires before doing anything
		 */
		do_action( 'wptelegram_widget_pull_updates_init' );

		$bot_token = WPTG_Widget()->options()->get( 'bot_token' );
		$username  = WPTG_Widget()->options()->get( 'username' );

		if ( ! $bot_token || ! $username ) {
			return;
		}

		$params = $this->get_update_params();

		$bot_api = new WPTelegram_Bot_API( $bot_token );

		$res = $bot_api->getUpdates( $params );

		if ( ! $bot_api->is_success( $res ) ) {

			do_action( 'wptelegram_widget_getupdates_failed', $res, $bot_token );

			// Conflict: when webhook is active.
			if ( ! is_wp_error( $res ) && 409 === $res->get_response_code() ) {
				$bot_api->deleteWebhook();
			}
			return;
		}

		$updates = $res->get_result();

		// for tests.
		$updates = (array) apply_filters( 'wptelegram_widget_updates', $updates );

		do_action( 'wptelegram_widget_after_getupdates', $updates, $res );

		if ( ! empty( $updates ) ) {
			// Pass the updates to the handler.
			$this->handle_updates( $updates );
		}

		/**
		 * Fires after doing everything
		 */
		do_action( 'wptelegram_widget_pull_updates_finish', $updates );

		exit( 'Done :)' );
	}

	/**
	 * Get params for getUpdates
	 *
	 * @since    1.0.0
	 */
	private function get_update_params() {

		$update_id = (int) WPTG_Widget()->options()->get( 'last_update_id' );

		if ( $update_id ) {
			$offset = ++$update_id;
		}

		$allowed_updates = json_encode( $this->get_allowed_updates() );

		$update_params = compact( 'offset', 'allowed_updates' );

		return (array) apply_filters( 'wptelegram_widget_update_params', $update_params );
	}

	/**
	 * Get allowed_updates
	 *
	 * @since    1.0.0
	 */
	private function get_allowed_updates() {

		$allowed_updates = array(
			'channel_post',
			// 'edited_channel_post',
			'message',
			// 'edited_message',
		);

		return (array) apply_filters( 'wptelegram_widget_allowed_updates', $allowed_updates );
	}

	/**
	 * Handle updates
	 *
	 * @param array $updates Array of updates.
	 *
	 * @since    1.0.0
	 */
	private function handle_updates( $updates ) {

		/**
		 * Fires before doing anything
		 */
		do_action( 'wptelegram_widget_handle_updates_init', $updates );

		$new_messages = $edited_messages = array();
		$messages     = WPTG_Widget()->options()->get( 'messages', array() );

		foreach ( (array) $updates as $update ) {

			// $is_edited passed by reference
			$message_id = $this->process_update( $update, $is_edited );

			if ( $message_id ) {

				// if it exists in the existing message IDs.
				if ( $is_edited && in_array( $message_id, $messages ) ) {
					$edited_messages[] = $message_id;
				} else {
					$new_messages[] = $message_id;
				}
			}
		}

		$update_id = $update['update_id'];
		WPTG_Widget()->options()->set( 'last_update_id', $update_id );

		if ( ! empty( $new_messages ) ) {

			$this->save_messages( $new_messages );
		}

		/*if ( ! empty( $edited_messages ) ) {

			$this->save_messages( $edited_messages, 'edited' );
		}*/

		/**
		 * Fires after doing everything
		 */
		do_action( 'wptelegram_widget_handle_updates_finish', $updates, $new_messages, $edited_messages );
	}

	/**
	 * Process an update
	 *
	 * @param array $update Update object
	 *
	 * @since    1.3.0
	 */
	private function process_update( $update, &$is_edited ) {

		/**
		 * Fires before doing anything
		 */
		do_action( 'wptelegram_widget_process_update_init', $update );

		$update_type = $this->get_update_type( $update );

		if ( ! $update_type ) {
			return false;
		}

		$message = $update[ $update_type ];

		$verified = $this->verify_username( $message );

		if ( ! $verified ) {
			return false;
		}

		$is_edited = ( 0 === strpos( $update_type, 'edited_' ) ) ? true : false;

		/**
		 * Fires after doing everything
		 */
		do_action( 'wptelegram_widget_process_update_finish', $update, $message, $verified, $is_edited );

		return $message['message_id'];

	}

	/**
	 * Get the update_type.
	 *
	 * @since  1.0.0
	 *
	 */
	private function get_update_type( $update ) {

		$update_type = '';

		$allowed_types = $this->get_allowed_updates();

		foreach ( $allowed_types as $type ) {

			if ( isset( $update[ $type ] ) ) {

				$update_type = $type;
				break;
			}
		}

		return apply_filters( 'wptelegram_widget_update_type', $update_type, $update );
	}

	/**
	 * Verify that the update if from the saved channel
	 * Verify by comparing username 
	 *
	 * @since  1.0.0
	 *
	 */
	private function verify_username( $message ) {

		$verified = false;

		$username = false;

		if ( isset( $message['chat']['username'] ) ) {

			$username = $message['chat']['username'];
		}

		$saved_username = WPTG_Widget()->options()->get( 'username' );

		if ( ! empty( $saved_username ) && strtolower( $saved_username ) === strtolower( $username ) ) {
			$verified = true;
		}

		return (bool) apply_filters( 'wptelegram_widget_verify_username', $verified, $message );
	}

	/**
	 * Store the message_ids
	 *
	 * @since  1.0.0
	 *
	 */
	private function save_messages( array $messages, $type = '' ) {

		$type = $type ? "{$type}_" : $type;

		$saved_messages = WPTG_Widget()->options()->get( "{$type}messages", array() );
		$messages = array_unique( array_merge( $saved_messages, $messages ) );
		$messages = array_filter( $messages );

		// allow maximum 50 messages
		$limit = (int) apply_filters( 'wptelegram_widget_saved_messages_limit', 50 );

		while ( count( $messages ) > $limit ) {
			array_shift( $messages );
		}

		WPTG_Widget()->options()->set( "{$type}messages", $messages );
	}

	/**
	 * Save the messages sent by WP Telegram P2TG.
	 *
	 * @since  1.5.0
	 *
	 */
	public function save_messages_sent_by_p2tg( $res, $responses, $post, $options, $bot_api ) {

		// if the message was not sent successfully.
		if ( ! $bot_api->is_success( $res ) ) {
			return;
		}

		// if the same bot token was not used.
		if ( $bot_api->get_bot_token() !== WPTG_Widget()->options()->get( 'bot_token' ) ) {
			return;
		}

		$result = $res->get_result();

		if ( empty( $result['chat']['username'] ) ) {
			return;
		}

		$used_username  = strtolower( $result['chat']['username'] );
		$saved_username = strtolower( WPTG_Widget()->options()->get( 'username' ) );

		if ( $used_username !== $saved_username ) {
			return;
		}

		$messages[] = $result['message_id'];

		$this->save_messages( $messages );
	}
}
