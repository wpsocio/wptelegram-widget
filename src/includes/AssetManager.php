<?php
/**
 * The assets manager of the plugin.
 *
 * @link       https://wpsocio.com
 * @since      2.0.0
 *
 * @package    WPTelegram\Widget
 * @subpackage WPTelegram\Widget\includes
 */

namespace WPTelegram\Widget\includes;

use WPTelegram\Widget\shared\shortcodes\LegacyWidget;
use WPTelegram\Widget\includes\restApi\RESTController;
use WPTelegram\Widget\includes\restApi\SettingsController;
use ReflectionClass;

/**
 * The assets manager of the plugin.
 *
 * Loads the plugin assets.
 *
 * @package    WPTelegram\Widget
 * @subpackage WPTelegram\Widget\includes
 * @author     WP Socio
 */
class AssetManager extends BaseClass {

	const ADMIN_MAIN_JS_HANDLE = 'wptelegram-widget--main';
	const BLOCKS_JS_HANDLE     = 'wptelegram-widget--blocks';
	const PUBLIC_JS_HANDLE     = 'wptelegram-widget--public';

	/**
	 * Register the assets.
	 *
	 * @since    2.1.0
	 */
	public function register_assets() {

		$request_check = new ReflectionClass( self::class );

		$constants = $request_check->getConstants();

		$assets = $this->plugin()->assets();

		$style_deps = [
			self::BLOCKS_JS_HANDLE     => [ 'wp-components' ],
			self::ADMIN_MAIN_JS_HANDLE => [ self::BLOCKS_JS_HANDLE ],
		];

		foreach ( $constants as $handle ) {
			wp_register_script(
				$handle,
				$assets->get_asset_url( $handle ),
				$assets->get_asset_dependencies( $handle ),
				$assets->get_asset_version( $handle ),
				true
			);

			// Register styles only if they exist.
			if ( $assets->has_asset( $handle, Assets::ASSET_EXT_CSS ) ) {
				$deps = ! empty( $style_deps[ $handle ] ) ? $style_deps[ $handle ] : [];
				wp_register_style(
					$handle,
					$assets->get_asset_url( $handle, Assets::ASSET_EXT_CSS ),
					$deps,
					$assets->get_asset_version( $handle, Assets::ASSET_EXT_CSS ),
					'all'
				);
			}
		}

		if ( ! defined( 'WPTELEGRAM_LOADED' ) ) {
			wp_register_style(
				'wptelegram-menu',
				$assets->url( sprintf( '/css/admin-menu%s.css', wp_scripts_get_suffix() ) ),
				[],
				$this->plugin()->version(),
				'all'
			);
		}

		$handle = self::BLOCKS_JS_HANDLE;

		if ( wp_style_is( $handle, 'registered' ) ) {
			$style = sprintf(
				':root {%1$s: %2$s;%3$s: %4$s}',
				'--wptelegram-widget-join-link-bg-color',
				$this->plugin()->options()->get_path( 'join_link.bgcolor', '#389ce9' ),
				'--wptelegram-widget-join-link-color',
				$this->plugin()->options()->get_path( 'join_link.text_color', '#fff' )
			);

			wp_add_inline_style( $handle, $style );
		}
	}

	/**
	 * Register the stylesheets for the public area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_public_styles() {

		$entrypoint = self::PUBLIC_JS_HANDLE;

		wp_enqueue_style( $entrypoint );
	}

	/**
	 * Register the stylesheets for the public area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_public_scripts() {

		$entrypoint = self::PUBLIC_JS_HANDLE;

		wp_enqueue_script( $entrypoint );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.0.0
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_admin_styles( $hook_suffix ) {

		if ( ! defined( 'WPTELEGRAM_LOADED' ) ) {
			wp_enqueue_style( 'wptelegram-menu' );
		}

		$handle = self::ADMIN_MAIN_JS_HANDLE;

		// Load only on settings page.
		if ( $this->is_settings_page( $hook_suffix ) && wp_style_is( $handle, 'registered' ) ) {
			wp_enqueue_style( $handle );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    2.0.0
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_admin_scripts( $hook_suffix ) {
		// Load only on settings page.
		if ( $this->is_settings_page( $hook_suffix ) ) {
			$handle = self::ADMIN_MAIN_JS_HANDLE;

			wp_enqueue_script( $handle );

			// Pass data to JS.
			$data = $this->get_dom_data();

			self::add_dom_data( $handle, $data );
		}
	}

	/**
	 * Add the data to DOM.
	 *
	 * @since 2.1.0
	 *
	 * @param string $handle The script handle to attach the data to.
	 * @param mixed  $data   The data to add.
	 * @param string $var    The JavaScript variable name to use.
	 *
	 * @return void
	 */
	public static function add_dom_data( $handle, $data, $var = 'wptelegram_widget' ) {
		wp_add_inline_script(
			$handle,
			sprintf( 'var %s = %s;', $var, wp_json_encode( $data ) ),
			'before'
		);
	}

	/**
	 * Get the common DOM data.
	 *
	 * @param string $for The domain for which the DOM data is to be rendered.
	 * possible values: 'SETTINGS_PAGE' | 'BLOCKS'.
	 *
	 * @return array
	 */
	public function get_dom_data( $for = 'SETTINGS_PAGE' ) {
		$data = [
			'pluginInfo' => [
				'title'       => $this->plugin()->title(),
				'name'        => $this->plugin()->name(),
				'version'     => $this->plugin()->version(),
				'description' => __( 'With this plugin, you can let the users widget to your website with their Telegram and make it simple for them to get connected.', 'wptelegram-widget' ),
			],
			'api'        => [
				'admin_url'      => admin_url(),
				'nonce'          => wp_create_nonce( 'wptelegram-widget' ),
				'use'            => 'SERVER', // or may be 'BROWSER'?
				'rest_namespace' => RESTController::REST_NAMESPACE,
				'wp_rest_url'    => esc_url_raw( rest_url() ),
			],
			'assets'     => [
				'logoUrl'   => $this->plugin()->assets()->url( '/icons/icon-128x128.png' ),
				'tgIconUrl' => $this->plugin()->assets()->url( '/icons/tg-icon.svg' ),
			],
			'uiData'     => [],
			'i18n'       => Utils::get_jed_locale_data( 'wptelegram-widget' ),
		];

		$settings = SettingsController::get_default_settings();

		// Not to expose bot token to non-admins.
		if ( 'SETTINGS_PAGE' === $for && current_user_can( 'manage_options' ) ) {
			$data['savedSettings'] = $settings;

			$data['uiData']['post_types'] = $this->get_post_type_options();

			$data['assets']['pullUpdatesUrl'] = add_query_arg( [ 'action' => 'wptelegram_widget_pull_updates' ], site_url() );
		}

		if ( 'BLOCKS' === $for ) {

			$data['assets']['message_view_url'] = LegacyWidget::get_single_message_embed_url( '%username%', '%message_id%', '%userpic%' );

			$data['uiData'] = array_merge(
				$data['uiData'],
				[
					'join_link_url'  => $this->plugin()->options()->get_path( 'join_link.url' ),
					'join_link_text' => $this->plugin()->options()->get_path( 'join_link.text' ),
				]
			);
		}

		return apply_filters( 'wptelegram_widget_assets_dom_data', $data, $for, $this->plugin() );
	}

	/**
	 * Get the registered post types.
	 *
	 * @since  1.9.0
	 * @return array
	 */
	public function get_post_type_options() {

		$options = [];

		$post_types = get_post_types( [ 'public' => true ], 'objects' );

		foreach ( $post_types  as $post_type ) {
			if ( 'attachment' !== $post_type->name ) {
				$options[] = [
					'value' => $post_type->name,
					'label' => "{$post_type->labels->singular_name} ({$post_type->name})",
				];
			}
		}

		return apply_filters( 'wptelegram_widget_post_type_options', $options, $post_types );
	}

	/**
	 * Enqueue assets for the Gutenberg block
	 *
	 * @since 2.0.0
	 * @param string $hook_suffix The current admin page.
	 */
	public function is_settings_page( $hook_suffix ) {
		return ( false !== strpos( $hook_suffix, '_page_' . $this->plugin()->name() ) );
	}

	/**
	 * Enqueue assets for the Gutenberg block
	 *
	 * @since    2.0.0
	 */
	public function enqueue_block_editor_assets() {
		$handle = self::BLOCKS_JS_HANDLE;

		wp_enqueue_script( $handle );

		$data = $this->get_dom_data( 'BLOCKS' );

		self::add_dom_data( $handle, $data );

		if ( wp_style_is( $handle, 'registered' ) ) {
			wp_enqueue_style( $handle );
		}
	}
}
