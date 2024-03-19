<?php
/**
 * Framework border field file.
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

if ( ! class_exists( 'SPF_SMART_BRANDS_Field_border' ) ) {
	/**
	 *
	 * Field: border
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_SMART_BRANDS_Field_border extends SPF_SMART_BRANDS_Fields {
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

			$args = wp_parse_args(
				$this->field,
				array(
					'top_icon'           => '<i class="fas fa-long-arrow-alt-up"></i>',
					'left_icon'          => '<i class="fas fa-long-arrow-alt-left"></i>',
					'bottom_icon'        => '<i class="fas fa-long-arrow-alt-down"></i>',
					'right_icon'         => '<i class="fas fa-long-arrow-alt-right"></i>',
					'all_icon'           => '<i class="fas fa-arrows-alt"></i>',
					'top_placeholder'    => esc_html__( 'top', 'smart-brands-for-woocommerce' ),
					'right_placeholder'  => esc_html__( 'right', 'smart-brands-for-woocommerce' ),
					'bottom_placeholder' => esc_html__( 'bottom', 'smart-brands-for-woocommerce' ),
					'left_placeholder'   => esc_html__( 'left', 'smart-brands-for-woocommerce' ),
					'all_placeholder'    => esc_html__( 'all', 'smart-brands-for-woocommerce' ),
					'top'                => true,
					'left'               => true,
					'bottom'             => true,
					'right'              => true,
					'all'                => false,
					'color'              => true,
					'hover_color'        => true,
					'style'              => true,
					'unit'               => 'px',
				)
			);

			$default_value = array(
				'top'         => '',
				'right'       => '',
				'bottom'      => '',
				'left'        => '',
				'color'       => '',
				'hover_color' => '',
				'style'       => 'solid',
				'all'         => '',
			);

			$border_props = array(
				'solid'  => esc_html__( 'Solid', 'smart-brands-for-woocommerce' ),
				'dashed' => esc_html__( 'Dashed', 'smart-brands-for-woocommerce' ),
				'dotted' => esc_html__( 'Dotted', 'smart-brands-for-woocommerce' ),
				'double' => esc_html__( 'Double', 'smart-brands-for-woocommerce' ),
				'inset'  => esc_html__( 'Inset', 'smart-brands-for-woocommerce' ),
				'outset' => esc_html__( 'Outset', 'smart-brands-for-woocommerce' ),
				'groove' => esc_html__( 'Groove', 'smart-brands-for-woocommerce' ),
				'ridge'  => esc_html__( 'Ridge', 'smart-brands-for-woocommerce' ),
				'none'   => esc_html__( 'None', 'smart-brands-for-woocommerce' ),
			);

			$default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

			$value = wp_parse_args( $this->value, $default_value );

			echo wp_kses_post( $this->field_before() );

			echo '<div class="csf--inputs" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';

			if ( ! empty( $args['all'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . esc_attr( $args['all_placeholder'] ) . '"' : '';

				echo '<div class="csf--border">';
				echo '<div class="csf--title">' . __( 'Width', 'smart-brands-for-woocommerce' ) . '</div>';
				echo '<div class="csf--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="csf--label csf--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '"' . $placeholder . ' class="csf-input-number csf--is-unit" step="any" />';//phpcs:ignore
				echo ( ! empty( $args['unit'] ) ) ? '<span class="csf--label csf--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
				echo '</div>';
				echo '</div>';

			} else {

				$properties = array();

				foreach ( array( 'top', 'right', 'bottom', 'left' ) as $prop ) {
					if ( ! empty( $args[ $prop ] ) ) {
						$properties[] = $prop;
					}
				}

				$properties = ( array( 'right', 'left' ) === $properties ) ? array_reverse( $properties ) : $properties;

				foreach ( $properties as $property ) {

					$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? ' placeholder="' . esc_attr( $args[ $property . '_placeholder' ] ) . '"' : '';

					echo '<div class="csf--border">';
					echo '<div class="csf--title">' . esc_html__( 'Width', 'smart-brands-for-woocommerce' ) . '</div>';
					echo '<div class="csf--input">';
					echo ( ! empty( $args['all_icon'] ) ) ? '<span class="csf--label csf--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '"' . $placeholder . ' class="csf-input-number csf--is-unit" step="any" />';//phpcs:ignore
					echo ( ! empty( $args['unit'] ) ) ? '<span class="csf--label csf--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
					echo '</div>';
					echo '</div>';

				}
			}

			if ( ! empty( $args['style'] ) ) {
				echo '<div class="csf--border">';
				echo '<div class="csf--title">' . esc_html__( 'Style', 'smart-brands-for-woocommerce' ) . '</div>';
				echo '<div class="csf--input">';
				echo '<select name="' . esc_attr( $this->field_name( '[style]' ) ) . '">';
				foreach ( $border_props as $border_prop_key => $border_prop_value ) {
					$selected = ( $value['style'] === $border_prop_key ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $border_prop_key ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $border_prop_value ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
				echo '</div>';
			}

			echo '</div>';

			if ( ! empty( $args['color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['color'] ) . '"' : '';
				echo '<div class="csf--color">';
				echo '<div class="csf-field-color">';
				echo '<div class="csf--title">' . esc_html__( 'Color', 'smart-brands-for-woocommerce' ) . '</div>';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[color]' ) ) . '" value="' . esc_attr( $value['color'] ) . '" class="csf-color"' . $default_color_attr . ' />';//phpcs:ignore
				echo '</div>';
				echo '</div>';
			}

			if ( ! empty( $args['hover_color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['hover_color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['hover_color'] ) . '"' : '';
				echo '<div class="csf--color">';
				echo '<div class="csf-field-color">';
				echo '<div class="csf--title">' . esc_html__( 'Hover Color', 'smart-brands-for-woocommerce' ) . '</div>';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[hover_color]' ) ) . '" value="' . esc_attr( $value['hover_color'] ) . '" class="csf-color"' . $default_color_attr . ' />'; //phpcs:ignore
				echo '</div>';
				echo '</div>';
			}

			echo wp_kses_post( $this->field_after() );
		}

	}
}
