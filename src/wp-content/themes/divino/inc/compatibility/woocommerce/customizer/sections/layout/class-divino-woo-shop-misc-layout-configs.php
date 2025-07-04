<?php
/**
 * WooCommerce Options for divino Theme.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 3.9.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'divino_Woo_Shop_Misc_Layout_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Woo_Shop_Misc_Layout_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register divino-WooCommerce Misc Customizer Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.9.2
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Enable Quantity Plus and Minus.
				 */
				array(
					'name'        => divino_THEME_SETTINGS . '[single-product-plus-minus-button]',
					'default'     => divino_get_option( 'single-product-plus-minus-button' ),
					'type'        => 'control',
					'section'     => 'section-woo-misc',
					'title'       => __( 'Enable Quantity Plus and Minus', 'divino' ),
					'description' => __( 'Adds plus and minus buttons besides product quantity', 'divino' ),
					'priority'    => 59,
					'control'     => 'ast-toggle-control',
				),

			);

			/**
			 * Option: Adds tabs only if divino addons is enabled.
			 */
			if ( divino_has_pro_woocommerce_addon() ) {
				$_configs[] = array(
					'name'        => 'section-woo-general-tabs',
					'section'     => 'section-woo-misc',
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
				);
			}

			if ( divino_showcase_upgrade_notices() ) {
				// Learn More link if divino Pro is not activated.
				$_configs[] = array(
					'name'     => divino_THEME_SETTINGS . '[ast-woo-misc-pro-items]',
					'type'     => 'control',
					'control'  => 'ast-upgrade',
					'campaign' => 'woocommerce',
					'choices'  => array(
						'two'   => array(
							'title' => __( 'Modern input style', 'divino' ),
						),
						'one'   => array(
							'title' => __( 'Sale badge modifications', 'divino' ),
						),
						'three' => array(
							'title' => __( 'Ecommerce steps navigation', 'divino' ),
						),
						'four'  => array(
							'title' => __( 'Quantity updater designs', 'divino' ),
						),
						'five'  => array(
							'title' => __( 'Modern my-account page', 'divino' ),
						),
						'six'   => array(
							'title' => __( 'Downloads, Orders grid view', 'divino' ),
						),
						'seven' => array(
							'title' => __( 'Modern thank-you page design', 'divino' ),
						),
					),
					'section'  => 'section-woo-misc',
					'default'  => '',
					'priority' => 999,
					'title'    => __( 'Access extra conversion tools to make more profit from your eCommerce store', 'divino' ),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
					'context'  => array(),
				);
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Woo_Shop_Misc_Layout_Configs();
