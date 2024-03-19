<?php
/**
 * Settings class for Shortcode generator options.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Admin/Settings
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

namespace ShapedPlugin\SmartBrands\Admin\Settings;

use ShapedPlugin\SmartBrands\Admin\Framework\Classes\SPF_SMART_BRANDS;
use ShapedPlugin\SmartBrands\Admin\Settings\ArchiveSettings;
use ShapedPlugin\SmartBrands\Admin\Settings\BasicSettings;
use ShapedPlugin\SmartBrands\Admin\Settings\CustomStyle;
use ShapedPlugin\SmartBrands\Admin\Settings\ProductPage;
use ShapedPlugin\SmartBrands\Admin\Settings\LoopPage;

/**
 * Settings class to create all settings options for Smart Brand.
 */
class Settings {

	/**
	 * Create Option fields for the setting options.
	 *
	 * @param string $prefix Option setting key prefix.
	 * @return void
	 */
	public static function options( $prefix ) {
		SPF_SMART_BRANDS::createOptions(
			$prefix,
			array(
				'menu_title'              => __( 'Settings', 'smart-brands-for-woocommerce' ),
				'menu_slug'               => 'smart-brands-settings',
				'menu_type'               => 'submenu',
				'menu_parent'             => 'edit.php?post_type=smart_brand_sc',
				'show_bar_menu'           => false,
				'show_sub_menu'           => false,
				'framework_title'         => __( 'Smart Brands Settings', 'smart-brands-for-woocommerce' ),
				'admin_bar_menu_priority' => 5,
				'show_search'             => false,
				'show_all_options'        => false,
				'show_reset_all'          => false,
				'show_reset_section'      => true,
				'show_footer'             => false,
				'theme'                   => 'light',
				'framework_class'         => 'sp-smart-brands-settings',
			)
		);

		// Serialized Ahead!
		BasicSettings::section( $prefix );
		ProductPage::section( $prefix );
		LoopPage::section( $prefix );
		ArchiveSettings::section( $prefix );
		CustomStyle::section( $prefix );
	}

}
