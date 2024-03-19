<?php
/**
 * The file to manage all public-facing functionality of the plugin.
 *
 * @link       https://shapedplugin.com
 *
 * @since      1.0.0
 *
 * @package    smart_brands_for_wc
 * @subpackage smart_brands_for_wc/Frontend
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

namespace ShapedPlugin\SmartBrands\Frontend;

/**
 * The Manager class to manage all public facing stuffs.
 *
 * @since 1.0.0
 */
class Manager {
	/**
	 * Custom Template locator.
	 *
	 * @param  mixed $template_name template name.
	 * @param  mixed $template_path template path.
	 * @param  mixed $default_path default path.
	 * @return string
	 */
	public static function smart_locate_template( $template_name, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) {
			$template_path = 'smart-brand-for-wc/templates';
		}

		if ( ! $default_path ) {
			$default_path = SMART_BRANDS_PATH . '/core/Frontend/templates/';
		}

		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		// Get default template.
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}
		// Return what we found.
		return $template;
	}

	/**
	 * Minify output
	 *
	 * @param  string $html output minifier.
	 * @return statement
	 */
	public static function minify_output( $html ) {
		$html = preg_replace( '/<!--(?!s*(?:[if [^]]+]|!|>))(?:(?!-->).)*-->/s', '', $html );
		$html = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $html );
		while ( stristr( $html, '  ' ) ) {
			$html = str_replace( '  ', ' ', $html );
		}
		return $html;
	}

	/**
	 * View preloader
	 *
	 * @param  mixed $preloader Show/hide preloader.
	 * @return void
	 */
	public static function smart_brand_preloader( $preloader ) {
		ob_start();
		include self::smart_locate_template( 'preloader.php' );
		$preloader = apply_filters( 'smart_brand_preloader', ob_get_clean() );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $preloader;
	}

	/**
	 * View Section Title.
	 *
	 * @param  integer $views_id The shortcode ID.
	 * @param  integer $show_section_title The show / hide option.
	 * @param  string  $main_section_title get the section title.
	 * @return void
	 */
	public static function smart_brand_section_title( $views_id, $show_section_title, $main_section_title ) {
		$title_text = $main_section_title;
		$title      = apply_filters( 'smart_brand_section_title', $title_text );
		if ( ! empty( $title ) && $show_section_title ) {
			include self::smart_locate_template( 'section-title.php' );
		}
	}

	/**
	 * Brand Thumbnail.
	 *
	 * The function to process brand image HTML.
	 *
	 * @param object $brand_term The brand term object to show brand thumbnail.
	 * @return void
	 */
	public static function brand_logo( $brand_term ) {
		$term_all_meta = get_term_meta( $brand_term->term_id, 'sp_smart_brand_taxonomy_meta', true );
		$term_logo_id  = isset( $term_all_meta['smart_brand_term_logo']['id'] ) ? $term_all_meta['smart_brand_term_logo']['id'] : '';

		$logo_source = wp_get_attachment_image_src( $term_logo_id, 'medium' );
		$logo_source = is_array( $logo_source ) ? $logo_source : array( '', '', '' );
		ob_start();
		if ( ! empty( $term_logo_id ) ) {
			include self::smart_locate_template( 'brand/image.php' );
		}
		$brand_logo = apply_filters( 'smart_brand_term_logo', ob_get_clean() );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $brand_logo;
	}

	/**
	 * The function to process views HTML.
	 *
	 * @param  integer $views_id The views generator ID.
	 * @param  array   $views_meta The array of the generator serialized meta.
	 * @param  string  $main_section_title get the section title.
	 * @return void
	 */
	public static function views_html( $views_id, $views_meta, $main_section_title ) {
		// Include generator configurations.
		include 'partials/configurations.php';
		// Filter parent categories.
		$brand_args      = array(
			'taxonomy' => 'sp_smart_brand',
			'orderby'  => $order_by,
			'order'    => $brand_order,
			'parent'   => 0,
		);
		$filtered_brands = get_categories( $brand_args );
		if ( 'rand' === $order_by ) {
			shuffle( $filtered_brands );
		}
		$carousel_class = 'carousel_layout' === $layout_preset ? ' sp-smart-brand-carousel-wrapper' : '';
		?>
		<div id="smart-brand-main-area-<?php echo esc_attr( $views_id ); ?>" class="sp-smart-brand-wrapper<?php echo esc_attr( $carousel_class ); ?>">
			<div class="sp-smart-brand-section">
				<?php
				self::smart_brand_preloader( $preloader );
				self::smart_brand_section_title( $views_id, $show_section_title, $main_section_title );
				switch ( $layout_preset ) {
					case 'carousel_layout':
							wp_enqueue_script( 'smart_brand_swiper' );
							wp_enqueue_script( 'smart_brand_script' );
						include self::smart_locate_template( 'carousel.php' );
						break;
				}
				?>
			</div>
		</div>
		<?php
	} // views html.
}
