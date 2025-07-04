<?php
/**
 * Bottom Footer Options for divino Theme.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'divino_Blog_Single_Layout_Configs' ) ) {

	/**
	 * Register Blog Single Layout Configurations.
	 */
	class divino_Blog_Single_Layout_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register Blog Single Layout Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			/** @psalm-suppress DocblockTypeContradiction */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$tab_config = divino_Builder_Helper::$design_tab;

			$_configs = array(

				/**
				 * Option: Single Post Content Width
				 */
				array(
					'name'       => divino_THEME_SETTINGS . '[blog-single-width]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'section-blog-single',
					'default'    => divino_get_option( 'blog-single-width' ),
					'priority'   => 6,
					'title'      => __( 'Content Width', 'divino' ),
					'choices'    => array(
						'default' => __( 'Default', 'divino' ),
						'custom'  => __( 'Custom', 'divino' ),
					),
					'transport'  => 'postMessage',
					'responsive' => false,
					'divider'    => array( 'ast_class' => 'ast-top-section-divider' ),
					'renderAs'   => 'text',
				),

				/**
				 * Option: Enter Width
				 */
				array(
					'name'        => divino_THEME_SETTINGS . '[blog-single-max-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => 'section-blog-single',
					'transport'   => 'postMessage',
					'default'     => divino_get_option( 'blog-single-max-width' ),
					'context'     => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[blog-single-width]',
							'operator' => '===',
							'value'    => 'custom',
						),
					),
					'priority'    => 6,
					'title'       => __( 'Custom Width', 'divino' ),
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 1920,
					),
					'divider'     => array( 'ast_class' => 'ast-top-divider' ),
				),

				/**
				 * Option: Content images shadow
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[single-content-images-shadow]',
					'default'  => divino_get_option( 'single-content-images-shadow' ),
					'type'     => 'control',
					'section'  => 'section-blog-single',
					'title'    => __( 'Content Images Box Shadow', 'divino' ),
					'control'  => 'ast-toggle-control',
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
					'priority' => 9,
					'context'  => divino_Builder_Helper::$general_tab,
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[section-blog-single-spacing-divider]',
					'section'  => 'section-blog-single',
					'title'    => __( 'Post Spacing', 'divino' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 24,
					'context'  => $tab_config,
				),

				/**
				 * Option: Single Post Spacing
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[single-post-outside-spacing]',
					'default'           => divino_get_option( 'single-post-outside-spacing' ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'section'           => 'section-blog-single',
					'title'             => __( 'Outside', 'divino' ),
					'linked_choices'    => true,
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'divino' ),
						'right'  => __( 'Right', 'divino' ),
						'bottom' => __( 'Bottom', 'divino' ),
						'left'   => __( 'Left', 'divino' ),
					),
					'priority'          => 25,
					'context'           => $tab_config,
					'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: Single Post Margin
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[single-post-inside-spacing]',
					'default'           => divino_get_option( 'single-post-inside-spacing' ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'section'           => 'section-blog-single',
					'title'             => __( 'Inside', 'divino' ),
					'linked_choices'    => true,
					'transport'         => 'refresh',
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'divino' ),
						'right'  => __( 'Right', 'divino' ),
						'bottom' => __( 'Bottom', 'divino' ),
						'left'   => __( 'Left', 'divino' ),
					),
					'priority'          => 30,
					'divider'           => array( 'ast_class' => 'ast-top-divider' ),
					'context'           => $tab_config,
				),
			);

			$_configs[] = array(
				'name'        => 'section-blog-single-ast-context-tabs',
				'section'     => 'section-blog-single',
				'type'        => 'control',
				'control'     => 'ast-builder-header-control',
				'priority'    => 0,
				'description' => '',
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Blog_Single_Layout_Configs();
