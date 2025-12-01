jQuery(document).ready(function($) {
    'use strict';

    const container = $('#vintage-groups-container');
    const productId = container.data('product-id');
    const nonce = container.data('nonce');
    
    let selectedProducts = [];

    // Переключение между созданием новой группы и добавлением в существующую
    $('input[name="group_action"]').on('change', function() {
        if ($(this).val() === 'existing') {
            $('#existing_group_row').slideDown();
            $('#new_group_row').slideUp();
        } else {
            $('#existing_group_row').slideUp();
            $('#new_group_row').slideDown();
        }
    });

    // Инициализация автозаполнения
    $('#product_search').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: vintageAdmin.ajaxUrl,
                type: 'GET',
                data: {
                    action: 'search_products_for_vintage',
                    term: request.term,
                    security: vintageAdmin.searchNonce
                },
                success: function(data) {
                    response(data);
                },
                error: function() {
                    response([]);
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
    function addProductToSelection(id, name) {
        if (selectedProducts.find(p => p.id == id)) {
            alert(vintageAdmin.strings.alreadyAdded);
            return;
        }

        selectedProducts.push({ id: id, name: name });
        updateSelectedProductsDisplay();
        updateHiddenField();
    }

    // Обновление отображения выбранных товаров
    function updateSelectedProductsDisplay() {
        if (selectedProducts.length === 0) {
            $('#selected_products').html('');
            return;
        }

        let html = '<div class="selected-products-container">';
        html += '<h4>' + vintageAdmin.strings.selectedProducts + '</h4>';
        html += '<ul class="selected-products-list">';

        selectedProducts.forEach(function(product, index) {
            html += '<li class="selected-product-item">';
            html += '<span class="product-name">' + escapeHtml(product.name) + '</span>';
            html += '<button type="button" class="button button-small remove-product" data-index="' + index + '">';
            html += '×';
            html += '</button>';
            html += '</li>';
        });

        html += '</ul></div>';
        $('#selected_products').html(html);
    }

    // Обновление скрытого поля с ID
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

    // Создание/добавление в группу
    $('#create_vintage_group').on('click', function(e) {
        e.preventDefault();

        const action = $('input[name="group_action"]:checked').val();
        let groupName = '';
        let existingGroupId = '';

        if (action === 'existing') {
            existingGroupId = $('#existing_group_select').val();
            groupName = $('#existing_group_select option:selected').text();

            if (!existingGroupId) {
                alert(vintageAdmin.strings.selectGroup);
                return;
            }
        } else {
            groupName = $('#group_name').val().trim();

            if (!groupName) {
                alert(vintageAdmin.strings.enterName);
                return;
            }

            const productIds = $('#selected_product_ids').val();
            if (!productIds) {
                alert(vintageAdmin.strings.selectProduct);
                return;
            }
        }

        const productIds = $('#selected_product_ids').val();

        // Удаляем старые скрытые поля если есть
        $('input[name="group_action_hidden"]').remove();
        $('input[name="existing_group_id_hidden"]').remove();
        $('input[name="group_name_hidden"]').remove();
        $('input[name="selected_product_ids_hidden"]').remove();

        // Добавляем скрытые поля для отправки с формой
        $('<input>').attr({
            type: 'hidden',
            name: 'group_action_hidden',
            value: action
        }).appendTo('#post');

        if (action === 'existing') {
            $('<input>').attr({
                type: 'hidden',
                name: 'existing_group_id_hidden',
                value: existingGroupId
            }).appendTo('#post');
        } else {
            $('<input>').attr({
                type: 'hidden',
                name: 'group_name_hidden',
                value: groupName
            }).appendTo('#post');
        }

        $('<input>').attr({
            type: 'hidden',
            name: 'selected_product_ids_hidden',
            value: productIds || ''
        }).appendTo('#post');

        // Отправляем форму
        $('#publish').click();
    });

    // Удаление из группы
    $('.remove-from-group').on('click', function() {
        const groupId = $(this).data('group-id');

        if (!confirm(vintageAdmin.strings.confirmRemove)) {
            return;
        }

        const button = $(this);
        button.prop('disabled', true).text('...');

        $.ajax({
            url: vintageAdmin.ajaxUrl,
            type: 'POST',
            data: {
                action: 'remove_product_from_vintage_group',
                group_id: groupId,
                product_id: productId,
                security: nonce
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(vintageAdmin.strings.error + ': ' + (response.data || ''));
                    button.prop('disabled', false).text(vintageAdmin.strings.remove);
                }
            },
            error: function() {
                alert(vintageAdmin.strings.error);
                button.prop('disabled', false).text(vintageAdmin.strings.remove);
            }
        });
    });

    // Экранирование HTML
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
});