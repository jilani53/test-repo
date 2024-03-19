<?php
/**
 * The frontend facing brand term image file.
 *
 * The output HTML for the image of brand term.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Frontend/templates/brand
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

?>
<div class="sp-smart-brand-thumb-wrapper text-center">
	<a href="<?php echo esc_url( get_term_link( $brand_term->term_id ) ); ?>">
		<img src="<?php echo esc_url( $logo_source[0] ?? '' ); ?>" alt="<?php echo esc_html( $brand_term->name ); ?>" width="<?php echo esc_attr( $logo_source[1] ); ?>" height="<?php echo esc_attr( $logo_source[2] ); ?>" class="sp-smart-brand-thumb"/>
	</a>
</div>
