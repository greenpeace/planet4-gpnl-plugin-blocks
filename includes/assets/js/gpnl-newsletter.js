"use strict";var newsletter_form_element={};function getFormObj(e){var t={},n=$(e).serializeArray();return $.each(n,function(e,n){t[n.name]=n.value}),t}function toggleDisable(e){e.prop("disabled",!e.prop("disabled"))}$(".gpnl-newsletter__form").on("submit",function(){var e=getFormObj(newsletter_form_element=this),n="newsletter_form_object_"+e.form_id;e.action="newsletter_form_process",e.nonce=window[n].nonce,e.marketingcode=window[n].marketingcode,e.literaturecode=window[n].literaturecode,e.screenid=window[n].screenid,toggleDisable($(newsletter_form_element).find("*")),$.ajax({type:"POST",url:window[n].ajaxUrl,data:e,success:function(){console.log("^-^"),$(newsletter_form_element).find("*").hide(),$(".gpnl-newsletter__title").html("Hoera, je bent er bijna! "),$(".gpnl-newsletter__description").html("<h4>Bevestig je aanmelding via de mail die je van ons ontvangt. Bedankt!  </h4>"),"undefined"!=typeof dataLayer&&dataLayer.push({event:"nieuwsbriefformulier",conv_campaign:e.literaturecode,conv_action:"registreer",conv_label:"registreer-nieuwsbrief"})},error:function(){console.log("o_o"),$(newsletter_form_element).find("*").hide(),$(".gpnl-newsletter__title").html("Oh nee!"),$(".gpnl-newsletter__description").html("<p>Hier gaat helaas iets mis, sorry. </p>"),$(newsletter_form_element).append("<a href='"+window.location.href+'\' class="btn btn-primary btn-block"">Probeer je het nog eens? </a>')}})});
//# sourceMappingURL=maps/gpnl-newsletter.js.map
