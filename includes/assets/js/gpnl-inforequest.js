"use strict";var request_form_element={},check_form_element={},form_config="request_form_object";function getFormObj(e){var o={},n=$(e).serializeArray();return $.each(n,function(e,n){o[n.name]=n.value}),o}function showErrorMessage(e){$(e).find("*").hide(),$(e).append("Oh nee!"),$(e).append("<p>Hier gaat helaas iets mis, sorry. </p>"),$(e).append("<a href='"+window.location.href+'\' class="btn btn-primary btn-block"">Probeer je het nog eens? </a>')}function toggleDisable(e){e.prop("disabled",!e.prop("disabled"))}function createCookie(e,n,o){var r=new Date;r.setTime(r.getTime()+24*o*60*60*1e3),document.cookie=encodeURI(e)+"="+encodeURI(n)+";domain=."+document.domain+";path=/;; expires="+r.toGMTString()}function readCookie(e){for(var n=e+"=",o=document.cookie.split(";"),r=0;r<o.length;r++){for(var t=o[r];" "===t.charAt(0);)t=t.substring(1,t.length);if(0===t.indexOf(n))return t.substring(n.length,t.length)}return null}function enableDownloadlinks(){$(".cover-card-column").each(function(){$(this).find("a").each(function(){var e=$(this).attr("href");$(this).attr("href",e+"?e=1")})})}$(document).ready(function(){var e=new URLSearchParams(window.location.search).has("e");"1"===window[form_config].hider&&($(".inforequest__wrapper").nextAll().hide(),$(".inforequest__title").html("<h3>Lesmateriaal downloaden</h3>"),$(".inforequest__title").after('<p class="inforequest__message">Registreer of log in om het lesmateriaal te downloaden.</p>')),null!==readCookie("gpnl_education")||e?($(".inforequest__title").html("<h3>Welkom terug!</h3>"),$(".inforequest__message").html("<p>Je kan nu gebruikmaken van de lesmaterialen.</p>"),$(".inforequest__wrapper").nextAll().show(1e3)):$(".hideshowbtn").show(1e3)}),$(".hideshowbtn").on("click",function(){var e=$(this).data("target");$(e).show(1e3),$(".hideshowbtn").hide(1e3)}),$(".inforequest_form").on("reset",function(){$(this).hide(1e3),$(".hideshowbtn").show(1e3)}),$(".gpnl-request__form").on("submit",function(){var e=getFormObj(request_form_element=this);e.action="request_form_process",e.nonce=window[form_config].nonce,e.literaturecode=window[form_config].literaturecode,toggleDisable($(request_form_element).find("*")),""===e.human?$.ajax({type:"POST",url:window[form_config].ajaxUrl,data:e,success:function(){$(request_form_element).find("*").hide(1e3),$(".inforequest__title").html("<h3>Dank je voor je aanmelding!</h3>"),$(request_form_element).prepend("<p>Je kan nu gebruikmaken van de lesmaterialen.</p>"),null!==readCookie("greenpeace")?(createCookie("gpnl_education",1,365),"1"===window[form_config].hider&&($(".inforequest__wrapper").nextAll().show(1e3),$(".inforequest__message").hide(1e3))):enableDownloadlinks()},error:function(){showErrorMessage(request_form_element)}}):showErrorMessage(request_form_element)}),$(".gpnl-check__form").on("submit",function(){var e=getFormObj(check_form_element=this);e.action="check_form_process",e.nonce=window[form_config].nonce,toggleDisable($(check_form_element).find("*")),""===e.human?$.ajax({type:"POST",url:window[form_config].ajaxUrl,data:e,success:function(){$(check_form_element).find("*").hide(1e3),$(".inforequest__title").html("<h3>Welkom terug!</h3>"),$(check_form_element).prepend("<p>Je kan nu gebruikmaken van de lesmaterialen.</p>"),null!==readCookie("greenpeace")?(createCookie("gpnl_education",1,365),"1"===window[form_config].hider&&($(".inforequest__wrapper").nextAll().show(1e3),$(".inforequest__message").hide(1e3))):enableDownloadlinks()},error:function(){$(".resetBtn").remove("*"),$(".gpnl-check__form").hide(1e3),$(".gpnl-request__form").prepend("<p>Dit emailadres is niet bij ons bekend. Wil je je inschrijven?"),$(".gpnl-request__form").show(1e3),$(".hideshowbtn").remove("*")}}):showErrorMessage(check_form_element)});
//# sourceMappingURL=maps/gpnl-inforequest.js.map
