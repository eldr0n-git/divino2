<?php
/**
 * Primary Footer component.
 *
 * @package     divino Builder
 * @link        https://www.brainstormforce.com
 * @since       divino 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'divino_BUILDER_FOOTER_PRIMARY_FOOTER_DIR', divino_THEME_DIR . 'inc/builder/type/footer/primary-footer' );
define( 'divino_BUILDER_FOOTER_PRIMARY_FOOTER_URI', divino_THEME_URI . 'inc/builder/type/footer/primary-footer' );

if ( ! class_exists( 'divino_Primary_Footer' ) ) {

	/**
	 * Primary Footer Initial Setup
	 *
	 * @since 3.0.0
	 */
	class divino_Primary_Footer {
		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {

			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once divino_BUILDER_FOOTER_PRIMARY_FOOTER_DIR . '/class-divino-primary-footer-component-loader.php';

			// Include front end files.
			if ( ! is_admin() || divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
				require_once divino_BUILDER_FOOTER_PRIMARY_FOOTER_DIR . '/dynamic-css/dynamic.css.php';
			}
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
	}

	/**
	 *  Kicking this off by creating an object.
	 */
	new divino_Primary_Footer();

}
