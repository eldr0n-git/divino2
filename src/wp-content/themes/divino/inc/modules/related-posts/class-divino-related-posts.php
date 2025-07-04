<?php
/**
 * Related Posts for divino theme.
 *
 * @package     divino
 * @link        https://www.brainstormforce.com
 * @since       divino 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'divino_RELATED_POSTS_DIR', divino_THEME_DIR . 'inc/modules/related-posts/' );

/**
 * Related Posts Initial Setup
 *
 * @since 3.5.0
 */
class divino_Related_Posts {
	/**
	 * Constructor function that initializes required actions and hooks
	 *
	 * @since 3.5.0
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once divino_RELATED_POSTS_DIR . 'class-divino-related-posts-loader.php';
		require_once divino_RELATED_POSTS_DIR . 'class-divino-related-posts-markup.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

		// Include front end files.
		if ( ! is_admin() ) {
			require_once divino_RELATED_POSTS_DIR . 'css/static-css.php'; // phpcs:ignore: WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once divino_RELATED_POSTS_DIR . 'css/dynamic-css.php'; // phpcs:ignore: WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
	}
}

/**
 *  Kicking this off by creating NEW instance.
 */
new divino_Related_Posts();
