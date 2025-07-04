<?php
/**
 * divino Theme Customizer Configuration Base.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.4.3
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Sanitizes
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'divino_Customizer_Config_Base' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Customizer_Config_Base {
		/**
		 * Constructor
		 */
		public function __construct() {
			// Bail early if it is not divino customizer.
			if ( ! divino_Customizer::is_divino_customizer() ) {
				return;
			}

			add_filter( 'divino_customizer_configurations', array( $this, 'register_configuration' ), 30, 2 );
		}

		/**
		 * Base Method for Registering Customizer Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			return $configurations;
		}

		/**
		 * Section Description
		 *
		 * @since 1.4.3
		 *
		 * @param  array $args Description arguments.
		 * @return mixed       Markup of the section description.
		 */
		public function section_get_description( $args ) {

			// Return if white labeled.
			if ( divino_is_white_labelled() ) {
				return '';
			}

			// Description.
			$content  = '<div class="divino-section-description">';
			$content .= wp_kses_post( divino_get_prop( $args, 'description' ) );

			// Links.
			if ( divino_get_prop( $args, 'links' ) ) {
				$content .= '<ul>';
				foreach ( $args['links'] as $link ) {

					if ( divino_get_prop( $link, 'attrs' ) ) {

						$content .= '<li>';

						// Attribute mapping.
						$attributes = ' target="_blank" ';
						foreach ( divino_get_prop( $link, 'attrs' ) as $attr => $attr_value ) {
							$attributes .= ' ' . $attr . '="' . esc_attr( $attr_value ) . '" ';
						}
						$content .= '<a ' . $attributes . '>' . esc_html( divino_get_prop( $link, 'text' ) ) . '</a></li>';

						$content .= '</li>';
					}
				}
				$content .= '</ul>';
			}

			$content .= '</div><!-- .divino-section-description -->';

			return $content;
		}

	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new divino_Customizer_Config_Base();
