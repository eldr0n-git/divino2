<?php
/**
 * Styling Options for divino Theme.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       1.4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'divino_Body_Colors_Configs' ) ) {

	/**
	 * Register Body Color Customizer Configurations.
	 */
	class divino_Body_Colors_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register Body Color Customizer Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_section = 'section-colors-background';

			if ( class_exists( 'divino_Ext_Extension' ) && divino_Ext_Extension::is_active( 'colors-and-background' ) && ! divino_has_gcp_typo_preset_compatibility() ) {
				$_section = 'section-colors-body';
			}

			$_configs = array(
				array(
					'name'      => divino_THEME_SETTINGS . '[global-color-palette]',
					'type'      => 'control',
					'control'   => 'ast-hidden',
					'section'   => $_section,
					'priority'  => 5,
					'title'     => __( 'Global Palette', 'divino' ),
					'default'   => divino_get_option( 'global-color-palette' ),
					'transport' => 'postMessage',
				),

				array(
					'name'      => 'divino-color-palettes',
					'type'      => 'control',
					'control'   => 'ast-color-palette',
					'section'   => $_section,
					'priority'  => 5,
					'title'     => __( 'Global Palette', 'divino' ),
					'default'   => divino_get_palette_colors(),
					'transport' => 'postMessage',
					'divider'   => array( 'ast_class' => 'ast-section-spacing ast-bottom-section-divider' ),
				),

				/**
				 * Option: Theme color heading
				 */
				array(
					'name'        => divino_THEME_SETTINGS . '[theme-color-divider-reset]',
					'section'     => $_section,
					'title'       => __( 'Theme Color', 'divino' ),
					'type'        => 'control',
					'control'     => 'ast-group-title',
					'priority'    => 5,
					'settings'    => array(),
					'input_attrs' => array(
						'reset_linked_controls' => array(
							'theme-color',
							'link-color',
							'link-h-color',
							'heading-base-color',
							'text-color',
							'border-color',
						),
					),
				),

				/**
				 * Option: Theme Color
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[theme-color]',
					'type'     => 'control',
					'control'  => 'ast-color',
					'section'  => $_section,
					'default'  => divino_get_option( 'theme-color' ),
					'priority' => 5,
					'title'    => __( 'Accent', 'divino' ),
				),

				/**
				 * Option: Link Colors group.
				 */
				array(
					'name'       => divino_THEME_SETTINGS . '[base-link-colors-group]',
					'default'    => divino_get_option( 'base-link-colors-group' ),
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Links', 'divino' ),
					'section'    => $_section,
					'transport'  => 'postMessage',
					'priority'   => 5,
					'responsive' => false,
				),

				array(
					'name'     => 'link-color',
					'parent'   => divino_THEME_SETTINGS . '[base-link-colors-group]',
					'section'  => $_section,
					'type'     => 'sub-control',
					'control'  => 'ast-color',
					'default'  => divino_get_option( 'link-color' ),
					'priority' => 5,
					'title'    => __( 'Normal', 'divino' ),
				),

				/**
				 * Option: Link Hover Color
				 */
				array(
					'name'     => 'link-h-color',
					'parent'   => divino_THEME_SETTINGS . '[base-link-colors-group]',
					'section'  => $_section,
					'default'  => divino_get_option( 'link-h-color' ),
					'type'     => 'sub-control',
					'control'  => 'ast-color',
					'priority' => 10,
					'title'    => __( 'Hover', 'divino' ),
				),

				/**
				 * Option: Text Color
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[text-color]',
					'default'  => divino_get_option( 'text-color' ),
					'type'     => 'control',
					'control'  => 'ast-color',
					'section'  => $_section,
					'priority' => 6,
					'title'    => __( 'Body Text', 'divino' ),
				),

				/**
				 * Option: Text Color
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[border-color]',
					'default'  => divino_get_option( 'border-color' ),
					'type'     => 'control',
					'control'  => 'ast-color',
					'section'  => $_section,
					'priority' => 6,
					'title'    => __( 'Borders', 'divino' ),
					'divider'  => array( 'ast_class' => 'ast-bottom-divider' ),
				),

			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Body_Colors_Configs();
