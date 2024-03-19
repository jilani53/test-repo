<?php
/**
 * The help page for the Smart Brands
 *
 * @package Smart Brands
 * @subpackage smart_brands_for_wc/admin
 */

namespace ShapedPlugin\SmartBrands\Admin\HelpPage;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access.

/**
 * The help class for the Smart Brands
 */
class Help {

	/**
	 * Single instance of the class
	 *
	 * @var null
	 */
	protected static $_instance = null;

	/**
	 * Plugins Path variable.
	 *
	 * @var array
	 */
	protected static $plugins = array(
		'woo-product-slider'             => 'main.php',
		'gallery-slider-for-woocommerce' => 'woo-gallery-slider.php',
		'post-carousel'                  => 'main.php',
		'easy-accordion-free'            => 'plugin-main.php',
		'logo-carousel-free'             => 'main.php',
		'location-weather'               => 'main.php',
		'woo-quickview'                  => 'woo-quick-view.php',
		'wp-expand-tabs-free'            => 'plugin-main.php',

	);

	/**
	 * Welcome pages
	 *
	 * @var array
	 */
	public $pages = array(
		'brand_help',
	);


	/**
	 * Not show this plugin list.
	 *
	 * @var array
	 */
	protected static $not_show_plugin_list = array( 'aitasi-coming-soon', 'latest-posts', 'widget-post-slider', 'easy-lightbox-wp' );

	/**
	 * Help Page construct function.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'help_admin_menu' ), 80 );

        $page   = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';// @codingStandardsIgnoreLine
		if ( 'brand_help' !== $page ) {
			return;
		}
		add_action( 'admin_print_scripts', array( $this, 'disable_admin_notices' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'help_page_enqueue_scripts' ) );
	}

	/**
	 * Main Help Page Instance
	 *
	 * @static
	 * @see Help Page
	 * @return self Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Help_page_enqueue_scripts function.
	 *
	 * @return void
	 */
	public function help_page_enqueue_scripts() {
		wp_enqueue_style( 'sp-smart-brand-help', SMART_BRANDS_URL . '/core/Admin/HelpPage/css/help-page.min.css', array(), SMART_BRANDS_VERSION );
		wp_enqueue_style( 'sp-smart-brand-help-fontello', SMART_BRANDS_URL . '/core/Admin/HelpPage/css/fontello.min.css', array(), SMART_BRANDS_VERSION );

		wp_enqueue_script( 'sp-smart-brand-help', SMART_BRANDS_URL . '/core/Admin/HelpPage/js/help-page.min.js', array(), SMART_BRANDS_VERSION, true );
	}

	/**
	 * Add admin menu.
	 *
	 * @return void
	 */
	public function help_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=smart_brand_sc',
			__( 'Smart Brands', 'smart-brands-for-woocommerce' ),
			__( 'Recommended', 'smart-brands-for-woocommerce' ),
			'manage_options',
			'edit.php?post_type=smart_brand_sc&page=brand_help#recommended'
		);
		add_submenu_page(
			'edit.php?post_type=smart_brand_sc',
			__( 'Smart Brands', 'smart-brands-for-woocommerce' ),
			__( 'Lite vs Pro', 'smart-brands-for-woocommerce' ),
			'manage_options',
			'edit.php?post_type=smart_brand_sc&page=brand_help#lite-to-pro'
		);
		add_submenu_page(
			'edit.php?post_type=smart_brand_sc',
			__( 'Smart Brands Help', 'smart-brands-for-woocommerce' ),
			__( 'Get Help', 'smart-brands-for-woocommerce' ),
			'manage_options',
			'brand_help',
			array(
				$this,
				'help_page_callback',
			)
		);
	}

	/**
	 * Spsbf_plugins_info_api_help_page function.
	 *
	 * @return void
	 */
	public function spsbf_plugins_info_api_help_page() {
		$plugins_arr = get_transient( 'spsbf_plugins' );
		if ( false === $plugins_arr ) {
			$args    = (object) array(
				'author'   => 'shapedplugin',
				'per_page' => '120',
				'page'     => '1',
				'fields'   => array(
					'slug',
					'name',
					'version',
					'downloaded',
					'active_installs',
					'last_updated',
					'rating',
					'num_ratings',
					'short_description',
					'author',
				),
			);
			$request = array(
				'action'  => 'query_plugins',
				'timeout' => 30,
				'request' => serialize( $args ),
			);
			// https://codex.wordpress.org/WordPress.org_API.
			$url      = 'http://api.wordpress.org/plugins/info/1.0/';
			$response = wp_remote_post( $url, array( 'body' => $request ) );

			if ( ! is_wp_error( $response ) ) {

				$plugins_arr = array();
				$plugins     = unserialize( $response['body'] );

				if ( isset( $plugins->plugins ) && ( count( $plugins->plugins ) > 0 ) ) {
					foreach ( $plugins->plugins as $pl ) {
						if ( ! in_array( $pl->slug, self::$not_show_plugin_list, true ) ) {
							$plugins_arr[] = array(
								'slug'              => $pl->slug,
								'name'              => $pl->name,
								'version'           => $pl->version,
								'downloaded'        => $pl->downloaded,
								'active_installs'   => $pl->active_installs,
								'last_updated'      => strtotime( $pl->last_updated ),
								'rating'            => $pl->rating,
								'num_ratings'       => $pl->num_ratings,
								'short_description' => $pl->short_description,
							);
						}
					}
				}

				set_transient( 'spsbf_plugins', $plugins_arr, 24 * HOUR_IN_SECONDS );
			}
		}

		if ( is_array( $plugins_arr ) && ( count( $plugins_arr ) > 0 ) ) {
			array_multisort( array_column( $plugins_arr, 'active_installs' ), SORT_DESC, $plugins_arr );

			foreach ( $plugins_arr as $plugin ) {
				$plugin_slug = $plugin['slug'];
				$image_type  = 'png';
				if ( isset( self::$plugins[ $plugin_slug ] ) ) {
					$plugin_file = self::$plugins[ $plugin_slug ];
				} else {
					$plugin_file = $plugin_slug . '.php';
				}

				switch ( $plugin_slug ) {
					case 'styble':
						$image_type = 'jpg';
						break;
					case 'location-weather':
					case 'gallery-slider-for-woocommerce':
						$image_type = 'gif';
						break;
				}

				$details_link = network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] . '&amp;TB_iframe=true&amp;width=750&amp;height=550' );
				?>
				<div class="plugin-card <?php echo esc_attr( $plugin_slug ); ?>" id="<?php echo esc_attr( $plugin_slug ); ?>">
					<div class="plugin-card-top">
						<div class="name column-name">
							<h3>
								<a class="thickbox" title="<?php echo esc_attr( $plugin['name'] ); ?>" href="<?php echo esc_url( $details_link ); ?>">
						<?php echo esc_html( $plugin['name'] ); ?>
									<img src="<?php echo esc_url( 'https://ps.w.org/' . $plugin_slug . '/assets/icon-256x256.' . $image_type ); ?>" class="plugin-icon"/>
								</a>
							</h3>
						</div>
						<div class="action-links">
							<ul class="plugin-action-buttons">
								<li>
						<?php
						if ( $this->is_plugin_installed( $plugin_slug, $plugin_file ) ) {
							if ( $this->is_plugin_active( $plugin_slug, $plugin_file ) ) {
								?>
										<button type="button" class="button button-disabled" disabled="disabled">Active</button>
									<?php
							} else {
								?>
											<a href="<?php echo esc_url( $this->activate_plugin_link( $plugin_slug, $plugin_file ) ); ?>" class="button button-primary activate-now">
									<?php esc_html_e( 'Activate', 'smart-brands-for-woocommerce' ); ?>
											</a>
									<?php
							}
						} else {
							?>
										<a href="<?php echo esc_url( $this->install_plugin_link( $plugin_slug ) ); ?>" class="button install-now">
								<?php esc_html_e( 'Install Now', 'smart-brands-for-woocommerce' ); ?>
										</a>
								<?php } ?>
								</li>
								<li>
									<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php echo esc_attr( 'More information about ' . $plugin['name'] ); ?>" title="<?php echo esc_attr( $plugin['name'] ); ?>">
								<?php esc_html_e( 'More Details', 'smart-brands-for-woocommerce' ); ?>
									</a>
								</li>
							</ul>
						</div>
						<div class="desc column-description">
							<p><?php echo esc_html( isset( $plugin['short_description'] ) ? $plugin['short_description'] : '' ); ?></p>
							<p class="authors"> <cite>By <a href="https://shapedplugin.com/">ShapedPlugin LLC</a></cite></p>
						</div>
					</div>
					<?php
					echo '<div class="plugin-card-bottom">';

					if ( isset( $plugin['rating'], $plugin['num_ratings'] ) ) {
						?>
						<div class="vers column-rating">
							<?php
							wp_star_rating(
								array(
									'rating' => $plugin['rating'],
									'type'   => 'percent',
									'number' => $plugin['num_ratings'],
								)
							);
							?>
							<span class="num-ratings">(<?php echo esc_html( number_format_i18n( $plugin['num_ratings'] ) ); ?>)</span>
						</div>
						<?php
					}
					if ( isset( $plugin['version'] ) ) {
						?>
						<div class="column-updated">
							<strong><?php esc_html_e( 'Version:', 'smart-brands-for-woocommerce' ); ?></strong>
							<span><?php echo esc_html( $plugin['version'] ); ?></span>
						</div>
							<?php
					}

					if ( isset( $plugin['active_installs'] ) ) {
						?>
						<div class="column-downloaded">
						<?php echo esc_html( number_format_i18n( $plugin['active_installs'] ) ) . esc_html__( '+ Active Installations', 'smart-brands-for-woocommerce' ); ?>
						</div>
									<?php
					}

					if ( isset( $plugin['last_updated'] ) ) {
						?>
						<div class="column-compatibility">
							<strong><?php esc_html_e( 'Last Updated:', 'smart-brands-for-woocommerce' ); ?></strong>
							<span><?php echo esc_html( human_time_diff( $plugin['last_updated'] ) ) . ' ' . esc_html__( 'ago', 'smart-brands-for-woocommerce' ); ?></span>
						</div>
									<?php
					}

					echo '</div>';
					?>
				</div>
				<?php
			}
		}
	}

	/**
	 * Check plugins installed function.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return boolean
	 */
	public function is_plugin_installed( $plugin_slug, $plugin_file ) {
		return file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Check active plugin function
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return boolean
	 */
	public function is_plugin_active( $plugin_slug, $plugin_file ) {
		return is_plugin_active( $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Install plugin link.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @return string
	 */
	public function install_plugin_link( $plugin_slug ) {
		return wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin_slug ), 'install-plugin_' . $plugin_slug );
	}

	/**
	 * Active Plugin Link function
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return string
	 */
	public function activate_plugin_link( $plugin_slug, $plugin_file ) {
		return wp_nonce_url( admin_url( 'edit.php?post_type=smart_brand_sc&page=brand_help&action=activate&plugin=' . $plugin_slug . '/' . $plugin_file . '#recommended' ), 'activate-plugin_' . $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Making page as clean as possible
	 */
	public function disable_admin_notices() {

		global $wp_filter;

		if ( isset( $_GET['post_type'] ) && isset( $_GET['page'] ) && 'smart_brand_sc' === wp_unslash( $_GET['post_type'] ) && in_array( wp_unslash( $_GET['page'] ), $this->pages ) ) { // @codingStandardsIgnoreLine

			if ( isset( $wp_filter['user_admin_notices'] ) ) {
				unset( $wp_filter['user_admin_notices'] );
			}
			if ( isset( $wp_filter['admin_notices'] ) ) {
				unset( $wp_filter['admin_notices'] );
			}
			if ( isset( $wp_filter['all_admin_notices'] ) ) {
				unset( $wp_filter['all_admin_notices'] );
			}
		}
	}

	/**
	 * The Smart Brands Help Callback.
	 *
	 * @return void
	 */
	public function help_page_callback() {
		add_thickbox();

		$action   = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
		$plugin   = isset( $_GET['plugin'] ) ? sanitize_text_field( wp_unslash( $_GET['plugin'] ) ) : '';
		$_wpnonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

		if ( isset( $action, $plugin ) && ( 'activate' === $action ) && wp_verify_nonce( $_wpnonce, 'activate-plugin_' . $plugin ) ) {
			activate_plugin( $plugin, '', false, true );
		}

		if ( isset( $action, $plugin ) && ( 'deactivate' === $action ) && wp_verify_nonce( $_wpnonce, 'deactivate-plugin_' . $plugin ) ) {
			deactivate_plugins( $plugin, '', false, true );
		}

		?>
		<div class="sp-smart-brand-help">
			<!-- Header section start -->
			<section class="spsbf__help header">
				<div class="spsbf-header-area-top">
					<p>You’re currently using <b>Smart Brands Lite</b>. To access additional features, consider <a target="_blank" href="https://shapedplugin.com/plugin/smart-brands-for-woocommerce/?ref=1" ><b>upgrading to Pro!</b></a> 🚀</p>
				</div>
				<div class="spsbf-header-area">
					<div class="spsbf-container">
						<div class="spsbf-header-logo">
							<img src="<?php echo esc_url( SMART_BRANDS_URL . '/core/Admin/HelpPage/img/logo.svg' ); ?>" alt="">
							<span><?php echo esc_html( SMART_BRANDS_VERSION ); ?></span>
						</div>
					</div>
					<div class="spsbf-header-logo-shape">
						<img src="<?php echo esc_url( SMART_BRANDS_URL . '/core/Admin/HelpPage/img/logo-shape.svg' ); ?>" alt="">
					</div>
				</div>
				<div class="spsbf-header-nav">
					<div class="spsbf-container">
						<div class="spsbf-header-nav-menu">
							<ul>
								<li><a class="active" data-id="get-start-tab"  href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=smart_brand_sc&page=brand_help#get-start' ); ?>"><i class="spsbf-icon-play"></i> Get Started</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=smart_brand_sc&page=brand_help#recommended' ); ?>" data-id="recommended-tab"><i class="spsbf-icon-recommended"></i> Recommended</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=smart_brand_sc&page=brand_help#lite-to-pro' ); ?>" data-id="lite-to-pro-tab"><i class="spsbf-icon-lite-to-pro-icon"></i> Lite Vs Pro</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=smart_brand_sc&page=brand_help#about-us' ); ?>" data-id="about-us-tab"><i class="spsbf-icon-info-circled-alt"></i> About Us</a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>
			<!-- Header section end -->

			<!-- Start Page -->
			<section class="spsbf__help start-page" id="get-start-tab">
				<div class="spsbf-container">
					<div class="spsbf-start-page-wrap">
						<div class="spsbf-video-area">
							<h2 class='spsbf-section-title'>Welcome to Smart Brands!</h2>
							<span class='spsbf-normal-paragraph'>Thank you for installing Smart Brands! This video will help you get started with the plugin. Enjoy!</span>
							<iframe width="724" height="405" src="https://www.youtube.com/embed/vFN6AZ8-ngI?si=9lc8jvWD6w4X3gLG" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
							<ul>
								<li><a class='spsbf-medium-btn' href="<?php echo esc_url( home_url( '/' ) . 'wp-admin/post-new.php?post_type=smart_brand_sc' ); ?>">Manage Brands Views</a></li>
								<li><a target="_blank" class='spsbf-medium-btn' href="https://demo.shapedplugin.com/smart-brands-for-woocommerce/">Live Demo</a></li>
								<li><a target="_blank" class='spsbf-medium-btn arrow-btn' href="https://shapedplugin.com/plugin/smart-brands-for-woocommerce/?ref=1">Explore Smart Brands <i class="spsbf-icon-button-arrow-icon"></i></a></li>
							</ul>
						</div>
						<div class="spsbf-start-page-sidebar">
							<div class="spsbf-start-page-sidebar-info-box">
								<div class="spsbf-info-box-title">
									<h4><i class="spsbf-icon-doc-icon"></i> Documentation</h4>
								</div>
								<span class='spsbf-normal-paragraph'>Explore Smart Brands plugin capabilities in our enriched documentation.</span>
								<a target="_blank" class='spsbf-small-btn' href="https://docs.shapedplugin.com/docs/smart-brands-for-woocommerce/overview/">Browse Now</a>
							</div>
							<div class="spsbf-start-page-sidebar-info-box">
								<div class="spsbf-info-box-title">
									<h4><i class="spsbf-icon-support"></i> Technical Support</h4>
								</div>
								<span class='spsbf-normal-paragraph'>For personalized assistance, reach out to our skilled support team for prompt help.</span>
								<a target="_blank" class='spsbf-small-btn' href="https://shapedplugin.com/create-new-ticket/">Ask Now</a>
							</div>
							<div class="spsbf-start-page-sidebar-info-box">
								<div class="spsbf-info-box-title">
									<h4><i class="spsbf-icon-team-icon"></i> Join The Community</h4>
								</div>
								<span class='spsbf-normal-paragraph'>Join the official ShapedPlugin Facebook group to share your experiences, thoughts, and ideas.</span>
								<a target="_blank" class='spsbf-small-btn' href="https://www.facebook.com/groups/ShapedPlugin/">Join Now</a>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Lite To Pro Page -->
			<section class="spsbf__help lite-to-pro-page" id="lite-to-pro-tab">
				<div class="spsbf-container">
					<div class="spsbf-call-to-action-top">
						<h2 class="spsbf-section-title">Lite vs Pro Comparison</h2>
						<a target="_blank" href="https://shapedplugin.com/plugin/smart-brands-for-woocommerce/?ref=1" class='spsbf-big-btn'>Upgrade to Pro Now!</a>
					</div>
					<div class="spsbf-lite-to-pro-wrap">
						<div class="spsbf-features">
							<ul>
								<li class='spsbf-header'>
									<span class='spsbf-title'>FEATURES</span>
									<span class='spsbf-free'>Lite</span>
									<span class='spsbf-pro'><i class='spsbf-icon-pro'></i> PRO</span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>All Free Version Features</span>
									<span class='spsbf-free spsbf-check-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Create Unlimited Brands</span>
									<span class='spsbf-free spsbf-check-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Set Brand Name and Description</span>
									<span class='spsbf-free spsbf-check-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Upload Brand Logo and banner Image for the Brand Archive Page</span>
									<span class='spsbf-free spsbf-check-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Set Custom URL for Each Brand</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Show Brands on the Product Detail Page</span>
									<span class='spsbf-free spsbf-check-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Manage Brand Hierarchically</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Layouts to Display Brands (Carousel, Grid, List, etc.)</span>
									<span class='spsbf-free'><b>1</b></span>
									<span class='spsbf-pro'><b>3</b></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Ticker Carousel Mode</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Enable Product Count</span>
									<span class='spsbf-free spsbf-check-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Filter Brands by Specific and Exclude</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Hide Empty Brands and Brands Without a Logo</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Brands Content Position (Top, Bottom, Left, Right)</span>
									<span class='spsbf-free'><b>1</b></span>
									<span class='spsbf-pro'><b>4</b></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Set Brand Logo Background Color</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Show/Hide Brand Name, Description, and Logo</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Multiple Ajax Pagination (Lode More Button and Infinite Scroll)</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Change Brand Taxonomy Slug & Label</span>
									<span class='spsbf-free spsbf-check-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>6+ Positions to Display Brands on Product Page (After Product Meta, After Add to Cart, After Price, etc)</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Display Brands in Product Loop/Shop Page (After Price, After Add to Cart Button)</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Select Brand Content in the Product and Loop/Shop Page (Logo and Name, Only Name, Only Logo)</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Include Brand Tab in Product Page (Name, Description, and Logo Tab)</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Sort Products by Brands in the Loop or Shop Page</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Set Brand Logo Custom Dimension in the Shop page and Product Single Page Separately</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Brand Description Position for the Archive Page (After Product Loop, Before Product Loop, and None)</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Brand Banner Position for the Archive Page (After Product Loop, Before Product Loop, and None)</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Select a Brand Page Breadcrumbs (Sample Page, Cart, My Account, Shop, and Checkout)</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Banner Position on the Brand Archive page</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Brands Items to Show Per Page and Change Load More Button Label</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Brands Carousel AutoPlay, AutoPlay Speed, Sliding Speed, Pause on Hover, Infinite Loop, and Slide to Scroll</span>
									<span class='spsbf-free spsbf-check-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>RTL and LTR Supported</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Display Multi-row Brand Logos Carousel</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Show/Hide Brand Carousel Navigation and Pagination</span>
									<span class='spsbf-free spsbf-check-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Stylize your Brand Carousel/Grid Typography with 1500+ Google Fonts</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>All Premium Features, Security Enhancements, and Compatibility</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
								<li class='spsbf-body'>
									<span class='spsbf-title'>Priority Top-notch Support</span>
									<span class='spsbf-free spsbf-close-icon'></span>
									<span class='spsbf-pro spsbf-check-icon'></span>
								</li>
							</ul>
						</div>
						<div class="spsbf-upgrade-to-pro">
							<h2 class='spsbf-section-title'>Upgrade To PRO & Enjoy Advanced Features!</h2>
							<span class='spsbf-section-subtitle'>Already, <b>300+</b> people are using Smart Brands on their websites to create beautiful showcase, why won’t you!</span>
							<div class="spsbf-upgrade-to-pro-btn">
								<div class="spsbf-action-btn">
									<a target="_blank" href="https://shapedplugin.com/plugin/smart-brands-for-woocommerce/?ref=1" class='spsbf-big-btn'>Upgrade to Pro Now!</a>
									<span class='spsbf-small-paragraph'>14-Day No-Questions-Asked <a target="_blank" href="https://shapedplugin.com/refund-policy/">Refund Policy</a></span>
								</div>
								<a target="_blank" href="https://shapedplugin.com/plugin/smart-brands-for-woocommerce/?ref=1" class='spsbf-big-btn-border'>See All Features</a>
								<a target="_blank" class="spsbf-big-btn-border spsbf-pro-live-btn" href="https://demo.shapedplugin.com/smart-brands-pro/">Pro Live Demo</a>
							</div>
						</div>
					</div>
					<div class="spsbf-testimonial">
						<div class="spsbf-testimonial-title-section">
							<span class='spsbf-testimonial-subtitle'>NO NEED TO TAKE OUR WORD FOR IT</span>
							<h2 class="spsbf-section-title">Our Users Love Smart Brands Pro!</h2>
						</div>
						<div class="spsbf-testimonial-wrap">
							<div class="spsbf-testimonial-area">
								<div class="spsbf-testimonial-content">
									<p>This is a very useful plugin for showing product brands on your shop. I really like the flexibility of options to use widgets or shortcode to display brands in various way.</p>
								</div>
								<div class="spsbf-testimonial-info">
									<div class="spsbf-img">
										<img src="<?php echo esc_url( SMART_BRANDS_URL . '/core/Admin/HelpPage/img/shaheen.png' ); ?>" alt="">
									</div>
									<div class="spsbf-info">
										<h3>Shaheen</h3>
										<div class="spsbf-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Recommended Page -->
			<section id="recommended-tab" class="spsbf-recommended-page">
				<div class="spsbf-container">
					<h2 class="spsbf-section-title">Enhance your Website with our Free Robust Plugins</h2>
					<div class="spsbf-wp-list-table plugin-install-php">
						<div class="spsbf-recommended-plugins" id="the-list">
							<?php
								$this->spsbf_plugins_info_api_help_page();
							?>
						</div>
					</div>
				</div>
			</section>

			<!-- About Page -->
			<section id="about-us-tab" class="spsbf__help about-page">
				<div class="spsbf-container">
					<div class="spsbf-about-box">
						<div class="spsbf-about-info">
							<h3>The Ultimate WooCommerce Brands Plugin from the Smart Brands Team, ShapedPlugin, LLC</h3>
							<p>At <b>ShapedPlugin LLC</b>, we have searched for the finest methods to enhance brand presentation on WooCommerce stores. Unfortunately, we couldn't find any suitable plugin that met our needs. Therefore, we developed a powerful brand plugin to empower your store's identity and boost sales effortlessly!</p>
							<p>Our plugin provides the simplest way to display brands on your WooCommerce store dynamically, enhancing your brand presence like never before!</p>
							<div class="spsbf-about-btn">
								<a target="_blank" href="https://shapedplugin.com/plugin/smart-brands-for-woocommerce/?ref=1" class='spsbf-medium-btn'>Explore Smart Brands</a>
								<a target="_blank" href="https://shapedplugin.com/about-us/" class='spsbf-medium-btn spsbf-arrow-btn'>More About Us <i class="spsbf-icon-button-arrow-icon"></i></a>
							</div>
						</div>
						<div class="spsbf-about-img">
							<img src="https://shapedplugin.com/wp-content/uploads/2024/01/shapedplugin-team.jpg" alt="">
							<span>Team ShapedPlugin LLC at WordCamp Sylhet</span>
						</div>
					</div>
					<div class="spsbf-our-plugin-list">
						<h3 class="spsbf-section-title">Upgrade your Website with our High-quality Plugins!</h3>
						<div class="spsbf-our-plugin-list-wrap">
							<a target="_blank" class="spsbf-our-plugin-list-box" href="https://wordpresscarousel.com/">
								<i class="spsbf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/wp-carousel-free/assets/icon-256x256.png" alt="">
								<h4>WP Carousel</h4>
								<p>The most powerful and user-friendly multi-purpose carousel, slider, & gallery plugin for WordPress.</p>
							</a>
							<a target="_blank" class="spsbf-our-plugin-list-box" href="https://realtestimonials.io/">
								<i class="spsbf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/testimonial-free/assets/icon-256x256.png" alt="">
								<h4>Real Testimonials</h4>
								<p>Simply collect, manage, and display Testimonials on your website and boost conversions.</p>
							</a>
							<a target="_blank" class="spsbf-our-plugin-list-box" href="https://smartpostshow.com/">
								<i class="spsbf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/post-carousel/assets/icon-256x256.png" alt="">
								<h4>Smart Post Show</h4>
								<p>Filter and display posts (any post types), pages, taxonomy, custom taxonomy, and custom field, in beautiful layouts.</p>
							</a>
							<a target="_blank" href="https://wooproductslider.io/" class="spsbf-our-plugin-list-box">
								<i class="spsbf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-product-slider/assets/icon-256x256.png" alt="">
								<h4>Product Slider for WooCommerce</h4>
								<p>Boost sales by interactive product Slider, Grid, and Table in your WooCommerce website or store.</p>
							</a>
							<a target="_blank" class="spsbf-our-plugin-list-box" href="https://shapedplugin.com/plugin/woocommerce-gallery-slider-pro/">
								<i class="spsbf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/gallery-slider-for-woocommerce/assets/icon-256x256.png" alt="">
								<h4>Gallery Slider for WooCommerce</h4>
								<p>Product gallery slider and additional variation images gallery for WooCommerce and boost your sales.</p>
							</a>
							<a target="_blank" class="spsbf-our-plugin-list-box" href="https://getwpteam.com/">
								<i class="spsbf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/team-free/assets/icon-256x256.png" alt="">
								<h4>WP Team</h4>
								<p>Display your team members smartly who are at the heart of your company or organization!</p>
							</a>
							<a target="_blank" class="spsbf-our-plugin-list-box" href="https://logocarousel.com/">
								<i class="spsbf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/logo-carousel-free/assets/icon-256x256.png" alt="">
								<h4>Logo Carousel</h4>
								<p>Showcase a group of logo images with Title, Description, Tooltips, Links, and Popup as a grid or in a carousel.</p>
							</a>
							<a target="_blank" class="spsbf-our-plugin-list-box" href="https://easyaccordion.io/">
								<i class="spsbf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/easy-accordion-free/assets/icon-256x256.png" alt="">
								<h4>Easy Accordion</h4>
								<p>Minimize customer support by offering comprehensive FAQs and increasing conversions.</p>
							</a>
							<a target="_blank" class="spsbf-our-plugin-list-box" href="https://shapedplugin.com/plugin/woocommerce-category-slider-pro/">
								<i class="spsbf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-category-slider-grid/assets/icon-256x256.png" alt="">
								<h4>Category Slider for WooCommerce</h4>
								<p>Display by filtering the list of categories aesthetically and boosting sales.</p>
							</a>
							<a target="_blank" class="spsbf-our-plugin-list-box" href="https://wptabs.com/">
								<i class="spsbf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/wp-expand-tabs-free/assets/icon-256x256.png" alt="">
								<h4>WP Tabs</h4>
								<p>Display tabbed content smartly & quickly on your WordPress site without coding skills.</p>
							</a>
							<a target="_blank" class="spsbf-our-plugin-list-box" href="https://shapedplugin.com/plugin/woocommerce-quick-view-pro/">
								<i class="spsbf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-quickview/assets/icon-256x256.png" alt="">
								<h4>Quick View for WooCommerce</h4>
								<p>Quickly view product information with smooth animation via AJAX in a nice Modal without opening the product page.</p>
							</a>
							<a target="_blank" class="spsbf-our-plugin-list-box" href="https://shapedplugin.com/plugin/smart-brands-for-woocommerce/">
								<i class="spsbf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/smart-brands-for-woocommerce/assets/icon-256x256.png" alt="">
								<h4>Smart Brands for WooCommerce</h4>
								<p>Smart Brands for WooCommerce Pro helps you display product brands in an attractive way on your online store.</p>
							</a>
						</div>
					</div>
				</div>
			</section>

			<!-- Footer Section -->
			<section class="spsbf-footer">
				<div class="spsbf-footer-top">
					<p><span>Made With <i class="spsbf-icon-heart"></i> </span> By the Team <a target="_blank" href="https://shapedplugin.com/">ShapedPlugin LLC</a></p>
					<p>Get connected with</p>
					<ul>
						<li><a target="_blank" href="https://www.facebook.com/ShapedPlugin/"><i class="spsbf-icon-fb"></i></a></li>
						<li><a target="_blank" href="https://twitter.com/intent/follow?screen_name=ShapedPlugin"><i class="spsbf-icon-x"></i></a></li>
						<li><a target="_blank" href="https://profiles.wordpress.org/shapedplugin/#content-plugins"><i class="spsbf-icon-wp-icon"></i></a></li>
						<li><a target="_blank" href="https://youtube.com/@ShapedPlugin?sub_confirmation=1"><i class="spsbf-icon-youtube-play"></i></a></li>
					</ul>
				</div>
			</section>
		</div>
		<?php
	}
}
