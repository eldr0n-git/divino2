<?php
/**
 * Plugin Name: DIVINO Custom Related Products
 * Plugin URI:
 * Description: Кастомные похожие товары с приоритетом по таксономиям: product_kind, region, wine_style, tags
 * Version: 2.0.0
 * Author: eldr0n
 * Author URI:
 * License: GPL v2 or later
 * Text Domain: custom-related-products
 */

if (!defined('ABSPATH')) {
    exit;
}

class Custom_Related_Products {

    private $taxonomies_priority = array(
        'region+product_kind+wine_style',
        'region+product_kind',
        'product_kind+product_tag'
    );
    
    private $optional_taxonomies = array('wine_style');

    public function __construct() {
        error_log('DIVINO: Плагин инициализирован! v2.0');
        
        add_action('init', array($this, 'register_gutenberg_block'));
        add_action('init', array($this, 'register_block_pattern'));
        
        remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
        
        add_shortcode('divino_related_products', array($this, 'shortcode_related_products'));
        
        error_log('DIVINO: Шорткод divino_related_products зарегистрирован');
    }

    public function register_gutenberg_block() {
        if (!function_exists('register_block_type')) {
            return;
        }

        wp_register_script(
            'custom-related-products-block',
            plugins_url('block.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n'),
            filemtime(plugin_dir_path(__FILE__) . 'block.js')
        );

        register_block_type('custom-related-products/related-products', array(
            'editor_script' => 'custom-related-products-block',
            'render_callback' => array($this, 'render_block'),
            'attributes' => array(
                'limit' => array('type' => 'number', 'default' => 4),
                'columns' => array('type' => 'number', 'default' => 4),
                'title' => array('type' => 'string', 'default' => 'Похожие товары')
            )
        ));
    }

    public function register_block_pattern() {
        if (function_exists('register_block_pattern')) {
            register_block_pattern(
                'divino/custom-related-products',
                array(
                    'title'       => __('DIVINO Похожие товары', 'custom-related-products'),
                    'description' => __('Кастомные похожие товары с приоритетом по таксономиям', 'custom-related-products'),
                    'content'     => '<!-- wp:custom-related-products/related-products {"limit":4,"columns":4,"title":"Похожие товары"} /-->',
                    'categories'  => array('woocommerce'),
                    'keywords'    => array('related', 'products', 'woocommerce', 'похожие', 'товары'),
                )
            );
        }
    }

    public function disable_wc_related_block($disabled_blocks) {
        $disabled_blocks[] = 'woocommerce/related-products';
        return $disabled_blocks;
    }

    public function shortcode_related_products($atts) {
        error_log('DIVINO: Шорткод вызван!');
        
        $atts = shortcode_atts(array(
            'limit' => 4,
            'columns' => 4,
            'title' => 'Похожие товары'
        ), $atts);
        
        return $this->render_block($atts);
    }

    public function render_block($attributes) {
        global $post;

        error_log('DIVINO render_block: ВЫЗВАН для товара ID = ' . ($post ? $post->ID : 'NULL'));

        if (!is_product() && !$post) {
            return '<div style="padding:20px;background:#fff3cd;border:1px solid #ffc107;"><strong>DEBUG:</strong> Этот блок работает только на странице товара.</div>';
        }

        $product_id = $post ? $post->ID : get_the_ID();
        $limit = isset($attributes['limit']) ? intval($attributes['limit']) : 4;
        $columns = isset($attributes['columns']) ? intval($attributes['columns']) : 4;
        $title = isset($attributes['title']) ? esc_html($attributes['title']) : 'Похожие товары';

        error_log('DIVINO render_block: Вызываем get_custom_related_products для товара ' . $product_id);

        $related_ids = $this->get_custom_related_products(array(), $product_id, array('limit' => $limit));

        error_log('DIVINO render_block: Получено ' . count($related_ids) . ' товаров: ' . implode(',', $related_ids));

        if (empty($related_ids)) {
            error_log('DIVINO render_block: Блок скрыт - нет похожих товаров');
            return '';
        }

        ob_start();
        ?>
        <section class="custom-related-products related products">
            <?php if ($title): ?>
                <h2><?php echo $title; ?></h2>
            <?php endif; ?>
            
            <ul class="products columns-<?php echo esc_attr($columns); ?>">
                <?php
                global $post;
                foreach ($related_ids as $related_id) {
                    $post = get_post($related_id);
                    setup_postdata($post);
                    wc_get_template_part('content', 'product');
                }
                wp_reset_postdata();
                ?>
            </ul>
        </section>
        <?php
        return ob_get_clean();
    }

    public function get_custom_related_products($related_products, $product_id, $args) {
        $related_products = array();
        $limit = isset($args['limit']) ? $args['limit'] : 4;

        $product = wc_get_product($product_id);
        if (!$product) {
            return $related_products;
        }

        $exclude_ids = array($product_id);
        
        error_log('========== DIVINO: Начинаем поиск для товара ID: ' . $product_id . ' ==========');

        foreach ($this->taxonomies_priority as $taxonomy_combo) {
            if (count($related_products) >= $limit) {
                break;
            }

            $taxonomies = explode('+', $taxonomy_combo);
            $tax_query = array('relation' => 'AND');
            $has_all_required = true;
            $combo_description = array();
            $terms_info = array();

            foreach ($taxonomies as $tax) {
                $terms = wp_get_post_terms($product_id, $tax, array('fields' => 'ids'));
                
                if (empty($terms) || is_wp_error($terms)) {
                    if (in_array($tax, $this->optional_taxonomies)) {
                        error_log('DIVINO: ' . $tax . ' отсутствует, пропускаем (опционально)');
                        continue;
                    }
                    error_log('DIVINO: ' . $tax . ' отсутствует (обязательно) - пропускаем комбинацию ' . $taxonomy_combo);
                    $has_all_required = false;
                    break;
                }
                
                // Для product_kind используем только конечные термины
                if ($tax === 'product_kind') {
                    $filtered_terms = array();
                    foreach ($terms as $term_id) {
                        $children = get_term_children($term_id, $tax);
                        if (empty($children) || is_wp_error($children)) {
                            $filtered_terms[] = $term_id;
                        }
                    }
                    $terms = $filtered_terms;
                    
                    if (empty($terms)) {
                        error_log('DIVINO: ' . $tax . ' - нет конечных терминов - пропускаем комбинацию ' . $taxonomy_combo);
                        $has_all_required = false;
                        break;
                    }
                }
                
                error_log('DIVINO: ' . $tax . ' - используем термины: ' . implode(',', $terms));

                $tax_query[] = array(
                    'taxonomy' => $tax,
                    'field' => 'term_id',
                    'terms' => $terms,
                    'operator' => 'IN'
                );
                
                $combo_description[] = $tax;
                $terms_info[$tax] = $terms;
            }

            if (!$has_all_required || count($tax_query) <= 1) {
                error_log('DIVINO: Пропускаем комбинацию ' . $taxonomy_combo);
                continue;
            }

            error_log('DIVINO: === Ищем по комбинации: ' . implode(' + ', $combo_description) . ' ===');

            $args_query = array(
                'post_type' => 'product',
                'posts_per_page' => $limit - count($related_products),
                'post__not_in' => array_merge($exclude_ids, $related_products),
                'post_status' => 'publish',
                'orderby' => 'rand',
                'tax_query' => $tax_query,
                'meta_query' => array(
                    array(
                        'key' => '_stock_status',
                        'value' => 'instock',
                        'compare' => '='
                    )
                )
            );

            $query = new WP_Query($args_query);

            if ($query->have_posts()) {
                $found_count = 0;
                while ($query->have_posts()) {
                    $query->the_post();
                    $found_id = get_the_ID();
                    
                    $is_valid = true;
                    $validation_log = array();
                    
                    foreach ($terms_info as $tax => $required_terms) {
                        $found_terms = wp_get_post_terms($found_id, $tax, array('fields' => 'ids'));
                        
                        // Для product_kind фильтруем родительские термины
                        if ($tax === 'product_kind') {
                            $filtered_terms = array();
                            foreach ($found_terms as $term_id) {
                                $children = get_term_children($term_id, $tax);
                                if (empty($children) || is_wp_error($children)) {
                                    $filtered_terms[] = $term_id;
                                }
                            }
                            $found_terms = $filtered_terms;
                        }
                        
                        $has_match = !empty(array_intersect($required_terms, $found_terms));
                        
                        $validation_log[] = $tax . ': ' . ($has_match ? 'OK' : 'FAIL');
                        
                        if (!$has_match) {
                            $is_valid = false;
                            error_log('DIVINO: Товар ID ' . $found_id . ' НЕ ПРОШЕЛ по ' . $tax);
                        }
                    }
                    
                    if ($is_valid && !in_array($found_id, $related_products)) {
                        $related_products[] = $found_id;
                        $found_count++;
                        error_log('DIVINO: ✓ Товар ID ' . $found_id . ' добавлен. Проверка: ' . implode(', ', $validation_log));
                    }
                }
                wp_reset_postdata();
                error_log('DIVINO: Найдено и добавлено ' . $found_count . ' товаров');
            }
        }

        if (empty($related_products)) {
            error_log('DIVINO: ========== Похожие товары не найдены, блок скрыт ==========');
            return array();
        }

        error_log('DIVINO: ========== Итого возвращаем ' . count($related_products) . ' товаров: ' . implode(', ', $related_products) . ' ==========');
        return array_slice($related_products, 0, $limit);
    }
}

new Custom_Related_Products();