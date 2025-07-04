<?php
/**
 * Helper class for font settings.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Font info class for System and Google fonts.
 */
if ( ! class_exists( 'divino_Fonts_Data' ) ) {

	/**
	 * Fonts Data
	 */
	final class divino_Fonts_Data {
		/**
		 * Localize Fonts
		 */
		public static function js() {

			$system = wp_json_encode( divino_Font_Families::get_system_fonts() );
			$google = wp_json_encode( divino_Font_Families::get_google_fonts() );
			$custom = wp_json_encode( divino_Font_Families::get_custom_fonts() );
			if ( ! empty( $custom ) ) {
				return 'var AstFontFamilies = { system: ' . $system . ', custom: ' . $custom . ', google: ' . $google . ' };';
			}

			return 'var AstFontFamilies = { system: ' . $system . ', google: ' . $google . ' };';
		}
	}

}
