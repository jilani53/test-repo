<?php
/**
 * The frontend facing brand file to show term item.
 *
 * It shows the frontend output of plugin for grid layout.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Frontend/templates/brand
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

use ShapedPlugin\SmartBrands\Frontend\Manager;

if ( $show_brand_logo ) {
	?>
	<div class="sp-brand-col-xs-2 sp-brand-term-image">
	<?php
	Manager::brand_logo( $brand_term );
	?>
	</div>
	<?php
}

if ( $show_product_count ) {
	echo '<span class="sp-brand-product-count">(' . esc_attr( $brand_term->count ) . ')</span>';
}
