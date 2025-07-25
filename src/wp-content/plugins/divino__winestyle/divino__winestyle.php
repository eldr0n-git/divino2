<?php
/**
 * Plugin Name: Divino – Wine Style
 * Description: Добавляет стили вина к товарам с типом 'wine' (product_kind).
 * Version: 1.0
 * Author: Dmitriy Mossin
 */

if (!defined('ABSPATH')) exit;

define('DIVINO_WINESTYLE_PATH', plugin_dir_path(__FILE__));

require_once DIVINO_WINESTYLE_PATH . 'includes/admin-settings.php';

// Подключаем метабокс на страницу редактирования товара
add_action('add_meta_boxes', 'divino_add_wine_style_metabox');
function divino_add_wine_style_metabox() {
    global $post;
    $product_kind = wp_get_post_terms($post->ID, 'product_kind', ['fields' => 'slugs']);
    if (in_array('wine', $product_kind)) {
        add_meta_box(
            'divino_wine_style_metabox',
            'Стиль вина',
            'divino_render_wine_style_metabox',
            'product',
            'side'
        );
    }
}

function divino_render_wine_style_metabox($post) {
    $selected_styles = (array) get_post_meta($post->ID, '_divino_wine_styles', true);
    $options = get_option('divino_wine_styles', []);

    echo '<select multiple name="divino_wine_styles[]" style="width:100%; min-height: 100px;">';
    foreach ($options as $style) {
        $selected = in_array($style, $selected_styles) ? 'selected' : '';
        echo "<option value='" . esc_attr($style) . "' $selected>" . esc_html($style) . "</option>";
    }
    echo '</select>';
}

add_action('save_post_product', function ($post_id) {
    if (isset($_POST['divino_wine_styles'])) {
        update_post_meta($post_id, '_divino_wine_styles', array_map('sanitize_text_field', $_POST['divino_wine_styles']));
    } else {
        delete_post_meta($post_id, '_divino_wine_styles');
    }
});
