<?php
/*
Template Name: сontact page
*/
?>
<?php get_header(); ?>

<div class="content">
    <div class="inner contacts-page">
        <h1>Контакты</h1>

        <div class="contacts-columns">
            <div class="contacts-info">
                <section class="contacts-section">
                    <div class="title5">Розничный отдел:</div>
                    <div class="contacts-row">
                        <span>Email:&nbsp;<a href="mailto:shop@rtm-a.ru"><i>shop@rtm-a.ru</i></a></span>
                        <span>Телефон:&nbsp;<a href="tel:79997728529"><i>8 (999) 772-85-29</i></a></span>
                    </div>
                </section>

                <section class="contacts-section">
                    <div class="title5">Отдел продаж:</div>
                    <div class="contacts-row">
                        <span>Email:&nbsp;<a href="mailto:sale@rtm-a.ru"><i>sale@rtm-a.ru</i></a></span>
                        <span>Телефон:&nbsp;<a href="tel:79161849845"><i></i></a></span>
                    </div>
                </section>

                <section class="contacts-section">
                    <div class="title5">Поддержка пользователей:</div>
                    <span>Email:&nbsp;<a href="mailto:support@rtm-a.ru"><i>support@rtm-a.ru</i></a></span>
                </section>

                <section class="contacts-section">
                    <div class="title5">Директор:</div>
                    <span>Email:&nbsp;<a href="mailto:dir@rtm-a.ru"><i>dir@rtm-a.ru</i></a></span>
                </section>

                <section class="contacts-section">
                    <div class="title5">Адрес:</div>
                    <span>Самовывоз заказов в г. Реутов (по предварительному согласованию).</span>
                </section>

                <section class="contacts-section">
                    <div class="title5">Социальные сети:</div>
                    <div class="contact-social">
                        <a href="https://vk.com/rtm_teh" target="_blank"><img class="icon-top" src="/wp-content/themes/rtm-theme/images/vk.png" alt="ВКонтакте"></a>
                        <a href="https://t.me/rtm_teh"><img class="icon-top" src="/wp-content/themes/rtm-theme/images/telegra.png" alt="РТМ - Оружейная компания"></a>
                    </div>
                </section>

                <section class="contacts-section">
                    <div class="title5">Реквизиты:</div>
                    <div class="contacts-requisites">
                        <span>ИП Гунькин И.А.</span>
                        <span>Юридический адрес: 125252, Россия, Москва, Ходынский б-р, д. 17, кв 205</span>
                        <span>ИНН: 771370572512</span>
                    </div>
                </section>

                <section class="contacts-section contacts-form">
                    <h3>Связь с техподдержкой</h3>
                    <?php echo do_shortcode('[contact-form-7 id="285"]'); ?>
                </section>
            </div>

            <div class="contacts-map-wrap">
                <iframe class="contacts-map" src="https://yandex.ru/map-widget/v1/?um=constructor%3Ac89f759f94f024fbdfe361e8598cd8ead8237c656b5cfc01f9fdecc03004b543&amp;source=constructor" title="Карта" loading="lazy" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
