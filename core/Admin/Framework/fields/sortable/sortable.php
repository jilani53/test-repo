<?php
/**
 * Framework shortcode fields file.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.8
 *
 * @package    Smart_Brands_For_Wc
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

use ShapedPlugin\SmartBrands\Admin\Framework\Classes\SPF_SMART_BRANDS;

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

/**
 *
 * Field: sortable
 *
 * @since 1.0.8
 * @version 1.0.8
 */
if ( ! class_exists( 'SPF_SMART_BRANDS_Field_sortable' ) ) {
		/**
		 *
		 * Field: sortable
		 *
		 * @since 1.0.8
		 * @version 1.0.8
		 */
	class SPF_SMART_BRANDS_Field_sortable extends SPF_SMART_BRANDS_Fields {
		/**
		 * The class constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * The render method.
		 *
		 * @return void
		 */
		public function render() {
			echo wp_kses_post( $this->field_before() );
			echo '<div class="csf-sortable" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';
			$pre_sortby = array();
			$pre_fields = array();

			// Add array-keys to defined fields for sort by.
			foreach ( $this->field['fields'] as $key => $field ) {
				$pre_fields[ $field['id'] ] = $field;
			}

			// Set sort by by saved-value or default-value.
			if ( ! empty( $this->value ) ) {
				foreach ( $this->value as $key => $value ) {
					$pre_sortby[ $key ] = $pre_fields[ $key ];
				}
				$diff = array_diff_key( $pre_fields, $this->value );
				if ( ! empty( $diff ) ) {
					$pre_sortby = array_merge( $pre_sortby, $diff );
				}
			} else {
				foreach ( $pre_fields as $key => $value ) {
					$pre_sortby[ $key ] = $value;
				}
			}

			foreach ( $pre_sortby as $key => $field ) {
				echo '<div class="csf-sortable-item">';
				echo '<div class="csf-sortable-content">';
				$field_default = ( isset( $this->field['default'][ $key ] ) ) ? $this->field['default'][ $key ] : '';
				$field_value   = ( isset( $this->value[ $key ] ) ) ? $this->value[ $key ] : $field_default;
				$unique_id     = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $this->field['id'] . ']' : $this->field['id'];

				SPF_SMART_BRANDS::field( $field, $field_value, $unique_id, 'field/sortable' );
				echo '</div>';
				echo '<div class="csf-sortable-helper"><i class="fas fa-arrows-alt"></i></div>';
				echo '</div>';
			}
			echo '</div>';
			echo wp_kses_post( $this->field_after() );
		}

		/**
		 * Enqueue
		 *
		 * @return void
		 */
		public function enqueue() {
			if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
			}
		}
	}
}
