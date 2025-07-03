<?php
/**
 * Site identity Header Configuration.
 *
 * @package     Astra
 * @link        https://wpastra.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register site identity header builder Customizer Configurations.
 *
 * @since 4.5.2
 * @return array Astra Customizer Configurations with updated configurations.
 */
function divino_header_site_identity_configuration() {
	$_section = 'title_tagline';

	$_configs = array(

		/*
		 * Update the Site Identity section inside Layout -> Header
		 *
		 * @since 3.0.0
		 */
		array(
			'name'     => 'title_tagline',
			'type'     => 'section',
			'priority' => 100,
			'title'    => __( 'Logo', 'astra' ),
			'panel'    => 'panel-header-builder-group',
		),

		/**
		 * Link to the astra logo and site title settings.
		 */
		array(
			'name'           => divino_THEME_SETTINGS . '[logo-title-settings-link]',
			'type'           => 'control',
			'control'        => 'ast-customizer-link',
			'section'        => 'astra-site-identity',
			'priority'       => 100,
			'link_type'      => 'section',
			'is_button_link' => true,
			'linked'         => 'title_tagline',
			'link_text'      => __( 'Site Title & Logo Settings', 'astra' ),
		),

		/**
		 * Option: Header Builder Tabs
		 */
		array(
			'name'        => $_section . '-ast-context-tabs',
			'section'     => $_section,
			'type'        => 'control',
			'control'     => 'ast-builder-header-control',
			'priority'    => 0,
			'description' => '',
		),

		/**
		 * Option: Header logo color.
		 */
		array(
			'name'        => divino_THEME_SETTINGS . '[header-logo-color]',
			'default'     => divino_get_option( 'header-logo-color' ),
			'type'        => 'control',
			'control'     => 'ast-color',
			'section'     => 'title_tagline',
			'priority'    => 5,
			'context'     => divino_Builder_Helper::$design_tab,
			'title'       => __( 'Logo Color', 'astra' ),
			'description' => __( 'Use it with transparent images for optimal results.', 'astra' ),
		),

		// Option: Site Title Color.
		array(
			'name'      => 'header-color-site-title',
			'parent'    => divino_THEME_SETTINGS . '[site-identity-title-color-group]',
			'section'   => 'title_tagline',
			'type'      => 'sub-control',
			'control'   => 'ast-color',
			'priority'  => 5,
			'default'   => divino_get_option( 'header-color-site-title' ),
			'transport' => 'postMessage',
			'title'     => __( 'Normal', 'astra' ),
			'context'   => divino_Builder_Helper::$design_tab,
		),

		// Option: Site Title Hover Color.
		array(
			'name'      => 'header-color-h-site-title',
			'parent'    => divino_THEME_SETTINGS . '[site-identity-title-color-group]',
			'section'   => 'title_tagline',
			'type'      => 'sub-control',
			'control'   => 'ast-color',
			'priority'  => 10,
			'transport' => 'postMessage',
			'default'   => divino_get_option( 'header-color-h-site-title' ),
			'title'     => __( 'Hover', 'astra' ),
			'context'   => divino_Builder_Helper::$design_tab,
		),

		/**
		 * Option: Divider
		 */
		array(
			'name'     => divino_THEME_SETTINGS . '[' . $_section . '-margin-divider]',
			'section'  => $_section,
			'title'    => __( 'Spacing', 'astra' ),
			'type'     => 'control',
			'control'  => 'ast-heading',
			'priority' => 220,
			'settings' => array(),
			'context'  => divino_Builder_Helper::$design_tab,
			'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		/**
		 * Option: Margin Space
		 */
		array(
			'name'              => divino_THEME_SETTINGS . '[' . $_section . '-margin]',
			'default'           => divino_get_option( $_section . '-margin' ),
			'type'              => 'control',
			'transport'         => 'postMessage',
			'control'           => 'ast-responsive-spacing',
			'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
			'section'           => $_section,
			'priority'          => 220,
			'title'             => __( 'Margin', 'astra' ),
			'linked_choices'    => true,
			'unit_choices'      => array( 'px', 'em', '%' ),
			'choices'           => array(
				'top'    => __( 'Top', 'astra' ),
				'right'  => __( 'Right', 'astra' ),
				'bottom' => __( 'Bottom', 'astra' ),
				'left'   => __( 'Left', 'astra' ),
			),
			'context'           => divino_Builder_Helper::$design_tab,
			'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
		),

	);

	/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
	if ( defined( 'divino_EXT_VER' ) && divino_Ext_Extension::is_active( 'typography' ) ) {
		/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$_configs[] = array(
			'name'              => 'font-size-site-title',
			'type'              => 'sub-control',
			'parent'            => divino_THEME_SETTINGS . '[site-title-typography]',
			'section'           => 'title_tagline',
			'control'           => 'ast-responsive-slider',
			'default'           => divino_get_option( 'font-size-site-title' ),
			'transport'         => 'postMessage',
			'priority'          => 12,
			'title'             => __( 'Font Size', 'astra' ),
			'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
			'suffix'            => array( 'px', 'em', 'vw', 'rem' ),
			'input_attrs'       => array(
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
		);
		$_configs[] = array(
			'name'              => 'font-size-site-tagline',
			'type'              => 'sub-control',
			'parent'            => divino_THEME_SETTINGS . '[site-tagline-typography]',
			'section'           => 'title_tagline',
			'control'           => 'ast-responsive-slider',
			'default'           => divino_get_option( 'font-size-site-tagline' ),
			'transport'         => 'postMessage',
			'priority'          => 16,
			'title'             => __( 'Font Size', 'astra' ),
			'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
			'suffix'            => array( 'px', 'em', 'vw', 'rem' ),
			'input_attrs'       => array(
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
		);
	} else {
		$_configs[] = array(
			'name'              => divino_THEME_SETTINGS . '[font-size-site-title]',
			'type'              => 'control',
			'section'           => 'title_tagline',
			'default'           => divino_get_option( 'font-size-site-title' ),
			'transport'         => 'postMessage',
			'control'           => 'ast-responsive-slider',
			'priority'          => true === divino_Builder_Helper::$is_header_footer_builder_active ? 16 : 8,
			'title'             => __( 'Title Font Size', 'astra' ),
			'is_font'           => true,
			'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
			'suffix'            => array( 'px', 'em', 'vw', 'rem' ),
			'input_attrs'       => array(
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
			'context'           => true === divino_Builder_Helper::$is_header_footer_builder_active ? array(
				divino_Builder_Helper::$design_tab_config,
				array(
					'relation' => 'OR',
					array(
						'setting'     => divino_THEME_SETTINGS . '[display-site-title-responsive]',
						'setting-key' => 'desktop',
						'operator'    => '==',
						'value'       => true,
					),
					array(
						'setting'     => divino_THEME_SETTINGS . '[display-site-title-responsive]',
						'setting-key' => 'tablet',
						'operator'    => '==',
						'value'       => true,
					),
					array(
						'setting'     => divino_THEME_SETTINGS . '[display-site-title-responsive]',
						'setting-key' => 'mobile',
						'operator'    => '==',
						'value'       => true,
					),
				),
			) : array(
				array(
					'relation' => 'OR',
					array(
						'setting'     => divino_THEME_SETTINGS . '[display-site-title-responsive]',
						'setting-key' => 'desktop',
						'operator'    => '==',
						'value'       => true,
					),
					array(
						'setting'     => divino_THEME_SETTINGS . '[display-site-title-responsive]',
						'setting-key' => 'tablet',
						'operator'    => '==',
						'value'       => true,
					),
					array(
						'setting'     => divino_THEME_SETTINGS . '[display-site-title-responsive]',
						'setting-key' => 'mobile',
						'operator'    => '==',
						'value'       => true,
					),
				),
			),
		);
		$_configs[] = array(
			'name'              => divino_THEME_SETTINGS . '[font-size-site-tagline]',
			'type'              => 'control',
			'section'           => 'title_tagline',
			'control'           => 'ast-responsive-slider',
			'default'           => divino_get_option( 'font-size-site-tagline' ),
			'transport'         => 'postMessage',
			'priority'          => true === divino_Builder_Helper::$is_header_footer_builder_active ? 20 : 12,
			'title'             => __( 'Tagline Font Size', 'astra' ),
			'is_font'           => true,
			'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
			'suffix'            => array( 'px', 'em', 'vw', 'rem' ),
			'input_attrs'       => array(
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
			'context'           => true === divino_Builder_Helper::$is_header_footer_builder_active ? array(
				divino_Builder_Helper::$design_tab_config,
				array(
					'relation' => 'OR',
					array(
						'setting'     => divino_THEME_SETTINGS . '[display-site-tagline-responsive]',
						'setting-key' => 'desktop',
						'operator'    => '==',
						'value'       => true,
					),
					array(
						'setting'     => divino_THEME_SETTINGS . '[display-site-tagline-responsive]',
						'setting-key' => 'tablet',
						'operator'    => '==',
						'value'       => true,
					),
					array(
						'setting'     => divino_THEME_SETTINGS . '[display-site-tagline-responsive]',
						'setting-key' => 'mobile',
						'operator'    => '==',
						'value'       => true,
					),
				),
			) : array(
				array(
					'relation' => 'OR',
					array(
						'setting'     => divino_THEME_SETTINGS . '[display-site-tagline-responsive]',
						'setting-key' => 'desktop',
						'operator'    => '==',
						'value'       => true,
					),
					array(
						'setting'     => divino_THEME_SETTINGS . '[display-site-tagline-responsive]',
						'setting-key' => 'tablet',
						'operator'    => '==',
						'value'       => true,
					),
					array(
						'setting'     => divino_THEME_SETTINGS . '[display-site-tagline-responsive]',
						'setting-key' => 'mobile',
						'operator'    => '==',
						'value'       => true,
					),
				),
			),
		);
	}

	$_configs = array_merge( $_configs, divino_Builder_Base_Configuration::prepare_visibility_tab( $_section ) );

	if ( divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
		array_map( 'divino_save_header_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
	add_action( 'init', 'divino_header_site_identity_configuration' );
}
