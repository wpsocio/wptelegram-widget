<?php
/**
 * The assets manager of the plugin.
 *
 * @link       https://t.me/manzoorwanijk
 * @since      x.y.z
 *
 * @package    WPTelegram\Widget
 * @subpackage WPTelegram\Widget\includes
 */

namespace WPTelegram\Widget\includes;

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
	 * @since    x.y.z
	 */
	public function enqueue_public_styles() {

		$entrypoint = self::PUBLIC_JS_HANDLE;

		wp_enqueue_style(
			$entrypoint,
			$this->plugin->assets()->get_asset_url( $entrypoint, Assets::ASSET_EXT_CSS ),
			array(),
			$this->plugin->assets()->get_asset_version( $entrypoint, Assets::ASSET_EXT_CSS ),
			'all'
		);
	}

	/**
	 * Register the stylesheets for the public area.
	 *
	 * @since    x.y.z
	 */
	public function enqueue_public_scripts() {

		$entrypoint = self::PUBLIC_JS_HANDLE;

		wp_enqueue_script(
			$entrypoint,
			$this->plugin->assets()->get_asset_url( $entrypoint ),
			$this->plugin->assets()->get_asset_dependencies( $entrypoint ),
			$this->plugin->assets()->get_asset_version( $entrypoint ),
			true
		);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    x.y.z
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_admin_styles( $hook_suffix ) {

		if ( ! defined( 'WPTELEGRAM_LOADED' ) ) {
			wp_enqueue_style(
				$this->plugin->name() . '-menu',
				$this->plugin->assets()->url( sprintf( '/css/admin-menu%s.css', wp_scripts_get_suffix() ) ),
				array(),
				$this->plugin->version(),
				'all'
			);
		}

		$entrypoint = self::ADMIN_MAIN_JS_HANDLE;

		// Load only on settings page.
		if ( $this->is_settings_page( $hook_suffix ) && $this->plugin->assets()->has_asset( $entrypoint, Assets::ASSET_EXT_CSS ) ) {
			wp_enqueue_style(
				$entrypoint,
				$this->plugin->assets()->get_asset_url( $entrypoint, Assets::ASSET_EXT_CSS ),
				array(),
				$this->plugin->assets()->get_asset_version( $entrypoint, Assets::ASSET_EXT_CSS ),
				'all'
			);
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    x.y.z
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_admin_scripts( $hook_suffix ) {
		// Load only on settings page.
		if ( $this->is_settings_page( $hook_suffix ) ) {
			$entrypoint = self::ADMIN_MAIN_JS_HANDLE;

			wp_enqueue_script(
				$entrypoint,
				$this->plugin->assets()->get_asset_url( $entrypoint ),
				$this->plugin->assets()->get_asset_dependencies( $entrypoint ),
				$this->plugin->assets()->get_asset_version( $entrypoint ),
				true
			);

			// Pass data to JS.
			$data = $this->get_dom_data();
			// Not to expose bot token to non-admins.
			if ( current_user_can( 'manage_options' ) ) {
				$data['savedSettings'] = \WPTelegram\Widget\includes\restApi\SettingsController::get_default_settings();
			}
			$data['uiData']['post_types'] = $this->get_post_type_options();

			$data['assets']['pullUpdatesUrl'] = add_query_arg( array( 'action' => 'wptelegram_widget_pull_updates' ), site_url() );

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
		$data = array(
			'pluginInfo' => array(
				'title'       => $this->plugin->title(),
				'name'        => $this->plugin->name(),
				'version'     => $this->plugin->version(),
				'description' => __( 'With this plugin, you can let the users widget to your website with their Telegram and make it simple for them to get connected.', 'wptelegram-widget' ),
			),
			'api'        => array(
				'admin_url'      => admin_url( 'admin-ajax.php' ),
				'nonce'          => wp_create_nonce( 'wptelegram-widget' ),
				'use'            => 'SERVER', // or may be 'BROWSER'?
				'rest_namespace' => 'wptelegram-widget/v1',
				'wp_rest_url'    => esc_url_raw( rest_url() ),
			),
			'assets'     => array(
				'logoUrl'   => $this->plugin->assets()->url( '/icons/icon-128x128.png' ),
				'tgIconUrl' => $this->plugin->assets()->url( '/icons/tg-icon.svg' ),
			),
			'uiData'     => array(),
			'i18n'       => wptelegram_get_jed_locale_data( 'wptelegram-widget' ),
		);

		return $data;
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
				$options[] = array(
					'value' => $post_type->name,
					'label' => "{$post_type->labels->singular_name} ({$post_type->name})",
				);
			}
		}

		return apply_filters( 'wptelegram_widget_post_type_options', $options, $post_types );
	}

	/**
	 * Enqueue assets for the Gutenberg block
	 *
	 * @since x.y.z
	 * @param string $hook_suffix The current admin page.
	 */
	public function is_settings_page( $hook_suffix ) {
		return ( false !== strpos( $hook_suffix, '_page_' . $this->plugin->name() ) );
	}

	/**
	 * Register assets.
	 *
	 * @since    x.y.z
	 */
	public function register_assets() {
		$entrypoint = self::BLOCKS_JS_HANDLE;

		if ( $this->plugin->assets()->has_asset( $entrypoint, Assets::ASSET_EXT_CSS ) ) {
			wp_register_style(
				$entrypoint,
				$this->plugin->assets()->get_asset_url( $entrypoint, Assets::ASSET_EXT_CSS ),
				array( 'wp-components' ),
				$this->plugin->assets()->get_asset_version( $entrypoint, Assets::ASSET_EXT_CSS ),
				'all'
			);
		}
	}

	/**
	 * Enqueue assets for the Gutenberg block
	 *
	 * @since    x.y.z
	 */
	public function enqueue_block_editor_assets() {
		$entrypoint = self::BLOCKS_JS_HANDLE;

		wp_enqueue_script(
			$entrypoint,
			$this->plugin->assets()->get_asset_url( $entrypoint ),
			$this->plugin->assets()->get_asset_dependencies( $entrypoint ),
			$this->plugin->assets()->get_asset_version( $entrypoint ),
			true
		);

		$data = $this->get_dom_data();

		$data['assets']['message_view_url'] = \WPTelegram\Widget\shared\Shared::get_message_view_url( '%username%', '%message_id%', '%userpic%' );

		$data['uiData'] = array_merge(
			$data['uiData'],
			array(
				'join_link_url'  => $this->plugin->options()->get( 'join_link' )['url'],
				'join_link_text' => $this->plugin->options()->get( 'join_link' )['text'],
			)
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

		if ( $this->plugin->assets()->has_asset( $entrypoint, Assets::ASSET_EXT_CSS ) ) {
			wp_enqueue_style( $entrypoint );
		}
	}
}
