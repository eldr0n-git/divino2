<?php
/**
 * divino HTML Component Dynamic CSS.
 *
 * @package     divino-builder
 * @link        https://wpdivino.com/
 * @since       3.0.0
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Builder Dynamic CSS.
 *
 * @since 3.0.0
 */
class divino_Html_Component_Dynamic_CSS {
	/**
	 * Dynamic CSS
	 *
	 * @param string $builder_type Builder Type.
	 * @return String Generated dynamic CSS for Heading Colors.
	 *
	 * @since 3.0.0
	 */
	public static function divino_html_dynamic_css( $builder_type = 'header' ) {

		$generated_css  = '';
		$html_css_flag  = false;
		$number_of_html = 'header' === $builder_type ? divino_Builder_Helper::$num_of_header_html : divino_Builder_Helper::$num_of_footer_html;

		for ( $index = 1; $index <= $number_of_html; $index++ ) {

			if ( ! divino_Builder_Helper::is_component_loaded( 'html-' . $index, $builder_type ) ) {
				continue;
			}

			$html_css_flag = true;

			$_section = 'header' === $builder_type ? 'section-hb-html-' . $index : 'section-fb-html-' . $index;

			$margin    = divino_get_option( $_section . '-margin' );
			$font_size = divino_get_option( 'font-size-' . $_section );

			$text_color_desktop = divino_get_prop( divino_get_option( $builder_type . '-html-' . $index . 'color' ), 'desktop' );
			$text_color_tablet  = divino_get_prop( divino_get_option( $builder_type . '-html-' . $index . 'color' ), 'tablet' );
			$text_color_mobile  = divino_get_prop( divino_get_option( $builder_type . '-html-' . $index . 'color' ), 'mobile' );

			$link_color_desktop = divino_get_prop( divino_get_option( $builder_type . '-html-' . $index . 'link-color' ), 'desktop' );
			$link_color_tablet  = divino_get_prop( divino_get_option( $builder_type . '-html-' . $index . 'link-color' ), 'tablet' );
			$link_color_mobile  = divino_get_prop( divino_get_option( $builder_type . '-html-' . $index . 'link-color' ), 'mobile' );

			$link_h_color_desktop = divino_get_prop( divino_get_option( $builder_type . '-html-' . $index . 'link-h-color' ), 'desktop' );
			$link_h_color_tablet  = divino_get_prop( divino_get_option( $builder_type . '-html-' . $index . 'link-h-color' ), 'tablet' );
			$link_h_color_mobile  = divino_get_prop( divino_get_option( $builder_type . '-html-' . $index . 'link-h-color' ), 'mobile' );

			$selector = 'header' === $builder_type ? '.ast-header-html-' . $index : '.footer-widget-area[data-section="section-fb-html-' . $index . '"]';

			$display_prop = 'header' === $builder_type ? 'flex' : 'block';

			$css_output_desktop = array(

				$selector . ' .ast-builder-html-element' => array(
					'color'     => $text_color_desktop,
					// Typography.
					'font-size' => divino_responsive_font( $font_size, 'desktop' ),
				),

				$selector                                => array(
					// Margin.
					'margin-top'    => divino_responsive_spacing( $margin, 'top', 'desktop' ),
					'margin-bottom' => divino_responsive_spacing( $margin, 'bottom', 'desktop' ),
					'margin-left'   => divino_responsive_spacing( $margin, 'left', 'desktop' ),
					'margin-right'  => divino_responsive_spacing( $margin, 'right', 'desktop' ),
				),

				// Link Color.
				$selector . ' a'                         => array(
					'color' => $link_color_desktop,
				),

				// Link Hover Color.
				$selector . ' a:hover'                   => array(
					'color' => $link_h_color_desktop,
				),
			);

			/* Parse CSS from array() */
			$css_output = divino_parse_css( $css_output_desktop );

			// Tablet CSS.
			$css_output_tablet = array(

				$selector . ' .ast-builder-html-element' => array(
					'color'     => $text_color_tablet,
					// Typography.
					'font-size' => divino_responsive_font( $font_size, 'tablet' ),
				),

				$selector                                => array(
					// Margin CSS.
					'margin-top'    => divino_responsive_spacing( $margin, 'top', 'tablet' ),
					'margin-bottom' => divino_responsive_spacing( $margin, 'bottom', 'tablet' ),
					'margin-left'   => divino_responsive_spacing( $margin, 'left', 'tablet' ),
					'margin-right'  => divino_responsive_spacing( $margin, 'right', 'tablet' ),
				),

				// Link Color.
				$selector . ' a'                         => array(
					'color' => $link_color_tablet,
				),

				// Link Hover Color.
				$selector . ' a:hover'                   => array(
					'color' => $link_h_color_tablet,
				),
			);
			$css_output .= divino_parse_css( $css_output_tablet, '', divino_get_tablet_breakpoint() );

			// Mobile CSS.
			$css_output_mobile = array(

				$selector . ' .ast-builder-html-element' => array(
					'color'     => $text_color_mobile,
					// Typography.
					'font-size' => divino_responsive_font( $font_size, 'mobile' ),
				),

				$selector                                => array(
					// Margin CSS.
					'margin-top'    => divino_responsive_spacing( $margin, 'top', 'mobile' ),
					'margin-bottom' => divino_responsive_spacing( $margin, 'bottom', 'mobile' ),
					'margin-left'   => divino_responsive_spacing( $margin, 'left', 'mobile' ),
					'margin-right'  => divino_responsive_spacing( $margin, 'right', 'mobile' ),
				),

				// Link Color.
				$selector . ' a'                         => array(
					'color' => $link_color_mobile,
				),

				// Link Hover Color.
				$selector . ' a:hover'                   => array(
					'color' => $link_h_color_mobile,
				),
			);
			$css_output .= divino_parse_css( $css_output_mobile, '', divino_get_mobile_breakpoint() );

			$generated_css .= $css_output;

			$generated_css .= divino_Builder_Base_Dynamic_CSS::prepare_advanced_typography_css( $_section, $selector );

			$generated_css .= divino_Builder_Base_Dynamic_CSS::prepare_visibility_css( $_section, $selector, $display_prop );
		}
		if ( true === $html_css_flag ) {
			$html_static_css = array(
				'.ast-builder-html-element img.alignnone' => array(
					'display' => 'inline-block',
				),
				'.ast-builder-html-element p:first-child' => array(
					'margin-top' => '0',
				),
				'.ast-builder-html-element p:last-child'  => array(
					'margin-bottom' => '0',
				),
				'.ast-header-break-point .main-header-bar .ast-builder-html-element' => array(
					'line-height' => '1.85714285714286',
				),
			);
			return divino_parse_css( $html_static_css ) . $generated_css;
		}

		return $generated_css;
	}
}

/**
 * Kicking this off by creating object of this class.
 */

new divino_Html_Component_Dynamic_CSS();
