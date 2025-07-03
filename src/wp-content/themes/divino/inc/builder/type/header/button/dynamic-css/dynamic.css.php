<?php
/**
 * Butons - Dynamic CSS
 *
 * @package Astra
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Heading Colors
 */
add_filter( 'divino_dynamic_theme_css', 'divino_hb_button_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Heading Colors.
 *
 * @since 3.0.0
 */
function divino_hb_button_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	$dynamic_css .= divino_Button_Component_Dynamic_CSS::divino_button_dynamic_css( 'header' );

	return $dynamic_css;
}
