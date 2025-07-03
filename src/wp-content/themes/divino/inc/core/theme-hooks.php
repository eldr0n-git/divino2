<?php
/**
 * *
 * @package     Divino
 * @link        https://divino.kz/
 * @since       divino 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Themes and Plugins can check for divino_hooks using current_theme_supports( 'divino_hooks', $hook )
 * to determine whether a theme declares itself to support this specific hook type.
 *
 * Example:
 * <code>
 *      // Declare support for all hook types
 *      add_theme_support( 'divino_hooks', array( 'all' ) );
 *
 *      // Declare support for certain hook types only
 *      add_theme_support( 'divino_hooks', array( 'header', 'content', 'footer' ) );
 * </code>
 */
add_theme_support(
	'divino_hooks',
	array(

		/**
		 * As a Theme developer, use the 'all' parameter, to declare support for all
		 * hook types.
		 * Please make sure you then actually reference all the hooks in this file,
		 * Plugin developers depend on it!
		 */
		'all',

		/**
		 * Themes can also choose to only support certain hook types.
		 * Please make sure you then actually reference all the hooks in this type
		 * family.
		 *
		 * When the 'all' parameter was set, specific hook types do not need to be
		 * added explicitly.
		 */
		'html',
		'body',
		'head',
		'header',
		'content',
		'entry',
		'comments',
		'sidebars',
		'sidebar',
		'footer',

	/**
	 * If/when WordPress Core implements similar methodology, Themes and Plugins
	 * will be able to check whether the version of THA supplied by the theme
	 * supports Core hooks.
	 */
	)
);

/**
 * Determines, whether the specific hook type is actually supported.
 *
 * Plugin developers should always check for the support of a <strong>specific</strong>
 * hook type before hooking a callback function to a hook of this type.
 *
 * Example:
 * <code>
 *      if ( current_theme_supports( 'divino_hooks', 'header' ) )
 *          add_action( 'divino_head_top', 'prefix_header_top' );
 * </code>
 *
 * @param bool  $bool true.
 * @param array $args The hook type being checked.
 * @param array $registered All registered hook types.
 *
 * @return bool
 */
function divino_current_theme_supports( $bool, $args, $registered ) {
	return in_array( $args[0], $registered[0] ) || in_array( 'all', $registered[0] );
}
add_filter( 'current_theme_supports-divino_hooks', 'divino_current_theme_supports', 10, 3 );

/**
 * HTML <html> hook
 * Special case, useful for <DOCTYPE>, etc.
 * $divino_supports[] = 'html;
 */
function divino_html_before() {
	do_action( 'divino_html_before' );
}
/**
 * HTML <body> hooks
 * $divino_supports[] = 'body';
 */
function divino_body_top() {
	do_action( 'divino_body_top' );
}

/**
 * Body Bottom
 */
function divino_body_bottom() {
	do_action( 'divino_body_bottom' );
}

/**
 * HTML <head> hooks
 *
 * $divino_supports[] = 'head';
 */
function divino_head_top() {
	do_action( 'divino_head_top' );
}

/**
 * Head Bottom
 */
function divino_head_bottom() {
	do_action( 'divino_head_bottom' );
}

/**
 * Semantic <header> hooks
 *
 * $divino_supports[] = 'header';
 */
function divino_header_before() {
	do_action( 'divino_header_before' );
}

/**
 * Site Header
 */
function divino_header() {
	do_action( 'divino_header' );
}

/**
 * Masthead Top
 */
function divino_masthead_top() {
	do_action( 'divino_masthead_top' );
}

/**
 * Masthead
 */
function divino_masthead() {
	do_action( 'divino_masthead' );
}

/**
 * Masthead Bottom
 */
function divino_masthead_bottom() {
	do_action( 'divino_masthead_bottom' );
}

/**
 * Header After
 */
function divino_header_after() {
	do_action( 'divino_header_after' );
}

/**
 * Main Header bar top
 */
function divino_main_header_bar_top() {
	do_action( 'divino_main_header_bar_top' );
}

/**
 * Main Header bar bottom
 */
function divino_main_header_bar_bottom() {
	do_action( 'divino_main_header_bar_bottom' );
}

/**
 * Main Header Content
 */
function divino_masthead_content() {
	do_action( 'divino_masthead_content' );
}
/**
 * Main toggle button before
 */
function divino_masthead_toggle_buttons_before() {
	do_action( 'divino_masthead_toggle_buttons_before' );
}

/**
 * Main toggle buttons
 */
function divino_masthead_toggle_buttons() {
	do_action( 'divino_masthead_toggle_buttons' );
}

/**
 * Main toggle button after
 */
function divino_masthead_toggle_buttons_after() {
	do_action( 'divino_masthead_toggle_buttons_after' );
}

/**
 * Semantic <content> hooks
 *
 * $divino_supports[] = 'content';
 */
function divino_content_before() {
	do_action( 'divino_content_before' );
}

/**
 * Content after
 */
function divino_content_after() {
	do_action( 'divino_content_after' );
}

/**
 * Content top
 */
function divino_content_top() {
	do_action( 'divino_content_top' );
}

/**
 * Content bottom
 */
function divino_content_bottom() {
	do_action( 'divino_content_bottom' );
}

/**
 * Content while before
 */
function divino_content_while_before() {
	do_action( 'divino_content_while_before' );
}

/**
 * Content loop
 */
function divino_content_loop() {
	do_action( 'divino_content_loop' );
}

/**
 * Conten Page Loop.
 *
 * Called from page.php
 */
function divino_content_page_loop() {
	do_action( 'divino_content_page_loop' );
}

/**
 * Content while after
 */
function divino_content_while_after() {
	do_action( 'divino_content_while_after' );
}

/**
 * Semantic <entry> hooks
 *
 * $divino_supports[] = 'entry';
 */
function divino_entry_before() {
	do_action( 'divino_entry_before' );
}

/**
 * Entry after
 */
function divino_entry_after() {
	do_action( 'divino_entry_after' );
}

/**
 * Entry content before
 */
function divino_entry_content_before() {
	do_action( 'divino_entry_content_before' );
}

/**
 * Entry content after
 */
function divino_entry_content_after() {
	do_action( 'divino_entry_content_after' );
}

/**
 * Entry Top
 */
function divino_entry_top() {
	do_action( 'divino_entry_top' );
}

/**
 * Entry bottom
 */
function divino_entry_bottom() {
	do_action( 'divino_entry_bottom' );
}

/**
 * Single Post Header Before
 */
function divino_single_header_before() {
	do_action( 'divino_single_header_before' );
}

/**
 * Single Post Header After
 */
function divino_single_header_after() {
	do_action( 'divino_single_header_after' );
}

/**
 * Single Post Header Top
 */
function divino_single_header_top() {
	do_action( 'divino_single_header_top' );
}

/**
 * Single Post Header Bottom
 */
function divino_single_header_bottom() {
	do_action( 'divino_single_header_bottom' );
}

/**
 * Comments block hooks
 *
 * $divino_supports[] = 'comments';
 */
function divino_comments_before() {
	do_action( 'divino_comments_before' );
}

/**
 * Comments after.
 */
function divino_comments_after() {
	do_action( 'divino_comments_after' );
}

/**
 * Semantic <sidebar> hooks
 *
 * $divino_supports[] = 'sidebar';
 */
function divino_sidebars_before() {
	do_action( 'divino_sidebars_before' );
}

/**
 * Sidebars after
 */
function divino_sidebars_after() {
	do_action( 'divino_sidebars_after' );
}

/**
 * Semantic <footer> hooks
 *
 * $divino_supports[] = 'footer';
 */
function divino_footer() {
	do_action( 'divino_footer' );
}

/**
 * Footer before
 */
function divino_footer_before() {
	do_action( 'divino_footer_before' );
}

/**
 * Footer after
 */
function divino_footer_after() {
	do_action( 'divino_footer_after' );
}

/**
 * Footer top
 */
function divino_footer_content_top() {
	do_action( 'divino_footer_content_top' );
}

/**
 * Footer
 */
function divino_footer_content() {
	do_action( 'divino_footer_content' );
}

/**
 * Footer bottom
 */
function divino_footer_content_bottom() {
	do_action( 'divino_footer_content_bottom' );
}

/**
 * Archive header
 */
function divino_archive_header() {
	do_action( 'divino_archive_header' );
}

/**
 * Pagination
 */
function divino_pagination() {
	do_action( 'divino_pagination' );
}

/**
 * Entry content single
 */
function divino_entry_content_single() {
	do_action( 'divino_entry_content_single' );
}

/**
 * Entry content single-page.
 *
 * @since 4.0.0
 */
function divino_entry_content_single_page() {
	do_action( 'divino_entry_content_single_page' );
}

/**
 * 404
 */
function divino_entry_content_404_page() {
	do_action( 'divino_entry_content_404_page' );
}

/**
 * Entry content blog
 */
function divino_entry_content_blog() {
	do_action( 'divino_entry_content_blog' );
}

/**
 * Blog featured post section
 */
function divino_blog_post_featured_format() {
	do_action( 'divino_blog_post_featured_format' );
}

/**
 * Primary Content Top
 */
function divino_primary_content_top() {
	do_action( 'divino_primary_content_top' );
}

/**
 * Primary Content Bottom
 */
function divino_primary_content_bottom() {
	do_action( 'divino_primary_content_bottom' );
}

/**
 * 404 Page content template action.
 */
function divino_404_content_template() {
	do_action( 'divino_404_content_template' );
}

if ( ! function_exists( 'wp_body_open' ) ) {

	/**
	 * Fire the wp_body_open action.
	 * Adds backward compatibility for WordPress versions < 5.2
	 *
	 * @since 1.8.7
	 */
	function wp_body_open() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
		do_action( 'wp_body_open' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}
}
