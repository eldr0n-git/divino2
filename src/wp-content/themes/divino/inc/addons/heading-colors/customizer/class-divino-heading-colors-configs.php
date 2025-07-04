<?php
/**
 * Heading Colors Options for divino theme.
 *
 * @package     divino
 * @link        https://www.brainstormforce.com
 * @since       divino 2.1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'divino_Heading_Colors_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Heading_Colors_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register divino Heading Colors Settings.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 2.1.4
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_section = 'section-colors-background';

			if ( class_exists( 'divino_Ext_Extension' ) && divino_Ext_Extension::is_active( 'colors-and-background' ) && ! divino_has_gcp_typo_preset_compatibility() ) {
				$_section = 'section-colors-body';
			}

			$_configs = array(

				// Option: Base Heading Color.
				array(
					'default'           => divino_get_option( 'heading-base-color' ),
					'type'              => 'control',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'transport'         => 'postMessage',
					'priority'          => 5,
					'name'              => divino_THEME_SETTINGS . '[heading-base-color]',
					'title'             => __( 'Heading (H1-H6)', 'divino' ),
					'section'           => $_section,
				),

				/**
				 * Option: Button Typography Heading
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[button-text-typography]',
					'default'   => divino_get_option( 'button-text-typography' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'is_font'   => true,
					'title'     => __( 'Font', 'divino' ),
					'section'   => 'section-buttons',
					'transport' => 'postMessage',
					'priority'  => 18.5,
					'divider'   => array( 'ast_class' => 'ast-bottom-divider' ),
				),

				/**
				 * Option: Outline Button Typography Heading
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[secondary-button-text-typography]',
					'default'   => divino_get_option( 'secondary-button-text-typography' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'is_font'   => true,
					'title'     => __( 'Font', 'divino' ),
					'section'   => 'section-buttons',
					'transport' => 'postMessage',
					'priority'  => 10,
					'divider'   => array( 'ast_class' => 'ast-bottom-divider' ),
					'context'   => divino_Builder_Helper::$design_tab,
				),

				/**
				 * Option: Button Font Family
				 */
				array(
					'name'      => 'font-family-button',
					'type'      => 'sub-control',
					'parent'    => divino_THEME_SETTINGS . '[button-text-typography]',
					'section'   => 'section-buttons',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'title'     => __( 'Font Family', 'divino' ),
					'default'   => divino_get_option( 'font-family-button' ),
					'connect'   => divino_THEME_SETTINGS . '[font-weight-button]',
					'priority'  => 1,
					'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Outline Button Font Family
				 */
				array(
					'name'      => 'secondary-font-family-button',
					'type'      => 'sub-control',
					'parent'    => divino_THEME_SETTINGS . '[secondary-button-text-typography]',
					'section'   => 'section-buttons',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'title'     => __( 'Font Family', 'divino' ),
					'default'   => divino_get_option( 'secondary-font-family-button' ),
					'connect'   => divino_THEME_SETTINGS . '[secondary-font-weight-button]',
					'priority'  => 1,
					'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Button Font Weight
				 */
				array(
					'name'              => 'font-weight-button',
					'type'              => 'sub-control',
					'parent'            => divino_THEME_SETTINGS . '[button-text-typography]',
					'section'           => 'section-buttons',
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'title'             => __( 'Font Weight', 'divino' ),
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => divino_get_option( 'font-weight-button' ),
					'connect'           => 'font-family-button',
					'priority'          => 2,
					'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Button Font Weight
				 */
				array(
					'name'              => 'secondary-font-weight-button',
					'type'              => 'sub-control',
					'parent'            => divino_THEME_SETTINGS . '[secondary-button-text-typography]',
					'section'           => 'section-buttons',
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'title'             => __( 'Font Weight', 'divino' ),
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => divino_get_option( 'secondary-font-weight-button' ),
					'connect'           => 'secondary-font-family-button',
					'priority'          => 2,
					'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Button Font Size
				 */

				array(
					'name'              => 'font-size-button',
					'parent'            => divino_THEME_SETTINGS . '[button-text-typography]',
					'type'              => 'sub-control',
					'control'           => 'ast-responsive-slider',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'section'           => 'section-buttons',
					'transport'         => 'postMessage',
					'title'             => __( 'Font Size', 'divino' ),
					'priority'          => 3,
					'default'           => divino_get_option( 'font-size-button' ),
					'suffix'            => array( 'px', 'em', 'vw', 'rem' ),
					'input_attrs'       => array(
						'px'  => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 200,
						),
						'em'  => array(
							'min'  => 0,
							'step' => 0.01,
							'max'  => 20,
						),
						'vw'  => array(
							'min'  => 0,
							'step' => 0.1,
							'max'  => 25,
						),
						'rem' => array(
							'min'  => 0,
							'step' => 0.1,
							'max'  => 20,
						),
					),
				),

				/**
				 * Option: Outline Button Font Size
				 */

				array(
					'name'              => 'secondary-font-size-button',
					'parent'            => divino_THEME_SETTINGS . '[secondary-button-text-typography]',
					'type'              => 'sub-control',
					'control'           => 'ast-responsive-slider',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'section'           => 'section-buttons',
					'transport'         => 'postMessage',
					'title'             => __( 'Font Size', 'divino' ),
					'priority'          => 3,
					'default'           => divino_get_option( 'secondary-font-size-button' ),
					'suffix'            => array( 'px', 'em', 'vw', 'rem' ),
					'input_attrs'       => array(
						'px'  => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 200,
						),
						'em'  => array(
							'min'  => 0,
							'step' => 0.01,
							'max'  => 20,
						),
						'vw'  => array(
							'min'  => 0,
							'step' => 0.1,
							'max'  => 25,
						),
						'rem' => array(
							'min'  => 0,
							'step' => 0.1,
							'max'  => 20,
						),
					),
				),

				/**
				 * Option: Button Font Extras
				 */
				array(
					'name'     => 'font-extras-button',
					'type'     => 'sub-control',
					'parent'   => divino_THEME_SETTINGS . '[button-text-typography]',
					'control'  => 'ast-font-extras',
					'section'  => 'section-buttons',
					'priority' => 4,
					'default'  => divino_get_option( 'font-extras-button' ),
				),

				/**
				 * Option: Outline Button Font Extras
				 */
				array(
					'name'     => 'secondary-font-extras-button',
					'type'     => 'sub-control',
					'parent'   => divino_THEME_SETTINGS . '[secondary-button-text-typography]',
					'control'  => 'ast-font-extras',
					'section'  => 'section-buttons',
					'priority' => 4,
					'default'  => divino_get_option( 'secondary-font-extras-button' ),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Heading_Colors_Configs();
