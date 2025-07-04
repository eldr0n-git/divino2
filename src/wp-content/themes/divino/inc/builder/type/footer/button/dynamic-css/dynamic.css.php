<?php
/**
 * Butons - Dynamic CSS
 *
 * @package divino
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Heading Colors
 */
add_filter( 'divino_dynamic_theme_css', 'divino_fb_button_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          divino Dynamic CSS.
 * @param  string $dynamic_css_filtered divino Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Heading Colors.
 *
 * @since 3.0.0
 */
function divino_fb_button_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	$dynamic_css   .= divino_Button_Component_Dynamic_CSS::divino_button_dynamic_css( 'footer' );
	$fb_button_flag = false;
	for ( $index = 1; $index <= divino_Builder_Helper::$num_of_footer_button; $index++ ) {

		if ( ! divino_Builder_Helper::is_component_loaded( 'button-' . $index, 'footer' ) ) {
			continue;
		}
		$fb_button_flag = true;

		$selector = '.ast-footer-button-' . $index . '[data-section="section-fb-button-' . $index . '"]';

		$alignment = divino_get_option( 'footer-button-' . $index . '-alignment' );

		$desktop_alignment = isset( $alignment['desktop'] ) ? $alignment['desktop'] : '';
		$tablet_alignment  = isset( $alignment['tablet'] ) ? $alignment['tablet'] : '';
		$mobile_alignment  = isset( $alignment['mobile'] ) ? $alignment['mobile'] : '';

		/**
		 * Copyright CSS.
		 */
		$css_output_desktop = array(
			$selector => array(
				'justify-content' => $desktop_alignment,
			),
		);

		$css_output_tablet = array(
			$selector => array(
				'justify-content' => $tablet_alignment,
			),
		);

		$css_output_mobile = array(
			$selector => array(
				'justify-content' => $mobile_alignment,
			),
		);

		/* Parse CSS from array() */
		$css_output  = divino_parse_css( $css_output_desktop );
		$css_output .= divino_parse_css( $css_output_tablet, '', divino_get_tablet_breakpoint() );
		$css_output .= divino_parse_css( $css_output_mobile, '', divino_get_mobile_breakpoint() );

		$dynamic_css .= $css_output;
	}
	if ( true === $fb_button_flag ) {
		$static_css = array(
			'[data-section*="section-fb-button-"] .menu-link' => array(
				'display' => 'none',
			),
			'[CLASS*="ast-footer-button-"][data-section^="section-fb-button-"]' => array(
				'justify-content' => 'center',
			),
			'.site-footer-focus-item[CLASS*="ast-footer-button-"]' => array(
				'display' => 'flex',
			),
		);
		return divino_parse_css( $static_css ) . $dynamic_css;
	}

	return $dynamic_css;
}
