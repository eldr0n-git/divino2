<?php
/**
 * Copyright component.
 *
 * @package     Astra Builder
 * @link        https://www.brainstormforce.com
 * @since       Astra 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'divino_BUILDER_FOOTER_COPYRIGHT_DIR', divino_THEME_DIR . 'inc/builder/type/footer/copyright' );
define( 'divino_BUILDER_FOOTER_COPYRIGHT_URI', divino_THEME_URI . 'inc/builder/type/footer/copyright' );

if ( ! class_exists( 'divino_Footer_Copyright_Component' ) ) {

	/**
	 * divino_Footer_Copyright_Component
	 *
	 * @since 3.0.0
	 */
	class divino_Footer_Copyright_Component {
		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {

			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once divino_BUILDER_FOOTER_COPYRIGHT_DIR . '/class-astra-footer-copyright-component-loader.php';

			// Include front end files.
			if ( ! is_admin() || divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
				require_once divino_BUILDER_FOOTER_COPYRIGHT_DIR . '/dynamic-css/dynamic.css.php';
			}
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
	}

	/**
	 *  Kicking this off by creating an object.
	 */
	new divino_Footer_Copyright_Component();

}
