<?php
/**
 * The Dynamic CSS Style file.
 *
 * It provides all the external style of the generated shortcode/view.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Frontend/partials
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

/**
 * It provides all the configurations of generated shortcode/view.
 */
require 'configurations.php';
$section_title_text = get_the_title( $views_id );
// Section Title style.
if ( $show_section_title && ! empty( $section_title_text ) ) {
	$custom_css .= '
    #smart-brand-main-area-' . $views_id . ' .sp-smart-brand-section .sp-smart-brand-section-title{
    margin: ' . $section_title_margin['top'] . $section_title_margin_unit . ' ' . $section_title_margin['right'] . $section_title_margin_unit . ' ' . $section_title_margin['bottom'] . $section_title_margin_unit . ' ' . $section_title_margin['left'] . $section_title_margin_unit . ';
}';
} else {
	$custom_css .= '
    #smart-brand-main-area-' . $views_id . ' .sp-smart-brand-carousel{
    margin-top: 65px;
}';
}
// Background & hover color style for Item.
$custom_css .= '
#smart-brand-main-area-' . $views_id . ' .sp-smart-brand-grid .sp-smart-brand-row [class*=sbfw-col-]{
	padding: ' . $space_between_brands['all'] . 'px;
}#smart-brand-main-area-' . $views_id . ' .sp-brand-term-row {
    border: ' . $product_brand_border['all'] . 'px ' . $product_brand_border['style'] . ' ' . $product_brand_border['color'] . ';
    border-radius: ' . $brand_border_radius['all'] . $brand_border_radius['unit'] . ';
}#smart-brand-main-area-' . $views_id . ' .sp-brand-term-row:hover {
    border-color: ' . $product_brand_border['hover_color'] . ';
}';

// Carousel navigation color.
if ( $carousel_navigation ) {
	$carousel_nav_color     = isset( $views_meta['carousel_navigation_color'] ) ? $views_meta['carousel_navigation_color'] : array(
		'color'          => '#aaaaaa',
		'hover_color'    => '#ffffff',
		'bg_color'       => 'transparent',
		'bg_hover_color' => '#63a37b',
	);
	$color                  = $carousel_nav_color['color'];
	$hover_color            = $carousel_nav_color['hover_color'];
	$background_color       = $carousel_nav_color['bg_color'];
	$background_hover_color = $carousel_nav_color['bg_hover_color'];

	$custom_css .= '#smart-brand-main-area-' . $views_id . ' .sp-smart-brand-carousel .sp-brand-button-next,
    #smart-brand-main-area-' . $views_id . ' .sp-smart-brand-carousel .sp-brand-button-prev{
        background-color: ' . $background_color . ';
    }#smart-brand-main-area-' . $views_id . ' .sp-smart-brand-carousel .sp-brand-button-next:hover,
    #smart-brand-main-area-' . $views_id . ' .sp-smart-brand-carousel .sp-brand-button-prev:hover{
        background-color: ' . $background_hover_color . ';
    }#smart-brand-main-area-' . $views_id . ' .sp-smart-brand-carousel .sp-brand-button-next:after,
    #smart-brand-main-area-' . $views_id . ' .sp-smart-brand-carousel .sp-brand-button-prev:after{
        color : ' . $color . ';
    }#smart-brand-main-area-' . $views_id . ' .sp-smart-brand-carousel .sp-brand-button-next:hover::after,
    #smart-brand-main-area-' . $views_id . ' .sp-smart-brand-carousel .sp-brand-button-prev:hover::after{
        color : ' . $hover_color . ';
    }';
}

// Carousel Pagination color.
if ( $carousel_pagination ) {
	$carousel_pag_color = isset( $views_meta['carousel_pagination_color'] ) ? $views_meta['carousel_pagination_color'] : array(
		'color'        => '#aaaaaa',
		'active_color' => '#63a37b',
	);
	$color              = $carousel_pag_color['color'];
	$active_color       = $carousel_pag_color['active_color'];

	$custom_css .= '
    #smart-brand-main-area-' . $views_id . ' .sp-brand-pagination .swiper-pagination-bullet{
        background: ' . $color . ';
    }#smart-brand-main-area-' . $views_id . ' .sp-brand-pagination .swiper-pagination-bullet-active{
        background: ' . $active_color . ';
    }';
}
