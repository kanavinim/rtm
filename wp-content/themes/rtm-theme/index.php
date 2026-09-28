<?php
/*
Template Name: Main page
*/
?>


<!DOCTYPE html>
<html lang="ru-ru">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <base href="<?php echo esc_url( home_url( '/' ) ); ?>" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="Тюнинг для огнестрельного оружия, рукоятки, антабки, приклады, прицелы, усм" />
        <meta name="rights" content="РТМ" />
        <meta name="author" content="Super User" />
        <meta name="description" content="Разработка и производство оружейной продукции" />
        <meta name="yandex-verification" content="c2a705e3cb645ed2" />
        <meta name="twitter:card" content="summary_large_image">
        <meta property="og:type" content="website">
        <meta property="og:url" content="/">
        <meta property="og:title" content="РТМ - оружейная компания">
        <meta property="og:description" content="Разработка и производство оружейной продукции">
        <meta property="og:image" content="/wp-content/themes/rtm-theme/images/og-image-banner.png">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        <link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() . '/css/styles.css?ver=' . filemtime( get_template_directory() . '/css/styles.css' ) ); ?>" />
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;lang=en" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />


        <script src="/wp-content/themes/rtm-theme/js/script.js"></script>
        <script src="/wp-content/themes/rtm-theme/js/slider.js" defer></script>
        <script type="text/javascript" src="/wp-content/themes/rtm-theme/js/jquery.sliderkit.1.9.2.pack.js"></script>
        <script type="text/javascript" src="/wp-content/themes/rtm-theme/js/sliderkit.counter.1.0.pack.js"></script>
        <script type="text/javascript" src="/wp-content/themes/rtm-theme/js/jquery.sudoSlider.min.js"></script>
        <script type="text/javascript" src="/wp-content/themes/rtm-theme/js/jQueryRotate.js"></script>
        <script src="/wp-content/themes/rtm-theme/js/jquery.placeholder.js"></script>



        <? wp_head() ?>
    </head>
    <body <?php body_class();?>
        >

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
                                <span class="phone-number"><a href="tel:89997728529" style="color: rgb(116, 116, 116);">8 (999) 772-85-29</a></span>
                                <div style="display: flex;">
                                    <a href="https://vk.com/rtm_teh" target="_blank" ><img class="icon-top" src="/wp-content/themes/rtm-theme/images/vk.png" alt="РТМ - Оружейная компания"/> </a>
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
                                <span class="phone-number"><a href="tel:78006893509" style="color: rgb(116, 116, 116);">8 (800) 689-35-09</a></span>
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


                    <div class="header-main clearfix inner">


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
       <div class="itc-slider" data-slider="itc-slider" data-loop="true" data-autoplay="true" data-interval="4000" loading="lazy">
            <div class="itc-slider__wrapper">
                <div class="itc-slider__items">
                    <div class="itc-slider__item">
                        <div class="banner-index"></div>
                    </div>

                    <div class="itc-slider__item">
                        <div class="banner-index-one"></div>
                    </div>

                    <div class="itc-slider__item">
                        <div class="banner-index-2"></div>
                    </div>
                    <div class="itc-slider__item">
                        <div class="banner-index-3"></div>
                    </div>
                    <div class="itc-slider__item">
                        <div class="banner-index-4"></div>
                    </div>
                    <div class="itc-slider__item">
                        <div class="banner-index-5"></div>
                    </div>
                    <div class="itc-slider__item">
                        <div class="banner-index-6"></div>
                    </div>
                    <div class="itc-slider__item">
                        <div class="banner-index-7"></div>
                    </div>

                </div>
            </div>
        </div>

        <h1 class="home-title">РТМ – оружейная компания</h1>

<div class="content">
        <div class="categories inner">
            <div class="catalog-column">
                <div>
                    <div  class="title4" ><a href="/product-category/rukojatki/">Рукоятки</a></div>
                    <ul>
                        <li><a href="/product/rpt-osovec-piii/">РПТ Осовец пIII</a></li>
                        <li><a href="/product/rpt-pillau-piii/">РПТ Пиллау пIII</a></li>
                        <li><a href="/product/rpt-brest/">РПТ Брест</a></li>
                        <li><a href="/product/rpt-izborsk/">РПТ Изборск</a></li>
                        <li><a href="/product/rpt-kronshtadt/">РПТ Кронштадт nII</a></li>
                        <li><a href="/product/rpt-narva/">РПТ Нарва</a></li>
                    </ul>
                </div>
                <div>
                    <div  class="title4" ><a href="/product-category/prikladi/">Приклады</a></div>
                    <ul>
                        <li><a href="/product/kontrgayka-priklada/">Контргайка приклада</a></li>
                        <li><a href="/product/atp-ar-15-mil-spec/">АТП AR-15</a></li>
                    </ul>
                </div>
                <div>
                    <div  class="title4"><a href="/product-category/others/">Прочее</a></div>
                    <ul>
	                    <li><a href="/product/nabor-dlya-okraski-svetonakopitelem/">Набор для окраски светонакопителем</a></li>
                        <li><a href="/product/zakladnaya-m-lok-2sht/">Закладная M-LOK</a></li>
                        <li><a href="/product/keymod/">Закладная KeyMod</a></li>

                    </ul>
                </div>

            </div>
            <div class="catalog-column">
                <div>
                    <div  class="title4"><a  href="/product-category/usm/">УСМ</a></div>
                    <ul>
                        <li><a href="/product/pfo-tekker-b-12-pll/">ПФО Теккер-К пII</a></li>
                        <li><a href="/product/pfo-tekker-k-pii/">ПФО Теккер B-12 пII</a></li>
                    </ul>
                </div>
                <div>
                    <div  class="title4"><a href="/product-category/pills/">Таблетки</a></div>
                    <ul>
                        <li><a href="/product/porshen-tabletka-b-12/">Таблетка B-12</a></li>
                        <li><a href="/product/podavatel-tabletka-870/">Таблетка-870</a></li>
                        <li><a href="/product/podavatel-tabletka-870s/">Таблетка-870C</a></li>

                    </ul>
                </div>
                <div>
                    <div  class="title4"><a href="/product-category/sight/">Прицелы</a></div>
                    <ul>
                        <li><a href="/product/smp-pm-1/">СМП ПМ-1</a></li>
                        <li><a href="/product/smp-pm-2/">СМП ПМ-2</a></li>
                    </ul>
                </div>
                <div>
                    <div  class="title4"><a href="/product-category/tools/">Инструменты</a></div>
                    <ul>
                        <li><a href="/product/ios-ekventor-pii/">ИОС Эквентор пII</a></li>
                    </ul>
                </div>

            </div>
            <div class="catalog-column no-margin">
                <div>
                    <div  class="title4"><a href="/product-category/antub/">Антабки</a></div>
                    <ul>
                        <li><a href="/product/afr-apsel/">АФР Апсель</a></li>
                        <li><a href="/product/afr-drek/">АФР Дрек</a></li>
                        <li><a href="/product/afr-rumpel/">АФР Румпель</a></li>
                        <li><a href="/product/afr-bushprit/">АФР Бушприт</a></li>
                        <li><a href="/product/afr-kingston/">АФР Кингстон</a></li>
                        <li><a href="/product/afr-kabestan/">АФР Кабестан</a></li>
                        <li><a href="/product/afr-kliver/">АФР Кливер</a></li>
                        <li><a href="/product/afr-revers/">АФР Реверс</a></li>
                    </ul>
                </div>
                <div>
                    <div  class="title4"><a href="/product-category/kronshtains/">Кронштейны</a></div>
                    <ul>
	                    <li><a href="/product/adatara/">СМА Адатара</a></li>
                        <li><a href="/product/sma-bakanovi-keymod/">СМА Баканови</a></li>
                    </ul>
                </div>
                <div>
                    <div  class="title4"><a href="/product-category/mertch/">Мерч</a></div>
                    <ul>
                        <li><a href="/product/kayton/">Кайтон</a></li>
                        <li><a href="/product/nabor-nakleek-1/">Набор наклеек №1</a></li>
                        <li><a href="/product/nabor-kontrolyorov-1-3/">Набор контролеров</a></li>
                        <li><a href="/product/nakleyka-pillau/">Наклейка Пиллау</a></li>
                        <li><a href="/product/bananovaya-nakleyka/">Банановая наклейка</a></li>
                        <li><a href="/product/pvh-patch-35h50mm-pii/">Патч 30х50 мм пII</a></li>
                        <li><a href="/product/cpr-1-5-sht/">ЦПР-1</a></li>

                    </ul>
                </div>

            </div>
        </div>


    </div><!-- CONTENT -->

<?php get_footer(); ?>
