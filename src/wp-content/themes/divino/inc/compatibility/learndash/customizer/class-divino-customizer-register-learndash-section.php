<?php
/**
 * Register customizer panels & sections.
 *
 * @package     Astra
 * @link        https://www.brainstormforce.com
 * @since       1.2.0
 * @since       1.4.6 Chnaged to using divino_Customizer API
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'divino_Customizer_Register_Learndash_Section' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Customizer_Register_Learndash_Section extends divino_Customizer_Config_Base {
		/**
		 * Register Panels and Sections for Customizer.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.2.0
		 * @since 1.4.6 Chnaged to using divino_Customizer API
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$configs = array(
				array(
					'type'     => 'section',
					'name'     => 'section-learndash',
					'priority' => 65,
					'title'    => __( 'LearnDash', 'astra' ),

				),

				array(
					'name'     => 'section-leandash-general',
					'title'    => __( 'General', 'astra' ),
					'type'     => 'section',
					'section'  => 'section-learndash',
					'priority' => 10,
				),

			);

			return array_merge( $configurations, $configs );
		}
	}
}

new divino_Customizer_Register_Learndash_Section();
