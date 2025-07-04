<?php
/**
 * Below Header.
 *
 * @package     divino-builder
 * @link        https://www.brainstormforce.com
 * @since       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'divino_BELOW_HEADER_DIR', divino_THEME_DIR . 'inc/builder/type/header/below-header' );
define( 'divino_BELOW_HEADER_URI', divino_THEME_URI . 'inc/builder/type/header/below-header' );

/**
 * Below Header Initial Setup
 *
 * @since 3.0.0
 */
class divino_Below_Header {
	/**
	 * Constructor function that initializes required actions and hooks.
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once divino_BELOW_HEADER_DIR . '/class-divino-below-header-loader.php';

		// Include front end files.
		if ( ! is_admin() || divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
			require_once divino_BELOW_HEADER_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating an object.
 */
new divino_Below_Header();
