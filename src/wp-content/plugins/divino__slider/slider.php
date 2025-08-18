<?php
/**
 * Plugin Name: DIVINO WooCommerce Advanced Slider
 * Plugin URI: https://example.com
 * Description: Профессиональный слайдер для главной страницы WooCommerce с административной панелью управления
 * Version: 1.0.1
 * Author: eldr0n
 * License: GPL v2 or later
 * Text Domain: wc-advanced-slider
 */

// Предотвращение прямого доступа
if (!defined('ABSPATH')) {
    exit;
}

// Определение констант плагина
define('DIVINO_SLIDER_VERSION', '1.0.1');
define('DIVINO_SLIDER_PLUGIN_PATH', plugin_dir_url(__FILE__));

class Divino_Slider {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'));
        add_action('wp_ajax_save_slider_data', array($this, 'save_slider_data'));
        add_action('wp_ajax_delete_slide', array($this, 'delete_slide'));
        add_action('wp_ajax_upload_slide_image', array($this, 'upload_slide_image'));
        add_shortcode('divino_slider', array($this, 'display_slider'));
        add_action( 'init', array($this, 'divino_slider_register_block') );
        
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        load_plugin_textdomain('wc-advanced-slider', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    public function activate() {
        // Создание таблицы для слайдов при активации плагина
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'wc_slider_slides';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            title varchar(255) DEFAULT '',
            description text DEFAULT '',
            button_text varchar(100) DEFAULT '',
            button_url varchar(255) DEFAULT '',
            image_url varchar(255) DEFAULT '',
            custom_styles text DEFAULT '',
            slide_order int(11) DEFAULT 0,
            is_active tinyint(1) DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        // Добавление дефолтных слайдов
        $this->add_default_slides();
    }
    
    public function deactivate() {
        // Очистка при деактивации плагина (опционально)
    }
    
    private function add_default_slides() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wc_slider_slides';
        
        // Проверяем, есть ли уже слайды
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        
        if ($count == 0) {
            $default_slides = array(
                array(
                    'title' => 'Новая коллекция',
                    'description' => 'Откройте для себя потрясающие товары из нашей новой коллекции',
                    'button_text' => 'Смотреть коллекцию',
                    'button_url' => '#',
                    'custom_styles' => 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);',
                    'slide_order' => 1
                ),
                array(
                    'title' => 'Скидка до 50%',
                    'description' => 'Невероятные предложения на популярные товары!',
                    'button_text' => 'Купить со скидкой',
                    'button_url' => '#',
                    'custom_styles' => 'background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);',
                    'slide_order' => 2
                ),
                array(
                    'title' => 'Бесплатная доставка',
                    'description' => 'Заказывайте от 2000 рублей и получайте бесплатную доставку',
                    'button_text' => 'Узнать больше',
                    'button_url' => '#',
                    'custom_styles' => 'background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);',
                    'slide_order' => 3
                )
            );
            
            foreach ($default_slides as $slide) {
                $wpdb->insert($table_name, $slide);
            }
        }
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'Divino Slider',
            'Divino Slider',
            'manage_options',
            'divino-slider',
            array($this, 'admin_page'),
            'dashicons-images-alt2',
            30
        );
    }
    
    public function admin_scripts($hook) {
        if ($hook != 'toplevel_page_wc-slider') {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        
        wp_enqueue_style('wc-slider-admin-css', DIVINO_SLIDER_PLUGIN_PATH . 'admin-style.css', array(), DIVINO_SLIDER_VERSION);
        wp_enqueue_script('wc-slider-admin-js', DIVINO_SLIDER_PLUGIN_PATH . 'admin-script.js', array('jquery', 'wp-color-picker'), DIVINO_SLIDER_VERSION, true);
        
        wp_localize_script('wc-slider-admin-js', 'wc_slider_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wc_slider_nonce')
        ));
    }
    
    public function frontend_scripts() {
        wp_enqueue_style('wc-slider-frontend-css', DIVINO_SLIDER_PLUGIN_PATH . 'frontend-style.css', array(), DIVINO_SLIDER_VERSION);
        wp_enqueue_script('wc-slider-frontend-js', DIVINO_SLIDER_PLUGIN_PATH . 'frontend-script.js', array('jquery'), DIVINO_SLIDER_VERSION, true);
        
        // Получаем настройки слайдера
        $settings = get_option('wc_slider_settings', array('slide_duration' => 5000));
        
        wp_localize_script('wc-slider-frontend-js', 'wc_slider_settings', $settings);
    }
    
    public function admin_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wc_slider_slides';
        
        // Получение слайдов
        $slides = $wpdb->get_results("SELECT * FROM $table_name ORDER BY slide_order ASC");
        
        // Получение настроек
        $settings = get_option('wc_slider_settings', array(
            'slide_duration' => 5000,
            'auto_play' => 1,
            'show_arrows' => 1,
            'show_dots' => 1
        ));
        
        ?>
        <div class="wrap">
            <h1><?php _e('WooCommerce Slider Management', 'wc-advanced-slider'); ?></h1>
            
            <div class="wc-slider-admin-container">
                <div class="wc-slider-tabs">
                    <button class="tab-button active" data-tab="slides"><?php _e('Слайды', 'wc-advanced-slider'); ?></button>
                    <button class="tab-button" data-tab="settings"><?php _e('Настройки', 'wc-advanced-slider'); ?></button>
                    <button class="tab-button" data-tab="shortcode"><?php _e('Шорткод', 'wc-advanced-slider'); ?></button>
                </div>
                
                <div class="tab-content active" id="slides-tab">
                    <div class="slides-header">
                        <h2><?php _e('Управление слайдами', 'wc-advanced-slider'); ?></h2>
                        <button class="button button-primary" id="add-new-slide"><?php _e('Добавить слайд', 'wc-advanced-slider'); ?></button>
                    </div>
                    
                    <div class="slides-container" id="slides-sortable">
                        <?php foreach ($slides as $slide): ?>
                            <?php $this->render_slide_item($slide); ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="tab-content" id="settings-tab">
                    <h2><?php _e('Настройки слайдера', 'wc-advanced-slider'); ?></h2>
                    <form id="slider-settings-form">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Время отображения слайда (мс)', 'wc-advanced-slider'); ?></th>
                                <td>
                                    <input type="number" name="slide_duration" value="<?php echo esc_attr($settings['slide_duration']); ?>" min="1000" step="100" />
                                    <p class="description"><?php _e('Время в миллисекундах (по умолчанию 5000 = 5 секунд)', 'wc-advanced-slider'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Автопрокрутка', 'wc-advanced-slider'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="auto_play" value="1" <?php checked($settings['auto_play'], 1); ?> />
                                        <?php _e('Включить автоматическую прокрутку', 'wc-advanced-slider'); ?>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Показать стрелки', 'wc-advanced-slider'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="show_arrows" value="1" <?php checked($settings['show_arrows'], 1); ?> />
                                        <?php _e('Показывать стрелки навигации', 'wc-advanced-slider'); ?>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Показать точки', 'wc-advanced-slider'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="show_dots" value="1" <?php checked($settings['show_dots'], 1); ?> />
                                        <?php _e('Показывать точки навигации', 'wc-advanced-slider'); ?>
                                    </label>
                                </td>
                            </tr>
                        </table>
                        <p class="submit">
                            <button type="submit" class="button button-primary"><?php _e('Сохранить настройки', 'wc-advanced-slider'); ?></button>
                        </p>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Modal для редактирования слайда -->
        <div id="slide-modal" class="wc-slider-modal" style="display: none;">
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <h2 id="modal-title"><?php _e('Редактировать слайд', 'wc-advanced-slider'); ?></h2>
                <form id="slide-form">
                    <input type="hidden" id="slide-id" name="slide_id" />
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('Заголовок', 'wc-advanced-slider'); ?></th>
                            <td><input type="text" id="slide-title" name="title" style="width: 100%;" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Описание', 'wc-advanced-slider'); ?></th>
                            <td><textarea id="slide-description" name="description" rows="3" style="width: 100%;"></textarea></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Текст кнопки', 'wc-advanced-slider'); ?></th>
                            <td><input type="text" id="slide-button-text" name="button_text" style="width: 100%;" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Ссылка кнопки', 'wc-advanced-slider'); ?></th>
                            <td><input type="url" id="slide-button-url" name="button_url" style="width: 100%;" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Изображение', 'wc-advanced-slider'); ?></th>
                            <td>
                                <div class="image-upload-container">
                                    <input type="hidden" id="slide-image-url" name="image_url" />
                                    <div id="slide-image-preview"></div>
                                    <button type="button" class="button" id="upload-image-btn"><?php _e('Выбрать изображение', 'wc-advanced-slider'); ?></button>
                                    <button type="button" class="button" id="remove-image-btn" style="display: none;"><?php _e('Удалить изображение', 'wc-advanced-slider'); ?></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Пользовательские стили', 'wc-advanced-slider'); ?></th>
                            <td>
                                <textarea id="slide-custom-styles" name="custom_styles" rows="4" style="width: 100%;" placeholder="background: #ff0000; color: white;"></textarea>
                                <p class="description"><?php _e('CSS стили для слайда (например: background: linear-gradient(45deg, #ff0000, #0000ff);)', 'wc-advanced-slider'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Активен', 'wc-advanced-slider'); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" id="slide-is-active" name="is_active" value="1" />
                                    <?php _e('Показывать этот слайд', 'wc-advanced-slider'); ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                    
                    <p class="submit">
                        <button type="submit" class="button button-primary"><?php _e('Сохранить слайд', 'wc-advanced-slider'); ?></button>
                        <button type="button" class="button" id="cancel-modal"><?php _e('Отмена', 'wc-advanced-slider'); ?></button>
                    </p>
                </form>
            </div>
        </div>
        <?php
    }
    
    private function render_slide_item($slide) {
        ?>
        <div class="slide-item" data-slide-id="<?php echo $slide->id; ?>">
            <div class="slide-handle">⋮⋮</div>
            <div class="slide-preview">
                <?php if ($slide->image_url): ?>
                    <img src="<?php echo esc_url($slide->image_url); ?>" alt="<?php echo esc_attr($slide->title); ?>" />
                <?php else: ?>
                    <div class="slide-placeholder" style="<?php echo esc_attr($slide->custom_styles); ?>">
                        <h3><?php echo esc_html($slide->title); ?></h3>
                    </div>
                <?php endif; ?>
            </div>
            <div class="slide-info">
                <h3><?php echo esc_html($slide->title); ?></h3>
                <p><?php echo esc_html(wp_trim_words($slide->description, 10)); ?></p>
                <div class="slide-actions">
                    <button class="button button-small edit-slide" data-slide-id="<?php echo $slide->id; ?>"><?php _e('Редактировать', 'wc-advanced-slider'); ?></button>
                    <button class="button button-small delete-slide" data-slide-id="<?php echo $slide->id; ?>"><?php _e('Удалить', 'wc-advanced-slider'); ?></button>
                    <span class="slide-status <?php echo $slide->is_active ? 'active' : 'inactive'; ?>">
                        <?php echo $slide->is_active ? __('Активен', 'wc-advanced-slider') : __('Неактивен', 'wc-advanced-slider'); ?>
                    </span>
                </div>
            </div>
        </div>
        <?php
    }
    
    public function save_slider_data() {
        check_ajax_referer('wc_slider_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Недостаточно прав доступа', 'wc-advanced-slider'));
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'wc_slider_slides';
        
        $action_type = sanitize_text_field($_POST['action_type']);
        
        if ($action_type === 'save_slide') {
            $slide_id = intval($_POST['slide_id']);
            $data = array(
                'title' => sanitize_text_field($_POST['title']),
                'description' => sanitize_textarea_field($_POST['description']),
                'button_text' => sanitize_text_field($_POST['button_text']),
                'button_url' => esc_url_raw($_POST['button_url']),
                'image_url' => esc_url_raw($_POST['image_url']),
                'custom_styles' => sanitize_textarea_field($_POST['custom_styles']),
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            );
            
            if ($slide_id > 0) {
                // Обновление существующего слайда
                $wpdb->update($table_name, $data, array('id' => $slide_id));
            } else {
                // Создание нового слайда
                $data['slide_order'] = $wpdb->get_var("SELECT MAX(slide_order) FROM $table_name") + 1;
                $wpdb->insert($table_name, $data);
                $slide_id = $wpdb->insert_id;
            }
            
            wp_send_json_success(array('slide_id' => $slide_id));
        }
        
        if ($action_type === 'save_settings') {
            $settings = array(
                'slide_duration' => intval($_POST['slide_duration']),
                'auto_play' => isset($_POST['auto_play']) ? 1 : 0,
                'show_arrows' => isset($_POST['show_arrows']) ? 1 : 0,
                'show_dots' => isset($_POST['show_dots']) ? 1 : 0
            );
            
            update_option('wc_slider_settings', $settings);
            wp_send_json_success();
        }
        
        if ($action_type === 'update_order') {
            $slides_order = $_POST['slides_order'];
            foreach ($slides_order as $index => $slide_id) {
                $wpdb->update($table_name, array('slide_order' => $index + 1), array('id' => intval($slide_id)));
            }
            wp_send_json_success();
        }
    }
    
    public function delete_slide() {
        check_ajax_referer('wc_slider_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Недостаточно прав доступа', 'wc-advanced-slider'));
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'wc_slider_slides';
        $slide_id = intval($_POST['slide_id']);
        
        $wpdb->delete($table_name, array('id' => $slide_id));
        wp_send_json_success();
    }
 
    public function display_slider($atts) {
        $atts = shortcode_atts(array(
            'height' => '500px',
            'class' => ''
        ), $atts);
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'wc_slider_slides';
        
        $slides = $wpdb->get_results("SELECT * FROM $table_name WHERE is_active = 1 ORDER BY slide_order ASC");
        
        if (empty($slides)) {
            return '<p>' . __('Слайды не найдены', 'wc-advanced-slider') . '</p>';
        }
        
        $settings = get_option('wc_slider_settings', array(
            'slide_duration' => 5000,
            'auto_play' => 1,
            'show_arrows' => 1,
            'show_dots' => 1
        ));
        
        ob_start();
        ?>
        <div class="wc-slider-container <?php echo esc_attr($atts['class']); ?>" style="height: <?php echo esc_attr($atts['height']); ?>;">
            <div class="wc-slider" data-settings='<?php echo json_encode($settings); ?>'>
                <?php foreach ($slides as $index => $slide): ?>
                    <div class="wc-slide <?php echo $index === 0 ? 'active' : ''; ?>" style="<?php echo esc_attr($slide->custom_styles); ?>">
                        <?php if ($slide->image_url): ?>
                            <div class="slide-bg-image" style="background-image: url('<?php echo esc_url($slide->image_url); ?>');"></div>
                        <?php endif; ?>
                        
                        <div class="slide-bg-shapes">
                            <div class="slide-bg-shape shape-1"></div>
                            <div class="slide-bg-shape shape-2"></div>
                            <div class="slide-bg-shape shape-3"></div>
                        </div>
                        
                        <div class="slide-content">
                            <?php if ($slide->title): ?>
                                <h2><?php echo esc_html($slide->title); ?></h2>
                            <?php endif; ?>
                            
                            <?php if ($slide->description): ?>
                                <p><?php echo esc_html($slide->description); ?></p>
                            <?php endif; ?>
                            
                            <?php if ($slide->button_text && $slide->button_url): ?>
                                <a href="<?php echo esc_url($slide->button_url); ?>" class="cta-button">
                                    <?php echo esc_html($slide->button_text); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php if ($settings['show_arrows']): ?>
                    <button class="slider-arrow prev-arrow">❮</button>
                    <button class="slider-arrow next-arrow">❯</button>
                <?php endif; ?>
                
                <?php if ($settings['show_dots']): ?>
                    <div class="slider-nav">
                        <?php foreach ($slides as $index => $slide): ?>
                            <div class="nav-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>"></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    // ---------- Гутенберг-блок ----------
    public function divino_slider_register_block() {
        register_block_type( 'divino/slider', array(
            'render_callback' => array( $this, 'divino_slider_block_render' ),
        ) );
    }
    public function divino_slider_block_render( $attributes ) {
        return $this->display_slider($attributes);
    }
}

// Инициализация плагина
new Divino_Slider();
?>