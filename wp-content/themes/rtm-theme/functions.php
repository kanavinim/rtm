<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
    $content_width = 980; /* pixels */
}

$storefront = (object) array(
    'version'    => $storefront_version,

    /**
     * Initialize all the things.
     */
    'main'       => require 'inc/class-storefront.php',
    'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';
require 'inc/wordpress-shims.php';

if ( class_exists( 'Jetpack' ) ) {
    $storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if ( storefront_is_woocommerce_activated() ) {
    $storefront->woocommerce            = require 'inc/woocommerce/class-storefront-woocommerce.php';
    $storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

    require 'inc/woocommerce/class-storefront-woocommerce-adjacent-products.php';

    require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
    require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
    require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

if ( is_admin() ) {
    $storefront->admin = require 'inc/admin/class-storefront-admin.php';

    require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if ( version_compare( get_bloginfo( 'version' ), '4.7.3', '>=' ) && ( is_admin() || is_customize_preview() ) ) {
    require 'inc/nux/class-storefront-nux-admin.php';
    require 'inc/nux/class-storefront-nux-guided-tour.php';
    require 'inc/nux/class-storefront-nux-starter-content.php';
}
if ( ! function_exists( 'cart_link' ) ) {
    function cart_link() {
        ?>
        <a class="cart-contents" href="/cart/" title="<?php _e( 'Перейти в корзину' ); ?>">
        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        <span class="cart-count"><?php echo sprintf( _n( '(%d)', '(%d)', WC()->cart->cart_contents_count ), WC()->cart->cart_contents_count ); ?></span>
        </a>
        <?php
    }
}

//Ajax Обновление кратких данных из корзины
add_filter('woocommerce_product_add_to_cart_text','my_woocommerce_variable_text_button',10,2);
function my_woocommerce_variable_text_button($text,$product){
if($product->product_type == 'variable'){
$text = 'Подробнее';
}
return $text;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );

function woocommerce_header_add_to_cart_fragment( $fragments ) {

    ?>
    <a class="cart-contents" href="/cart/" title="<?php _e( 'Перейти в корзину' ); ?>">
    <i class="fa fa-shopping-cart" aria-hidden="true"></i><span class="cart-count"><?php echo sprintf( _n( '(%d)', '(%d)', WC()->cart->cart_contents_count ), WC()->cart->cart_contents_count ); ?></span>
    </a>
    <?php
    $fragments['a.cart-contents'] = ob_get_clean();
    return $fragments;
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */


function get_categories_product($categories_list = '') {

    $args = array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => false,
                    'parent'   => 0
            );
    $product_cat = get_terms( $args );

    foreach ($product_cat as $parent_product_cat)
    {

    echo '
            <ul>
                <li><a href="'.get_term_link($parent_product_cat->term_id).'">'.$parent_product_cat->name.'</a>
                <ul>
                    ';
    $child_args = array(
                            'taxonomy' => 'product_cat',
                            'hide_empty' => false,
                            'parent'   => $parent_product_cat->term_id
                    );
    $child_product_cats = get_terms( $child_args );
    foreach ($child_product_cats as $child_product_cat)
    {
        echo '<li><a href="'.get_term_link($child_product_cat->term_id).'">'.$child_product_cat->name.'</a></li>';
    }

    echo '</ul>
            </li>
        </ul>';
    }}


    //Текст, который будет вместо цены
function product_price_replacement(){
    return '<span class="woocommerce-Price-amount amount">Бесплатно да</span>';
}

// Замена цены на текст
add_filter( 'woocommerce_get_price_html', 'filter_get_price_html_callback', 10, 2 );
function filter_get_price_html_callback( $price, $product ){
    if(( $product->get_price()== 0 )) {
        $price = product_price_replacement();
    }
    return $price;

}

add_filter("woocommerce_checkout_fields", "sort_fields_billing");
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
function sort_fields_billing($fields) {

    $fields["billing"]["billing_first_name"]["priority"] = 1;
    $fields["billing"]["billing_last_name"]["priority"] = 2;
    $fields["billing"]["billing_company"]["priority"] = 3;
    $fields["billing"]["billing_postcode"]["priority"] = 4;
    $fields["billing"]["billing_country"]["priority"] = 5;
    $fields["billing"]["billing_state"]["priority"] = 6;
    $fields["billing"]["billing_city"]["priority"] = 7;
    $fields["billing"]["billing_address_1"]["priority"] = 8;
    $fields["billing"]["billing_address_2"]["priority"] = 9;
    $fields["billing"]["billing_phone"]["priority"] = 10;
    $fields["billing"]["billing_email"]["priority"] = 11;
    $mobile = isMobile();
    if(!$mobile){
        $fields['billing']['billing_postcode']['class'] = array('form-row-first');
        $fields['billing']['billing_country']['class'] = array('form-row-last');
        $fields['billing']['billing_state']['class'] = array('form-row-first');
        $fields['billing']['billing_city']['class'] = array('form-row-last');
        $fields["billing"]["billing_phone"]["class"] = array('form-row-first');
    	$fields["billing"]["billing_email"]["class"] = array('form-row-last');
    }
    else{
        $fields["billing"]["billing_first_name"]["class"] = array('form-row-wide');
        $fields["billing"]["billing_last_name"]["class"] = array('form-row-wide');
        $fields["billing"]["billing_email"]["class"] = array('form-row-wide');

    }

    return $fields;

}
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );


function awoohc_add_update_form_billing( $fragments ) {

    $checkout = WC()->checkout();

  //  parse_str( $_POST['post_data'], $fields_values );
  
//echo "<pre>"; print_r($_POST) ;echo "</pre>"; 
    ob_start();

    echo '<div class="woocommerce-billing-fields__field-wrapper">';

    $fields = $checkout->get_checkout_fields( 'billing' );

    foreach ( $fields as $key => $field ) {
        $value = $checkout->get_value( $key );

        if ( isset( $field['country_field'], $fields[ $field['country_field'] ] ) ) {
            $field['country'] = $checkout->get_value( $field['country_field'] );
        }

        if ( ! $value && ! empty( $fields_values[ $key ] ) ) {
            $value = $fields_values[ $key ];
        }

        woocommerce_form_field( $key, $field, $value );
    }

    echo '</div>';

    $fragments['.woocommerce-billing-fields__field-wrapper'] = ob_get_clean();

    return $fragments;
}

add_filter( 'woocommerce_checkout_fields', 'awoohc_add_update_form_billing', 99, 99 );

function awoohc_override_checkout_fields( $fields ) {

$fields['billing']['billing_email']['label'] = "Электропочта";


    return $fields;
}

add_filter( 'woocommerce_checkout_fields', 'awoohc_override_checkout_fields' );


function awoohc_add_script_update_shipping_method() {

    if ( is_checkout() ) {
        ?>


        <!--Выполняем обновление полей при переключении доставки-->
        <script>
              jQuery( document ).ready( function( $ ) {
                  $( document.body ).on( 'updated_checkout updated_shipping_method', function( event, xhr, data ) {
                      $( 'input[name^="shipping_method"]' ).on( 'change', function() {
                          $( '.woocommerce-billing-fields__field-wrapper' ).block( {
                              message: null,
                              overlayCSS: {
                                  background: '#fff',
                                  'z-index': 1000000,
                                  opacity: 0.3
                              }
                          } );
                      } );
                  } );
              } );
        </script>
        <?php
    }
}

add_action( 'wp_footer', 'awoohc_add_script_update_shipping_method' );

add_filter( 'woocommerce_checkout_fields', 'your_require_wc_phone_field', 99, 99);

function your_require_wc_phone_field( $fields ) {
    $fields['billing']['billing_email']['label'] = "Электропочта";
    $fields['billing']['billing_company']['label'] = "Компания";
    $fields['billing']['billing_address_1']['label'] = "Адрес доставки";
    $fields['billing']['billing_address_2']['label'] = "Адрес доставки";



    	// получаем выбранные методы доставки.
    $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

    // проверяем текущий метод и убираем не ненужные поля.
    if ( false !== strpos( $chosen_methods[0], 'free_shipping:1' ) ) {
            $fields['billing']['billing_city']['required'] = false;
            $fields['billing']['billing_state']['required'] = false;
            $fields['billing']['billing_address_1']['required'] = false;
            $fields['billing']['billing_address_2']['required'] = false;
            $fields['billing']['billing_postcode']['required'] = false;
            $fields['billing']['billing_country']['required'] = false;
            unset($fields['billing']['billing_address_2']);
            unset($fields['billing']['billing_postcode']);
            
            

            unset($fields['billing']['billing_city']);
            unset($fields['billing']['billing_state']);
            unset($fields['billing']['billing_address_1']);

    }
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_2']);

    return $fields;
}


add_filter( 'woocommerce_checkout_fields', 'your_require_wc_phone_field_moscow', 999, 999);
function your_require_wc_phone_field_moscow( $fields ) {


    	// получаем выбранные методы доставки.
    $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

    // проверяем текущий метод и убираем не ненужные поля.
    if ( false !== strpos( $chosen_methods[0], 'flat_rate:18' ) ) {
            $fields['billing']['billing_city']['required'] = false;
            $fields['billing']['billing_state']['required'] = false;
            $fields['billing']['billing_address_1']['required'] = true;
            unset($fields['billing']['billing_postcode']);

            unset($fields['billing']['billing_city']);
            unset($fields['billing']['billing_state']);


    }
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_state']);
    if(false !== strpos( $chosen_methods[0], 'flat_rate:20')) {
        if(!isMobile()){
            $fields['billing']['billing_company']['class'] = array('form-row-first');
        $fields['billing']['billing_postcode']['class'] = array('form-row-last');
        }else{
            $fields["billing"]["billing_company"]["class"] = array('form-row-wide');
            $fields["billing"]["billing_postcode"]["class"] = array('form-row-wide');
        }

    }

    return $fields;
}

add_filter( 'woocommerce_min_password_strength', 'example_woocommerce_min_password_strength' );
function example_woocommerce_min_password_strength( $strength ) {
    return 2;
}
add_action( 'woocommerce_email_after_order_table', 'wc_add_payment_type_to_emails', 15, 2 );
function wc_add_payment_type_to_emails( $order, $is_admin_email ) {
echo '<p><strong>Оплата:</strong> ' . $order->payment_method_title . '</p>';

}
// попытка в совместимость
add_action('woocommerce_product_data_tabs','new_tab');
function new_tab($tab){
$tab['new_tab']=array('label'=>"Совместимость",'target'=>"new_tab");
return $tab;
}

/**
 * RTM: variation dropdown args — color order + hide empty placeholder when default selected.
 * Color sequence: black, olive, khaki/hakki, banana; then red; then others.
 * Prefer this over CSS nth-child reordering of THWVS swatches.
 */
function rtm_color_sort_rank( $label ) {
	$l = mb_strtolower( trim( wp_strip_all_tags( (string) $label ) ), 'UTF-8' );
	$l = str_replace( array( 'ё', 'é' ), array( 'е', 'e' ), $l );
	$rules = array(
		10  => array( 'black', 'черный', 'чёрный' ),
		20  => array( 'olive', 'олива', 'олив' ),
		30  => array( 'hakki', 'khaki', 'хаки' ),
		40  => array( 'banana', 'бананов', 'банан' ),
		50  => array( 'red', 'красный' ),
	);
	foreach ( $rules as $rank => $needles ) {
		foreach ( $needles as $needle ) {
			if ( $l === $needle || mb_strpos( $l, $needle, 0, 'UTF-8' ) === 0 || mb_strpos( $l, $needle, 0, 'UTF-8' ) !== false ) {
				return $rank;
			}
		}
	}
	return 1000;
}

function rtm_is_color_attribute( $attribute ) {
	$attr = (string) $attribute;
	$attr_l = mb_strtolower( $attr, 'UTF-8' );
	return ( $attr === 'pa_color' || $attr_l === 'цвет' || false !== strpos( $attr_l, 'color' ) || false !== strpos( $attr_l, 'цвет' ) );
}

function rtm_sort_color_options( $options ) {
	if ( empty( $options ) || ! is_array( $options ) ) {
		return $options;
	}
	$indexed = array();
	$i = 0;
	foreach ( $options as $key => $value ) {
		$label = is_string( $key ) && ! is_numeric( $key ) ? $key : $value;
		// Taxonomy terms may be slugs as values with labels elsewhere; use value for custom attrs.
		$rank_source = is_string( $value ) ? $value : (string) $label;
		// For pa_color, $options is list of term slugs/names.
		$indexed[] = array(
			'key'   => $key,
			'value' => $value,
			'rank'  => rtm_color_sort_rank( $rank_source ),
			'idx'   => $i++,
		);
	}
	usort(
		$indexed,
		function( $a, $b ) {
			if ( $a['rank'] === $b['rank'] ) {
				return $a['idx'] - $b['idx'];
			}
			return $a['rank'] - $b['rank'];
		}
	);
	$sorted = array();
	$is_list = array_keys( $options ) === range( 0, count( $options ) - 1 );
	foreach ( $indexed as $row ) {
		if ( $is_list ) {
			$sorted[] = $row['value'];
		} else {
			$sorted[ $row['key'] ] = $row['value'];
		}
	}
	return $sorted;
}

add_filter( 'woocommerce_dropdown_variation_attribute_options_args', 'rtm_variation_dropdown_args', 20 );
function rtm_variation_dropdown_args( $args ) {
	if ( ! empty( $args['selected'] ) ) {
		// Hide "Выбрать опцию" when a default is already selected (e.g. ЦПР-1 quantity).
		$args['show_option_none'] = '';
	}
	if ( ! empty( $args['attribute'] ) && rtm_is_color_attribute( $args['attribute'] ) && ! empty( $args['options'] ) && is_array( $args['options'] ) ) {
		$args['options'] = rtm_sort_color_options( $args['options'] );
	}
	return $args;
}

/**
 * THWVS always injects an empty <option value="">…</option>. Strip it when a default is selected.
 */
add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'rtm_variation_dropdown_html', 110, 2 );
function rtm_variation_dropdown_html( $html, $args ) {
	if ( ! empty( $args['selected'] ) ) {
		$html = preg_replace( '/<option value="">.*?<\/option>/u', '', $html, 1 );
	}
	return $html;
}

/**
 * Stock text: only "в наличии" / "не в наличии" (no quantity, no emoji — emoji removed via CSS).
 */
add_filter( 'woocommerce_get_availability_text', 'rtm_availability_text', 60, 2 );
function rtm_availability_text( $availability, $product ) {
	if ( ! $product ) {
		return $availability;
	}
	if ( $product->is_in_stock() ) {
		return 'в наличии';
	}
	return 'не в наличии';
}

add_filter( 'woocommerce_get_stock_html', 'rtm_stock_html_class_cleanup', 20, 2 );
function rtm_stock_html_class_cleanup( $html, $product ) {
	// Keep WC markup/classes; text already filtered above.
	return $html;
}


