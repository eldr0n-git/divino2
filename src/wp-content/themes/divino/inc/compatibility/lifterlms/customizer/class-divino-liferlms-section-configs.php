<?php
/**
 * Register customizer panels & sections.
 *
 * @package     divino\
 * @link        https://www.brainstormforce.com
 * @since       divino 1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'divino_Liferlms_Section_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Liferlms_Section_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register LearnDash Container settings.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				array(
					'name'     => 'section-lifterlms',
					'type'     => 'section',
					'priority' => 65,
					'title'    => __( 'LifterLMS', 'divino' ),
				),

				/**
				 * General Section
				 */
				array(
					'name'     => 'section-lifterlms-general',
					'type'     => 'section',
					'title'    => __( 'General', 'divino' ),
					'section'  => 'section-lifterlms',
					'priority' => 0,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Liferlms_Section_Configs();
