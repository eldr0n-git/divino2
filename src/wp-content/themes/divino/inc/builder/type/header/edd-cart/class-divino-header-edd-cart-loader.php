<?php
/**
 * EDD Cart Styling Loader for divino theme.
 *
 * @package     divino-builder
 * @link        https://wpdivino.com/
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Customizer Initialization
 *
 * @since 3.0.0
 */
class divino_Header_Edd_Cart_Loader {
	/**
	 * Constructor
	 *
	 * @since 3.0.0
	 */
	public function __construct() {

		add_action( 'customize_preview_init', array( $this, 'preview_scripts' ), 110 );
	}

	/**
	 * Customizer Preview
	 *
	 * @since 3.0.0
	 */
	public function preview_scripts() {
		/**
		 * Load unminified if SCRIPT_DEBUG is true.
		 */
		/* Directory and Extension */
		$dir_name    = SCRIPT_DEBUG ? 'unminified' : 'minified';
		$file_prefix = SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script( 'divino-header-builder-edd-cart-customizer-preview-js', divino_HEADER_EDD_CART_URI . '/assets/js/' . $dir_name . '/customizer-preview' . $file_prefix . '.js', array( 'customize-preview', 'divino-customizer-preview-js' ), divino_THEME_VERSION, true );
	}

}

/**
 *  Kicking this off by creating the object of the class.
 */
new divino_Header_Edd_Cart_Loader();
