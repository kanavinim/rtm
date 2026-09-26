<!DOCTYPE html>
<html lang="ru-ru">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <base href="<?php echo esc_url( home_url( '/' ) ); ?>" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="rights" content="РТМ" />
        <meta name="author" content="Super User" />        
        <meta name="twitter:card" content="summary_large_image">
        <meta property="og:type" content="website">
        <meta property="og:url" content="/">
        <meta property="og:title" content="РТМ - оружейная компания">      
        <meta property="og:image" content="/wp-content/themes/rtm-theme/images/og-image-banner.png">

        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        <link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/css/styles.css?ver=' . filemtime( get_template_directory() . '/css/styles.css' ) ); ?>" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
        <meta name="yandex-verification" content="c2a705e3cb645ed2" />

        <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
        <script src="/wp-content/themes/rtm-theme/js/script.js"></script>
       
      <!--<//?php
            //if($_SERVER['REQUEST_URI'] == '/news/') {
                // header('Location: https://rtm-a.ru/news/');

            }
        ?>
  -->
        <? wp_head() ?>
    </head>
    <body <?php body_class();?>>

        <?php wp_body_open(); ?>

        <?php do_action( 'storefront_before_site' ); ?>
        <div id="page" class="hfeed site">
            <?php do_action( 'storefront_before_header' ); ?>
            <div class="wrapper">
                <div class="header" style="<?php storefront_header_styles(); ?>">
                    <div class="header-top clearfix desktop">
                        <div class="inner">
                            <a href="/shop/">Каталог</a>

                            <ul>
                                <li><a href="/delivery/" style="padding-left: 0px;">доставка</a></li>
                                <li><a href="/news/">новости</a></li>
                                <li><a href="/contacts/">контакты</a></li>
                                <li><a href="/dealers/">дилеры</a></li>
                            </ul>
                            <div class="header-top-right" style="display: flex; margin-top: 10px;">
                                <span class="phone-number"><a href="tel:89771177499" style="color: rgb(116, 116, 116);">8 (999) 772-85-29</a></span>
                                <div style="display: flex;">
                                    <a href="https://vk.com/rtm_teh" target="_blank"><img class="icon-top" src="/wp-content/themes/rtm-theme/images/vk.png" alt=""/> </a>
                                    <a href="https://t.me/rtm_teh"><img class="icon-top" src="/wp-content/themes/rtm-theme/images/telegra.png" alt="РТМ - Оружейная компания" /></a>
                                </div>
                                <div onclick="window.open('/cart/','_self')" class="cart-header">
                                    <li class="menu-item cart-punkt" style="margin-right: 0px !important;"><?php cart_link(); ?><?php the_widget( 'WC_Widget_Cart', 'title=' ); ?></li>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="menu-items" class="mob-menu-items">
                        <ul>
                            <li><a href="/">Главная</a></li>
                            <li><a href="/shop/">Каталог</a></li>
                            <li><a href="/delivery/">доставка</a></li>
                            <li><a href="/news/">новости</a></li>
                            <li><a href="/contacts/">контакты</a></li>
                            <li><a href="/dealers/">дилеры</a></li>
                        </ul>
                    </div>
                    <div class="header-top clearfix mobile">
                        <div class="inner">
                            <a id="menu-icon" class="mob-menu" onclick="openClose()" aria-label="Меню"><span class="mob-menu-label">Меню</span></a>


                            <div class="header-top-right" style="display: flex;">
                                <span class="phone-number"><a href="tel:89771177499" style="color: rgb(116, 116, 116);">8 (999) 772-85-29</a></span>
                                <div style="display: flex;">
                                    <a href="https://vk.com/rtm_teh" target="_blank" ><img class="icon-top" src="/wp-content/themes/rtm-theme/images/vk.png" alt=""/> </a>
                                    <img class="icon-top" src="/wp-content/themes/rtm-theme/images/telegra.png" alt="" />
                                </div>
                                <div onclick="window.open('/cart/','_self')" class="cart-header">
                                    <li class="menu-item cart-punkt" style="margin-right: 0px !important;"><?php cart_link(); ?><?php the_widget( 'WC_Widget_Cart', 'title=' ); ?></li>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="header-main clearfix inner mobile-logo">


                            <a href="/"><img src="/wp-content/themes/rtm-theme/images/logo.svg" class="logo-img" alt="РТМ - Оружейная компания" /></a>
                            <div style="display: flex;">
                                <div class="search-form-cls">
                                    <?php get_search_form(); ?>
                                </div>

                                <div class="enter">
                                    <?php  /* Панель входа на сайт */
                                        global $user_ID, $user_identity;
                                        wp_get_current_user();
                                        if (!$user_ID):
                                    ?>
                                    <a style="color: #000 !important;" href="/my-account/">войти</a>
                                    <?php
                                        else:
                                    ?>
                                    <a style="color: #000 !important;" href="/my-account/">
                                        <?php _e(' '); ?>


                                            <?php echo $user_identity; ?>

                                    </a>

                                    <?php
                                        endif;
                                    ?>
                                </div>
                            </div>


                    </div>
                </div>
            </div>
        </div>
        <!-- HEADER <p>></p><p style="font-weight: 600;"><?php ?></p> -->
        <div class="inner">
							<?php
if ( function_exists('yoast_breadcrumb') ) {
  yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
}
?>
			<!--<div class="coockies-path">

				<p>Главная</p><p>></p><p style="font-weight: 600;"><//?php single_post_title(); ?></p>
			</div>-->
		
		
		</div>

        <div class="btn-up btn-up_hide">
            <img src="/wp-content/themes/rtm-theme/images/arrow-1.png" style="width: 45px;" />
        </div>
        <script>

        const btnUp = {
            el: document.querySelector('.btn-up'),
            show() {
                // удалим у кнопки класс btn-up_hide
                this.el.classList.remove('btn-up_hide');
            },
            hide() {
                // добавим к кнопке класс btn-up_hide
                this.el.classList.add('btn-up_hide');
            },
            addEventListener() {
                // при прокрутке содержимого страницы
                window.addEventListener('scroll', () => {
                    // определяем величину прокрутки
                    const scrollY = window.scrollY || document.documentElement.scrollTop;
                    // если страница прокручена больше чем на 400px, то делаем кнопку видимой, иначе скрываем
                    scrollY > 400 ? this.show() : this.hide();
                });
                // при нажатии на кнопку .btn-up
                document.querySelector('.btn-up').onclick = () => {
                    // переместим в начало страницы
                    window.scrollTo({
                        top: 0,
                        left: 0,
                        behavior: 'smooth'
                    });
                }
            }
        }

        btnUp.addEventListener();
        </script>