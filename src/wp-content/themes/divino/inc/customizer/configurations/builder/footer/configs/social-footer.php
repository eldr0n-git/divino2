<?php
/**
 * Social footer Configuration.
 *
 * @package     Astra
 * @link        https://wpastra.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register social footer builder Customizer Configurations.
 *
 * @param array $configurations Astra Customizer Configurations.
 * @since 4.5.2
 * @return array Astra Customizer Configurations with updated configurations.
 */
function divino_social_footer_configuration( $configurations = array() ) {

	$_configs = divino_Social_Icon_Component_Configs::register_configuration( $configurations, 'footer', 'section-fb-social-icons-' );

	if ( divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
		array_map( 'divino_save_footer_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
	add_action( 'init', 'divino_social_footer_configuration', 10, 0 );
}
