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

if ( ! class_exists( 'divino_Woo_Shop_Single_Layout_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Woo_Shop_Single_Layout_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register divino-WooCommerce Shop Single Layout Customizer Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$product_divider_title = divino_has_pro_woocommerce_addon() ? __( 'Product Structure Options', 'divino' ) : __( 'Product Options', 'divino' );

			$clonning_attr    = array();
			$add_to_cart_attr = array();

			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( divino_has_pro_woocommerce_addon() ) {

				/**
				 * Single product extras control.
				 */
				$clonning_attr['summary-extras'] = array(
					'clone'       => false,
					'is_parent'   => true,
					'main_index'  => 'summary-extras',
					'clone_limit' => 2,
					'title'       => __( 'Extras', 'divino' ),
				);

			}

			/**
			 * Single product add to cart control.
			 */
			$add_to_cart_attr['add_cart'] = array(
				'clone'       => false,
				'is_parent'   => true,
				'main_index'  => 'add_cart',
				'clone_limit' => 2,
				'title'       => __( 'Add To Cart', 'divino' ),
			);

			/**
			 * Single product payment control.
			 */

			$clonning_attr['single-product-payments'] = array(
				'clone'       => false,
				'is_parent'   => true,
				'main_index'  => 'single-product-payments',
				'clone_limit' => 2,
				'title'       => __( 'Payments', 'divino' ),
			);

			$_configs = array(

				array(
					'name'        => 'section-woo-shop-single-ast-context-tabs',
					'section'     => 'section-woo-shop-single',
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
				),

				/**
				 * Option: Divider.
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[woo-single-product-structure-divider]',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Single Product Structure', 'divino' ),
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
					'name'              => divino_THEME_SETTINGS . '[single-product-structure]',
					'default'           => divino_get_option( 'single-product-structure' ),
					'type'              => 'control',
					'control'           => 'ast-sortable',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_multi_choices' ),
					'section'           => 'section-woo-shop-single',
					'priority'          => 15,
					'choices'           => array_merge(
						array(
							'title'   => __( 'Title', 'divino' ),
							'price'   => __( 'Price', 'divino' ),
							'ratings' => __( 'Ratings', 'divino' ),
						),
						$add_to_cart_attr,
						array(
							'short_desc' => __( 'Short Description', 'divino' ),
							'meta'       => __( 'Meta', 'divino' ),
							'category'   => __( 'Category', 'divino' ),
						),
						$clonning_attr
					),
				),

				/**
				 * Option: Divider.
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[woo-single-product-structure-fields-divider]',
					'section'  => 'section-woo-shop-single',
					'title'    => $product_divider_title,
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 16,
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Disable Breadcrumb
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[single-product-breadcrumb-disable]',
					'section'  => 'section-woo-shop-single',
					'type'     => 'control',
					'control'  => 'ast-toggle-control',
					'default'  => divino_get_option( 'single-product-breadcrumb-disable' ),
					'title'    => __( 'Enable Breadcrumb', 'divino' ),
					'priority' => 16,
				),

				/**
				 * Option: Enable free shipping
				 */
				array(
					'name'        => divino_THEME_SETTINGS . '[single-product-enable-shipping]',
					'default'     => divino_get_option( 'single-product-enable-shipping' ),
					'type'        => 'control',
					'section'     => 'section-woo-shop-single',
					'title'       => __( 'Enable Shipping Text', 'divino' ),
					'description' => __( 'Adds shipping text next to the product price.', 'divino' ),
					'control'     => 'ast-toggle-control',
					'priority'    => 16,
				),

				/**
				 * Option: Single page variation tab layout.
				 */
				array(
					'name'        => divino_THEME_SETTINGS . '[single-product-variation-tabs-layout]',
					'default'     => divino_get_option( 'single-product-variation-tabs-layout' ),
					'type'        => 'control',
					'section'     => 'section-woo-shop-single',
					'title'       => __( 'Product Variation Layout', 'divino' ),
					'description' => __( 'Changes single product variation layout to be displayed inline or stacked.', 'divino' ),
					'context'     => array(
						divino_Builder_Helper::$general_tab_config,
					),
					'control'     => 'ast-selector',
					'priority'    => 17,
					'choices'     => array(
						'horizontal' => __( 'Inline', 'divino' ),
						'vertical'   => __( 'Stack', 'divino' ),
					),
					'renderAs'    => 'text',
					'responsive'  => false,
				),

				/**
				 * Option: Disable Transparent Header on WooCommerce Product pages
				 */
				array(
					'name'     => 'transparent-header-disable-woo-products',
					'parent'   => divino_THEME_SETTINGS . '[transparent-header-disable-on]',
					'default'  => divino_get_option( 'transparent-header-disable-woo-products' ),
					'type'     => 'sub-control',
					'section'  => 'section-transparent-header',
					'title'    => __( 'WooCommerce Product Pages', 'divino' ),
					'priority' => 26,
					'control'  => 'ast-toggle-control',
				),

				/**
				 * Option: Free shipping text
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[single-product-shipping-text]',
					'default'  => divino_get_option( 'single-product-shipping-text' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Shipping Text', 'divino' ),
					'context'  => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[single-product-enable-shipping]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'control'  => 'text',
					'priority' => 16,
					'divider'  => array( 'ast_class' => 'ast-bottom-spacing' ),
				),

				/**
				 * Option: Divider.
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[single-product-sticky-add-to-cart-divider]',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Sticky Add To Cart', 'divino' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 76,
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Sticky add to cart.
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[single-product-sticky-add-to-cart]',
					'default'  => divino_get_option( 'single-product-sticky-add-to-cart' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Enable Sticky Add to Cart', 'divino' ),
					'control'  => 'ast-toggle-control',
					'priority' => 76,
				),

				/**
				 * Option: Sticky add to cart position.
				 */
				array(
					'name'       => divino_THEME_SETTINGS . '[single-product-sticky-add-to-cart-position]',
					'default'    => divino_get_option( 'single-product-sticky-add-to-cart-position' ),
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'section-woo-shop-single',
					'priority'   => 76,
					'title'      => __( 'Sticky Placement ', 'divino' ),
					'choices'    => array(
						'top'    => __( 'Top', 'divino' ),
						'bottom' => __( 'Bottom', 'divino' ),
					),
					'transport'  => 'postMessage',
					'renderAs'   => 'text',
					'responsive' => false,
					'context'    => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[single-product-sticky-add-to-cart]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'divider'    => array( 'ast_class' => 'ast-top-divider' ),
				),

				/**
				 * Option: Divider.
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[woo-single-product-sticky-color-divider]',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Sticky Add To Cart Colors', 'divino' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 82,
					'settings' => array(),
					'context'  => array(
						divino_Builder_Helper::$design_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[single-product-sticky-add-to-cart]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: Sticky add to cart text color.
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[single-product-sticky-add-to-cart-text-color]',
					'default'           => divino_get_option( 'single-product-sticky-add-to-cart-text-color' ),
					'type'              => 'control',
					'section'           => 'section-woo-shop-single',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'transport'         => 'postMessage',
					'title'             => __( 'Text Color', 'divino' ),
					'context'           => array(
						divino_Builder_Helper::$design_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[single-product-sticky-add-to-cart]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'priority'          => 82,
					'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: Sticky add to cart background color.
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[single-product-sticky-add-to-cart-bg-color]',
					'default'           => divino_get_option( 'single-product-sticky-add-to-cart-bg-color' ),
					'type'              => 'control',
					'section'           => 'section-woo-shop-single',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'transport'         => 'postMessage',
					'title'             => __( 'Background Color', 'divino' ),
					'context'           => array(
						divino_Builder_Helper::$design_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[single-product-sticky-add-to-cart]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'priority'          => 82,
				),

				/**
				 * Option: Sticky add to cart button text color.
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[single-product-sticky-add-to-cart-btn-color]',
					'default'   => divino_get_option( 'single-product-sticky-add-to-cart-btn-color' ),
					'type'      => 'control',
					'control'   => 'ast-color-group',
					'title'     => __( 'Button Text', 'divino' ),
					'section'   => 'section-woo-shop-single',
					'transport' => 'postMessage',
					'priority'  => 82,
					'context'   => array(
						divino_Builder_Helper::$design_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[single-product-sticky-add-to-cart]',
							'operator' => '==',
							'value'    => true,
						),
					),
				),

				/**
				 * Option: Link Color.
				 */
				array(
					'type'     => 'sub-control',
					'priority' => 76,
					'parent'   => divino_THEME_SETTINGS . '[single-product-sticky-add-to-cart-btn-color]',
					'section'  => 'section-woo-shop-single',
					'control'  => 'ast-color',
					'default'  => divino_get_option( 'single-product-sticky-add-to-cart-btn-n-color' ),
					'name'     => 'single-product-sticky-add-to-cart-btn-n-color',
					'title'    => __( 'Normal', 'divino' ),
					'tab'      => __( 'Normal', 'divino' ),
				),

				/**
				 * Option: Link Hover Color.
				 */
				array(
					'type'              => 'sub-control',
					'priority'          => 82,
					'parent'            => divino_THEME_SETTINGS . '[single-product-sticky-add-to-cart-btn-color]',
					'section'           => 'section-woo-shop-single',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'default'           => divino_get_option( 'single-product-sticky-add-to-cart-btn-h-color' ),
					'transport'         => 'postMessage',
					'name'              => 'single-product-sticky-add-to-cart-btn-h-color',
					'title'             => __( 'Hover', 'divino' ),
					'tab'               => __( 'Hover', 'divino' ),
				),

				/**
				 * Option: Sticky add to cart button background color.
				 */
				array(
					'name'      => divino_THEME_SETTINGS . '[single-product-sticky-add-to-cart-btn-bg-color]',
					'default'   => divino_get_option( 'single-product-sticky-add-to-cart-btn-bg-color' ),
					'type'      => 'control',
					'control'   => 'ast-color-group',
					'title'     => __( 'Button Background', 'divino' ),
					'section'   => 'section-woo-shop-single',
					'transport' => 'postMessage',
					'priority'  => 82,
					'context'   => array(
						divino_Builder_Helper::$design_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[single-product-sticky-add-to-cart]',
							'operator' => '==',
							'value'    => true,
						),
					),
				),

				/**
				 * Option: Link Color.
				 */
				array(
					'type'     => 'sub-control',
					'priority' => 82,
					'parent'   => divino_THEME_SETTINGS . '[single-product-sticky-add-to-cart-btn-bg-color]',
					'section'  => 'section-woo-shop-single',
					'control'  => 'ast-color',
					'default'  => divino_get_option( 'single-product-sticky-add-to-cart-btn-bg-n-color' ),
					'name'     => 'single-product-sticky-add-to-cart-btn-bg-n-color',
					'title'    => __( 'Normal', 'divino' ),
					'tab'      => __( 'Normal', 'divino' ),
				),

				/**
				 * Option: Link Hover Color.
				 */
				array(
					'type'              => 'sub-control',
					'priority'          => 82,
					'parent'            => divino_THEME_SETTINGS . '[single-product-sticky-add-to-cart-btn-bg-color]',
					'section'           => 'section-woo-shop-single',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'default'           => divino_get_option( 'single-product-sticky-add-to-cart-btn-bg-h-color' ),
					'transport'         => 'postMessage',
					'name'              => 'single-product-sticky-add-to-cart-btn-bg-h-color',
					'title'             => __( 'Hover', 'divino' ),
					'tab'               => __( 'Hover', 'divino' ),
				),

				/**
				 * Single product payment icon color style.
				 */
				array(
					'name'       => 'single-product-payment-icon-color',
					'parent'     => divino_THEME_SETTINGS . '[single-product-structure]',
					'default'    => divino_get_option( 'single-product-payment-icon-color' ),
					'linked'     => 'single-product-payments',
					'type'       => 'sub-control',
					'control'    => 'ast-selector',
					'section'    => 'section-woo-shop-single',
					'priority'   => 5,
					'title'      => __( 'Choose Icon Colors', 'divino' ),
					'choices'    => array(
						'inherit'            => __( 'Default', 'divino' ),
						'inherit_text_color' => __( 'Grayscale', 'divino' ),
					),
					'transport'  => 'postMessage',
					'responsive' => false,
					'renderAs'   => 'text',
				),

				/**
				 * Single product payment heading text.
				 */
				array(
					'name'      => 'single-product-payment-text',
					'parent'    => divino_THEME_SETTINGS . '[single-product-structure]',
					'default'   => divino_get_option( 'single-product-payment-text' ),
					'linked'    => 'single-product-payments',
					'type'      => 'sub-control',
					'control'   => 'ast-text-input',
					'section'   => 'section-woo-shop-single',
					'priority'  => 5,
					'transport' => 'postMessage',
					'title'     => __( 'Payment Title', 'divino' ),
					'settings'  => array(),
				),

			);

			/**
			 * Single product extras list.
			 */
			$_configs[] = array(
				'name'        => 'single-product-payment-list',
				'parent'      => divino_THEME_SETTINGS . '[single-product-structure]',
				'default'     => divino_get_option( 'single-product-payment-list' ),
				'linked'      => 'single-product-payments',
				'type'        => 'sub-control',
				'control'     => 'ast-list-icons',
				'section'     => 'section-woo-shop-single',
				'priority'    => 10,
				'divider'     => array( 'ast_class' => 'ast-bottom-divider' ),
				'disable'     => false,
				'input_attrs' => array(
					'text_control_label'       => __( 'Payment Title', 'divino' ),
					'text_control_placeholder' => __( 'Add payment title', 'divino' ),
				),
			);

			/**
			 * Option: Button width option
			 */
			$_configs[] = array(
				'name'        => 'single-product-cart-button-width',
				'parent'      => divino_THEME_SETTINGS . '[single-product-structure]',
				'default'     => divino_get_option( 'single-product-cart-button-width' ),
				'linked'      => 'add_cart',
				'type'        => 'sub-control',
				'control'     => 'ast-responsive-slider',
				'responsive'  => true,
				'section'     => 'section-woo-shop-single',
				'priority'    => 11,
				'title'       => __( 'Button Width', 'divino' ),
				'transport'   => 'postMessage',
				'suffix'      => '%',
				'input_attrs' => array(
					'min'  => 1,
					'step' => 1,
					'max'  => 100,
				),
			);

			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( divino_has_pro_woocommerce_addon() ) {
				/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				$_configs[] = array(
					'name'        => 'single-product-cart-button-width',
					'parent'      => divino_THEME_SETTINGS . '[single-product-structure]',
					'default'     => divino_get_option( 'single-product-cart-button-width' ),
					'linked'      => 'add_cart',
					'type'        => 'sub-control',
					'control'     => 'ast-responsive-slider',
					'responsive'  => true,
					'section'     => 'section-woo-shop-single',
					'priority'    => 11,
					'title'       => __( 'Button Width', 'divino' ),
					'transport'   => 'postMessage',
					'suffix'      => '%',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 100,
					),
				);

			} else {
				$_configs[] = array(
					'name'        => divino_THEME_SETTINGS . '[single-product-cart-button-width]',
					'default'     => divino_get_option( 'single-product-cart-button-width' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'responsive'  => true,
					'control'     => 'ast-responsive-slider',
					'section'     => 'section-woo-shop-single',
					'title'       => __( 'Button Width', 'divino' ),
					'suffix'      => '%',
					'priority'    => 16,
					'input_attrs' => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 100,
					),
					'divider'     => array( 'ast_class' => 'ast-top-section-divider ast-bottom-section-divider' ),
				);
			}

			if ( ! defined( 'divino_EXT_VER' ) ) {
				$_configs[] = array(
					'name'     => divino_THEME_SETTINGS . '[sticky-add-to-cart-notice]',
					'type'     => 'control',
					'control'  => 'ast-description',
					'section'  => 'section-woo-shop-single',
					'priority' => 5,
					'label'    => '',
					'help'     => __( 'Note: To get design settings make sure to enable sticky add to cart.', 'divino' ),
					'context'  => array(
						divino_Builder_Helper::$design_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[single-product-sticky-add-to-cart]',
							'operator' => '==',
							'value'    => false,
						),
					),
				);

				if ( divino_showcase_upgrade_notices() ) {
					// Learn More link if divino Pro is not activated.
					$_configs[] = array(
						'name'     => divino_THEME_SETTINGS . '[ast-woo-single-product-pro-items]',
						'type'     => 'control',
						'control'  => 'ast-upgrade',
						'campaign' => 'woocommerce',
						'choices'  => array(
							'two'   => array(
								'title' => __( 'More product galleries', 'divino' ),
							),
							'three' => array(
								'title' => __( 'Sticky product summary', 'divino' ),
							),
							'five'  => array(
								'title' => __( 'Product description layouts', 'divino' ),
							),
							'six'   => array(
								'title' => __( 'Related, Upsell product controls', 'divino' ),
							),
							'seven' => array(
								'title' => __( 'Extras option for product structure', 'divino' ),
							),
							'eight' => array(
								'title' => __( 'More typography options', 'divino' ),
							),
							'nine'  => array(
								'title' => __( 'More color options', 'divino' ),
							),
							'one'   => array(
								'title' => __( 'More design controls', 'divino' ),
							),
						),
						'section'  => 'section-woo-shop-single',
						'default'  => '',
						'priority' => 999,
						'title'    => __( 'Extra conversion options for store product pages means extra profit!', 'divino' ),
						'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
						'context'  => array(),
					);
				}
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Woo_Shop_Single_Layout_Configs();
