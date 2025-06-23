<?php
/*
Plugin Name: DIVINO Serving Products
Description: Добавляет поле "Температура сервировки" к товарам WooCommerce.
Version: 1.00
Author: eldr0n
Website: https://divino.kz
*/

// Запрещаем прямой доступ к файлу
if (!defined('ABSPATH')) {
    exit;
}


add_action('woocommerce_product_options_general_product_data', 'add_serving_temperature_field');
function add_serving_temperature_field() {
    global $post;
    
    if (has_term(apply_filters('divino_product_kinds', []), 'product_kind', $post)) {
        woocommerce_wp_text_input([
            'id' => 'serving_temperature',
            'label' => __('Температура сервировки (градусов цельсия)', 'woocommerce'),
            'desc_tip' => true,
            'description' => __('Введите температуру сервировки', 'woocommerce'),
            'type' => 'text'
        ]);
    }
}

add_action('woocommerce_process_product_meta', 'save_serving_temperature_field');
function save_serving_temperature_field($post_id) {
    if (isset($_POST['serving_temperature'])) {
        update_post_meta($post_id, 'serving_temperature', sanitize_text_field($_POST['serving_temperature']));
    }
}

add_action('woocommerce_single_product_summary', 'display_serving_temperature', 25);
function display_serving_temperature() {
    global $product;
    
    if (!has_term(apply_filters('divino_product_kinds', []), 'product_kind', $product->get_id())) {
        return;
    }
    
    $value = get_post_meta($product->get_id(), 'serving_temperature', true);
    
    if ( $value !== '' ) {
        echo '<p><strong>Температура сервировки:</strong> ' . esc_html($value) . '&deg;</p>';
    }
}