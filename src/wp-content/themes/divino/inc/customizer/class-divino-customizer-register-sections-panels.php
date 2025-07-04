<?php
/**
 * Register customizer panels & sections.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'divino_Customizer_Register_Sections_Panels' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Customizer_Register_Sections_Panels extends divino_Customizer_Config_Base {
		/**
		 * Register Panels and Sections for Customizer.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$configs = array(

				/**
				 * Layout Panel
				 */

				array(
					'name'     => 'panel-global',
					'type'     => 'panel',
					'priority' => 10,
					'title'    => __( 'Global', 'divino' ),
				),

				array(
					'name'               => 'section-container-layout',
					'type'               => 'section',
					'priority'           => 17,
					'title'              => __( 'Container', 'divino' ),
					'panel'              => 'panel-global',
					'description_hidden' => true,
					'description'        => $this->section_get_description(
						array(
							'description' => '<p><b>' . __( 'Helpful Information', 'divino' ) . '</b></p>',
							'links'       => array(
								array(
									'text'  => __( 'Site Layout Overview', 'divino' ) . ' &#187;',
									'attrs' => array(
										'href' => divino_get_pro_url( '/docs/site-layout-overview/', 'free-theme', 'customizer', 'helpful_information' ),
									),
								),
								array(
									'text'  => __( 'Container Overview', 'divino' ) . ' &#187;',
									'attrs' => array(
										'href' => divino_get_pro_url( '/docs/container-overview/', 'free-theme', 'customizer', 'helpful_information' ),
									),
								),
							),
						)
					),
				),

				/*
				 * Header section
				 *
				 * @since 1.4.0
				 */
				array(
					'name'     => 'panel-header-group',
					'type'     => 'panel',
					'priority' => 20,
					'title'    => __( 'Header', 'divino' ),
				),

				/*
				 * Update the Site Identity section inside Layout -> Header
				 *
				 * @since 1.4.0
				 */
				array(
					'name'               => 'title_tagline',
					'type'               => 'section',
					'priority'           => 5,
					'title'              => __( 'Site Identity', 'divino' ),
					'panel'              => 'panel-header-group',
					'description_hidden' => true,
					'description'        => $this->section_get_description(
						array(
							'description' => '<p><b>' . __( 'Helpful Information', 'divino' ) . '</b></p>',
							'links'       => array(
								array(
									'text'  => __( 'Site Identity Overview', 'divino' ) . ' &#187;',
									'attrs' => array(
										'href' => divino_get_pro_url( '/docs/site-identity-free/', 'free-theme', 'customizer', 'helpful_information' ),
									),
								),
							),
						)
					),
				),

				/*
				 * Update the Primary Header section
				 *
				 * @since 1.4.0
				 */
				array(
					'name'               => 'section-header',
					'type'               => 'section',
					'priority'           => 15,
					'title'              => __( 'Primary Header', 'divino' ),
					'panel'              => 'panel-header-group',
					'description_hidden' => true,
					'description'        => $this->section_get_description(
						array(
							'description' => '<p><b>' . __( 'Helpful Information', 'divino' ) . '</b></p>',
							'links'       => array(
								array(
									'text'  => __( 'Primary Header Overview', 'divino' ) . ' &#187;',
									'attrs' => array(
										'href' => divino_get_pro_url( '/docs/header-overview/', 'free-theme', 'customizer', 'helpful_information' ),
									),
								),
							),
						)
					),
				),

				array(
					'name'     => 'section-primary-menu',
					'type'     => 'section',
					'priority' => 15,
					'title'    => __( 'Primary Menu', 'divino' ),
					'panel'    => 'panel-header-group',
				),
				array(
					'name'     => 'section-footer-group',
					'type'     => 'section',
					'title'    => __( 'Footer', 'divino' ),
					'priority' => 55,
				),

				array(
					'name'             => 'section-separator',
					'type'             => 'section',
					'ast_type'         => 'ast-section-separator',
					'priority'         => 70,
					'section_callback' => 'divino_WP_Customize_Separator',
				),

				/**
				 * Footer Widgets Section
				 */

				array(
					'name'     => 'section-footer-adv',
					'type'     => 'section',
					'title'    => __( 'Footer Widgets', 'divino' ),
					'section'  => 'section-footer-group',
					'priority' => 5,
				),

				array(
					'name'               => 'section-footer-small',
					'type'               => 'section',
					'title'              => __( 'Footer Bar', 'divino' ),
					'section'            => 'section-footer-group',
					'priority'           => 10,
					'description_hidden' => true,
					'description'        => $this->section_get_description(
						array(
							'description' => '<p><b>' . __( 'Helpful Information', 'divino' ) . '</b></p>',
							'links'       => array(
								array(
									'text'  => __( 'Footer Bar Overview', 'divino' ) . ' &#187;',
									'attrs' => array(
										'href' => divino_get_pro_url( '/docs/footer-bar/', 'free-theme', 'customizer', 'helpful_information' ),
									),
								),
							),
						)
					),
				),

				array(
					'name'     => 'section-blog-group',
					'type'     => 'section',
					'priority' => 20,
					'title'    => __( 'Post Types', 'divino' ),
				),
				array(
					'name'     => 'section-general-group',
					'type'     => 'section',
					'priority' => 20,
					'title'    => __( 'General', 'divino' ),
				),
				array(
					'name'     => 'section-blog',
					'type'     => 'section',
					'priority' => 5,
					'title'    => __( 'Blog / Archive', 'divino' ),
					'section'  => 'section-blog-group',
				),
				array(
					'name'     => 'section-blog-single',
					'type'     => 'section',
					'priority' => 10,
					'title'    => __( 'Single Post', 'divino' ),
					'section'  => 'section-blog-group',
				),

				array(
					'name'     => 'section-page-dynamic-group',
					'type'     => 'section',
					'priority' => 40,
					'title'    => __( 'Page', 'divino' ),
				),
				array(
					'name'     => 'section-single-page',
					'type'     => 'section',
					'priority' => 10,
					'title'    => __( 'Single Page', 'divino' ),
					'section'  => 'section-blog-group',
				),

				array(
					'name'               => 'section-sidebars',
					'type'               => 'section',
					'priority'           => 50,
					'title'              => __( 'Sidebar', 'divino' ),
					'description_hidden' => true,
					'section'            => 'section-general-group',
					'description'        => $this->section_get_description(
						array(
							'description' => '<p><b>' . __( 'Helpful Information', 'divino' ) . '</b></p>',
							'links'       => array(
								array(
									'text'  => __( 'Sidebar Overview', 'divino' ) . ' &#187;',
									'attrs' => array(
										'href' => divino_get_pro_url( '/docs/sidebar-free/', 'free-theme', 'customizer', 'helpful_information' ),
									),
								),
							),
						)
					),
				),

				/**
				 * Accessibility Panel
				 *
				 * @since 4.1.0
				 */
				array(
					'name'     => 'section-accessibility',
					'type'     => 'section',
					'priority' => 65,
					'title'    => __( 'Accessibility', 'divino' ),
					'section'  => 'section-general-group',
				),

				/**
				 * Colors Panel
				 */
				array(
					'name'               => 'section-colors-background',
					'type'               => 'section',
					'priority'           => 16,
					'title'              => __( 'Colors', 'divino' ),
					'description_hidden' => true,
					'description'        => $this->section_get_description(
						array(
							'description' => '<p><b>' . __( 'Helpful Information', 'divino' ) . '</b></p>',
							'links'       => array(
								array(
									'text'  => __( 'Colors & Background Overview', 'divino' ) . ' &#187;',
									'attrs' => array(
										'href' => divino_get_pro_url( '/docs/colors-background/', 'free-theme', 'customizer', 'helpful_information' ),
									),
								),
							),
						)
					),
					'panel'              => 'panel-global',
				),

				array(
					'name'     => 'section-colors-body',
					'type'     => 'section',
					'title'    => __( 'Base Colors', 'divino' ),
					'panel'    => 'panel-global',
					'priority' => 1,
					'section'  => 'section-colors-background',
				),

				array(
					'name'     => 'section-footer-adv-color-bg',
					'type'     => 'section',
					'title'    => __( 'Footer Widgets', 'divino' ),
					'panel'    => 'panel-colors-background',
					'priority' => 55,
				),

				/**
				 * Typography Panel
				 */
				array(
					'name'               => 'section-typography',
					'type'               => 'section',
					'title'              => __( 'Typography', 'divino' ),
					'priority'           => 15,
					'description_hidden' => true,
					'description'        => $this->section_get_description(
						array(
							'description' => '<p><b>' . __( 'Helpful Information', 'divino' ) . '</b></p>',
							'links'       => array(
								array(
									'text'  => __( 'Typography Overview', 'divino' ) . ' &#187;',
									'attrs' => array(
										'href' => divino_get_pro_url( '/docs/typography-free/', 'free-theme', 'customizer', 'helpful_information' ),
									),
								),
							),
						)
					),
					'panel'              => 'panel-global',
				),

				array(
					'name'     => 'section-body-typo',
					'type'     => 'section',
					'title'    => __( 'Base Typography', 'divino' ),
					'section'  => 'section-typography',
					'priority' => 1,
					'panel'    => 'panel-global',
				),

				array(
					'name'     => 'section-content-typo',
					'type'     => 'section',
					'title'    => __( 'Headings', 'divino' ),
					'section'  => 'section-typography',
					'priority' => 35,
					'panel'    => 'panel-global',
				),

				/**
				 * Buttons Section
				 */
				array(
					'name'     => 'section-buttons',
					'type'     => 'section',
					'priority' => 50,
					'title'    => __( 'Buttons', 'divino' ),
					'panel'    => 'panel-global',
				),

				/**
				 * Header Buttons
				 */
				array(
					'name'     => 'section-header-button',
					'type'     => 'section',
					'priority' => 10,
					'title'    => __( 'Header Button', 'divino' ),
					'section'  => 'section-buttons',
				),

				/**
				 * Header Button - Default
				 */
				array(
					'name'     => 'section-header-button-default',
					'type'     => 'section',
					'priority' => 10,
					'title'    => __( 'Primary Header Button', 'divino' ),
					'section'  => 'section-header-button',
				),

				/**
				 * Header Button - Transparent
				 */
				array(
					'name'     => 'section-header-button-transparent',
					'type'     => 'section',
					'priority' => 10,
					'title'    => __( 'Transparent Header Button', 'divino' ),
					'section'  => 'section-header-button',
				),

				/**
				 * Block Editor specific configs.
				 */
				array(
					'name'     => 'section-block-editor',
					'type'     => 'section',
					'priority' => 80,
					'title'    => __( 'Block Editor', 'divino' ),
					'section'  => 'section-general-group',
				),

				/**
				 * Global Misc specific configs.
				 */
				array(
					'name'     => 'section-global-misc',
					'type'     => 'section',
					'priority' => 80,
					'title'    => __( 'Misc', 'divino' ),
					'section'  => 'section-general-group',
				),

				/**
				 * Option: Scroll To Top
				 */
				array(
					'name'     => 'section-scroll-to-top',
					'title'    => __( 'Scroll To Top', 'divino' ),
					'type'     => 'section',
					'section'  => 'section-general-group',
					'priority' => 60,
				),
			);

			// Add spacial page section under page group.
			foreach ( divino_Posts_Structure_Loader::get_special_page_types() as $index => $special_type ) {
				$configs[] = array(
					'name'     => 'ast-section-' . $special_type . '-page',
					'type'     => 'section',
					'priority' => 10 + absint( $index ),
					'title'    => sprintf(
						/* translators: %s: Name of special page type */
						esc_html__( '%s Page', 'divino' ),
						ucfirst( $special_type )
					),
					'section'  => 'section-blog-group',
				);
			}

			return array_merge( $configurations, $configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new divino_Customizer_Register_Sections_Panels();
