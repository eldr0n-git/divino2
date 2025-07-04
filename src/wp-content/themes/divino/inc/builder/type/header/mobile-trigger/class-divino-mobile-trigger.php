<?php
/**
 * Mobile Trigger.
 *
 * @package     divino-builder
 * @link        https://www.brainstormforce.com
 * @since       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'divino_MOBILE_TRIGGER_DIR', divino_THEME_DIR . 'inc/builder/type/header/mobile-trigger' );
define( 'divino_MOBILE_TRIGGER_URI', divino_THEME_URI . 'inc/builder/type/header/mobile-trigger' );

/**
 * Mobile Trigger Initial Setup
 *
 * @since 3.0.0
 */
class divino_Mobile_Trigger {
	/**
	 * Constructor function that initializes required actions and hooks.
	 */
	public function __construct() {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once divino_MOBILE_TRIGGER_DIR . '/class-divino-mobile-trigger-loader.php';

		// Include front end files.
		if ( ! is_admin() || divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
			require_once divino_MOBILE_TRIGGER_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating an object.
 */
new divino_Mobile_Trigger();
