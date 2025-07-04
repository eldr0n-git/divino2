<?php
/**
 * Breadcrumbs Options for divino theme.
 *
 * @package     divino
 * @link        https://www.brainstormforce.com
 * @since       divino 1.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'divino_Breadcrumbs_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Breadcrumbs_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register divino-Breadcrumbs Settings.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.7.0
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$breadcrumb_source_list = apply_filters(
				'divino_breadcrumb_source_list',
				array(
					'default' => __( 'Default', 'divino' ),
				),
				'breadcrumb-list'
			);

			$_section = 'section-breadcrumb';

			$positions = array(
				'none'                      => __( 'None', 'divino' ),
				'divino_masthead_content'    => __( 'Inside', 'divino' ),
				'divino_header_markup_after' => __( 'After Header', 'divino' ),
				'divino_entry_top'           => __( 'Before Title', 'divino' ),
			);

			if ( true === divino_Builder_Helper::$is_header_footer_builder_active ) {
				$positions = array(
					'none'                                 => __( 'None', 'divino' ),
					'divino_header_primary_container_after' => __( 'Inside', 'divino' ),
					'divino_header_after'                   => __( 'After', 'divino' ),
					'divino_entry_top'                      => __( 'Before Title', 'divino' ),
				);
			}

			$_configs = array(

				/*
				 * Breadcrumb
				 */
				array(
					'name'               => $_section,
					'type'               => 'section',
					'section'            => 'section-general-group',
					'priority'           => 20,
					'title'              => __( 'Breadcrumb', 'divino' ),
					'description_hidden' => true,
					'description'        => $this->section_get_description(
						array(
							'description' => '<p><b>' . __( 'Helpful Information', 'divino' ) . '</b></p>',
							'links'       => array(
								array(
									'text'  => __( 'Breadcrumb Overview', 'divino' ) . ' &#187;',
									'attrs' => array(
										'href' => divino_get_pro_url( '/docs/add-breadcrumbs-with-divino/', 'free-theme', 'customizer', 'helpful_information' ),
									),
								),
							),
						)
					),
				),

				/**
				 * Option: Breadcrumb Position
				 */
				array(
					'name'       => divino_THEME_SETTINGS . '[breadcrumb-position]',
					'default'    => divino_get_option( 'breadcrumb-position', 'none' ),
					'section'    => $_section,
					'title'      => __( 'Header Position', 'divino' ),
					'type'       => 'control',
					'control'    => 'ast-select',
					'priority'   => 5,
					'choices'    => $positions,
					'partial'    => array(
						'selector'            => '.ast-breadcrumbs-wrapper .ast-breadcrumbs .trail-items',
						'container_inclusive' => false,
					),
					'context'    => divino_Builder_Helper::$general_tab,
					'responsive' => false,
					'renderAs'   => 'text',
					'divider'    => array( 'ast_class' => 'ast-section-spacing' ),
				),

				// Breadcrumb if set to None - Show the notice under the Design tab.
				array(
					'name'     => divino_THEME_SETTINGS . '[breadcrumb-position-none-notice]',
					'type'     => 'control',
					'control'  => 'ast-description',
					'section'  => $_section,
					'priority' => 5,
					'label'    => '',
					'help'     => __( 'Note: To get design settings in action make sure to select Header Position other than None.', 'divino' ),
					'context'  => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '==',
							'value'    => 'none',
						),
						true === divino_Builder_Helper::$is_header_footer_builder_active ?
							divino_Builder_Helper::$design_tab_config : divino_Builder_Helper::$general_tab_config,
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[breadcrumb-disable-layout-divider]',
					'section'  => $_section,
					'title'    => __( 'Display Settings', 'divino' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 25,
					'context'  => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						divino_Builder_Helper::$general_tab_config,
					),
				),

				array(
					'name'     => divino_THEME_SETTINGS . '[breadcrumb-disable-on]',
					'section'  => $_section,
					'title'    => __( 'Enable on', 'divino' ),
					'type'     => 'control',
					'control'  => 'ast-multiselect-checkbox-group',
					'priority' => 25,
					'options'  => array(
						'showAllButton' => true,
					),
					'context'  => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						divino_Builder_Helper::$general_tab_config,
					),
					'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: Disable Breadcrumb on Categories
				 */
				array(
					'name'     => 'breadcrumb-disable-home-page',
					'default'  => divino_get_option( 'breadcrumb-disable-home-page', '1' ),
					'parent'   => divino_THEME_SETTINGS . '[breadcrumb-disable-on]',
					'type'     => 'sub-control',
					'section'  => $_section,
					'title'    => __( 'Home Page?', 'divino' ),
					'priority' => 25,
					'control'  => 'ast-toggle-control',
					'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: Disable Breadcrumb on Categories
				 */
				array(
					'name'        => 'breadcrumb-disable-blog-posts-page',
					'default'     => divino_get_option( 'breadcrumb-disable-blog-posts-page', '1' ),
					'parent'      => divino_THEME_SETTINGS . '[breadcrumb-disable-on]',
					'type'        => 'sub-control',
					'section'     => $_section,
					'description' => __( 'Latest posts page or when any page is selected as blog page', 'divino' ),
					'title'       => __( 'Blog / Posts Page?', 'divino' ),
					'priority'    => 25,
					'control'     => 'ast-toggle-control',
				),

				/**
				 * Option: Disable Breadcrumb on Search
				 */
				array(
					'name'     => 'breadcrumb-disable-search',
					'default'  => divino_get_option( 'breadcrumb-disable-search', '1' ),
					'parent'   => divino_THEME_SETTINGS . '[breadcrumb-disable-on]',
					'type'     => 'sub-control',
					'section'  => $_section,
					'title'    => __( 'Search?', 'divino' ),
					'priority' => 30,
					'control'  => 'ast-toggle-control',
				),

				/**
				 * Option: Disable Breadcrumb on Archive
				 */
				array(
					'name'     => 'breadcrumb-disable-archive',
					'default'  => divino_get_option( 'breadcrumb-disable-archive', '1' ),
					'parent'   => divino_THEME_SETTINGS . '[breadcrumb-disable-on]',
					'type'     => 'sub-control',
					'section'  => $_section,
					'title'    => __( 'Archive?', 'divino' ),
					'priority' => 35,
					'control'  => 'ast-toggle-control',
				),

				/**
				 * Option: Disable Breadcrumb on Single Page
				 */
				array(
					'name'     => 'breadcrumb-disable-single-page',
					'default'  => divino_get_option( 'breadcrumb-disable-single-page', '1' ),
					'parent'   => divino_THEME_SETTINGS . '[breadcrumb-disable-on]',
					'type'     => 'sub-control',
					'section'  => $_section,
					'title'    => __( 'Single Page?', 'divino' ),
					'priority' => 40,
					'control'  => 'ast-toggle-control',
				),

				/**
				 * Option: Disable Breadcrumb on Single Post
				 */
				array(
					'name'     => 'breadcrumb-disable-single-post',
					'default'  => divino_get_option( 'breadcrumb-disable-single-post', '1' ),
					'parent'   => divino_THEME_SETTINGS . '[breadcrumb-disable-on]',
					'type'     => 'sub-control',
					'section'  => $_section,
					'title'    => __( 'Single Post?', 'divino' ),
					'priority' => 45,
					'control'  => 'ast-toggle-control',
				),

				/**
				 * Option: Disable Breadcrumb on Singular
				 */
				array(
					'name'        => 'breadcrumb-disable-singular',
					'default'     => divino_get_option( 'breadcrumb-disable-singular', '1' ),
					'parent'      => divino_THEME_SETTINGS . '[breadcrumb-disable-on]',
					'type'        => 'sub-control',
					'section'     => $_section,
					'description' => __( 'All Pages, All Posts, All Attachments', 'divino' ),
					'title'       => __( 'Singular?', 'divino' ),
					'priority'    => 50,
					'control'     => 'ast-toggle-control',
				),

				/**
				 * Option: Disable Breadcrumb on 404 Page
				 */
				array(
					'name'     => 'breadcrumb-disable-404-page',
					'default'  => divino_get_option( 'breadcrumb-disable-404-page', '1' ),
					'parent'   => divino_THEME_SETTINGS . '[breadcrumb-disable-on]',
					'type'     => 'sub-control',
					'section'  => $_section,
					'title'    => __( '404 Page?', 'divino' ),
					'priority' => 55,
					'control'  => 'ast-toggle-control',
				),

				/**
				 * Option: Breadcrumb Alignment
				 */
				array(
					'name'       => divino_THEME_SETTINGS . '[breadcrumb-alignment]',
					'default'    => divino_get_option( 'breadcrumb-alignment', 'left' ),
					'section'    => $_section,
					'transport'  => 'postMessage',
					'title'      => __( 'Alignment', 'divino' ),
					'type'       => 'control',
					'control'    => 'ast-selector',
					'priority'   => 24,
					'context'    => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						divino_Builder_Helper::$general_tab_config,
					),
					'choices'    => array(
						'left'   => 'align-left',
						'center' => 'align-center',
						'right'  => 'align-right',
					),
					'responsive' => false,
					'divider'    => array( 'ast_class' => 'ast-top-section-divider ast-bottom-spacing' ),
				),

				/**
				 * Option: Breadcrumb Spacing
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[breadcrumb-spacing]',
					'default'           => divino_get_option( 'breadcrumb-spacing' ),
					'type'              => 'control',
					'transport'         => 'postMessage',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'priority'          => 83,
					'title'             => __( 'Spacing', 'divino' ),
					'linked_choices'    => true,
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'divino' ),
						'right'  => __( 'Right', 'divino' ),
						'bottom' => __( 'Bottom', 'divino' ),
						'left'   => __( 'Left', 'divino' ),
					),

					'section'           => $_section,
					'context'           => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						true === divino_Builder_Helper::$is_header_footer_builder_active ?
							divino_Builder_Helper::$design_tab_config : divino_Builder_Helper::$general_tab_config,
					),
				),
			);

			if ( $this->is_third_party_breadcrumb_active() ) {

				$_configs[] = array(
					'name'     => divino_THEME_SETTINGS . '[select-breadcrumb-source]',
					'default'  => divino_get_option( 'select-breadcrumb-source', 'default' ),
					'section'  => $_section,
					'title'    => __( 'Breadcrumb Source', 'divino' ),
					'type'     => 'control',
					'control'  => 'ast-select',
					'priority' => 10,
					'choices'  => $breadcrumb_source_list,
					'context'  => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						divino_Builder_Helper::$general_tab_config,
					),
					'divider'  => array( 'ast_class' => 'ast-top-divider' ),
				);
			}

			if ( $this->is_selected_breadcrumb_active() ) {

				/**
				 * Option: Breadcrumb separator
				 */

				$_configs[] = array(
					'name'              => divino_THEME_SETTINGS . '[breadcrumb-separator-selector]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_choices' ),
					'default'           => divino_get_option( 'breadcrumb-separator-selector' ),
					'priority'          => 15,
					'title'             => __( 'Separator', 'divino' ),
					'section'           => $_section,
					'choices'           => array(
						'\003E'   => array(
							'label' => __( 'Type 1', 'divino' ),
							'path'  => divino_Builder_UI_Controller::fetch_svg_icon( 'breadcrumb-separator-1' ),
						),
						'\00BB'   => array(
							'label' => __( 'Type 2', 'divino' ),
							'path'  => divino_Builder_UI_Controller::fetch_svg_icon( 'breadcrumb-separator-2' ),
						),
						'\002F'   => array(
							'label' => __( 'Type 3', 'divino' ),
							'path'  => divino_Builder_UI_Controller::fetch_svg_icon( 'breadcrumb-separator-3' ),
						),
						'unicode' => array(
							'label' => __( 'Custom separator', 'divino' ),
							'path'  => divino_Builder_UI_Controller::fetch_svg_icon( 'breadcrumb-separator-unicode' ),
						),
					),
					'alt_layout'        => true,
					'transport'         => 'postMessage',
					'context'           => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						divino_Builder_Helper::$general_tab_config,
					),
					'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
				);

				/**
				 * Option: Breadcrumb Unicode input
				 */

				$_configs[] = array(
					'name'      => divino_THEME_SETTINGS . '[breadcrumb-separator]',
					'type'      => 'control',
					'control'   => 'text',
					'section'   => $_section,
					'default'   => divino_get_option( 'breadcrumb-separator' ),
					'priority'  => 15,
					'title'     => __( 'Unicode', 'divino' ),
					'context'   => array(
						array(
							'setting'  => divino_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						array(
							'setting'  => divino_THEME_SETTINGS . '[breadcrumb-separator-selector]',
							'operator' => '=',
							'value'    => 'unicode',
						),
						divino_Builder_Helper::$general_tab_config,
					),
					'transport' => 'postMessage',
				);
			}

			if ( true === divino_Builder_Helper::$is_header_footer_builder_active ) {

				$_configs[] = array(
					'name'        => $_section . '-ast-context-tabs',
					'section'     => $_section,
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
				);
			}

			return array_merge( $configurations, $_configs );
		}

		/**
		 * Is third-party breadcrumb active.
		 * Decide if the Source option should be visible depending on third party plugins.
		 *
		 * @return bool  True - If the option should be displayed, False - If the option should be hidden.
		 */
		public function is_third_party_breadcrumb_active() {

			// Check if breadcrumb is turned on from WPSEO option.
			$breadcrumb_enable = is_callable( 'WPSEO_Options::get' ) ? WPSEO_Options::get( 'breadcrumbs-enable' ) : false;
			$wpseo_option      = get_option( 'wpseo_internallinks' ) ? get_option( 'wpseo_internallinks' ) : $breadcrumb_enable;
			if ( ! is_array( $wpseo_option ) ) {
				unset( $wpseo_option );
				$wpseo_option = array(
					'breadcrumbs-enable' => $breadcrumb_enable,
				);
			}

			// Check if breadcrumb is turned on from SEO Yoast plugin.
			if ( function_exists( 'yoast_breadcrumb' ) && true === $wpseo_option['breadcrumbs-enable'] ) {
				return true;
			}

			// Check if breadcrumb is turned on from Breadcrumb NavXT plugin.
			if ( function_exists( 'bcn_display' ) ) {
				return true;
			}

			// Check if breadcrumb is turned on from Rank Math plugin.
			if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
				return true;
			}

			// Check if breadcrumb is turned on from SEOPress plugin.
			if ( function_exists( 'seopress_display_breadcrumbs' ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Is selected third-party breadcrumb active.
		 * Decide if the Separator option should be visible depending on third party plugins.
		 *
		 * @return bool  True - If the option should be displayed, False - If the option should be hidden.
		 */
		public function is_selected_breadcrumb_active() {

			// Check if breadcrumb is turned on from WPSEO option.
			$selected_breadcrumb_source = divino_get_option( 'select-breadcrumb-source' );
			$breadcrumb_enable          = is_callable( 'WPSEO_Options::get' ) ? WPSEO_Options::get( 'breadcrumbs-enable' ) : false;
			$wpseo_option               = get_option( 'wpseo_internallinks' ) ? get_option( 'wpseo_internallinks' ) : $breadcrumb_enable;
			if ( ! is_array( $wpseo_option ) ) {

				unset( $wpseo_option );
				$wpseo_option = array(
					'breadcrumbs-enable' => $breadcrumb_enable,
				);
			}

			// Check if breadcrumb is turned on from SEO Yoast plugin.
			if ( function_exists( 'yoast_breadcrumb' ) && true === $wpseo_option['breadcrumbs-enable'] && 'yoast-seo-breadcrumbs' === $selected_breadcrumb_source ) {
				return false;
			}

			// Check if breadcrumb is turned on from Breadcrumb NavXT plugin.
			if ( function_exists( 'bcn_display' ) && 'breadcrumb-navxt' === $selected_breadcrumb_source ) {
				return false;
			}

			// Check if breadcrumb is turned on from Rank Math plugin.
			if ( function_exists( 'rank_math_the_breadcrumbs' ) && 'rank-math' === $selected_breadcrumb_source ) {
				return false;
			}

			// Check if breadcrumb is turned on from SEOPress plugin.
			if ( function_exists( 'seopress_display_breadcrumbs' ) ) {
				return false;
			}

			return true;
		}
	}
}

new divino_Breadcrumbs_Configs();
