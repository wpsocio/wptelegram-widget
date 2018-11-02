<?php

/**
 * 
 * @link              https://t.me/manzoorwanijk
 * @since             1.0.0
 * @package           WPTelegram_Widget
 *
 * @wordpress-plugin
 * Plugin Name:       WP Telegram Widget
 * Plugin URI:        https://t.me/WPTelegram
 * Description:       Display the Telegram Public Channel or Group Feed in a WordPress widget or anywhere you want using a shortcode.
 * Version:           1.3.3
 * Author:            Manzoor Wani
 * Author URI:        https://t.me/manzoorwanijk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wptelegram-widget
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'WPTELEGRAM_WIDGET_VER', '1.3.3' );

define( 'WPTELEGRAM_WIDGET_BASENAME', plugin_basename( __FILE__ ) );

define( 'WPTELEGRAM_WIDGET_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

define( 'WPTELEGRAM_WIDGET_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wptelegram-widget-activator.php
 */
function activate_wptelegram_widget() {
	require_once WPTELEGRAM_WIDGET_DIR . '/includes/class-wptelegram-widget-activator.php';
	WPTelegram_Widget_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wptelegram-widget-deactivator.php
 */
function deactivate_wptelegram_widget() {
	require_once WPTELEGRAM_WIDGET_DIR . '/includes/class-wptelegram-widget-deactivator.php';
	WPTelegram_Widget_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wptelegram_widget' );
register_deactivation_hook( __FILE__, 'deactivate_wptelegram_widget' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require WPTELEGRAM_WIDGET_DIR . '/includes/class-wptelegram-widget.php';

/**
 * Begins execution of the plugin and acts as the main instance of WPTelegram_Widget.
 *
 * Returns the main instance of WPTelegram_Login to prevent the need to use globals.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function WPTG_Widget() {

	return WPTelegram_Widget::instance();
}

// Fire
WPTG_Widget();

define( 'WPTELEGRAM_WIDGET_LOADED', true );