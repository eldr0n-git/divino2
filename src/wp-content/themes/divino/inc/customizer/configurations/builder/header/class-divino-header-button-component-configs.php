<?php
/**
 * [Header] options for astra theme.
 *
 * @package     Astra Header Footer Builder
 * @link        https://www.brainstormforce.com
 * @since       3.0.0
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'divino_Customizer_Config_Base' ) ) {

	/**
	 * Register below header Configurations.
	 */
	class divino_Header_Button_Component_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register Button control for Header/Footer Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.0.0
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			return divino_header_button_configuration( $configurations );
		}
	}

	new divino_Header_Button_Component_Configs();
}
