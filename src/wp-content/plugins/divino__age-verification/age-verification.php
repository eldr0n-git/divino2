<?php
/**
 * Plugin Name: Divino Age Verification
 * Plugin URI: https://divino.kz
 * Description: Показывает модальное окно с подтверждением возраста 21+ при первом посещении сайта
 * Version: 1.0.0
 * Author: eldr0n
 * Author URI: https://yoursite.com
 * License: GPL v2 or later
 * Text Domain: age-verification
 */

// Защита от прямого доступа
if (!defined('ABSPATH')) {
    exit;
}

class Age_Verification_Modal {

    private $cookie_name = 'age_verified';
    private $cookie_duration = 90; // дней

    public function __construct() {
        add_action('wp_footer', array($this, 'add_modal_html'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    /**
     * Подключение стилей и скриптов
     */
    public function enqueue_scripts() {
        // Проверяем, есть ли кука
        if (isset($_COOKIE[$this->cookie_name])) {
            return;
        }

        wp_enqueue_style(
            'age-verification-modal',
            plugin_dir_url(__FILE__) . 'css/style.css',
            array(),
            '1.0.0'
        );

        wp_enqueue_script(
            'age-verification-modal',
            plugin_dir_url(__FILE__) . 'js/script.js',
            array('jquery'),
            '1.0.0',
            true
        );

        // Передаём данные в JavaScript
        wp_localize_script('age-verification-modal', 'ageVerificationData', array(
            'cookieName' => $this->cookie_name,
            'cookieDuration' => $this->cookie_duration
        ));
    }

    /**
     * Добавление HTML модального окна
     */
    public function add_modal_html() {
        // Если кука уже установлена, не показываем модальное окно
        if (isset($_COOKIE[$this->cookie_name])) {
            return;
        }
        ?>
        <div id="age-verification-modal" class="age-modal">
            <div class="age-modal-content">
                <div class="age-modal-icon">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                </div>
                <h2 class="age-modal-title">Подтверждение возраста</h2>
                <p class="age-modal-text">
                    Для посещения этого сайта вам должно быть 21 год или более.
                    <br>
                    Пожалуйста, подтвердите ваш возраст.
                </p>
                <button id="age-verify-btn" class="age-verify-button">
                    Мне 21 или более лет
                </button>
            </div>
        </div>
        <?php
    }
}

// Инициализация плагина
function age_verification_modal_init() {
    new Age_Verification_Modal();
}
add_action('plugins_loaded', 'age_verification_modal_init');
