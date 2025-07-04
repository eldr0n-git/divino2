/**
 * This file adds some LIVE to the Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 *
 * @package divino
 * @since x.x.x
 */

function divino_dynamic_build_css( addon, control, css_property, selector, unitSupport = false ) {
	var tablet_break_point    = divinoPostStrcturesData.tablet_break_point || 768,
		mobile_break_point    = divinoPostStrcturesData.mobile_break_point || 544,
		unitSuffix = unitSupport || '';

	wp.customize( control, function( value ) {
		value.bind( function( value ) {
			if ( value.desktop || value.mobile || value.tablet ) {
				// Remove <style> first!
				control = control.replace( '[', '-' );
				control = control.replace( ']', '' );
				jQuery( 'style#' + control + '-dynamic-preview-css' ).remove();

				var DeskVal = '',
					TabletFontVal = '',
					MobileVal = '';

				if ( '' != value.desktop ) {
					DeskVal = css_property + ': ' + value.desktop;
				}
				if ( '' != value.tablet ) {
					TabletFontVal = css_property + ': ' + value.tablet;
				}
				if ( '' != value.mobile ) {
					MobileVal = css_property + ': ' + value.mobile;
				}

				// Concat and append new <style>.
				jQuery( 'head' ).append(
					'<style id="' + control + '-dynamic-preview-css">'
					+ selector + ' { ' + DeskVal + unitSuffix + ' }'
					+ '@media (max-width: ' + tablet_break_point + 'px) {' + selector + ' { ' + TabletFontVal + unitSuffix + ' } }'
					+ '@media (max-width: ' + mobile_break_point + 'px) {' + selector + ' { ' + MobileVal + unitSuffix + ' } }'
					+ '</style>'
				);
			} else {
				jQuery( 'style#' + control + '-' + addon ).remove();
			}
		} );
	} );
}

function divino_refresh_customizer( control ) {
	wp.customize( control, function( value ) {
		value.bind( function( value ) {
			wp.customize.preview.send( 'refresh' );
		} );
	} );
}

( function( $ ) {

	var postTypesCount = divinoPostStrcturesData.post_types.length || false,
		postTypes = divinoPostStrcturesData.post_types || [],
		specialsTypesCount = divinoPostStrcturesData.special_pages.length || false,
		specialsTypes = divinoPostStrcturesData.special_pages || [],
		tablet_break_point    = divinoPostStrcturesData.tablet_break_point || 768,
		mobile_break_point    = divinoPostStrcturesData.mobile_break_point || 544;

	/**
	 * For single layouts.
	 */
	for ( var index = 0; index < postTypesCount; index++ ) {
		var postType = postTypes[ index ],
			layoutType = ( undefined !== wp.customize( 'divino-settings[ast-dynamic-single-' + postType + '-layout]' ) ) ? wp.customize( 'divino-settings[ast-dynamic-single-' + postType + '-layout]' ).get() : 'both';

		let exclude_attribute = divinoPostStrcturesData.enabled_related_post ? ':not(.related-entry-header)' : '';

		let selector = '';
		if( 'layout-2' === layoutType ) {
			selector = 'body .ast-single-entry-banner[data-post-type="' + postType + '"]';
		} else if( 'layout-1' === layoutType ) {
			selector = 'header.entry-header' + exclude_attribute;
		} else {
			selector = 'body .ast-single-entry-banner[data-post-type="' + postType + '"], header.entry-header';
		}

		let singleSectionID = '',
			bodyPostTypeClass = 'single-' + postType;
		if ( 'post' !== postType ) {
			if ( 'product' === postType ) {
				singleSectionID = 'section-woo-shop-single';
			} else if ( 'page' === postType ) {
				bodyPostTypeClass = 'page';
				singleSectionID = 'section-single-page';
			} else if ( 'download' === postType ) {
				singleSectionID = 'section-edd-single';
			} else {
				singleSectionID = 'single-posttype-' . postType;
			}

			divino_responsive_spacing( 'divino-settings[' + singleSectionID + '-padding]', 'body.' + bodyPostTypeClass + ' .site .site-content #primary .ast-article-single', 'padding',  ['top', 'right', 'bottom', 'left' ] );
			divino_responsive_spacing( 'divino-settings[' + singleSectionID + '-margin]', 'body.' + bodyPostTypeClass + ' .site .site-content #primary', 'margin', ['top', 'right', 'bottom', 'left' ] );
		}

		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-meta-date-type]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-date-format]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-taxonomy]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-taxonomy-1]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-taxonomy-2]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-taxonomy-style]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-taxonomy-1-style]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-taxonomy-2-style]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-author-avatar]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-structural-taxonomy]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-structural-taxonomy-style]' );

		wp.customize( 'divino-settings[ast-dynamic-single-' + postType + '-author-avatar-size]', function( value ) {
			value.bind( function( size ) {
				var dynamicStyle = '';
				dynamicStyle +=  '.site .ast-author-avatar img {';
				dynamicStyle += 'width: ' + size + 'px;';
				dynamicStyle += 'height: ' + size + 'px;';
				dynamicStyle += '} ';

				divino_add_dynamic_css( 'ast-dynamic-single-' + postType + '-author-avatar-size', dynamicStyle );
			} );
		} );

		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-article-featured-image-position-layout-1]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-article-featured-image-position-layout-2]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-article-featured-image-width-type]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-article-featured-image-ratio-type]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-article-featured-image-ratio-pre-scale]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-article-featured-image-custom-scale-width]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-article-featured-image-custom-scale-height]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-article-featured-image-size]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-remove-featured-padding]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-metadata-separator]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-author-prefix-label]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-featured-as-background]' );
		divino_refresh_customizer( 'divino-settings[ast-dynamic-single-' + postType + '-banner-featured-overlay]' );

		divino_dynamic_build_css(
			'ast-dynamic-single-' + postType + '-horizontal-alignment',
			'divino-settings[ast-dynamic-single-' + postType + '-horizontal-alignment]',
			'text-align',
			selector
		);

		divino_dynamic_build_css(
			'ast-dynamic-single-' + postType + '-banner-height',
			'divino-settings[ast-dynamic-single-' + postType + '-banner-height]',
			'min-height',
			selector,
			'px'
		);

		divino_apply_responsive_background_css( 'divino-settings[ast-dynamic-single-' + postType + '-banner-background]', ' body .ast-single-entry-banner[data-post-type="' + postType + '"]', 'desktop' );
		divino_apply_responsive_background_css( 'divino-settings[ast-dynamic-single-' + postType + '-banner-background]', ' body .ast-single-entry-banner[data-post-type="' + postType + '"]', 'tablet' );
		divino_apply_responsive_background_css( 'divino-settings[ast-dynamic-single-' + postType + '-banner-background]', ' body .ast-single-entry-banner[data-post-type="' + postType + '"]', 'mobile' );

		divino_css(
			'divino-settings[ast-dynamic-single-' + postType + '-vertical-alignment]',
			'justify-content',
			'body .ast-single-entry-banner[data-post-type="' + postType + '"]'
		);

		divino_css(
			'divino-settings[ast-dynamic-single-' + postType + '-banner-custom-width]',
			'max-width',
			'body .ast-single-entry-banner[data-post-type="' + postType + '"][data-banner-width-type="custom"]',
			'px'
		);

		divino_css(
			'divino-settings[ast-dynamic-single-' + postType + '-elements-gap]',
			'margin-bottom',
			'header.entry-header > *:not(:last-child), body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container > *:not(:last-child), header.entry-header .read-more, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container .read-more',
			'px'
		);

		divino_css(
			'divino-settings[ast-dynamic-single-' + postType + '-banner-text-color]',
			'color',
			'header.entry-header *, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container *',
		);

		divino_css(
			'divino-settings[ast-dynamic-single-' + postType + '-banner-title-color]',
			'color',
			'header.entry-header .entry-title, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container .entry-title',
		);

		divino_css(
			'divino-settings[ast-dynamic-single-' + postType + '-banner-link-color]',
			'color',
			'body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container a, header.entry-header a, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container a *, header.entry-header a *'
		);

		divino_css(
			'divino-settings[ast-dynamic-single-' + postType + '-banner-link-hover-color]',
			'color',
			'body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container a:hover, header.entry-header a:hover, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container a:hover *, header.entry-header a:hover *'
		);

		divino_responsive_spacing( 'divino-settings[ast-dynamic-single-' + postType + '-banner-padding]','body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container', 'padding',  ['top', 'right', 'bottom', 'left' ] );
		divino_responsive_spacing( 'divino-settings[ast-dynamic-single-' + postType + '-banner-margin]','body .ast-single-entry-banner[data-post-type="' + postType + '"]', 'margin',  ['top', 'right', 'bottom', 'left' ] );

		// Banner - Title.
		divino_generate_outside_font_family_css( 'divino-settings[ast-dynamic-single-' + postType + '-title-font-family]', ' header.entry-header .entry-title, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container .entry-title' );
		divino_generate_font_weight_css( 'divino-settings[ast-dynamic-single-' + postType + '-title-font-family]', 'divino-settings[ast-dynamic-single-' + postType + '-title-font-weight]', 'font-weight', ' header.entry-header .entry-title, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container .entry-title' );
		divino_css( 'divino-settings[ast-dynamic-single-' + postType + '-title-font-weight]', 'font-weight', ' header.entry-header .entry-title, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container .entry-title' );
		divino_responsive_font_size( 'divino-settings[ast-dynamic-single-' + postType + '-title-font-size]', ' header.entry-header .entry-title, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container .entry-title' );
		divino_font_extras_css( 'ast-dynamic-single-' + postType + '-title-font-extras', ' header.entry-header .entry-title, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container .entry-title' );

		// Banner - Text.
		divino_generate_outside_font_family_css( 'divino-settings[ast-dynamic-single-' + postType + '-text-font-family]', ' header.entry-header *, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container *' );
		divino_generate_font_weight_css( 'divino-settings[ast-dynamic-single-' + postType + '-text-font-family]', 'divino-settings[ast-dynamic-single-' + postType + '-text-font-weight]', 'font-weight', ' header.entry-header *, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container *' );
		divino_css( 'divino-settings[ast-dynamic-single-' + postType + '-text-font-weight]', 'font-weight', ' header.entry-header *, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container *' );
		divino_responsive_font_size( 'divino-settings[ast-dynamic-single-' + postType + '-text-font-size]', ' header.entry-header *, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container *' );
		divino_font_extras_css( 'ast-dynamic-single-' + postType + '-text-font-extras', ' header.entry-header *, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container *' );

		// Banner - Meta.
		divino_generate_outside_font_family_css( 'divino-settings[ast-dynamic-single-' + postType + '-meta-font-family]', ' header.entry-header .entry-meta, header.entry-header .entry-meta *, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container .entry-meta, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container .entry-meta *' );
		divino_generate_font_weight_css( 'divino-settings[ast-dynamic-single-' + postType + '-meta-font-family]', 'divino-settings[ast-dynamic-single-' + postType + '-meta-font-weight]', 'font-weight', ' header.entry-header .entry-meta, header.entry-header .entry-meta *, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container .entry-meta, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container .entry-meta *' );
		divino_css( 'divino-settings[ast-dynamic-single-' + postType + '-meta-font-weight]', 'font-weight', ' header.entry-header .entry-meta, header.entry-header .entry-meta *, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container .entry-meta, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container .entry-meta *' );
		divino_responsive_font_size( 'divino-settings[ast-dynamic-single-' + postType + '-meta-font-size]', ' header.entry-header .entry-meta, header.entry-header .entry-meta *, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container .entry-meta, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container .entry-meta *' );
		divino_font_extras_css( 'ast-dynamic-single-' + postType + '-meta-font-extras', ' header.entry-header .entry-meta, header.entry-header .entry-meta *, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container .entry-meta, body .ast-single-entry-banner[data-post-type="' + postType + '"] .ast-container .entry-meta *' );
	}

	/**
	 * For archive layouts.
	 */
	for ( var index = 0; index < postTypesCount; index++ ) {
		var postType = postTypes[ index ],
			layoutType = ( undefined !== wp.customize( 'divino-settings[ast-dynamic-archive-' + postType + '-layout]' ) ) ? wp.customize( 'divino-settings[ast-dynamic-archive-' + postType + '-layout]' ).get() : 'both',
			layout1BodySelector = 'sc_product' === postType ? 'body.page' : 'body.archive';

		if( 'layout-2' === layoutType ) {
			var selector = 'body .ast-archive-entry-banner[data-post-type="' + postType + '"]';
		} else if( 'layout-1' === layoutType ) {
			var selector = '' + layout1BodySelector + ' .ast-archive-description';
		} else {
			var selector = 'body .ast-archive-entry-banner[data-post-type="' + postType + '"], ' + layout1BodySelector + ' .ast-archive-description';
		}

		divino_refresh_customizer(
			'divino-settings[ast-dynamic-archive-' + postType + '-custom-title]'
		);

		divino_refresh_customizer(
			'divino-settings[ast-dynamic-archive-' + postType + '-custom-description]'
		);

		divino_dynamic_build_css(
			'ast-dynamic-archive-' + postType + '-horizontal-alignment',
			'divino-settings[ast-dynamic-archive-' + postType + '-horizontal-alignment]',
			'text-align',
			selector
		);

		divino_dynamic_build_css(
			'ast-dynamic-archive-' + postType + '-banner-height',
			'divino-settings[ast-dynamic-archive-' + postType + '-banner-height]',
			'min-height',
			selector,
			'px'
		);

		wp.customize( 'divino-settings[ast-dynamic-archive-' + postType + 'banner-width-type]', function( value ) {
			value.bind( function( type ) {
				if ( 'custom' === type ) {
					jQuery('body .ast-archive-entry-banner[data-post-type="' + postType + '"]').attr( 'data-banner-width-type', 'custom' );
					var customWidthSize = wp.customize( 'divino-settings[ast-dynamic-archive-' + postType + 'banner-custom-width]' ).get(),
						dynamicStyle = '';
						dynamicStyle += 'body .ast-archive-entry-banner[data-post-type="' + postType + '"][data-banner-width-type="custom"] {';
						dynamicStyle += 'max-width: ' + customWidthSize + 'px;';
						dynamicStyle += '} ';
					divino_add_dynamic_css( 'ast-dynamic-archive-' + postType + '-banner-width-type', dynamicStyle );
				} else {
					jQuery('body .ast-archive-entry-banner[data-post-type="' + postType + '"]').attr( 'data-banner-width-type', 'full' );
				}
			} );
		} );

		wp.customize( 'divino-settings[ast-dynamic-archive-' + postType + '-banner-height]', function( value ) {
			value.bind( function( size ) {

				if( size.desktop != '' || size.tablet != '' || size.mobile != '' ) {
					var dynamicStyle = '';
					dynamicStyle += 'body .ast-archive-entry-banner[data-post-type="' + postType + '"] {';
					dynamicStyle += 'min-height: ' + size.desktop + 'px;';
					dynamicStyle += '} ';

					dynamicStyle +=  '@media (max-width: ' + tablet_break_point + 'px) {';
					dynamicStyle += 'body .ast-archive-entry-banner[data-post-type="' + postType + '"] {';
					dynamicStyle += 'min-height: ' + size.tablet + 'px;';
					dynamicStyle += '} ';
					dynamicStyle += '} ';

					dynamicStyle +=  '@media (max-width: ' + mobile_break_point + 'px) {';
					dynamicStyle += 'body .ast-archive-entry-banner[data-post-type="' + postType + '"] {';
					dynamicStyle += 'min-height: ' + size.mobile + 'px;';
					dynamicStyle += '} ';
					dynamicStyle += '} ';

					divino_add_dynamic_css( 'ast-dynamic-archive-' + postType + '-banner-height', dynamicStyle );
				}
			} );
		} );

		divino_css(
			'divino-settings[ast-dynamic-archive-' + postType + '-vertical-alignment]',
			'justify-content',
			selector
		);

		divino_css(
			'divino-settings[ast-dynamic-archive-' + postType + '-banner-custom-width]',
			'max-width',
			'body .ast-archive-entry-banner[data-post-type="' + postType + '"][data-banner-width-type="custom"]',
			'px'
		);

		divino_css(
			'divino-settings[ast-dynamic-archive-' + postType + '-elements-gap]',
			'margin-bottom',
			'' + layout1BodySelector + ' .ast-archive-description > *:not(:last-child), body .ast-archive-entry-banner[data-post-type="' + postType + '"] .ast-container > *:not(:last-child)',
			'px'
		);

		divino_css(
			'divino-settings[ast-dynamic-archive-' + postType + '-banner-text-color]',
			'color',
			'body .ast-archive-entry-banner[data-post-type="' + postType + '"] .ast-container *, ' + layout1BodySelector + ' .ast-archive-description *'
		);

		divino_css(
			'divino-settings[ast-dynamic-archive-' + postType + '-banner-title-color]',
			'color',
			'body .ast-archive-entry-banner[data-post-type="' + postType + '"] .ast-container h1, ' + layout1BodySelector + ' .ast-archive-description .ast-archive-title, body .ast-archive-entry-banner[data-post-type="' + postType + '"] .ast-container h1 *, ' + layout1BodySelector + ' .ast-archive-description .ast-archive-title *'
		);

		divino_css(
			'divino-settings[ast-dynamic-archive-' + postType + '-banner-link-color]',
			'color',
			'body .ast-archive-entry-banner[data-post-type="' + postType + '"] .ast-container a, ' + layout1BodySelector + ' .ast-archive-description a, body .ast-archive-entry-banner[data-post-type="' + postType + '"] .ast-container a *, ' + layout1BodySelector + ' .ast-archive-description a *'
		);

		divino_css(
			'divino-settings[ast-dynamic-archive-' + postType + '-banner-link-hover-color]',
			'color',
			'body .ast-archive-entry-banner[data-post-type="' + postType + '"] .ast-container a:hover, ' + layout1BodySelector + ' .ast-archive-description a:hover, body .ast-archive-entry-banner[data-post-type="' + postType + '"] .ast-container a:hover *, ' + layout1BodySelector + ' .ast-archive-description a:hover *'
		);

		divino_apply_responsive_background_css( 'divino-settings[ast-dynamic-archive-' + postType + '-banner-custom-bg]', 'body .ast-archive-entry-banner[data-post-type="' + postType + '"][data-banner-background-type="custom"], ' + layout1BodySelector + ' .ast-archive-description', 'desktop' );
		divino_apply_responsive_background_css( 'divino-settings[ast-dynamic-archive-' + postType + '-banner-custom-bg]', 'body .ast-archive-entry-banner[data-post-type="' + postType + '"][data-banner-background-type="custom"], ' + layout1BodySelector + ' .ast-archive-description', 'tablet' );
		divino_apply_responsive_background_css( 'divino-settings[ast-dynamic-archive-' + postType + '-banner-custom-bg]', 'body .ast-archive-entry-banner[data-post-type="' + postType + '"][data-banner-background-type="custom"], ' + layout1BodySelector + ' .ast-archive-description', 'mobile' );

		divino_responsive_spacing( 'divino-settings[ast-dynamic-archive-' + postType + '-banner-padding]', 'body .ast-archive-entry-banner[data-post-type="' + postType + '"], ' + layout1BodySelector + ' .ast-archive-description', 'padding',  ['top', 'right', 'bottom', 'left' ] );
		divino_responsive_spacing( 'divino-settings[ast-dynamic-archive-' + postType + '-banner-margin]', 'body .ast-archive-entry-banner[data-post-type="' + postType + '"], ' + layout1BodySelector + ' .ast-archive-description', 'margin',  ['top', 'right', 'bottom', 'left' ] );

		// Banner - Title.
		divino_generate_outside_font_family_css( 'divino-settings[ast-dynamic-archive-' + postType + '-title-font-family]', 'body .ast-archive-entry-banner[data-post-type="' + postType + '"] h1, ' + layout1BodySelector + ' .ast-archive-description h1, body .ast-archive-entry-banner[data-post-type="' + postType + '"] h1 *, ' + layout1BodySelector + ' .ast-archive-description h1 *' );
		divino_generate_font_weight_css( 'divino-settings[ast-dynamic-archive-' + postType + '-title-font-family]', 'divino-settings[ast-dynamic-archive-' + postType + '-title-font-weight]', 'font-weight', 'body .ast-archive-entry-banner[data-post-type="' + postType + '"] h1, ' + layout1BodySelector + ' .ast-archive-description h1, body .ast-archive-entry-banner[data-post-type="' + postType + '"] h1 *, ' + layout1BodySelector + ' .ast-archive-description h1 *' );
		divino_css( 'divino-settings[ast-dynamic-archive-' + postType + '-title-font-weight]', 'font-weight', 'body .ast-archive-entry-banner[data-post-type="' + postType + '"] .ast-container h1, ' + layout1BodySelector + ' .ast-archive-description h1, body .ast-archive-entry-banner[data-post-type="' + postType + '"] h1 *, ' + layout1BodySelector + ' .ast-archive-description h1 *' );
		divino_responsive_font_size( 'divino-settings[ast-dynamic-archive-' + postType + '-title-font-size]', 'body .ast-archive-entry-banner[data-post-type="' + postType + '"] .ast-container h1, ' + layout1BodySelector + ' .ast-archive-description .ast-archive-title, body .ast-archive-entry-banner[data-post-type="' + postType + '"] h1 *, ' + layout1BodySelector + ' .ast-archive-description .ast-archive-title *' );
		divino_font_extras_css( 'ast-dynamic-archive-' + postType + '-title-font-extras', 'body .ast-archive-entry-banner[data-post-type="' + postType + '"] .ast-container h1, ' + layout1BodySelector + ' .ast-archive-description .ast-archive-title, body .ast-archive-entry-banner[data-post-type="' + postType + '"] .ast-container h1 *, ' + layout1BodySelector + ' .ast-archive-description h1 *' );

		// Banner - Text.
		divino_generate_outside_font_family_css( 'divino-settings[ast-dynamic-archive-' + postType + '-text-font-family]', 'body .ast-archive-entry-banner[data-post-type="' + postType + '"], body .ast-archive-entry-banner[data-post-type="' + postType + '"] *, ' + layout1BodySelector + ' .ast-archive-description, ' + layout1BodySelector + ' .ast-archive-description *' );
		divino_generate_font_weight_css( 'divino-settings[ast-dynamic-archive-' + postType + '-text-font-family]', 'divino-settings[ast-dynamic-archive-' + postType + '-text-font-weight]', 'font-weight', 'body .ast-archive-entry-banner[data-post-type="' + postType + '"], body .ast-archive-entry-banner[data-post-type="' + postType + '"] *, ' + layout1BodySelector + ' .ast-archive-description, ' + layout1BodySelector + ' .ast-archive-description *' );
		divino_css( 'divino-settings[ast-dynamic-archive-' + postType + '-text-font-weight]', 'font-weight', 'body .ast-archive-entry-banner[data-post-type="' + postType + '"], body .ast-archive-entry-banner[data-post-type="' + postType + '"] *, ' + layout1BodySelector + ' .ast-archive-description, ' + layout1BodySelector + ' .ast-archive-description *' );
		divino_responsive_font_size( 'divino-settings[ast-dynamic-archive-' + postType + '-text-font-size]', 'body .ast-archive-entry-banner[data-post-type="' + postType + '"], body .ast-archive-entry-banner[data-post-type="' + postType + '"] *, ' + layout1BodySelector + ' .ast-archive-description, ' + layout1BodySelector + ' .ast-archive-description *' );
		divino_font_extras_css( 'ast-dynamic-archive-' + postType + '-text-font-extras', 'body .ast-archive-entry-banner[data-post-type="' + postType + '"] .ast-container *, ' + layout1BodySelector + ' .ast-archive-description *' );
	}

	/**
	 * For special pages.
	 */
	for ( var index = 0; index < specialsTypesCount; index++ ) {
		var postType = specialsTypes[ index ],
			sectionKey = 'section-' + postType + '-page-title',
			sectiondivinoSettingKey = 'divino-settings[' + sectionKey,
			layoutType = ( undefined !== wp.customize( sectiondivinoSettingKey + '-layout]' ) ) ? wp.customize( sectiondivinoSettingKey + '-layout]' ).get() : 'both',
			selector = '.search .ast-archive-entry-banner, .search .ast-archive-description';

		divino_refresh_customizer(
			sectiondivinoSettingKey + '-custom-title]'
		);

		divino_refresh_customizer(
			sectiondivinoSettingKey + '-custom-description]'
		);

		divino_dynamic_build_css(
			sectionKey + '-horizontal-alignment',
			sectiondivinoSettingKey + '-horizontal-alignment]',
			'text-align',
			selector
		);

		divino_dynamic_build_css(
			sectionKey + '-banner-height',
			sectiondivinoSettingKey + '-banner-height]',
			'min-height',
			selector,
			'px'
		);

		wp.customize( sectiondivinoSettingKey + 'banner-width-type]', function( value ) {
			value.bind( function( type ) {
				if ( 'custom' === type ) {
					jQuery(selector).attr( 'data-banner-width-type', 'custom' );
					var customWidthSize = wp.customize( sectiondivinoSettingKey + 'banner-custom-width]' ).get(),
						dynamicStyle = '';
						dynamicStyle += selector + '[data-banner-width-type="custom"] {';
						dynamicStyle += 'max-width: ' + customWidthSize + 'px;';
						dynamicStyle += '} ';
					divino_add_dynamic_css( sectionKey + '-banner-width-type', dynamicStyle );
				} else {
					jQuery(selector).attr( 'data-banner-width-type', 'full' );
				}
			} );
		} );

		wp.customize( sectiondivinoSettingKey + '-banner-height]', function( value ) {
			value.bind( function( size ) {

				if( size.desktop != '' || size.tablet != '' || size.mobile != '' ) {
					var dynamicStyle = '';
					dynamicStyle += selector + ' {';
					dynamicStyle += 'min-height: ' + size.desktop + 'px;';
					dynamicStyle += '} ';

					dynamicStyle +=  '@media (max-width: ' + tablet_break_point + 'px) {';
					dynamicStyle += selector + ' {';
					dynamicStyle += 'min-height: ' + size.tablet + 'px;';
					dynamicStyle += '} ';
					dynamicStyle += '} ';

					dynamicStyle +=  '@media (max-width: ' + mobile_break_point + 'px) {';
					dynamicStyle += selector + ' {';
					dynamicStyle += 'min-height: ' + size.mobile + 'px;';
					dynamicStyle += '} ';
					dynamicStyle += '} ';

					divino_add_dynamic_css( sectionKey + '-banner-height', dynamicStyle );
				}
			} );
		} );

		divino_css(
			sectiondivinoSettingKey + '-vertical-alignment]',
			'justify-content',
			selector
		);

		divino_css(
			sectiondivinoSettingKey + '-banner-custom-width]',
			'max-width',
			selector + '[data-banner-width-type="custom"]',
			'px'
		);

		divino_css(
			sectiondivinoSettingKey + '-elements-gap]',
			'margin-bottom',
			'.search .ast-archive-description > *:not(:last-child), .search .ast-archive-entry-banner[data-post-type="' + postType + '"] .ast-container > *:not(:last-child)',
			'px'
		);

		divino_css(
			sectiondivinoSettingKey + '-banner-text-color]',
			'color',
			'.search .ast-archive-entry-banner .ast-container *, .search .ast-archive-description *'
		);

		divino_css(
			sectiondivinoSettingKey + '-banner-title-color]',
			'color',
			'.search .ast-archive-entry-banner .ast-container h1, .search .ast-archive-description h1, .search .ast-archive-entry-banner .ast-container h1 *, .search .ast-archive-description h1 *'
		);

		divino_css(
			sectiondivinoSettingKey + '-banner-link-color]',
			'color',
			selector + ' .ast-container a, ' + '.search .ast-archive-description a, .search .ast-archive-entry-banner[data-post-type="' + postType + '"] .ast-container a *, ' + '.search .ast-archive-description a *'
		);

		divino_css(
			sectiondivinoSettingKey + '-banner-link-hover-color]',
			'color',
			selector + ' .ast-container a:hover, ' + '.search .ast-archive-description a:hover, .search .ast-archive-entry-banner[data-post-type="' + postType + '"] .ast-container a:hover *, ' + '.search .ast-archive-description a:hover *'
		);

		divino_apply_responsive_background_css( sectiondivinoSettingKey + '-banner-custom-bg]', '.search .ast-archive-entry-banner[data-banner-background-type="custom"], .search .ast-archive-description', 'desktop' );
		divino_apply_responsive_background_css( sectiondivinoSettingKey + '-banner-custom-bg]', '.search .ast-archive-entry-banner[data-banner-background-type="custom"], .search .ast-archive-description', 'tablet' );
		divino_apply_responsive_background_css( sectiondivinoSettingKey + '-banner-custom-bg]', '.search .ast-archive-entry-banner[data-banner-background-type="custom"], .search .ast-archive-description', 'mobile' );

		divino_responsive_spacing( sectiondivinoSettingKey + '-banner-padding]', selector + ', ' + '.search .ast-archive-description', 'padding',  ['top', 'right', 'bottom', 'left' ] );
		divino_responsive_spacing( sectiondivinoSettingKey + '-banner-margin]', selector + ', ' + '.search .ast-archive-description', 'margin',  ['top', 'right', 'bottom', 'left' ] );

		// Banner - Title.
		divino_generate_outside_font_family_css( sectiondivinoSettingKey + '-title-font-family]', '.search .ast-archive-entry-banner .ast-container h1, .search .ast-archive-description h1, .search .ast-archive-description .ast-archive-title, .search .ast-archive-entry-banner .ast-container h1 *, .search .ast-archive-description h1 *' );
		divino_generate_font_weight_css( sectiondivinoSettingKey + '-title-font-family]', sectiondivinoSettingKey + '-title-font-weight]', 'font-weight', '.search .ast-archive-entry-banner .ast-container h1, .search .ast-archive-description h1, .search .ast-archive-description .ast-archive-title, .search .ast-archive-entry-banner .ast-container h1 *, .search .ast-archive-description h1 *' );
		divino_css( sectiondivinoSettingKey + '-title-font-weight]', 'font-weight',  '.search .ast-archive-entry-banner .ast-container h1, .search .ast-archive-description h1, .search .ast-archive-description .ast-archive-title, .search .ast-archive-entry-banner .ast-container h1 *, .search .ast-archive-description h1 *' );
		divino_responsive_font_size( sectiondivinoSettingKey + '-title-font-size]', '.search .ast-archive-entry-banner .ast-container h1, .search .ast-archive-description h1, .search .ast-archive-description .ast-archive-title, .search .ast-archive-entry-banner .ast-container h1 *, .search .ast-archive-description h1 *' );
		divino_font_extras_css( sectionKey + '-title-font-extras', '.search .ast-archive-entry-banner .ast-container h1, .search .ast-archive-description h1, .search .ast-archive-description .ast-archive-title, .search .ast-archive-entry-banner .ast-container h1 *, .search .ast-archive-description h1 *' );

		// Banner - Text.
		divino_generate_outside_font_family_css( sectiondivinoSettingKey + '-text-font-family]',  '.search .ast-archive-description *, .search .ast-archive-entry-banner .ast-container *' );
		divino_generate_font_weight_css( sectiondivinoSettingKey + '-text-font-family]', sectiondivinoSettingKey + '-text-font-weight]', 'font-weight', '.search .ast-archive-description *, .search .ast-archive-entry-banner .ast-container *' );
		divino_css( sectiondivinoSettingKey + '-text-font-weight]', 'font-weight', '.search .ast-archive-description *, .search .ast-archive-entry-banner .ast-container *' );
		divino_responsive_font_size( sectiondivinoSettingKey + '-text-font-size]', '.search .ast-archive-description *, .search .ast-archive-entry-banner .ast-container *' );
		divino_font_extras_css( sectionKey + '-text-font-extras', '.search .ast-archive-description *, .search .ast-archive-entry-banner .ast-container *' );
	}

} )( jQuery );
