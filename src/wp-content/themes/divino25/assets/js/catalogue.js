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
