<?php
/**
 * Header Menu Colors - Dynamic CSS
 *
 * @package astra-builder
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Header Menu Colors
 */
add_filter( 'divino_dynamic_theme_css', 'divino_hb_mobile_menu_dynamic_css', 11 );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Header Menu Colors.
 *
 * @since 3.0.0
 */
function divino_hb_mobile_menu_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( ! divino_Builder_Helper::is_component_loaded( 'mobile-menu', 'header' ) ) {
		return $dynamic_css;
	}

	$_section = 'section-header-mobile-menu';

	$selector = '.ast-builder-menu-mobile .main-navigation';

	// Sub Menu.
	$sub_menu_divider_toggle = divino_get_option( 'header-mobile-menu-submenu-item-border' );
	$sub_menu_divider_size   = divino_get_option( 'header-mobile-menu-submenu-item-b-size' );
	$sub_menu_divider_color  = divino_get_option( 'header-mobile-menu-submenu-item-b-color' );

	// Menu.
	$menu_resp_color           = divino_get_option( 'header-mobile-menu-color-responsive' );
	$menu_resp_bg_color        = divino_get_option( 'header-mobile-menu-bg-obj-responsive' );
	$menu_resp_color_hover     = divino_get_option( 'header-mobile-menu-h-color-responsive' );
	$menu_resp_bg_color_hover  = divino_get_option( 'header-mobile-menu-h-bg-color-responsive' );
	$menu_resp_color_active    = divino_get_option( 'header-mobile-menu-a-color-responsive' );
	$menu_resp_bg_color_active = divino_get_option( 'header-mobile-menu-a-bg-color-responsive' );

	$submenu_resp_bg_color = divino_get_option( 'header-mobile-menu-submenu-bg-color-responsive' );

	$menu_resp_color_desktop = isset( $menu_resp_color['desktop'] ) ? $menu_resp_color['desktop'] : '';
	$menu_resp_color_tablet  = isset( $menu_resp_color['tablet'] ) ? $menu_resp_color['tablet'] : '';
	$menu_resp_color_mobile  = isset( $menu_resp_color['mobile'] ) ? $menu_resp_color['mobile'] : '';

	$menu_resp_color_hover_desktop = isset( $menu_resp_color_hover['desktop'] ) ? $menu_resp_color_hover['desktop'] : '';
	$menu_resp_color_hover_tablet  = isset( $menu_resp_color_hover['tablet'] ) ? $menu_resp_color_hover['tablet'] : '';
	$menu_resp_color_hover_mobile  = isset( $menu_resp_color_hover['mobile'] ) ? $menu_resp_color_hover['mobile'] : '';

	$menu_resp_bg_color_hover_desktop = isset( $menu_resp_bg_color_hover['desktop'] ) ? $menu_resp_bg_color_hover['desktop'] : '';
	$menu_resp_bg_color_hover_tablet  = isset( $menu_resp_bg_color_hover['tablet'] ) ? $menu_resp_bg_color_hover['tablet'] : '';
	$menu_resp_bg_color_hover_mobile  = isset( $menu_resp_bg_color_hover['mobile'] ) ? $menu_resp_bg_color_hover['mobile'] : '';

	$menu_resp_color_active_desktop = isset( $menu_resp_color_active['desktop'] ) ? $menu_resp_color_active['desktop'] : '';
	$menu_resp_color_active_tablet  = isset( $menu_resp_color_active['tablet'] ) ? $menu_resp_color_active['tablet'] : '';
	$menu_resp_color_active_mobile  = isset( $menu_resp_color_active['mobile'] ) ? $menu_resp_color_active['mobile'] : '';

	$menu_resp_bg_color_active_desktop = isset( $menu_resp_bg_color_active['desktop'] ) ? $menu_resp_bg_color_active['desktop'] : '';
	$menu_resp_bg_color_active_tablet  = isset( $menu_resp_bg_color_active['tablet'] ) ? $menu_resp_bg_color_active['tablet'] : '';
	$menu_resp_bg_color_active_mobile  = isset( $menu_resp_bg_color_active['mobile'] ) ? $menu_resp_bg_color_active['mobile'] : '';

	// Typography.
	$menu_font_size              = divino_get_option( 'header-mobile-menu-font-size' );
	$menu_font_size_desktop      = isset( $menu_font_size['desktop'] ) ? $menu_font_size['desktop'] : '';
	$menu_font_size_tablet       = isset( $menu_font_size['tablet'] ) ? $menu_font_size['tablet'] : '';
	$menu_font_size_mobile       = isset( $menu_font_size['mobile'] ) ? $menu_font_size['mobile'] : '';
	$menu_font_size_desktop_unit = isset( $menu_font_size['desktop-unit'] ) ? $menu_font_size['desktop-unit'] : '';
	$menu_font_size_tablet_unit  = isset( $menu_font_size['tablet-unit'] ) ? $menu_font_size['tablet-unit'] : '';
	$menu_font_size_mobile_unit  = isset( $menu_font_size['mobile-unit'] ) ? $menu_font_size['mobile-unit'] : '';

	// Spacing.
	$menu_spacing = divino_get_option( 'header-mobile-menu-menu-spacing' );

	$sub_menu_divider_color = true === $sub_menu_divider_toggle ? $sub_menu_divider_color : '';

	// Margin.
	$margin          = divino_get_option( $_section . '-margin' );
	$margin_selector = '.ast-builder-menu-mobile .main-header-menu, .ast-header-break-point .ast-builder-menu-mobile .main-header-menu';

	$menu_spacing_desktop_top = divino_responsive_spacing( $menu_spacing, 'top', 'desktop' );
	$menu_spacing_desktop_top = isset( $menu_spacing_desktop_top ) && ! empty( $menu_spacing_desktop_top ) ? $menu_spacing_desktop_top : 0;

	$menu_spacing_tablet_top = divino_responsive_spacing( $menu_spacing, 'top', 'tablet' );
	$menu_spacing_tablet_top = isset( $menu_spacing_tablet_top ) && ! empty( $menu_spacing_tablet_top ) ? $menu_spacing_tablet_top : 0;

	$menu_spacing_mobile_top = divino_responsive_spacing( $menu_spacing, 'top', 'mobile' );

	if ( isset( $menu_spacing_mobile_top ) && '' === $menu_spacing_mobile_top && isset( $menu_spacing_tablet_top ) && '' !== $menu_spacing_tablet_top && 0 !== $menu_spacing_tablet_top ) {

		$menu_spacing_mobile_top = $menu_spacing_tablet_top;
	}

	$menu_spacing_mobile_top = isset( $menu_spacing_mobile_top ) && ! empty( $menu_spacing_mobile_top ) ? $menu_spacing_mobile_top : 0;

	$css_output_desktop = array(

		$selector . ' .menu-item > .menu-link'             => divino_get_font_array_css( divino_get_option( 'header-mobile-menu-font-family' ), divino_get_option( 'header-mobile-menu-font-weight' ), array(), 'font-extras-header-mobile-menu' ),
		$selector                                          => array(
			'font-size' => divino_get_font_css_value( $menu_font_size_desktop, $menu_font_size_desktop_unit ),
		),
		$selector . ' .main-header-menu .menu-item > .menu-link' => array(
			'color'          => $menu_resp_color_desktop,
			'padding-top'    => divino_responsive_spacing( $menu_spacing, 'top', 'desktop' ),
			'padding-bottom' => divino_responsive_spacing( $menu_spacing, 'bottom', 'desktop' ),
			'padding-left'   => divino_responsive_spacing( $menu_spacing, 'left', 'desktop' ),
			'padding-right'  => divino_responsive_spacing( $menu_spacing, 'right', 'desktop' ),
		),
		$selector . ' .main-header-menu .menu-item > .ast-menu-toggle' => array(
			'color' => $menu_resp_color_desktop,
		),
		$selector . ' .main-header-menu .menu-item:hover > .menu-link, ' . $selector . ' .inline-on-mobile .menu-item:hover > .ast-menu-toggle' => array(
			'color'      => $menu_resp_color_hover_desktop,
			'background' => $menu_resp_bg_color_hover_desktop,
		),

		'.ast-builder-menu-mobile .menu-item:hover > .menu-link, ' . $selector . ' .inline-on-mobile .menu-item:hover > .ast-menu-toggle' => array(
			'color'      => $menu_resp_color_hover_desktop,
			'background' => $menu_resp_bg_color_hover_desktop,
		),

		$selector . ' .menu-item:hover > .ast-menu-toggle' => array(
			'color' => $menu_resp_color_hover_desktop,
		),
		$selector . ' .menu-item.current-menu-item > .menu-link, ' . $selector . ' .inline-on-mobile .menu-item.current-menu-item > .ast-menu-toggle, ' . $selector . ' .menu-item.current-menu-ancestor > .menu-link, ' . $selector . ' .menu-item.current-menu-ancestor > .ast-menu-toggle' => array(
			'color'      => $menu_resp_color_active_desktop,
			'background' => $menu_resp_bg_color_active_desktop,
		),
		$selector . ' .menu-item.current-menu-item > .ast-menu-toggle' => array(
			'color' => $menu_resp_color_active_desktop,
		),
		$selector . ' .menu-item.menu-item-has-children > .ast-menu-toggle' => array(
			'top'   => $menu_spacing_desktop_top,
			'right' => divino_calculate_spacing( divino_responsive_spacing( $menu_spacing, 'right', 'desktop' ), '-', '0.907', 'em' ),
		),
		$selector . ' .menu-item-has-children > .menu-link:after' => array(
			'content' => 'unset',
		),
		// Margin CSS.
		$margin_selector                                   => array(
			'margin-top'    => divino_responsive_spacing( $margin, 'top', 'desktop' ),
			'margin-bottom' => divino_responsive_spacing( $margin, 'bottom', 'desktop' ),
			'margin-left'   => divino_responsive_spacing( $margin, 'left', 'desktop' ),
			'margin-right'  => divino_responsive_spacing( $margin, 'right', 'desktop' ),
		),
	);

	$css_output_desktop[ $selector . ' .main-header-menu, ' . $selector . ' .main-header-menu .menu-link, ' . $selector . ' .main-header-menu .sub-menu' ] = divino_get_responsive_background_obj( $menu_resp_bg_color, 'desktop' );
	$css_output_desktop[ $selector . ' .main-header-menu .sub-menu .menu-link' ] = array(
		'background-color' => isset( $submenu_resp_bg_color['desktop'] ) ? $submenu_resp_bg_color['desktop'] : '',
	);

	$css_output_tablet = array(

		$selector                                          => array(
			'font-size' => divino_get_font_css_value( $menu_font_size_tablet, $menu_font_size_tablet_unit ),
		),
		$selector . ' .main-header-menu .menu-item > .menu-link' => array(
			'color'          => $menu_resp_color_tablet,
			'padding-top'    => divino_responsive_spacing( $menu_spacing, 'top', 'tablet' ),
			'padding-bottom' => divino_responsive_spacing( $menu_spacing, 'bottom', 'tablet' ),
			'padding-left'   => divino_responsive_spacing( $menu_spacing, 'left', 'tablet' ),
			'padding-right'  => divino_responsive_spacing( $menu_spacing, 'right', 'tablet' ),
		),
		$selector . ' .main-header-menu .menu-item > .ast-menu-toggle' => array(
			'color' => $menu_resp_color_tablet,
		),
		$selector . ' .main-header-menu .menu-item:hover > .menu-link, ' . $selector . ' .inline-on-mobile .menu-item:hover > .ast-menu-toggle' => array(
			'color'      => $menu_resp_color_hover_tablet,
			'background' => $menu_resp_bg_color_hover_tablet,
		),
		$selector . ' .menu-item:hover > .ast-menu-toggle' => array(
			'color' => $menu_resp_color_hover_tablet,
		),
		$selector . ' .menu-item.current-menu-item > .menu-link, ' . $selector . ' .inline-on-mobile .menu-item.current-menu-item > .ast-menu-toggle, ' . $selector . ' .menu-item.current-menu-ancestor > .menu-link, ' . $selector . ' .menu-item.current-menu-ancestor > .ast-menu-toggle' => array(
			'color'      => $menu_resp_color_active_tablet,
			'background' => $menu_resp_bg_color_active_tablet,
		),
		$selector . ' .menu-item.current-menu-item > .ast-menu-toggle' => array(
			'color' => $menu_resp_color_active_tablet,
		),
		$selector . ' .menu-item.menu-item-has-children > .ast-menu-toggle' => array(
			'top'   => $menu_spacing_tablet_top,
			'right' => divino_calculate_spacing( divino_responsive_spacing( $menu_spacing, 'right', 'tablet' ), '-', '0.907', 'em' ),
		),
		$selector . ' .menu-item-has-children > .menu-link:after' => array(
			'content' => 'unset',
		),
		// Margin CSS.
		$margin_selector                                   => array(
			'margin-top'    => divino_responsive_spacing( $margin, 'top', 'tablet' ),
			'margin-bottom' => divino_responsive_spacing( $margin, 'bottom', 'tablet' ),
			'margin-left'   => divino_responsive_spacing( $margin, 'left', 'tablet' ),
			'margin-right'  => divino_responsive_spacing( $margin, 'right', 'tablet' ),
		),
	);

	$css_output_tablet[ $selector . ' .main-header-menu , ' . $selector . ' .main-header-menu .menu-link, ' . $selector . ' .main-header-menu .sub-menu' ] = divino_get_responsive_background_obj( $menu_resp_bg_color, 'tablet' );
	$css_output_tablet[ $selector . ' .main-header-menu .sub-menu .menu-link' ] = array(
		'background-color' => isset( $submenu_resp_bg_color['tablet'] )
			? $submenu_resp_bg_color['tablet']
			: ( isset( $submenu_resp_bg_color['desktop'] ) ? $submenu_resp_bg_color['desktop'] : '' ),
	);

	$css_output_mobile = array(

		$selector        => array(
			'font-size' => divino_get_font_css_value( $menu_font_size_mobile, $menu_font_size_mobile_unit ),
		),
		$selector . ' .main-header-menu .menu-item > .menu-link' => array(
			'color'          => $menu_resp_color_mobile,
			'padding-top'    => divino_responsive_spacing( $menu_spacing, 'top', 'mobile' ),
			'padding-bottom' => divino_responsive_spacing( $menu_spacing, 'bottom', 'mobile' ),
			'padding-left'   => divino_responsive_spacing( $menu_spacing, 'left', 'mobile' ),
			'padding-right'  => divino_responsive_spacing( $menu_spacing, 'right', 'mobile' ),
		),
		$selector . ' .main-header-menu .menu-item  > .ast-menu-toggle' => array(
			'color' => $menu_resp_color_mobile,
		),
		$selector . ' .main-header-menu .menu-item:hover > .menu-link, ' . $selector . ' .inline-on-mobile .menu-item:hover > .ast-menu-toggle' => array(
			'color'      => $menu_resp_color_hover_mobile,
			'background' => $menu_resp_bg_color_hover_mobile,
		),
		$selector . ' .menu-item:hover  > .ast-menu-toggle' => array(
			'color' => $menu_resp_color_hover_mobile,
		),
		$selector . ' .menu-item.current-menu-item > .menu-link, ' . $selector . ' .inline-on-mobile .menu-item.current-menu-item > .ast-menu-toggle, ' . $selector . ' .menu-item.current-menu-ancestor > .menu-link, ' . $selector . ' .menu-item.current-menu-ancestor > .ast-menu-toggle' => array(
			'color'      => $menu_resp_color_active_mobile,
			'background' => $menu_resp_bg_color_active_mobile,
		),
		$selector . ' .menu-item.current-menu-item  > .ast-menu-toggle' => array(
			'color' => $menu_resp_color_active_mobile,
		),
		$selector . ' .menu-item.menu-item-has-children > .ast-menu-toggle' => array(
			'top'   => $menu_spacing_mobile_top,
			'right' => divino_calculate_spacing( divino_responsive_spacing( $menu_spacing, 'right', 'mobile' ), '-', '0.907', 'em' ),
		),
		// Margin CSS.
		$margin_selector => array(
			'margin-top'    => divino_responsive_spacing( $margin, 'top', 'mobile' ),
			'margin-bottom' => divino_responsive_spacing( $margin, 'bottom', 'mobile' ),
			'margin-left'   => divino_responsive_spacing( $margin, 'left', 'mobile' ),
			'margin-right'  => divino_responsive_spacing( $margin, 'right', 'mobile' ),
		),
	);

	$css_output_mobile[ $selector . ' .main-header-menu, ' . $selector . ' .main-header-menu .menu-link, ' . $selector . ' .main-header-menu .sub-menu' ] = divino_get_responsive_background_obj( $menu_resp_bg_color, 'mobile' );
	$css_output_mobile[ $selector . ' .main-header-menu .sub-menu .menu-link' ] = array(
		'background-color' => isset( $submenu_resp_bg_color['mobile'] )
			? $submenu_resp_bg_color['mobile']
			: ( isset( $submenu_resp_bg_color['desktop'] ) ? $submenu_resp_bg_color['desktop'] : '' ),
	);

	if ( true === $sub_menu_divider_toggle ) {

		$css_output_desktop_submenu = array(
			'.ast-hfb-header ' . $selector . ' .main-header-menu, .ast-hfb-header ' . $selector . ' .main-header-menu, .ast-hfb-header .ast-mobile-header-content ' . $selector . ' .main-header-menu, .ast-hfb-header .ast-mobile-popup-content ' . $selector . ' .main-header-menu' => array(
				'border-top-width' => $sub_menu_divider_size . 'px',
				'border-color'     => $sub_menu_divider_color,
			),
			'.ast-hfb-header ' . $selector . ' .menu-item .sub-menu .menu-link, .ast-hfb-header ' . $selector . ' .menu-item .menu-link, .ast-hfb-header ' . $selector . ' .menu-item .sub-menu .menu-link, .ast-hfb-header ' . $selector . ' .menu-item .menu-link, .ast-hfb-header .ast-mobile-header-content ' . $selector . ' .menu-item .sub-menu .menu-link, .ast-hfb-header .ast-mobile-header-content ' . $selector . ' .menu-item .menu-link, .ast-hfb-header .ast-mobile-popup-content ' . $selector . ' .menu-item .sub-menu .menu-link, .ast-hfb-header .ast-mobile-popup-content ' . $selector . ' .menu-item .menu-link' => array(
				'border-bottom-width' => $sub_menu_divider_size . 'px',
				'border-color'        => $sub_menu_divider_color,
				'border-style'        => 'solid',
			),
		);

	} else {

		$css_output_desktop_submenu = array(

			'.ast-hfb-header .ast-builder-menu-mobile .main-header-menu, .ast-hfb-header .ast-builder-menu-mobile .main-navigation .menu-item .menu-link, .ast-hfb-header .ast-builder-menu-mobile .main-navigation .menu-item .sub-menu .menu-link' => array(
				'border-style' => 'none',
			),
		);
	}

	$css_output_desktop_submenu[ $selector . ' .menu-item.menu-item-has-children > .ast-menu-toggle' ] = array(
		'top'   => $menu_spacing_desktop_top,
		'right' => divino_calculate_spacing( divino_responsive_spacing( $menu_spacing, 'right', 'desktop' ), '-', '0.907', 'em' ),
	);

	$css_output  = divino_parse_css( $css_output_desktop );
	$css_output .= divino_parse_css( $css_output_desktop_submenu );
	$css_output .= divino_parse_css( $css_output_tablet, '', divino_get_tablet_breakpoint() );
	$css_output .= divino_parse_css( $css_output_mobile, '', divino_get_mobile_breakpoint() );

	$dynamic_css .= $css_output;

	$dynamic_css .= divino_Builder_Base_Dynamic_CSS::prepare_visibility_css( $_section, $selector, 'block' );

	return $dynamic_css;
}
