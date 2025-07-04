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
 * divino Schema Markup.
 *
 * @since 2.1.3
 */
class divino_Schema {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->include_schemas();

		add_action( 'wp', array( $this, 'setup_schema' ) );
	}

	/**
	 * Setup schema
	 *
	 * @since 2.1.3
	 */
	public function setup_schema() {
	}

	/**
	 * Include schema files.
	 *
	 * @since 2.1.3
	 */
	private function include_schemas() {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once divino_THEME_DIR . 'inc/schema/class-divino-creativework-schema.php';
		require_once divino_THEME_DIR . 'inc/schema/class-divino-wpheader-schema.php';
		require_once divino_THEME_DIR . 'inc/schema/class-divino-wpfooter-schema.php';
		require_once divino_THEME_DIR . 'inc/schema/class-divino-wpsidebar-schema.php';
		require_once divino_THEME_DIR . 'inc/schema/class-divino-person-schema.php';
		require_once divino_THEME_DIR . 'inc/schema/class-divino-organization-schema.php';
		require_once divino_THEME_DIR . 'inc/schema/class-divino-site-navigation-schema.php';
		require_once divino_THEME_DIR . 'inc/schema/class-divino-breadcrumb-schema.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Enabled schema
	 *
	 * @since 2.1.3
	 */
	protected function schema_enabled() {
		return apply_filters( 'divino_schema_enabled', true );
	}

}

new divino_Schema();
