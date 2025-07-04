<?php
/**
 * Primary Footer Styling Loader for divino theme.
 *
 * @package     divino Builder
 * @link        https://www.brainstormforce.com
 * @since       divino 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Primary Footer Initialization
 *
 * @since 3.0.0
 */
class divino_Primary_Footer_Component_Loader {
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
		wp_enqueue_script( 'divino-footer-primary-footer-customizer-preview-js', divino_BUILDER_FOOTER_PRIMARY_FOOTER_URI . '/assets/js/' . $dir_name . '/customizer-preview' . $file_prefix . '.js', array( 'customize-preview', 'divino-customizer-preview-js' ), divino_THEME_VERSION, true );

		// Localize variables for Footer JS.
		wp_localize_script(
			'divino-heading-primary-customizer-preview-js',
			'divinoBuilderPrimaryFooterData',
			array(
				'footer_content_width' => divino_get_option( 'site-content-width' ),
			)
		);
	}
}

/**
 *  Kicking this off by creating the object of the class.
 */
new divino_Primary_Footer_Component_Loader();
