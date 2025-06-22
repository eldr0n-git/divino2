<?php
/**
 * Plugin Name: DIVINO Wine Grape Varieties
 * Description: Добавляет сорт(ы) винограда с процентом для товаров с типом "Вино".
 * Version: 1.1
 * Author: eldr0n
 * Website: https://divino.kz
 */

add_action('init', function () {
    if (!class_exists('WooCommerce')) return;

    register_post_type('grape_variety', [
        'labels' => [
            'name' => 'Сорта винограда',
            'singular_name' => 'Сорт винограда',
            'add_new' => 'Добавить сорт',
            'add_new_item' => 'Добавить новый сорт винограда',
            'edit_item' => 'Редактировать сорт',
            'new_item' => 'Новый сорт',
            'view_item' => 'Посмотреть сорт',
            'search_items' => 'Поиск сортов',
            'menu_name' => 'Сорта винограда',
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'edit.php?post_type=product',
        'supports' => ['title', 'editor'],
        'menu_icon' => 'dashicons-carrot',
    ]);
});

add_action('add_meta_boxes', function () {
    // Метабокс для цвета сорта
    add_meta_box(
        'grape_color',
        'Цвет сорта',
        function ($post) {
            $value = get_post_meta($post->ID, '_grape_color', true);
            ?>
            <select name="grape_color">
                <option value="">Выберите цвет</option>
                <option value="white" <?= selected($value, 'white') ?>>Белый</option>
                <option value="black" <?= selected($value, 'black') ?>>Черный</option>
            </select>
            <?php
        },
        'grape_variety',
        'side'
    );

    // Метабокс для продуктов
    add_meta_box(
        'wine_grape_varieties',
        'Сорта винограда',
        'render_grape_variety_box',
        'product',
        'normal',
        'default'
    );
});

add_action('save_post_grape_variety', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST['grape_color'])) {
        update_post_meta($post_id, '_grape_color', sanitize_text_field($_POST['grape_color']));
    }
});

add_action('save_post_product', function ($post_id) {
    if (!isset($_POST['grape_varieties_nonce']) || !wp_verify_nonce($_POST['grape_varieties_nonce'], 'save_grape_varieties')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (!current_user_can('edit_post', $post_id)) return;

    $data = $_POST['grape_varieties'] ?? [];

    $cleaned = [];
    foreach ($data as $entry) {
        $name = sanitize_text_field($entry['name'] ?? '');
        $percent = floatval($entry['percent'] ?? 0);
        if ($name !== '') {
            $cleaned[] = ['name' => $name, 'percent' => $percent];
        }
    }

    update_post_meta($post_id, '_grape_varieties', $cleaned);
});

function render_grape_variety_box($post) {
    $terms = wp_get_post_terms($post->ID, 'product_kind', ['fields' => 'slugs']);
    if (!in_array('wine', $terms)) {
        echo '<p>Доступно только для товаров типа "Вино".</p>';
        return;
    }

    $varieties = get_post_meta($post->ID, '_grape_varieties', true);
    if (!is_array($varieties)) {
        $varieties = [];
    }

    wp_nonce_field('save_grape_varieties', 'grape_varieties_nonce');

    echo '<div id="grape-varieties-wrapper">';
    foreach ($varieties as $index => $item) {
        echo '<div class="grape-variety-row">';
        printf(
            '<input class="grape-name" name="grape_varieties[%d][name]" value="%s" placeholder="Сорт винограда" style="width: 45%%;" />',
            $index,
            esc_attr($item['name'])
        );
        printf(
            ' <input name="grape_varieties[%d][percent]" value="%s" placeholder="Процент" style="width: 20%%;" />',
            $index,
            esc_attr($item['percent'])
        );
        echo '</div>';
    }
    echo '</div>';
    echo '<button type="button" onclick="addGrapeVarietyRow()">Добавить сорт</button>';

    // Получаем все названия сортов для автозаполнения
    $all_grapes = get_posts([
        'post_type' => 'grape_variety',
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ]);

    $grape_names = array_map(function ($post) {
        return esc_js($post->post_title);
    }, $all_grapes);

    $grape_names_js = '';
    if (!empty($grape_names)) {
        $quoted = array_map(fn($name) => '"' . $name . '"', $grape_names);
        $grape_names_js = implode(',', $quoted);
    }

    echo <<<HTML
<script>
function addGrapeVarietyRow() {
    const wrapper = document.getElementById('grape-varieties-wrapper');
    const index = wrapper.children.length;
    const div = document.createElement('div');
    div.className = 'grape-variety-row';
    div.innerHTML = `
        <input class="grape-name" name="grape_varieties[\${index}][name]" placeholder="Сорт винограда" style="width: 45%;" />
        <input name="grape_varieties[\${index}][percent]" placeholder="Процент" style="width: 20%;" />
    `;
    wrapper.appendChild(div);
    initAutocomplete(); // переинициализация
}

function initAutocomplete() {
    const source = [{$grape_names_js}];
    const inputs = document.querySelectorAll('.grape-name');
    inputs.forEach(input => {
        if (!input.dataset.autocomplete) {
            jQuery(input).autocomplete({ source });
            input.dataset.autocomplete = 'true';
        }
    });
}

document.addEventListener('DOMContentLoaded', initAutocomplete);
</script>
HTML;

}


//
// Вывод сортов винограда на странице товара
add_action('woocommerce_single_product_summary', 'divino_show_grape_varieties', 25);

function divino_show_grape_varieties() {
    global $post;

    // Только для товаров с типом "Вино"
    $terms = wp_get_post_terms($post->ID, 'product_kind', ['fields' => 'slugs']);
    if (!in_array('wine', $terms)) {
        return;
    }

    $varieties = get_post_meta($post->ID, '_grape_varieties', true);
    if (empty($varieties) || !is_array($varieties)) {
        return;
    }

    echo '<div class="grape-varieties"><h3>Сорта винограда</h3><ul>';
    foreach ($varieties as $item) {
        $name = esc_html($item['name'] ?? '');
        $percent = esc_html($item['percent'] ?? '');
        if ($name) {
            echo "<li>{$name}" . ($percent !== '' ? " — {$percent}%" : '') . "</li>";
        }
    }
    echo '</ul></div>';
}




