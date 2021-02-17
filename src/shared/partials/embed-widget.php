<?php
/**
 * Provide a public-facing view for the widget
 *
 * Available vars:
 * $posts, $post, $wp_did_header, $wp_query, $wp_rewrite,
 * $wpdb, $wp_version, $wp, $id, $comment, $user_ID
 *
 * $embedded_widget_url, $widget_width, $widget_height
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
	<div class="wptelegram-widget-embed">
		<iframe frameborder="0" width="<?php echo esc_attr( $widget_width ); ?>" height="<?php echo esc_attr( $widget_height ); ?>" src="<?php echo esc_attr( $embedded_widget_url ); ?>">Your Browser Does Not Support iframes!</iframe>
	</div>
</div>
<?php
