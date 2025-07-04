<?php
/**
 * Register customizer panels & sections fro Woocommerce.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.1.0
 * @since       1.4.6 Chnaged to using divino_Customizer API
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'divino_Customizer_Register_Woo_Section' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Customizer_Register_Woo_Section extends divino_Customizer_Config_Base {
		/**
		 * Register Panels and Sections for Customizer.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$configs = array(

				array(
					'name'     => 'section-woo-shop',
					'title'    => __( 'Shop', 'divino' ),
					'type'     => 'section',
					'priority' => 20,
					'panel'    => 'woocommerce',
				),

				array(
					'name'     => 'section-woo-shop-single',
					'type'     => 'section',
					'title'    => __( 'Single Product', 'divino' ),
					'priority' => 12,
					'panel'    => 'woocommerce',
				),

				array(
					'name'     => 'section-woo-shop-cart',
					'type'     => 'section',
					'title'    => __( 'Cart', 'divino' ),
					'priority' => 20,
					'panel'    => 'woocommerce',
				),

				array(
					'name'     => 'section-woo-general',
					'title'    => __( 'General', 'divino' ),
					'type'     => 'section',
					'priority' => 10,
					'panel'    => 'woocommerce',
				),

				array(
					'name'     => 'section-woo-misc',
					'title'    => __( 'Misc', 'divino' ),
					'type'     => 'section',
					'priority' => 24.5,
					'panel'    => 'woocommerce',
				),
			);

			return array_merge( $configurations, $configs );
		}
	}
}

new divino_Customizer_Register_Woo_Section();
