<?php
/*
Plugin Name: WooCommerce Product Types & Brands
Description: Добавляет таксономии "Тип товара" и "Производитель" для товаров WooCommerce.
Version: 1.03
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
add_action('init', 'divino_register_taxonomies');

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
// Добавляем поддержку таксономии product_kind в WooCommerce для корректной работы с шаблонами
add_filter( 'woocommerce_is_woocommerce', function( $is_woocommerce ) {
    if ( is_tax( 'product_kind' ) ) {
        return true;
    }
    return $is_woocommerce;
});
add_action( 'pre_get_posts', function( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( is_tax( 'product_kind' ) ) {
        $query->set( 'post_type', 'product' );
    }
});

// Flush rewrite rules on plugin activation
register_activation_hook(__FILE__, 'divino_flush_rewrite_rules_on_activation');

function divino_flush_rewrite_rules_on_activation() {
    // Re-register taxonomies
    divino_register_taxonomies();
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Extract taxonomy registration into a separate function for reuse
function divino_register_taxonomies() {
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
        'show_in_nav_menus' => true,
        'hierarchical' => true, // как категории
        'show_ui' => true,
        'show_in_menu' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'product-kind', 'with_front' => false],
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
    
    // Add rewrite tag for legacy ?product_kind=red-wine format
    add_rewrite_tag('%product_kind%', '([^&]+)');
}

// Handle legacy ?product_kind=red-wine format by converting to taxonomy query
add_action('parse_request', function($wp) {
    if (isset($_GET['product_kind']) && !empty($_GET['product_kind'])) {
        $term_slug = sanitize_text_field($_GET['product_kind']);
        
        // Check if the term exists in the product_kind taxonomy
        $term = get_term_by('slug', $term_slug, 'product_kind');
        
        if ($term) {
            // Convert query var to taxonomy query
            $wp->query_vars['post_type'] = 'product';
            $wp->query_vars['product_kind'] = $term_slug;
            $wp->query_vars['is_tax'] = true;
            
            // Set the main query to handle this as a taxonomy archive
            global $wp_query;
            $wp_query->is_tax = true;
            $wp_query->is_archive = true;
            $wp_query->is_home = false;
            $wp_query->queried_object = $term;
            $wp_query->queried_object_id = $term->term_id;
        }
    }
});

