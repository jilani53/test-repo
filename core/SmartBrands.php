<?php
/**
 * The file of the SmartBrands class.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.0
 *
 * @package    Smart_Brands_For_Wc
 */

namespace ShapedPlugin\SmartBrands;

use ShapedPlugin\SmartBrands\Loader;
use ShapedPlugin\SmartBrands\Admin\Admin;
use ShapedPlugin\SmartBrands\Admin\HelpPage\Help;
use ShapedPlugin\SmartBrands\Admin\ElementorAddons\Element_Shortcode_Addons;
use ShapedPlugin\SmartBrands\Admin\Brands\Brands;
use ShapedPlugin\SmartBrands\Frontend\Frontend;
use ShapedPlugin\SmartBrands\Traits\Singleton;

// don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The main class of the plugin.
 *
 * Handle all the class and methods of the plugin.
 *
 * @author     ShapedPlugin <support@shapedplugin.com>
 */
class SmartBrands {

	/**
	 * Plugin version
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var string
	 */
	protected $version;

	/**
	 * Plugin slug
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var string
	 */
	protected $plugin_slug;

	/**
	 * Main Loader.
	 *
	 * The loader that's responsible for maintaining and registering all hooks that empowers
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var object
	 */
	protected $loader;

	use Singleton;


	/**
	 * Constructor for the SmartBrands class.
	 *
	 * Sets up all the appropriate hooks and actions within the plugin.
	 */
	public function __construct() {
		$this->version     = SMART_BRANDS_VERSION;
		$this->plugin_slug = 'smart-brands-for-woocommerce';
		$this->load_dependencies();
		$this->define_constants();
		// Define backend facing hooks.
		$this->define_admin_hooks();
		// Define frontend facing hooks.
		$this->define_public_hooks();
		add_action( 'woocommerce_loaded', array( $this, 'init_plugin' ) );

		/**
		 * ElementOr shortcode block.
		 */
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( ( is_plugin_active( 'elementor/elementor.php' ) || is_plugin_active_for_network( 'elementor/elementor.php' ) ) ) {
			new Element_Shortcode_Addons();
		}

	}

	/**
	 * Define the constants
	 *
	 * @return void
	 */
	public function define_constants() {
		define( 'SMART_BRANDS_INCLUDES', SMART_BRANDS_PATH . '/includes' );
		define( 'SMART_BRANDS_URL', plugins_url( '', SMART_BRANDS_FILE ) );
		define( 'SMART_BRANDS_FRONT', SMART_BRANDS_URL . '/core/Frontend' );
	}

	/**
	 * Load the plugin after all plugins are loaded.
	 *
	 * @return void
	 */
	public function init_plugin() {
		do_action( 'smart_brands_for_wc_loaded' );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Loader. Orchestrates the hooks of the plugin.
	 * - Teamproi18n. Defines internationalization functionality.
	 * - Admin. Defines all hooks for the admin area.
	 * - Frontend. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		$this->loader = new Loader();
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Frontend( $this->get_plugin_slug(), $this->get_version() );
		$this->loader->add_shortcode( 'smart_brand_for_wc', $plugin_public, 'render_shortcode' );
		$this->loader->add_action( 'wp_loaded', $plugin_public, 'register_all_scripts' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}

	/**
	 * Register all of the hooks related to the admin dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Admin( $this->get_plugin_slug(), $this->get_version() );

		$this->loader->add_action( 'woocommerce_init', $plugin_admin, 'shortcode_post_type' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		// Help Page.
		Help::instance();
		$this->loader->add_filter( 'manage_smart_brand_sc_posts_columns', $plugin_admin, 'set_brands_custom_column', 10, 2 );
		$this->loader->add_action( 'manage_smart_brand_sc_posts_custom_column', $plugin_admin, 'get_brands_custom_column', 10, 2 );
		$this->loader->add_filter( 'post_updated_messages', $plugin_admin, 'post_update_message' );
		$this->loader->add_filter( 'term_updated_messages', $plugin_admin, 'update_brand_term_messages' );
		// WooCommerce Error Admin Notice.
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		if ( ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) && ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			add_action( 'admin_notices', array( $this, 'sp_brand_error_admin_notice' ) );
		}
		// Smart Brands Taxonomy.
		$brand_taxonomy = new Brands();
		$this->loader->add_action( 'woocommerce_init', $brand_taxonomy, 'register_taxonomy_brands' );
	}

	/**
	 * Initialize plugin for localization
	 *
	 * @uses load_plugin_textdomain()
	 */
	public function localization_setup() {
		load_plugin_textdomain( 'smart-brands-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * WooCommerce not installed error message
	 */
	public function sp_brand_error_admin_notice() {
		$link    = esc_url(
			add_query_arg(
				array(
					'tab'       => 'plugin-information',
					'plugin'    => 'woocommerce',
					'TB_iframe' => 'true',
					'width'     => '640',
					'height'    => '500',
				),
				admin_url( 'plugin-install.php' )
			)
		);
		$outline = '<div class="error"><p>You must install and activate <a class="thickbox open-plugin-details-modal" href="' . $link . '"><strong>WooCommerce</strong></a> plugin to make the <strong>Smart Brand for WooCommerce</strong> work.</p></div>';
		echo wp_kses_post( $outline );
	}

	/**
	 * Is WooCommerce active
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function is_wc_active() {
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'smart_brands_for_wc_active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The slug of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
