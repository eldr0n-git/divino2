<?php
/**
 * Plugin Name: Divino Product Custom Name
 * Plugin URI: https://example.com
 * Description: Добавляет возможность указать дополнительное наименование товара
 * Version: 1.0.4
 * Author: eldr0n
 * Text Domain: divino-custom-name
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) {
    exit;
}

class Divino_Product_Custom_Name {

    private static $instance = null;

    const META_KEY = '_divino_custom_product_name';

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('add_meta_boxes', [$this, 'add_meta_box']);
        add_action('save_post_product', [$this, 'save_meta_box'], 10, 2);
        add_action('init', [$this, 'register_block']);
    }

    /**
     * Добавляет метабокс в редактор товара
     */
    public function add_meta_box() {
        add_meta_box(
            'divino_custom_product_name',
            __('Дополнительное наименование товара', 'divino-custom-name'),
            [$this, 'render_meta_box'],
            'product',
            'side',
            'high'
        );
    }

    /**
     * Отображает содержимое метабокса
     */
    public function render_meta_box($post) {
        wp_nonce_field('divino_custom_name_nonce', 'divino_custom_name_nonce_field');

        $custom_name = get_post_meta($post->ID, self::META_KEY, true);

        // Если дополнительное название не задано, используем основное
        if (empty($custom_name)) {
            $custom_name = $post->post_title;
        }

        ?>
        <div class="divino-custom-name-field">
            <p>
                <label for="divino_custom_product_name">
                    <?php _e('Дополнительное название:', 'divino-custom-name'); ?>
                </label>
            </p>
            <input
                type="text"
                id="divino_custom_product_name"
                name="divino_custom_product_name"
                value="<?php echo esc_attr($custom_name); ?>"
                style="width: 100%;"
                placeholder="<?php echo esc_attr($post->post_title); ?>"
            />
            <p class="description">
                <?php _e('Если поле пустое, будет использовано основное наименование товара.', 'divino-custom-name'); ?>
            </p>
        </div>
        <style>
            .divino-custom-name-field label {
                font-weight: 600;
                margin-bottom: 5px;
                display: block;
            }
            .divino-custom-name-field input {
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            .divino-custom-name-field .description {
                font-size: 12px;
                color: #666;
                margin-top: 8px;
            }
        </style>
        <?php
    }

    /**
     * Сохраняет данные метабокса
     */
    public function save_meta_box($post_id, $post) {
        // Проверка nonce
        if (!isset($_POST['divino_custom_name_nonce_field']) ||
            !wp_verify_nonce($_POST['divino_custom_name_nonce_field'], 'divino_custom_name_nonce')) {
            return;
        }

        // Проверка автосохранения
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Проверка прав
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Сохранение данных
        if (isset($_POST['divino_custom_product_name'])) {
            $custom_name = sanitize_text_field($_POST['divino_custom_product_name']);

            // Если поле пустое, используем основное название
            if (empty($custom_name)) {
                $custom_name = $post->post_title;
            }

            update_post_meta($post_id, self::META_KEY, $custom_name);
        }
    }

    /**
     * Регистрирует блок для использования в шаблонах
     */
    public function register_block() {
        if (!function_exists('register_block_type')) {
            return;
        }

        register_block_type('divino/product-custom-name', [
            'render_callback' => [$this, 'render_block'],
            'attributes' => [
                'prefix' => [
                    'type' => 'string',
                    'default' => ''
                ],
                'suffix' => [
                    'type' => 'string',
                    'default' => ''
                ],
                'className' => [
                    'type' => 'string',
                    'default' => ''
                ],
                'showIfEmpty' => [
                    'type' => 'boolean',
                    'default' => true
                ]
            ]
        ]);
    }

    /**
     * Рендерит блок на фронтенде
     */
    public function render_block($attributes, $content, $block) {
        // Пытаемся получить ID товара из контекста блока
        $product_id = isset($block->context['postId']) ? $block->context['postId'] : 0;

        // Если ID не указан, пытаемся получить из глобальной переменной
        if ($product_id === 0) {
            global $post;
            if ($post && $post->post_type === 'product') {
                $product_id = $post->ID;
            }
        }

        if ($product_id === 0) {
            return '';
        }

        $custom_name = get_post_meta($product_id, self::META_KEY, true);

        // Если дополнительное название не задано, используем основное
        if (empty($custom_name)) {
            $custom_name = get_the_title($product_id);
        }

        // Если название пустое и showIfEmpty = false, не выводим ничего
        if (empty($custom_name) && !$attributes['showIfEmpty']) {
            return '';
        }

        $prefix = isset($attributes['prefix']) ? $attributes['prefix'] : '';
        $suffix = isset($attributes['suffix']) ? $attributes['suffix'] : '';
        $class_name = isset($attributes['className']) ? esc_attr($attributes['className']) : '';

        $output = '<div class="divino-product-custom-name ' . $class_name . '">';
        // $output .= esc_html($prefix) . esc_html($custom_name) . esc_html($suffix);
        $output .= $custom_name;
        $output .= '</div>';

        return $output;
    }

    /**
     * Получает дополнительное название товара
     */
    public static function get_custom_name($product_id = 0) {
        if ($product_id === 0) {
            global $post;
            $product_id = $post->ID;
        }

        $custom_name = get_post_meta($product_id, self::META_KEY, true);

        if (empty($custom_name)) {
            $custom_name = get_the_title($product_id);
        }

        return $custom_name;
    }
}

// Инициализация плагина
Divino_Product_Custom_Name::get_instance();

/**
 * Вспомогательная функция для получения дополнительного названия в шаблонах
 *
 * @param int $product_id ID товара (опционально)
 * @return string Дополнительное название товара
 */
function divino_get_product_custom_name($product_id = 0) {
    return Divino_Product_Custom_Name::get_custom_name($product_id);
}

/**
 * Вспомогательная функция для вывода дополнительного названия в шаблонах
 *
 * @param int $product_id ID товара (опционально)
 */
function divino_the_product_custom_name($product_id = 0) {
    echo esc_html(divino_get_product_custom_name($product_id));
}
