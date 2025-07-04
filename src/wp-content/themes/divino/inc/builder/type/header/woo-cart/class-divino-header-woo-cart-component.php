<?php
/**
 * WooCommerce Cart for divino theme.
 *
 * @package     divino-builder
 * @link        https://wpdivino.com/
 * @since       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'divino_HEADER_WOO_CART_DIR', divino_THEME_DIR . 'inc/builder/type/header/woo-cart' );
define( 'divino_HEADER_WOO_CART_URI', divino_THEME_URI . 'inc/builder/type/header/woo-cart' );

if ( ! class_exists( 'divino_Header_Woo_Cart_Component' ) ) {

	/**
	 * Heading Initial Setup
	 *
	 * @since 3.0.0
	 */
	class divino_Header_Woo_Cart_Component {
		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {

			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once divino_HEADER_WOO_CART_DIR . '/class-divino-header-woo-cart-loader.php';

			// Include front end files.
			if ( ! is_admin() || divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
				require_once divino_HEADER_WOO_CART_DIR . '/dynamic-css/dynamic.css.php';
			}
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
	}

	/**
	 *  Kicking this off by creating an object.
	 */
	new divino_Header_Woo_Cart_Component();

}
