<?php
/**
 * Framework layout preset field file.
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

if ( ! class_exists( 'SPF_SMART_BRANDS_Field_layout_preset' ) ) {
	/**
	 *
	 * Field: layout_preset
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_SMART_BRANDS_Field_layout_preset extends SPF_SMART_BRANDS_Fields {

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

			$args = wp_parse_args(
				$this->field,
				array(
					'multiple' => false,
					'options'  => array(),
				)
			);

			$value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo wp_kses_post( $this->field_before() );

			if ( ! empty( $args['options'] ) ) {

				echo '<div class="csf-siblings csf--image-group" data-multiple="' . esc_attr( $args['multiple'] ) . '">';

				foreach ( $args['options'] as $key => $option ) {

					$type           = ( $args['multiple'] ) ? 'checkbox' : 'radio';
					$extra          = ( $args['multiple'] ) ? '[]' : '';
					$active         = ( in_array( $key, $value ) ) ? ' csf--active' : '';
					$checked        = ( in_array( $key, $value ) ) ? ' checked' : '';
					$pro_only_class = isset( $option['pro_only'] ) ? ' csf-pro-only' : '';

					echo '<div class="csf--sibling csf--image' . esc_attr( $active . $pro_only_class ) . '">';
					echo '<figure>';
					echo '<img src="' . esc_url( $option['image'] ) . '" alt="' . esc_attr( $option['text'] ) . '" />';
					echo '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $this->field_name( $extra ) ) . '" value="' . esc_attr( $key ) . '"' . $this->field_attributes() . $checked . '/>';// phpcs:ignore
					echo '</figure>';
					echo '<span class="csf-layout-type">' . wp_kses_post( $option['text'] ) . '</span>';
					echo '</div>';
				}
				echo '</div>';
			}
			echo '<div class="clear"></div>';
			echo wp_kses_post( $this->field_after() );
		}

	}
}
