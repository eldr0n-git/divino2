<?php
/**
 * Contact Form 7 Compatibility File.
 *
 * @package divino
 */

// If plugin - 'Contact Form 7' not exist then return.
if ( ! class_exists( 'WPCF7' ) ) {
	return;
}

/**
 * divino Contact Form 7 Compatibility
 */
if ( ! class_exists( 'divino_Contact_Form_7' ) ) {

	/**
	 * divino Contact Form 7 Compatibility
	 *
	 * @since 1.0.0
	 */
	class divino_Contact_Form_7 {
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
			add_action( 'wpcf7_enqueue_styles', array( $this, 'add_styles' ) );
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

			$css_file = divino_THEME_URI . 'assets/css/' . $dir_name . '/compatibility/contact-form-7-main' . $file_prefix . '.css';

			wp_enqueue_style( 'divino-contact-form-7', $css_file, array(), divino_THEME_VERSION, 'all' );
		}

	}

}

/**
 * Kicking this off by calling 'get_instance()' method
 */
divino_Contact_Form_7::get_instance();
