<?php
/**
 * Global Misc Options for divino Theme.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino  4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register divino Global Misc Configurations.
 */
class divino_Global_Misc_Configs extends divino_Customizer_Config_Base {
	/**
	 * Register divino Global Misc  Configurations.
	 *
	 * @param Array                $configurations divino Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 4.0.0
	 * @return Array divino Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		$_configs = array(

			/**
			 * Option: Scroll to id.
			 */
			array(
				'name'     => divino_THEME_SETTINGS . '[enable-scroll-to-id]',
				'default'  => divino_get_option( 'enable-scroll-to-id' ),
				'type'     => 'control',
				'control'  => 'ast-toggle-control',
				'title'    => __( 'Enable Smooth Scroll to ID', 'divino' ),
				'section'  => 'section-global-misc',
				'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
				'priority' => 10,
			),
		);

		return array_merge( $configurations, $_configs );
	}
}

new divino_Global_Misc_Configs();
