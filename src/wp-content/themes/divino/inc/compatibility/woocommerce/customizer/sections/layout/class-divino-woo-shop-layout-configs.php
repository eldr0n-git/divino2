<?php
/**
 * WooCommerce Options for divino Theme.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'divino_Woo_Shop_Layout_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Woo_Shop_Layout_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register divino-WooCommerce Shop Layout Customizer Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$divino_addon_with_woo = divino_has_pro_woocommerce_addon() ? true : false;
			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

			$add_to_cart_attr             = array();
			$ratings                      = array();
			$divino_shop_page_pro_features = array();

			if ( $divino_addon_with_woo ) {
				$divino_shop_page_pro_features = array(
					'redirect_cart_page'     => __( 'Redirect To Cart Page', 'divino' ),
					'redirect_checkout_page' => __( 'Redirect To Checkout Page', 'divino' ),
				);
			}

			/**
			 * Shop product add to cart control.
			 */
			$add_to_cart_attr['add_cart'] = array(
				'clone'       => false,
				'is_parent'   => true,
				'main_index'  => 'add_cart',
				'clone_limit' => 2,
				'title'       => __( 'Add To Cart', 'divino' ),
			);

			/**
			 * Shop product total review count.
			 */
			$ratings['ratings'] = array(
				'clone'       => false,
				'is_parent'   => true,
				'main_index'  => 'ratings',
				'clone_limit' => 2,
				'title'       => __( 'Ratings', 'divino' ),
			);

			if ( $divino_addon_with_woo ) {
				$current_shop_layouts = array(
					'shop-page-grid-style'   => array(
						'label' => __( 'Design 1', 'divino' ),
						'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'shop-grid-view', false ) : '',
					),
					'shop-page-modern-style' => array(
						'label' => __( 'Design 2', 'divino' ),
						'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'shop-modern-view', false ) : '',
					),
					'shop-page-list-style'   => array(
						'label' => __( 'Design 3', 'divino' ),
						'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'shop-list-view', false ) : '',
					),
				);
			} else {
				$current_shop_layouts = array(
					'shop-page-grid-style'   => array(
						'label' => __( 'Design 1', 'divino' ),
						'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'shop-grid-view', false ) : '',
					),
					'shop-page-modern-style' => array(
						'label' => __( 'Design 2', 'divino' ),
						'path'  => class_exists( 'divino_Builder_UI_Controller' ) ? divino_Builder_UI_Controller::fetch_svg_icon( 'shop-modern-view', false ) : '',
					),
				);
			}

			$_configs = array(

				/**
				 * Option: Context for shop archive section.
				 */
				array(
					'name'        => 'section-woocommerce-shop-context-tabs',
					'section'     => 'woocommerce_product_catalog',
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[shop-box-styling]',
					'section'  => 'woocommerce_product_catalog',
					'title'    => __( 'Shop Card Styling', 'divino' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 229,
					'settings' => array(),
					'context'  => array(
						divino_Builder_Helper::$design_tab_config,
					),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Content Alignment
				 */
				array(
					'name'       => divino_THEME_SETTINGS . '[shop-product-align-responsive]',
					'default'    => divino_get_option( 'shop-product-align-responsive' ),
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'woocommerce_product_catalog',
					'priority'   => 229,
					'title'      => __( 'Horizontal Content Alignment', 'divino' ),
					'responsive' => true,
					'choices'    => array(
						'align-left'   => 'align-left',
						'align-center' => 'align-center',
						'align-right'  => 'align-right',
					),
					'context'    => array(
						divino_Builder_Helper::$design_tab_config,
					),
					'divider'    => ! defined( 'divino_EXT_VER' ) ? array() : array( 'ast_class' => 'ast-bottom-section-divider' ),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[woo-shop-structure-divider]',
					'section'  => 'woocommerce_product_catalog',
					'title'    => __( 'Shop Card Structure', 'divino' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 15,
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Single Post Meta
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[shop-product-structure]',
					'type'              => 'control',
					'control'           => 'ast-sortable',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_multi_choices' ),
					'section'           => 'woocommerce_product_catalog',
					'default'           => divino_get_option( 'shop-product-structure' ),
					'priority'          => 15,
					'choices'           => array_merge(
						array(
							'title'      => __( 'Title', 'divino' ),
							'price'      => __( 'Price', 'divino' ),
							'short_desc' => __( 'Short Description', 'divino' ),
						),
						$add_to_cart_attr,
						array(
							'category' => __( 'Category', 'divino' ),
						),
						$ratings,
					),
					'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[woo-shop-skin-divider]',
					'section'  => 'woocommerce_product_catalog',
					'title'    => __( 'Shop Layout', 'divino' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 7,
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Choose Product Style
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[shop-style]',
					'default'           => divino_get_option( 'shop-style' ),
					'type'              => 'control',
					'section'           => 'woocommerce_product_catalog',
					'title'             => __( 'Shop Card Design', 'divino' ),
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_choices' ),
					'priority'          => 8,
					'choices'           => $current_shop_layouts,
					'divider'           => array( 'ast_class' => 'ast-bottom-section-divider' ),
				),

				/**
				 * Option: Shop Columns
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[shop-grids]',
					'type'              => 'control',
					'control'           => 'ast-responsive-slider',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'section'           => 'woocommerce_product_catalog',
					'default'           => divino_get_option(
						'shop-grids',
						array(
							'desktop' => 4,
							'tablet'  => 3,
							'mobile'  => 2,
						)
					),
					'priority'          => 9,
					'title'             => __( 'Shop Columns', 'divino' ),
					'input_attrs'       => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 6,
					),
					'divider'           => array( 'ast_class' => 'ast-bottom-section-divider' ),
				),

				/**
				 * Option: Products Per Page
				 */
				array(
					'name'         => divino_THEME_SETTINGS . '[shop-no-of-products]',
					'type'         => 'control',
					'section'      => 'woocommerce_product_catalog',
					'title'        => __( 'Products Per Page', 'divino' ),
					'default'      => divino_get_option( 'shop-no-of-products' ),
					'control'      => 'ast-number',
					'qty_selector' => true,
					'priority'     => 9,
					'input_attrs'  => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 100,
					),
				),

				/**
				 * Option: Shop Archive Content Width
				 */
				array(
					'name'       => divino_THEME_SETTINGS . '[shop-archive-width]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'woocommerce_product_catalog',
					'default'    => divino_get_option( 'shop-archive-width' ),
					'priority'   => 9,
					'title'      => __( 'Shop Archive Content Width', 'divino' ),
					'choices'    => array(
						'default' => __( 'Default', 'divino' ),
						'custom'  => __( 'Custom', 'divino' ),
					),
					'transport'  => 'refresh',
					'renderAs'   => 'text',
					'responsive' => false,
					'divider'    => $divino_addon_with_woo ? array( 'ast_class' => 'ast-top-section-divider' ) : array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: Enter Width
				 */
				array(
					'name'        => divino_THEME_SETTINGS . '[shop-archive-max-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => 'woocommerce_product_catalog',
					'default'     => divino_get_option( 'shop-archive-max-width' ),
					'priority'    => 9,
					'title'       => __( 'Custom Width', 'divino' ),
					'transport'   => 'postMessage',
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 768,
						'step' => 1,
						'max'  => 1920,
					),
					'context'     => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[shop-archive-width]',
							'operator' => '===',
							'value'    => 'custom',
						),
					),
					'divider'     => array( 'ast_class' => 'ast-top-divider' ),
				),
			);

			/**
			 * Option: Shop add to cart action.
			 */
			$_configs[] = array(
				'name'       => 'shop-add-to-cart-action',
				'parent'     => divino_THEME_SETTINGS . '[shop-product-structure]',
				'default'    => divino_get_option( 'shop-add-to-cart-action' ),
				'section'    => 'woocommerce_product_catalog',
				'title'      => __( 'Add To Cart Action', 'divino' ),
				'type'       => 'sub-control',
				'control'    => 'ast-select',
				'linked'     => 'add_cart',
				'priority'   => 10,
				'choices'    => array_merge(
					array(
						'default'       => __( 'Default', 'divino' ),
						'slide_in_cart' => __( 'Slide In Cart', 'divino' ),
					),
					$divino_shop_page_pro_features
				),
				'responsive' => false,
				'renderAs'   => 'text',
				'transport'  => 'postMessage',
			);

				/**
				 * Total Review count option config.
				 */
				$_configs[] = array(
					'name'       => 'shop-ratings-product-archive',
					'parent'     => divino_THEME_SETTINGS . '[shop-product-structure]',
					'default'    => divino_get_option( 'shop-ratings-product-archive' ),
					'linked'     => 'ratings',
					'type'       => 'sub-control',
					'control'    => 'ast-selector',
					'section'    => 'woocommerce_product_catalog',
					'priority'   => 10,
					'title'      => __( 'Review Count', 'divino' ),
					'choices'    => array(
						'default'      => __( 'Default', 'divino' ),
						'count_string' => __( 'Count + Text', 'divino' ),
					),
					'transport'  => 'postMessage',
					'responsive' => false,
					'renderAs'   => 'text',
				);

				/**
				 * Option: Shop add to cart action notice.
				 */
				$_configs[] = array(
					'name'     => 'shop-add-to-cart-action-notice',
					'parent'   => divino_THEME_SETTINGS . '[shop-product-structure]',
					'type'     => 'sub-control',
					'control'  => 'ast-description',
					'section'  => 'woocommerce_product_catalog',
					'priority' => 10,
					'label'    => '',
					'linked'   => 'add_cart',
					'help'     => __( 'Please publish the changes and see result on the frontend.<br />[Slide in cart requires Cart added inside Header Builder]', 'divino' ),
				);

				// Learn More link if divino Pro is not activated.
				if ( divino_showcase_upgrade_notices() ) {
					$_configs[] = array(
						'name'     => divino_THEME_SETTINGS . '[ast-woo-shop-pro-items]',
						'type'     => 'control',
						'control'  => 'ast-upgrade',
						'campaign' => 'woocommerce',
						'choices'  => array(
							'two'   => array(
								'title' => __( 'More shop design layouts', 'divino' ),
							),
							'three' => array(
								'title' => __( 'Shop toolbar structure', 'divino' ),
							),
							'five'  => array(
								'title' => __( 'Offcanvas product filters', 'divino' ),
							),
							'six'   => array(
								'title' => __( 'Products quick view', 'divino' ),
							),
							'seven' => array(
								'title' => __( 'Shop pagination', 'divino' ),
							),
							'eight' => array(
								'title' => __( 'More typography options', 'divino' ),
							),
							'nine'  => array(
								'title' => __( 'More color options', 'divino' ),
							),
							'ten'   => array(
								'title' => __( 'More spacing options', 'divino' ),
							),
							'four'  => array(
								'title' => __( 'Box shadow design options', 'divino' ),
							),
							'one'   => array(
								'title' => __( 'More design controls', 'divino' ),
							),
						),
						'section'  => 'woocommerce_product_catalog',
						'default'  => '',
						'priority' => 999,
						'title'    => __( 'Optimize your WooCommerce store for maximum profit with enhanced features', 'divino' ),
						'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
						'context'  => array(),
					);
				}

				return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Woo_Shop_Layout_Configs();
