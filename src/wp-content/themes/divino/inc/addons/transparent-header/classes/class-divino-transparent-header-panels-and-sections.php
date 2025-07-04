<?php
/**
 * Transparent Header Options for our theme.
 *
 * @package     divino Addon
 * @link        https://www.brainstormforce.com
 * @since       divino 1.4.3
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'divino_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Customizer Sanitizes
 *
 * @since 1.4.3
 */
if ( ! class_exists( 'divino_Transparent_Header_Panels_And_Sections' ) ) {

	/**
	 * Register Transparent Header Customizer Configurations.
	 */
	class divino_Transparent_Header_Panels_And_Sections extends divino_Customizer_Config_Base {
		/**
		 * Register Transparent Header Customizer Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				array(
					'name'     => 'section-transparent-header',
					'title'    => esc_html__( 'Transparent Header', 'divino' ),
					'panel'    => true === divino_Builder_Helper::$is_header_footer_builder_active ? 'panel-header-builder-group' : 'panel-header-group',
					'type'     => 'section',
					'priority' => 33,
				),

				array(
					'name'     => 'section-colors-header-group',
					'type'     => 'section',
					'title'    => esc_html__( 'Header', 'divino' ),
					'panel'    => 'panel-colors-background',
					'priority' => 20,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new divino_Transparent_Header_Panels_And_Sections();
