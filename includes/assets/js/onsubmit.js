"use strict";function getFormObj(n){var t={},e=$(n).serializeArray();return $.each(e,function(n,e){t[e.name]=e.value}),t}function toggleDisable(n){n.prop("disabled",!n.prop("disabled"))}function cardflip(n){var e=$(n),t=n.parentNode,a=$(n.parentNode.parentNode);$(e.find(".signBtn")).toggle(),$(e.find(".policies")).toggle(),$(t.nextElementSibling).css("position",flip_positionattribute(t.nextElementSibling)),$(t).css("position",flip_positionattribute(t)),a.toggleClass("flipped"),a.one("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend",function(){$(t).toggle(),a.off("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend")})}function flip_positionattribute(n){return"absolute"===$(n).css("position")?"relative":"absolute"}function fireShareEvent(n,e){if("undefined"!=typeof dataLayer){var t="petition_form_object_"+e;dataLayer.push({event:"petitiebutton",conv_campaign:window[t].analytics_campaign,conv_action:window[t].ga_action,conv_label:"share_"+n})}else console.log("GTM not defined?")}function socialBlueDeDuplicate(n,e,t){var a=("https:"==document.location.protocol?"https://secure.event.":"http://www.")+"affiliatepartners.com",i="/js/ApConversionPixelV1.1.js";_apOrderValue=0,_apOrderNumber="email="+n+"-telefoonnumer="+e,_apRef=t;try{$("body").append(unescape("%3Cscript src='"+a+i+"' type='text/javascript'%3E%3C/script%3E"))}catch(n){var o=document.createElement("script");o.type="text/javascript",o.src=a+i,document.getElementsByTagName("head")[0].appendChild(o)}}function getUrlVars(){for(var n,e=[],t=window.location.href.split("#")[0].slice(window.location.href.indexOf("?")+1).split("&"),a=0;a<t.length;a++)n=t[a].split("="),e.push(n[0]),e[n[0]]=n[1];return e}$(".gpnl-petitionform").on("submit",function(){var e=this,t=getFormObj(e),a="petition_form_object_"+t.form_id;t.action="petition_form_process",t.nonce=window[a].nonce,t.ad_campaign=window[a].ad_campaign,toggleDisable($(e).find("*")),$.ajax({type:"POST",url:window[a].ajaxUrl,data:t,success:function(n){console.log("^-^"),"undefined"!=typeof dataLayer&&dataLayer.push({event:"petitiebutton",conv_campaign:window[a].analytics_campaign,conv_action:window[a].ga_action,conv_label:"registreer"}),""!==t.phone?("undefined"!=typeof dataLayer&&dataLayer.push({event:"petitiebutton",conv_campaign:window[a].analytics_campaign,conv_action:"telnr",conv_label:"Ja"}),"SB"===window[a].ad_campaign?(fbq("track","Lead"),socialBlueDeDuplicate(t.mail,n.data.phone,window[a].apref)):"JA"===window[a].ad_campaign&&fbq("track",window[a].jalt_track)):"undefined"!=typeof dataLayer&&dataLayer.push({event:"petitiebutton",conv_campaign:window[a].analytics_campaign,conv_action:"telnr",conv_label:"Nee"}),cardflip(e),null!=getUrlVars().clangct&&clang.conversion.track()},error:function(){console.log("o_o");var n=$(e.parentNode.nextElementSibling);n.find("*").hide(""),n.append("<p>Sorry, er gaat momenteel iets fout, probeer het nu of later opnieuw.</p>"),n.append("<a href='"+window.location.href+'\' class="btn btn-primary btn-block"">Probeer opnieuw</a>'),cardflip(e)}})});
//# sourceMappingURL=maps/onsubmit.js.map
