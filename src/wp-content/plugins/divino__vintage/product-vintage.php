<?php
/**
 * Plugin Name: Divino Vintage
 * Description: Плагин для группировки товаров по винтажу/году выпуска
 * Version: 1.0.29
 * Author: eldr0n
 */

// Предотвращаем прямой доступ
if (!defined('ABSPATH')) {
    exit;
}

class WC_Vintage_Products {

    public function __construct() {
        // Проверяем WooCommerce сразу при создании объекта
        add_action('plugins_loaded', array($this, 'init'));
    }

    public function init() {
        // Проверяем, активен ли WooCommerce
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
            return;
        }

        // Хуки для админки
        add_action('add_meta_boxes', array($this, 'add_vintage_meta_boxes'));
        add_action('save_post', array($this, 'save_vintage_meta'));

        // AJAX для автозаполнения
        add_action('wp_ajax_search_products_for_vintage', array($this, 'ajax_search_products'));
        add_action('wp_ajax_remove_product_from_vintage_group', array($this, 'ajax_remove_product_from_group'));

        // Добавляем отладочную информацию
        add_action('wp_ajax_test_vintage_ajax', array($this, 'test_ajax'));

        // Хуки для фронтенда
        add_action('woocommerce_single_product_summary', array($this, 'display_vintage_products'), 25);

        // Подключаем стили и скрипты
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));

        // Создаем таблицу если её нет
        $this->create_vintage_groups_table();
    }

    /**
     * Уведомление об отсутствии WooCommerce
     */
    public function woocommerce_missing_notice() {
        echo '<div class="notice notice-error"><p>WooCommerce Vintage Products требует активации WooCommerce!</p></div>';
    }

    /**
     * Создание таблицы для групп винтажных товаров
     */
    public function create_vintage_groups_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'vintage_product_groups';

        // Проверяем, существует ли таблица
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;

        if (!$table_exists) {
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                group_name varchar(255) NOT NULL,
                product_ids text NOT NULL,
                created_at datetime DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            error_log('Vintage Products: Table created: ' . $table_name);
        } else {
            error_log('Vintage Products: Table already exists: ' . $table_name);
        }
    }

    /**
     * Добавляем метабоксы в панель редактирования товара
     */
    public function add_vintage_meta_boxes() {
        add_meta_box(
            'vintage_year_meta_box',
            'Год выпуска/Винтаж',
            array($this, 'vintage_year_meta_box_callback'),
            'product',
            'normal',
            'high'
        );

        add_meta_box(
            'vintage_groups_meta_box',
            'Группы винтажных товаров',
            array($this, 'vintage_groups_meta_box_callback'),
            'product',
            'normal',
            'high'
        );
    }

    /**
     * Метабокс для года выпуска
     */
    public function vintage_year_meta_box_callback($post) {
        wp_nonce_field('vintage_meta_box', 'vintage_meta_box_nonce');

        $vintage_year = get_post_meta($post->ID, '_vintage_year', true);

        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th><label for="vintage_year">Год выпуска:</label></th>';
        echo '<td><input type="number" id="vintage_year" name="vintage_year" value="' . esc_attr($vintage_year) . '" min="1800" max="' . date('Y') . '" style="width: 100px;" /></td>';
        echo '</tr>';
        echo '</table>';
    }

    /**
     * Метабокс для групп винтажных товаров
     */
    public function vintage_groups_meta_box_callback($post) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'vintage_product_groups';
        $current_groups = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name WHERE FIND_IN_SET(%d, product_ids) > 0",
            $post->ID
        ));

        echo '<div id="vintage-groups-container">';
        echo '<h4>Текущие группы:</h4>';

        if ($current_groups) {
            foreach ($current_groups as $group) {
                echo '<div class="vintage-group-item" data-group-id="' . $group->id . '">';
                echo '<strong>' . esc_html($group->group_name) . '</strong> ';
                echo '<button type="button" class="button remove-from-group" data-group-id="' . $group->id . '">Удалить из группы</button>';
                echo '</div>';
            }
        } else {
            echo '<p>Товар не входит ни в одну группу.</p>';
        }

        echo '<hr>';
        echo '<h4>Создать новую группу или добавить в существующую:</h4>';
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th><label for="group_name">Название группы:</label></th>';
        echo '<td><input type="text" id="group_name" name="group_name" style="width: 300px;" placeholder="Например: Вино Шато Марго" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="related_products">Связанные товары:</label></th>';
        echo '<td>';
        echo '<input type="text" id="product_search" placeholder="Начните вводить название товара..." style="width: 400px;" />';
        echo '<button type="button" id="test_ajax" class="button" style="margin-left: 10px;">Тест AJAX</button>';
        echo '<div id="search_results"></div>';
        echo '<div id="selected_products"></div>';
        echo '<input type="hidden" id="selected_product_ids" name="selected_product_ids" />';
        echo '</td>';
        echo '</tr>';
        echo '</table>';
        echo '<p class="submit">';
        echo '<button type="button" id="create_vintage_group" class="button button-primary">Создать/Обновить группу</button>';
        echo '</p>';
        echo '</div>';

        // Добавляем скрипт прямо здесь
        ?>
        <script type="text/javascript">
        console.log('Vintage Products: Script loaded directly in metabox');

        jQuery(document).ready(function($) {
            console.log('Vintage Products: jQuery ready, version:', $.fn.jquery);

            let selectedProducts = [];

            // Тест AJAX - сразу привязываем к существующей кнопке
            $('#test_ajax').on('click', function(e) {
                e.preventDefault();
                console.log('Test AJAX button clicked');

                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'test_vintage_ajax'
                    },
                    success: function(response) {
                        console.log('Test AJAX success:', response);
                        alert('AJAX работает: ' + JSON.stringify(response));
                    },
                    error: function(xhr, status, error) {
                        console.log('Test AJAX error:', error);
                        console.log('Status:', status);
                        console.log('XHR responseText:', xhr.responseText);
                        alert('AJAX ошибка: ' + error + ' Status: ' + status);
                    }
                });
            });

            // Проверяем наличие jQuery UI
            if (typeof $.ui === 'undefined' || typeof $.ui.autocomplete === 'undefined') {
                console.warn('jQuery UI Autocomplete not loaded, loading from CDN...');
                $.getScript('https://code.jquery.com/ui/1.13.2/jquery-ui.min.js', function() {
                    initAutocomplete();
                });
            } else {
                initAutocomplete();
            }

            function initAutocomplete() {
                console.log('Initializing autocomplete...');

                $('#product_search').autocomplete({
                    source: function(request, response) {
                        console.log('Searching for:', request.term);
                        $.ajax({
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            type: 'GET',
                            data: {
                                action: 'search_products_for_vintage',
                                term: request.term
                            },
                            success: function(data) {
                                console.log('Search AJAX response:', data);
                                response(data);
                            },
                            error: function(xhr, status, error) {
                                console.log('Search AJAX error:', error);
                                console.log('XHR responseText:', xhr.responseText);
                                response([]);
                            }
                        });
                    },
                    minLength: 2,
                    select: function(event, ui) {
                        console.log('Selected product:', ui.item);
                        addProductToSelection(ui.item.id, ui.item.label);
                        $(this).val('');
                        return false;
                    }
                });
            }

            function addProductToSelection(productId, productName) {
                if (selectedProducts.find(p => p.id == productId)) {
                    alert('Этот товар уже добавлен');
                    return;
                }

                selectedProducts.push({
                    id: productId,
                    name: productName
                });

                updateSelectedProductsDisplay();
                updateHiddenField();
            }

            function updateSelectedProductsDisplay() {
                let html = '<div class="selected-products-container">';
                html += '<h4>Выбранные товары:</h4>';

                selectedProducts.forEach(function(product, index) {
                    html += '<div class="selected-product-item" data-product-id="' + product.id + '">';
                    html += '<span>' + product.name + '</span>';
                    html += '<button type="button" class="button remove-product" data-index="' + index + '">×</button>';
                    html += '</div>';
                });

                html += '</div>';
                $('#selected_products').html(html);
            }

            function updateHiddenField() {
                const ids = selectedProducts.map(p => p.id).join(',');
                $('#selected_product_ids').val(ids);
                console.log('Updated selected IDs:', ids);
            }

            $(document).on('click', '.remove-product', function() {
                const index = $(this).data('index');
                selectedProducts.splice(index, 1);
                updateSelectedProductsDisplay();
                updateHiddenField();
            });

            $('#create_vintage_group').on('click', function(e) {
                e.preventDefault();

                const groupName = $('#group_name').val().trim();
                const productIds = $('#selected_product_ids').val();

                console.log('Creating group:', groupName, 'with products:', productIds);

                if (!groupName) {
                    alert('Введите название группы');
                    return;
                }

                if (!productIds) {
                    alert('Выберите хотя бы один товар для группы');
                    return;
                }

                $('<input>').attr({
                    type: 'hidden',
                    name: 'group_name_hidden',
                    value: groupName
                }).appendTo('#post');

                $('<input>').attr({
                    type: 'hidden',
                    name: 'selected_product_ids_hidden',
                    value: productIds
                }).appendTo('#post');

                $('#publish').click();
            });

            $('.remove-from-group').on('click', function() {
                const groupId = $(this).data('group-id');
                const productId = <?php echo $post->ID; ?>;

                if (confirm('Вы уверены, что хотите удалить товар из этой группы?')) {
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: {
                            action: 'remove_product_from_vintage_group',
                            group_id: groupId,
                            product_id: productId
                        },
                        success: function(response) {
                            console.log('Remove from group response:', response);
                            if (response.success) {
                                location.reload();
                            } else {
                                alert('Ошибка при удалении из группы: ' + (response.data || 'Unknown error'));
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('Remove from group error:', error);
                            alert('AJAX ошибка при удалении из группы');
                        }
                    });
                }
            });
        });
        </script>
        <?php
    }

    /**
     * Сохранение метаданных
     */
    public function save_vintage_meta($post_id) {
        // Логируем для отладки
        error_log('Vintage Products: save_vintage_meta called for post ' . $post_id);
        error_log('Vintage Products: POST data: ' . print_r($_POST, true));

        // Проверки безопасности
        if (!isset($_POST['vintage_meta_box_nonce'])) {
            error_log('Vintage Products: No nonce found');
            return;
        }

        if (!wp_verify_nonce($_POST['vintage_meta_box_nonce'], 'vintage_meta_box')) {
            error_log('Vintage Products: Nonce verification failed');
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            error_log('Vintage Products: Autosave, skipping');
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            error_log('Vintage Products: Permission denied');
            return;
        }

        // Сохраняем год выпуска
        if (isset($_POST['vintage_year'])) {
            update_post_meta($post_id, '_vintage_year', sanitize_text_field($_POST['vintage_year']));
            error_log('Vintage Products: Saved vintage year: ' . $_POST['vintage_year']);
        }

        // Обработка групп винтажных товаров
        if (isset($_POST['group_name_hidden']) && isset($_POST['selected_product_ids_hidden'])) {
            error_log('Vintage Products: Found group data - name: ' . $_POST['group_name_hidden'] . ', products: ' . $_POST['selected_product_ids_hidden']);
            $this->save_vintage_group($_POST['group_name_hidden'], $_POST['selected_product_ids_hidden'], $post_id);
        } else {
            error_log('Vintage Products: No group data found');
        }
    }

    /**
     * Сохранение группы винтажных товаров
     */
    private function save_vintage_group($group_name, $product_ids_string, $current_product_id) {
        global $wpdb;

        error_log('Vintage Products: save_vintage_group called');
        error_log('Vintage Products: Group name: ' . $group_name);
        error_log('Vintage Products: Product IDs string: ' . $product_ids_string);
        error_log('Vintage Products: Current product ID: ' . $current_product_id);

        if (empty($group_name) || empty($product_ids_string)) {
            error_log('Vintage Products: Empty group name or product IDs');
            return;
        }

        $table_name = $wpdb->prefix . 'vintage_product_groups';
        $group_name = sanitize_text_field($group_name);
        $product_ids = array_filter(array_map('intval', explode(',', $product_ids_string)));

        // Добавляем текущий товар в список
        if (!in_array($current_product_id, $product_ids)) {
            $product_ids[] = $current_product_id;
        }

        $product_ids_string = implode(',', $product_ids);
        error_log('Vintage Products: Final product IDs: ' . $product_ids_string);

        // Проверяем, существует ли группа с таким названием
        $existing_group = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE group_name = %s",
            $group_name
        ));

        if ($existing_group) {
            error_log('Vintage Products: Updating existing group');
            // Обновляем существующую группу
            $existing_ids = array_filter(array_map('intval', explode(',', $existing_group->product_ids)));
            $merged_ids = array_unique(array_merge($existing_ids, $product_ids));

            $result = $wpdb->update(
                $table_name,
                array('product_ids' => implode(',', $merged_ids)),
                array('id' => $existing_group->id)
            );
            error_log('Vintage Products: Update result: ' . $result);
        } else {
            error_log('Vintage Products: Creating new group');
            // Создаем новую группу
            $result = $wpdb->insert(
                $table_name,
                array(
                    'group_name' => $group_name,
                    'product_ids' => $product_ids_string
                )
            );
            error_log('Vintage Products: Insert result: ' . $result);
            if ($wpdb->last_error) {
                error_log('Vintage Products: DB error: ' . $wpdb->last_error);
            }
        }
    }

    /**
     * Скрипт для футера админки
     */
    public function admin_footer_script() {
        global $post;

        // Проверяем, что мы на странице редактирования товара
        if (!$post || $post->post_type !== 'product') {
            return;
        }
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            console.log('Vintage Products script loaded');

            let selectedProducts = [];

            // Тест AJAX
            $('#test_ajax').click(function(e) {
                e.preventDefault();
                console.log('Testing AJAX...');

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'test_vintage_ajax'
                    },
                    success: function(response) {
                        console.log('Test AJAX success:', response);
                        alert('AJAX работает: ' + response.data);
                    },
                    error: function(xhr, status, error) {
                        console.log('Test AJAX error:', error);
                        console.log('XHR:', xhr);
                        alert('AJAX ошибка: ' + error);
                    }
                });
            });

            // Автозаполнение для поиска товаров
            $('#product_search').autocomplete({
                source: function(request, response) {
                    console.log('Searching for:', request.term);
                    $.ajax({
                        url: ajaxurl,
                        type: 'GET',
                        data: {
                            action: 'search_products_for_vintage',
                            term: request.term
                        },
                        success: function(data) {
                            console.log('AJAX response:', data);
                            response(data);
                        },
                        error: function(xhr, status, error) {
                            console.log('AJAX error:', error);
                            console.log('XHR:', xhr);
                            response([]);
                        }
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    console.log('Selected product:', ui.item);
                    addProductToSelection(ui.item.id, ui.item.label);
                    $(this).val('');
                    return false;
                }
            });

            // Добавление товара в выбранные
            function addProductToSelection(productId, productName) {
                // Проверяем, не добавлен ли уже товар
                if (selectedProducts.find(p => p.id == productId)) {
                    alert('Этот товар уже добавлен');
                    return;
                }

                selectedProducts.push({
                    id: productId,
                    name: productName
                });

                updateSelectedProductsDisplay();
                updateHiddenField();
            }

            // Обновление отображения выбранных товаров
            function updateSelectedProductsDisplay() {
                let html = '<div class="selected-products-container">';
                html += '<h4>Выбранные товары:</h4>';

                selectedProducts.forEach(function(product, index) {
                    html += '<div class="selected-product-item" data-product-id="' + product.id + '">';
                    html += '<span>' + product.name + '</span>';
                    html += '<button type="button" class="button remove-product" data-index="' + index + '">×</button>';
                    html += '</div>';
                });

                html += '</div>';
                $('#selected_products').html(html);
            }

            // Обновление скрытого поля с ID товаров
            function updateHiddenField() {
                const ids = selectedProducts.map(p => p.id).join(',');
                $('#selected_product_ids').val(ids);
                console.log('Updated selected IDs:', ids);
            }

            // Удаление товара из выбранных
            $(document).on('click', '.remove-product', function() {
                const index = $(this).data('index');
                selectedProducts.splice(index, 1);
                updateSelectedProductsDisplay();
                updateHiddenField();
            });

            // Создание группы
            $('#create_vintage_group').click(function(e) {
                e.preventDefault();

                const groupName = $('#group_name').val().trim();
                const productIds = $('#selected_product_ids').val();

                console.log('Creating group:', groupName, 'with products:', productIds);

                if (!groupName) {
                    alert('Введите название группы');
                    return;
                }

                if (!productIds) {
                    alert('Выберите хотя бы один товар для группы');
                    return;
                }

                // Добавляем скрытые поля к форме для сохранения
                if ($('input[name="group_name_hidden"]').length === 0) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'group_name_hidden',
                        value: groupName
                    }).appendTo('#post');
                } else {
                    $('input[name="group_name_hidden"]').val(groupName);
                }

                if ($('input[name="selected_product_ids_hidden"]').length === 0) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'selected_product_ids_hidden',
                        value: productIds
                    }).appendTo('#post');
                } else {
                    $('input[name="selected_product_ids_hidden"]').val(productIds);
                }

                // Сохраняем пост
                $('#publish').click();
            });

            // Удаление из группы
            $(document).on('click', '.remove-from-group', function() {
                const groupId = $(this).data('group-id');
                const productId = $('#post_ID').val();

                if (confirm('Вы уверены, что хотите удалить товар из этой группы?')) {
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'remove_product_from_vintage_group',
                            group_id: groupId,
                            product_id: productId
                        },
                        success: function(response) {
                            console.log('Remove from group response:', response);
                            if (response.success) {
                                location.reload();
                            } else {
                                alert('Ошибка при удалении из группы: ' + response.data);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('Remove from group error:', error);
                            alert('AJAX ошибка при удалении из группы');
                        }
                    });
                }
            });
        });
        </script>
        <?php
    }
    public function ajax_search_products() {
        if (!current_user_can('edit_posts')) {
            wp_die();
        }

        $search_term = sanitize_text_field($_GET['term']);

        $products = get_posts(array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 10,
            's' => $search_term,
            'meta_query' => array(
                array(
                    'key' => '_stock_status',
                    'value' => 'instock'
                )
            )
        ));

        $results = array();
        foreach ($products as $product) {
            $results[] = array(
                'id' => $product->ID,
                'label' => $product->post_title,
                'value' => $product->post_title
            );
        }

        wp_send_json($results);
    }

    /**
     * Подключение скриптов и стилей в админке
     */
    public function admin_scripts($hook) {
        global $post;

        if ($hook != 'post-new.php' && $hook != 'post.php') {
            return;
        }

        if ($post->post_type != 'product') {
            return;
        }

        wp_enqueue_script('jquery-ui-autocomplete');


        wp_localize_script('vintage-products-admin', 'vintage_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('vintage_ajax_nonce')
        ));

        wp_enqueue_style('vintage-products-admin', plugin_dir_url(__FILE__) . 'admin.css', array(), '1.0.0');
    }

    /**
     * Получить CSS стили для админки
     */
    private function get_admin_css() {
        return '
        .vintage-group-item {
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            margin-bottom: 5px;
            border-radius: 3px;
        }

        .vintage-group-item strong {
            margin-right: 10px;
        }

        .selected-products-container {
            margin-top: 15px;
            padding: 15px;
            background: #f0f0f1;
            border-radius: 3px;
        }

        .selected-product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: white;
            border: 1px solid #c3c4c7;
            margin-bottom: 5px;
            border-radius: 3px;
        }

        .selected-product-item span {
            flex-grow: 1;
        }

        .selected-product-item .remove-product {
            background: #dc3232;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
            line-height: 1;
        }

        .selected-product-item .remove-product:hover {
            background: #a02020;
        }

        .ui-autocomplete {
            max-height: 200px;
            overflow-y: auto;
            z-index: 10000;
        }

        .vintage-products-section {
            margin: 30px 0;
            padding: 20px 0;
            border-top: 1px solid #e0e0e0;
        }

        .vintage-products-section h3 {
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #333;
        }

        .vintage-group {
            margin-bottom: 30px;
        }

        .vintage-group h4 {
            margin-bottom: 15px;
            font-size: 1.2em;
            color: #666;
        }

        .vintage-products-list {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .vintage-product-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background: #fafafa;
            transition: all 0.3s ease;
            min-width: 280px;
        }

        .vintage-product-item:hover {
            border-color: #c0c0c0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .vintage-product-item a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: inherit;
            width: 100%;
        }

        .vintage-product-item img {
            margin-right: 15px;
            border-radius: 4px;
            object-fit: cover;
        }

        .vintage-product-info {
            position: absolute;
            display: none;
        }
        .vintage-product-item > a:hover > .vintage-product-info {
            display: block;
        }
        .vintage-product-info h5 {
            margin: 0 0 5px 0;
            font-size: 1.1em;
            color: #333;
            line-height: 1.3;
        }

        .vintage-product-info .vintage-year {
            display: block;
            font-size: 0.9em;
            color: #888;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .vintage-product-info .price {
            font-weight: bold;
            color: #2c5aa0;
            font-size: 1.1em;
        }
        ';
    }
    public function ajax_remove_product_from_group() {
        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Недостаточно прав');
        }

        if (!wp_verify_nonce($_POST['_ajax_nonce'], 'vintage_ajax_nonce')) {
            wp_send_json_error('Неверный nonce');
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'vintage_product_groups';

        $group_id = intval($_POST['group_id']);
        $product_id = intval($_POST['product_id']);

        // Получаем группу
        $group = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE id = %d",
            $group_id
        ));

        if (!$group) {
            wp_send_json_error('Группа не найдена');
        }

        // Удаляем товар из списка
        $product_ids = array_filter(array_map('intval', explode(',', $group->product_ids)));
        $product_ids = array_diff($product_ids, array($product_id));

        if (empty($product_ids)) {
            // Если в группе не осталось товаров, удаляем группу
            $wpdb->delete($table_name, array('id' => $group_id));
        } else {
            // Обновляем группу
            $wpdb->update(
                $table_name,
                array('product_ids' => implode(',', $product_ids)),
                array('id' => $group_id)
            );
        }

        wp_send_json_success('Товар удален из группы');
    }
    public function display_vintage_products() {
        global $product, $wpdb;

        if (!$product) {
            return;
        }

        $product_id = $product->get_id();
        $table_name = $wpdb->prefix . 'vintage_product_groups';

        // Находим группы, в которые входит текущий товар
        $groups = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name WHERE FIND_IN_SET(%d, product_ids) > 0",
            $product_id
        ));

        if (!$groups) {
            return;
        }

        $current_vintage = get_post_meta($product_id, '_vintage_year', true);

        echo '<div class="vintage-products-section">';
        echo '<h3>Другие винтажи</h3>';

        foreach ($groups as $group) {
            $product_ids = array_filter(array_map('intval', explode(',', $group->product_ids)));
            $related_products = array();

            foreach ($product_ids as $pid) {
                if ($pid != $product_id) {
                    $related_product = wc_get_product($pid);
                    if ($related_product && $related_product->is_in_stock()) {
                        $vintage_year = get_post_meta($pid, '_vintage_year', true);
                        $related_products[] = array(
                            'product' => $related_product,
                            'vintage' => $vintage_year
                        );
                    }
                }
            }

            if (!empty($related_products)) {
                // Сортируем по году
                usort($related_products, function($a, $b) {
                    return (int)$b['vintage'] - (int)$a['vintage'];
                });

                echo '<div class="vintage-group">';
                // echo '<h4>' . esc_html($group->group_name) . '</h4>';
                echo '<div class="vintage-products-list">';

                foreach ($related_products as $item) {
                    $prod = $item['product'];
                    $vintage = $item['vintage'];
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($prod->get_id()), 'thumbnail');
                    $image_url = $image ? $image[0] : wc_placeholder_img_src();

                    echo '<div class="vintage-product-item">';
                    echo '<a href="' . get_permalink($prod->get_id()) . '">';

                    if ($vintage) {
                        echo '<span class="vintage-year">' . esc_html($vintage) . ' г.</span>';
                    }
                    echo '<div class="vintage-product-info">';
                    echo '<h5>' . esc_html($prod->get_name()) . '</h5>';
                    echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($prod->get_name()) . '" width="80" height="80" />';
                    echo '<span class="price">' . $prod->get_price_html() . '</span>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }

                echo '</div>';
                echo '</div>';
            }
        }

        echo '</div>';
    }
}

// Инициализация плагина
new WC_Vintage_Products();
