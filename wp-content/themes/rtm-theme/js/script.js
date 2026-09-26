$(document).ready(function () {

$('#add').click(function() {
    $(this).before('<input type="text" name="adress[]" class="">');
  });

$('input, textarea').placeholder();

var sudoSlider = $("#slider").sudoSlider({
     effect: "fade",
     // auto:true,
     speed: 1000,
     pause:6000,
     prevNext:true
  });


  $('.minus').click(function () {
        var $input = $(this).parent().find('input');
        var count = parseInt($input.val()) - 1;
        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.change();
        return false;
    });
    $('.plus').click(function () {
        var $input = $(this).parent().find('input');
        $input.val(parseInt($input.val()) + 1);
        $input.change();
        return false;
    });




$(".hide_text").hide();
    $(".show_text1").hide();
  $(".visible-menu").toggle(0, function(){
    $(".visible-menu").toggle(0);
  });
  $(".show_text").on("click", function(e){
    $(this).parent().addClass("active");
    $(this).parent().find(".hide_text").show();
    $(this).parent().find(".visible-menu").slideToggle("slow");
    $(this).parent().find(".show_text1").show();
    $(this).parent().find(".show_text").hide();

  });
  $(".hide_text").on("click", function(e){
    $(this).parent().removeClass("active");
    $(this).parent().find(".show_text").show();
    $(this).parent().find(".hide_text").slideToggle("slow");
    $(this).parent().find(".visible-menu").slideToggle("slow");
        $(this).parent().find(".show_text1").hide();
            $(this).parent().find(".show_text").show();
  });
    $(".show_text1").on("click", function(e){
    $(this).parent().removeClass("active");
    $(this).parent().find(".show_text").show();
    $(this).parent().find(".hide_text").slideToggle("slow");
    $(this).parent().find(".visible-menu").slideToggle("slow");
        $(this).parent().find(".show_text1").hide();
            $(this).parent().find(".show_text").show();
  });


(function($){
    $(document).ready(function(){
      //select
      $('.custom-select').wrap('<div class="clc-select">');
      $('.custom-select').before('<div class="clc-select-head"></div><ul class="clc-select-body"></ul>');

      $('.custom-select').each(function(){
              var txt = $(this).find('option').eq(0).text();
        $(this).parent().find('.clc-select-head').text(txt);
        $(this).find('option').each(function(){
                  $(this).parents('.clc-select').find('.clc-select-body').append('<li>'+$(this).text()+'</li>');
              });
          });

      $('body').delegate('.clc-select', 'click', function(e){
        e.stopPropagation();
        $(this).find('.clc-select-body').stop().slideDown(500);
      });

      $('body').delegate('.clc-select', 'mouseleave', function(){
        $(this).find('.clc-select-body').stop().slideUp(500);
      });

      $('.clc-select').delegate('li', 'click', function(e){
        e.stopPropagation();
        var txt = $(this).text();
        $(this).parents('.clc-select').find('.clc-select-head').text(txt);
        $(this).parent().stop().slideUp(150);
        $(this).parents('.clc-select').find('select').val(txt);
      });
    });
    function number_format(number, decimals, dec_point, thousands_sep) {
      number = (number + '')
      .replace(/[^0-9+\-Ee.]/g, '');
      var n = !isFinite(+number) ? 0 : +number,
      prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
      sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
      dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
      s = '',
      toFixedFix = function(n, prec) {
        var k = Math.pow(10, prec);
        return '' + (Math.round(n * k) / k)
        .toFixed(prec);
      };
      // Fix for IE parseFloat(0.55).toFixed(0) = 0;
      s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
      .split('.');
      if (s[0].length > 3) {
      s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
      }
      if ((s[1] || '')
      .length < prec) {
      s[1] = s[1] || '';
      s[1] += new Array(prec - s[1].length + 1)
        .join('0');
      }
      return s.join(dec);
    }
  })(jQuery);



// slider
$(document).on('click', ".carousel-button-right",function(){
  var carusel = $(this).parents('.carousel');
  right_carusel(carusel);
  return false;
});
$(document).on('click',".carousel-button-left",function(){
  var carusel = $(this).parents('.carousel');
  left_carusel(carusel);
  return false;
});
function left_carusel(carusel){
   var block_width = $(carusel).find('.carousel-block').outerWidth();
   $(carusel).find(".carousel-items .carousel-block").eq(-1).clone().prependTo($(carusel).find(".carousel-items"));
   $(carusel).find(".carousel-items").css({"left":"-"+block_width+"px"});
   $(carusel).find(".carousel-items").animate({left: "0px"}, 200);
   $(carusel).find(".carousel-items .carousel-block").eq(-1).remove();
}
function right_carusel(carusel){
   var block_width = $(carusel).find('.carousel-block').outerWidth();
   $(carusel).find(".carousel-items").animate({left: "-"+ block_width +"px"}, 200);
   setTimeout(function () {
      $(carusel).find(".carousel-items .carousel-block").eq(0).clone().appendTo($(carusel).find(".carousel-items"));
      $(carusel).find(".carousel-items .carousel-block").eq(0).remove();
      $(carusel).find(".carousel-items").css({"left":"0px"});
   }, 300);
}
$(function() {
//Раскомментируйте строку ниже, чтобы включить автоматическую прокрутку карусели
//  auto_right('.carousel:first');
})
// Автоматическая прокрутка
function auto_right(carusel){
  setTimeout(function(){
    right_carusel(carusel);
    auto_right(carusel);
  }, 3000)
}

});

$(window).load(function(){

    $("#carousel-demo1").sliderkit({
      auto:false,
      shownavitems:1,
      start:0,
      counter:true
    });

    $(".photosgallery-5").sliderkit({
        mousewheel:false,
        shownavitems:5,
        panelbtnshover:false,
        auto:false,
        circular:false,
        navscrollatend:false,
        navpanelautoswitch:false,
        debug:1
      });

  });

$(document).ready(function() {
	// Открытие корзины при наведении
	$('.cart-punkt').hover(function() {
	$('.widget_shopping_cart').toggleClass('open');
	});
});
$(document).ready(function() {
	if(document.location == "https://rtm-a.ru/news-list/category/%d0%bd%d0%be%d0%b2%d0%be%d1%81%d1%82%d0%b8/")
        document.location = "https://rtm-a.ru/news-list";
});

jQuery( function( $ ) {
 
	$( 'body' ).on( 'change', '.qty', function() { // поле с количеством имеет класс .qty
		$( '[name="update_cart"]' ).trigger( 'click' );
	} );
 
} );


function openClose (){
    var element = document.getElementById('menu-items');
    var page = document.getElementsByTagName('body');
    var icon = document.getElementById('menu-icon');
    if(element.style.display == 'block'){
        icon.style.background = "url(/wp-content/themes/rtm-theme/images/catalog.png) no-repeat";
        element.style.display = 'none';
        page[0].style.overflow = 'auto';
    }
    else{
        icon.style.background = "url(/wp-content/themes/rtm-theme/images/catalog-close.png) no-repeat";
        element.style.display = 'block';
        page[0].style.overflow = 'hidden';
    }
}

$(document).ready(function(){
    document.querySelector('section.related h2').innerHTML = "Товары этой категории";


});


document.querySelectorAll('.lmp_products_loading span')[0].innerHTML = "Товаров больше нет!";








$(document).ready(function () {

$("a.nextBtn, .more a img").rotate({
   bind:
     {
        click: function(){
            $(this).rotate({ angle:0,animateTo:360,easing: $.easing.easeInOutExpo })
        }
     }
});



});

