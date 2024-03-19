<?php
/**
 * The admin-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two hooks for how to
 * enqueue the admin-facing stylesheet and JavaScript.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Admin
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

namespace ShapedPlugin\SmartBrands\Admin;

use ShapedPlugin\SmartBrands\Admin\Views\Views;
use ShapedPlugin\SmartBrands\Admin\Settings\Settings;
use ShapedPlugin\SmartBrands\Admin\GutenbergBlock;
use ShapedPlugin\SmartBrands\Admin\Review_Notice;

/**
 * The Admin class
 *
 * This class handles all the admin facing functionalities.
 */
class Admin {

	/**
	 * The slug of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_slug   The slug of this plugin.
	 */
	private $plugin_slug;

	/**
	 * The min of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $min   The slug of this plugin.
	 */
	private $min;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The class constructor.
	 *
	 * @param string $plugin_slug The slug of the plugin.
	 * @param string $version Current version of the plugin.
	 */
	public function __construct( $plugin_slug, $version ) {

		$this->plugin_slug = $plugin_slug;
		$this->version     = $version;
		$this->min         = defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';

		Views::metaboxes( 'sp_smart_brand_metaboxes' ); // Generator metaboxes.
		Views::view_heading( 'sp_smart_brand_header' ); // Heading Style.
		Settings::options( 'sp_smart_brand_settings' ); // Setting options.

		// Plugin action link Button.
		$active_plugins = get_option( 'active_plugins' );
		foreach ( $active_plugins as $active_plugin ) {
			$plugin_file = strpos( $active_plugin, 'smart-brands-for-woocommerce.php' );
			if ( false !== $plugin_file ) {
				add_filter( 'plugin_action_links_' . $active_plugin, array( $this, 'add_plugin_action_links' ) );
			}
		}
		add_action( 'woocommerce_product_duplicate', array( $this, 'sp_brand_product_duplicate_save' ), 10, 2 );
		// Add custom columns to the admin brands list.
		add_filter( 'manage_edit-sp_smart_brand_columns', array( $this, 'smart_brand_cat_columns' ) );
		add_filter( 'manage_sp_smart_brand_custom_column', array( $this, 'smart_brand_cat_column' ), 10, 3 );

		// Admin footer text.
		add_filter( 'admin_footer_text', array( $this, 'spsb_review_text' ) );
		add_filter( 'update_footer', array( $this, 'spsb_footer_version' ), 11 );
		// Redirect after the plugin activation.
		add_action( 'activated_plugin', array( $this, 'redirect_after_activation' ), 10, 2 );

		new GutenbergBlock();
		new Review_Notice();
	}

	/**
	 * Footer review text function
	 *
	 * @param string $text footer review text.
	 * @return string
	 */
	public function spsb_review_text( $text ) {
		$screen = get_current_screen();
		if ( 'smart_brand_sc' === $screen->post_type ) {
			$url  = 'https://wordpress.org/support/plugin/smart-brands-for-woocommerce/reviews/?filter=5#new-post';
			$text = sprintf( wp_kses_post( 'Enjoying <strong>Smart Brands for WooCommerce?</strong> Please rate us <span class="spsb-footer-text-star">★★★★★</span> <a href="%s" target="_blank">WordPress.org</a>. Your positive feedback will help us grow more. Thank you! 😊', 'smart-brands-for-woocommerce' ), esc_url( $url ) );
		}
		return $text;
	}

	/**
	 * Footer version text function
	 *
	 * @param string $text footer version text.
	 * @return string
	 */
	public function spsb_footer_version( $text ) {
		$screen = get_current_screen();
		if ( 'smart_brand_sc' === $screen->post_type ) {
			$text = 'Smart Brands ' . SMART_BRANDS_VERSION;
		}
		return $text;
	}

	/**
	 * Save selected brand taxonomy term in duplicated WC product.
	 *
	 * @param object $duplicate The duplicate product.
	 * @param object $product The product ID.
	 */
	public function sp_brand_product_duplicate_save( $duplicate, $product ) {
		$product_brands = wp_get_object_terms( $product->get_id(), 'sp_smart_brand', array( 'fields' => 'ids' ) );

		wp_set_object_terms( $duplicate->get_id(), $product_brands, 'sp_smart_brand' );
	}

	/**
	 * Add plugin action menu.
	 *
	 * @param array $link The action link.
	 *
	 * @return array
	 */
	public function add_plugin_action_links( $link ) {
		$new_links = array(
			sprintf( '<a href="%s">%s</a>', admin_url( 'post-new.php?post_type=smart_brand_sc' ), __( 'Add New', 'smart-brands-for-woocommerce' ) ),
			sprintf( '<a href="%s">%s</a>', admin_url( 'edit.php?post_type=smart_brand_sc&page=smart-brands-settings' ), __( 'Settings', 'smart-brands-for-woocommerce' ) ),
		);
		return array_merge( $new_links, $link );
	}

	/**
	 * Register smart_brand_sc custom post type
	 *
	 * @since    1.0.0
	 */
	public function shortcode_post_type() {
		$labels     = array(
			'name'               => __( 'Brands Views', 'smart-brands-for-woocommerce' ),
			'singular_name'      => __( 'Brands Views', 'smart-brands-for-woocommerce' ),
			'add_new'            => __( 'Add New', 'smart-brands-for-woocommerce' ),
			'add_new_item'       => __( 'Add New Brand', 'smart-brands-for-woocommerce' ),
			'edit_item'          => __( 'Edit Views', 'smart-brands-for-woocommerce' ),
			'new_item'           => __( 'New Views', 'smart-brands-for-woocommerce' ),
			/* translators: %s is replaced with 'Singular views name' */
			'all_items'          => __( 'Brands Views', 'smart-brands-for-woocommerce' ),
			'view_item'          => __( 'View Smart Brands Shortcode', 'smart-brands-for-woocommerce' ),
			'search_items'       => __( 'Search Smart Brands Shortcode', 'smart-brands-for-woocommerce' ),
			'not_found'          => __( 'No Smart Brands Shortcode Found', 'smart-brands-for-woocommerce' ),
			'not_found_in_trash' => __( 'No Smart Brands Shortcode Found in Trash', 'smart-brands-for-woocommerce' ),
			'parent_item_colon'  => null,
			/* translators: %s is replaced with 'Singular views name' */
			'menu_name'          => __( 'Smart Brands', 'smart-brands-for-woocommerce' ),
		);
		$menu_icon  = 'data:image/svg+xml;base64,' . base64_encode(
			'<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 91.38 91.38" style="enable-background:new 0 0 91.38 91.38;" xml:space="preserve">
		<style type="text/css">
			.st0{fill:#8E8E8E;}
		</style>
	  	<g><path class="st0" d="M78.27,0H13.11C5.87,0,0,5.87,0,13.11v65.16c0,7.24,5.87,13.11,13.11,13.11h65.17
			   c7.24,0,13.11-5.87,13.11-13.11V13.11C91.38,5.87,85.51,0,78.27,0z M78.52,36.55c0,3.19-1.24,6.26-3.46,8.55l-30,30.92c-0.75,0.78-1.66,1.4-2.66,1.83s-2.07,0.65-3.16,0.66c-1.08,0.01-2.16-0.2-3.17-0.61c-1.01-0.41-1.92-1.02-2.69-1.79L15.26,57.99 c-0.77-0.77-1.37-1.68-1.79-2.69c-0.41-1-0.62-2.08-0.61-3.17c0.01-1.09,0.23-2.16,0.66-3.16s1.05-1.9,1.83-2.66l30.93-29.99v0.01 c2.29-2.22,5.36-3.47,8.55-3.47h15.49c4.52,0,8.19,3.66,8.19,8.19L78.52,36.55L78.52,36.55z"/><g> <path class="st0" d="M70.19,27.25c0.02,1.6-0.58,3.15-1.68,4.31c-0.57,0.6-1.25,1.08-2,1.41s-1.56,0.51-2.39,0.52 c-0.82,0.01-1.64-0.14-2.4-0.45c-0.76-0.31-1.45-0.77-2.03-1.35c-0.58-0.58-1.04-1.27-1.35-2.04c-0.31-0.76-0.46-1.58-0.45-2.4 s0.19-1.63,0.52-2.39c0.33-0.75,0.81-1.43,1.4-2c1.16-1.1,2.71-1.7,4.32-1.68c1.6,0.02,3.13,0.67,4.27,1.8 C69.52,24.11,70.17,25.64,70.19,27.25z"/><path class="st0" d="M60.05,45.83c0,0.56-0.11,1.11-0.32,1.62s-0.52,0.98-0.92,1.37L46.78,60.86c-0.8,0.8-1.87,1.24-3,1.24 s-2.2-0.44-3-1.24c-0.8-0.8-1.24-1.87-1.24-3c0-1.12,0.45-2.2,1.24-3l12.03-12.03c0.39-0.39,0.86-0.7,1.38-0.92 c0.51-0.21,1.06-0.32,1.62-0.32c0.56,0,1.11,0.11,1.62,0.32s0.98,0.53,1.37,0.92c0.4,0.4,0.71,0.86,0.92,1.38 C59.94,44.72,60.05,45.27,60.05,45.83z"/><path class="st0" d="M49.79,35.57c0,1.12-0.45,2.2-1.24,3L36.52,50.6c-0.79,0.8-1.87,1.24-3,1.24s-2.2-0.45-3-1.24 c-0.8-0.8-1.24-1.87-1.24-3s0.45-2.2,1.24-3l12.03-12.03c0.8-0.8,1.87-1.24,3-1.24c1.12,0,2.2,0.45,3,1.24 C49.35,33.37,49.79,34.45,49.79,35.57z"/> </g></g>
	   </svg>'
		);
		$capability = apply_filters( 'sp_smart_brand_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		register_post_type(
			'smart_brand_sc',
			array(
				'labels'              => $labels,
				'has_archive'         => true,
				'capability_type'     => 'post',
				'supports'            => array( 'title' ),
				'show_in_menu'        => true,
				'menu_position'       => 56,
				'menu_icon'           => $menu_icon,
				'public'              => true,
				'publicly_queryable'  => false,
				'show_ui'             => $show_ui,
				'exclude_from_search' => true,
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => false,
				'has_archive'         => false,
				'rewrite'             => false,
				'show_in_rest'        => true,
			)
		);
	}

	/**
	 * Post update messages for ShortCode Generator.
	 *
	 * @param string $message The post update message for the post type smart_brand_sc.
	 * @return statement
	 */
	public function post_update_message( $message ) {
		$screen = get_current_screen();
		if ( 'smart_brand_sc' === $screen->post_type ) {
			$message['post'][1] = esc_html__( 'View updated.', 'smart-brands-for-woocommerce' );
			$message['post'][6] = esc_html__( 'View published.', 'smart-brands-for-woocommerce' );
		}

		return $message;
	}
	/**
	 * Customize brand taxonomies update messages.
	 *
	 * @param array $messages The brand taxonomy message.
	 * @since 1.0.0
	 * @return bool
	 */
	public static function update_brand_term_messages( $messages ) {
		$messages['sp_smart_brand'] = array(
			0 => '',
			1 => __( 'Brand added.', 'smart-brands-for-woocommerce' ),
			2 => __( 'Brand deleted.', 'smart-brands-for-woocommerce' ),
			3 => __( 'Brand updated.', 'smart-brands-for-woocommerce' ),
		);

		return $messages;
	}


	/**
	 * Register the styles for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'sp-brand-review-notice', plugin_dir_url( __FILE__ ) . '/assets/css/review-notice' . $this->min . '.css', array(), $this->version, 'all' );
		$current_screen    = get_current_screen();
		$current_post_type = $current_screen->post_type;
		if ( 'smart_brand_sc' === $current_post_type ) {
			wp_enqueue_style( 'sp-brand-admin-styles', plugin_dir_url( __FILE__ ) . '/assets/css/style' . $this->min . '.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'sp-brand-settings-icons', plugin_dir_url( __FILE__ ) . '/assets/css/fontello' . $this->min . '.css', array(), $this->version, 'all' );
		}
		wp_enqueue_style( 'wc-admin-product-table-style', plugin_dir_url( __FILE__ ) . '/assets/css/admin-product.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$current_screen    = get_current_screen();
		$current_post_type = $current_screen->post_type;
		if ( 'smart_brand_sc' === $current_post_type ) {
			wp_enqueue_style( 'smart_brand_swiper' );
			wp_enqueue_style( 'smart_brand_style' );

			wp_enqueue_script( 'smart_brand_swiper' );
			wp_enqueue_script( 'sp-brand-admin-js', plugin_dir_url( __FILE__ ) . '/assets/js/brand-admin' . $this->min . '.js', array( 'jquery' ), $this->version, true );
		}
	}

	/**
	 * Rename columns in all Brand page for Smart Brands for WooCommerce.
	 *
	 * @since    1.0.0
	 * @param  mixed $columns columns of all brand page.
	 */
	public function set_brands_custom_column( $columns ) {
		return array(
			'cb'        => '<input type="checkbox" />',
			'title'     => __( 'Name', 'smart-brands-for-woocommerce' ),
			'shortcode' => __( 'Shortcode', 'smart-brands-for-woocommerce' ),
			'date'      => __( 'Date', 'smart-brands-for-woocommerce' ),
		);
	}

	/**
	 * Get Shortcode generator columns.
	 *
	 * @since    1.0.0
	 * @param  mixed   $column columns of all brand page.
	 * @param integer $post_id post id of brand.
	 */
	public function get_brands_custom_column( $column, $post_id ) {
		switch ( $column ) {
			case 'shortcode':
				echo '<div class="sbfw-after-copy-text"><i class="fa fa-check-circle"></i> ' . esc_html__( 'Shortcode Copied to Clipboard!', 'smart-brands-for-woocommerce' ) . ' </div>';
				echo "<input style='width: 230px; padding: 6px;' readonly='readonly' type='text' onclick='this.select()' value='";
				echo '[smart_brand_for_wc id="' . esc_html( $post_id ) . '"]';
				echo "'/>";
				break;
			default:
				echo '';
		}
	}

	/**
	 * Thumbnail column added to the admin brands list.
	 *
	 * @param mixed $columns Columns array.
	 * @return array
	 */
	public function smart_brand_cat_columns( $columns ) {
		$new_columns = array();

		if ( isset( $columns['cb'] ) ) {
			$new_columns['cb'] = $columns['cb'];
			unset( $columns['cb'] );
		}

		$new_columns['thumb'] = __( 'Logo', 'smart-brands-for-woocommerce' );

		$columns = array_merge( $new_columns, $columns );

		return $columns;
	}

	/**
	 * Thumbnail column value added to admin brands list.
	 *
	 * @param string $columns Column HTML output.
	 * @param string $column Column name.
	 * @param int    $post_id term ID.
	 *
	 * @return string
	 */
	public function smart_brand_cat_column( $columns, $column, $post_id ) {
		if ( 'thumb' === $column ) {
			// Get term meta options.
			$thumbnail    = get_term_meta( $post_id );
			$brand_img    = isset( $thumbnail['sp_smart_brand_taxonomy_meta'][0] ) ? maybe_unserialize( $thumbnail['sp_smart_brand_taxonomy_meta'][0], array( 'allowed_classes' => false ) ) : '';
			$thumbnail_id = isset( $brand_img['smart_brand_term_logo']['id'] ) ? $brand_img['smart_brand_term_logo']['id'] : '';
			if ( $thumbnail_id ) {
				$image = wp_get_attachment_thumb_url( $thumbnail_id );
			} else {
				$image = wc_placeholder_img_src();
			}
			// Prevent esc_url from breaking spaces in urls for image embeds. Ref: https://core.trac.wordpress.org/ticket/23605 .
			$image    = str_replace( ' ', '%20', $image );
			$columns .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Brand logo', 'smart-brands-for-woocommerce' ) . '" class="wp-post-image" height="48" width="48" />';
		}
		return $columns;
	}

	/**
	 * Redirect after activation.
	 *
	 * @since 1.0.8
	 *
	 * @param string $file Path to the plugin file, relative to the plugin.
	 *
	 * @return void
	 */
	public function redirect_after_activation( $file ) {

		if ( plugin_basename( SMART_BRANDS_FILE ) === $file && ( ! ( defined( 'WP_CLI' ) && WP_CLI ) ) ) {
			exit( esc_url( wp_safe_redirect( admin_url( 'edit.php?post_type=smart_brand_sc&page=brand_help' ) ) ) );
		}
	}
}
