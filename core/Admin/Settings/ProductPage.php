<?php
/**
 * Product Single Page tab.
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
 * This class is responsible for product single page tab in settings page.
 *
 * @since      1.0.0
 */
class ProductPage {

	/**
	 * Product page settings.
	 *
	 * @since 1.0.0
	 * @param string $prefix sp_smart_brand_settings.
	 */
	public static function section( $prefix ) {
		SPF_SMART_BRANDS::createSection(
			$prefix,
			array(
				'title'  => __( 'Product Page Settings', 'smart-brands-for-woocommerce' ),
				'icon'   => 'sp_brand-icon-product-page',
				'fields' => array(
					array(
						'id'         => 'enable_brand_in_single_page',
						'type'       => 'switcher',
						'title'      => __( 'Brand in Product Page', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Show/hide brand in the product single page.', 'smart-brands-for-woocommerce' ),
						'text_on'    => __( 'show', 'smart-brands-for-woocommerce' ),
						'text_off'   => __( 'hide', 'smart-brands-for-woocommerce' ),
						'default'    => true,
						'text_width' => 77,
					),

					array(
						'id'         => 'brand_position_in_product_page',
						'type'       => 'select',
						'title'      => __( 'Brand Position in Product Page', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Select a position for the product single page.', 'smart-brands-for-woocommerce' ),
						'options'    => array(
							'after_product_meta'    => __( 'After Product Meta', 'smart-brands-for-woocommerce' ),

							'before_product_title'  => array(
								'text'     => __( 'Before Product Title (Pro)', 'smart-brands-for-woocommerce' ),
								'pro_only' => true,
							),
							'after_product_title'   => array(
								'text'     => __( 'After Product Title (Pro)', 'smart-brands-for-woocommerce' ),
								'pro_only' => true,
							),
							'after_product_price'   => array(
								'text'     => __( 'After Product Price (Pro)', 'smart-brands-for-woocommerce' ),
								'pro_only' => true,
							),
							'after_product_excerpt' => array(
								'text'     => __( 'After Product Excerpt (Pro)', 'smart-brands-for-woocommerce' ),
								'pro_only' => true,
							),
							'after_add_to_card'     => array(
								'text'     => __( 'After Add to Cart (Pro)', 'smart-brands-for-woocommerce' ),
								'pro_only' => true,
							),
							'after_product_share'   => array(
								'text'     => __( 'After Product Share (Pro)', 'smart-brands-for-woocommerce' ),
								'pro_only' => true,
							),
						),
						'default'    => 'after_product_meta',
						'dependency' => array( 'enable_brand_in_single_page', '==', 'true', true ),
					),
					array(
						'id'         => 'brand_content_in_product_page',
						'type'       => 'select',
						'title'      => __( 'Brand Content in Product Page', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Select brand content for the product single page.', 'smart-brands-for-woocommerce' ),
						'options'    => array(
							'only_name'     => __( 'Only Name', 'smart-brands-for-woocommerce' ),
							'only_logo'     => array(
								'text'     => __( 'Only Logo (Pro)', 'smart-brands-for-woocommerce' ),
								'pro_only' => true,
							),
							'logo_and_name' => array(
								'text'     => __( 'Logo and Name (Pro)', 'smart-brands-for-woocommerce' ),
								'pro_only' => true,
							),
						),
						'default'    => 'only_name',
						'dependency' => array( 'enable_brand_in_single_page', '==', 'true' ),
					),
					array(
						'id'         => 'brand_logo_size_in_product_page',
						'type'       => 'image_sizes',
						'title'      => __( 'Brand Logo Size in Product Page', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Set a size for the brand logo in the product single page.', 'smart-brands-for-woocommerce' ),
						'chosen'     => true,
						'default'    => 'medium',
						'dependency' => array( 'enable_brand_in_single_page', '==', 'true' ),
					),
					array(
						'id'                => 'custom_size_in_product_page',
						'type'              => 'dimensions_advanced',
						'title'             => __( 'Custom Size', 'smart-brands-for-woocommerce' ),
						'subtitle'          => __( 'Set a custom width and height of the brand logo.', 'smart-brands-for-woocommerce' ),
						'chosen'            => true,
						'bottom'            => false,
						'left'              => false,
						'color'             => false,
						'top_icon'          => '<i class="fa fa-arrows-h"></i>',
						'right_icon'        => '<i class="fa fa-arrows-v"></i>',
						'top_placeholder'   => 'width',
						'right_placeholder' => 'height',
						'styles'            => array(
							'Hard-crop',
							'Soft-crop',
						),
						'default'           => array(
							'top'   => '400',
							'right' => '445',
							'style' => 'Hard-crop',
							'unit'  => 'px',
						),
						'attributes'        => array(
							'min' => 0,
						),
						'dependency'        => array( 'brand_logo_size_in_product_page|enable_brand_in_single_page', '==|==', 'custom|true', true ),
					),
					array(
						'id'         => 'brand_tab_in_single_page',
						'type'       => 'switcher',
						'title'      => __( 'Brand Tab in Product Page', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Brand Tab in product single page.', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Enable/Disable brand tab in single page.', 'smart-brands-for-woocommerce' ),
						'text_on'    => __( 'Enabled', 'smart-brands-for-woocommerce' ),
						'text_off'   => __( 'Disabled', 'smart-brands-for-woocommerce' ),
						'default'    => false,
						'text_width' => 96,
					),
					array(
						'id'         => 'content_tab_info',
						'type'       => 'sortable',
						'title'      => __( 'Brand Contents in Tab', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Show/hide brand content in the tab.', 'smart-brands-for-woocommerce' ),
						'class'      => 'style_sortable_content_tab csf_pro_option',
						'default'    => array(
							'brand_name'        => true,
							'brand_description' => true,
							'brand_logo'        => true,
						),
						'fields'     => array(
							array(
								'id'         => 'brand_name',
								'type'       => 'switcher',
								'title'      => __( 'Brand Name', 'smart-brands-for-woocommerce' ),
								'text_on'    => __( 'Show', 'smart-brands-for-woocommerce' ),
								'text_off'   => __( 'Hide', 'smart-brands-for-woocommerce' ),
								'text_width' => 75,
							),
							array(
								'id'         => 'brand_description',
								'type'       => 'switcher',
								'title'      => __( 'Brand Description', 'smart-brands-for-woocommerce' ),
								'text_on'    => __( 'Show', 'smart-brands-for-woocommerce' ),
								'text_off'   => __( 'Hide', 'smart-brands-for-woocommerce' ),
								'text_width' => 75,
							),
							array(
								'id'         => 'brand_logo',
								'type'       => 'switcher',
								'title'      => __( 'Brand Logo', 'smart-brands-for-woocommerce' ),
								'text_on'    => __( 'Show', 'smart-brands-for-woocommerce' ),
								'text_off'   => __( 'Hide', 'smart-brands-for-woocommerce' ),
								'text_width' => 75,
							),
						),
						'dependency' => array( 'brand_tab_in_single_page', '==', 'true' ),
					),
					array(
						'type'    => 'submessage',
						'content' => __(
							'To showcase the brands prominently on the <a target="_blank" href="https://demo.shapedplugin.com/smart-brands-pro/product/wireless-portable-charger/">product page</a> and boost sales, <a target="_blank" href="https://shapedplugin.com/plugin/smart-brands-for-woocommerce/?ref=1">Upgrade to Pro!</a>',
							'smart-brands-for-woocommerce'
						),
					),
				),
			)
		);
	}
}
