<?php
/**
 * The file that defines the core plugin class
 *
 * @link       https://t.me/manzoorwanijk
 * @since      1.0.0
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/includes
 */

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
class WPTelegram_Widget {

	/**
	 * The single instance of the class.
	 *
	 * @since 1.0.0
	 * @var   WPTelegram_Widget|null $instance The instance.
	 */
	protected static $instance = null;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WPTelegram_Widget_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * @var      string    $options    The plugin options
	 */
	protected $options;

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

		$this->title = __( 'WP Telegram Widget', 'wptelegram-widget' );

		$this->plugin_name = strtolower( __CLASS__ );

		$this->load_dependencies();
		$this->set_options();

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
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once $this->dir( '/includes/class-wptelegram-widget-loader.php' );

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once $this->dir( '/includes/class-wptelegram-widget-i18n.php' );

		/**
		 * The class responsible for plugin options
		 */
		require_once $this->dir( '/includes/class-wptelegram-widget-options.php' );

		/**
		 * The classes responsible for WP REST API of the plugin.
		 */
		require_once $this->dir( '/includes/rest-api/class-wptelegram-widget-rest-controller.php' );
		require_once $this->dir( '/includes/rest-api/class-wptelegram-widget-settings-controller.php' );
		require_once $this->dir( '/includes/rest-api/class-wptelegram-widget-bot-api-controller.php' );

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once $this->dir( '/admin/class-wptelegram-widget-admin.php' );

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once $this->dir( '/public/class-wptelegram-widget-public.php' );

		/**
		 * Helper functions
		 */
		require_once $this->dir( '/includes/helper-functions.php' );

		/**
		 * Our widget classes
		 */
		require_once $this->dir( '/public/widgets/class-wptelegram-widget-widget.php' );

		require_once $this->dir( '/public/widgets/class-wptelegram-widget-ajax-widget.php' );

		require_once $this->dir( '/public/widgets/class-wptelegram-widget-join-channel.php' );

		/**
		 * The class responsible for loading WPTelegram_Bot_API library
		 */
		require_once $this->dir( '/includes/wptelegram-bot-api/class-wptelegram-bot-api-loader.php' );

		$this->loader = new WPTelegram_Widget_Loader();

	}

	/**
	 * Set the plugin options
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_options() {

		$this->options = new WPTelegram_Widget_Options( $this->plugin_name );

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

		$plugin_i18n = new WPTelegram_Widget_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new WPTelegram_Widget_Admin( $this );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'enqueue_block_editor_assets', $plugin_admin, 'enqueue_block_editor_assets' );

		$this->loader->add_filter( 'script_loader_tag', $plugin_admin, 'format_twitter_script_tag', 10, 3 );

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

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new WPTelegram_Widget_Public( $this );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'after_setup_theme', $plugin_public, 'do_upgrade' );

		$this->loader->add_action( 'init', $plugin_public, 'add_rewrite_rules' );

		$this->loader->add_filter( 'template_include', $plugin_public, 'set_embed_template', 99 );

		$this->loader->add_action( 'init', $plugin_public, 'may_be_fire_pull_updates' );

		$this->loader->add_action( 'init', $plugin_public, 'register_blocks' );

		$this->loader->add_action( 'wptelegram_widget_render_embedded_widget', $plugin_public, 'render_embedded_widget', 10, 1 );

		$this->loader->add_action( 'wptelegram_widget_render_embedded_post', $plugin_public, 'render_embedded_post', 10, 2 );

		$this->loader->add_action( 'wptelegram_widget_cron_pull_updates', $plugin_public, 'cron_pull_updates' );

		$this->loader->add_shortcode( 'wptelegram-ajax-widget', get_class( $plugin_public ), 'ajax_widget_shortcode' );

		$this->loader->add_shortcode( 'wptelegram-join-channel', get_class( $plugin_public ), 'join_channel_shortcode' );

		$this->loader->add_shortcode( 'wptelegram-widget', get_class( $plugin_public ), 'post_embed_shortcode' );

		$this->loader->add_shortcode( 'wptelegram_feed_widget', get_class( $plugin_public ), 'post_embed_shortcode' );

		// better be safe by using PHP_INT_MAX to make sure
		// some dumb people don't remove your schedule.
		$this->loader->add_filter( 'cron_schedules', $plugin_public, 'custom_cron_schedules', PHP_INT_MAX, 1 ); //phpcs:ignore WordPress.WP.CronInterval

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
	 * The suffix to use for plugin assets.
	 *
	 * @since 1.7.1
	 *
	 * @return string The suffix to use.
	 */
	public function suffix() {
		return ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WPTelegram_Widget_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}
}
