<?php
/**
 * Plugin Name: DIVINO Product Kind Breadcrumb
 * Description: Кастомный breadcrumb для WooCommerce с использованием таксономии product_kind вместо стандартных категорий
 * Version: 1.1.1
 * Author: eldr0n
 * website URI: https://divino.kz
 * Text Domain: product-kind-breadcrumb
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
// Декларация совместимости с WooCommerce HPOS
add_action('before_woocommerce_init', function() {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});
// Запрещаем прямой доступ к файлу
if (!defined('ABSPATH')) {
    exit;
}

// Проверяем наличие WooCommerce
if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

// Основной класс плагина
class ProductKindBreadcrumb {

    private static $instance = null;

    // Версия плагина
    public $version = '1.1.1';

    // Путь к плагину
    public $plugin_path;

    // URL плагина
    public $plugin_url;

    // Настройки по умолчанию
    private $default_settings = array(
        'separator' => '<span>&gt;</span> ',
        'show_home' => true,
        'show_shop' => true,
        'home_text' => 'Главная',
        'auto_replace' => true,
        'breadcrumb_class' => 'product-kind-breadcrumb',
        'force_hide_default' => true,
        'debug_mode' => false
    );

    /**
     * Singleton pattern
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Конструктор
     */
    private function __construct() {
        $this->plugin_path = plugin_dir_path(__FILE__);
        $this->plugin_url  = plugin_dir_url(__FILE__);

        // Хуки активации/деактивации
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));

        // Инициализация плагина
        add_action('plugins_loaded', array($this, 'init'));
    }

    /**
     * Инициализация плагина
     */
    public function init() {
        // Проверяем наличие WooCommerce
        if (!$this->is_woocommerce_active()) {
            add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
            return;
        }

        // Загружаем текстовый домен
        load_plugin_textdomain('product-kind-breadcrumb', false, dirname(plugin_basename(__FILE__)) . '/languages');

        // Основные хуки
        $this->init_hooks();

        // Админ панель
        if (is_admin()) {
            $this->init_admin();
        }
    }

    /**
     * Инициализация хуков
     */
    /**
 * Инициализация хуков
 */
private function init_hooks() {
    // Основные функции
    add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    add_shortcode('product_kind_breadcrumb', array($this, 'breadcrumb_shortcode'));

    // Замена стандартного breadcrumb (если включено в настройках)
    $settings = $this->get_settings();
    if ($settings['auto_replace']) {
        // Удаляем стандартные breadcrumb
        add_action('init', array($this, 'remove_woocommerce_breadcrumb'));
    }

    // Режим отладки
    if ($settings['debug_mode']) {
        add_action('wp_footer', array($this, 'debug_breadcrumb_info'));
    }
}

/**
 * Настройка хуков для breadcrumb в зависимости от типа страницы
 */
public function setup_breadcrumb_hooks() {
    if (is_product()) {
        // Для страниц продуктов
        add_action('woocommerce_before_single_product', array($this, 'display_breadcrumb'), 5);

    } elseif (is_tax('product_kind') || is_tax('product_cat')) {
        // Для архивов таксономий - пробуем разные хуки

        // 4. Если ничего не работает - добавляем в начало body
        add_action('wp_body_open', array($this, 'display_breadcrumb_fallback'), 5);

    } elseif (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
        // Для других страниц WooCommerce
        add_action('woocommerce_before_main_content', array($this, 'display_breadcrumb'), 5);
    }
}

/**
 * Fallback метод для отображения breadcrumb
 */
public function display_breadcrumb_fallback() {
    if (is_tax('product_kind') || is_tax('product_cat')) {
        echo '<div class="breadcrumb-fallback" style="margin-bottom: 20px;">';
        $this->display_breadcrumb();
        echo '</div>';

        // Удаляем хук после первого использования
        remove_action('wp_body_open', array($this, 'display_breadcrumb_fallback'), 5);
    }
}
    private function get_deepest_term($terms, $taxonomy) {
        $deepest_term = null;
        $max_depth = -1;

        foreach ($terms as $term) {
            $depth = 0;
            $current = $term;
            while ($current->parent != 0) {
                $current = get_term($current->parent, $taxonomy);
                if (is_wp_error($current)) break;
                $depth++;
            }
            if ($depth > $max_depth) {
                $max_depth = $depth;
                $deepest_term = $term;
            }
        }

        return $deepest_term ? $deepest_term : $terms[0];
    }

    private function get_term_hierarchy($term, $taxonomy) {
        $hierarchy = array();
        while ($term && !is_wp_error($term)) {
            array_unshift($hierarchy, $term);
            if ($term->parent == 0) break;
            $term = get_term($term->parent, $taxonomy);
        }
        return $hierarchy;
    }

    private function get_primary_product_cat($product_id) {
        $terms = wp_get_post_terms($product_id, 'product_cat');
        if (empty($terms) || is_wp_error($terms)) return null;
        return $this->get_deepest_term($terms, 'product_cat');
    }

    /**
     * Инициализация админ панели
     */
    private function init_admin() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'init_settings'));
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_settings_link'));
    }

    /**
     * Проверка активности WooCommerce
     */
    private function is_woocommerce_active() {
        return class_exists('WooCommerce');
    }

    /**
     * Уведомление об отсутствии WooCommerce
     */
    public function woocommerce_missing_notice() {
        $message = sprintf(
            __('Плагин %s требует активации WooCommerce для работы.', 'product-kind-breadcrumb'),
            '<strong>Product Kind Breadcrumb</strong>'
        );
        echo '<div class="notice notice-error"><p>' . $message . '</p></div>';
    }

    /**
     * Активация плагина
     */
    public function activate() {
        // Устанавливаем настройки по умолчанию
        if (!get_option('product_kind_breadcrumb_settings')) {
            update_option('product_kind_breadcrumb_settings', $this->default_settings);
        }
    }

    /**
     * Деактивация плагина
     */
    public function deactivate() {
        // Очищаем кэш
        wp_cache_flush();
    }

    /**
     * Получение настроек
     */
    public function get_settings() {
        $settings = get_option('product_kind_breadcrumb_settings', $this->default_settings);
        return wp_parse_args($settings, $this->default_settings);
    }

    /**
     * Подключение стилей
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            'product-kind-breadcrumb',
            $this->plugin_url . 'assets/style.css',
            array(),
            $this->version
        );

        // // Дополнительные стили
        // $custom_css = $this->get_custom_css();
        // if ($custom_css) {
        //     wp_add_inline_style('product-kind-breadcrumb', $custom_css);
        // }
    }

    /**
     * Кастомные CSS стили
     */
    private function get_custom_css() {
        $settings = $this->get_settings();

        return "
        .{$settings['breadcrumb_class']} {
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }

        .{$settings['breadcrumb_class']} a {
            color: #0073aa;
            text-decoration: none;
        }

        .{$settings['breadcrumb_class']} a:hover {
            text-decoration: underline;
        }
        ";
    }

    /**
     * Основная функция генерации breadcrumb
     */
    public function generate_breadcrumb() {
        global $post;

        $settings = $this->get_settings();
        $breadcrumb = array();

        // Главная страница
        // if ($settings['show_home']) {
        //     $breadcrumb[] = '<a href="' . home_url() . '">' . esc_html($settings['home_text']) . '</a>';
        // }

        // Страница магазина
        if ($settings['show_shop'] && is_woocommerce()) {
            $shop_page_id = wc_get_page_id('shop');
            if ($shop_page_id) {
                $breadcrumb[] = '<a href="' . get_permalink($shop_page_id) . '">' . /*get_the_title($shop_page_id)*/'Каталог' . '</a>';
            }
        }

        if (is_product()) {
            // === PRODUCT PAGE ===
            $kind_term = $this->get_primary_product_kind($post->ID);
            $cat_term = $this->get_primary_product_cat($post->ID);

            if ($kind_term) {
                $kind_hierarchy = $this->get_term_hierarchy($kind_term, 'product_kind');
                foreach ($kind_hierarchy as $term) {
                    $breadcrumb[] = '<a href="' . get_term_link($term) . '">' . esc_html($term->name) . '</a>';
                }
            }

            // if ($cat_term) {
            //     $cat_hierarchy = $this->get_term_hierarchy($cat_term, 'product_cat');
            //     foreach ($cat_hierarchy as $term) {
            //         $breadcrumb[] = '<a href="' . get_term_link($term) . '">' . esc_html($term->name) . '</a>';
            //     }
            // }

            $breadcrumb[] = esc_html(get_the_title());

        } elseif (is_tax('product_kind') || is_tax('product_cat')) {
            // === ARCHIVE PAGE ===
            $current_term = get_queried_object();
            $taxonomy = $current_term->taxonomy;

            $hierarchy = $this->get_term_hierarchy($current_term, $taxonomy);
            foreach ($hierarchy as $key => $term) {
                if ($key === array_key_last($hierarchy)) {
                    $breadcrumb[] = esc_html($term->name);
                } else {
                    $breadcrumb[] = '<a href="' . get_term_link($term) . '">' . esc_html($term->name) . '</a>';
                }
            }

            // Добавим связанную таксономию, если есть
            $related_tax = ($taxonomy === 'product_kind') ? 'product_cat' : 'product_kind';
            $product_ids = get_posts(array(
                'post_type' => 'product',
                'numberposts' => 1,
                'tax_query' => array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'term_id',
                        'terms' => $current_term->term_id
                    )
                ),
                'fields' => 'ids'
            ));

            // КАТЕГОРИИ ! Если есть связанные продукты, получаем их термины
            // if (!empty($product_ids)) {
            //     $related_terms = wp_get_post_terms($product_ids[0], $related_tax);
            //     if (!empty($related_terms) && !is_wp_error($related_terms)) {
            //         $primary_related = $this->get_deepest_term($related_terms, $related_tax);
            //         $related_hierarchy = $this->get_term_hierarchy($primary_related, $related_tax);
            //         foreach ($related_hierarchy as $term) {
            //             $breadcrumb[] = '<a href="' . get_term_link($term) . '">' . esc_html($term->name) . '</a>';
            //         }
            //     }
            // }
        }


        return implode($settings['separator'], $breadcrumb);
    }

    /**
     * Получение иерархии product_kind
     */
    private function get_product_kind_hierarchy($term) {
        $hierarchy = array();

        while ($term && !is_wp_error($term)) {
            array_unshift($hierarchy, $term);
            if ($term->parent == 0) {
                break;
            }
            $term = get_term($term->parent, 'product_kind');
        }

        return $hierarchy;
    }

    /**
     * Получение основного product_kind
     */
    private function get_primary_product_kind($product_id) {
        $terms = wp_get_post_terms($product_id, 'product_kind');

        if (empty($terms) || is_wp_error($terms)) {
            return null;
        }

        // Проверяем основную категорию (если задана вручную)
        $primary_kind_id = get_post_meta($product_id, '_primary_product_kind', true);
        if ($primary_kind_id) {
            foreach ($terms as $term) {
                if ($term->term_id == $primary_kind_id) {
                    return $term;
                }
            }
        }

        // Найдем термин с наибольшей глубиной (самый вложенный)
        $deepest_term = null;
        $max_depth = -1;

        foreach ($terms as $term) {
            $depth = $this->get_term_depth($term);
            if ($depth > $max_depth) {
                $max_depth = $depth;
                $deepest_term = $term;
            }
        }

        return $deepest_term ? $deepest_term : $terms[0];
    }

    /**
     * Получение глубины термина в иерархии
     */
    private function get_term_depth($term) {
        $depth = 0;
        $current_term = $term;

        while ($current_term && $current_term->parent != 0) {
            $depth++;
            $current_term = get_term($current_term->parent, 'product_kind');
            if (is_wp_error($current_term)) {
                break;
            }
        }

        return $depth;
    }

    /**
     * Функция для вывода breadcrumb
     */
    public function display_breadcrumb($args = array()) {
        $settings = $this->get_settings();

        $defaults = array(
            'before' => '<div class="breadcrumb__cnt wp-block-group alignwide has-global-padding is-layout-constrained wp-block-group-is-layout-constrained"><nav class="' . esc_attr($settings['breadcrumb_class']) . '">',
            'after' => '</nav></div>',
            'echo' => true
        );

        $args = wp_parse_args($args, $defaults);

        $breadcrumb_html = $this->generate_breadcrumb();

        if ($breadcrumb_html) {
            $output = $args['before'] . $breadcrumb_html . $args['after'];

            if ($args['echo']) {
                echo $output;
            } else {
                return $output;
            }
        }
    }

    /**
     * Шорткод
     */
    public function breadcrumb_shortcode($atts) {
        $settings = $this->get_settings();

        $atts = shortcode_atts(array(
            'class' => $settings['breadcrumb_class'],
            'separator' => $settings['separator']
        ), $atts);

        // Временно изменяем настройки для этого вызова
        $temp_settings = $settings;
        $temp_settings['breadcrumb_class'] = $atts['class'];
        $temp_settings['separator'] = $atts['separator'];

        // Сохраняем оригинальные настройки
        $original_settings = $this->get_settings();
        update_option('product_kind_breadcrumb_settings', $temp_settings);

        $output = $this->display_breadcrumb(array('echo' => false));

        // Восстанавливаем настройки
        update_option('product_kind_breadcrumb_settings', $original_settings);

        return $output;
    }

    /**
     * Удаление стандартного breadcrumb WooCommerce
     */
    public function remove_woocommerce_breadcrumb() {
        // Удаляем стандартные хуки WooCommerce
        //remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
        //remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 10);

        // Удаляем из других возможных мест
        //remove_action('woocommerce_single_product_summary', 'woocommerce_breadcrumb', 20);
        //remove_action('woocommerce_archive_description', 'woocommerce_breadcrumb', 10);

        // Удаляем breadcrumb из темы (если она добавляет через собственные функции)
        //remove_action('wp_head', 'woocommerce_breadcrumb');
        //remove_action('wp_footer', 'woocommerce_breadcrumb');

        // Попытка удалить через фильтр (для некоторых тем)
        add_filter('woocommerce_get_breadcrumb', '__return_empty_array', 999);

        // Отключаем через настройки темы (если поддерживается)
        add_filter('theme_mod_woocommerce_breadcrumb_enable', '__return_false');

        // Дополнительные попытки удаления -- СКРЫВАЕМ breadcrumb KIND
        $this->advanced_breadcrumb_removal();
    }

    /**
     * Расширенное удаление breadcrumb
     */
    private function advanced_breadcrumb_removal() {
        global $wp_filter;

        // Список всех возможных хуков где может быть breadcrumb
        $hooks_to_check = array(
            'woocommerce_before_main_content',
            'woocommerce_before_single_product',
            'woocommerce_before_single_product_summary',
            'woocommerce_single_product_summary',
            'woocommerce_after_single_product_summary',
            'woocommerce_before_shop_loop',
            'woocommerce_after_shop_loop',
            'woocommerce_archive_description',
            'storefront_before_content',
            'astra_content_before',
            'generate_before_content'
        );

        foreach ($hooks_to_check as $hook) {
            if (isset($wp_filter[$hook])) {
                foreach ($wp_filter[$hook]->callbacks as $priority => $callbacks) {
                    foreach ($callbacks as $callback_id => $callback) {
                        // Ищем функции связанные с breadcrumb
                        if (is_string($callback['function']) &&
                            (strpos($callback['function'], 'breadcrumb') !== false ||
                             strpos($callback['function'], 'breadcrumbs') !== false)) {
                            remove_action($hook, $callback['function'], $priority);
                        }

                        // Проверяем массивы функций
                        if (is_array($callback['function']) && isset($callback['function'][1]) &&
                            is_string($callback['function'][1]) &&
                            (strpos($callback['function'][1], 'breadcrumb') !== false ||
                             strpos($callback['function'][1], 'breadcrumbs') !== false)) {
                            remove_action($hook, $callback['function'], $priority);
                        }
                    }
                }
            }
        }

    }


    /**
     * Добавление кастомного breadcrumb
     */
    public function add_custom_breadcrumb() {
        $queried_object = get_queried_object();

        if (
            is_woocommerce() ||
            is_cart() ||
            is_checkout() ||
            is_account_page() ||
            is_product() ||
            is_tax('product_kind') ||
            is_tax('product_cat')
        ) {
            $this->display_breadcrumb();
        }
    }


    /**
     * Добавление меню в админ панель
     */
    public function add_admin_menu() {
        add_options_page(
            __('Product Kind Breadcrumb', 'product-kind-breadcrumb'),
            __('Product Kind Breadcrumb', 'product-kind-breadcrumb'),
            'manage_options',
            'product-kind-breadcrumb',
            array($this, 'admin_page')
        );
    }

    /**
     * Инициализация настроек
     */
    public function init_settings() {
        register_setting('product_kind_breadcrumb_settings', 'product_kind_breadcrumb_settings');

        add_settings_section(
            'general_settings',
            __('Общие настройки', 'product-kind-breadcrumb'),
            null,
            'product-kind-breadcrumb'
        );

        // Поля настроек
        $fields = array(
            'separator' => __('Разделитель', 'product-kind-breadcrumb'),
            'home_text' => __('Текст для главной', 'product-kind-breadcrumb'),
            'breadcrumb_class' => __('CSS класс', 'product-kind-breadcrumb')
        );

        foreach ($fields as $field => $label) {
            add_settings_field(
                $field,
                $label,
                array($this, 'render_text_field'),
                'product-kind-breadcrumb',
                'general_settings',
                array('field' => $field)
            );
        }

        // Чекбоксы
        $checkboxes = array(
            'show_home' => __('Показывать "Главная"', 'product-kind-breadcrumb'),
            'show_shop' => __('Показывать "Магазин"', 'product-kind-breadcrumb'),
            'auto_replace' => __('Автоматически заменять стандартный breadcrumb', 'product-kind-breadcrumb'),
            'force_hide_default' => __('Принудительно скрывать все стандартные breadcrumb', 'product-kind-breadcrumb'),
            'debug_mode' => __('Режим отладки (показывать информацию о breadcrumb)', 'product-kind-breadcrumb')
        );

        foreach ($checkboxes as $field => $label) {
            add_settings_field(
                $field,
                $label,
                array($this, 'render_checkbox_field'),
                'product-kind-breadcrumb',
                'general_settings',
                array('field' => $field)
            );
        }
    }

    /**
     * Рендер текстового поля
     */
    public function render_text_field($args) {
        $settings = $this->get_settings();
        $field = $args['field'];
        $value = isset($settings[$field]) ? $settings[$field] : '';

        echo '<input type="text" name="product_kind_breadcrumb_settings[' . $field . ']" value="' . esc_attr($value) . '" class="regular-text" />';
    }

    /**
     * Рендер чекбокса
     */
    public function render_checkbox_field($args) {
        $settings = $this->get_settings();
        $field = $args['field'];
        $checked = isset($settings[$field]) && $settings[$field] ? 'checked="checked"' : '';

        echo '<input type="checkbox" name="product_kind_breadcrumb_settings[' . $field . ']" value="1" ' . $checked . ' />';
    }

    /**
     * Страница настроек
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Product Kind Breadcrumb', 'product-kind-breadcrumb'); ?></h1>

            <form method="post" action="options.php">
                <?php
                settings_fields('product_kind_breadcrumb_settings');
                do_settings_sections('product-kind-breadcrumb');
                submit_button();
                ?>
            </form>

            <h2><?php _e('Использование', 'product-kind-breadcrumb'); ?></h2>
            <p><?php _e('Вы можете использовать следующие способы вывода breadcrumb:', 'product-kind-breadcrumb'); ?></p>

            <h3><?php _e('В коде темы:', 'product-kind-breadcrumb'); ?></h3>
            <code>&lt;?php ProductKindBreadcrumb::get_instance()-&gt;display_breadcrumb(); ?&gt;</code>

            <h3><?php _e('Шорткод:', 'product-kind-breadcrumb'); ?></h3>
            <code>[product_kind_breadcrumb]</code>

            <h3><?php _e('С параметрами:', 'product-kind-breadcrumb'); ?></h3>
            <code>[product_kind_breadcrumb class="my-breadcrumb" separator=" → "]</code>
        </div>
        <?php
    }

    /**
     * Ссылка на настройки в списке плагинов
     */
    public function add_settings_link($links) {
        $settings_link = '<a href="' . admin_url('options-general.php?page=product-kind-breadcrumb') . '">' . __('Настройки', 'product-kind-breadcrumb') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    /**
     * Отладочная информация
     */
    public function debug_breadcrumb_info() {
        if (!current_user_can('manage_options')) {
            return;
        }

        global $post, $wp_filter;

        echo '<div style="position: fixed; bottom: 10px; right: 10px; background: #000; color: #fff; padding: 10px; font-size: 12px; z-index: 9999; max-width: 300px; border-radius: 5px;">';
        echo '<strong>Product Kind Breadcrumb Debug:</strong><br>';

        if (is_product() && $post) {
            $terms = wp_get_post_terms($post->ID, 'product_kind');
            echo 'Product ID: ' . $post->ID . '<br>';
            echo 'Product Kind Terms: ';
            if (!empty($terms)) {
                foreach ($terms as $term) {
                    echo $term->name . ' (ID: ' . $term->term_id . ', Parent: ' . $term->parent . '), ';
                }
            } else {
                echo 'Нет терминов';
            }
            echo '<br>';

            $primary = $this->get_primary_product_kind($post->ID);
            if ($primary) {
                echo 'Primary Term: ' . $primary->name . ' (Depth: ' . $this->get_term_depth($primary) . ')<br>';
            }
        }

        // Проверяем активные хуки breadcrumb
        $breadcrumb_hooks = 0;
        $hooks_to_check = array('woocommerce_before_main_content', 'woocommerce_single_product_summary');

        foreach ($hooks_to_check as $hook) {
            if (isset($wp_filter[$hook])) {
                foreach ($wp_filter[$hook]->callbacks as $priority => $callbacks) {
                    foreach ($callbacks as $callback) {
                        if (is_string($callback['function']) && strpos($callback['function'], 'breadcrumb') !== false) {
                            $breadcrumb_hooks++;
                        }
                    }
                }
            }
        }

        echo 'Active breadcrumb hooks: ' . $breadcrumb_hooks . '<br>';
        echo 'Current page: ' . (is_product() ? 'Product' : (is_tax('product_kind') ? 'Product Kind Archive' : 'Other'));
        echo '</div>';
    }
}

// Инициализация плагина
ProductKindBreadcrumb::get_instance();

// Функция для использования в темах
function product_kind_breadcrumb($args = array()) {

    // ВЫВОДИМ ХЛЕБНЫЕ КРОШКИ ТОЛЬКО НА СТРАНИЦАХ ПРОДУКТОВ И ТАКСОНОМИЙ
    if (
        is_product() ||
        is_tax('product_kind') ||
        is_tax('product_cat')
    ) {
        // Вызываем метод класса для отображения breadcrumb
        ProductKindBreadcrumb::get_instance()->display_breadcrumb($args);
    }
}
?>
