<?php
/**
 * Easy Digital Downloads Options for divino Theme.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.5.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'divino_Edd_Archive_Layout_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class divino_Edd_Archive_Layout_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register divino-Easy Digital Downloads Shop Layout Customizer Configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.5.5
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$grid_ast_divider = defined( 'divino_EXT_VER' ) && divino_Ext_Extension::is_active( 'edd' ) ? array() : array( 'ast_class' => 'ast-top-section-divider' );
			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

			$_configs = array(

				/**
				 * Option: Shop Columns
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[edd-archive-grids]',
					'type'              => 'control',
					'control'           => 'ast-responsive-slider',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'section'           => 'section-edd-archive',
					'default'           => divino_get_option(
						'edd-archive-grids',
						array(
							'desktop' => 4,
							'tablet'  => 3,
							'mobile'  => 2,
						)
					),
					'priority'          => 10,
					'title'             => __( 'Archive Columns', 'divino' ),
					'input_attrs'       => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 6,
					),
					'divider'           => $grid_ast_divider,
					'transport'         => 'postMessage',
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[edd-archive-product-structure-divider]',
					'section'  => 'section-edd-archive',
					'title'    => __( 'Product Structure', 'divino' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 30,
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: EDD Archive Post Meta
				 */
				array(
					'name'              => divino_THEME_SETTINGS . '[edd-archive-product-structure]',
					'type'              => 'control',
					'control'           => 'ast-sortable',
					'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_multi_choices' ),
					'section'           => 'section-edd-archive',
					'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
					'default'           => divino_get_option( 'edd-archive-product-structure' ),
					'priority'          => 30,
					'title'             => __( 'Product Structure', 'divino' ),
					'description'       => __( 'The Image option cannot be sortable if the Product Style is selected to the List Style ', 'divino' ),
					'choices'           => array(
						'image'      => __( 'Image', 'divino' ),
						'category'   => __( 'Category', 'divino' ),
						'title'      => __( 'Title', 'divino' ),
						'price'      => __( 'Price', 'divino' ),
						'short_desc' => __( 'Short Description', 'divino' ),
						'add_cart'   => __( 'Add To Cart', 'divino' ),
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[edd-archive-button-divider]',
					'section'  => 'section-edd-archive',
					'title'    => __( 'Buttons', 'divino' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 31,
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-section-spacing ast-bottom-spacing' ),
				),

				/**
				 * Option: Add to Cart button text
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[edd-archive-add-to-cart-button-text]',
					'type'     => 'control',
					'control'  => 'text',
					'section'  => 'section-edd-archive',
					'default'  => divino_get_option( 'edd-archive-add-to-cart-button-text' ),
					'priority' => 31,
					'title'    => __( 'Cart Button Text', 'divino' ),
					'context'  => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[edd-archive-product-structure]',
							'operator' => 'contains',
							'value'    => 'add_cart',
						),
					),
					'divider'  => array( 'ast_class' => 'ast-top-spacing ast-bottom-section-divider' ),
				),

				/**
				 * Option: Variable product button
				 */

				array(
					'name'       => divino_THEME_SETTINGS . '[edd-archive-variable-button]',
					'default'    => divino_get_option( 'edd-archive-variable-button' ),
					'section'    => 'section-edd-archive',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'title'      => __( 'Variable Product Button', 'divino' ),
					'priority'   => 31,
					'choices'    => array(
						'button'  => __( 'Button', 'divino' ),
						'options' => __( 'Options', 'divino' ),
					),
					'transport'  => 'refresh',
					'renderAs'   => 'text',
					'responsive' => false,
					'context'    => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[edd-archive-product-structure]',
							'operator' => 'contains',
							'value'    => 'add_cart',
						),
					),
					'divider'    => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Variable product button text
				 */
				array(
					'name'     => divino_THEME_SETTINGS . '[edd-archive-variable-button-text]',
					'type'     => 'control',
					'control'  => 'text',
					'divider'  => array( 'ast_class' => 'ast-bottom-divider' ),
					'section'  => 'section-edd-archive',
					'default'  => divino_get_option( 'edd-archive-variable-button-text' ),
					'context'  => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[edd-archive-variable-button]',
							'operator' => '==',
							'value'    => 'button',
						),
					),
					'priority' => 31,
					'title'    => __( 'Variable Product Button Text', 'divino' ),
				),

				/**
				 * Option: Archive Content Width
				 */
				array(
					'name'       => divino_THEME_SETTINGS . '[edd-archive-width]',
					'default'    => divino_get_option( 'edd-archive-width' ),
					'section'    => 'section-edd-archive',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'title'      => __( 'Archive Content Width', 'divino' ),
					'divider'    => array( 'ast_class' => 'ast-top-section-divider' ),
					'priority'   => 220,
					'choices'    => array(
						'default' => __( 'Default', 'divino' ),
						'custom'  => __( 'Custom', 'divino' ),
					),
					'transport'  => 'postMessage',
					'renderAs'   => 'text',
					'responsive' => false,
				),

				/**
				 * Option: Enter Width
				 */
				array(
					'name'        => divino_THEME_SETTINGS . '[edd-archive-max-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => 'section-edd-archive',
					'default'     => divino_get_option( 'edd-archive-max-width' ),
					'priority'    => 225,
					'context'     => array(
						divino_Builder_Helper::$general_tab_config,
						array(
							'setting'  => divino_THEME_SETTINGS . '[edd-archive-width]',
							'operator' => '===',
							'value'    => 'custom',
						),
					),

					'title'       => __( 'Custom Width', 'divino' ),
					'transport'   => 'postMessage',
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 768,
						'step' => 1,
						'max'  => 1920,
					),
					'divider'     => array( 'ast_class' => 'ast-top-divider' ),
				),
			);

			// Learn More link if divino Pro is not activated.
			if ( divino_showcase_upgrade_notices() ) {

				$_configs[] =

					/**
					 * Option: Learn More about Contant Typography
					 */
					array(
						'name'     => divino_THEME_SETTINGS . '[edd-product-archive-button-link]',
						'type'     => 'control',
						'control'  => 'ast-button-link',
						'section'  => 'section-edd-archive',
						'priority' => 999,
						'title'    => __( 'View divino Pro Features', 'divino' ),
						'url'      => divino_get_pro_url( '/pricing/', 'free-theme', 'customizer', 'edd' ),
						'settings' => array(),
						'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
					);

			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Edd_Archive_Layout_Configs();
