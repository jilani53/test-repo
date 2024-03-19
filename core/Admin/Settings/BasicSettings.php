<?php
/**
 * Basic Settings tab.
 *
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Admin/Settings
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

namespace ShapedPlugin\SmartBrands\Admin\Settings;

use ShapedPlugin\SmartBrands\Admin\Framework\Classes\SPF_SMART_BRANDS;

// Cannot access directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * This class is responsible for Basic settings tab in settings page.
 *
 * @since      1.0.0
 */
class BasicSettings {

	/**
	 * Basic Preferences.
	 *
	 * @since 1.0.0
	 * @param string $prefix sp_smart_brand_settings.
	 */
	public static function section( $prefix ) {
		SPF_SMART_BRANDS::createSection(
			$prefix,
			array(
				'title'  => __( 'Basic Preferences', 'smart-brands-for-woocommerce' ),
				'icon'   => 'sp_brand-icon-basic-preferences',
				'fields' => array(
					array(
						'id'          => 'smart_brand_taxonomy_slug',
						'type'        => 'text',
						'title'       => __( 'Brands Taxonomy Slug', 'smart-brands-for-woocommerce' ),
						'subtitle'    => __( 'Rewrite slug for plugin\'s brand taxonomy.', 'smart-brands-for-woocommerce' ),
						'default'     => __( 'product-brands', 'smart-brands-for-woocommerce' ),
						'placeholder' => 'slug',
					),
					array(
						'id'       => 'brand_label_before_name',
						'type'     => 'text',
						'title'    => __( 'Brand Label[Brand :]', 'smart-brands-for-woocommerce' ),
						'subtitle' => __( 'The text before the brand name.', 'smart-brands-for-woocommerce' ),
						'default'  => __( 'Brand : ', 'smart-brands-for-woocommerce' ),
					),
				),
			)
		);

	}
}
