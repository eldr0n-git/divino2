<?php
/**
 * Store Notice options for divino Theme.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer WooCommerece store notice - customizer config initial setup.
 */
class divino_Woo_Store_Notice_Configs extends divino_Customizer_Config_Base {
	/**
	 * Register divino-WooCommerce Shop Cart Layout Customizer Configurations.
	 *
	 * @param Array                $configurations divino Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 3.9.0
	 * @return Array divino Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		$_configs = array(

			/**
			 * Option: Transparent Header Builder - HTML Elements configs.
			 */
			array(
				'name'      => divino_THEME_SETTINGS . '[woo-store-notice-colors-group]',
				'default'   => divino_get_option( 'woo-store-notice-colors-group' ),
				'type'      => 'control',
				'control'   => 'ast-color-group',
				'title'     => __( 'Color', 'divino' ),
				'section'   => 'woocommerce_store_notice',
				'transport' => 'postMessage',
				'priority'  => 50,
				'context'   => array(
					array(
						'setting'  => 'woocommerce_demo_store',
						'operator' => '==',
						'value'    => true,
					),
				),
				'divider'   => array( 'ast_class' => 'ast-top-divider ast-bottom-divider' ),
			),

			// Option: Text Color.
			array(
				'name'              => 'store-notice-text-color',
				'default'           => divino_get_option( 'store-notice-text-color' ),
				'parent'            => divino_THEME_SETTINGS . '[woo-store-notice-colors-group]',
				'type'              => 'sub-control',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'section'           => 'woocommerce_store_notice',
				'transport'         => 'postMessage',
				'priority'          => 1,
				'title'             => __( 'Text', 'divino' ),
			),

			// Option: Background Color.
			array(
				'name'              => 'store-notice-background-color',
				'default'           => divino_get_option( 'store-notice-background-color' ),
				'parent'            => divino_THEME_SETTINGS . '[woo-store-notice-colors-group]',
				'type'              => 'sub-control',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'section'           => 'woocommerce_store_notice',
				'transport'         => 'postMessage',
				'priority'          => 2,
				'title'             => __( 'Background', 'divino' ),
			),

			/**
			 * Option: Notice Position
			 */
			array(
				'name'       => divino_THEME_SETTINGS . '[store-notice-position]',
				'default'    => divino_get_option( 'store-notice-position' ),
				'type'       => 'control',
				'control'    => 'ast-selector',
				'section'    => 'woocommerce_store_notice',
				'transport'  => 'postMessage',
				'priority'   => 60,
				'title'      => __( 'Notice Position', 'divino' ),
				'choices'    => array(
					'hang-over-top' => __( 'Hang Over Top', 'divino' ),
					'top'           => __( 'Top', 'divino' ),
					'bottom'        => __( 'Bottom', 'divino' ),
				),
				'context'    => array(
					array(
						'setting'  => 'woocommerce_demo_store',
						'operator' => '==',
						'value'    => true,
					),
				),
				'renderAs'   => 'text',
				'responsive' => false,
			),
		);

		return array_merge( $configurations, $_configs );
	}
}

new divino_Woo_Store_Notice_Configs();
