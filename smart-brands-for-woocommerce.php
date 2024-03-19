<?php
/**
 * Smart Brands for WooCommerce
 *
 * @package           Smart Brands for WooCommerce
 * @author            ShapedPlugin
 * @license           GPL-2.0-or-later
 *
 * Plugin Name:       Smart Brands for WooCommerce
 * Plugin URI:        https://shapedplugin.com/plugin/smart-brands-for-woocommerce/
 * Description:       Smart Brands for WooCommerce plugin allows you to add brands for products and gain credibility by displaying them in your WooCommerce shop.
 * Version:           1.1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * WC tested up to:   8.6.1
 * Author:            ShapedPlugin LLC
 * Author URI:        https://shapedplugin.com/
 * Text Domain:       smart-brands-for-woocommerce
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class_exists( 'ShapedPlugin\SmartBrands\SmartBrands' ) || require_once __DIR__ . '/vendor/autoload.php';

use ShapedPlugin\SmartBrands\SmartBrands;

define( 'SMART_BRANDS_VERSION', '1.1.0' );
define( 'SMART_BRANDS_FILE', __FILE__ );
define( 'SMART_BRANDS_PATH', dirname( SMART_BRANDS_FILE ) );

// Declare that the plugin is compatible with WooCommerce High-Performance order storage feature.
add_action(
	'before_woocommerce_init',
	function() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);

if ( ! function_exists( 'smart_brand_for_wc' ) ) {
	/**
	 * Shortcode converter function
	 *
	 * @param  int $id shortcode id.
	 * @return void
	 */
	function smart_brand_for_wc( $id ) {
		echo do_shortcode( '[smart_brand_for_wc id="' . $id . '"]' );
	}
}

/**
 * Init the SmartBrands plugin.
 *
 * @since 1.0.0
 */
function launch_smart_brands() {
	// Launch the plugin.
	$smart_brand_for_wc = new SmartBrands();
	$smart_brand_for_wc->run();
}

// Launch the plugin.
launch_smart_brands();
