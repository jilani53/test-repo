<?php
/**
 * Framework column field file.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.8
 *
 * @package    Smart_Brands_For_Wc
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

/**
 *
 * Field: color_group
 *
 * @since 1.0.8
 * @version 1.0.8
 */
if ( ! class_exists( 'SPF_SMART_BRANDS_Field_color_group' ) ) {
	/**
	 *
	 * Field: Color Group
	 *
	 * @since 1.0.8
	 * @version 1.0.8
	 */
	class SPF_SMART_BRANDS_Field_color_group extends SPF_SMART_BRANDS_Fields {
		/**
		 * Field constructor.
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
		 * Render field
		 *
		 * @return void
		 */
		public function render() {
			$options = ( ! empty( $this->field['options'] ) ) ? $this->field['options'] : array();
			echo wp_kses_post( $this->field_before() );

			if ( ! empty( $options ) ) {
				foreach ( $options as $key => $option ) {
					$color_value  = ( ! empty( $this->value[ $key ] ) ) ? $this->value[ $key ] : '';
					$default_attr = ( ! empty( $this->field['default'][ $key ] ) ) ? ' data-default-color="' . esc_attr( $this->field['default'][ $key ] ) . '"' : '';

					echo '<div class="csf--left csf-field-color">';
					echo '<div class="csf--title">' . esc_html( $option ) . '</div>';
					echo '<input type="text" name="' . esc_attr( $this->field_name( '[' . $key . ']' ) ) . '" value="' . esc_attr( $color_value ) . '" class="csf-color"' . $default_attr . $this->field_attributes() . '/>'; // phpcs:ignore
					echo '</div>';
				}
			}
			echo wp_kses_post( $this->field_after() );
		}
	}
}
