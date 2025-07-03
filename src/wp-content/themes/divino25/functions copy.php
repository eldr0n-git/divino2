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

// Enqueues editor-style.css in the editors.
if ( ! function_exists( 'divino_editor_style' ) ) :
	/**
	 * Enqueues editor-style.css in the editors.
	 *
	 * @since divino 1.0
	 *
	 * @return void
	 */
	function divino_editor_style() {
		add_editor_style( get_parent_theme_file_uri( 'assets/css/editor-style.css' ) );
	}
endif;
add_action( 'after_setup_theme', 'divino_editor_style' );

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

// Registers custom block styles.
if ( ! function_exists( 'divino_block_styles' ) ) :
	/**
	 * Registers custom block styles.
	 *
	 * @since divino 1.0
	 *
	 * @return void
	 */
	function divino_block_styles() {
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'divino' ),
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;
add_action( 'init', 'divino_block_styles' );

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

add_action('wp_loaded', function () {
    $taxonomies = get_taxonomies([], 'names');
    //error_log('Зарегистрированные таксономии: ' . print_r($taxonomies, true));
});

//FIX WISHLIST PLUGIN DEBUG MESSAGES
// Подавить уведомление о раннем вызове textdomain
add_action('init', function() {
    remove_action('_load_textdomain_just_in_time', '_load_textdomain_just_in_time');
}, 1);

// Или более точечно для конкретного плагина
add_filter('doing_it_wrong_trigger_error', function($trigger, $function_name) {
    if ($function_name === '_load_textdomain_just_in_time' &&
        strpos(debug_backtrace()[2]['file'] ?? '', 'ti-woocommerce-wishlist') !== false) {
        return false;
    }
    return $trigger;
}, 10, 2);




function divino_login_styles() {
    // Подключаем CSS-файл
    wp_enqueue_style( 'divino-login', get_stylesheet_directory_uri() . '/assets/css/divino-login.css' );
}
add_action( 'login_enqueue_scripts', 'divino_login_styles' );


// FONTS
// Enqueue the Onest font from Google Fonts
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

// Remove the prefix from WooCommerce archive titles
function remove_custom_taxonomy_archive_title_prefix_general( $title ) {
    // Check if we are on an archive page for your 'product_kind' custom taxonomy
    if ( is_tax( 'product_kind' ) ) {
        // Get the title of the current term (e.g., "Белое вино")
        $title = single_term_title( '', false );
    }
    return $title;
}

// Удаляем префикс из заголовков H1 архивов таксономий
add_filter( 'get_the_archive_title', 'remove_custom_taxonomy_archive_title_prefix_general' );



// Регистрируем query var для product_kind
add_filter('query_vars', function($vars) {
    $vars[] = 'product_kind';
    return $vars;
});

// Модифицируем основной запрос для обработки product_kind
add_action('pre_get_posts', function($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (isset($_GET['product_kind'])) {
            $query->set('post_type', 'product');
            $query->set('product_kind', sanitize_text_field($_GET['product_kind']));
            
            // Если product_kind - это кастомная таксономия
            if (taxonomy_exists('product_kind')) {
                $query->set('tax_query', array(
                    array(
                        'taxonomy' => 'product_kind',
                        'field'    => 'slug',
                        'terms'    => sanitize_text_field($_GET['product_kind'])
                    )
                ));
            }
            // Если это мета-поле
            else {
                $query->set('meta_query', array(
                    array(
                        'key'     => 'product_kind',
                        'value'   => sanitize_text_field($_GET['product_kind']),
                        'compare' => '='
                    )
                ));
            }
        }
    }
});

// Принудительно используем шаблон архива товаров
add_filter('template_include', function($template) {
    if (isset($_GET['product_kind']) && !is_admin()) {
        // Сначала проверяем в теме
        $wc_template = locate_template('woocommerce/archive-product.php');
        if ($wc_template) {
            return $wc_template;
        }
        
        // Затем стандартный шаблон архива товаров
        $archive_template = locate_template('archive-product.php');
        if ($archive_template) {
            return $archive_template;
        }
        
        // Если ничего не найдено, используем шаблон WooCommerce
        return WC()->plugin_path() . '/templates/archive-product.php';
    }
    return $template;
});