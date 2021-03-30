<?php
/**
 * The file that defines the core plugin class
 *
 * @link       https://manzoorwani.dev
 * @since      1.0.0
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/includes
 */

namespace WPTelegram\Widget\includes;

use WPTelegram\Widget\admin\Admin;
use WPTelegram\Widget\shared\Shared;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/includes
 * @author     Manzoor Wani
 */
class Main {

	/**
	 * The single instance of the class.
	 *
	 * @since 1.0.0
	 * @var   Main $instance The instance.
	 */
	protected static $instance = null;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Title of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $title    Title of the plugin
	 */
	protected $title;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The plugin options
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Options    $options    The plugin options
	 */
	protected $options;

	/**
	 * The assets handler.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $assets    The assets handler.
	 */
	protected $assets;

	/**
	 * Main class Instance.
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @return Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->version = WPTELEGRAM_WIDGET_VER;

		$this->plugin_name = 'wptelegram_widget';

		$this->load_dependencies();
		$this->set_options();
		$this->set_assets();

		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		$this->run();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WPTelegram_Widget_Loader. Orchestrates the hooks of the plugin.
	 * - WPTelegram_Widget_i18n. Defines internationalization functionality.
	 * - WPTelegram_Widget_Admin. Defines all hooks for the admin area.
	 * - WPTelegram_Widget_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Helper functions
		 */
		require_once $this->dir( '/includes/helper-functions.php' );

		/**
		 * The class responsible for loading \WPTelegram\BotAPI library
		 */
		require_once $this->dir( '/includes/wptelegram-bot-api/src/index.php' );

		$this->loader = new Loader();

	}

	/**
	 * Set the plugin options
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_options() {

		$this->options = new Options( $this->plugin_name );

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WPTelegram_Widget_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Set the assets handler.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function set_assets() {
		$this->assets = new Assets( $this->dir( '/assets' ), $this->url( '/assets' ) );
	}

	/**
	 * Get the plugin assets handler.
	 *
	 * @since    2.0.0
	 * @access   public
	 *
	 * @return Assets The assets instance.
	 */
	public function assets() {

		return $this->assets;
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Admin( $this );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu', 11 );

		$this->loader->add_action( 'rest_api_init', $plugin_admin, 'register_rest_routes' );

		$this->loader->add_action( 'widgets_init', $plugin_admin, 'register_widgets' );

		// To be used for long polling.
		$this->loader->add_action( 'admin_post_nopriv_wptelegram_widget_pull_updates', $plugin_admin, 'fire_pull_updates' );
		$this->loader->add_action( 'admin_post_wptelegram_widget_pull_updates', $plugin_admin, 'fire_pull_updates' );

		$this->loader->add_action( 'wptelegram_widget_pull_the_updates', $plugin_admin, 'pull_the_updates' );

		// To be used for displaying the widget messages.
		$this->loader->add_action( 'admin_post_nopriv_wptelegram_widget_view', $plugin_admin, 'render_widget_view' );
		$this->loader->add_action( 'admin_post_wptelegram_widget_view', $plugin_admin, 'render_widget_view' );

		$this->loader->add_action( 'wptelegram_p2tg_api_response', $plugin_admin, 'save_messages_sent_by_p2tg', 10, 5 );

		$this->loader->add_filter( 'block_categories', $plugin_admin, 'register_block_category', 10, 1 );

		$this->loader->add_filter( 'rest_request_before_callbacks', Utils::class, 'fitler_rest_errors', 10, 3 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$upgrade = new Upgrade( $this );

		$this->loader->add_action( 'after_setup_theme', $upgrade, 'do_upgrade' );

		$shared = new Shared( $this );

		$this->loader->add_action( 'init', $shared, 'add_rewrite_rules' );

		$this->loader->add_filter( 'template_include', $shared, 'set_embed_template', 99 );

		$this->loader->add_filter( 'template_include', $shared, 'intercept_v_template', 999 );

		$this->loader->add_action( 'init', $shared, 'may_be_fire_pull_updates' );

		$this->loader->add_action( 'init', $shared, 'register_blocks' );

		$this->loader->add_action( 'wptelegram_widget_cron_pull_updates', $shared, 'cron_pull_updates' );

		// better be safe by using PHP_INT_MAX to make sure
		// some dumb people don't remove your schedule.
		$this->loader->add_filter( 'cron_schedules', $shared, 'custom_cron_schedules', PHP_INT_MAX, 1 ); //phpcs:ignore WordPress.WP.CronInterval

		$proprity = $this->options()->get_path( 'join_link.priority', 10 );

		$this->loader->add_filter( 'the_content', $shared, 'add_join_link_to_post_content', $proprity, 1 );

		$asset_manager = new AssetManager( $this );

		$this->loader->add_action( 'init', $asset_manager, 'register_assets' );

		$this->loader->add_action( 'wp_enqueue_scripts', $asset_manager, 'enqueue_public_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $asset_manager, 'enqueue_public_scripts' );

		$this->loader->add_action( 'admin_enqueue_scripts', $asset_manager, 'enqueue_admin_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $asset_manager, 'enqueue_admin_scripts' );

		$this->loader->add_action( 'enqueue_block_editor_assets', $asset_manager, 'enqueue_block_editor_assets' );

		// Register shortcodes.
		$shortcodes = [
			'wptelegram-ajax-widget'  => 'AjaxWidget',
			'wptelegram-join-channel' => 'JoinChannel',
			'wptelegram-widget'       => 'LegacyWidget',
		];

		foreach ( $shortcodes as $shortcode => $class ) {
			$this->loader->add_shortcode( $shortcode, '\WPTelegram\Widget\shared\shortcodes\\' . $class, 'render' );
		}

		// Register embeds.
		$this->loader->add_action( 'wptelegram_widget_ajax_widget_embed', '\WPTelegram\Widget\shared\embed\AjaxWidget', 'render', 10, 1 );

		$this->loader->add_action( 'wptelegram_widget_single_message_embed', '\WPTelegram\Widget\shared\embed\SingleMessage', 'render', 10, 2 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	private function run() {
		$this->loader->run();
	}

	/**
	 * Get the plugin options
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @return Options The options instance.
	 */
	public function options() {

		return $this->options;
	}

	/**
	 * The title of the plugin.
	 *
	 * @since     1.7.1
	 * @return    string    The title of the plugin.
	 */
	public function title() {
		// Set here instead of constructor
		// to be able to translate it.
		if ( ! $this->title ) {
			$this->title = __( 'WP Telegram Widget', 'wptelegram-widget' );
		}
		return $this->title;
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.7.1
	 * @return    string    The name of the plugin.
	 */
	public function name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.7.1
	 * @return    string    The version number of the plugin.
	 */
	public function version() {
		return $this->version;
	}

	/**
	 * Retrieve directory path to the plugin.
	 *
	 * @since 1.7.1
	 * @param string $path Path to append.
	 * @return string Directory with optional path appended
	 */
	public function dir( $path = '' ) {
		return WPTELEGRAM_WIDGET_DIR . $path;
	}

	/**
	 * Retrieve URL path to the plugin.
	 *
	 * @since 1.7.1
	 * @param string $path Path to append.
	 * @return string URL with optional path appended
	 */
	public function url( $path = '' ) {
		return WPTELEGRAM_WIDGET_URL . $path;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}
}
