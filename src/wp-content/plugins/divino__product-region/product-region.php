<?php
/**
 * Plugin Name: DIVINO Product Regions
 * Description: Добавляет таксономию "Регион" для товаров WooCommerce с иерархией, автозаполнением и фильтрацией.
 * Version: 1.0
 * Author: eldr0n
 */
// Запрещаем прямой доступ к файлу
if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function () {
    register_taxonomy('region', 'product', [
        'labels' => [
            'name' => 'Регионы',
            'singular_name' => 'Регион',
            'search_items' => 'Поиск регионов',
            'all_items' => 'Все регионы',
            'parent_item' => 'Родительский регион',
            'parent_item_colon' => 'Родитель:',
            'edit_item' => 'Редактировать регион',
            'update_item' => 'Обновить регион',
            'add_new_item' => 'Добавить новый регион',
            'new_item_name' => 'Название нового региона',
            'menu_name' => 'Регионы',
        ],
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'region'],
    ]);
});

// Фильтр по региону в админке
add_filter('parse_query', function ($query) {
    global $pagenow;
    if ($pagenow === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'product' && isset($_GET['region'])) {
        $query->query_vars['tax_query'][] = [
            'taxonomy' => 'region',
            'field'    => 'slug',
            'terms'    => $_GET['region'],
        ];
    }
});
// При сохранении товара автоматически добавляем все родительские регионы
add_action('save_post_product', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $term_ids = wp_get_post_terms($post_id, 'region', ['fields' => 'ids']);
    if (empty($term_ids) || is_wp_error($term_ids)) return;

    $all_ids = $term_ids;
    foreach ($term_ids as $term_id) {
        $ancestors = get_ancestors($term_id, 'region', 'taxonomy');
        $all_ids   = array_merge($all_ids, $ancestors);
    }
    $all_ids = array_unique(array_map('intval', $all_ids));

    if (count($all_ids) !== count($term_ids)) {
        wp_set_post_terms($post_id, $all_ids, 'region');
    }
}, 20);

// Вывод региона на странице товара — от родителя к дочернему
add_action('woocommerce_single_product_summary', function () {
    $terms = get_the_terms(get_the_ID(), 'region');
    if (empty($terms) || is_wp_error($terms)) return;

    // Сортируем: сначала корневые (parent=0), потом дочерние
    usort($terms, fn($a, $b) => $a->parent - $b->parent);

    $names = array_map(fn($t) => esc_html($t->name), $terms);
    echo '<div class="product-region"><strong>Регион:</strong> ' . implode(', ', $names) . '</div>';
}, 21);



// Добавим автозаполнение на странице редактирования товара
add_action('admin_enqueue_scripts', 'divino_enqueue_region_autocomplete');

function divino_enqueue_region_autocomplete($hook) {
    // Проверяем, что это редактирование товара
    $screen = get_current_screen();
    if ($screen->post_type !== 'product') return;

    $terms = get_terms([
        'taxonomy' => 'region',
        'hide_empty' => false,
    ]);

    if (empty($terms) || is_wp_error($terms)) return;

    $region_names = array_map(function ($term) {
        return esc_js($term->name);
    }, $terms);

    $js_array = json_encode($region_names);

    // Добавим JS напрямую в админку
    add_action('admin_footer', function () use ($js_array) {
        echo <<<HTML
<script>
jQuery(document).ready(function($) {
    const regionTerms = $js_array;
    $('input.tax_input_region').autocomplete({
        source: regionTerms,
        minLength: 1
    });
});
</script>
HTML;
    });
}

