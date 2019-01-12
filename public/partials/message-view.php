<?php

/**
 * Provide a public-facing view for a single widget message
 *
 * @link       https://t.me/manzoorwanijk
 * @since      1.4.0
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/public/partials
 */

$username = get_query_var( 'username', false );
$message_id = get_query_var( 'message_id', false );

if ( ! $username || ! $message_id ) {
	status_header( 404 );
	exit;
}

$message_id = sanitize_text_field( $message_id );

$saved_username = WPTG_Widget()->options()->get( 'username' );

if ( $saved_username !== $username ) {
	status_header( 401 );
	exit;
}

do_action( 'wptelegram_widget_render_single_message', $username, $message_id );

do_action( "wptelegram_widget_render_{$username}_single_message", $message_id );