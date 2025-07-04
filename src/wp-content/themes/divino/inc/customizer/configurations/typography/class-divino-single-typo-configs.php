<?php
/**
 * Styling Options for divino Theme.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.0.15
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'divino_Single_Typo_Configs' ) ) {

	/**
	 * Customizer Single Typography Configurations.
	 *
	 * @since 1.4.3
	 */
	class divino_Single_Typo_Configs extends divino_Customizer_Config_Base {
		/**
		 * Register Single Typography configurations.
		 *
		 * @param Array                $configurations divino Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array divino Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array();

			// Learn More link if divino Pro is not activated.
			if ( divino_showcase_upgrade_notices() ) {

				$_configs = array(

					/**
					 * Option: divino Pro blog single post's options.
					 */
					array(
						'name'     => divino_THEME_SETTINGS . '[ast-single-post-items]',
						'type'     => 'control',
						'control'  => 'ast-upgrade',
						'campaign' => 'blog-single',
						'choices'  => array(
							'one'   => array(
								'title' => __( 'Author Box with Social Share', 'divino' ),
							),
							'two'   => array(
								'title' => __( 'Auto load previous posts', 'divino' ),
							),
							'three' => array(
								'title' => __( 'Single post navigation control', 'divino' ),
							),
							'four'  => array(
								'title' => __( 'Custom featured images size', 'divino' ),
							),
							'seven' => array(
								'title' => __( 'Single post read time', 'divino' ),
							),
							'five'  => array(
								'title' => __( 'Extended typography options', 'divino' ),
							),
							'six'   => array(
								'title' => __( 'Extended spacing options', 'divino' ),
							),
							'eight' => array(
								'title' => __( 'Social sharing options', 'divino' ),
							),
						),
						'section'  => 'section-blog-single',
						'default'  => '',
						'priority' => 999,
						'context'  => array(),
						'title'    => __( 'Extensive range of tools to help blog pages stand out.', 'divino' ),
						'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
					),
				);
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new divino_Single_Typo_Configs();
