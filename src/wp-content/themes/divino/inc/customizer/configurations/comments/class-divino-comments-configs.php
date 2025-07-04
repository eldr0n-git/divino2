<?php
/**
 * Comments options for divino Theme.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 4.6.0
 */

if ( ! class_exists( 'divino_Comments_Configs' ) ) {

	/**
	 * Register Comments Customizer Configurations.
	 */
	class divino_Comments_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register Comments Customizer Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.8.0
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			$parent_section = 'section-blog-single';
			$_configs       = array(
				array(
					'name'        => 'comments-section-ast-context-tabs',
					'section'     => 'ast-sub-section-comments',
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
					'context'     => array(),
				),
				array(
					'name'     => 'ast-sub-section-comments',
					'title'    => __( 'Comments', 'divino' ),
					'type'     => 'section',
					'section'  => $parent_section,
					'panel'    => '',
					'priority' => 1,
				),
				array(
					'name'     => divino_THEME_SETTINGS . '[comments-single-section-heading]',
					'section'  => $parent_section,
					'type'     => 'control',
					'control'  => 'ast-heading',
					'title'    => __( 'Comments', 'divino' ),
					'priority' => 20,
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
				),
				array(
					'name'     => divino_THEME_SETTINGS . '[enable-comments-area]',
					'type'     => 'control',
					'default'  => divino_get_option( 'enable-comments-area' ),
					'control'  => 'ast-section-toggle',
					'section'  => $parent_section,
					'priority' => 20,
					'linked'   => 'ast-sub-section-comments',
					'linkText' => __( 'Comments', 'divino' ),
					'divider'  => array( 'ast_class' => 'ast-bottom-divider ast-bottom-section-divider' ),
				),
				array(
					'name'        => divino_THEME_SETTINGS . '[comments-box-placement]',
					'default'     => divino_get_option( 'comments-box-placement' ),
					'type'        => 'control',
					'section'     => 'ast-sub-section-comments',
					'priority'    => 20,
					'title'       => __( 'Section Placement', 'divino' ),
					'control'     => 'ast-selector',
					'description' => __( 'Decide whether to isolate or integrate the module with the entry content area.', 'divino' ),
					'choices'     => array(
						''        => __( 'Default', 'divino' ),
						'inside'  => __( 'Contained', 'divino' ),
						'outside' => __( 'Separated', 'divino' ),
					),
					'divider'     => array( 'ast_class' => 'ast-section-spacing' ),
					'context'     => divino_Builder_Helper::$general_tab,
					'responsive'  => false,
					'renderAs'    => 'text',
				),
				array(
					'name'       => divino_THEME_SETTINGS . '[comments-box-container-width]',
					'default'    => divino_get_option( 'comments-box-container-width' ),
					'type'       => 'control',
					'section'    => 'ast-sub-section-comments',
					'priority'   => 20,
					'title'      => __( 'Container Structure', 'divino' ),
					'control'    => 'ast-selector',
					'choices'    => array(
						'narrow' => __( 'Narrow', 'divino' ),
						''       => __( 'Full Width', 'divino' ),
					),
					'context'    => array(
						divino_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => divino_THEME_SETTINGS . '[comments-box-placement]',
							'operator' => '==',
							'value'    => 'outside',
						),
					),
					'divider'    => array( 'ast_class' => 'ast-top-section-spacing' ),
					'responsive' => false,
					'renderAs'   => 'text',
				),
				array(
					'name'       => divino_THEME_SETTINGS . '[comment-form-position]',
					'default'    => divino_get_option( 'comment-form-position' ),
					'type'       => 'control',
					'section'    => 'ast-sub-section-comments',
					'priority'   => 20,
					'title'      => __( 'Form Position', 'divino' ),
					'control'    => 'ast-selector',
					'choices'    => array(
						'below' => __( 'Below Comments', 'divino' ),
						'above' => __( 'Above Comments', 'divino' ),
					),
					'context'    => divino_Builder_Helper::$general_tab,
					'divider'    => array( 'ast_class' => 'ast-top-section-divider' ),
					'responsive' => false,
					'renderAs'   => 'text',
				),
			);

			$_configs = array_merge( $_configs, divino_Extended_Base_Configuration::prepare_section_spacing_border_options( 'ast-sub-section-comments', true ) );

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by creating new instance.
 */
new divino_Comments_Configs();
