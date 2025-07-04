<?php
/**
 * divino Icons - Dynamic CSS.
 *
 * @package divino
 * @since 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'divino_dynamic_theme_css', 'divino_icons_static_css' );

/**
 * divino Icons - Dynamic CSS.
 *
 * @param string $dynamic_css Dynamic CSS.
 * @since 3.5.0
 */
function divino_icons_static_css( $dynamic_css ) {

	if ( false === divino_Icons::is_svg_icons() ) {
		$divino_icons         = '
        .divino-icon-down_arrow::after {
            content: "\e900";
            font-family: divino;
        }
        .divino-icon-close::after {
            content: "\e5cd";
            font-family: divino;
        }
        .divino-icon-drag_handle::after {
            content: "\e25d";
            font-family: divino;
        }
        .divino-icon-format_align_justify::after {
            content: "\e235";
            font-family: divino;
        }
        .divino-icon-menu::after {
            content: "\e5d2";
            font-family: divino;
        }
        .divino-icon-reorder::after {
            content: "\e8fe";
            font-family: divino;
        }
        .divino-icon-search::after {
            content: "\e8b6";
            font-family: divino;
        }
        .divino-icon-zoom_in::after {
            content: "\e56b";
            font-family: divino;
        }
        .divino-icon-check-circle::after {
            content: "\e901";
            font-family: divino;
        }
        .divino-icon-shopping-cart::after {
            content: "\f07a";
            font-family: divino;
        }
        .divino-icon-shopping-bag::after {
            content: "\f290";
            font-family: divino;
        }
        .divino-icon-shopping-basket::after {
            content: "\f291";
            font-family: divino;
        }
        .divino-icon-circle-o::after {
            content: "\e903";
            font-family: divino;
        }
        .divino-icon-certificate::after {
            content: "\e902";
            font-family: divino;
        }';
		return $dynamic_css .= divino_Enqueue_Scripts::trim_css( $divino_icons );
	}
	return $dynamic_css;
}
