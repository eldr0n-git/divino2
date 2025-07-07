<?php
/**
 * Loader Functions
 *
 * @package     divino
 * @link        https://divino.kz/
 * @since       divino 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme Enqueue Scripts
 */
if ( ! class_exists( 'divino_Enqueue_Scripts' ) ) {

	/**
	 * Theme Enqueue Scripts
	 */
	class divino_Enqueue_Scripts {
		/**
		 * Class styles.
		 *
		 * @var Enqueued $styles styles.
		 */
		public static $styles;

		/**
		 * Class scripts.
		 *
		 * @var Enqueued $scripts scripts.
		 */
		public static $scripts;

		/**
		 * Constructor
		 */
		public function __construct() {

			add_action( 'divino_get_fonts', array( $this, 'add_fonts' ), 1 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 1 );
			add_action( 'enqueue_block_editor_assets', array( $this, 'gutenberg_assets' ) );
			add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );
			add_action( 'wp_print_footer_scripts', array( $this, 'divino_skip_link_focus_fix' ) );
			add_filter( 'gallery_style', array( $this, 'enqueue_galleries_style' ) );
		}

		/**
		 * Fix skip link focus in IE11.
		 *
		 * This does not enqueue the script because it is tiny and because it is only for IE11,
		 * thus it does not warrant having an entire dedicated blocking script being loaded.
		 *
		 * @link https://git.io/vWdr2
		 * @link https://github.com/WordPress/twentynineteen/pull/47/files
		 * @link https://github.com/ampproject/amphtml/issues/18671
		 */
		public function divino_skip_link_focus_fix() {
			// Skip printing script on AMP content, since accessibility fix is covered by AMP framework.
			if ( divino_is_amp_endpoint() ) {
				return;
			}

			// The following is minified via `terser --compress --mangle -- js/skip-link-focus-fix.js`.
			?>
			<script>
			/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
			</script>
			<?php
		}

		/**
		 * Admin body classes.
		 *
		 * Body classes to be added to <body> tag in admin page
		 *
		 * @param String $classes body classes returned from the filter.
		 * @return String body classes to be added to <body> tag in admin page
		 */
		public function admin_body_class( $classes ) {

			global $pagenow;
			$screen = get_current_screen();

			if ( true === apply_filters( 'divino_block_editor_hover_effect', true ) ) {
				$classes .= ' ast-highlight-wpblock-onhover';
			}

			if ( ( 'post-new.php' === $pagenow || 'post.php' === $pagenow ) && ( defined( 'divino_ADVANCED_HOOKS_POST_TYPE' ) && divino_ADVANCED_HOOKS_POST_TYPE === $screen->post_type ) || 'widgets.php' === $pagenow ) {
				return $classes;
			}

			$post_id          = get_the_ID();
			$is_boxed         = divino_is_content_style_boxed( $post_id );
			$is_sidebar_boxed = divino_is_sidebar_style_boxed( $post_id );
			$classes         .= $is_boxed ? ' ast-default-content-style-boxed' : ' ast-default-content-unboxed';
			$classes         .= $is_sidebar_boxed ? ' ast-default-sidebar-style-boxed' : ' ast-default-sidebar-unboxed';

			if ( $post_id ) {
				$meta_content_layout = divino_toggle_layout( 'ast-site-content-layout', 'meta', $post_id );
			}

			if ( ( isset( $meta_content_layout ) && ! empty( $meta_content_layout ) ) && 'default' !== $meta_content_layout ) {
				$content_layout = $meta_content_layout;
			} else {
				$content_layout = divino_toggle_layout( 'ast-site-content-layout', 'global', false );
			}

			$editor_default_content_layout = divino_toggle_layout( 'single-' . strval( get_post_type() ) . '-ast-content-layout', 'single', false );

			if ( 'default' === $editor_default_content_layout || empty( $editor_default_content_layout ) ) {
				// Get the GLOBAL content layout value.
				// NOTE: Here not used `true` in the below function call.
				$editor_default_content_layout = divino_toggle_layout( 'ast-site-content-layout', 'global', false );
				$editor_default_content_layout = divino_apply_boxed_layouts( $editor_default_content_layout, $is_boxed, $is_sidebar_boxed, $post_id );
				$classes                      .= ' ast-default-layout-' . $editor_default_content_layout;
			} else {
				$editor_default_content_layout = divino_apply_boxed_layouts( $editor_default_content_layout, $is_boxed, $is_sidebar_boxed, $post_id );
				$classes                      .= ' ast-default-layout-' . $editor_default_content_layout;
			}

			$content_layout = divino_apply_boxed_layouts( $content_layout, $is_boxed, $is_sidebar_boxed, $post_id );

			if ( 'content-boxed-container' === $content_layout ) {
				$classes .= ' ast-separate-container';
			} elseif ( 'boxed-container' === $content_layout ) {
				$classes .= ' ast-separate-container ast-two-container';
			} elseif ( 'page-builder' === $content_layout ) {
				$classes .= ' ast-page-builder-template';
			} elseif ( 'plain-container' === $content_layout ) {
				$classes .= ' ast-plain-container';
			} elseif ( 'narrow-container' === $content_layout ) {
				$classes .= ' ast-narrow-container';
			}

			$site_layout = divino_get_option( 'site-layout' );
			if ( 'ast-box-layout' === $site_layout ) {
				$classes .= ' ast-max-width-layout';
			}

			// block CSS class.
			if ( divino_block_based_legacy_setup() ) {
				$classes .= ' ast-block-legacy';
			} else {
				$classes .= ' ast-block-custom';
			}

			$classes .= ' ast-' . divino_page_layout();
			$classes .= ' ast-sidebar-default-' . divino_get_sidebar_layout_for_editor( strval( get_post_type() ) );

			return $classes;
		}

		/**
		 * List of all assets.
		 *
		 * @return array assets array.
		 */
		public static function theme_assets() {

			$default_assets = array(
				// handle => location ( in /assets/js/ ) ( without .js ext).
				'js'  => array(
					'divino-theme-js' => 'style',
				),
				// handle => location ( in /assets/css/ ) ( without .css ext).
				'css' => array(
					'divino-theme-css' => divino_Builder_Helper::apply_flex_based_css() ? 'style-flex' : 'style',
				),
			);

			if ( true === divino_Builder_Helper::$is_header_footer_builder_active ) {

				$default_assets = array(
					// handle => location ( in /assets/js/ ) ( without .js ext).
					'js'  => array(
						'divino-theme-js' => 'frontend',
					),
					// handle => location ( in /assets/css/ ) ( without .css ext).
					'css' => array(
						'divino-theme-css' => divino_Builder_Helper::apply_flex_based_css() ? 'main' : 'frontend',
					),
				);

				if ( defined( 'divino_EXT_VER' ) && version_compare( divino_EXT_VER, '3.5.9', '<' ) ) {
					$default_assets['js']['divino-theme-js-pro'] = 'frontend-pro';
				}

				if ( ( class_exists( 'Easy_Digital_Downloads' ) && divino_Builder_Helper::is_component_loaded( 'edd-cart', 'header' ) ) ||
					( class_exists( 'WooCommerce' ) && divino_Builder_Helper::is_component_loaded( 'woo-cart', 'header' ) ) ) {
					$default_assets['js']['divino-mobile-cart'] = 'mobile-cart';
				}

				/** @psalm-suppress RedundantCondition */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				if ( ( true === divino_Builder_Helper::$is_header_footer_builder_active && divino_Builder_Helper::is_component_loaded( 'search', 'header' ) && divino_get_option( 'live-search', false ) ) || ( is_search() && true === divino_get_option( 'ast-search-live-search' ) ) ) {
					/** @psalm-suppress RedundantCondition */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
					$default_assets['js']['divino-live-search'] = 'live-search';
				}

				if ( class_exists( 'WooCommerce' ) ) {
					if ( is_product() && divino_get_option( 'single-product-sticky-add-to-cart' ) ) {
						$default_assets['js']['divino-sticky-add-to-cart'] = 'sticky-add-to-cart';
					}

					if ( ! is_customize_preview() ) {
						$divino_shop_add_to_cart = divino_get_option( 'shop-add-to-cart-action' );
						if ( $divino_shop_add_to_cart && 'default' !== $divino_shop_add_to_cart ) {
							$default_assets['js']['divino-shop-add-to-cart'] = 'shop-add-to-cart';
						}
					}

					/** @psalm-suppress UndefinedFunction */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
					$divino_add_to_cart_quantity_btn_enabled = apply_filters( 'divino_add_to_cart_quantity_btn_enabled', divino_get_option( 'single-product-plus-minus-button' ) );
					if ( $divino_add_to_cart_quantity_btn_enabled ) {
						$default_assets['js']['divino-add-to-cart-quantity-btn'] = 'add-to-cart-quantity-btn';
					}
				}
			}

			if ( divino_get_option( 'site-sticky-sidebar', false ) ) {
				$default_assets['js']['divino-sticky-sidebar'] = 'sticky-sidebar';
			}

			return apply_filters( 'divino_theme_assets', $default_assets );
		}

		/**
		 * Add Fonts
		 */
		public function add_fonts() {

			$font_family  = divino_get_option( 'body-font-family' );
			$font_weight  = divino_get_option( 'body-font-weight' );
			$font_variant = divino_get_option( 'body-font-variant' );

			divino_Fonts::add_font( $font_family, $font_weight );
			divino_Fonts::add_font( $font_family, $font_variant );

			// Render headings font.
			$heading_font_family  = divino_get_option( 'headings-font-family' );
			$heading_font_weight  = divino_get_option( 'headings-font-weight' );
			$heading_font_variant = divino_get_option( 'headings-font-variant' );

			divino_Fonts::add_font( $heading_font_family, $heading_font_weight );
			divino_Fonts::add_font( $heading_font_family, $heading_font_variant );
		}

		/**
		 * Enqueue Scripts
		 */
		public function enqueue_scripts() {

			if ( false === self::enqueue_theme_assets() ) {
				return;
			}

			/* Directory and Extension */
			$file_prefix = SCRIPT_DEBUG ? '' : '.min';
			$dir_name    = SCRIPT_DEBUG ? 'unminified' : 'minified';

			$js_uri  = divino_THEME_URI . 'assets/js/' . $dir_name . '/';
			$css_uri = divino_THEME_URI . 'assets/css/minified/';

			/**
			 * IE Only Js and CSS Files.
			 */
			// Flexibility.js for flexbox IE10 support.
			wp_enqueue_script( 'divino-flexibility', $js_uri . 'flexibility' . $file_prefix . '.js', array(), divino_THEME_VERSION, false );
			wp_add_inline_script( 'divino-flexibility', 'flexibility(document.documentElement);' );
			wp_script_add_data( 'divino-flexibility', 'conditional', 'IE' );

			// Polyfill for CustomEvent for IE.
			wp_register_script( 'divino-customevent', $js_uri . 'custom-events-polyfill' . $file_prefix . '.js', array(), divino_THEME_VERSION, false );
			wp_register_style( 'divino-galleries-css', $css_uri . 'galleries.min.css', array(), divino_THEME_VERSION, 'all' );
			// All assets.
			$all_assets = self::theme_assets();
			$styles     = $all_assets['css'];
			$scripts    = $all_assets['js'];

			wp_enqueue_style( 'divino-style', get_stylesheet_uri(), array(), divino_THEME_VERSION, 'all' );

			if ( is_array( $styles ) && ! empty( $styles ) ) {
				// Register & Enqueue Styles.
				foreach ( $styles as $key => $style ) {

					$dependency = array();

					// Add dynamic CSS dependency for all styles except for theme's style.css.
					if ( 'divino-theme-css' !== $key && class_exists( 'divino_Cache_Base' ) ) {
						if ( ! divino_Cache_Base::inline_assets() ) {
							$dependency[] = 'divino-theme-dynamic';
						}
					}

					// Generate CSS URL.
					$css_file = $css_uri . $style . '.min.css';

					// Register.
					wp_register_style( $key, $css_file, $dependency, divino_THEME_VERSION, 'all' );

					// Enqueue.
					wp_enqueue_style( $key );

					// RTL support.
					wp_style_add_data( $key, 'rtl', 'replace' );
				}
			}

			// Fonts - Render Fonts.
			divino_Fonts::render_fonts();

			/**
			 * Inline styles
			 */

			add_filter( 'divino_dynamic_theme_css', array( 'divino_Dynamic_CSS', 'return_output' ) );
			add_filter( 'divino_dynamic_theme_css', array( 'divino_Dynamic_CSS', 'return_meta_output' ) );

			$menu_animation = divino_get_option( 'header-main-submenu-container-animation' );

			// Submenu Container Animation for header builder.
			if ( true === divino_Builder_Helper::$is_header_footer_builder_active ) {

				for ( $index = 1; $index <= divino_Builder_Helper::$component_limit; $index++ ) {

					$menu_animation_enable = divino_get_option( 'header-menu' . $index . '-submenu-container-animation' );

					if ( divino_Builder_Helper::is_component_loaded( 'menu-' . $index, 'header' ) && ! empty( $menu_animation_enable ) ) {
						$menu_animation = 'is_animated';
						break;
					}
				}
			}

			$rtl = is_rtl() ? '-rtl' : '';

			if ( ! empty( $menu_animation ) || is_customize_preview() ) {
				if ( class_exists( 'divino_Cache' ) ) {
					divino_Cache::add_css_file( divino_THEME_DIR . 'assets/css/minified/menu-animation' . $rtl . '.min.css' );
				} else {
					wp_register_style( 'divino-menu-animation', $css_uri . 'menu-animation.min.css', null, divino_THEME_VERSION, 'all' );
					wp_enqueue_style( 'divino-menu-animation' );
				}
			}

			if ( ! class_exists( 'divino_Cache' ) ) {
				$theme_css_data = apply_filters( 'divino_dynamic_theme_css', '' );
				wp_add_inline_style( 'divino-theme-css', $theme_css_data );
			}

			if ( divino_is_amp_endpoint() ) {
				return;
			}

			// Comment assets.
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}

			if ( is_array( $scripts ) && ! empty( $scripts ) ) {

				// Register & Enqueue Scripts.
				foreach ( $scripts as $key => $script ) {

					// Register.
					wp_register_script( $key, $js_uri . $script . $file_prefix . '.js', array(), divino_THEME_VERSION, true );

					// Enqueue.
					wp_enqueue_script( $key );
				}
			}

			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$quantity_type = defined( 'divino_EXT_VER' ) && divino_Ext_Extension::is_active( 'woocommerce' ) ? divino_get_option( 'cart-plus-minus-button-type' ) : 'normal';
			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

			$divino_localize = array(
				'break_point'                     => divino_header_break_point(),    // Header Break Point.
				'isRtl'                           => is_rtl(),
				'is_scroll_to_id'                 => divino_get_option( 'enable-scroll-to-id' ),
				'is_scroll_to_top'                => divino_get_option( 'scroll-to-top-enable' ),
				'is_header_footer_builder_active' => divino_Builder_Helper::$is_header_footer_builder_active,
				'responsive_cart_click'           => divino_get_option( 'responsive-cart-click-action' ),
				'is_dark_palette'                 => divino_Global_Palette::is_dark_palette(),
			);

			wp_localize_script( 'divino-theme-js', 'divino', apply_filters( 'divino_theme_js_localize', $divino_localize ) );

			$divino_qty_btn_localize = array(
				'plus_qty'   => __( 'Plus Quantity', 'divino' ),
				'minus_qty'  => __( 'Minus Quantity', 'divino' ),
				'style_type' => $quantity_type,    // Quantity button type.
			);

			wp_localize_script( 'divino-add-to-cart-quantity-btn', 'divino_qty_btn', apply_filters( 'divino_qty_btn_js_localize', $divino_qty_btn_localize ) );

			$divino_cart_localize_data = array(
				'desktop_layout'        => divino_get_option( 'woo-header-cart-click-action' ),    // WooCommerce sidebar flyout desktop.
				'responsive_cart_click' => divino_get_option( 'responsive-cart-click-action' ),    // WooCommerce responsive devices flyout.
			);

			wp_localize_script( 'divino-mobile-cart', 'divino_cart', apply_filters( 'divino_cart_js_localize', $divino_cart_localize_data ) );

			if ( ( true === divino_Builder_Helper::$is_header_footer_builder_active && divino_Builder_Helper::is_component_loaded( 'search', 'header' ) && divino_get_option( 'live-search', false ) ) || ( is_search() && true === divino_get_option( 'ast-search-live-search' ) ) ) {
				$search_post_types      = array();
				$search_post_type_label = array();
				$search_within_val      = divino_get_option( 'live-search-post-types' );
				if ( ! empty( $search_within_val ) && is_array( $search_within_val ) ) {
					foreach ( $search_within_val as $post_type => $value ) {
						if ( $value && post_type_exists( $post_type ) ) {
							$search_post_types[] = $post_type;
							/** @psalm-suppress PossiblyNullPropertyFetch */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
							$post_type_object                     = get_post_type_object( $post_type );
							$search_post_type_label[ $post_type ] = is_object( $post_type_object ) && isset( $post_type_object->labels->name ) ? esc_html( $post_type_object->labels->name ) : $post_type;
							/** @psalm-suppress PossiblyNullPropertyFetch */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
						}
					}
				}

				$search_page_post_types      = array();
				$search_page_post_type_label = array();
				$search_page_within_val      = divino_get_option( 'ast-search-live-search-post-types' );
				if ( is_search() && ! empty( $search_page_within_val ) && is_array( $search_page_within_val ) ) {
					foreach ( $search_page_within_val as $post_type => $value ) {
						if ( $value && post_type_exists( $post_type ) ) {
							$search_page_post_types[] = $post_type;
							/** @psalm-suppress PossiblyNullPropertyFetch */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
							$post_type_object                          = get_post_type_object( $post_type );
							$search_page_post_type_label[ $post_type ] = is_object( $post_type_object ) && isset( $post_type_object->labels->name ) ? esc_html( $post_type_object->labels->name ) : $post_type;
							/** @psalm-suppress PossiblyNullPropertyFetch */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
						}
					}
				}

				$divino_live_search_localize_data = array(
					'rest_api_url'                 => get_rest_url(),
					'search_posts_per_page'        => divino_get_option( 'live-search-result-count' ),
					'search_post_types'            => $search_post_types,
					'search_post_types_labels'     => $search_post_type_label,
					'search_language'              => divino_get_current_language_slug(),
					'no_live_results_found'        => __( 'No results found', 'divino' ),
					'search_page_condition'        => is_search() && true === divino_get_option( 'ast-search-live-search' ) ? true : false,
					'search_page_post_types'       => $search_page_post_types,
					'search_page_post_type_labels' => $search_page_post_type_label,
				);

				wp_localize_script( 'divino-live-search', 'divino_search', apply_filters( 'divino_search_js_localize', $divino_live_search_localize_data ) );
			}

			if ( class_exists( 'woocommerce' ) ) {
				$is_divino_pro = function_exists( 'divino_has_pro_woocommerce_addon' ) ? divino_has_pro_woocommerce_addon() : false;

				$divino_shop_add_to_cart_localize_data = array(
					'shop_add_to_cart_action' => divino_get_option( 'shop-add-to-cart-action' ),
					'cart_url'                => wc_get_cart_url(),
					'checkout_url'            => wc_get_checkout_url(),
					'is_divino_pro'            => $is_divino_pro,
				);
				wp_localize_script( 'divino-shop-add-to-cart', 'divino_shop_add_to_cart', apply_filters( 'divino_shop_add_to_cart_js_localize', $divino_shop_add_to_cart_localize_data ) );
			}

			$sticky_sidebar = divino_get_option( 'site-sticky-sidebar', false );
			if ( $sticky_sidebar ) {

				/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				$sticky_header_addon = ( defined( 'divino_EXT_VER' ) && divino_Ext_Extension::is_active( 'sticky-header' ) );
				/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

				$divino_sticky_sidebar_localize_data = array(
					'sticky_sidebar_on'   => $sticky_sidebar,
					'header_above_height' => divino_get_option( 'hba-header-height' ),
					'header_height'       => divino_get_option( 'hb-header-height' ),
					'header_below_height' => divino_get_option( 'hbb-header-height' ),
					'header_above_stick'  => divino_get_option( 'header-above-stick', false ),
					'header_main_stick'   => divino_get_option( 'header-main-stick', false ),
					'header_below_stick'  => divino_get_option( 'header-below-stick', false ),
					'sticky_header_addon' => $sticky_header_addon,
					'desktop_breakpoint'  => divino_get_tablet_breakpoint( '', 1 ),
				);
				wp_localize_script( 'divino-sticky-sidebar', 'divino_sticky_sidebar', apply_filters( 'divino_sticky_sidebar_js_localize', $divino_sticky_sidebar_localize_data ) );
			}
		}

		/**
		 * Trim CSS
		 *
		 * @since 1.0.0
		 * @param string $css CSS content to trim.
		 * @return string
		 */
		public static function trim_css( $css = '' ) {

			// Trim white space for faster page loading.
			if ( ! empty( $css ) ) {
				$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
				$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );
				$css = str_replace( ', ', ',', $css );
			}

			return $css;
		}

		/**
		 * Enqueue Gutenberg assets.
		 *
		 * @since 1.5.2
		 *
		 * @return void
		 */
		public function gutenberg_assets() {
			/* Directory and Extension */
			$rtl = '';
			if ( is_rtl() ) {
				$rtl = '-rtl';
			}

			$js_uri = divino_THEME_URI . 'inc/assets/js/block-editor-script.js';
			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			wp_enqueue_script( 'divino-block-editor-script', $js_uri, false, divino_THEME_VERSION, 'all' );
			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

			$content_bg_obj = divino_get_option( 'content-bg-obj-responsive' );
			$site_bg_obj    = divino_get_option( 'site-layout-outside-bg-obj-responsive' );

			$site_builder_url = admin_url( 'admin.php?page=theme-builder' );

			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$is_divino_pro_colors_activated = ( defined( 'divino_EXT_VER' ) && divino_Ext_Extension::is_active( 'colors-and-background' ) );
			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

			$divino_global_palette_instance = new divino_Global_Palette();
			$divino_colors                  = array(
				'var(--ast-global-color-0)'     => $divino_global_palette_instance->get_color_by_palette_variable( 'var(--ast-global-color-0)' ),
				'var(--ast-global-color-1)'     => $divino_global_palette_instance->get_color_by_palette_variable( 'var(--ast-global-color-1)' ),
				'var(--ast-global-color-2)'     => $divino_global_palette_instance->get_color_by_palette_variable( 'var(--ast-global-color-2)' ),
				'var(--ast-global-color-3)'     => $divino_global_palette_instance->get_color_by_palette_variable( 'var(--ast-global-color-3)' ),
				'var(--ast-global-color-4)'     => $divino_global_palette_instance->get_color_by_palette_variable( 'var(--ast-global-color-4)' ),
				'var(--ast-global-color-5)'     => $divino_global_palette_instance->get_color_by_palette_variable( 'var(--ast-global-color-5)' ),
				'var(--ast-global-color-6)'     => $divino_global_palette_instance->get_color_by_palette_variable( 'var(--ast-global-color-6)' ),
				'var(--ast-global-color-7)'     => $divino_global_palette_instance->get_color_by_palette_variable( 'var(--ast-global-color-7)' ),
				'var(--ast-global-color-8)'     => $divino_global_palette_instance->get_color_by_palette_variable( 'var(--ast-global-color-8)' ),
				'ast_wp_version_higher_6_3'     => divino_wp_version_compare( '6.2.99', '>' ),
				'ast_wp_version_higher_6_4'     => divino_wp_version_compare( '6.4.99', '>' ),
				'is_dark_palette'               => divino_Global_Palette::is_dark_palette(),
				'apply_content_bg_fullwidth'    => divino_apply_content_background_fullwidth_layouts(),
				'customizer_content_bg_obj'     => $content_bg_obj,
				'customizer_site_bg_obj'        => $site_bg_obj,
				'is_divino_pro_colors_activated' => $is_divino_pro_colors_activated,
				'site_builder_url'              => $site_builder_url,
				'mobile_logo'                   => divino_get_option( 'mobile-header-logo' ),
				'mobile_logo_state'             => divino_get_option( 'different-mobile-logo' ),
			);

			wp_localize_script( 'divino-block-editor-script', 'divinoColors', apply_filters( 'divino_theme_root_colors', $divino_colors ) );

			// Render fonts in Gutenberg layout.
			divino_Fonts::render_fonts();

			if ( divino_block_based_legacy_setup() ) {
				$css_uri = divino_THEME_URI . 'inc/assets/css/block-editor-styles' . $rtl . '.css';
				/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				wp_enqueue_style( 'divino-block-editor-styles', $css_uri, false, divino_THEME_VERSION, 'all' );
				/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				wp_add_inline_style( 'divino-block-editor-styles', apply_filters( 'divino_block_editor_dynamic_css', Gutenberg_Editor_CSS::get_css() ) );
			} else {
				$css_uri = divino_THEME_URI . 'inc/assets/css/wp-editor-styles' . $rtl . '.css';
				/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				wp_enqueue_style( 'divino-wp-editor-styles', $css_uri, false, divino_THEME_VERSION, 'all' );
				/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				wp_add_inline_style( 'divino-wp-editor-styles', apply_filters( 'divino_block_editor_dynamic_css', divino_WP_Editor_CSS::get_css() ) );
			}
		}

		/**
		 * Function to check if enqueuing of divino assets are disabled.
		 *
		 * @since 2.1.0
		 * @return bool
		 */
		public static function enqueue_theme_assets() {
			return apply_filters( 'divino_enqueue_theme_assets', true );
		}

		/**
		 * Enqueue galleries relates CSS on gallery_style filter.
		 *
		 * @param string $gallery_style gallery style and div.
		 * @since 3.5.0
		 * @return string
		 */
		public function enqueue_galleries_style( $gallery_style ) {
			wp_enqueue_style( 'divino-galleries-css' );
			return $gallery_style;
		}

	}

	new divino_Enqueue_Scripts();
}
