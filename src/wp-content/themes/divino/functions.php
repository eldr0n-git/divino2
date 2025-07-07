<?php
/**
 * divino functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package divino
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Define Constants
 */
define( 'divino_THEME_VERSION', '4.11.5' );
define( 'divino_THEME_SETTINGS', 'divino-settings' );
define( 'divino_THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'divino_THEME_URI', trailingslashit( esc_url( get_template_directory_uri() ) ) );
define( 'divino_THEME_ORG_VERSION', file_exists( divino_THEME_DIR . 'inc/w-org-version.php' ) );

/**
 * Minimum Version requirement of the divino Pro addon.
 * This constant will be used to display the notice asking user to update the divino addon to the version defined below.
 */
define( 'divino_EXT_MIN_VER', '4.11.1' );

/**
 * Load in-house compatibility.
 */
if ( divino_THEME_ORG_VERSION ) {
	require_once divino_THEME_DIR . 'inc/w-org-version.php';
}

/**
 * Setup helper functions of divino.
 */
require_once divino_THEME_DIR . 'inc/core/class-divino-theme-options.php';
require_once divino_THEME_DIR . 'inc/core/class-theme-strings.php';
require_once divino_THEME_DIR . 'inc/core/common-functions.php';
require_once divino_THEME_DIR . 'inc/core/class-divino-icons.php';

define( 'divino_WEBSITE_BASE_URL', 'https://divino.kz' );

/**
 * ToDo: Deprecate constants in future versions as they are no longer used in the codebase.
 */
define( 'divino_PRO_UPGRADE_URL', divino_THEME_ORG_VERSION ? divino_get_pro_url( '/pricing/', 'free-theme', 'dashboard', 'upgrade' ) : 'https://woocommerce.com/products/divino-pro/' );
define( 'divino_PRO_CUSTOMIZER_UPGRADE_URL', divino_THEME_ORG_VERSION ? divino_get_pro_url( '/pricing/', 'free-theme', 'customizer', 'upgrade' ) : 'https://woocommerce.com/products/divino-pro/' );

/**
 * Update theme
 */
require_once divino_THEME_DIR . 'inc/theme-update/divino-update-functions.php';
require_once divino_THEME_DIR . 'inc/theme-update/class-divino-theme-background-updater.php';

/**
 * Fonts Files
 */
require_once divino_THEME_DIR . 'inc/customizer/class-divino-font-families.php';
if ( is_admin() ) {
	require_once divino_THEME_DIR . 'inc/customizer/class-divino-fonts-data.php';
}

require_once divino_THEME_DIR . 'inc/lib/webfont/class-divino-webfont-loader.php';
require_once divino_THEME_DIR . 'inc/lib/docs/class-divino-docs-loader.php';
require_once divino_THEME_DIR . 'inc/customizer/class-divino-fonts.php';

require_once divino_THEME_DIR . 'inc/dynamic-css/custom-menu-old-header.php';
require_once divino_THEME_DIR . 'inc/dynamic-css/container-layouts.php';
require_once divino_THEME_DIR . 'inc/dynamic-css/divino-icons.php';
require_once divino_THEME_DIR . 'inc/core/class-divino-walker-page.php';
require_once divino_THEME_DIR . 'inc/core/class-divino-enqueue-scripts.php';
require_once divino_THEME_DIR . 'inc/core/class-gutenberg-editor-css.php';
require_once divino_THEME_DIR . 'inc/core/class-divino-wp-editor-css.php';
require_once divino_THEME_DIR . 'inc/dynamic-css/block-editor-compatibility.php';
require_once divino_THEME_DIR . 'inc/dynamic-css/inline-on-mobile.php';
require_once divino_THEME_DIR . 'inc/dynamic-css/content-background.php';
require_once divino_THEME_DIR . 'inc/dynamic-css/dark-mode.php';
require_once divino_THEME_DIR . 'inc/class-divino-dynamic-css.php';
require_once divino_THEME_DIR . 'inc/class-divino-global-palette.php';

// Enable NPS Survey only if the starter templates version is < 4.3.7 or > 4.4.4 to prevent fatal error.
if ( ! defined( 'divino_SITES_VER' ) || version_compare( divino_SITES_VER, '4.3.7', '<' ) || version_compare( divino_SITES_VER, '4.4.4', '>' ) ) {
	// NPS Survey Integration
	require_once divino_THEME_DIR . 'inc/lib/class-divino-nps-notice.php';
	require_once divino_THEME_DIR . 'inc/lib/class-divino-nps-survey.php';
}

/**
 * Custom template tags for this theme.
 */
require_once divino_THEME_DIR . 'inc/core/class-divino-attr.php';
require_once divino_THEME_DIR . 'inc/template-tags.php';

require_once divino_THEME_DIR . 'inc/widgets.php';
require_once divino_THEME_DIR . 'inc/core/theme-hooks.php';
require_once divino_THEME_DIR . 'inc/admin-functions.php';
require_once divino_THEME_DIR . 'inc/core/sidebar-manager.php';

/**
 * Markup Functions
 */
require_once divino_THEME_DIR . 'inc/markup-extras.php';
require_once divino_THEME_DIR . 'inc/extras.php';
require_once divino_THEME_DIR . 'inc/blog/blog-config.php';
require_once divino_THEME_DIR . 'inc/blog/blog.php';
require_once divino_THEME_DIR . 'inc/blog/single-blog.php';

/**
 * Markup Files
 */
require_once divino_THEME_DIR . 'inc/template-parts.php';
require_once divino_THEME_DIR . 'inc/class-divino-loop.php';
require_once divino_THEME_DIR . 'inc/class-divino-mobile-header.php';

/**
 * Functions and definitions.
 */
require_once divino_THEME_DIR . 'inc/class-divino-after-setup-theme.php';

// Required files.
require_once divino_THEME_DIR . 'inc/core/class-divino-admin-helper.php';

require_once divino_THEME_DIR . 'inc/schema/class-divino-schema.php';

/* Setup API */
require_once divino_THEME_DIR . 'admin/includes/class-divino-api-init.php';

if ( is_admin() ) {
	/**
	 * Admin Menu Settings
	 */
	require_once divino_THEME_DIR . 'inc/core/class-divino-admin-settings.php';
	require_once divino_THEME_DIR . 'admin/class-divino-admin-loader.php';
	require_once divino_THEME_DIR . 'inc/lib/divino-notices/class-divino-notices.php';
}

/**
 * Metabox additions.
 */
require_once divino_THEME_DIR . 'inc/metabox/class-divino-meta-boxes.php';
require_once divino_THEME_DIR . 'inc/metabox/class-divino-meta-box-operations.php';
require_once divino_THEME_DIR . 'inc/metabox/class-divino-elementor-editor-settings.php';

/**
 * Customizer additions.
 */
require_once divino_THEME_DIR . 'inc/customizer/class-divino-customizer.php';

/**
 * divino Modules.
 */
require_once divino_THEME_DIR . 'inc/modules/posts-structures/class-divino-post-structures.php';
require_once divino_THEME_DIR . 'inc/modules/related-posts/class-divino-related-posts.php';

/**
 * Compatibility
 */
require_once divino_THEME_DIR . 'inc/compatibility/class-divino-gutenberg.php';
require_once divino_THEME_DIR . 'inc/compatibility/class-divino-jetpack.php';
require_once divino_THEME_DIR . 'inc/compatibility/woocommerce/class-divino-woocommerce.php';
require_once divino_THEME_DIR . 'inc/compatibility/edd/class-divino-edd.php';
require_once divino_THEME_DIR . 'inc/compatibility/lifterlms/class-divino-lifterlms.php';
require_once divino_THEME_DIR . 'inc/compatibility/learndash/class-divino-learndash.php';
require_once divino_THEME_DIR . 'inc/compatibility/class-divino-beaver-builder.php';
require_once divino_THEME_DIR . 'inc/compatibility/class-divino-bb-ultimate-addon.php';
require_once divino_THEME_DIR . 'inc/compatibility/class-divino-contact-form-7.php';
require_once divino_THEME_DIR . 'inc/compatibility/class-divino-visual-composer.php';
require_once divino_THEME_DIR . 'inc/compatibility/class-divino-site-origin.php';
require_once divino_THEME_DIR . 'inc/compatibility/class-divino-gravity-forms.php';
require_once divino_THEME_DIR . 'inc/compatibility/class-divino-bne-flyout.php';
require_once divino_THEME_DIR . 'inc/compatibility/class-divino-ubermeu.php';
require_once divino_THEME_DIR . 'inc/compatibility/class-divino-divi-builder.php';
require_once divino_THEME_DIR . 'inc/compatibility/class-divino-amp.php';
require_once divino_THEME_DIR . 'inc/compatibility/class-divino-yoast-seo.php';
require_once divino_THEME_DIR . 'inc/compatibility/surecart/class-divino-surecart.php';
require_once divino_THEME_DIR . 'inc/compatibility/class-divino-starter-content.php';
require_once divino_THEME_DIR . 'inc/addons/transparent-header/class-divino-ext-transparent-header.php';
require_once divino_THEME_DIR . 'inc/addons/breadcrumbs/class-divino-breadcrumbs.php';
require_once divino_THEME_DIR . 'inc/addons/scroll-to-top/class-divino-scroll-to-top.php';
require_once divino_THEME_DIR . 'inc/addons/heading-colors/class-divino-heading-colors.php';
require_once divino_THEME_DIR . 'inc/builder/class-divino-builder-loader.php';

// Elementor Compatibility requires PHP 5.4 for namespaces.
if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
	require_once divino_THEME_DIR . 'inc/compatibility/class-divino-elementor.php';
	require_once divino_THEME_DIR . 'inc/compatibility/class-divino-elementor-pro.php';
	require_once divino_THEME_DIR . 'inc/compatibility/class-divino-web-stories.php';
}

// Beaver Themer compatibility requires PHP 5.3 for anonymous functions.
if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {
	require_once divino_THEME_DIR . 'inc/compatibility/class-divino-beaver-themer.php';
}

require_once divino_THEME_DIR . 'inc/core/markup/class-divino-markup.php';

/**
 * Load deprecated functions
 */
require_once divino_THEME_DIR . 'inc/core/deprecated/deprecated-filters.php';
require_once divino_THEME_DIR . 'inc/core/deprecated/deprecated-hooks.php';
require_once divino_THEME_DIR . 'inc/core/deprecated/deprecated-functions.php';


