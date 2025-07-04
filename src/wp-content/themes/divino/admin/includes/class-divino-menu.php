<?php
/**
 * Class divino_Menu.
 *
 * @package divino
 * @since 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * divino_Menu.
 *
 * @since 4.1.0
 */
class divino_Menu {
	/**
	 * Instance
	 *
	 * @var null $instance
	 * @since 4.0.0
	 */
	private static $instance;

	/**
	 * Initiator
	 *
	 * @since 4.0.0
	 * @return object initialized object of class.
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
	 * Page title
	 *
	 * @since 4.0.0
	 * @var string $page_title
	 */
	public static $page_title = 'divino';

	/**
	 * Plugin slug
	 *
	 * @since 4.0.0
	 * @var string $plugin_slug
	 */
	public static $plugin_slug = 'divino';

	/**
	 * Constructor
	 *
	 * @since 4.0.0
	 */
	public function __construct() {
		$this->initialize_hooks();
	}

	/**
	 * Init Hooks.
	 *
	 * @since 4.0.0
	 * @return void
	 */
	public function initialize_hooks() {
		self::$plugin_slug = self::get_theme_page_slug();

		add_action( 'admin_menu', array( $this, 'setup_menu' ) );
		add_action( 'admin_init', array( $this, 'settings_admin_scripts' ) );

		add_filter( 'install_plugins_tabs', array( $this, 'add_divino_woo_suggestions_link' ), 1 );
		add_action( 'install_plugins_pre_divino-woo', array( $this, 'update_plugin_suggestions_tab_link' ) );
	}

	/**
	 * Add divino~Woo Suggestions plugin tab link.
	 *
	 * @param array $tabs Plugin tabs.
	 * @since 4.7.3
	 * @return array
	 */
	public function add_divino_woo_suggestions_link( $tabs ) {
		if ( class_exists( 'WooCommerce' ) ) {
			$tabs['divino-woo'] = esc_html__( 'For ', 'divino' ) . self::$page_title . '~Woo';
		}
		return $tabs;
	}

	/**
	 * Update plugin suggestions tab link.
	 *
	 * @since 4.7.3
	 * @return void
	 */
	public function update_plugin_suggestions_tab_link() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_GET['tab'] ) || 'divino-woo' !== $_GET['tab'] ) {
			return;
		}
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		$extensions_url = add_query_arg(
			array(
				'page' => self::$plugin_slug,
				'path' => 'woocommerce',
				'ref'  => 'plugins',
			),
			admin_url( 'admin.php' )
		);

		wp_safe_redirect( $extensions_url );
		exit;
	}

	/**
	 * Theme options page Slug getter including White Label string.
	 *
	 * @since 4.0.0
	 * @return string Theme Options Page Slug.
	 */
	public static function get_theme_page_slug() {
		return apply_filters( 'divino_theme_page_slug', self::$plugin_slug );
	}

	/**
	 *  Initialize after divino gets loaded.
	 *
	 * @since 4.0.0
	 */
	public function settings_admin_scripts() {
		// Enqueue admin scripts.
		/** @psalm-suppress PossiblyInvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		if ( ! empty( $_GET['page'] ) && ( self::$plugin_slug === $_GET['page'] || false !== strpos( $_GET['page'], self::$plugin_slug . '_' ) ) ) { //phpcs:ignore
			/** @psalm-suppress PossiblyInvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			add_action( 'admin_enqueue_scripts', array( $this, 'styles_scripts' ) );
			add_filter( 'admin_footer_text', array( $this, 'divino_admin_footer_link' ), 99 );
		}
	}

	/**
	 * Add submenu to admin menu.
	 *
	 * @since 4.0.0
	 */
	public function setup_menu() {
		global $submenu;

		$capability = 'manage_options';

		if ( ! current_user_can( $capability ) ) {
			return;
		}

		self::$page_title = apply_filters( 'divino_page_title', esc_html__( 'divino', 'divino' ) );

		$divino_icon = apply_filters( 'divino_menu_icon', 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNzcyIiBoZWlnaHQ9Ijc3MiIgdmlld0JveD0iMCAwIDc3MiA3NzIiIGZpbGw9IiNhN2FhYWQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+DQo8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTM4NiA3NzJDNTk5LjE4MiA3NzIgNzcyIDU5OS4xODIgNzcyIDM4NkM3NzIgMTcyLjgxOCA1OTkuMTgyIDAgMzg2IDBDMTcyLjgxOCAwIDAgMTcyLjgxOCAwIDM4NkMwIDU5OS4xODIgMTcyLjgxOCA3NzIgMzg2IDc3MlpNMjYxLjcxMyAzNDMuODg2TDI2MS42NzUgMzQzLjk2OEMyMjIuNDE3IDQyNi45OTQgMTgzLjE1OSA1MTAuMDE5IDE0My45MDIgNTkyLjk1MkgyNDQuODQ3QzI3Ni42MjcgNTI4LjczOSAzMDguNDA3IDQ2NC40MzQgMzQwLjE4NyA0MDAuMTI4QzM3MS45NjUgMzM1LjgyNyA0MDMuNzQyIDI3MS41MjcgNDM1LjUyIDIwNy4zMkwzNzkuNDQgOTVDMzQwLjE5NyAxNzcuOSAzMDAuOTU1IDI2MC44OTMgMjYxLjcxMyAzNDMuODg2Wk00MzYuNjczIDQwNC4wNzVDNDUyLjkwNiAzNzAuNzQ1IDQ2OS4xMzkgMzM3LjQxNSA0ODUuNDY3IDMwNC4wODVDNTA5LjMwMSAzNTIuMjI5IDUzMy4wNDIgNDAwLjM3NCA1NTYuNzgyIDQ0OC41MThDNTgwLjUyMyA0OTYuNjYzIDYwNC4yNjQgNTQ0LjgwNyA2MjguMDk4IDU5Mi45NTJINTE5LjI0OEM1MTMuMDU0IDU3OC42OTMgNTA2Ljc2NyA1NjQuNTI3IDUwMC40OCA1NTAuMzYyQzQ5NC4xOTMgNTM2LjE5NiA0ODcuOTA2IDUyMi4wMzEgNDgxLjcxMyA1MDcuNzczSDM4NkwzODcuODc3IDUwNC4wNjlDNDA0LjIwNSA0NzAuNzM4IDQyMC40MzkgNDM3LjQwNiA0MzYuNjczIDQwNC4wNzVaIiBmaWxsPSIjYTdhYWFkIi8+DQo8L3N2Zz4=' );
		$priority   = apply_filters( 'divino_menu_priority', 59 );

		add_menu_page( // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_menu_page -- Taken the menu on top level
			self::$page_title,
			self::$page_title,
			$capability,
			self::$plugin_slug,
			array( $this, 'render_admin_dashboard' ),
			$divino_icon,
			$priority
		);

		// Add Customize submenu.
		add_submenu_page( // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page -- Taken the menu on top level
			self::$plugin_slug,
			__( 'Customize', 'divino' ),
			__( 'Customize', 'divino' ),
			$capability,
			'customize.php'
		);

		// Add Custom Layout submenu.
		/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$show_custom_layout_submenu = defined( 'divino_EXT_VER' ) && ! divino_Ext_Extension::is_active( 'advanced-hooks' ) ? false : true;
		/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

		if ( $show_custom_layout_submenu && defined( 'divino_EXT_VER' ) && version_compare( divino_EXT_VER, '4.5.0', '<' ) ) {
			add_submenu_page( // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page -- Taken the menu on top level
				self::$plugin_slug,
				__( 'Custom Layouts', 'divino' ),
				__( 'Custom Layouts', 'divino' ),
				$capability,
				/* @psalm-suppress UndefinedClass */
				defined( 'divino_EXT_VER' ) && divino_Ext_Extension::is_active( 'advanced-hooks' ) ? 'edit.php?post_type=divino-advanced-hook' : 'admin.php?page=' . self::$plugin_slug . '&path=custom-layouts'
			);
		}

		if ( ! divino_is_white_labelled() ) {
			// Add divino~Woo Extensions page or Spectra submenu.
			/** @psalm-suppress TypeDoesNotContainType */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( divino_THEME_ORG_VERSION && class_exists( 'WooCommerce' ) ) {
				/** @psalm-suppress TypeDoesNotContainType */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				add_submenu_page( // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page -- Taken the menu on top level
					self::$plugin_slug,
					'WooCommerce',
					'WooCommerce',
					$capability,
					'admin.php?page=' . self::$plugin_slug . '&path=woocommerce'
				);
			} elseif ( divino_THEME_ORG_VERSION && ! $this->spectra_has_top_level_menu() ) {
				add_submenu_page( // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page -- Taken the menu on top level
					self::$plugin_slug,
					'Spectra',
					'Spectra',
					$capability,
					$this->get_spectra_page_admin_link()
				);
			}
		}

		// Rename to Home menu.
		$submenu[ self::$plugin_slug ][0][0] = esc_html__( 'Dashboard', 'divino' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Required to rename the home menu.
	}

	/**
	 * In version 2.4.1 Spectra introduces top level admin menu so there is no meaning to show Spectra submenu from divino menu.
	 *
	 * @since 4.1.4
	 * @return bool true|false.
	 */
	public function spectra_has_top_level_menu() {
		return defined( 'UAGB_VER' ) && version_compare( UAGB_VER, '2.4.1', '>=' ) ? true : false;
	}

	/**
	 * Provide the Spectra admin page URL.
	 *
	 * @since 4.1.1
	 * @return string url.
	 */
	public function get_spectra_page_admin_link() {
		$spectra_admin_url = defined( 'UAGB_VER' ) ? ( $this->spectra_has_top_level_menu() ? admin_url( 'admin.php?page=' . UAGB_SLUG ) : admin_url( 'options-general.php?page=' . UAGB_SLUG ) ) : 'admin.php?page=' . self::$plugin_slug . '&path=spectra';
		return apply_filters( 'divino_dashboard_spectra_admin_link', $spectra_admin_url );
	}

	/**
	 * Renders the admin settings.
	 *
	 * @since 4.0.0
	 * @return void
	 */
	public function render_admin_dashboard() {
		$page_action = '';

		if ( isset( $_GET['action'] ) ) { //phpcs:ignore
			/** @psalm-suppress PossiblyInvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$page_action = sanitize_text_field( wp_unslash( $_GET['action'] ) ); //phpcs:ignore
			/** @psalm-suppress PossiblyInvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$page_action = str_replace( '_', '-', $page_action );
		}

		?>
		<div class="ast-menu-page-wrapper">
			<div id="ast-menu-page">
				<div class="ast-menu-page-content">
					<div id="divino-dashboard-app" class="divino-dashboard-app"> </div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Enqueues the needed CSS/JS for the builder's admin settings page.
	 *
	 * @since 4.0.0
	 */
	public function styles_scripts() {

		if ( is_customize_preview() ) {
			return;
		}

		wp_enqueue_style( 'divino-admin-font', 'https://fonts.googleapis.com/css2?family=Inter:wght@200;400;500&display=swap', array(), divino_THEME_VERSION ); // Styles.

		wp_enqueue_style( 'wp-components' );

		/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$show_self_branding = defined( 'divino_EXT_VER' ) && is_callable( 'divino_Ext_White_Label_Markup::show_branding' ) ? divino_Ext_White_Label_Markup::show_branding() : true;
		/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$user_firstname = wp_get_current_user()->user_firstname;
		/** @psalm-suppress TypeDoesNotContainType */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$divino_addon_locale = divino_THEME_ORG_VERSION ? 'divino-addon/divino-addon.php' : 'divino-pro/divino-pro.php';
		/** @psalm-suppress TypeDoesNotContainType */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$localize = array(
			'current_user'            => ! empty( $user_firstname ) ? ucfirst( $user_firstname ) : ucfirst( wp_get_current_user()->display_name ),
			'admin_base_url'          => admin_url(),
			'plugin_dir'              => divino_THEME_URI,
			'plugin_ver'              => defined( 'divino_EXT_VER' ) ? divino_EXT_VER : '',
			'version'                 => divino_THEME_VERSION,
			'pro_available'           => defined( 'divino_EXT_VER' ) ? true : false,
			'pro_installed_status'    => 'installed' === self::get_plugin_status( $divino_addon_locale ) ? true : false,
			'divino_addon_locale'      => $divino_addon_locale,
			/** @psalm-suppress TypeDoesNotContainType */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			'divino_rating_url'        => divino_THEME_ORG_VERSION ? 'https://wordpress.org/support/theme/divino/reviews/?rate=5#new-post' : 'https://woo.com/products/divino/#reviews',
			/** @psalm-suppress TypeDoesNotContainType */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			'spectra_plugin_status'   => self::get_plugin_status( 'ultimate-addons-for-gutenberg/ultimate-addons-for-gutenberg.php' ),
			'theme_name'              => divino_get_theme_name(),
			'plugin_name'             => divino_get_addon_name(),
			'quick_settings'          => self::divino_get_quick_links(),
			'ajax_url'                => admin_url( 'admin-ajax.php' ),
			'is_whitelabel'           => divino_is_white_labelled(),
			'show_self_branding'      => $show_self_branding,
			'admin_url'               => admin_url( 'admin.php' ),
			'home_slug'               => self::$plugin_slug,
			'upgrade_url'             => divino_get_upgrade_url( 'dashboard' ),
			'customize_url'           => admin_url( 'customize.php' ),
			'divino_base_url'          => admin_url( 'admin.php?page=' . self::$plugin_slug ),
			'logo_url'                => apply_filters( 'divino_admin_menu_icon', divino_THEME_URI . 'inc/assets/images/divino-logo.svg' ),
			'update_nonce'            => wp_create_nonce( 'divino_update_admin_setting' ),
			/** @psalm-suppress TypeDoesNotContainType */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			'show_plugins'            => apply_filters( 'divino_show_free_extend_plugins', true ) && divino_THEME_ORG_VERSION ? true : false, // Legacy filter support.
			/** @psalm-suppress TypeDoesNotContainType */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			'useful_plugins'          => self::divino_get_useful_plugins(),
			'extensions'              => self::divino_get_pro_extensions(),
			'plugin_manager_nonce'    => wp_create_nonce( 'divino_plugin_manager_nonce' ),
			'plugin_installer_nonce'  => wp_create_nonce( 'updates' ),
			'free_vs_pro_link'        => admin_url( 'admin.php?page=' . self::$plugin_slug . '&path=free-vs-pro' ),
			'show_builder_migration'  => divino_Builder_Helper::is_header_footer_builder_active(),
			'plugin_installing_text'  => esc_html__( 'Installing', 'divino' ),
			'plugin_installed_text'   => esc_html__( 'Installed', 'divino' ),
			'plugin_activating_text'  => esc_html__( 'Activating', 'divino' ),
			'plugin_activated_text'   => esc_html__( 'Activated', 'divino' ),
			'plugin_activate_text'    => esc_html__( 'Activate', 'divino' ),
			'starter_templates_data'  => self::get_starter_template_plugin_data(),
			'divino_docs_data'         => divino_remote_docs_data(),
			'upgrade_notice'          => divino_showcase_upgrade_notices(),
			'show_banner_video'       => apply_filters( 'divino_show_banner_video', true ),
			'is_woo_active'           => class_exists( 'WooCommerce' ) ? true : false,
			'woo_extensions'          => self::divino_get_woo_extensions( false ),
			'divinoWebsite'            => array(
				'baseUrl'                => divino_WEBSITE_BASE_URL,
				'docsUrl'                => divino_get_pro_url( '/docs/', 'free-theme', 'dashboard', 'documentation' ),
				'docsCategoryDynamicUrl' => divino_get_pro_url( '/docs-category/{{category}}', 'free-theme', 'dashboard', 'documentation' ),
				'vipPrioritySupportUrl'  => divino_get_pro_url( '/vip-priority-support/', 'free-theme', 'dashboard', 'vip-priority-support' ),
				'templatesUrl'           => divino_get_pro_url( '/website-templates/', 'free-theme', 'dashboard', 'starter-templates' ),
				'whatsNewFeedUrl'        => esc_url( divino_WEBSITE_BASE_URL . '/whats-new/feed/' ),
			),
			/** @psalm-suppress TypeDoesNotContainType */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			'divino_cta_btn_url'       => divino_THEME_ORG_VERSION ? divino_get_pro_url( '/pricing/', 'free-theme', 'dashboard', 'unlock-pro-features-CTA' ) : 'https://woocommerce.com/products/divino-pro/',
			/** @psalm-suppress TypeDoesNotContainType */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			'plugin_configuring_text' => esc_html__( 'Configuring', 'divino' ),
			'bsfUsageTrackingUrl'     => 'https://store.brainstormforce.com/usage-tracking/?utm_source=divino&utm_medium=dashboard&utm_campaign=usage_tracking',
		);

		$this->settings_app_scripts( apply_filters( 'divino_react_admin_localize', $localize ) );
	}

	/**
	 * Get customizer quick links for easy navigation.
	 *
	 * @return array
	 * @since 4.0.0
	 */
	public static function divino_get_quick_links() {
		return apply_filters(
			'divino_quick_settings',
			array(
				'logo-favicon' => array(
					'title'     => __( 'Site Identity', 'divino' ),
					'quick_url' => admin_url( 'customize.php?autofocus[control]=site_icon' ),
				),
				'header'       => array(
					'title'     => __( 'Header Settings', 'divino' ),
					'quick_url' => admin_url( 'customize.php?autofocus[panel]=panel-header-group' ),
				),
				'footer'       => array(
					'title'     => __( 'Footer Settings', 'divino' ),
					'quick_url' => admin_url( 'customize.php?autofocus[section]=section-footer-group' ),
				),
				'colors'       => array(
					'title'     => __( 'Color', 'divino' ),
					'quick_url' => admin_url( 'customize.php?autofocus[section]=section-colors-background' ),
				),
				'typography'   => array(
					'title'     => __( 'Typography', 'divino' ),
					'quick_url' => admin_url( 'customize.php?autofocus[section]=section-typography' ),
				),
				'button'       => array(
					'title'     => __( 'Button', 'divino' ),
					'quick_url' => admin_url( 'customize.php?autofocus[section]=section-buttons' ),
				),
				'blog-options' => array(
					'title'     => __( 'Blog Options', 'divino' ),
					'quick_url' => admin_url( 'customize.php?autofocus[section]=section-blog-group' ),
				),
				'layout'       => array(
					'title'     => __( 'Layout', 'divino' ),
					'quick_url' => admin_url( 'customize.php?autofocus[section]=section-container-layout' ),
				),
			)
		);
	}

	/**
	 * Check if Starter Templates promotions is being disabled.
	 *
	 * @return bool
	 * @since 4.8.9
	 */
	public static function is_promoting_starter_templates() {
		/**
		 * Filter to disable Starter Templates promotions.
		 * Used in the Website Learners platform: A popular YouTube channel that has been our partner since 2017.
		 *
		 * @param bool $disable_starter_templates_promotions Whether to disable Starter Templates promotions.
		 *
		 * @since 4.8.9
		 */
		return ! apply_filters( 'divino_disable_starter_templates_promotions', false );
	}

	/**
	 * Get Starter Templates plugin data.
	 *
	 * @return array
	 * @since 4.0.0
	 */
	public static function get_starter_template_plugin_data() {

		/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$st_data = array(
			'title'        => is_callable( 'divino_Ext_White_Label_Markup::get_whitelabel_string' ) ? divino_Ext_White_Label_Markup::get_whitelabel_string( 'divino-sites', 'name', __( 'Starter Templates', 'divino' ) ) : __( 'Starter Templates', 'divino' ),
			'description'  => is_callable( 'divino_Ext_White_Label_Markup::get_whitelabel_string' ) ? divino_Ext_White_Label_Markup::get_whitelabel_string( 'divino-sites', 'description', __( 'Create professional designed pixel perfect websites in minutes. Get access to 300+ pre-made full website templates for your favorite page builder.', 'divino' ) ) : __( 'Create professional designed pixel perfect websites in minutes. Get access to 300+ pre-made full website templates for your favorite page builder.', 'divino' ),
			'is_available' => defined( 'divino_PRO_SITES_VER' ) || defined( 'divino_SITES_VER' ) ? true : false,
			'redirection'  => admin_url( 'themes.php?page=starter-templates' ),
			'icon_path'    => 'https://ps.w.org/divino-sites/assets/icon-256x256.gif',
			'is_promoting' => self::is_promoting_starter_templates(),
		);
		/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

		$skip_free_version = false;
		$pro_plugin_status = self::get_plugin_status( 'divino-pro-sites/divino-pro-sites.php' );

		if ( 'installed' === $pro_plugin_status || 'activated' === $pro_plugin_status ) {
			$skip_free_version = true;
			$st_data['slug']   = 'divino-pro-sites';
			$st_data['status'] = $pro_plugin_status;
			$st_data['path']   = 'divino-pro-sites/divino-pro-sites.php';
		}

		$free_plugin_status = self::get_plugin_status( 'divino-sites/divino-sites.php' );
		if ( ! $skip_free_version ) {
			$st_data['slug']   = 'divino-sites';
			$st_data['status'] = $free_plugin_status;
			$st_data['path']   = 'divino-sites/divino-sites.php';
		}

		return $st_data;
	}

	/**
	 * Method to check plugin configuration status.
	 *
	 * @since 4.8.2
	 *
	 * @param  string $plugin_init_file Plugin init file.
	 *
	 * @return bool Returns true if plugin is configured, false otherwise.
	 */
	public static function is_plugin_configured( $plugin_init_file ) {

		switch ( $plugin_init_file ) {
			case 'surecart/surecart.php':
				/** @psalm-suppress UndefinedClass */
				return class_exists( '\SureCart\Models\ApiToken' ) && \SureCart\Models\ApiToken::get();

			case 'suretriggers/suretriggers.php':
				if ( class_exists( '\SureTriggers\Controllers\OptionController' ) ) {
					/** @psalm-suppress UndefinedClass */
					$st_key = \SureTriggers\Controllers\OptionController::get_option( 'secret_key' );
					return $st_key && $st_key !== 'connection-denied';
				}
				// Fall through: Intentional fall-through without returning.
				return false;

			case 'checkout-plugins-stripe-woo/checkout-plugins-stripe-woo.php':
				// If the setup is not skipped and connected to the Stripe.
				/** @psalm-suppress UndefinedClass */
				return 'skipped' !== get_option( 'cpsw_setup_status', false ) && class_exists( '\CPSW\Admin\Admin_Controller' ) && \CPSW\Admin\Admin_Controller::get_instance()->is_stripe_connected();

			case 'checkout-paypal-woo/checkout-paypal-woo.php':
				if ( ! class_exists( '\CPPW\Gateway\Paypal\Api\Client' ) ) {
					return false;
				}
				$remote_url = 'v1/identity/oauth2/userinfo?schema=paypalv1.1';
				try {
					/** @psalm-suppress UndefinedClass */
					$response = \CPPW\Gateway\Paypal\Api\Client::request( $remote_url, array(), 'get' );
					if ( ! is_array( $response ) || empty( $response['user_id'] ) ) {
						return false;
					}
					return true;
				} catch ( \Exception $e ) {
					// Handle exception silently.
					return false;
				}
				// Fall through: This catch block will always return false if an exception is caught.
				return false;
		}

		return true;
	}

	/**
	 * Get plugin status
	 *
	 * @since 4.0.0
	 *
	 * @param  string $plugin_init_file Plugin init file.
	 * @return mixed
	 */
	public static function get_plugin_status( $plugin_init_file ) {

		$installed_plugins = get_plugins();

		if ( ! isset( $installed_plugins[ $plugin_init_file ] ) ) {
			return 'install';
		}

		if ( is_plugin_active( $plugin_init_file ) ) {
			if ( ! self::is_plugin_configured( $plugin_init_file ) ) {
				return 'configure';
			}
			return 'activated';
		}

		return 'installed';
	}

	/**
	 * Get divino's pro extension list.
	 *
	 * @since 4.0.0
	 * @return array
	 */
	public static function divino_get_pro_extensions() {
		return apply_filters(
			'divino_addon_list',
			array(
				'colors-and-background' => array(
					'title'     => __( 'Colors & Background', 'divino' ),
					'class'     => 'ast-addon',
					'title_url' => divino_get_pro_url( '/docs/colors-background-module/', 'free-theme', 'dashboard', 'documentation' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => divino_get_pro_url( '/docs/colors-background-module/', 'free-theme', 'dashboard', 'documentation' ),
							'link_text'    => __( 'Documentation', 'divino' ),
							'target_blank' => true,
						),
					),
				),
				'typography'            => array(
					'title'     => __( 'Typography', 'divino' ),
					'class'     => 'ast-addon',
					'title_url' => divino_get_pro_url( '/docs/typography-module/', 'free-theme', 'dashboard', 'documentation' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => divino_get_pro_url( '/docs/typography-module/', 'free-theme', 'dashboard', 'documentation' ),
							'link_text'    => __( 'Documentation', 'divino' ),
							'target_blank' => true,
						),
					),
				),
				'spacing'               => array(
					'title'     => __( 'Spacing', 'divino' ),
					'class'     => 'ast-addon',
					'title_url' => divino_get_pro_url( '/docs/spacing-addon-overview/', 'free-theme', 'dashboard', 'documentation' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => divino_get_pro_url( '/docs/spacing-addon-overview/', 'free-theme', 'dashboard', 'documentation' ),
							'link_text'    => __( 'Documentation', 'divino' ),
							'target_blank' => true,
						),
					),
				),
				'blog-pro'              => array(
					'title'     => __( 'Blog Pro', 'divino' ),
					'class'     => 'ast-addon',
					'title_url' => divino_get_pro_url( '/docs/blog-pro-overview/', 'free-theme', 'dashboard', 'documentation' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => divino_get_pro_url( '/docs/blog-pro-overview/', 'free-theme', 'dashboard', 'documentation' ),
							'link_text'    => __( 'Documentation', 'divino' ),
							'target_blank' => true,
						),
					),
				),
				'mobile-header'         => array(
					'title'     => __( 'Mobile Header', 'divino' ),
					'class'     => 'ast-addon',
					'title_url' => divino_get_pro_url( '/docs/mobile-header-with-divino/', 'free-theme', 'dashboard', 'documentation' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => divino_get_pro_url( '/docs/mobile-header-with-divino/', 'free-theme', 'dashboard', 'documentation' ),
							'link_text'    => __( 'Documentation', 'divino' ),
							'target_blank' => true,
						),
					),
				),
				'header-sections'       => array(
					'title'     => __( 'Header Sections', 'divino' ),
					'class'     => 'ast-addon',
					'title_url' => divino_get_pro_url( '/docs/header-sections-pro/', 'free-theme', 'dashboard', 'documentation' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => divino_get_pro_url( '/docs/header-sections-pro/', 'free-theme', 'dashboard', 'documentation' ),
							'link_text'    => __( 'Documentation', 'divino' ),
							'target_blank' => true,
						),
					),
				),
				'sticky-header'         => array(
					'title'     => __( 'Sticky Header', 'divino' ),
					'class'     => 'ast-addon',
					'title_url' => divino_get_pro_url( '/docs/sticky-header-pro/', 'free-theme', 'dashboard', 'documentation' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => divino_get_pro_url( '/docs/sticky-header-pro/', 'free-theme', 'dashboard', 'documentation' ),
							'link_text'    => __( 'Documentation', 'divino' ),
							'target_blank' => true,
						),
					),
				),
				'site-layouts'          => array(
					'title'     => __( 'Site Layouts', 'divino' ),
					'class'     => 'ast-addon',
					'title_url' => divino_get_pro_url( '/docs/site-layout-overview/', 'free-theme', 'dashboard', 'documentation' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => divino_get_pro_url( '/docs/site-layout-overview/', 'free-theme', 'dashboard', 'documentation' ),
							'link_text'    => __( 'Documentation', 'divino' ),
							'target_blank' => true,
						),
					),
				),
				'advanced-footer'       => array(
					'title'     => __( 'Footer Widgets', 'divino' ),
					'class'     => 'ast-addon',
					'title_url' => divino_get_pro_url( '/docs/footer-widgets-divino-pro/', 'free-theme', 'dashboard', 'documentation' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => divino_get_pro_url( '/docs/footer-widgets-divino-pro/', 'free-theme', 'dashboard', 'documentation' ),
							'link_text'    => __( 'Documentation', 'divino' ),
							'target_blank' => true,
						),
					),
				),
				'nav-menu'              => array(
					'title'     => __( 'Nav Menu', 'divino' ),
					'class'     => 'ast-addon',
					'title_url' => divino_get_pro_url( '/docs/nav-menu-addon/', 'free-theme', 'dashboard', 'documentation' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => divino_get_pro_url( '/docs/nav-menu-addon/', 'free-theme', 'dashboard', 'documentation' ),
							'link_text'    => __( 'Documentation', 'divino' ),
							'target_blank' => true,
						),
					),
				),
				'advanced-hooks'        => array(
					'title'           => defined( 'divino_EXT_VER' ) && version_compare( divino_EXT_VER, '4.5.0', '<' ) ? __( 'Custom Layouts', 'divino' ) : __( 'Site Builder', 'divino' ),
					'description'     => __( 'Add content conditionally in the various hook areas of the theme.', 'divino' ),
					'manage_settings' => true,
					'class'           => 'ast-addon',
					'title_url'       => divino_get_pro_url( '/docs/custom-layouts-pro/', 'free-theme', 'dashboard', 'documentation' ),
					'links'           => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => divino_get_pro_url( '/docs/custom-layouts-pro/', 'free-theme', 'dashboard', 'documentation' ),
							'link_text'    => __( 'Documentation', 'divino' ),
							'target_blank' => true,
						),
					),
				),
				'advanced-headers'      => array(
					'title'           => __( 'Page Headers', 'divino' ),
					'description'     => __( 'Make your header layouts look more appealing and sexy!', 'divino' ),
					'manage_settings' => true,
					'class'           => 'ast-addon',
					'title_url'       => divino_get_pro_url( '/docs/page-headers-overview/', 'free-theme', 'dashboard', 'documentation' ),
					'links'           => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => divino_get_pro_url( '/docs/page-headers-overview/', 'free-theme', 'dashboard', 'documentation' ),
							'link_text'    => __( 'Documentation', 'divino' ),
							'target_blank' => true,
						),
					),
				),
				'woocommerce'           => array(
					'title'     => 'WooCommerce',
					'class'     => 'ast-addon',
					'isActive'  => defined( 'divino_EXT_VER' ) && class_exists( 'WooCommerce' ) ? true : false,
					'title_url' => divino_get_pro_url( '/docs/woocommerce-module-overview/', 'free-theme', 'dashboard', 'documentation' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => divino_get_pro_url( '/docs/woocommerce-module-overview/', 'free-theme', 'dashboard', 'documentation' ),
							'link_text'    => __( 'Documentation', 'divino' ),
							'target_blank' => true,
						),
					),
				),
				'edd'                   => array(
					'title'     => 'Easy Digital Downloads',
					'class'     => 'ast-addon',
					'isActive'  => defined( 'divino_EXT_VER' ) && class_exists( 'Easy_Digital_Downloads' ) ? true : false,
					'title_url' => divino_get_pro_url( '/docs/easy-digital-downloads-module-overview/', 'free-theme', 'dashboard', 'documentation' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => divino_get_pro_url( '/docs/easy-digital-downloads-module-overview/', 'free-theme', 'dashboard', 'documentation' ),
							'link_text'    => __( 'Documentation', 'divino' ),
							'target_blank' => true,
						),
					),
				),
				'learndash'             => array(
					'title'       => 'LearnDash',
					'isActive'    => defined( 'divino_EXT_VER' ) && class_exists( 'SFWD_LMS' ) ? true : false,
					'description' => __( 'Supercharge your LearnDash website with amazing design features.', 'divino' ),
					'class'       => 'ast-addon',
					'title_url'   => divino_get_pro_url( '/docs/learndash-integration-in-divino-pro/', 'free-theme', 'dashboard', 'documentation' ),
					'links'       => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => divino_get_pro_url( '/docs/learndash-integration-in-divino-pro/', 'free-theme', 'dashboard', 'documentation' ),
							'link_text'    => __( 'Documentation', 'divino' ),
							'target_blank' => true,
						),
					),
				),
				'lifterlms'             => array(
					'title'     => 'LifterLMS',
					'class'     => 'ast-addon',
					'isActive'  => defined( 'divino_EXT_VER' ) && class_exists( 'LifterLMS' ) ? true : false,
					'title_url' => divino_get_pro_url( '/docs/lifterlms-module-pro/', 'free-theme', 'dashboard', 'documentation' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => divino_get_pro_url( '/docs/lifterlms-module-pro/', 'free-theme', 'dashboard', 'documentation' ),
							'link_text'    => __( 'Documentation', 'divino' ),
							'target_blank' => true,
						),
					),
				),
				'white-label'           => array(
					'title'     => __( 'White Label', 'divino' ),
					'class'     => 'ast-addon',
					'title_url' => divino_get_pro_url( '/docs/how-to-white-label-divino/', 'free-theme', 'dashboard', 'documentation' ),
					'links'     => array(
						array(
							'link_class'   => 'ast-learn-more',
							'link_url'     => divino_get_pro_url( '/docs/how-to-white-label-divino/', 'free-theme', 'dashboard', 'documentation' ),
							'link_text'    => __( 'Documentation', 'divino' ),
							'target_blank' => true,
						),
					),
				),
			)
		);
	}

	/**
	 * Get divino's recommended WooCommerce extensions.
	 *
	 * @param bool $under_useful_plugins Add under useful plugins or not.
	 *
	 * @since 4.7.3
	 * @return array
	 */
	public static function divino_get_woo_extensions( $under_useful_plugins = true ) {

		$extensions = array(
			array(
				'title'       => 'CartFlows: Create Sales Funnel',
				'subtitle'    => $under_useful_plugins ? __( '#1 Sales Funnel WordPress Builder.', 'divino' ) : __( 'Build high-converting E-Commerce stores with CartFlows, the ultimate checkout and funnel builder.', 'divino' ),
				'status'      => self::get_plugin_status( 'cartflows/cartflows.php' ),
				'slug'        => 'cartflows',
				'path'        => 'cartflows/cartflows.php',
				'redirection' => false === get_option( 'wcf_setup_complete', false ) && ! get_option( 'wcf_setup_skipped', false ) ? admin_url( 'index.php?page=cartflow-setup' ) : admin_url( 'admin.php?page=cartflows' ),
				'ratings'     => '(400+)',
				'activations' => '200,000 +',
				'logoPath'    => array(
					'internal_icon' => false,
					'icon_path'     => 'https://ps.w.org/cartflows/assets/icon-256x256.gif',
				),
			),
		);

		if ( ! $under_useful_plugins ) {
			$extensions[] = array(
				'title'       => 'OttoKit: WordPress Automation',
				'subtitle'    => __( 'Connect your WordPress plugins, WooCommerce sites, apps, and websites for powerful automations.', 'divino' ),
				'status'      => self::get_plugin_status( 'suretriggers/suretriggers.php' ),
				'slug'        => 'suretriggers',
				'path'        => 'suretriggers/suretriggers.php',
				'redirection' => admin_url( 'admin.php?page=suretriggers' ),
				'ratings'     => '(60+)',
				'activations' => '1,00,000 +',
				'logoPath'    => array(
					'internal_icon' => true,
					'icon_path'     => 'ottokit',
				),
			);
		}

		$extensions[] = array(
			'title'       => 'Spectra: Blocks Builder',
			'subtitle'    => $under_useful_plugins ? __( 'Free WordPress Page Builder.', 'divino' ) : __( 'Power-up block editor with advanced blocks for faster and effortlessly website creation.', 'divino' ),
			'status'      => self::get_plugin_status( 'ultimate-addons-for-gutenberg/ultimate-addons-for-gutenberg.php' ),
			'slug'        => 'ultimate-addons-for-gutenberg',
			'path'        => 'ultimate-addons-for-gutenberg/ultimate-addons-for-gutenberg.php',
			'redirection' => admin_url( 'options-general.php?page=spectra' ),
			'ratings'     => '(1500+)',
			'activations' => '1,000,000 +',
			'logoPath'    => array(
				'internal_icon' => false,
				'icon_path'     => 'https://ps.w.org/ultimate-addons-for-gutenberg/assets/icon-256x256.gif',
			),
		);

		$extensions[] = array(
			'title'       => 'Modern Cart for WooCommerce',
			'subtitle'    => $under_useful_plugins ? __( 'Modern Cart: A smarter way to sell', 'divino' ) : __( 'Say goodbye to slow checkouts – boost sales with a smooth, hassle-free experience.', 'divino' ),
			'status'      => 'visit',
			'slug'        => '',
			'path'        => '',
			'redirection' => esc_url( 'https://cartflows.com/modern-cart-for-woocommerce/?utm_source=cross_promotions&utm_medium=referral&utm_campaign=divino_dashboard' ),
			'ratings'     => '(25+)',
			'activations' => '100 +',
			'logoPath'    => array(
				'internal_icon' => true,
				'icon_path'     => 'moderncart',
			),
		);

		if ( ! $under_useful_plugins ) {
			$extensions[] = array(
				'title'       => 'PayPal Payments For WooCommerce',
				'subtitle'    => __( 'PayPal Payments For WooCommerce simplifies and secures PayPal transactions on your store.', 'divino' ),
				'status'      => self::get_plugin_status( 'checkout-paypal-woo/checkout-paypal-woo.php' ),
				'slug'        => 'checkout-paypal-woo',
				'path'        => 'checkout-paypal-woo/checkout-paypal-woo.php',
				'redirection' => admin_url( 'admin.php?page=wc-settings&tab=cppw_api_settings' ),
				'ratings'     => '(2)',
				'activations' => '6,000 +',
				'logoPath'    => array(
					'internal_icon' => false,
					'icon_path'     => 'https://ps.w.org/checkout-paypal-woo/assets/icon-128x128.jpg',
				),
			);
		}

		$extensions[] = array(
			'title'       => 'Cart Abandonment Recovery',
			'subtitle'    => $under_useful_plugins ? __( 'Recover lost revenue automatically.', 'divino' ) : __( 'Capture emails at checkout and send follow-up emails to recover lost revenue.', 'divino' ),
			'status'      => self::get_plugin_status( 'woo-cart-abandonment-recovery/woo-cart-abandonment-recovery.php' ),
			'slug'        => 'woo-cart-abandonment-recovery',
			'path'        => 'woo-cart-abandonment-recovery/woo-cart-abandonment-recovery.php',
			'redirection' => admin_url( 'admin.php?page=woo-cart-abandonment-recovery' ),
			'ratings'     => '(490+)',
			'activations' => '300,000 +',
			'logoPath'    => array(
				'internal_icon' => false,
				'icon_path'     => 'https://ps.w.org/woo-cart-abandonment-recovery/assets/icon-128x128.png',
			),
		);

		if ( ! $under_useful_plugins ) {
			$extensions[] = array(
				'title'       => 'Variations Swatches by CartFlows',
				'subtitle'    => __( 'Convert WooCommerce variation dropdown attributes into attractive swatches instantly.', 'divino' ),
				'status'      => self::get_plugin_status( 'variation-swatches-woo/variation-swatches-woo.php' ),
				'slug'        => 'variation-swatches-woo',
				'path'        => 'variation-swatches-woo/variation-swatches-woo.php',
				'redirection' => admin_url( 'admin.php?page=cfvsw_settings' ),
				'ratings'     => '(30+)',
				'activations' => '200,000 +',
				'logoPath'    => array(
					'internal_icon' => false,
					'icon_path'     => 'https://ps.w.org/variation-swatches-woo/assets/icon.svg',
				),
			);
		}

		$extensions[] = array(
			'title'       => 'Google Analytics for WooCommerce',
			'subtitle'    => __( 'Boost sales with WooCommerce analytics.', 'divino' ),
			'status'      => self::get_plugin_status( 'woocommerce-google-analytics-integration/woocommerce-google-analytics-integration.php' ),
			'slug'        => 'woocommerce-google-analytics-integration',
			'path'        => 'woocommerce-google-analytics-integration/woocommerce-google-analytics-integration.php',
			'redirection' => admin_url( 'admin.php?page=wc-settings&tab=integration&section=google_analytics' ),
			'ratings'     => '(110+)',
			'activations' => '200,000 +',
			'logoPath'    => array(
				'internal_icon' => false,
				'icon_path'     => 'https://ps.w.org/woocommerce-google-analytics-integration/assets/icon-256x256.png',
			),
		);

		return $extensions;
	}

	/**
	 * Get divino's useful plugins.
	 * Extend this in following way -
	 *
	 * //  array(
	 * //         'title' => "Plugin Name",
	 * //         'subtitle' => "Plugin description goes here.",
	 * //         'path' => 'plugin-slug/plugin-slug.php',
	 * //         'redirection' => admin_url( 'admin.php?page=sc-dashboard' ),
	 * //         'status' => self::get_plugin_status( 'plugin-slug/plugin-slug.php' ),
	 * //         'logoPath' => array(
	 * //             'internal_icon' => true, // true = will take internal divino's any icon. false = provide next custom icon link.
	 * //             'icon_path' => "spectra", // If internal_icon false then - example custom SVG URL: divino_THEME_URI . 'inc/assets/images/divino.svg'.
	 * //         ),
	 * //     ),
	 *
	 * @since 4.0.0
	 * @return array
	 */
	public static function divino_get_useful_plugins() {
		// Making useful plugin section dynamic.
		if ( class_exists( 'WooCommerce' ) ) {
			$useful_plugins = self::divino_get_woo_extensions();
		} else {
			$surecart_status      = self::get_plugin_status( 'surecart/surecart.php' );
			$surecart_redirection = 'activated' === $surecart_status ? 'sc-dashboard' : 'sc-getting-started';

			$plugins = array(
				'surecart'      => array(
					'title'       => 'SureCart',
					'subtitle'    => __( 'Sell products, services, subscriptions & more.', 'divino' ),
					'status'      => $surecart_status,
					'slug'        => 'surecart',
					'path'        => 'surecart/surecart.php',
					'redirection' => admin_url( 'admin.php?page=' . esc_attr( $surecart_redirection ) ),
					'logoPath'    => array(
						'internal_icon' => true,
						'icon_path'     => 'surecart_logo',
					),
				),
				'spectra'       => array(
					'title'       => 'Spectra',
					'subtitle'    => __( 'Free WordPress Page Builder.', 'divino' ),
					'status'      => self::get_plugin_status( 'ultimate-addons-for-gutenberg/ultimate-addons-for-gutenberg.php' ),
					'slug'        => 'ultimate-addons-for-gutenberg',
					'path'        => 'ultimate-addons-for-gutenberg/ultimate-addons-for-gutenberg.php',
					'redirection' => admin_url( 'options-general.php?page=spectra' ),
					'logoPath'    => array(
						'internal_icon' => false,
						'icon_path'     => 'https://ps.w.org/ultimate-addons-for-gutenberg/assets/icon-256x256.gif',
					),
				),
				'suretriggers'  => array(
					'title'       => 'OttoKit',
					'subtitle'    => __( 'Automate your WordPress setup.', 'divino' ),
					'isPro'       => false,
					'status'      => self::get_plugin_status( 'suretriggers/suretriggers.php' ),
					'slug'        => 'suretriggers',
					'path'        => 'suretriggers/suretriggers.php',
					'redirection' => admin_url( 'admin.php?page=suretriggers' ),
					'logoPath'    => array(
						'internal_icon' => true,
						'icon_path'     => 'ottokit',
					),
				),
				'sureforms'     => array(
					'title'       => 'SureForms',
					'subtitle'    => __( 'A versatile form builder plugin.', 'divino' ),
					'status'      => self::get_plugin_status( 'sureforms/sureforms.php' ),
					'slug'        => 'sureforms',
					'path'        => 'sureforms/sureforms.php',
					'redirection' => admin_url( 'admin.php?page=sureforms_menu' ),
					'logoPath'    => array(
						'internal_icon' => true,
						'icon_path'     => 'sureforms',
					),
				),
				'presto-player' => array(
					'title'       => 'Presto Player',
					'subtitle'    => __( 'Ultimate Video Player For WordPress.', 'divino' ),
					'status'      => self::get_plugin_status( 'presto-player/presto-player.php' ),
					'slug'        => 'presto-player',
					'path'        => 'presto-player/presto-player.php',
					'redirection' => admin_url( 'edit.php?post_type=pp_video_block' ),
					'logoPath'    => array(
						'internal_icon' => false,
						'icon_path'     => 'https://ps.w.org/presto-player/assets/icon-128x128.png',
					),
				),
				'uael'          => array(
					'title'       => 'Ultimate Addons for Elementor',
					'subtitle'    => __( 'Extend Elementor with premium widgets.', 'divino' ),
					'status'      => self::get_plugin_status( 'header-footer-elementor/header-footer-elementor.php' ),
					'slug'        => 'header-footer-elementor',
					'path'        => 'header-footer-elementor/header-footer-elementor.php',
					'redirection' => admin_url( 'admin.php?page=hfe#onboarding' ),
					'logoPath'    => array(
						'internal_icon' => false,
						'icon_path'     => 'https://ps.w.org/header-footer-elementor/assets/icon-256x256.gif',
					),
				),
			);

			// Pick useful plugins depending on Elementor status.
			$useful_plugins_keys = defined( 'ELEMENTOR_VERSION' )
				? array( 'uael', 'sureforms', 'spectra', 'suretriggers', 'presto-player' )
				: array( 'sureforms', 'spectra', 'suretriggers', 'surecart', 'presto-player' );

			$useful_plugins = array_map( static fn( $key ) => $plugins[ $key ], $useful_plugins_keys );
		}

		return apply_filters( 'divino_useful_plugins', $useful_plugins );
	}

	/**
	 * Settings app scripts
	 *
	 * @since 4.0.0
	 * @param array $localize Variable names.
	 */
	public function settings_app_scripts( $localize ) {
		$handle            = 'divino-admin-dashboard-app';
		$build_path        = divino_THEME_ADMIN_DIR . 'assets/build/';
		$build_url         = divino_THEME_ADMIN_URL . 'assets/build/';
		$script_asset_path = $build_path . 'dashboard-app.asset.php';

		/** @psalm-suppress MissingFile */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$script_info = file_exists( $script_asset_path ) ? include $script_asset_path : array(  // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound -- Not a template file so loading in a normal way.
			'dependencies' => array(),
			'version'      => divino_THEME_VERSION,
		);
		/** @psalm-suppress MissingFile */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

		$script_dep = array_merge( $script_info['dependencies'], array( 'updates', 'wp-hooks' ) );

		wp_register_script(
			$handle,
			$build_url . 'dashboard-app.js',
			$script_dep,
			$script_info['version'],
			true
		);

		wp_register_style(
			$handle,
			$build_url . 'dashboard-app.css',
			array(),
			divino_THEME_VERSION
		);

		wp_enqueue_script( $handle );

		wp_set_script_translations( $handle, 'divino' );

		wp_enqueue_style( $handle );

		wp_style_add_data( $handle, 'rtl', 'replace' );

		wp_localize_script( $handle, 'divino_admin', $localize );
	}

	/**
	 *  Add footer link.
	 *
	 * @since 4.0.0
	 */
	public function divino_admin_footer_link() {
		$theme_name = divino_get_theme_name();
		/** @psalm-suppress TypeDoesNotContainType */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$rating_url = divino_THEME_ORG_VERSION ? 'https://wordpress.org/support/theme/divino/reviews/?rate=5#new-post' : 'https://woo.com/products/divino/#reviews';
		/** @psalm-suppress TypeDoesNotContainType */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		if ( divino_is_white_labelled() ) {
			$footer_text = '<span id="footer-thankyou">' . __( 'Thank you for using', 'divino' ) . '<span class="focus:text-divino-hover active:text-divino-hover hover:text-divino-hover"> ' . esc_html( $theme_name ) . '.</span></span>';
		} else {
			$footer_text = sprintf(
				/* translators: 1: divino, 2: Theme rating link */
				__( 'Enjoyed %1$s? Please leave us a %2$s rating. We really appreciate your support!', 'divino' ),
				'<span class="ast-footer-thankyou"><strong>' . esc_html( $theme_name ) . '</strong>',
				'<a href="' . esc_url( $rating_url ) . '" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a></span>'
			);
		}
		return $footer_text;
	}
}

divino_Menu::get_instance();
