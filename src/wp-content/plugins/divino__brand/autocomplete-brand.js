jQuery(document).ready(function ($) {
    const brandInput = $('input#product_brand_autocomplete');

    if (brandInput.length) {
        $.ajax({
            url: ajaxurl,
            data: {
                action: 'get_all_product_brands',
            },
            success: function (data) {
                brandInput.autocomplete({
                    source: data,
                    select: function (event, ui) {
                        brandInput.val(ui.item.label);
                        return false;
                    },
                });
            },
        });
    }
});
