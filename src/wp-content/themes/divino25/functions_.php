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

/* TMP! */
add_action('wp_head', 'debug_checkout_fields');
function debug_checkout_fields() {
    if (is_checkout()) {
        global $woocommerce;
        echo '<!-- WC Version: ' . $woocommerce->version . ' -->';
        echo '<!-- Theme: ' . get_template() . ' -->';
    }
}


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
}, 18);

// Общая функция
function render_product_regions() {
    $post_id = get_the_ID();
    $terms = get_the_terms($post_id, 'region');

    if (!empty($terms) && !is_wp_error($terms)) {
        $output = '<div class="product-regions text-center">';


        // Сортируем термины: сначала родительские, потом дочерние
        usort($terms, function($a, $b) {
            if ($a->parent === $b->parent) {
                return 0;
            }
            return ($a->parent === 0) ? -1 : 1; // Сначала родительские, потом дочерние
        });

        foreach ($terms as $key => $term) {
            if ( $term->parent !== 0) {
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



/* * Скрытие блока post-terms для таксономии product_kind
 * Этот фильтр удаляет блок post-terms, если он относится к таксономии product_kind
 */
add_filter('get_the_terms', function($terms, $post_id, $taxonomy) {
    if ($taxonomy === 'product_kind' && !empty($terms)) {
        // Фильтруем только дочерние термины (у которых parent != 0)
        $filtered_terms = array();
        foreach ($terms as $term) {
            if ($term->parent != 0) { // Только дочерние термины
                $filtered_terms[] = $term;
            }
        }
        return !empty($filtered_terms) ? $filtered_terms : false;
    }
    return $terms;
}, 10, 3);


/* * Отключение полей платёжного адреса в WooCommerce
 * Этот фильтр удаляет все поля платёжного адреса из формы оформления заказа
 */
// add_filter( 'woocommerce_checkout_fields', 'disable_billing_address_fields' );
// function disable_billing_address_fields( $fields ) {
//     // Удаляем все поля платёжного адреса на странице оформления заказа
//     $fields['billing'] = array();
//     return $fields;
// }

add_filter( 'woocommerce_enable_order_notes_field', '__return_false' ); // Отключаем поле заметок к заказу

add_filter( 'woocommerce_my_account_get_addresses', 'disable_billing_address_my_account', 10, 2 );
function disable_billing_address_my_account( $addresses ) {
    // Удаляем платёжный адрес из раздела "Адреса" в личном кабинете
    unset( $addresses['billing'] );
    return $addresses;
}

add_filter( 'woocommerce_my_account_edit_address_field', 'hide_billing_address_fields', 10, 2 );
function hide_billing_address_fields( $fields, $type ) {
    // Скрываем поля редактирования платёжного адреса
    if ( $type === 'billing' ) {
        $fields = array();
    }
    return $fields;
}



// Добавляем JavaScript для автоматического заполнения поля страны
add_action( 'wp_footer', 'auto_fill_country_script' );

function auto_fill_country_script() {
    if ( is_account_page() ) {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Скрываем поле страны
            $('#shipping_country_field, #billing_country_field').hide();

            // Автоматически устанавливаем значение
            $('#shipping_country, #billing_country').val('KZ');

            // При отправке формы убеждаемся, что значение установлено
            $('form.edit-address').on('submit', function() {
                if ($('#shipping_country').length && $('#shipping_country').val() === '') {
                    $('#shipping_country').val('KZ');
                }
                if ($('#billing_country').length && $('#billing_country').val() === '') {
                    $('#billing_country').val('KZ');
                }
            });
        });
        </script>
        <?php
    }
}

// CSS для скрытия поля
add_action( 'wp_head', 'hide_country_field_styles' );

function hide_country_field_styles() {
    if ( is_account_page() ) {
        echo '<style>
            #shipping_country_field,
            #billing_country_field {
                display: none !important;
            }
        </style>';
    }
}


// Remove Downloads tab from My Account
add_filter( 'woocommerce_account_menu_items', 'remove_downloads_tab' );
function remove_downloads_tab( $menu_items ) {
    // Удаляем вкладку "Загрузки" из меню личного кабинета
    unset( $menu_items['downloads'] );
    return $menu_items;
}
add_filter( 'woocommerce_customer_has_downloads', '__return_false' );

// Add custom body class for product slug
// add_filter( 'body_class', 'add_divino25_slug_body_class' );
// function add_divino25_slug_body_class( $classes ) {
//     // if ( is_singular( 'product' ) ) {
//     //     global $post;
//     //     if ( $post ) {
//     //         $slug = $post->post_name; // это slug товара
//     //         $classes[] = 'divino25-' . sanitize_html_class( $slug );
//     //     }
//     // }

//     // Получаем ID текущего товара
// //$product_id = get_the_ID(); // Работает внутри цикла WordPress
// // Или, если у вас есть объект товара: $product_id = $product->get_id();

// // Получаем термины таксономии product_kind для товара
// //$terms = get_the_terms($product_id, 'product_kind');

// // if ($terms && !is_wp_error($terms)) {
// //     // Перебираем термины
// //     foreach ($terms as $term) {
// //         echo esc_html($term->name); // Выводим название термина, например, "premium"
// //     }
// // } else {
// //     echo 'Термины не найдены или произошла ошибка';
// // }
// //var_dump($terms[0]);


//     // if (is_tax('product_kind')) {
//     //     $term = get_queried_object();
//     //     if ($term && !is_wp_error($term)) {
//     //         $classes[] = 'divino25-product-kind-' . sanitize_html_class($terms[0]->slug);
//     //     }
//     // }
//      return '';
// }

add_filter('template_include', function($template) {
    ob_start();
    include $template;
    $output = ob_get_clean();

    // Получаем классы от body_class()
    $body_classes = get_body_class();
    $body_classes[] = 'divino25-debug'; // Добавляем ваш класс
    $classes_string = implode(' ', $body_classes); // Преобразуем массив классов в строку
    $product_id = get_the_ID();
    $terms = get_the_terms($product_id, 'product_kind');


    if ( is_singular( 'product' ) ) {
        global $post;
        if ( $post ) {
            $slug = $post->post_name; // это slug товара
        }
    }
    if ($slug) {
        $term = get_queried_object();
        if ($term && !is_wp_error($term)) {
            $classes[] = 'divino25-product-kind-' . sanitize_html_class($term->slug);
            $classes_string .= ' divino25-product-kind-' . sanitize_html_class($terms[0]->slug);
        }
    }

    // Заменяем <body> с учётом всех классов
    $output = str_replace('<body', '<body class="' . esc_attr($classes_string) . '"', $output);

    echo $output;
    return null;
});






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

/* * Customize the HTML output of the New Products block
 */
add_filter('woocommerce_blocks_product_grid_item_html', 'customize_new_products_block_html', 10, 3);
function customize_new_products_block_html($html, $data, $product) {
    // Кастомный HTML для каждого продукта в блоке
    $html = '<li class="divino-product-item">';
    $html .= '<a href="' . esc_url($data->permalink) . '">' . $data->image . '</a>';
    $html .= '<div class="divino-price">' . $data->price . '</div>';
    $html .= '<a href="' . esc_url($data->permalink) . '">' . esc_html($data->title) . '</a>';
    $html .= '</li>';
    return $html;
}




/**
 * Shortcode для вывода регионов товара
 * Использование: [product_regions] или [product_regions product_id="123"]
 */
function divino_product_regions_shortcode($atts) {
    // Получаем атрибуты шорткода
    $atts = shortcode_atts(array(
        'product_id' => null, // ID товара (необязательный)
        'class' => '', // Дополнительный CSS класс (необязательный)
    ), $atts, 'product_regions');

    // Определяем ID товара
    if ($atts['product_id']) {
        $post_id = intval($atts['product_id']);
    } else {
        $post_id = get_the_ID();
    }

    // Проверяем, что ID товара валиден
    if (!$post_id || get_post_type($post_id) !== 'product') {
        return ''; // Возвращаем пустую строку, если это не товар
    }

    // Получаем термины таксономии region
    $terms = get_the_terms($post_id, 'region');

    if (empty($terms) || is_wp_error($terms)) {
        return ''; // Возвращаем пустую строку, если нет регионов
    }

    // Формируем вывод
    $additional_class = $atts['class'] ? ' ' . sanitize_html_class($atts['class']) : '';
    $output = '<div class="product-regions text-center' . $additional_class . '">';

    // Сортируем термины: сначала родительские, потом дочерние
    usort($terms, function($a, $b) {
        if ($a->parent === $b->parent) {
            return 0;
        }
        return ($a->parent === 0) ? -1 : 1; // Сначала родительские, потом дочерние
    });

    foreach ($terms as $key => $term) {
        if ($term->parent !== 0) {
            // Дочерний термин (регион)
            $output .= '<span class="region"><a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a></span>';
        } else {
            // Родительский термин (страна)
            $output .= '<span class="country country-' . esc_attr($term->slug) . '"><a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a></span>';
        }
    }

    $output .= '</div>';

    return $output;
}

// Регистрируем шорткод
add_shortcode('product_regions', 'divino_product_regions_shortcode');

/**
 * Дополнительный шорткод для вывода только стран товара
 * Использование: [product_countries] или [product_countries product_id="123"]
 */
function divino_product_countries_shortcode($atts) {
    $atts = shortcode_atts(array(
        'product_id' => null,
        'class' => '',
    ), $atts, 'product_countries');

    if ($atts['product_id']) {
        $post_id = intval($atts['product_id']);
    } else {
        $post_id = get_the_ID();
    }

    if (!$post_id || get_post_type($post_id) !== 'product') {
        return '';
    }

    $terms = get_the_terms($post_id, 'region');

    if (empty($terms) || is_wp_error($terms)) {
        return '';
    }

    $additional_class = $atts['class'] ? ' ' . sanitize_html_class($atts['class']) : '';
    $output = '<div class="product-countries' . $additional_class . '">';

    foreach ($terms as $term) {
        if ($term->parent === 0) { // Только родительские термины (страны)
            $output .= '<span class="country country-' . esc_attr($term->slug) . '"><a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a></span>';
        }
    }

    $output .= '</div>';

    return $output;
}

// Регистрируем дополнительный шорткод
add_shortcode('product_countries', 'divino_product_countries_shortcode');

/**
 * Шорткод для вывода только регионов товара (без стран)
 * Использование: [product_regions_only] или [product_regions_only product_id="123"]
 */
function divino_product_regions_only_shortcode($atts) {
    $atts = shortcode_atts(array(
        'product_id' => null,
        'class' => '',
    ), $atts, 'product_regions_only');

    if ($atts['product_id']) {
        $post_id = intval($atts['product_id']);
    } else {
        $post_id = get_the_ID();
    }

    if (!$post_id || get_post_type($post_id) !== 'product') {
        return '';
    }

    $terms = get_the_terms($post_id, 'region');

    if (empty($terms) || is_wp_error($terms)) {
        return '';
    }

    $additional_class = $atts['class'] ? ' ' . sanitize_html_class($atts['class']) : '';
    $output = '<div class="product-regions-only' . $additional_class . '">';

    foreach ($terms as $term) {
        if ($term->parent !== 0) { // Только дочерние термины (регионы)
            $output .= '<span class="region"><a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a></span>';
        }
    }

    $output .= '</div>';

    return $output;
}

// Регистрируем третий шорткод
add_shortcode('product_regions_only', 'divino_product_regions_only_shortcode');


function catalogue_enqueue_scripts() {
    // Регистрируем и подключаем JS-файл
    wp_enqueue_script(
        'catalogue-script', // Уникальный идентификатор скрипта
        get_theme_file_uri('/assets/js/catalogue.js'),

        '1.0.0',
        true // Загружать в футере (true) или в хедере (false)
    );
}
add_action('wp_enqueue_scripts', 'catalogue_enqueue_scripts');

/**
 * Функция для установки контекста товара в Query Loop блоке
 * Заменяет шорткоды на версии с product_id
 *
 * @param string $block_content Содержимое блока
 * @param array $block Массив атрибутов блока
 * @return string Изменённое содержимое блока
 */
function set_product_context_for_query_loop($block_content, $block) {
    // Проверяем, что мы в Query Loop блоке
    if ($block['blockName'] === 'core/query' ||
        (isset($block['attrs']['query']['postType']) && $block['attrs']['query']['postType'] === 'product')) {

        global $post;
        if ($post && $post->post_type === 'product') {
            $product_id = $post->ID;

            // Заменяем шорткоды, добавляя product_id
            $block_content = str_replace(
                ['[divino_sugar_short]', '[divino_wine_style]'],
                [
                    '[divino_sugar_short product_id="' . $product_id . '"]',
                    '[divino_wine_style product_id="' . $product_id . '"]'
                ],
                $block_content
            );
        }
    }

    return $block_content;
}
add_filter('render_block', 'set_product_context_for_query_loop', 10, 2);



// AJAX обработчик входа пользователя
add_action('wp_ajax_custom_user_login', 'handle_custom_user_login');
add_action('wp_ajax_nopriv_custom_user_login', 'handle_custom_user_login');

function handle_custom_user_login() {
    // Проверяем nonce
    if (!wp_verify_nonce($_POST['security'], 'custom_login_nonce')) {
        wp_die(json_encode(array('success' => false, 'data' => 'Ошибка безопасности')));
    }

    $username = sanitize_text_field($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    // Попытка входа
    $user = wp_authenticate($username, $password);

    if (is_wp_error($user)) {
        wp_die(json_encode(array('success' => false, 'data' => $user->get_error_message())));
    }

    // Вход успешен
    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID, $remember);

    wp_die(json_encode(array('success' => true, 'data' => 'Вход выполнен успешно')));
}

// AJAX обработчик регистрации пользователя
add_action('wp_ajax_custom_user_register', 'handle_custom_user_register');
add_action('wp_ajax_nopriv_custom_user_register', 'handle_custom_user_register');

function handle_custom_user_register() {
    // Проверяем nonce
    if (!wp_verify_nonce($_POST['security'], 'custom_register_nonce')) {
        wp_die(json_encode(array('success' => false, 'data' => 'Ошибка безопасности')));
    }

    // Проверяем, разрешена ли регистрация
    if (!get_option('users_can_register')) {
        wp_die(json_encode(array('success' => false, 'data' => 'Регистрация отключена')));
    }

    $username = sanitize_text_field($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];

    // Валидация
    if (empty($username) || empty($email) || empty($password)) {
        wp_die(json_encode(array('success' => false, 'data' => 'Заполните все поля')));
    }

    if (!is_email($email)) {
        wp_die(json_encode(array('success' => false, 'data' => 'Некорректный email')));
    }

    if (username_exists($username)) {
        wp_die(json_encode(array('success' => false, 'data' => 'Пользователь с таким именем уже существует')));
    }

    if (email_exists($email)) {
        wp_die(json_encode(array('success' => false, 'data' => 'Пользователь с таким email уже существует')));
    }

    // Создаем пользователя
    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        wp_die(json_encode(array('success' => false, 'data' => $user_id->get_error_message())));
    }

    // Автоматический вход после регистрации
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);

    wp_die(json_encode(array('success' => true, 'data' => 'Регистрация прошла успешно')));
}

// Сохранение кастомного поля WhatsApp
// add_action('woocommerce_checkout_update_order_meta', 'save_custom_checkout_field_whatsapp');
// function save_custom_checkout_field_whatsapp($order_id) {
//     if (!empty($_POST['billing_whatsapp'])) {
//         update_post_meta($order_id, '_billing_whatsapp', sanitize_text_field($_POST['billing_whatsapp']));
//     }
// }

// Отображение поля WhatsApp в админке заказа
// add_action('woocommerce_admin_order_data_after_billing_address', 'display_whatsapp_in_admin_order');
// function display_whatsapp_in_admin_order($order) {
//     $whatsapp = get_post_meta($order->get_id(), '_billing_whatsapp', true);
//     if ($whatsapp) {
//         echo '<p><strong>WhatsApp:</strong> ' . esc_html($whatsapp) . '</p>';
//     }
// }

// Добавление поля WhatsApp в email уведомления
// add_action('woocommerce_email_customer_details', 'add_whatsapp_to_emails', 20, 4);
// function add_whatsapp_to_emails($order, $sent_to_admin, $plain_text, $email) {
//     $whatsapp = get_post_meta($order->get_id(), '_billing_whatsapp', true);

//     if ($whatsapp) {
//         if ($plain_text) {
//             echo "\nWhatsApp: " . $whatsapp . "\n";
//         } else {
//             echo '<p><strong>WhatsApp:</strong> ' . esc_html($whatsapp) . '</p>';
//         }
//     }
// }

// Валидация полей при оформлении заказа
// add_action('woocommerce_checkout_process', 'validate_custom_checkout_fields');
// function validate_custom_checkout_fields() {
//     // Валидация телефона
//     if (empty($_POST['billing_phone'])) {
//         wc_add_notice('Пожалуйста, укажите номер телефона.', 'error');
//     }

//     // Валидация WhatsApp (если заполнен)
//     if (!empty($_POST['billing_whatsapp'])) {
//         $whatsapp = sanitize_text_field($_POST['billing_whatsapp']);
//         if (!preg_match('/^[\+]?[0-9\s\-\(\)]+$/', $whatsapp)) {
//             wc_add_notice('Пожалуйста, введите корректный номер WhatsApp.', 'error');
//         }
//     }

//     // Валидация адреса
//     if (empty($_POST['billing_address_1'])) {
//         wc_add_notice('Пожалуйста, укажите адрес доставки.', 'error');
//     }
// }


/* функции сохранения данных формы заказа */
add_filter('woocommerce_checkout_fields', 'customize_checkout_fields');
function customize_checkout_fields($fields) {
    // Удаляем ВСЕ ненужные поля billing
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_postcode']);
    
    // Оставляем и настраиваем нужные поля
    $fields['billing']['billing_first_name']['required'] = true;
    $fields['billing']['billing_first_name']['placeholder'] = 'Введите ваше имя';
    
    $fields['billing']['billing_last_name']['required'] = true;
    $fields['billing']['billing_last_name']['placeholder'] = 'Введите вашу фамилию';
    
    $fields['billing']['billing_phone']['required'] = true;
    $fields['billing']['billing_phone']['placeholder'] = '+7 (___) ___-__-__';
    
    $fields['billing']['billing_email']['required'] = true;
    $fields['billing']['billing_email']['placeholder'] = 'ваш@email.com';
    
    $fields['billing']['billing_address_1']['required'] = true;
    $fields['billing']['billing_address_1']['label'] = 'Адрес доставки';
    $fields['billing']['billing_address_1']['placeholder'] = 'Улица, дом, квартира';
    
    // Добавляем поле WhatsApp
    $fields['billing']['billing_whatsapp'] = array(
        'label'       => 'WhatsApp',
        'placeholder' => '+7 (___) ___-__-__',
        'required'    => false,
        'class'       => array('form-row-wide'),
        'clear'       => true,
        'priority'    => 25,
        'type'        => 'tel'
    );
    
    // Скрываем поле страны, но оставляем его в форме
    $fields['billing']['billing_country']['default'] = 'KZ';
    $fields['billing']['billing_country']['class'][] = 'hidden-country-field';
    
    // Удаляем все shipping поля
    $fields['shipping'] = array();
    
    // Удаляем поле заметок
    unset($fields['order']['order_comments']);
    
    return $fields;
}

// 2. Принудительно устанавливаем значения по умолчанию
add_filter('woocommerce_checkout_get_value', 'set_checkout_field_defaults', 10, 2);
function set_checkout_field_defaults($value, $input) {
    if ($input === 'billing_country') {
        return 'KZ';
    }
    return $value;
}



// // Отображение всех полей в админке заказа
// add_action('woocommerce_admin_order_data_after_billing_address', 'display_custom_fields_in_admin_order');
// function display_custom_fields_in_admin_order($order) {
//     // Отображаем только WhatsApp, остальные поля отображаются автоматически
//     $whatsapp = get_post_meta($order->get_id(), '_billing_whatsapp', true);
//     if ($whatsapp) {
//         echo '<p><strong>WhatsApp:</strong> ' . esc_html($whatsapp) . '</p>';
//     }
// }


// // Добавление полей в email уведомления
// add_action('woocommerce_email_customer_details', 'add_custom_fields_to_emails', 20, 4);
// function add_custom_fields_to_emails($order, $sent_to_admin, $plain_text, $email) {
//     $whatsapp = get_post_meta($order->get_id(), '_billing_whatsapp', true);
    
//     if ($whatsapp) {
//         if ($plain_text) {
//             echo "\nWhatsApp: " . $whatsapp . "\n";
//         } else {
//             echo '<p><strong>WhatsApp:</strong> ' . esc_html($whatsapp) . '</p>';
//         }
//     }
// }



// 3. Простая валидация только для WhatsApp
add_action('woocommerce_checkout_process', 'simple_checkout_validation');
function simple_checkout_validation() {
    // Проверяем только WhatsApp, если он заполнен
    if (!empty($_POST['billing_whatsapp'])) {
        $whatsapp = sanitize_text_field($_POST['billing_whatsapp']);
        if (strlen($whatsapp) < 10) {
            wc_add_notice('Введите корректный номер WhatsApp.', 'error');
        }
    }
}
// add_action('woocommerce_checkout_process', 'validate_checkout_fields_custom');
// function validate_checkout_fields_custom() {
//     // Проверяем только кастомное поле WhatsApp, остальные поля WooCommerce проверит сам
//     if (!empty($_POST['billing_whatsapp'])) {
//         $whatsapp = sanitize_text_field($_POST['billing_whatsapp']);
//         // Простая проверка на наличие цифр и допустимых символов
//         if (!preg_match('/^[\+]?[0-9\s\-\(\)]+$/', $whatsapp)) {
//             wc_add_notice('Пожалуйста, введите корректный номер WhatsApp.', 'error');
//         }
//     }
// }

// 4. Сохранение кастомных полей
add_action('woocommerce_checkout_update_order_meta', 'save_whatsapp_field');
function save_whatsapp_field($order_id) {
    if (!empty($_POST['billing_whatsapp'])) {
        update_post_meta($order_id, '_billing_whatsapp', sanitize_text_field($_POST['billing_whatsapp']));
    }
    
    // Принудительно сохраняем страну
    update_post_meta($order_id, '_billing_country', 'KZ');
    update_post_meta($order_id, '_shipping_country', 'KZ');
}

// 5. Отображение в админке
add_action('woocommerce_admin_order_data_after_billing_address', 'display_whatsapp_in_admin');
function display_whatsapp_in_admin($order) {
    $whatsapp = get_post_meta($order->get_id(), '_billing_whatsapp', true);
    if ($whatsapp) {
        echo '<p><strong>WhatsApp:</strong> ' . esc_html($whatsapp) . '</p>';
    }
}

// 6. Добавление в email
add_action('woocommerce_email_customer_details', 'add_whatsapp_to_email', 20, 4);
function add_whatsapp_to_email($order, $sent_to_admin, $plain_text, $email) {
    $whatsapp = get_post_meta($order->get_id(), '_billing_whatsapp', true);
    if ($whatsapp) {
        if ($plain_text) {
            echo "\nWhatsApp: " . $whatsapp . "\n";
        } else {
            echo '<p><strong>WhatsApp:</strong> ' . esc_html($whatsapp) . '</p>';
        }
    }
}

// 7. CSS для скрытия поля страны
add_action('wp_head', 'checkout_custom_styles');
function checkout_custom_styles() {
    if (is_checkout()) {
        ?>
        <style>
        .hidden-country-field,
        #billing_country_field {
            display: none !important;
        }
        </style>
        <?php
    }
}

// 8. JavaScript для обеспечения корректной работы
add_action('wp_footer', 'checkout_custom_script');
function checkout_custom_script() {
    if (is_checkout()) {
        ?>
        <script>
        jQuery(document).ready(function($) {
            // Устанавливаем Казахстан
            $('#billing_country').val('KZ');
            
            // При любых обновлениях checkout
            $(document.body).on('updated_checkout', function() {
                $('#billing_country').val('KZ');
            });
            
            // Отладка: выводим значения полей при отправке формы
            $('form.checkout').on('submit', function() {
                console.log('Отправляем форму...');
                console.log('Имя:', $('#billing_first_name').val());
                console.log('Фамилия:', $('#billing_last_name').val());
                console.log('Телефон:', $('#billing_phone').val());
                console.log('Email:', $('#billing_email').val());
                console.log('Адрес:', $('#billing_address_1').val());
                console.log('WhatsApp:', $('#billing_whatsapp').val());
                console.log('Страна:', $('#billing_country').val());
            });
        });
        </script>
        <?php
    }
}

// 9. Ограничиваем страны только Казахстаном
add_filter('woocommerce_countries_allowed_countries', 'restrict_countries');
add_filter('woocommerce_countries_shipping_countries', 'restrict_countries');
function restrict_countries($countries) {
    return array('KZ' => 'Казахстан');
}

// 10. Устанавливаем страну по умолчанию
add_filter('default_checkout_billing_country', function() { return 'KZ'; });
add_filter('default_checkout_shipping_country', function() { return 'KZ'; });

// 11. Отладочная функция (ВРЕМЕННО - удалите после решения проблемы)
add_action('woocommerce_checkout_process', 'debug_checkout_data', 5);
function debug_checkout_data() {
    error_log('=== DEBUG CHECKOUT DATA ===');
    error_log('POST данные: ' . print_r($_POST, true));
    error_log('Имя: ' . (isset($_POST['billing_first_name']) ? $_POST['billing_first_name'] : 'НЕТ'));
    error_log('Фамилия: ' . (isset($_POST['billing_last_name']) ? $_POST['billing_last_name'] : 'НЕТ'));
    error_log('Телефон: ' . (isset($_POST['billing_phone']) ? $_POST['billing_phone'] : 'НЕТ'));
    error_log('Email: ' . (isset($_POST['billing_email']) ? $_POST['billing_email'] : 'НЕТ'));
    error_log('Адрес: ' . (isset($_POST['billing_address_1']) ? $_POST['billing_address_1'] : 'НЕТ'));
    error_log('=== END DEBUG ===');
}