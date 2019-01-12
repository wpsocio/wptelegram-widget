<?php

/**
 * Provide a public-facing view for the widget
 *
 * Available vars:
 * $posts, $post, $wp_did_header, $wp_query, $wp_rewrite,
 * $wpdb, $wp_version, $wp, $id, $comment, $user_ID
 *
 * $message_view_urls, $widget_width
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

	foreach ( $message_view_urls as $src ) : ?>

		<div class="wptelegram-widget-message">

			<iframe frameborder="0" scrolling="no" width="<?php echo esc_attr( $widget_width ); ?>%" src="<?php echo esc_attr( $src ); ?>">Your Browser Does Not Support iframes!</iframe>

		</div>

	<?php endforeach; ?>
</div>