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
	 * The plugin class instance.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      WPTelegram_Widget $plugin The plugin class instance.
	 */
	private $plugin;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param WPTelegram_Widget $plugin The plugin class instance.
	 */
	public function __construct( $plugin ) {

		$this->plugin = $plugin;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_styles( $hook_suffix ) {

		if ( ! defined( 'WPTELEGRAM_LOADED' ) ) {
			wp_enqueue_style(
				$this->plugin->name(),
				$this->plugin->url( '/admin/css/admin-menu' ) . $this->plugin->suffix() . '.css',
				array(),
				$this->plugin->version(),
				'all'
			);
		}

		// Load only on settings page.
		if ( $this->is_settings_page( $hook_suffix ) ) {
			wp_enqueue_style( $this->plugin->name() . '-bootstrap', $this->plugin->url( '/admin/css/bootstrap/bootstrap' ) . $this->plugin->suffix() . '.css', array(), $this->plugin->version(), 'all' );
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
			$this->plugin->name(),
			$this->plugin->url( '/admin/js/wptelegram-widget-admin' ) . $this->plugin->suffix() . '.js',
			array( 'jquery' ),
			$this->plugin->version(),
			false
		);

		// script localization.
		$translation_array = array(
			'title'   => $this->plugin->title(),
			'name'    => $this->plugin->name(),
			'version' => $this->plugin->version(),
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
			$this->plugin->name(),
			'wptelegram_widget',
			$translation_array
		);

		// Load only on settings page.
		if ( $this->is_settings_page( $hook_suffix ) ) {

			// Avoid caching for development.
			$version = defined( 'WPTELEGRAM_DEV' ) && WPTELEGRAM_DEV ? gmdate( 'y.m.d-is', filemtime( $this->plugin->dir( '/admin/settings/dist/settings-dist.js' ) ) ) : $this->plugin->version();

			wp_enqueue_script( $this->plugin->name() . '-settings', $this->plugin->url( '/admin/settings/dist/settings-dist.js' ), array( 'jquery' ), $version, true );

			// Pass data to JS.
			$data = array(
				'settings' => array(
					'info'        => array(
						// phpcs:ignore Squiz.PHP.CommentedOutCode
						'use' => 'server', // or 'browser'.
					),
					'saved_opts'  => current_user_can( 'manage_options' ) ? WPTelegram_Widget_Settings_Controller::get_default_settings() : array(), // Not to expose bot token to non-admins.
					'assets'      => array(
						'pull_updates_url' => add_query_arg( array( 'action' => 'wptelegram_widget_pull_updates' ), site_url() ),
						'admin_url'        => untrailingslashit( admin_url() ),
						'logo_url'         => $this->plugin->url( '/admin/icons/icon-100x100.svg' ),
						'tg_icon'          => $this->plugin->url( '/admin/icons/tg-icon.svg' ),
					),
					'select_opts' => array(
						'post_types' => $this->get_post_type_options(),
					),
					'i18n'        => wptelegram_get_jed_locale_data( 'wptelegram-widget' ),
				),
			);

			wp_add_inline_script(
				$this->plugin->name(),
				sprintf( 'Object.assign(wptelegram_widget, %s);', json_encode( $data ) ), // phpcs:ignore WordPress.WP.AlternativeFunctions
				'before'
			);

			// For Facebook like button.
			wp_add_inline_script(
				$this->plugin->name() . '-settings',
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
			wp_enqueue_script( $this->plugin->name() . '-twitter', 'https://platform.twitter.com/widgets.js', array(), $this->plugin->version(), true );
		}

		// If the block editor assets are loaded.
		if ( did_action( 'enqueue_block_editor_assets' ) ) {
			$data = array(
				'blocks' => array(
					'assets'         => array(
						'message_view_url' => WPTelegram_Widget_Public::get_message_view_url( '%username%', '%message_id%', '%userpic%' ),
					),
					'join_link_url'  => $this->plugin->options()->get( 'join_link_url' ),
					'join_link_text' => $this->plugin->options()->get( 'join_link_text' ),
				),
			);

			wp_add_inline_script(
				$this->plugin->name(),
				sprintf( 'Object.assign(wptelegram_widget, %s);', json_encode( $data ) ), // phpcs:ignore WordPress.WP.AlternativeFunctions
				'before'
			);
		}
	}

	/**
	 * Get the registered post types.
	 *
	 * @since  1.9.0
	 * @return array
	 */
	public function get_post_type_options() {

		$options = array();

		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		foreach ( $post_types  as $post_type ) {

			if ( 'attachment' !== $post_type->name ) {

				$options[ $post_type->name ] = "{$post_type->labels->singular_name} ({$post_type->name})";
			}
		}

		return apply_filters( 'wptelegram_widget_post_type_options', $options, $post_types );
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
		if ( $this->plugin->name() . '-twitter' !== $handle ) {
			return $tag;
		}
		// phpcs:ignore WordPress.WP.EnqueuedResources
		return '<script async src="' . $src . '" charset="utf-8"></script>' . PHP_EOL;
	}

	/**
	 * Enqueue assets for the Gutenberg block
	 *
	 * @since 1.7.0
	 * @param string $hook_suffix The current admin page.
	 */
	public function is_settings_page( $hook_suffix ) {
		return ( current_user_can( 'manage_options' ) && false !== strpos( $hook_suffix, '_page_' . $this->plugin->name() ) );
	}

	/**
	 * Enqueue assets for the Gutenberg block.
	 *
	 * @since 1.5.0
	 */
	public function enqueue_block_editor_assets() {

		wp_enqueue_script(
			$this->plugin->name() . '-block',
			$this->plugin->url( '/blocks/dist/blocks-build.js' ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
			$this->plugin->version(),
			true
		);

		wp_enqueue_style(
			$this->plugin->name() . '-block',
			$this->plugin->url( '/blocks/dist/blocks-build.css' ),
			array( 'wp-edit-blocks' ),
			$this->plugin->version()
		);
	}

	/**
	 * Registers custom category for blocks.
	 *
	 * @since 1.9.0
	 *
	 * @param array $categories The block categories.
	 * @return array
	 */
	public function register_block_category( $categories ) {
		$slugs = wp_list_pluck( $categories, 'slug' );
		$slug  = 'wptelegram';
		if ( in_array( $slug, $slugs, true ) ) {
			return $categories;
		}
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => $slug,
					'title' => __( 'WP Telegram', 'wptelegram-widget' ),
					'icon'  => null,
				),
			)
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
				esc_html( $this->plugin->title() ),
				esc_html__( 'Telegram Widget', 'wptelegram-widget' ),
				'manage_options',
				$this->plugin->name(),
				array( $this, 'display_plugin_admin_page' )
			);
		} else {
			add_menu_page(
				esc_html( $this->plugin->title() ),
				esc_html( $this->plugin->title() ),
				'manage_options',
				$this->plugin->name(),
				array( $this, 'display_plugin_admin_page' )
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
		include $this->plugin->dir( '/admin/partials/admin-display.php' );
	}

	/**
	 * Create our feed widget
	 *
	 * @since    1.0.0
	 */
	public function register_widgets() {

		register_widget( 'WPTelegram_Widget_Widget' );

		register_widget( 'WPTelegram_Widget_Ajax_Widget' );

		register_widget( 'WPTelegram_Widget_Join_Channel' );
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

		$bot_token = $this->plugin->options()->get( 'bot_token' );
		$username  = $this->plugin->options()->get( 'username' );

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

		$update_id = (int) $this->plugin->options()->get( 'last_update_id' );

		if ( $update_id ) {
			$offset = ++$update_id;
		}

		$allowed_updates = json_encode( $this->get_allowed_updates() ); // phpcs:ignore WordPress.WP.AlternativeFunctions

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

		$new_messages    = array();
		$edited_messages = array();
		$messages        = $this->plugin->options()->get( 'messages', array() );

		foreach ( (array) $updates as $update ) {

			// $is_edited passed by reference
			$message_id = $this->process_update( $update, $is_edited );

			if ( $message_id ) {

				// if it exists in the existing message IDs.
				if ( $is_edited && in_array( $message_id, $messages, true ) ) {
					$edited_messages[] = $message_id;
				} else {
					$new_messages[] = $message_id;
				}
			}
		}

		$update_id = $update['update_id'];
		$this->plugin->options()->set( 'last_update_id', $update_id );

		if ( ! empty( $new_messages ) ) {

			$this->save_messages( $new_messages );
		}

		/**
		 * Fires after doing everything
		 */
		do_action( 'wptelegram_widget_handle_updates_finish', $updates, $new_messages, $edited_messages );
	}

	/**
	 * Process an update.
	 *
	 * @param array $update    Update object.
	 * @param bool  $is_edited If the update is an edit.
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
	 * @since 1.0.0
	 * @param array $update The update object.
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
	 * Verify that the update if from the saved channel.
	 * Verify by comparing username.
	 *
	 * @since 1.0.0
	 * @param array $message The message object.
	 */
	private function verify_username( $message ) {

		$verified = false;

		$username = false;

		if ( isset( $message['chat']['username'] ) ) {

			$username = $message['chat']['username'];
		}

		$saved_username = $this->plugin->options()->get( 'username' );

		if ( ! empty( $saved_username ) && strtolower( $saved_username ) === strtolower( $username ) ) {
			$verified = true;
		}

		return (bool) apply_filters( 'wptelegram_widget_verify_username', $verified, $message );
	}

	/**
	 * Store the message_ids.
	 *
	 * @since  1.0.0
	 *
	 * @param array  $messages The mesage IDs.
	 * @param string $type     Edited or new.
	 * @return void
	 */
	private function save_messages( array $messages, $type = '' ) {

		$type           = $type ? "{$type}_" : $type;
		$saved_messages = $this->plugin->options()->get( "{$type}messages", array() );
		$messages       = array_unique( array_merge( $saved_messages, $messages ) );
		$messages       = array_filter( $messages );

		// Allow maximum 50 messages.
		$limit = (int) apply_filters( 'wptelegram_widget_saved_messages_limit', 50 );

		$count = count( $messages );

		while ( $count > $limit ) {
			array_shift( $messages );

			$count = count( $messages );
		}

		$this->plugin->options()->set( "{$type}messages", $messages );
	}

	/**
	 * Save the messages sent by WP Telegram P2TG.
	 *
	 * @since  1.5.0
	 *
	 * @param Object  $res       The response from API call.
	 * @param array   $responses The responses sent via P2TG.
	 * @param WP_Post $post      The post being sent.
	 * @param Object  $options   P2TG options object.
	 * @param Object  $bot_api   Bot API object.
	 */
	public function save_messages_sent_by_p2tg( $res, $responses, $post, $options, $bot_api ) {

		// if the message was not sent successfully.
		if ( ! $bot_api->is_success( $res ) ) {
			return;
		}

		// if the same bot token was not used.
		if ( $bot_api->get_bot_token() !== $this->plugin->options()->get( 'bot_token' ) ) {
			return;
		}

		$result = $res->get_result();

		if ( empty( $result['chat']['username'] ) ) {
			return;
		}

		$used_username  = strtolower( $result['chat']['username'] );
		$saved_username = strtolower( $this->plugin->options()->get( 'username' ) );

		if ( $used_username !== $saved_username ) {
			return;
		}

		$messages[] = $result['message_id'];

		$this->save_messages( $messages );
	}
}
