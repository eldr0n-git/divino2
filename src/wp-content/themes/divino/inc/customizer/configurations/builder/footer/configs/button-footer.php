<?php
/**
 * Button footer Configuration.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register button footer builder Customizer Configurations.
 *
 * @param array $configurations divino Customizer Configurations.
 * @since 4.5.2
 * @return array divino Customizer Configurations with updated configurations.
 */
function divino_button_footer_configuration( $configurations = array() ) {
	$_configs = divino_Button_Component_Configs::register_configuration( $configurations, 'footer', 'section-fb-button-' );

	if ( divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
		array_map( 'divino_save_footer_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
	add_action( 'init', 'divino_button_footer_configuration', 10, 0 );
}
