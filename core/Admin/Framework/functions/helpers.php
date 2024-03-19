<?php
/**
 * Framework helper file.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Frontend
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! function_exists( 'csf_array_search' ) ) {
	/**
	 * Array search key & value
	 *
	 * @param  mixed $array main array.
	 * @param  mixed $key key.
	 * @param  mixed $value val.
	 * @return array
	 */
	function csf_array_search( $array, $key, $value ) {

		$results = array();

		if ( is_array( $array ) ) {
			if ( isset( $array[ $key ] ) && $array[ $key ] == $value ) {
				$results[] = $array;
			}

			foreach ( $array as $sub_array ) {
				$results = array_merge( $results, csf_array_search( $sub_array, $key, $value ) );
			}
		}

		return $results;

	}
}

if ( ! function_exists( 'csf_timeout' ) ) {
	/**
	 * Between Microtime
	 *
	 * @param  mixed $timenow  current time.
	 * @param  mixed $starttime start time.
	 * @param  mixed $timeout time out.
	 * @return mixed
	 */
	function csf_timeout( $timenow, $starttime, $timeout = 30 ) {
		return ( ( $timenow - $starttime ) < $timeout ) ? true : false;
	}
}


if ( ! function_exists( 'csf_wp_editor_api' ) ) {
	/**
	 *
	 * Check for wp editor api
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function csf_wp_editor_api() {
		global $wp_version;
		return version_compare( $wp_version, '4.8', '>=' );
	}
}
