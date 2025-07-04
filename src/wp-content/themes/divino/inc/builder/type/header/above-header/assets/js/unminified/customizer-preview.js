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

	var tablet_break_point    = divinoBuilderPreview.tablet_break_point || 768,
		mobile_break_point    = divinoBuilderPreview.mobile_break_point || 544;

	wp.customize( 'divino-settings[hba-header-height]', function( value ) {
		value.bind( function( size ) {

			if( size.desktop != '' || size.tablet != '' || size.mobile != '' ) {
				var dynamicStyle = '';
				dynamicStyle += '.ast-above-header-bar .site-above-header-wrap, .ast-mobile-header-wrap .ast-above-header-bar{';
				dynamicStyle += 'min-height: ' + size.desktop + 'px;';
				dynamicStyle += '} ';
				dynamicStyle += '.ast-desktop .ast-above-header-bar .main-header-menu > .menu-item {';
				dynamicStyle += 'line-height: ' + size.desktop + 'px;';
				dynamicStyle += '} ';

				dynamicStyle +=  '@media (max-width: ' + tablet_break_point + 'px) {';
				dynamicStyle += '.ast-above-header-bar .site-above-header-wrap, .ast-mobile-header-wrap .ast-above-header-bar{';
				dynamicStyle += 'min-height: ' + size.tablet + 'px;';
				dynamicStyle += '} ';
				dynamicStyle += '} ';

				dynamicStyle +=  '@media (max-width: ' + mobile_break_point + 'px) {';
				dynamicStyle += '.ast-above-header-bar .site-above-header-wrap, .ast-mobile-header-wrap .ast-above-header-bar{';
				dynamicStyle += 'min-height: ' + size.mobile + 'px;';
				dynamicStyle += '} ';
				dynamicStyle += '} ';

				divino_add_dynamic_css( 'hba-header-height', dynamicStyle );
			}
		} );
	} );

	// Border Bottom width.
	wp.customize( 'divino-settings[hba-header-separator]', function( value ) {
		value.bind( function( border ) {

			var color = wp.customize( 'divino-settings[hba-header-bottom-border-color]' ).get(),
				dynamicStyle = '';

			dynamicStyle += '.ast-above-header.ast-above-header-bar, .ast-above-header-bar {';
			dynamicStyle += 'border-bottom-width: ' + border + 'px;';
			dynamicStyle += 'border-bottom-style: solid;';
			dynamicStyle += 'border-color:' + color + ';';
			dynamicStyle += '}';

			divino_add_dynamic_css( 'hba-header-separator', dynamicStyle );

		} );
	} );

	// Border Color.
	divino_css(
		'divino-settings[hba-header-bottom-border-color]',
		'border-color',
		'.ast-above-header.ast-above-header-bar, .ast-above-header-bar'
	);

	// Responsive BG styles > Below Header Row.
	divino_apply_responsive_background_css( 'divino-settings[hba-header-bg-obj-responsive]', '.ast-above-header.ast-above-header-bar', 'desktop' );
	divino_apply_responsive_background_css( 'divino-settings[hba-header-bg-obj-responsive]', '.ast-above-header.ast-above-header-bar', 'tablet' );
	divino_apply_responsive_background_css( 'divino-settings[hba-header-bg-obj-responsive]', '.ast-above-header.ast-above-header-bar', 'mobile' );
	
	if (document.querySelector(".ast-above-header-wrap .site-logo-img")) {
	divino_apply_responsive_background_css(
		"divino-settings[hba-header-bg-obj-responsive]",
		".ast-sg-element-wrap.ast-sg-logo-section, .ast-above-header.ast-above-header-bar",
		"desktop"
	);
	divino_apply_responsive_background_css(
		"divino-settings[hba-header-bg-obj-responsive]",
		".ast-sg-element-wrap.ast-sg-logo-section, .ast-above-header.ast-above-header-bar",
		"tablet"
	);
	divino_apply_responsive_background_css(
		"divino-settings[hba-header-bg-obj-responsive]",
		".ast-sg-element-wrap.ast-sg-logo-section, .ast-above-header.ast-above-header-bar",
		"mobile"
	);
}

	// Advanced CSS Generation.
	divino_builder_advanced_css( 'section-above-header-builder', '.ast-above-header.ast-above-header-bar, .ast-header-break-point #masthead.site-header .ast-above-header-bar' );

    // Advanced Visibility CSS Generation.
	divino_builder_visibility_css( 'section-above-header-builder', '.ast-above-header-bar', 'grid' );

} )( jQuery );
