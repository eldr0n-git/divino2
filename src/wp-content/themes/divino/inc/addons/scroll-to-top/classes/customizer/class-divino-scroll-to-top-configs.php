<?php
/**
 * Scroll To Top Options for our theme.
 *
 * @package     divino
 * @link        https://www.brainstormforce.com
 * @since       4.0.0
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
 * Register Scroll To Top Customizer Configurations.
 */
class divino_Scroll_To_Top_Configs extends divino_Customizer_Config_Base {
	/**
	 * Register Scroll To Top Customizer Configurations.
	 *
	 * @param Array                $configurations divino Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 4.0.0
	 * @return Array divino Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		$_configs = array(

			/**
			 * Option: Enable Scroll To Top
			 */
			array(
				'name'     => divino_THEME_SETTINGS . '[scroll-to-top-enable]',
				'default'  => divino_get_option( 'scroll-to-top-enable' ),
				'type'     => 'control',
				'section'  => 'section-scroll-to-top',
				'title'    => __( 'Enable Scroll to Top', 'divino' ),
				'priority' => 1,
				'control'  => 'ast-toggle-control',
				'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
			),

			/**
			 * Option: Scroll to Top Display On
			 */
			array(
				'name'       => divino_THEME_SETTINGS . '[scroll-to-top-on-devices]',
				'default'    => divino_get_option( 'scroll-to-top-on-devices' ),
				'type'       => 'control',
				'control'    => 'ast-selector',
				'section'    => 'section-scroll-to-top',
				'priority'   => 10,
				'title'      => __( 'Display On', 'divino' ),
				'choices'    => array(
					'desktop' => __( 'Desktop', 'divino' ),
					'mobile'  => __( 'Mobile', 'divino' ),
					'both'    => __( 'Desktop + Mobile', 'divino' ),
				),
				'renderAs'   => 'text',
				'responsive' => false,
				'divider'    => array( 'ast_class' => 'ast-top-divider ast-bottom-divider' ),
				'context'    => array(
					'relation' => 'AND',
					divino_Builder_Helper::$general_tab_config,
					array(
						'setting'  => divino_THEME_SETTINGS . '[scroll-to-top-enable]',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			/**
			 * Option: Scroll to Top Position
			 */
			array(
				'name'       => divino_THEME_SETTINGS . '[scroll-to-top-icon-position]',
				'default'    => divino_get_option( 'scroll-to-top-icon-position' ),
				'type'       => 'control',
				'control'    => 'ast-selector',
				'transport'  => 'postMessage',
				'section'    => 'section-scroll-to-top',
				'title'      => __( 'Position', 'divino' ),
				'choices'    => array(
					'left'  => __( 'Left', 'divino' ),
					'right' => __( 'Right', 'divino' ),
				),
				'priority'   => 11,
				'responsive' => false,
				'renderAs'   => 'text',
				'divider'    => array( 'ast_class' => 'ast-bottom-divider' ),
				'context'    => array(
					'relation' => 'AND',
					divino_Builder_Helper::$general_tab_config,
					array(
						'setting'  => divino_THEME_SETTINGS . '[scroll-to-top-enable]',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			/**
			 * Option: Scroll To Top Icon Size
			 */
			array(
				'name'      => divino_THEME_SETTINGS . '[scroll-to-top-icon-size]',
				'default'   => divino_get_option( 'scroll-to-top-icon-size' ),
				'type'      => 'control',
				'control'   => 'ast-slider',
				'transport' => 'postMessage',
				'section'   => 'section-scroll-to-top',
				'title'     => __( 'Icon Size', 'divino' ),
				'suffix'    => 'px',
				'priority'  => 12,
				'context'   => array(
					'relation' => 'AND',
					divino_Builder_Helper::$general_tab_config,
					array(
						'setting'  => divino_THEME_SETTINGS . '[scroll-to-top-enable]',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			array(
				'name'     => divino_THEME_SETTINGS . '[scroll-on-top-color-group]',
				'default'  => divino_get_option( 'scroll-on-top-color-group' ),
				'type'     => 'control',
				'control'  => 'ast-color-group',
				'title'    => __( 'Icon Color', 'divino' ),
				'section'  => 'section-scroll-to-top',
				'context'  => array(
					'relation' => 'AND',
					true === divino_Builder_Helper::$is_header_footer_builder_active ? divino_Builder_Helper::$design_tab_config : divino_Builder_Helper::$general_tab_config,
					array(
						'setting'  => divino_THEME_SETTINGS . '[scroll-to-top-enable]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'priority' => 1,
				'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
			),

			array(
				'name'      => divino_THEME_SETTINGS . '[scroll-on-top-bg-color-group]',
				'default'   => divino_get_option( 'scroll-on-top-bg-color-group' ),
				'type'      => 'control',
				'control'   => 'ast-color-group',
				'title'     => __( 'Background Color', 'divino' ),
				'section'   => 'section-scroll-to-top',
				'transport' => 'postMessage',
				'context'   => array(
					'relation' => 'AND',
					true === divino_Builder_Helper::$is_header_footer_builder_active ? divino_Builder_Helper::$design_tab_config : divino_Builder_Helper::$general_tab_config,
					array(
						'setting'  => divino_THEME_SETTINGS . '[scroll-to-top-enable]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'priority'  => 1,
			),

			/**
			 * Option: Scroll To Top Radius
			 */
			array(
				'name'           => divino_THEME_SETTINGS . '[scroll-to-top-icon-radius-fields]',
				'default'        => divino_get_option( 'scroll-to-top-icon-radius-fields' ),
				'type'           => 'control',
				'control'        => 'ast-responsive-spacing',
				'transport'      => 'postMessage',
				'section'        => 'section-scroll-to-top',
				'title'          => __( 'Border Radius', 'divino' ),
				'suffix'         => 'px',
				'priority'       => 1,
				'divider'        => array( 'ast_class' => 'ast-top-section-divider' ),
				'context'        => array(
					'relation' => 'AND',
					true === divino_Builder_Helper::$is_header_footer_builder_active ? divino_Builder_Helper::$design_tab_config : divino_Builder_Helper::$general_tab_config,
					array(
						'setting'  => divino_THEME_SETTINGS . '[scroll-to-top-enable]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'linked_choices' => true,
				'unit_choices'   => array( 'px', 'em', '%' ),
				'choices'        => array(
					'top'    => __( 'Top', 'divino' ),
					'right'  => __( 'Right', 'divino' ),
					'bottom' => __( 'Bottom', 'divino' ),
					'left'   => __( 'Left', 'divino' ),
				),
				'connected'      => false,
			),

			/**
			 * Option: Icon Color
			 */
			array(
				'name'              => 'scroll-to-top-icon-color',
				'default'           => divino_get_option( 'scroll-to-top-icon-color' ),
				'type'              => 'sub-control',
				'priority'          => 1,
				'parent'            => divino_THEME_SETTINGS . '[scroll-on-top-color-group]',
				'section'           => 'section-scroll-to-top',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'transport'         => 'postMessage',
				'title'             => __( 'Color', 'divino' ),
			),

			/**
			 * Option: Icon Background Color
			 */
			array(
				'name'              => 'scroll-to-top-icon-bg-color',
				'default'           => divino_get_option( 'scroll-to-top-icon-bg-color' ),
				'type'              => 'sub-control',
				'priority'          => 1,
				'parent'            => divino_THEME_SETTINGS . '[scroll-on-top-bg-color-group]',
				'section'           => 'section-scroll-to-top',
				'transport'         => 'postMessage',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'title'             => __( 'Color', 'divino' ),
			),

			/**
			 * Option: Icon Hover Color
			 */
			array(
				'name'              => 'scroll-to-top-icon-h-color',
				'default'           => divino_get_option( 'scroll-to-top-icon-h-color' ),
				'type'              => 'sub-control',
				'priority'          => 1,
				'parent'            => divino_THEME_SETTINGS . '[scroll-on-top-color-group]',
				'section'           => 'section-scroll-to-top',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'transport'         => 'postMessage',
				'title'             => __( 'Hover Color', 'divino' ),
			),

			/**
			 * Option: Link Hover Background Color
			 */
			array(
				'name'              => 'scroll-to-top-icon-h-bg-color',
				'default'           => divino_get_option( 'scroll-to-top-icon-h-bg-color' ),
				'type'              => 'sub-control',
				'priority'          => 1,
				'parent'            => divino_THEME_SETTINGS . '[scroll-on-top-bg-color-group]',
				'section'           => 'section-scroll-to-top',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'transport'         => 'postMessage',
				'title'             => __( 'Hover Color', 'divino' ),
			),
		);

		if ( true === divino_Builder_Helper::$is_header_footer_builder_active ) {
			$_configs[] = array(
				'name'        => 'section-scroll-to-top-ast-context-tabs',
				'section'     => 'section-scroll-to-top',
				'type'        => 'control',
				'control'     => 'ast-builder-header-control',
				'priority'    => 0,
				'description' => '',
			);
			$_configs[] = array(
				'name'     => divino_THEME_SETTINGS . '[enable-scroll-to-top-notice]',
				'type'     => 'control',
				'control'  => 'ast-description',
				'section'  => 'section-scroll-to-top',
				'priority' => 1,
				'label'    => '',
				'help'     => __( 'Note: To get design settings in action make sure to enable Scroll to Top.', 'divino' ),
				'context'  => array(
					'relation' => 'AND',
					divino_Builder_Helper::$design_tab_config,
					array(
						'setting'  => divino_THEME_SETTINGS . '[scroll-to-top-enable]',
						'operator' => '!=',
						'value'    => true,
					),
				),
			);
		}

		return array_merge( $configurations, $_configs );
	}
}

/** Creating instance for getting customizer configs. */
new divino_Scroll_To_Top_Configs();
