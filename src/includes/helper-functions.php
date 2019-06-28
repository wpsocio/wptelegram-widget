<?php

if ( ! function_exists( 'wptelegram_widget' ) ) {
	/**
	 * Get or display the widget
	 *
	 * @since 1.0.0
	 * 
	 * @param  array   $args Shortcode Params
	 * @param  boolean $echo Whether to display or return
	 * 
	 * @return NULL|string        The html output
	 */
	function wptelegram_widget( $args = array(), $echo = true ) {
		$output = WPTelegram_Widget_Public::post_embed_shortcode( $args );
		if ( $echo ) {
			echo $output;
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
	 * @param  array   $args Shortcode Params
	 * @param  boolean $echo Whether to display or return
	 * 
	 * @return NULL|string        The html output
	 */
	function wptelegram_ajax_widget( $args = array(), $echo = true ) {
		$output = WPTelegram_Widget_Public::ajax_widget_shortcode( $args );
		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
}