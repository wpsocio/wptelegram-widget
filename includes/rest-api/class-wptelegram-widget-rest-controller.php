<?php
/**
 * WP REST API functionality of the plugin.
 *
 * @link       https://t.me/manzoorwanijk
 * @since      1.7.0
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/includes
 */

/**
 * Base class for all the endpoints.
 *
 * @since 1.7.0
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/includes
 * @author     Manzoor Wani <@manzoorwanijk>
 */
abstract class WPTelegram_Widget_REST_Controller extends WP_REST_Controller {

	/**
	 * The namespace of this controller's route.
	 *
	 * @since 1.7.0
	 * @var string
	 */
	protected $namespace = 'wptelegram-widget/v1';

	/**
	 * The base of this controller's route.
	 *
	 * @var string
	 */
	protected $rest_base;
}
