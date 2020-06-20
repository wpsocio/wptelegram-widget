<?php
/**
 * The template to interept request to /v by Telegram scrip.
 *
 * @link       https://t.me/manzoorwanijk
 * @since      1.9.3
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/public/partials
 */

header( 'Content-Type: application/json; charset=utf-8' );
echo 'true';
