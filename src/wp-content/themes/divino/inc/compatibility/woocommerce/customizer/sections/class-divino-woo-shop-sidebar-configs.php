<?php
/**
 * Content Spacing Options for our theme.
 *
 * @package     divino
 * @link        https://www.brainstormforce.com
 * @since       divino 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'divino_Woo_Shop_Sidebar_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Woo_Shop_Sidebar_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register divino-WooCommerce Shop Sidebar Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Sidebar Layout.
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[woocommerce-sidebar-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => 'section-woo-general',
					'default'           => divino_get_option( 'woocommerce-sidebar-layout' ),
					'priority'          => 5,
					'title'             => __( 'Sidebar Layout', 'divino' ),
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
					'description'       => __( 'Sidebar will only apply when container layout is set to normal.', 'divino' ),
					'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Woocommerce Sidebar Style.
				 */
				array(
					'name'       => divino_THEME_SETTINGS . '[woocommerce-sidebar-style]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'section-woo-general',
					'default'    => divino_get_option( 'woocommerce-sidebar-style', 'default' ),
					'priority'   => 5,
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

				/**
				 * Option: Divider
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[shop-display-options-divider]',
					'section'  => 'woocommerce_product_catalog',
					'title'    => __( 'Shop Display Options', 'divino' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 9.5,
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider ast-bottom-spacing' ),
				),

			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Woo_Shop_Sidebar_Configs();
