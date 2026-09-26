const wp_bottom_menu_class = document.querySelector('.wp-bottom-menu');
const wp_bottom_menu_sfw =  document.querySelector('.wp-bottom-menu-search-form-wrapper');
const wp_bottom_menu_sft = document.querySelector(".wp-bottom-menu-search-form-trigger");
var wpbmsf = false;

if ( wp_bottom_menu_sft !== null ){
    wp_bottom_menu_sft.addEventListener("click" ,function(){
        wp_bottom_menu_sfw.classList.toggle("sf-active");
        wp_bottom_menu_class.classList.toggle("sf-active");
        if (!wpbmsf){
            wp_bottom_menu_sfw.style.bottom = wp_bottom_menu_class.clientHeight + "px";
            wpbmsf = true;
        } else {
            wp_bottom_menu_sfw.style.bottom = "0px";
            wpbmsf = false;
        }
    });
}

window.addEventListener('DOMContentLoaded', (event) => {
    document.body.style.paddingBottom = wp_bottom_menu_class.clientHeight + "px";
});

// Nav trigger
const wp_bottom_menu_nav_wrapper = document.querySelector(".wp-bottom-menu-nav-wrapper");
const wp_bottom_menu_nav_trigger = document.querySelector(".wp-bottom-menu-nav-trigger");
const wp_bottom_menu_nav_close = document.querySelector(".wpbm-nav-close");
var wpbm_nav = false;

if ( wp_bottom_menu_nav_trigger !== null ){
    wp_bottom_menu_nav_trigger.addEventListener("click" ,function(){
        wp_bottom_menu_nav_wrapper.classList.toggle("active");
    });
}

if ( wp_bottom_menu_nav_close !== null ){
    wp_bottom_menu_nav_close.addEventListener("click" ,function(){
        wp_bottom_menu_nav_wrapper.classList.toggle("active");
    });
}