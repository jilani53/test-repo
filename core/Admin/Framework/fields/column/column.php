<?php
/**
 * Framework column field file.
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

if ( ! class_exists( 'SPF_SMART_BRANDS_Field_column' ) ) {
	/**
	 *
	 * Field: column
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_SMART_BRANDS_Field_column extends SPF_SMART_BRANDS_Fields {
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
					'large_desktop_icon'        => '<i class="fa fa-television"></i>',
					'desktop_icon'              => '<i class="fa fa-desktop"></i>',
					'laptop_icon'               => '<i class="fa fa-laptop"></i>',
					'tablet_icon'               => '<i class="fa fa-tablet"></i>',
					'mobile_icon'               => '<i class="fa fa-mobile"></i>',
					'all_text'                  => '<i class="fa fa-arrows"></i>',
					'large_desktop_placeholder' => esc_html__( 'Large Desktop', 'smart-brands-for-woocommerce' ),
					'desktop_placeholder'       => esc_html__( 'Desktop', 'smart-brands-for-woocommerce' ),
					'laptop_placeholder'        => esc_html__( 'Small Desktop', 'smart-brands-for-woocommerce' ),
					'tablet_placeholder'        => esc_html__( 'Tablet', 'smart-brands-for-woocommerce' ),
					'mobile_placeholder'        => esc_html__( 'Mobile', 'smart-brands-for-woocommerce' ),
					'all_placeholder'           => esc_html__( 'all', 'smart-brands-for-woocommerce' ),
					'large_desktop'             => true,
					'desktop'                   => true,
					'laptop'                    => true,
					'tablet'                    => true,
					'mobile'                    => true,
					'unit'                      => false,
					'min'                       => '0',
					'all'                       => false,
					'units'                     => array( 'px', '%', 'em' ),
				)
			);

			$default_values = array(
				'large_desktop' => '4',
				'desktop'       => '4',
				'laptop'        => '3',
				'tablet'        => '2',
				'mobile'        => '1',
				'min'           => '',
				'all'           => '',
				'unit'          => 'px',
			);

			$value = wp_parse_args( $this->value, $default_values );

			echo wp_kses_post( $this->field_before() );

			echo '<div class="csf--inputs">';

			$min = ( isset( $args['min'] ) ) ? ' min="' . $args['min'] . '"' : '';
			if ( ! empty( $args['all'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . $args['all_placeholder'] . '"' : '';

				echo '<div class="csf--input">';
				echo ( ! empty( $args['all_text'] ) ) ? '<span class="csf--label csf--icon">' . wp_kses_post( $args['all_text'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '"' . $placeholder . $min . ' class="csf-number" />';//phpcs:ignore
				echo ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? '<span class="csf--label csf--label-unit">' . esc_html( $args['units'][0] ) . '</span>' : '';
				echo '</div>';

			} else {

				$properties = array();

				foreach ( array( 'large_desktop', 'desktop', 'laptop', 'tablet', 'mobile' ) as $prop ) {
					if ( ! empty( $args[ $prop ] ) ) {
						$properties[] = $prop;
					}
				}

				$properties = ( array( 'laptop', 'mobile' ) === $properties ) ? array_reverse( $properties ) : $properties;

				foreach ( $properties as $property ) {

					$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? ' placeholder="' . esc_attr( $args[ $property . '_placeholder' ] ) . '"' : '';

					echo '<div class="csf--input">';
					echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="csf--label csf--icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '"' . $placeholder . $min . ' class="csf-number" />';// phpcs:ignore
					echo ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? '<span class="csf--label csf--label-unit">' . esc_html( $args['units'][0] ) . '</span>' : '';
					echo '</div>';

				}
			}

			if ( ! empty( $args['unit'] ) && count( $args['units'] ) > 1 ) {
				echo '<select name="' . esc_attr( $this->field_name( '[unit]' ) ) . '">';
				foreach ( $args['units'] as $unit ) {
					$selected = ( $value['unit'] === $unit ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $unit ) . '"' . esc_attr( $selected ) . '>' . esc_html( $unit ) . '</option>';
				}
				echo '</select>';
			}

			echo '</div>';
			echo wp_kses_post( $this->field_after() );

		}
	}
}
