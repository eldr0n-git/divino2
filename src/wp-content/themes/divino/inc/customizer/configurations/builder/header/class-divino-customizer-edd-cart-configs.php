<?php
/**
 * divino Theme Customizer Configuration for EDD Cart Element.
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
 * Register Builder Customizer Configurations.
 *
 * @since 3.0.0
 */
class divino_Customizer_Edd_Cart_Configs extends divino_Customizer_Config_Base {
	/**
	 * Register Builder Customizer Configurations.
	 *
	 * @param Array                $configurations divino Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 3.0.0
	 * @return Array divino Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {
		if ( is_callable( 'divino_edd_cart_header_configuration' ) ) {
			$configurations = divino_edd_cart_header_configuration( $configurations );
		}
		return $configurations;
	}
}

/**
 * Kicking this off by creating object of this class.
 */

new divino_Customizer_Edd_Cart_Configs();
