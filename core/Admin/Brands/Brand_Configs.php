<?php
/**
 * Brand taxonomy fields.
 *
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Admin/Brands
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

namespace ShapedPlugin\SmartBrands\Admin\Brands;

use ShapedPlugin\SmartBrands\Admin\Framework\Classes\SPF_SMART_BRANDS;

// Cannot access directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * This class is responsible for brand taxonomy options.
 *
 * @since      1.0.0
 */
class Brand_Configs {

	/**
	 * Brand taxonomy fields configurations.
	 *
	 * @since 1.0.0
	 * @param string $prefix sp_smart_brand_taxonomy_options.
	 */
	public static function section( $prefix ) {
		SPF_SMART_BRANDS::createSection(
			$prefix,
			array(
				'fields' => array(
					array(
						'id'    => 'smart_brand_term_logo',
						'type'  => 'media',
						'title' => __( 'Brand logo', 'smart-brands-for-woocommerce' ),
					),
					array(
						'id'       => 'smart_brand_term_banner_link',
						'type'     => 'text',
						'title'    => __( 'Brand banner link', 'smart-brands-for-woocommerce' ),
						'subtitle' => __( 'The link of the brand banner', 'smart-brands-for-woocommerce' ),
						'sanitize' => 'sp_smart_brand_sanitize_url',
					),
				),
			)
		);
	}
}
