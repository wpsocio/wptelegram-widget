<?php
/**
 * The assets manager of the plugin.
 *
 * @link       https://manzoorwani.dev
 * @since      2.0.0
 *
 * @package    WPTelegram\Widget
 * @subpackage WPTelegram\Widget\includes
 */

namespace WPTelegram\Widget\includes;

use WPTelegram\Widget\shared\shortcodes\LegacyWidget;
use WPTelegram\Widget\includes\restApi\RESTController;

/**
 * The assets manager of the plugin.
 *
 * Loads the plugin assets.
 *
 * @package    WPTelegram\Widget
 * @subpackage WPTelegram\Widget\includes
 * @author     Manzoor Wani <@manzoorwanijk>
 */
class AssetManager extends BaseClass {

	const ADMIN_MAIN_JS_HANDLE = 'wptelegram-widget--main';
	const BLOCKS_JS_HANDLE     = 'wptelegram-widget--blocks';
	const PUBLIC_JS_HANDLE     = 'wptelegram-widget--public';

	/**
	 * Register the stylesheets for the public area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_public_styles() {

		$entrypoint = self::PUBLIC_JS_HANDLE;

		wp_enqueue_style(
			$entrypoint,
			$this->plugin()->assets()->get_asset_url( $entrypoint, Assets::ASSET_EXT_CSS ),
			[],
			$this->plugin()->assets()->get_asset_version( $entrypoint, Assets::ASSET_EXT_CSS ),
			'all'
		);
	}

	/**
	 * Register the stylesheets for the public area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_public_scripts() {

		$entrypoint = self::PUBLIC_JS_HANDLE;

		wp_enqueue_script(
			$entrypoint,
			$this->plugin()->assets()->get_asset_url( $entrypoint ),
			$this->plugin()->assets()->get_asset_dependencies( $entrypoint ),
			$this->plugin()->assets()->get_asset_version( $entrypoint ),
			true
		);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.0.0
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_admin_styles( $hook_suffix ) {

		if ( ! defined( 'WPTELEGRAM_LOADED' ) ) {
			wp_enqueue_style(
				$this->plugin()->name() . '-menu',
				$this->plugin()->assets()->url( sprintf( '/css/admin-menu%s.css', wp_scripts_get_suffix() ) ),
				[],
				$this->plugin()->version(),
				'all'
			);
		}

		$entrypoint = self::ADMIN_MAIN_JS_HANDLE;

		// Load only on settings page.
		if ( $this->is_settings_page( $hook_suffix ) && $this->plugin()->assets()->has_asset( $entrypoint, Assets::ASSET_EXT_CSS ) ) {
			wp_enqueue_style(
				$entrypoint,
				$this->plugin()->assets()->get_asset_url( $entrypoint, Assets::ASSET_EXT_CSS ),
				// For join link preview.
				[ self::BLOCKS_JS_HANDLE ],
				$this->plugin()->assets()->get_asset_version( $entrypoint, Assets::ASSET_EXT_CSS ),
				'all'
			);
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
			$entrypoint = self::ADMIN_MAIN_JS_HANDLE;

			wp_enqueue_script(
				$entrypoint,
				$this->plugin()->assets()->get_asset_url( $entrypoint ),
				$this->plugin()->assets()->get_asset_dependencies( $entrypoint ),
				$this->plugin()->assets()->get_asset_version( $entrypoint ),
				true
			);

			// Pass data to JS.
			$data = $this->get_dom_data();
			// Not to expose bot token to non-admins.
			if ( current_user_can( 'manage_options' ) ) {
				$data['savedSettings'] = \WPTelegram\Widget\includes\restApi\SettingsController::get_default_settings();
			}
			$data['uiData']['post_types'] = $this->get_post_type_options();

			$data['assets']['pullUpdatesUrl'] = add_query_arg( [ 'action' => 'wptelegram_widget_pull_updates' ], site_url() );

			wp_add_inline_script(
				$entrypoint,
				sprintf( 'var wptelegram_widget = %s;', json_encode( $data ) ), // phpcs:ignore WordPress.WP.AlternativeFunctions
				'before'
			);
		}
	}

	/**
	 * Get the common DOM data.
	 *
	 * @return array
	 */
	private function get_dom_data() {
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
				'rest_namespace' => RESTController::NAMESPACE,
				'wp_rest_url'    => esc_url_raw( rest_url() ),
			],
			'assets'     => [
				'logoUrl'   => $this->plugin()->assets()->url( '/icons/icon-128x128.png' ),
				'tgIconUrl' => $this->plugin()->assets()->url( '/icons/tg-icon.svg' ),
			],
			'uiData'     => [],
			'i18n'       => Utils::get_jed_locale_data( 'wptelegram-widget' ),
		];

		return $data;
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
	 * Register assets.
	 *
	 * @since    2.0.0
	 */
	public function register_assets() {
		$entrypoint = self::BLOCKS_JS_HANDLE;

		if ( $this->plugin()->assets()->has_asset( $entrypoint, Assets::ASSET_EXT_CSS ) ) {
			wp_register_style(
				$entrypoint,
				$this->plugin()->assets()->get_asset_url( $entrypoint, Assets::ASSET_EXT_CSS ),
				[ 'wp-components' ],
				$this->plugin()->assets()->get_asset_version( $entrypoint, Assets::ASSET_EXT_CSS ),
				'all'
			);
			$style = sprintf(
				':root {%1$s: %2$s;%3$s: %4$s}',
				'--wptelegram-widget-join-link-bg-color',
				$this->plugin()->options()->get_path( 'join_link.bgcolor', '#389ce9' ),
				'--wptelegram-widget-join-link-color',
				$this->plugin()->options()->get_path( 'join_link.text_color', '#fff' )
			);

			wp_add_inline_style( $entrypoint, $style );
		}
	}

	/**
	 * Enqueue assets for the Gutenberg block
	 *
	 * @since    2.0.0
	 */
	public function enqueue_block_editor_assets() {
		$entrypoint = self::BLOCKS_JS_HANDLE;

		wp_enqueue_script(
			$entrypoint,
			$this->plugin()->assets()->get_asset_url( $entrypoint ),
			$this->plugin()->assets()->get_asset_dependencies( $entrypoint ),
			$this->plugin()->assets()->get_asset_version( $entrypoint ),
			true
		);

		$data = $this->get_dom_data();

		$data['assets']['message_view_url'] = LegacyWidget::get_single_message_embed_url( '%username%', '%message_id%', '%userpic%' );

		$data['uiData'] = array_merge(
			$data['uiData'],
			[
				'join_link_url'  => $this->plugin()->options()->get_path( 'join_link.url' ),
				'join_link_text' => $this->plugin()->options()->get_path( 'join_link.text' ),
			]
		);

		wp_add_inline_script(
			$entrypoint,
			sprintf( 'var wptelegram_widget = %s;', json_encode( $data ) ), // phpcs:ignore WordPress.WP.AlternativeFunctions
			'before'
		);

		// don't load styles for dev env.
		if ( defined( 'WP_PLUGINS_DEV_LOADED' ) ) {
			return;
		}

		if ( $this->plugin()->assets()->has_asset( $entrypoint, Assets::ASSET_EXT_CSS ) ) {
			wp_enqueue_style( $entrypoint );
		}
	}
}
