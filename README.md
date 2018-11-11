# WP Telegram Channel Widget

**Contributors:**      [manzoorwanijk](https://github.com/manzoorwanijk)  
**Tags:**              telegram, feed, widget, channel, group  
**Requires at least:** 3.8.0  
**Tested up to:**      4.9.8  
**Stable tag:**        1.3.4  
**License:**           GPLv2 or later  
**License URI:**       [http://www.gnu.org/licenses/gpl-2.0.html](http://www.gnu.org/licenses/gpl-2.0.html)  

[![Wordpress plugin](https://img.shields.io/wordpress/plugin/v/wptelegram-widget.svg)](https://wordpress.org/plugins/wptelegram-widget/)
[![Wordpress](https://img.shields.io/wordpress/plugin/dt/wptelegram-widget.svg)](https://wordpress.org/plugins/wptelegram-widget/)
[![Wordpress rating](https://img.shields.io/wordpress/plugin/r/wptelegram-widget.svg)](https://wordpress.org/plugins/wptelegram-widget/)

Complete contributors list found here: [github.com/manzoorwanijk/wptelegram-widget/graphs/contributors](https://github.com/manzoorwanijk/wptelegram-widget/graphs/contributors)

**[Download plugin on wordpress.org](https://wordpress.org/plugins/wptelegram-widget/)**

## Description

Display the Telegram Public Channel or Group Feed in a WordPress widget or anywhere you want using a shortcode.

## Features:

* Pulls updates automatically from Telegram
* Uses a responsive widget to display the feed
* Fits anywhere you want it to be
* The received messages can be seen from `/wp-admin`
* Automatically removes deleted messages
* Can be displayed using a shortcode
* Can be extended with custom code

## Widget Info
Goto **Appearance** > **Widgets** and click/drag **WP Telegram Widget** and place it where you want it to be.

Alternately, you can use the below shortcode.

Inside page or post content:

`[wptelegram-widget num_messages="5" widget_width="100" author_photo="auto"]`

Inside the theme templates
```php
<?php
if ( function_exists( 'wptelegram_widget' ) ) {
    $args = array(
        // 'num_messages'    => 5,
        // 'widget_width'    => 100,
        // 'author_photo'    => 'auto',
    );

    wptelegram_widget( $args );
}
?>
```
or
```php
<?php echo do_shortcode( '[wptelegram-widget num_messages="5" widget_width="100" author_photo="always_show"]' ); ?>
```

### Contribution
Development occurs on Github, and all contributions welcome.

## Translation
If you are looking to provide language translation files, Please do so via [WordPress Plugin Translations](https://translate.wordpress.org/projects/wp-plugins/wptelegram-widget).

## Installation

1. Upload the `wptelegram-widget` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the Plugins menu in WordPress. After activation, you should see the menu of this plugin the the admin
3. Configure the plugin.

