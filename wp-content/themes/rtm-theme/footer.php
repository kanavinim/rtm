<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>

<footer class="">
    <div class="inner">
        <div class="footer-top clearfix">
            <ul>
                <li><a href="/shop/" style="padding-left: 0px;">Каталог</a></li>
                <li><a href="/delivery/">доставка</a></li>
                    <li><a href="/news/">новости</a></li>
                    <li><a href="/contacts/">контакты</a></li>
                    <li><a href="/dealers/">дилеры</a></li>
            </ul>
            <div class="header-top-right-bottom">
                    <span><a href="tel:+79997728529" style="color: #555;">8 (999) 772-85-29</a></span>
                    <div style="display: flex;">
                        <a href="https://vk.com/rtm_teh" target="_blank" ><img class="icon-top" src="/wp-content/themes/rtm-theme/images/vk.png" alt="РТМ - Оружейная компания"/> </a>
                        <a href="https://t.me/rtm_teh"><img class="icon-top" src="/wp-content/themes/rtm-theme/images/telegra.png" alt="РТМ - Оружейная компания" /></a>
                    </div>

                </div>
        </div>
        <div class="clearfix footer-bottom">
			<p  class="footer-underline" >РТМ – оружейная компания<br><span style="font-weight: 300;">ИП Гунькин И.А. ИНН 771370572512</span><br>
			<span class="footer-underline" >Все права защищены, &nbsp;<span id="copyright">
                             <script>document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))</script>
                           </span></span>
			
			</p>
			<br>
			<span style="font-weight: 300;"><a style="color:white;" href="/dogovor-publichnoj-oferty/">Договор публичной оферты</a></span>
			<br>
			<span style="font-weight: 300;"><a style="color:white;" href="/polzovatelskoe-soglashenie/">Пользовательское соглашение</a></span>
			<br>
			<span style="font-weight: 300;"><a style="color:white;" href="/soglasie-na-obrabotku-personalnyh-dannyh/">Согласие на обработку персональных данных</a></span>
				
			
            
        </div>
    </div>

</footer>

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]function(){(m[i].a=m[i].a[]).push(arguments)};
   m[i].l=1*new Date();
   for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
   k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(98433704, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/98433704" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

 <?php wp_footer(); ?>

</body>
<script src="https://use.fontawesome.com/173ec72ed3.js"></script> 

</html>
