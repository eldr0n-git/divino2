<?php
/**
 * Search for divino theme.
 *
 * @package     divino-builder
 * @link        https://wpdivino.com/
 * @since       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'divino_HEADER_SEARCH_DIR', divino_THEME_DIR . 'inc/builder/type/header/search' );
define( 'divino_HEADER_SEARCH_URI', divino_THEME_URI . 'inc/builder/type/header/search' );

/**
 * Heading Initial Setup
 *
 * @since 3.0.0
 */
class divino_Header_Search_Component {
	/**
	 * Constructor function that initializes required actions and hooks
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once divino_HEADER_SEARCH_DIR . '/class-divino-header-search-component-loader.php';

		// Include front end files.
		if ( ! is_admin() || divino_Builder_Customizer::divino_collect_customizer_builder_data() ) {
			require_once divino_HEADER_SEARCH_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

		add_filter( 'rest_post_query', array( $this, 'divino_update_rest_post_query' ), 10, 2 );
	}

	/**
	 * Update REST Post Query for live search.
	 *
	 * @since 4.4.0
	 * @param array $args Query args.
	 * @param array $request Request args.
	 * @return array
	 */
	public function divino_update_rest_post_query( $args, $request ) {
		if (
			isset( $request['post_type'] )
			&&
			( strpos( $request['post_type'], 'ast_queried' ) !== false )
		) {
			$search_post_types = explode( ':', sanitize_text_field( $request['post_type'] ) );

			$args = array(
				'posts_per_page' => ! empty( $args['posts_per_page'] ) ? $args['posts_per_page'] : 10,
				'post_type'      => $search_post_types,
				'paged'          => 1,
				's'              => ! empty( $args['s'] ) ? $args['s'] : '',
			);

			if ( in_array( 'product', $search_post_types ) ) {
				// Added product visibility checks, excluding hidden or shop-only visibility types.
				$args['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'slug',
					'terms'    => array( 'exclude-from-search' ),
					'operator' => 'NOT IN',
				);
			}
		}

		return $args;
	}
}

/**
 *  Kicking this off by creating an object.
 */
new divino_Header_Search_Component();
