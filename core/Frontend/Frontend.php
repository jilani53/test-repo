<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Frontend
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

namespace ShapedPlugin\SmartBrands\Frontend;

use ShapedPlugin\SmartBrands\Frontend\Manager;

/**
 * The Frontend class to manage all public facing stuffs.
 *
 * @since 1.0.0
 */
class Frontend {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		add_shortcode( 'smart_brand_for_wc', array( $this, 'render_shortcode' ) );
		$this->brand_logo_position();

		add_action(
			'wp',
			function () {
				if ( is_tax( 'sp_smart_brand' ) ) {
					remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
				}}
		);
		add_action( 'save_post', array( $this, 'delete_page_option_on_save' ) );
		add_action( 'template_redirect', array( $this, 'sp_support_block_template' ) );
	}

	/**
	 * Gets the existing shortcode-id, page-id and option-key from the current page.
	 *
	 * @return array
	 */
	public static function get_page_data() {
		$current_page_id    = get_queried_object_id();
		$option_key         = 'sp_smart_brand_page_id' . $current_page_id;
		$found_generator_id = get_option( $option_key );
		if ( is_multisite() ) {
			$option_key         = 'sp_smart_brand_page_id' . get_current_blog_id() . $current_page_id;
			$found_generator_id = get_site_option( $option_key );
		}
		$get_page_data = array(
			'page_id'      => $current_page_id,
			'generator_id' => $found_generator_id,
			'option_key'   => $option_key,
		);
		return $get_page_data;
	}

	/**
	 * Load dynamic style of the existing shortcode id.
	 *
	 * @param  mixed $found_generator_id to push id option for getting how many shortcode in the page.
	 * @param  mixed $views_meta to push all options.
	 * @return array get dynamic style use in the specific shortcode.
	 */
	public static function load_dynamic_style( $found_generator_id, $views_meta = '' ) {
		$custom_css = '';
		// If multiple shortcode found in the page.
		if ( is_array( $found_generator_id ) ) {
			foreach ( $found_generator_id as $views_id ) {
				if ( $views_id && is_numeric( $views_id ) && get_post_status( $views_id ) !== 'trash' ) {
					$views_meta = get_post_meta( $views_id, 'sp_smart_brand_metaboxes', true );
					include 'partials/dynamic-style.php';
				}
			}
		} else {
			// If single shortcode found in the page.
			$views_id = $found_generator_id;
			include 'partials/dynamic-style.php';
		}
		$custom_style = trim( html_entity_decode( get_option( 'sp_smart_brand_settings' )['custom_css'] ) );
		// Custom css merge with dynamic style.
		if ( ! empty( $custom_style ) ) {
			$custom_css .= $custom_style;
		}
		$dynamic_style = array(
			'dynamic_css' => Manager::minify_output( $custom_css ),
		);
		return $dynamic_style;
	}

	/**
	 * If the option does not exist, it will be created.
	 *
	 * It will be serialized before it is inserted into the database.
	 *
	 * @param  string $post_id existing shortcode id.
	 * @param  array  $get_page_data get current page-id, shortcode-id and option-key from the page.
	 * @return void
	 */
	public static function db_options_update( $post_id, $get_page_data ) {
		$found_generator_id = $get_page_data['generator_id'];
		$option_key         = $get_page_data['option_key'];
		$current_page_id    = $get_page_data['page_id'];
		if ( $found_generator_id ) {
			$found_generator_id = is_array( $found_generator_id ) ? $found_generator_id : array( $found_generator_id );
			if ( ! in_array( $post_id, $found_generator_id ) || empty( $found_generator_id ) ) {
				// If not found the shortcode id in the page options.
				array_push( $found_generator_id, $post_id );
				if ( is_multisite() ) {
					update_site_option( $option_key, $found_generator_id );
				} else {
					update_option( $option_key, $found_generator_id );
				}
			}
		} else {
			// If option not set in current page add option.
			if ( $current_page_id ) {
				if ( is_multisite() ) {
					add_site_option( $option_key, array( $post_id ) );
				} else {
					add_option( $option_key, array( $post_id ) );
				}
			}
		}
	}

	/**
	 * Delete page shortcode ids array option on save
	 *
	 * @param  int $post_ID current post id.
	 * @return void
	 */
	public function delete_page_option_on_save( $post_ID ) {
		if ( is_multisite() ) {
			$option_key = 'sp_smart_brand_page_id' . get_current_blog_id() . $post_ID;
			if ( get_site_option( $option_key ) ) {
				delete_site_option( $option_key );
			}
		} elseif ( get_option( 'sp_smart_brand_page_id' . $post_ID ) ) {
				delete_option( 'sp_smart_brand_page_id' . $post_ID );
		}
	}

	/**
	 * Support block templates for the brand taxonomy.
	 */
	public function sp_support_block_template() {
		/**
		 * Check if the function wp_is_block_theme() exists and if the current theme is a block-based theme.
		 */
		if ( is_tax( 'sp_smart_brand' ) && function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) {
			// Enable block templates for Brands archive page.
			add_filter( 'woocommerce_has_block_template', '__return_true', 10, 0 );
		}
	}

	/**
	 * WooCommerce_single_product_summary hook.
	 *
	 * @hooked woocommerce_template_single_title - 5
	 * @hooked woocommerce_template_single_rating - 10
	 * @hooked woocommerce_template_single_price - 10
	 * @hooked woocommerce_template_single_excerpt - 20
	 * @hooked woocommerce_template_single_add_to_cart - 30
	 * @hooked woocommerce_template_single_meta - 40
	 * @hooked woocommerce_template_single_sharing - 50
	 */
	private function brand_logo_position() {
		include 'partials/settings.php';

		// Brand position in product single page.
		if ( $show_brand_in_single_page ) {
			if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) {
				add_action( 'woocommerce_product_meta_end', array( $this, 'brand_in_woocommerce_single_product_summary' ), 31 );
			} else {
				add_action( 'woocommerce_single_product_summary', array( $this, 'brand_in_woocommerce_single_product_summary' ), 31 );
			}
		}
		// Brand logo position in loop/shop page.
		if ( $show_brand_in_loop_page ) {
			add_action( 'woocommerce_after_shop_loop_item', array( $this, 'show_brands_in_loop' ), 10 );
		}
		add_action( 'sp_wps_brands_after_product', array( $this, 'show_brands_in_loop' ), 10 );
	}

	/**
	 * Brand view in single product page.
	 *
	 * @since    1.0.0
	 */
	public function brand_in_woocommerce_single_product_summary() {
		require 'partials/settings.php';
		global $product;
		$current_product = $product->get_id();
		$brands          = wp_get_post_terms( $current_product, 'sp_smart_brand' );
		echo '<div class="sp-smart-brand-single-product">';
		echo '<div class="sp-smart-brand-content-wrapper">';
		if ( ! empty( $brands ) ) {
			echo esc_html( $brand_label_before_name );
		}
		foreach ( $brands as $brand_key => $brand_term ) {
			include Manager::smart_locate_template( 'brand/name.php' );
		}
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Brand view in Loop or shop page.
	 *
	 * @since    1.0.0
	 */
	public function show_brands_in_loop() {
		require 'partials/settings.php';
		global $product;
		$current_product = $product->get_id();
		$brands          = wp_get_post_terms( $current_product, 'sp_smart_brand' );
		echo '<div class="sp-smart-brand-single-product">';
		echo '<div class="sp-smart-brand-content-wrapper">';

		if ( ! empty( $brands ) ) {
			echo esc_html( $brand_label_before_name );
		}
		foreach ( $brands as $brand_key => $brand_term ) {
			include Manager::smart_locate_template( 'brand/name.php' );
		}
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Category_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Category_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$get_page_data      = self::get_page_data();
		$found_generator_id = $get_page_data['generator_id'];
		if ( $found_generator_id ) {
			wp_enqueue_style( 'smart_brand_swiper' );
			wp_enqueue_style( 'smart_brand_font_awesome' );
			wp_enqueue_style( 'smart_brand_style' );
			// Load dynamic style based on the existing shortcode ids in the current page.
			$dynamic_style = self::load_dynamic_style( $found_generator_id );
			wp_add_inline_style( 'smart_brand_style', wp_strip_all_tags( $dynamic_style['dynamic_css'] ) );
		} else {
			wp_enqueue_style( 'smart_brand_style' );
		}
	}

	/**
	 * Register the All scripts for the public-facing side of the site.
	 *
	 * @since    2.0
	 */
	public function register_all_scripts() {
		wp_register_style( 'smart_brand_swiper', SMART_BRANDS_FRONT . '/assets/css/swiper-bundle.min.css', array(), '7.4.1', 'all' );
		wp_register_style( 'smart_brand_font_awesome', SMART_BRANDS_FRONT . '/assets/css/font-awesome.min.css', array(), SMART_BRANDS_VERSION, 'all' );
		wp_register_style( 'smart_brand_style', SMART_BRANDS_FRONT . '/assets/css/style.min.css', array(), SMART_BRANDS_VERSION, 'all' );

		wp_register_script( 'smart_brand_swiper', SMART_BRANDS_FRONT . '/assets/js/swiper-bundle.min.js', array(), '7.4.1', true );
		wp_register_script( 'smart_brand_script', SMART_BRANDS_FRONT . '/assets/js/script.js', array(), SMART_BRANDS_VERSION, true );
	}

	/**
	 * Generate and render shortcode.
	 *
	 * @param array $attributes Shortcode's all option.
	 * @since 1.0.0
	 */
	public function render_shortcode( $attributes ) {
		// Show nothing if the shortocode/view post is in trash or shortcode doesn't have any ID.
		if ( empty( $attributes['id'] ) || 'smart_brand_sc' !== get_post_type( $attributes['id'] ) || ( 'trash' === get_post_status( $attributes['id'] ) ) ) {
			return;
		}
		$views_id           = esc_attr( (int) $attributes['id'] );
		$views_meta         = get_post_meta( $views_id, 'sp_smart_brand_metaboxes', true );
		$main_section_title = get_the_title( $views_id );
		ob_start();
		// Stylesheet loading problem solving here. Shortcode id to push page id option for getting how many shortcode in the page.
		$get_page_data      = self::get_page_data();
		$found_generator_id = $get_page_data['generator_id'];
		// This shortcode id not in page id option. Enqueue stylesheets in shortcode.
		if ( ! is_array( $found_generator_id ) || ! $found_generator_id || ! in_array( $views_id, $found_generator_id ) ) {
			wp_enqueue_style( 'smart_brand_swiper' );
			wp_enqueue_style( 'smart_brand_font_awesome' );
			wp_enqueue_style( 'smart_brand_style' );
			// Load dynamic style based on the existing shortcode ids in the current page.
			$dynamic_style = self::load_dynamic_style( $views_id, $views_meta );
			echo '<style id="sp_smart_brand_css' . $views_id . '">' . wp_strip_all_tags( $dynamic_style['dynamic_css'] ) . '</style>'; // phpcs:ignore
		}

		// Update options if the existing shortcode id option not found.
		self::db_options_update( $views_id, $get_page_data );
		Manager::views_html( $views_id, $views_meta, $main_section_title );
		return ob_get_clean();
	}
}
