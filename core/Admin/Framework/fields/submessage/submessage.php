<?php
/**
 * Framework submessage field file.
 *
 * @link       https://shapedplugin.com
 * @since      2.0.0
 *
 * @package    Smart_Brands_For_Wc
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPF_SMART_BRANDS_Field_submessage' ) ) {
	/**
	 *
	 * Field: submessage
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_SMART_BRANDS_Field_submessage extends SPF_SMART_BRANDS_Fields {
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
			$style = ( ! empty( $this->field['style'] ) ) ? $this->field['style'] : 'normal';
			echo '<div class="csf-field-submessage csf-submessage-' . esc_attr( $style ) . '">' . wp_kses_post( $this->field['content'] ) . '</div>';
		}

	}
}
