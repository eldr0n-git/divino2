<?php
/**
 * Plugin Name: DIVINO Custom Product SKU Display
 * Description: Выводит артикул товара без префикса "SKU:" с поддержкой шорткода и блока Gutenberg
 * Version: 1.0.0
 * Author: eldr0n
 * Text Domain: divino-sku
 */

// Предотвращаем прямой доступ
if (!defined('ABSPATH')) {
    exit;
}

class CustomProductSKU {

    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function init() {
        // Регистрируем шорткод
        add_shortcode('custom_sku', array($this, 'display_sku_shortcode'));

        // Регистрируем блок для Gutenberg
        add_action('init', array($this, 'register_block'));
    }

    /**
     * Шорткод для вывода артикула
     * Использование: [custom_sku] или [custom_sku product_id="123"]
     */
    public function display_sku_shortcode($atts) {
        $atts = shortcode_atts(array(
            'product_id' => null,
            'class' => 'custom-product-sku',
            'prefix' => '', // Можно добавить свой префикс
            'suffix' => ''  // Можно добавить суффикс
        ), $atts);

        // Получаем продукт
        if ($atts['product_id']) {
            $product = wc_get_product($atts['product_id']);
        } else {
            global $product;
        }

        // Проверяем, что продукт существует и есть артикул
        if (!$product || !$product->get_sku()) {
            return '';
        }

        $sku = $product->get_sku();

        return sprintf(
            '<span class="%s">Артикул: %s%s%s</span>',
            esc_attr($atts['class']),
            esc_html($atts['prefix']),
            esc_html($sku),
            esc_html($atts['suffix'])
        );
    }

    /**
     * Функция для использования в шаблонах PHP
     */
    public static function get_product_sku($product_id = null, $args = array()) {
        if ($product_id) {
            $product = wc_get_product($product_id);
        } else {
            global $product;
        }

        if (!$product || !$product->get_sku()) {
            return '';
        }

        $defaults = array(
            'class' => 'custom-product-sku',
            'prefix' => '',
            'suffix' => '',
            'wrapper' => 'span'
        );

        $args = wp_parse_args($args, $defaults);

        return sprintf(
            '<%s class="%s">%s%s%s</%s>',
            $args['wrapper'],
            esc_attr($args['class']),
            esc_html($args['prefix']),
            esc_html($product->get_sku()),
            esc_html($args['suffix']),
            $args['wrapper']
        );
    }

    /**
     * Регистрируем блок для Gutenberg
     */
    public function register_block() {
        if (!function_exists('register_block_type')) {
            return;
        }

        register_block_type('custom-sku/product-sku', array(
            'render_callback' => array($this, 'render_block'),
            'attributes' => array(
                'className' => array(
                    'type' => 'string',
                    'default' => 'custom-product-sku'
                ),
                'prefix' => array(
                    'type' => 'string',
                    'default' => ''
                ),
                'suffix' => array(
                    'type' => 'string',
                    'default' => ''
                )
            )
        ));
    }

    /**
     * Рендер блока
     */
    public function render_block($attributes) {
        $class = isset($attributes['className']) ? $attributes['className'] : 'custom-product-sku';
        $prefix = isset($attributes['prefix']) ? $attributes['prefix'] : '';
        $suffix = isset($attributes['suffix']) ? $attributes['suffix'] : '';

        return $this->display_sku_shortcode(array(
            'class' => $class,
            'prefix' => $prefix,
            'suffix' => $suffix
        ));
    }

    /**
     * Подключаем стили
     */
    public function enqueue_scripts() {
        // wp_add_inline_style('woocommerce-general', '
        //     .custom-product-sku {
        //         display: inline-block;
        //         font-weight: 600;
        //         color: #666;
        //         margin: 5px 0;
        //     }

        //     .custom-product-sku-wrapper {
        //         margin: 10px 0;
        //     }
        // ');
    }
}

// Инициализируем плагин
new CustomProductSKU();

/**
 * Функция для использования в шаблонах
 * Использование: echo custom_product_sku();
 */
function custom_product_sku($product_id = null, $args = array()) {
    return CustomProductSKU::get_product_sku($product_id, $args);
}

/**
 * Функция для прямого вывода в шаблонах
 * Использование: custom_product_sku_display();
 */
function custom_product_sku_display($product_id = null, $args = array()) {
    echo custom_product_sku($product_id, $args);
}
?>
