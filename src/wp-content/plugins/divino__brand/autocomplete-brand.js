jQuery(document).ready(function ($) {
    const brandInput = $('#product_brand_autocomplete');

    if (brandInput.length > 0) {
        // Убедитесь, что ajaxurl определён
        const ajaxUrl = typeof ajaxurl !== 'undefined' ? ajaxurl : '/wp-admin/admin-ajax.php';

        $.ajax({
            url: ajaxUrl,
            method: 'POST', // Явно указываем метод
            data: {
                action: 'get_all_product_brands',
                // nonce: my_script_vars.nonce // Если требуется защита
            },
            success: function (data) {
                // Проверка формата данных
                if (Array.isArray(data)) {
                    brandInput.autocomplete({
                        source: data,
                        select: function (event, ui) {
                            brandInput.val(ui.item.label);
                            return false; // Предотвращаем стандартное поведение
                        },
                    });
                } else {
                    console.error('Неверный формат данных:', data);
                }
            },
            error: function (xhr, status, error) {
                console.error('Ошибка AJAX:', status, error);
            },
        });
    }
});