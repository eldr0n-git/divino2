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

if ( ! class_exists( 'divino_Sidebar_Layout_Configs' ) ) {

	/**
	 * Register divino Sidebar Layout Configurations.
	 */
	class divino_Sidebar_Layout_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register divino Sidebar Layout Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Default Sidebar Position
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[site-sidebar-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => 'section-sidebars',
					'default'           => divino_get_option( 'site-sidebar-layout' ),
					'priority'          => 5,
					'description'       => __( 'Sidebar will only apply when container layout is set to normal.', 'divino' ),
					'title'             => __( 'Default Layout', 'divino' ),
					'choices'           => array(
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

				/**
				 * Option: Site Sidebar Style.
				 */
				array(
					'name'       => divino_THEME_SETTINGS . '[site-sidebar-style]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'section-sidebars',
					'default'    => divino_get_option( 'site-sidebar-style', 'unboxed' ),
					'priority'   => 9,
					'title'      => __( 'Sidebar Style', 'divino' ),
					'choices'    => array(
						'unboxed' => __( 'Unboxed', 'divino' ),
						'boxed'   => __( 'Boxed', 'divino' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
					'divider'    => array( 'ast_class' => 'ast-top-divider ast-bottom-section-divider' ),
				),

				/**
				 * Option: Primary Content Width
				 */
				array(
					'name'        => divino_THEME_SETTINGS . '[site-sidebar-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'default'     => divino_get_option( 'site-sidebar-width' ),
					'section'     => 'section-sidebars',
					'priority'    => 15,
					'title'       => __( 'Sidebar Width', 'divino' ),
					'suffix'      => '%',
					'transport'   => 'postMessage',
					'input_attrs' => array(
						'min'  => 15,
						'step' => 1,
						'max'  => 50,
					),

				),

				array(
					'name'     => divino_THEME_SETTINGS . '[site-sidebar-width-description]',
					'type'     => 'control',
					'control'  => 'ast-description',
					'section'  => 'section-sidebars',
					'priority' => 15,
					'title'    => '',
					'help'     => __( 'Sidebar width will apply only when one of the above sidebar is set.', 'divino' ),
					'divider'  => array( 'ast_class' => 'ast-bottom-section-divider' ),
					'settings' => array(),
				),

				/**
				 * Option: Sticky Sidebar
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[site-sticky-sidebar]',
					'default'  => divino_get_option( 'site-sticky-sidebar' ),
					'type'     => 'control',
					'section'  => 'section-sidebars',
					'title'    => __( 'Enable Sticky Sidebar', 'divino' ),
					'priority' => 15,
					'control'  => 'ast-toggle-control',
					'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
				),
			);

			// Learn More link if divino Pro is not activated.
			if ( divino_showcase_upgrade_notices() ) {
				$_configs[] = array(
					'name'     => divino_THEME_SETTINGS . '[ast-sidebar-pro-items]',
					'type'     => 'control',
					'control'  => 'ast-upgrade',
					'campaign' => 'sidebar',
					'choices'  => array(
						'one'   => array(
							'title' => __( 'Sidebar spacing', 'divino' ),
						),
						'two'   => array(
							'title' => __( 'Sidebar color options', 'divino' ),
						),
						'three' => array(
							'title' => __( 'Widget color options', 'divino' ),
						),
						'four'  => array(
							'title' => __( 'Widget title typography', 'divino' ),
						),
						'five'  => array(
							'title' => __( 'Widget content typography', 'divino' ),
						),
					),
					'section'  => 'section-sidebars',
					'default'  => '',
					'priority' => 999,
					'title'    => __( 'Make sidebars work harder to engage with divino Pro', 'divino' ),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
				);
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Sidebar_Layout_Configs();
