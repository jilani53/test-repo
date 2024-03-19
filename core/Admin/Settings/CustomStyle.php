<?php
/**
 * Custom CSS settings tab.
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
 * This class is responsible for Custom CSS settings tab in settings page.
 *
 * @since      1.0.0
 */
class CustomStyle {

	/**
	 * Custom CSS settings.
	 *
	 * @since 1.0.0
	 * @param string $prefix sp_smart_brand_settings.
	 */
	public static function section( $prefix ) {
		SPF_SMART_BRANDS::createSection(
			$prefix,
			array(
				'title'  => __( 'Custom CSS', 'smart-brands-for-woocommerce' ),
				'icon'   => 'fa fa-file-code-o',
				'fields' => array(
					array(
						'id'       => 'custom_css',
						'type'     => 'code_editor',
						'title'    => __( 'Custom CSS', 'smart-brands-for-woocommerce' ),
						'settings' => array(
							'icon'  => 'fa fa-sliders',
							'theme' => 'mbo',
							'mode'  => 'css',
						),
					),
				),
			)
		);

	}
}
