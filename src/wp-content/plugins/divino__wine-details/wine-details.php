<?php
/**
 * Plugin Name: DIVINO Wine Details
 * Plugin URI: https://divino.kz
 * Description: Добавляет дополнительные поля для товаров WooCommerce: способ производства, выдержка, дегустационные заметки
 * Version: 1.0.1
 * Author: eldr0n
 * <!-- wp:wc-wine-details/vineyards {"prefix":"Виноградники: "} /-->
 * <!-- wp:wc-wine-details/production-method {"prefix":"Способ производства: "} /-->
 * <!-- wp:wc-wine-details/aging {"prefix":"Выдержка: "} /-->
 * <!-- wp:wc-wine-details/tasting-notes {"prefix":"Дегустационные заметки: "} /-->
 * <!-- wp:wc-wine-details/interesting {"prefix":"Это интересно: "} /-->
 */

if (!defined('ABSPATH')) {
    exit;
}

class Divino_Wine_Details {
    
    public function __construct() {
        // Добавление полей в админке
        add_action('add_meta_boxes', array($this, 'add_product_meta_boxes'));
        add_action('save_post', array($this, 'save_product_meta_boxes'));
        
        // Регистрация Gutenberg блоков
        add_action('init', array($this, 'register_gutenberg_blocks'));
    }
    
    /**
     * Добавление метабоксов в редактор товара
     */
    public function add_product_meta_boxes() {
        add_meta_box(
            'wc_wine_production_method',
            __('Способ производства', 'wc-wine-details'),
            array($this, 'render_production_method_metabox'),
            'product',
            'normal',
            'default'
        );
        
        add_meta_box(
            'wc_wine_aging',
            __('Выдержка', 'wc-wine-details'),
            array($this, 'render_aging_metabox'),
            'product',
            'normal',
            'default'
        );
        
        add_meta_box(
            'wc_wine_tasting_notes',
            __('Дегустационные заметки', 'wc-wine-details'),
            array($this, 'render_tasting_notes_metabox'),
            'product',
            'normal',
            'default'
        );
        
        add_meta_box(
            'wc_wine_vineyards',
            __('Виноградники', 'wc-wine-details'),
            array($this, 'render_vineyards_metabox'),
            'product',
            'normal',
            'default'
        );
        
        add_meta_box(
            'wc_wine_interesting',
            __('Это интересно', 'wc-wine-details'),
            array($this, 'render_interesting_metabox'),
            'product',
            'normal',
            'default'
        );
    }
    
    /**
     * Рендер метабокса "Способ производства"
     */
    public function render_production_method_metabox($post) {
        wp_nonce_field('wc_wine_details_nonce', 'wc_wine_details_nonce_field');
        $value = get_post_meta($post->ID, '_wine_production_method', true);
        ?>
        <p>
            <label for="wine_production_method"><?php _e('Способ производства:', 'wc-wine-details'); ?></label>
            <textarea 
                id="wine_production_method" 
                name="wine_production_method" 
                rows="4" 
                style="width:100%;"
            ><?php echo esc_textarea($value); ?></textarea>
        </p>
        <?php
    }
    
    /**
     * Рендер метабокса "Выдержка"
     */
    public function render_aging_metabox($post) {
        $value = get_post_meta($post->ID, '_wine_aging', true);
        ?>
        <p>
            <label for="wine_aging"><?php _e('Выдержка:', 'wc-wine-details'); ?></label>
            <textarea 
                id="wine_aging" 
                name="wine_aging" 
                rows="4" 
                style="width:100%;"
            ><?php echo esc_textarea($value); ?></textarea>
        </p>
        <?php
    }
    
    /**
     * Рендер метабокса "Дегустационные заметки"
     */
    public function render_tasting_notes_metabox($post) {
        $value = get_post_meta($post->ID, '_wine_tasting_notes', true);
        ?>
        <p>
            <label for="wine_tasting_notes"><?php _e('Дегустационные заметки:', 'wc-wine-details'); ?></label>
            <textarea 
                id="wine_tasting_notes" 
                name="wine_tasting_notes" 
                rows="6" 
                style="width:100%;"
            ><?php echo esc_textarea($value); ?></textarea>
        </p>
        <?php
    }
    
    /**
     * Рендер метабокса "Виноградники"
     */
    public function render_vineyards_metabox($post) {
        $value = get_post_meta($post->ID, '_wine_vineyards', true);
        ?>
        <p>
            <label for="wine_vineyards"><?php _e('Виноградники:', 'wc-wine-details'); ?></label>
            <textarea 
                id="wine_vineyards" 
                name="wine_vineyards" 
                rows="4" 
                style="width:100%;"
            ><?php echo esc_textarea($value); ?></textarea>
        </p>
        <?php
    }
    
    /**
     * Рендер метабокса "Это интересно"
     */
    public function render_interesting_metabox($post) {
        $value = get_post_meta($post->ID, '_wine_interesting', true);
        ?>
        <p>
            <label for="wine_interesting"><?php _e('Это интересно:', 'wc-wine-details'); ?></label>
            <textarea 
                id="wine_interesting" 
                name="wine_interesting" 
                rows="4" 
                style="width:100%;"
            ><?php echo esc_textarea($value); ?></textarea>
        </p>
        <?php
    }
    
    /**
     * Сохранение данных метабоксов
     */
    public function save_product_meta_boxes($post_id) {
        // Проверка nonce
        if (!isset($_POST['wc_wine_details_nonce_field']) || 
            !wp_verify_nonce($_POST['wc_wine_details_nonce_field'], 'wc_wine_details_nonce')) {
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
        
        // Сохранение полей
        if (isset($_POST['wine_production_method'])) {
            update_post_meta($post_id, '_wine_production_method', sanitize_textarea_field($_POST['wine_production_method']));
        }
        
        if (isset($_POST['wine_aging'])) {
            update_post_meta($post_id, '_wine_aging', sanitize_textarea_field($_POST['wine_aging']));
        }
        
        if (isset($_POST['wine_tasting_notes'])) {
            update_post_meta($post_id, '_wine_tasting_notes', sanitize_textarea_field($_POST['wine_tasting_notes']));
        }

        if (isset($_POST['wine_vineyards'])) {
            update_post_meta($post_id, '_wine_vineyards', sanitize_textarea_field($_POST['wine_vineyards']));
        }

        if (isset($_POST['wine_interesting'])) {
            update_post_meta($post_id, '_wine_interesting', sanitize_textarea_field($_POST['wine_interesting']));
        }
    }
    
    /**
     * Регистрация Gutenberg блоков
     */
    public function register_gutenberg_blocks() {
        // Регистрация блока для способа производства
        register_block_type('wc-wine-details/production-method', array(
            'render_callback' => array($this, 'render_production_method_block'),
            'attributes' => array(
                'prefix' => array(
                    'type' => 'string',
                    'default' => 'Способ производства: '
                ),
                'className' => array(
                    'type' => 'string',
                    'default' => ''
                )
            )
        ));
        
        // Регистрация блока для выдержки
        register_block_type('wc-wine-details/aging', array(
            'render_callback' => array($this, 'render_aging_block'),
            'attributes' => array(
                'prefix' => array(
                    'type' => 'string',
                    'default' => 'Выдержка: '
                ),
                'className' => array(
                    'type' => 'string',
                    'default' => ''
                )
            )
        ));
        
        // Регистрация блока для дегустационных заметок
        register_block_type('wc-wine-details/tasting-notes', array(
            'render_callback' => array($this, 'render_tasting_notes_block'),
            'attributes' => array(
                'prefix' => array(
                    'type' => 'string',
                    'default' => 'Дегустационные заметки: '
                ),
                'className' => array(
                    'type' => 'string',
                    'default' => ''
                )
            )
        ));
    }
    
    /**
     * Рендер блока "Способ производства"
     */
    public function render_production_method_block($attributes) {
        global $post;
        
        if (!$post || $post->post_type !== 'product') {
            return '';
        }
        
        $value = get_post_meta($post->ID, '_wine_production_method', true);
        
        if (empty($value)) {
            return '';
        }
        
        $prefix = isset($attributes['prefix']) ? esc_html($attributes['prefix']) : 'Способ производства: ';
        $class = isset($attributes['className']) ? ' class="' . esc_attr($attributes['className']) . '"' : '';
        
        return '<div' . $class . '><strong>' . $prefix . '</strong>' . nl2br(esc_html($value)) . '</div>';
    }
    
    /**
     * Рендер блока "Выдержка"
     */
    public function render_aging_block($attributes) {
        global $post;
        
        if (!$post || $post->post_type !== 'product') {
            return '';
        }
        
        $value = get_post_meta($post->ID, '_wine_aging', true);
        
        if (empty($value)) {
            return '';
        }
        
        $prefix = isset($attributes['prefix']) ? esc_html($attributes['prefix']) : 'Выдержка: ';
        $class = isset($attributes['className']) ? ' class="' . esc_attr($attributes['className']) . '"' : '';
        
        return '<div' . $class . '><strong>' . $prefix . '</strong>' . nl2br(esc_html($value)) . '</div>';
    }
    
    /**
     * Рендер блока "Дегустационные заметки"
     */
    public function render_tasting_notes_block($attributes) {
        global $post;
        
        if (!$post || $post->post_type !== 'product') {
            return '';
        }
        
        $value = get_post_meta($post->ID, '_wine_tasting_notes', true);
        
        if (empty($value)) {
            return '';
        }
        
        $prefix = isset($attributes['prefix']) ? esc_html($attributes['prefix']) : 'Дегустационные заметки: ';
        $class = isset($attributes['className']) ? ' class="' . esc_attr($attributes['className']) . '"' : '';
        
        return '<div' . $class . '><strong>' . $prefix . '</strong>' . nl2br(esc_html($value)) . '</div>';
    }
}


// Инициализация плагина
new Divino_Wine_Details();