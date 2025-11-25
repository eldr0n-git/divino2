<?php
/**
 * Plugin Name: Divino Product Rating
 * Plugin URI: https://divino.kz
 * Description: Плагин для создания кастомных рейтингов товаров WooCommerce
 * Version: 1.0.19
 * Author: eldr0n
 * Text Domain: divino-product-rating
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Шорткоды
 * [divino_rating_block] - полный блок с резюме, заметками и всеми рейтингами
 * [divino_rating_values] - только значения рейтингов в формате "Ярлык: Значение"
 */

if (!defined('ABSPATH')) {
    exit;
}

class Divino_Product_Rating {
    
    private $table_name;
    
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'divino_ratings';
        
        add_action('plugins_loaded', array($this, 'check_table'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('add_meta_boxes', array($this, 'add_product_rating_metabox'));
        add_action('save_post', array($this, 'save_product_rating_meta'));
        add_shortcode('divino_rating_block', array($this, 'rating_block_shortcode'));
        add_shortcode('divino_rating_values', array($this, 'rating_values_shortcode'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('admin_post_divino_save_rating', array($this, 'handle_save_rating'));
        add_action('admin_post_divino_delete_rating', array($this, 'handle_delete_rating'));
    }
    
    /**
     * Проверка существования таблицы при загрузке плагина
     */
    public function check_table() {
        global $wpdb;
        
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'");
        
        if ($table_exists != $this->table_name) {
            $this->activate();
        }
    }
    
    /**
     * Активация плагина - создание таблицы
     */
    public function activate() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE {$this->table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            label varchar(100) NOT NULL,
            max_scale int NOT NULL DEFAULT 100,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Добавление меню в админ панель
     */
    public function add_admin_menu() {
        add_menu_page(
            'Рейтинги',
            'Рейтинги',
            'manage_options',
            'divino-ratings',
            array($this, 'render_ratings_page'),
            'dashicons-star-filled',
            56
        );
        
        add_submenu_page(
            'divino-ratings',
            'Добавить рейтинг',
            'Добавить новый',
            'manage_options',
            'divino-ratings-add',
            array($this, 'render_add_rating_page')
        );
    }
    
    /**
     * Страница списка рейтингов
     */
    public function render_ratings_page() {
        global $wpdb;
        
        // Отладка - показываем имя таблицы
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'");
        
        $ratings = $wpdb->get_results("SELECT * FROM {$this->table_name} ORDER BY name ASC");
        
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Рейтинги</h1>
            <a href="<?php echo admin_url('admin.php?page=divino-ratings-add'); ?>" class="page-title-action">Добавить новый</a>
            <hr class="wp-header-end">
            
            <?php if (isset($_GET['message']) && $_GET['message'] == 'saved') : ?>
                <div class="notice notice-success is-dismissible">
                    <p>Рейтинг успешно сохранен!</p>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['message']) && $_GET['message'] == 'deleted') : ?>
                <div class="notice notice-success is-dismissible">
                    <p>Рейтинг успешно удален!</p>
                </div>
            <?php endif; ?>
            
            <?php if ($table_exists != $this->table_name) : ?>
                <div class="notice notice-error">
                    <p>Ошибка: Таблица <code><?php echo $this->table_name; ?></code> не существует. Попробуйте деактивировать и активировать плагин заново.</p>
                </div>
            <?php endif; ?>
            
            <?php if (empty($ratings)) : ?>
                <p>Рейтинги не найдены. <a href="<?php echo admin_url('admin.php?page=divino-ratings-add'); ?>">Создать первый рейтинг</a></p>
            <?php else : ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Наименование рейтинга</th>
                            <th>Ярлык</th>
                            <th>Шкала оценки</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ratings as $rating) : ?>
                            <tr>
                                <td><strong><?php echo esc_html($rating->name); ?></strong></td>
                                <td><?php echo esc_html($rating->label); ?></td>
                                <td><?php echo esc_html($rating->max_scale); ?></td>
                                <td>
                                    <a href="<?php echo admin_url('admin.php?page=divino-ratings-add&edit=' . $rating->id); ?>">Редактировать</a> |
                                    <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=divino_delete_rating&id=' . $rating->id), 'divino_delete_rating_' . $rating->id); ?>" 
                                       onclick="return confirm('Вы уверены, что хотите удалить этот рейтинг?');">Удалить</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <?php
    }
    
    /**
     * Страница добавления/редактирования рейтинга
     */
    public function render_add_rating_page() {
        global $wpdb;
        
        $edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
        $rating = null;
        
        if ($edit_id) {
            $rating = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $edit_id));
        }
        
        $name = $rating ? $rating->name : '';
        $label = $rating ? $rating->label : '';
        $max_scale = $rating ? $rating->max_scale : 100;
        
        ?>
        <div class="wrap">
            <h1><?php echo $edit_id ? 'Редактировать рейтинг' : 'Добавить новый рейтинг'; ?></h1>
            
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="divino_save_rating">
                <?php wp_nonce_field('divino_save_rating', 'divino_rating_nonce'); ?>
                
                <?php if ($edit_id) : ?>
                    <input type="hidden" name="rating_id" value="<?php echo $edit_id; ?>">
                <?php endif; ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="rating_name">Наименование рейтинга <span class="required">*</span></label>
                        </th>
                        <td>
                            <input type="text" id="rating_name" name="rating_name" 
                                   value="<?php echo esc_attr($name); ?>" 
                                   class="regular-text" required>
                            <p class="description">Полное название рейтинга</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="rating_label">Ярлык <span class="required">*</span></label>
                        </th>
                        <td>
                            <input type="text" id="rating_label" name="rating_label" 
                                   value="<?php echo esc_attr($label); ?>" 
                                   class="regular-text" required>
                            <p class="description">Краткий ярлык для отображения (например: WA, RP, JS)</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="rating_max_scale">Шкала оценки (максимум) <span class="required">*</span></label>
                        </th>
                        <td>
                            <input type="number" id="rating_max_scale" name="rating_max_scale" 
                                   value="<?php echo esc_attr($max_scale); ?>" 
                                   min="1" max="1000" class="small-text" required>
                            <p class="description">Максимальное значение рейтинга</p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button button-primary" 
                           value="<?php echo $edit_id ? 'Обновить рейтинг' : 'Добавить рейтинг'; ?>">
                    <a href="<?php echo admin_url('admin.php?page=divino-ratings'); ?>" class="button">Отмена</a>
                </p>
            </form>
        </div>
        <?php
    }
    
    /**
     * Обработка сохранения рейтинга
     */
    public function handle_save_rating() {
        if (!current_user_can('manage_options')) {
            wp_die('Недостаточно прав');
        }
        
        check_admin_referer('divino_save_rating', 'divino_rating_nonce');
        
        global $wpdb;
        
        $rating_id = isset($_POST['rating_id']) ? intval($_POST['rating_id']) : 0;
        $name = sanitize_text_field($_POST['rating_name']);
        $label = sanitize_text_field($_POST['rating_label']);
        $max_scale = intval($_POST['rating_max_scale']);
        
        $data = array(
            'name' => $name,
            'label' => $label,
            'max_scale' => $max_scale,
        );
        
        if ($rating_id) {
            // Обновление существующего рейтинга
            $wpdb->update(
                $this->table_name,
                $data,
                array('id' => $rating_id),
                array('%s', '%s', '%d'),
                array('%d')
            );
        } else {
            // Создание нового рейтинга
            $wpdb->insert(
                $this->table_name,
                $data,
                array('%s', '%s', '%d')
            );
        }
        
        wp_redirect(admin_url('admin.php?page=divino-ratings&message=saved'));
        exit;
    }
    
    /**
     * Обработка удаления рейтинга
     */
    public function handle_delete_rating() {
        if (!current_user_can('manage_options')) {
            wp_die('Недостаточно прав');
        }
        
        $rating_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        check_admin_referer('divino_delete_rating_' . $rating_id);
        
        global $wpdb;
        
        $wpdb->delete(
            $this->table_name,
            array('id' => $rating_id),
            array('%d')
        );
        
        wp_redirect(admin_url('admin.php?page=divino-ratings&message=deleted'));
        exit;
    }
    
    /**
     * Получение всех рейтингов
     */
    private function get_all_ratings() {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$this->table_name} ORDER BY name ASC");
    }
    
    /**
     * Добавление метабокса в редактирование товара
     */
    public function add_product_rating_metabox() {
        add_meta_box(
            'divino_product_rating',
            'Рейтинг товара',
            array($this, 'render_product_rating_metabox'),
            'product',
            'normal',
            'default'
        );
    }
    
    /**
     * Отображение метабокса рейтинга в товаре
     */
    public function render_product_rating_metabox($post) {
        wp_nonce_field('divino_product_rating_meta', 'divino_product_rating_nonce');
        
        $ratings = $this->get_all_ratings();
        
        $saved_ratings = get_post_meta($post->ID, '_divino_product_ratings', true);
        if (!is_array($saved_ratings)) {
            $saved_ratings = array();
        }
        
        ?>
        <div class="divino-rating-wrapper">
            <h4>Добавьте рейтинги для товара:</h4>
            <div id="divino-ratings-container">
                <?php
                if (!empty($saved_ratings)) {
                    foreach ($saved_ratings as $index => $rating_data) {
                        $this->render_rating_row($ratings, $rating_data, $index);
                    }
                } else {
                    $this->render_rating_row($ratings, array(), 0);
                }
                ?>
            </div>
            
            <p>
                <button type="button" class="button divino-add-rating">+ Добавить рейтинг</button>
            </p>
        </div>
        
        <script type="text/template" id="divino-rating-row-template">
            <?php $this->render_rating_row($ratings, array(), '{{INDEX}}'); ?>
        </script>
        <?php
    }
    
    /**
     * Рендер одной строки рейтинга
     */
    private function render_rating_row($ratings, $rating_data, $index) {
        $selected_rating = isset($rating_data['rating_id']) ? $rating_data['rating_id'] : '';
        $rating_value = isset($rating_data['value']) ? $rating_data['value'] : '';
        $summary = isset($rating_data['summary']) ? $rating_data['summary'] : '';
        $notes = isset($rating_data['notes']) ? $rating_data['notes'] : '';
        
        ?>
        <div class="divino-rating-row" style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 4px;">
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Тип рейтинга:</label>
                <select name="divino_product_ratings[<?php echo $index; ?>][rating_id]" class="divino-rating-select" style="width: 100%; max-width: 400px;">
                    <option value="">-- Выберите рейтинг --</option>
                    <?php foreach ($ratings as $rating) : ?>
                        <option value="<?php echo $rating->id; ?>" 
                                data-max="<?php echo esc_attr($rating->max_scale); ?>"
                                <?php selected($selected_rating, $rating->id); ?>>
                            <?php echo esc_html($rating->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Значение рейтинга:</label>
                <input type="number" name="divino_product_ratings[<?php echo $index; ?>][value]" 
                       value="<?php echo esc_attr($rating_value); ?>" 
                       class="divino-rating-value" 
                       step="0.1" min="0" style="width: 150px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Общее резюме:</label>
                <input type="text" name="divino_product_ratings[<?php echo $index; ?>][summary]" 
                       value="<?php echo esc_attr($summary); ?>" 
                       class="divino-rating-summary" 
                       style="width: 100%;" 
                       placeholder="Краткое описание оценки">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Дегустационные заметки:</label>
                <textarea name="divino_product_ratings[<?php echo $index; ?>][notes]" 
                          class="divino-rating-notes" 
                          rows="3" 
                          style="width: 100%;" 
                          placeholder="Подробные заметки о дегустации"><?php echo esc_textarea($notes); ?></textarea>
            </div>
            
            <div>
                <button type="button" class="button divino-remove-rating">Удалить этот рейтинг</button>
            </div>
        </div>
        <?php
    }
    
    /**
     * Сохранение метаданных товара
     */
    public function save_product_rating_meta($post_id) {
        // Проверки безопасности
        if (!isset($_POST['divino_product_rating_nonce'])) {
            return;
        }
        
        if (!wp_verify_nonce($_POST['divino_product_rating_nonce'], 'divino_product_rating_meta')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Только для товаров
        if (get_post_type($post_id) !== 'product') {
            return;
        }
        
        if (isset($_POST['divino_product_ratings'])) {
            $ratings = array();
            foreach ($_POST['divino_product_ratings'] as $rating) {
                if (!empty($rating['rating_id'])) {
                    $ratings[] = array(
                        'rating_id' => absint($rating['rating_id']),
                        'value' => isset($rating['value']) ? floatval($rating['value']) : 0,
                        'summary' => isset($rating['summary']) ? sanitize_text_field($rating['summary']) : '',
                        'notes' => isset($rating['notes']) ? sanitize_textarea_field($rating['notes']) : '',
                    );
                }
            }
            update_post_meta($post_id, '_divino_product_ratings', $ratings);
        }
    }
    
    /**
     * Подключение скриптов для админки
     */
    public function enqueue_admin_scripts($hook) {
        global $post_type;
        
        if ('product' === $post_type && ('post.php' === $hook || 'post-new.php' === $hook)) {
            wp_enqueue_script(
                'divino-rating-admin',
                plugin_dir_url(__FILE__) . 'admin.js',
                array('jquery'),
                '1.0.0',
                true
            );
        }
    }
    
    /**
     * Шорткод для полного блока рейтингов и наград
     */
    public function rating_block_shortcode($atts) {
        global $post, $wpdb;
        
        if (!$post || get_post_type($post) !== 'product') {
            return '';
        }
        
        $ratings = get_post_meta($post->ID, '_divino_product_ratings', true);
        
        if (empty($ratings)) {
            return '';
        }
        
        ob_start();
        ?>
        <div class="divino-rating-block">
            <h2>Рейтинги и награды</h2>
            
            <div class="divino-rating-list">
            <?php foreach ($ratings as $rating_data) : 
                $rating = $wpdb->get_row($wpdb->prepare(
                    "SELECT * FROM {$this->table_name} WHERE id = %d", 
                    $rating_data['rating_id']
                ));
                if ($rating) :
            ?>

                
                <div class="divino-rating-item">
                    <div class="divino-rating-icon">
                        <?php
                            $path = get_stylesheet_directory() . '/assets/images/awards/'.$rating->label.'.svg';
                            $svg  = file_get_contents($path);
                            // Значение рейтинга для замены в шаблоне SVG
                            $new_value = $rating_data['value'];
                            // Заменяем содержимое <text id="value">...</text>
                            $svg = preg_replace_callback(
                                '/<text[^>]*id="value"[^>]*>.*?<\/text>/s',
                                function ($matches) use ($new_value) {
                                    return preg_replace('/>.*?</', '>' . $new_value . '<', $matches[0]);
                                },
                                $svg
                            );
                            echo $svg; 
                        ?>
                    </div>
                    <div class="rating__cnt">
                        <div class="rating">
                            <!-- <div class="rating__value">
                                <?php echo esc_html($rating_data['value']); ?>
                            </div> -->
                            <div class="rating__title">
                                <?php echo esc_html($rating->name); ?>
                            </div>
                        </div>
                        <!-- <h4 style="margin-top: 0;">
                            <?php echo esc_html($rating->name); ?>
                            <span style="float: right; font-size: 1.2em; color: #2271b1;">
                                <?php echo esc_html($rating->label); ?>: <?php echo esc_html($rating_data['value']); ?> / <?php echo esc_html($rating->max_scale); ?>
                            </span>
                        </h4> -->
                        
                        <?php if (!empty($rating_data['summary'])) : ?>
                            <div class="divino-rating-summary">
                                <strong>Общее резюме:</strong>
                                <p><?php echo esc_html($rating_data['summary']); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($rating_data['notes'])) : ?>
                            <div class="divino-rating-notes">
                                <strong>Дегустационные заметки:</strong>
                                <p><?php echo nl2br(esc_html($rating_data['notes'])); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php 
                endif;
            endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Шорткод для вывода только значений рейтингов
     */
    public function rating_values_shortcode($atts) {
        global $post, $wpdb;
        
        if (!$post || get_post_type($post) !== 'product') {
            return '';
        }
        
        $ratings = get_post_meta($post->ID, '_divino_product_ratings', true);
        
        if (empty($ratings)) {
            return '';
        }
        
        ob_start();
        ?>
        <a class="divino-rating-values-only" href="#divino-ratings">
            <?php 
                $currentRating = 0;
                foreach ($ratings as $rating_data) : 
                $rating = $wpdb->get_row($wpdb->prepare(
                    "SELECT * FROM {$this->table_name} WHERE id = %d", 
                    $rating_data['rating_id']
                ));
                $currentRating++;
                // Limit output of award badges to 3 cause fo layout issues
                if ($rating && $currentRating < 4) :
            ?>
                <span class="divino-rating-item">
                    <!-- <span class="divino-label"><?php echo esc_html($rating->label); ?>:</span> -->
                    <div class="divino-rating-icon">
                        <?php
                            $path = get_stylesheet_directory() . '/assets/images/awards/'.$rating->label.'.svg';
                            $svg  = file_get_contents($path);
                            // Значение рейтинга для замены в шаблоне SVG
                            $new_value = $rating_data['value'];
                            // Заменяем содержимое <text id="value">...</text>
                            $svg = preg_replace_callback(
                                '/<text[^>]*id="value"[^>]*>.*?<\/text>/s',
                                function ($matches) use ($new_value) {
                                    return preg_replace('/>.*?</', '>' . $new_value . '<', $matches[0]);
                                },
                                $svg
                            );
                            echo $svg; 
                        ?>
                    </div>
                    <!-- <span class="divino-value"><?php echo esc_html($rating_data['value']); ?></span> -->
                </span>
            <?php 
                endif;
            endforeach; ?>
        </a>
        <?php
        return ob_get_clean();
    }
}

// Инициализация плагина
$divino_rating = new Divino_Product_Rating();

// Хук активации
register_activation_hook(__FILE__, array($divino_rating, 'activate'));
?>