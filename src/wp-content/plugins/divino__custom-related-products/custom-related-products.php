<?php
/**
 * Plugin Name: Custom Related Products
 * Plugin URI:
 * Description: Кастомные похожие товары с приоритетом по таксономиям: wine_style, region, grape_variety, tags
 * Version: 1.0.0
 * Author: Your Name
 * Author URI:
 * License: GPL v2 or later
 * Text Domain: custom-related-products
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Custom_Related_Products {

    private $taxonomies_priority = array(
        'wine_style',
        'region',
        'grape_variety',
        'product_tag'
    );

    public function __construct() {
        // Заменяем стандартную функцию related products
        add_filter('woocommerce_related_products', array($this, 'get_custom_related_products'), 10, 3);
    }

    /**
     * Получить кастомные похожие товары
     */
    public function get_custom_related_products($related_products, $product_id, $args) {
        $related_products = array();
        $limit = isset($args['limit']) ? $args['limit'] : 4;

        // Получаем текущий товар
        $product = wc_get_product($product_id);
        if (!$product) {
            return $related_products;
        }

        // Исключаем текущий товар и товары не в наличии
        $exclude_ids = array($product_id);

        // Проходим по таксономиям в порядке приоритета
        foreach ($this->taxonomies_priority as $taxonomy) {
            // Если уже набрали достаточно товаров, выходим
            if (count($related_products) >= $limit) {
                break;
            }

            // Получаем термины текущего товара для этой таксономии
            $terms = wp_get_post_terms($product_id, $taxonomy, array('fields' => 'ids'));

            if (empty($terms) || is_wp_error($terms)) {
                continue;
            }

            // Ищем товары с такими же терминами
            $args_query = array(
                'post_type' => 'product',
                'posts_per_page' => $limit - count($related_products),
                'post__not_in' => array_merge($exclude_ids, $related_products),
                'post_status' => 'publish',
                'orderby' => 'rand',
                'tax_query' => array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'term_id',
                        'terms' => $terms,
                        'operator' => 'IN'
                    )
                ),
                'meta_query' => array(
                    array(
                        'key' => '_stock_status',
                        'value' => 'instock',
                        'compare' => '='
                    )
                )
            );

            $query = new WP_Query($args_query);

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $found_id = get_the_ID();
                    if (!in_array($found_id, $related_products)) {
                        $related_products[] = $found_id;
                    }
                }
                wp_reset_postdata();
            }
        }

        // Если не набрали нужное количество, добавляем случайные товары
        if (count($related_products) < $limit) {
            $random_args = array(
                'post_type' => 'product',
                'posts_per_page' => $limit - count($related_products),
                'post__not_in' => array_merge($exclude_ids, $related_products),
                'post_status' => 'publish',
                'orderby' => 'rand',
                'meta_query' => array(
                    array(
                        'key' => '_stock_status',
                        'value' => 'instock',
                        'compare' => '='
                    )
                )
            );

            $random_query = new WP_Query($random_args);

            if ($random_query->have_posts()) {
                while ($random_query->have_posts()) {
                    $random_query->the_post();
                    $found_id = get_the_ID();
                    if (!in_array($found_id, $related_products)) {
                        $related_products[] = $found_id;
                    }
                }
                wp_reset_postdata();
            }
        }

        return array_slice($related_products, 0, $limit);
    }
}

// Инициализация плагина
new Custom_Related_Products();
