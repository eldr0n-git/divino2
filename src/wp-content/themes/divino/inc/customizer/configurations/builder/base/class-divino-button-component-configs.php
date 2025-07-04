<?php
/**
 * divino Theme Customizer Configuration Builder.
 *
 * @package     divino-builder
 * @link        https://wpdivino.com/
 * @since       3.0.0
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Builder Customizer Configurations.
 *
 * @since 3.0.0
 */
class divino_Button_Component_Configs {
	/**
	 * Register Builder Customizer Configurations.
	 *
	 * @param array  $configurations Configurations.
	 * @param string $builder_type Builder Type.
	 * @param string $section Section.
	 *
	 * @since 3.0.0
	 * @return array $configurations divino Customizer Configurations with updated configurations.
	 */
	public static function register_configuration( $configurations, $builder_type = 'header', $section = 'section-hb-button-' ) {

		if ( 'footer' === $builder_type ) {
			$class_obj        = divino_Builder_Footer::get_instance();
			$number_of_button = divino_Builder_Helper::$num_of_footer_button;
			$component_limit  = defined( 'divino_EXT_VER' ) ? divino_Builder_Helper::$component_limit : divino_Builder_Helper::$num_of_footer_button;
		} else {
			$class_obj        = divino_Builder_Header::get_instance();
			$number_of_button = divino_Builder_Helper::$num_of_header_button;
			$component_limit  = defined( 'divino_EXT_VER' ) ? divino_Builder_Helper::$component_limit : divino_Builder_Helper::$num_of_header_button;
		}

		$button_config = array();

		for ( $index = 1; $index <= $component_limit; $index++ ) {

			$_section = $section . $index;
			$_prefix  = 'button' . $index;

			/**
			 * These options are related to Header Section - Button.
			 * Prefix hs represents - Header Section.
			 */
			$button_config[] = array(

				/*
					* Header Builder section - Button Component Configs.
					*/
				array(
					'name'        => $_section,
					'type'        => 'section',
					'priority'    => 50,
					/* translators: %s Index */
					'title'       => 1 === $number_of_button ? __( 'Button', 'divino' ) : sprintf( __( 'Button %s', 'divino' ), $index ),
					'panel'       => 'panel-' . $builder_type . '-builder-group',
					'clone_index' => $index,
					'clone_type'  => $builder_type . '-button',
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
				 * Option: Button Text
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text]',
					'default'   => divino_get_option( $builder_type . '-' . $_prefix . '-text' ),
					'type'      => 'control',
					'control'   => 'text',
					'section'   => $_section,
					'priority'  => 20,
					'title'     => __( 'Text', 'divino' ),
					'transport' => 'postMessage',
					'partial'   => array(
						'selector'            => '.ast-' . $builder_type . '-button-' . $index,
						'container_inclusive' => false,
						'render_callback'     => array( $class_obj, 'button_' . $index ),
						'fallback_refresh'    => false,
					),
					'context'   => divino_Builder_Helper::$general_tab,
				),

				/**
				 * Option: Button Link
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-link-option]',
					'default'           => divino_get_option( $builder_type . '-' . $_prefix . '-link-option' ),
					'type'              => 'control',
					'control'           => 'ast-link',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_link' ),
					'section'           => $_section,
					'priority'          => 30,
					'title'             => __( 'Link', 'divino' ),
					'transport'         => 'postMessage',
					'partial'           => array(
						'selector'            => '.ast-' . $builder_type . '-button-' . $index,
						'container_inclusive' => false,
						'render_callback'     => array( $class_obj, 'button_' . $index ),
					),
					'context'           => divino_Builder_Helper::$general_tab,
					'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Group: Primary Header Button Colors Group
				 */
				array(
					'name'       => divino_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text-color-group]',
					'default'    => divino_get_option( $builder_type . '-' . $_prefix . '-color-group' ),
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Text Color', 'divino' ),
					'section'    => $_section,
					'transport'  => 'postMessage',
					'priority'   => 70,
					'context'    => divino_Builder_Helper::$design_tab,
					'responsive' => true,
					'divider'    => array( 'ast_class' => 'ast-section-spacing' ),
				),
				array(
					'name'       => divino_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-background-color-group]',
					'default'    => divino_get_option( $builder_type . '-' . $_prefix . '-color-group' ),
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Background Color', 'divino' ),
					'section'    => $_section,
					'transport'  => 'postMessage',
					'priority'   => 70,
					'context'    => divino_Builder_Helper::$design_tab,
					'responsive' => true,
				),

				/**
				 * Option: Button Text Color
				 */
				array(
					'name'       => $builder_type . '-' . $_prefix . '-text-color',
					'transport'  => 'postMessage',
					'default'    => divino_get_option( $builder_type . '-' . $_prefix . '-text-color' ),
					'type'       => 'sub-control',
					'parent'     => divino_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text-color-group]',
					'section'    => $_section,
					'tab'        => __( 'Normal', 'divino' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 9,
					'context'    => divino_Builder_Helper::$design_tab,
					'title'      => __( 'Normal', 'divino' ),
				),

				/**
				 * Option: Button Text Hover Color
				 */
				array(
					'name'       => $builder_type . '-' . $_prefix . '-text-h-color',
					'default'    => divino_get_option( $builder_type . '-' . $_prefix . '-text-h-color' ),
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'parent'     => divino_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text-color-group]',
					'section'    => $_section,
					'tab'        => __( 'Hover', 'divino' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 9,
					'context'    => divino_Builder_Helper::$design_tab,
					'title'      => __( 'Hover', 'divino' ),
				),

				/**
				 * Option: Button Background Color
				 */
				array(
					'name'       => $builder_type . '-' . $_prefix . '-back-color',
					'default'    => divino_get_option( $builder_type . '-' . $_prefix . '-back-color' ),
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'parent'     => divino_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-background-color-group]',
					'section'    => $_section,
					'tab'        => __( 'Normal', 'divino' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 10,
					'context'    => divino_Builder_Helper::$design_tab,
					'title'      => __( 'Normal', 'divino' ),
				),

				/**
				 * Option: Button Button Hover Color
				 */
				array(
					'name'       => $builder_type . '-' . $_prefix . '-back-h-color',
					'default'    => divino_get_option( $builder_type . '-' . $_prefix . '-back-h-color' ),
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'parent'     => divino_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-background-color-group]',
					'section'    => $_section,
					'tab'        => __( 'Hover', 'divino' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 10,
					'context'    => divino_Builder_Helper::$design_tab,
					'title'      => __( 'Hover', 'divino' ),
				),

				array(
					'name'       => divino_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-builder-button-border-colors-group]',
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Border Color', 'divino' ),
					'section'    => $_section,
					'priority'   => 70,
					'transport'  => 'postMessage',
					'context'    => divino_Builder_Helper::$design_tab,
					'responsive' => true,
					'divider'    => array( 'ast_class' => 'ast-bottom-divider' ),
				),

				/**
				 * Option: Button Border Color
				 */
				array(
					'name'       => $builder_type . '-' . $_prefix . '-border-color',
					'default'    => divino_get_option( $builder_type . '-' . $_prefix . '-border-color' ),
					'parent'     => divino_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-builder-button-border-colors-group]',
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'section'    => $_section,
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 70,
					'context'    => divino_Builder_Helper::$design_tab,
					'title'      => __( 'Normal', 'divino' ),
				),

				/**
				 * Option: Button Border Hover Color
				 */
				array(
					'name'       => $builder_type . '-' . $_prefix . '-border-h-color',
					'default'    => divino_get_option( $builder_type . '-' . $_prefix . '-border-h-color' ),
					'parent'     => divino_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-builder-button-border-colors-group]',
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'section'    => $_section,
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 70,
					'context'    => divino_Builder_Helper::$design_tab,
					'title'      => __( 'Hover', 'divino' ),
				),

				/**
				 * Option: Button Border Size
				 */
				array(
					'name'           => divino_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-border-size]',
					'default'        => divino_get_option( $builder_type . '-' . $_prefix . '-border-size' ),
					'type'           => 'control',
					'section'        => $_section,
					'control'        => 'ast-border',
					'transport'      => 'postMessage',
					'linked_choices' => true,
					'priority'       => 99,
					'title'          => __( 'Border Width', 'divino' ),
					'context'        => divino_Builder_Helper::$design_tab,
					'choices'        => array(
						'top'    => __( 'Top', 'divino' ),
						'right'  => __( 'Right', 'divino' ),
						'bottom' => __( 'Bottom', 'divino' ),
						'left'   => __( 'Left', 'divino' ),
					),
					'divider'        => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Button Radius Fields
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-border-radius-fields]',
					'default'           => divino_get_option( $builder_type . '-' . $_prefix . '-border-radius-fields' ),
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
					'priority'          => 99,
					'context'           => divino_Builder_Helper::$design_tab,
					'connected'         => false,
					'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Primary Header Button Typography
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text-typography]',
					'default'   => divino_get_option( $builder_type . '-' . $_prefix . '-text-typography' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'is_font'   => true,
					'title'     => __( 'Font', 'divino' ),
					'section'   => $_section,
					'transport' => 'postMessage',
					'context'   => divino_Builder_Helper::$design_tab,
					'priority'  => 90,
				),

				/**
				 * Option: Primary Header Button Font Family
				 */
				array(
					'name'      => $builder_type . '-' . $_prefix . '-font-family',
					'default'   => divino_get_option( $builder_type . '-' . $_prefix . '-font-family' ),
					'parent'    => divino_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text-typography]',
					'type'      => 'sub-control',
					'section'   => $_section,
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'title'     => __( 'Font Family', 'divino' ),
					'context'   => divino_Builder_Helper::$general_tab,
					'connect'   => $builder_type . '-' . $_prefix . '-font-weight',
					'priority'  => 1,
					'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Primary Footer Button Font Weight
				 */
				array(
					'name'              => $builder_type . '-' . $_prefix . '-font-weight',
					'default'           => divino_get_option( $builder_type . '-' . $_prefix . '-font-weight' ),
					'parent'            => divino_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text-typography]',
					'type'              => 'sub-control',
					'section'           => $_section,
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'title'             => __( 'Font Weight', 'divino' ),
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'connect'           => $builder_type . '-' . $_prefix . '-font-family',
					'priority'          => 2,
					'context'           => divino_Builder_Helper::$general_tab,
					'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Primary Header Button Font Size
				 */

				array(
					'name'              => $builder_type . '-' . $_prefix . '-font-size',
					'default'           => divino_get_option( $builder_type . '-' . $_prefix . '-font-size' ),
					'parent'            => divino_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text-typography]',
					'transport'         => 'postMessage',
					'title'             => __( 'Font Size', 'divino' ),
					'type'              => 'sub-control',
					'section'           => $_section,
					'control'           => 'ast-responsive-slider',
					'priority'          => 3,
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
				 * Option: Primary Footer Button Font Extras
				 */
				array(
					'name'     => $builder_type . '-' . $_prefix . '-font-extras',
					'parent'   => divino_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text-typography]',
					'section'  => $_section,
					'type'     => 'sub-control',
					'control'  => 'ast-font-extras',
					'priority' => 5,
					'default'  => divino_get_option( 'breadcrumb-font-extras' ),
					'context'  => divino_Builder_Helper::$general_tab,
					'title'    => __( 'Font Extras', 'divino' ),
				),
			);

			if ( 'footer' === $builder_type ) {
				$button_config[] = array(

					array(
						'name'      => divino_THEME_SETTINGS . '[footer-button-' . $index . '-alignment]',
						'default'   => divino_get_option( 'footer-button-' . $index . '-alignment' ),
						'type'      => 'control',
						'control'   => 'ast-selector',
						'section'   => $_section,
						'priority'  => 35,
						'title'     => __( 'Alignment', 'divino' ),
						'context'   => divino_Builder_Helper::$general_tab,
						'transport' => 'postMessage',
						'choices'   => array(
							'flex-start' => 'align-left',
							'center'     => 'align-center',
							'flex-end'   => 'align-right',
						),
						'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
					),
				);
			}

			$button_config[] = divino_Builder_Base_Configuration::prepare_visibility_tab( $_section, $builder_type );

			$button_config[] = divino_Extended_Base_Configuration::prepare_advanced_tab( $_section );

		}

		$button_config = call_user_func_array( 'array_merge', $button_config + array( array() ) );

		return array_merge( $configurations, $button_config );
	}
}

/**
 * Kicking this off by creating object of this class.
 */

new divino_Button_Component_Configs();
