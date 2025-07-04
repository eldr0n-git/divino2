<?php
/**
 * Easy Digital Downloads Options for divino Theme.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.5.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'divino_Edd_Single_Product_Layout_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Edd_Single_Product_Layout_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register divino-Easy Digital Downloads Shop Cart Layout Customizer Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.5.5
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Cart upsells
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[disable-edd-single-product-nav]',
					'section'  => 'section-edd-single',
					'type'     => 'control',
					'control'  => 'ast-toggle-control',
					'default'  => divino_get_option( 'disable-edd-single-product-nav' ),
					'title'    => __( 'Disable Product Navigation', 'divino' ),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
					'priority' => 10,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Edd_Single_Product_Layout_Configs();
