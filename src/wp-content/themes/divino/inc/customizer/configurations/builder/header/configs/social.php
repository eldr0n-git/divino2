<?php
/**
 * Social Header Configuration.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register social header builder Customizer Configurations.
 *
 * @param array $configurations divino Customizer Configurations.
 * @since 4.5.2
 * @return array divino Customizer Configurations with updated configurations.
 */
function divino_header_social_configuration( $configurations = array() ) {

	$_configs = divino_Social_Icon_Component_Configs::register_configuration( $configurations, 'header', 'section-hb-social-icons-' );

	if ( divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
		array_map( 'divino_save_header_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
	add_action( 'init', 'divino_header_social_configuration', 10, 0 );
}
