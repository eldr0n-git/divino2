jQuery(document).ready(function($) {
    
    // Инициализация
    initializeTabs();
    initializeSortable();
    initializeModal();
    initializeImageUpload();
    bindEvents();
    
    // Табы
    function initializeTabs() {
        $('.tab-button').on('click', function() {
            const tabId = $(this).data('tab');
            
            $('.tab-button').removeClass('active');
            $('.tab-content').removeClass('active');
            
            $(this).addClass('active');
            $('#' + tabId + '-tab').addClass('active');
        });
    }
    
    // Сортировка слайдов
    function initializeSortable() {
        $('#slides-sortable').sortable({
            handle: '.slide-handle',
            placeholder: 'ui-sortable-placeholder',
            helper: 'clone',
            update: function(event, ui) {
                updateSlidesOrder();
            }
        });
    }
    
    // Обновление порядка слайдов
    function updateSlidesOrder() {
        const slidesOrder = [];
        $('#slides-sortable .slide-item').each(function() {
            slidesOrder.push($(this).data('slide-id'));
        });
        
        $.ajax({
            url: wc_slider_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'save_slider_data',
                action_type: 'update_order',
                slides_order: slidesOrder,
                nonce: wc_slider_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    showNotice('Порядок слайдов обновлен', 'success');
                }
            }
        });
    }
    
    // Модальное окно
    function initializeModal() {
        const modal = $('#slide-modal');
        const closeBtn = $('.close-modal');
        
        closeBtn.on('click', function() {
            modal.hide();
        });
        
        $(window).on('click', function(event) {
            if (event.target === modal[0]) {
                modal.hide();
            }
        });
        
        $('#cancel-modal').on('click', function() {
            modal.hide();
        });
    }
    
    // Загрузка изображений
    function initializeImageUpload() {
        let mediaUploader;
        
        $('#upload-image-btn').on('click', function(e) {
            e.preventDefault();
            
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            mediaUploader = wp.media({
                title: 'Выберите изображение для слайда',
                button: {
                    text: 'Использовать это изображение'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });
            
            mediaUploader.on('select', function() {
                const attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#slide-image-url').val(attachment.url);
                $('#slide-image-preview').html('<img src="' + attachment.url + '" alt="Preview" />');
                $('#remove-image-btn').show();
                $('.image-upload-container').addClass('has-image');
            });
            
            mediaUploader.open();
        });
        
        $('#remove-image-btn').on('click', function() {
            $('#slide-image-url').val('');
            $('#slide-image-preview').empty();
            $(this).hide();
            $('.image-upload-container').removeClass('has-image');
        });
    }
    
    // Привязка событий
    function bindEvents() {
        // Добавление нового слайда
        $('#add-new-slide').on('click', function() {
            openSlideModal();
        });
        
        // Редактирование слайда
        $(document).on('click', '.edit-slide', function() {
            const slideId = $(this).data('slide-id');
            loadSlideData(slideId);
        });
        
        // Удаление слайда
        $(document).on('click', '.delete-slide', function() {
            const slideId = $(this).data('slide-id');
            if (confirm('Вы уверены, что хотите удалить этот слайд?')) {
                deleteSlide(slideId);
            }
        });
        
        // Сохранение слайда
        $('#slide-form').on('submit', function(e) {
            e.preventDefault();
            saveSlide();
        });
        
        // Сохранение настроек
        $('#slider-settings-form').on('submit', function(e) {
            e.preventDefault();
            saveSettings();
        });
    }
    
    // Открытие модального окна для нового слайда
    function openSlideModal(slideData = null) {
        const modal = $('#slide-modal');
        const form = $('#slide-form');
        
        // Сброс формы
        form[0].reset();
        $('#slide-id').val('');
        $('#slide-image-preview').empty();
        $('#remove-image-btn').hide();
        $('.image-upload-container').removeClass('has-image');
        
        if (slideData) {
            // Заполнение формы данными слайда
            $('#modal-title').text('Редактировать слайд');
            $('#slide-id').val(slideData.id);
            $('#slide-title').val(slideData.title);
            $('#slide-description').val(slideData.description);
            $('#slide-button-text').val(slideData.button_text);
            $('#slide-button-url').val(slideData.button_url);
            $('#slide-image-url').val(slideData.image_url);
            $('#slide-custom-styles').val(slideData.custom_styles);
            $('#slide-is-active').prop('checked', slideData.is_active == 1);
            
            if (slideData.image_url) {
                $('#slide-image-preview').html('<img src="' + slideData.image_url + '" alt="Preview" />');
                $('#remove-image-btn').show();
                $('.image-upload-container').addClass('has-image');
            }
        } else {
            $('#modal-title').text('Добавить новый слайд');
            $('#slide-is-active').prop('checked', true);
        }
        
        modal.show();
    }
    
    // Загрузка данных слайда
    function loadSlideData(slideId) {
        $.ajax({
            url: wc_slider_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_slide_data',
                slide_id: slideId,
                nonce: wc_slider_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    openSlideModal(response.data);
                }
            },
            error: function() {
                // Fallback - открыть модал с пустой формой
                openSlideModal();
            }
        });
    }
    
    // Сохранение слайда
    function saveSlide() {
        const form = $('#slide-form');
        const formData = new FormData(form[0]);
        
        formData.append('action', 'save_slider_data');
        formData.append('action_type', 'save_slide');
        formData.append('nonce', wc_slider_ajax.nonce);
        
        // Показываем индикатор загрузки
        form.addClass('loading');
        
        $.ajax({
            url: wc_slider_ajax.ajax_url,
            type: 'POST',
            data: Object.fromEntries(formData),
            success: function(response) {
                form.removeClass('loading');
                
                if (response.success) {
                    $('#slide-modal').hide();
                    showNotice('Слайд успешно сохранен', 'success');
                    location.reload(); // Перезагрузка страницы для обновления списка
                } else {
                    showNotice('Ошибка при сохранении слайда', 'error');
                }
            },
            error: function() {
                form.removeClass('loading');
                showNotice('Ошибка при сохранении слайда', 'error');
            }
        });
    }
    
    // Удаление слайда
    function deleteSlide(slideId) {
        $.ajax({
            url: wc_slider_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'delete_slide',
                slide_id: slideId,
                nonce: wc_slider_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('[data-slide-id="' + slideId + '"]').fadeOut(300, function() {
                        $(this).remove();
                    });
                    showNotice('Слайд удален', 'success');
                } else {
                    showNotice('Ошибка при удалении слайда', 'error');
                }
            },
            error: function() {
                showNotice('Ошибка при удалении слайда', 'error');
            }
        });
    }
    
    // Сохранение настроек
    function saveSettings() {
        const form = $('#slider-settings-form');
        const formData = new FormData(form[0]);
        
        formData.append('action', 'save_slider_data');
        formData.append('action_type', 'save_settings');
        formData.append('nonce', wc_slider_ajax.nonce);
        
        form.addClass('loading');
        
        $.ajax({
            url: wc_slider_ajax.ajax_url,
            type: 'POST',
            data: Object.fromEntries(formData),
            success: function(response) {
                form.removeClass('loading');
                
                if (response.success) {
                    showNotice('Настройки сохранены', 'success');
                } else {
                    showNotice('Ошибка при сохранении настроек', 'error');
                }
            },
            error: function() {
                form.removeClass('loading');
                showNotice('Ошибка при сохранении настроек', 'error');
            }
        });
    }
    
    // Показать уведомление
    function showNotice(message, type) {
        // Удаляем предыдущие уведомления
        $('.wc-slider-notice').remove();
        
        const notice = $('<div class="wc-slider-notice ' + type + '">' + message + '</div>');
        $('.wc-slider-admin-container').prepend(notice);
        
        notice.fadeIn();
        
        // Автоматически скрыть через 5 секунд
        setTimeout(function() {
            notice.fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
    }
    
    // Дополнительная функция для получения данных слайда
    // (нужно добавить соответствующий AJAX handler в PHP)
    function getSlideData(slideId) {
        return $.ajax({
            url: wc_slider_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_slide_data',
                slide_id: slideId,
                nonce: wc_slider_ajax.nonce
            }
        });
    }
    
    // Color picker для пользовательских стилей
    if ($.fn.wpColorPicker) {
        $('#slide-custom-styles').after('<input type="text" class="color-picker" />');
        $('.color-picker').wpColorPicker({
            change: function(event, ui) {
                const color = ui.color.toString();
                const currentStyles = $('#slide-custom-styles').val();
                const newStyles = currentStyles + '\nbackground-color: ' + color + ';';
                $('#slide-custom-styles').val(newStyles);
            }
        });
    }
    
    // Предпросмотр стилей
    $('#slide-custom-styles').on('input', function() {
        const styles = $(this).val();
        // Можно добавить предпросмотр стилей в реальном времени
    });
    
    // Валидация формы
    function validateSlideForm() {
        const title = $('#slide-title').val().trim();
        const description = $('#slide-description').val().trim();
        
        if (!title && !description) {
            showNotice('Заполните хотя бы заголовок или описание', 'error');
            return false;
        }
        
        const buttonText = $('#slide-button-text').val().trim();
        const buttonUrl = $('#slide-button-url').val().trim();
        
        if (buttonText && !buttonUrl) {
            showNotice('Если указан текст кнопки, необходимо также указать ссылку', 'error');
            return false;
        }
        
        return true;
    }
    
    // Добавляем валидацию к форме
    $('#slide-form').on('submit', function(e) {
        if (!validateSlideForm()) {
            e.preventDefault();
            return false;
        }
    });
});