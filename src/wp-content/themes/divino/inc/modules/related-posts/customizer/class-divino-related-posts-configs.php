<?php
/**
 * Related Posts Options for divino Theme.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'divino_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Register Related Posts Configurations.
 */
class divino_Related_Posts_Configs extends divino_Customizer_Config_Base {
	/**
	 * Register Related Posts Configurations.
	 *
	 * @param Array                $configurations divino Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 3.5.0
	 * @return Array divino Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {
		$related_structure_sub_controls = array();
		$meta_config_options            = array();
		$parent_section                 = 'section-blog-single';

		$related_structure_sub_controls['featured-image'] = array(
			'clone'       => false,
			'is_parent'   => true,
			'main_index'  => 'featured-image',
			'clone_limit' => 2,
			'title'       => __( 'Featured Image', 'divino' ),
		);
		$related_structure_sub_controls['title-meta']     = array(
			'clone'       => false,
			'is_parent'   => true,
			'main_index'  => 'title-meta',
			'clone_limit' => 2,
			'title'       => __( 'Title & Post Meta', 'divino' ),
		);
		$meta_config_options['category']                  = array(
			'clone'       => false,
			'is_parent'   => true,
			'main_index'  => 'category',
			'clone_limit' => 1,
			'title'       => __( 'Category', 'divino' ),
		);
		$meta_config_options['author']                    = array(
			'clone'       => false,
			'is_parent'   => true,
			'main_index'  => 'author',
			'clone_limit' => 1,
			'title'       => __( 'Author', 'divino' ),
		);
		$meta_config_options['date']                      = array(
			'clone'       => false,
			'is_parent'   => true,
			'main_index'  => 'date',
			'clone_limit' => 1,
			'title'       => __( 'Date', 'divino' ),
		);
		$meta_config_options['tag']                       = array(
			'clone'       => false,
			'is_parent'   => true,
			'main_index'  => 'tag',
			'clone_limit' => 1,
			'title'       => __( 'Tag', 'divino' ),
		);

		$_configs = array(

			/**
			 * Option: Related Posts Query
			 */
			array(
				'name'     => divino_THEME_SETTINGS . '[related-posts-section-heading]',
				'section'  => 'section-blog-single',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'title'    => __( 'Related Posts', 'divino' ),
				'priority' => 10,
				'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
			),

			array(
				'name'        => 'related-posts-section-ast-context-tabs',
				'section'     => 'ast-sub-section-related-posts',
				'type'        => 'control',
				'control'     => 'ast-builder-header-control',
				'priority'    => 0,
				'description' => '',
				'context'     => array(),
			),

			array(
				'name'     => 'ast-sub-section-related-posts',
				'title'    => __( 'Related Posts', 'divino' ),
				'type'     => 'section',
				'section'  => $parent_section,
				'panel'    => '',
				'priority' => 1,
			),

			array(
				'name'     => divino_THEME_SETTINGS . '[enable-related-posts]',
				'type'     => 'control',
				'default'  => divino_get_option( 'enable-related-posts' ),
				'control'  => 'ast-section-toggle',
				'section'  => $parent_section,
				'priority' => 10,
				'linked'   => 'ast-sub-section-related-posts',
				'linkText' => __( 'Related Posts', 'divino' ),
				'divider'  => array( 'ast_class' => 'ast-bottom-divider ast-bottom-section-divider' ),
			),

			/**
			 * Option: Related Posts Title
			 */
			array(
				'name'      => divino_THEME_SETTINGS . '[related-posts-title]',
				'default'   => divino_get_option( 'related-posts-title' ),
				'type'      => 'control',
				'section'   => 'ast-sub-section-related-posts',
				'priority'  => 11,
				'title'     => __( 'Title', 'divino' ),
				'control'   => 'ast-text-input',
				'divider'   => array( 'ast_class' => 'ast-section-spacing' ),
				'transport' => 'postMessage',
				'partial'   => array(
					'selector'            => '.ast-related-posts-title-section .ast-related-posts-title',
					'container_inclusive' => false,
					'render_callback'     => array( 'divino_Related_Posts_Loader', 'render_related_posts_title' ),
				),
				'context'   => array(
					divino_Builder_Helper::$general_tab_config,
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			/**
			 * Option: Related Posts Title Alignment
			 */
			array(
				'name'       => divino_THEME_SETTINGS . '[releted-posts-title-alignment]',
				'default'    => divino_get_option( 'releted-posts-title-alignment' ),
				'section'    => 'ast-sub-section-related-posts',
				'transport'  => 'postMessage',
				'title'      => __( 'Title Alignment', 'divino' ),
				'type'       => 'control',
				'control'    => 'ast-selector',
				'priority'   => 11,
				'responsive' => false,
				'divider'    => array( 'ast_class' => 'ast-top-divider' ),
				'context'    => array(
					divino_Builder_Helper::$general_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => divino_THEME_SETTINGS . '[related-posts-title]',
						'operator' => '!=',
						'value'    => '',
					),
				),
				'choices'    => array(
					'left'   => 'align-left',
					'center' => 'align-center',
					'right'  => 'align-right',
				),
			),

			/**
			 * Option: Related Posts Structure
			 */
			array(
				'name'              => divino_THEME_SETTINGS . '[related-posts-structure]',
				'type'              => 'control',
				'control'           => 'ast-sortable',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_multi_choices' ),
				'section'           => 'ast-sub-section-related-posts',
				'default'           => divino_get_option( 'related-posts-structure' ),
				'priority'          => 12,
				'context'           => array(
					divino_Builder_Helper::$general_tab_config,
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'title'             => __( 'Posts Structure', 'divino' ),

				'choices'           => $related_structure_sub_controls,
				'divider'           => array( 'ast_class' => 'ast-top-divider' ),
			),
			/**
			 * Option: Meta Data Separator.
			 */
			array(
				'name'       => 'related-metadata-separator',
				'default'    => divino_get_option( 'related-metadata-separator', '/' ),
				'type'       => 'sub-control',
				'transport'  => 'postMessage',
				'parent'     => divino_THEME_SETTINGS . '[related-posts-structure]',
				'linked'     => 'title-meta',
				'section'    => 'ast-sub-section-related-posts',
				'priority'   => 10,
				'control'    => 'ast-selector',
				'title'      => __( 'Divider Type', 'divino' ),
				'choices'    => array(
					'/'    => '/',
					'-'    => '-',
					'|'    => '|',
					'•'    => '•',
					'none' => __( 'None', 'divino' ),
				),
				'responsive' => false,
				'renderAs'   => 'text',
			),
			array(
				'name'              => divino_THEME_SETTINGS . '[related-posts-meta-structure]',
				'type'              => 'control',
				'control'           => 'ast-sortable',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_multi_choices' ),
				'default'           => divino_get_option( 'related-posts-meta-structure' ),
				'context'           => array(
					divino_Builder_Helper::$general_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => divino_THEME_SETTINGS . '[related-posts-structure]',
						'operator' => 'contains',
						'value'    => 'title-meta',
					),
				),
				'section'           => 'ast-sub-section-related-posts',
				'priority'          => 12,
				'title'             => __( 'Meta', 'divino' ),
				'choices'           => array_merge(
					array(
						'comments' => __( 'Comments', 'divino' ),
					),
					$meta_config_options
				),
				'divider'           => array( 'ast_class' => 'ast-top-divider' ),
			),
			array(
				'name'                   => 'related-posts-image-ratio-type',
				'default'                => divino_get_option( 'related-posts-image-ratio-type', '' ),
				'type'                   => 'sub-control',
				'transport'              => 'postMessage',
				'parent'                 => divino_THEME_SETTINGS . '[related-posts-structure]',
				'section'                => 'ast-sub-section-related-posts',
				'linked'                 => 'featured-image',
				'priority'               => 5,
				'control'                => 'ast-selector',
				'title'                  => __( 'Image Ratio', 'divino' ),
				'choices'                => array(
					''           => __( 'Original', 'divino' ),
					'predefined' => __( 'Predefined', 'divino' ),
					'custom'     => __( 'Custom', 'divino' ),
				),
				'responsive'             => false,
				'renderAs'               => 'text',
				'contextual_sub_control' => true,
				'input_attrs'            => array(
					'dependents' => array(
						''           => array( 'related-posts-original-image-scale-description' ),
						'predefined' => array( 'related-posts-image-ratio-pre-scale' ),
						'custom'     => array( 'related-posts-image-custom-scale-width', 'related-posts-image-custom-scale-height', 'related-posts-custom-image-scale-description' ),
					),
				),
			),
			array(
				'name'       => 'related-posts-image-ratio-pre-scale',
				'default'    => divino_get_option( 'related-posts-image-ratio-pre-scale' ),
				'type'       => 'sub-control',
				'transport'  => 'postMessage',
				'parent'     => divino_THEME_SETTINGS . '[related-posts-structure]',
				'linked'     => 'featured-image',
				'section'    => 'ast-sub-section-related-posts',
				'priority'   => 10,
				'control'    => 'ast-selector',
				'choices'    => array(
					'1/1'  => __( '1:1', 'divino' ),
					'4/3'  => __( '4:3', 'divino' ),
					'16/9' => __( '16:9', 'divino' ),
					'2/1'  => __( '2:1', 'divino' ),
				),
				'responsive' => false,
				'renderAs'   => 'text',
			),
			array(
				'name'              => 'related-posts-image-custom-scale-width',
				'default'           => divino_get_option( 'related-posts-image-custom-scale-width', 16 ),
				'type'              => 'sub-control',
				'control'           => 'ast-number',
				'transport'         => 'postMessage',
				'parent'            => divino_THEME_SETTINGS . '[related-posts-structure]',
				'section'           => 'ast-sub-section-related-posts',
				'linked'            => 'featured-image',
				'priority'          => 11,
				'qty_selector'      => true,
				'title'             => __( 'Width', 'divino' ),
				'input_attrs'       => array(
					'style'       => 'text-align:center;',
					'placeholder' => __( 'Auto', 'divino' ),
					'min'         => 1,
					'max'         => 1000,
				),
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
			),
			array(
				'name'              => 'related-posts-image-custom-scale-height',
				'default'           => divino_get_option( 'related-posts-image-custom-scale-height', 9 ),
				'type'              => 'sub-control',
				'control'           => 'ast-number',
				'transport'         => 'postMessage',
				'parent'            => divino_THEME_SETTINGS . '[related-posts-structure]',
				'section'           => 'ast-sub-section-related-posts',
				'linked'            => 'featured-image',
				'priority'          => 12,
				'qty_selector'      => true,
				'title'             => __( 'Height', 'divino' ),
				'input_attrs'       => array(
					'style'       => 'text-align:center;',
					'placeholder' => __( 'Auto', 'divino' ),
					'min'         => 1,
					'max'         => 1000,
				),
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
			),
			array(
				'name'     => 'related-posts-custom-image-scale-description',
				'parent'   => divino_THEME_SETTINGS . '[related-posts-structure]',
				'linked'   => 'featured-image',
				'type'     => 'sub-control',
				'control'  => 'ast-description',
				'section'  => 'ast-sub-section-related-posts',
				'priority' => 14,
				'label'    => '',
				'help'     => sprintf( /* translators: 1: link open markup, 2: link close markup */ __( 'Calculate a personalized image ratio using this %1$s online tool %2$s for your image dimensions.', 'divino' ), '<a href="' . esc_url( 'https://www.digitalrebellion.com/webapps/aspectcalc' ) . '" target="_blank">', '</a>' ),
			),
			array(
				'name'        => 'related-posts-image-size',
				'default'     => divino_get_option( 'related-posts-image-size', 'large' ),
				'parent'      => divino_THEME_SETTINGS . '[related-posts-structure]',
				'section'     => 'ast-sub-section-related-posts',
				'linked'      => 'featured-image',
				'type'        => 'sub-control',
				'priority'    => 17,
				'transport'   => 'postMessage',
				'title'       => __( 'Image Size', 'divino' ),
				'divider'     => array( 'ast_class' => 'ast-top-divider' ),
				'control'     => 'ast-select',
				'choices'     => divino_get_site_image_sizes(),
				'description' => __( 'Note: Image Size & Ratio won\'t work if Image Position set as Background.', 'divino' ),
			),
			array(
				'name'      => 'related-posts-author-prefix-label',
				'default'   => divino_get_option( 'related-posts-author-prefix-label', divino_default_strings( 'string-blog-meta-author-by', false ) ),
				'parent'    => divino_THEME_SETTINGS . '[related-posts-meta-structure]',
				'linked'    => 'author',
				'type'      => 'sub-control',
				'control'   => 'ast-text-input',
				'section'   => 'ast-sub-section-related-posts',
				'divider'   => array( 'ast_class' => 'ast-bottom-divider ast-bottom-section-spacing' ),
				'title'     => __( 'Prefix Label', 'divino' ),
				'priority'  => 1,
				'transport' => 'postMessage',
			),
			array(
				'name'      => 'related-posts-author-avatar',
				'parent'    => divino_THEME_SETTINGS . '[related-posts-meta-structure]',
				'default'   => divino_get_option( 'related-posts-author-avatar' ),
				'linked'    => 'author',
				'type'      => 'sub-control',
				'control'   => 'ast-toggle',
				'section'   => 'ast-sub-section-related-posts',
				'priority'  => 5,
				'title'     => __( 'Author Avatar', 'divino' ),
				'transport' => 'postMessage',
			),
			array(
				'name'        => 'related-posts-author-avatar-size',
				'parent'      => divino_THEME_SETTINGS . '[related-posts-meta-structure]',
				'default'     => divino_get_option( 'related-posts-author-avatar-size', 30 ),
				'linked'      => 'author',
				'type'        => 'sub-control',
				'control'     => 'ast-slider',
				'transport'   => 'postMessage',
				'section'     => 'ast-sub-section-related-posts',
				'priority'    => 10,
				'title'       => __( 'Image Size', 'divino' ),
				'suffix'      => 'px',
				'input_attrs' => array(
					'min'  => 1,
					'step' => 1,
					'max'  => 200,
				),
			),
			array(
				'name'       => 'related-posts-meta-date-type',
				'parent'     => divino_THEME_SETTINGS . '[related-posts-meta-structure]',
				'type'       => 'sub-control',
				'control'    => 'ast-selector',
				'section'    => 'ast-sub-section-related-posts',
				'default'    => divino_get_option( 'related-posts-meta-date-type', 'published' ),
				'priority'   => 1,
				'linked'     => 'date',
				'transport'  => 'refresh',
				'title'      => __( 'Type', 'divino' ),
				'choices'    => array(
					'published' => __( 'Published', 'divino' ),
					'updated'   => __( 'Last Updated', 'divino' ),
				),
				'divider'    => array( 'ast_class' => 'ast-bottom-divider ast-bottom-spacing' ),
				'responsive' => false,
				'renderAs'   => 'text',
			),
			array(
				'name'       => 'related-posts-date-format',
				'default'    => divino_get_option( 'related-posts-date-format', '' ),
				'parent'     => divino_THEME_SETTINGS . '[related-posts-meta-structure]',
				'linked'     => 'date',
				'type'       => 'sub-control',
				'control'    => 'ast-select',
				'section'    => 'ast-sub-section-related-posts',
				'priority'   => 2,
				'responsive' => false,
				'renderAs'   => 'text',
				'title'      => __( 'Format', 'divino' ),
				'choices'    => array(
					''       => __( 'Default', 'divino' ),
					'F j, Y' => 'November 6, 2010',
					'Y-m-d'  => '2010-11-06',
					'm/d/Y'  => '11/06/2010',
					'd/m/Y'  => '06/11/2010',
				),
			),
			array(
				'name'       => 'related-posts-category-style',
				'parent'     => divino_THEME_SETTINGS . '[related-posts-meta-structure]',
				'type'       => 'sub-control',
				'control'    => 'ast-selector',
				'section'    => 'ast-sub-section-related-posts',
				'default'    => divino_get_option( 'related-posts-category-style', '' ),
				'priority'   => 2,
				'linked'     => 'category',
				'transport'  => 'refresh',
				'title'      => __( 'Style', 'divino' ),
				'choices'    => array(
					'none'      => __( 'Default', 'divino' ),
					'badge'     => __( 'Badge', 'divino' ),
					'underline' => __( 'Underline', 'divino' ),
				),
				'responsive' => false,
				'renderAs'   => 'text',
			),
			array(
				'name'       => 'related-posts-tag-style',
				'parent'     => divino_THEME_SETTINGS . '[related-posts-meta-structure]',
				'type'       => 'sub-control',
				'control'    => 'ast-selector',
				'section'    => 'ast-sub-section-related-posts',
				'default'    => divino_get_option( 'related-posts-tag-style', '' ),
				'priority'   => 2,
				'linked'     => 'tag',
				'transport'  => 'refresh',
				'title'      => __( 'Style', 'divino' ),
				'choices'    => array(
					'none'      => __( 'Default', 'divino' ),
					'badge'     => __( 'Badge', 'divino' ),
					'underline' => __( 'Underline', 'divino' ),
				),
				'responsive' => false,
				'renderAs'   => 'text',
			),

			/**
			 * Option: Enable excerpt for Related Posts.
			 */
			array(
				'name'     => divino_THEME_SETTINGS . '[enable-related-posts-excerpt]',
				'default'  => divino_get_option( 'enable-related-posts-excerpt' ),
				'type'     => 'control',
				'control'  => 'ast-toggle-control',
				'title'    => __( 'Enable Post Excerpt', 'divino' ),
				'section'  => 'ast-sub-section-related-posts',
				'priority' => 12,
				'context'  => array(
					divino_Builder_Helper::$general_tab_config,
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'divider'  => array( 'ast_class' => 'ast-top-divider' ),
			),

			/**
			 * Option: Excerpt word count for Related Posts
			 */
			array(
				'name'        => divino_THEME_SETTINGS . '[related-posts-excerpt-count]',
				'default'     => divino_get_option( 'related-posts-excerpt-count' ),
				'type'        => 'control',
				'control'     => 'ast-slider',
				'context'     => array(
					divino_Builder_Helper::$general_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts-excerpt]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'section'     => 'ast-sub-section-related-posts',
				'title'       => __( 'Excerpt Word Count', 'divino' ),
				'divider'     => array( 'ast_class' => 'ast-section-spacing' ),
				'priority'    => 12,
				'input_attrs' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 60,
				),
			),

			/**
			 * Option: No. of Related Posts
			 */
			array(
				'name'        => divino_THEME_SETTINGS . '[related-posts-total-count]',
				'default'     => divino_get_option( 'related-posts-total-count' ),
				'type'        => 'control',
				'control'     => 'ast-slider',
				'context'     => array(
					divino_Builder_Helper::$general_tab_config,
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'section'     => 'ast-sub-section-related-posts',
				'title'       => __( 'Total Number of Related Posts', 'divino' ),
				'priority'    => 11,
				'input_attrs' => array(
					'min'  => 1,
					'step' => 1,
					'max'  => 20,
				),
				'divider'     => array( 'ast_class' => 'ast-top-divider ast-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Columns
			 */
			array(
				'name'       => divino_THEME_SETTINGS . '[related-posts-grid-responsive]',
				'type'       => 'control',
				'control'    => 'ast-selector',
				'section'    => 'ast-sub-section-related-posts',
				'default'    => divino_get_option( 'related-posts-grid-responsive' ),
				'priority'   => 11,
				'context'    => array(
					divino_Builder_Helper::$general_tab_config,
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'title'      => __( 'Grid Column Layout', 'divino' ),
				'choices'    => array(
					'full'    => __( '1', 'divino' ),
					'2-equal' => __( '2', 'divino' ),
					'3-equal' => __( '3', 'divino' ),
					'4-equal' => __( '4', 'divino' ),
				),
				'responsive' => true,
				'renderAs'   => 'text',
				'divider'    => array( 'ast_class' => 'ast-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Query group setting
			 */
			array(
				'name'      => divino_THEME_SETTINGS . '[related-posts-query-group]',
				'default'   => divino_get_option( 'related-posts-query-group' ),
				'type'      => 'control',
				'transport' => 'postMessage',
				'control'   => 'ast-settings-group',
				'context'   => array(
					divino_Builder_Helper::$general_tab_config,
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'title'     => __( 'Posts Query', 'divino' ),
				'section'   => 'ast-sub-section-related-posts',
				'priority'  => 11,
			),

			/**
			 * Option: Related Posts based on.
			 */
			array(
				'name'       => 'related-posts-based-on',
				'default'    => divino_get_option( 'related-posts-based-on' ),
				'type'       => 'sub-control',
				'transport'  => 'postMessage',
				'parent'     => divino_THEME_SETTINGS . '[related-posts-query-group]',
				'section'    => 'ast-sub-section-related-posts',
				'priority'   => 1,
				'control'    => 'ast-selector',
				'divider'    => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				'title'      => __( 'Related Posts by', 'divino' ),
				'choices'    => array(
					'categories' => __( 'Categories', 'divino' ),
					'tags'       => __( 'Tags', 'divino' ),
				),
				'responsive' => false,
				'renderAs'   => 'text',
			),

			/**
			 * Option: Display Post Structure
			 */
			array(
				'name'      => 'related-posts-order-by',
				'default'   => divino_get_option( 'related-posts-order-by' ),
				'parent'    => divino_THEME_SETTINGS . '[related-posts-query-group]',
				'section'   => 'ast-sub-section-related-posts',
				'type'      => 'sub-control',
				'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				'priority'  => 2,
				'transport' => 'postMessage',
				'title'     => __( 'Order by', 'divino' ),
				'control'   => 'ast-select',
				'choices'   => array(
					'date'          => __( 'Date', 'divino' ),
					'title'         => __( 'Title', 'divino' ),
					'menu_order'    => __( 'Post Order', 'divino' ),
					'rand'          => __( 'Random', 'divino' ),
					'comment_count' => __( 'Comment Counts', 'divino' ),
				),
			),

			/**
			 * Option: Display Post Structure
			 */
			array(
				'name'       => 'related-posts-order',
				'parent'     => divino_THEME_SETTINGS . '[related-posts-query-group]',
				'section'    => 'ast-sub-section-related-posts',
				'type'       => 'sub-control',
				'transport'  => 'postMessage',
				'title'      => __( 'Order', 'divino' ),
				'default'    => divino_get_option( 'related-posts-order' ),
				'control'    => 'ast-selector',
				'priority'   => 3,
				'choices'    => array(
					'asc'  => __( 'Ascending', 'divino' ),
					'desc' => __( 'Descending', 'divino' ),
				),
				'responsive' => false,
				'renderAs'   => 'text',
			),

			array(
				'name'        => divino_THEME_SETTINGS . '[related-posts-box-placement]',
				'default'     => divino_get_option( 'related-posts-box-placement' ),
				'type'        => 'control',
				'section'     => 'ast-sub-section-related-posts',
				'priority'    => 12,
				'title'       => __( 'Section Placement', 'divino' ),
				'control'     => 'ast-selector',
				'description' => __( 'Decide whether to isolate or integrate the module with the entry content area.', 'divino' ),
				'divider'     => array( 'ast_class' => 'ast-top-divider' ),
				'choices'     => array(
					'default' => __( 'Default', 'divino' ),
					'inside'  => __( 'Contained', 'divino' ),
					'outside' => __( 'Separated', 'divino' ),
				),
				'context'     => array(
					divino_Builder_Helper::$general_tab_config,
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'responsive'  => false,
				'renderAs'    => 'text',
			),
			array(
				'name'        => divino_THEME_SETTINGS . '[related-posts-outside-location]',
				'default'     => divino_get_option( 'related-posts-outside-location' ),
				'type'        => 'control',
				'section'     => 'ast-sub-section-related-posts',
				'priority'    => 12,
				'title'       => __( 'Location', 'divino' ),
				'control'     => 'ast-selector',
				'choices'     => array(
					'below' => __( 'Below Comments', 'divino' ),
					'above' => __( 'Above Comments', 'divino' ),
				),
				'description' => __( 'To sync this option with comments, use the same positioning for both sections: Contained or Separated.', 'divino' ),
				'divider'     => array( 'ast_class' => 'ast-top-section-spacing' ),
				'context'     => array(
					divino_Builder_Helper::$general_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => divino_THEME_SETTINGS . '[related-posts-box-placement]',
						'operator' => '!=',
						'value'    => 'default',
					),
				),
				'responsive'  => false,
				'renderAs'    => 'text',
			),
			array(
				'name'       => divino_THEME_SETTINGS . '[related-posts-container-width]',
				'default'    => divino_get_option( 'related-posts-container-width' ),
				'type'       => 'control',
				'section'    => 'ast-sub-section-related-posts',
				'priority'   => 12,
				'title'      => __( 'Container Structure', 'divino' ),
				'control'    => 'ast-selector',
				'choices'    => array(
					'narrow' => __( 'Narrow', 'divino' ),
					''       => __( 'Full Width', 'divino' ),
				),
				'divider'    => array( 'ast_class' => 'ast-top-section-spacing' ),
				'context'    => array(
					divino_Builder_Helper::$general_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => divino_THEME_SETTINGS . '[related-posts-box-placement]',
						'operator' => '==',
						'value'    => 'outside',
					),
				),
				'responsive' => false,
				'renderAs'   => 'text',
			),

			/**
			 * Option: Related Posts colors setting group
			 */
			array(
				'name'      => divino_THEME_SETTINGS . '[related-posts-colors-group]',
				'default'   => divino_get_option( 'related-posts-colors-group' ),
				'type'      => 'control',
				'transport' => 'postMessage',
				'control'   => 'ast-settings-group',
				'context'   => array(
					divino_Builder_Helper::$design_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => divino_THEME_SETTINGS . '[related-posts-structure]',
						'operator' => 'contains',
						'value'    => 'title-meta',
					),
				),
				'title'     => __( 'Content Colors', 'divino' ),
				'section'   => 'ast-sub-section-related-posts',
				'priority'  => 15,
				'divider'   => array( 'ast_class' => 'ast-bottom-divider' ),
			),

			/**
			 * Option: Related Posts title typography setting group
			 */
			array(
				'name'      => divino_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
				'type'      => 'control',
				'priority'  => 16,
				'control'   => 'ast-settings-group',
				'is_font'   => true,
				'context'   => array(
					divino_Builder_Helper::$design_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => divino_THEME_SETTINGS . '[related-posts-title]',
						'operator' => '!=',
						'value'    => '',
					),
				),
				'title'     => __( 'Section Title Font', 'divino' ),
				'section'   => 'ast-sub-section-related-posts',
				'transport' => 'postMessage',
			),

			/**
			 * Option: Related Posts title typography setting group
			 */
			array(
				'name'      => divino_THEME_SETTINGS . '[related-posts-title-typography-group]',
				'type'      => 'control',
				'priority'  => 17,
				'control'   => 'ast-settings-group',
				'context'   => array(
					divino_Builder_Helper::$design_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => divino_THEME_SETTINGS . '[related-posts-structure]',
						'operator' => 'contains',
						'value'    => 'title-meta',
					),
				),
				'title'     => __( 'Post Title Font', 'divino' ),
				'is_font'   => true,
				'section'   => 'ast-sub-section-related-posts',
				'transport' => 'postMessage',
			),

			/**
			 * Option: Related Posts meta typography setting group
			 */
			array(
				'name'      => divino_THEME_SETTINGS . '[related-posts-meta-typography-group]',
				'type'      => 'control',
				'priority'  => 18,
				'control'   => 'ast-settings-group',
				'context'   => array(
					divino_Builder_Helper::$design_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => divino_THEME_SETTINGS . '[related-posts-structure]',
						'operator' => 'contains',
						'value'    => 'title-meta',
					),
				),
				'title'     => __( 'Meta Font', 'divino' ),
				'is_font'   => true,
				'section'   => 'ast-sub-section-related-posts',
				'transport' => 'postMessage',
			),

			/**
			 * Option: Related Posts content typography setting group
			 */
			array(
				'name'      => divino_THEME_SETTINGS . '[related-posts-content-typography-group]',
				'type'      => 'control',
				'priority'  => 21,
				'control'   => 'ast-settings-group',
				'is_font'   => true,
				'context'   => array(
					divino_Builder_Helper::$design_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts-excerpt]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'title'     => __( 'Content Font', 'divino' ),
				'section'   => 'ast-sub-section-related-posts',
				'transport' => 'postMessage',
			),

			/**
			 * Option: Related post block text color
			 */
			array(
				'name'      => 'related-posts-text-color',
				'tab'       => __( 'Normal', 'divino' ),
				'type'      => 'sub-control',
				'parent'    => divino_THEME_SETTINGS . '[related-posts-colors-group]',
				'section'   => 'ast-sub-section-related-posts',
				'default'   => divino_get_option( 'related-posts-text-color' ),
				'transport' => 'postMessage',
				'control'   => 'ast-color',
				'title'     => __( 'Text Color', 'divino' ),
			),

			/**
			 * Option: Related post block CTA link color
			 */
			array(
				'name'      => 'related-posts-link-color',
				'tab'       => __( 'Normal', 'divino' ),
				'type'      => 'sub-control',
				'parent'    => divino_THEME_SETTINGS . '[related-posts-colors-group]',
				'section'   => 'ast-sub-section-related-posts',
				'default'   => divino_get_option( 'related-posts-link-color' ),
				'transport' => 'postMessage',
				'control'   => 'ast-color',
				'title'     => __( 'Link Color', 'divino' ),
			),

			/**
			 * Option: Related post block BG color
			 */
			array(
				'name'              => divino_THEME_SETTINGS . '[related-posts-title-color]',
				'default'           => divino_get_option( 'related-posts-title-color' ),
				'type'              => 'control',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'section'           => 'ast-sub-section-related-posts',
				'transport'         => 'postMessage',
				'priority'          => 14,
				'context'           => array(
					divino_Builder_Helper::$design_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => divino_THEME_SETTINGS . '[related-posts-title]',
						'operator' => '!=',
						'value'    => '',
					),
				),
				'title'             => __( 'Section Title', 'divino' ),
				'divider'           => array( 'ast_class' => 'ast-top-section-spacing' ),
			),

			/**
			 * Option: Related post block BG color
			 */
			array(
				'name'              => divino_THEME_SETTINGS . '[related-posts-background-color]',
				'default'           => divino_get_option( 'related-posts-background-color' ),
				'type'              => 'control',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'section'           => 'ast-sub-section-related-posts',
				'transport'         => 'postMessage',
				'priority'          => 14,
				'context'           => array(
					divino_Builder_Helper::$design_tab_config,
					array(
						'setting'  => divino_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'title'             => __( 'Section Background', 'divino' ),
				'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
			),

			/**
			 * Option: Related post meta color
			 */
			array(
				'name'      => 'related-posts-meta-color',
				'default'   => divino_get_option( 'related-posts-meta-color' ),
				'tab'       => __( 'Normal', 'divino' ),
				'type'      => 'sub-control',
				'parent'    => divino_THEME_SETTINGS . '[related-posts-colors-group]',
				'section'   => 'ast-sub-section-related-posts',
				'transport' => 'postMessage',
				'control'   => 'ast-color',
				'title'     => __( 'Meta Color', 'divino' ),
			),

			/**
			 * Option: Related hover CTA link color
			 */
			array(
				'name'      => 'related-posts-link-hover-color',
				'type'      => 'sub-control',
				'tab'       => __( 'Hover', 'divino' ),
				'parent'    => divino_THEME_SETTINGS . '[related-posts-colors-group]',
				'section'   => 'ast-sub-section-related-posts',
				'control'   => 'ast-color',
				'default'   => divino_get_option( 'related-posts-link-hover-color' ),
				'transport' => 'postMessage',
				'title'     => __( 'Link Color', 'divino' ),
			),

			/**
			 * Option: Related hover meta link color
			 */
			array(
				'name'      => 'related-posts-meta-link-hover-color',
				'type'      => 'sub-control',
				'tab'       => __( 'Hover', 'divino' ),
				'parent'    => divino_THEME_SETTINGS . '[related-posts-colors-group]',
				'section'   => 'ast-sub-section-related-posts',
				'control'   => 'ast-color',
				'default'   => divino_get_option( 'related-posts-meta-link-hover-color' ),
				'transport' => 'postMessage',
				'title'     => __( 'Meta Link Color', 'divino' ),
			),

			/**
			 * Option: Related Posts Title Font Family
			 */
			array(
				'name'      => 'related-posts-title-font-family',
				'parent'    => divino_THEME_SETTINGS . '[related-posts-title-typography-group]',
				'section'   => 'ast-sub-section-related-posts',
				'type'      => 'sub-control',
				'control'   => 'ast-font',
				'font_type' => 'ast-font-family',
				'default'   => divino_get_option( 'related-posts-title-font-family' ),
				'title'     => __( 'Font Family', 'divino' ),
				'connect'   => divino_THEME_SETTINGS . '[related-posts-title-font-weight]',
				'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Title Font Weight
			 */
			array(
				'name'              => 'related-posts-title-font-weight',
				'parent'            => divino_THEME_SETTINGS . '[related-posts-title-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-font',
				'font_type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'default'           => divino_get_option( 'related-posts-title-font-weight' ),
				'title'             => __( 'Font Weight', 'divino' ),
				'connect'           => 'related-posts-title-font-family',
				'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Title Font Size
			 */

			array(
				'name'              => 'related-posts-title-font-size',
				'parent'            => divino_THEME_SETTINGS . '[related-posts-title-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-responsive-slider',
				'default'           => divino_get_option( 'related-posts-title-font-size' ),
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
			 * Option: Related Posts Title Font Extras
			 */
			array(
				'name'    => 'related-posts-title-font-extras',
				'type'    => 'sub-control',
				'parent'  => divino_THEME_SETTINGS . '[related-posts-title-typography-group]',
				'control' => 'ast-font-extras',
				'section' => 'ast-sub-section-related-posts',
				'default' => divino_get_option( 'related-posts-title-font-extras' ),
				'title'   => __( 'Font Extras', 'divino' ),
			),

			/**
			 * Option: Related Posts Title Font Family
			 */
			array(
				'name'      => 'related-posts-section-title-font-family',
				'parent'    => divino_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
				'section'   => 'ast-sub-section-related-posts',
				'type'      => 'sub-control',
				'control'   => 'ast-font',
				'font_type' => 'ast-font-family',
				'default'   => divino_get_option( 'related-posts-section-title-font-family' ),
				'title'     => __( 'Font Family', 'divino' ),
				'connect'   => divino_THEME_SETTINGS . '[related-posts-section-title-font-weight]',
				'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Title Font Weight
			 */
			array(
				'name'              => 'related-posts-section-title-font-weight',
				'parent'            => divino_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-font',
				'font_type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'default'           => divino_get_option( 'related-posts-section-title-font-weight' ),
				'title'             => __( 'Font Weight', 'divino' ),
				'connect'           => 'related-posts-section-title-font-family',
				'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Title Font Size
			 */

			array(
				'name'              => 'related-posts-section-title-font-size',
				'parent'            => divino_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-responsive-slider',
				'default'           => divino_get_option( 'related-posts-section-title-font-size' ),
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
			 * Option: Related Posts Title Font Extras
			 */
			array(
				'name'    => 'related-posts-section-title-font-extras',
				'type'    => 'sub-control',
				'parent'  => divino_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
				'control' => 'ast-font-extras',
				'section' => 'ast-sub-section-related-posts',
				'default' => divino_get_option( 'related-posts-section-title-font-extras' ),
				'title'   => __( 'Font Extras', 'divino' ),
			),

			/**
			 * Option: Related Posts Meta Font Family
			 */
			array(
				'name'      => 'related-posts-meta-font-family',
				'parent'    => divino_THEME_SETTINGS . '[related-posts-meta-typography-group]',
				'section'   => 'ast-sub-section-related-posts',
				'type'      => 'sub-control',
				'control'   => 'ast-font',
				'font_type' => 'ast-font-family',
				'default'   => divino_get_option( 'related-posts-meta-font-family' ),
				'title'     => __( 'Font Family', 'divino' ),
				'connect'   => divino_THEME_SETTINGS . '[related-posts-meta-font-weight]',
				'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Meta Font Weight
			 */
			array(
				'name'              => 'related-posts-meta-font-weight',
				'parent'            => divino_THEME_SETTINGS . '[related-posts-meta-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-font',
				'font_type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'default'           => divino_get_option( 'related-posts-meta-font-weight' ),
				'title'             => __( 'Font Weight', 'divino' ),
				'connect'           => 'related-posts-meta-font-family',
				'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Meta Font Size
			 */

			array(
				'name'              => 'related-posts-meta-font-size',
				'parent'            => divino_THEME_SETTINGS . '[related-posts-meta-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-responsive-slider',
				'default'           => divino_get_option( 'related-posts-meta-font-size' ),
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
			 * Option: Related Posts Meta Font Extras
			 */
			array(
				'name'    => 'related-posts-meta-font-extras',
				'type'    => 'sub-control',
				'parent'  => divino_THEME_SETTINGS . '[related-posts-meta-typography-group]',
				'control' => 'ast-font-extras',
				'section' => 'ast-sub-section-related-posts',
				'default' => divino_get_option( 'related-posts-meta-font-extras' ),
				'title'   => __( 'Font Extras', 'divino' ),
			),

			/**
			 * Option: Related Posts Content Font Family
			 */
			array(
				'name'      => 'related-posts-content-font-family',
				'parent'    => divino_THEME_SETTINGS . '[related-posts-content-typography-group]',
				'section'   => 'ast-sub-section-related-posts',
				'type'      => 'sub-control',
				'control'   => 'ast-font',
				'font_type' => 'ast-font-family',
				'default'   => divino_get_option( 'related-posts-content-font-family' ),
				'title'     => __( 'Font Family', 'divino' ),
				'connect'   => divino_THEME_SETTINGS . '[related-posts-content-font-weight]',
				'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Content Font Weight
			 */
			array(
				'name'              => 'related-posts-content-font-weight',
				'parent'            => divino_THEME_SETTINGS . '[related-posts-content-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-font',
				'font_type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'default'           => divino_get_option( 'related-posts-content-font-weight' ),
				'title'             => __( 'Font Weight', 'divino' ),
				'connect'           => 'related-posts-content-font-family',
				'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Content Font Size
			 */
			array(
				'name'              => 'related-posts-content-font-size',
				'parent'            => divino_THEME_SETTINGS . '[related-posts-content-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-responsive-slider',
				'default'           => divino_get_option( 'related-posts-content-font-size' ),
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
			 * Option: Related Posts Content Font Extras.
			 */
			/**
			 * Option: Related Posts Meta Font Extras
			 */
			array(
				'name'    => 'related-posts-content-font-extras',
				'type'    => 'sub-control',
				'parent'  => divino_THEME_SETTINGS . '[related-posts-content-typography-group]',
				'control' => 'ast-font-extras',
				'section' => 'ast-sub-section-related-posts',
				'default' => divino_get_option( 'related-posts-content-font-extras' ),
				'title'   => __( 'Font Extras', 'divino' ),
			),
		);

		$_configs = array_merge( $_configs, divino_Extended_Base_Configuration::prepare_section_spacing_border_options( 'ast-sub-section-related-posts' ) );

		return array_merge( $configurations, $_configs );
	}
}

/**
 *  Kicking this off by creating NEW instance.
 */
new divino_Related_Posts_Configs();
