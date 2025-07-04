<?php
/**
 * Mobile Trigger Header Configuration.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Header Trigger header builder Customizer Configurations.
 *
 * @since 4.5.2
 * @return array divino Customizer Configurations with updated configurations.
 */
function divino_header_mobile_trigger_configuration() {
	$_section = 'section-header-mobile-trigger';

	$_configs = array(

		/*
		* Header Builder section
		*/
		array(
			'name'     => 'section-header-mobile-trigger',
			'type'     => 'section',
			'priority' => 70,
			'title'    => __( 'Toggle Button', 'divino' ),
			'panel'    => 'panel-header-builder-group',
		),

		/**
		 * Option: Header Builder Tabs
		 */
		array(
			'name'        => $_section . '-ast-context-tabs',
			'section'     => $_section,
			'type'        => 'control',
			'control'     => 'ast-builder-header-control',
			'priority'    => 0,
			'description' => '',

		),

		/**
		 * Option: Header Html Editor.
		 */
		array(
			'name'              => divino_THEME_SETTINGS . '[header-trigger-icon]',
			'type'              => 'control',
			'control'           => 'ast-radio-image',
			'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_choices' ),
			'default'           => divino_get_option( 'header-trigger-icon' ),
			'title'             => __( 'Icons', 'divino' ),
			'section'           => $_section,
			'choices'           => array(
				'menu'  => array(
					'label' => __( 'Menu', 'divino' ),
					'path'  => divino_Builder_UI_Controller::fetch_svg_icon( 'mobile_menu' ),
				),
				'menu2' => array(
					'label' => __( 'Menu 2', 'divino' ),
					'path'  => divino_Builder_UI_Controller::fetch_svg_icon( 'mobile_menu2' ),
				),
				'menu3' => array(
					'label' => __( 'Menu 3', 'divino' ),
					'path'  => divino_Builder_UI_Controller::fetch_svg_icon( 'mobile_menu3' ),
				),
			),
			'transport'         => 'postMessage',
			'partial'           => array(
				'selector'        => '.ast-button-wrap',
				'render_callback' => array( 'divino_Builder_UI_Controller', 'render_mobile_trigger' ),
			),
			'priority'          => 10,
			'context'           => divino_Builder_Helper::$general_tab,
			'divider'           => array( 'ast_class' => 'ast-section-spacing ast-bottom-section-divider ast-inline' ),
			'alt_layout'        => true,
		),

		/**
		 * Option: Toggle Button Style
		 */
		array(
			'name'       => divino_THEME_SETTINGS . '[mobile-header-toggle-btn-style]',
			'default'    => divino_get_option( 'mobile-header-toggle-btn-style' ),
			'section'    => $_section,
			'title'      => __( 'Toggle Button Style', 'divino' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'priority'   => 11,
			'choices'    => array(
				'fill'    => __( 'Fill', 'divino' ),
				'outline' => __( 'Outline', 'divino' ),
				'minimal' => __( 'Minimal', 'divino' ),
			),
			'context'    => divino_Builder_Helper::$general_tab,
			'transport'  => 'postMessage',
			'partial'    => array(
				'selector'        => '.ast-button-wrap',
				'render_callback' => array( 'divino_Builder_UI_Controller', 'render_mobile_trigger' ),
			),
			'responsive' => false,
			'divider'    => array( 'ast_class' => 'ast-bottom-section-divider' ),
			'renderAs'   => 'text',
		),

		/**
		 * Option: Mobile Menu Label
		 */
		array(
			'name'      => divino_THEME_SETTINGS . '[mobile-header-menu-label]',
			'transport' => 'postMessage',
			'partial'   => array(
				'selector'        => '.ast-button-wrap',
				'render_callback' => array( 'divino_Builder_UI_Controller', 'render_mobile_trigger' ),
			),
			'default'   => divino_get_option( 'mobile-header-menu-label' ),
			'section'   => $_section,
			'priority'  => 20,
			'title'     => __( 'Menu Label', 'divino' ),
			'type'      => 'control',
			'control'   => 'text',
			'context'   => divino_Builder_Helper::$general_tab,
			'divider'   => array( 'ast_class' => 'ast-bottom-divider ast-top-divider' ),
		),

		/**
		 * Option: Toggle Button Color
		 */
		array(
			'name'              => divino_THEME_SETTINGS . '[mobile-header-toggle-btn-color]',
			'default'           => divino_get_option( 'mobile-header-toggle-btn-color' ),
			'type'              => 'control',
			'control'           => 'ast-color',
			'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'title'             => __( 'Icon Color', 'divino' ),
			'section'           => $_section,
			'transport'         => 'postMessage',
			'priority'          => 40,
			'context'           => divino_Builder_Helper::$design_tab,
			'divider'           => array( 'ast_class' => 'ast-section-spacing' ),

		),

		/**
		 * Option: Icon Size
		 */
		array(
			'name'        => divino_THEME_SETTINGS . '[mobile-header-toggle-icon-size]',
			'default'     => divino_get_option( 'mobile-header-toggle-icon-size' ),
			'type'        => 'control',
			'control'     => 'ast-slider',
			'section'     => $_section,
			'title'       => __( 'Icon Size', 'divino' ),
			'priority'    => 50,
			'suffix'      => 'px',
			'transport'   => 'postMessage',
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 100,
			),
			'context'     => divino_Builder_Helper::$design_tab,
			'divider'     => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		/**
		 * Option: Toggle Button Bg Color
		 */
		array(
			'name'              => divino_THEME_SETTINGS . '[mobile-header-toggle-btn-bg-color]',
			'default'           => divino_get_option( 'mobile-header-toggle-btn-bg-color' ),
			'type'              => 'control',
			'control'           => 'ast-color',
			'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'title'             => __( 'Background Color', 'divino' ),
			'section'           => $_section,
			'transport'         => 'postMessage',
			'priority'          => 40,
			'context'           => array(
				divino_Builder_Helper::$design_tab_config,
				array(
					'setting'  => divino_THEME_SETTINGS . '[mobile-header-toggle-btn-style]',
					'operator' => '==',
					'value'    => 'fill',
				),
			),
		),

		/**
		 * Option: Toggle Button Border Size
		 */
		array(
			'name'           => divino_THEME_SETTINGS . '[mobile-header-toggle-btn-border-size]',
			'default'        => divino_get_option( 'mobile-header-toggle-btn-border-size' ),
			'type'           => 'control',
			'section'        => $_section,
			'control'        => 'ast-border',
			'transport'      => 'postMessage',
			'linked_choices' => true,
			'priority'       => 60,
			'title'          => __( 'Border Width', 'divino' ),
			'choices'        => array(
				'top'    => __( 'Top', 'divino' ),
				'right'  => __( 'Right', 'divino' ),
				'bottom' => __( 'Bottom', 'divino' ),
				'left'   => __( 'Left', 'divino' ),
			),
			'context'        => array(
				divino_Builder_Helper::$design_tab_config,
				array(
					'setting'  => divino_THEME_SETTINGS . '[mobile-header-toggle-btn-style]',
					'operator' => '==',
					'value'    => 'outline',
				),
			),
			'divider'        => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		/**
		 * Option: Toggle Button Border Color
		 */
		array(
			'name'              => divino_THEME_SETTINGS . '[mobile-header-toggle-border-color]',
			'default'           => divino_get_option( 'mobile-header-toggle-border-color' ),
			'type'              => 'control',
			'control'           => 'ast-color',
			'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'title'             => __( 'Border Color', 'divino' ),
			'section'           => $_section,
			'transport'         => 'postMessage',
			'priority'          => 40,
			'context'           => array(
				divino_Builder_Helper::$design_tab_config,
				array(
					'setting'  => divino_THEME_SETTINGS . '[mobile-header-toggle-btn-style]',
					'operator' => '==',
					'value'    => 'outline',
				),
			),
		),

		/**
		 * Option: Button Radius Fields
		 */
		array(
			'name'              => divino_THEME_SETTINGS . '[mobile-header-toggle-border-radius-fields]',
			'default'           => divino_get_option( 'mobile-header-toggle-border-radius-fields' ),
			'type'              => 'control',
			'control'           => 'ast-responsive-spacing',
			'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
			'section'           => $_section,
			'title'             => __( 'Border Radius', 'divino' ),
			'linked_choices'    => true,
			'transport'         => 'postMessage',
			'unit_choices'      => array( 'px', 'em', '%' ),
			'choices'           => array(
				'top'    => __( 'Top', 'divino' ),
				'right'  => __( 'Right', 'divino' ),
				'bottom' => __( 'Bottom', 'divino' ),
				'left'   => __( 'Left', 'divino' ),
			),
			'priority'          => 50,
			'connected'         => false,
			'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
			'context'           => array(
				divino_Builder_Helper::$design_tab_config,
				array(
					'setting'  => divino_THEME_SETTINGS . '[mobile-header-toggle-btn-style]',
					'operator' => '!=',
					'value'    => 'minimal',
				),
			),
		),

		/**
		 * Option: Divider
		 */
		array(
			'name'     => divino_THEME_SETTINGS . '[' . $_section . '-margin-divider]',
			'section'  => $_section,
			'title'    => __( 'Spacing', 'divino' ),
			'type'     => 'control',
			'control'  => 'ast-heading',
			'priority' => 130,
			'settings' => array(),
			'context'  => divino_Builder_Helper::$design_tab,
			'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		/**
		 * Option: Margin Space
		 */
		array(
			'name'              => divino_THEME_SETTINGS . '[' . $_section . '-margin]',
			'default'           => divino_get_option( $_section . '-margin' ),
			'type'              => 'control',
			'transport'         => 'postMessage',
			'control'           => 'ast-responsive-spacing',
			'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
			'section'           => $_section,
			'priority'          => 130,
			'title'             => __( 'Margin', 'divino' ),
			'linked_choices'    => true,
			'unit_choices'      => array( 'px', 'em', '%' ),
			'choices'           => array(
				'top'    => __( 'Top', 'divino' ),
				'right'  => __( 'Right', 'divino' ),
				'bottom' => __( 'Bottom', 'divino' ),
				'left'   => __( 'Left', 'divino' ),
			),
			'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
			'context'           => divino_Builder_Helper::$design_tab,
		),
	);

	/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
	if ( defined( 'divino_EXT_VER' ) && divino_Ext_Extension::is_active( 'typography' ) ) {
		/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

		$typo_configs = array(

			// Option Group: Trigger Typography.
			array(
				'name'      => divino_THEME_SETTINGS . '[mobile-header-label-typography]',
				'default'   => divino_get_option( 'mobile-header-label-typography' ),
				'type'      => 'control',
				'control'   => 'ast-settings-group',
				'is_font'   => true,
				'title'     => __( 'Typography', 'divino' ),
				'section'   => $_section,
				'transport' => 'postMessage',
				'priority'  => 70,
				'context'   => array(
					divino_Builder_Helper::$design_tab_config,
					array(
						'setting'  => divino_THEME_SETTINGS . '[mobile-header-menu-label]',
						'operator' => '!=',
						'value'    => '',
					),
				),
			),

			// Option: Trigger Font Size.
			array(
				'name'        => 'mobile-header-label-font-size',
				'default'     => divino_get_option( 'mobile-header-label-font-size' ),
				'parent'      => divino_THEME_SETTINGS . '[mobile-header-label-typography]',
				'section'     => $_section,
				'type'        => 'sub-control',
				'priority'    => 23,
				'suffix'      => 'px',
				'title'       => __( 'Font Size', 'divino' ),
				'control'     => 'ast-slider',
				'transport'   => 'postMessage',
				'input_attrs' => array(
					'min' => 0,
					'max' => 200,
				),
				'units'       => array(
					'px'  => 'px',
					'em'  => 'em',
					'vw'  => 'vw',
					'rem' => 'rem',
				),
				'context'     => divino_Builder_Helper::$design_tab,
			),
		);

	} else {

		$typo_configs = array(

			// Option: Trigger Font Size.
			array(
				'name'        => divino_THEME_SETTINGS . '[mobile-header-label-font-size]',
				'default'     => divino_get_option( 'mobile-header-label-font-size' ),
				'section'     => $_section,
				'type'        => 'control',
				'priority'    => 70,
				'suffix'      => 'px',
				'title'       => __( 'Font Size', 'divino' ),
				'control'     => 'ast-slider',
				'transport'   => 'postMessage',
				'input_attrs' => array(
					'min' => 0,
					'max' => 200,
				),
				'units'       => array(
					'px'  => 'px',
					'em'  => 'em',
					'vw'  => 'vw',
					'rem' => 'rem',
				),
				'context'     => divino_Builder_Helper::$design_tab,
			),
		);
	}

	$_configs = array_merge( $_configs, $typo_configs );

	if ( divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
		array_map( 'divino_save_header_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
	add_action( 'init', 'divino_header_mobile_trigger_configuration' );
}
