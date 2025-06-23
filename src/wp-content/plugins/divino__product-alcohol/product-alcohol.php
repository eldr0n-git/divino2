<?php
/*
Plugin Name: DIVINO Alcohol Percentage for Products
Description: Добавляет поле "Процент алкоголя" к товарам WooCommerce.
Version: 1.03
Author: eldr0n
Website: https://divino.kz
*/

// Запрещаем прямой доступ к файлу
if (!defined('ABSPATH')) {
    exit;
}

// Функция для получения категорий алкогольных товаров
function get_product_categories() {
    return ['spirits', 'softspirits', 'wine', 'champagne-and-sparkling'];
}

add_action('woocommerce_product_options_general_product_data', 'add_alcohol_percentage_field');
function add_alcohol_percentage_field() {
    global $post;
    
    if (has_term(get_product_categories(), 'product_kind', $post)) {
        woocommerce_wp_text_input([
            'id' => 'alcohol_percentage',
            'label' => __('Процент алкоголя (%)', 'woocommerce'),
            'desc_tip' => true,
            'description' => __('Введите процентное содержание алкоголя, например 12.5', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => [
                'step' => '0.1',
                'min' => '0'
            ]
        ]);
    }
}

add_action('woocommerce_process_product_meta', 'save_alcohol_percentage_field');
function save_alcohol_percentage_field($post_id) {
    if (isset($_POST['alcohol_percentage'])) {
        update_post_meta($post_id, 'alcohol_percentage', sanitize_text_field($_POST['alcohol_percentage']));
    }
}

add_action('woocommerce_single_product_summary', 'display_alcohol_percentage', 25);
function display_alcohol_percentage() {
    global $product;
    
    if (!has_term(get_product_categories(), 'product_kind', $product->get_id())) {
        return;
    }
    
    $value = get_post_meta($product->get_id(), 'alcohol_percentage', true);
    
    if ($value === '0' || $value === 0 || $value === '0.0' || $value === '0.00') {
        echo '<p><strong>Алкоголь:</strong> <span title="В напитке нет алкоголя">🚫 Безалкогольный</span></p>';
    } elseif (!empty($value)) {
        echo '<p><strong>Алкоголь:</strong> ' . esc_html($value) . '%</p>';
    }
}