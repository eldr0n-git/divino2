<?php
/**
 * Plugin Name: Divino Slider
 * Description: Простой слайдер с управлением слайдами через админ-панель и динамическим блоком Гутенберга.
 * Version: 1.0.0
 * Author: eldr0n
 * Text Domain: divino-slider
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'DIVINO_SLIDER_PATH', plugin_dir_path( __FILE__ ) );
define( 'DIVINO_SLIDER_URL',  plugin_dir_url( __FILE__ ) );

require_once DIVINO_SLIDER_PATH . 'includes/class-divino-slider.php';

function divino_slider_activate() {
    // Flush rewrite on activation to register CPT routes
    divino_slider(); // ensure class is loaded/instantiated
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'divino_slider_activate' );

function divino_slider_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'divino_slider_deactivate' );

/**
 * Bootstrap singleton
 */
function divino_slider() {
    static $instance = null;
    if ( null === $instance ) {
        $instance = new Divino_Slider_Plugin();
    }
    return $instance;
}
divino_slider();
