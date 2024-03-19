<?php
/**
 * The plugin gutenberg block Initializer.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.4
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\SmartBrands\Admin\GutenbergBlock;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode class.
 */
class Shortcode {
	/**
	 * Script and style suffix
	 *
	 * @since 1.0.4
	 * @access protected
	 * @var string
	 */
	protected $min;

	/**
	 * Custom Gutenberg Block Initializer.
	 */
	public function __construct() {
		$this->min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';
		add_action( 'init', array( $this, 'sp_smart_brand_gutenberg_shortcode_block' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'sp_smart_brand_block_editor_assets' ) );
	}

	/**
	 * Register block editor script for backend.
	 */
	public function sp_smart_brand_block_editor_assets() {
		wp_enqueue_script(
			'sp-smart-brand-shortcode-block',
			plugins_url( '/GutenbergBlock/build/index.js', __DIR__ ),
			array( 'jquery' ),
			SMART_BRANDS_VERSION,
			true
		);

		/**
		 * Register block editor css file enqueue for backend.
		 */
		wp_enqueue_style( 'smart_brand_swiper' );
		wp_enqueue_style( 'smart_brand_style' );
	}

	/**
	 * Shortcode list.
	 *
	 * @return array
	 */
	public function post_list() {
		$shortcodes = get_posts(
			array(
				'post_type'      => 'smart_brand_sc',
				'post_status'    => 'publish',
				'posts_per_page' => 9999,
			)
		);

		if ( count( $shortcodes ) < 1 ) {
			return array();
		}

		return array_map(
			function ( $shortcode ) {
					return (object) array(
						'id'    => absint( $shortcode->ID ),
						'title' => esc_html( $shortcode->post_title ),
					);
			},
			$shortcodes
		);
	}

	/**
	 * Register Gutenberg shortcode block.
	 */
	public function sp_smart_brand_gutenberg_shortcode_block() {
		/**
		 * Register block editor js file enqueue for backend.
		 */
		wp_register_script( 'sp-smart-brand-gb', SMART_BRANDS_URL . '/core/Admin/GutenbergBlock/assets/js/gutenberg-script.js', array( 'jquery' ), SMART_BRANDS_VERSION, true );

		wp_localize_script(
			'sp-smart-brand-gb',
			'sp_smart_brand_gb_block',
			array(
				'url'           => SMART_BRANDS_URL,
				'loadPublic'    => SMART_BRANDS_URL . '/core/Frontend/assets/js/script.min.js',
				'link'          => admin_url( 'post-new.php?post_type=smart_brand_sc' ),
				'shortCodeList' => $this->post_list(),
			)
		);
		/**
		 * Register Gutenberg block on server-side.
		 */
		register_block_type(
			'sp-smart-brand/shortcode',
			array(
				'attributes'      => array(
					'shortcode'          => array(
						'type'    => 'object',
						'default' => '',
					),
					'shortcode'          => array(
						'type'    => 'string',
						'default' => '',
					),
					'showInputShortcode' => array(
						'type'    => 'boolean',
						'default' => true,
					),
					'preview'            => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'is_admin'           => array(
						'type'    => 'boolean',
						'default' => is_admin(),
					),
				),
				'example'         => array(
					'attributes' => array(
						'preview' => true,
					),
				),
				// Enqueue blocks.editor.build.js in the editor only.
				'editor_script'   => array(
					'smart_brand_swiper',
					'sp-smart-brand-gb',
				),
				// Enqueue blocks.editor.build.css in the editor only.
				'editor_style'    => array(),
				'render_callback' => array( $this, 'sp_smart_brand_render_shortcode' ),
			)
		);
	}

	/**
	 * Render callback.
	 *
	 * @param string $attributes Shortcode.
	 * @return string
	 */
	public function sp_smart_brand_render_shortcode( $attributes ) {

		$class_name = '';
		if ( ! empty( $attributes['className'] ) ) {
			$class_name = 'class="' . esc_attr( $attributes['className'] ) . '"';
		}
		if ( ! $attributes['is_admin'] ) {
			return '<div ' . $class_name . '>' . do_shortcode( '[smart_brand_for_wc id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' ) . '</div>';
		}

		$edit_page_link = get_edit_post_link( sanitize_text_field( $attributes['shortcode'] ) );
		return '<div id="' . uniqid() . '" ' . $class_name . ' ><a href="' . esc_url( $edit_page_link ) . '" target="_blank" class="sp_smart_brand_block_edit_button">' . __( 'Edit Brand', 'smart-brands-for-woocommerce' ) . '</a>' . do_shortcode( '[smart_brand_for_wc id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' ) . '</div>';
	}
}
