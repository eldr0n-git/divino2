<?php
/**
 * General Options for divino Theme.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'divino_Site_Container_Layout_Configs' ) ) {

	/**
	 * Register divino Site Container Layout Customizer Configurations.
	 */
	class divino_Site_Container_Layout_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register divino Site Container Layout Customizer Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_section = 'section-colors-background';

			if ( class_exists( 'divino_Ext_Extension' ) && divino_Ext_Extension::is_active( 'colors-and-background' ) && ! divino_has_gcp_typo_preset_compatibility() ) {
				$_section = 'section-colors-body';
			}

			$_configs = array(

				/**
				 * Option: Global Revamped Container Layouts.
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[ast-site-content-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => 'section-container-layout',
					'default'           => divino_get_option( 'ast-site-content-layout', 'normal-width-container' ),
					'priority'          => 9,
					'title'             => __( 'Container Layout', 'divino' ),
					'transport'         => 'refresh',
					'choices'           => array(
						'normal-width-container' => array(
							'label' => __( 'Normal', 'divino' ),
							'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'normal-width-container', false ) : '',
						),
						'narrow-width-container' => array(
							'label' => __( 'Narrow', 'divino' ),
							'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'narrow-width-container', false ) : '',
						),
						'full-width-container'   => array(
							'label' => __( 'Full Width', 'divino' ),
							'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'full-width-container', false ) : '',
						),
					),
					'divider'           => array( 'ast_class' => 'ast-section-spacing ast-bottom-divider' ),
				),

				/**
				 * Option: Global Content Style.
				 */
				array(
					'name'        => divino_THEME_SETTINGS . '[site-content-style]',
					'type'        => 'control',
					'control'     => 'ast-selector',
					'section'     => 'section-container-layout',
					'default'     => divino_get_option( 'site-content-style', 'boxed' ),
					'priority'    => 9,
					'description' => __( 'Container style will apply only when layout is set to either normal or narrow.', 'divino' ),
					'title'       => __( 'Container Style', 'divino' ),
					'choices'     => array(
						'unboxed' => __( 'Unboxed', 'divino' ),
						'boxed'   => __( 'Boxed', 'divino' ),
					),
					'responsive'  => false,
					'renderAs'    => 'text',
				),

				/**
				 * Option: Theme color heading
				 */
				array(
					'name'        => divino_THEME_SETTINGS . '[surface-colors-title]',
					'section'     => $_section,
					'title'       => __( 'Surface Color', 'divino' ),
					'type'        => 'control',
					'control'     => 'ast-group-title',
					'priority'    => 25,
					'responsive'  => true,
					'settings'    => array(),
					'input_attrs' => array(
						'reset_linked_controls' => array(
							'site-layout-outside-bg-obj-responsive',
							'content-bg-obj-responsive',
						),
					),
					'divider'     => array( 'ast_class' => 'ast-top-section-spacing' ),
				),

				/**
				 * Option: Body Background
				 */
				array(
					'name'        => divino_THEME_SETTINGS . '[site-layout-outside-bg-obj-responsive]',
					'type'        => 'control',
					'control'     => 'ast-responsive-background',
					'default'     => divino_get_option( 'site-layout-outside-bg-obj-responsive' ),
					'section'     => $_section,
					'transport'   => 'postMessage',
					'priority'    => 25,
					'input_attrs' => array(
						'ignore_responsive_btns' => true,
					),
					'title'       => __( 'Site Background', 'divino' ),
				),
			);

			if ( divino_has_gcp_typo_preset_compatibility() ) {

				$_configs[] = array(
					'name'        => divino_THEME_SETTINGS . '[content-bg-obj-responsive]',
					'default'     => divino_get_option( 'content-bg-obj-responsive' ),
					'type'        => 'control',
					'control'     => 'ast-responsive-background',
					'section'     => $_section,
					'title'       => __( 'Content Background', 'divino' ),
					'transport'   => 'postMessage',
					'input_attrs' => array(
						'ignore_responsive_btns' => true,
					),
					'priority'    => 25,
					'divider'     => defined( 'divino_EXT_VER' ) && divino_Ext_Extension::is_active( 'colors-and-background' ) ? array() : array(),
				);
			}

			$configurations = array_merge( $configurations, $_configs );

			// Learn More link if divino Pro is not activated.
			if ( divino_showcase_upgrade_notices() ) {
				$config = array(
					array(
						'name'     => divino_THEME_SETTINGS . '[ast-site-layout-button-link]',
						'type'     => 'control',
						'control'  => 'ast-upgrade',
						'campaign' => 'global',
						'choices'  => array(
							'one'   => array(
								'title' => __( 'Full Width layout', 'divino' ),
							),
							'two'   => array(
								'title' => __( 'Padded layout', 'divino' ),
							),
							'three' => array(
								'title' => __( 'Fluid layout', 'divino' ),
							),
							'four'  => array(
								'title' => __( 'Container spacings', 'divino' ),
							),
						),
						'section'  => 'section-container-layout',
						'default'  => '',
						'priority' => 999,
						'title'    => __( 'Use containers to their maximum potential with divino Pro', 'divino' ),
						'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
					),
				);

				$configurations = array_merge( $configurations, $config );
			}

			return $configurations;
		}
	}
}

new divino_Site_Container_Layout_Configs();
