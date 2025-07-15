<?php
/**
 * Divino25 Filters
 *
 * @package divino25
 */

function divino25_add_filters() {
	do_action( 'woocommerce_before_main_content' );
}

function divino25_filter_scripts() {
    if (is_shop() || is_product_category() || is_product_tag() || is_tax('product_kind') || is_tax('region')) {
        wp_enqueue_script('divino25-filter-script', get_stylesheet_directory_uri() . '/assets/js/filters.js', array('jquery'), '1.0', true);
    }
}
add_action('wp_enqueue_scripts', 'divino25_filter_scripts');
