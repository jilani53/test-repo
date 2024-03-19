<?php
/**
 * The plugin elementor shortcode Widget.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Admin/ElementorAddons/Widgets
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\SmartBrands\Admin\ElementorAddons\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ShapedPlugin\SmartBrands\Frontend\Frontend;
use ShapedPlugin\SmartBrands\Frontend\Manager;

/**
 * Elementor List Widget.
 *
 * Elementor widget that inserts an embeddable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Element_Shortcode_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve list widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'sp-brands-shortcode-ew';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve list widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Smart Brands for WooCommerce', 'smart-brands-for-woocommerce' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve list widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'sp_smart_brands-elementor-icon';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the list widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'basic' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the list widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'shortcode', 'wp brands', 'brand' );
	}

	/**
	 * Get all post list.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function sp_brands_post_list() {
		$post_list       = array();
		$sp_brands_posts = new \WP_Query(
			array(
				'post_type'      => 'smart_brand_sc',
				'post_status'    => 'publish',
				'posts_per_page' => 10000,
			)
		);
		$posts           = $sp_brands_posts->posts;
		foreach ( $posts as $post ) {
			$post_list[ $post->ID ] = $post->post_title;
		}
		krsort( $post_list );
		return $post_list;
	}

	/**
	 * Register list widget controls.
	 *
	 * Add input fields to allow the user to customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'smart-brands-for-woocommerce' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'sp_smart_brands_shortcode',
			array(
				'label'       => __( 'Smart Brands Shortcode(s)', 'smart-brands-for-woocommerce' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => '',
				'options'     => $this->sp_brands_post_list(),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings                  = $this->get_settings_for_display();
		$sp_smart_brands_shortcode = $settings['sp_smart_brands_shortcode'];

		if ( '' === $sp_smart_brands_shortcode ) {
			echo '<div style="text-align: center; margin-top: 0; padding: 10px" class="elementor-add-section-drag-title">Select a shortcode</div>';
			return;
		}

		$views_id = (int) $sp_smart_brands_shortcode;
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {

			$views_meta         = get_post_meta( $views_id, 'sp_smart_brand_metaboxes', true );
			$main_section_title = get_the_title( $views_id );
			// Stylesheet loading problem solving here. Shortcode id to push page id option for getting how many shortcode in the page.
			$get_page_data      = Frontend::get_page_data();
			$found_generator_id = $get_page_data['generator_id'];
			// This shortcode id not in page id option. Enqueue stylesheets in shortcode.
			if ( ! is_array( $found_generator_id ) || ! $found_generator_id || ! in_array( $views_id, $found_generator_id ) ) {
				wp_enqueue_style( 'smart_brand_swiper' );
				wp_enqueue_style( 'smart_brand_font_awesome' );
				wp_enqueue_style( 'smart_brand_style' );
				// Load dynamic style based on the existing shortcode ids in the current page.
				$dynamic_style = Frontend::load_dynamic_style( $views_id, $views_meta );
				echo '<style id="sp_smart_brand_css' . esc_attr( $views_id ) . '">' . wp_strip_all_tags( $dynamic_style['dynamic_css'] ) . '</style>';
			}

			// Update options if the existing shortcode id option not found.
			Manager::views_html( $views_id, $views_meta, $main_section_title );
			?>
			<script src="<?php echo esc_url( SMART_BRANDS_URL . '/core/Frontend/assets/js/script.min.js' ); ?>" ></script>
			<script src="<?php echo esc_url( SMART_BRANDS_URL . '/core/Frontend/assets/js/swiper-bundle.min.js' ); ?>" ></script>
			<?php
		} else {
			echo do_shortcode( '[smart_brand_for_wc id="' . esc_attr( $views_id ) . '"]' );
		}
	}

}
