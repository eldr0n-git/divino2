<?php
/**
 * Styling Options for divino Theme.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.0.15
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'divino_Archive_Typo_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Archive_Typo_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register Archive Typography Customizer Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array();

			// Learn More link if divino Pro is not activated.
			if ( divino_showcase_upgrade_notices() ) {

				$_configs = array(

					/**
					 * Option: divino Pro items for blog pro.
					 */
					array(
						'name'     => divino_THEME_SETTINGS . '[ast-blog-pro-items]',
						'type'     => 'control',
						'control'  => 'ast-upgrade',
						'campaign' => 'blog-archive',
						'choices'  => array(
							'one'    => array(
								'title' => __( 'Posts Filter', 'divino' ),
							),
							'eleven' => array(
								'title' => __( 'Posts Reveal Effect', 'divino' ),
							),
							'two'    => array(
								'title' => __( 'Grid, Masonry layout', 'divino' ),
							),
							'twelve' => array(
								'title' => __( 'Extended Meta Options', 'divino' ),
							),
							'three'  => array(
								'title' => __( 'Custom image size', 'divino' ),
							),
							'four'   => array(
								'title' => __( 'Archive pagination', 'divino' ),
							),
							'six'    => array(
								'title' => __( 'Extended typography', 'divino' ),
							),
							'seven'  => array(
								'title' => __( 'Extended spacing', 'divino' ),
							),
							'eight'  => array(
								'title' => __( 'Archive read time', 'divino' ),
							),
							'nine'   => array(
								'title' => __( 'Archive excerpt', 'divino' ),
							),
						),
						'section'  => 'section-blog',
						'default'  => '',
						'priority' => 999,
						'context'  => array(),
						'title'    => __( 'Take your blog to the next level with powerful design features.', 'divino' ),
						'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
					),
				);
			}

			if ( ! defined( 'divino_EXT_VER' ) || ( defined( 'divino_EXT_VER' ) && ! divino_Ext_Extension::is_active( 'typography' ) ) ) {
				$new_configs = array(
					/**
					 * Option: Blog - Post Title Font Size
					 */
					array(
						'name'              => divino_THEME_SETTINGS . '[font-size-page-title]',
						'control'           => 'ast-responsive-slider',
						'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
						'section'           => 'section-blog',
						'type'              => 'control',
						'transport'         => 'postMessage',
						'title'             => __( 'Post Title Size', 'divino' ),
						'priority'          => 140,
						'default'           => divino_get_option( 'font-size-page-title' ),
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
						'context'           => divino_Builder_Helper::$design_tab,
						'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
					),
					array(
						'name'              => divino_THEME_SETTINGS . '[font-size-post-meta]',
						'control'           => 'ast-responsive-slider',
						'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
						'section'           => 'section-blog',
						'type'              => 'control',
						'transport'         => 'postMessage',
						'title'             => __( 'Meta Font Size', 'divino' ),
						'is_font'           => true,
						'priority'          => 140,
						'default'           => divino_get_option( 'font-size-post-meta' ),
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
						'context'           => divino_Builder_Helper::$design_tab,
						'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
					),
					array(
						'name'              => divino_THEME_SETTINGS . '[font-size-post-tax]',
						'control'           => 'ast-responsive-slider',
						'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
						'section'           => 'section-blog',
						'type'              => 'control',
						'transport'         => 'postMessage',
						'title'             => __( 'Taxonomy Font', 'divino' ),
						'is_font'           => true,
						'priority'          => 140,
						'default'           => divino_get_option( 'font-size-post-tax' ),
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
						'context'           => array(
							divino_Builder_Helper::$design_tab_config,
							array(
								'relation' => 'OR',
								array(
									'setting'  => divino_THEME_SETTINGS . '[blog-post-structure]',
									'operator' => 'contains',
									'value'    => 'category',
								),
								array(
									'setting'  => divino_THEME_SETTINGS . '[blog-post-structure]',
									'operator' => 'contains',
									'value'    => 'tag',
								),
								array(
									'setting'  => divino_THEME_SETTINGS . '[blog-meta]',
									'operator' => 'contains',
									'value'    => 'category',
								),
								array(
									'setting'  => divino_THEME_SETTINGS . '[blog-meta]',
									'operator' => 'contains',
									'value'    => 'tag',
								),
							),
						),
						'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
					),
				);
				$_configs    = array_merge( $_configs, $new_configs );
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Archive_Typo_Configs();
