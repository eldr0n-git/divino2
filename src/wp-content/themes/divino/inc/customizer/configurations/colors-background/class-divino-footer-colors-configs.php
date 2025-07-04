<?php
/**
 * Styling Options for divino Theme.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       1.4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'divino_Footer_Colors_Configs' ) ) {

	/**
	 * Register Footer Color Configurations.
	 */
	class divino_Footer_Colors_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register Footer Color Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			$_configs = array(

				/**
				 * Option: Color
				 */
				array(
					'name'     => 'footer-color',
					'type'     => 'sub-control',
					'priority' => 5,
					'parent'   => divino_THEME_SETTINGS . '[footer-bar-content-group]',
					'section'  => 'section-footer-small',
					'control'  => 'ast-color',
					'title'    => __( 'Text Color', 'divino' ),
					'default'  => divino_get_option( 'footer-color' ),
				),

				/**
				 * Option: Link Color
				 */
				array(
					'name'     => 'footer-link-color',
					'type'     => 'sub-control',
					'priority' => 6,
					'parent'   => divino_THEME_SETTINGS . '[footer-bar-link-color-group]',
					'section'  => 'section-footer-small',
					'control'  => 'ast-color',
					'default'  => divino_get_option( 'footer-link-color' ),
					'title'    => __( 'Normal', 'divino' ),
				),

				/**
				 * Option: Link Hover Color
				 */
				array(
					'name'     => 'footer-link-h-color',
					'type'     => 'sub-control',
					'priority' => 5,
					'parent'   => divino_THEME_SETTINGS . '[footer-bar-link-color-group]',
					'section'  => 'section-footer-small',
					'control'  => 'ast-color',
					'title'    => __( 'Hover', 'divino' ),
					'default'  => divino_get_option( 'section-footer-small' ),
				),

				/**
				 * Option: Footer Background
				 */
				array(
					'name'              => 'footer-bg-obj',
					'type'              => 'sub-control',
					'priority'          => 7,
					'parent'            => divino_THEME_SETTINGS . '[footer-bar-background-group]',
					'section'           => 'section-footer-small',
					'transport'         => 'postMessage',
					'control'           => 'ast-background',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_background_obj' ),
					'default'           => divino_get_option( 'footer-bg-obj' ),
					'label'             => __( 'Background', 'divino' ),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Footer_Colors_Configs();
