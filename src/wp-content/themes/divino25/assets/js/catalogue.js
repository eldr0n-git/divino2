/* CATALOGUE VIEW SWITCHING: LIST VIEW or GRID VIEW */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelector(".view__switch--list").addEventListener("click", function(e) {
        e.preventDefault();
        const catalogue = document.querySelector(".dv-collection");
        if ( catalogue ){
            catalogue.classList.remove("grid");
            catalogue.classList.add("list");
            this.classList.add("active");
            document.querySelector(".view__switch--grid").classList.remove("active");
        }  
    });
    document.querySelector(".view__switch--grid").addEventListener("click", function(e) {
        e.preventDefault();
        const catalogue = document.querySelector(".dv-collection");
        if ( catalogue ){
            catalogue.classList.remove("list");
            catalogue.classList.add("grid");
            this.classList.add("active");
            document.querySelector(".view__switch--list").classList.remove("active");
        }  
    });
});
