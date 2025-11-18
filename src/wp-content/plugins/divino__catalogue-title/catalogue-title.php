<?php
/**
 * Plugin Name: DIVINO Cataloguer Product Kind Title
 * Description: Изменяет заголовок архива для таксономии product_kind
 * Version: 1.0
 * Author: eldr0n
 * Website: https://divino.kz
 */

// Запрещаем прямой доступ к файлу
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Изменяет заголовок архива для product_kind
 */
add_filter('get_the_archive_title', function ($title) {
    // Проверяем, находимся ли мы на странице таксономии product_kind
    if (is_tax('product_kind')) {
        $term = get_queried_object();
        if ($term && !is_wp_error($term)) {
            // Возвращаем только название термина без префикса
            return $term->name;
        }
    }

    return $title;
}, 10, 1);

/**
 * Дополнительно обрабатываем legacy формат ?product_kind=white-wine
 */
add_filter('get_the_archive_title', function ($title) {
    // Если это не таксономия, но есть параметр product_kind в URL
    if (!is_tax('product_kind') && isset($_GET['product_kind']) && !empty($_GET['product_kind'])) {
        $term_slug = sanitize_text_field($_GET['product_kind']);
        $term = get_term_by('slug', $term_slug, 'product_kind');

        if ($term && !is_wp_error($term)) {
            return $term->name;
        }
    }

    return $title;
}, 20, 1);

/**
 * Убираем префикс "Тип товара:" из заголовка
 */
add_filter('get_the_archive_title_prefix', function ($prefix) {
    if (is_tax('product_kind')) {
        return '';
    }

    return $prefix;
});
