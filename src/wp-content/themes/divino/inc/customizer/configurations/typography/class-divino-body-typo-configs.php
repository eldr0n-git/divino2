<?php
/**
 * Styling Options for divino Theme.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.0.15
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'divino_Body_Typo_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Body_Typo_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register Body Typography Customizer Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$typo_section = divino_has_gcp_typo_preset_compatibility() ? 'section-typography' : 'section-body-typo';

			$_configs = array(

				/**
				 * Option: Divider.
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[ast-body-font-settings-divider]',
					'section'  => $typo_section,
					'title'    => __( 'Base Font', 'divino' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 6,
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-no-spacing' ),
				),

				/**
				 * Option: Body font family.
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[ast-body-font-settings]',
					'default'   => divino_get_option( 'ast-body-font-settings' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'is_font'   => true,
					'title'     => __( 'Body Font', 'divino' ),
					'section'   => $typo_section,
					'transport' => 'postMessage',
					'priority'  => 6,
				),

				/**
				 * Option: Font Family
				 */
				array(
					'name'        => 'body-font-family',
					'parent'      => divino_THEME_SETTINGS . '[ast-body-font-settings]',
					'type'        => 'sub-control',
					'control'     => 'ast-font',
					'font_type'   => 'ast-font-family',
					'ast_inherit' => __( 'Default System Font', 'divino' ),
					'default'     => divino_get_option( 'body-font-family' ),
					'section'     => $typo_section,
					'priority'    => 6,
					'title'       => __( 'Font Family', 'divino' ),
					'connect'     => divino_THEME_SETTINGS . '[body-font-weight]',
					'variant'     => divino_THEME_SETTINGS . '[body-font-variant]',
					'divider'     => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Font Variant
				 */
				array(
					'name'              => 'body-font-variant',
					'type'              => 'sub-control',
					'parent'            => divino_THEME_SETTINGS . '[ast-body-font-settings]',
					'control'           => 'ast-font-variant',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_font_variant' ),
					'default'           => divino_get_option( 'body-font-variant' ),
					'ast_inherit'       => __( 'Default', 'divino' ),
					'section'           => $typo_section,
					'priority'          => 15,
					'title'             => '',
					'variant'           => divino_THEME_SETTINGS . '[body-font-family]',
					'context'           => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[body-font-family]',
							'operator' => '!=',
							'value'    => 'inherit',
						),
					),
					'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),

				),

				/**
				 * Option: Font Weight
				 */
				array(
					'name'              => 'body-font-weight',
					'type'              => 'sub-control',
					'parent'            => divino_THEME_SETTINGS . '[ast-body-font-settings]',
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => divino_get_option( 'body-font-weight' ),
					'ast_inherit'       => __( 'Default', 'divino' ),
					'section'           => $typo_section,
					'priority'          => 14,
					'title'             => __( 'Font Weight', 'divino' ),
					'connect'           => 'body-font-family',
				),

				/**
				 * Option: Body Font Size
				 */
				array(
					'name'        => 'font-size-body',
					'type'        => 'sub-control',
					'parent'      => divino_THEME_SETTINGS . '[ast-body-font-settings]',
					'control'     => 'ast-responsive-slider',
					'section'     => $typo_section,
					'default'     => divino_get_option( 'font-size-body' ),
					'priority'    => 15,
					'lazy'        => true,
					'title'       => __( 'Font Size', 'divino' ),
					'suffix'      => array( 'px', 'em', 'vw', 'rem' ),
					'input_attrs' => array(
						'px'  => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 200,
						),
						'em'  => array(
							'min'  => 0,
							'step' => 0.01,
							'max'  => 20,
						),
						'vw'  => array(
							'min'  => 0,
							'step' => 0.1,
							'max'  => 25,
						),
						'rem' => array(
							'min'  => 0,
							'step' => 0.1,
							'max'  => 20,
						),
					),
				),

				/**
				 * Option: Body Font Height
				 */
				array(
					'name'     => 'body-font-extras',
					'type'     => 'sub-control',
					'parent'   => divino_THEME_SETTINGS . '[ast-body-font-settings]',
					'control'  => 'ast-font-extras',
					'section'  => $typo_section,
					'priority' => 25,
					'default'  => divino_get_option( 'body-font-extras' ),
					'title'    => __( 'Font Extras', 'divino' ),
				),

				/**
				 * Option: Headings font family.
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[ast-headings-font-settings]',
					'default'   => divino_get_option( 'ast-headings-font-settings' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Headings Font', 'divino' ),
					'section'   => $typo_section,
					'is_font'   => true,
					'transport' => 'postMessage',
					'priority'  => 10,
					'divider'   => array( 'ast_class' => 'ast-bottom-section-divider' ),
				),

				/**
				 * Option: Divider.
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[ast-headings-font-settings-divider]',
					'section'  => $typo_section,
					'title'    => __( 'Heading Font', 'divino' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 10,
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-no-spacing' ),
				),

				/**
				 * Option: Headings Font Family
				 */
				array(
					'name'      => 'headings-font-family',
					'type'      => 'sub-control',
					'parent'    => divino_THEME_SETTINGS . '[ast-headings-font-settings]',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'default'   => divino_get_option( 'headings-font-family' ),
					'title'     => __( 'Font Family', 'divino' ),
					'section'   => $typo_section,
					'priority'  => 26,
					'connect'   => divino_THEME_SETTINGS . '[headings-font-weight]',
					'variant'   => divino_THEME_SETTINGS . '[headings-font-variant]',
					'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Headings Font Weight
				 */
				array(
					'name'              => 'headings-font-weight',
					'type'              => 'sub-control',
					'parent'            => divino_THEME_SETTINGS . '[ast-headings-font-settings]',
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => divino_get_option( 'headings-font-weight' ),
					'title'             => __( 'Font Weight', 'divino' ),
					'section'           => $typo_section,
					'priority'          => 26,
					'connect'           => 'headings-font-family',
				),

				/**
				 * Option: Font Variant
				 */
				array(
					'name'              => 'headings-font-variant',
					'type'              => 'sub-control',
					'parent'            => divino_THEME_SETTINGS . '[ast-headings-font-settings]',
					'control'           => 'ast-font-variant',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_font_variant' ),
					'default'           => divino_get_option( 'headings-font-variant' ),
					'ast_inherit'       => __( 'Default', 'divino' ),
					'section'           => $typo_section,
					'priority'          => 26,
					'variant'           => divino_THEME_SETTINGS . '[headings-font-family]',
					'context'           => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[headings-font-family]',
							'operator' => '!=',
							'value'    => 'inherit',
						),
					),
				),

				/**
				 * Option: Heading Font Height
				 */
				array(
					'name'      => 'headings-font-extras',
					'type'      => 'sub-control',
					'parent'    => divino_THEME_SETTINGS . '[ast-headings-font-settings]',
					'control'   => 'ast-font-extras',
					'transport' => 'postMessage',
					'section'   => $typo_section,
					'priority'  => 26,
					'default'   => divino_get_option( 'headings-font-extras' ),
					'title'     => __( 'Font Extras', 'divino' ),
					'divider'   => array( 'ast_class' => 'ast-sub-top-divider' ),
				),

				/**
				 * Option: Paragraph Margin Bottom
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[para-margin-bottom]',
					'type'              => 'control',
					'control'           => 'ast-slider',
					'default'           => divino_get_option( 'para-margin-bottom' ),
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'transport'         => 'postMessage',
					'section'           => $typo_section,
					'priority'          => 31,
					'title'             => __( 'Paragraph Margin Bottom', 'divino' ),
					'suffix'            => 'em',
					'lazy'              => true,
					'input_attrs'       => array(
						'min'  => 0.5,
						'step' => 0.01,
						'max'  => 5,
					),
					'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Underline links in entry-content.
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[underline-content-links]',
					'default'   => divino_get_option( 'underline-content-links' ),
					'type'      => 'control',
					'control'   => 'ast-toggle-control',
					'section'   => $typo_section,
					'priority'  => 32,
					'divider'   => array( 'ast_class' => 'ast-top-divider' ),
					'title'     => __( 'Underline Content Links', 'divino' ),
					'transport' => 'postMessage',
				),
			);

			if ( divino_has_gcp_typo_preset_compatibility() ) {

				/**
				 * Option: H1 Typography Section.
				 */
				$_configs[] = array(
					'name'      => divino_THEME_SETTINGS . '[ast-heading-h1-typo]',
					'default'   => divino_get_option( 'ast-heading-h1-typo' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'H1 Font', 'divino' ),
					'is_font'   => true,
					'section'   => $typo_section,
					'transport' => 'postMessage',
					'priority'  => 30,
				);

				/**
				 * Option: H2 Typography Section.
				 */
				$_configs[] = array(
					'name'      => divino_THEME_SETTINGS . '[ast-heading-h2-typo]',
					'default'   => divino_get_option( 'ast-heading-h2-typo' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'H2 Font', 'divino' ),
					'is_font'   => true,
					'section'   => $typo_section,
					'transport' => 'postMessage',
					'priority'  => 30,
				);

				/**
				 * Option: H3 Typography Section.
				 */
				$_configs[] = array(
					'name'      => divino_THEME_SETTINGS . '[ast-heading-h3-typo]',
					'default'   => divino_get_option( 'ast-heading-h3-typo' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'H3 Font', 'divino' ),
					'is_font'   => true,
					'section'   => $typo_section,
					'transport' => 'postMessage',
					'priority'  => 30,
				);

				/**
				 * Option: H4 Typography Section.
				 */
				$_configs[] = array(
					'name'      => divino_THEME_SETTINGS . '[ast-heading-h4-typo]',
					'default'   => divino_get_option( 'ast-heading-h4-typo' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'is_font'   => true,
					'title'     => __( 'H4 Font', 'divino' ),
					'section'   => $typo_section,
					'transport' => 'postMessage',
					'priority'  => 30,
				);

				/**
				 * Option: H5 Typography Section.
				 */
				$_configs[] = array(
					'name'      => divino_THEME_SETTINGS . '[ast-heading-h5-typo]',
					'default'   => divino_get_option( 'ast-heading-h5-typo' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'H5 Font', 'divino' ),
					'is_font'   => true,
					'section'   => $typo_section,
					'transport' => 'postMessage',
					'priority'  => 30,
				);

				/**
				 * Option: H6 Typography Section.
				 */
				$_configs[] = array(
					'name'      => divino_THEME_SETTINGS . '[ast-heading-h6-typo]',
					'default'   => divino_get_option( 'ast-heading-h6-typo' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'H6 Font', 'divino' ),
					'is_font'   => true,
					'section'   => $typo_section,
					'transport' => 'postMessage',
					'priority'  => 30,
				);
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Body_Typo_Configs();
