<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://t.me/manzoorwanijk
 * @since      1.0.0
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/public
 * @author     Manzoor Wani 
 */
class WPTelegram_Widget_Public {

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
	 * The Telegram API
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var WPTelegram_Bot_API $tg_api Telegram API Object
	 */
	private $tg_api;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since	1.0.0
	 * @param 	string    $title		Title of the plugin
	 * @param	string    $plugin_name	The name of the plugin.
	 * @param	string    $version		The version of this plugin.
	 */
	public function __construct( $title, $plugin_name, $version ) {

		$this->title = $title;
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Use minified libraries if SCRIPT_DEBUG is turned off
		$this->suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, WPTELEGRAM_WIDGET_URL . '/public/css/wptelegram-widget-public' . $this->suffix . '.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		
		wp_enqueue_script( $this->plugin_name, WPTELEGRAM_WIDGET_URL . '/public/js/wptelegram-widget-public' . $this->suffix . '.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Registers shortcode to display channel feed
	 *
	 * @since    1.0.0
	 */
	public static function feed_widget_shortcode( $atts ) {

		// fetch messages
		$messages = array_reverse( WPTG_Widget()->options()->get( 'messages', array() ) );

		if ( empty( $messages ) ) {
			return;
		}
		
		$defaults = array(
            'num_messages'	=> 5,
            'widget_width'	=> 100,
            'author_photo'	=> 'auto',
        );

        // use global options
        foreach ( $defaults as $key => $default ) {
    		$defaults[ $key ] = WPTG_Widget()->options()->get( $key, $default );
        }

	    $args = shortcode_atts( $defaults, $atts, 'wptelegram-widget' );

        $args = array_map( 'sanitize_text_field', $args );

		$username = WPTG_Widget()->options()->get( 'username' );

		$num_messages = absint( $args['num_messages'] );

		if ( ! $num_messages ) {
			$num_messages = 5;
		}

		$messages = array_slice( $messages, 0, $num_messages );

	    $widget_width = absint( $args['widget_width'] );
	    $author_photo = $args['author_photo'];

	    if ( ! $widget_width || $widget_width > 100 ) {
			$widget_width = 100;
		}

		switch ( $author_photo ) {
			case 'always_show':
				$author_photo = 'true';
				break;
			case 'always_hide':
				$author_photo = 'false';
				break;
			default:
				$author_photo = null;
				break;
		}
		
		$action = 'wptelegram_widget_view';
		if ( ! is_null( $author_photo ) ) {
			$userpic = $author_photo;
		}
		$widget_args = compact( 'action', 'userpic' );

	    set_query_var( 'widget_messages', $messages );
	    set_query_var( 'widget_width', $widget_width );
	    set_query_var( 'widget_args', $widget_args );

		ob_start();
        if ( $overridden_template = locate_template( 'wptelegram-widget/widget-template.php' ) ) {
		    /**
		     * locate_template() returns path to file.
		     * if either the child theme or the parent theme have overridden the template.
		     */

			if ( self::is_valid_template( $overridden_template ) ) {
			    load_template( $overridden_template );
			}
		} else {
		    /*
		     * If neither the child nor parent theme have overridden the template,
		     * we load the template from the 'partials' sub-directory of the directory this file is in.
		     */
		    load_template( dirname( __FILE__ ) . '/partials/widget-template.php' );
		}
        $html = ob_get_contents();
        ob_get_clean();
        return $html;
	}

	/**
	 * Check whether the template path is valid
	 *
	 * @since	1.3.0
	 * @param	string	$template	The template path
	 * @return	bool
	 */
	private static function is_valid_template( $template ) {
		/**
		 * Only allow templates that are in the active theme directory,
		 * parent theme directory, or the /wp-includes/theme-compat/ directory
		 * (prevent directory traversal attacks)
		 */
		$valid_paths = array_map( 'realpath',
			array(
				STYLESHEETPATH,
				TEMPLATEPATH,
				ABSPATH . WPINC . '/theme-compat/',
			)
		);

		$path = realpath( $template );

		foreach ( $valid_paths as $valid_path ) {
			if ( preg_match( '#\A' . preg_quote( $valid_path ) . '#', $path ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Pull the updates from Telegram
	 *
	 * @since    1.0.0
	 */
	public function trigger_pull_updates() {
		// Avoid infinite loop
		if ( isset( $_GET['action'] ) && 'wptelegram_widget_pull_updates' === $_GET['action'] ) {
			return;
		}

		$bot_token = WPTG_Widget()->options()->get( 'bot_token' );
		$username = WPTG_Widget()->options()->get( 'username' );

		if ( ! $bot_token || ! $username ) {
			return;
		}

		/**
		 * Send a non-blocking request to admin-post.php
		 * to reduce the processing time of the page
		 * The update process will be completed in the background
		 */
	    $admin_post_url = admin_url( 'admin-post.php' );
	    $args = array(
	    	'action'	=> 'wptelegram_widget_pull_updates',
    	);
		$post_url = add_query_arg( $args, $admin_post_url );
	    $args = array(
	    	'timeout'	=> 0.1,
	    	'blocking'	=> false,
    	);
		wp_remote_post( $post_url, $args );
	}

	/**
	 * Add custom schedules
	 *
	 * @since	1.0.0
     * 
	 */
	public function custom_cron_schedules( $schedules ) {
		$schedules['wptelegram_five_minutely'] = array(
			'interval'	=> 5 * MINUTE_IN_SECONDS, // Intervals in seconds
			'display'	=> __( 'Every 5 Minutes', 'wptelegram-widget' ),
		);
		return $schedules;
	}

	/**
	 * Upgrade the options etc.
	 */
	public function do_upgrade() {
		
		$option = 'wptelegram_widget_messages';
		$messages = get_option( $option, array() );

		if ( ! empty( $messages ) ) {

			WPTG_Widget()->options()->set( 'messages', $messages );

			delete_option( $option );
		}

		$transient = 'wptelegram_widget_last_update_id';
		if ( $update_id = (int) get_site_transient( $transient ) ) {
			WPTG_Widget()->options()->set( 'last_update_id', $update_id );
			delete_site_transient( $transient );
		}

		// set cron event in case of active plugin update
		if ( ! wp_next_scheduled ( 'wptelegram_widget_pull_updates' ) ) {
			wp_schedule_event( time(), 'wptelegram_five_minutely', 'wptelegram_widget_pull_updates' );
		}
	}
}
