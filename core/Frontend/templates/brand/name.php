<?php
/**
 * The frontend facing brand term name file.
 *
 * The output HTML for the name of brand term.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Frontend/templates/brand
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

?>
<div class="sp-smart-brand-name text-center">
	<?php do_action( 'sp_smart_brand_before_name' ); ?>
	<a class="sp-brand-name" href="<?php echo esc_url( get_term_link( $brand_term->term_id ) ); ?>">
		<?php
		$brand_name = $brand_term->name;
		if ( is_array( $brands ) && count( $brands ) > 1 && ( $brand_key < count( $brands ) - 1 ) ) {
			$brand_name .= ', ';
		}
		echo $brand_name; // phpcs:ignore
		?>
	</a>
	<?php do_action( 'sp_smart_brand_after_name' ); ?>
</div>
