<?php

/**
 * Fired during plugin activation
 *
 * @link       https://t.me/manzoorwanijk
 * @since      1.0.0
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/includes
 * @author     Manzoor Wani 
 */
class WPTelegram_Widget_Activator {

	/**
	 * Create the cron(s)
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if ( ! wp_next_scheduled ( 'wptelegram_widget_pull_updates' ) ) {
	        wp_schedule_event( time(), 'wptelegram_five_minutely', 'wptelegram_widget_pull_updates' );
	    }
	}

}
