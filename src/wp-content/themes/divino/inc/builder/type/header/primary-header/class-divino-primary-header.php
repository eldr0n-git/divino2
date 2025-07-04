<?php
/**
 * Heading Colors for divino theme.
 *
 * @package     divino
 * @link        https://www.brainstormforce.com
 * @since       divino 3.0.0.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'divino_PRIMARY_HEADER_DIR', divino_THEME_DIR . 'inc/builder/type/header/primary-header' );
define( 'divino_PRIMARY_HEADER_URI', divino_THEME_URI . 'inc/builder/type/header/primary-header' );

/**
 * Heading Initial Setup
 *
 * @since 3.0.0
 */
class divino_Primary_Header {
	/**
	 * Constructor function that initializes required actions and hooks
	 */
	public function __construct() {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once divino_PRIMARY_HEADER_DIR . '/class-divino-primary-header-loader.php';

		// Include front end files.
		if ( ! is_admin() || divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
			require_once divino_PRIMARY_HEADER_DIR . '/dynamic-css/dynamic.css.php';
			remove_filter( 'divino_dynamic_theme_css', 'divino_header_breakpoint_style' );
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating an object.
 */
new divino_Primary_Header();
