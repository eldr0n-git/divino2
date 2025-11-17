<?php
/**
 * Plugin Name: Divino Food Pairings
 * Description: Плагин для управления едой, сочетаниями и привязкой к товарам WooCommerce
 * Version: 1.0.0
 * Author: eldr0n
 * Text Domain: wc-food-pairings
 */

// Предотвращаем прямой доступ
if (!defined('ABSPATH')) {
    exit;
}

// Основной класс плагина
class WC_Food_Pairings {

    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('admin_menu', array($this, 'add_admin_menus'));
        add_action('add_meta_boxes', array($this, 'add_product_metabox'));
        add_action('add_meta_boxes_food', array($this, 'add_food_slug_metabox'));
        add_action('save_post_food', array($this, 'save_food_slug_meta'));
        add_action('save_post', array($this, 'save_product_meta'));
        add_action('wp_ajax_search_food', array($this, 'ajax_search_food'));
        add_action('wp_ajax_search_grape_varieties', array($this, 'ajax_search_grape_varieties'));
        add_action('wp_ajax_get_popular_food', array($this, 'ajax_get_popular_food'));
        add_action('wp_ajax_save_pairing', array($this, 'ajax_save_pairing'));
        add_action('wp_ajax_delete_pairing', array($this, 'ajax_delete_pairing'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_shortcode('divino_food_pairings', array($this, 'food_pairings_shortcode'));

        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }

    public function init() {
        $this->register_post_types();
        $this->register_taxonomies();
    }

    public function activate() {
        $this->init();
        flush_rewrite_rules();
        $this->create_tables();
    }

    public function deactivate() {
        flush_rewrite_rules();
    }

    private function create_tables() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'food_pairings';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            grape_varieties text NOT NULL,
            food_items text NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function register_post_types() {
        // Регистрируем тип записи "Еда"
        register_post_type('food', array(
            'labels' => array(
                'name' => 'Еда',
                'singular_name' => 'Блюдо',
                'add_new' => 'Добавить блюдо',
                'add_new_item' => 'Добавить новое блюдо',
                'edit_item' => 'Редактировать блюдо',
                'new_item' => 'Новое блюдо',
                'view_item' => 'Просмотреть блюдо',
                'search_items' => 'Поиск блюд',
                'not_found' => 'Блюда не найдены',
                'not_found_in_trash' => 'В корзине блюд не найдено'
            ),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'supports' => array('title', 'editor', 'thumbnail', 'revisions', 'slug'),
            'has_archive' => true,
            'rewrite' => array('slug' => 'food')
        ));
    }

    public function register_taxonomies() {
        // Регистрируем таксономию для еды
        register_taxonomy('food_category', 'food', array(
            'labels' => array(
                'name' => 'Категории еды',
                'singular_name' => 'Категория еды',
                'add_new_item' => 'Добавить новую категорию',
                'edit_item' => 'Редактировать категорию',
                'new_item' => 'Новая категория',
                'view_item' => 'Просмотреть категорию',
                'search_items' => 'Поиск категорий',
                'not_found' => 'Категории не найдены'
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'food-category')
        ));
    }

    public function add_admin_menus() {
        // Главное меню
        add_menu_page(
            'Управление едой и сочетаниями',
            'Еда и Сочетания',
            'manage_options',
            'food-management',
            array($this, 'food_management_page'),
            'dashicons-carrot',
            30
        );

        // Подменю для еды
        add_submenu_page(
            'food-management',
            'Еда',
            'Еда',
            'manage_options',
            'edit.php?post_type=food'
        );

        // Подменю для сочетаний
        add_submenu_page(
            'food-management',
            'Сочетания',
            'Сочетания',
            'manage_options',
            'food-pairings',
            array($this, 'pairings_page')
        );
    }

    public function food_management_page() {
        ?>
        <div class="wrap">
            <h1>Управление едой и сочетаниями</h1>
            <div class="card">
                <h2>Добро пожаловать!</h2>
                <p>Используйте это меню для управления блюдами и их сочетаниями с сортами винограда.</p>
                <ul>
                    <li><a href="<?php echo admin_url('edit.php?post_type=food'); ?>">Управление блюдами</a></li>
                    <li><a href="<?php echo admin_url('admin.php?page=food-pairings'); ?>">Управление сочетаниями</a></li>
                </ul>
            </div>
        </div>
        <?php
    }

    public function pairings_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'food_pairings';

        // Получаем все сочетания
        $pairings = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
        ?>
        <div class="wrap">
            <h1>Сочетания</h1>

            <button type="button" class="button button-primary" id="create-pairing-btn">Создать сочетание</button>

            <!-- Форма создания сочетания -->
            <div id="pairing-form" style="display: none; margin-top: 20px;">
                <div class="card">
                    <h2>Создать новое сочетание</h2>
                    <form id="new-pairing-form">
                        <table class="form-table">
                            <tr>
                                <th><label for="grape-varieties">Сорта винограда</label></th>
                                <td style="position: relative">
                                    <input type="text" id="grape-varieties" class="regular-text" placeholder="Начните вводить сорт винограда...">
                                    <div id="selected-grapes"></div>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="food-items">Блюда</label></th>
                                <td style="position: relative">
                                    <input type="text" id="food-items" class="regular-text" placeholder="Начните вводить название блюда...">
                                    <div id="selected-food"></div>
                                    <div id="popular-food">
                                        <h4>Популярные блюда:</h4>
                                        <div id="popular-food-list"></div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <p class="submit">
                            <input type="submit" class="button button-primary" value="Сохранить сочетание">
                            <button type="button" class="button" id="cancel-pairing">Отмена</button>
                        </p>
                    </form>
                </div>
            </div>

            <!-- Список существующих сочетаний -->
            <div id="pairings-list">
                <h2>Существующие сочетания</h2>
                <?php if (empty($pairings)): ?>
                    <p>Сочетания пока не созданы.</p>
                <?php else: ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Сорта винограда</th>
                                <th>Блюда</th>
                                <th>Дата создания</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pairings as $pairing): ?>
                                <tr>
                                    <td><?php echo $pairing->id; ?></td>
                                    <td><?php echo esc_html($pairing->grape_varieties); ?></td>
                                    <td><?php /*echo esc_html($pairing->food_items); */
                                        $ids = array_filter(array_map('trim', explode(',', $pairing->food_items)));
                                        $names = array_map(function($id){ return get_the_title($id); }, $ids);
                                        echo esc_html(implode(', ', $names));
                                        ?>
                                    </td>
                                    <td><?php echo $pairing->created_at; ?></td>
                                    <td>
                                        <button class="button button-small delete-pairing" data-id="<?php echo $pairing->id; ?>">Удалить</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <style>
        .selected-item {
            display: inline-block;
            background: #f1f1f1;
            padding: 5px 10px;
            margin: 2px;
            border-radius: 3px;
            position: relative;
        }
        .selected-item .remove {
            margin-left: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        #popular-food-list .popular-item {
            display: inline-block;
            background: #e7f3ff;
            padding: 3px 8px;
            margin: 2px;
            border-radius: 3px;
            cursor: pointer;
            border: 1px solid #b3d9ff;
        }
        #popular-food-list .popular-item:hover {
            background: #cce7ff;
        }
        .autocomplete-suggestions {
            border: 1px solid #ccc;
            background: white;
            max-height: 200px;
            overflow-y: auto;
            position: absolute;
            z-index: 1000;
            width: 100%;
        }
        .autocomplete-suggestion {
            padding: 10px;
            cursor: pointer;
        }
        .autocomplete-suggestion:hover {
            background: #f1f1f1;
        }
        </style>
        <?php
    }

    public function add_product_metabox() {
        add_meta_box(
            'product-food-pairings',
            'Сочетания с едой',
            array($this, 'product_metabox_callback'),
            'product',
            'normal',
            'default'
        );
    }

    public function product_metabox_callback($post) {
        wp_nonce_field('save_product_food_meta', 'product_food_meta_nonce');

        // Get grape varieties to find suggested food pairings
        $grape_varieties = get_post_meta($post->ID, '_grape_varieties', true);
        $suggested_food_ids = $this->get_food_pairings_by_grape_varieties($grape_varieties);

        // Get food items that are already saved for the product
        $saved_food_ids = get_post_meta($post->ID, '_product_food_items', true);
        if (!is_array($saved_food_ids)) {
            $saved_food_ids = array();
        }

        // Combine suggested food with saved food, ensuring no duplicates
        $all_food_ids = array_unique(array_merge($suggested_food_ids, $saved_food_ids));
        $all_food_ids = array_map('intval', $all_food_ids);

        ?>
        <div id="product-food-meta">
            <p>
                <label for="product-food-search">Добавить блюда:</label>
                <input type="text" id="product-food-search" class="regular-text" placeholder="Начните вводить название блюда...">
            </p>

            <div id="product-selected-food">
                <h4>Выбранные блюда:</h4>
                <div id="selected-food-items">
                    <?php foreach ($all_food_ids as $food_id): ?>
                        <?php
                        $food_id = (int) $food_id;
                        if ($food_id > 0) :
                            $food_title = get_the_title($food_id);
                            if ($food_title): ?>
                                <span class="selected-item" data-id="<?php echo $food_id; ?>">
                                    <?php echo esc_html($food_title); ?>
                                    <span class="remove">×</span>
                                </span>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="product-popular-food">
                <h4>Популярные блюда:</h4>
                <div id="popular-food-items"></div>
            </div>

            <input type="hidden" id="product-food-items-hidden" name="product_food_items" value="<?php echo esc_attr(implode(',', $all_food_ids)); ?>">
        </div>
        <?php
    }

    public function save_product_meta($post_id) {
        if (!isset($_POST['product_food_meta_nonce']) || !wp_verify_nonce($_POST['product_food_meta_nonce'], 'save_product_food_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['product_food_items'])) {
            $food_items = sanitize_text_field($_POST['product_food_items']);
            $food_array = array_filter(explode(',', $food_items));
            update_post_meta($post_id, '_product_food_items', $food_array);
        }
    }

    public function ajax_search_food() {
        $search = sanitize_text_field($_GET['search']);

        $posts = get_posts(array(
            'post_type' => 'food',
            'posts_per_page' => 10,
            's' => $search,
            'post_status' => 'publish'
        ));

        $results = array();
        foreach ($posts as $post) {
            $results[] = array(
                'id' => $post->ID,
                'title' => $post->post_title
            );
        }

        wp_send_json($results);
    }

    public function ajax_search_grape_varieties() {
        $search = sanitize_text_field($_GET['search']);

        $posts = get_posts(array(
            'post_type' => 'grape_variety',
            'posts_per_page' => 10,
            's' => $search,
            'post_status' => 'publish'
        ));

        $results = array();
        foreach ($posts as $post) {
            $results[] = array(
                'id' => $post->post_title,
                'title' => $post->post_title
            );
        }

        wp_send_json($results);
    }

    public function ajax_get_popular_food() {
        // Получаем популярные блюда (можно расширить логику)
        $posts = get_posts(array(
            'post_type' => 'food',
            'posts_per_page' => 10,
            'meta_key' => '_popular_food',
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
            'post_status' => 'publish'
        ));

        // Если нет популярных, берем последние
        if (empty($posts)) {
            $posts = get_posts(array(
                'post_type' => 'food',
                'posts_per_page' => 10,
                'post_status' => 'publish'
            ));
        }

        $results = array();
        foreach ($posts as $post) {
            $results[] = array(
                'id' => $post->ID,
                'title' => $post->post_title
            );
        }

        wp_send_json($results);
    }

    public function ajax_save_pairing() {
        global $wpdb;

        $grape_varieties = isset($_POST['grape_varieties']) ? sanitize_text_field($_POST['grape_varieties']) : '';
        $food_ids = isset($_POST['food_ids']) ? array_map('intval', explode(',', $_POST['food_ids'])) : [];

        if (empty($grape_varieties) || empty($food_ids)) {
            wp_send_json_error('Missing data.');
        }

        $table_name = $wpdb->prefix . 'food_pairings';

        $result = $wpdb->insert(
            $table_name,
            array(
                'grape_varieties' => $grape_varieties,
                'food_items' => implode(',', $food_ids)
            )
        );

        if ($result !== false) {
            wp_send_json_success('Сочетание сохранено');
        } else {
            wp_send_json_error('Ошибка сохранения');
        }
    }

    public function ajax_delete_pairing() {
        global $wpdb;

        $pairing_id = intval($_POST['pairing_id']);
        $table_name = $wpdb->prefix . 'food_pairings';

        $result = $wpdb->delete($table_name, array('id' => $pairing_id));

        if ($result !== false) {
            wp_send_json_success('Сочетание удалено');
        } else {
            wp_send_json_error('Ошибка удаления');
        }
    }

    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, 'food-pairings') !== false || $hook === 'post.php' || $hook === 'post-new.php') {
            wp_enqueue_script('jquery');
            wp_enqueue_script('wc-food-pairings-admin', plugin_dir_url(__FILE__) . 'admin.js', array('jquery'), '1.0.0', true);
            wp_localize_script('wc-food-pairings-admin', 'wcFoodPairings', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('wc_food_pairings_nonce')
            ));
        }
    }

    public function get_food_pairings_by_grape_varieties($grape_varieties) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'food_pairings';
        $food_ids = [];

        if (empty($grape_varieties) || !is_array($grape_varieties)) {
            return $food_ids;
        }

        $grape_names = array_map(function($variety) {
            return $variety['name'];
        }, $grape_varieties);

        foreach ($grape_names as $grape_name) {
            $grape_name_like = '%' . $wpdb->esc_like($grape_name) . '%';
            $results = $wpdb->get_results($wpdb->prepare(
                "SELECT food_items FROM $table_name WHERE grape_varieties LIKE %s",
                $grape_name_like
            ));

            if (!empty($results)) {
                foreach ($results as $result) {
                    $ids = explode(',', $result->food_items);
                    $food_ids = array_merge($food_ids, $ids);
                }
            }
        }

        return array_unique(array_map('intval', $food_ids));
    }

    public function food_pairings_shortcode($atts) {
        $atts = shortcode_atts(array(
            'product_id' => get_the_ID(),
        ), $atts, 'divino_food_pairings');

        $product_id = $atts['product_id'];

        if (!$product_id) {
            return '';
        }

        $food_items = get_post_meta($product_id, '_product_food_items', true);

        if (empty($food_items) || !is_array($food_items)) {
            return '';
        }

        $output = '<div class="food-pairings">';
        $output .= '<h3>Сочетания с едой:</h3>';
        $output .= '<ul class="foodList">';

        foreach ($food_items as $food_id) {
            $food_post = get_post($food_id);
            if ($food_post) {
                $food_title = $food_post->post_title;
                $food_slug = $food_post->post_name;
                $output .= '<li class="foodItem">'
                                . '<div class="foodItem__icon foodItem__icon--' . esc_attr($food_slug) . '"></div>'
                                . '<span class="foodItem__title">' . esc_html($food_title) . '</span>'
                            . '</li>';
            }
        }

        $output .= '</ul>';
        $output .= '</div>';

        return $output;
    }

    public function add_food_slug_metabox() {
        add_meta_box(
            'food_slug_metabox',
            'Слаг (URL)',
            array($this, 'food_slug_metabox_callback'),
            'food',
            'side',
            'default'
        );
    }

    public function food_slug_metabox_callback($post) {
        wp_nonce_field('save_food_slug', 'food_slug_nonce');
        $slug = $post->post_name;
        echo '<label for="food_slug">Слаг:</label>';
        echo '<input type="text" id="food_slug" name="food_slug" value="' . esc_attr($slug) . '" style="width: 100%;" />';
    }

    public function save_food_slug_meta($post_id) {
        if (!isset($_POST['food_slug_nonce']) || !wp_verify_nonce($_POST['food_slug_nonce'], 'save_food_slug')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['food_slug'])) {
            $new_slug = sanitize_title($_POST['food_slug']);
            if ($new_slug !== get_post_field('post_name', $post_id)) {
                // Unhook this function to prevent infinite loop
                remove_action('save_post_food', array($this, 'save_food_slug_meta'));

                // Update the post slug
                wp_update_post(array(
                    'ID' => $post_id,
                    'post_name' => $new_slug
                ));

                // Re-hook this function
                add_action('save_post_food', array($this, 'save_food_slug_meta'));
            }
        }
    }
}

// Инициализация плагина
new WC_Food_Pairings();
