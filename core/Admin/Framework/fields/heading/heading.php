<?php
/**
 * Framework heading field file.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.0
 *
 * @package    Smart_Brands_For_Wc
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPF_SMART_BRANDS_Field_heading' ) ) {
	/**
	 *
	 * Field: heading
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_SMART_BRANDS_Field_heading extends SPF_SMART_BRANDS_Fields {

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
		 * Render
		 *
		 * @return void
		 */
		public function render() {
			echo ( ! empty( $this->field['icon'] ) ) ? wp_kses_post( $this->field['icon'] ) : '';
			echo ( ! empty( $this->field['content'] ) ) ? wp_kses_post( $this->field['content'] ) : '';
			echo ( ! empty( $this->field['image'] ) ) ? '<img src="' . esc_url( $this->field['image'] ) . '"/>' : '';
			echo ( ! empty( $this->field['after'] ) && ! empty( $this->field['link'] ) ) ? '<span class="spacer"></span><span class="support"><a href="' . esc_url( $this->field['link'] ) . '" target="_blank">' . wp_kses_post( $this->field['after'] ) . '</a></span>' : '';
		}

	}
}
