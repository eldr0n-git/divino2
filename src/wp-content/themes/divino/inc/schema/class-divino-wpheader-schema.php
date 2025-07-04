<?php
/**
 * Schema markup.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 2.1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * divino CreativeWork Schema Markup.
 *
 * @since 2.1.3
 */
class divino_WPHeader_Schema extends divino_Schema {
	/**
	 * Setup schema
	 *
	 * @since 2.1.3
	 */
	public function setup_schema() {

		if ( true !== $this->schema_enabled() ) {
			return false;
		}

		add_filter( 'divino_attr_header', array( $this, 'wpheader_Schema' ) );
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function wpheader_Schema( $attr ) {
		$attr['itemtype']  = 'https://schema.org/WPHeader';
		$attr['itemscope'] = 'itemscope';
		$attr['itemid']    = '#masthead';

		return $attr;
	}

	/**
	 * Enabled schema
	 *
	 * @since 2.1.3
	 */
	protected function schema_enabled() {
		return apply_filters( 'divino_wpheader_schema_enabled', parent::schema_enabled() );
	}

}

new divino_WPHeader_Schema();
