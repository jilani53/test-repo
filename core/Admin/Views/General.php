<?php
/**
 * General tab.
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
 * This class is responsible for General tab in Smart Brand Views page.
 *
 * @since      1.0.0
 */
class General {

	/**
	 * General settings.
	 *
	 * @since 1.0.0
	 * @param string $prefix sp_smart_brand_metaboxes.
	 */
	public static function section( $prefix ) {
		SPF_SMART_BRANDS::createSection(
			$prefix,
			array(
				'title'  => __( 'General Settings', 'smart-brands-for-woocommerce' ),
				'icon'   => 'fa fa-gear',
				'fields' => array(
					array(
						'id'       => 'brand_layout_preset',
						'type'     => 'layout_preset',
						'class'    => 'sp_brand_layout_preset sp_brand_only_pro',
						'title'    => __( 'Layout Type', 'smart-brands-for-woocommerce' ),
						'subtitle' => __( 'Choose a layout.', 'smart-brands-for-woocommerce' ),
						'desc'     => __( 'To create <a target="_blank" href="https://demo.shapedplugin.com/smart-brands-pro/brand-showcase/">beautiful layouts</a> and access to advanced customizations, <a target="_blank" href="https://shapedplugin.com/plugin/smart-brands-for-woocommerce/?ref=1">Upgrade to Pro!</a>', 'smart-brands-for-woocommerce' ),
						'options'  => array(
							'carousel_layout' => array(
								'image' => SMART_BRANDS_URL . '/core/Admin/assets/img/layout/carousel.svg',
								'text'  => __( 'Carousel', 'smart-brands-for-woocommerce' ),
							),
							'grid_layout'     => array(
								'image'    => SMART_BRANDS_URL . '/core/Admin/assets/img/layout/grid.svg',
								'text'     => __( 'Grid', 'smart-brands-for-woocommerce' ),
								'pro_only' => true,
							),
							'list_layout'     => array(
								'image'    => SMART_BRANDS_URL . '/core/Admin/assets/img/layout/list.svg',
								'text'     => __( 'List', 'smart-brands-for-woocommerce' ),
								'pro_only' => true,
							),
						),
						'default'  => 'carousel_layout',
					),
					array(
						'id'         => 'carousel_mode',
						'type'       => 'layout_preset',
						'class'      => 'brand-carousel-mode sp_brand_only_pro',
						'title'      => __( 'Carousel Mode', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Set carousel mode.', 'smart-brands-for-woocommerce' ),
						'options'    => array(
							'standard' => array(
								'image' => SMART_BRANDS_URL . '/core/Admin/assets/img/standard.svg',
								'text'  => __( 'Standard', 'smart-brands-for-woocommerce' ),
							),
							'ticker'   => array(
								'image'    => SMART_BRANDS_URL . '/core/Admin/assets/img/ticker.svg',
								'text'     => __( 'Ticker', 'smart-brands-for-woocommerce' ),
								'pro_only' => true,
							),
						),
						'title_help' => '<div class="sp_brand-info-label">Carousel Mode</div><div class="sp_brand-short-content">This feature allows you to select the most suitable carousel mode between Standard, Ticker (continuous scrolling)</div><a class="sp_brand-open-docs" href="https://demo.shapedplugin.com/smart-brands-pro/brand-showcase/" target="_blank">Live Demo</a>',
						'smart-brands-for-woocommerce',
						'default'    => 'standard',
						'dependency' => array( 'brand_layout_preset', '==', 'carousel_layout' ),
					),
					array(
						'id'       => 'number_of_column',
						'type'     => 'column',
						'title'    => __( 'Column(s)', 'smart-brands-for-woocommerce' ),
						'subtitle' => __( 'Set number of row in different devices for responsive view.', 'smart-brands-for-woocommerce' ),
						'default'  => array(
							'large_desktop' => '4',
							'desktop'       => '4',
							'laptop'        => '3',
							'tablet'        => '2',
							'mobile'        => '1',
						),
						'min'      => '1',
					),
					array(
						'id'          => 'space_between_brands',
						'type'        => 'spacing',
						'title'       => __( 'Space Between Brands', 'smart-brands-for-woocommerce' ),
						'subtitle'    => __( 'Set space in pixel between brands.', 'smart-brands-for-woocommerce' ),
						'output_mode' => 'margin',
						'all'         => true,
						'all_text'    => false,
						'all_icon'    => '<i class="fa fa-arrows-h"></i>',
						'units'       => array(
							esc_html__( 'px', 'smart-brands-for-woocommerce' ),
						),
						'default'     => array(
							'all'  => '20',
							'unit' => 'px',
						),
					),
					array(
						'id'       => 'show_brands_from',
						'type'     => 'select',
						'title'    => __( 'Filter Brands', 'smart-brands-for-woocommerce' ),
						'subtitle' => __( 'Select an option to display by filtering brands.', 'smart-brands-for-woocommerce' ),
						'options'  => array(
							'latest'   => __( 'All', 'smart-brands-for-woocommerce' ),
							'specific' => array(
								'text'     => __( 'Specific (Pro)', 'smart-brands-for-woocommerce' ),
								'pro_only' => true,
							),
							'exclude'  => array(
								'text'     => __( 'Exclude (Pro)', 'smart-brands-for-woocommerce' ),
								'pro_only' => true,
							),
						),
						'default'  => 'latest',
					),
					array(
						'id'         => 'show_brand_product_count',
						'type'       => 'switcher',
						'title'      => __( 'Product Count', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'It shows the number of products associated with each brand.', 'smart-brands-for-woocommerce' ),
						'text_on'    => __( 'Show', 'smart-brands-for-woocommerce' ),
						'text_off'   => __( 'Hide', 'smart-brands-for-woocommerce' ),
						'default'    => false,
						'text_width' => 77,
					),
					array(
						'id'       => 'hide_brand_without_product',
						'type'     => 'checkbox',
						'class'    => 'csf_pro_option',
						'title'    => __( 'Hide Empty Brands', 'smart-brands-for-woocommerce' ),
						'subtitle' => __( 'Check to hide the brands those don\'t have any product (Pro).', 'smart-brands-for-woocommerce' ),
						'default'  => false,
					),
					array(
						'id'       => 'hide_brand_without_logo',
						'type'     => 'checkbox',
						'class'    => 'csf_pro_option',
						'title'    => __( 'Hide Brands Without Logo', 'smart-brands-for-woocommerce' ),
						'subtitle' => __( 'Check to hide brands those don\'t have any logo (Pro).', 'smart-brands-for-woocommerce' ),
					),
					array(
						'id'       => 'order_by',
						'type'     => 'select',
						'title'    => __( 'Order By', 'smart-brands-for-woocommerce' ),
						'subtitle' => __( 'Select an order by option.', 'smart-brands-for-woocommerce' ),
						'options'  => array(
							'rand' => __( 'Random', 'smart-brands-for-woocommerce' ),
							'date' => __( 'Date', 'smart-brands-for-woocommerce' ),
							'name' => __( 'Name', 'smart-brands-for-woocommerce' ),
						),
						'default'  => 'menu_order',
					),
					array(
						'id'       => 'order',
						'type'     => 'select',
						'title'    => __( 'Order', 'smart-brands-for-woocommerce' ),
						'options'  => array(
							'ASC'  => __( 'Ascending', 'smart-brands-for-woocommerce' ),
							'DESC' => __( 'Descending', 'smart-brands-for-woocommerce' ),
						),
						'default'  => 'DESC',
						'subtitle' => __( 'Select an order option.', 'smart-brands-for-woocommerce' ),
					),
					array(
						'id'         => 'enable_preloader',
						'type'       => 'switcher',
						'title'      => __( 'Preloader', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Smart brands will be hidden until page load completed and ajax pagination.', 'smart-brands-for-woocommerce' ),
						'text_on'    => __( 'Enabled', 'smart-brands-for-woocommerce' ),
						'text_off'   => __( 'Disabled', 'smart-brands-for-woocommerce' ),
						'text_width' => 96,
						'default'    => true,
					),
				),
			)
		);
	}
}
