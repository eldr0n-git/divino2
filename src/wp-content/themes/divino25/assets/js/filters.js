jQuery(document).ready(function($) {
    $('.filter-form').on('submit', function(e) {
        e.preventDefault();
        var regions = [];
        $('input[name="region[]"]:checked').each(function() {
            regions.push($(this).val());
        });
        var url = window.location.href.split('?')[0];
        if (regions.length > 0) {
            url += '?region=' + regions.join(',');
        }
        window.location.href = url;
    });

    // customize the add to cart button styling
    $('.add_to_cart_button').on('click', function(e) {
        $(this).addClass('product--added');
    });


});
