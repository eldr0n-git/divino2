<?php
/**
 * WIdget control - Dynamic CSS
 *
 * @package divino Builder
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Heading Colors
 */
add_filter( 'divino_dynamic_theme_css', 'divino_hb_widget_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          divino Dynamic CSS.
 * @param  string $dynamic_css_filtered divino Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Heading Colors.
 *
 * @since 3.0.0
 */
function divino_hb_widget_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	$dynamic_css .= divino_Widget_Component_Dynamic_CSS::divino_widget_dynamic_css( 'header' );

	return $dynamic_css;
}
