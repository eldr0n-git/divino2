<?php
/**
 * Footer Colors for divino theme Buttpn.
 *
 * @package     divino
 * @link        https://www.brainstormforce.com
 * @since       divino 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'divino_FOOTER_BUTTON_DIR', divino_THEME_DIR . 'inc/builder/type/footer/button' );
define( 'divino_FOOTER_BUTTON_URI', divino_THEME_URI . 'inc/builder/type/footer/button' );

/**
 * Heading Initial Setup
 *
 * @since 3.0.0
 */
class divino_Footer_Button_Component {
	/**
	 * Constructor function that initializes required actions and hooks
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once divino_FOOTER_BUTTON_DIR . '/class-divino-footer-button-component-loader.php';

		// Include front end files.
		if ( ! is_admin() || divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
			require_once divino_FOOTER_BUTTON_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating an object.
 */
new divino_Footer_Button_Component();
