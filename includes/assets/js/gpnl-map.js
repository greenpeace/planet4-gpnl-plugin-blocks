"use strict";$(document).ready(function(){var e=[config.centerlat,config.centerlng],t=config.zoom,o="string"==typeof config.marker?JSON.parse(config.marker):[],a="string"==typeof config.polyline?JSON.parse(config.polyline):[],n=L.map("map").setView(e,t);L.tileLayer("https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png",{maxZoom:19,attribution:'&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Tiles style by <a href="https://www.hotosm.org/" target="_blank">Humanitarian OpenStreetMap Team</a> hosted by <a href="https://openstreetmap.fr/" target="_blank">OpenStreetMap France</a>'}).addTo(n);var p=L.Icon.extend({options:{shadowUrl:null,iconAnchor:new L.Point(12,12),iconSize:new L.Point(24,24),iconUrl:"https://storage.googleapis.com/planet4-netherlands-stateless/2018/05/913c0158-cropped-5b45d6f2-p4_favicon-150x150.png"}});$.each(a,function(e,t){L.polyline(t,{color:"#66CC00",weight:5,opacity:1}).addTo(n)}),$.each(o,function(e,t){var o=L.marker(t,{icon:new p}).addTo(n);0===e&&o.bindPopup('<a href="https://www.greenpeace.org/nl/acties/plasticmonster/plastival/">Doe mee met de Plastic Monster Rave!</a>',{className:"popupCustom"}).openPopup(),1===e&&o.bindPopup('<a href="https://www.greenpeace.org/nl/acties/plasticmonster/rave/">Kom ook naar het Plastival!</a>',{className:"popupCustom"})})});
//# sourceMappingURL=maps/gpnl-map.js.map
