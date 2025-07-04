/**
 * Install Starter Templates
 *
 *
 * @since 1.2.4
 */

(function($){

	divinoThemeAdmin = {

		init: function()
		{
			this._bind();
		},


		/**
		 * Binds events for the divino Theme.
		 *
		 * @since 1.0.0
		 * @method _bind
		 */
		_bind: function()
		{
			$( document ).on('ast-after-plugin-active', divinoThemeAdmin._disableActivcationNotice );
			$( document ).on('click' , '.divino-install-recommended-plugin', divinoThemeAdmin._installNow );
			$( document ).on('click' , '.divino-activate-recommended-plugin', divinoThemeAdmin._activatePlugin);
			$( document ).on('wp-plugin-install-success' , divinoThemeAdmin._activatePlugin);
			$( document ).on('wp-plugin-install-error'   , divinoThemeAdmin._installError);
			$( document ).on('wp-plugin-installing'      , divinoThemeAdmin._pluginInstalling);
		},

		/**
		 * Plugin Installation Error.
		 */
		_installError: function( event, response ) {

			var $card = jQuery( '.divino-install-recommended-plugin' );

			$card
				.removeClass( 'button-primary' )
				.addClass( 'disabled' )
				.html( wp.updates.l10n.installFailedShort );
		},

		/**
		 * Installing Plugin
		 */
		_pluginInstalling: function(event, args) {
			event.preventDefault();

			var slug = args.slug;

			var $card = jQuery( '.divino-install-recommended-plugin' );
			var activatingText = divino.recommendedPluiginActivatingText;


			$card.each(function( index, element ) {
				element = jQuery( element );
				if ( element.data('slug') === slug ) {
					element.addClass('updating-message');
					element.html( activatingText );
				}
			});
		},

		/**
		 * Activate Success
		 */
		_activatePlugin: function( event, response ) {

			event.preventDefault();

			var $message = jQuery(event.target);
			var $init = $message.data('init');
			var activatedSlug = $init;

			if (typeof $init === 'undefined') {
				var $message = jQuery('.divino-install-recommended-plugin[data-slug=' + response.slug + ']');
				activatedSlug = response.slug;
			}

			// Transform the 'Install' button into an 'Activate' button.
			$init = $message.data('init');
			var activatingText = divino.recommendedPluiginActivatingText;
			var divinoSitesLink = divino.divinoSitesLink;
			var divinoPluginRecommendedNonce = divino.divinoPluginManagerNonce;

			$message.removeClass( 'install-now installed button-disabled updated-message' )
				.addClass('updating-message')
				.html( activatingText );

			// WordPress adds "Activate" button after waiting for 1000ms. So we will run our activation after that.
			setTimeout( function() {

				$.ajax({
					url: divino.ajaxUrl,
					type: 'POST',
					data: {
						'action'            : 'divino_recommended_plugin_activate',
						'security'          : divinoPluginRecommendedNonce,
						'init'              : $init,
					},
				})
				.done(function (result) {

					console.error( result );

					if( result.success ) {
						$message.removeClass( 'divino-activate-recommended-plugin divino-install-recommended-plugin button button-primary install-now activate-now updating-message' );

						$message.parent('.ast-addon-link-wrapper').parent('.divino-recommended-plugin').addClass('active');

						jQuery(document).trigger( 'ast-after-plugin-active', [divinoSitesLink, activatedSlug] );

					} else {

						$message.removeClass( 'updating-message' );
					}

				});

			}, 1200 );

		},

		/**
		 * Install Now
		 */
		_installNow: function(event)
		{
			event.preventDefault();

			var $button 	= jQuery( event.target ),
				$document   = jQuery(document);

			if ( $button.hasClass( 'updating-message' ) || $button.hasClass( 'button-disabled' ) ) {
				return;
			}

			if ( wp.updates.shouldRequestFilesystemCredentials && ! wp.updates.ajaxLocked ) {
				wp.updates.requestFilesystemCredentials( event );

				$document.on( 'credential-modal-cancel', function() {
					var $message = $( '.divino-install-recommended-plugin.updating-message' );

					$message
						.addClass('divino-activate-recommended-plugin')
						.removeClass( 'updating-message divino-install-recommended-plugin' )
						.text( wp.updates.l10n.installNow );

					wp.a11y.speak( wp.updates.l10n.updateCancel, 'polite' );
				} );
			}

			wp.updates.installPlugin( {
				slug:    $button.data( 'slug' )
			});
		},

		/**
		 * After plugin active redirect and deactivate activation notice
		 */
		_disableActivcationNotice: function( event, divinoSitesLink, activatedSlug )
		{
			event.preventDefault();

			if ( activatedSlug.indexOf( 'divino-sites' ) >= 0 || activatedSlug.indexOf( 'divino-pro-sites' ) >= 0 ) {
				if ( 'undefined' != typeof divinoNotices ) {
			    	divinoNotices._ajax( 'divino-sites-on-active', '' );
				}
				window.location.href = divinoSitesLink + '&ast-disable-activation-notice';
			}
		},
	};

	/**
	 * Initialize divinoThemeAdmin
	 */
	$(function(){
		divinoThemeAdmin.init();
	});

})(jQuery);
