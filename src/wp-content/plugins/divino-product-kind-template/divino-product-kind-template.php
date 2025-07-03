<?php
/**
 * Plugin Name: DIVINO Product Kind Template Fix (Forced)
 * Description: Принудительно подключает шаблон WooCommerce для product_kind и применяет обёртку темы.
 * Version: 1.1
 * Author: eldr0n
 * Text Domain: divino-product-kind-template
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// DEBUG: Показываем, что плагин загружен
add_action( 'init', function() {
    if ( is_tax( 'product_kind' ) ) {
        add_action( 'wp_head', function() {
            echo "<!-- DIVINO PLUGIN ACTIVE: product_kind -->\n";
        });
    }
});

// Подменяем основной шаблон страницы (принудительно)
add_filter( 'template_include', function( $template ) {
    if ( is_tax( 'product_kind' ) ) {
        $custom_template = get_stylesheet_directory() . '/woocommerce/archive-product.php';
        if ( file_exists( $custom_template ) ) {
            return $custom_template;
        } else {
            // DEBUG: если шаблон не найден
            add_action( 'wp_head', function() {
                echo "<!-- archive-product.php NOT FOUND in theme -->\n";
            });
        }
    }
    return $template;
}, 99); // приоритет 99 — чтобы было ПОСЛЕ WooCommerce

// Убедимся, что WooCommerce считает страницу своей
add_filter( 'woocommerce_is_woocommerce', function( $is_woocommerce ) {
    if ( is_tax( 'product_kind' ) ) {
        return true;
    }
    return $is_woocommerce;
}, 20);

// Правильный тип записи для product_kind
add_action( 'pre_get_posts', function( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( is_tax( 'product_kind' ) ) {
        $query->set( 'post_type', 'product' );
    }
});
