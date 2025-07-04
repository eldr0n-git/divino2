<?php
/**
 * Transparent Header Options for our theme.
 *
 * @package     divino Addon
 * @link        https://www.brainstormforce.com
 * @since       divino 1.4.3
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'divino_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Customizer Sanitizes
 *
 * @since 1.4.3
 */
if ( ! class_exists( 'divino_Customizer_Transparent_Header_Configs' ) ) {

	/**
	 * Register Transparent Header Customizer Configurations.
	 */
	class divino_Customizer_Transparent_Header_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register Transparent Header Customizer Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_section = 'section-transparent-header';

			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$diff_trans_logo = divino_get_option( 'different-transparent-logo', false );

			// Old setting option for disabling the transparent header on 404, search and archive pages.
			$transparent_header_disable_archive = divino_get_option( 'transparent-header-disable-archive' );

			$_configs = array(

				/**
				 * Option: Enable Transparent Header
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[transparent-header-enable]',
					'default'  => divino_get_option( 'transparent-header-enable' ),
					'type'     => 'control',
					'section'  => $_section,
					'title'    => __( 'Enable on Complete Website', 'divino' ),
					'priority' => 20,
					'control'  => 'ast-toggle-control',
					'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: Disable on.
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[transparent-header-disable-on]',
					'type'     => 'control',
					'control'  => 'ast-multiselect-checkbox-group',
					'section'  => $_section,
					'title'    => __( 'Disable on', 'divino' ),
					'options'  => array(
						'showAllButton' => true,
					),
					'priority' => 20,
					'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
					'context'  => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[transparent-header-enable]',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),

				/**
				 * Option: Disable Transparent Header on 404 Page
				 */
				array(
					'name'        => 'transparent-header-disable-404-page',
					'parent'      => divino_THEME_SETTINGS . '[transparent-header-disable-on]',
					'default'     => divino_get_option( 'transparent-header-disable-404-page', $transparent_header_disable_archive ),
					'type'        => 'sub-control',
					'section'     => $_section,
					'title'       => __( '404 Page', 'divino' ),
					'description' => __( 'This setting is generally not recommended on 404 page. If you would like to enable it, uncheck this option', 'divino' ),
					'priority'    => 25,
					'control'     => 'ast-toggle-control',
				),

				/**
				 * Option: Disable Transparent Header on Search Page
				 */
				array(
					'name'        => 'transparent-header-disable-search-page',
					'parent'      => divino_THEME_SETTINGS . '[transparent-header-disable-on]',
					'default'     => divino_get_option( 'transparent-header-disable-search-page', $transparent_header_disable_archive ),
					'type'        => 'sub-control',
					'section'     => $_section,
					'title'       => __( 'Search Page', 'divino' ),
					'description' => __( 'This setting is generally not recommended on search page. If you would like to enable it, uncheck this option', 'divino' ),
					'priority'    => 25,
					'control'     => 'ast-toggle-control',
				),

				/**
				 * Option: Disable Transparent Header on Archive Pages
				 */
				array(
					'name'        => 'transparent-header-disable-archive-pages',
					'parent'      => divino_THEME_SETTINGS . '[transparent-header-disable-on]',
					'default'     => divino_get_option( 'transparent-header-disable-archive-pages', $transparent_header_disable_archive ),
					'type'        => 'sub-control',
					'section'     => $_section,
					'title'       => __( 'Archive Pages', 'divino' ),
					'description' => __( 'This setting is generally not recommended on archives pages, etc. If you would like to enable it, uncheck this option', 'divino' ),
					'priority'    => 25,
					'control'     => 'ast-toggle-control',
				),

				/**
				 * Option: Disable Transparent Header on Archive Pages
				 */
				array(
					'name'        => 'transparent-header-disable-index',
					'parent'      => divino_THEME_SETTINGS . '[transparent-header-disable-on]',
					'default'     => divino_get_option( 'transparent-header-disable-index' ),
					'type'        => 'sub-control',
					'section'     => $_section,
					'title'       => __( 'Blog page?', 'divino' ),
					'description' => __( 'Blog Page is when Latest Posts are selected to be displayed on a particular page.', 'divino' ),
					'priority'    => 25,
					'control'     => 'ast-toggle-control',
				),

				/**
				 * Option: Disable Transparent Header on Your latest posts index Page
				 */
				array(
					'name'        => 'transparent-header-disable-latest-posts-index',
					'parent'      => divino_THEME_SETTINGS . '[transparent-header-disable-on]',
					'default'     => divino_get_option( 'transparent-header-disable-latest-posts-index' ),
					'type'        => 'sub-control',
					'section'     => $_section,
					'title'       => __( 'Latest Posts Page', 'divino' ),
					'description' => __( "Latest Posts page is your site's front page when the latest posts are displayed on the home page.", 'divino' ),
					'priority'    => 25,
					'control'     => 'ast-toggle-control',
				),

				/**
				 * Option: Disable Transparent Header on Pages
				 */
				array(
					'name'     => 'transparent-header-disable-page',
					'parent'   => divino_THEME_SETTINGS . '[transparent-header-disable-on]',
					'default'  => divino_get_option( 'transparent-header-disable-page' ),
					'type'     => 'sub-control',
					'section'  => $_section,
					'title'    => __( 'Pages', 'divino' ),
					'priority' => 25,
					'control'  => 'ast-toggle-control',
				),

				/**
				 * Option: Disable Transparent Header on Posts
				 */
				array(
					'name'     => 'transparent-header-disable-posts',
					'parent'   => divino_THEME_SETTINGS . '[transparent-header-disable-on]',
					'default'  => divino_get_option( 'transparent-header-disable-posts' ),
					'type'     => 'sub-control',
					'section'  => $_section,
					'title'    => __( 'Posts', 'divino' ),
					'priority' => 25,
					'control'  => 'ast-toggle-control',
				),

				/**
				 * Option: Sticky Header Display On
				 */
				array(
					'name'       => divino_THEME_SETTINGS . '[transparent-header-on-devices]',
					'default'    => divino_get_option( 'transparent-header-on-devices' ),
					'type'       => 'control',
					'section'    => $_section,
					'priority'   => 27,
					'title'      => __( 'Enable On', 'divino' ),
					'control'    => 'ast-selector',
					'choices'    => array(
						'desktop' => __( 'Desktop', 'divino' ),
						'mobile'  => __( 'Mobile', 'divino' ),
						'both'    => __( 'Desktop + Mobile', 'divino' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
					'divider'    => array( 'ast_class' => 'ast-top-divider ast-bottom-section-divider' ),
				),

				array(
					'name'     => divino_THEME_SETTINGS . '[different-transparent-logo]',
					'default'  => $diff_trans_logo,
					'type'     => 'control',
					'section'  => $_section,
					'title'    => __( 'Different Transparent Logo', 'divino' ),
					'priority' => 30,
					'control'  => 'ast-toggle-control',
				),

				/**
				 * Option: Transparent header logo selector
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[transparent-header-logo]',
					'default'           => divino_get_option( 'transparent-header-logo' ),
					'type'              => 'control',
					'control'           => 'image',
					'sanitize_callback' => 'esc_url_raw',
					'section'           => $_section,
					'context'           => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[different-transparent-logo]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'priority'          => 30.1,
					'title'             => __( 'Logo', 'divino' ),
					'description'       => __( 'Note: A subtle shadow is added in this preview so white logos remain visible.', 'divino' ),
					'library_filter'    => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
					'partial'           => array(
						'selector'            => '.ast-replace-site-logo-transparent .site-branding .site-logo-img',
						'container_inclusive' => false,
					),
				),

				/**
				 * Option: Different retina logo
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[different-transparent-retina-logo]',
					'default'  => divino_get_option( 'different-transparent-retina-logo' ),
					'type'     => 'control',
					'section'  => $_section,
					'title'    => __( 'Different Logo For Retina Devices?', 'divino' ),
					'context'  => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[different-transparent-logo]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'priority' => 30.2,
					'control'  => 'ast-toggle-control',
					'divider'  => array( 'ast_class' => 'ast-top-divider' ),
				),

				/**
				 * Option: Transparent header logo selector
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[transparent-header-retina-logo]',
					'default'           => divino_get_option( 'transparent-header-retina-logo' ),
					'type'              => 'control',
					'control'           => 'image',
					'sanitize_callback' => 'esc_url_raw',
					'section'           => $_section,
					'context'           => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[different-transparent-retina-logo]',
							'operator' => '==',
							'value'    => true,
						),
						array(
							'setting'  => divino_THEME_SETTINGS . '[different-transparent-logo]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'priority'          => 30.3,
					'title'             => __( 'Retina Logo', 'divino' ),
					'description'       => __( 'Note: A subtle shadow is added in this preview so white logos remain visible.', 'divino' ),
					'library_filter'    => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
					'divider'           => array( 'ast_class' => 'ast-top-divider' ),
				),

				/**
				 * Option: Transparent header logo width
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[transparent-header-logo-width]',
					'default'           => divino_get_option( 'transparent-header-logo-width' ),
					'type'              => 'control',
					'transport'         => 'postMessage',
					'control'           => 'ast-responsive-slider',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'section'           => $_section,
					'context'           => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[different-transparent-logo]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'suffix'            => 'px',
					'priority'          => 30.4,
					'title'             => __( 'Logo Width', 'divino' ),
					'input_attrs'       => array(
						'min'  => 50,
						'step' => 1,
						'max'  => 600,
					),
					'divider'           => array( 'ast_class' => 'ast-top-divider' ),
				),

				/**
				 * Option: Bottom Border Size
				 */
				array(
					'name'        => divino_THEME_SETTINGS . '[transparent-header-main-sep]',
					'default'     => divino_get_option( 'transparent-header-main-sep' ),
					'type'        => 'control',
					'transport'   => 'refresh',
					'control'     => 'ast-slider',
					'section'     => $_section,
					'priority'    => 32,
					'title'       => __( 'Bottom Border Size', 'divino' ),
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 600,
					),
					'context'     => true === divino_Builder_Helper::$is_header_footer_builder_active ? divino_Builder_Helper::$design_tab : divino_Builder_Helper::$general_tab,
					'divider'     => array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: Bottom Border Color
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[transparent-header-main-sep-color]',
					'default'           => divino_get_option( 'transparent-header-main-sep-color' ),
					'type'              => 'control',
					'transport'         => 'refresh',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'section'           => $_section,
					'priority'          => 32,
					'title'             => __( 'Bottom Border Color', 'divino' ),
					'context'           => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[transparent-header-main-sep]',
							'operator' => '>=',
							'value'    => 1,
						),
						true === divino_Builder_Helper::$is_header_footer_builder_active ? divino_Builder_Helper::$design_tab_config : divino_Builder_Helper::$general_tab_config,
					),
				),

				/**
				 * Option: Transparent Header Styling
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[divider-sec-transparent-styling]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => $_section,
					'title'    => __( 'Colors & Background', 'divino' ),
					'priority' => 32,
					'settings' => array(),
					'context'  => true === divino_Builder_Helper::$is_header_footer_builder_active ? divino_Builder_Helper::$design_tab : divino_Builder_Helper::$general_tab,
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				array(
					'name'       => divino_THEME_SETTINGS . '[transparent-header-colors]',
					'default'    => divino_get_option( 'transparent-header-colors' ),
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Site Title', 'divino' ),
					'section'    => $_section,
					'transport'  => 'postMessage',
					'priority'   => 34,
					'context'    => divino_Builder_Helper::$is_header_footer_builder_active ? divino_Builder_Helper::$design_tab : divino_Builder_Helper::$general_tab,
					'responsive' => true,
					'divider'    => array( 'ast_class' => 'ast-top-divider' ),
				),

				array(
					'name'       => divino_THEME_SETTINGS . '[transparent-header-colors-menu]',
					'default'    => divino_get_option( 'transparent-header-colors-menu' ),
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Text / Link', 'divino' ),
					'section'    => $_section,
					'transport'  => 'postMessage',
					'priority'   => 35,
					'context'    => divino_Builder_Helper::$is_header_footer_builder_active ? divino_Builder_Helper::$design_tab : divino_Builder_Helper::$general_tab,
					'responsive' => true,
					'divider'    => array(
						'ast_class' => 'ast-top-divider',
						'ast_title' => __( 'Menu Color', 'divino' ),
					),
				),

				array(
					'name'       => divino_THEME_SETTINGS . '[transparent-header-colors-submenu]',
					'default'    => divino_get_option( 'transparent-header-colors-submenu' ),
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Text / Link', 'divino' ),
					'section'    => $_section,
					'transport'  => 'postMessage',
					'priority'   => 37,
					'context'    => true === divino_Builder_Helper::$is_header_footer_builder_active ? divino_Builder_Helper::$design_tab : divino_Builder_Helper::$general_tab,
					'responsive' => true,
					'divider'    => array(
						'ast_class' => 'ast-top-divider',
						'ast_title' => __( 'Submenu Color', 'divino' ),
					),
				),
			);

			if ( true === divino_Builder_Helper::$is_header_footer_builder_active ) {
				$_hfb_configs = array(
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
					 * Option: Transparent Header Builder - Social Element configs.
					 */
					array(
						'name'       => divino_THEME_SETTINGS . '[transparent-header-social-text-colors-content]',
						'default'    => divino_get_option( 'transparent-header-social-colors-content' ),
						'type'       => 'control',
						'control'    => 'ast-color-group',
						'title'      => __( 'Text / Icon', 'divino' ),
						'section'    => $_section,
						'transport'  => 'postMessage',
						'priority'   => 40,
						'context'    => divino_Builder_Helper::$design_tab,
						'responsive' => true,
						'divider'    => array(
							'ast_class' => 'ast-top-divider',
							'ast_title' => __( 'Social Color', 'divino' ),
						),
					),
					array(
						'name'       => divino_THEME_SETTINGS . '[transparent-header-social-background-colors-content]',
						'default'    => divino_get_option( 'transparent-header-social-colors-content' ),
						'type'       => 'control',
						'control'    => 'ast-color-group',
						'title'      => __( 'Background', 'divino' ),
						'section'    => $_section,
						'transport'  => 'postMessage',
						'priority'   => 40,
						'context'    => divino_Builder_Helper::$design_tab,
						'responsive' => true,
					),

					/**
					 * Option: Social Text Color
					 */
					array(
						'name'       => 'transparent-header-social-icons-color',
						'transport'  => 'postMessage',
						'default'    => divino_get_option( 'transparent-header-social-icons-color' ),
						'type'       => 'sub-control',
						'parent'     => divino_THEME_SETTINGS . '[transparent-header-social-text-colors-content]',
						'section'    => 'section-transparent-header',
						'tab'        => __( 'Normal', 'divino' ),
						'control'    => 'ast-responsive-color',
						'responsive' => true,
						'rgba'       => true,
						'priority'   => 5,
						'context'    => divino_Builder_Helper::$design_tab,
						'title'      => __( 'Normal', 'divino' ),
					),

					/**
					 * Option: Social Text Hover Color
					 */
					array(
						'name'       => 'transparent-header-social-icons-h-color',
						'default'    => divino_get_option( 'transparent-header-social-icons-h-color' ),
						'transport'  => 'postMessage',
						'type'       => 'sub-control',
						'parent'     => divino_THEME_SETTINGS . '[transparent-header-social-text-colors-content]',
						'section'    => 'section-transparent-header',
						'tab'        => __( 'Hover', 'divino' ),
						'control'    => 'ast-responsive-color',
						'responsive' => true,
						'rgba'       => true,
						'priority'   => 7,
						'context'    => divino_Builder_Helper::$design_tab,
						'title'      => __( 'Hover', 'divino' ),
					),

					/**
					 * Option: Social Background Color
					 */
					array(
						'name'       => 'transparent-header-social-icons-bg-color',
						'default'    => divino_get_option( 'transparent-header-social-icons-bg-color' ),
						'transport'  => 'postMessage',
						'type'       => 'sub-control',
						'parent'     => divino_THEME_SETTINGS . '[transparent-header-social-background-colors-content]',
						'section'    => 'section-transparent-header',
						'tab'        => __( 'Normal', 'divino' ),
						'control'    => 'ast-responsive-color',
						'responsive' => true,
						'rgba'       => true,
						'priority'   => 9,
						'context'    => divino_Builder_Helper::$design_tab,
						'title'      => __( 'Normal', 'divino' ),
					),

					/**
					 * Option: Social Background Hover Color
					 */
					array(
						'name'       => 'transparent-header-social-icons-bg-h-color',
						'default'    => divino_get_option( 'transparent-header-social-icons-bg-h-color' ),
						'transport'  => 'postMessage',
						'type'       => 'sub-control',
						'parent'     => divino_THEME_SETTINGS . '[transparent-header-social-background-colors-content]',
						'section'    => 'section-transparent-header',
						'tab'        => __( 'Hover', 'divino' ),
						'control'    => 'ast-responsive-color',
						'responsive' => true,
						'rgba'       => true,
						'priority'   => 11,
						'context'    => divino_Builder_Helper::$design_tab,
						'title'      => __( 'Hover', 'divino' ),
					),

					/**
					 * Option: Transparent Header Builder - HTML Elements configs.
					 */
					array(
						'name'      => divino_THEME_SETTINGS . '[transparent-header-html-colors-group]',
						'default'   => divino_get_option( 'transparent-header-html-colors-group' ),
						'type'      => 'control',
						'control'   => 'ast-color-group',
						'title'     => __( 'Link', 'divino' ),
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 75,
						'context'   => divino_Builder_Helper::$design_tab,
					),

					// Option: HTML Text Color.
					array(
						'name'              => divino_THEME_SETTINGS . '[transparent-header-html-text-color]',
						'default'           => divino_get_option( 'transparent-header-html-text-color' ),
						'type'              => 'control',
						'control'           => 'ast-color',
						'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
						'section'           => 'section-transparent-header',
						'transport'         => 'postMessage',
						'priority'          => 74,
						'title'             => __( 'Text', 'divino' ),
						'context'           => divino_Builder_Helper::$design_tab,
						'divider'           => array(
							'ast_class' => 'ast-top-divider ast-top-divider',
							'ast_title' => __( 'HTML Color', 'divino' ),
						),
					),

					// Option: HTML Link Color.
					array(
						'name'              => 'transparent-header-html-link-color',
						'default'           => divino_get_option( 'transparent-header-html-link-color' ),
						'parent'            => divino_THEME_SETTINGS . '[transparent-header-html-colors-group]',
						'type'              => 'sub-control',
						'control'           => 'ast-color',
						'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
						'section'           => 'section-transparent-header',
						'transport'         => 'postMessage',
						'priority'          => 5,
						'title'             => __( 'Normal', 'divino' ),
						'context'           => divino_Builder_Helper::$general_tab,
					),

					// Option: HTML Link Hover Color.
					array(
						'name'              => 'transparent-header-html-link-h-color',
						'default'           => divino_get_option( 'transparent-header-html-link-h-color' ),
						'parent'            => divino_THEME_SETTINGS . '[transparent-header-html-colors-group]',
						'type'              => 'sub-control',
						'control'           => 'ast-color',
						'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
						'section'           => 'section-transparent-header',
						'transport'         => 'postMessage',
						'priority'          => 5,
						'title'             => __( 'Hover', 'divino' ),
						'context'           => divino_Builder_Helper::$general_tab,
					),

					/**
					 * Option: Transparent Header Builder - Search Elements configs.
					 */

					// Option: Search Color.
					array(
						'name'              => divino_THEME_SETTINGS . '[transparent-header-search-icon-color]',
						'default'           => divino_get_option( 'transparent-header-search-icon-color' ),
						'type'              => 'control',
						'control'           => 'ast-color',
						'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
						'section'           => 'section-transparent-header',
						'transport'         => 'postMessage',
						'priority'          => 45,
						'title'             => __( 'Icon', 'divino' ),
						'context'           => divino_Builder_Helper::$design_tab,

						'divider'           => array(
							'ast_class' => 'ast-top-divider ast-top-divider',
							'ast_title' => __( 'Search Color', 'divino' ),
						),
					),

					/**
					 * Search Box Background Color
					 */
					array(
						'name'              => divino_THEME_SETTINGS . '[transparent-header-search-box-background-color]',
						'default'           => divino_get_option( 'transparent-header-search-box-background-color' ),
						'type'              => 'control',
						'section'           => 'section-transparent-header',
						'priority'          => 45,
						'transport'         => 'postMessage',
						'control'           => 'ast-color',
						'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
						'title'             => __( 'Box Background', 'divino' ),
						'context'           => array(
							divino_Builder_Helper::$design_tab_config,
							array(
								'setting'  => divino_THEME_SETTINGS . '[header-search-box-type]',
								'operator' => 'in',
								'value'    => array( 'slide-search', 'search-box' ),
							),
						),
					),

					/**
					 * Group: Transparent Header Button Colors Group
					 */
					array(
						'name'      => divino_THEME_SETTINGS . '[transparent-header-buttons-text-group]',
						'default'   => divino_get_option( 'transparent-header-buttons-group' ),
						'type'      => 'control',
						'control'   => 'ast-color-group',
						'title'     => __( 'Text', 'divino' ),
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 60,
						'context'   => divino_Builder_Helper::$design_tab,
						'divider'   => array(
							'ast_class' => 'ast-top-divider',
							'ast_title' => __( 'Button Color', 'divino' ),
						),
					),
					array(
						'name'      => divino_THEME_SETTINGS . '[transparent-header-buttons-background-group]',
						'default'   => divino_get_option( 'transparent-header-buttons-group' ),
						'type'      => 'control',
						'control'   => 'ast-color-group',
						'title'     => __( 'Background', 'divino' ),
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 60,
						'context'   => divino_Builder_Helper::$design_tab,
					),
					array(
						'name'      => divino_THEME_SETTINGS . '[transparent-header-buttons-border-group]',
						'default'   => divino_get_option( 'transparent-header-buttons-border-group' ),
						'type'      => 'control',
						'control'   => 'ast-color-group',
						'title'     => __( 'Border Color', 'divino' ),
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 60,
						'context'   => divino_Builder_Helper::$design_tab,
					),

					/**
					 * Option: Button Text Color
					 */
					array(
						'name'              => 'transparent-header-button-text-color',
						'transport'         => 'postMessage',
						'default'           => divino_get_option( 'transparent-header-button-text-color' ),
						'type'              => 'sub-control',
						'parent'            => divino_THEME_SETTINGS . '[transparent-header-buttons-text-group]',
						'section'           => 'section-transparent-header',
						'tab'               => __( 'Normal', 'divino' ),
						'control'           => 'ast-color',
						'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
						'priority'          => 5,
						'title'             => __( 'Normal', 'divino' ),
					),

					/**
					 * Option: Button Text Hover Color
					 */
					array(
						'name'              => 'transparent-header-button-text-h-color',
						'default'           => divino_get_option( 'transparent-header-button-text-h-color' ),
						'transport'         => 'postMessage',
						'type'              => 'sub-control',
						'parent'            => divino_THEME_SETTINGS . '[transparent-header-buttons-text-group]',
						'section'           => 'section-transparent-header',
						'tab'               => __( 'Hover', 'divino' ),
						'control'           => 'ast-color',
						'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
						'priority'          => 7,
						'title'             => __( 'Hover', 'divino' ),
					),

					/**
					 * Option: Button Background Color
					 */
					array(
						'name'              => 'transparent-header-button-bg-color',
						'default'           => divino_get_option( 'transparent-header-button-bg-color' ),
						'transport'         => 'postMessage',
						'type'              => 'sub-control',
						'parent'            => divino_THEME_SETTINGS . '[transparent-header-buttons-background-group]',
						'section'           => 'section-transparent-header',
						'tab'               => __( 'Normal', 'divino' ),
						'control'           => 'ast-color',
						'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
						'priority'          => 9,
						'title'             => __( 'Normal', 'divino' ),
					),

					/**
					 * Option: Button Button Hover Color
					 */
					array(
						'name'              => 'transparent-header-button-bg-h-color',
						'default'           => divino_get_option( 'transparent-header-button-bg-h-color' ),
						'transport'         => 'postMessage',
						'type'              => 'sub-control',
						'parent'            => divino_THEME_SETTINGS . '[transparent-header-buttons-background-group]',
						'section'           => 'section-transparent-header',
						'tab'               => __( 'Hover', 'divino' ),
						'control'           => 'ast-color',
						'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
						'priority'          => 11,
						'title'             => __( 'Hover', 'divino' ),
					),

					/**
					 * Option: Button Border Color
					 */
					array(
						'name'      => 'transparent-header-button-border-color',
						'transport' => 'postMessage',
						'default'   => divino_get_option( 'transparent-header-button-border-color' ),
						'type'      => 'sub-control',
						'parent'    => divino_THEME_SETTINGS . '[transparent-header-buttons-border-group]',
						'section'   => 'section-transparent-header',
						'tab'       => __( 'Normal', 'divino' ),
						'control'   => 'ast-color',
						'priority'  => 5,
						'title'     => __( 'Normal', 'divino' ),
					),

					/**
					 * Option: Button Border Hover Color
					 */
					array(
						'name'      => 'transparent-header-button-border-h-color',
						'default'   => divino_get_option( 'transparent-header-button-border-h-color' ),
						'transport' => 'postMessage',
						'type'      => 'sub-control',
						'parent'    => divino_THEME_SETTINGS . '[transparent-header-buttons-border-group]',
						'section'   => 'section-transparent-header',
						'tab'       => __( 'Hover', 'divino' ),
						'control'   => 'ast-color',
						'priority'  => 7,
						'title'     => __( 'Hover', 'divino' ),
					),

					array(
						'name'              => divino_THEME_SETTINGS . '[transparent-account-icon-color]',
						'default'           => divino_get_option( 'transparent-account-icon-color' ),
						'type'              => 'control',
						'control'           => 'ast-color',
						'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
						'section'           => 'section-transparent-header',
						'transport'         => 'postMessage',
						'priority'          => 65,
						'title'             => __( 'Icon', 'divino' ),
						'divider'           => array(
							'ast_class' => 'ast-top-divider ast-top-divider',
							'ast_title' => __( 'Account', 'divino' ),
						),
						'context'           => array(
							divino_Builder_Helper::$design_tab_config,
							array(
								'relation' => 'OR',
								array(
									'setting'  => divino_THEME_SETTINGS . '[header-account-login-style]',
									'operator' => '==',
									'value'    => 'icon',
								),
								array(
									'setting'  => divino_THEME_SETTINGS . '[header-account-login-style]',
									'operator' => '==',
									'value'    => 'text',
								),
								array(
									'setting'  => divino_THEME_SETTINGS . '[header-account-logout-style]',
									'operator' => '!=',
									'value'    => 'none',
								),
							),
						),
					),

					array(
						'name'              => divino_THEME_SETTINGS . '[transparent-account-type-text-color]',
						'default'           => divino_get_option( 'transparent-account-type-text-color' ),
						'type'              => 'control',
						'section'           => $_section,
						'priority'          => 65,
						'transport'         => 'postMessage',
						'control'           => 'ast-color',
						'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
						'title'             => __( 'Text', 'divino' ),
						'context'           => array(
							divino_Builder_Helper::$design_tab_config,
							array(
								'relation' => 'OR',
								array(
									'setting'  => divino_THEME_SETTINGS . '[header-account-login-style]',
									'operator' => '==',
									'value'    => 'icon',
								),
								array(
									'setting'  => divino_THEME_SETTINGS . '[header-account-login-style]',
									'operator' => '==',
									'value'    => 'text',
								),
								array(
									'setting'  => divino_THEME_SETTINGS . '[header-account-logout-style]',
									'operator' => '!=',
									'value'    => 'none',
								),
							),
						),
					),

					/**
					 * Option: Toggle Button Color
					 */
					array(
						'name'      => divino_THEME_SETTINGS . '[transparent-header-toggle-btn-color]',
						'default'   => divino_get_option( 'transparent-header-toggle-btn-color' ),
						'type'      => 'control',
						'control'   => 'ast-color',
						'title'     => __( 'Icon', 'divino' ),
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 70,
						'context'   => divino_Builder_Helper::$design_tab,
						'divider'   => array(
							'ast_class' => 'ast-top-divider ast-top-divider',
							'ast_title' => __( 'Toggle Color', 'divino' ),
						),
					),

					/**
					 * Option: Toggle Button Bg Color
					 */
					array(
						'name'      => divino_THEME_SETTINGS . '[transparent-header-toggle-btn-bg-color]',
						'default'   => divino_get_option( 'transparent-header-toggle-btn-bg-color' ),
						'type'      => 'control',
						'control'   => 'ast-color',
						'title'     => __( 'Background', 'divino' ),
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 70,
						'context'   => divino_Builder_Helper::$design_tab,
					),

					/**
					 * Option: Toggle Button Border Color
					 */
					array(
						'name'      => divino_THEME_SETTINGS . '[transparent-header-toggle-border-color]',
						'default'   => divino_get_option( 'transparent-header-toggle-border-color' ),
						'type'      => 'control',
						'control'   => 'ast-color',
						'title'     => __( 'Border', 'divino' ),
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 70,
						'context'   => divino_Builder_Helper::$design_tab,
					),
				);

				$_configs = array_merge( $_configs, $_hfb_configs );
			} else {
				$_old_content_configs = array(

					/**
					 * Option: Content Section Text color.
					 */
					array(
						'name'       => divino_THEME_SETTINGS . '[transparent-content-section-text-color-responsive]',
						'default'    => divino_get_option( 'transparent-content-section-text-color-responsive' ),
						'type'       => 'control',
						'priority'   => 39,
						'section'    => $_section,
						'transport'  => 'postMessage',
						'control'    => 'ast-responsive-color',
						'title'      => __( 'Text', 'divino' ),
						'responsive' => true,
						'rgba'       => true,
						'divider'    => array(
							'ast_class' => 'ast-top-divider',
							'ast_title' => __( 'Content', 'divino' ),
						),
					),
					/**
					 * Option: Header Builder Tabs
					 */
					array(
						'name'       => divino_THEME_SETTINGS . '[transparent-header-colors-content]',
						'default'    => divino_get_option( 'transparent-header-colors-content' ),
						'type'       => 'control',
						'control'    => 'ast-color-group',
						'title'      => __( 'Link', 'divino' ),
						'section'    => $_section,
						'transport'  => 'postMessage',
						'priority'   => 39,
						'context'    => true === divino_Builder_Helper::$is_header_footer_builder_active ? divino_Builder_Helper::$design_tab : divino_Builder_Helper::$general_tab,
						'responsive' => true,
					),
				);

				$_configs = array_merge( $_configs, $_old_content_configs );
			}

			if ( defined( 'divino_EXT_VER' ) && ( true === divino_Builder_Helper::$is_header_footer_builder_active ) ) {

				$pro_elements_transparent_config = array(

					/**
					 * Search Box Background Color
					 */
					array(
						'name'              => divino_THEME_SETTINGS . '[transparent-header-search-box-placeholder-color]',
						'default'           => divino_get_option( 'transparent-header-search-box-placeholder-color' ),
						'type'              => 'control',
						'section'           => 'section-transparent-header',
						'priority'          => 45,
						'transport'         => 'postMessage',
						'control'           => 'ast-color',
						'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
						'title'             => __( 'Text / Placeholder', 'divino' ),
						'context'           => array(
							divino_Builder_Helper::$design_tab_config,
							array(
								'setting'  => divino_THEME_SETTINGS . '[header-search-box-type]',
								'operator' => 'in',
								'value'    => array( 'slide-search', 'search-box' ),
							),
						),
					),

					/**
					 * Option: Transparent Header Builder - Divider Elements configs.
					 */
					array(
						'name'              => divino_THEME_SETTINGS . '[transparent-header-divider-color]',
						'default'           => divino_get_option( 'transparent-header-divider-color' ),
						'type'              => 'control',
						'control'           => 'ast-color',
						'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
						'transport'         => 'postMessage',
						'priority'          => 64,
						'title'             => __( 'Divider', 'divino' ),
						'section'           => 'section-transparent-header',
						'context'           => divino_Builder_Helper::$design_tab,
						'divider'           => array( 'ast_class' => 'ast-top-divider ast-top-divider' ),
					),

					array(
						'name'      => divino_THEME_SETTINGS . '[transparent-account-menu-colors]',
						'default'   => divino_get_option( 'transparent-account-menu-colors' ),
						'type'      => 'control',
						'control'   => 'ast-settings-group',
						'title'     => __( 'Account Menu Color', 'divino' ),
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 66,
						'context'   => array(
							divino_Builder_Helper::$design_tab_config,
							array(
								'setting'  => divino_THEME_SETTINGS . '[header-account-action-type]',
								'operator' => '==',
								'value'    => 'menu',
							),
						),
						'divider'   => array( 'ast_class' => 'ast-top-divider' ),
					),

					// Option: Menu Color.
					array(
						'name'      => 'transparent-account-menu-color',
						'default'   => divino_get_option( 'transparent-account-menu-color' ),
						'parent'    => divino_THEME_SETTINGS . '[transparent-account-menu-colors]',
						'type'      => 'sub-control',
						'control'   => 'ast-color',
						'transport' => 'postMessage',
						'tab'       => __( 'Normal', 'divino' ),
						'section'   => 'section-transparent-header',
						'title'     => __( 'Link / Text Color', 'divino' ),
						'priority'  => 7,
						'context'   => array(
							array(
								'setting'  => divino_THEME_SETTINGS . '[header-account-action-type]',
								'operator' => '==',
								'value'    => 'menu',
							),
							divino_Builder_Helper::$design_tab,
						),
					),

					// Option: Background Color.
					array(
						'name'      => 'transparent-account-menu-bg-obj',
						'default'   => divino_get_option( 'transparent-account-menu-bg-obj' ),
						'parent'    => divino_THEME_SETTINGS . '[transparent-account-menu-colors]',
						'type'      => 'sub-control',
						'control'   => 'ast-color',
						'transport' => 'postMessage',
						'section'   => 'section-transparent-header',
						'title'     => __( 'Background Color', 'divino' ),
						'tab'       => __( 'Normal', 'divino' ),
						'priority'  => 8,
						'context'   => divino_Builder_Helper::$design_tab,
					),

					// Option: Menu Hover Color.
					array(
						'name'      => 'transparent-account-menu-h-color',
						'default'   => divino_get_option( 'transparent-account-menu-h-color' ),
						'parent'    => divino_THEME_SETTINGS . '[transparent-account-menu-colors]',
						'tab'       => __( 'Hover', 'divino' ),
						'type'      => 'sub-control',
						'control'   => 'ast-color',
						'transport' => 'postMessage',
						'title'     => __( 'Link Color', 'divino' ),
						'section'   => 'section-transparent-header',
						'priority'  => 19,
						'context'   => divino_Builder_Helper::$design_tab,
					),

					// Option: Menu Hover Background Color.
					array(
						'name'      => 'transparent-account-menu-h-bg-color',
						'default'   => divino_get_option( 'transparent-account-menu-h-bg-color' ),
						'parent'    => divino_THEME_SETTINGS . '[transparent-account-menu-colors]',
						'type'      => 'sub-control',
						'title'     => __( 'Background Color', 'divino' ),
						'section'   => 'section-transparent-header',
						'control'   => 'ast-color',
						'transport' => 'postMessage',
						'tab'       => __( 'Hover', 'divino' ),
						'priority'  => 21,
						'context'   => divino_Builder_Helper::$design_tab,
					),

					// Option: Active Menu Color.
					array(
						'name'      => 'transparent-account-menu-a-color',
						'default'   => divino_get_option( 'transparent-account-menu-a-color' ),
						'parent'    => divino_THEME_SETTINGS . '[transparent-account-menu-colors]',
						'type'      => 'sub-control',
						'section'   => 'section-transparent-header',
						'control'   => 'ast-color',
						'transport' => 'postMessage',
						'tab'       => __( 'Active', 'divino' ),
						'title'     => __( 'Link Color', 'divino' ),
						'priority'  => 31,
						'context'   => divino_Builder_Helper::$design_tab,
					),

					// Option: Active Menu Background Color.
					array(
						'name'      => 'transparent-account-menu-a-bg-color',
						'default'   => divino_get_option( 'transparent-account-menu-a-bg-color' ),
						'parent'    => divino_THEME_SETTINGS . '[transparent-account-menu-colors]',
						'type'      => 'sub-control',
						'control'   => 'ast-color',
						'transport' => 'postMessage',
						'section'   => 'section-transparent-header',
						'title'     => __( 'Background Color', 'divino' ),
						'tab'       => __( 'Active', 'divino' ),
						'priority'  => 33,
						'context'   => divino_Builder_Helper::$design_tab,
					),
				);

				$_configs = array_merge( $_configs, $pro_elements_transparent_config );
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new divino_Customizer_Transparent_Header_Configs();
