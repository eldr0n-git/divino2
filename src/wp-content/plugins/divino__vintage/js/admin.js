jQuery(document).ready(function($) {
    let selectedProducts = [];

    // Автозаполнение для поиска товаров
    $('#product_search').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: vintage_ajax.ajax_url,
                type: 'GET',
                data: {
                    action: 'search_products_for_vintage',
                    term: request.term,
                    _ajax_nonce: vintage_ajax.nonce
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            addProductToSelection(ui.item.id, ui.item.label);
            $(this).val('');
            return false;
        }
    });

    // Добавление товара в выбранные
    function addProductToSelection(productId, productName) {
        // Проверяем, не добавлен ли уже товар
        if (selectedProducts.find(p => p.id == productId)) {
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
    }

    // Удаление товара из выбранных
    $(document).on('click', '.remove-product', function() {
        const index = $(this).data('index');
        selectedProducts.splice(index, 1);
        updateSelectedProductsDisplay();
        updateHiddenField();
    });

    // Создание группы
    $('#create_vintage_group').click(function() {
        const groupName = $('#group_name').val().trim();
        const productIds = $('#selected_product_ids').val();

        if (!groupName) {
            alert('Введите название группы');
            return;
        }

        if (!productIds) {
            alert('Выберите хотя бы один товар для группы');
            return;
        }

        // Добавляем скрытые поля к форме для сохранения
        if ($('input[name="group_name"]').length === 0) {
            $('<input>').attr({
                type: 'hidden',
                name: 'group_name',
                value: groupName
            }).appendTo('#post');
        } else {
            $('input[name="group_name"]').val(groupName);
        }

        if ($('input[name="selected_product_ids"]').length === 0) {
            $('<input>').attr({
                type: 'hidden',
                name: 'selected_product_ids',
                value: productIds
            }).appendTo('#post');
        } else {
            $('input[name="selected_product_ids"]').val(productIds);
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
                url: vintage_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'remove_product_from_vintage_group',
                    group_id: groupId,
                    product_id: productId,
                    _ajax_nonce: vintage_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Ошибка при удалении из группы');
                    }
                }
            });
        }
    });
});
