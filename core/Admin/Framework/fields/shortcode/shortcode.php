<?php
/**
 * Framework shortcode fields file.
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

if ( ! class_exists( 'SPF_SMART_BRANDS_Field_shortcode' ) ) {
	/**
	 *
	 * Field: Shortcode
	 *
	 * @since 2.0
	 * @version 2.0
	 */
	class SPF_SMART_BRANDS_Field_shortcode extends SPF_SMART_BRANDS_Fields {
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

			$type = ( ! empty( $this->field['attributes']['type'] ) ) ? $this->field['attributes']['type'] : 'text';
			global $post;
			$postid = $post->ID;
			echo ( ! empty( $postid ) ) ? '<div class="sp-smart-brand-scode-wrapper">
			<div class="sbfw-col-lg-3">
				<div class="sbfw-scode-content">
					<h2 class="sbfw-sc-title">Shortcode</h2>
					<div class="sbfw-after-copy-text"><i class="fa fa-check-circle"></i>  Shortcode  Copied to Clipboard! </div>
					<p>Copy and paste this shortcode into your posts or pages:</p>
					<div class="shortcode-wrap">
					<div class="sbfw-shcode-selectable">[smart_brand_for_wc id="' . esc_attr( $postid ) . '"] </div>
					</div>
				</div>
			</div>
			<div class="sbfw-col-lg-3">
				<div class="sbfw-scode-content">
					<h2 class="sbfw-sc-title">Page Builders</h2>
					<p>Smart Brands has seamless integration with <strong>Gutenberg</strong>, Classic Editor, Elementor, Divi, Bricks, Beaver, Oxygen, WPBakery Builder, etc.</p>
				</div>
			</div>
			<div class="sbfw-col-lg-3">
				<div class="sbfw-scode-content">
					<h2 class="sbfw-sc-title">Template Include</h2>
					<p>Paste the PHP code into your template file:</p>
					<div class="shortcode-wrap">
					<span class="sbfw-shcode-selectable">&lt;?php smart_brand_for_wc( ' . esc_attr( $postid ) . ' ); ?&gt;</span>
					</div>
				</div>
			</div>
		</div>' : '';
		}
	}
}
