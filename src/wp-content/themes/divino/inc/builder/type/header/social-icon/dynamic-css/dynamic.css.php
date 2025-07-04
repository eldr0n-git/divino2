<?php
/**
 * Heading Colors - Dynamic CSS
 *
 * @package divino
 * @since 2.1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Heading Colors
 */
add_filter( 'divino_dynamic_theme_css', 'divino_hb_social_icon_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          divino Dynamic CSS.
 * @param  string $dynamic_css_filtered divino Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Heading Colors.
 *
 * @since 2.1.4
 */
function divino_hb_social_icon_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	$dynamic_css .= divino_Social_Component_Dynamic_CSS::divino_social_dynamic_css( 'header' );

	return $dynamic_css;
}
