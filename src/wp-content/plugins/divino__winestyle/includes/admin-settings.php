<?php
// Создание страницы настроек
add_action('admin_menu', function () {
    add_submenu_page(
        'woocommerce',
        'Стили вина',
        'Стили вина',
        'manage_options',
        'divino-wine-styles',
        'divino_render_wine_styles_page'
    );
});

// Регистрируем опцию
add_action('admin_init', function () {
    register_setting('divino_wine_styles_group', 'divino_wine_styles', [
        'type' => 'array',
        'sanitize_callback' => 'divino_sanitize_wine_styles'
    ]);
});

function divino_sanitize_wine_styles($input) {
    return array_filter(array_map('sanitize_text_field', $input));
}

function divino_render_wine_styles_page() {
    $options = get_option('divino_wine_styles', []);

    echo '<div class="wrap"><h1>Стили вина</h1>';
    echo '<form method="post" action="options.php">';
    settings_fields('divino_wine_styles_group');

    echo '<table class="form-table"><tr><th><label for="divino_wine_styles">Стили (по одному в строке)</label></th><td>';
    echo '<textarea name="divino_wine_styles[]" rows="10" style="width: 100%;">';
    echo implode("\n", $options);
    echo '</textarea>';
    echo '</td></tr></table>';

    submit_button('Сохранить стили');
    echo '</form></div>';
}
