<?php
/**
 * Easy Digital Downloads Sidebar Options for our theme.
 *
 * @package     divino
 * @link        https://www.brainstormforce.com
 * @since       divino 1.5.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'divino_Edd_Sidebar_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Edd_Sidebar_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register divino Easy Digital Downloads Sidebar Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.5.5
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: General Sidebar Layout.
				 */

				array(
					'name'              => divino_THEME_SETTINGS . '[edd-general-sidebar-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => 'section-edd-general',
					'default'           => divino_get_option( 'edd-general-sidebar-layout' ),
					'priority'          => 6,
					'title'             => __( 'Sidebar Layout', 'divino' ),
					'choices'           => array(
						'default'       => array(
							'label' => __( 'Default', 'divino' ),
							'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'layout-default', false ) : '',
						),
						'no-sidebar'    => array(
							'label' => __( 'No Sidebar', 'divino' ),
							'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'no-sidebar', false ) : '',
						),
						'left-sidebar'  => array(
							'label' => __( 'Left Sidebar', 'divino' ),
							'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'left-sidebar', false ) : '',
						),
						'right-sidebar' => array(
							'label' => __( 'Right Sidebar', 'divino' ),
							'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'right-sidebar', false ) : '',
						),
					),
					'description'       => __( 'Sidebar will only apply when container layout is set to normal.', 'divino' ),
					'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: EDD Sidebar Style.
				 */
				array(
					'name'       => divino_THEME_SETTINGS . '[edd-sidebar-style]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'section-edd-general',
					'default'    => divino_get_option( 'edd-sidebar-style', 'default' ),
					'priority'   => 6,
					'title'      => __( 'Sidebar Style', 'divino' ),
					'choices'    => array(
						'default' => __( 'Default', 'divino' ),
						'unboxed' => __( 'Unboxed', 'divino' ),
						'boxed'   => __( 'Boxed', 'divino' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
					'divider'    => array( 'ast_class' => 'ast-top-divider ast-top-spacing' ),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Edd_Sidebar_Configs();
