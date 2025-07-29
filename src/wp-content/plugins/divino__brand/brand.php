<?php
/*
Plugin Name: WooCommerce Product Brand (Autocomplete)
Description: Добавляет поле "Производитель" к товарам WooCommerce с автозаполнением.
Version: 1.0
Author: eldr0n
Website: https://divino.kz
*/
// Запрещаем прямой доступ к файлу
if (!defined('ABSPATH')) {
    exit;
}
add_action('add_meta_boxes', function () {
    add_meta_box(
        'product_brand_meta_box',
        __('Производитель', 'woocommerce'),
        'render_brand_meta_box',
        'product',
        'side',
        'default'
    );
});

function render_brand_meta_box($post) {
    $value = get_post_meta($post->ID, '_product_brand', true);
    $all_brands = get_all_product_brands();

    echo '<label for="product_brand_field">Введите или выберите:</label><br>';
    echo '<input type="text" list="brand_suggestions" id="product_brand_field" name="product_brand_field" value="' . esc_attr($value) . '" style="width:100%;" />';
    echo '<datalist id="brand_suggestions">';
    foreach ($all_brands as $brand) {
        echo '<option value="' . esc_attr($brand) . '">';
    }
    echo '</datalist>';
}

add_action('save_post_product', function ($post_id) {
    if (isset($_POST['product_brand_field'])) {
        update_post_meta($post_id, '_product_brand', sanitize_text_field($_POST['product_brand_field']));
    }
});

function get_all_product_brands() {
    global $wpdb;
    $results = $wpdb->get_col("
        SELECT DISTINCT meta_value
        FROM $wpdb->postmeta
        WHERE meta_key = '_product_brand'
        AND meta_value != ''
    ");
    return array_filter(array_unique($results));
}

// Показываем на странице товара
add_action('woocommerce_single_product_summary', function () {
    global $product;
    $brand = get_post_meta($product->get_id(), '_product_brand', true);
    if ($brand) {
        echo '<p><strong>Производитель:</strong> ' . esc_html($brand) . '</p>';
    }
}, 10);

add_action('wp_ajax_get_all_product_brands', function () {
    $terms = get_terms([
        'taxonomy' => 'product_brand',
        'hide_empty' => false,
    ]);

    $result = array_map(function ($term) {
        return [
            'label' => $term->name,
            'value' => $term->term_id,
        ];
    }, $terms);

    wp_send_json($result);
});
