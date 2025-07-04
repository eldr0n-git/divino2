<?php
/**
 * Admin functions - Functions that add some functionality to WordPress admin panel
 *
 * @package divino
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register menus
 */
if ( ! function_exists( 'divino_register_menu_locations' ) ) {

	/**
	 * Register menus
	 *
	 * @since 1.0.0
	 */
	function divino_register_menu_locations() {

		/**
		 * Primary Menus
		 */
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'divino' ),
			)
		);

		if ( true === divino_Builder_Helper::$is_header_footer_builder_active ) {

			/**
			 * Register the Secondary & Mobile menus.
			 */
			register_nav_menus(
				array(
					'secondary_menu' => esc_html__( 'Secondary Menu', 'divino' ),
					'mobile_menu'    => esc_html__( 'Off-Canvas Menu', 'divino' ),
				)
			);

			$component_limit = defined( 'divino_EXT_VER' ) ? divino_Builder_Helper::$component_limit : divino_Builder_Helper::$num_of_header_menu;

			for ( $index = 3; $index <= $component_limit; $index++ ) {

				if ( ! is_customize_preview() && ! divino_Builder_Helper::is_component_loaded( 'menu-' . $index ) ) {
					continue;
				}

				register_nav_menus(
					array(
						'menu_' . $index => esc_html__( 'Menu ', 'divino' ) . $index,
					)
				);
			}

			/**
			 * Register the Account menus.
			 */
			register_nav_menus(
				array(
					'loggedin_account_menu' => esc_html__( 'Logged In Account Menu', 'divino' ),
				)
			);

		}

		/**
		 * Footer Menus
		 */
		register_nav_menus(
			array(
				'footer_menu' => esc_html__( 'Footer Menu', 'divino' ),
			)
		);
	}
}

add_action( 'init', 'divino_register_menu_locations' );
