/* CATALOGUE VIEW SWITCHING: LIST VIEW or GRID VIEW */
document.addEventListener('DOMContentLoaded', () => {
    const listButton = document.querySelector(".view__switch--list");
    const gridButton = document.querySelector(".view__switch--grid");
    if ( listButton ){
        listButton.addEventListener("click", function(e) {
            e.preventDefault();
            const catalogue = document.querySelector(".dv-collection");
            if ( catalogue ){
                catalogue.classList.remove("grid");
                catalogue.classList.add("list");
                this.classList.add("active");
                document.querySelector(".view__switch--grid").classList.remove("active");
            }  
        });
    }
    if ( gridButton ) {
        gridButton.addEventListener("click", function(e) {
            e.preventDefault();
            const catalogue = document.querySelector(".dv-collection");
            if ( catalogue ){
                catalogue.classList.remove("list");
                catalogue.classList.add("grid");
                this.classList.add("active");
                document.querySelector(".view__switch--list").classList.remove("active");
            }  
        });
    }
});
document.addEventListener('DOMContentLoaded', function () {
    const searchForms = document.querySelectorAll('.search-form');

    searchForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const input = form.querySelector('input[name="s"]');
            if (input && !input.value.trim()) {
                e.preventDefault(); // отменяем отправку
            }
        });
    });
});

/* ====================================
   BURGER MENU
   ==================================== */
document.addEventListener('DOMContentLoaded', function() {
    const burgerToggle = document.querySelector('.burger-menu-toggle');
    const burgerOverlay = document.getElementById('burgerMenuOverlay');
    const burgerClose = document.getElementById('burgerMenuClose');
    const burgerLinks = document.querySelectorAll('.burger-menu__link');

    function openBurgerMenu() {
        if (burgerToggle) {
            burgerToggle.classList.add('active');
        }
        if (burgerOverlay) {
            burgerOverlay.classList.add('active');
        }
        document.body.classList.add('burger-menu-open');
    }

    function closeBurgerMenu() {
        if (burgerToggle) {
            burgerToggle.classList.remove('active');
        }
        if (burgerOverlay) {
            burgerOverlay.classList.remove('active');
        }
        document.body.classList.remove('burger-menu-open');
    }

    // Toggle menu open/close
    if (burgerToggle) {
        burgerToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            if (burgerOverlay && burgerOverlay.classList.contains('active')) {
                closeBurgerMenu();
            } else {
                openBurgerMenu();
            }
        });
    }

    // Close button
    if (burgerClose) {
        burgerClose.addEventListener('click', function(e) {
            e.stopPropagation();
            closeBurgerMenu();
        });
    }

    // Close on link click
    if (burgerLinks.length > 0) {
        burgerLinks.forEach(function(link, index) {
            link.addEventListener('click', function() {
                closeBurgerMenu();
            });
        });
    }

    // Close on overlay click (outside the menu)
    document.addEventListener('click', function(e) {
        if (burgerOverlay && burgerOverlay.classList.contains('active')) {
            if (!burgerOverlay.contains(e.target) && !burgerToggle.contains(e.target)) {
                closeBurgerMenu();
            }
        }
    });

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && burgerOverlay && burgerOverlay.classList.contains('active')) {
            closeBurgerMenu();
        }
    });

    // Prevent clicks inside overlay from closing it
    if (burgerOverlay) {
        burgerOverlay.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    /* ====================================
       SEARCH TOGGLE (MOBILE)
       ==================================== */
    const searchToggleMobile = document.querySelector('.search-toggle-mobile');
    const searchFormMobile = document.querySelector('.search-form--mobile-fullwidth');

    if (searchToggleMobile && searchFormMobile) {
        searchToggleMobile.addEventListener('click', function(e) {
            e.stopPropagation();
            searchFormMobile.classList.toggle('active');
            this.classList.toggle('active');
        });

        // Close search form when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchFormMobile.contains(e.target) && !searchToggleMobile.contains(e.target)) {
                searchFormMobile.classList.remove('active');
                searchToggleMobile.classList.remove('active');
            }
        });

        // Close search form on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchFormMobile.classList.contains('active')) {
                searchFormMobile.classList.remove('active');
                searchToggleMobile.classList.remove('active');
            }
        });
    }
});
