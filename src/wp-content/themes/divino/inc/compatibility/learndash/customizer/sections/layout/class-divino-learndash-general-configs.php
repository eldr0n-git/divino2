<?php
/**
 * LifterLMS General Options for our theme.
 *
 * @package     divino
 * @link        https://www.brainstormforce.com
 * @since       1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'divino_Learndash_General_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Learndash_General_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register LearnDash General Layout settings.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Display Serial Number
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[learndash-lesson-serial-number]',
					'section'  => 'section-leandash-general',
					'type'     => 'control',
					'control'  => 'ast-toggle-control',
					'default'  => divino_get_option( 'learndash-lesson-serial-number' ),
					'title'    => __( 'Display Serial Number', 'divino' ),
					'priority' => 25,
					'divider'  => array(
						'ast_class' => 'ast-top-divider',
						'ast_title' => __( 'Course Content Table', 'divino' ),
					),
				),

				/**
				 * Option: Differentiate Rows
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[learndash-differentiate-rows]',
					'default'  => divino_get_option( 'learndash-differentiate-rows' ),
					'type'     => 'control',
					'control'  => 'ast-toggle-control',
					'section'  => 'section-leandash-general',
					'title'    => __( 'Differentiate Rows', 'divino' ),
					'priority' => 30,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Learndash_General_Configs();
