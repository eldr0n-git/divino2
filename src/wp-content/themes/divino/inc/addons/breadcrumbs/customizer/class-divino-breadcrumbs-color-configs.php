<?php
/**
 * Colors - Breadcrumbs Options for theme.
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
if ( ! class_exists( 'divino_Breadcrumbs_Color_Configs' ) ) {

	/**
	 * Register Colors and Background - Breadcrumbs Options Customizer Configurations.
	 */
	class divino_Breadcrumbs_Color_Configs extends divino_Customizer_Config_Base {
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

				/**
				 * Option: Divider
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[breadcrumb-color-section-divider]',
					'section'  => 'section-breadcumb',
					'title'    => __( 'Colors', 'divino' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 72,
					'divider'  => array( 'ast_class' => 'ast-bottom-spacing' ),
					'context'  => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						true === divino_Builder_Helper::$is_header_footer_builder_active ?
							divino_Builder_Helper::$design_tab_config : divino_Builder_Helper::$general_tab_config,
					),
				),

				/*
				 * Breadcrumb Color
				 */
				array(
					'name'       => divino_THEME_SETTINGS . '[breadcrumb-bg-color]',
					'type'       => 'control',
					'default'    => divino_get_option( 'breadcrumb-bg-color' ),
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Background Color', 'divino' ),
					'responsive' => true,
					'rgba'       => true,
					'context'    => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						true === divino_Builder_Helper::$is_header_footer_builder_active ?
							divino_Builder_Helper::$design_tab_config : divino_Builder_Helper::$general_tab_config,
					),
					'priority'   => 72,
				),
				array(
					'name'       => divino_THEME_SETTINGS . '[breadcrumb-active-color-responsive]',
					'default'    => divino_get_option( 'breadcrumb-active-color-responsive' ),
					'type'       => 'control',
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Text Color', 'divino' ),
					'responsive' => true,
					'rgba'       => true,
					'context'    => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						true === divino_Builder_Helper::$is_header_footer_builder_active ?
							divino_Builder_Helper::$design_tab_config : divino_Builder_Helper::$general_tab_config,
					),
					'priority'   => 72,
				),
				array(
					'name'       => divino_THEME_SETTINGS . '[breadcrumb-separator-color]',
					'default'    => divino_get_option( 'breadcrumb-separator-color' ),
					'type'       => 'control',
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Separator Color', 'divino' ),
					'responsive' => true,
					'rgba'       => true,
					'context'    => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						true === divino_Builder_Helper::$is_header_footer_builder_active ?
							divino_Builder_Helper::$design_tab_config : divino_Builder_Helper::$general_tab_config,
					),
					'priority'   => 72,
				),

				array(
					'name'       => divino_THEME_SETTINGS . '[section-breadcrumb-link-color]',
					'default'    => divino_get_option( 'section-breadcrumb-color' ),
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Content Link Color', 'divino' ),
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'priority'   => 72,
					'context'    => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						true === divino_Builder_Helper::$is_header_footer_builder_active ?
							divino_Builder_Helper::$design_tab_config : divino_Builder_Helper::$general_tab_config,
					),
					'responsive' => true,
					'divider'    => array( 'ast_class' => 'ast-bottom-section-divider' ),
				),

				array(
					'name'       => 'breadcrumb-text-color-responsive',
					'default'    => divino_get_option( 'breadcrumb-text-color-responsive' ),
					'type'       => 'sub-control',
					'parent'     => divino_THEME_SETTINGS . '[section-breadcrumb-link-color]',
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'tab'        => __( 'Normal', 'divino' ),
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Normal', 'divino' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 15,
				),

				array(
					'name'       => 'breadcrumb-hover-color-responsive',
					'default'    => divino_get_option( 'breadcrumb-hover-color-responsive' ),
					'type'       => 'sub-control',
					'parent'     => divino_THEME_SETTINGS . '[section-breadcrumb-link-color]',
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'tab'        => __( 'Hover', 'divino' ),
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Hover', 'divino' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 20,
				),
			);

			if ( false === divino_Builder_Helper::$is_header_footer_builder_active ) {
				array_push(
					$_configs,
					/**
					 * Option: Divider
					 * Option: breadcrumb color Section divider
					 */
					array(
						'name'     => divino_THEME_SETTINGS . '[section-breadcrumb-color-divider]',
						'type'     => 'control',
						'control'  => 'ast-heading',
						'section'  => 'section-breadcrumb',
						'title'    => __( 'Colors', 'divino' ),
						'priority' => 71,
						'settings' => array(),
						'context'  => array(
							array(
								'setting'  => divino_THEME_SETTINGS . '[breadcrumb-position]',
								'operator' => '!=',
								'value'    => 'none',
							),
							divino_Builder_Helper::$general_tab_config,
						),
					)
				);
			}
			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new divino_Breadcrumbs_Color_Configs();
