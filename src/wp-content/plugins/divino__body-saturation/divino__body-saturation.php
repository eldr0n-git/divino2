<?php
/**
 * Plugin Name: Divino - Body Saturation
 * Description: Добавляет атрибут "Насыщенность" для вин.
 * Version: 1.0
 * Author: Gemini
 */

if (!defined('ABSPATH')) exit;

// Добавление метабокса
function divino_add_body_saturation_metabox() {
    global $post;
    if (!$post) return;

    $product_kind = wp_get_post_terms($post->ID, 'product_kind', ['fields' => 'slugs']);
    if (in_array('wine', $product_kind)) {
        add_meta_box(
            'divino_body_saturation_metabox',
            'Насыщенность',
            'divino_render_body_saturation_metabox',
            'product',
            'side',
            'high'
        );
    }
}
add_action('add_meta_boxes_product', 'divino_add_body_saturation_metabox');

// Рендер метабокса
function divino_render_body_saturation_metabox($post) {
    wp_nonce_field('divino_save_body_saturation', 'divino_body_saturation_nonce');
    $value = get_post_meta($post->ID, '_body_saturation', true);
    if (empty($value)) {
        $value = 4; // Значение по умолчанию
    }
    ?>
    <div class="divino-saturation-slider-container">
        <div class="divino-saturation-labels">
            <span class="label-left">Легкое</span>
            <span class="label-right">Полнотелое</span>
        </div>
        <input type="range" id="divino_body_saturation" name="divino_body_saturation" min="1" max="7" value="<?php echo esc_attr($value); ?>" class="divino-saturation-slider">
        <div id="divino-saturation-value-display"><?php echo esc_attr($value); ?></div>
    </div>
    <?php
}

// Сохранение значения
function divino_save_body_saturation($post_id) {
    if (!isset($_POST['divino_body_saturation_nonce']) || !wp_verify_nonce($_POST['divino_body_saturation_nonce'], 'divino_save_body_saturation')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['divino_body_saturation'])) {
        update_post_meta($post_id, '_body_saturation', sanitize_text_field($_POST['divino_body_saturation']));
    }
}
add_action('save_post_product', 'divino_save_body_saturation');

// Подключение скриптов и стилей
function divino_enqueue_saturation_assets($hook) {
    global $post;
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        if (isset($post->post_type) && $post->post_type === 'product') {
            wp_enqueue_style('divino-saturation-style', plugin_dir_url(__FILE__) . 'css/style.css');
            wp_enqueue_script('divino-saturation-script', plugin_dir_url(__FILE__) . 'js/script.js', ['jquery'], '1.0', true);
        }
    }
}
add_action('admin_enqueue_scripts', 'divino_enqueue_saturation_assets');
