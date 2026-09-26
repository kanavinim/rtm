var ipol_ozonpay_refundError = false;

jQuery('.ipol_order_product input[type=number]').val(0);

function recalcRefund() {
    let totalRefundAmount = 0, refundData = [];
    ipol_ozonpay_refundError = false;

    jQuery('.ipol_order_product').each(function(){
        let t=jQuery(this),itemid=t.find('.ipol_ozonpay_positioninfo').attr('pi'),positions=[],posCount=0, addCount = 0,positionPrice = Number(t.find('.ipol_ozonpay_positioninfo').attr('pr'));
        let numberInp = t.find('input[type=number]');
        if (isNaN(positionPrice)) positionPrice = 0;
        t.find('.ipol_ozonpay_positioninfo tr').each(function(){
            let el = jQuery(this);
            if (el.find('.ipol_ozonpay_checkrefund input').prop('checked')) {
                positions.push(el.attr('pi'));
                posCount++;
            }
        });
        if (numberInp.length > 0) {
            let curVal = Number(numberInp.val()), max = Number(numberInp.attr('initmax')),currentMax = max - posCount;
            numberInp.attr('max',currentMax);
            if ( curVal > currentMax ) {
                numberInp.val(currentMax);
                curVal = currentMax;
                if (currentMax < 0 ) {
                    numberInp.val(0);
                    ipol_ozonpay_refundError = true;
                    alert("Для возврата было выбрано больше, чем было куплено. \nПроверьте, что для возврата выбраны товары с правильной маркировкой \nили указано нужное количество товара без маркировки. \nУберите лишние товары из возврата.");
                }
            }
            addCount = curVal;
        }

        t.find('.product_refund_total span').html( ( (posCount+addCount)*positionPrice / 100 ) );

        totalRefundAmount += ( (posCount+addCount)*positionPrice );
        if ( (posCount+addCount) > 0 ) refundData.push({
            itm:itemid,
            pos:positions,
            addcnt:addCount
        });
    });

    jQuery('#refund_form input[name=value]').val(JSON.stringify(refundData));
    jQuery('.ipol_ozonpay_orderpage .total_refund span').html(totalRefundAmount/100);

}


jQuery('.ipol_order_product').each(function(){
    let t=jQuery(this);

    t.find('.ipol_ozonpay_checkconfirm input').prop('checked',true);
    t.find('.ipol_ozonpay_checkrefund input').prop('checked',false);

    t.find('.ipol_ozonpay_checkconfirm input').change(recalcConfirm);
    t.find('.ipol_ozonpay_checkrefund input').change(recalcRefund);

    t.find('input[type=number]').keyup(function(event){
        var t=event.target,max,currentCount,rep = /[-;":'a-zA-Zа-яА-Я\\=`ёЁ/\*++!@#$%\^&_№?><\s|~(),\[\]{}\.]/g;
        if (rep.test(t.value)) t.value = t.value.replace(rep, '');

        currentCount = Number(t.value);
        max = Number(t.attributes['max'].nodeValue);
        if (isNaN(currentCount)) currentCount = 0;
        if (currentCount>max) currentCount=max;
        t.value = currentCount;
    });
    t.find('input[type=number]').change(recalcRefund);

});

function recalcConfirm() {
    let totalConfirmAmount = 0, confirmData = [];
    jQuery('.ipol_order_product').each(function(){
        let t=jQuery(this),itemid=t.find('.ipol_ozonpay_positioninfo').attr('pi'),positions=[],positionPrice = Number(t.find('.ipol_ozonpay_positioninfo').attr('pr'));
        if (isNaN(positionPrice)) positionPrice = 0;
        t.find('.ipol_ozonpay_positioninfo tr').each(function(){
            let el = jQuery(this);
            if (el.find('.ipol_ozonpay_checkconfirm input').prop('checked')) {
                positions.push(el.attr('pi'));
            }
        });
        totalConfirmAmount += (positionPrice * positions.length);
        if (positions.length > 0) confirmData.push({
            itm:itemid,
            pos:positions
        });
    });
    jQuery('#confirm_form input[name=value]').val(JSON.stringify(confirmData));
    jQuery('.ipol_ozonpay_orderpage .total_confirm span').html(totalConfirmAmount/100);
}
recalcConfirm();

if (jQuery('.ipol_ozonpay_toppanel.main').length) {
    jQuery(window).resize(function(){
        jQuery('.ipol_ozonpay_toppanel.actionpanel').css({left:( jQuery('.ipol_ozonpay_toppanel.main').offset().left )+'px'});
    });
    jQuery('.ipol_ozonpay_toppanel.actionpanel').css({left:( jQuery('.ipol_ozonpay_toppanel.main').offset().left )+'px'});
}

jQuery('.actionpanel .cancelpaybtn').click(function(){
    jQuery('.ppwin.cancelwin,.ppwinbg').fadeIn();
});
jQuery('.actionpanel .sendfinalcheckbtn').click(function(){
    jQuery('.ppwin.sendcheckwin,.ppwinbg').fadeIn();
});
jQuery('.actionpanel .refreshmarkbtn').click(function(){
    jQuery('.ppwin.sendmarkwin,.ppwinbg').fadeIn();
});
jQuery('.actionpanel .confirmpaybtn').click(function(){
    jQuery('.ppwin.confirmwin,.ppwinbg').fadeIn();
});
jQuery('.actionpanel .refundbtn').click(function(){
    if (ipol_ozonpay_refundError) {
        jQuery('.ppwin.errorwin p.h').html('Возврат невозможен.');
        jQuery('.ppwin.errorwin p.t').html('Для возврата было выбрано больше товара, чем возможно вернуть.<br/> Пожалуйста, проверьте количества товаров для возврата по каждой позиции.');
        jQuery('.ppwin.errorwin,.ppwinbg').fadeIn();
    } else {
        jQuery('.ppwin.refundwin,.ppwinbg').fadeIn();
    }
});

jQuery('.ppwin.cancelwin .processbtn').click(function(){
    jQuery('#cancel_form').trigger('submit');
});
jQuery('.ppwin.sendcheckwin .processbtn').click(function(){
    let markInfo = [], isError = false;
    jQuery('.ipol_order_product').each(function(){
        let t=jQuery(this), product = {
            itm: t.find('.ipol_ozonpay_positioninfo').attr('pi'),
            m: []
        };
        t.find('.ipol_ozonpay_positioninfo.marked tr.r').each(function(){
            let el = jQuery(this), mcode = el.find('input[type=text]').val();
            if (mcode == '') isError = true;
            product.m.push({
                i: el.attr('pi'),
                m: mcode
            });
        });
        if (product.m.length > 0) markInfo.push(product);
    });
    if (isError) {
        jQuery('.ppwin.sendmarkwin').fadeOut();
        jQuery('.ppwin.errorwin p.h').html('Ошибка.');
        jQuery('.ppwin.errorwin p.t').html('Для отправки финального чека <br/>заполнение всех данных маркировки.');
        jQuery('.ppwin.errorwin,.ppwinbg').fadeIn();
    } else {
        jQuery('#fincheck_form input[name=value]').val(JSON.stringify(markInfo));
        jQuery('#fincheck_form').trigger('submit');
    }
});
jQuery('.ppwin.sendmarkwin .processbtn').click(function(){
    let markInfo = [], isError = false;
    jQuery('.ipol_order_product').each(function(){
        let t=jQuery(this), product = {
            itm: t.find('.ipol_ozonpay_positioninfo').attr('pi'),
            m: []
        };
        t.find('.ipol_ozonpay_positioninfo.marked tr.r').each(function(){
            let el = jQuery(this), mcode = el.find('input[type=text]').val();
            if (mcode == '') isError = true;
            product.m.push({
                i: el.attr('pi'),
                m: mcode
            });
        });
        if (product.m.length > 0) markInfo.push(product);
    });
    if (isError) {
        jQuery('.ppwin.sendmarkwin').fadeOut();
        jQuery('.ppwin.errorwin p.h').html('Ошибка.');
        jQuery('.ppwin.errorwin p.t').html('Для обновления маркировки требуется <br/>заполнение всех данных маркировки.');
        jQuery('.ppwin.errorwin,.ppwinbg').fadeIn();
    } else {
        jQuery('#update_mark_form input[name=value]').val(JSON.stringify(markInfo));
        jQuery('#update_mark_form').trigger('submit');
    }
});
jQuery('.ppwin.confirmwin .processbtn').click(function(){
    jQuery('#confirm_form').trigger('submit');
});
jQuery('.ppwin.refundwin .processbtn').click(function(){
    jQuery('#refund_form').trigger('submit');
});

jQuery('.ppwin .close,.ppwin .closebtn').click(function(){
    jQuery('.ppwinbg,.ppwin').fadeOut();
});





