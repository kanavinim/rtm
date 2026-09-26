
<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>


<div class="left_block sticky-sidebar col-md-3 mobile-catalog">
      <nav class="accordion arrows">
    <input type="radio" name="accordion" id="cb1" />
    <section class="box">
      <label class="box-title" for="cb1">Рукоятки</label>
      <label class="box-close" for="acc-close"></label>
      <div onclick="window.open('/product-category/rukojatki/ugol#content-place','_self')" class="box-content">Угловые</div>
      <div onclick="window.open('/product-category/rukojatki/vertical#content-place','_self')" class="box-content">Вертикальные</div>
    </section>
    <input type="radio" name="accordion" id="cb2" />
    <section onclick="window.open('/product-category/usm#content-place','_self')" class="box">
      <label class="box-title" for="cb2">УСМ</label>
      <label class="box-close" for="acc-close"></label>
    </section>
    <input type="radio" name="accordion" id="cb3" />
    <section onclick="window.open('/product-category/antub#content-place','_self')" class="box">
      <label class="box-title" for="cb3">Антабки</label>
      <label class="box-close" for="acc-close"></label>
    </section>
    <input type="radio" name="accordion" id="cb4" />
    <section onclick="window.open('/product-category/tools#content-place','_self')" class="box">
      <label class="box-title" for="cb4">Инструмент</label>
      <label class="box-close" for="acc-close"></label>
    </section>
    <input type="radio" name="accordion" id="cb5" />
    <section onclick="window.open('/product-category/pills#content-place','_self')" class="box">
      <label class="box-title" for="cb5">Таблетки</label>
      <label class="box-close" for="acc-close"></label>
    </section>
    <input type="radio" name="accordion" id="cb6" />
    <section class="box">
      <label class="box-title" for="cb6">Мерч</label>
      <label class="box-close" for="acc-close"></label>
      <div onclick="window.open('/product-category/mertch/patch#content-place','_self')" class="box-content">Патчи</div>
      <div onclick="window.open('/product-category/mertch/sticks/#content-place','_self')" class="box-content">Наклейки</div>
    </section>
    <input type="radio" name="accordion" id="cb7" />
    <section onclick="window.open('/product-category/kronshtains#content-place','_self')" class="box">
      <label class="box-title" for="cb7">Кронштейны</label>
      <label class="box-close" for="acc-close"></label>
    </section>
    <input type="radio" name="accordion" id="cb8" />
    <section onclick="window.open('/product-category/prikladi#content-place','_self')" class="box">
      <label class="box-title" for="cb8">Приклады</label>
      <label class="box-close" for="acc-close"></label>
    </section>
    <input type="radio" name="accordion" id="cb9" />
    <section onclick="window.open('/product-category/sight#content-place','_self')" class="box">
      <label class="box-title" for="cb9">Прицелы</label>
      <label class="box-close" for="acc-close"></label>
    </section>
    <input type="radio" name="accordion" id="cb10" />
    <section onclick="window.open('/product-category/others#content-place','_self')" class="box">
      <label class="box-title" for="cb10">Прочее</label>
      <label class="box-close" for="acc-close"></label>
    </section>


    <input type="radio" name="accordion" id="acc-close" />
  </nav>

</div>
<div id="content-place" class="inner">

<?php if ( apply_filters( 'woocommerce_show_page_title', true )) : ?>
		<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
<?php endif; ?>
</div>
<div class="inner" style="display:flex;">

<div  class="left_block sticky-sidebar col-md-2 desktop">
      <?php echo get_categories_product(); ?>
</div>

<script>
    var rukojatki = document.querySelectorAll(".left_block > ul:nth-child(1) > li:nth-child(1)")[0];
    var sub_category_rukojatki = document.querySelectorAll(".left_block > ul:nth-child(1) > li:nth-child(1) > ul > li")[0];
    var sub_category_rukojatki1 = document.querySelectorAll(".left_block > ul:nth-child(1) > li:nth-child(1) > ul > li")[1];
    var sub_category_merch = document.querySelectorAll(".left_block > ul:nth-child(10) > li:nth-child(1) > ul > li")[0];
    var sub_category_merch1 = document.querySelectorAll(".left_block > ul:nth-child(10) > li:nth-child(1) > ul > li")[1];
    const points = document.querySelectorAll(".left_block ul li a");
    for(let point of points){
     if(point.href == document.location){
      point.style.borderLeft = "2.5px solid #b8b8b8";
      point.style.paddingLeft = "5px";
      if(point.href == "https://rtm-a.ru/product-category/rukojatki/" || point.href == "https://rtm-a.ru/product-category/rukojatki/vertical/" || point.href == "https://rtm-a.ru/product-category/rukojatki/ugol/"){
        sub_category_rukojatki.style.display = "block";
        sub_category_rukojatki1.style.display = "block";
      }
      if(point.href == "https://rtm-a.ru/product-category/mertch/" || point.href == "https://rtm-a.ru/product-category/mertch/sticks/" || point.href == "https://rtm-a.ru/product-category/mertch/patch/"){
        sub_category_merch.style.display = "block";
        sub_category_merch1.style.display = "block";
      }

     }
    }
</script>


<div  class="col-md-10 mobile-product-loop" style="margin-left: 40px;">
<?php
if ( woocommerce_product_loop() ) {

    /**
     * Hook: woocommerce_before_shop_loop.
     *
     * @hooked woocommerce_output_all_notices - 10
     * @hooked woocommerce_result_count - 20
     * @hooked woocommerce_catalog_ordering - 30
     */
    do_action( 'woocommerce_before_shop_loop' );

    woocommerce_product_loop_start();

    if ( wc_get_loop_prop( 'total' ) ) {
        while ( have_posts() ) {
            the_post();

            /**
             * Hook: woocommerce_shop_loop.
             */
            do_action( 'woocommerce_shop_loop' );

            wc_get_template_part( 'content', 'product' );
        }
    }

    woocommerce_product_loop_end();

    /**
     * Hook: woocommerce_after_shop_loop.
     *
     * @hooked woocommerce_pagination - 10
     */
    do_action( 'woocommerce_after_shop_loop' );
} else {
    /**
     * Hook: woocommerce_no_products_found.
     *
     * @hooked wc_no_products_found - 10
     */
    do_action( 'woocommerce_no_products_found' );
}


?>


</div>
</div>
    <?php get_footer('shop'); ?>