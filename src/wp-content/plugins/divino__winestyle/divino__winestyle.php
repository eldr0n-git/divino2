<?php
/**
 * Plugin Name: Divino – Wine Style
 * Description: Добавляет стили вина к товарам с типом 'wine' (product_kind).
 * Version: 1.1
 * Author: Dmitriy Mossin
 */

if (!defined('ABSPATH')) exit;

define('DIVINO_WINESTYLE_PATH', plugin_dir_path(__FILE__));

// Include shortcode functionality
require_once DIVINO_WINESTYLE_PATH . 'includes/shortcode.php';

// require_once DIVINO_WINESTYLE_PATH . 'includes/admin-settings.php';

// Регистрация таксономии "Стиль вина"
function divino_register_wine_style_taxonomy() {
    $labels = [
        'name'              => _x('Стили вина', 'taxonomy general name'),
        'singular_name'     => _x('Стиль вина', 'taxonomy singular name'),
        'search_items'      => __('Искать стили'),
        'all_items'         => __('Все стили'),
        'parent_item'       => __('Родительский стиль'),
        'parent_item_colon' => __('Родительский стиль:'),
        'edit_item'         => __('Редактировать стиль'),
        'update_item'       => __('Обновить стиль'),
        'add_new_item'      => __('Добавить новый стиль'),
        'new_item_name'     => __('Название нового стиля'),
        'menu_name'         => __('Стили вина'),
    ];

    $args = [
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'wine-style'],
        'show_in_menu'      => 'edit.php?post_type=product',
    ];

    register_taxonomy('wine_style', ['product'], $args);
}
add_action('init', 'divino_register_wine_style_taxonomy');


// // Подключаем метабокс на страницу редактирования товара
// add_action('add_meta_boxes', 'divino_add_wine_style_metabox');
// function divino_add_wine_style_metabox() {
//     global $post;
//     $product_kind = wp_get_post_terms($post->ID, 'product_kind', ['fields' => 'slugs']);
//     if (in_array('wine', $product_kind)) {
//         add_meta_box(
//             'divino_wine_style_metabox',
//             'Стиль вина',
//             'divino_render_wine_style_metabox',
//             'product',
//             'side'
//         );
//     }
// }

// function divino_render_wine_style_metabox($post) {
//     $selected_styles = (array) get_post_meta($post->ID, '_divino_wine_styles', true);
//     $options = get_option('divino_wine_styles', []);

//     echo '<select multiple name="divino_wine_styles[]" style="width:100%; min-height: 100px;">';
//     foreach ($options as $style) {
//         $selected = in_array($style, $selected_styles) ? 'selected' : '';
//         echo "<option value='" . esc_attr($style) . "' $selected>" . esc_html($style) . "</option>";
//     }
//     echo '</select>';
// }

// add_action('save_post_product', function ($post_id) {
//     if (isset($_POST['divino_wine_styles'])) {
//         update_post_meta($post_id, '_divino_wine_styles', array_map('sanitize_text_field', $_POST['divino_wine_styles']));
//     } else {
//         delete_post_meta($post_id, '_divino_wine_styles');
//     }
// });

// Хук для миграции данных
register_activation_hook(__FILE__, 'divino_migrate_wine_styles_data');

function divino_migrate_wine_styles_data() {
    // Убедимся, что это разовый запуск
    if (get_option('divino_wine_style_migration_done')) {
        return;
    }

    // 1. Переносим основные стили из опций в термы
    $options = get_option('divino_wine_styles', []);
    if (!empty($options)) {
        foreach ($options as $style_name) {
            if (!term_exists($style_name, 'wine_style')) {
                wp_insert_term($style_name, 'wine_style');
            }
        }
    }

    // 2. Переносим мета-данные продуктов
    $products = get_posts([
        'post_type' => 'product',
        'numberposts' => -1,
        'meta_query' => [
            [
                'key' => '_divino_wine_styles',
                'compare' => 'EXISTS'
            ]
        ]
    ]);

    foreach ($products as $product) {
        $styles = get_post_meta($product->ID, '_divino_wine_styles', true);
        if (!empty($styles)) {
            wp_set_post_terms($product->ID, $styles, 'wine_style', false);
        }
    }

    // Ставим флаг, что миграция завершена
    update_option('divino_wine_style_migration_done', true);
}


