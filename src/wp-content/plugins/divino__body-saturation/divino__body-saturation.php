<?php
/**
 * Plugin Name: Divino - Wine Characteristics
 * Description: Добавляет атрибуты "Насыщенность", "Кислотность" и "Танины" для вин.
 * Version: 2.1
 * Author: eldr0n
 * [body_saturation]  // Насыщенность
 * [acidity]          // Кислотность
 * [tannins]          // Танины
 */

if (!defined('ABSPATH')) exit;

// Добавление метабоксов
function divino_add_wine_characteristics_metabox() {
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
        
        add_meta_box(
            'divino_acidity_metabox',
            'Кислотность',
            'divino_render_acidity_metabox',
            'product',
            'side',
            'high'
        );
        
        add_meta_box(
            'divino_tannins_metabox',
            'Танины',
            'divino_render_tannins_metabox',
            'product',
            'side',
            'high'
        );
    }
}
add_action('add_meta_boxes_product', 'divino_add_wine_characteristics_metabox');

// Рендер метабокса "Насыщенность"
function divino_render_body_saturation_metabox($post) {
    wp_nonce_field('divino_save_body_saturation', 'divino_body_saturation_nonce');
    $value = get_post_meta($post->ID, '_body_saturation', true);
    $is_active = get_post_meta($post->ID, '_body_saturation_active', true);
    
    if (empty($value)) {
        $value = 4;
    }
    ?>
    <div class="divino-saturation-slider-container">
        <div class="divino-saturation-labels">
            <span class="label-left">Легкое</span>
            <span class="label-right">Полнотелое</span>
        </div>
        <input type="range" id="divino_body_saturation" name="divino_body_saturation" min="1" max="7" value="<?php echo esc_attr($value); ?>" class="divino-saturation-slider">
        <div id="divino-saturation-value-display"><?php echo esc_attr($value); ?></div>
        <div style="display:flex; align-items: center; margin-top: 10px;">
            <input type="checkbox" name="divino_body_saturation_active" id="divino_body_saturation_active" <?php checked($is_active, '1'); ?>>
            <label for="divino_body_saturation_active">Активно</label>
        </div>
    </div>
    <?php
}

// Рендер метабокса "Кислотность"
function divino_render_acidity_metabox($post) {
    wp_nonce_field('divino_save_acidity', 'divino_acidity_nonce');
    $value = get_post_meta($post->ID, '_acidity', true);
    $is_active = get_post_meta($post->ID, '_acidity_active', true);
    
    if (empty($value)) {
        $value = 4;
    }
    ?>
    <div class="divino-saturation-slider-container">
        <div class="divino-saturation-labels">
            <span class="label-left">Низкая</span>
            <span class="label-right">Высокая</span>
        </div>
        <input type="range" id="divino_acidity" name="divino_acidity" min="1" max="7" value="<?php echo esc_attr($value); ?>" class="divino-saturation-slider">
        <div id="divino-acidity-value-display"><?php echo esc_attr($value); ?></div>
        <div style="display:flex; align-items: center; margin-top: 10px;">
            <input type="checkbox" name="divino_acidity_active" id="divino_acidity_active" <?php checked($is_active, '1'); ?>>
            <label for="divino_acidity_active">Активно</label>
        </div>
    </div>
    <?php
}

// Рендер метабокса "Танины"
function divino_render_tannins_metabox($post) {
    wp_nonce_field('divino_save_tannins', 'divino_tannins_nonce');
    $value = get_post_meta($post->ID, '_tannins', true);
    $is_active = get_post_meta($post->ID, '_tannins_active', true);
    
    if (empty($value)) {
        $value = 4;
    }
    ?>
    <div class="divino-saturation-slider-container">
        <div class="divino-saturation-labels">
            <span class="label-left">Мягкие</span>
            <span class="label-right">Жесткие</span>
        </div>
        <input type="range" id="divino_tannins" name="divino_tannins" min="1" max="7" value="<?php echo esc_attr($value); ?>" class="divino-saturation-slider">
        <div id="divino-tannins-value-display"><?php echo esc_attr($value); ?></div>
        <div style="display:flex; align-items: center; margin-top: 10px;">
            <input type="checkbox" name="divino_tannins_active" id="divino_tannins_active" <?php checked($is_active, '1'); ?>>
            <label for="divino_tannins_active">Активно</label>
        </div>
    </div>
    <?php
}

// Сохранение значений
function divino_save_wine_characteristics($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Сохранение насыщенности
    if (isset($_POST['divino_body_saturation_nonce']) && wp_verify_nonce($_POST['divino_body_saturation_nonce'], 'divino_save_body_saturation')) {
        if (isset($_POST['divino_body_saturation'])) {
            update_post_meta($post_id, '_body_saturation', sanitize_text_field($_POST['divino_body_saturation']));
        }
        // Сохраняем статус активности
        $is_active = isset($_POST['divino_body_saturation_active']) ? '1' : '0';
        update_post_meta($post_id, '_body_saturation_active', $is_active);
    }

    // Сохранение кислотности
    if (isset($_POST['divino_acidity_nonce']) && wp_verify_nonce($_POST['divino_acidity_nonce'], 'divino_save_acidity')) {
        if (isset($_POST['divino_acidity'])) {
            update_post_meta($post_id, '_acidity', sanitize_text_field($_POST['divino_acidity']));
        }
        // Сохраняем статус активности
        $is_active = isset($_POST['divino_acidity_active']) ? '1' : '0';
        update_post_meta($post_id, '_acidity_active', $is_active);
    }

    // Сохранение танинов
    if (isset($_POST['divino_tannins_nonce']) && wp_verify_nonce($_POST['divino_tannins_nonce'], 'divino_save_tannins')) {
        if (isset($_POST['divino_tannins'])) {
            update_post_meta($post_id, '_tannins', sanitize_text_field($_POST['divino_tannins']));
        }
        // Сохраняем статус активности
        $is_active = isset($_POST['divino_tannins_active']) ? '1' : '0';
        update_post_meta($post_id, '_tannins_active', $is_active);
    }
}
add_action('save_post_product', 'divino_save_wine_characteristics');

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

// Функции для получения значений
function divino_get_body_saturation($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $value = get_post_meta($post_id, '_body_saturation', true);
    return empty($value) ? 4 : $value;
}

function divino_get_acidity($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $value = get_post_meta($post_id, '_acidity', true);
    return empty($value) ? 4 : $value;
}

function divino_get_tannins($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $value = get_post_meta($post_id, '_tannins', true);
    return empty($value) ? 4 : $value;
}

// Функции для проверки активности
function divino_is_body_saturation_active($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, '_body_saturation_active', true) === '1';
}

function divino_is_acidity_active($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, '_acidity_active', true) === '1';
}

function divino_is_tannins_active($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, '_tannins_active', true) === '1';
}

// Шорткод для вывода насыщенности
function divino_body_saturation_shortcode($atts) {
    $atts = shortcode_atts(array('post_id' => null), $atts, 'body_saturation');
    
    $post_id = $atts['post_id'] ? intval($atts['post_id']) : get_the_ID();
    
    // Проверяем активность
    if (!divino_is_body_saturation_active($post_id)) {
        return '';
    }
    
    $wine_body_range = [
        ['label' => 'Легкое', 'value' => '1'],
        ['label' => '', 'value' => '2'],
        ['label' => '', 'value' => '3'],
        ['label' => '', 'value' => '4'],
        ['label' => '', 'value' => '5'],
        ['label' => '', 'value' => '6'],
        ['label' => 'Полнотелое', 'value' => '7'] 
    ];

    $value = divino_get_body_saturation($post_id);
    
    $output = '<div class="slider-scale-set"><div class="slider-scale"><div class="slider-scale__title"><strong>Насыщенность:</strong></div><div class="slider-scale__track">';
    
    foreach ($wine_body_range as $wine_body_value) {
        $is_active = ($value === $wine_body_value['value']) ? 'active' : '';
        $output .= '<div class="slider-scale__point-wrapper ' . $is_active . '">';
        $output .= '<div class="slider-scale__point"></div>';
        $output .= '<div class="slider-scale__label">' . esc_html($wine_body_value['label']) . '</div>';
        $output .= '</div>';
    }
    
    $output .= '</div></div></div>';
    
    return $output;
}
add_shortcode('body_saturation', 'divino_body_saturation_shortcode');

// Отключение wpautop для шорткодов
remove_filter('the_content', 'wpautop');
add_filter('the_content', 'wpautop', 99);
add_filter('the_content', 'shortcode_unautop', 100);

// Шорткод для вывода кислотности
function divino_acidity_shortcode($atts) {
    $atts = shortcode_atts(array('post_id' => null), $atts, 'acidity');
    
    $post_id = $atts['post_id'] ? intval($atts['post_id']) : get_the_ID();
    
    // Проверяем активность
    if (!divino_is_acidity_active($post_id)) {
        return '';
    }
    
    $acidity_range = [
        ['label' => 'Низкая', 'value' => '1'],
        ['label' => '', 'value' => '2'],
        ['label' => '', 'value' => '3'],
        ['label' => '', 'value' => '4'],
        ['label' => '', 'value' => '5'],
        ['label' => '', 'value' => '6'],
        ['label' => 'Высокая', 'value' => '7'] 
    ];

    $value = divino_get_acidity($post_id);
    
    $output = '<div class="slider-scale-set"><div class="slider-scale"><div class="slider-scale__title"><strong>Кислотность:</strong></div><div class="slider-scale__track">';
    
    foreach ($acidity_range as $acidity_value) {
        $is_active = ($value === $acidity_value['value']) ? 'active' : '';
        $output .= '<div class="slider-scale__point-wrapper ' . $is_active . '">';
        $output .= '<div class="slider-scale__point"></div>';
        $output .= '<div class="slider-scale__label">' . esc_html($acidity_value['label']) . '</div>';
        $output .= '</div>';
    }
    
    $output .= '</div></div></div>';
    
    return $output;
}
add_shortcode('acidity', 'divino_acidity_shortcode');

// Шорткод для вывода танинов
function divino_tannins_shortcode($atts) {
    $atts = shortcode_atts(array('post_id' => null), $atts, 'tannins');
    
    $post_id = $atts['post_id'] ? intval($atts['post_id']) : get_the_ID();
    
    // Проверяем активность
    if (!divino_is_tannins_active($post_id)) {
        return '';
    }
    
    $tannins_range = [
        ['label' => 'Мягкие', 'value' => '1'],
        ['label' => '', 'value' => '2'],
        ['label' => '', 'value' => '3'],
        ['label' => '', 'value' => '4'],
        ['label' => '', 'value' => '5'],
        ['label' => '', 'value' => '6'],
        ['label' => 'Жесткие', 'value' => '7'] 
    ];

    $value = divino_get_tannins($post_id);
    
    $output = '<div class="slider-scale-set"><div class="slider-scale"><div class="slider-scale__title"><strong>Танины:</strong></div><div class="slider-scale__track">';
    
    foreach ($tannins_range as $tannins_value) {
        $is_active = ($value === $tannins_value['value']) ? 'active' : '';
        $output .= '<div class="slider-scale__point-wrapper ' . $is_active . '">';
        $output .= '<div class="slider-scale__point"></div>';
        $output .= '<div class="slider-scale__label">' . esc_html($tannins_value['label']) . '</div>';
        $output .= '</div>';
    }
    
    $output .= '</div></div></div>';
    
    return $output;
}
add_shortcode('tannins', 'divino_tannins_shortcode');