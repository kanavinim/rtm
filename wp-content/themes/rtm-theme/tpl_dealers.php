<?php
/*
Template Name: dealers page
*/
?>
<?php get_header(); ?>

<div class="inner dealers-btn-box">
    <div>
        <span>Хотите стать дилером? Ничего себе!</span>
        <button class="dealers-btn" onclick="window.open('/dealers-info/', '_self')">Стать дилером</button>
    </div>
</div>

<h1 class="dealers-title">Дилеры</h1>

<div class="tabs">

    <!--
    <input type="radio" name="tab-btn" id="tab-btn-1" value="" checked>
    <label for="tab-btn-1">Интернет-магазины</label>
    -->
    <input type="radio" name="tab-btn" id="tab-btn-2" value="" checked>
    <label for="tab-btn-2">По всей России</label>
    <input type="radio" name="tab-btn" id="tab-btn-3" value="">
    <label for="tab-btn-3">Москва</label>
    <input type="radio" name="tab-btn" id="tab-btn-4" value="">
    <label for="tab-btn-4">Московская область</label>
    <input type="radio" name="tab-btn" id="tab-btn-5" value="">
    <label for="tab-btn-5">Санкт-Петербург</label>
    <input type="radio" name="tab-btn" id="tab-btn-6" value="">
    <label for="tab-btn-6">Казахстан</label>

    <div id="content-2">
        <div class="inner">
            <div class="row dealers-grid">
                <div class="col-md-6 pad-15">
                    <a href="https://bazatactical.ru/proizvoditeli/rtm-russkaya-takticheskaya-manufaktura" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/4.png" alt="База Тактикал" class="dealers-img" />
                        <div class="vertical-center">
                            <span>База Тактикал</span>
                            <p>г. Санкт-Петербург, Поэтический бульвар, 4, этаж 4</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://line-f.ru/shop/category/magaziny/?brand=РТМ" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/line-logo.png" alt="Линия Огня" class="dealers-img dealers-line" />
                        <div class="vertical-center">
                            <span>Линия Огня</span>
                            <p>г. Санкт-Петербург, Средний пр. В.О.,85</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://voentorg.ru/brands/rtm/" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/8.png" alt="Второй фронт" class="dealers-img" />
                        <div class="vertical-center">
                            <span>Второй фронт</span>
                            <p>г. Москва, ул. Кржижановского, 4, корпус 2</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://rusdefense.ru/rtm" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/defens-logo.png" alt="RusDefense" class="dealers-img" />
                        <div class="vertical-center">
                            <span>RusDefense</span>
                            <p>г. Мытищи, ул. Новослабодская, вл. 1, стр. 1</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://ivantactical.ru/?s=ртм&post_type=product" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/2.png" alt="Медведь Иван" class="dealers-img" />
                        <div class="vertical-center">
                            <span>Медведь Иван</span>
                            <p>г. Орел, ул. Латышских стрелков, 52.</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://custom-guns.ru/" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/9.png" alt="Custom Guns" class="dealers-img" />
                        <div class="vertical-center">
                            <span>Custom Guns</span>
                            <p>г. Санкт-Петербург, Полюстровский проспект, дом 32К</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="http://ohot-club.ru/" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/7.png" alt="Охотничий клуб" class="dealers-img" />
                        <div class="vertical-center">
                            <span>Охотничий клуб</span>
                            <p>г. Реутов, ул. Победы, 31а</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://www.pro-shooter.ru/collection/rtm" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/psh-logo.png" alt="Pro-Shooter" class="dealers-img" />
                        <div class="vertical-center">
                            <span>Pro-Shooter</span>
                            <p>г. Москва, Симферопольский бульвар, д. 4</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://allmulticam.ru/collection/Оружейная-компания-РТМ" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/all-logo.png" alt="Allmulticam" class="dealers-img" />
                        <div class="vertical-center">
                            <span>Allmulticam</span>
                            <p>г. Москва, ул. Коцюбинского, д. 4, подъезд 3, офис 144</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://www.shooter-man.ru/search?lang=ru&q=ртм" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/6.png" alt="Shooter man" class="dealers-img" />
                        <div class="vertical-center">
                            <span>Shooter man</span>
                            <p>г. Москва, ул.Шарикоподшипниковская, 13, стр.65, этаж 2, офис 6</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://www.angryman.ru/collection/all/rtm" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/10.png" alt="Angry Man" class="dealers-img" />
                        <div class="vertical-center">
                            <span>Angry Man</span>
                            <p>г.Коломна, ул. Октябрьской революции 354А, 6 этаж</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://www.tdrussia.com/" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/1.png" alt="Тактические решения" class="dealers-img" />
                        <div class="vertical-center">
                            <span>Тактические решения</span>
                            <p>Интернет-магазин доставка по всей России</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://ishooter.ru/manufacturer/rtm-oruzheinaya-kompaniya/" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/5.png" alt="iShooter" class="dealers-img" />
                        <div class="vertical-center">
                            <span>iShooter</span>
                            <p>Интернет-магазин доставка по всей России</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://academygear.ru/" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/12.png" alt="Академия снаряжения" class="dealers-img" />
                        <div class="vertical-center">
                            <span>Академия снаряжения</span>
                            <p>Интернет-магазин доставка по всей России</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://orengun.su/" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/13.png" alt="ORENGUN" class="dealers-img" />
                        <div class="vertical-center">
                            <span>ORENGUN</span>
                            <p>г. Оренбург: пр. Парковый 11, ул. Мира, 3/1</p>
                            <p>г. Самара, ул. Гагарина 2</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://camozon.ru/" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/14.png" alt="CAMOZON" class="dealers-img" />
                        <div class="vertical-center">
                            <span>CAMOZON</span>
                            <p>г. Москва, ул. Смольная, 63-б корпус «Водный мир» 2-ой этаж, павильоны H-9/11/15</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://50bmg.ru/" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/15.png" alt="50BMG" class="dealers-img" />
                        <div class="vertical-center">
                            <span>50BMG</span>
                            <p>г. Москва, ул. Константинова, д. 16, офис 102</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div id="content-3">
        <div class="inner">
            <div class="row dealers-grid">
                <div class="col-md-6 pad-15">
                    <a href="https://voentorg.ru/brands/rtm/" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/8.png" alt="Второй фронт" class="dealers-img" />
                        <div class="vertical-center">
                            <span>Второй фронт</span>
                            <p>г. Москва, ул. Кржижановского, 4, корпус 2</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://www.shooter-man.ru/search?lang=ru&q=ртм" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/6.png" alt="Shooter man" class="dealers-img" />
                        <div class="vertical-center">
                            <span>Shooter man</span>
                            <p>г. Москва, ул.Шарикоподшипниковская, 13, стр.65, этаж 2, офис 6</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://www.pro-shooter.ru/collection/rtm" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/psh-logo.png" alt="Pro-Shooter" class="dealers-img" />
                        <div class="vertical-center">
                            <span>Pro-Shooter</span>
                            <p>г. Москва, Симферопольский бульвар, д. 4</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://allmulticam.ru/collection/Оружейная-компания-РТМ" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/all-logo.png" alt="Allmulticam" class="dealers-img" />
                        <div class="vertical-center">
                            <span>Allmulticam</span>
                            <p>г. Москва, ул. Коцюбинского, д. 4, подъезд 3, офис 144</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://camozon.ru/" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/14.png" alt="CAMOZON" class="dealers-img" />
                        <div class="vertical-center">
                            <span>CAMOZON</span>
                            <p>г. Москва, ул. Смольная, 63-б корпус «Водный мир» 2-ой этаж, павильоны H-9/11/15</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://50bmg.ru/" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/15.png" alt="50BMG" class="dealers-img" />
                        <div class="vertical-center">
                            <span>50BMG</span>
                            <p>г. Москва, ул. Константинова, д. 16, офис 102</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="dealers-map">
                <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3Ad9e96b9d3ada7f97afeeb9bda480af5fd86e87e3d6f1fef500e2c0fbe0b00b20&amp;source=constructor" width="100%" height="565" frameborder="0" title="Карта дилеров — Москва"></iframe>
            </div>
        </div>
    </div>

    <div id="content-4">
        <div class="inner">
            <div class="row dealers-grid">
                <div class="col-md-6 pad-15">
                    <a href="http://ohot-club.ru/" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/7.png" alt="Охотничий клуб" class="dealers-img" />
                        <div class="vertical-center">
                            <span>Охотничий клуб</span>
                            <p>г. Реутов, ул. Победы, 31а</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://rusdefense.ru/rtm" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/defens-logo.png" alt="RusDefense" class="dealers-img" />
                        <div class="vertical-center">
                            <span>RusDefense</span>
                            <p>г. Мытищи, ул. Новослабодская, вл. 1, стр. 1</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="dealers-map">
                <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A5f709e2b4c43411c1b6a7e4a45fb6183ab40ddef98409ef389063ae051906570&amp;source=constructor" width="100%" height="565" frameborder="0" title="Карта дилеров — Московская область"></iframe>
            </div>
        </div>
    </div>

    <div id="content-5">
        <div class="inner">
            <div class="row dealers-grid">
                <div class="col-md-6 pad-15">
                    <a href="https://bazatactical.ru/proizvoditeli/rtm-russkaya-takticheskaya-manufaktura" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/4.png" alt="База Тактикал" class="dealers-img" />
                        <div class="vertical-center">
                            <span>База Тактикал</span>
                            <p>г. Санкт-Петербург, Поэтический бульвар, 4, этаж 4</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://custom-guns.ru/" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/9.png" alt="Custom Guns" class="dealers-img" />
                        <div class="vertical-center">
                            <span>Custom Guns</span>
                            <p>г. Санкт-Петербург, Полюстровский проспект, дом 32К</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 pad-15">
                    <a href="https://line-f.ru/shop/category/magaziny/?brand=РТМ" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/line-logo.png" alt="Линия Огня" class="dealers-img dealers-line" />
                        <div class="vertical-center">
                            <span>Линия Огня</span>
                            <p>г. Санкт-Петербург, Средний пр. В.О.,85</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="dealers-map">
                <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A08d28c85767b3e9553f9d3a553ca2ebb75c0c1aa3568439f4f3ee5a9ac2573cd&amp;source=constructor" width="100%" height="565" frameborder="0" title="Карта дилеров — Санкт-Петербург"></iframe>
            </div>
        </div>
    </div>

    <div id="content-6">
        <div class="inner">
            <div class="row dealers-grid">
                <div class="col-md-6 pad-15">
                    <a href="https://t.me/+uVr6x_f3N-k0NDBi" target="_blank">
                        <img src="/wp-content/themes/rtm-theme/images/tinatuning.jpg" alt="ТИНА ТЮНИНГ" class="dealers-img" />
                        <div class="vertical-center">
                            <span>ТИНА ТЮНИНГ</span>
                            <p>Республика Казахстан</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="dealers-map">
                <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A3cb3c30b53f7af862480b9b61a1570eacdc9d78dd9e883f3068bd28068b3f445&amp;source=constructor" width="100%" height="565" frameborder="0" title="Карта дилеров — Казахстан"></iframe>
            </div>
        </div>
    </div>

</div>

<?php get_footer(); ?>
