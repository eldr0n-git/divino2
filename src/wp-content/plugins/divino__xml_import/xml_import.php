<?php
/**
 * Plugin Name: DIVINO WooCommerce XML Import
 * Description: Импорт товаров из XML файла в WooCommerce
 * Version: 1.01
 * Author: eldr0n
 */

// Предотвращаем прямой доступ
if (!defined('ABSPATH')) {
    exit;
}

// Проверяем активность WooCommerce
register_activation_hook(__FILE__, 'wc_xml_import_check_woocommerce');
function wc_xml_import_check_woocommerce() {
    if (!class_exists('WooCommerce')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die('Этот плагин требует активации WooCommerce.');
    }
}

// Инициализация плагина
add_action('plugins_loaded', 'wc_xml_import_init');
function wc_xml_import_init() {
    if (!class_exists('WooCommerce')) {
        return;
    }

    new WC_XML_Import_Plugin();
}

class WC_XML_Import_Plugin {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'handle_form_submission'));
    }

    /**
     * Добавляем меню в админ панель
     */
    public function add_admin_menu() {
        add_submenu_page(
            'woocommerce',
            'XML Импорт',
            'XML Импорт',
            'manage_woocommerce',
            'wc-xml-import',
            array($this, 'admin_page')
        );
    }

    /**
     * Обработка отправки формы
     */
    public function handle_form_submission() {
        if (!isset($_POST['wc_xml_import_submit']) || !wp_verify_nonce($_POST['wc_xml_import_nonce'], 'wc_xml_import_action')) {
            return;
        }

        if (!current_user_can('manage_woocommerce')) {
            wp_die('Недостаточно прав для выполнения этого действия.');
        }

        if (!isset($_FILES['xml_file']) || $_FILES['xml_file']['error'] !== UPLOAD_ERR_OK) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p>Ошибка загрузки файла. Убедитесь, что файл выбран и размер не превышает допустимый.</p></div>';
            });
            return;
        }

        $file_path = $_FILES['xml_file']['tmp_name'];
        $file_name = $_FILES['xml_file']['name'];

        // Проверяем расширение файла
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if ($file_extension !== 'xml') {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p>Неверный формат файла. Загрузите XML файл.</p></div>';
            });
            return;
        }

        try {
            $result = $this->process_xml_import($file_path);

            add_action('admin_notices', function() use ($result) {
                echo '<div class="notice notice-success"><p>' . $result['message'] . '</p></div>';
                echo '<div class="notice notice-info">';
                echo '<p><strong>Результаты импорта:</strong></p>';
                echo '<p>Создано товаров: ' . $result['created'] . '</p>';
                echo '<p>Обновлено товаров: ' . $result['updated'] . '</p>';
                echo '<p>Ошибок: ' . $result['errors'] . '</p>';
                echo '</div>';
            });

        } catch (Exception $e) {
            add_action('admin_notices', function() use ($e) {
                echo '<div class="notice notice-error"><p>Ошибка обработки файла: ' . esc_html($e->getMessage()) . '</p></div>';
            });
        }
    }

    /**
     * Страница администратора
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1>XML Импорт товаров</h1>

            <div class="card">
                <h2>Инструкция</h2>
                <p>Этот инструмент позволяет импортировать товары из XML файла в каталог WooCommerce.</p>
                <p><strong>Логика импорта:</strong></p>
                <ul>
                    <li>Для товаров с одинаковым артикулом в рамках одного склада используется запись с самой поздней датой (PricePeriod)</li>
                    <li>Для товаров с одинаковым артикулом из разных складов суммируется количество</li>
                    <li>Если товар с таким артикулом уже существует - обновляется цена и количество</li>
                    <li>Если товара нет в каталоге - создается новый товар</li>
                </ul>
            </div>

            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('wc_xml_import_action', 'wc_xml_import_nonce'); ?>

                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="xml_file">Выберите XML файл</label></th>
                        <td>
                            <input type="file" id="xml_file" name="xml_file" accept=".xml" required>
                            <p class="description">Выберите XML файл с товарами для импорта. Максимальный размер файла: <?php echo wp_max_upload_size() / (1024 * 1024); ?>MB</p>
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <input type="submit" name="wc_xml_import_submit" class="button-primary" value="Начать импорт">
                </p>
            </form>
        </div>
        <?php
    }

    /**
     * Обработка XML импорта
     */
    private function process_xml_import($file_path) {
        // Увеличиваем лимит времени выполнения
        set_time_limit(300);

        // Проверяем, что файл существует и читаем
        if (!file_exists($file_path)) {
            throw new Exception('Файл не найден');
        }

        // Включаем обработку ошибок libxml
        libxml_use_internal_errors(true);

        // Загружаем XML
        $xml = simplexml_load_file($file_path);
        if ($xml === false) {
            $errors = libxml_get_errors();
            $error_message = 'Не удалось загрузить XML файл. ';
            if (!empty($errors)) {
                $error_message .= 'Ошибка: ' . $errors[0]->message;
            }
            throw new Exception($error_message);
        }

        // Парсим данные
        $items = $this->parse_xml_data($xml);

        if (empty($items)) {
            throw new Exception('В XML файле не найдено товаров для импорта');
        }

        // Обрабатываем товары
        $created = 0;
        $updated = 0;
        $errors = 0;

        foreach ($items as $item) {
            try {
                $exists = $this->product_exists($item['Article']);

                if ($this->process_product($item)) {
                    if ($exists) {
                        $updated++;
                    } else {
                        $created++;
                    }
                } else {
                    $errors++;
                }
            } catch (Exception $e) {
                $errors++;
                error_log('Ошибка обработки товара ' . $item['Article'] . ': ' . $e->getMessage());
            }
        }

        return array(
            'message' => 'Импорт завершен успешно! Обработано товаров: ' . count($items),
            'created' => $created,
            'updated' => $updated,
            'errors' => $errors
        );
    }

    /**
     * Парсинг XML данных
     */
    private function parse_xml_data($xml) {
        $items = array();
        $warehouses = array();

        // Собираем все товары по складам
        foreach ($xml->Warehouse as $warehouse) {
            $warehouse_name = (string)$warehouse['name'];

            foreach ($warehouse->Item as $item) {
                $article = (string)$item->Article;
                $price_period = (string)$item->PricePeriod;
                $price = (string)preg_replace('/\s+/', '', $item->Price);;

                if (!isset($warehouses[$warehouse_name])) {
                    $warehouses[$warehouse_name] = array();
                }

                if (!isset($warehouses[$warehouse_name][$article])) {
                    $warehouses[$warehouse_name][$article] = array();
                }

                $warehouses[$warehouse_name][$article][] = array(
                    'Article' => $article,
                    'Name' => (string)$item->Name,
                    'Price' => $price,
                    'Quantity' => (int)$item->Quantity,
                    'PricePeriod' => $price_period,
                    'Description' => (string)$item->Description
                );
            }
        }

        // Обрабатываем логику фильтрации и суммирования
        $processed_items = array();

        foreach ($warehouses as $warehouse_name => $warehouse_articles) {
            foreach ($warehouse_articles as $article => $article_items) {
                // Находим элемент с самым свежим PricePeriod
                $latest_item = null;
                $latest_date = '';

                foreach ($article_items as $item) {
                    if ($item['PricePeriod'] > $latest_date) {
                        $latest_date = $item['PricePeriod'];
                        $latest_item = $item;
                    }
                }

                if ($latest_item) {
                    if (!isset($processed_items[$article])) {
                        $processed_items[$article] = $latest_item;
                    } else {
                        // Суммируем количество если товар найден в другом складе
                        $processed_items[$article]['Quantity'] += $latest_item['Quantity'];

                        // Используем более свежую цену и данные
                        if ($latest_item['PricePeriod'] > $processed_items[$article]['PricePeriod']) {
                            $processed_items[$article]['Price'] = $latest_item['Price'];
                            $processed_items[$article]['Name'] = $latest_item['Name'];
                            $processed_items[$article]['Description'] = $latest_item['Description'];
                            $processed_items[$article]['PricePeriod'] = $latest_item['PricePeriod'];
                        }
                    }
                }
            }
        }

        return array_values($processed_items);
    }

    /**
     * Проверяем существование товара
     */
    private function product_exists($article) {
        $existing = wc_get_products(array(
            'meta_key' => '_article',
            'meta_value' => $article,
            'limit' => 1
        ));

        return !empty($existing);
    }

    /**
     * Обработка товара
     */
    private function process_product($item) {
        // Ищем существующий товар по артикулу
        $existing = wc_get_products(array(
            'meta_key' => '_article',
            'meta_value' => $item['Article'],
            'limit' => 1
        ));

        if (!empty($existing)) {
            // Обновляем существующий товар
            $product = $existing[0];

            // Обновляем цену
            $product->set_regular_price($item['Price']);
            $product->set_price($item['Price']);

            // Обновляем количество
            $product->set_stock_quantity($item['Quantity']);
            $product->set_manage_stock(true);

            // Устанавливаем статус наличия
            if ($item['Quantity'] > 0) {
                $product->set_stock_status('instock');
            } else {
                $product->set_stock_status('outofstock');
            }

            $product->save();

        } else {
            // Создаем новый товар
            $product = new WC_Product();

            $product->set_name($item['Name']);
            $product->set_description($item['Description']);
            $product->set_regular_price($item['Price']);
            $product->set_price($item['Price']);
            $product->set_stock_quantity($item['Quantity']);
            $product->set_manage_stock(true);

            // Устанавливаем статус наличия
            if ($item['Quantity'] > 0) {
                $product->set_stock_status('instock');
            } else {
                $product->set_stock_status('outofstock');
            }

            $product->set_status('publish');

            $product_id = $product->save();

            // Сохраняем артикул в мета поле
            update_post_meta($product_id, '_article', $item['Article']);
        }

        return true;
    }
}
