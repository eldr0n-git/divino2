<?php
/**
 * Entry Content options for divino Theme.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 3.8.0
 */

if ( ! class_exists( 'divino_Block_Editor_Configs' ) ) {

	/**
	 * Register Site Layout Customizer Configurations.
	 */
	class divino_Block_Editor_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register Site Layout Customizer Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.8.0
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$is_legacy_setup = 'legacy' === divino_get_option( 'wp-blocks-ui', 'comfort' ) || true === divino_get_option( 'blocks-legacy-setup', false ) ? true : false;

			$preset_options = array(
				'compact' => __( 'Compact', 'divino' ),
				'comfort' => __( 'Comfort', 'divino' ),
				'custom'  => __( 'Custom', 'divino' ),
			);
			if ( $is_legacy_setup ) {
				$preset_options = array(
					'legacy'  => __( 'Legacy', 'divino' ),
					'compact' => __( 'Compact', 'divino' ),
					'comfort' => __( 'Comfort', 'divino' ),
					'custom'  => __( 'Custom', 'divino' ),
				);
			}

			$_configs = array(
				/**
				 * Option: Presets for block editor padding.
				 */
				array(
					'name'       => divino_THEME_SETTINGS . '[wp-blocks-ui]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'section-block-editor',
					'default'    => divino_get_option( 'wp-blocks-ui' ),
					'priority'   => 9,
					'title'      => __( 'Core Blocks Spacing', 'divino' ),
					'choices'    => $preset_options,
					'responsive' => false,
					'renderAs'   => 'text',
				),

				/**
				 * Option: Global Padding Option.
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[wp-blocks-global-padding]',
					'section'           => 'section-block-editor',
					'title'             => __( 'Size', 'divino' ),
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'default'           => divino_get_option( 'wp-blocks-global-padding' ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'choices'           => array(
						'top'    => __( 'Top', 'divino' ),
						'right'  => __( 'Right', 'divino' ),
						'bottom' => __( 'Bottom', 'divino' ),
						'left'   => __( 'Left', 'divino' ),
					),
					'linked_choices'    => true,
					'priority'          => 10,
					'unit_choices'      => array( 'px', 'em', '%' ),
					'context'           => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[wp-blocks-ui]',
							'operator' => '===',
							'value'    => 'custom',
						),
					),
					'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
				),
				array(
					'name'     => divino_THEME_SETTINGS . '[wp-blocks-ui-description]',
					'type'     => 'control',
					'control'  => 'ast-description',
					'section'  => 'section-block-editor',
					'priority' => 10,
					'help'     => '<span style="margin-top: -5px;">' . __( 'Global padding setting for WordPress Group, Column, Cover blocks, it can be overridden by respective block\'s Dimension setting.', 'divino' ) . '</span>',
					'settings' => array(),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by creating new instance.
 */
new divino_Block_Editor_Configs();
