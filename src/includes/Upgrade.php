<?php
/**
 * Do the necessary db upgrade
 *
 * @link       https://t.me/manzoorwanijk
 * @since      x.y.z
 *
 * @package    WPTelegram\Widget
 * @subpackage WPTelegram\Widget\includes
 */

namespace WPTelegram\Widget\includes;

/**
 * Do the necessary db upgrade.
 *
 * Do the nececessary the incremental upgrade.
 *
 * @package    WPTelegram\Widget
 * @subpackage WPTelegram\Widget\includes
 * @author     Manzoor Wani <@manzoorwanijk>
 */
class Upgrade extends BaseClass {

	/**
	 * Do the necessary db upgrade, if needed
	 *
	 * @since    1.3.0
	 */
	public function do_upgrade() {

		$current_version = get_option( 'wptelegram_widget_ver', '1.2.0' );

		if ( ! version_compare( $current_version, $this->plugin->version(), '<' ) ) {
			return;
		}

		$plugin_settings = WPTG_Widget()->options()->get_data();
		$is_new_install  = empty( $plugin_settings );

		do_action( 'wptelegram_widget_before_do_upgrade', $current_version );

		$version_upgrades = array();

		if ( ! $is_new_install ) {
			// the sequential upgrades
			// subsequent upgrade depends upon the previous one.
			$version_upgrades = array(
				'1.3.0', // first upgrade.
				'1.4.0',
				'1.5.0',
				'1.6.1',
				'1.7.0',
				'1.9.0',
				'2.0.0',
			);
		}

		// always.
		if ( ! in_array( $this->plugin->version(), $version_upgrades, true ) ) {
			$version_upgrades[] = $this->plugin->version();
		}

		foreach ( $version_upgrades as $target_version ) {

			if ( version_compare( $current_version, $target_version, '<' ) ) {

				$this->upgrade_to( $target_version );

				$current_version = $target_version;
			}
		}

		do_action( 'wptelegram_widget_after_do_upgrade', $current_version );
	}

	/**
	 * Upgrade to a specific version
	 *
	 * @since 1.3.0
	 *
	 * @param string $version The plugin verion to upgrade to.
	 */
	private function upgrade_to( $version ) {

		// 2.0.1 becomes 2_0_1
		$_version = str_replace( '.', '_', $version );

		$method = array( $this, "upgrade_to_{$_version}" );

		if ( is_callable( $method ) ) {

			call_user_func( $method );
		}

		update_option( 'wptelegram_widget_ver', $version );
	}

	/**
	 * Upgrade to version 1.3.0
	 *
	 * @since    1.4.0
	 */
	private function upgrade_to_1_3_0() {

		$option   = 'wptelegram_widget_messages';
		$messages = get_option( $option, array() );

		if ( ! empty( $messages ) ) {

			WPTG_Widget()->options()->set( 'messages', $messages );
		}

		delete_option( $option );

		$transient = 'wptelegram_widget_last_update_id';
		$update_id = (int) get_site_transient( $transient );
		if ( $update_id ) {
			WPTG_Widget()->options()->set( 'last_update_id', $update_id );
			delete_site_transient( $transient );
		}

		// set cron event in case of active plugin update.
		if ( ! wp_next_scheduled( 'wptelegram_widget_pull_updates' ) ) {
			wp_schedule_event( time(), 'wptelegram_five_minutely', 'wptelegram_widget_pull_updates' );
		}
	}

	/**
	 * Upgrade to version 1.4.0
	 *
	 * @since    1.4.0
	 */
	private function upgrade_to_1_4_0() {
		flush_rewrite_rules();
	}

	/**
	 * Upgrade to version 1.5.0
	 *
	 * @since    1.5.0
	 */
	private function upgrade_to_1_5_0() {
		wp_clear_scheduled_hook( 'wptelegram_widget_pull_updates' );

		// set cron event in case of active plugin update.
		if ( ! wp_next_scheduled( 'wptelegram_widget_cron_pull_updates' ) ) {
			wp_schedule_event( time(), 'wptelegram_five_minutely', 'wptelegram_widget_cron_pull_updates' );
		}
	}

	/**
	 * Upgrade to version 1.6.1
	 *
	 * @since    1.6.1
	 */
	private function upgrade_to_1_6_1() {
		flush_rewrite_rules();
	}

	/**
	 * Upgrade to version 1.7.0
	 *
	 * @since    1.7.0
	 */
	private function upgrade_to_1_7_0() {

		$google_script_url = WPTG_Widget()->options()->get( 'google_script_url' );

		if ( ! empty( $google_script_url ) ) {
			$telegram_blocked = 'yes';
		} else {
			$telegram_blocked = 'no';
		}

		WPTG_Widget()->options()->set( 'telegram_blocked', $telegram_blocked );
	}

	/**
	 * Upgrade to version 1.9.0
	 *
	 * @since    1.9.0
	 */
	private function upgrade_to_1_9_0() {

		$username = WPTG_Widget()->options()->get( 'username' );

		$field_values = array(
			'join_link_post_types' => array( 'post' ),
			'join_link_position'   => 'after_content',
		);

		if ( $username ) {
			$field_values['join_link_url']  = sprintf( 'https://t.me/%s', $username );
			$field_values['join_link_text'] = sprintf( 'Join @%s on Telegram', $username );
		}

		foreach ( $field_values as $field => $value ) {
			WPTG_Widget()->options()->set( $field, $value );
		}
	}

	/**
	 * Upgrade to version x.y.z
	 *
	 * @since    x.y.z
	 */
	private function upgrade_to_2_0_0() {

		$ajax_widget = array(
			'username'      => '',
			'widget_width'  => array(
				'field'   => 'width',
				'default' => '100%',
			),
			'widget_height' => array(
				'field'   => 'height',
				'default' => '600',
			),
		);

		$old_fields = array(
			'ajax_widget'   => $ajax_widget,
			'legacy_widget' => array_merge(
				$ajax_widget,
				array(
					'bot_token'    => '',
					'author_photo' => array(
						'field'   => 'author_photo',
						'default' => 'auto',
					),
					'num_messages' => array(
						'field'   => 'num_messages',
						'default' => '5',
					),
				)
			),
			'join_link'     => array(
				'join_link_text'       => array(
					'field' => 'text',
				),
				'join_link_url'        => array(
					'field' => 'url',
				),
				'join_link_post_types' => array(
					'field'   => 'post_types',
					'default' => array( 'post' ),
				),
				'join_link_position'   => array(
					'field'   => 'position',
					'default' => 'after_content',
				),
				'join_link_priority'   => array(
					'field'   => 'priority',
					'default' => '10',
				),
			),
			'advanced'      => array(
				'telegram_blocked'  => array(
					'field'   => 'telegram_blocked',
					'default' => false,
				),
				'google_script_url' => '',
			),
		);

		$options = WPTG_Widget()->options();

		$new_settings = array();

		foreach ( $old_fields as $section => $fields ) {
			foreach ( $fields as $field => $data ) {
				$default = ! empty( $data['default'] ) ? $data['default'] : '';
				$value   = $options->get( $field, $default );

				$new_field = ! empty( $data['field'] ) ? $data['field'] : $field;

				$new_settings[ $section ][ $new_field ] = $value;
			}
		}

		$username         = $options->get( 'username' );
		$messages         = $options->get( 'messages', array() );
		$last_update_id   = $options->get( 'last_update_id' );
		$telegram_blocked = $options->get( 'telegram_blocked' );

		$new_settings['messages'][ strtolower( $username ) ] = $messages;

		$new_settings['last_update_id'] = $last_update_id;

		$new_settings['advanced']['telegram_blocked'] = 'yes' === $telegram_blocked;

		$options->set_data( $new_settings )->update_data();
	}
}
