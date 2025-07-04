(function () {

    // Triggers sticky add to cart on scroll.
    const divinoStickyAddToCart = document.querySelector(".ast-sticky-add-to-cart");

    if (divinoStickyAddToCart) {
        const scrollOffset = document.querySelector('.product .single_add_to_cart_button').offsetTop;
        window.addEventListener("scroll", function () {
            if (window.scrollY >= scrollOffset) {
                divinoStickyAddToCart.classList.add('is-active');
            } else {
                divinoStickyAddToCart.classList.remove('is-active');
            }
        })
    }

    // Smooth scrolls if select option button is active.
    const divinoSmoothScrollBtn = document.querySelector(".ast-sticky-add-to-cart-action-wrap .single_link_to_cart_button");
    const element = document.querySelector(".single_add_to_cart_button");

    if (divinoSmoothScrollBtn && element) {
        const headerOffset = 230;
        const elementPosition = document.querySelector('.single_add_to_cart_button').offsetTop;
        if (elementPosition) {
            const offsetPosition = elementPosition - headerOffset;

            if (offsetPosition) {
                divinoSmoothScrollBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: "smooth"
                    });
                });
            }
        }
    }

})();
