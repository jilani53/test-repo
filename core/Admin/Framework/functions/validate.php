<?php
/**
 * Framework validate file.
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

if ( ! function_exists( 'csf_validate_email' ) ) {
	/**
	 *
	 * Email validate
	 *
	 * @param  string $value valid email address.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function csf_validate_email( $value ) {

		if ( ! filter_var( $value, FILTER_VALIDATE_EMAIL ) ) {
			return esc_html__( 'Please enter a valid email address.', 'smart-brands-for-woocommerce' );
		}

	}
}

if ( ! function_exists( 'csf_validate_numeric' ) ) {
	/**
	 * Numeric validate
	 *
	 * @param  int $value int.
	 * @return int
	 */
	function csf_validate_numeric( $value ) {

		if ( ! is_numeric( $value ) ) {
			return esc_html__( 'Please enter a valid number.', 'smart-brands-for-woocommerce' );
		}

	}
}

if ( ! function_exists( 'csf_validate_required' ) ) {
	/**
	 * Required validate
	 *
	 * @param  string $value string.
	 * @return string
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function csf_validate_required( $value ) {

		if ( empty( $value ) ) {
			return esc_html__( 'This field is required.', 'smart-brands-for-woocommerce' );
		}

	}
}

if ( ! function_exists( 'csf_validate_url' ) ) {
	/**
	 * URL validate
	 *
	 * @param  string $value check valid URL.
	 * @return string
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function csf_validate_url( $value ) {

		if ( ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
			return esc_html__( 'Please enter a valid URL.', 'smart-brands-for-woocommerce' );
		}

	}
}

if ( ! function_exists( 'csf_customize_validate_email' ) ) {
	/**
	 *
	 * Email validate for Customizer
	 *
	 * @param object $validity Email validity.
	 * @param string $value The Email.
	 * @param object $wp_customize Customize option.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function csf_customize_validate_email( $validity, $value, $wp_customize ) {

		if ( ! sanitize_email( $value ) ) {
			$validity->add( 'required', esc_html__( 'Please enter a valid email address.', 'smart-brands-for-woocommerce' ) );
		}

		return $validity;

	}
}

if ( ! function_exists( 'csf_customize_validate_numeric' ) ) {
	/**
	 *
	 * Numeric validate for Customizer
	 *
	 * @param object $validity Numeric validity.
	 * @param string $value The Numeric.
	 * @param object $wp_customize Customize option.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function csf_customize_validate_numeric( $validity, $value, $wp_customize ) {

		if ( ! is_numeric( $value ) ) {
			$validity->add( 'required', esc_html__( 'Please enter a valid number.', 'smart-brands-for-woocommerce' ) );
		}

		return $validity;

	}
}

if ( ! function_exists( 'csf_customize_validate_required' ) ) {
	/**
	 *
	 * Required validate for Customizer
	 *
	 * @param object $validity Required validity.
	 * @param string $value The Required.
	 * @param object $wp_customize Customize option.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function csf_customize_validate_required( $validity, $value, $wp_customize ) {
		if ( empty( $value ) ) {
			$validity->add( 'required', esc_html__( 'This field is required.', 'smart-brands-for-woocommerce' ) );
		}
		return $validity;
	}
}

if ( ! function_exists( 'csf_customize_validate_url' ) ) {
	/**
	 *
	 * URL validate for Customizer
	 *
	 * @param object $validity URL validity.
	 * @param string $value The URL.
	 * @param object $wp_customize Customize option.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function csf_customize_validate_url( $validity, $value, $wp_customize ) {

		if ( ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
			$validity->add( 'required', esc_html__( 'Please enter a valid URL.', 'smart-brands-for-woocommerce' ) );
		}

		return $validity;

	}
}
