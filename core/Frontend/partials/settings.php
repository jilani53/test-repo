<?php
/**
 * All settings of Smart Brand for WooCommerce.
 *
 * It provides all the settings of WooCommerce product page.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Frontend/partials
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

$views_options = get_option( 'sp_smart_brand_settings' );

/*
 * Basic Brand Settings.
*/
$product_brand_taxonomy  = isset( $views_options['product_brand_taxonomy'] ) ? $views_options['product_brand_taxonomy'] : 'sample_page';
$brand_label_before_name = isset( $views_options['brand_label_before_name'] ) ? $views_options['brand_label_before_name'] : 'Brand : ';

/*
 * Product page Tab.
*/
$show_brand_in_single_page = isset( $views_options['enable_brand_in_single_page'] ) ? $views_options['enable_brand_in_single_page'] : 'true';
$brand_content_tab_info    = isset( $views_options['content_tab_info'] ) ? $views_options['content_tab_info'] : '';

/*
 * Loop page Tab.
*/
$show_brand_in_loop_page = isset( $views_options['enable_brand_in_loop_page'] ) ? $views_options['enable_brand_in_loop_page'] : 'true';
