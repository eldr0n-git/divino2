<?php
/**
 * divino Attributes Class.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.6.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'divino_Attr' ) ) {

	/**
	 * Class divino_Attr
	 */
	class divino_Attr {
		/**
		 * Store Instance on Current Class.
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Initialuze the Class.
		 *
		 * @since 1.6.2
		 */
		private function __construct() {
		}

		/**
		 * Build list of attributes into a string and apply contextual filter on string.
		 *
		 * The contextual filter is of the form `divino_attr_{context}_output`.
		 *
		 * @since 1.6.2
		 *
		 * @param string $context    The context, to build filter name.
		 * @param array  $attributes Optional. Extra attributes to merge with defaults.
		 * @param array  $args       Optional. Custom data to pass to filter.
		 * @return string String of HTML attributes and values.
		 */
		public function divino_attr( $context, $attributes = array(), $args = array() ) {

			$attributes = $this->divino_parse_attr( $context, $attributes, $args );

			$output = '';

			// Cycle through attributes, build tag attribute string.
			foreach ( $attributes as $key => $value ) {

				if ( ! $value ) {
					continue;
				}

				if ( true === $value ) {
					$output .= esc_html( $key ) . ' ';
				} else {
					$output .= sprintf( '%s="%s" ', esc_html( $key ), esc_attr( $value ) );
				}
			}

			$output = apply_filters( "divino_attr_{$context}_output", $output, $attributes, $context, $args );

			return trim( $output );
		}

		/**
		 * Merge array of attributes with defaults, and apply contextual filter on array.
		 *
		 * The contextual filter is of the form `divino_attr_{context}`.
		 *
		 * @since 1.6.2
		 *
		 * @param string $context    The context, to build filter name.
		 * @param array  $attributes Optional. Extra attributes to merge with defaults.
		 * @param array  $args       Optional. Custom data to pass to filter.
		 * @return array Merged and filtered attributes.
		 */
		public function divino_parse_attr( $context, $attributes = array(), $args = array() ) {

			$defaults = array(
				'class' => sanitize_html_class( $context ),
			);

			$attributes = wp_parse_args( $attributes, $defaults );

			// Contextual filter.
			return apply_filters( "divino_attr_{$context}", $attributes, $context, $args );
		}

	}

}

/**
 * Kicking this off by calling 'get_instance()' method
 */
divino_Attr::get_instance();
