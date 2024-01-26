<?php
/**
 * Provide a public-facing view for the widget
 *
 * Available vars:
 * $posts, $post, $wp_did_header, $wp_query, $wp_rewrite,
 * $wpdb, $wp_version, $wp, $id, $comment, $user_ID
 *
 * $link, $text, $attributes
 *
 * @link       https://wpsocio.com
 * @since      1.0.0
 *
 * @package    WPTelegram\Widget
 * @subpackage WPTelegram\Widget\shared\partials
 */

// This file should primarily consist of HTML with a little bit of PHP.
?>
<div class="wp-block-wptelegram-widget-join-channel aligncenter">
	<a href="<?php echo esc_attr( $link ); ?>" class="components-button join-link is-large has-text has-icon" <?php echo $attributes; // phpcs:ignore ?>>
		<svg width="19px" height="16px" viewBox="0 0 19 16" role="img" aria-hidden="true" focusable="false">
			<g>
				<path d="M0.465,6.638 L17.511,0.073 C18.078,-0.145 18.714,0.137 18.932,0.704 C19.009,0.903 19.026,1.121 18.981,1.33 L16.042,15.001 C15.896,15.679 15.228,16.111 14.549,15.965 C14.375,15.928 14.211,15.854 14.068,15.748 L8.223,11.443 C7.874,11.185 7.799,10.694 8.057,10.345 C8.082,10.311 8.109,10.279 8.139,10.249 L14.191,4.322 C14.315,4.201 14.317,4.002 14.195,3.878 C14.091,3.771 13.926,3.753 13.8,3.834 L5.602,9.138 C5.112,9.456 4.502,9.528 3.952,9.333 L0.486,8.112 C0.077,7.967 -0.138,7.519 0.007,7.11 C0.083,6.893 0.25,6.721 0.465,6.638 Z" ></path>
			</g>
		</svg>
		<?php echo esc_html( $text ); ?>
	</a>
</div>
<?php
