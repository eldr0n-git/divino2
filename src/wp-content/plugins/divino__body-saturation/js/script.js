jQuery(document).ready(function($) {
    const slider = $('#divino_body_saturation');
    const display = $('#divino-saturation-value-display');

    if (slider.length) {
        // Инициализация отображения
        display.text(slider.val());

        // Обновление при изменении
        slider.on('input', function() {
            display.text($(this).val());
        });
    }
});
