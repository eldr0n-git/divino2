<?php
/**
 * Search - Dynamic CSS
 *
 * @package Astra
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Search
 */
add_filter( 'divino_dynamic_theme_css', 'divino_hb_search_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Search.
 *
 * @since 3.0.0
 */
function divino_hb_search_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( ! divino_Builder_Helper::is_component_loaded( 'search', 'header' ) ) {
		return $dynamic_css;
	}

	$_section                = 'section-header-search';
	$selector                = '.ast-header-search';
	$icon_size               = divino_get_option( 'header-search-icon-space' );
	$search_width            = divino_get_option( 'header-search-width' );
	$search_type             = divino_get_option( 'header-search-box-type' );
	$live_search             = divino_get_option( 'live-search' );
	$search_width_applicable = ! defined( 'divino_EXT_VER' ) || ( defined( 'divino_EXT_VER' ) && ( 'slide-search' === $search_type || 'search-box' === $search_type ) ) ? true : false;

	$icon_size_desktop = isset( $icon_size ) && isset( $icon_size['desktop'] ) && ! empty( $icon_size['desktop'] ) ? $icon_size['desktop'] : 20;

	$icon_size_tablet = isset( $icon_size ) && isset( $icon_size['tablet'] ) && ! empty( $icon_size['tablet'] ) ? $icon_size['tablet'] : 20;

	$icon_size_mobile = isset( $icon_size ) && isset( $icon_size['mobile'] ) && ! empty( $icon_size['mobile'] ) ? $icon_size['mobile'] : 20;

	$icon_color_desktop = divino_get_prop( divino_get_option( 'header-search-icon-color' ), 'desktop' );
	$icon_color_tablet  = divino_get_prop( divino_get_option( 'header-search-icon-color' ), 'tablet' );
	$icon_color_mobile  = divino_get_prop( divino_get_option( 'header-search-icon-color' ), 'mobile' );

	$margin          = divino_get_option( $_section . '-margin' );
	$margin_selector = '.ast-hfb-header .site-header-section > .ast-header-search, .ast-hfb-header .ast-header-search';

	/**
	 * Search CSS.
	 */
	$css_output_desktop = array(
		$selector . ' .ast-search-menu-icon .search-form .search-field:-ms-input-placeholder,' . $selector . ' .ast-search-menu-icon .search-form .search-field:-ms-input-placeholder' => array(
			'opacity' => '0.5',
		),
		$selector . ' .ast-search-menu-icon.slide-search .search-form, .ast-header-search .ast-search-menu-icon.ast-inline-search .search-form' => array(
			'-js-display' => 'flex',
			'display'     => 'flex',
			'align-items' => 'center',
		),
		'.ast-builder-layout-element.ast-header-search' => array(
			'height' => 'auto',
		),
		$selector . ' .astra-search-icon'               => array(
			'color'     => esc_attr( $icon_color_desktop ),
			'font-size' => divino_get_css_value( $icon_size_desktop, 'px' ),
		),
		$selector . ' .search-field::placeholder,' . $selector . ' .ast-icon' => array(
			'color' => esc_attr( $icon_color_desktop ),
		),
		$margin_selector                                => array(
			// Margin CSS.
			'margin-top'    => divino_responsive_spacing( $margin, 'top', 'desktop' ),
			'margin-bottom' => divino_responsive_spacing( $margin, 'bottom', 'desktop' ),
			'margin-left'   => divino_responsive_spacing( $margin, 'left', 'desktop' ),
			'margin-right'  => divino_responsive_spacing( $margin, 'right', 'desktop' ),
		),
	);

	if ( $search_width_applicable ) {
		$css_output_desktop[ $selector . ' form.search-form .search-field, ' . $selector . ' .ast-dropdown-active.ast-search-menu-icon.slide-search input.search-field' ] = array(
			'width' => divino_get_css_value( divino_get_prop( $search_width, 'desktop' ), 'px' ),
		);
	}

	if ( $live_search && divino_Builder_Helper::is_component_loaded( 'search', 'header' ) && ! is_customize_preview() && apply_filters( 'divino_increased_search_icon_zindex', true ) ) {
		$css_output_desktop['.ast-search-menu-icon'] = array(
			'z-index' => '5', // To fix search results container overlapping issue with menu (AST-3605).
		);
	}

	$css_output_tablet = array(

		$selector . ' .astra-search-icon' => array(
			'color'     => esc_attr( $icon_color_tablet ),
			'font-size' => divino_get_css_value( $icon_size_tablet, 'px' ),
		),
		$selector . ' .search-field::placeholder,' . $selector . ' .ast-icon' => array(
			'color' => esc_attr( $icon_color_tablet ),
		),
		$margin_selector                  => array(
			// Margin CSS.
			'margin-top'    => divino_responsive_spacing( $margin, 'top', 'tablet' ),
			'margin-bottom' => divino_responsive_spacing( $margin, 'bottom', 'tablet' ),
			'margin-left'   => divino_responsive_spacing( $margin, 'left', 'tablet' ),
			'margin-right'  => divino_responsive_spacing( $margin, 'right', 'tablet' ),
		),
	);

	if ( $search_width_applicable ) {
		$css_output_tablet[ $selector . ' form.search-form .search-field, ' . $selector . ' .ast-dropdown-active.ast-search-menu-icon.slide-search input.search-field, .ast-mobile-header-content .ast-search-menu-icon .search-form' ] = array(
			'width' => divino_get_css_value( divino_get_prop( $search_width, 'tablet' ), 'px' ),
		);
	}

	if ( $live_search && divino_Builder_Helper::is_component_loaded( 'search', 'header', 'mobile' ) ) {
		$css_output_tablet['.ast-mobile-header-content .ast-header-search .ast-search-menu-icon .search-form'] = array(
			'overflow' => 'visible', // To fix search results container should overflow inside offcanvas (AST-3604).
		);
	}

	$css_output_mobile = array(

		$selector . ' .astra-search-icon' => array(
			'color'     => esc_attr( $icon_color_mobile ),
			'font-size' => divino_get_css_value( $icon_size_mobile, 'px' ),
		),
		$selector . ' .search-field::placeholder,' . $selector . ' .ast-icon' => array(
			'color' => esc_attr( $icon_color_mobile ),
		),
		$margin_selector                  => array(
			// Margin CSS.
			'margin-top'    => divino_responsive_spacing( $margin, 'top', 'mobile' ),
			'margin-bottom' => divino_responsive_spacing( $margin, 'bottom', 'mobile' ),
			'margin-left'   => divino_responsive_spacing( $margin, 'left', 'mobile' ),
			'margin-right'  => divino_responsive_spacing( $margin, 'right', 'mobile' ),
		),
	);

	if ( $search_width_applicable ) {
		$css_output_mobile[ $selector . ' form.search-form .search-field, ' . $selector . ' .ast-dropdown-active.ast-search-menu-icon.slide-search input.search-field, .ast-mobile-header-content .ast-search-menu-icon .search-form' ] = array(
			'width' => divino_get_css_value( divino_get_prop( $search_width, 'mobile' ), 'px' ),
		);
	}

	/* Parse CSS from array() */
	$css_output  = divino_search_static_css();
	$css_output .= divino_parse_css( $css_output_desktop );
	$css_output .= divino_parse_css( $css_output_tablet, '', divino_get_tablet_breakpoint() );
	$css_output .= divino_parse_css( $css_output_mobile, '', divino_get_mobile_breakpoint() );

	$dynamic_css .= $css_output;

	$dynamic_css .= divino_Builder_Base_Dynamic_CSS::prepare_visibility_css( $_section, $selector );

	return $dynamic_css;
}
