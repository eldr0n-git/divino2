<?php
/*
Plugin Name: WooCommerce Product Types & Brands
Description: Добавляет таксономии "Тип товара" и "Производитель" для товаров WooCommerce.
Version: 1.02
Author: eldr0n
Website: https://divino.kz
*/
// Запрещаем прямой доступ к файлу
if (!defined('ABSPATH')) {
    exit;
}
// !!!! ТИПЫ ТОВАРОВ !!!!
add_filter('divino_product_kinds', function () {
    return ['spirits', 'softspirits', 'wine', 'champagne-and-sparkling'];
});
// Регистрируем кастомные таксономии
add_action('init', function () {
    // Тип товара
    register_taxonomy('product_kind', 'product', [
        'labels' => [
            'name' => 'Типы товара',
            'singular_name' => 'Тип товара',
            'add_new_item' => 'Добавить тип товара',
            'edit_item' => 'Редактировать тип',
            'search_items' => 'Поиск типов',
        ],
        'public' => true,
        'hierarchical' => true, // как категории
        'show_ui' => true,
        'show_in_menu' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'kind'],
    ]);

    // Производитель
    register_taxonomy('product_brand', 'product', [
        'labels' => [
            'name' => 'Производители',
            'singular_name' => 'Производитель',
            'add_new_item' => 'Добавить производителя',
            'edit_item' => 'Редактировать производителя',
            'search_items' => 'Поиск производителей',
        ],
        'public' => true,
        'hierarchical' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'brand'],
    ]);
});

// Улучшаем UX для производителей — автодополнение
add_action('admin_enqueue_scripts', function () {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'product') {
        wp_enqueue_script('autocomplete-brand', plugin_dir_url(__FILE__) . 'autocomplete-brand.js', ['jquery'], null, true);
        wp_enqueue_style('autocomplete-brand-style', plugin_dir_url(__FILE__) . 'autocomplete-brand.css');
    }
});

// Добавляем стили для админки
add_action('admin_enqueue_scripts', function ($hook) {
    // Только на страницах списка товаров
    if ($hook === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'product') {
        wp_enqueue_style(
            'my-admin-style',
            plugin_dir_url(__FILE__) . 'assets/admin.css',
            [],
            filemtime(plugin_dir_path(__FILE__) . 'assets/admin.css')
        );
    }
});

