<?php
/**
 * Contains the helper functions.
 *
 * @link       https://t.me/manzoorwanijk
 * @since      1.0.0
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/includes
 */

if ( ! function_exists( 'wptelegram_widget' ) ) {
	/**
	 * Get or display the widget
	 *
	 * @since 1.0.0
	 *
	 * @param array   $args Shortcode Params.
	 * @param boolean $echo Whether to display or return.
	 *
	 * @return NULL|string        The html output
	 */
	function wptelegram_widget( $args = array(), $echo = true ) {
		$output = \WPTelegram\Widget\shared\shortcodes\LegacyWidget::render( $args );
		if ( $echo ) {
			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput
		} else {
			return $output;
		}
	}
}

if ( ! function_exists( 'wptelegram_ajax_widget' ) ) {
	/**
	 * Get or display the ajax widget
	 *
	 * @since 1.0.0
	 *
	 * @param  array   $args Shortcode Params.
	 * @param  boolean $echo Whether to display or return.
	 *
	 * @return NULL|string        The html output
	 */
	function wptelegram_ajax_widget( $args = array(), $echo = true ) {
		$output = \WPTelegram\Widget\shared\shortcodes\AjaxWidget::render( $args );
		if ( $echo ) {
			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput
		} else {
			return $output;
		}
	}
}

if ( ! function_exists( 'wptelegram_join_channel' ) ) {
	/**
	 * Display join channel button.
	 *
	 * @since 1.8.0
	 *
	 * @param  array   $args Shortcode Params.
	 * @param  boolean $echo Whether to display or return.
	 *
	 * @return NULL|string        The html output
	 */
	function wptelegram_join_channel( $args = array(), $echo = true ) {
		$output = \WPTelegram\Widget\shared\shortcodes\JoinChannel::render( $args );
		if ( $echo ) {
			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput
		} else {
			return $output;
		}
	}
}

if ( ! function_exists( 'wptelegram_get_jed_locale_data' ) ) {

	/**
	 * Returns Jed-formatted localization data.
	 *
	 * @source gutenberg_get_jed_locale_data()
	 *
	 * @since 1.5.0
	 *
	 * @param  string $domain Translation domain.
	 *
	 * @return array
	 */
	function wptelegram_get_jed_locale_data( $domain ) {
		$translations = get_translations_for_domain( $domain );

		$locale = array(
			'' => array(
				'domain' => $domain,
				'lang'   => is_admin() ? get_user_locale() : get_locale(),
			),
		);

		if ( ! empty( $translations->headers['Plural-Forms'] ) ) {
			$locale['']['plural_forms'] = $translations->headers['Plural-Forms'];
		}

		foreach ( $translations->entries as $msgid => $entry ) {
			$locale[ $msgid ] = $entry->translations;
		}

		return $locale;
	}
}
