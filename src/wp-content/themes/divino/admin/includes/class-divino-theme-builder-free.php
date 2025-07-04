<?php
/**
 * Site Builder Free Version Preview.
 *
 * @package divino
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'divino_Theme_Builder_Free' ) ) {

	define( 'divino_THEME_BUILDER_FREE_DIR', divino_THEME_DIR . 'admin/assets/theme-builder/' );
	define( 'divino_THEME_BUILDER_FREE_URI', divino_THEME_URI . 'admin/assets/theme-builder/' );

	/**
	 * Site Builder initial setup.
	 *
	 * @since 4.5.0
	 */
	class divino_Theme_Builder_Free {
		/**
		 * Member Variable
		 *
		 * @var null $instance
		 */
		private static $instance;

		/**
		 *  Initiator
		 *
		 * @since 4.5.0
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				/** @psalm-suppress InvalidPropertyAssignmentValue */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				self::$instance = new self();
				/** @psalm-suppress InvalidPropertyAssignmentValue */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			}
			return self::$instance;
		}

		/**
		 *  Constructor
		 *
		 * @since 4.5.0
		 * @return void
		 */
		public function __construct() {
			$is_divino_addon_active = defined( 'divino_EXT_VER' );
			if ( ! $is_divino_addon_active ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'theme_builder_admin_enqueue_scripts' ) );
				add_action( 'admin_body_class', array( $this, 'admin_body_class' ) );
				add_action( 'admin_menu', array( $this, 'setup_menu' ) );
				add_action( 'admin_init', array( $this, 'divino_theme_builder_disable_notices' ) );
			}
			add_action( 'admin_page_access_denied', array( $this, 'divino_theme_builder_access_denied_redirect' ) );
		}

		/**
		 *  Enqueue scripts and styles.
		 *
		 * @since 4.5.0
		 * @return void
		 */
		public function theme_builder_admin_enqueue_scripts() {
			$file_prefix = '';
			if ( is_rtl() ) {
				$file_prefix .= '.rtl';
			}

			wp_enqueue_style( 'divino-theme-builder-style', divino_THEME_BUILDER_FREE_URI . 'build/index' . $file_prefix . '.css', array(), divino_THEME_VERSION );

			wp_enqueue_script( 'divino-theme-builder-script', divino_THEME_BUILDER_FREE_URI . 'build/index.js', array( 'wp-element' ), divino_THEME_VERSION, true );

			wp_enqueue_style( 'dashicons' );

			$divino_addon_locale = divino_THEME_ORG_VERSION ? 'divino-addon/divino-addon.php' : 'divino-pro/divino-pro.php';

			$localized_data = array(
				'title'                      => esc_html__( 'Site Builder', 'divino' ),
				'rest_url'                   => '/wp-json/divino-addon/v1/custom-layouts/',
				'new_custom_layout_base_url' => admin_url( 'post-new.php?post_type=divino-advanced-hook' ),
				'divino_pricing_page_url'     => divino_get_pro_url( '/pricing/', 'free-theme', 'site-builder', 'upgrade' ),
				'divino_docs_page_url'        => divino_get_pro_url( '/docs/custom-layouts-pro/', 'free-theme', 'site-builder', 'documentation' ),
				'divino_base_url'             => admin_url( 'admin.php?page=' . divino_Menu::get_theme_page_slug() ),
			);

			wp_localize_script( 'divino-theme-builder-script', 'divino_theme_builder', $localized_data );
			wp_set_script_translations( 'divino-theme-builder-script', 'divino' );

			$localize = array(
				'pro_installed_status'   => 'installed' === divino_Menu::get_plugin_status( $divino_addon_locale ) ? true : false,
				'ajax_url'               => admin_url( 'admin-ajax.php' ),
				'upgrade_url'            => divino_get_upgrade_url( 'dashboard' ),
				'divino_base_url'         => admin_url( 'admin.php?page=' . divino_Menu::get_theme_page_slug() ),
				'update_nonce'           => wp_create_nonce( 'divino_update_admin_setting' ),
				'plugin_manager_nonce'   => wp_create_nonce( 'divino_plugin_manager_nonce' ),
				'plugin_installer_nonce' => wp_create_nonce( 'updates' ),
				'plugin_installing_text' => esc_html__( 'Installing', 'divino' ),
				'plugin_installed_text'  => esc_html__( 'Installed', 'divino' ),
				'plugin_activating_text' => esc_html__( 'Activating', 'divino' ),
				'plugin_activated_text'  => esc_html__( 'Activated', 'divino' ),
				'plugin_activate_text'   => esc_html__( 'Activate', 'divino' ),
			);

			wp_localize_script( 'divino-theme-builder-script', 'divino_admin', $localize );
		}

		/**
		 * Admin Body Classes
		 *
		 * @since 4.5.0
		 * @param string $classes Space separated class string.
		 */
		public function admin_body_class( $classes = '' ) {
			$theme_builder_class = isset( $_GET['page'] ) && 'theme-builder-free' === $_GET['page'] ? 'ast-theme-builder' : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Fetching a $_GET value, no nonce available to validate.
			$classes            .= ' ' . $theme_builder_class . ' ';

			return $classes;
		}

		/**
		 * Renders the admin settings.
		 *
		 * @since 4.5.0
		 * @return void
		 */
		public function render_theme_builder() {
			?>
				<div class="ast-tb-menu-page-wrapper">
					<div id="ast-tb-menu-page">
						<div class="ast-tb-menu-page-content">
							<div id="ast-tb-app-root" class="ast-tb-app-root"></div>
						</div>
					</div>
				</div>
			<?php
		}

		/**
		 * Setup menu.
		 *
		 * @since 4.5.0
		 * @return void
		 */
		public function setup_menu() {
			add_submenu_page( // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page -- Taken the menu on top level
				'divino',
				__( 'Site Builder', 'divino' ),
				__( 'Site Builder', 'divino' ),
				'manage_options',
				'theme-builder-free',
				array( $this, 'render_theme_builder' ),
				2
			);
		}

		/**
		 * Disable notices for Site Builder page.
		 *
		 * @since 4.5.0
		 * @return void
		 */
		public function divino_theme_builder_disable_notices() {

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Fetching a $_GET value, no nonce available to validate.
			if ( isset( $_GET['page'] ) && 'theme-builder-free' === $_GET['page'] ) {
				remove_all_actions( 'admin_notices' );
				remove_all_actions( 'all_admin_notices' ); // For older versions of WordPress
			}
		}

		/**
		 * Redirect to Site Builder pro from free preview if pro module is active.
		 *
		 * @since 4.5.0
		 * @return void
		 */
		public function divino_theme_builder_access_denied_redirect() {

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Fetching a $_GET value, no nonce available to validate.
			if ( isset( $_GET['page'] ) && 'theme-builder-free' === $_GET['page'] ) {
				/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				$is_divino_addon_active = ( defined( 'divino_EXT_VER' ) && divino_Ext_Extension::is_active( 'advanced-hooks' ) );
				/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				if ( $is_divino_addon_active ) {
					wp_safe_redirect( admin_url( 'admin.php?page=theme-builder' ) );
					exit;
				}
			}
		}
	}

	/**
	 *  Kicking this off by calling 'get_instance()' method
	 */
	divino_Theme_Builder_Free::get_instance();

}
