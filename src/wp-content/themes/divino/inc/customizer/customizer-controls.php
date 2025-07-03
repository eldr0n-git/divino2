<?php
/**
 * Astra Theme Customizer Controls.
 *
 * @package     Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$divino_control_dir = divino_THEME_DIR . 'inc/customizer/custom-controls';

// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require $divino_control_dir . '/class-astra-customizer-control-base.php';
require $divino_control_dir . '/typography/class-astra-control-typography.php';
require_once $divino_control_dir . '/logo-svg-icon/class-astra-control-logo-svg-icon.php';
require $divino_control_dir . '/description/class-astra-control-description.php';
require $divino_control_dir . '/customizer-link/class-astra-control-customizer-link.php';
// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
