"use strict";var request_form_element={};function getFormObj(e){var n={},r=$(e).serializeArray();return $.each(r,function(e,r){n[r.name]=r.value}),n}function showErrorMessage(e){$(e).find("*").hide(),$(e).append("Oh nee!"),$(e).append("<p>Hier gaat helaas iets mis, sorry. </p>"),$(e).append("<a href='"+window.location.href+'\' class="btn btn-primary btn-block"">Probeer je het nog eens? </a>')}function toggleDisable(e){e.prop("disabled",!e.prop("disabled"))}$(".hideshowbtn").on("click",function(){var e=$(this).data("target");$(e).show(1e3),$(".hideshowbtn").hide(1e3)}),$(".gpnl-request__form").on("submit",function(){var e=getFormObj(request_form_element=this),r="request_form_object";e.action="request_form_process",e.nonce=window[r].nonce,e.literaturecode=window[r].literaturecode,toggleDisable($(request_form_element).find("*")),""===e.human?$.ajax({type:"POST",url:window[r].ajaxUrl,data:e,success:function(){$(request_form_element).find("*").hide(),$(request_form_element).append("<h2>Hoera, je bent er bijna!</h2>")},error:function(){showErrorMessage(request_form_element)}}):showErrorMessage(request_form_element)});
//# sourceMappingURL=maps/gpnl-inforequest.js.map
