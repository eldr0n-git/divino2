<?php
/**
 * Social Icon Styling Loader for Astra theme.
 *
 * @package     astra-builder
 * @link        https://wpastra.com/
 * @since       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Customizer Initialization
 *
 * @since 3.0.0s
 */
class divino_Header_Social_Icon_Component_Loader {
	/**
	 * Constructor
	 *
	 * @since 3.0.0s
	 */
	public function __construct() {
		add_action( 'customize_preview_init', array( $this, 'preview_scripts' ), 110 );
	}

	/**
	 * Customizer Preview
	 *
	 * @since 3.0.0s
	 */
	public function preview_scripts() {
		/**
		 * Load unminified if SCRIPT_DEBUG is true.
		 */
		/* Directory and Extension */
		$dir_name    = SCRIPT_DEBUG ? 'unminified' : 'minified';
		$file_prefix = SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script( 'astra-heading-social-icon-customizer-preview-js', divino_HEADER_SOCIAL_ICON_URI . '/assets/js/' . $dir_name . '/customizer-preview' . $file_prefix . '.js', array( 'customize-preview', 'astra-customizer-preview-js' ), divino_THEME_VERSION, true );

		// Localize variables for Astra Breakpoints JS.
		wp_localize_script(
			'astra-heading-social-icon-customizer-preview-js',
			'astraBuilderHeaderSocial',
			array(
				'tablet_break_point' => divino_get_tablet_breakpoint(),
				'mobile_break_point' => divino_get_mobile_breakpoint(),
				'component_limit'    => defined( 'divino_EXT_VER' ) ? divino_Builder_Helper::$component_limit : divino_Builder_Helper::$num_of_header_social_icons,
			)
		);
	}
}

/**
 *  Kicking this off by creating the object of the class.
 */
new divino_Header_Social_Icon_Component_Loader();
