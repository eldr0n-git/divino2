<?php
/**
 * WIDGET Styling Loader for divino theme.
 *
 * @package     divino Builder
 * @link        https://www.brainstormforce.com
 * @since       divino 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Customizer Initialization
 *
 * @since 3.0.0
 */
class divino_Footer_Widget_Component_Loader {
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
		wp_enqueue_script( 'divino-footer-widget-customizer-preview-js', divino_BUILDER_FOOTER_WIDGET_URI . '/assets/js/' . $dir_name . '/customizer-preview' . $file_prefix . '.js', array( 'customize-preview', 'divino-customizer-preview-js' ), divino_THEME_VERSION, true );

		// Localize variables for WIDGET JS.
		wp_localize_script(
			'divino-footer-widget-customizer-preview-js',
			'divinoBuilderWidgetData',
			array(
				'footer_widget_count' => defined( 'divino_EXT_VER' ) ? divino_Builder_Helper::$component_limit : divino_Builder_Helper::$num_of_footer_widgets,
				'tablet_break_point'  => divino_get_tablet_breakpoint(),
				'mobile_break_point'  => divino_get_mobile_breakpoint(),
				'is_flex_based_css'   => divino_Builder_Helper::apply_flex_based_css(),
				'has_block_editor'    => divino_has_widgets_block_editor(),
			)
		);
	}
}

/**
 *  Kicking this off by creating the object of the class.
 */
new divino_Footer_Widget_Component_Loader();
