/**
 * This file adds some LIVE to the Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 *
 * @package divino
 * @since 3.0.0
 */

( function( $ ) {

	divino_builder_social_css( 'header', divinoBuilderHeaderSocial.component_limit );

} )( jQuery );
