<?php
/**
 * Plugin Name: DIVINO Product Volume
 * Description: Характеристика "Объем" для товаров
 * Version: 1.1
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
    $volume_ml = get_post_meta($post->ID, '_product_volume', true);

    $terms = wp_get_post_terms($post->ID, 'product_kind', ['fields' => 'slugs']);
    if (!empty($volume_ml)) {
        // Конвертируем миллилитры в литры
        $volume_l = $volume_ml / 1000;

        // Форматируем с запятой как десятичный разделитель
        $volume_formatted = str_replace('.', ',', number_format($volume_l, 2));

        // Убираем незначащие нули после запятой
        $volume_formatted = rtrim($volume_formatted, '0');
        $volume_formatted = rtrim($volume_formatted, ',');

        echo '<div class="product-volume">';
        echo '<strong>Объём:</strong>';
        echo '<span>' . esc_html($volume_formatted) . ' л</span>';
        echo '</div>';
    }
}, 26);

/**
 * Добавляет объем к заголовку товара
 */
add_filter('the_title', function ($title, $post_id = null) {
    // Проверяем, что это товар
    if (get_post_type($post_id) !== 'product') {
        return $title;
    }

    // Исключаем админку, RSS и служебные контексты
    if (is_admin() || is_feed() || doing_filter('wp_title') || doing_filter('document_title')) {
        return $title;
    }

    // Проверяем, не добавлен ли уже объем
    if (strpos($title, 'product-title-volume') !== false) {
        return $title;
    }

    // Добавляем объем только на фронтенде для страниц товаров и каталога
    if (is_singular('product') || is_shop() || is_product_category() || is_product_tag() || is_archive()) {
        $volume = divino_get_product_volume($post_id);

        if (!empty($volume)) {
            $title .= ' <span class="product-title-volume">' . esc_html($volume) . '</span>';
        }
    }

    return $title;
}, 10, 2);

/**
 * Добавляем стили для объема в заголовке
 */
add_action('wp_head', function () {
    if (!is_singular('product')) {
        return;
    }
    ?>
    <style>
        .product-title-volume {
            font-weight: 400;
            color: #666;
            font-size: 0.85em;
        }

        h1.wp-block-post-title .product-title-volume {
            font-size: 0.75em;
        }
    </style>
    <?php
});

/**
 * Вспомогательная функция для получения объема товара
 *
 * @param int $product_id ID товара (опционально)
 * @return string Объем товара
 */
function divino_get_product_volume($product_id = 0) {
    if ($product_id === 0) {
        global $post;
        $product_id = $post->ID;
    }

    $volume_ml = get_post_meta($product_id, '_product_volume', true);

    if (empty($volume_ml)) {
        return '';
    }

    // Конвертируем миллилитры в литры
    $volume_l = $volume_ml / 1000;

    // Форматируем с запятой как десятичный разделитель
    $volume_formatted = str_replace('.', ',', number_format($volume_l, 2));

    // Убираем незначащие нули после запятой
    $volume_formatted = rtrim($volume_formatted, '0');
    $volume_formatted = rtrim($volume_formatted, ',');

    return $volume_formatted . ' л';
}

/**
 * Вспомогательная функция для вывода объема товара
 *
 * @param int $product_id ID товара (опционально)
 */
function divino_the_product_volume($product_id = 0) {
    echo esc_html(divino_get_product_volume($product_id));
}
