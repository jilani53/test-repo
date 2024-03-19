<?php
/**
 * Display tab.
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
 * This class is responsible for Display options tab in Smart Brand Views page.
 *
 * @since      1.0.0
 */
class Display {

	/**
	 * Display settings.
	 *
	 * @since 1.0.0
	 * @param string $prefix sp_smart_brand_metaboxes.
	 */
	public static function section( $prefix ) {
		SPF_SMART_BRANDS::createSection(
			$prefix,
			array(
				'title'  => __( 'Display Settings', 'smart-brands-for-woocommerce' ),
				'icon'   => 'fa fa-th-large',
				'fields' => array(
					array(
						'id'         => 'show_brand_section_title',
						'type'       => 'switcher',
						'title'      => __( 'Brand Section Title', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Show/Hide Brand section title.', 'smart-brands-for-woocommerce' ),
						'text_on'    => __( 'Show', 'smart-brands-for-woocommerce' ),
						'text_off'   => __( 'Hide', 'smart-brands-for-woocommerce' ),
						'default'    => true,
						'text_width' => 75,
					),
					array(
						'id'          => 'section_title_margin_around',
						'type'        => 'spacing',
						'title'       => __( 'Section Title Margin', 'smart-brands-for-woocommerce' ),
						'subtitle'    => __( 'Set margin for section title.', 'smart-brands-for-woocommerce' ),
						'output_mode' => 'margin',
						'units'       => array(
							esc_html__( 'px', 'smart-brands-for-woocommerce' ),
							esc_html__( 'em', 'smart-brands-for-woocommerce' ),
						),
						'default'     => array(
							'top'    => '0',
							'right'  => '0',
							'bottom' => '30',
							'left'   => '0',
							'unit'   => 'px',
						),
						'dependency'  => array(
							'show_brand_section_title',
							'==',
							'true',
						),
					),
					array(
						'id'         => 'brand_content_position',
						'type'       => 'layout_preset',
						'class'      => 'sbfw_content_position_img_width',
						'title'      => __( 'Brands Content Position', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Select a position for the brands.', 'smart-brands-for-woocommerce' ),
						'desc'       => __( 'To display the brand\'s various content positions, including names and descriptions, <a target="_blank" href="https://shapedplugin.com/plugin/smart-brands-for-woocommerce/?ref=1">Upgrade to Pro!</a>', 'smart-brands-for-woocommerce' ),
						'options'    => array(
							'top-thumb'    => array(
								'image' => SMART_BRANDS_URL . '/core/Admin/assets/img/content-position/top.svg',
								'text'  => __( 'Top', 'smart-brands-for-woocommerce' ),
							),
							'bottom-thumb' => array(
								'image'    => SMART_BRANDS_URL . '/core/Admin/assets/img/content-position/bottom.svg',
								'text'     => __( 'Bottom', 'smart-brands-for-woocommerce' ),
								'pro_only' => true,
							),
							'left-thumb'   => array(
								'image'    => SMART_BRANDS_URL . '/core/Admin/assets/img/content-position/left.svg',
								'text'     => __( 'Left', 'smart-brands-for-woocommerce' ),
								'pro_only' => true,
							),
							'right-thumb'  => array(
								'image'    => SMART_BRANDS_URL . '/core/Admin/assets/img/content-position/right.svg',
								'text'     => __( 'Right', 'smart-brands-for-woocommerce' ),
								'pro_only' => true,
							),
						),
						'default'    => 'top-thumb',
						'dependency' => array( 'brand_layout_preset', '!=', 'list_layout', true ),
					),
					array(
						'id'       => 'product_brand_border',
						'type'     => 'border',
						'title'    => __( 'Border', 'smart-brands-for-woocommerce' ),
						'subtitle' => __( 'Set border for Product brand.', 'smart-brands-for-woocommerce' ),
						'all'      => true,
						'default'  => array(
							'all'         => '0',
							'style'       => 'solid',
							'color'       => '#ddd',
							'hover_color' => '#ddd',
						),
					),
					array(
						'id'       => 'brand_border_radius',
						'type'     => 'spacing',
						'title'    => __( 'Radius', 'smart-brands-for-woocommerce' ),
						'subtitle' => __( 'Set border radius.', 'smart-brands-for-woocommerce' ),
						'top'      => false,
						'bottom'   => false,
						'left'     => false,
						'right'    => false,
						'all'      => true,
						'units'    => array( 'px', '%' ),
						'default'  => array(
							'all'  => '0',
							'unit' => 'px',
						),
					),
					array(
						'id'         => 'show_brand_name',
						'type'       => 'switcher',
						'class'      => 'brand_only_pro_switcher',
						'title'      => __( 'Brand Name', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Show/Hide Brand Name.', 'smart-brands-for-woocommerce' ),
						'text_on'    => __( 'Show', 'smart-brands-for-woocommerce' ),
						'text_off'   => __( 'Hide', 'smart-brands-for-woocommerce' ),
						'default'    => true,
						'text_width' => 75,
					),
					array(
						'id'         => 'show_brand_description',
						'type'       => 'switcher',
						'class'      => 'brand_only_pro_switcher',
						'title'      => __( 'Brand Description', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Show/Hide Brand Description.', 'smart-brands-for-woocommerce' ),
						'text_on'    => __( 'Show', 'smart-brands-for-woocommerce' ),
						'text_off'   => __( 'Hide', 'smart-brands-for-woocommerce' ),
						'default'    => true,
						'text_width' => 75,
					),
					array(
						'id'         => 'show_brand_logo',
						'type'       => 'switcher',
						'title'      => __( 'Brand Logo', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Show/Hide Brand Logo.', 'smart-brands-for-woocommerce' ),
						'text_on'    => __( 'Show', 'smart-brands-for-woocommerce' ),
						'text_off'   => __( 'Hide', 'smart-brands-for-woocommerce' ),
						'default'    => true,
						'text_width' => 75,
					),
				),
			)
		);
	}
}
