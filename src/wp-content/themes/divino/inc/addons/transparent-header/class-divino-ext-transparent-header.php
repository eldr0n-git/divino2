<?php
/**
 * Sticky Header Extension
 *
 * @package divino
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'divino_THEME_TRANSPARENT_HEADER_DIR', divino_THEME_DIR . 'inc/addons/transparent-header/' );
define( 'divino_THEME_TRANSPARENT_HEADER_URI', divino_THEME_URI . 'inc/addons/transparent-header/' );

if ( ! class_exists( 'divino_Ext_Transparent_Header' ) ) {

	/**
	 * Sticky Header Initial Setup
	 *
	 * @since 1.0.0
	 */
	class divino_Ext_Transparent_Header {
		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 *  Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {

			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once divino_THEME_TRANSPARENT_HEADER_DIR . 'classes/class-divino-ext-transparent-header-loader.php';
			require_once divino_THEME_TRANSPARENT_HEADER_DIR . 'classes/class-divino-ext-transparent-header-markup.php';

			// Include front end files.
			if ( ! is_admin() ) {
				require_once divino_THEME_TRANSPARENT_HEADER_DIR . 'classes/dynamic-css/dynamic.css.php';
				require_once divino_THEME_TRANSPARENT_HEADER_DIR . 'classes/dynamic-css/header-sections-dynamic.css.php';
			}
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
	}

	/**
	 *  Kicking this off by calling 'get_instance()' method
	 */
	divino_Ext_Transparent_Header::get_instance();

}
