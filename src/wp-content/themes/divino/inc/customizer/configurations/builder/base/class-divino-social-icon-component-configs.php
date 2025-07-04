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
class divino_Social_Icon_Component_Configs {
	/**
	 * Register Builder Customizer Configurations.
	 *
	 * @param array  $configurations Configurations.
	 * @param string $builder_type Builder Type.
	 * @param string $section Section slug.
	 * @since 3.0.0
	 * @return array $configurations divino Customizer Configurations with updated configurations.
	 */
	public static function register_configuration( $configurations, $builder_type = 'header', $section = 'section-hb-social-icons-' ) {

		$social_configs = array();

		$class_obj              = divino_Builder_Header::get_instance();
		$number_of_social_icons = divino_Builder_Helper::$num_of_header_social_icons;

		if ( 'footer' === $builder_type ) {
			$class_obj              = divino_Builder_Footer::get_instance();
			$number_of_social_icons = divino_Builder_Helper::$num_of_footer_social_icons;
			$component_limit        = defined( 'divino_EXT_VER' ) ? divino_Builder_Helper::$component_limit : divino_Builder_Helper::$num_of_header_social_icons;
		} else {
			$component_limit = defined( 'divino_EXT_VER' ) ? divino_Builder_Helper::$component_limit : divino_Builder_Helper::$num_of_footer_social_icons;
		}

		for ( $index = 1; $index <= $component_limit; $index++ ) {

			$_section = $section . $index;

			$_configs = array(

				/*
				* Builder section
				*/
				array(
					'name'        => $_section,
					'type'        => 'section',
					'priority'    => 90,
					/* translators: 1: index */
					'title'       => 1 === $number_of_social_icons ? __( 'Social Icons', 'divino' ) : sprintf( __( 'Social Icons %s', 'divino' ), $index ),
					'panel'       => 'panel-' . $builder_type . '-builder-group',
					'clone_index' => $index,
					'clone_type'  => $builder_type . '-social-icons',
				),

				/**
				 * Option: Builder Tabs
				 */
				array(
					'name'        => $_section . '-ast-context-tabs',
					'section'     => $_section,
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
				),

				array(
					'name'       => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-color-type]',
					'default'    => divino_get_option( $builder_type . '-social-' . $index . '-color-type' ),
					'section'    => $_section,
					'type'       => 'control',
					'control'    => 'ast-selector',
					'title'      => __( 'Color Type', 'divino' ),
					'priority'   => 1,
					'choices'    => array(
						'custom'   => __( 'Custom', 'divino' ),
						'official' => __( 'Official', 'divino' ),
					),
					'context'    => divino_Builder_Helper::$design_tab,
					'responsive' => false,
					'renderAs'   => 'text',
					'divider'    => array( 'ast_class' => 'ast-section-spacing ast-bottom-divider' ),
				),

				array(
					'name'       => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-brand-color]',
					'default'    => divino_get_option( $builder_type . '-social-' . $index . '-brand-color' ),
					'type'       => 'control',
					'section'    => $_section,
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Icon Color', 'divino' ),
					'responsive' => true,
					'rgba'       => true,
					'context'    => array(
						divino_Builder_Helper::$design_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-brand-hover-toggle]',
							'operator' => '==',
							'value'    => true,
						),
						array(
							'setting'  => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-color-type]',
							'operator' => '==',
							'value'    => 'official',
						),
					),
					'priority'   => 1,
				),

				array(
					'name'       => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-brand-label-color]',
					'default'    => divino_get_option( $builder_type . '-social-' . $index . '-brand-label-color' ),
					'type'       => 'control',
					'section'    => $_section,
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Label Color', 'divino' ),
					'responsive' => true,
					'rgba'       => true,
					'context'    => array(
						divino_Builder_Helper::$design_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-brand-hover-toggle]',
							'operator' => '==',
							'value'    => true,
						),
						array(
							'setting'  => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-color-type]',
							'operator' => '==',
							'value'    => 'official',
						),
					),
					'priority'   => 1,
					'divider'    => array( 'ast_class' => 'ast-bottom-divider' ),
				),

				/**
				 * Option: Toggle Social Icons Brand Color On Hover.
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-brand-hover-toggle]',
					'default'  => divino_get_option( $builder_type . '-social-' . $index . '-brand-hover-toggle' ),
					'type'     => 'control',
					'section'  => $_section,
					'title'    => __( 'Enable Brand Color On Hover', 'divino' ),
					'priority' => 1,
					'control'  => 'ast-toggle-control',
					'context'  => array(
						divino_Builder_Helper::$design_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-color-type]',
							'operator' => '==',
							'value'    => 'official',
						),
					),
				),

				/**
				 * Group: Primary Social Colors Group
				 */
				array(
					'name'       => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-icon-color-group]',
					'default'    => divino_get_option( $builder_type . '-social-' . $index . '-color-group' ),
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Icon Color', 'divino' ),
					'section'    => $_section,
					'transport'  => 'postMessage',
					'priority'   => 1,
					'context'    => array(
						divino_Builder_Helper::$design_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-color-type]',
							'operator' => '==',
							'value'    => 'custom',
						),
					),
					'responsive' => true,
				),
				array(
					'name'       => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-label-color-group]',
					'default'    => divino_get_option( $builder_type . '-social-' . $index . '-color-group' ),
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Label Color', 'divino' ),
					'section'    => $_section,
					'transport'  => 'postMessage',
					'priority'   => 1,
					'context'    => array(
						divino_Builder_Helper::$design_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-color-type]',
							'operator' => '==',
							'value'    => 'custom',
						),
					),
					'responsive' => true,
				),
				array(
					'name'       => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-background-color-group]',
					'default'    => divino_get_option( $builder_type . '-social-' . $index . '-color-group' ),
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Background Color', 'divino' ),
					'section'    => $_section,
					'transport'  => 'postMessage',
					'priority'   => 1,
					'context'    => array(
						divino_Builder_Helper::$design_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-color-type]',
							'operator' => '==',
							'value'    => 'custom',
						),
					),
					'responsive' => true,
				),

				/**
				 * Option: Social Text Color
				 */
				array(
					'name'       => $builder_type . '-social-' . $index . '-color',
					'transport'  => 'postMessage',
					'default'    => divino_get_option( $builder_type . '-social-' . $index . '-color' ),
					'type'       => 'sub-control',
					'parent'     => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-icon-color-group]',
					'section'    => $_section,
					'tab'        => __( 'Normal', 'divino' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 1,
					'context'    => divino_Builder_Helper::$design_tab,
					'title'      => __( 'Normal', 'divino' ),
				),

				/**
				 * Option: Social Text Hover Color
				 */
				array(
					'name'       => $builder_type . '-social-' . $index . '-h-color',
					'default'    => divino_get_option( $builder_type . '-social-' . $index . '-h-color' ),
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'parent'     => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-icon-color-group]',
					'section'    => $_section,
					'tab'        => __( 'Hover', 'divino' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 1,
					'context'    => divino_Builder_Helper::$design_tab,
					'title'      => __( 'Hover', 'divino' ),
				),

				/**
				 * Option: Social Label Color
				 */
				array(
					'name'       => $builder_type . '-social-' . $index . '-label-color',
					'default'    => divino_get_option( $builder_type . '-social-' . $index . '-label-color' ),
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'parent'     => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-label-color-group]',
					'section'    => $_section,
					'tab'        => __( 'Normal', 'divino' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 1,
					'context'    => divino_Builder_Helper::$design_tab,
					'title'      => __( 'Normal', 'divino' ),
				),

				/**
				 * Option: Social Label Hover Color
				 */
				array(
					'name'       => $builder_type . '-social-' . $index . '-label-h-color',
					'default'    => divino_get_option( $builder_type . '-social-' . $index . '-label-h-color' ),
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'parent'     => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-label-color-group]',
					'section'    => $_section,
					'tab'        => __( 'Hover', 'divino' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 1,
					'context'    => divino_Builder_Helper::$design_tab,
					'title'      => __( 'Hover', 'divino' ),
				),

				/**
				 * Option: Social Background Color
				 */
				array(
					'name'       => $builder_type . '-social-' . $index . '-bg-color',
					'default'    => divino_get_option( $builder_type . '-social-' . $index . '-bg-color' ),
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'parent'     => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-background-color-group]',
					'section'    => $_section,
					'tab'        => __( 'Normal', 'divino' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 1,
					'context'    => divino_Builder_Helper::$design_tab,
					'title'      => __( 'Normal', 'divino' ),
				),

				/**
				 * Option: Social Background Hover Color
				 */
				array(
					'name'       => $builder_type . '-social-' . $index . '-bg-h-color',
					'default'    => divino_get_option( $builder_type . '-social-' . $index . '-bg-h-color' ),
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'parent'     => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-background-color-group]',
					'section'    => $_section,
					'tab'        => __( 'Hover', 'divino' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 1,
					'context'    => divino_Builder_Helper::$design_tab,
					'title'      => __( 'Hover', 'divino' ),
				),

				/**
				 * Option: Social Icons.
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[' . $builder_type . '-social-icons-' . $index . ']',
					'section'   => $_section,
					'type'      => 'control',
					'control'   => 'ast-social-icons',
					'title'     => __( 'Social Icons', 'divino' ),
					'transport' => 'postMessage',
					'priority'  => 1,
					'default'   => divino_get_option( $builder_type . '-social-icons-' . $index ),
					'partial'   => array(
						'selector'            => '.ast-' . $builder_type . '-social-' . $index . '-wrap',
						'container_inclusive' => true,
						'render_callback'     => array( $class_obj, $builder_type . '_social_' . $index ),
						'fallback_refresh'    => false,
					),
					'context'   => divino_Builder_Helper::$general_tab,
					'divider'   => array( 'ast_class' => 'ast-bottom-section-divider ast-section-spacing' ),
				),

				// Show label Toggle.
				array(
					'name'      => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-label-toggle]',
					'default'   => divino_get_option( $builder_type . '-social-' . $index . '-label-toggle' ),
					'type'      => 'control',
					'control'   => 'ast-toggle-control',
					'section'   => $_section,
					'priority'  => 2,
					'title'     => __( 'Show Label', 'divino' ),
					'transport' => 'postMessage',
					'partial'   => array(
						'selector'            => '.ast-' . $builder_type . '-social-' . $index . '-wrap',
						'container_inclusive' => true,
						'render_callback'     => array( $class_obj, $builder_type . '_social_' . $index ),
						'fallback_refresh'    => false,
					),
					'context'   => divino_Builder_Helper::$general_tab,
				),

				/**
				 * Option: Social Icon Spacing
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-space]',
					'section'           => $_section,
					'priority'          => 2,
					'transport'         => 'postMessage',
					'default'           => divino_get_option( $builder_type . '-social-' . $index . '-space' ),
					'title'             => __( 'Icon Spacing', 'divino' ),
					'suffix'            => 'px',
					'type'              => 'control',
					'control'           => 'ast-responsive-slider',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'input_attrs'       => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 50,
					),
					'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
					'context'           => divino_Builder_Helper::$design_tab,
				),

				/**
				 * Option: Social Icon Background Spacing.
				 */
				array(
					'name'        => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-bg-space]',
					'section'     => $_section,
					'priority'    => 2,
					'transport'   => 'postMessage',
					'default'     => divino_get_option( $builder_type . '-social-' . $index . '-bg-space' ),
					'title'       => __( 'Icon Background Space', 'divino' ),
					'suffix'      => 'px',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 50,
					),
					'context'     => divino_Builder_Helper::$design_tab,
					'divider'     => array( 'ast_class' => 'ast-bottom-divider' ),

				),

				/**
				 * Option: Social Icon Size
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-size]',
					'section'           => $_section,
					'priority'          => 1,
					'transport'         => 'postMessage',
					'default'           => divino_get_option( $builder_type . '-social-' . $index . '-size' ),
					'title'             => __( 'Icon Size', 'divino' ),
					'suffix'            => 'px',
					'type'              => 'control',
					'control'           => 'ast-responsive-slider',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'input_attrs'       => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 50,
					),
					'divider'           => array( 'ast_class' => 'ast-bottom-divider ast-top-section-divider' ),
					'context'           => divino_Builder_Helper::$design_tab,
				),

				/**
				 * Option: Button Radius Fields
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-radius-fields]',
					'default'           => divino_get_option( $builder_type . '-social-' . $index . '-radius-fields' ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'section'           => $_section,
					'title'             => __( 'Icon Radius', 'divino' ),
					'linked_choices'    => true,
					'transport'         => 'postMessage',
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'divino' ),
						'right'  => __( 'Right', 'divino' ),
						'bottom' => __( 'Bottom', 'divino' ),
						'left'   => __( 'Left', 'divino' ),
					),
					'priority'          => 4,
					'connected'         => false,
					'context'           => divino_Builder_Helper::$design_tab,
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
					'priority' => 49,
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
					'priority'          => 49,
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
					'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
				),
			);

			if ( 'footer' === $builder_type ) {

				$_configs[] = array(
					'name'      => divino_THEME_SETTINGS . '[footer-social-' . $index . '-alignment]',
					'default'   => divino_get_option( 'footer-social-' . $index . '-alignment' ),
					'type'      => 'control',
					'control'   => 'ast-selector',
					'section'   => $_section,
					'priority'  => 6,
					'title'     => __( 'Alignment', 'divino' ),
					'context'   => divino_Builder_Helper::$general_tab,
					'transport' => 'refresh',
					'choices'   => array(
						'left'   => 'align-left',
						'center' => 'align-center',
						'right'  => 'align-right',
					),
					'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
				);
			}

			$social_configs[] = divino_Builder_Base_Configuration::prepare_visibility_tab( $_section, $builder_type );

			$social_configs[] = divino_Builder_Base_Configuration::prepare_typography_options(
				$_section,
				array(
					divino_Builder_Helper::$design_tab_config,
					array(
						'setting'  => divino_THEME_SETTINGS . '[' . $builder_type . '-social-' . $index . '-label-toggle]',
						'operator' => '===',
						'value'    => true,
					),
				)
			);

			$social_configs[] = $_configs;
		}

		$social_configs = call_user_func_array( 'array_merge', $social_configs + array( array() ) );
		return array_merge( $configurations, $social_configs );
	}
}

/**
 * Kicking this off by creating object of this class.
 */

new divino_Social_Icon_Component_Configs();
