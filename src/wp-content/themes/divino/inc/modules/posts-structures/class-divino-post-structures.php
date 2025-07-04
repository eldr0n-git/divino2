<?php
/**
 * Post Strctures Extension
 *
 * @package divino
 * @since 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'divino_THEME_POST_STRUCTURE_DIR', divino_THEME_DIR . 'inc/modules/posts-structures/' );
define( 'divino_THEME_POST_STRUCTURE_URI', divino_THEME_URI . 'inc/modules/posts-structures/' );

/**
 * Post Strctures Initial Setup
 *
 * @since 4.0.0
 */
class divino_Post_Structures {
	/**
	 * Constructor function that loads require files.
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once divino_THEME_POST_STRUCTURE_DIR . 'class-divino-posts-structure-loader.php';
		require_once divino_THEME_POST_STRUCTURE_DIR . 'class-divino-posts-structure-markup.php';

		// Include front end files.
		if ( ! is_admin() ) {
			require_once divino_THEME_POST_STRUCTURE_DIR . 'css/single-dynamic.css.php';
			require_once divino_THEME_POST_STRUCTURE_DIR . 'css/archive-dynamic.css.php';
			require_once divino_THEME_POST_STRUCTURE_DIR . 'css/special-dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating new object.
 */
new divino_Post_Structures();
