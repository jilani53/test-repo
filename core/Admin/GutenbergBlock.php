<?php
/**
 * The plugin gutenberg block.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.4
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\SmartBrands\Admin;

use ShapedPlugin\SmartBrands\Admin\GutenbergBlock\Shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom Gutenberg Block.
 */
class GutenbergBlock {

	/**
	 * Block Initializer.
	 */
	public function __construct() {
		new Shortcode();
	}

}
