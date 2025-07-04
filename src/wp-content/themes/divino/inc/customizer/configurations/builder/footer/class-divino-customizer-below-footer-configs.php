<?php
/**
 * divino Theme Customizer Configuration Below footer.
 *
 * @package     divino-builder
 * @link        https://wpdivino.com/
 * @since       3.0.0
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'divino_Customizer_Config_Base' ) ) {

	/**
	 * Register Below footer Customizer Configurations.
	 *
	 * @since 3.0.0
	 */
	class divino_Customizer_Below_Footer_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register Builder Below footer Customizer Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.0.0
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			$_configs = divino_below_footer_configuration();
			return array_merge( $configurations, $_configs );
		}
	}

	/**
	 * Kicking this off by creating object of this class.
	 */
	new divino_Customizer_Below_Footer_Configs();
}
