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

if ( ! class_exists( 'divino_Footer_Layout_Configs' ) ) {

	/**
	 * Register Footer Layout Configurations.
	 */
	class divino_Footer_Layout_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register Footer Layout Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Footer Bar Layout
				 */

				array(
					'name'              => divino_THEME_SETTINGS . '[footer-sml-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_choices' ),
					'default'           => divino_get_option( 'footer-sml-layout' ),
					'section'           => 'section-footer-small',
					'priority'          => 5,
					'title'             => __( 'Layout', 'divino' ),
					'choices'           => array(
						'disabled'            => array(
							'label' => __( 'Disabled', 'divino' ),
							'path'  => divino_Builder_UI_Controller::fetch_svg_icon( 'disabled' ),
						),
						'footer-sml-layout-1' => array(
							'label' => __( 'Footer Bar Layout 1', 'divino' ),
							'path'  => divino_Builder_UI_Controller::fetch_svg_icon( 'footer-layout-1' ),
						),
						'footer-sml-layout-2' => array(
							'label' => __( 'Footer Bar Layout 2', 'divino' ),
							'path'  => divino_Builder_UI_Controller::fetch_svg_icon( 'footer-layout-2' ),
						),
					),
					'partial'           => array(
						'selector'            => '.ast-small-footer',
						'container_inclusive' => false,
					),
				),

				/**
				 *  Section: Section 1
				 */
				array(
					'name'       => divino_THEME_SETTINGS . '[footer-sml-section-1]',
					'control'    => 'ast-selector',
					'default'    => divino_get_option( 'footer-sml-section-1' ),
					'type'       => 'control',
					'context'    => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[footer-sml-layout]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
					'section'    => 'section-footer-small',
					'priority'   => 15,
					'title'      => __( 'Section 1', 'divino' ),
					'divider'    => array( 'ast_class' => 'ast-top-divider' ),
					'choices'    => array(
						''       => __( 'None', 'divino' ),
						'custom' => __( 'Text', 'divino' ),
						'widget' => __( 'Widget', 'divino' ),
						'menu'   => __( 'Footer Menu', 'divino' ),
					),
					'partial'    => array(
						'selector'            => '.ast-small-footer .ast-container .ast-footer-widget-1-area .ast-no-widget-row, .ast-small-footer .ast-container .ast-small-footer-section-1 .footer-primary-navigation .nav-menu',
						'container_inclusive' => false,
					),
					'responsive' => false,
					'renderAs'   => 'text',
				),
				/**
				 * Option: Section 1 Custom Text
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[footer-sml-section-1-credit]',
					'default'   => divino_get_option( 'footer-sml-section-1-credit' ),
					'type'      => 'control',
					'control'   => 'textarea',
					'transport' => 'postMessage',
					'section'   => 'section-footer-small',
					'context'   => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[footer-sml-section-1]',
							'operator' => '==',
							'value'    => array( 'custom' ),
						),
						array(
							'setting'  => divino_THEME_SETTINGS . '[footer-sml-layout]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
					'priority'  => 20,
					'title'     => __( 'Section 1 Custom Text', 'divino' ),
					'choices'   => array(
						''       => __( 'None', 'divino' ),
						'custom' => __( 'Custom Text', 'divino' ),
						'widget' => __( 'Widget', 'divino' ),
						'menu'   => __( 'Footer Menu', 'divino' ),
					),
					'partial'   => array(
						'selector'            => '.ast-small-footer .ast-container .ast-small-footer-section.ast-small-footer-section-1:has(> .ast-footer-site-title)',
						'container_inclusive' => false,
						'render_callback'     => 'divino_Customizer_Partials::render_footer_sml_section_1_credit',
					),
				),

				/**
				 * Option: Section 2
				 */
				array(
					'name'       => divino_THEME_SETTINGS . '[footer-sml-section-2]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'default'    => divino_get_option( 'footer-sml-section-2' ),
					'context'    => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[footer-sml-layout]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
					'section'    => 'section-footer-small',
					'priority'   => 25,
					'title'      => __( 'Section 2', 'divino' ),
					'choices'    => array(
						''       => __( 'None', 'divino' ),
						'custom' => __( 'Text', 'divino' ),
						'widget' => __( 'Widget', 'divino' ),
						'menu'   => __( 'Footer Menu', 'divino' ),
					),
					'partial'    => array(
						'selector'            => '.ast-small-footer .ast-container .ast-footer-widget-2-area .ast-no-widget-row, .ast-small-footer .ast-container .ast-small-footer-section-2 .footer-primary-navigation .nav-menu',
						'container_inclusive' => false,
					),
					'responsive' => false,
					'renderAs'   => 'text',
				),

				/**
				 * Option: Section 2 Custom Text
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[footer-sml-section-2-credit]',
					'type'      => 'control',
					'control'   => 'textarea',
					'transport' => 'postMessage',
					'default'   => divino_get_option( 'footer-sml-section-2-credit' ),
					'section'   => 'section-footer-small',
					'priority'  => 30,
					'context'   => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[footer-sml-section-2]',
							'operator' => '==',
							'value'    => 'custom',
						),
					),
					'title'     => __( 'Section 2 Custom Text', 'divino' ),
					'partial'   => array(
						'selector'            => '.ast-small-footer-section-2',
						'container_inclusive' => false,
						'render_callback'     => 'divino_Customizer_Partials::render_footer_sml_section_2_credit',
					),
					'partial'   => array(
						'selector'            => '.ast-small-footer .ast-container .ast-small-footer-section.ast-small-footer-section-2:has(> .ast-footer-site-title)',
						'container_inclusive' => false,
					),
				),

				/**
				 * Option: Footer Top Border
				 */
				array(
					'name'        => divino_THEME_SETTINGS . '[footer-sml-divider]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'default'     => divino_get_option( 'footer-sml-divider' ),
					'section'     => 'section-footer-small',
					'priority'    => 40,
					'suffix'      => 'px',
					'context'     => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[footer-sml-layout]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
					'title'       => __( 'Border Size', 'divino' ),
					'transport'   => 'postMessage',
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 600,
					),
				),

				/**
				 * Option: Footer Top Border Color
				 */

				array(
					'name'              => divino_THEME_SETTINGS . '[footer-sml-divider-color]',
					'section'           => 'section-footer-small',
					'default'           => divino_get_option( 'footer-sml-divider-color', '#7a7a7a' ),
					'type'              => 'control',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
					'context'           => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[footer-sml-divider]',
							'operator' => '>=',
							'value'    => 1,
						),
						array(
							'setting'  => divino_THEME_SETTINGS . '[footer-sml-layout]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
					'priority'          => 45,
					'title'             => __( 'Border Color', 'divino' ),
					'transport'         => 'postMessage',
				),

				/**
				 * Option: Footer Bar Content Group
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[footer-bar-background-group]',
					'default'   => divino_get_option( 'footer-bar-background-group' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Background Color', 'divino' ),
					'section'   => 'section-footer-small',
					'transport' => 'postMessage',
					'divider'   => array( 'ast_class' => 'ast-bottom-divider' ),
					'priority'  => 47,
					'context'   => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[footer-sml-layout]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
				),

				/**
				 * Option: Footer Bar Content Group
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[footer-bar-content-group]',
					'default'   => divino_get_option( 'footer-bar-content-group' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Content Colors', 'divino' ),
					'section'   => 'section-footer-small',
					'transport' => 'postMessage',
					'priority'  => 47,
					'context'   => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[footer-sml-layout]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
				),

				/**
				 * Option: Footer Bar Content Group
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[footer-bar-link-color-group]',
					'default'   => divino_get_option( 'footer-bar-link-color-group' ),
					'type'      => 'control',
					'control'   => 'ast-color-group',
					'title'     => __( 'Link Color', 'divino' ),
					'section'   => 'section-footer-small',
					'transport' => 'postMessage',
					'priority'  => 47,
					'divider'   => array( 'ast_class' => 'ast-bottom-divider' ),
					'context'   => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[footer-sml-layout]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
				),

				/**
				 * Option: Header Width
				 */
				array(
					'name'       => divino_THEME_SETTINGS . '[footer-layout-width]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'default'    => divino_get_option( 'footer-layout-width' ),
					'section'    => 'section-footer-small',
					'divider'    => array( 'ast_class' => 'ast-top-divider ast-bottom-divider' ),
					'context'    => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[site-layout]',
							'operator' => '!=',
							'value'    => 'ast-box-layout',
						),
						array(
							'setting'  => divino_THEME_SETTINGS . '[site-layout]',
							'operator' => '!=',
							'value'    => 'ast-fluid-width-layout',
						),
						array(
							'setting'  => divino_THEME_SETTINGS . '[footer-sml-layout]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
					'priority'   => 35,
					'title'      => __( 'Width', 'divino' ),
					'choices'    => array(
						'full'    => __( 'Full Width', 'divino' ),
						'content' => __( 'Content Width', 'divino' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
				),

				/**
				 * Option: Footer Top Border
				 */
				array(
					'name'        => divino_THEME_SETTINGS . '[footer-adv-border-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'transport'   => 'postMessage',
					'section'     => 'section-footer-adv',
					'default'     => divino_get_option( 'footer-adv-border-width' ),
					'priority'    => 40,
					'context'     => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[footer-adv]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
					'suffix'      => 'px',
					'title'       => __( 'Top Border Size', 'divino' ),
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 600,
					),
				),

				/**
				 * Option: Footer Top Border Color
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[footer-adv-border-color]',
					'section'           => 'section-footer-adv',
					'title'             => __( 'Top Border Color', 'divino' ),
					'type'              => 'control',
					'transport'         => 'postMessage',
					'control'           => 'ast-color',
					'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'default'           => divino_get_option( 'footer-adv-border-color' ),
					'context'           => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[footer-adv]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
					'priority'          => 45,
				),
			);

			$configurations = array_merge( $configurations, $_configs );

			// Learn More link if divino Pro is not activated.
			if ( ! defined( 'divino_EXT_VER' ) || ( defined( 'divino_EXT_VER' ) && false === divino_Ext_Extension::is_active( 'advanced-footer' ) ) ) {

				$config = array(

					/**
					 * Option: Footer Widgets Layout Layout
					 */
					array(
						'name'              => divino_THEME_SETTINGS . '[footer-adv]',
						'type'              => 'control',
						'priority'          => 0,
						'control'           => 'ast-radio-image',
						'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_choices' ),
						'default'           => divino_get_option( 'footer-adv' ),
						'title'             => __( 'Layout', 'divino' ),
						'section'           => 'section-footer-adv',
						'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
						'choices'           => array(
							'disabled' => array(
								'label' => __( 'Disable', 'divino' ),
								'path'  => divino_Builder_UI_Controller::fetch_svg_icon( 'disabled' ),
							),
							'layout-4' => array(
								'label' => __( 'Layout 4', 'divino' ),
								'path'  => divino_Builder_UI_Controller::fetch_svg_icon( 'footer-layout-4' ),
							),
						),
						'partial'           => array(
							'selector'            => '.footer-adv .ast-container',
							'container_inclusive' => false,
						),
					),

					/**
					 * Option: Learn More about Footer Widget
					 */
					array(
						'name'     => divino_THEME_SETTINGS . '[ast-footer-widget-more-feature-description]',
						'type'     => 'control',
						'control'  => 'ast-description',
						'section'  => 'section-footer-adv',
						'priority' => 999,
						'label'    => '',
						'help'     => '<span>' . __( 'More Options Available in divino Pro!', 'divino' ) . '</span><a style="display: block;" href="' . divino_get_upgrade_url( 'customizer' ) . '" class="button button-secondary"  target="_blank" rel="noopener">' . __( 'Learn More', 'divino' ) . '</a>',
						'settings' => array(),
					),

				);

				$configurations = array_merge( $configurations, $config );
			}

			return $configurations;
		}
	}
}

new divino_Footer_Layout_Configs();
