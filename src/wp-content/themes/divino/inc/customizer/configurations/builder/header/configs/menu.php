<?php
/**
 * Menu Header Configuration.
 *
 * @package     divino
 * @link        https://divino.kz/
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register menu header builder Customizer Configurations.
 *
 * @since 1.0.0
 * @return array divino Customizer Configurations with updated configurations.
 */
function divino_header_menu_configuration() {
	$menu_configs = array();

	$component_limit = defined( 'divino_EXT_VER' ) ? divino_Builder_Helper::$component_limit : divino_Builder_Helper::$num_of_header_menu;

	for ( $index = 1; $index <= $component_limit; $index++ ) {

		$_section = 'section-hb-menu-' . $index;
		$_prefix  = 'menu' . $index;

		switch ( $index ) {
			case 1:
				$edit_menu_title = __( 'Primary Menu', 'divino' );
				break;
			case 2:
				$edit_menu_title = __( 'Secondary Menu', 'divino' );
				break;
			default:
				$edit_menu_title = __( 'Menu ', 'divino' ) . $index;
				break;
		}

		$_configs = array(

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

			// Section: Primary Header.
			array(
				'name'        => $_section,
				'type'        => 'section',
				'title'       => $edit_menu_title,
				'panel'       => 'panel-header-builder-group',
				'priority'    => 40,
				'clone_index' => $index,
				'clone_type'  => 'header-menu',
			),

			/**
			 * Option: Theme Menu create link.
			 */
			array(
				'name'      => divino_THEME_SETTINGS . '[header-' . $_prefix . '-create-menu-link]',
				'default'   => divino_get_option( 'header-' . $_prefix . '-create-menu-link' ),
				'type'      => 'control',
				'control'   => 'ast-customizer-link',
				'section'   => $_section,
				'priority'  => 30,
				'link_type' => 'section',
				'linked'    => 'menu_locations',
				'link_text' => __( 'Configure Menu from Here.', 'divino' ),
				'context'   => divino_Builder_Helper::$general_tab,
			),

			/**
			 * Option: Menu hover style
			 */
			array(
				'name'       => divino_THEME_SETTINGS . '[header-' . $_prefix . '-menu-hover-animation]',
				'default'    => divino_get_option( 'header-' . $_prefix . '-menu-hover-animation' ),
				'type'       => 'control',
				'control'    => 'ast-select',
				'section'    => $_section,
				'priority'   => 10,
				'title'      => __( 'Menu Hover Style', 'divino' ),
				'choices'    => array(
					''          => __( 'None', 'divino' ),
					'zoom'      => __( 'Zoom In', 'divino' ),
					'underline' => __( 'Underline', 'divino' ),
					'overline'  => __( 'Overline', 'divino' ),
				),
				'context'    => divino_Builder_Helper::$design_tab,
				'transport'  => 'postMessage',
				'responsive' => false,
				'renderAs'   => 'text',
				'divider'    => array( 'ast_class' => 'ast-section-spacing' ),
			),

			/**
			 * Option: Submenu heading.
			 */
			array(
				'name'     => divino_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-heading]',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'section'  => $_section,
				'title'    => __( 'Submenu', 'divino' ),
				'settings' => array(),
				'priority' => 30,
				'context'  => divino_Builder_Helper::$general_tab,
				'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
			),

			/**
			 * Option: Submenu width
			 */
			array(
				'name'        => divino_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-width]',
				'default'     => divino_get_option( 'header-' . $_prefix . '-submenu-width' ),
				'type'        => 'control',
				'context'     => divino_Builder_Helper::$general_tab,
				'section'     => $_section,
				'control'     => 'ast-slider',
				'priority'    => 30.5,
				'title'       => __( 'Width', 'divino' ),
				'suffix'      => 'px',
				'input_attrs' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 1920,
				),
				'transport'   => 'postMessage',
				'divider'     => array( 'ast_class' => 'ast-section-spacing' ),
			),

			/**
			 * Option: Submenu Animation
			 */
			array(
				'name'       => divino_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-container-animation]',
				'default'    => divino_get_option( 'header-' . $_prefix . '-submenu-container-animation' ),
				'type'       => 'control',
				'control'    => 'ast-select',
				'section'    => $_section,
				'priority'   => 23,
				'title'      => __( 'Submenu Animation', 'divino' ),
				'choices'    => array(
					''           => __( 'None', 'divino' ),
					'slide-down' => __( 'Slide Down', 'divino' ),
					'slide-up'   => __( 'Slide Up', 'divino' ),
					'fade'       => __( 'Fade', 'divino' ),
				),
				'context'    => divino_Builder_Helper::$design_tab,
				'transport'  => 'postMessage',
				'responsive' => false,
				'renderAs'   => 'text',
				'divider'    => array( 'ast_class' => 'ast-bottom-divider ast-top-section-divider' ),
			),

			// Option: Submenu Container Divider.
			array(
				'name'     => divino_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-container-divider]',
				'section'  => $_section,
				'type'     => 'control',
				'control'  => 'ast-heading',
				'title'    => __( 'Submenu Container', 'divino' ),
				'priority' => 20,
				'settings' => array(),
				'context'  => divino_Builder_Helper::$design_tab,
				'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
			),

			// Option: Submenu Divider Size.
			array(
				'name'        => divino_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-item-b-size]',
				'type'        => 'control',
				'control'     => 'ast-slider',
				'default'     => divino_get_option( 'header-' . $_prefix . '-submenu-item-b-size' ),
				'section'     => $_section,
				'priority'    => 20.5,
				'transport'   => 'postMessage',
				'title'       => __( 'Divider Size', 'divino' ),
				'context'     => array(
					divino_Builder_Helper::$design_tab_config,
					array(
						'setting'  => divino_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-item-border]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'suffix'      => 'px',
				'input_attrs' => array(
					'min'  => 1,
					'step' => 1,
					'max'  => 10,
				),
				'divider'     => array( 'ast_class' => 'ast-section-spacing' ),
			),

			// Option: Submenu item Border Color.
			array(
				'name'              => divino_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-item-b-color]',
				'default'           => divino_get_option( 'header-' . $_prefix . '-submenu-item-b-color' ),
				'type'              => 'control',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'transport'         => 'postMessage',
				'title'             => __( 'Divider Color', 'divino' ),
				'section'           => $_section,
				'priority'          => 21,
				'context'           => array(
					divino_Builder_Helper::$design_tab_config,
					array(
						'setting'  => divino_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-item-border]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
			),

			/**
			 * Option: Submenu Top Offset
			 */
			array(
				'name'        => divino_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-top-offset]',
				'default'     => divino_get_option( 'header-' . $_prefix . '-submenu-top-offset' ),
				'type'        => 'control',
				'context'     => divino_Builder_Helper::$design_tab,
				'section'     => $_section,
				'control'     => 'ast-slider',
				'priority'    => 22,
				'title'       => __( 'Top Offset', 'divino' ),
				'suffix'      => 'px',
				'transport'   => 'postMessage',
				'input_attrs' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 200,
				),
			),

			// Option: Sub-Menu Border.
			array(
				'name'           => divino_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-border]',
				'default'        => divino_get_option( 'header-' . $_prefix . '-submenu-border' ),
				'type'           => 'control',
				'control'        => 'ast-border',
				'transport'      => 'postMessage',
				'section'        => $_section,
				'linked_choices' => true,
				'context'        => divino_Builder_Helper::$design_tab,
				'priority'       => 23,
				'title'          => __( 'Border Width', 'divino' ),
				'choices'        => array(
					'top'    => __( 'Top', 'divino' ),
					'right'  => __( 'Right', 'divino' ),
					'bottom' => __( 'Bottom', 'divino' ),
					'left'   => __( 'Left', 'divino' ),
				),
				'divider'        => array( 'ast_class' => 'ast-bottom-divider' ),
			),

			// Option: Submenu Container Border Color.
			array(
				'name'              => divino_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-b-color]',
				'default'           => divino_get_option( 'header-' . $_prefix . '-submenu-b-color' ),
				'parent'            => divino_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-border-group]',
				'type'              => 'control',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'transport'         => 'postMessage',
				'title'             => __( 'Border Color', 'divino' ),
				'section'           => $_section,
				'priority'          => 23,
				'context'           => divino_Builder_Helper::$design_tab,
				'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
			),

			/**
			 * Option: Button Radius Fields
			 */
			array(
				'name'              => divino_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-border-radius-fields]',
				'default'           => divino_get_option( 'header-' . $_prefix . '-submenu-border-radius-fields' ),
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
				'priority'          => 23,
				'connected'         => false,
				'divider'           => array( 'ast_class' => 'ast-bottom-section-divider' ),
				'context'           => divino_Builder_Helper::$design_tab,
			),

			// Option: Submenu Divider Checkbox.
			array(
				'name'      => divino_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-item-border]',
				'default'   => divino_get_option( 'header-' . $_prefix . '-submenu-item-border' ),
				'type'      => 'control',
				'control'   => 'ast-toggle-control',
				'section'   => $_section,
				'priority'  => 35,
				'title'     => __( 'Item Divider', 'divino' ),
				'context'   => divino_Builder_Helper::$general_tab,
				'transport' => 'postMessage',
				'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),

			),

			// Option: Menu Stack on Mobile Checkbox.
			array(
				'name'      => divino_THEME_SETTINGS . '[header-' . $_prefix . '-menu-stack-on-mobile]',
				'default'   => divino_get_option( 'header-' . $_prefix . '-menu-stack-on-mobile' ),
				'type'      => 'control',
				'control'   => 'ast-toggle-control',
				'section'   => $_section,
				'priority'  => 41,
				'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
				'title'     => __( 'Stack on Responsive', 'divino' ),
				'context'   => divino_Builder_Helper::$responsive_general_tab,
				'transport' => 'postMessage',
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
				'priority'          => 151,
				'title'             => __( 'Margin', 'divino' ),
				'linked_choices'    => true,
				'unit_choices'      => array( 'px', 'em', '%' ),
				'choices'           => array(
					'top'    => __( 'Top', 'divino' ),
					'right'  => __( 'Right', 'divino' ),
					'bottom' => __( 'Bottom', 'divino' ),
					'left'   => __( 'Left', 'divino' ),
				),
				'context'           => divino_Builder_Helper::$design_tab,
				'divider'           => array( 'ast_class' => 'ast-top-divider' ),
			),

			// Option Group: Menu Color.
			array(
				'name'       => divino_THEME_SETTINGS . '[header-' . $_prefix . '-text-colors]',
				'type'       => 'control',
				'control'    => 'ast-color-group',
				'title'      => __( 'Text / Link', 'divino' ),
				'section'    => $_section,
				'transport'  => 'postMessage',
				'priority'   => 90,
				'context'    => divino_Builder_Helper::$design_tab,
				'responsive' => true,
				'divider'    => array(
					'ast_title' => __( 'Menu Color', 'divino' ),
				),
			),
			array(
				'name'       => divino_THEME_SETTINGS . '[header-' . $_prefix . '-background-colors]',
				'type'       => 'control',
				'control'    => 'ast-color-group',
				'title'      => __( 'Background', 'divino' ),
				'section'    => $_section,
				'transport'  => 'postMessage',
				'priority'   => 90,
				'context'    => divino_Builder_Helper::$design_tab,
				'responsive' => true,
			),

			// Option: Menu Color.
			array(
				'name'       => 'header-' . $_prefix . '-color-responsive',
				'default'    => divino_get_option( 'header-' . $_prefix . '-color-responsive' ),
				'parent'     => divino_THEME_SETTINGS . '[header-' . $_prefix . '-text-colors]',
				'type'       => 'sub-control',
				'control'    => 'ast-responsive-color',
				'transport'  => 'postMessage',
				'tab'        => __( 'Normal', 'divino' ),
				'section'    => $_section,
				'title'      => __( 'Normal', 'divino' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 7,
				'context'    => divino_Builder_Helper::$general_tab,
			),

			// Option: Menu Background image, color.
			array(
				'name'       => 'header-' . $_prefix . '-bg-obj-responsive',
				'default'    => divino_get_option( 'header-' . $_prefix . '-bg-obj-responsive' ),
				'parent'     => divino_THEME_SETTINGS . '[header-' . $_prefix . '-background-colors]',
				'type'       => 'sub-control',
				'control'    => 'ast-responsive-background',
				'section'    => $_section,
				'transport'  => 'postMessage',
				'tab'        => __( 'Normal', 'divino' ),
				'data_attrs' => array( 'name' => 'header-' . $_prefix . '-bg-obj-responsive' ),
				'title'      => __( 'Normal', 'divino' ),
				'priority'   => 9,
				'context'    => divino_Builder_Helper::$general_tab,
			),

			// Option: Menu Hover Color.
			array(
				'name'       => 'header-' . $_prefix . '-h-color-responsive',
				'default'    => divino_get_option( 'header-' . $_prefix . '-h-color-responsive' ),
				'parent'     => divino_THEME_SETTINGS . '[header-' . $_prefix . '-text-colors]',
				'tab'        => __( 'Hover', 'divino' ),
				'type'       => 'sub-control',
				'control'    => 'ast-responsive-color',
				'transport'  => 'postMessage',
				'title'      => __( 'Hover', 'divino' ),
				'section'    => $_section,
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 19,
				'context'    => divino_Builder_Helper::$general_tab,
			),

			// Option: Menu Hover Background Color.
			array(
				'name'       => 'header-' . $_prefix . '-h-bg-color-responsive',
				'default'    => divino_get_option( 'header-' . $_prefix . '-h-bg-color-responsive' ),
				'parent'     => divino_THEME_SETTINGS . '[header-' . $_prefix . '-background-colors]',
				'type'       => 'sub-control',
				'title'      => __( 'Hover', 'divino' ),
				'section'    => $_section,
				'control'    => 'ast-responsive-color',
				'transport'  => 'postMessage',
				'tab'        => __( 'Hover', 'divino' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 21,
				'context'    => divino_Builder_Helper::$general_tab,
			),

			// Option: Active Menu Color.
			array(
				'name'       => 'header-' . $_prefix . '-a-color-responsive',
				'default'    => divino_get_option( 'header-' . $_prefix . '-a-color-responsive' ),
				'parent'     => divino_THEME_SETTINGS . '[header-' . $_prefix . '-text-colors]',
				'type'       => 'sub-control',
				'section'    => $_section,
				'control'    => 'ast-responsive-color',
				'transport'  => 'postMessage',
				'tab'        => __( 'Active', 'divino' ),
				'title'      => __( 'Active', 'divino' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 31,
				'context'    => divino_Builder_Helper::$general_tab,
			),

			// Option: Active Menu Background Color.
			array(
				'name'       => 'header-' . $_prefix . '-a-bg-color-responsive',
				'default'    => divino_get_option( 'header-' . $_prefix . '-a-bg-color-responsive' ),
				'parent'     => divino_THEME_SETTINGS . '[header-' . $_prefix . '-background-colors]',
				'type'       => 'sub-control',
				'control'    => 'ast-responsive-color',
				'transport'  => 'postMessage',
				'section'    => $_section,
				'title'      => __( 'Active', 'divino' ),
				'tab'        => __( 'Active', 'divino' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 33,
				'context'    => divino_Builder_Helper::$general_tab,
			),

			// Font Divider.
			array(
				'name'     => divino_THEME_SETTINGS . '[header-' . $index . '-font-divider]',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'section'  => $_section,
				'title'    => __( 'Font', 'divino' ),
				'settings' => array(),
				'priority' => 120,
				'context'  => divino_Builder_Helper::$design_tab,
				'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
			),

			// Option Group: Menu Typography.
			array(
				'name'      => divino_THEME_SETTINGS . '[header-' . $_prefix . '-header-menu-typography]',
				'default'   => divino_get_option( 'header-' . $_prefix . '-header-menu-typography' ),
				'type'      => 'control',
				'control'   => 'ast-settings-group',
				'title'     => __( 'Menu Font', 'divino' ),
				'is_font'   => true,
				'section'   => $_section,
				'transport' => 'postMessage',
				'priority'  => 120,
				'context'   => divino_Builder_Helper::$design_tab,
			),

			// Option: Menu Font Family.
			array(
				'name'      => 'header-' . $_prefix . '-font-family',
				'default'   => divino_get_option( 'header-' . $_prefix . '-font-family' ),
				'parent'    => divino_THEME_SETTINGS . '[header-' . $_prefix . '-header-menu-typography]',
				'type'      => 'sub-control',
				'section'   => $_section,
				'transport' => 'postMessage',
				'control'   => 'ast-font',
				'font_type' => 'ast-font-family',
				'title'     => __( 'Font Family', 'divino' ),
				'priority'  => 22,
				'connect'   => 'header-' . $_prefix . '-font-weight',
				'context'   => divino_Builder_Helper::$general_tab,
				'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			// Option: Menu Font Weight.
			array(
				'name'              => 'header-' . $_prefix . '-font-weight',
				'default'           => divino_get_option( 'header-' . $_prefix . '-font-weight' ),
				'parent'            => divino_THEME_SETTINGS . '[header-' . $_prefix . '-header-menu-typography]',
				'section'           => $_section,
				'type'              => 'sub-control',
				'control'           => 'ast-font',
				'transport'         => 'postMessage',
				'font_type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'title'             => __( 'Font Weight', 'divino' ),
				'priority'          => 23,
				'connect'           => 'header-' . $_prefix . '-font-family',
				'context'           => divino_Builder_Helper::$general_tab,
				'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			// Option: Menu Font Size.
			array(
				'name'              => 'header-' . $_prefix . '-font-size',
				'default'           => divino_get_option( 'header-' . $_prefix . '-font-size' ),
				'parent'            => divino_THEME_SETTINGS . '[header-' . $_prefix . '-header-menu-typography]',
				'section'           => $_section,
				'type'              => 'sub-control',
				'priority'          => 23,
				'title'             => __( 'Font Size', 'divino' ),
				'control'           => 'ast-responsive-slider',
				'transport'         => 'postMessage',
				'context'           => divino_Builder_Helper::$general_tab,
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
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
			 * Option: Primary Menu Font Extras
			 */
			array(
				'name'     => 'header-' . $_prefix . '-font-extras',
				'parent'   => divino_THEME_SETTINGS . '[header-' . $_prefix . '-header-menu-typography]',
				'section'  => $_section,
				'type'     => 'sub-control',
				'control'  => 'ast-font-extras',
				'priority' => 26,
				'default'  => divino_get_option( 'header-' . $_prefix . '-font-extras' ),
				'title'    => __( 'Font Extras', 'divino' ),
			),

			/**
			 * Option: Spacing Divider
			 */
			array(
				'name'     => divino_THEME_SETTINGS . '[header-' . $index . '-spacing-divider]',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'section'  => $_section,
				'title'    => __( 'Spacing', 'divino' ),
				'settings' => array(),
				'priority' => 150,
				'context'  => divino_Builder_Helper::$design_tab,
				'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
			),

			// Option - Menu Space.
			array(
				'name'              => divino_THEME_SETTINGS . '[header-' . $_prefix . '-menu-spacing]',
				'default'           => divino_get_option( 'header-' . $_prefix . '-menu-spacing' ),
				'type'              => 'control',
				'control'           => 'ast-responsive-spacing',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
				'transport'         => 'postMessage',
				'section'           => $_section,
				'priority'          => 150,
				'title'             => __( 'Menu', 'divino' ),
				'linked_choices'    => true,
				'unit_choices'      => array( 'px', 'em', '%' ),
				'choices'           => array(
					'top'    => __( 'Top', 'divino' ),
					'right'  => __( 'Right', 'divino' ),
					'bottom' => __( 'Bottom', 'divino' ),
					'left'   => __( 'Left', 'divino' ),
				),
				'context'           => divino_Builder_Helper::$design_tab,
				'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
			),
		);

		$menu_configs[] = divino_Builder_Base_Configuration::prepare_visibility_tab( $_section );
		$menu_configs[] = $_configs;
	}

	$menu_configs = call_user_func_array( 'array_merge', $menu_configs + array( array() ) );

	if ( divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
		array_map( 'divino_save_header_customizer_configs', $menu_configs );
	}

	return $menu_configs;
}

if ( divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
	add_action( 'init', 'divino_header_menu_configuration' );
}
