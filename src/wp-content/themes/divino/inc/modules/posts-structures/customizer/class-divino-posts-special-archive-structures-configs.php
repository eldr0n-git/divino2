<?php
/**
 * Posts Structures Options for special pages.
 *
 * 1. Search page.
 *
 * @package     divino
 * @link        https://www.brainstormforce.com
 * @since       divino 4.6.0
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
 * Register Posts Structures Customizer Configurations.
 *
 * @since 4.6.0
 */
class divino_Posts_Special_Archive_Structures_Configs extends divino_Customizer_Config_Base {
	/**
	 * Register Posts Structures Customizer Configurations.
	 *
	 * @param array                $configurations divino Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 4.6.0
	 * @return Array divino Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {
		$section = 'ast-section-search-page';

		$blog_layout = array(
			'blog-layout-4' => array(
				'label' => __( 'Grid', 'divino' ),
				'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'blog-layout-4', false ) : '',
			),
			'blog-layout-5' => array(
				'label' => __( 'List', 'divino' ),
				'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'blog-layout-5', false ) : '',
			),
			'blog-layout-6' => array(
				'label' => __( 'Cover', 'divino' ),
				'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'blog-layout-6', false ) : '',
			),
		);

		foreach ( divino_Posts_Structure_Loader::get_special_page_types() as $special_type ) {
			$section       = 'ast-section-' . $special_type . '-page';
			$title_section = 'section-' . $special_type . '-page-title';

			$archive_structure_choices                                    = array();
			$archive_structure_choices[ $title_section . '-title' ]       = array(
				'clone'       => false,
				'is_parent'   => true,
				'main_index'  => $title_section . '-title',
				'clone_limit' => 2,
				'title'       => __( 'Heading', 'divino' ),
			);
			$archive_structure_choices[ $title_section . '-description' ] = array(
				'clone'       => false,
				'is_parent'   => true,
				'main_index'  => $title_section . '-description',
				'clone_limit' => 2,
				'title'       => __( 'Subheading', 'divino' ),
			);
			$archive_structure_choices[ $title_section . '-breadcrumb' ]  = __( 'Breadcrumb', 'divino' );

			$_configs = array(

				array(
					'name'        => $title_section . '-ast-context-tabs',
					'section'     => $title_section,
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
					'context'     => array(),
				),

				array(
					'name'     => $title_section,
					'title'    => ucfirst( $special_type ) . __( ' Page Title', 'divino' ),
					'type'     => 'section',
					'section'  => $section,
					'panel'    => '',
					'priority' => 1,
				),

				array(
					'name'     => divino_THEME_SETTINGS . '[ast-' . $special_type . '-page-title]',
					'type'     => 'control',
					'default'  => divino_get_option( 'ast-' . $special_type . '-page-title', true ),
					'control'  => 'ast-section-toggle',
					'section'  => $section,
					'priority' => 2,
					'linked'   => $title_section,
					'linkText' => ucfirst( $special_type ) . __( ' Page Title', 'divino' ),
					'divider'  => array( 'ast_class' => 'ast-bottom-divider ast-bottom-section-divider' ),
				),

				array(
					'name'                   => divino_THEME_SETTINGS . '[' . $title_section . '-layout]',
					'type'                   => 'control',
					'control'                => 'ast-radio-image',
					'sanitize_callback'      => array( 'divino_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'                => $title_section,
					'default'                => divino_get_option( $title_section . '-layout', 'layout-1' ),
					'priority'               => 5,
					'context'                => divino_Builder_Helper::$general_tab,
					'title'                  => __( 'Banner Layout', 'divino' ),
					'divider'                => array( 'ast_class' => 'ast-section-spacing' ),
					'choices'                => array(
						'layout-1' => array(
							'label' => __( 'Layout 1', 'divino' ),
							'path'  => divino_Builder_UI_Controller::fetch_svg_icon( 'banner-layout-1' ),
						),
						'layout-2' => array(
							'label' => __( 'Layout 2', 'divino' ),
							'path'  => divino_Builder_UI_Controller::fetch_svg_icon( 'banner-layout-2' ),
						),
					),
					'contextual_sub_control' => true,
					'input_attrs'            => array(
						'dependents' => array(
							'layout-1' => array( $title_section . '-empty-layout-message', $title_section . '-article-featured-image-position-layout-1', $title_section . '-article-featured-image-width-type' ),
							'layout-2' => array( $title_section . '-featured-as-background', $title_section . '-banner-featured-overlay', $title_section . '-image-position', $title_section . '-featured-help-notice', $title_section . '-article-featured-image-position-layout-2' ),
						),
					),
				),

				array(
					'name'       => divino_THEME_SETTINGS . '[' . $title_section . '-banner-width-type]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => $title_section,
					'default'    => divino_get_option( $title_section . '-banner-width-type', 'fullwidth' ),
					'priority'   => 10,
					'title'      => __( 'Container Width', 'divino' ),
					'choices'    => array(
						'fullwidth' => __( 'Full Width', 'divino' ),
						'custom'    => __( 'Custom', 'divino' ),
					),
					'divider'    => array( 'ast_class' => 'ast-top-divider ast-bottom-spacing' ),
					'responsive' => false,
					'renderAs'   => 'text',
					'context'    => array(
						divino_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => divino_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
					),
				),

				array(
					'name'        => divino_THEME_SETTINGS . '[' . $title_section . '-banner-custom-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => $title_section,
					'transport'   => 'postMessage',
					'default'     => divino_get_option( $title_section . '-banner-custom-width', 1200 ),
					'context'     => array(
						divino_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => divino_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
						array(
							'setting'  => divino_THEME_SETTINGS . '[' . $title_section . '-banner-width-type]',
							'operator' => '===',
							'value'    => 'custom',
						),
					),
					'priority'    => 15,
					'title'       => __( 'Custom Width', 'divino' ),
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 768,
						'step' => 1,
						'max'  => 1920,
					),
				),

				array(
					'name'              => divino_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'type'              => 'control',
					'control'           => 'ast-sortable',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_multi_choices' ),
					'section'           => $title_section,
					'context'           => divino_Builder_Helper::$general_tab,
					'default'           => divino_get_option( $title_section . '-structure' ),
					'priority'          => 20,
					'title'             => __( 'Structure', 'divino' ),
					'choices'           => $archive_structure_choices,
				),

				array(
					'name'     => $title_section . '-custom-title',
					'parent'   => divino_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'default'  => divino_get_option( $title_section . '-custom-title' ),
					'linked'   => $title_section . '-title',
					'type'     => 'sub-control',
					'control'  => 'ast-text-input',
					'settings' => array(),
					'section'  => $title_section,
					'priority' => 1,
					'title'    => __( 'Text', 'divino' ),
				),

				array(
					'name'        => $title_section . '-found-custom-description',
					'parent'      => divino_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'default'     => divino_get_option( $title_section . '-found-custom-description' ),
					'linked'      => $title_section . '-description',
					'type'        => 'sub-control',
					'control'     => 'ast-text-input',
					'input_attrs' => array(
						'textarea' => true,
					),
					'section'     => $title_section,
					'priority'    => 1,
					'title'       => __( 'When Results Found', 'divino' ),
				),

				array(
					'name'        => $title_section . '-not-found-custom-description',
					'parent'      => divino_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'default'     => divino_get_option( $title_section . '-not-found-custom-description' ),
					'linked'      => $title_section . '-description',
					'type'        => 'sub-control',
					'control'     => 'ast-text-input',
					'input_attrs' => array(
						'textarea' => true,
					),
					'section'     => $title_section,
					'priority'    => 1,
					'title'       => __( 'When Results Not Found', 'divino' ),
				),

				array(
					'name'      => divino_THEME_SETTINGS . '[' . $title_section . '-horizontal-alignment]',
					'default'   => divino_get_option( $title_section . '-horizontal-alignment' ),
					'type'      => 'control',
					'control'   => 'ast-selector',
					'section'   => $title_section,
					'priority'  => 21,
					'title'     => __( 'Horizontal Alignment', 'divino' ),
					'context'   => divino_Builder_Helper::$general_tab,
					'transport' => 'postMessage',
					'choices'   => array(
						'left'   => 'align-left',
						'center' => 'align-center',
						'right'  => 'align-right',
					),
					'divider'   => array( 'ast_class' => 'ast-top-divider' ),
				),
				array(
					'name'       => divino_THEME_SETTINGS . '[' . $title_section . '-vertical-alignment]',
					'default'    => divino_get_option( $title_section . '-vertical-alignment', 'center' ),
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => $title_section,
					'priority'   => 22,
					'title'      => __( 'Vertical Alignment', 'divino' ),
					'choices'    => array(
						'flex-start' => __( 'Top', 'divino' ),
						'center'     => __( 'Middle', 'divino' ),
						'flex-end'   => __( 'Bottom', 'divino' ),
					),
					'divider'    => array( 'ast_class' => 'ast-top-divider ast-section-spacing' ),
					'context'    => array(
						divino_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => divino_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
					),
					'transport'  => 'postMessage',
					'renderAs'   => 'text',
					'responsive' => false,
				),
				array(
					'name'              => divino_THEME_SETTINGS . '[' . $title_section . '-banner-height]',
					'type'              => 'control',
					'control'           => 'ast-responsive-slider',
					'section'           => $title_section,
					'transport'         => 'postMessage',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'default'           => divino_get_option( $title_section . '-banner-height', divino_Posts_Structure_Loader::get_customizer_default( 'responsive-slider' ) ),
					'context'           => array(
						divino_Builder_Helper::$design_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => divino_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
					),
					'priority'          => 1,
					'title'             => __( 'Banner Min Height', 'divino' ),
					'suffix'            => 'px',
					'input_attrs'       => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 1000,
					),
					'divider'           => array( 'ast_class' => 'ast-bottom-divider ast-section-spacing' ),
				),
				array(
					'name'        => divino_THEME_SETTINGS . '[' . $title_section . '-elements-gap]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => $title_section,
					'transport'   => 'postMessage',
					'default'     => divino_get_option( $title_section . '-elements-gap', 10 ),
					'context'     => divino_Builder_Helper::$design_tab,
					'priority'    => 5,
					'title'       => __( 'Inner Elements Spacing', 'divino' ),
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 100,
					),
					'divider'     => array( 'ast_class' => 'ast-bottom-divider ast-section-spacing' ),
				),
				array(
					'name'       => divino_THEME_SETTINGS . '[' . $title_section . '-banner-image-type]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => $title_section,
					'default'    => divino_get_option( $title_section . '-banner-image-type', 'none' ),
					'priority'   => 5,
					'context'    => divino_Builder_Helper::$design_tab,
					'title'      => __( 'Container Background', 'divino' ),
					'choices'    => array(
						'none'   => __( 'None', 'divino' ),
						'custom' => __( 'Custom', 'divino' ),
					),
					'divider'    => array( 'ast_class' => 'ast-section-spacing' ),
					'responsive' => false,
					'renderAs'   => 'text',
				),
				array(
					'name'      => divino_THEME_SETTINGS . '[' . $title_section . '-banner-custom-bg]',
					'default'   => divino_get_option( $title_section . '-banner-custom-bg', divino_Posts_Structure_Loader::get_customizer_default( 'responsive-background' ) ),
					'type'      => 'control',
					'control'   => 'ast-responsive-background',
					'section'   => $title_section,
					'title'     => __( 'Background', 'divino' ),
					'transport' => 'postMessage',
					'priority'  => 5,
					'context'   => array(
						divino_Builder_Helper::$design_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => divino_THEME_SETTINGS . '[' . $title_section . '-banner-image-type]',
							'operator' => '===',
							'value'    => 'custom',
						),
					),
				),
				array(
					'name'      => divino_THEME_SETTINGS . '[' . $title_section . '-banner-title-color]',
					'type'      => 'control',
					'control'   => 'ast-color',
					'section'   => $title_section,
					'default'   => divino_get_option( $title_section . '-banner-title-color' ),
					'transport' => 'postMessage',
					'priority'  => 9,
					'title'     => __( 'Title Color', 'divino' ),
					'divider'   => array( 'ast_class' => 'ast-top-divider ast-top-spacing' ),
					'context'   => divino_Builder_Helper::$design_tab,
				),
				array(
					'name'      => divino_THEME_SETTINGS . '[' . $title_section . '-banner-text-color]',
					'type'      => 'control',
					'control'   => 'ast-color',
					'section'   => $title_section,
					'default'   => divino_get_option( $title_section . '-banner-text-color' ),
					'priority'  => 10,
					'title'     => __( 'Text Color', 'divino' ),
					'transport' => 'postMessage',
					'context'   => divino_Builder_Helper::$design_tab,
				),
				array(
					'name'      => divino_THEME_SETTINGS . '[' . $title_section . '-banner-link-color]',
					'type'      => 'control',
					'control'   => 'ast-color',
					'section'   => $title_section,
					'default'   => divino_get_option( $title_section . '-banner-link-color' ),
					'transport' => 'postMessage',
					'priority'  => 15,
					'title'     => __( 'Link Color', 'divino' ),
					'context'   => divino_Builder_Helper::$design_tab,
				),
				array(
					'name'      => divino_THEME_SETTINGS . '[' . $title_section . '-banner-link-hover-color]',
					'type'      => 'control',
					'control'   => 'ast-color',
					'section'   => $title_section,
					'default'   => divino_get_option( $title_section . '-banner-link-hover-color' ),
					'transport' => 'postMessage',
					'priority'  => 20,
					'title'     => __( 'Link Hover Color', 'divino' ),
					'context'   => divino_Builder_Helper::$design_tab,
					'divider'   => array( 'ast_class' => 'ast-bottom-spacing' ),
				),
				array(
					'name'      => divino_THEME_SETTINGS . '[' . $title_section . '-banner-title-typography-group]',
					'type'      => 'control',
					'priority'  => 22,
					'control'   => 'ast-settings-group',
					'context'   => array(
						divino_Builder_Helper::$design_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => divino_THEME_SETTINGS . '[' . $title_section . '-structure]',
							'operator' => 'contains',
							'value'    => $title_section . '-title',
						),
					),
					'divider'   => array( 'ast_class' => 'ast-top-divider' ),
					'title'     => __( 'Title Font', 'divino' ),
					'is_font'   => true,
					'section'   => $title_section,
					'transport' => 'postMessage',
				),
				array(
					'name'      => divino_THEME_SETTINGS . '[' . $title_section . '-banner-text-typography-group]',
					'type'      => 'control',
					'priority'  => 25,
					'control'   => 'ast-settings-group',
					'context'   => divino_Builder_Helper::$design_tab,
					'title'     => __( 'Text Font', 'divino' ),
					'is_font'   => true,
					'divider'   => array( 'ast_class' => 'ast-bottom-spacing' ),
					'section'   => $title_section,
					'transport' => 'postMessage',
				),
				array(
					'name'      => $title_section . '-text-font-family',
					'parent'    => divino_THEME_SETTINGS . '[' . $title_section . '-banner-text-typography-group]',
					'section'   => $title_section,
					'type'      => 'sub-control',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'default'   => divino_get_option( $title_section . '-text-font-family', 'inherit' ),
					'title'     => __( 'Font Family', 'divino' ),
					'connect'   => divino_THEME_SETTINGS . '[' . $title_section . '-text-font-weight]',
					'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),
				array(
					'name'              => $title_section . '-text-font-weight',
					'parent'            => divino_THEME_SETTINGS . '[' . $title_section . '-banner-text-typography-group]',
					'section'           => $title_section,
					'type'              => 'sub-control',
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => divino_get_option( $title_section . '-text-font-weight', 'inherit' ),
					'title'             => __( 'Font Weight', 'divino' ),
					'connect'           => $title_section . '-text-font-family',
					'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),
				array(
					'name'              => $title_section . '-text-font-size',
					'parent'            => divino_THEME_SETTINGS . '[' . $title_section . '-banner-text-typography-group]',
					'section'           => $title_section,
					'type'              => 'sub-control',
					'control'           => 'ast-responsive-slider',
					'default'           => divino_get_option( $title_section . '-text-font-size', divino_Posts_Structure_Loader::get_customizer_default( 'font-size' ) ),
					'transport'         => 'postMessage',
					'title'             => __( 'Font Size', 'divino' ),
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
							'step' => 1,
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
				array(
					'name'    => $title_section . '-text-font-extras',
					'parent'  => divino_THEME_SETTINGS . '[' . $title_section . '-banner-text-typography-group]',
					'section' => $title_section,
					'type'    => 'sub-control',
					'control' => 'ast-font-extras',
					'default' => divino_get_option( $title_section . '-text-font-extras', divino_Posts_Structure_Loader::get_customizer_default( 'font-extras' ) ),
					'title'   => __( 'Font Extras', 'divino' ),
				),
				array(
					'name'      => $title_section . '-title-font-family',
					'parent'    => divino_THEME_SETTINGS . '[' . $title_section . '-banner-title-typography-group]',
					'section'   => $title_section,
					'type'      => 'sub-control',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'default'   => divino_get_option( $title_section . '-title-font-family', 'inherit' ),
					'title'     => __( 'Font Family', 'divino' ),
					'connect'   => divino_THEME_SETTINGS . '[' . $title_section . '-title-font-weight]',
					'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),
				array(
					'name'              => $title_section . '-title-font-weight',
					'parent'            => divino_THEME_SETTINGS . '[' . $title_section . '-banner-title-typography-group]',
					'section'           => $title_section,
					'type'              => 'sub-control',
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => divino_get_option( $title_section . '-title-font-weight' ),
					'title'             => __( 'Font Weight', 'divino' ),
					'connect'           => $title_section . '-title-font-family',
					'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),
				array(
					'name'              => $title_section . '-title-font-size',
					'parent'            => divino_THEME_SETTINGS . '[' . $title_section . '-banner-title-typography-group]',
					'section'           => $title_section,
					'type'              => 'sub-control',
					'control'           => 'ast-responsive-slider',
					'default'           => divino_get_option( $title_section . '-title-font-size' ),
					'transport'         => 'postMessage',
					'title'             => __( 'Font Size', 'divino' ),
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
							'step' => 1,
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
				array(
					'name'    => $title_section . '-title-font-extras',
					'parent'  => divino_THEME_SETTINGS . '[' . $title_section . '-banner-title-typography-group]',
					'section' => $title_section,
					'type'    => 'sub-control',
					'control' => 'ast-font-extras',
					'default' => divino_get_option( $title_section . '-title-font-extras', divino_Posts_Structure_Loader::get_customizer_default( 'font-extras' ) ),
					'title'   => __( 'Font Extras', 'divino' ),
				),
				array(
					'name'              => divino_THEME_SETTINGS . '[' . $title_section . '-banner-margin]',
					'default'           => divino_get_option( $title_section . '-banner-margin', divino_Posts_Structure_Loader::get_customizer_default( 'responsive-spacing' ) ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'section'           => $title_section,
					'title'             => __( 'Margin', 'divino' ),
					'linked_choices'    => true,
					'transport'         => 'postMessage',
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'divino' ),
						'right'  => __( 'Right', 'divino' ),
						'bottom' => __( 'Bottom', 'divino' ),
						'left'   => __( 'Left', 'divino' ),
					),
					'context'           => divino_Builder_Helper::$design_tab,
					'priority'          => 100,
					'connected'         => false,
					'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
				),
				array(
					'name'              => divino_THEME_SETTINGS . '[' . $title_section . '-banner-padding]',
					'default'           => divino_get_option( $title_section . '-banner-padding', divino_Posts_Structure_Loader::get_customizer_default( 'responsive-padding' ) ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'section'           => $title_section,
					'title'             => __( 'Padding', 'divino' ),
					'linked_choices'    => true,
					'transport'         => 'postMessage',
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'divino' ),
						'right'  => __( 'Right', 'divino' ),
						'bottom' => __( 'Bottom', 'divino' ),
						'left'   => __( 'Left', 'divino' ),
					),
					'context'           => divino_Builder_Helper::$design_tab,
					'priority'          => 120,
					'connected'         => false,
				),

				array(
					'name'              => divino_THEME_SETTINGS . '[ast-' . $special_type . '-content-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => $section,
					'default'           => divino_get_option( 'ast-' . $special_type . '-content-layout', 'default' ),
					'priority'          => 3,
					'title'             => __( 'Container Layout', 'divino' ),
					'choices'           => array(
						'default'                => array(
							'label' => __( 'Default', 'divino' ),
							'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'layout-default', false ) : '',
						),
						'normal-width-container' => array(
							'label' => __( 'Normal', 'divino' ),
							'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'normal-width-container', false ) : '',
						),
						'narrow-width-container' => array(
							'label' => __( 'Narrow', 'divino' ),
							'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'narrow-width-container', false ) : '',
						),
						'full-width-container'   => array(
							'label' => __( 'Full Width', 'divino' ),
							'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'full-width-container', false ) : '',
						),
					),
					'divider'           => array( 'ast_class' => 'ast-top-divider ast-bottom-spacing' ),
				),

				array(
					'name'        => divino_THEME_SETTINGS . '[ast-' . $special_type . '-content-style]',
					'type'        => 'control',
					'control'     => 'ast-selector',
					'section'     => $section,
					'default'     => divino_get_option( 'ast-' . $special_type . '-content-style', 'default' ),
					'priority'    => 3,
					'title'       => __( 'Container Style', 'divino' ),
					'description' => __( 'Container style will apply only when layout is set to either normal or narrow.', 'divino' ),
					'choices'     => array(
						'default' => __( 'Default', 'divino' ),
						'unboxed' => __( 'Unboxed', 'divino' ),
						'boxed'   => __( 'Boxed', 'divino' ),
					),
					'renderAs'    => 'text',
					'responsive'  => false,
					'divider'     => array( 'ast_class' => 'ast-top-divider' ),
				),

				array(
					'name'              => divino_THEME_SETTINGS . '[ast-' . $special_type . '-sidebar-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => $section,
					'default'           => divino_get_option( 'ast-' . $special_type . '-sidebar-layout', 'default' ),
					'description'       => __( 'Sidebar will only apply when container layout is set to normal.', 'divino' ),
					'priority'          => 3,
					'title'             => __( 'Sidebar Layout', 'divino' ),
					'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
					'choices'           => array(
						'default'       => array(
							'label' => __( 'Default', 'divino' ),
							'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'layout-default', false ) : '',
						),
						'no-sidebar'    => array(
							'label' => __( 'No Sidebar', 'divino' ),
							'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'no-sidebar', false ) : '',
						),
						'left-sidebar'  => array(
							'label' => __( 'Left Sidebar', 'divino' ),
							'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'left-sidebar', false ) : '',
						),
						'right-sidebar' => array(
							'label' => __( 'Right Sidebar', 'divino' ),
							'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'right-sidebar', false ) : '',
						),
					),
				),

				array(
					'name'       => divino_THEME_SETTINGS . '[ast-' . $special_type . '-sidebar-style]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => $section,
					'default'    => divino_get_option( 'ast-' . $special_type . '-sidebar-style', 'default' ),
					'priority'   => 3,
					'title'      => __( 'Sidebar Style', 'divino' ),
					'choices'    => array(
						'default' => __( 'Default', 'divino' ),
						'unboxed' => __( 'Unboxed', 'divino' ),
						'boxed'   => __( 'Boxed', 'divino' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
					'divider'    => array( 'ast_class' => 'ast-top-divider' ),
				),

				array(
					'name'              => divino_THEME_SETTINGS . '[ast-' . $special_type . '-results-style]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => $section,
					'default'           => divino_get_option( 'ast-' . $special_type . '-results-style' ),
					'priority'          => 14,
					'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
					'title'             => __( 'Results Layout', 'divino' ),
					'choices'           => $blog_layout,
				),

				array(
					'name'         => divino_THEME_SETTINGS . '[ast-' . $special_type . '-results-per-page]',
					'default'      => divino_get_option( 'ast-' . $special_type . '-results-per-page' ),
					'type'         => 'control',
					'control'      => 'ast-number',
					'qty_selector' => true,
					'section'      => $section,
					'title'        => __( 'Post Per Page', 'divino' ),
					'priority'     => 14,
					'responsive'   => false,
					'input_attrs'  => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 500,
					),
					'divider'      => array( 'ast_class' => 'ast-top-divider' ),
				),

				array(
					'name'        => divino_THEME_SETTINGS . '[ast-' . $special_type . '-live-search]',
					'default'     => divino_get_option( 'ast-' . $special_type . '-live-search' ),
					'type'        => 'control',
					'control'     => 'ast-toggle-control',
					'section'     => $section,
					'description' => __( 'This option activates Live Search support for the search box on the no results page.', 'divino' ),
					'title'       => __( 'Live Search', 'divino' ),
					'priority'    => 15,
					'context'     => divino_Builder_Helper::$general_tab,
					'divider'     => array( 'ast_class' => 'ast-top-divider' ),
				),

				array(
					'name'        => divino_THEME_SETTINGS . '[ast-' . $special_type . '-live-search-post-types]',
					'default'     => divino_get_option( 'ast-' . $special_type . '-live-search-post-types' ),
					'type'        => 'control',
					'section'     => $section,
					'control'     => 'ast-multi-selector',
					'priority'    => 15,
					'title'       => __( 'Search Within Post Types', 'divino' ),
					'context'     => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[ast-' . $special_type . '-live-search]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'transport'   => 'refresh',
					'choices'     => divino_customizer_search_post_types_choices(),
					'divider'     => array( 'ast_class' => 'ast-top-divider' ),
					'renderAs'    => 'text',
					'input_attrs' => array(
						'stack_after' => 2, // Currently stack options supports after 2 & 3.
					),
				),
			);

			$configurations = array_merge( $configurations, $_configs );
		}

		return $configurations;
	}
}

/**
 * Kicking this off by creating new object.
 */
new divino_Posts_Special_Archive_Structures_Configs();
