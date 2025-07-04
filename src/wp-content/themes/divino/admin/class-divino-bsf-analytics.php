<?php
/**
 * divino BSF Analytics class helps to connect BSF Analytics.
 *
 * @package divino.
 */

defined( 'ABSPATH' ) or exit;

/**
 * divino BSF Analytics class.
 *
 * @since 4.10.0
 */
class divino_BSF_Analytics {
	/**
	 * Instance object.
	 *
	 * @var self|null Class Instance.
	 */
	private static $instance = null;

	/**
	 * Class constructor.
	 *
	 * @return void
	 * @since 4.10.0
	 */
	public function __construct() {
		/*
		* BSF Analytics.
		*/
		if ( ! class_exists( 'BSF_Analytics_Loader' ) ) {
			require_once divino_THEME_DIR . 'inc/lib/bsf-analytics/class-bsf-analytics-loader.php';
		}

		add_action( 'init', array( $this, 'init_bsf_analytics' ), 5 );
		add_filter( 'bsf_core_stats', array( $this, 'add_divino_analytics_data' ) );
	}

	/**
	 * Initializes BSF Analytics.
	 *
	 * @since 4.10.0
	 * @return void
	 */
	public function init_bsf_analytics() {
		// Bail early if BSF_Analytics_Loader::get_instance is not callable and if divino white labelling is enabled.
		if ( ! is_callable( '\BSF_Analytics_Loader::get_instance' ) || divino_is_white_labelled() ) {
			return;
		}

		// Kept it for future reference.
		// add_filter(
		// 'uds_survey_allowed_screens',
		// static function ( $screens ) {
		// $screens[] = 'themes';
		// return $screens;
		// }
		// );

		$bsf_analytics = \BSF_Analytics_Loader::get_instance();
		$bsf_analytics->set_entity(
			array(
				'divino' => array(
					'product_name'        => 'divino',
					'path'                => divino_THEME_DIR . 'inc/lib/bsf-analytics',
					'author'              => 'brainstormforce',
					'time_to_display'     => '+24 hours',
					'hide_optin_checkbox' => true,

					/* Deactivation Survey */
					'deactivation_survey' => apply_filters(
						'divino_deactivation_survey_data',
						array(
							// Kept it for future reference.
							// array(
							// 'id'                => 'deactivation-survey-divino',
							// 'popup_logo'        => divino_THEME_URI . 'inc/assets/images/divino-logo.svg',
							// 'plugin_slug'       => 'divino',
							// 'popup_title'       => __( 'Quick Feedback', 'divino' ),
							// 'support_url'       => 'https://wpdivino.com/contact/',
							// 'popup_description' => __( 'If you have a moment, please share why you are deactivating divino:', 'divino' ),
							// 'show_on_screens'   => array( 'themes' ),
							// 'plugin_version'    => divino_THEME_VERSION,
							// 'popup_reasons'     => self::get_default_reasons(),
							// ),
						)
					),
				),
			)
		);
	}

	/**
	 * Get the array of default reasons.
	 *
	 * @since 4.10.0
	 * @return array Default reasons.
	 */
	public static function get_default_reasons() {
		return array(
			'temporary_deactivation' => array(
				'label'           => esc_html__( 'This is a temporary deactivation for testing.', 'divino' ),
				'placeholder'     => esc_html__( 'How can we assist you?', 'divino' ),
				'show_cta'        => 'false',
				'accept_feedback' => 'false',
			),
			'theme_not_working'      => array(
				'label'           => esc_html__( 'The theme isn\'t working properly.', 'divino' ),
				'placeholder'     => esc_html__( 'Please tell us more about what went wrong?', 'divino' ),
				'show_cta'        => 'true',
				'accept_feedback' => 'true',
			),
			'found_better_theme'     => array(
				'label'           => esc_html__( 'I found a better alternative theme.', 'divino' ),
				'placeholder'     => esc_html__( 'Could you please specify which theme?', 'divino' ),
				'show_cta'        => 'false',
				'accept_feedback' => 'true',
			),
			'missing_a_feature'      => array(
				'label'           => esc_html__( 'It\'s missing a specific feature.', 'divino' ),
				'placeholder'     => esc_html__( 'Please tell us more about the feature.', 'divino' ),
				'show_cta'        => 'false',
				'accept_feedback' => 'true',
			),
			'other'                  => array(
				'label'           => esc_html__( 'Other', 'divino' ),
				'placeholder'     => esc_html__( 'Please tell us more details.', 'divino' ),
				'show_cta'        => 'false',
				'accept_feedback' => 'true',
			),
		);
	}

	/**
	 * Callback function to add divino specific analytics data.
	 *
	 * @param array $stats_data existing stats_data.
	 *
	 * @since 4.10.0
	 * @return array
	 */
	public function add_divino_analytics_data( $stats_data ) {
		if ( ! isset( $stats_data['plugin_data']['divino'] ) ) {
			$stats_data['plugin_data']['divino'] = array();
		}

		$bsf_internal_referrer    = get_option( 'bsf_product_referers', array() );
		$admin_dashboard_settings = get_option( 'divino_admin_settings', array() );
		$is_hf_builder_active     = class_exists( 'divino_Builder_Helper' ) ? divino_Builder_Helper::$is_header_footer_builder_active : true;

		$divino_stats = array(
			'free_version'                 => divino_THEME_VERSION,
			'site_language'                => get_locale(),
			'numeric_values'               => array(),
			'boolean_values'               => array(
				'pro_active'             => defined( 'divino_EXT_VER' ),
				'divino_sites_active'     => is_plugin_active( 'divino-sites/divino-sites.php' ),
				'divino_pro_sites_active' => is_plugin_active( 'divino-pro-sites/divino-pro-sites.php' ),
				'is_using_dark_palette'  => divino_Global_Palette::is_dark_palette(),
			),
			'internal_referrer'            => empty( $bsf_internal_referrer['divino'] ) ? '' : $bsf_internal_referrer['divino'],
			'using_old_header_footer'      => $is_hf_builder_active ? 'no' : 'yes',
			'loading_google_fonts_locally' => isset( $admin_dashboard_settings['self_hosted_gfonts'] ) && $admin_dashboard_settings['self_hosted_gfonts'] ? 'yes' : 'no',
			'preloading_local_fonts'       => isset( $admin_dashboard_settings['preload_local_fonts'] ) && $admin_dashboard_settings['preload_local_fonts'] ? 'yes' : 'no',
		);

		$stats_data['plugin_data']['divino'] = array_merge_recursive( $stats_data['plugin_data']['divino'], $divino_stats );

		return $stats_data;
	}

	/**
	 * Initiator.
	 *
	 * @since 4.10.0
	 * @return self initialized object of class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

/**
 * Initiates the divino_BSF_Analytics class instance.
 */
divino_BSF_Analytics::get_instance();
