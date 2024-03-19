<?php
/**
 * The frontend facing Carousel layout file.
 *
 * It shows the frontend output of plugin for carousel layout.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Frontend/templates
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

use ShapedPlugin\SmartBrands\Frontend\Manager;
?>
<div class="swiper-container sp-smart-brand-carousel" data-carousel='{
	"autoplay" : <?php echo esc_attr( $autoplay ); ?>,
	"autoplay_speed" : <?php echo esc_attr( $autoplay_speed ); ?>,
	"spaceBetween" : <?php echo esc_attr( $space_between_brands['all'] ); ?>,
	"sliding_speed" : <?php echo esc_attr( $sliding_speed ); ?>,
	"pause_on_hover" : <?php echo esc_attr( $pause_on_hover ); ?>,
	"infinite_loop" : <?php echo esc_attr( $infinite_loop ); ?>,
	"free_mode" : <?php echo esc_attr( $free_mode ); ?>,
	"carousel_navigation" : "<?php echo esc_attr( $carousel_navigation ); ?>",
	"carousel_pagination" : "<?php echo esc_attr( $carousel_pagination ); ?>",
	"slideToScroll": {
		"large_desktop": <?php echo esc_attr( $large_desktop ); ?>,
		"desktop": <?php echo esc_attr( $desktop ); ?>,
		"laptop": <?php echo esc_attr( $laptop ); ?>,
		"tablet": <?php echo esc_attr( $tablet ); ?>,
		"mobile": <?php echo esc_attr( $mobile ); ?>
		},
	"slidesPerView": {
		"large_desktop": <?php echo esc_html( $lg_desktop_screen ); ?>,
		"desktop": <?php echo esc_html( $desktop_screen ); ?>,
		"laptop": <?php echo esc_html( $laptop_screen ); ?>, 
		"tablet": <?php echo esc_html( $tablet_screen ); ?>,
		"mobile": <?php echo esc_html( $mobile_screen ); ?>
		}
}'>
	<div class="swiper-wrapper">
		<?php
		foreach ( $filtered_brands as $brand_key => $brand_term ) {
			?>
			<div class="swiper-slide smart-brand-term">
				<div class="sp-brand-term-row sp-brand-term-no-gutters">
					<?php include Manager::smart_locate_template( 'brand/brand.php' ); ?>
				</div>
			</div>
				<?php
		}
		?>
	</div>
	<?php if ( $carousel_navigation ) { ?>
		<div class="sp-brand-button-next swiper-button-next"></div>
		<div class="sp-brand-button-prev swiper-button-prev"></div>
		<?php
	}
	if ( $carousel_pagination ) {
		?>
	<div class="sp-brand-pagination swiper-pagination"></div>
	<?php } ?>
</div>
