<?php
/**
 * Bottom Footer Options for divino Theme.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'divino_Site_Identity_Configs' ) ) {

	/**
	 * Register divino Customizerr Site identity Customizer Configurations.
	 */
	class divino_Site_Identity_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register divino Customizerr Site identity Customizer Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_section = 'title_tagline';
			$_configs = array(

				/**
				 * Notice for Colors - Transparent header enabled on page.
				 */
				array(
					'name'            => divino_THEME_SETTINGS . '[ast-callback-notice-header-transparent-header-logo]',
					'type'            => 'control',
					'control'         => 'ast-description',
					'section'         => $_section,
					'priority'        => 1,
					'context'         => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[different-transparent-logo]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'active_callback' => array( $this, 'is_transparent_header_enabled' ),
					'help'            => $this->get_help_text_notice( 'transparent-header' ),
				),

				/**
				 * Option: Transparent Header Section - Link.
				 */
				array(
					'name'            => divino_THEME_SETTINGS . '[ast-callback-notice-header-transparent-header-logo-link]',
					'type'            => 'control',
					'control'         => 'ast-customizer-link',
					'section'         => $_section,
					'priority'        => 1,
					'link_type'       => 'control',
					'linked'          => divino_THEME_SETTINGS . '[transparent-header-logo]',
					'context'         => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[different-transparent-logo]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'link_text'       => '<u>' . __( 'Customize Transparent Header', 'divino' ) . '</u>',
					'active_callback' => array( $this, 'is_transparent_header_enabled' ),
				),

				/**
				 * Option: Different retina logo
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[different-retina-logo]',
					'type'      => 'control',
					'control'   => 'ast-toggle-control',
					'section'   => $_section,
					'title'     => __( 'Different Logo For Retina Devices?', 'divino' ),
					'default'   => divino_get_option( 'different-retina-logo' ),
					'priority'  => 5,
					'transport' => 'postMessage',
					'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
					'context'   => array(
						array(
							'setting'  => 'custom_logo',
							'operator' => '!=',
							'value'    => '',
						),
						divino_Builder_Helper::$general_tab_config,
					),
					'partial'   => array(
						'selector'            => '.site-branding',
						'container_inclusive' => false,
						'render_callback'     => 'divino_Builder_Header::site_identity',
					),
				),

				/**
				 * Option: Retina logo selector
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[ast-header-retina-logo]',
					'default'           => divino_get_option( 'ast-header-retina-logo' ),
					'type'              => 'control',
					'control'           => 'image',
					'sanitize_callback' => 'esc_url_raw',
					'section'           => 'title_tagline',
					'context'           => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[different-retina-logo]',
							'operator' => '!=',
							'value'    => 0,
						),
						array(
							'setting'  => 'custom_logo',
							'operator' => '!=',
							'value'    => '',
						),
						divino_Builder_Helper::$general_tab_config,
					),
					'priority'          => 5.5,
					'title'             => __( 'Retina Logo', 'divino' ),
					'library_filter'    => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
					'transport'         => 'postMessage',
					'partial'           => array(
						'selector'            => '.site-branding',
						'container_inclusive' => false,
						'render_callback'     => 'divino_Builder_Header::site_identity',
					),
				),

				/**
				 * Option: Inherit Desktop logo
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[different-mobile-logo]',
					'type'      => 'control',
					'control'   => 'ast-toggle-control',
					'default'   => divino_get_option( 'different-mobile-logo' ),
					'section'   => 'title_tagline',
					'title'     => __( 'Different Logo For Mobile Devices?', 'divino' ),
					'priority'  => 5.5,
					'context'   => array(
						array(
							'setting'  => 'custom_logo',
							'operator' => '!=',
							'value'    => '',
						),
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => 'ast_selected_device',
							'operator' => 'in',
							'value'    => array( 'tablet', 'mobile' ),
						),
					),
					'transport' => 'postMessage',
					'partial'   => array(
						'selector'            => '.site-branding',
						'container_inclusive' => false,
						'render_callback'     => 'divino_Builder_Header::site_identity',
					),
					'divider'   => array( 'ast_class' => 'ast-top-divider' ),
				),

				/**
				 * Option: Mobile header logo
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[mobile-header-logo]',
					'default'           => divino_get_option( 'mobile-header-logo' ),
					'type'              => 'control',
					'control'           => 'image',
					'sanitize_callback' => 'esc_url_raw',
					'section'           => 'title_tagline',
					'priority'          => 6,
					'title'             => __( 'Mobile Logo (optional)', 'divino' ),
					'library_filter'    => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
					'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
					'context'           => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[different-mobile-logo]',
							'operator' => '==',
							'value'    => '1',
						),
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => 'ast_selected_device',
							'operator' => 'in',
							'value'    => array( 'tablet', 'mobile' ),
						),
					),
				),

				/**
				 * Option: Use Logo SVG Icon.
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[use-logo-svg-icon]',
					'default'   => divino_get_option( 'use-logo-svg-icon' ),
					'type'      => 'control',
					'control'   => 'ast-toggle-control',
					'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
					'section'   => $_section,
					'title'     => __( 'Use Logo SVG Icon', 'divino' ),
					'priority'  => 6,
					'transport' => 'postMessage',
					'partial'   => array(
						'selector'            => '.site-branding',
						'container_inclusive' => true,
						'render_callback'     => 'divino_Builder_UI_Controller::render_site_identity',
						'fallback_refresh'    => false,
					),
					'context'   => array(
						array(
							'setting'  => 'custom_logo',
							'operator' => '==',
							'value'    => false,
						),
						divino_Builder_Helper::$general_tab_config,
					),
				),

				/**
				 * Option: Logo SVG Icon
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[logo-svg-icon]',
					'type'              => 'control',
					'control'           => 'ast-logo-svg-icon',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_logo_svg_icon' ),
					'section'           => $_section,
					'default'           => divino_get_option( 'logo-svg-icon' ),
					'description'       => __( 'When using Custom SVG code, do not include few attributes such as "width", "height", and "fill" in your custom svg code to utilize existing customizer controls.', 'divino' ),
					'priority'          => 6,
					'title'             => __( 'Logo SVG Icon', 'divino' ),
					'divider'           => array( 'ast_class' => 'ast-top-divider' ),
					'transport'         => 'postMessage',
					'partial'           => array(
						'selector'            => '.site-branding',
						'container_inclusive' => true,
						'render_callback'     => 'divino_Builder_UI_Controller::render_site_identity',
						'fallback_refresh'    => false,
					),
					'context'           => array(
						array(
							'setting'  => 'custom_logo',
							'operator' => '==',
							'value'    => false,
						),
						array(
							'setting'  => divino_THEME_SETTINGS . '[use-logo-svg-icon]',
							'operator' => '==',
							'value'    => true,
						),
						divino_Builder_Helper::$general_tab_config,
					),
				),

				/**
				 * Option: Logo SVG Gap
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[logo-svg-site-title-gap]',
					'type'              => 'control',
					'control'           => 'ast-responsive-slider',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'section'           => $_section,
					'transport'         => 'postMessage',
					'default'           => divino_get_option( 'logo-svg-site-title-gap' ),
					'priority'          => 7,
					'title'             => __( 'Logo SVG Gap', 'divino' ),
					'suffix'            => 'px',
					'input_attrs'       => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 600,
					),
					'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
					'context'           => array(
						array(
							'setting'  => 'custom_logo',
							'operator' => '==',
							'value'    => false,
						),
						array(
							'setting'  => divino_THEME_SETTINGS . '[use-logo-svg-icon]',
							'operator' => '==',
							'value'    => true,
						),
						divino_Builder_Helper::$general_tab_config,
					),
				),

				/**
				 * Option: Logo Width
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[ast-header-responsive-logo-width]',
					'type'              => 'control',
					'control'           => 'ast-responsive-slider',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'section'           => $_section,
					'transport'         => 'postMessage',
					'default'           => divino_get_option( 'ast-header-responsive-logo-width' ),
					'priority'          => 7,
					'title'             => __( 'Logo Width', 'divino' ),
					'suffix'            => 'px',
					'input_attrs'       => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 600,
					),
					'divider'           => array( 'ast_class' => 'ast-top-section-divider ast-bottom-section-divider' ),
				),

				array(
					'name'      => divino_THEME_SETTINGS . '[display-site-title-responsive]',
					'default'   => divino_get_option( 'display-site-title-responsive' ),
					'type'      => 'control',
					'control'   => 'ast-multi-selector',
					'section'   => $_section,
					'priority'  => 8,
					'title'     => __( 'Site Title Visibility', 'divino' ),
					'context'   => divino_Builder_Helper::$general_tab,
					'transport' => 'postMessage',
					'choices'   => array(
						'desktop' => 'customizer-desktop',
						'tablet'  => 'customizer-tablet',
						'mobile'  => 'customizer-mobile',
					),
					'divider'   => array( 'ast_class' => 'ast-top-divider' ),
				),

				/**
				 * Option: Display Tagline
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[display-site-tagline-responsive]',
					'default'   => divino_get_option( 'display-site-tagline-responsive' ),
					'type'      => 'control',
					'control'   => 'ast-multi-selector',
					'section'   => $_section,
					'priority'  => 12,
					'title'     => __( 'Site Tagline Visibility', 'divino' ),
					'context'   => divino_Builder_Helper::$general_tab,
					'transport' => 'postMessage',
					'choices'   => array(
						'desktop' => 'customizer-desktop',
						'tablet'  => 'customizer-tablet',
						'mobile'  => 'customizer-mobile',
					),
					'divider'   => array( 'ast_class' => 'ast-top-divider' ),
				),

				/**
				 * Option: Logo inline title.
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[logo-title-inline]',
					'default'   => divino_get_option( 'logo-title-inline' ),
					'type'      => 'control',
					'context'   => array( divino_Builder_Helper::$general_tab_config ),
					'control'   => 'ast-toggle-control',
					'divider'   => array( 'ast_class' => 'ast-top-divider logo-inline' ),
					'section'   => $_section,
					'title'     => __( 'Inline Logo & Site Title', 'divino' ),
					'priority'  => 8,
					'transport' => 'postMessage',
					'partial'   => array(
						'selector'            => '.site-branding',
						'container_inclusive' => false,
						'render_callback'     => 'divino_Builder_Header::site_identity',
					),
				),
			);

			$_configs = array_merge(
				$_configs,
				array(
					// Color Group control for site title colors.
					array(
						'name'       => divino_THEME_SETTINGS . '[site-identity-title-color-group]',
						'default'    => divino_get_option( 'site-identity-title-color-group' ),
						'type'       => 'control',
						'control'    => 'ast-color-group',
						'title'      => divino_Builder_Helper::$is_header_footer_builder_active ? __( 'Title Color', 'divino' ) : __( 'Colors', 'divino' ),
						'section'    => $_section,
						'responsive' => false,
						'transport'  => 'postMessage',
						'priority'   => 8,
						'context'    => true === divino_Builder_Helper::$is_header_footer_builder_active ? array( divino_Builder_Helper::$design_tab_config ) : '',
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
						'title'     => __( 'Normal', 'divino' ),
						'context'   => divino_Builder_Helper::$design_tab,
					),

					// Color Group control for Logo SVG Icon Colors.
					array(
						'name'       => divino_THEME_SETTINGS . '[logo-svg-icon-color-group]',
						'default'    => divino_get_option( 'logo-svg-icon-color-group' ),
						'type'       => 'control',
						'control'    => 'ast-color-group',
						'title'      => __( 'Logo SVG Icon Color', 'divino' ),
						'section'    => $_section,
						'responsive' => false,
						'transport'  => 'postMessage',
						'priority'   => 8,
						'context'    => array(
							array(
								'setting'  => 'custom_logo',
								'operator' => '==',
								'value'    => false,
							),
							array(
								'setting'  => divino_THEME_SETTINGS . '[use-logo-svg-icon]',
								'operator' => '==',
								'value'    => true,
							),
							divino_Builder_Helper::$design_tab_config,
						),
					),

					// Option: Logo SVG Icon Color.
					array(
						'name'      => 'logo-svg-icon-color',
						'parent'    => divino_THEME_SETTINGS . '[logo-svg-icon-color-group]',
						'section'   => 'title_tagline',
						'type'      => 'sub-control',
						'control'   => 'ast-color',
						'priority'  => 5,
						'default'   => divino_get_option( 'logo-svg-icon-color' ),
						'title'     => __( 'Normal', 'divino' ),
						'transport' => 'postMessage',
						'context'   => array(
							array(
								'setting'  => 'custom_logo',
								'operator' => '==',
								'value'    => false,
							),
							array(
								'setting'  => divino_THEME_SETTINGS . '[use-logo-svg-icon]',
								'operator' => '==',
								'value'    => true,
							),
							divino_Builder_Helper::$design_tab_config,
						),
						'divider'   => array( 'ast_class' => 'ast-bottom-divider' ),
					),

					// Option: Logo SVG Icon Hover Color.
					array(
						'name'      => 'logo-svg-icon-hover-color',
						'parent'    => divino_THEME_SETTINGS . '[logo-svg-icon-color-group]',
						'section'   => 'title_tagline',
						'type'      => 'sub-control',
						'control'   => 'ast-color',
						'priority'  => 10,
						'default'   => divino_get_option( 'logo-svg-icon-hover-color' ),
						'title'     => __( 'Hover', 'divino' ),
						'transport' => 'postMessage',
						'context'   => array(
							array(
								'setting'  => 'custom_logo',
								'operator' => '==',
								'value'    => false,
							),
							array(
								'setting'  => divino_THEME_SETTINGS . '[use-logo-svg-icon]',
								'operator' => '==',
								'value'    => true,
							),
							divino_Builder_Helper::$design_tab_config,
						),
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
						'title'     => __( 'Hover', 'divino' ),
						'context'   => divino_Builder_Helper::$design_tab,
					),

					// Option: Site Tagline Color.
					array(
						'name'      => divino_THEME_SETTINGS . '[header-color-site-tagline]',
						'type'      => 'control',
						'control'   => 'ast-color',
						'transport' => 'postMessage',
						'default'   => divino_get_option( 'header-color-site-tagline' ),
						'title'     => true === divino_Builder_Helper::$is_header_footer_builder_active ? __( 'Tagline', 'divino' ) : __( 'Color', 'divino' ),
						'section'   => 'title_tagline',
						'priority'  => true === divino_Builder_Helper::$is_header_footer_builder_active ? 8 : 12,
						'context'   => true === divino_Builder_Helper::$is_header_footer_builder_active ? array( divino_Builder_Helper::$design_tab_config ) : '',
						'divider'   => array( 'ast_class' => 'ast-bottom-divider' ),
					),
				)
			);

			if ( true === divino_Builder_Helper::$is_header_footer_builder_active ) {

				$_configs = array_merge(
					$_configs,
					array(
						/**
						 * Notice - Transparent meta header enabled on page.
						 */
						array(
							'name'            => divino_THEME_SETTINGS . '[ast-callback-notice-header-transparent-meta-enabled]',
							'type'            => 'control',
							'control'         => 'ast-description',
							'section'         => 'section-header-builder-layout',
							'priority'        => 1,
							'active_callback' => array( $this, 'is_transparent_header_enabled' ),
							'help'            => $this->get_help_text_notice( 'transparent-meta' ),
						),

						/**
						 * Notice Link - Transparent meta header enabled on page.
						 */
						array(
							'name'            => divino_THEME_SETTINGS . '[ast-callback-notice-header-transparent-header-meta-link]',
							'type'            => 'control',
							'control'         => 'ast-customizer-link',
							'section'         => 'section-header-builder-layout',
							'priority'        => 1,
							'link_type'       => 'section',
							'linked'          => 'section-transparent-header',
							'link_text'       => '<u>' . __( 'Customize Transparent Header', 'divino' ) . '</u>',
							'active_callback' => array( $this, 'is_transparent_header_enabled' ),
						),

						/**
						 * Link to the site icon.
						 */
						array(
							'name'           => divino_THEME_SETTINGS . '[site-icon-link]',
							'type'           => 'control',
							'control'        => 'ast-customizer-link',
							'section'        => 'title_tagline',
							'priority'       => 340,
							'link_type'      => 'control',
							'is_button_link' => true,
							'linked'         => 'site_icon',
							'link_text'      => __( 'Site Icon', 'divino' ),
							'divider'        => array( 'ast_class' => 'ast-bottom-divider' ),
						),
					)
				);
			}

			if ( defined( 'divino_EXT_VER' ) && divino_Ext_Extension::is_active( 'typography' ) ) {

				$new_configs = array(

					/**
					 * Option: Header Site Title.
					 */
					array(
						'name'      => divino_THEME_SETTINGS . '[site-title-typography]',
						'default'   => divino_get_option( 'site-title-typography' ),
						'type'      => 'control',
						'control'   => 'ast-settings-group',
						'title'     => true === divino_Builder_Helper::$is_header_footer_builder_active ? __( 'Title Font', 'divino' ) : __( 'Typography', 'divino' ),
						'is_font'   => true,
						'section'   => $_section,
						'transport' => 'postMessage',
						'priority'  => true === divino_Builder_Helper::$is_header_footer_builder_active ? 16 : 8,
						'context'   => true === divino_Builder_Helper::$is_header_footer_builder_active ? array( divino_Builder_Helper::$design_tab_config ) : '',
					),

					/**
					 * Options: Site Tagline.
					 */
					array(
						'name'      => divino_THEME_SETTINGS . '[site-tagline-typography]',
						'default'   => divino_get_option( 'site-tagline-typography' ),
						'type'      => 'control',
						'control'   => 'ast-settings-group',
						'title'     => true === divino_Builder_Helper::$is_header_footer_builder_active ? __( 'Tagline Font', 'divino' ) : __( 'Typography', 'divino' ),
						'section'   => $_section,
						'transport' => 'postMessage',
						'is_font'   => true,
						'priority'  => true === divino_Builder_Helper::$is_header_footer_builder_active ? 20 : 11,
						'context'   => true === divino_Builder_Helper::$is_header_footer_builder_active ? array( divino_Builder_Helper::$design_tab_config ) : '',
					),
				);

				$_configs = array_merge( $_configs, $new_configs );
			}

			return array_merge( $configurations, $_configs );
		}

		/**
		 * Check if transparent header is enabled on the page being previewed.
		 *
		 * @since  2.4.5
		 * @return bool True - If Transparent Header is enabled, False if not.
		 */
		public function is_transparent_header_enabled() {
			$status = divino_Ext_Transparent_Header_Markup::is_transparent_header();
			return true === $status ? true : false;
		}

		/**
		 * Help notice message to be displayed when the page that is being previewed has Logo set from Transparent Header.
		 *
		 * @since  2.4.5
		 * @param String $context Type of notice message to be returned.
		 * @return String HTML Markup for the help notice.
		 */
		private function get_help_text_notice( $context ) {

			switch ( $context ) {
				case 'transparent-header':
					$notice = __( 'Logo is set in the Transparent Header Section. Click below to customize it.', 'divino' );
					break;
				case 'transparent-meta':
					$notice = __( 'This page uses the Transparent Header. Click below to customize it.', 'divino' );
					break;
				default:
					$notice = '';
			}
			return $notice;
		}
	}
}

new divino_Site_Identity_Configs();
