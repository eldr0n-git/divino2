<?php
/**
 * divino Admin Loader
 *
 * @package divino
 * @since 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'divino_Admin_Loader' ) ) {
	/**
	 * divino_Admin_Loader
	 *
	 * @since 4.0.0
	 */
	class divino_Admin_Loader {
		/**
		 * Instance
		 *
		 * @var null $instance
		 * @since 4.0.0
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since 4.0.0
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				/** @psalm-suppress InvalidPropertyAssignmentValue */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				self::$instance = new self();
				/** @psalm-suppress InvalidPropertyAssignmentValue */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 4.0.0
		 */
		public function __construct() {
			define( 'divino_THEME_ADMIN_DIR', divino_THEME_DIR . 'admin/' );
			define( 'divino_THEME_ADMIN_URL', divino_THEME_URI . 'admin/' );

			$this->includes();
		}

		/**
		 * Include required classes.
		 *
		 * @since 4.0.0
		 */
		public function includes() {
			/* Ajax init */
			require_once divino_THEME_ADMIN_DIR . 'includes/class-divino-admin-ajax.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound -- Not a template file so loading in a normal way.

			/* Setup Menu */
			require_once divino_THEME_ADMIN_DIR . 'includes/class-divino-menu.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound -- Not a template file so loading in a normal way.

			require_once divino_THEME_ADMIN_DIR . 'includes/class-divino-theme-builder-free.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound -- Not a template file so loading in a normal way.

			/* BSF Analytics */
			require_once divino_THEME_ADMIN_DIR . 'class-divino-bsf-analytics.php';
		}
	}
}

divino_Admin_Loader::get_instance();
