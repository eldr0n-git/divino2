<?php
/**
 * Heading Colors for Astra theme.
 *
 * @package     Astra
 * @link        https://www.brainstormforce.com
 * @since       Astra 2.1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'divino_THEME_HEADING_COLORS_DIR', divino_THEME_DIR . 'inc/addons/heading-colors/' );
define( 'divino_THEME_HEADING_COLORS_URI', divino_THEME_URI . 'inc/addons/heading-colors/' );

if ( ! class_exists( 'divino_Heading_Colors' ) ) {

	/**
	 * Heading Initial Setup
	 *
	 * @since 2.1.4
	 */
	class divino_Heading_Colors {
		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {

			require_once divino_THEME_HEADING_COLORS_DIR . 'class-astra-heading-colors-loader.php';// phpcs:ignore: WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

			// Include front end files.
			if ( ! is_admin() ) {
				require_once divino_THEME_HEADING_COLORS_DIR . 'dynamic-css/dynamic.css.php';// phpcs:ignore: WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}
		}
	}

	/**
	 *  Kicking this off by creating an object.
	 */
	new divino_Heading_Colors();

}
