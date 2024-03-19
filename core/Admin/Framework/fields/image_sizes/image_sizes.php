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
	die; } // Cannot access directly.
/**
 *
 * Field: Image sizes.
 *
 * @since 1.0.8
 * @version 1.0.8
 */
if ( ! class_exists( 'SPF_SMART_BRANDS_Field_image_sizes' ) ) {
	/**
	 *
	 * Field: layout_preset
	 *
	 * @since 1.0.8
	 * @version 1.0.8
	 */
	class SPF_SMART_BRANDS_Field_image_sizes extends SPF_SMART_BRANDS_Fields {

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
		 * The render method.
		 *
		 * @return void
		 */
		public function render() {
			$args = wp_parse_args(
				$this->field,
				array(
					'chosen'      => false,
					'multiple'    => false,
					'placeholder' => '',
				)
			);

			$this->value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );
			echo wp_kses_post( $this->field_before() );

			// Get the image sizes.
			global $_wp_additional_image_sizes;
			$sizes = array();

			foreach ( get_intermediate_image_sizes() as $_size ) {
				if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {

					$width  = get_option( "{$_size}_size_w" );
					$height = get_option( "{$_size}_size_h" );
					$crop   = (bool) get_option( "{$_size}_crop" ) ? 'hard' : 'soft';

					$sizes[ $_size ] = ucfirst( "{$_size} - $crop:{$width}x{$height}" );

				} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

					$width  = $_wp_additional_image_sizes[ $_size ]['width'];
					$height = $_wp_additional_image_sizes[ $_size ]['height'];
					$crop   = $_wp_additional_image_sizes[ $_size ]['crop'] ? 'hard' : 'soft';

					$sizes[ $_size ] = ucfirst( "{$_size} - $crop:{$width}X{$height}" );
				}
			}
			$sizes = array_merge(
				$sizes,
				array(
					'full'   => __( 'Original uploaded image', 'smart-brands-for-woocommerce' ),
					'custom' => __( 'Set custom size (Pro)', 'smart-brands-for-woocommerce' ),
				)
			);

			if ( ! empty( $sizes ) ) {
				$multiple_name    = ( $args['multiple'] ) ? '[]' : '';
				$multiple_attr    = ( $args['multiple'] ) ? ' multiple="multiple"' : '';
				$chosen_rtl       = ( is_rtl() ) ? ' chosen-rtl' : '';
				$chosen_attr      = ( $args['chosen'] ) ? ' class="spf-chosen' . esc_attr( $chosen_rtl ) . '"' : '';
				$placeholder_attr = ( $args['chosen'] && $args['placeholder'] ) ? ' data-placeholder="' . esc_attr( $args['placeholder'] ) . '"' : '';
				if ( ! empty( $sizes ) ) {
					echo '<select name="' . esc_attr( $this->field_name( $multiple_name ) ) . '"' . $multiple_attr . $chosen_attr . $placeholder_attr . $this->field_attributes() . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

					if ( $args['placeholder'] && empty( $args['multiple'] ) ) {
						if ( ! empty( $args['chosen'] ) ) {
							echo '<option value=""></option>';
						} else {
							echo '<option value="">' . esc_html( $args['placeholder'] ) . '</option>';
						}
					}

					foreach ( $sizes as $option_key => $option ) {

						$is_custom_size = '';
						if ( 'custom' === $option_key ) {
							$is_custom_size = 'disabled';
						}

						if ( is_array( $option ) && ! empty( $option ) ) {
							echo '<optgroup label="' . esc_attr( $option_key ) . '">';

							foreach ( $option as $sub_key => $sub_value ) {
								$selected = ( in_array( $sub_key, $this->value, true ) ) ? ' selected' : '';
								echo '<option value="' . esc_attr( $sub_key ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $sub_value ) . '</option>';
							}
							echo '</optgroup>';
						} else {
							$selected = ( in_array( $option_key, $this->value, true ) ) ? ' selected' : '';
							echo '<option ' . esc_attr( $is_custom_size ) . ' value="' . esc_attr( $option_key ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $option ) . '</option>';
						}
					}
					echo '</select>';

				} else {
					echo ! empty( $this->field['empty_message'] ) ? wp_kses_post( $this->field['empty_message'] ) : esc_html__( 'No image sizes found.', 'smart-brands-for-woocommerce' );
				}
			}
			echo wp_kses_post( $this->field_after() );
		}
	}
}
