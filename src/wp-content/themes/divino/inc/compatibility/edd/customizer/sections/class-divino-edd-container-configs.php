<?php
/**
 * Easy Digital Downloads Container Options for divino theme.
 *
 * @package     divino
 * @link        https://www.brainstormforce.com
 * @since       divino 1.5.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'divino_Edd_Container_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Edd_Container_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register divino-Easy Digital Downloads Shop Container Settings.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.5.5
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Revamped Container Layout.
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[edd-ast-content-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => 'section-edd-general',
					'default'           => divino_get_option( 'edd-ast-content-layout' ),
					'priority'          => 5,
					'title'             => __( 'Container Layout', 'divino' ),
					'choices'           => array(
						'default'                => array(
							'label' => __( 'Default', 'divino' ),
							'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'layout-default', false ) : '',
						),
						'normal-width-container' => array(
							'label' => __( 'Normal', 'divino' ),
							'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'normal-width-container', false ) : '',
						),
						'full-width-container'   => array(
							'label' => __( 'Full Width', 'divino' ),
							'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'full-width-container', false ) : '',
						),
					),
					'divider'           => array( 'ast_class' => 'ast-bottom-spacing ast-bottom-divider' ),
				),

				/**
				 * Option: Content Style Option.
				 */
				array(
					'name'        => divino_THEME_SETTINGS . '[edd-content-style]',
					'type'        => 'control',
					'control'     => 'ast-selector',
					'section'     => 'section-edd-general',
					'default'     => divino_get_option( 'edd-content-style', 'default' ),
					'description' => __( 'Container style will apply only when layout is set to either normal or narrow.', 'divino' ),
					'priority'    => 5,
					'title'       => __( 'Container Style', 'divino' ),
					'choices'     => array(
						'default' => __( 'Default', 'divino' ),
						'unboxed' => __( 'Unboxed', 'divino' ),
						'boxed'   => __( 'Boxed', 'divino' ),
					),
					'renderAs'    => 'text',
					'responsive'  => false,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Edd_Container_Configs();
