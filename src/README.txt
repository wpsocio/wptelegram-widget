=== WP Telegram Widget and Join Link ===
Contributors: manzoorwanijk
Donate link: https://paypal.me/manzoorwanijk
Tags: telegram, feed, widget, channel, group
Requires at least: 4.7
Tested up to: 5.2.4
Requires PHP: 5.6
Stable tag: 1.7.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display the Telegram Public Channel or Group Feed in a WordPress widget or anywhere you want using a simple shortcode.

== Description ==
Display the Telegram Public Channel or Group Feed in a WordPress widget or anywhere you want using a simple shortcode.

== Excellent Support ==

**Join the Chat**

We have a public group on Telegram to provide help setting up the plugin, discuss issues, features, translations etc. Join [@WPTelegramChat](https://t.me/WPTelegramChat)
For rules, see the pinned message. No spam please.

== Features ==

*	Provides an ajax widget to display channel feed
*	Ajax widget contains a Join Channel link
*	Pulls updates automatically from Telegram
*	Uses a responsive widget to display the feed
*	Fits anywhere you want it to be
*	The received messages can be seen from /wp-admin
*	Automatically removes deleted messages
*	Can be displayed using a shortcode
*	Available as a Gutengerg block
*	Allows embeding of Telegram public channel messages
*	Can be extended with custom code

## Widget Info
Goto **Appearance** > **Widgets** and click/drag **WP Telegram Widget** and place it where you want it to be.

Alternately, you can use the below shortcode.

Inside page or post content:

`[wptelegram-widget num_messages="5" widget_width="100" author_photo="auto"]`

Inside the theme templates
~~~
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
~~~
or

~~~
<?php
    echo do_shortcode( '[wptelegram-widget num_messages="5" widget_width="100" author_photo="always_show"]' );
?>
~~~

**Get in touch**

*	Website [wptelegram.com](https://wptelegram.com)
*	Telegram [@WPTelegram](https://t.me/WPTelegram)
*	Facebook [@WPTelegram](https://fb.com/WPTelegram)
*	Twitter [@WPTelegram](https://twitter.com/WPTelegram)

**Contribution**
Development occurs on [Github](https://github.com/manzoorwanijk/wptelegram-widget), and all contributions welcome.

**Translations**

Many thanks to the translators for the great job!

* [Алексей Семёнов](https://profiles.wordpress.org/els7777) (Russian)
* [robertskiba](https://profiles.wordpress.org/robertskiba/) (German)

* Note: You can also contribute in translating this plugin into your local language. Join the Chat (above)


== Installation ==


1. Upload the `wptelegram-widget` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the Plugins menu in WordPress. After activation, you should see the menu of this plugin the the admin
3. Configure the plugin.

**Enjoy!**

== Frequently Asked Questions ==

= How to create a Telegram Bot =

[How do I create a bot?](https://core.telegram.org/bots/faq#how-do-i-create-a-bot).


== Screenshots ==

1. Settings Page
2. Settings Page (Cont...)
3. Widget Settings (back-end)
4. Widget View (front-end)
5. Widget Messages List

== Changelog ==

= 1.7.3 =
* Updated bot token pattern.

= 1.7.2 =
* Fixed the issue of settings not being saved without Bot Token.

= 1.7.1 =
* Improved compatibility with different browsers.
* Improved the code as per WPCS.
* Fixed the issue of settings not being saved without Bot Token.

= 1.7.0 =
* Migrated from CMB2 to React for settings page.
* Added Guternberg block for Ajax Widget.
* Removed Widget Messages list in favour of Ajax Widget.
* Bot Token is no more required for the ajax widget to work.
* Made the ajax widget search form visible again.
* Search form results are now displayed in the same iframe, instead of new tab.
* Fixed the Google Script URL not being used.

== Upgrade Notice ==