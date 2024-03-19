<?php
/**
 * Framework option.class file.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.0
 *
 * @package    Smart_Brands_For_Wc
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

use ShapedPlugin\SmartBrands\Admin\Framework\Classes\SPF_SMART_BRANDS;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPF_SMART_BRANDS_Options' ) ) {
	/**
	 *
	 * Options Class
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_SMART_BRANDS_Options extends SPF_SMART_BRANDS_Abstract {
		/**
		 * Unique
		 *
		 * @var string
		 */
		public $unique = '';
		/**
		 * Notice
		 *
		 * @var string
		 */
		public $notice = '';
		/**
		 * Abstract
		 *
		 * @var string
		 */
		public $abstract = 'options';
		/**
		 * Sections
		 *
		 * @var array
		 */
		public $sections = array();
		/**
		 * Options
		 *
		 * @var array
		 */
		public $options = array();
		/**
		 * Errors
		 *
		 * @var array
		 */
		public $errors = array();
		/**
		 * Pre_tabs
		 *
		 * @var array
		 */
		public $pre_tabs = array();
		/**
		 * Pre_fields
		 *
		 * @var array
		 */
		public $pre_fields = array();
		/**
		 * Pre_sections
		 *
		 * @var array
		 */
		public $pre_sections = array();
		/**
		 * Default Arguments.
		 *
		 * @var array
		 */
		public $args = array(
			// framework title.
			'framework_title'         => 'Codestar Framework <small>by Codestar</small>',
			'framework_class'         => '',

			// menu settings.
			'menu_title'              => '',
			'menu_slug'               => '',
			'menu_type'               => 'menu',
			'menu_capability'         => 'manage_options',
			'menu_icon'               => null,
			'menu_position'           => null,
			'menu_hidden'             => false,
			'menu_parent'             => '',
			'sub_menu_title'          => '',

			// menu extras.
			'show_bar_menu'           => true,
			'show_sub_menu'           => true,
			'show_in_network'         => true,
			'show_in_customizer'      => false,

			'show_search'             => true,
			'show_reset_all'          => true,
			'show_reset_section'      => true,
			'show_footer'             => true,
			'show_all_options'        => true,
			'show_form_warning'       => true,
			'sticky_header'           => true,
			'save_defaults'           => true,
			'ajax_save'               => true,
			'form_action'             => '',

			// admin bar menu settings.
			'admin_bar_menu_icon'     => '',
			'admin_bar_menu_priority' => 50,

			// footer.
			'footer_text'             => '',
			'footer_after'            => '',
			'footer_credit'           => '',

			// database model.
			'database'                => '', // options, transient, theme_mod, network.
			'transient_time'          => 0,

			// contextual help.
			'contextual_help'         => array(),
			'contextual_help_sidebar' => '',

			// typography options.
			'enqueue_webfont'         => true,
			'async_webfont'           => false,

			// others.
			'output_css'              => true,

			// theme.
			'nav'                     => 'normal',
			'theme'                   => 'dark',
			'class'                   => '',

			// external default values.
			'defaults'                => array(),
		);

		/**
		 * Run framework construct.
		 *
		 * @param  mixed $key key.
		 * @param  mixed $params parameters.
		 * @return void
		 */
		public function __construct( $key, $params = array() ) {

			$this->unique   = $key;
			$this->args     = apply_filters( "csf_{$this->unique}_args", wp_parse_args( $params['args'], $this->args ), $this );
			$this->sections = apply_filters( "csf_{$this->unique}_sections", $params['sections'], $this );

			// run only is admin panel options, avoid performance loss.
			$this->pre_tabs     = $this->pre_tabs( $this->sections );
			$this->pre_fields   = $this->pre_fields( $this->sections );
			$this->pre_sections = $this->pre_sections( $this->sections );

			$this->get_options();
			$this->set_options();
			$this->save_defaults();

			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
			add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_menu' ), $this->args['admin_bar_menu_priority'] );
			add_action( 'wp_ajax_csf_' . $this->unique . '_ajax_save', array( $this, 'ajax_save' ) );

			if ( 'network' === $this->args['database'] && ! empty( $this->args['show_in_network'] ) ) {
				add_action( 'network_admin_menu', array( $this, 'add_admin_menu' ) );
			}

			// wp enqeueu for typography and output css.
			parent::__construct();

		}

		/**
		 * Instance
		 *
		 * @param  mixed $key key.
		 * @param  mixed $params params.
		 * @return statement
		 */
		public static function instance( $key, $params = array() ) {
			return new self( $key, $params );
		}

		/**
		 * Pre_tabs
		 *
		 * @param  array $sections sections.
		 * @return array
		 */
		public function pre_tabs( $sections ) {

			$result  = array();
			$parents = array();
			$count   = 100;

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $section['parent'] ) ) {
					$section['priority']             = ( isset( $section['priority'] ) ) ? $section['priority'] : $count;
					$parents[ $section['parent'] ][] = $section;
					unset( $sections[ $key ] );
				}
				$count++;
			}

			foreach ( $sections as $key => $section ) {
				$section['priority'] = ( isset( $section['priority'] ) ) ? $section['priority'] : $count;
				if ( ! empty( $section['id'] ) && ! empty( $parents[ $section['id'] ] ) ) {
					$section['subs'] = wp_list_sort( $parents[ $section['id'] ], array( 'priority' => 'ASC' ), 'ASC', true );
				}
				$result[] = $section;
				$count++;
			}

			return wp_list_sort( $result, array( 'priority' => 'ASC' ), 'ASC', true );
		}

		/**
		 * Pre_fields
		 *
		 * @param  mixed $sections sections.
		 * @return array
		 */
		public function pre_fields( $sections ) {

			$result = array();

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $section['fields'] ) ) {
					foreach ( $section['fields'] as $field ) {
						$result[] = $field;
					}
				}
			}

			return $result;
		}

		/**
		 * Pre_sections
		 *
		 * @param  mixed $sections section.
		 * @return array
		 */
		public function pre_sections( $sections ) {
			$result = array();

			foreach ( $this->pre_tabs as $tab ) {
				if ( ! empty( $tab['subs'] ) ) {
					foreach ( $tab['subs'] as $sub ) {
						$sub['ptitle'] = $tab['title'];
						$result[]      = $sub;
					}
				}
				if ( empty( $tab['subs'] ) ) {
					$result[] = $tab;
				}
			}

			return $result;
		}

		/**
		 * Add admin bar menu.
		 *
		 * @param object $wp_admin_bar admin bar.
		 * @return void
		 */
		public function add_admin_bar_menu( $wp_admin_bar ) {

			if ( ! current_user_can( $this->args['menu_capability'] ) ) {
				return;
			}

			if ( is_network_admin() && ( 'network' !== $this->args['database'] || true !== $this->args['show_in_network'] ) ) {
				return;
			}

			if ( ! empty( $this->args['show_bar_menu'] ) && empty( $this->args['menu_hidden'] ) ) {

				global $submenu;

				$menu_slug = $this->args['menu_slug'];
				$menu_icon = ( ! empty( $this->args['admin_bar_menu_icon'] ) ) ? '<span class="csf-ab-icon ab-icon ' . esc_attr( $this->args['admin_bar_menu_icon'] ) . '"></span>' : '';

				$wp_admin_bar->add_node(
					array(
						'id'    => $menu_slug,
						'title' => $menu_icon . esc_attr( $this->args['menu_title'] ),
						'href'  => esc_url( ( is_network_admin() ) ? network_admin_url( 'admin.php?page=' . $menu_slug ) : admin_url( 'admin.php?page=' . $menu_slug ) ),
					)
				);

				if ( ! empty( $submenu[ $menu_slug ] ) ) {
					foreach ( $submenu[ $menu_slug ] as $menu_key => $menu_value ) {
						$wp_admin_bar->add_node(
							array(
								'parent' => $menu_slug,
								'id'     => $menu_slug . '-' . $menu_key,
								'title'  => $menu_value[0],
								'href'   => esc_url( ( is_network_admin() ) ? network_admin_url( 'admin.php?page=' . $menu_value[2] ) : admin_url( 'admin.php?page=' . $menu_value[2] ) ),
							)
						);
					}
				}
			}

		}

		/**
		 * Ajax_save
		 *
		 * @return void
		 */
		public function ajax_save() {

			$result = $this->set_options( true );

			if ( ! $result ) {
				wp_send_json_error( array( 'error' => esc_html__( 'Error while saving the changes.', 'smart-brands-for-woocommerce' ) ) );
			} else {
				wp_send_json_success(
					array(
						'notice' => $this->notice,
						'errors' => $this->errors,
					)
				);
			}

		}

		/**
		 * Get default value
		 *
		 * @param  mixed $field field.
		 * @return mixed
		 */
		public function get_default( $field ) {

			$default = ( isset( $field['default'] ) ) ? $field['default'] : '';
			$default = ( isset( $this->args['defaults'][ $field['id'] ] ) ) ? $this->args['defaults'][ $field['id'] ] : $default;

			return $default;

		}

		/**
		 * Save defaults and set new fields value to main options.
		 *
		 * @return void
		 */
		public function save_defaults() {

			$tmp_options = $this->options;

			foreach ( $this->pre_fields as $field ) {
				if ( ! empty( $field['id'] ) ) {
					$this->options[ $field['id'] ] = ( isset( $this->options[ $field['id'] ] ) ) ? $this->options[ $field['id'] ] : $this->get_default( $field );
				}
			}

			if ( $this->args['save_defaults'] && empty( $tmp_options ) ) {
				$this->save_options( $this->options );
			}

		}

		/**
		 * Set options.
		 *
		 * @param bool $ajax check.
		 * @return bool
		 */
		public function set_options( $ajax = false ) {

			// XSS ok.
			// No worries, This "POST" requests is sanitizing in the below foreach. see #L337 - #L341.
			$response = ( $ajax && ! empty( $_POST['data'] ) ) ? json_decode( wp_unslash( trim( $_POST['data'] ) ), true ) : $_POST;

			// Set variables.
			$data      = array();
			$noncekey  = 'csf_options_nonce' . $this->unique;
			$nonce     = ( ! empty( $response[ $noncekey ] ) ) ? $response[ $noncekey ] : '';
			$options   = ( ! empty( $response[ $this->unique ] ) ) ? $response[ $this->unique ] : array();
			$transient = ( ! empty( $response['csf_transient'] ) ) ? $response['csf_transient'] : array();

			if ( wp_verify_nonce( $nonce, 'csf_options_nonce' ) ) {

				$importing  = false;
				$section_id = ( ! empty( $transient['section'] ) ) ? $transient['section'] : '';

				if ( ! $ajax && ! empty( $response['csf_import_data'] ) ) {

					// XSS ok.
					// No worries, This "POST" requests is sanitizing in the below foreach. see #L337 - #L341.
					$import_data  = json_decode( wp_unslash( trim( $response['csf_import_data'] ) ), true );
					$options      = ( is_array( $import_data ) && ! empty( $import_data ) ) ? $import_data : array();
					$importing    = true;
					$this->notice = esc_html__( 'Settings successfully imported.', 'smart-brands-for-woocommerce' );

				}

				if ( ! empty( $transient['reset'] ) ) {

					foreach ( $this->pre_fields as $field ) {
						if ( ! empty( $field['id'] ) ) {
							$data[ $field['id'] ] = $this->get_default( $field );
						}
					}

					$this->notice = esc_html__( 'Default settings restored.', 'smart-brands-for-woocommerce' );

				} elseif ( ! empty( $transient['reset_section'] ) && ! empty( $section_id ) ) {

					if ( ! empty( $this->pre_sections[ $section_id - 1 ]['fields'] ) ) {

						foreach ( $this->pre_sections[ $section_id - 1 ]['fields'] as $field ) {
							if ( ! empty( $field['id'] ) ) {
								$data[ $field['id'] ] = $this->get_default( $field );
							}
						}
					}

					$data = wp_parse_args( $data, $this->options );

					$this->notice = esc_html__( 'Default settings restored.', 'smart-brands-for-woocommerce' );

				} else {

					// sanitize and validate.
					foreach ( $this->pre_fields as $field ) {

						if ( ! empty( $field['id'] ) ) {

							$field_id    = $field['id'];
							$field_value = isset( $options[ $field_id ] ) ? $options[ $field_id ] : '';

							// Ajax and Importing doing wp_unslash already.
							if ( ! $ajax && ! $importing ) {
								$field_value = wp_unslash( $field_value );
							}

							// Sanitize "post" request of field.
							if ( ! isset( $field['sanitize'] ) ) {

								if ( is_array( $field_value ) ) {

									$data[ $field_id ] = wp_kses_post_deep( $field_value );

								} else {

									$data[ $field_id ] = wp_kses_post( $field_value );

								}
							} elseif ( isset( $field['sanitize'] ) && is_callable( $field['sanitize'] ) ) {
								$data[ $field_id ] = call_user_func( $field['sanitize'], $field_value );
							} else {
								$data[ $field_id ] = $field_value;
							}

							// Validate "post" request of field.
							if ( isset( $field['validate'] ) && is_callable( $field['validate'] ) ) {

								$has_validated = call_user_func( $field['validate'], $field_value );

								if ( ! empty( $has_validated ) ) {

									$data[ $field_id ]         = ( isset( $this->options[ $field_id ] ) ) ? $this->options[ $field_id ] : '';
									$this->errors[ $field_id ] = $has_validated;

								}
							}
						}
					}
				}

				$data = apply_filters( "csf_{$this->unique}_save", $data, $this );

				do_action( "csf_{$this->unique}_save_before", $data, $this );

				$this->options = $data;

				$this->save_options( $data );

				do_action( "csf_{$this->unique}_save_after", $data, $this );

				if ( empty( $this->notice ) ) {
					$this->notice = esc_html__( 'Settings saved.', 'smart-brands-for-woocommerce' );
				}

				return true;

			}

			return false;

		}

		/**
		 * Save options database.
		 *
		 * @param mixed $data data.
		 * @return void
		 */
		public function save_options( $data ) {

			if ( 'transient' === $this->args['database'] ) {
				set_transient( $this->unique, $data, $this->args['transient_time'] );
			} elseif ( 'theme_mod' === $this->args['database'] ) {
				set_theme_mod( $this->unique, $data );
			} elseif ( 'network' === $this->args['database'] ) {
				update_site_option( $this->unique, $data );
			} else {
				update_option( $this->unique, $data );
			}

			do_action( "csf_{$this->unique}_saved", $data, $this );

		}

		/**
		 * Get options from database.
		 *
		 * @return mixed
		 */
		public function get_options() {

			if ( 'transient' === $this->args['database'] ) {
				$this->options = get_transient( $this->unique );
			} elseif ( 'theme_mod' === $this->args['database'] ) {
				$this->options = get_theme_mod( $this->unique );
			} elseif ( 'network' === $this->args['database'] ) {
				$this->options = get_site_option( $this->unique );
			} else {
				$this->options = get_option( $this->unique );
			}

			if ( empty( $this->options ) ) {
				$this->options = array();
			}

			return $this->options;

		}

		/**
		 * Wp api: admin menu.
		 *
		 * @return void
		 */
		public function add_admin_menu() {

			extract( $this->args );

			if ( 'submenu' === $menu_type ) {

				$menu_page = call_user_func( 'add_submenu_page', $menu_parent, esc_attr( $menu_title ), esc_attr( $menu_title ), $menu_capability, $menu_slug, array( $this, 'add_options_html' ) );

			} else {

				$menu_page = call_user_func( 'add_menu_page', esc_attr( $menu_title ), esc_attr( $menu_title ), $menu_capability, $menu_slug, array( $this, 'add_options_html' ), $menu_icon, $menu_position );

				if ( ! empty( $sub_menu_title ) ) {
					call_user_func( 'add_submenu_page', $menu_slug, esc_attr( $sub_menu_title ), esc_attr( $sub_menu_title ), $menu_capability, $menu_slug, array( $this, 'add_options_html' ) );
				}

				if ( ! empty( $this->args['show_sub_menu'] ) && count( $this->pre_tabs ) > 1 ) {

					// create submenus.
					foreach ( $this->pre_tabs as $section ) {
						call_user_func( 'add_submenu_page', $menu_slug, esc_attr( $section['title'] ), esc_attr( $section['title'] ), $menu_capability, $menu_slug . '#tab=' . sanitize_title( $section['title'] ), '__return_null' );
					}

					remove_submenu_page( $menu_slug, $menu_slug );

				}

				if ( ! empty( $menu_hidden ) ) {
					remove_menu_page( $menu_slug );
				}
			}

			add_action( 'load-' . $menu_page, array( $this, 'add_page_on_load' ) );

		}

		/**
		 * Add_page_on_load
		 *
		 * @return void
		 */
		public function add_page_on_load() {

			if ( ! empty( $this->args['contextual_help'] ) ) {

				$screen = get_current_screen();

				foreach ( $this->args['contextual_help'] as $tab ) {
					$screen->add_help_tab( $tab );
				}

				if ( ! empty( $this->args['contextual_help_sidebar'] ) ) {
					$screen->set_help_sidebar( $this->args['contextual_help_sidebar'] );
				}
			}

			add_filter( 'admin_footer_text', array( $this, 'add_admin_footer_text' ) );

		}

		/**
		 * Add admin footer text.
		 *
		 * @return void
		 */
		public function add_admin_footer_text() {
			$default = 'Thank you for creating with <a href="http://codestarframework.com/" target="_blank">Codestar Framework</a>';
			echo ! empty( $this->args['footer_credit'] ) ? wp_kses_post( $this->args['footer_credit'] ) : wp_kses_post( $default );
		}

		/**
		 * Error check
		 *
		 * @param  mixed $sections Sections.
		 * @param  mixed $err error.
		 * @return statement
		 */
		public function error_check( $sections, $err = '' ) {

			if ( ! $this->args['ajax_save'] ) {

				if ( ! empty( $sections['fields'] ) ) {
					foreach ( $sections['fields'] as $field ) {
						if ( ! empty( $field['id'] ) ) {
							if ( array_key_exists( $field['id'], $this->errors ) ) {
								$err = '<span class="csf-label-error">!</span>';
							}
						}
					}
				}

				if ( ! empty( $sections['subs'] ) ) {
					foreach ( $sections['subs'] as $sub ) {
						$err = $this->error_check( $sub, $err );
					}
				}

				if ( ! empty( $sections['id'] ) && array_key_exists( $sections['id'], $this->errors ) ) {
					$err = $this->errors[ $sections['id'] ];
				}
			}

			return $err;
		}

		/**
		 * Option page html output.
		 *
		 * @return void
		 */
		public function add_options_html() {

			$has_nav       = ( count( $this->pre_tabs ) > 1 ) ? true : false;
			$show_all      = ( ! $has_nav ) ? ' csf-show-all' : '';
			$ajax_class    = ( $this->args['ajax_save'] ) ? ' csf-save-ajax' : '';
			$sticky_class  = ( $this->args['sticky_header'] ) ? ' csf-sticky-header' : '';
			$wrapper_class = ( $this->args['framework_class'] ) ? ' ' . $this->args['framework_class'] : '';
			$theme         = ( $this->args['theme'] ) ? ' csf-theme-' . $this->args['theme'] : '';
			$class         = ( $this->args['class'] ) ? ' ' . $this->args['class'] : '';
			$nav_type      = ( 'inline' === $this->args['nav'] ) ? 'inline' : 'normal';
			$form_action   = ( $this->args['form_action'] ) ? $this->args['form_action'] : '';

			do_action( 'csf_options_before' );

			echo '<div class="csf csf-options' . esc_attr( $theme . $class . $wrapper_class ) . '" data-slug="' . esc_attr( $this->args['menu_slug'] ) . '" data-unique="' . esc_attr( $this->unique ) . '">';

			echo '<div class="csf-container">';

			echo '<form method="post" action="' . esc_attr( $form_action ) . '" enctype="multipart/form-data" id="csf-form" autocomplete="off" novalidate="novalidate">';

			echo '<input type="hidden" class="csf-section-id" name="csf_transient[section]" value="1">';

			wp_nonce_field( 'csf_options_nonce', 'csf_options_nonce' . $this->unique );

			echo '<div class="csf-header' . esc_attr( $sticky_class ) . '">';
			echo '<div class="csf-header-inner">';

			echo '<div class="csf-header-left">';
			echo '<h1><img src="' . esc_url( SMART_BRANDS_URL ) . '/core/Admin/assets/img/sbn.svg" alt="Settings Image">' . wp_kses_post( $this->args['framework_title'] ) . '</h1>';
			echo '</div>';

			echo '<div class="csf-header-right">';

			$notice_class = ( ! empty( $this->notice ) ) ? 'csf-form-show' : '';
			$notice_text  = ( ! empty( $this->notice ) ) ? $this->notice : '';

			echo ( $has_nav && $this->args['show_all_options'] ) ? '<div class="csf-expand-all" title="' . esc_html__( 'show all settings', 'smart-brands-for-woocommerce' ) . '"><i class="fas fa-outdent"></i></div>' : '';

			echo ( $this->args['show_search'] ) ? '<div class="csf-search"><input type="text" name="csf-search" placeholder="' . esc_html__( 'Search...', 'smart-brands-for-woocommerce' ) . '" autocomplete="off" /></div>' : '';

			echo '<div class="csf-buttons">';
			echo '<input disabled type="submit" name="' . esc_attr( $this->unique ) . '[_nonce][save]" class="button button-primary csf-top-save csf-save' . esc_attr( $ajax_class ) . '" value="' . esc_html__( 'Save Settings', 'smart-brands-for-woocommerce' ) . '" data-save="' . esc_html__( 'Saving...', 'smart-brands-for-woocommerce' ) . '">';
			echo ( $this->args['show_reset_section'] ) ? '<input type="submit" name="csf_transient[reset_section]" class="button button-secondary csf-reset-section csf-confirm" value="' . esc_html__( 'Reset Tab', 'smart-brands-for-woocommerce' ) . '" data-confirm="' . esc_html__( 'Are you sure to reset all the settings of this tab?', 'smart-brands-for-woocommerce' ) . '">' : '';
			echo ( $this->args['show_reset_all'] ) ? '<input type="submit" name="csf_transient[reset]" class="button csf-warning-primary csf-reset-all csf-confirm" value="' . ( ( $this->args['show_reset_section'] ) ? esc_html__( 'Reset All', 'smart-brands-for-woocommerce' ) : esc_html__( 'Reset', 'smart-brands-for-woocommerce' ) ) . '" data-confirm="' . esc_html__( 'Are you sure you want to reset all settings to default values?', 'smart-brands-for-woocommerce' ) . '">' : '';
			echo '</div>';

			echo '</div>';

			echo '<div class="clear"></div>';
			echo '</div>';
			echo '</div>';

			echo '<div class="csf-wrapper' . esc_attr( $show_all ) . '">';

			if ( $has_nav ) {

				echo '<div class="csf-nav csf-nav-' . esc_attr( $nav_type ) . ' csf-nav-options">';

				echo '<ul>';

				foreach ( $this->pre_tabs as $tab ) {

					$tab_id    = sanitize_title( $tab['title'] );
					$tab_error = $this->error_check( $tab );
					$tab_icon  = ( ! empty( $tab['icon'] ) ) ? '<i class="csf-tab-icon ' . esc_attr( $tab['icon'] ) . '"></i>' : '';

					if ( ! empty( $tab['subs'] ) ) {

						echo '<li class="csf-tab-item">';

						echo '<a href="#tab=' . esc_attr( $tab_id ) . '" data-tab-id="' . esc_attr( $tab_id ) . '" class="csf-arrow">' . wp_kses_post( $tab_icon . $tab['title'] . $tab_error ) . '</a>';

						echo '<ul>';

						foreach ( $tab['subs'] as $sub ) {

							$sub_id    = $tab_id . '/' . sanitize_title( $sub['title'] );
							$sub_error = $this->error_check( $sub );
							$sub_icon  = ( ! empty( $sub['icon'] ) ) ? '<i class="csf-tab-icon ' . esc_attr( $sub['icon'] ) . '"></i>' : '';

							echo '<li><a href="#tab=' . esc_attr( $sub_id ) . '" data-tab-id="' . esc_attr( $sub_id ) . '">' . wp_kses_post( $sub_icon . $sub['title'] . $sub_error ) . '</a></li>';

						}

						echo '</ul>';

						echo '</li>';

					} else {

						echo '<li class="csf-tab-item"><a href="#tab=' . esc_attr( $tab_id ) . '" data-tab-id="' . esc_attr( $tab_id ) . '">' . wp_kses_post( $tab_icon . $tab['title'] . $tab_error ) . '</a></li>';

					}
				}

				echo '</ul>';

				echo '</div>';

			}

			echo '<div class="csf-content">';

			echo '<div class="csf-sections">';

			foreach ( $this->pre_sections as $section ) {

				$section_onload = ( ! $has_nav ) ? ' csf-onload' : '';
				$section_class  = ( ! empty( $section['class'] ) ) ? ' ' . $section['class'] : '';
				$section_icon   = ( ! empty( $section['icon'] ) ) ? '<i class="csf-section-icon ' . esc_attr( $section['icon'] ) . '"></i>' : '';
				$section_title  = ( ! empty( $section['title'] ) ) ? $section['title'] : '';
				$section_parent = ( ! empty( $section['ptitle'] ) ) ? sanitize_title( $section['ptitle'] ) . '/' : '';
				$section_slug   = ( ! empty( $section['title'] ) ) ? sanitize_title( $section_title ) : '';

				echo '<div class="csf-section hidden' . esc_attr( $section_onload . $section_class ) . '" data-section-id="' . esc_attr( $section_parent . $section_slug ) . '">';
				echo ( $has_nav ) ? '<div class="csf-section-title"><h3>' . wp_kses_post( $section_icon . $section_title ) . '</h3></div>' : '';
				echo ( ! empty( $section['description'] ) ) ? '<div class="csf-field csf-section-description">' . wp_kses_post( $section['description'] ) . '</div>' : '';

				if ( ! empty( $section['fields'] ) ) {

					foreach ( $section['fields'] as $field ) {

						$is_field_error = $this->error_check( $field );

						if ( ! empty( $is_field_error ) ) {
							$field['_error'] = $is_field_error;
						}

						if ( ! empty( $field['id'] ) ) {
							$field['default'] = $this->get_default( $field );
						}

						$value = ( ! empty( $field['id'] ) && isset( $this->options[ $field['id'] ] ) ) ? $this->options[ $field['id'] ] : '';

						SPF_SMART_BRANDS::field( $field, $value, $this->unique, 'options' );

					}
				} else {
					echo '<div class="csf-no-option">' . esc_html__( 'No data available.', 'smart-brands-for-woocommerce' ) . '</div>';
				}

				echo '</div>';

			}

			echo '</div>';

			echo '<div class="clear"></div>';

			echo '</div>';

			echo ( $has_nav && 'normal' === $nav_type ) ? '<div class="csf-nav-background"></div>' : '';

			echo '</div>';

			if ( ! empty( $this->args['show_footer'] ) ) {

				echo '<div class="csf-footer">';

				echo '<div class="csf-buttons">';
				echo '<input type="submit" name="csf_transient[save]" class="button button-primary csf-save' . esc_attr( $ajax_class ) . '" value="' . esc_html__( 'Save', 'smart-brands-for-woocommerce' ) . '" data-save="' . esc_html__( 'Saving...', 'smart-brands-for-woocommerce' ) . '">';
				echo ( $this->args['show_reset_section'] ) ? '<input type="submit" name="csf_transient[reset_section]" class="button button-secondary csf-reset-section csf-confirm" value="' . esc_html__( 'Reset Section', 'smart-brands-for-woocommerce' ) . '" data-confirm="' . esc_html__( 'Are you sure to reset this section options?', 'smart-brands-for-woocommerce' ) . '">' : '';
				echo ( $this->args['show_reset_all'] ) ? '<input type="submit" name="csf_transient[reset]" class="button csf-warning-primary csf-reset-all csf-confirm" value="' . ( ( $this->args['show_reset_section'] ) ? esc_html__( 'Reset All', 'smart-brands-for-woocommerce' ) : esc_html__( 'Reset', 'smart-brands-for-woocommerce' ) ) . '" data-confirm="' . esc_html__( 'Are you sure you want to reset all settings to default values?', 'smart-brands-for-woocommerce' ) . '">' : '';
				echo '</div>';

				echo ( ! empty( $this->args['footer_text'] ) ) ? '<div class="csf-copyright">' . wp_kses_post( $this->args['footer_text'] ) . '</div>' : '';

				echo '<div class="clear"></div>';
				echo '</div>';

			}

			echo '</form>';

			echo '</div>';

			echo '<div class="clear"></div>';

			echo ( ! empty( $this->args['footer_after'] ) ) ? wp_kses_post( $this->args['footer_after'] ) : '';

			echo '</div>';

			do_action( 'csf_options_after' );

		}
	}
}
