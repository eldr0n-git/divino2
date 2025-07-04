<?php
/**
 * Accessibility Options for divino Theme.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 4.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register divino Accessibility Configurations.
 */
class divino_Accessibility_Configs extends divino_Customizer_Config_Base {
	/**
	 * Register divino Accessibility Configurations.
	 *
	 * @param Array                $configurations divino Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 4.1.0
	 * @return Array divino Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		$_configs = array(

			/**
			 * Option: Toggle for accessibility.
			 */
			array(
				'name'     => divino_THEME_SETTINGS . '[site-accessibility-toggle]',
				'default'  => divino_get_option( 'site-accessibility-toggle' ),
				'type'     => 'control',
				'control'  => 'ast-toggle-control',
				'title'    => __( 'Site Accessibility', 'divino' ),
				'section'  => 'section-accessibility',
				'priority' => 1,
				'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
			),

			/**
			 * Option: Highlight type.
			 */
			array(
				'name'     => divino_THEME_SETTINGS . '[site-accessibility-highlight-type]',
				'default'  => divino_get_option( 'site-accessibility-highlight-type' ),
				'type'     => 'control',
				'control'  => 'ast-radio-icon',
				'priority' => 1,
				'title'    => __( 'Global Highlight', 'divino' ),
				'section'  => 'section-accessibility',
				'choices'  => array(
					'dotted' => array(
						'label' => __( 'Dotted', 'divino' ),
						'path'  => 'ellipsis',
					),
					'solid'  => array(
						'label' => __( 'Solid', 'divino' ),
						'path'  => 'minus',
					),
				),
				'divider'  => array( 'ast_class' => 'ast-top-divider' ),
				'context'  => array(
					array(
						'setting'  => divino_THEME_SETTINGS . '[site-accessibility-toggle]',
						'operator' => '===',
						'value'    => true,
					),
				),
			),

			/**
			 * Option: Highlight color.
			 */
			array(
				'name'     => divino_THEME_SETTINGS . '[site-accessibility-highlight-color]',
				'default'  => divino_get_option( 'site-accessibility-highlight-color' ),
				'type'     => 'control',
				'control'  => 'ast-color',
				'priority' => 1,
				'title'    => __( 'Color', 'divino' ),
				'section'  => 'section-accessibility',
				'context'  => array(
					array(
						'setting'  => divino_THEME_SETTINGS . '[site-accessibility-toggle]',
						'operator' => '===',
						'value'    => true,
					),
				),
			),

			/**
			 * Option: Highlight type.
			 */
			array(
				'name'     => divino_THEME_SETTINGS . '[site-accessibility-highlight-input-type]',
				'default'  => divino_get_option( 'site-accessibility-highlight-input-type' ),
				'type'     => 'control',
				'control'  => 'ast-radio-icon',
				'priority' => 1,
				'title'    => __( 'Input Highlight', 'divino' ),
				'section'  => 'section-accessibility',
				'choices'  => array(
					'disable' => array(
						'label' => __( 'Disable', 'divino' ),
						'path'  => 'remove',
					),
					'dotted'  => array(
						'label' => __( 'Dotted', 'divino' ),
						'path'  => 'ellipsis',
					),
					'solid'   => array(
						'label' => __( 'Solid', 'divino' ),
						'path'  => 'minus',
					),
				),
				'divider'  => array( 'ast_class' => 'ast-top-divider' ),
				'context'  => array(
					array(
						'setting'  => divino_THEME_SETTINGS . '[site-accessibility-toggle]',
						'operator' => '===',
						'value'    => true,
					),
				),
			),

			/**
			 * Option: Highlight color.
			 */
			array(
				'name'     => divino_THEME_SETTINGS . '[site-accessibility-highlight-input-color]',
				'default'  => divino_get_option( 'site-accessibility-highlight-input-color' ),
				'type'     => 'control',
				'control'  => 'ast-color',
				'priority' => 1,
				'title'    => __( 'Color', 'divino' ),
				'section'  => 'section-accessibility',
				'context'  => array(
					array(
						'setting'  => divino_THEME_SETTINGS . '[site-accessibility-toggle]',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		);

		return array_merge( $configurations, $_configs );
	}
}

new divino_Accessibility_Configs();
