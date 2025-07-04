<?php
/**
 * divino Theme Customizer Controls.
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$divino_control_dir = divino_THEME_DIR . 'inc/customizer/custom-controls';

// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require $divino_control_dir . '/class-divino-customizer-control-base.php';
require $divino_control_dir . '/typography/class-divino-control-typography.php';
require_once $divino_control_dir . '/logo-svg-icon/class-divino-control-logo-svg-icon.php';
require $divino_control_dir . '/description/class-divino-control-description.php';
require $divino_control_dir . '/customizer-link/class-divino-control-customizer-link.php';
// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
