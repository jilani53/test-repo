<?php
/**
 * Views class for Shortcode generator options.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Admin/Views
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

namespace ShapedPlugin\SmartBrands\Admin\Views;

use ShapedPlugin\SmartBrands\Admin\Framework\Classes\SPF_SMART_BRANDS;
use ShapedPlugin\SmartBrands\Admin\Views\General;
use ShapedPlugin\SmartBrands\Admin\Views\Display;
use ShapedPlugin\SmartBrands\Admin\Views\Carousel;

/**
 * Views class to create all metabox options for Smart Brand Shortcode generator.
 */
class Views {

	/**
	 * Create metabox for the Generator options.
	 *
	 * @param string $prefix Metabox key prefix.
	 * @return void
	 */
	public static function view_heading( $prefix ) {
		SPF_SMART_BRANDS::createMetabox(
			$prefix,
			array(
				'title'        => __( 'Views Generator Header', 'smart-brands-for-woocommerce' ),
				'post_type'    => 'smart_brand_sc',
				'class'        => 'smart-brand-metabox-tabs',
				'nav'          => 'inline',
				'show_restore' => false,
				'context'      => 'normal',
				'preview'      => true,
			)
		);

		Heading::section( $prefix );
	}

	/**
	 * Create metabox for the Generator options.
	 *
	 * @param string $prefix Metabox key prefix.
	 * @return void
	 */
	public static function metaboxes( $prefix ) {
		SPF_SMART_BRANDS::createMetabox(
			$prefix,
			array(
				'title'        => __( 'Views Generator Settings', 'smart-brands-for-woocommerce' ),
				'post_type'    => 'smart_brand_sc',
				'theme'        => 'light',
				'class'        => 'smart-brand-metabox-tabs',
				'nav'          => 'inline',
				'show_restore' => false,
			)
		);

		// Serialized Ahead!
		General::section( $prefix );
		Display::section( $prefix );
		Carousel::section( $prefix ); // Depend on Carousel layout.

	}

}
