<?php
/**
 * Single variation cart button
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

global $product;
global $count;
?>
<div class="woocommerce-variation-add-to-cart variations_button">
    <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

    <?php
    do_action( 'woocommerce_before_add_to_cart_quantity' );

    woocommerce_quantity_input(
        array(
            'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
            'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
            'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
        )
    );

    do_action( 'woocommerce_after_add_to_cart_quantity' );
    ?>
    <script>

        $(document).on('change', '.thwvsf_fields', function(){
            var e = document.querySelectorAll('.variation_id')[0];
            switch(e.value){
               case '129': case '124': case '143': case '115': case '277': case '948': { //yellow
                   document.querySelectorAll('.single_add_to_cart_button')[0].style.cssText = "border-color: rgb(40, 40, 40) !important;background-color: rgb(245 225 77) !important;color: rgb(40, 40, 40) !important;";
                   break;
               }
               default:
                   document.querySelectorAll('.single_add_to_cart_button')[0].style.cssText = "border-color: #282828 !important; background-color: #fff !important;color: #282828 !important;";
                   break;
               }
        });
        $(document).on('click','.thwvsf-color-li', function(){
           var e = document.querySelectorAll('.variation_id')[0];
           switch(e.value){
               case '938': case '968': case '946': case '955': case '950': case '984': { //olive
                    document.querySelectorAll('.single_add_to_cart_button')[0].style.cssText = "border-color: #282828 !important; background-color: #998e6a !important;color: #fff !important;";
                    break;
               }
               case '959': { //silver
                    document.querySelectorAll('.single_add_to_cart_button')[0].style.cssText = "border-color: #282828 !important; background-color: #858383 !important;color: #fff !important;";
                    break;
               }
               case '935': case'967': case '945': case '953': case '949': case '983': { //khaki
                   document.querySelectorAll('.single_add_to_cart_button')[0].style.cssText = "border-color: #282828 !important; background-color: #848e75 !important;color: #fff !important;";
                   break;
               }
               case '933': case '966': case '947': case '956': case '977': case '962': case '948': case '936': case '1027': { //yellow
                   document.querySelectorAll('.single_add_to_cart_button')[0].style.cssText = "border-color: rgb(40, 40, 40) !important;background-color: rgb(245 225 77) !important;color: rgb(40, 40, 40) !important;";
                   break;
               }
               case '965': case '164': { //red
                   document.querySelectorAll('.single_add_to_cart_button')[0].style.cssText = "border-color: #282828  !important; background-color: #e92f2f  !important;color: #fff !important;";
                   break;
               }
               default:
                    document.querySelectorAll('.single_add_to_cart_button')[0].style.cssText = "border-color: #282828 !important; background-color: #fff !important;color: #282828 !important;";
                   break;
           }
        });

        //alert();
        //$('li[title=""]').css("order", "1");
    </script>
    <button type="submit" style="" class="single_add_to_cart_button button alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

    <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

    <input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
    <input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
    <input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>
