<?php
/**
 * Mobile Trigger - Dynamic CSS
 *
 * @package astra-builder
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Mobile Trigger.
 */
add_filter( 'divino_dynamic_theme_css', 'divino_mobile_trigger_row_setting', 11 );

/**
 * Mobile Trigger - Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Heading Colors.
 *
 * @since 3.0.0
 */
function divino_mobile_trigger_row_setting( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( ! divino_Builder_Helper::is_component_loaded( 'mobile-trigger', 'header', 'mobile' ) && ! divino_Builder_Helper::is_component_loaded( 'mobile-trigger', 'header', 'desktop' ) ) {
		return $dynamic_css;
	}

	$_section = 'section-header-mobile-trigger';

	$selector = '[data-section="section-header-mobile-trigger"]';

	$theme_color                  = divino_get_option( 'theme-color' );
	$icon_size                    = divino_get_option( 'mobile-header-toggle-icon-size' );
	$trigger_bg                   = divino_get_option( 'mobile-header-toggle-btn-bg-color', $theme_color );
	$trigger_border_width         = divino_get_option( 'mobile-header-toggle-btn-border-size' );
	$trigger_border_color         = divino_get_option( 'mobile-header-toggle-border-color', $trigger_bg );
	$trigger_border_radius_fields = divino_get_option( 'mobile-header-toggle-border-radius-fields' );
	$font_size                    = divino_get_option( 'mobile-header-label-font-size' );
	$style                        = divino_get_option( 'mobile-header-toggle-btn-style' );
	$default                      = '#ffffff';

	if ( 'fill' !== $style ) {
		$default = $theme_color;
	}

	$icon_color = divino_get_option( 'mobile-header-toggle-btn-color', $default );

	// Border.
	$trigger_border_width_top = isset( $trigger_border_width ) && isset( $trigger_border_width['top'] ) ? $trigger_border_width['top'] : 0;

	$trigger_border_width_bottom = isset( $trigger_border_width ) && isset( $trigger_border_width['bottom'] ) ? $trigger_border_width['bottom'] : 0;

	$trigger_border_width_right = isset( $trigger_border_width ) && isset( $trigger_border_width['right'] ) ? $trigger_border_width['right'] : 0;

	$trigger_border_width_left = isset( $trigger_border_width ) && isset( $trigger_border_width['left'] ) ? $trigger_border_width['left'] : 0;

	$margin          = divino_get_option( $_section . '-margin' );
	$margin_selector = $selector . ' .ast-button-wrap .menu-toggle';

	/**
	 * Off-Canvas CSS.
	 */
	$css_output = array(

		$selector . ' .ast-button-wrap .mobile-menu-toggle-icon .ast-mobile-svg' => array(
			'width'  => divino_get_css_value( $icon_size, 'px' ),
			'height' => divino_get_css_value( $icon_size, 'px' ),
			'fill'   => $icon_color,
		),
		$selector . ' .ast-button-wrap .mobile-menu-wrap .mobile-menu' => array(
			// Color.
			'color'     => $icon_color,

			// Typography.
			'font-size' => divino_get_css_value( $font_size, 'px' ),
		),
		$margin_selector => array(
			// Margin CSS.
			'margin-top'    => divino_responsive_spacing( $margin, 'top', 'desktop' ),
			'margin-bottom' => divino_responsive_spacing( $margin, 'bottom', 'desktop' ),
			'margin-left'   => divino_responsive_spacing( $margin, 'left', 'desktop' ),
			'margin-right'  => divino_responsive_spacing( $margin, 'right', 'desktop' ),
		),
	);

	// Execute all cases in customizer preview.
	$is_customizer = false;
	if ( is_customize_preview() ) {
		$is_customizer = true;
	}
	switch ( $style ) {
		case 'minimal': // phpcs:ignore PSR2.ControlStructures.SwitchDeclaration.TerminatingComment
			$css_output_minimal = array(
				$selector . ' .ast-button-wrap .ast-mobile-menu-trigger-minimal' => array(
					// Color & Border.
					'color'      => esc_attr( $icon_color ),
					'border'     => 'none',
					'background' => 'transparent',
				),
			);
			$dynamic_css       .= divino_parse_css( $css_output_minimal );
			if ( false === $is_customizer ) {
				break;
			}

			// no break
		case 'fill': // phpcs:ignore PSR2.ControlStructures.SwitchDeclaration.TerminatingComment
			$css_output_fill        = array(
				$selector . ' .ast-button-wrap .ast-mobile-menu-trigger-fill' => array(
					// Color & Border.
					'color'                      => esc_attr( $icon_color ),
					'border'                     => 'none',
					'background'                 => esc_attr( $trigger_bg ),
					'border-top-left-radius'     => divino_responsive_spacing( $trigger_border_radius_fields, 'top', 'desktop' ),
					'border-top-right-radius'    => divino_responsive_spacing( $trigger_border_radius_fields, 'right', 'desktop' ),
					'border-bottom-right-radius' => divino_responsive_spacing( $trigger_border_radius_fields, 'bottom', 'desktop' ),
					'border-bottom-left-radius'  => divino_responsive_spacing( $trigger_border_radius_fields, 'left', 'desktop' ),
				),
			);
			$css_output_fill_tablet = array(
				$selector . ' .ast-button-wrap .ast-mobile-menu-trigger-fill' => array(
					'border-top-left-radius'     => divino_responsive_spacing( $trigger_border_radius_fields, 'top', 'tablet' ),
					'border-top-right-radius'    => divino_responsive_spacing( $trigger_border_radius_fields, 'right', 'tablet' ),
					'border-bottom-right-radius' => divino_responsive_spacing( $trigger_border_radius_fields, 'bottom', 'tablet' ),
					'border-bottom-left-radius'  => divino_responsive_spacing( $trigger_border_radius_fields, 'left', 'tablet' ),
				),
			);
			$css_output_fill_mobile = array(
				$selector . ' .ast-button-wrap .ast-mobile-menu-trigger-fill' => array(
					'border-top-left-radius'     => divino_responsive_spacing( $trigger_border_radius_fields, 'top', 'mobile' ),
					'border-top-right-radius'    => divino_responsive_spacing( $trigger_border_radius_fields, 'right', 'mobile' ),
					'border-bottom-right-radius' => divino_responsive_spacing( $trigger_border_radius_fields, 'bottom', 'mobile' ),
					'border-bottom-left-radius'  => divino_responsive_spacing( $trigger_border_radius_fields, 'left', 'mobile' ),
				),
			);
			$dynamic_css           .= divino_parse_css( $css_output_fill );
			$dynamic_css           .= divino_parse_css( $css_output_fill_tablet, '', divino_get_tablet_breakpoint() );
			$dynamic_css           .= divino_parse_css( $css_output_fill_mobile, '', divino_get_mobile_breakpoint() );
			if ( false === $is_customizer ) {
				break;
			}

			// no break
		case 'outline': // phpcs:ignore PSR2.ControlStructures.SwitchDeclaration.TerminatingComment
			$css_output_outline        = array(
				$selector . ' .ast-button-wrap .ast-mobile-menu-trigger-outline' => array(
					'background'                 => 'transparent',
					'color'                      => esc_attr( $icon_color ),
					'border-top-width'           => divino_get_css_value( $trigger_border_width_top, 'px' ),
					'border-bottom-width'        => divino_get_css_value( $trigger_border_width_bottom, 'px' ),
					'border-right-width'         => divino_get_css_value( $trigger_border_width_right, 'px' ),
					'border-left-width'          => divino_get_css_value( $trigger_border_width_left, 'px' ),
					'border-style'               => 'solid',
					'border-color'               => $trigger_border_color,
					'border-top-left-radius'     => divino_responsive_spacing( $trigger_border_radius_fields, 'top', 'desktop' ),
					'border-top-right-radius'    => divino_responsive_spacing( $trigger_border_radius_fields, 'right', 'desktop' ),
					'border-bottom-right-radius' => divino_responsive_spacing( $trigger_border_radius_fields, 'bottom', 'desktop' ),
					'border-bottom-left-radius'  => divino_responsive_spacing( $trigger_border_radius_fields, 'left', 'desktop' ),
				),
			);
			$css_output_outline_tablet = array(
				$selector . ' .ast-button-wrap .ast-mobile-menu-trigger-outline' => array(
					'border-top-left-radius'     => divino_responsive_spacing( $trigger_border_radius_fields, 'top', 'tablet' ),
					'border-top-right-radius'    => divino_responsive_spacing( $trigger_border_radius_fields, 'right', 'tablet' ),
					'border-bottom-right-radius' => divino_responsive_spacing( $trigger_border_radius_fields, 'bottom', 'tablet' ),
					'border-bottom-left-radius'  => divino_responsive_spacing( $trigger_border_radius_fields, 'left', 'tablet' ),
				),
			);
			$css_output_outline_mobile = array(
				$selector . ' .ast-button-wrap .ast-mobile-menu-trigger-outline' => array(
					'border-top-left-radius'     => divino_responsive_spacing( $trigger_border_radius_fields, 'top', 'mobile' ),
					'border-top-right-radius'    => divino_responsive_spacing( $trigger_border_radius_fields, 'right', 'mobile' ),
					'border-bottom-right-radius' => divino_responsive_spacing( $trigger_border_radius_fields, 'bottom', 'mobile' ),
					'border-bottom-left-radius'  => divino_responsive_spacing( $trigger_border_radius_fields, 'left', 'mobile' ),
				),
			);
			$dynamic_css              .= divino_parse_css( $css_output_outline );
			$dynamic_css              .= divino_parse_css( $css_output_outline_tablet, '', divino_get_tablet_breakpoint() );
			$dynamic_css              .= divino_parse_css( $css_output_outline_mobile, '', divino_get_mobile_breakpoint() );
			if ( false === $is_customizer ) {
				break;
			}

			// no break
		default:
			$dynamic_css .= '';
			break;

	}

	// Tablet CSS.
	$css_output_tablet = array(

		$margin_selector => array(
			// Margin CSS.
			'margin-top'    => divino_responsive_spacing( $margin, 'top', 'tablet' ),
			'margin-bottom' => divino_responsive_spacing( $margin, 'bottom', 'tablet' ),
			'margin-left'   => divino_responsive_spacing( $margin, 'left', 'tablet' ),
			'margin-right'  => divino_responsive_spacing( $margin, 'right', 'tablet' ),
		),
	);

	// Mobile CSS.
	$css_output_mobile = array(

		$margin_selector => array(
			// Margin CSS.
			'margin-top'    => divino_responsive_spacing( $margin, 'top', 'mobile' ),
			'margin-bottom' => divino_responsive_spacing( $margin, 'bottom', 'mobile' ),
			'margin-left'   => divino_responsive_spacing( $margin, 'left', 'mobile' ),
			'margin-right'  => divino_responsive_spacing( $margin, 'right', 'mobile' ),
		),
	);

	/* Parse CSS from array() */
	$css_output  = divino_parse_css( $css_output );
	$css_output .= divino_parse_css( $css_output_tablet, '', divino_get_tablet_breakpoint() );
	$css_output .= divino_parse_css( $css_output_mobile, '', divino_get_mobile_breakpoint() );

	$dynamic_css .= $css_output;

	return $dynamic_css;
}
