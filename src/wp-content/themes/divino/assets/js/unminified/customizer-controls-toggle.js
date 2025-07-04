/**
 * Customizer controls toggles
 *
 * @package divino
 */

( function( $ ) {


	/**
	 * Trigger hooks
	 */
	ASTControlTrigger = {

	    /**
	     * Trigger a hook.
	     *
	     * @since 1.0.0
	     * @method triggerHook
	     * @param {String} hook The hook to trigger.
	     * @param {Array} args An array of args to pass to the hook.
		 */
	    triggerHook: function( hook, args )
	    {
	    	$( 'body' ).trigger( 'divino-control-trigger.' + hook, args );
	    },

	    /**
	     * Add a hook.
	     *
	     * @since 1.0.0
	     * @method addHook
	     * @param {String} hook The hook to add.
	     * @param {Function} callback A function to call when the hook is triggered.
	     */
	    addHook: function( hook, callback )
	    {
	    	$( 'body' ).on( 'divino-control-trigger.' + hook, callback );
	    },

	    /**
	     * Remove a hook.
	     *
	     * @since 1.0.0
	     * @method removeHook
	     * @param {String} hook The hook to remove.
	     * @param {Function} callback The callback function to remove.
	     */
	    removeHook: function( hook, callback )
	    {
		    $( 'body' ).off( 'divino-control-trigger.' + hook, callback );
	    },
	};

	/**
	 * Helper class that contains data for showing and hiding controls.
	 *
	 * @since 1.0.0
	 * @class ASTCustomizerToggles
	 */
	ASTCustomizerToggles = {

		'divino-settings[display-site-title-responsive]' : [],

		'divino-settings[display-site-tagline-responsive]' : [],

		'divino-settings[ast-header-retina-logo]' :[],

		'custom_logo' : [],

		/**
		 * Section - Header
		 *
		 * @link  ?autofocus[section]=section-header
		 */

		/**
		 * Layout 2
		 */
		// Layout 2 > Right Section > Text / HTML
		// Layout 2 > Right Section > Search Type
		// Layout 2 > Right Section > Search Type > Search Box Type.
		'divino-settings[header-main-rt-section]' : [],


		'divino-settings[hide-custom-menu-mobile]' :[],


		/**
		 * Blog
		 */
		'divino-settings[blog-width]' :[],

		'divino-settings[blog-post-structure]' :[],

		/**
		 * Blog Single
		 */
		 'divino-settings[blog-single-post-structure]' : [],

		'divino-settings[blog-single-width]' : [],

		'divino-settings[blog-single-meta]' :[],


		/**
		 * Small Footer
		 */
		'divino-settings[footer-sml-layout]' : [],

		'divino-settings[footer-sml-section-1]' :[],

		'divino-settings[footer-sml-section-2]' :[],

		'divino-settings[footer-sml-divider]' :[],

		'divino-settings[header-main-sep]' :[],

		'divino-settings[disable-primary-nav]' :[],

		/**
		 * Footer Widgets
		 */
		'divino-settings[footer-adv]' :[],

		'divino-settings[shop-archive-width]' :[],

		'divino-settings[mobile-header-logo]' :[],

		'divino-settings[different-mobile-logo]' :[],
	};

} )( jQuery );
