jQuery(document).ready(function($) {

    // Переменные для хранения выбранных элементов
    var selectedGrapes = [];
    var selectedFoodPairing = [];
    var selectedFoodProduct = [];
    var editSelectedGrapes = [];
    var editSelectedFood = [];

    // Показать/скрыть форму создания сочетания
    $('#create-pairing-btn').on('click', function() {
        $('#pairing-form').slideToggle();
        loadPopularFood();
    });

    $('#cancel-pairing').on('click', function() {
        $('#pairing-form').slideUp();
        resetPairingForm();
    });

    // Автозаполнение для сортов винограда
    $('#grape-varieties').on('input', function() {
        var search = $(this).val();
        if (search.length > 1) {
            searchGrapeVarieties(search);
        } else {
            hideAutocomplete();
        }
    });

    // Автозаполнение для еды в сочетаниях
    $('#food-items').on('input', function() {
        var search = $(this).val();
        if (search.length > 1) {
            searchFood(search, 'pairing');
        } else {
            hideAutocomplete();
        }
    });

    // Автозаполнение для еды в товарах
    $('#product-food-search').on('input', function() {
        var search = $(this).val();
        if (search.length > 1) {
            searchFood(search, 'product');
        } else {
            hideAutocomplete();
        }
    });

    // Поиск сортов винограда
    function searchGrapeVarieties(search) {
        $.get(wcFoodPairings.ajax_url, {
            action: 'search_grape_varieties',
            search: search
        }, function(data) {
            showAutocomplete(data, '#grape-varieties', 'grape');
        });
    }

    // Поиск еды
    function searchFood(search, context) {
        $.get(wcFoodPairings.ajax_url, {
            action: 'search_food',
            search: search
        }, function(data) {
            var targetField = context === 'pairing' ? '#food-items' : '#product-food-search';
            showAutocomplete(data, targetField, 'food', context);
        });
    }

    // Показать автозаполнение
    function showAutocomplete(data, targetField, type, context) {
        hideAutocomplete();

        if (data.length === 0) return;

        var $field = $(targetField);
        var $suggestions = $('<div class="autocomplete-suggestions"></div>');

        $.each(data, function(index, item) {
            var $suggestion = $('<div class="autocomplete-suggestion" data-id="' + item.id + '">' + item.title + '</div>');
            $suggestion.on('click', function() {
                addSelectedItem(item, type, context);
                $field.val('');
                hideAutocomplete();
            });
            $suggestions.append($suggestion);
        });

        $field.parent().append($suggestions);
        $suggestions.css({
            'position': 'absolute',
            'top': $field.position().top + $field.outerHeight(),
            'left': $field.position().left,
            'width': $field.outerWidth()
        });
    }

    // Скрыть автозаполнение
    function hideAutocomplete() {
        $('.autocomplete-suggestions').remove();
    }

    // Добавить выбранный элемент
    function addSelectedItem(item, type, context) {
        if (type === 'grape') {
            if (selectedGrapes.some(grape => grape.id === item.id)) return;
            selectedGrapes.push(item);
            updateSelectedGrapes();
        } else if (type === 'edit-grape') {
            if (editSelectedGrapes.some(function(g) { return g.id === item.id; })) return;
            editSelectedGrapes.push(item);
            updateEditSelectedGrapes();
        } else if (type === 'edit-food') {
            if (editSelectedFood.some(function(f) { return f.id === item.id; })) return;
            editSelectedFood.push(item);
            updateEditSelectedFood();
        } else if (type === 'food') {
            if (context === 'pairing') {
                if (selectedFoodPairing.some(food => food.id === item.id)) return;
                selectedFoodPairing.push(item);
                updateSelectedFoodPairing();
            } else if (context === 'product') {
                if (selectedFoodProduct.some(food => food.id === item.id)) return;
                selectedFoodProduct.push(item);
                updateSelectedFoodProduct();
            }
        }
    }

    // Обновить отображение выбранных сортов винограда
    function updateSelectedGrapes() {
        var $container = $('#selected-grapes');
        $container.empty();

        $.each(selectedGrapes, function(index, grape) {
            var $item = $('<span class="selected-item" data-id="' + grape.id + '">' + grape.title + ' <span class="remove">×</span></span>');
            $item.find('.remove').on('click', function() {
                selectedGrapes = selectedGrapes.filter(g => g.id !== grape.id);
                updateSelectedGrapes();
            });
            $container.append($item);
        });
    }

    // Обновить отображение выбранной еды для сочетаний
    function updateSelectedFoodPairing() {
        var $container = $('#selected-food');
        $container.empty();

        $.each(selectedFoodPairing, function(index, food) {
            var $item = $('<span class="selected-item" data-id="' + food.id + '">' + food.title + ' <span class="remove">×</span></span>');
            $item.find('.remove').on('click', function() {
                selectedFoodPairing = selectedFoodPairing.filter(f => f.id !== food.id);
                updateSelectedFoodPairing();
            });
            $container.append($item);
        });
    }

    // Обновить отображение выбранной еды для товаров
    function updateSelectedFoodProduct() {
        var $container = $('#selected-food-items');
        $container.empty();

        var foodIds = [];
        $.each(selectedFoodProduct, function(index, food) {
            var $item = $('<span class="selected-item" data-id="' + food.id + '">' + food.title + ' <span class="remove">×</span></span>');
            $item.find('.remove').on('click', function() {
                selectedFoodProduct = selectedFoodProduct.filter(f => f.id !== food.id);
                updateSelectedFoodProduct();
            });
            $container.append($item);
            foodIds.push(food.id);
        });

        $('#product-food-items-hidden').val(foodIds.join(','));
    }

    // Загрузить популярную еду
    function loadPopularFood() {
        $.get(wcFoodPairings.ajax_url, {
            action: 'get_popular_food'
        }, function(data) {
            var $container = $('#popular-food-list');
            $container.empty();

            $.each(data, function(index, food) {
                var $item = $('<span class="popular-item" data-id="' + food.id + '">' + food.title + '</span>');
                $item.on('click', function() {
                    addSelectedItem(food, 'food', 'pairing');
                });
                $container.append($item);
            });
        });
    }

    // Загрузить популярную еду для товаров
    function loadPopularFoodForProducts() {
        $.get(wcFoodPairings.ajax_url, {
            action: 'get_popular_food'
        }, function(data) {
            var $container = $('#popular-food-items');
            $container.empty();

            $.each(data, function(index, food) {
                var $item = $('<span class="popular-item" data-id="' + food.id + '">' + food.title + '</span>');
                $item.on('click', function() {
                    addSelectedItem(food, 'food', 'product');
                });
                $container.append($item);
            });
        });
    }

    // Сохранить сочетание
    $('#new-pairing-form').on('submit', function(e) {
        e.preventDefault();

        if (selectedGrapes.length === 0 || selectedFoodPairing.length === 0) {
            alert('Пожалуйста, выберите хотя бы один сорт винограда и одно блюдо.');
            return;
        }

        var grapeNames = selectedGrapes.map(g => g.title).join(', ');
        var foodIds = selectedFoodPairing.map(f => f.id).join(',');

        $.post(wcFoodPairings.ajax_url, {
            action: 'save_pairing',
            grape_varieties: grapeNames,
            food_ids: foodIds,
            _ajax_nonce: wcFoodPairings.nonce
        }, function(response) {
            if (response.success) {
                alert('Сочетание сохранено!');
                location.reload();
            } else {
                alert('Ошибка сохранения: ' + response.data);
            }
        });
    });

    // ─── Редактирование сочетания ────────────────────────────────────

    function openEditModal(id, grapes, foodIds, foodNames) {
        editSelectedGrapes = grapes
            ? grapes.split(', ').map(function(name) { return { id: name, title: name }; })
            : [];

        var ids    = foodIds    ? foodIds.split(',')    : [];
        var names  = foodNames  ? foodNames.split('|||') : [];
        editSelectedFood = ids.map(function(id, i) {
            return { id: parseInt(id, 10), title: names[i] || id };
        });

        $('#edit-pairing-id').val(id);
        $('#edit-grape-varieties').val('');
        $('#edit-food-items').val('');
        updateEditSelectedGrapes();
        updateEditSelectedFood();
        $('#edit-pairing-modal').fadeIn(150);
    }

    function closeEditModal() {
        $('#edit-pairing-modal').fadeOut(150);
        editSelectedGrapes = [];
        editSelectedFood = [];
    }

    $(document).on('click', '.edit-pairing', function() {
        var $btn = $(this);
        openEditModal(
            $btn.data('id'),
            $btn.data('grapes'),
            String($btn.data('food-ids')),
            $btn.data('food-names')
        );
    });

    $('#close-edit-modal, #cancel-edit-pairing').on('click', closeEditModal);
    $('#edit-pairing-modal').on('click', function(e) {
        if ($(e.target).is('#edit-pairing-modal')) closeEditModal();
    });

    // Автозаполнение в форме редактирования
    $('#edit-grape-varieties').on('input', function() {
        var search = $(this).val();
        if (search.length > 1) {
            $.get(wcFoodPairings.ajax_url, { action: 'search_grape_varieties', search: search }, function(data) {
                showAutocomplete(data, '#edit-grape-varieties', 'edit-grape');
            });
        } else {
            hideAutocomplete();
        }
    });

    $('#edit-food-items').on('input', function() {
        var search = $(this).val();
        if (search.length > 1) {
            $.get(wcFoodPairings.ajax_url, { action: 'search_food', search: search }, function(data) {
                showAutocomplete(data, '#edit-food-items', 'edit-food');
            });
        } else {
            hideAutocomplete();
        }
    });

    function updateEditSelectedGrapes() {
        var $container = $('#edit-selected-grapes');
        $container.empty();
        $.each(editSelectedGrapes, function(i, grape) {
            var $item = $('<span class="selected-item" data-id="' + grape.id + '">' + grape.title + ' <span class="remove">×</span></span>');
            $item.find('.remove').on('click', function() {
                editSelectedGrapes = editSelectedGrapes.filter(function(g) { return g.id !== grape.id; });
                updateEditSelectedGrapes();
            });
            $container.append($item);
        });
    }

    function updateEditSelectedFood() {
        var $container = $('#edit-selected-food');
        $container.empty();
        $.each(editSelectedFood, function(i, food) {
            var $item = $('<span class="selected-item" data-id="' + food.id + '">' + food.title + ' <span class="remove">×</span></span>');
            $item.find('.remove').on('click', function() {
                editSelectedFood = editSelectedFood.filter(function(f) { return f.id !== food.id; });
                updateEditSelectedFood();
            });
            $container.append($item);
        });
    }

    // Сохранить изменения
    $('#edit-pairing-form').on('submit', function(e) {
        e.preventDefault();

        if (editSelectedGrapes.length === 0 || editSelectedFood.length === 0) {
            alert('Выберите хотя бы один сорт винограда и одно блюдо.');
            return;
        }

        var grapeNames = editSelectedGrapes.map(function(g) { return g.title; }).join(', ');
        var foodIds    = editSelectedFood.map(function(f) { return f.id; }).join(',');

        $.post(wcFoodPairings.ajax_url, {
            action:          'update_pairing',
            pairing_id:      $('#edit-pairing-id').val(),
            grape_varieties: grapeNames,
            food_ids:        foodIds,
            _ajax_nonce:     wcFoodPairings.nonce
        }, function(response) {
            if (response.success) {
                closeEditModal();
                location.reload();
            } else {
                alert('Ошибка: ' + response.data);
            }
        });
    });

    // Удалить сочетание
    $('.delete-pairing').on('click', function() {
        if (!confirm('Вы уверены, что хотите удалить это сочетание?')) {
            return;
        }

        var pairingId = $(this).data('id');
        var $row = $(this).closest('tr');

        $.post(wcFoodPairings.ajax_url, {
            action: 'delete_pairing',
            pairing_id: pairingId,
            _ajax_nonce: wcFoodPairings.nonce
        }, function(response) {
            if (response.success) {
                $row.fadeOut();
            } else {
                alert('Ошибка удаления: ' + response.data);
            }
        });
    });

    // Сбросить форму создания сочетания
    function resetPairingForm() {
        selectedGrapes = [];
        selectedFoodPairing = [];
        updateSelectedGrapes();
        updateSelectedFoodPairing();
        $('#grape-varieties').val('');
        $('#food-items').val('');
        $('#popular-food-list').empty();
    }

    // Инициализация для страницы товара
    if ($('#product-food-meta').length > 0) {
        // Загрузить существующие выбранные блюда
        $('#selected-food-items .selected-item').each(function() {
            var id = $(this).data('id');
            var title = $(this).text().replace('×', '').trim();
            selectedFoodProduct.push({id: id, title: title});
        });

        // Обработчики для удаления существующих элементов
        $(document).on('click', '#selected-food-items .remove', function() {
            var $item = $(this).parent();
            var id = $item.data('id');
            selectedFoodProduct = selectedFoodProduct.filter(f => f.id != id);
            updateSelectedFoodProduct();
        });

        // Загрузить популярную еду при загрузке страницы
        loadPopularFoodForProducts();
    }

    // Закрыть автозаполнение при клике вне его
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.autocomplete-suggestions, input[type="text"]').length) {
            hideAutocomplete();
        }
    });

    // Обработка клавиш для автозаполнения
    $(document).on('keydown', 'input[type="text"]', function(e) {
        var $suggestions = $('.autocomplete-suggestions');
        if ($suggestions.length === 0) return;

        var $active = $suggestions.find('.autocomplete-suggestion.active');
        var $items = $suggestions.find('.autocomplete-suggestion');

        if (e.keyCode === 40) { // Стрелка вниз
            e.preventDefault();
            if ($active.length === 0) {
                $items.first().addClass('active');
            } else {
                $active.removeClass('active');
                var $next = $active.next();
                if ($next.length === 0) {
                    $items.first().addClass('active');
                } else {
                    $next.addClass('active');
                }
            }
        } else if (e.keyCode === 38) { // Стрелка вверх
            e.preventDefault();
            if ($active.length === 0) {
                $items.last().addClass('active');
            } else {
                $active.removeClass('active');
                var $prev = $active.prev();
                if ($prev.length === 0) {
                    $items.last().addClass('active');
                } else {
                    $prev.addClass('active');
                }
            }
        } else if (e.keyCode === 13) { // Enter
            e.preventDefault();
            if ($active.length > 0) {
                $active.click();
            }
        } else if (e.keyCode === 27) { // Escape
            hideAutocomplete();
        }
    });

    // Стили для активного элемента автозаполнения
    $(document).on('mouseenter', '.autocomplete-suggestion', function() {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
    });

    // Дополнительные стили
    var additionalCSS = `
        <style>
        .autocomplete-suggestion.active {
            background-color: #0073aa !important;
            color: white !important;
        }
        .selected-item {
            display: inline-block;
            background: #f1f1f1;
            padding: 5px 10px;
            margin: 2px;
            border-radius: 3px;
            position: relative;
            border: 1px solid #ddd;
        }
        .selected-item .remove {
            margin-left: 5px;
            cursor: pointer;
            font-weight: bold;
            color: #666;
        }
        .selected-item .remove:hover {
            color: #d63384;
        }
        .popular-item {
            display: inline-block;
            background: #e7f3ff;
            padding: 3px 8px;
            margin: 2px;
            border-radius: 3px;
            cursor: pointer;
            border: 1px solid #b3d9ff;
            font-size: 12px;
        }
        .popular-item:hover {
            background: #cce7ff;
            border-color: #66b3ff;
        }
        .autocomplete-suggestions {
            border: 1px solid #ccc;
            background: white;
            max-height: 200px;
            overflow-y: auto;
            position: absolute;
            z-index: 1000;
            width: 100%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .autocomplete-suggestion {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }
        .autocomplete-suggestion:hover,
        .autocomplete-suggestion.active {
            background: #f1f1f1;
        }
        .autocomplete-suggestion:last-child {
            border-bottom: none;
        }
        #product-food-meta h4,
        #pairing-form h4 {
            margin-top: 15px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        #selected-grapes,
        #selected-food,
        #selected-food-items {
            min-height: 30px;
            padding: 10px;
            background: #fafafa;
            border: 1px solid #ddd;
            border-radius: 3px;
            margin-top: 5px;
        }
        #popular-food-list,
        #popular-food-items {
            margin-top: 10px;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #e5e5e5;
            border-radius: 3px;
        }
        .card {
            background: white;
            border: 1px solid #c3c4c7;
            border-radius: 4px;
            padding: 20px;
            margin: 20px 0;
        }
        </style>
    `;

    $('head').append(additionalCSS);
});
