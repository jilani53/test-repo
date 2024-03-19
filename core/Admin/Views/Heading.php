<?php
/**
 * Typography tab.
 *
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Admin/Views
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

namespace ShapedPlugin\SmartBrands\Admin\Views;

use ShapedPlugin\SmartBrands\Admin\Framework\Classes\SPF_SMART_BRANDS;

// Cannot access directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * This class is responsible for the heading of the views options.
 *
 * @since      1.0.0
 */
class Heading {

	/**
	 * Heading configuration.
	 *
	 * @since 1.0.0
	 * @param string $prefix sp_smart_brand_metaboxes.
	 */
	public static function section( $prefix ) {
		SPF_SMART_BRANDS::createSection(
			$prefix,
			array(
				'fields' => array(
					array(
						'type'  => 'heading',
						'class' => 'smart-brand-options-header',
						'after' => '<i class="fa fa-life-ring"></i> Support',
						'link'  => 'https://shapedplugin.com/support/',
						'image' => SMART_BRANDS_URL . '/core/Admin/assets/img/smart-brands white-logo.svg',
					),
					array(
						'id'   => 'generator_for_shortcode',
						'type'    => 'shortcode',
					),
				),
			)
		);
	}
}
