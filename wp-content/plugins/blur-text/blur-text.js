/**
 * Created by Linnea on 9/13/2015.
 */
jQuery( document ).ready(function() {

    var compatible = true;

    var $blur = jQuery(".blur"),
        $blur_hover = jQuery(".blur_hover"),
        $blur_click = jQuery(".blur_click");

    jQuery.fn.blur = function() {
        this.find('*').removeAttr('style');
        var c = this.css("color");
        this.attr('oldcolor', c);
        var background_color = this.css("background");
        this.attr('oldbackground', background_color);
        this.css('color', 'transparent');
        this.css('text-shadow', '0px 0px 10px ' + c);
    };

    jQuery.fn.unblur = function() {
            var oldcolor = this.attr('oldcolor');
            this.css('color', oldcolor);
            this.css('text-shadow', 'none');
            this.removeAttr('oldcolor');
    };

    $blur_hover.each(function() {
        toggleblur(jQuery(this));
    });

    $blur.each(function() {
        toggleblur(jQuery(this));
    });
    $blur_click.each(function() {
        toggleblur(jQuery(this));
    });

    // register click action listener
    $blur_click.click(function() {
        toggleblur(jQuery(this));
    });
    // register hover action listener
    $blur_hover.hover(function() {
        toggleblur(jQuery(this));
    });

    function toggleblur($element) {

        var attr = $element.attr('oldcolor');
        // For some browsers, `attr` is undefined; for others,
        // `attr` is false.  Check for both.
        if (typeof attr !== typeof undefined && attr !== false) {
            $element.unblur();
        } else {
            $element.blur();
        }
    }
});
