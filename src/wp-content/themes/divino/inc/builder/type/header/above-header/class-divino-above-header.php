<?php
/**
 * Above Header.
 *
 * @package     divino-builder
 * @link        https://www.brainstormforce.com
 * @since       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'divino_ABOVE_HEADER_DIR', divino_THEME_DIR . 'inc/builder/type/header/above-header' );
define( 'divino_ABOVE_HEADER_URI', divino_THEME_URI . 'inc/builder/type/header/above-header' );

/**
 * Above Header Initial Setup
 *
 * @since 3.0.0
 */
class divino_Above_Header {
	/**
	 * Constructor function that initializes required actions and hooks.
	 */
	public function __construct() {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once divino_ABOVE_HEADER_DIR . '/class-divino-above-header-loader.php';

		// Include front end files.
		if ( ! is_admin() || divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
			require_once divino_ABOVE_HEADER_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating an object.
 */
new divino_Above_Header();
