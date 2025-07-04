<?php
/**
 * Register customizer Aspra Pro Section.
 *
 * @package   divino
 * @link      https://wpdivino.com/
 * @since     divino 1.0.10
 */

if ( ! class_exists( 'divino_Pro_Upgrade_Link_Configs' ) ) {

	/**
	 * Register Button Customizer Configurations.
	 */
	class divino_Pro_Upgrade_Link_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register Button Customizer Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(
				array(
					'name'             => 'divino-pro',
					'type'             => 'section',
					'ast_type'         => 'divino-pro',
					'title'            => esc_html__( 'More Options Available in divino Pro!', 'divino' ),
					'pro_url'          => divino_get_upgrade_url( 'pricing' ),
					'priority'         => 1,
					'section_callback' => 'divino_Pro_Customizer',
				),

				array(
					'name'      => divino_THEME_SETTINGS . '[divino-pro-section-notice]',
					'type'      => 'control',
					'transport' => 'postMessage',
					'control'   => 'ast-hidden',
					'section'   => 'divino-pro',
					'priority'  => 0,
				),

			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Pro_Upgrade_Link_Configs();
