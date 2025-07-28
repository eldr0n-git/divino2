<?php
/**
 * Plugin Name: DIVINO Product Volume
 * Description: Характеристика "Объем" для товаров
 * Version: 1.0
 * Author: eldr0n
 * Website: https://divino.kz
 */
// Запрещаем прямой доступ к файлу
if (!defined('ABSPATH')) {
    exit;
}

 add_action('add_meta_boxes', function () {
    add_meta_box(
        'product_volume_box',
        'Объём',
        'divino_render_volume_box',
        'product',
        'side',
        'default'
    );
});

function divino_render_volume_box($post) {
    $allowed_kinds = ['wine', 'softspirits', 'champagne-and-sparkling', 'spirits']; // Разрешенные типы товаров
    $terms = wp_get_post_terms($post->ID, 'product_kind', ['fields' => 'slugs']);

    if (!array_intersect($allowed_kinds, $terms)) {
        echo '<p>Доступно только для некоторых типов товаров.</p>';
        return;
    }

    $volume = get_post_meta($post->ID, '_product_volume', true);
    $common_volumes = [750, 1500, 375]; // Можно адаптировать под нужды

    ?>
    <label for="product_volume">Объём (мл):</label>
    <input type="number" id="product_volume" name="product_volume" value="<?= esc_attr($volume) ?>" style="width:100%;" />

    <p style="margin-top: 8px; font-size: 13px;">Популярные:</p>
    <div id="common-volumes">
        <?php foreach ($common_volumes as $val): ?>
            <button type="button" class="button volume-suggestion" data-value="<?= $val ?>" style="margin: 2px 4px 4px 0;"><?= $val ?> мл</button>
        <?php endforeach; ?>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.volume-suggestion').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('product_volume').value = this.dataset.value;
            });
        });
    });
    </script>
    <?php
}


add_action('save_post_product', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['product_volume'])) {
        update_post_meta($post_id, '_product_volume', floatval($_POST['product_volume']));
    }
});

// Отобразить на странице товара
add_action('woocommerce_single_product_summary', function () {
    global $post;
    $volume = get_post_meta($post->ID, '_product_volume', true);

    $terms = wp_get_post_terms($post->ID, 'product_kind', ['fields' => 'slugs']);
    if (!empty($volume)) {
        echo '<div class="product-volume">';
        echo '<strong>Объём:</strong>';
        echo '<span>' . esc_html($volume) . ' мл</span>';
        echo '</div>';
    }

}, 26);
