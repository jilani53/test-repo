<?php
/**
 * Section title.
 *
 * This template can be overridden by copying it to yourtheme/smart-brand-for-wc/templates/section-title.php
 *
 * @link       https://shapedplugin.com
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Frontend/templates
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

?>
<h3 class="sp-smart-brand-section-title">
	<?php do_action( 'sp_smart_brand_before_section_title' ); ?>
	<span><?php echo wp_kses_post( $title ); ?></span>
	<?php do_action( 'sp_smart_brand_after_section_title' ); ?>
</h3>
