<?php
/**
 * The trait file to create single instance.
 *
 * @link       https://shapedplugin.com
 * @since      1.0.0
 *
 * @package    Smart_Brands_For_Wc
 * @subpackage Smart_Brands_For_Wc/Traits
 */

namespace ShapedPlugin\SmartBrands\Traits;

/**
 * The trait for singleton instance.
 *
 * @package Smart_Brands_For_Wc
 * @since 1.0.0
 */
trait Singleton {

	/**
	 * The single instance of the class.
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Make a class instance.
	 *
	 * @return object
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}
