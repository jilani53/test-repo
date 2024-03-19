<?php
/**
 * The shortcode/view configurations file.
 *
 * It provides all the configurations/settings of generated shortcode/view.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Frontend/partials
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

/*
 * General Tab.
*/
$layout_preset      = isset( $views_meta['brand_layout_preset'] ) ? $views_meta['brand_layout_preset'] : '';
$show_brand_logo    = isset( $views_meta['show_brand_logo'] ) ? $views_meta['show_brand_logo'] : true;
$show_product_count = isset( $views_meta['show_brand_product_count'] ) ? $views_meta['show_brand_product_count'] : false;
// Responsive Column.
$columns = isset( $views_meta['number_of_column'] ) ? $views_meta['number_of_column'] : '';

$lg_desktop_screen = $columns['large_desktop'] ?? '4';
$desktop_screen    = $columns['desktop'] ?? '4';
$laptop_screen     = $columns['laptop'] ?? '3';
$tablet_screen     = $columns['tablet'] ?? '2';
$mobile_screen     = $columns['mobile'] ?? '1';

$order_by    = $views_meta['order_by'];
$brand_order = $views_meta['order'];
$preloader   = $views_meta['enable_preloader'];

/*
 * Display Tab.
*/
$show_section_title        = $views_meta['show_brand_section_title'];
$section_title_margin      = $views_meta['section_title_margin_around'];
$section_title_margin_unit = $section_title_margin['unit'];
$space_between_brands      = $views_meta['space_between_brands'];
$product_brand_border      = $views_meta['product_brand_border'];
$brand_border_radius       = $views_meta['brand_border_radius'];

/*
 * Carousel Tab.
*/
$autoplay       = $views_meta['carousel_autoplay'] ? 'true' : 'false';
$autoplay_speed = $views_meta['carousel_autoplay_speed'];
$sliding_speed  = $views_meta['carousel_sliding_speed'];
$pause_on_hover = $views_meta['carousel_pause_on_hover'] ? 'true' : 'false';
$infinite_loop  = $views_meta['carousel_infinite_loop'] ? 'true' : 'false';
$free_mode      = isset( $views_meta['carousel_free_mode'] ) && $views_meta['carousel_free_mode'] ? 'true' : 'false';
// slide to scroll.
$slide_to_scroll = $views_meta['number_of_brand_to_scroll'];
$large_desktop   = $slide_to_scroll['large_desktop'];
$desktop         = $slide_to_scroll['large_desktop'];
$laptop          = $slide_to_scroll['laptop'];
$tablet          = $slide_to_scroll['tablet'];
$mobile          = $slide_to_scroll['mobile'];

$carousel_navigation = $views_meta['carousel_navigation'];
$carousel_pagination = $views_meta['carousel_pagination'];
