<?php
/**
 * Content Background - Dynamic CSS
 *
 * @package divino
 * @since 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'divino_dynamic_theme_css', 'divino_content_background_css', 11 );

/**
 * Content Background - Dynamic CSS
 *
 * @param  string $dynamic_css          divino Dynamic CSS.
 * @return String Generated dynamic CSS for content background.
 *
 * @since 3.2.0
 */
function divino_content_background_css( $dynamic_css ) {

	if ( ! divino_has_gcp_typo_preset_compatibility() ) {
		return $dynamic_css;
	}

	$content_bg_obj = divino_get_option( 'content-bg-obj-responsive' );

	// Override content background with meta value if set.
	$meta_background_enabled = divino_get_option_meta( 'ast-page-background-enabled' );

	// Check for third party pages meta.
	if ( '' === $meta_background_enabled && divino_with_third_party() ) {
		$meta_background_enabled = divino_third_party_archive_meta( 'ast-page-background-enabled' );
		if ( isset( $meta_background_enabled ) && 'enabled' === $meta_background_enabled ) {
			$content_bg_obj = divino_third_party_archive_meta( 'ast-content-background-meta' );
		}
	} elseif ( isset( $meta_background_enabled ) && 'enabled' === $meta_background_enabled ) {
		$content_bg_obj = divino_get_option_meta( 'ast-content-background-meta' );
	}

	$blog_layout                  = divino_get_blog_layout();
	$blog_grid                    = divino_get_option( 'blog-grid' );
	$sidebar_default_css          = $content_bg_obj;
	$is_boxed                     = divino_is_content_style_boxed();
	$is_sidebar_boxed             = divino_is_sidebar_style_boxed();
	$current_layout               = divino_get_content_layout();
	$narrow_dynamic_selector      = 'narrow-width-container' === $current_layout && $is_boxed ? ', .ast-narrow-container .site-content' : '';
	$comments_wrapper_bg_selector = divino_Dynamic_CSS::divino_4_6_0_compatibility() ? ', .ast-separate-container .comments-area' : ', .ast-separate-container .comments-area .comment-respond, .ast-separate-container .comments-area .ast-comment-list li, .ast-separate-container .comments-area .comments-title';

	$author_box_extra_selector = true === divino_check_is_structural_setup() ? '.site-main' : '';

	// Apply unboxed container with sidebar boxed look by changing background color to site background color.
	$content_bg_obj = divino_apply_unboxed_container( $content_bg_obj, $is_boxed, $is_sidebar_boxed, $current_layout );

	// Container Layout Colors.
	$container_css = array(
		'.ast-separate-container .ast-article-single:not(.ast-related-post), .woocommerce.ast-separate-container .ast-woocommerce-container, .ast-separate-container .error-404, .ast-separate-container .no-results, .single.ast-separate-container ' . esc_attr( $author_box_extra_selector ) . ' .ast-author-meta, .ast-separate-container .related-posts-title-wrapper,.ast-separate-container .comments-count-wrapper, .ast-box-layout.ast-plain-container .site-content,.ast-padded-layout.ast-plain-container .site-content, .ast-separate-container .ast-archive-description' . $narrow_dynamic_selector . $comments_wrapper_bg_selector => divino_get_responsive_background_obj( $content_bg_obj, 'desktop' ),
	);
	// Container Layout Colors.
	$container_css_tablet = array(
		'.ast-separate-container .ast-article-single:not(.ast-related-post), .woocommerce.ast-separate-container .ast-woocommerce-container, .ast-separate-container .error-404, .ast-separate-container .no-results, .single.ast-separate-container ' . esc_attr( $author_box_extra_selector ) . ' .ast-author-meta, .ast-separate-container .related-posts-title-wrapper,.ast-separate-container .comments-count-wrapper, .ast-box-layout.ast-plain-container .site-content,.ast-padded-layout.ast-plain-container .site-content, .ast-separate-container .ast-archive-description' . $narrow_dynamic_selector => divino_get_responsive_background_obj( $content_bg_obj, 'tablet' ),
	);

	// Container Layout Colors.
	$container_css_mobile = array(
		'.ast-separate-container .ast-article-single:not(.ast-related-post), .woocommerce.ast-separate-container .ast-woocommerce-container, .ast-separate-container .error-404, .ast-separate-container .no-results, .single.ast-separate-container ' . esc_attr( $author_box_extra_selector ) . ' .ast-author-meta, .ast-separate-container .related-posts-title-wrapper,.ast-separate-container .comments-count-wrapper, .ast-box-layout.ast-plain-container .site-content,.ast-padded-layout.ast-plain-container .site-content, .ast-separate-container .ast-archive-description' . $narrow_dynamic_selector => divino_get_responsive_background_obj( $content_bg_obj, 'mobile' ),
	);

	// Sidebar specific css.
	$sidebar_css = array(
		'.ast-separate-container.ast-two-container #secondary .widget' => divino_get_responsive_background_obj( $sidebar_default_css, 'desktop' ),
	);

	// Sidebar specific css.
	$sidebar_css_tablet = array(
		'.ast-separate-container.ast-two-container #secondary .widget' => divino_get_responsive_background_obj( $sidebar_default_css, 'tablet' ),
	);

	// Sidebar specific css.
	$sidebar_css_mobile = array(
		'.ast-separate-container.ast-two-container #secondary .widget' => divino_get_responsive_background_obj( $sidebar_default_css, 'mobile' ),
	);

	// Apply Content BG Color for Narrow Unboxed Container.
	if ( ! divino_is_content_style_boxed() && 'narrow-container' === $current_layout ) {
		$container_css        = array_merge(
			$container_css,
			array( '.ast-narrow-container .site-content' => divino_get_responsive_background_obj( $content_bg_obj, 'desktop' ) )
		);
		$container_css_tablet = array_merge(
			$container_css_tablet,
			array( '.ast-narrow-container .site-content' => divino_get_responsive_background_obj( $content_bg_obj, 'tablet' ) )
		);
		$container_css_mobile = array_merge(
			$container_css_mobile,
			array( '.ast-narrow-container .site-content' => divino_get_responsive_background_obj( $content_bg_obj, 'mobile' ) )
		);
	}

	// Blog Pro Layout Colors.
	if ( ( 'blog-layout-1' === $blog_layout || 'blog-layout-4' === $blog_layout || 'blog-layout-6' === $blog_layout ) || ( defined( 'divino_EXT_VER' ) && ( 'blog-layout-1' === $blog_layout || 'blog-layout-4' === $blog_layout || 'blog-layout-6' === $blog_layout ) && 1 !== $blog_grid ) ) {
		$blog_layouts        = array(
			'.ast-separate-container .ast-article-inner' => divino_get_responsive_background_obj( $content_bg_obj, 'desktop' ),
		);
		$blog_layouts_tablet = array(
			'.ast-separate-container .ast-article-inner' => divino_get_responsive_background_obj( $content_bg_obj, 'tablet' ),
		);
		$blog_layouts_mobile = array(
			'.ast-separate-container .ast-article-inner' => divino_get_responsive_background_obj( $content_bg_obj, 'mobile' ),
		);
	} else {
		$blog_layouts        = array(
			'.ast-separate-container .ast-article-post' => divino_get_responsive_background_obj( $content_bg_obj, 'desktop' ),
		);
		$blog_layouts_tablet = array(
			'.ast-separate-container .ast-article-post' => divino_get_responsive_background_obj( $content_bg_obj, 'tablet' ),
		);
		$blog_layouts_mobile = array(
			'.ast-separate-container .ast-article-post' => divino_get_responsive_background_obj( $content_bg_obj, 'mobile' ),
		);
		$inner_layout        = array(
			'.ast-separate-container .ast-article-inner' => array(
				'background-color' => 'transparent',
				'background-image' => 'none',
			),
		);
		$dynamic_css        .= divino_parse_css( $inner_layout );
	}

	$dynamic_css .= divino_parse_css( $blog_layouts );
	/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
	$dynamic_css .= divino_parse_css( $blog_layouts_tablet, '', divino_get_tablet_breakpoint() );
	/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
	$dynamic_css .= divino_parse_css( $blog_layouts_mobile, '', divino_get_mobile_breakpoint() );
	$dynamic_css .= divino_parse_css( $container_css );
	/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
	$dynamic_css .= divino_parse_css( $container_css_tablet, '', divino_get_tablet_breakpoint() );
	/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
	$dynamic_css .= divino_parse_css( $container_css_mobile, '', divino_get_mobile_breakpoint() );
	$dynamic_css .= divino_parse_css( $sidebar_css );
	/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
	$dynamic_css .= divino_parse_css( $sidebar_css_tablet, '', divino_get_tablet_breakpoint() );
	/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
	$dynamic_css .= divino_parse_css( $sidebar_css_mobile, '', divino_get_mobile_breakpoint() );

	if ( divino_apply_content_background_fullwidth_layouts() ) {
		$fullwidth_layout        = array(
			'.ast-plain-container, .ast-page-builder-template' => divino_get_responsive_background_obj( $content_bg_obj, 'desktop' ),
		);
		$fullwidth_layout_tablet = array(
			'.ast-plain-container, .ast-page-builder-template' => divino_get_responsive_background_obj( $content_bg_obj, 'tablet' ),
		);
		$fullwidth_layout_mobile = array(
			'.ast-plain-container, .ast-page-builder-template' => divino_get_responsive_background_obj( $content_bg_obj, 'mobile' ),
		);

		$dynamic_css .= divino_parse_css( $fullwidth_layout );
		/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$dynamic_css .= divino_parse_css( $fullwidth_layout_tablet, '', divino_get_tablet_breakpoint() );
		/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$dynamic_css .= divino_parse_css( $fullwidth_layout_mobile, '', divino_get_mobile_breakpoint() );
	}

	return $dynamic_css;
}

/**
 * Applies an unboxed container to the content.
 *
 * @since 4.2.0
 * @param array $content_bg_obj The background object for the content.
 * @param bool  $is_boxed Container style is boxed or not.
 * @param bool  $is_sidebar_boxed Sidebar style is boxed or not.
 * @param mixed $current_layout The current container layout applied.
 * @return array $content_bg_obj The updated background object for the content.
 */
function divino_apply_unboxed_container( $content_bg_obj, $is_boxed, $is_sidebar_boxed, $current_layout ) {

	$site_bg_obj             = divino_get_option( 'site-layout-outside-bg-obj-responsive' );
	$meta_background_enabled = divino_get_option_meta( 'ast-page-background-enabled' );

	// Check for third party pages meta.
	if ( '' === $meta_background_enabled && divino_with_third_party() ) {
		$meta_background_enabled = divino_third_party_archive_meta( 'ast-page-background-enabled' );
		if ( isset( $meta_background_enabled ) && 'enabled' === $meta_background_enabled ) {
			$site_bg_obj = divino_third_party_archive_meta( 'ast-page-background-meta' );
		}
	} elseif ( isset( $meta_background_enabled ) && 'enabled' === $meta_background_enabled ) {
		$site_bg_obj = divino_get_option_meta( 'ast-page-background-meta' );
	}

	if ( 'plain-container' === $current_layout && ! $is_boxed && $is_sidebar_boxed && 'no-sidebar' !== divino_page_layout() ) {
		$content_bg_obj = $site_bg_obj;
	}
	return $content_bg_obj;
}
