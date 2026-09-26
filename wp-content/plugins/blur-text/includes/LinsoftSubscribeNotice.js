/**
 * Created by Linnea on 9/17/2015.
 */

jQuery(document).ready(function () {
    console.log("sanity test");

    // when click on Yes
    jQuery("#lins_submit").click(function () {
        var email = jQuery('#ls_email').val();
        console.log(email);
        jQuery.post(ajaxurl, 'action=blurtext&lins_optin=1&lins_email=' + email, function () {
            jQuery("div.blur-text").html("" +
                "<p>Thanks, we'll stay in touch!</p> <p>If you know of anyone looking to hire a Wordpress programmer, please "
                + "<A href='http://www.linsoftware.com/services/wordpress/'>send them my way.</a></p>");
        });
    });


    // when click on No
    jQuery("#lins_nt").click(function () {
        // send ajax
        jQuery.post(ajaxurl, 'action=blurtext&lins_optin=0', function () {
            jQuery("div.blur-text").hide();
        });
    });


});