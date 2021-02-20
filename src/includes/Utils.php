<?php
/**
 * Utility methods.
 *
 * @link       https://t.me/manzoorwanijk
 * @since      x.y.z
 *
 * @package    WPTelegram\Widget
 * @subpackage WPTelegram\Widget\includes
 */

namespace WPTelegram\Widget\includes;

use WPTelegram\Widget\includes\restApi\RESTController;
use WP_Error;

/**
 * Utility methods.
 *
 * Utility methods.
 *
 * @package    WPTelegram\Widget
 * @subpackage WPTelegram\Widget\includes
 * @author     Manzoor Wani <@manzoorwanijk>
 */
class Utils {

	/**
	 * Filter WP REST API errors.
	 *
	 * @param mixed            $response Result to send to the client. Usually a WP_REST_Response or WP_Error.
	 * @param array            $handler  Route handler used for the request.
	 * @param \WP_REST_Request $request  Request used to generate the response.
	 *
	 * @since    x.y.z
	 */
	public static function fitler_rest_errors( $response, $handler, $request ) {

		$matches_route    = 0 === strpos( ltrim( $request->get_route(), '/' ), RESTController::NAMESPACE );
		$is_invalid_param = is_wp_error( $response ) && 'rest_invalid_param' === $response->get_error_code();

		if ( ! $is_invalid_param || ! $matches_route ) {
			return $response;
		}

		$data = $response->get_error_data();

		$invalid_params = array();
		if ( ! empty( $data['params'] ) ) {
			foreach ( $data['params'] as $error ) {
				preg_match( '/\A\S+/', $error, $match );
				$invalid_params[ $match[0] ] = $error;
			}
		}

		$data['params'] = $invalid_params;

		return new WP_Error(
			$response->get_error_code(),
			$response->get_error_message(),
			$data
		);
	}

	/**
	 * Create a regex from the given pattern.
	 *
	 * @since    x.y.z
	 *
	 * @param string  $pattern     The pattern to match.
	 * @param boolean $allow_empty Whether to allow an ampty string.
	 * @param boolean $match_full  Whether to match the complete word.
	 * @param string  $delim       The delimiter to use.
	 *
	 * @return string
	 */
	public static function enhance_regex( $pattern, $allow_empty = false, $match_full = true, $delim = '' ) {
		if ( $allow_empty ) {
			$pattern = '(?:' . $pattern . ')?';
		}
		if ( $match_full ) {
			$pattern = '\A' . $pattern . '\Z';
		}
		if ( $delim ) {
			$pattern = $delim . $pattern . $delim;
		}
		return $pattern;
	}
}
