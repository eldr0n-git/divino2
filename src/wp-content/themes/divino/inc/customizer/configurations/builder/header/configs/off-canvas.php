<?php
/**
 * Off canvas Header Configuration.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register off-canvas header builder Customizer Configurations.
 *
 * @since 4.5.2
 * @return array divino Customizer Configurations with updated configurations.
 */
function divino_header_off_canvas_configuration() {
	$_section = 'section-popup-header-builder';

	$_configs = array(

		// Section: Off-Canvas.
		array(
			'name'     => $_section,
			'type'     => 'section',
			'title'    => __( 'Off-Canvas', 'divino' ),
			'panel'    => 'panel-header-builder-group',
			'priority' => 30,
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
		 * Option: Mobile Header Type.
		 */
		array(
			'name'       => divino_THEME_SETTINGS . '[mobile-header-type]',
			'default'    => divino_get_option( 'mobile-header-type' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'priority'   => 25,
			'title'      => __( 'Header Type', 'divino' ),
			'choices'    => array(
				'off-canvas' => __( 'Flyout', 'divino' ),
				'full-width' => __( 'Full-Screen', 'divino' ),
				'dropdown'   => __( 'Dropdown', 'divino' ),
			),
			'transport'  => 'refresh',
			'context'    => divino_Builder_Helper::$general_tab,
			'renderAs'   => 'text',
			'responsive' => false,
		),

		/**
		 * Option: Off-Canvas Move Body.
		 */
		array(
			'name'        => divino_THEME_SETTINGS . '[off-canvas-move-body]',
			'default'     => divino_get_option( 'off-canvas-move-body' ),
			'type'        => 'control',
			'control'     => 'ast-toggle-control',
			'section'     => $_section,
			'priority'    => 30,
			'title'       => __( 'Move Body', 'divino' ),
			'description' => __( 'Enable to shift the body content when the off-canvas menu opens.', 'divino' ),
			'context'     => array(
				divino_Builder_Helper::$general_tab_config,
				array(
					'setting'  => divino_THEME_SETTINGS . '[mobile-header-type]',
					'operator' => '==',
					'value'    => 'dropdown',
				),
			),
			'divider'     => array( 'ast_class' => 'ast-top-divider ast-section-spacing' ),
		),

		array(
			'name'     => divino_THEME_SETTINGS . '[off-canvas-move-body-notice]',
			'type'     => 'control',
			'control'  => 'ast-description',
			'section'  => $_section,
			'priority' => 30,
			'help'     => esc_html__( 'Note: This is not applicable on Transparent and Sticky Headers!', 'divino' ),
			'context'  => array(
				divino_Builder_Helper::$general_tab_config,
				array(
					'setting'  => divino_THEME_SETTINGS . '[mobile-header-type]',
					'operator' => '==',
					'value'    => 'dropdown',
				),
			),
		),

		/**
		 * Option: Off-Canvas Slide-Out.
		 */
		array(
			'name'       => divino_THEME_SETTINGS . '[off-canvas-slide]',
			'default'    => divino_get_option( 'off-canvas-slide' ),
			'type'       => 'control',
			'transport'  => 'postMessage',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'priority'   => 30,
			'title'      => __( 'Position', 'divino' ),
			'choices'    => array(
				'left'  => __( 'Left', 'divino' ),
				'right' => __( 'Right', 'divino' ),
			),
			'context'    => array(
				divino_Builder_Helper::$general_tab_config,
				array(
					'setting'  => divino_THEME_SETTINGS . '[mobile-header-type]',
					'operator' => '==',
					'value'    => 'off-canvas',
				),
			),
			'renderAs'   => 'text',
			'responsive' => false,
			'divider'    => array( 'ast_class' => 'ast-top-divider ast-bottom-divider' ),
		),

		/**
		 * Option: Toggle on click of button or link.
		 */
		array(
			'name'       => divino_THEME_SETTINGS . '[header-builder-menu-toggle-target]',
			'default'    => divino_get_option( 'header-builder-menu-toggle-target' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'context'    => divino_Builder_Helper::$general_tab,
			'priority'   => 40,
			'title'      => __( 'Dropdown Target', 'divino' ),
			'suffix'     => '',
			'choices'    => array(
				'icon' => __( 'Icon', 'divino' ),
				'link' => __( 'Link', 'divino' ),
			),
			'renderAs'   => 'text',
			'responsive' => false,
			'transport'  => 'postMessage',
			'divider'    => array( 'ast_class' => 'ast-bottom-section-divider ast-top-section-divider' ),
		),

		/**
		 * Option: Content alignment option for offcanvas
		 */
		array(
			'name'       => divino_THEME_SETTINGS . '[header-offcanvas-content-alignment]',
			'default'    => divino_get_option( 'header-offcanvas-content-alignment' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'context'    => divino_Builder_Helper::$general_tab,
			'priority'   => 40,
			'title'      => __( 'Content Alignment', 'divino' ),
			'suffix'     => '',
			'choices'    => array(
				'flex-start' => __( 'Left', 'divino' ),
				'center'     => __( 'Center', 'divino' ),
				'flex-end'   => __( 'Right', 'divino' ),
			),
			'renderAs'   => 'text',
			'responsive' => false,
			'transport'  => 'postMessage',
		),

		// Option Group: Off-Canvas Colors Group.
		array(
			'name'              => divino_THEME_SETTINGS . '[off-canvas-background]',
			'type'              => 'control',
			'control'           => 'ast-background',
			'title'             => __( 'Background', 'divino' ),
			'section'           => $_section,
			'transport'         => 'postMessage',
			'priority'          => 26,
			'context'           => divino_Builder_Helper::$design_tab,
			'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_background_obj' ),
			'default'           => divino_get_option( 'off-canvas-background' ),
			'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
		),

		// Option: Off-Canvas Close Icon Color.
		array(
			'name'              => divino_THEME_SETTINGS . '[off-canvas-close-color]',
			'transport'         => 'postMessage',
			'default'           => divino_get_option( 'off-canvas-close-color' ),
			'type'              => 'control',
			'control'           => 'ast-color',
			'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'section'           => $_section,
			'priority'          => 27,
			'title'             => __( 'Close Icon Color', 'divino' ),
			'context'           => array(
				'relation' => 'AND',
				divino_Builder_Helper::$design_tab_config,
				array(
					'relation' => 'OR',
					array(
						'setting'  => divino_THEME_SETTINGS . '[mobile-header-type]',
						'operator' => '==',
						'value'    => 'off-canvas',
					),
					array(
						'setting'  => divino_THEME_SETTINGS . '[mobile-header-type]',
						'operator' => '==',
						'value'    => 'full-width',
					),
				),
			),
		),

		// Spacing Between every element in the flyout.
		array(
			'name'      => divino_THEME_SETTINGS . '[off-canvas-inner-spacing]',
			'default'   => divino_get_option( 'off-canvas-inner-spacing' ),
			'type'      => 'control',
			'control'   => 'ast-slider',
			'title'     => __( 'Inner Element Spacing', 'divino' ),
			'section'   => $_section,
			'transport' => 'postMessage',
			'priority'  => 28,
			'context'   => divino_Builder_Helper::$design_tab,
			'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		// Option Group: Off-Canvas Colors Group.
		array(
			'name'              => divino_THEME_SETTINGS . '[off-canvas-background]',
			'type'              => 'control',
			'control'           => 'ast-background',
			'title'             => __( 'Background', 'divino' ),
			'section'           => $_section,
			'transport'         => 'postMessage',
			'priority'          => 30,
			'context'           => divino_Builder_Helper::$design_tab,
			'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_background_obj' ),
			'default'           => divino_get_option( 'off-canvas-background' ),
			'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
		),

		/**
		 * Option: Popup Padding.
		 */

		array(
			'name'           => divino_THEME_SETTINGS . '[off-canvas-padding]',
			'default'        => divino_get_option( 'off-canvas-padding' ),
			'type'           => 'control',
			'transport'      => 'postMessage',
			'control'        => 'ast-responsive-spacing',
			'section'        => $_section,
			'priority'       => 210,
			'title'          => __( 'Popup Padding', 'divino' ),
			'linked_choices' => true,
			'unit_choices'   => array( 'px', 'em', '%' ),
			'choices'        => array(
				'top'    => __( 'Top', 'divino' ),
				'right'  => __( 'Right', 'divino' ),
				'bottom' => __( 'Bottom', 'divino' ),
				'left'   => __( 'Left', 'divino' ),
			),
			'context'        => array(
				'relation' => 'AND',
				divino_Builder_Helper::$design_tab_config,
				array(
					'relation' => 'OR',
					array(
						'setting'  => divino_THEME_SETTINGS . '[mobile-header-type]',
						'operator' => '==',
						'value'    => 'off-canvas',
					),
					array(
						'setting'  => divino_THEME_SETTINGS . '[mobile-header-type]',
						'operator' => '==',
						'value'    => 'full-width',
					),
				),
			),
			'divider'        => array( 'ast_class' => 'ast-top-section-divider' ),
		),

	);

	if ( divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
		array_map( 'divino_save_header_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
	add_action( 'init', 'divino_header_off_canvas_configuration' );
}
