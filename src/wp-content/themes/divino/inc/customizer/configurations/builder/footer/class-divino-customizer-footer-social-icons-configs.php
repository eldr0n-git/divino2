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

if ( ! class_exists( 'divino_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Social Icons Customizer Configurations.
 *
 * @since 3.0.0
 */
class divino_Customizer_Footer_Social_Icons_Configs extends divino_Customizer_Config_Base {
	/**
	 * Social Icons Customizer Configurations.
	 *
	 * @param Array                $configurations divino Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 3.0.0
	 * @return Array divino Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {
		return divino_social_footer_configuration( $configurations );
	}
}

/**
 * Kicking this off by creating object of this class.
 */

new divino_Customizer_Footer_Social_Icons_Configs();
