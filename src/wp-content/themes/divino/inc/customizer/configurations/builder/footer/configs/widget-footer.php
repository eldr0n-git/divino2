<?php
/**
 * Widget footer Configuration.
 *
 * @package     Astra
 * @link        https://wpastra.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register widget footer builder Customizer Configurations.
 *
 * @param  array $configurations Astra Customizer Configurations.
 * @since 4.5.2
 * @return array Astra Customizer Configurations with updated configurations.
 */
function divino_widget_footer_configuration( $configurations = array() ) {
	$widget_config  = divino_Builder_Base_Configuration::prepare_widget_options( 'footer' );
	$configurations = array_merge( $configurations, $widget_config );

	if ( divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
		array_map( 'divino_save_footer_customizer_configs', $configurations );
	}

	return $configurations;
}

if ( divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
	add_action( 'init', 'divino_widget_footer_configuration', 10, 0 );
}
