<?php
/**
 * Framework abstract.class file.
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

if ( ! class_exists( 'SPF_SMART_BRANDS_Abstract' ) ) {
	/**
	 *
	 * Abstract Class
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	abstract class SPF_SMART_BRANDS_Abstract {
		/**
		 * Abstract
		 *
		 * @var string
		 */
		public $abstract = '';
		/**
		 * Output css
		 *
		 * @var string
		 */
		public $output_css = '';

		/**
		 * Constructor of the class.
		 */
		public function __construct() {

			// Collect output css and typography.
			if ( ! empty( $this->args['output_css'] ) || ! empty( $this->args['enqueue_webfont'] ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'collect_output_css_and_typography' ), 10 );
				SPF_SMART_BRANDS::$css = apply_filters( "csf_{$this->unique}_output_css", SPF_SMART_BRANDS::$css, $this );
			}
		}

		/**
		 * Collect output css and typography
		 *
		 * @return void
		 */
		public function collect_output_css_and_typography() {
			$this->recursive_output_css( $this->pre_fields );
		}

		/**
		 * Recursive output css
		 *
		 * @param  mixed $fields fields.
		 * @param  mixed $combine_field combine field.
		 * @return void
		 */
		public function recursive_output_css( $fields = array(), $combine_field = array() ) {

			if ( ! empty( $fields ) ) {

				foreach ( $fields as $field ) {

					$field_id     = ( ! empty( $field['id'] ) ) ? $field['id'] : '';
					$field_type   = ( ! empty( $field['type'] ) ) ? $field['type'] : '';
					$field_output = ( ! empty( $field['output'] ) ) ? $field['output'] : '';
					$field_check  = ( 'typography' === $field_type || $field_output ) ? true : false;
					$field_class  = 'SPF_SMART_BRANDS_Field_' . $field_type;

					if ( $field_type && $field_id ) {
						if ( class_exists( $field_class ) ) {

							if ( method_exists( $field_class, 'output' ) || method_exists( $field_class, 'enqueue_google_fonts' ) ) {

								$field_value = '';

								if ( $field_check && ( 'options' === $this->abstract || 'customize' === $this->abstract ) ) {

									if ( ! empty( $combine_field ) ) {

										$field_value = ( isset( $this->options[ $combine_field['id'] ][ $field_id ] ) ) ? $this->options[ $combine_field['id'] ][ $field_id ] : '';

									} else {

										$field_value = ( isset( $this->options[ $field_id ] ) ) ? $this->options[ $field_id ] : '';

									}
								} elseif ( $field_check && ( 'metabox' === $this->abstract && is_singular() || 'taxonomy' === $this->abstract && is_archive() ) ) {

									if ( ! empty( $combine_field ) ) {

										$meta_value  = $this->get_meta_value( $combine_field );
										$field_value = ( isset( $meta_value[ $field_id ] ) ) ? $meta_value[ $field_id ] : '';

									} else {

										$meta_value  = $this->get_meta_value( $field );
										$field_value = ( isset( $meta_value ) ) ? $meta_value : '';

									}
								}

								$instance = new $field_class( $field, $field_value, $this->unique, 'wp/enqueue', $this );

								// typography enqueue and embed google web fonts.
								if ( $field_type === 'typography' && $this->args['enqueue_webfont'] && ! empty( $field_value['font-family'] ) ) {

									$method = ( ! empty( $this->args['async_webfont'] ) ) ? 'async' : 'enqueue';

									$instance->enqueue_google_fonts( $method );

								}

								// output css.
								if ( $field_output && $this->args['output_css'] ) {
									SPF_SMART_BRANDS::$css .= $instance->output();
								}

								unset( $instance );

							}
						}
					}
				}
			}
		}
	}
}
