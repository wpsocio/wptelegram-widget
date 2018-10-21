<?php

/**
 * Provide a public-facing view for the widget
 *
 * Available vars:
 * $posts, $post, $wp_did_header, $wp_query, $wp_rewrite,
 * $wpdb, $wp_version, $wp, $id, $comment, $user_ID
 *
 * $widget_messages, $widget_width, $widget_args
 *
 * @link       https://t.me/manzoorwanijk
 * @since      1.0.0
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/public/partials
 */
// This file should primarily consist of HTML with a little bit of PHP.
?>
<div class="wptelegram-widget-wrap">
	<?php

	$admin_post_url = admin_url( 'admin-post.php' );

	foreach ( $widget_messages as $message_id ) :
		$widget_args['message_id'] = $message_id; ?>

		<div class="wptelegram-widget-message">

			<iframe frameborder="0" scrolling="no" width="<?php echo esc_attr( $widget_width ); ?>%" src="<?php echo esc_attr( add_query_arg( $widget_args, $admin_post_url ) ); ?>">Your Browser Does Not Support iframes!</iframe>

		</div>

	<?php endforeach; ?>
</div>