<?php
/**
 * Provide a public-facing view for the whole channel
 *
 * @link       https://t.me/manzoorwanijk
 * @since      x.y.z
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/public/partials
 */

$username   = get_query_var( 'username', false );

if ( ! $username ) {
	status_header( 404 );
	exit;
}

$username = sanitize_text_field( $username );

do_action( 'wptelegram_widget_render_embedded_widget', $username );

do_action( "wptelegram_widget_render_{$username}_embedded_widget" );
