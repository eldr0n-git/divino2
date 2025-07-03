<?php
/**
 * Heading Colors for Astra theme.
 *
 * @package     astra-builder
 * @link        https://wpastra.com/
 * @since       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'divino_HEADER_SOCIAL_ICON_DIR', divino_THEME_DIR . 'inc/builder/type/header/social-icon' );
define( 'divino_HEADER_SOCIAL_ICON_URI', divino_THEME_URI . 'inc/builder/type/header/social-icon' );

/**
 * Heading Initial Setup
 *
 * @since 3.0.0
 */
class divino_Header_Social_Icon_Component {
	/**
	 * Constructor function that initializes required actions and hooks
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once divino_HEADER_SOCIAL_ICON_DIR . '/class-astra-header-social-icon-component-loader.php';

		// Include front end files.
		if ( ! is_admin() || divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
			require_once divino_HEADER_SOCIAL_ICON_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating an object.
 */
new divino_Header_Social_Icon_Component();
