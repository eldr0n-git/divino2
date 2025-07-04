<?php
/**
 * Gravity Forms File.
 *
 * @package divino
 */

// If plugin - 'Gravity Forms' not exist then return.
if ( ! class_exists( 'GFForms' ) ) {
	return;
}

/**
 * divino Gravity Forms
 */
if ( ! class_exists( 'divino_Gravity_Forms' ) ) {

	/**
	 * divino Gravity Forms
	 *
	 * @since 1.0.0
	 */
	class divino_Gravity_Forms {
		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			if ( divino_Dynamic_CSS::divino_4_6_0_compatibility() ) {
				return;
			}
			add_action( 'gform_enqueue_scripts', array( $this, 'add_styles' ) );
		}

		/**
		 * Add assets in theme
		 *
		 * @since 1.0.0
		 */
		public function add_styles() {
			$file_prefix = '.min';
			$dir_name    = 'minified';

			if ( is_rtl() ) {
				$file_prefix .= '-rtl';
			}

			$css_file = divino_THEME_URI . 'assets/css/' . $dir_name . '/compatibility/gravity-forms' . $file_prefix . '.css';

			wp_enqueue_style( 'divino-gravity-forms', $css_file, array(), divino_THEME_VERSION, 'all' );
		}

	}

}

/**
 * Kicking this off by calling 'get_instance()' method
 */
divino_Gravity_Forms::get_instance();
