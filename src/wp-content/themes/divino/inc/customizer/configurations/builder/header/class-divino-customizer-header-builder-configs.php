<?php
/**
 * divino Theme Customizer Configuration Builder.
 *
 * @package     divino-builder
 * @link        https://wpdivino.com/
 * @since       3.0.0
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'divino_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Register Builder Customizer Configurations.
 *
 * @since 3.0.0
 */
class divino_Customizer_Header_Builder_Configs extends divino_Customizer_Config_Base {
	/**
	 * Register Builder Customizer Configurations.
	 *
	 * @param Array                $configurations divino Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 3.0.0
	 * @return Array divino Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {
		return divino_header_header_builder_configuration( $configurations );
	}
}

/**
 * Kicking this off by creating object of this class.
 */
if ( class_exists( 'divino_Customizer_Config_Base' ) ) {
	new divino_Customizer_Header_Builder_Configs();
}
