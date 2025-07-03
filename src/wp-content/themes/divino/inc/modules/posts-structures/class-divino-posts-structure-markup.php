<?php
/**
 * Hero section layout for Astra theme.
 *
 * @package     Astra
 * @link        https://www.brainstormforce.com
 * @since       Astra 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Breadcrumbs Markup Initial Setup
 *
 * @since 4.0.0
 */
class divino_Posts_Structure_Markup {
	/**
	 *  Constructor
	 */
	public function __construct() {
		$divino_banner_hook = apply_filters( 'divino_banner_hook', 'divino_content_before' );
		add_action( esc_attr( $divino_banner_hook ), array( $this, 'divino_add_hero_section_markup' ), 99 );
	}

	/**
	 * Check eligibility to override default entry header.
	 *
	 * @since 4.0.0
	 * @return void
	 */
	public function divino_add_hero_section_markup() {
		if ( apply_filters( 'divino_apply_hero_header_banner', true ) ) {
			$this->override_entry_header();
		}
	}

	/**
	 * Support custom title & description support for archive.
	 *
	 * @param string $title Default archive title.
	 * @since 4.0.0
	 * @return string
	 */
	public function divino_archive_custom_title( $title ) {
		$post_type    = divino_get_post_type();
		$custom_title = divino_get_option( 'ast-dynamic-archive-' . $post_type . '-custom-title', '' );
		return ! empty( $custom_title ) ? $custom_title : $title;
	}

	/**
	 * Override default entry header.
	 *
	 * @since 4.0.0
	 * @return void
	 */
	public function override_entry_header() {

		if ( is_front_page() && 'page' === get_option( 'show_on_front' ) && divino_get_option( 'ast-dynamic-single-page-disable-structure-meta-on-front-page', false ) ) {
			return;
		}

		if ( is_search() ) {
			if ( true === divino_get_option( 'ast-search-page-title', true ) ) {
				if ( 'layout-2' === divino_get_option( 'section-search-page-title-layout', 'layout-1' ) ) {
					$type = 'search';

					do_action( 'divino_before_archive_' . $type . '_banner_content' );
					get_template_part( 'template-parts/special-banner' );
					do_action( 'divino_after_archive_' . $type . '_banner_content' );

					remove_action( 'divino_archive_header', 'divino_archive_page_info' );
					return;
				}
			} else {
				add_filter( 'divino_the_title_enabled', '__return_false' );
				return;
			}
		}

		$post_type = divino_get_post_type();
		$type      = is_singular( $post_type ) ? 'single' : 'archive';

		$supported_post_types = divino_Posts_Structure_Loader::get_supported_post_types();
		if ( ! in_array( $post_type, $supported_post_types ) ) {
			return;
		}

		$layout_type = 'single' === $type ? divino_get_option( 'ast-dynamic-single-' . $post_type . '-layout', 'layout-1' ) : divino_get_option( 'ast-dynamic-archive-' . $post_type . '-layout', 'layout-1' );

		// If banner title section is disabled then halt further processing.
		if ( 'single' === $type ) {
			if ( false === divino_get_option( 'ast-single-' . $post_type . '-title', class_exists( 'WooCommerce' ) && 'product' === $post_type ? false : true ) ) {
				add_filter( 'divino_single_layout_one_banner_visibility', '__return_false' );
				return;
			}

			$visibility = get_post_meta( absint( divino_get_post_id() ), 'ast-banner-title-visibility', true );
			$visibility = apply_filters( 'divino_banner_title_area_visibility', $visibility );
			if ( 'disabled' === $visibility ) {
				add_filter( 'divino_single_layout_one_banner_visibility', '__return_false' );
				return;
			}
		} else {
			// If layout-1 is set then no need to process further.
			if ( false === divino_get_option( 'ast-archive-' . $post_type . '-title', class_exists( 'WooCommerce' ) && 'product' === $post_type ? false : true ) ) {
				add_filter( 'divino_the_title_enabled', '__return_false' );
				return;
			}
			if ( 'layout-1' === $layout_type ) {
				// WooCommerce specific compatibility - As layout-1 support needs to add externally.
				if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_taxonomy() ) ) {
					$this->divino_woocommerce_banner_layout_1_compatibility();
					add_action( 'divino_primary_content_top', array( $this, 'divino_force_render_banner_layout_1' ) );
				}
				return;
			}

			add_filter( 'divino_the_title_enabled', '__return_false' );
		}

		if ( 'single' === $type && 'layout-2' === $layout_type ) {
			do_action( 'divino_before_single_' . $post_type . '_banner_content' );

			get_template_part( 'template-parts/single-banner' );

			do_action( 'divino_after_single_' . $post_type . '_banner_content' );

			add_filter( 'divino_remove_entry_header_content', '__return_true' );
			add_filter( 'divino_single_layout_one_banner_visibility', '__return_false' );
		} elseif ( ( is_front_page() && is_home() ) || ( is_home() ) ) {
			if ( true === divino_get_option( 'ast-dynamic-archive-post-banner-on-blog', false ) ) {
				// For latest posts page.
				add_filter( 'divino_the_default_home_page_title', array( $this, 'divino_archive_custom_title' ) );

				// For blog page.
				add_filter( 'divino_the_blog_home_page_title', array( $this, 'divino_archive_custom_title' ) );

				do_action( 'divino_before_archive_' . $post_type . '_banner_content' );

				get_template_part( 'template-parts/archive-banner' );

				do_action( 'divino_after_archive_' . $post_type . '_banner_content' );

				remove_filter( 'divino_the_default_home_page_title', array( $this, 'divino_archive_custom_title' ) );

				remove_filter( 'divino_the_blog_home_page_title', array( $this, 'divino_archive_custom_title' ) );
			}
		} elseif ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_taxonomy() ) ) {
			$this->divino_woocommerce_banner_layout_1_compatibility();

			do_action( 'divino_before_archive_' . $post_type . '_banner_content' );

			get_template_part( 'template-parts/archive-banner' );

			do_action( 'divino_after_archive_' . $post_type . '_banner_content' );

			if ( is_shop() ) {
				remove_filter( 'woocommerce_page_title', array( $this, 'divino_archive_custom_title' ) );
			}
		} elseif ( class_exists( 'WooCommerce' ) && 'single' === $type && 'product' === $post_type && 'layout-1' === $layout_type ) {
			// Adding layout 1 support to Product post type for single layout.
			add_action( 'divino_primary_content_top', array( $this, 'divino_force_render_banner_layout_1' ) );
		} elseif ( 'archive' === $type ) {
			$is_post_type_archive = is_post_type_archive( $post_type ) ? true : false;

			if ( $is_post_type_archive ) {
				add_filter( 'get_the_archive_title', array( $this, 'divino_archive_custom_title' ) );
			}

			do_action( 'divino_before_archive_' . $post_type . '_banner_content' );

			get_template_part( 'template-parts/archive-banner' );

			do_action( 'divino_after_archive_' . $post_type . '_banner_content' );

			if ( $is_post_type_archive ) {
				remove_filter( 'get_the_archive_title', array( $this, 'divino_archive_custom_title' ) );
			}
		}
	}

	/**
	 * Layout 1 will also needed for WooCommerce product.
	 * Case: WooCommerce by default adds "Shop" title, breadcrumb on shop/product-archive frontend, but this should also get linked to banner layout 1.
	 *
	 * @since 4.0.0
	 * @return void
	 */
	public function divino_woocommerce_banner_layout_1_compatibility() {
		// For custom title page.
		if ( is_shop() ) {
			add_filter( 'woocommerce_page_title', array( $this, 'divino_archive_custom_title' ) );
		}
		add_filter( 'woocommerce_show_page_title', '__return_false' );

		remove_action(
			'woocommerce_before_main_content',
			'woocommerce_breadcrumb',
			20
		);

		remove_action(
			'woocommerce_archive_description',
			'woocommerce_taxonomy_archive_description'
		);

		remove_action(
			'woocommerce_archive_description',
			'woocommerce_product_archive_description'
		);
	}

	/**
	 * Enable layout 1 for some cases. Ex. WC Product.
	 *
	 * @since 4.0.0
	 * @return void
	 */
	public function divino_force_render_banner_layout_1() {
		$is_singular = is_singular() ? true : false;
		if ( $is_singular ) {
			?> <header class="entry-header <?php divino_entry_header_class(); ?>">
			<?php
			divino_single_header_top();
		} else {
			?>
			<section class="ast-archive-description">
			<?php
			do_action( 'divino_before_archive_title' );
		}

		divino_banner_elements_order();

		if ( $is_singular ) {
			?>
			</header> <!-- .entry-header -->
			<?php
			divino_single_header_bottom();
		} else {
			?>
			</section>
			<?php
			do_action( 'divino_after_archive_title' );
		}
	}
}

new divino_Posts_Structure_Markup();
