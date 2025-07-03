<?php
/**
 * HTML Styling Loader for Astra theme.
 *
 * @package     Astra Builder
 * @link        https://www.brainstormforce.com
 * @since       Astra 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Customizer Initialization
 *
 * @since 3.0.0
 */
class divino_Footer_Html_Component_Loader {
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
		wp_enqueue_script( 'astra-footer-html-customizer-preview-js', divino_BUILDER_FOOTER_HTML_URI . '/assets/js/' . $dir_name . '/customizer-preview' . $file_prefix . '.js', array( 'customize-preview', 'astra-customizer-preview-js' ), divino_THEME_VERSION, true );

		// Localize variables for HTML JS.
		wp_localize_script(
			'astra-footer-html-customizer-preview-js',
			'AstraBuilderHTMLData',
			array(
				'component_limit'    => defined( 'divino_EXT_VER' ) ? divino_Builder_Helper::$component_limit : divino_Builder_Helper::$num_of_footer_html,
				'tablet_break_point' => divino_get_tablet_breakpoint(),
				'mobile_break_point' => divino_get_mobile_breakpoint(),
			)
		);
	}
}

/**
 *  Kicking this off by creating the object of the class.
 */
new divino_Footer_Html_Component_Loader();
