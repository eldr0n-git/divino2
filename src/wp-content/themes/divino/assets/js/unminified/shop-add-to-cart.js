jQuery(document).ready(function() {
    jQuery(document.body).on('added_to_cart', function() {

        const singleProductButton = jQuery('.single_add_to_cart_button');
        if (!singleProductButton.hasClass('loading') && divino_shop_add_to_cart.shop_add_to_cart_action) {
            const slideInCart = jQuery('#divino-mobile-cart-drawer');
            if (divino_shop_add_to_cart.elementor_preview_active) {
                return;
            } else {
                if ('slide_in_cart' === divino_shop_add_to_cart.shop_add_to_cart_action && slideInCart.length > 0) {
                    slideInCart.addClass('active');
                    jQuery('html').addClass('ast-mobile-cart-active');
                }
                if (divino_shop_add_to_cart.is_divino_pro) {
                    if ('redirect_cart_page' === divino_shop_add_to_cart.shop_add_to_cart_action) {
                        window.open(divino_shop_add_to_cart.cart_url, "_self");
                    }
                    if ('redirect_checkout_page' === divino_shop_add_to_cart.shop_add_to_cart_action) {
                        window.open(divino_shop_add_to_cart.checkout_url, "_self");
                    }
                }
            }
        }
    });
});
