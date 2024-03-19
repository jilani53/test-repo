<?php
/**
 * The plugin elementor addons.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Admin/ElementorAddons
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\SmartBrands\Admin\ElementorAddons;

use ShapedPlugin\SmartBrands\Admin\ElementorAddons\Widgets;

/**
 * Elementor shortcode addon.
 */
class Element_Shortcode_Addons {
	/**
	 * Script and Style suffix
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string
	 */
	protected $min;

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Element_Shortcode_Addons The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Elementor_Test_Extension An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		$this->on_plugins_loaded();
		add_action( 'elementor/preview/enqueue_scripts', array( $this, 'sp_brands_enqueue_scripts' ) );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'sp_brands_enqueue_styles' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'sp_brands_addons_icon' ) );
	}

	/**
	 * Enqueue CSS for the ElementOr edit page.
	 *
	 * @since    1.0.0
	 */
	public function sp_brands_enqueue_styles() {
		/**
		 * Register block editor CSS file enqueue for backend.
		 */
		wp_enqueue_style( 'smart_brand_swiper' );
		wp_enqueue_style( 'smart_brand_font_awesome' );
		wp_enqueue_style( 'smart_brand_style' );
	}
	/**
	 * Enqueue the JavaScript for the ElementOr block area.
	 *
	 * @since    1.0.0
	 */
	public function sp_brands_enqueue_scripts() {
		/**
		 * Register block editor CSS file enqueue for elemantor edit page.
		 */
		wp_enqueue_style( 'smart_brand_swiper' );
		wp_enqueue_style( 'smart_brand_style' );
	}

	/**
	 * Elementor block icon.
	 *
	 * @since    1.0.0
	 * @return void
	 */
	public function sp_brands_addons_icon() {
		wp_enqueue_style( 'smart-brands-elementor-addons-icon', plugin_dir_url( __DIR__ ) . '/assets/css/smart-brands-icon.min.css', array(), '1.0.0', 'all' );
	}

	/**
	 * On Plugins Loaded
	 *
	 * Checks if Elementor has loaded, and performs some compatibility checks.
	 * If All checks pass, inits the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function on_plugins_loaded() {
		add_action( 'elementor/init', array( $this, 'init' ) );
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {
		// Add Plugin actions.
		add_action( 'elementor/widgets/register', array( $this, 'init_widgets' ) );
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_widgets() {
		// Register widget.
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\Element_Shortcode_Widget() );

	}

}
Element_Shortcode_Addons::instance();
