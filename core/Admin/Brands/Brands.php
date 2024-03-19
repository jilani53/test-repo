<?php
/**
 * The file of the Brands taxonomy.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.0
 *
 * @package    Smart_Brands_For_Wc
 */

namespace ShapedPlugin\SmartBrands\Admin\Brands;

use ShapedPlugin\SmartBrands\Admin\Framework\Classes\SPF_SMART_BRANDS;

defined( 'ABSPATH' ) || die( 'No direct access, please!' );

/**
 * Brands Class.
 *
 * @since 1.0.0
 */
class Brands {

	/**
	 * The Brands class constructor.
	 */
	public function __construct() {
		self::taxonomy_options( 'sp_smart_brand_taxonomy_meta' );
	}

	/**
	 * Register brand taxonomy.
	 *
	 * @return void
	 */
	public function register_taxonomy_brands() {

		$views_options = get_option( 'sp_smart_brand_settings' );
		$custom_slug   = isset( $views_options['smart_brand_taxonomy_slug'] ) ? $views_options['smart_brand_taxonomy_slug'] : 'product-brands';

		$labels     = array(
			'name'                       => apply_filters( 'sp_smart_brand_taxonomy_label_name', __( 'Brands', 'smart-brands-for-woocommerce' ) ),
			'singular_name'              => __( 'Brand', 'smart-brands-for-woocommerce' ),
			'menu_name'                  => __( 'Brands', 'smart-brands-for-woocommerce' ),
			'all_items'                  => __( 'All Brands', 'smart-brands-for-woocommerce' ),
			'edit_item'                  => __( 'Edit Brand', 'smart-brands-for-woocommerce' ),
			'view_item'                  => __( 'View Brand', 'smart-brands-for-woocommerce' ),
			'update_item'                => __( 'Update Brand', 'smart-brands-for-woocommerce' ),
			'add_new_item'               => __( 'Add New Brand', 'smart-brands-for-woocommerce' ),
			'new_item_name'              => __( 'New Brand Name', 'smart-brands-for-woocommerce' ),
			'parent_item'                => __( 'Parent Brand', 'smart-brands-for-woocommerce' ),
			'parent_item_colon'          => __( 'Parent Brand:', 'smart-brands-for-woocommerce' ),
			'search_items'               => __( 'Search Brands', 'smart-brands-for-woocommerce' ),
			'popular_items'              => __( 'Popular Brands', 'smart-brands-for-woocommerce' ),
			'separate_items_with_commas' => __( 'Separate brands with commas', 'smart-brands-for-woocommerce' ),
			'add_or_remove_items'        => __( 'Add or remove brands', 'smart-brands-for-woocommerce' ),
			'choose_from_most_used'      => __( 'Choose from the most used brands', 'smart-brands-for-woocommerce' ),
			'not_found'                  => __( 'No brands found', 'smart-brands-for-woocommerce' ),
			'back_to_items'              => __( 'Go to Brands', 'smart-brands-for-woocommerce' ),
		);
		$capability = apply_filters( 'sp_smart_brand_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		$args       = apply_filters(
			'sp_smart_brand_taxonomy_args',
			array(
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => $show_ui,
				'show_in_menu'      => true,
				'query_var'         => true,
				'public'            => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'rewrite'           => array(
					'slug'         => apply_filters( 'sp_smart_brands_taxonomy_rewrite_slug', $custom_slug ),
					'hierarchical' => true,
					'with_front'   => apply_filters( 'sp_smart_brands_taxonomy_with_front', true ),
					'ep_mask'      => EP_PERMALINK,
				),
			)
		);
		register_taxonomy( 'sp_smart_brand', array( 'product' ), $args );
	}

	/**
	 * Create Taxonomy Option Fields.
	 *
	 * @param string $prefix Option setting key prefix.
	 * @return void
	 */
	public static function taxonomy_options( $prefix ) {
		SPF_SMART_BRANDS::createTaxonomyOptions(
			$prefix,
			array(
				'taxonomy'  => 'sp_smart_brand',
				'data_type' => 'serialize', // The type of the database save options. `serialize` or `unserialize`.
			)
		);
		// Serialized Ahead!
		Brand_Configs::section( $prefix );
	}
}
