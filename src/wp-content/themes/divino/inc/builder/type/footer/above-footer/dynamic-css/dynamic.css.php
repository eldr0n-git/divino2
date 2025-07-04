<?php
/**
 * Above Footer control - Dynamic CSS
 *
 * @package divino Builder
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Above Footer CSS
 */
add_filter( 'divino_dynamic_theme_css', 'divino_fb_above_footer_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          divino Dynamic CSS.
 * @param  string $dynamic_css_filtered divino Dynamic CSS Filters.
 * @return String Generated dynamic CSS for above Footer.
 *
 * @since 3.0.0
 */
function divino_fb_above_footer_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( ! ( divino_Builder_Helper::is_footer_row_empty( 'above' ) || is_customize_preview() ) ) {
		return $dynamic_css;
	}

	$_section = 'section-above-footer-builder';

	$selector = '.site-above-footer-wrap[data-section="section-above-footer-builder"]';

	$footer_bg               = divino_get_option( 'hba-footer-bg-obj-responsive' );
	$footer_top_border_size  = divino_get_option( 'hba-footer-separator' );
	$footer_top_border_color = divino_get_option( 'hba-footer-top-border-color' );
	$footer_height           = divino_get_option( 'hba-footer-height' );
	$footer_width            = divino_get_option( 'hba-footer-layout-width' );
	$content_width           = divino_get_option( 'site-content-width' );
	$inner_spacing           = divino_get_option( 'hba-inner-spacing' );

	$layout = divino_get_option( 'hba-footer-layout' );

	$desk_layout = isset( $layout['desktop'] ) ? $layout['desktop'] : 'full';
	$tab_layout  = isset( $layout['tablet'] ) ? $layout['tablet'] : 'full';
	$mob_layout  = isset( $layout['mobile'] ) ? $layout['mobile'] : 'full';

	$inner_spacing_desktop = isset( $inner_spacing['desktop'] ) ? $inner_spacing['desktop'] : '';
	$inner_spacing_tablet  = isset( $inner_spacing['tablet'] ) ? $inner_spacing['tablet'] : '';
	$inner_spacing_mobile  = isset( $inner_spacing['mobile'] ) ? $inner_spacing['mobile'] : '';

	$css_output_desktop = array(
		'.site-above-footer-wrap'            => array(
			'padding-top'    => '20px',
			'padding-bottom' => '20px',
		),
		$selector                            => divino_get_responsive_background_obj( $footer_bg, 'desktop' ),
		$selector . ' .ast-builder-grid-row' => array(
			'grid-column-gap' => divino_get_css_value( $inner_spacing_desktop, 'px' ),
		),
		$selector . ' .ast-builder-grid-row, ' . $selector . ' .site-footer-section' => array(
			'align-items' => divino_get_option( 'hba-footer-vertical-alignment' ),
		),
		$selector . '.ast-footer-row-inline .site-footer-section' => array(
			'display'       => 'flex',
			'margin-bottom' => '0',
		),
		'.ast-builder-grid-row-' . $desk_layout . ' .ast-builder-grid-row' => array(
			'grid-template-columns' => divino_Builder_Helper::$grid_size_mapping[ $desk_layout ],
		),

	);

	if ( isset( $footer_width ) && 'content' === $footer_width ) {

		$css_output_desktop[ $selector . ' .ast-builder-grid-row' ]['max-width']    = divino_get_css_value( $content_width, 'px' );
		$css_output_desktop[ $selector . ' .ast-builder-grid-row' ]['min-height']   = divino_get_css_value( $footer_height, 'px' );
		$css_output_desktop[ $selector . ' .ast-builder-grid-row' ]['margin-left']  = 'auto';
		$css_output_desktop[ $selector . ' .ast-builder-grid-row' ]['margin-right'] = 'auto';
	} else {
		$css_output_desktop[ $selector . ' .ast-builder-grid-row' ]['max-width']     = '100%';
		$css_output_desktop[ $selector . ' .ast-builder-grid-row' ]['padding-left']  = '35px';
		$css_output_desktop[ $selector . ' .ast-builder-grid-row' ]['padding-right'] = '35px';
	}

	$css_output_desktop[ $selector ]['min-height'] = divino_get_css_value( $footer_height, 'px' );

	if ( isset( $footer_top_border_size ) && 1 <= $footer_top_border_size ) {

		$css_output_desktop[ $selector ]['border-style'] = 'solid';

		$css_output_desktop[ $selector ]['border-width'] = '0px';

		$css_output_desktop[ $selector ]['border-top-width'] = divino_get_css_value( $footer_top_border_size, 'px' );

		$css_output_desktop[ $selector ]['border-top-color'] = $footer_top_border_color;
	}

	$css_output_tablet = array(

		$selector                            => divino_get_responsive_background_obj( $footer_bg, 'tablet' ),
		$selector . ' .ast-builder-grid-row' => array(
			'grid-column-gap' => divino_get_css_value( $inner_spacing_tablet, 'px' ),
			'grid-row-gap'    => divino_get_css_value( $inner_spacing_tablet, 'px' ),
		),
		$selector . '.ast-footer-row-tablet-inline .site-footer-section' => array(
			'display'       => 'flex',
			'margin-bottom' => '0',
		),
		$selector . '.ast-footer-row-tablet-stack .site-footer-section' => array(
			'display'       => 'block',
			'margin-bottom' => '10px',
		),
		'.ast-builder-grid-row-container.ast-builder-grid-row-tablet-' . $tab_layout . ' .ast-builder-grid-row' => array(
			'grid-template-columns' => divino_Builder_Helper::$grid_size_mapping[ $tab_layout ],
		),
	);
	$css_output_mobile = array(

		$selector                            => divino_get_responsive_background_obj( $footer_bg, 'mobile' ),
		$selector . ' .ast-builder-grid-row' => array(
			'grid-column-gap' => divino_get_css_value( $inner_spacing_mobile, 'px' ),
			'grid-row-gap'    => divino_get_css_value( $inner_spacing_mobile, 'px' ),
		),
		$selector . '.ast-footer-row-mobile-inline .site-footer-section' => array(
			'display'       => 'flex',
			'margin-bottom' => '0',
		),
		$selector . '.ast-footer-row-mobile-stack .site-footer-section' => array(
			'display'       => 'block',
			'margin-bottom' => '10px',
		),
		'.ast-builder-grid-row-container.ast-builder-grid-row-mobile-' . $mob_layout . ' .ast-builder-grid-row' => array(
			'grid-template-columns' => divino_Builder_Helper::$grid_size_mapping[ $mob_layout ],
		),
	);

	/* Parse CSS from array() */
	$css_output  = divino_parse_css( $css_output_desktop );
	$css_output .= divino_parse_css( $css_output_tablet, '', divino_get_tablet_breakpoint() );
	$css_output .= divino_parse_css( $css_output_mobile, '', divino_get_mobile_breakpoint() );

	$dynamic_css .= $css_output;

	$dynamic_css .= divino_Extended_Base_Dynamic_CSS::prepare_advanced_margin_padding_css( $_section, $selector );
	$dynamic_css .= divino_Builder_Base_Dynamic_CSS::prepare_visibility_css( $_section, $selector, 'grid' );
	return $dynamic_css;
}
