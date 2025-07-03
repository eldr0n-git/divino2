<?php
/**
 * Copyright control - Dynamic CSS
 *
 * @package Astra Builder
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Copyright CSS
 */
add_filter( 'divino_dynamic_theme_css', 'divino_fb_copyright_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Heading Colors.
 *
 * @since 3.0.0
 */
function divino_fb_copyright_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( ! divino_Builder_Helper::is_component_loaded( 'copyright', 'footer' ) ) {
		return $dynamic_css;
	}

	$_section = 'section-footer-copyright';

	$selector = '.ast-footer-copyright ';

	$visibility_selector = '.ast-footer-copyright.ast-builder-layout-element';

	$alignment = divino_get_option( 'footer-copyright-alignment' );

	$desktop_alignment = isset( $alignment['desktop'] ) ? $alignment['desktop'] : '';
	$tablet_alignment  = isset( $alignment['tablet'] ) ? $alignment['tablet'] : '';
	$mobile_alignment  = isset( $alignment['mobile'] ) ? $alignment['mobile'] : '';

	$margin = divino_get_option( $_section . '-margin' );

	/**
	 * Copyright CSS.
	 */
	$css_output_desktop = array(
		'.ast-footer-copyright' => array(
			'text-align' => $desktop_alignment,
		),
		$selector               => array(
			'color'         => divino_get_option( 'footer-copyright-color', divino_get_option( 'text-color' ) ),
			// Margin CSS.
			'margin-top'    => divino_responsive_spacing( $margin, 'top', 'desktop' ),
			'margin-bottom' => divino_responsive_spacing( $margin, 'bottom', 'desktop' ),
			'margin-left'   => divino_responsive_spacing( $margin, 'left', 'desktop' ),
			'margin-right'  => divino_responsive_spacing( $margin, 'right', 'desktop' ),
		),
	);

	$css_output_tablet = array(
		'.ast-footer-copyright' => array(
			'text-align' => $tablet_alignment,
		),
		$selector               => array(
			// Margin CSS.
			'margin-top'    => divino_responsive_spacing( $margin, 'top', 'tablet' ),
			'margin-bottom' => divino_responsive_spacing( $margin, 'bottom', 'tablet' ),
			'margin-left'   => divino_responsive_spacing( $margin, 'left', 'tablet' ),
			'margin-right'  => divino_responsive_spacing( $margin, 'right', 'tablet' ),
		),
	);

	$css_output_mobile = array(
		'.ast-footer-copyright' => array(
			'text-align' => $mobile_alignment,
		),
		$selector               => array(
			// Margin CSS.
			'margin-top'    => divino_responsive_spacing( $margin, 'top', 'mobile' ),
			'margin-bottom' => divino_responsive_spacing( $margin, 'bottom', 'mobile' ),
			'margin-left'   => divino_responsive_spacing( $margin, 'left', 'mobile' ),
			'margin-right'  => divino_responsive_spacing( $margin, 'right', 'mobile' ),
		),
	);

	/* Parse CSS from array() */
	$css_output  = divino_parse_css( $css_output_desktop );
	$css_output .= divino_parse_css( $css_output_tablet, '', divino_get_tablet_breakpoint() );
	$css_output .= divino_parse_css( $css_output_mobile, '', divino_get_mobile_breakpoint() );

	$dynamic_css .= $css_output;

	$dynamic_css .= divino_Builder_Base_Dynamic_CSS::prepare_advanced_typography_css( $_section, $selector );

	$dynamic_css .= divino_Builder_Base_Dynamic_CSS::prepare_visibility_css( $_section, $visibility_selector );

	return $dynamic_css;
}
