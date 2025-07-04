<?php
/**
 * Site Origin Compatibility File.
 *
 * @package divino
 */

// If plugin - 'Site Origin' not exist then return.
if ( ! class_exists( 'SiteOrigin_Panels_Settings' ) ) {
	return;
}

/**
 * divino Site Origin Compatibility
 */
if ( ! class_exists( 'divino_Site_Origin' ) ) {

	/**
	 * divino Site Origin Compatibility
	 *
	 * @since 1.0.0
	 */
	class divino_Site_Origin {
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
			add_filter( 'divino_theme_assets', array( $this, 'add_styles' ) );
		}

		/**
		 * Add assets in theme
		 *
		 * @param array $assets list of theme assets (JS & CSS).
		 * @return array List of updated assets.
		 * @since 1.0.0
		 */
		public function add_styles( $assets ) {
			$assets['css']['divino-site-origin'] = 'compatibility/site-origin';
			return $assets;
		}

	}

}

/**
 * Kicking this off by calling 'get_instance()' method
 */
divino_Site_Origin::get_instance();
