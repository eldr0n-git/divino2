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
class divino_Html_Component_Configs {
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
	public static function register_configuration( $configurations, $builder_type = 'header', $section = 'section-hb-html-' ) {

		$html_config = array();

		if ( 'footer' === $builder_type ) {
			$class_obj       = divino_Builder_Footer::get_instance();
			$component_limit = defined( 'divino_EXT_VER' ) ? divino_Builder_Helper::$component_limit : divino_Builder_Helper::$num_of_footer_html;
		} else {
			$class_obj       = divino_Builder_Header::get_instance();
			$component_limit = defined( 'divino_EXT_VER' ) ? divino_Builder_Helper::$component_limit : divino_Builder_Helper::$num_of_header_html;
		}

		for ( $index = 1; $index <= $component_limit; $index++ ) {

			$_section = $section . $index;

			$_configs = array(

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

				/*
				 * Builder section
				 */
				array(
					'name'        => $_section,
					'type'        => 'section',
					'priority'    => 60,
					/* translators: %s Index */
					'title'       => sprintf( __( 'HTML %s', 'divino' ), $index ),
					'panel'       => 'panel-' . $builder_type . '-builder-group',
					'clone_index' => $index,
					'clone_type'  => $builder_type . '-html',
				),

				/**
				 * Option: Html Editor.
				 */
				array(
					'name'        => divino_THEME_SETTINGS . '[' . $builder_type . '-html-' . $index . ']',
					'type'        => 'control',
					'control'     => 'ast-html-editor',
					'section'     => $_section,
					'transport'   => 'postMessage',
					'priority'    => 4,
					'default'     => divino_get_option( $builder_type . '-html-' . $index ),
					'input_attrs' => array(
						'id' => $builder_type . '-html-' . $index,
					),
					'partial'     => array(
						'selector'         => '.ast-' . $builder_type . '-html-' . $index,
						'render_callback'  => array( $class_obj, $builder_type . '_html_' . $index ),
						'fallback_refresh' => false,
					),
					'context'     => divino_Builder_Helper::$general_tab,
					'divider'     => array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: HTML Color.
				 */

				array(
					'name'       => divino_THEME_SETTINGS . '[' . $builder_type . '-html-' . $index . 'color]',
					'default'    => divino_get_option( $builder_type . '-html-' . $index . 'color' ),
					'type'       => 'control',
					'section'    => $_section,
					'priority'   => 8,
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'title'      => __( 'Text Color', 'divino' ),
					'context'    => divino_Builder_Helper::$design_tab,
					'divider'    => array( 'ast_class' => 'ast-section-spacing' ),
				),
				array(
					'name'       => divino_THEME_SETTINGS . '[' . $builder_type . '-html-' . $index . '-link-group]',
					'default'    => divino_get_option( $builder_type . '-html-' . $index . '-color-group' ),
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Link Color', 'divino' ),
					'section'    => $_section,
					'transport'  => 'postMessage',
					'priority'   => 8,
					'context'    => divino_Builder_Helper::$design_tab,
					'responsive' => true,
					'divider'    => array( 'ast_class' => 'ast-bottom-section-divider' ),
				),

				/**
				 * Option: Link Color.
				 */
				array(
					'name'       => $builder_type . '-html-' . $index . 'link-color',
					'default'    => divino_get_option( $builder_type . '-html-' . $index . 'link-color' ),
					'type'       => 'sub-control',
					'section'    => $_section,
					'priority'   => 9,
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'parent'     => divino_THEME_SETTINGS . '[' . $builder_type . '-html-' . $index . '-link-group]',
					'title'      => __( 'Normal', 'divino' ),
					'context'    => divino_Builder_Helper::$design_tab,
					'divider'    => array( 'ast_class' => 'ast-bottom-section-divider' ),
				),

				/**
				 * Option: Link Hover Color.
				 */
				array(
					'name'       => $builder_type . '-html-' . $index . 'link-h-color',
					'default'    => divino_get_option( $builder_type . '-html-' . $index . 'link-h-color' ),
					'type'       => 'sub-control',
					'section'    => $_section,
					'priority'   => 10,
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'parent'     => divino_THEME_SETTINGS . '[' . $builder_type . '-html-' . $index . '-link-group]',
					'title'      => __( 'Hover', 'divino' ),
					'context'    => divino_Builder_Helper::$design_tab,
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
					'priority' => 109,
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
					'priority'          => 109,
					'title'             => __( 'Margin', 'divino' ),
					'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
					'linked_choices'    => true,
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'divino' ),
						'right'  => __( 'Right', 'divino' ),
						'bottom' => __( 'Bottom', 'divino' ),
						'left'   => __( 'Left', 'divino' ),
					),
					'context'           => divino_Builder_Helper::$design_tab,
				),
			);

			if ( 'footer' === $builder_type ) {
				$_configs[] = array(
					'name'      => divino_THEME_SETTINGS . '[footer-html-' . $index . '-alignment]',
					'default'   => divino_get_option( 'footer-html-' . $index . '-alignment' ),
					'type'      => 'control',
					'control'   => 'ast-selector',
					'section'   => $_section,
					'priority'  => 6,
					'title'     => __( 'Alignment', 'divino' ),
					'context'   => divino_Builder_Helper::$general_tab,
					'transport' => 'postMessage',
					'choices'   => array(
						'left'   => 'align-left',
						'center' => 'align-center',
						'right'  => 'align-right',
					),
					'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
				);
			}

			$html_config[] = divino_Builder_Base_Configuration::prepare_visibility_tab( $_section, $builder_type );

			$html_config[] = divino_Builder_Base_Configuration::prepare_typography_options( $_section );

			$html_config[] = $_configs;
		}

		$html_config = call_user_func_array( 'array_merge', $html_config + array( array() ) );
		return array_merge( $configurations, $html_config );
	}
}

/**
 * Kicking this off by creating object of this class.
 */

new divino_Html_Component_Configs();
