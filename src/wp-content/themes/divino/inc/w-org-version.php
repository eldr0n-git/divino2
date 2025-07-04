<?php
/**
 * This file ensures that it is w.org divino theme.
 *
 * @package divino
 * @since divino 4.8.4
 */

/**
 * Function to filter input of Custom Layout's code editor.
 *
 * @param  string $output Output.
 * @param  string $key Key.
 * @return string
 * @since 4.8.4
 */
function divino_default_filter_input( $output, $key ) {
	return filter_input( INPUT_POST, $key, FILTER_DEFAULT ); // phpcs:ignore WordPressVIPMinimum.Security.PHPFilterFunctions.RestrictedFilter -- Default filter after all other cases, Keeping this filter for backward compatibility.
}

add_filter( 'divino_php_default_filter_input', 'divino_default_filter_input', 10, 2 );
