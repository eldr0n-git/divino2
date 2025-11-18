<?php
/*
Plugin Name: WooCommerce Product Types & Brands
Description: Добавляет таксономии "Тип товара" и "Производитель" для товаров WooCommerce.
Version: 1.1
Author: eldr0n
Website: https://divino.kz
*/
// Запрещаем прямой доступ к файлу
if (!defined('ABSPATH')) {
    exit;
}

// !!!! ТИПЫ ТОВАРОВ !!!!
add_filter('divino_product_kinds', function () {
    return ['spirits', 'softspirits', 'wine', 'champagne-and-sparkling'];
});

// Регистрируем кастомные таксономии
add_action('init', 'divino_register_taxonomies');

// Улучшаем UX для производителей — автодополнение
add_action('admin_enqueue_scripts', function () {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'product') {
        wp_enqueue_script('autocomplete-brand', plugin_dir_url(__FILE__) . 'autocomplete-brand.js', ['jquery'], null, true);
        wp_enqueue_style('autocomplete-brand-style', plugin_dir_url(__FILE__) . 'autocomplete-brand.css');
    }
});

// Добавляем стили для админки
add_action('admin_enqueue_scripts', function ($hook) {
    // Только на страницах списка товаров
    if ($hook === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'product') {
        wp_enqueue_style(
            'my-admin-style',
            plugin_dir_url(__FILE__) . 'assets/admin.css',
            [],
            filemtime(plugin_dir_path(__FILE__) . 'assets/admin.css')
        );
    }
    
    // Подключаем медиабиблиотеку на страницах редактирования таксономий
    if (in_array($hook, ['term.php', 'edit-tags.php'])) {
        wp_enqueue_media();
    }
});

// Добавляем поддержку таксономии product_kind в WooCommerce
add_filter('woocommerce_is_woocommerce', function($is_woocommerce) {
    if (is_tax('product_kind')) {
        return true;
    }
    return $is_woocommerce;
});

add_action('pre_get_posts', function($query) {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    if (is_tax('product_kind')) {
        $query->set('post_type', 'product');
    }
});

// Flush rewrite rules on plugin activation
register_activation_hook(__FILE__, 'divino_flush_rewrite_rules_on_activation');

function divino_flush_rewrite_rules_on_activation() {
    divino_register_taxonomies();
    flush_rewrite_rules();
}

// Extract taxonomy registration into a separate function for reuse
function divino_register_taxonomies() {
    // Тип товара
    register_taxonomy('product_kind', 'product', [
        'labels' => [
            'name' => 'Типы товара',
            'singular_name' => 'Тип товара',
            'add_new_item' => 'Добавить тип товара',
            'edit_item' => 'Редактировать тип',
            'search_items' => 'Поиск типов',
        ],
        'public' => true,
        'show_in_nav_menus' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'product-kind', 'with_front' => false],
    ]);

    // Производитель
    register_taxonomy('product_brand', 'product', [
        'labels' => [
            'name' => 'Производители',
            'singular_name' => 'Производитель',
            'add_new_item' => 'Добавить производителя',
            'edit_item' => 'Редактировать производителя',
            'search_items' => 'Поиск производителей',
        ],
        'public' => true,
        'hierarchical' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'brand'],
    ]);

    add_rewrite_tag('%product_kind%', '([^&]+)');
}

// Handle legacy ?product_kind=red-wine format
add_action('parse_request', function($wp) {
    if (isset($_GET['product_kind']) && !empty($_GET['product_kind'])) {
        $term_slug = sanitize_text_field($_GET['product_kind']);
        $term = get_term_by('slug', $term_slug, 'product_kind');

        if ($term) {
            $wp->query_vars['post_type'] = 'product';
            $wp->query_vars['product_kind'] = $term_slug;
        }
    }
});

// ========================================
// НОВЫЙ ФУНКЦИОНАЛ: Дополнительные поля для производителей
// ========================================

// Добавляем поля при создании нового производителя
add_action('product_brand_add_form_fields', 'divino_add_brand_fields');
function divino_add_brand_fields() {
    ?>
    <div class="form-field">
        <label for="brand_background_image">Фоновое изображение</label>
        <div class="divino-image-wrapper">
            <input type="hidden" id="brand_background_image" name="brand_background_image" value="">
            <input type="button" class="button button-secondary" value="Выбрать изображение" onclick="
                var frame = wp.media({title: 'Выберите изображение', button: {text: 'Использовать'}, multiple: false});
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    document.getElementById('brand_background_image').value = attachment.id;
                    document.getElementById('brand_image_preview').innerHTML = '<img src=\'' + attachment.url + '\' style=\'max-width:300px;height:auto;\'><br>';
                    document.getElementById('brand_remove_image').style.display = 'inline-block';
                });
                frame.open();
            ">
            <input type="button" id="brand_remove_image" class="button button-secondary" value="Удалить" style="display:none;" onclick="
                document.getElementById('brand_background_image').value = '';
                document.getElementById('brand_image_preview').innerHTML = '';
                this.style.display = 'none';
            ">
            <div id="brand_image_preview" style="margin-top:10px;"></div>
        </div>
    </div>
    
    <div class="form-field">
        <label for="brand_background_color">Цвет фона</label>
        <input type="text" id="brand_background_color" name="brand_background_color" value="" placeholder="#ffffff">
        <p class="description">Введите цвет в формате HEX (например, #ffffff) или RGB</p>
    </div>
    <?php
}

// Добавляем поля при редактировании производителя
add_action('product_brand_edit_form_fields', 'divino_edit_brand_fields');
function divino_edit_brand_fields($term) {
    $background_image = get_term_meta($term->term_id, 'brand_background_image', true);
    $background_color = get_term_meta($term->term_id, 'brand_background_color', true);
    $image_url = $background_image ? wp_get_attachment_url($background_image) : '';
    ?>
    <tr class="form-field">
        <th scope="row">
            <label for="brand_background_image">Фоновое изображение</label>
        </th>
        <td>
            <input type="hidden" id="brand_background_image" name="brand_background_image" value="<?php echo esc_attr($background_image); ?>">
            <input type="button" class="button button-secondary" value="Выбрать изображение" onclick="
                var frame = wp.media({title: 'Выберите изображение', button: {text: 'Использовать'}, multiple: false});
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    document.getElementById('brand_background_image').value = attachment.id;
                    document.getElementById('brand_image_preview').innerHTML = '<img src=\'' + attachment.url + '\' style=\'max-width:300px;height:auto;\'>';
                    document.getElementById('brand_remove_image').style.display = 'inline-block';
                });
                frame.open();
            ">
            <input type="button" id="brand_remove_image" class="button button-secondary" value="Удалить" <?php echo $image_url ? '' : 'style="display:none;"'; ?> onclick="
                document.getElementById('brand_background_image').value = '';
                document.getElementById('brand_image_preview').innerHTML = '';
                this.style.display = 'none';
            ">
            <div id="brand_image_preview" style="margin-top:10px;">
                <?php if ($image_url): ?>
                    <img src="<?php echo esc_url($image_url); ?>" style="max-width:300px; height:auto;">
                <?php endif; ?>
            </div>
        </td>
    </tr>
    
    <tr class="form-field">
        <th scope="row">
            <label for="brand_background_color">Цвет фона</label>
        </th>
        <td>
            <input type="text" id="brand_background_color" name="brand_background_color" value="<?php echo esc_attr($background_color); ?>" placeholder="#ffffff">
            <p class="description">Введите цвет в формате HEX (например, #ffffff) или RGB</p>
        </td>
    </tr>
    <?php
}

// Сохраняем метаданные производителя
add_action('created_product_brand', 'divino_save_brand_fields');
add_action('edited_product_brand', 'divino_save_brand_fields');
function divino_save_brand_fields($term_id) {
    if (isset($_POST['brand_background_image'])) {
        update_term_meta($term_id, 'brand_background_image', sanitize_text_field($_POST['brand_background_image']));
    }
    
    if (isset($_POST['brand_background_color'])) {
        update_term_meta($term_id, 'brand_background_color', sanitize_text_field($_POST['brand_background_color']));
    }
}

// ========================================
// ШОРТКОД ДЛЯ ВЫВОДА ИНФОРМАЦИИ О ПРОИЗВОДИТЕЛЕ
// ========================================

// Регистрируем шорткод [producer_info]
add_shortcode('producer_info', 'divino_producer_info_shortcode');

function divino_producer_info_shortcode($atts) {
    // Параметры шорткода
    $atts = shortcode_atts([
        'id' => 0, // ID производителя (необязательно, если на странице товара)
    ], $atts);
    
    $brand_id = intval($atts['id']);
    
    // Если ID не указан, пытаемся получить из текущего товара
    if (!$brand_id && is_singular('product')) {
        global $post;
        $brands = wp_get_post_terms($post->ID, 'product_brand');
        if (!empty($brands) && !is_wp_error($brands)) {
            $brand_id = $brands[0]->term_id;
        }
    }
    
    if (!$brand_id) {
        return '<div class="divino-producer-info">Производитель не указан</div>';
    }
    
    $term = get_term($brand_id, 'product_brand');
    if (!$term || is_wp_error($term)) {
        return '<div class="divino-producer-info">Производитель не найден</div>';
    }
    
    // Получаем метаданные
    $background_image = get_term_meta($brand_id, 'brand_background_image', true);
    $background_color = get_term_meta($brand_id, 'brand_background_color', true);
    $image_url = $background_image ? wp_get_attachment_url($background_image) : '';
    
    // Формируем стили
    $style = [];
    if ($background_color) {
        $style[] = 'background-color: ' . esc_attr($background_color);
    }
    if ($image_url) {
        $style[] = 'background-image: url(' . esc_url($image_url) . ')';
        $style[] = 'background-size: cover';
        $style[] = 'background-position: center';
    }
    $style_attr = !empty($style) ? ' style="' . implode('; ', $style) . '"' : '';
    
    // Формируем HTML
    ob_start();
    ?>
    <div class="divino-producer-info"<?php echo $style_attr; ?>>
        <div class="divino-producer-content">
            <h2 class="divino-producer-name"><?php echo esc_html($term->name); ?></h2>
            <?php if ($term->description): ?>
                <div class="divino-producer-description">
                    <?php echo wp_kses_post($term->description); ?>
                </div>
            <?php endif; ?>
            <a href="<?php echo esc_url(get_term_link($term)); ?>" class="divino-producer-link">
                Все товары производителя →
            </a>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Добавляем базовые стили для шорткода на фронтенде
add_action('wp_enqueue_scripts', 'divino_enqueue_producer_styles');
function divino_enqueue_producer_styles() {
    wp_add_inline_style('wp-block-library', '
        .divino-producer-info {
            padding: 40px 20px;
            margin: 20px 0;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }
        .divino-producer-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
        }
        .divino-producer-name {
            font-size: 2em;
            margin-bottom: 20px;
        }
        .divino-producer-description {
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .divino-producer-link {
            display: inline-block;
            padding: 10px 20px;
            background: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .divino-producer-link:hover {
            background: #555;
        }
    ');
}