<?php
/**
 * Plugin Name: Divino Sugar
 * Description: Добавляет метабокс для управления содержанием сахара в винах и игристых напитках
 * Version: 1.0.0
 * Author: Divino
 * Text Domain: divino-sugar
 */

// Предотвращаем прямой доступ
if (!defined('ABSPATH')) {
    exit;
}

class DivinoSugar {
    
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_sugar_metabox'));
        add_action('save_post', array($this, 'save_sugar_content'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Добавляет метабокс для содержания сахара
     */
    public function add_sugar_metabox() {
        add_meta_box(
            'divino_sugar_metabox',
            'Содержание сахара',
            array($this, 'render_sugar_metabox'),
            'product',
            'normal',
            'high'
        );
    }
    
    /**
     * Отображает содержимое метабокса
     */
    public function render_sugar_metabox($post) {
        // Добавляем nonce для безопасности
        wp_nonce_field('divino_sugar_save', 'divino_sugar_nonce');
        
        // Проверяем тип товара
        $allowed_kinds = ['wine', 'champagne-and-sparkling']; // Разрешенные типы товаров
        $terms = wp_get_post_terms($post->ID, 'product_kind', ['fields' => 'slugs']);
        $meta_product_kind = get_post_meta($post->ID, 'product_kind', true);
        
        // Определяем тип продукта для настройки слайдера
        $product_type = '';
        
        // Сначала пробуем через таксономию
        if (!empty($terms) && array_intersect($allowed_kinds, $terms)) {
            if (in_array('wine', $terms)) {
                $product_type = 'wine';
            } elseif (in_array('champagne-and-sparkling', $terms)) {
                $product_type = 'champagne-and-sparkling';
            }
        }
        // Если не найдено, пробуем через мета-поле
        elseif (!empty($meta_product_kind) && in_array($meta_product_kind, $allowed_kinds)) {
            $product_type = $meta_product_kind;
        }
        
        // Если тип не подходит, показываем сообщение
        if (empty($product_type)) {
            echo '<p>Метабокс содержания сахара доступен только для вин и игристых напитков.</p>';
            return;
        }
        
        // Получаем сохраненные значения
        $sugar_content = get_post_meta($post->ID, '_divino_sugar_content', true);
        $sugar_content = $sugar_content ? floatval($sugar_content) : 0;
        
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="divino_sugar_content">Содержание сахара (г/л)</label>
                </th>
                <td>
                    <input type="number" 
                           id="divino_sugar_content" 
                           name="divino_sugar_content" 
                           value="<?php echo esc_attr($sugar_content); ?>" 
                           step="0.1" 
                           min="0" 
                           style="width: 100px;" />
                    <span id="sugar-unit">г/л</span>
                </td>
            </tr>
            <tr>
                <th scope="row">Категория сладости</th>
                <td>
                    <div id="sugar-slider-container">
                        <div id="sugar-slider"></div>
                        <div id="sugar-labels"></div>
                        <div id="current-category" style="margin-top: 10px; font-weight: bold; color: #0073aa;"></div>
                    </div>
                </td>
            </tr>
        </table>
        
        <input type="hidden" id="divino-product-type" value="<?php echo esc_attr($product_type); ?>" />
        
        <style>
            #sugar-slider {
                height: 20px;
                background: linear-gradient(to right, #f0f0f0, #0073aa);
                border-radius: 10px;
                position: relative;
                margin: 20px 0;
                cursor: pointer;
            }
            
            #sugar-slider::before {
                content: '';
                position: absolute;
                width: 20px;
                height: 20px;
                background: #fff;
                border: 3px solid #0073aa;
                border-radius: 50%;
                top: 0;
                transform: translateX(-50%);
                cursor: grab;
                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            }
            
            #sugar-slider:active::before {
                cursor: grabbing;
            }
            
            #sugar-labels {
                display: flex;
                justify-content: space-between;
                font-size: 12px;
                color: #666;
                margin-top: 10px;
            }
            
            .sugar-label {
                text-align: center;
                flex: 1;
                padding: 5px;
                background: #f9f9f9;
                border: 1px solid #ddd;
                margin: 0 2px;
                border-radius: 3px;
            }
            
            .sugar-label.active {
                background: #0073aa;
                color: white;
                font-weight: bold;
            }
        </style>
        
        <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('divino_sugar_content');
            const slider = document.getElementById('sugar-slider');
            const labelsContainer = document.getElementById('sugar-labels');
            const currentCategory = document.getElementById('current-category');
            const productType = document.getElementById('divino-product-type').value;
            
            console.log('Product type from hidden field:', productType);
            
            // Конфигурации для разных типов продуктов
            const wineConfig = {
                categories: [
                    { name: 'Сухое вино', min: 0, max: 4, label: 'Сухое<br>(0-4 г/л)' },
                    { name: 'Полусухое вино', min: 4, max: 12, label: 'Полусухое<br>(4-12 г/л)' },
                    { name: 'Полусладкое вино', min: 12, max: 45, label: 'Полусладкое<br>(12-45 г/л)' },
                    { name: 'Сладкое вино', min: 45, max: 100, label: 'Сладкое<br>(45+ г/л)' }
                ],
                maxValue: 100
            };
            
            const champagneConfig = {
                categories: [
                    { name: 'Brut Nature/Zero', min: 0, max: 3, label: 'Brut Nature<br>(0-3 г/л)' },
                    { name: 'Extra Brut', min: 3, max: 6, label: 'Extra Brut<br>(3-6 г/л)' },
                    { name: 'Brut', min: 6, max: 12, label: 'Brut<br>(6-12 г/л)' },
                    { name: 'Extra Sec/Extra Dry', min: 12, max: 17, label: 'Extra Sec<br>(12-17 г/л)' },
                    { name: 'Sec/Dry', min: 17, max: 32, label: 'Sec<br>(17-32 г/л)' },
                    { name: 'Demi-Sec', min: 32, max: 50, label: 'Demi-Sec<br>(32-50 г/л)' },
                    { name: 'Doux', min: 50, max: 80, label: 'Doux<br>(50+ г/л)' }
                ],
                maxValue: 80
            };
            
            // Выбираем конфигурацию в зависимости от типа продукта
            let currentConfig;
            if (productType === 'wine') {
                currentConfig = wineConfig;
            } else if (productType === 'champagne-and-sparkling') {
                currentConfig = champagneConfig;
            } else {
                console.error('Unknown product type:', productType);
                return;
            }
            
            function setupSlider() {
                // Создаем лейблы
                labelsContainer.innerHTML = '';
                currentConfig.categories.forEach((category, index) => {
                    const label = document.createElement('div');
                    label.className = 'sugar-label';
                    label.innerHTML = category.label;
                    label.dataset.index = index;
                    labelsContainer.appendChild(label);
                });
                
                updateSliderPosition();
            }
            
            function updateSliderPosition() {
                const value = parseFloat(input.value) || 0;
                let position = 0;
                let activeCategory = null;
                
                // Находим активную категорию
                for (let i = 0; i < currentConfig.categories.length; i++) {
                    const category = currentConfig.categories[i];
                    if (value >= category.min && (value < category.max || i === currentConfig.categories.length - 1)) {
                        activeCategory = category;
                        // Вычисляем позицию внутри категории
                        const categoryWidth = 100 / currentConfig.categories.length;
                        const categoryProgress = Math.min((value - category.min) / (category.max - category.min), 1);
                        position = (i * categoryWidth) + (categoryProgress * categoryWidth);
                        break;
                    }
                }
                
                // Обновляем позицию слайдера
                slider.style.setProperty('--position', position + '%');
                slider.style.background = `linear-gradient(to right, #f0f0f0 ${position}%, #0073aa ${position}%)`;
                
                // Обновляем активный лейбл
                document.querySelectorAll('.sugar-label').forEach((label, index) => {
                    label.classList.toggle('active', activeCategory && index === currentConfig.categories.indexOf(activeCategory));
                });
                
                // Обновляем текущую категорию
                currentCategory.textContent = activeCategory ? activeCategory.name : 'Не определено';
            }
            
            function handleSliderClick(event) {
                const rect = slider.getBoundingClientRect();
                const clickX = event.clientX - rect.left;
                const percentage = (clickX / rect.width) * 100;
                
                // Определяем категорию по проценту
                const categoryIndex = Math.floor(percentage / (100 / currentConfig.categories.length));
                const categoryClampedIndex = Math.max(0, Math.min(categoryIndex, currentConfig.categories.length - 1));
                const category = currentConfig.categories[categoryClampedIndex];
                
                // Вычисляем значение внутри категории
                const categoryWidth = 100 / currentConfig.categories.length;
                const categoryStartPercent = categoryClampedIndex * categoryWidth;
                const categoryProgress = (percentage - categoryStartPercent) / categoryWidth;
                const value = category.min + (categoryProgress * (category.max - category.min));
                
                input.value = Math.max(0, Math.round(value * 10) / 10);
                updateSliderPosition();
            }
            
            // Обработчики событий
            input.addEventListener('input', updateSliderPosition);
            slider.addEventListener('click', handleSliderClick);
            
            // Инициализация
            setupSlider();
        });
        </script>
        <?php
    }
    
    /**
     * Сохраняет значение содержания сахара
     */
    public function save_sugar_content($post_id) {
        // Проверяем nonce
        if (!isset($_POST['divino_sugar_nonce']) || !wp_verify_nonce($_POST['divino_sugar_nonce'], 'divino_sugar_save')) {
            return;
        }
        
        // Проверяем права пользователя
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Проверяем автосохранение
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Сохраняем содержание сахара
        if (isset($_POST['divino_sugar_content'])) {
            $sugar_content = floatval($_POST['divino_sugar_content']);
            update_post_meta($post_id, '_divino_sugar_content', $sugar_content);
        }
    }
    
    /**
     * Подключает скрипты и стили для админки
     */
    public function enqueue_admin_scripts($hook) {
        global $post;
        
        if ($hook !== 'post.php' && $hook !== 'post-new.php') {
            return;
        }
        
        if (!$post || $post->post_type !== 'product') {
            return;
        }
        
        wp_enqueue_script('jquery');
    }
}

// Инициализируем плагин
new DivinoSugar();

/**
 * Функция для получения содержания сахара товара
 */
function divino_get_sugar_content($product_id) {
    return get_post_meta($product_id, '_divino_sugar_content', true);
}

/**
 * Функция для определения категории сладости
 */
function divino_get_sugar_category($product_id) {
    $sugar_content = floatval(divino_get_sugar_content($product_id));
    $product_kind = get_post_meta($product_id, 'product_kind', true);
    
    if ($product_kind === 'wine') {
        if ($sugar_content <= 4) return 'Сухое вино';
        if ($sugar_content <= 12) return 'Полусухое вино';
        if ($sugar_content <= 45) return 'Полусладкое вино';
        return 'Сладкое вино';
    } elseif ($product_kind === 'champagne-and-sparkling') {
        if ($sugar_content <= 3) return 'Brut Nature/Zero';
        if ($sugar_content <= 6) return 'Extra Brut';
        if ($sugar_content <= 12) return 'Brut';
        if ($sugar_content <= 17) return 'Extra Sec/Extra Dry';
        if ($sugar_content <= 32) return 'Sec/Dry';
        if ($sugar_content <= 50) return 'Demi-Sec';
        return 'Doux';
    }
    
    return '';
}









/**
 * Register the shortcode [divino_sugar].
 */
add_shortcode( 'divino_sugar', 'divino_sugar_frontend_shortcode_handler' );
function divino_sugar_frontend_shortcode_handler( $atts ) {
    global $post;

    $sugar_content = get_post_meta( $post->ID, '_divino_sugar_content', true );
    //$product_kind = get_post_meta( $post->ID, 'product_kind', true );
    $product_kind = wp_get_post_terms($post->ID, 'product_kind', ['fields' => 'slugs'])[0];

    // Only proceed if we have the necessary data
    if ( empty( $sugar_content ) || ! in_array( $product_kind, [ 'wine', 'champagne-and-sparkling' ] ) ) {
        return '';
    }

    // Enqueue the frontend CSS file
    wp_enqueue_style(
        'divino-sugar-frontend-css',
        plugin_dir_url( __FILE__ ) . 'css/frontend.css',
        [],
        '1.2'
    );

    // Define the ranges for wine and champagne
    $wine_ranges = [
        ['label' => 'Сухое', 'min' => 0, 'max' => 4],
        ['label' => 'Полусухое', 'min' => 4, 'max' => 12],
        ['label' => 'Полусладкое', 'min' => 12, 'max' => 45],
        ['label' => 'Сладкое', 'min' => 45, 'max' => 999] // High max for "more than"
    ];
    $champagne_ranges = [
        ['label' => 'Brut Nature', 'min' => 0, 'max' => 3],
        ['label' => 'Extra Brut', 'min' => 3, 'max' => 6],
        ['label' => 'Brut', 'min' => 6, 'max' => 12],
        ['label' => 'Extra Dry', 'min' => 12, 'max' => 17],
        ['label' => 'Sec', 'min' => 17, 'max' => 32],
        ['label' => 'Demi-Sec', 'min' => 32, 'max' => 50],
        ['label' => 'Doux', 'min' => 50, 'max' => 999] // High max for "more than"
    ];

    $ranges = ($product_kind === 'wine') ? $wine_ranges : $champagne_ranges;
    $sugar_float = floatval($sugar_content);
    $active_label = '';

    // Find the active label based on sugar content
    foreach ($ranges as $index => $range) {
        if ($sugar_float > $range['min'] && $sugar_float <= $range['max']) {
            $active_label = $range['label'];
            break;
        }
    }
     // A special check for the very first category (e.g. 0-4)
    if (empty($active_label) && $sugar_float >= $ranges[0]['min'] && $sugar_float <= $ranges[0]['max']) {
        $active_label = $ranges[0]['label'];
    }

    //return '<h1>EEEEEEEEEEEEEEEEE</h1>';

    // Start output buffering to capture HTML
    ob_start();
    ?>
    <div class="divino-sugar-timeline">
        <div class="timeline-header">
            <strong>Сладость:</strong>
            <span class="current-sugar-value"><?php echo esc_html($active_label); ?></span>
        </div>
        <div class="timeline-track">
            <?php foreach ($ranges as $range): ?>
                <?php
                    $is_active = ($active_label === $range['label']) ? 'active' : '';
                ?>
                <div class="timeline-point-wrapper">
                    <div class="timeline-point <?php echo $is_active; ?>"></div>
                    <div class="timeline-label"><?php echo esc_html($range['label']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    // Return the captured HTML
    return ob_get_clean();
}

?>