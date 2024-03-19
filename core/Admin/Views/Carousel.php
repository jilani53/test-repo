<?php
/**
 * Carousel Controls tab.
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
class Carousel {

	/**
	 * Carousel settings.
	 *
	 * @since 1.0.0
	 * @param string $prefix sp_smart_brand_metaboxes.
	 */
	public static function section( $prefix ) {
		SPF_SMART_BRANDS::createSection(
			$prefix,
			array(
				'title'  => __( 'Carousel Settings', 'smart-brands-for-woocommerce' ),
				'icon'   => 'fa fa-sliders',
				'fields' => array(
					array(
						'id'         => 'carousel_autoplay',
						'type'       => 'switcher',
						'title'      => __( 'AutoPlay', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'On/Off auto play.', 'smart-brands-for-woocommerce' ),
						'text_on'    => __( 'Enabled', 'smart-brands-for-woocommerce' ),
						'text_off'   => __( 'Disabled', 'smart-brands-for-woocommerce' ),
						'text_width' => 96,
						'default'    => true,
					),
					array(
						'id'         => 'carousel_autoplay_speed',
						'type'       => 'slider',
						'class'      => 'brand_carousel_auto_play_ranger',
						'title'      => __( 'AutoPlay Delay Time', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Set autoplay delay time in millisecond.', 'smart-brands-for-woocommerce' ),
						'unit'       => __( 'ms', 'smart-brands-for-woocommerce' ),
						'step'       => 100,
						'min'        => 100,
						'max'        => 50000,
						'default'    => 2000,
						'dependency' => array( 'carousel_autoplay', '==', 'true', true ),
					),
					array(
						'id'       => 'carousel_sliding_speed',
						'type'     => 'slider',
						'class'    => 'brand_carousel_auto_play_ranger',
						'title'    => __( 'Sliding Speed', 'smart-brands-for-woocommerce' ),
						'subtitle' => __( 'Set carousel scroll speed in millisecond.', 'smart-brands-for-woocommerce' ),
						'unit'     => __( 'ms', 'smart-brands-for-woocommerce' ),
						'step'     => 50,
						'min'      => 100,
						'max'      => 20000,
						'default'  => 400,
					),
					array(
						'id'         => 'carousel_pause_on_hover',
						'type'       => 'switcher',
						'title'      => __( 'Pause on Hover', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Enabled/Disabled Pause on Hover.', 'smart-brands-for-woocommerce' ),
						'text_on'    => __( 'Enabled', 'smart-brands-for-woocommerce' ),
						'text_off'   => __( 'Disabled', 'smart-brands-for-woocommerce' ),
						'text_width' => 96,
						'default'    => true,
					),
					array(
						'id'         => 'carousel_infinite_loop',
						'type'       => 'switcher',
						'title'      => __( 'Infinite Loop', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Enable/Disable Infinite Loop.', 'smart-brands-for-woocommerce' ),
						'text_on'    => __( 'Enabled', 'smart-brands-for-woocommerce' ),
						'text_off'   => __( 'Disabled', 'smart-brands-for-woocommerce' ),
						'text_width' => 96,
						'default'    => true,
					),
					array(
						'id'         => 'carousel_free_mode',
						'type'       => 'switcher',
						'title'      => __( 'Free Mode', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Enable/Disable Free Mode.', 'smart-brands-for-woocommerce' ),
						'text_on'    => __( 'Enabled', 'smart-brands-for-woocommerce' ),
						'text_off'   => __( 'Disabled', 'smart-brands-for-woocommerce' ),
						'text_width' => 96,
						'default'    => false,
					),
					array(
						'id'       => 'number_of_brand_to_scroll',
						'type'     => 'column',
						'title'    => __( 'Slide To Scroll', 'smart-brands-for-woocommerce' ),
						'subtitle' => __( 'Number of brand(s) to scroll at a time.', 'smart-brands-for-woocommerce' ),
						'unit'     => false,
						'default'  => array(
							'large_desktop' => '1',
							'desktop'       => '1',
							'laptop'        => '1',
							'tablet'        => '1',
							'mobile'        => '1',
						),
					),
					array(
						'id'         => 'carousel_row',
						'type'       => 'column',
						'class'      => 'csf_pro_option',
						'title'      => __( 'Carousel Row', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Set number of rows in different devices for responsive view.', 'smart-brands-for-woocommerce' ),
						'default'    => array(
							'large_desktop' => '1',
							'desktop'       => '1',
							'laptop'        => '1',
							'tablet'        => '1',
							'mobile'        => '1',
						),
						'dependency' => array( 'carousel_mode', '==', 'standard', true ),
					),
					array(
						'id'         => 'carousel_navigation',
						'type'       => 'switcher',
						'title'      => __( 'Navigation', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Show/Hide carousel navigation.', 'smart-brands-for-woocommerce' ),
						'text_on'    => __( 'Show', 'smart-brands-for-woocommerce' ),
						'text_off'   => __( 'Hide', 'smart-brands-for-woocommerce' ),
						'default'    => true,
						'text_width' => 75,
					),
					array(
						'id'         => 'carousel_navigation_color',
						'type'       => 'color_group',
						'title'      => __( 'Navigation Color', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Set color for the carousel navigation.', 'smart-brands-for-woocommerce' ),
						'options'    => array(
							'color'          => __( 'Color', 'smart-brands-for-woocommerce' ),
							'hover_color'    => __( 'Hover Color', 'smart-brands-for-woocommerce' ),
							'bg_color'       => __( 'Background', 'smart-brands-for-woocommerce' ),
							'bg_hover_color' => __( 'Hover Background', 'smart-brands-for-woocommerce' ),
						),
						'default'    => array(
							'color'          => '#aaaaaa',
							'hover_color'    => '#ffffff',
							'bg_color'       => 'transparent',
							'bg_hover_color' => '#0f68a9',
						),
						'dependency' => array( 'carousel_navigation|carousel_mode', '==|==', 'true|standard', true ),
					),
					array(
						'id'         => 'carousel_pagination',
						'type'       => 'switcher',
						'title'      => __( 'Pagination', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Show/Hide carousel pagination.', 'smart-brands-for-woocommerce' ),
						'text_on'    => __( 'Show', 'smart-brands-for-woocommerce' ),
						'text_off'   => __( 'Hide', 'smart-brands-for-woocommerce' ),
						'default'    => true,
						'text_width' => 75,
					),
					array(
						'id'         => 'carousel_pagination_color',
						'type'       => 'color_group',
						'title'      => __( 'Pagination Color', 'smart-brands-for-woocommerce' ),
						'subtitle'   => __( 'Set color for the carousel Pagination.', 'smart-brands-for-woocommerce' ),
						'options'    => array(
							'color'        => __( 'Color', 'smart-brands-for-woocommerce' ),
							'active_color' => __( 'Active Color', 'smart-brands-for-woocommerce' ),
						),
						'default'    => array(
							'color'        => '#aaaaaa',
							'active_color' => '#0f68a9',
						),
						'dependency' => array( 'carousel_pagination|carousel_mode', '==|==', 'true|standard', true ),
					),
				),
			)
		);
	}
}
