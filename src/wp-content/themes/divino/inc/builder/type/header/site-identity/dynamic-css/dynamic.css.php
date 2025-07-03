<?php
/**
 * Site Identity - Dynamic CSS
 *
 * @package Astra
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Site Identity
 */
add_filter( 'divino_dynamic_theme_css', 'divino_hb_site_identity_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Site Identity.
 *
 * @since 3.0.0
 */
function divino_hb_site_identity_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( ! divino_Builder_Helper::is_component_loaded( 'logo', 'header' ) ) {
		return $dynamic_css;
	}

	$_section            = 'title_tagline';
	$selector            = '.ast-builder-layout-element .ast-site-identity';
	$visibility_selector = '.ast-builder-layout-element[data-section="title_tagline"]';
	$margin              = divino_get_option( $_section . '-margin' );

	// Desktop CSS.
	$css_output_desktop = array(

		$selector => array(

			// Margin CSS.
			'margin-top'    => divino_responsive_spacing( $margin, 'top', 'desktop' ),
			'margin-bottom' => divino_responsive_spacing( $margin, 'bottom', 'desktop' ),
			'margin-left'   => divino_responsive_spacing( $margin, 'left', 'desktop' ),
			'margin-right'  => divino_responsive_spacing( $margin, 'right', 'desktop' ),
		),
	);

	// Tablet CSS.
	$css_output_tablet = array(

		$selector => array(

			// Margin CSS.
			'margin-top'    => divino_responsive_spacing( $margin, 'top', 'tablet' ),
			'margin-bottom' => divino_responsive_spacing( $margin, 'bottom', 'tablet' ),
			'margin-left'   => divino_responsive_spacing( $margin, 'left', 'tablet' ),
			'margin-right'  => divino_responsive_spacing( $margin, 'right', 'tablet' ),
		),
	);

	// Mobile CSS.
	$css_output_mobile = array(

		$selector => array(

			// Margin CSS.
			'margin-top'    => divino_responsive_spacing( $margin, 'top', 'mobile' ),
			'margin-bottom' => divino_responsive_spacing( $margin, 'bottom', 'mobile' ),
			'margin-left'   => divino_responsive_spacing( $margin, 'left', 'mobile' ),
			'margin-right'  => divino_responsive_spacing( $margin, 'right', 'mobile' ),
		),
	);

	$css_output  = divino_parse_css( $css_output_desktop );
	$css_output .= divino_parse_css( $css_output_tablet, '', divino_get_tablet_breakpoint() );
	$css_output .= divino_parse_css( $css_output_mobile, '', divino_get_mobile_breakpoint() );

	$dynamic_css .= $css_output;
	$dynamic_css .= divino_Builder_Base_Dynamic_CSS::prepare_visibility_css( $_section, $visibility_selector );

	return $dynamic_css;
}
