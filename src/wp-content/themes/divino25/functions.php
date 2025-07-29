<?php
/**
 * divino theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage divino theme
 * @since divino theme 1.0
 */

// Adds theme support for post formats.
if ( ! function_exists( 'divino_post_format_setup' ) ) :
	/**
	 * Adds theme support for post formats.
	 *
	 * @since divino 1.0
	 *
	 * @return void
	 */
	function divino_post_format_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}
endif;
add_action( 'after_setup_theme', 'divino_post_format_setup' );

// Enqueues style.css on the front.
if ( ! function_exists( 'divino_enqueue_styles' ) ) :
	/**
	 * Enqueues style.css on the front.
	 *
	 * @since divino 1.0
	 *
	 * @return void
	 */
	function divino_enqueue_styles() {
		wp_enqueue_style(
			'divino-style',
			get_parent_theme_file_uri( 'style.css' ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'divino_enqueue_styles' );

// FONTS
function main_enqueue_onest_font() {
    wp_enqueue_style(
        'onest-font',
        'https://fonts.googleapis.com/css2?family=Onest:wght@100..900&display=swap',
        [],
        null
    );
}
function main_enqueue_rubik_font() {
    wp_enqueue_style(
        'main-font',
        'https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap',
        [],
        null
    );
}
function main_enqueue_title_font() {
    wp_enqueue_style(
        'title-font',
        'https://fonts.googleapis.com/css2?family=Literata:ital,opsz,wght@0,7..72,200..900;1,7..72,200..900&display=swap',
        [],
        null
    );
}

add_action('wp_enqueue_scripts', 'main_enqueue_onest_font');
add_action('wp_enqueue_scripts', 'main_enqueue_rubik_font');
add_action('wp_enqueue_scripts', 'main_enqueue_title_font');

// Registers pattern categories.
if ( ! function_exists( 'divino_pattern_categories' ) ) :
	/**
	 * Registers pattern categories.
	 *
	 * @since divino 1.0
	 *
	 * @return void
	 */
	function divino_pattern_categories() {
		register_block_pattern_category(
			'divino_page',
			array(
				'label'       => __( 'Pages', 'divino' ),
				'description' => __( 'A collection of full page layouts.', 'divino' ),
			)
		);

		register_block_pattern_category(
			'divino_post-format',
			array(
				'label'       => __( 'Post formats', 'divino' ),
				'description' => __( 'A collection of post format patterns.', 'divino' ),
			)
		);
	}
endif;
add_action( 'init', 'divino_pattern_categories' );

// Registers block binding sources.
if ( ! function_exists( 'divino_register_block_bindings' ) ) :
	/**
	 * Registers the post format block binding source.
	 *
	 * @since divino 1.0
	 *
	 * @return void
	 */
	function divino_register_block_bindings() {
		register_block_bindings_source(
			'divino/format',
			array(
				'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'divino' ),
				'get_value_callback' => 'divino_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'divino_register_block_bindings' );

// Registers block binding callback function for the post format name.
if ( ! function_exists( 'divino_format_binding' ) ) :
	/**
	 * Callback function for the post format name block binding source.
	 *
	 * @since divino 1.0
	 *
	 * @return string|void Post format name, or nothing if the format is 'standard'.
	 */
	function divino_format_binding() {
		$post_format_slug = get_post_format();

		if ( $post_format_slug && 'standard' !== $post_format_slug ) {
			return get_post_format_string( $post_format_slug );
		}
	}
endif;

// Adds a custom action to log product save events.
add_action('save_post_product', function ($post_id) {
    error_log('Сохраняется товар: ' . $post_id);
    error_log('Тип товара: ' . json_encode(wp_get_post_terms($post_id, 'product_type', ['fields' => 'names'])));
});

//=====================================================================================
// IMAGES SIZE CATALOG
add_theme_support('post-thumbnails');
add_image_size('product-card', 260, 370, true);

add_filter( 'woocommerce_template_debug_mode', '__return_true' );

// Disable cropping for product thumbnails
add_filter( 'woocommerce_get_image_size_thumbnail', function( $size ) {
    return array(
        'width'  => 0,
        'height' => 600,
        'crop'   => 0,
    );
} );

if ( ! function_exists( 'divino_get_cart_contents_count' ) ) {
    function divino_get_cart_contents_count() {
        if ( class_exists( 'WooCommerce' ) && WC()->cart ) {
            return WC()->cart->get_cart_contents_count();
        }
        return 0;
    }
}

//=====================================================================================
// Add filters to catalog page
require_once __DIR__ . '/divino25-filters.php';

// Sidebar for filters
// function add_woocommerce_sidebar() {
//     if (is_shop() || is_product_category() || is_product_tag() || is_tax('product_kind') || is_tax('region') ) {
//         echo '<h1>22222222222222EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE</h1>';
//         echo '<div class="shop-sidebar">';
//         dynamic_sidebar('shop-filters');
//         echo '</div>';
//     }
// }
add_action('woocommerce_sidebar', 'add_shop_filters');

// Shortcode for WooCommerce sidebar
function divino_woocommerce_sidebar_shortcode() {
    ob_start(); // Включаем буферизацию вывода
    do_action( 'woocommerce_sidebar' ); // Выполняем хук, его вывод попадет в буфер
    return ob_get_clean(); // Возвращаем содержимое буфера и очищаем его
}
add_shortcode( 'divino_woocommerce_sidebar', 'divino_woocommerce_sidebar_shortcode' );



// Custom widget for product_kind taxonomy
function add_product_kind_filter() {
    $terms = get_terms(array(
        'taxonomy' => 'product_kind',
        'hide_empty' => true,
    ));
    if (!empty($terms) && !is_wp_error($terms)) {
        echo '<div class="widget woocommerce widget_product_kind">';
        echo '<h3 class="widget-title">Тип товара</h3>';
        echo '<ul>';
        foreach ($terms as $term) {
            $current_term = get_queried_object();
            $active_class = '';
            if ($current_term && isset($current_term->term_id) && $current_term->term_id == $term->term_id) {
                $active_class = ' class="active"';
            }
            echo '<li' . $active_class . '>';
            echo '<a href="' . get_term_link($term) . '">' . $term->name . ' (' . $term->count . ')</a>';
            echo '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }
}

function add_product_country_filter() {
    $terms = get_terms(array(
        'taxonomy' => 'region',
        'hide_empty' => true,
    ));
    if (!empty($terms) && !is_wp_error($terms)) {
        echo '<div class="widget woocommerce widget_product_region">';
        echo '<h3 class="widget-title">Страны</h3>';
        echo '<form class="filter-form" method="post">';

        // Preserve existing query parameters
        foreach ($_GET as $key => $value) {
            if ($key !== 'region') {
                if (is_array($value)) {
                    foreach ($value as $val) {
                        echo '<input type="hidden" name="' . esc_attr($key) . '[]" value="' . esc_attr($val) . '">';
                    }
                } else {
                    echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '">';
                }
            }
        }

        echo '<ul>';
        foreach ($terms as $term) {
            if ( $term->parent == 0 ) {
                $checked = isset($_GET['region']) && in_array($term->slug, (array)$_GET['region']) ? 'checked' : '';
                echo '<li>';
                echo '<label><input type="checkbox" name="region[]" value="' . esc_attr($term->slug) . '" ' . $checked . '> ' . esc_html($term->name) . ' (' . $term->count . ')</label>';
                echo '</li>';
            }
        }
        echo '</ul>';
        echo '<button type="submit">Применить</button>';
        echo '</form>';
        echo '</div>';
    }
}

function add_product_region_filter() {
    $terms = get_terms(array(
        'taxonomy' => 'region',
        'hide_empty' => true,
    ));
    if (!empty($terms) && !is_wp_error($terms)) {
        echo '<div class="widget woocommerce widget_product_region">';
        echo '<h3 class="widget-title">Регионы</h3>';
        echo '<ul>';
        foreach ($terms as $term) {
            if ( $term->parent != 0 ) {
                $current_term = get_queried_object();
                $active_class = '';
                if ($current_term && isset($current_term->term_id) && $current_term->term_id == $term->term_id) {
                    $active_class = ' class="active"';
                }
                echo '<li' . $active_class . '>';
                echo '<a href="' . get_term_link($term) . '">' . esc_html($term->name) . ' (' . $term->count . ')</a>';
                echo '</li>';
            }
        }
        echo '</ul>';
        echo '</div>';
    }
}

// Add filters before shop loop
function add_shop_filters() {
    if (is_shop() || is_product_category() || is_product_tag() || is_tax('product_kind') || is_tax('region') || isset($_GET['region'])) {
        echo '<div class="shop-filters-sidebar">';

        // Product categories
        //the_widget('WC_Widget_Product_Categories', array('title' => 'Категории'));

        // Custom filters
        add_product_kind_filter();
        add_product_country_filter();
        add_product_region_filter();

        // Price filter
        the_widget('WC_Widget_Price_Filter', array('title' => 'Цена'));

        // Color attribute if exists
        if (taxonomy_exists('pa_color')) {
            the_widget('WC_Widget_Layered_Nav', array('title' => 'Цвет', 'attribute' => 'pa_color'));
        }
        echo '</div>';
    }
}
//add_action('woocommerce_before_shop_loop', 'add_shop_filters', 5);

function add_region_query_var( $vars ) {
    $vars[] = 'region';
    return $vars;
}
add_filter( 'query_vars', 'add_region_query_var' );

function divino_region_filter_as_archive( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {
        $region = $query->get( 'region' );
        if ( ! empty( $region ) ) {
            $query->set( 'post_type', 'product' );
            $query->set( 'tax_query', array(
                array(
                    'taxonomy' => 'region',
                    'field'    => 'slug',
                    'terms'    => (array) $region,
                ),
            ) );

            // Make WordPress and WooCommerce recognize this as a product archive page
            $query->set( 'is_shop', true );
            $query->set( 'is_product_taxonomy', true );
            $query->set( 'is_tax', true );
        }
    }
}
add_action( 'pre_get_posts', 'divino_region_filter_as_archive' );

// Reverse the order of terms in the 'region' taxonomy when displayed
function reverse_region_terms_order($terms, $post_id, $taxonomy) {
    if ($taxonomy === 'region') {
        return array_reverse($terms);
    }
    return $terms;
}
add_filter('get_the_terms', 'reverse_region_terms_order', 10, 3);


// Custom render for product region
// Ваш существующий фильтр для archive-product
add_filter('render_block', function ($block_content, $block) {
    if ($block['blockName'] === 'core/post-terms' && isset($block['attrs']['term']) && $block['attrs']['term'] === 'region') {
        return render_product_regions();
    }
    return $block_content;
}, 10, 2);

// Для single-product
add_action('woocommerce_single_product_summary', function() {
    echo render_product_regions();
}, 19);

// Общая функция
function render_product_regions() {
    $post_id = get_the_ID();
    $terms = get_the_terms($post_id, 'region');

    if (!empty($terms) && !is_wp_error($terms)) {
        $output = '<div class="product-regions text-center">';
        foreach ($terms as $key => $term) {
            if ($key > 0) {
                $output .= '<span class="region"><a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a></span>';
            } else {
                $output .= '<span class="country country-'.$term->slug.'"><a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a></span>';
            }
        }
        $output .= '</div>';
        return $output;
    }
    return '';
}


add_filter('woocommerce_get_stock_html', 'custom_stock_html', 10, 2);

// Hiding Number of Products
function custom_stock_html($html, $product) {
    // Проверяем, есть ли товар в наличии
    if ($product->is_in_stock()) {
        return '<p class="sp-stock in-stock">В наличии</p>';
    } else {
        return '<p class="sp-stock out-of-stock">Нет в наличии</p>';
    }
    return $html; // Возвращаем исходный HTML, если ничего не изменилось
}
























add_filter('woocommerce_page_title', 'divino_custom_woocommerce_title');
function divino_custom_woocommerce_title($title) {
    if (is_shop()) {
        return 'Все наши вина'; // главная страница магазина
    }

    if (is_product_category('red-wine')) {
        return 'Красные вина';
    }

    if (is_product_tag('sparkling')) {
        return 'Игристые шедевры';
    }

    return $title; // по умолчанию
}






