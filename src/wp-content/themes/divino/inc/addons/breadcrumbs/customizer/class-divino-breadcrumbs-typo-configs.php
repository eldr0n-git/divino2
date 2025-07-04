<?php
/**
 * Typography - Breadcrumbs Options for theme.
 *
 * @package     divino
 * @link        https://www.brainstormforce.com
 * @since       divino 1.7.0
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'divino_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Customizer Sanitizes
 *
 * @since 1.7.0
 */
if ( ! class_exists( 'divino_Breadcrumbs_Typo_Configs' ) ) {

	/**
	 * Register Colors and Background - Breadcrumbs Options Customizer Configurations.
	 */
	class divino_Breadcrumbs_Typo_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register Colors and Background - Breadcrumbs Options Customizer Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.7.0
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/*
				 * Breadcrumb Typography
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[section-breadcrumb-typo]',
					'default'   => divino_get_option( 'section-breadcrumb-typo' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'is_font'   => true,
					'title'     => esc_html__( 'Content Font', 'divino' ),
					'section'   => 'section-breadcrumb',
					'transport' => 'postMessage',
					'priority'  => 71,
					'context'   => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						true === divino_Builder_Helper::$is_header_footer_builder_active ?
							divino_Builder_Helper::$design_tab_config : divino_Builder_Helper::$general_tab_config,
					),
					'divider'   => array( 'ast_class' => 'ast-section-spacing ast-bottom-section-divider' ),
				),

				/**
				 * Option: Font Family
				 */
				array(
					'name'      => 'breadcrumb-font-family',
					'default'   => divino_get_option( 'breadcrumb-font-family' ),
					'type'      => 'sub-control',
					'parent'    => divino_THEME_SETTINGS . '[section-breadcrumb-typo]',
					'section'   => 'section-breadcrumb',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'title'     => esc_html__( 'Font Family', 'divino' ),
					'connect'   => 'breadcrumb-font-weight',
					'priority'  => 5,
					'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Font Weight
				 */
				array(
					'name'              => 'breadcrumb-font-weight',
					'control'           => 'ast-font',
					'type'              => 'sub-control',
					'parent'            => divino_THEME_SETTINGS . '[section-breadcrumb-typo]',
					'section'           => 'section-breadcrumb',
					'font_type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => divino_get_option( 'breadcrumb-font-weight' ),
					'title'             => esc_html__( 'Font Weight', 'divino' ),
					'connect'           => 'breadcrumb-font-family',
					'priority'          => 10,
					'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Font Size
				 */

				array(
					'name'              => 'breadcrumb-font-size',
					'parent'            => divino_THEME_SETTINGS . '[section-breadcrumb-typo]',
					'type'              => 'sub-control',
					'control'           => 'ast-responsive-slider',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'section'           => 'section-breadcrumb',
					'transport'         => 'postMessage',
					'title'             => esc_html__( 'Font Size', 'divino' ),
					'priority'          => 10,
					'default'           => divino_get_option( 'breadcrumb-font-size' ),
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
				 * Option: Breadcrumb Content Font Extras
				 */
				array(
					'name'     => 'breadcrumb-font-extras',
					'type'     => 'sub-control',
					'parent'   => divino_THEME_SETTINGS . '[section-breadcrumb-typo]',
					'control'  => 'ast-font-extras',
					'section'  => 'section-breadcrumb',
					'priority' => 25,
					'default'  => divino_get_option( 'breadcrumb-font-extras' ),
					'title'    => esc_html__( 'Line Height', 'divino' ),
				),

			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new divino_Breadcrumbs_Typo_Configs();
