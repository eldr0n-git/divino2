<?php
/**
 * Register customizer panels & sections for Easy Digital Downloads.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.5.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'divino_Customizer_Register_Edd_Section' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Customizer_Register_Edd_Section extends divino_Customizer_Config_Base {
		/**
		 * Register Panels and Sections for Customizer.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.5.5
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$configs = array(
				/**
				 * WooCommerce
				 */
				array(
					'name'     => 'section-edd-group',
					'type'     => 'section',
					'title'    => __( 'Easy Digital Downloads', 'divino' ),
					'priority' => 60,
				),

				array(
					'name'     => 'section-edd-general',
					'title'    => __( 'General', 'divino' ),
					'type'     => 'section',
					'section'  => 'section-edd-group',
					'priority' => 10,
				),

				array(
					'name'     => 'section-edd-archive',
					'title'    => __( 'Product Archive', 'divino' ),
					'type'     => 'section',
					'section'  => 'section-edd-group',
					'priority' => 10,
				),

				array(
					'name'     => 'section-edd-single',
					'type'     => 'section',
					'title'    => __( 'Single Product', 'divino' ),
					'section'  => 'section-edd-group',
					'priority' => 15,
				),
			);

			return array_merge( $configurations, $configs );
		}
	}
}

new divino_Customizer_Register_Edd_Section();
