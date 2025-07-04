<?php
/**
 * Breadcrumbs for divino theme.
 *
 * @package     divino
 * @link        https://www.brainstormforce.com
 * @since       divino 1.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'divino_Breadcrumbs_Markup' ) ) {

	/**
	 * Breadcrumbs Markup Initial Setup
	 *
	 * @since 1.8.0
	 */
	class divino_Breadcrumbs_Markup {
		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 *  Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 *  Constructor
		 */
		public function __construct() {

			add_action( 'wp', array( $this, 'divino_breadcumb_template' ) );
		}

		/**
		 * divino Breadcrumbs Template
		 *
		 * Loads template based on the style option selected in options panel for Breadcrumbs.
		 *
		 * @since 1.8.0
		 *
		 * @return void
		 */
		public function divino_breadcumb_template() {

			$breadcrumb_position = divino_get_option( 'breadcrumb-position' );

			$breadcrumb_enabled = false;

			if ( is_singular() ) {
				$breadcrumb_enabled = get_post_meta( get_the_ID(), 'ast-breadcrumbs-content', true );
			}

			if ( 'disabled' !== $breadcrumb_enabled && $breadcrumb_position && 'none' !== $breadcrumb_position && ! ( ( is_home() || is_front_page() ) && ( 'divino_entry_top' === $breadcrumb_position ) ) ) {
				if ( self::divino_breadcrumb_rules() ) {
					if ( ( is_archive() || is_search() ) && 'divino_entry_top' === $breadcrumb_position ) {
						add_action( 'divino_before_archive_title', array( $this, 'divino_hook_breadcrumb_position' ), 15 );
					} else {
						add_action( $breadcrumb_position, array( $this, 'divino_hook_breadcrumb_position' ), 15 );
					}
				}
			}
		}

		/**
		 * divino Hook Breadcrumb Position
		 *
		 * Hook breadcrumb to position of selected option
		 *
		 * @since 1.8.0
		 *
		 * @return void
		 */
		public function divino_hook_breadcrumb_position() {
			$breadcrumb_position = divino_get_option( 'breadcrumb-position' );

			if ( $breadcrumb_position && ( 'divino_header_markup_after' === $breadcrumb_position || 'divino_header_after' === $breadcrumb_position ) ) {
				echo '<div class="main-header-bar ast-header-breadcrumb">
							<div class="ast-container">';
			}
			divino_get_breadcrumb();
			if ( $breadcrumb_position && ( 'divino_header_markup_after' === $breadcrumb_position || 'divino_header_after' === $breadcrumb_position ) ) {
				echo '	</div>
					</div>';
			}
		}

		/**
		 * divino Breadcrumbs Rules
		 *
		 * Checks the rules defined for displaying Breadcrumb on different pages.
		 *
		 * @since 1.8.0
		 *
		 * @return bool
		 */
		public static function divino_breadcrumb_rules() {

			// Display Breadcrumb default true.
			$display_breadcrumb = true;

			if ( is_front_page() && '0' == divino_get_option( 'breadcrumb-disable-home-page' ) ) {
				$display_breadcrumb = false;
			}

			if ( is_home() && '0' == divino_get_option( 'breadcrumb-disable-blog-posts-page' ) ) {
				$display_breadcrumb = false;
			}

			if ( is_search() && '0' == divino_get_option( 'breadcrumb-disable-search' ) ) {
				$display_breadcrumb = false;
			}

			if ( is_archive() && '0' == divino_get_option( 'breadcrumb-disable-archive' ) ) {
				$display_breadcrumb = false;
			}

			if ( is_page() && '0' == divino_get_option( 'breadcrumb-disable-single-page' ) ) {
				$display_breadcrumb = false;
			}

			if ( is_single() && '0' == divino_get_option( 'breadcrumb-disable-single-post' ) ) {
				$display_breadcrumb = false;
			}

			if ( is_singular() && '0' == divino_get_option( 'breadcrumb-disable-singular' ) ) {
				$display_breadcrumb = false;
			}

			if ( is_404() && '0' == divino_get_option( 'breadcrumb-disable-404-page' ) ) {
				$display_breadcrumb = false;
			}

			return apply_filters( 'divino_breadcrumb_enabled', $display_breadcrumb );
		}
	}
}

/**
 *  Kicking this off by calling 'get_instance()' method
 */
divino_Breadcrumbs_Markup::get_instance();
