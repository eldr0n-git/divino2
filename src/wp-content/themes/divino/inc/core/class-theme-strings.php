<?php
/**
 * divino Theme Strings
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Default Strings
 */
if ( ! function_exists( 'divino_default_strings' ) ) {

	/**
	 * Default Strings
	 *
	 * @since 1.0.0
	 * @param  string $key  String key.
	 * @param  bool   $echo Print string.
	 * @return mixed        Return string or nothing.
	 */
	function divino_default_strings( $key, $echo = true ) {

		$post_comment_dynamic_string = true === divino_Dynamic_CSS::divino_core_form_btns_styling() ? __( 'Post Comment', 'divino' ) : __( 'Post Comment &raquo;', 'divino' );
		$defaults                    = apply_filters(
			'divino_default_strings',
			array(

				// Header.
				'string-header-skip-link'                => __( 'Skip to content', 'divino' ),

				// 404 Page Strings.
				'string-404-sub-title'                   => __( 'It looks like the link pointing here was faulty. Maybe try searching?', 'divino' ),

				// Search Page Strings.
				'string-search-nothing-found'            => __( 'Nothing Found', 'divino' ),
				'string-search-nothing-found-message'    => __( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'divino' ),
				'string-full-width-search-message'       => __( 'Start typing and press enter to search', 'divino' ),
				'string-full-width-search-placeholder'   => __( 'Search...', 'divino' ),
				'string-header-cover-search-placeholder' => __( 'Search...', 'divino' ),
				'string-search-input-placeholder'        => __( 'Search...', 'divino' ),

				// Comment Template Strings.
				'string-comment-reply-link'              => __( 'Reply', 'divino' ),
				'string-comment-edit-link'               => __( 'Edit', 'divino' ),
				'string-comment-awaiting-moderation'     => __( 'Your comment is awaiting moderation.', 'divino' ),
				'string-comment-title-reply'             => __( 'Leave a Comment', 'divino' ),
				'string-comment-cancel-reply-link'       => __( 'Cancel Reply', 'divino' ),
				'string-comment-label-submit'            => $post_comment_dynamic_string,
				'string-comment-label-message'           => __( 'Type here..', 'divino' ),
				'string-comment-label-name'              => __( 'Name', 'divino' ),
				'string-comment-label-email'             => __( 'Email', 'divino' ),
				'string-comment-label-website'           => __( 'Website', 'divino' ),
				'string-comment-closed'                  => __( 'Comments are closed.', 'divino' ),
				'string-comment-navigation-title'        => __( 'Comment navigation', 'divino' ),
				'string-comment-navigation-next'         => __( 'Newer Comments', 'divino' ),
				'string-comment-navigation-previous'     => __( 'Older Comments', 'divino' ),

				// Blog Default Strings.
				'string-blog-page-links-before'          => __( 'Pages:', 'divino' ),
				'string-blog-meta-author-by'             => __( 'By ', 'divino' ),
				'string-blog-meta-leave-a-comment'       => __( 'Leave a Comment', 'divino' ),
				'string-blog-meta-one-comment'           => __( '1 Comment', 'divino' ),
				'string-blog-meta-multiple-comment'      => __( '% Comments', 'divino' ),
				'string-blog-navigation-next'            => __( 'Next', 'divino' ) . ' <span class="ast-right-arrow" aria-hidden="true">&rarr;</span>',
				'string-blog-navigation-previous'        => '<span class="ast-left-arrow" aria-hidden="true">&larr;</span> ' . __( 'Previous', 'divino' ),
				'string-next-text'                       => __( 'Next', 'divino' ),
				'string-previous-text'                   => __( 'Previous', 'divino' ),

				// Single Post Default Strings.
				'string-single-page-links-before'        => __( 'Pages:', 'divino' ),
				/* translators: 1: Post type label */
				'string-single-navigation-next'          => __( 'Next %s', 'divino' ) . ' <span class="ast-right-arrow" aria-hidden="true">&rarr;</span>',
				/* translators: 1: Post type label */
				'string-single-navigation-previous'      => '<span class="ast-left-arrow" aria-hidden="true">&larr;</span> ' . __( 'Previous %s', 'divino' ),

				// Content None.
				'string-content-nothing-found-message'   => __( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'divino' ),

			)
		);

		if ( is_rtl() ) {
			$defaults['string-blog-navigation-next']     = __( 'Next', 'divino' ) . ' <span class="ast-left-arrow" aria-hidden="true">&larr;</span>';
			$defaults['string-blog-navigation-previous'] = '<span class="ast-right-arrow" aria-hidden="true">&rarr;</span> ' . __( 'Previous', 'divino' );

			/* translators: 1: Post type label */
			$defaults['string-single-navigation-next'] = __( 'Next %s', 'divino' ) . ' <span class="ast-left-arrow" aria-hidden="true">&larr;</span>';
			/* translators: 1: Post type label */
			$defaults['string-single-navigation-previous'] = '<span class="ast-right-arrow" aria-hidden="true">&rarr;</span> ' . __( 'Previous %s', 'divino' );
		}

		$output = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

		/**
		 * Print or return
		 */
		if ( $echo ) {
			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $output;
		}
	}
}
