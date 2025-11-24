jQuery(document).ready(function($) {
    var ratingIndex = $('.divino-rating-row').length;
    
    // Добавление нового рейтинга
    $('.divino-add-rating').on('click', function() {
        var template = $('#divino-rating-row-template').html();
        template = template.replace(/{{INDEX}}/g, ratingIndex);
        $('#divino-ratings-container').append(template);
        ratingIndex++;
    });
    
    // Удаление рейтинга
    $(document).on('click', '.divino-remove-rating', function() {
        $(this).closest('.divino-rating-row').remove();
    });
    
    // Обновление максимального значения при выборе рейтинга
    $(document).on('change', '.divino-rating-select', function() {
        var maxValue = $(this).find('option:selected').data('max');
        var valueInput = $(this).closest('.divino-rating-row').find('.divino-rating-value');
        if (maxValue) {
            valueInput.attr('max', maxValue);
        }
    });
});