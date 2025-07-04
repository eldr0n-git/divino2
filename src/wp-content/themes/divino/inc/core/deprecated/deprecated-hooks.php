<?php
/**
 * Deprecated Hooks of divino Theme.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.0.23
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'divino_do_action_deprecated' ) ) {
	/**
	 * divino Filter Deprecated
	 *
	 * @since 1.1.1
	 * @param string $tag         The name of the filter hook.
	 * @param array  $args        Array of additional function arguments to be passed to apply_filters().
	 * @param string $version     The version of WordPress that deprecated the hook.
	 * @param string $replacement Optional. The hook that should have been used. Default false.
	 * @param string $message     Optional. A message regarding the change. Default null.
	 */
	function divino_do_action_deprecated( $tag, $args, $version, $replacement = false, $message = null ) {
		if ( function_exists( 'do_action_deprecated' ) ) { /* WP >= 4.6 */
			do_action_deprecated( $tag, $args, $version, $replacement, $message );
		} else {
			do_action_ref_array( $tag, $args ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound
		}
	}
}

// Depreciating asta_register_admin_menu hook.
add_action( 'divino_register_admin_menu', 'divino_deprecated_asta_register_admin_menu_hook', 10, 5 );

/**
 * Depreciating 'asta_register_admin_menu' action & replacing with 'divino_register_admin_menu'.
 *
 * @param string   $parent_page        Admin menu page.
 * @param string   $page_title         The text to be displayed in the title tags of the page when the menu is selected.
 * @param string   $capability         The capability required for this menu to be displayed to the user.
 * @param string   $page_menu_slug     The slug name to refer to this menu by (should be unique for this menu).
 * @param callable $page_menu_func     The function to be called to output the content for this page.
 *
 * @since 3.7.4
 */
function divino_deprecated_asta_register_admin_menu_hook( $parent_page, $page_title, $capability, $page_menu_slug, $page_menu_func ) {
	divino_do_action_deprecated( 'asta_register_admin_menu', array( $parent_page, $page_title, $capability, $page_menu_slug, $page_menu_func ), '3.7.4', 'divino_register_admin_menu' );
}

/**
 * Actions which are deprecated in admin redesign phase.
 *
 * @since 4.0.0
 */
function divino_show_deprecated_admin_hooks_warnings() {
	global $pagenow;
	$screen = get_current_screen();
	if ( 'admin.php' === $pagenow && is_object( $screen ) && 'toplevel_page_' . divino_Menu::get_theme_page_slug() === $screen->id ) {
		divino_do_action_deprecated( 'divino_welcome_page_content_before', array(), '4.0.0' );
		divino_do_action_deprecated( 'divino_welcome_page_content', array(), '4.0.0' );
		divino_do_action_deprecated( 'divino_welcome_page_content_after', array(), '4.0.0' );
		divino_do_action_deprecated( 'divino_welcome_page_right_sidebar_before', array(), '4.0.0' );
		divino_do_action_deprecated( 'divino_welcome_page_right_sidebar_content', array(), '4.0.0' );
		divino_do_action_deprecated( 'divino_welcome_page_right_sidebar_after', array(), '4.0.0' );
		divino_do_action_deprecated( 'divino_single_post_order_before', array(), '4.0.0' );
		divino_do_action_deprecated( 'divino_single_post_title_before', array(), '4.0.0' );
		divino_do_action_deprecated( 'divino_single_post_title_after', array(), '4.0.0' );
		divino_do_action_deprecated( 'divino_single_post_meta_before', array(), '4.0.0' );
		divino_do_action_deprecated( 'divino_single_post_meta_after', array(), '4.0.0' );
		divino_do_action_deprecated( 'divino_single_post_order_after', array(), '4.0.0' );
	}
}

// Depreciating legacy admin hooks.
add_action( 'admin_notices', 'divino_show_deprecated_admin_hooks_warnings', 999 );
