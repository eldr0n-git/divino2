<?php
/**
 * divino Builder Admin Loader.
 *
 * @package divino-builder
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class divino_Builder_Admin.
 */
final class divino_Builder_Admin {
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
		add_action( 'wp_ajax_ast-migrate-to-builder', array( $this, 'migrate_to_builder' ) );
		add_action( 'wp_ajax_ast-disable-pro-notices', array( $this, 'disable_divino_pro_notices' ) );
	}

	/**
	 * Update Customizer Header Footer quick links from options page.
	 *
	 * @since 3.0.0
	 * @param array $args default Header Footer quick links.
	 * @return array updated Header Footer quick links.
	 */
	public function update_customizer_header_footer_link( $args ) {
		if ( isset( $args['header']['quick_url'] ) ) {
			$args['header']['quick_url'] = admin_url( 'customize.php?autofocus[panel]=panel-header-builder-group' );
		}
		if ( isset( $args['footer']['quick_url'] ) ) {
			$args['footer']['quick_url'] = admin_url( 'customize.php?autofocus[panel]=panel-footer-builder-group' );
		}
		return $args;
	}

	/**
	 * Migrate to New Header Builder
	 */
	public function migrate_to_builder() {

		check_ajax_referer( 'divino-builder-module-nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You don\'t have the access', 'divino' ) );
		}

		/** @psalm-suppress PossiblyInvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$migrate = isset( $_POST['value'] ) ? sanitize_key( $_POST['value'] ) : '';
		/** @psalm-suppress PossiblyInvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$migrate = $migrate ? true : false;
		/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$migration_flag = divino_get_option( 'v3-option-migration', false );
		divino_update_option( 'is-header-footer-builder', $migrate );
		if ( $migrate && false === $migration_flag ) {
			require_once divino_THEME_DIR . 'inc/theme-update/divino-builder-migration-updater.php';  // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			divino_header_builder_migration();
		}
		wp_send_json_success();
	}

	/**
	 * Disable pro upgrade notice from all over in divino.
	 *
	 * @since 3.9.4
	 */
	public function disable_divino_pro_notices() {

		check_ajax_referer( 'divino-upgrade-notices-nonce', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You don\'t have the access', 'divino' ) );
		}

		/** @psalm-suppress PossiblyInvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$migrate = isset( $_POST['value'] ) ? sanitize_key( $_POST['value'] ) : '';
		/** @psalm-suppress PossiblyInvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$migrate = $migrate ? true : false;
		divino_update_option( 'ast-disable-upgrade-notices', $migrate );

		wp_send_json_success();
	}
}

/**
 *  Prepare if class 'divino_Builder_Admin' exist.
 *  Kicking this off by calling 'get_instance()' method
 */
divino_Builder_Admin::get_instance();
