<?php
/**
 * divino Extended Configuration.
 *
 * @package divino
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class divino_Extended_Base_Configuration.
 */
final class divino_Extended_Base_Configuration {
	/**
	 * Member Variable
	 *
	 * @var mixed instance
	 */
	private static $instance = null;

	/**
	 *  Initiator
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
	}

	/**
	 * Prepare Advance header configuration.
	 *
	 * @param string $section_id Section ID.
	 * @param string $heading_class Optional. Heading class. Defaults to 'ast-top-section-divider'.
	 * @return array
	 */
	public static function prepare_advanced_tab( $section_id, $heading_class = 'ast-top-section-divider' ) {

		return array(

			/**
			 * Option: Divider
			 */
			array(
				'name'     => divino_THEME_SETTINGS . '[' . $section_id . '-divider]',
				'section'  => $section_id,
				'title'    => __( 'Spacing', 'divino' ),
				'type'     => 'control',
				'control'  => 'ast-heading',
				'priority' => 210,
				'settings' => array(),
				'context'  => divino_Builder_Helper::$design_tab,
				'divider'  => array( 'ast_class' => $heading_class ),
			),

			/**
			 * Option: Padded Layout Custom Width
			 */
			array(
				'name'              => divino_THEME_SETTINGS . '[' . $section_id . '-padding]',
				'default'           => divino_get_option( $section_id . '-padding' ),
				'type'              => 'control',
				'transport'         => 'postMessage',
				'control'           => 'ast-responsive-spacing',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
				'section'           => $section_id,
				'priority'          => 210,
				'title'             => __( 'Padding', 'divino' ),
				'linked_choices'    => true,
				'unit_choices'      => array( 'px', 'em', '%' ),
				'choices'           => array(
					'top'    => __( 'Top', 'divino' ),
					'right'  => __( 'Right', 'divino' ),
					'bottom' => __( 'Bottom', 'divino' ),
					'left'   => __( 'Left', 'divino' ),
				),
				'context'           => divino_Builder_Helper::$design_tab,
				'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
			),

			/**
			 * Option: Padded Layout Custom Width
			 */
			array(
				'name'              => divino_THEME_SETTINGS . '[' . $section_id . '-margin]',
				'default'           => divino_get_option( $section_id . '-margin' ),
				'type'              => 'control',
				'transport'         => 'postMessage',
				'control'           => 'ast-responsive-spacing',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
				'section'           => $section_id,
				'priority'          => 220,
				'title'             => __( 'Margin', 'divino' ),
				'linked_choices'    => true,
				'unit_choices'      => array( 'px', 'em', '%' ),
				'choices'           => array(
					'top'    => __( 'Top', 'divino' ),
					'right'  => __( 'Right', 'divino' ),
					'bottom' => __( 'Bottom', 'divino' ),
					'left'   => __( 'Left', 'divino' ),
				),
				'context'           => divino_Builder_Helper::$design_tab,
				'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
			),
		);
	}

	/**
	 * Prepare Spacing & Border options.
	 *
	 * @param string $section_id section id.
	 * @param bool   $skip_border_divider Skip border control divider or not.
	 *
	 * @since 4.6.0
	 * @return array
	 */
	public static function prepare_section_spacing_border_options( $section_id, $skip_border_divider = false ) {
		$_configs        = array(
			array(
				'name'      => divino_THEME_SETTINGS . '[' . $section_id . '-border-group]',
				'default'   => divino_get_option( $section_id . '-border-group' ),
				'type'      => 'control',
				'control'   => 'ast-settings-group',
				'title'     => __( 'Border', 'divino' ),
				'section'   => $section_id,
				'transport' => 'postMessage',
				'priority'  => 150,
				'divider'   => true === $skip_border_divider ? array( 'ast_class' => 'ast-top-section-spacing' ) : array( 'ast_class' => 'ast-top-divider' ),
				'context'   => divino_Builder_Helper::$design_tab,
			),
			array(
				'name'           => $section_id . '-border-width',
				'default'        => divino_get_option( $section_id . '-border-width' ),
				'parent'         => divino_THEME_SETTINGS . '[' . $section_id . '-border-group]',
				'type'           => 'sub-control',
				'transport'      => 'postMessage',
				'control'        => 'ast-border',
				'title'          => __( 'Border Width', 'divino' ),
				'divider'        => array( 'ast_class' => 'ast-bottom-divider' ),
				'section'        => $section_id,
				'linked_choices' => true,
				'priority'       => 1,
				'choices'        => array(
					'top'    => __( 'Top', 'divino' ),
					'right'  => __( 'Right', 'divino' ),
					'bottom' => __( 'Bottom', 'divino' ),
					'left'   => __( 'Left', 'divino' ),
				),
			),
			array(
				'name'              => $section_id . '-border-color',
				'default'           => divino_get_option( $section_id . '-border-color' ),
				'type'              => 'sub-control',
				'priority'          => 1,
				'parent'            => divino_THEME_SETTINGS . '[' . $section_id . '-border-group]',
				'section'           => $section_id,
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'divino_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'transport'         => 'postMessage',
				'title'             => __( 'Color', 'divino' ),
				'divider'           => array( 'ast_class' => 'ast-top-spacing ast-bottom-spacing' ),
			),
			array(
				'name'           => $section_id . '-border-radius',
				'default'        => divino_get_option( $section_id . '-border-radius' ),
				'parent'         => divino_THEME_SETTINGS . '[' . $section_id . '-border-group]',
				'type'           => 'sub-control',
				'transport'      => 'postMessage',
				'control'        => 'ast-border',
				'title'          => __( 'Border Radius', 'divino' ),
				'divider'        => array( 'ast_class' => 'ast-top-divider' ),
				'section'        => $section_id,
				'linked_choices' => true,
				'priority'       => 1,
				'choices'        => array(
					'top'    => __( 'Top', 'divino' ),
					'right'  => __( 'Right', 'divino' ),
					'bottom' => __( 'Bottom', 'divino' ),
					'left'   => __( 'Left', 'divino' ),
				),
			),
		);
		$spacing_configs = self::prepare_advanced_tab( $section_id );
		return array_merge( $_configs, $spacing_configs );
	}
}

/**
 *  Prepare if class 'divino_Extended_Base_Configuration' exist.
 *  Kicking this off by calling 'get_instance()' method
 */
divino_Extended_Base_Configuration::get_instance();
