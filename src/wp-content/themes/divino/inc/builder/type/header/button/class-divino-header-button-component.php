<?php
/**
 * Heading Colors for divino theme.
 *
 * @package     divino
 * @link        https://www.brainstormforce.com
 * @since       divino 2.1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'divino_HEADER_BUTTON_DIR', divino_THEME_DIR . 'inc/builder/type/header/button' );
define( 'divino_HEADER_BUTTON_URI', divino_THEME_URI . 'inc/builder/type/header/button' );

/**
 * Heading Initial Setup
 *
 * @since 2.1.4
 */
class divino_Header_Button_Component {
	/**
	 * Constructor function that initializes required actions and hooks
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once divino_HEADER_BUTTON_DIR . '/class-divino-header-button-component-loader.php';

		// Include front end files.
		if ( ! is_admin() || divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
			require_once divino_HEADER_BUTTON_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

}

/**
 *  Kicking this off by creating an object.
 */
new divino_Header_Button_Component();
