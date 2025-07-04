<?php
/**
 * Container Options for divino theme.
 *
 * @package     divino
 * @link        https://www.brainstormforce.com
 * @since       divino 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'divino_Woo_Shop_Container_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Woo_Shop_Container_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register divino-WooCommerce Shop Container Settings.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Revamped Container Layout.
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[woocommerce-ast-content-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => 'section-woo-general',
					'default'           => divino_get_option( 'woocommerce-ast-content-layout' ),
					'priority'          => 5,
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
						'full-width-container'   => array(
							'label' => __( 'Full Width', 'divino' ),
							'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'full-width-container', false ) : '',
						),
					),
					'divider'           => array( 'ast_class' => 'ast-section-spacing ast-bottom-spacing' ),
				),

				/**
				 * Option: Content Style Option.
				 */
				array(
					'name'        => divino_THEME_SETTINGS . '[woocommerce-content-style]',
					'type'        => 'control',
					'control'     => 'ast-selector',
					'section'     => 'section-woo-general',
					'default'     => divino_get_option( 'woocommerce-content-style', 'default' ),
					'priority'    => 5,
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
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Woo_Shop_Container_Configs();
