!function(x){"use strict";function n(){var n=[];return x("#listing-items .listing-item").length&&x("#listing-items .listing-item").each(function(){var e=decodeURIComponent(x(this).data("lmap"));try{n.push(JSON.parse(e))}catch(e){console.log(e)}}),n}function M(e,n,o){return!isNaN(n)&&e<=n&&n<=o}new google.maps.Point(22,16),_townhub_add_ons.marker;var D=document.createElement("div");D.className="map-box";var B={content:D,disableAutoPan:!0,alignBottom:!0,maxWidth:300,pixelOffset:new google.maps.Size(-150,-55),zIndex:null,boxStyle:{width:"300px"},closeBoxMargin:"0",closeBoxURL:"",infoBoxClearance:new google.maps.Size(1,1),isHidden:!1,pane:"floatPane",enableEventPropagation:!1},E=!1;function o(e,n){window.currentMarker=null;var l=new google.maps.LatLngBounds,o={lat:parseFloat(_townhub_add_ons.center_lat),lng:parseFloat(_townhub_add_ons.center_lng)},t=_.template(x("#tmpl-map-info").length?x("#tmpl-map-info").html():"",{variable:"data",evaluate:/<#([\s\S]+?)#>/g,interpolate:/{{{([\s\S]+?)}}}/g,escape:/{{([^}]+?)}}(?!})/g});var a,i,r,s=[];if(e.length)for(var c=0;c<e.length;c++)i=e[c].latitude,r=e[c].longitude,i=parseFloat(i),r=parseFloat(r),M(-90,i,90)&&M(-180,r,180)&&s.push([(a=e[c],t(a)),parseFloat(e[c].latitude),parseFloat(e[c].longitude),c+1,e[c].gmap_marker?e[c].gmap_marker:_townhub_add_ons.marker,e[c].title,e[c].ID,e[c].price,e[c].price_from]);var d=x(I).attr("data-map-zoom"),p=x(I).attr("data-map-scroll");if(null!=d)var g=parseInt(d);else g=parseInt(_townhub_add_ons.map_zoom);if(null!=p)var m=parseInt(p);else m=!1;var u={maxZoom:18,zoom:g,scrollwheel:m,center:new google.maps.LatLng(o),mapTypeId:google.maps.MapTypeId[_townhub_add_ons.gmap_type],zoomControl:!1,mapTypeControl:!1,scaleControl:!1,panControl:!1,navigationControl:!1,streetViewControl:!1,animation:google.maps.Animation.BOUNCE,gestureHandling:"cooperative",styles:[{featureType:"administrative",elementType:"labels.text.fill",stylers:[{color:"#444444"}]}]},h=new google.maps.Map(I,u),v=[],w=new InfoBox;if(google.maps.event.addListener(w,"domready",function(){var e=new CustomEvent("mapPopupOpened",{detail:"googlemap"});window.dispatchEvent(e),x(".infoBox-close").click(function(e){e.preventDefault(),w.close(),x(".map-marker-container").removeClass("clicked infoBox-opened")}),x(".bookmark-listing-btn").click(function(e){e.preventDefault(),window.doBookmarkAjax(this)})}),google.maps.event.addListener(h,"idle",function(){E=!0}),E)var f=new OverlappingMarkerSpiderfier(h,{markersWontMove:!0,markersWontHide:!0});P(document.createElement("div"),h);for(c=0;c<s.length;++c){var k="",y=parseFloat(s[c][8]);if(!isNaN(y)&&0<y&&(k=s[c][7]),1!=_townhub_add_ons.use_dfmarker)!function(){var e,n,o,t,a=new google.maps.LatLng(s[c][1],s[c][2]),i=new T(a,h,{ID:c,marker_id:s[c][6],price:k},s[c][4],c+1,s[c][5]);v.push(i),google.maps.event.addDomListener(i,"click",(e=i,n=s[c][0],o=c,t=e.getMap(),function(){window.currentMarker=o,w.setOptions(B),D.innerHTML=n,w.close(),w.open(t,e),h.panTo(a),h.panBy(0,-110)})),l.extend(a)}();else{var b=new google.maps.Marker({animation:google.maps.Animation.DROP,position:new google.maps.LatLng(s[c][1],s[c][2]),icon:s[c][4],id:c,map:h,title:s[c][5]});v.push(b),z(b,s[c][0]);var C=new google.maps.LatLng(b.position.lat(),b.position.lng());l.extend(C),E&&f.addMarker(b)}}v.length&&(h.fitBounds(l),h.panToBounds(l));new MarkerClusterer(h,v,{cssClass:"cluster",imagePath:"images/",styles:[{url:"",height:40,width:40}],minClusterSize:2,maxZoom:15});google.maps.event.addDomListener(window,"resize",function(){var e=h.getCenter();google.maps.event.trigger(h,"resize"),h.setCenter(e)}),x("#listing-items").on("mouseover",".listing-item",function(){var e=x(this).index();v.length&&-1<e<v.length&&null!=v[e]&&v[e].setAnimation(google.maps.Animation.BOUNCE)}),x("#listing-items").on("mouseout",".listing-item",function(){var e=x(this).index();v.length&&-1<e<v.length&&null!=v[e]&&v[e].setAnimation(null)}),x(".nextmap-nav").off("click").on("click",function(e){if(e.preventDefault(),h.setZoom(g),null==window.currentMarker)google.maps.event.trigger(v[0],"click");else{var n=window.currentMarker;n+1<v.length?google.maps.event.trigger(v[n+1],"click"):google.maps.event.trigger(v[0],"click")}}),x(".prevmap-nav").off("click").on("click",function(e){if(e.preventDefault(),h.setZoom(g),null==window.currentMarker)google.maps.event.trigger(v[v.length-1],"click");else{var n=window.currentMarker;n-1<0?google.maps.event.trigger(v[v.length-1],"click"):google.maps.event.trigger(v[n-1],"click")}}),x(".map-container").length&&x(document).on("click",".map-item",function(e){e.preventDefault(),h.setZoom(15);var n=x(this).closest(".listing-item-loop").index();if(null!=v[n]&&(google.maps.event.trigger(v[n],"click"),1064<x(window).width()&&x(".map-container").hasClass("fw-map")))return x("html, body").animate({scrollTop:x(".map-container").offset().top+"-110px"},1e3),!1}),window.listingsMap=h;var L=new CustomEvent("listingsMapInit",{detail:"mapInit"});window.dispatchEvent(L)}var I=document.getElementById("map-main");null!=I&&(google.maps.event.addDomListener(window,"load",function(){null!=window._townhub_add_ons_map&&window._townhub_add_ons_map.length?o(window._townhub_add_ons_map):o(n())}),window.addEventListener("listingsChanged",function(e){"ajax_search"==e.detail&&o(n())})),x(document).on("click",".scrollContorl",function(e){e.preventDefault(),x(this).toggleClass("enabledsroll"),x(this).is(".enabledsroll")?window.listingsMap.setOptions({scrollwheel:!0}):window.listingsMap.setOptions({scrollwheel:!1})}),x(document).on("click",".geoLocation",function(e){e.preventDefault(),navigator.geolocation&&navigator.geolocation.getCurrentPosition(function(e){var n=new google.maps.LatLng(e.coords.latitude,e.coords.longitude);window.listingsMap.setCenter(n),window.listingsMap.setZoom(12);new google.maps.Marker({position:n,map:window.listingsMap,title:""})})});var T=function(e,n,o,t,a,i){this.latlng=e,this.args=o,this.markerImg=t,this.markerCount=a,this.ltitle=i,this.div=null,this.setMap(n)};function t(){x(".singleMap").each(function(){var e=x(this),n=new google.maps.LatLng(parseFloat(e.data("lat")),parseFloat(e.data("lng"))),o=e.data("loc"),t=e.data("zoom")?e.data("zoom"):parseInt(_townhub_add_ons.map_zoom),a=null!=e.data("marker")&&""!=e.data("marker")?e.data("marker"):_townhub_add_ons.marker,i={zoom:t,center:n,scrollwheel:!1,zoomControl:!1,mapTypeControl:!1,scaleControl:!1,panControl:!1,navigationControl:!1,streetViewControl:!1,mapTypeId:google.maps.MapTypeId[_townhub_add_ons.gmap_type],styles:[{featureType:"landscape",elementType:"all",stylers:[{color:"#f2f2f2"}]}]},l=new google.maps.Map(e[0],i);if(1!=_townhub_add_ons.use_dfmarker)new T(n,l,{},a,"",o);else new google.maps.Marker({position:n,map:l,icon:a,title:o});P(document.createElement("div"),l),window.singleGMap=l})}function z(e,o){var t=e.get("map");e.addListener("click",function(){window.currentMarker=e.id,D.innerHTML=o;var n=new InfoBox(B);n.open(t,e),t.panTo(e.getPosition()),t.panBy(0,-110),google.maps.event.addListener(n,"domready",function(){var e=new CustomEvent("mapPopupOpened",{detail:"googlemap"});window.dispatchEvent(e),x(".infoBox-close").click(function(e){e.preventDefault(),n.close(),x(".map-marker-container").removeClass("clicked infoBox-opened")}),x(".bookmark-listing-btn").click(function(e){e.preventDefault(),window.doBookmarkAjax(this)})}),t.addListener("click",function(e){n.close()}),t.addListener("zoom_changed",function(e){n.close()})})}function P(e,n){e.index=1,e.className="zoom-controls-wrap",cthMobileDetect()?(n.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(e),e.classList.add("for-mobile")):n.controls[google.maps.ControlPosition.RIGHT_CENTER].push(e),e.style.padding="5px";var o=document.createElement("div");e.appendChild(o);var t=document.createElement("div");t.className="mapzoom-in",o.appendChild(t);var a=document.createElement("div");a.className="mapzoom-out",o.appendChild(a),google.maps.event.addDomListener(t,"click",function(){n.setZoom(n.getZoom()+1)}),google.maps.event.addDomListener(a,"click",function(){n.setZoom(n.getZoom()-1)})}T.prototype=new google.maps.OverlayView,T.prototype.draw=function(){var n=this,e=this.div;if(null==e){(e=this.div=document.createElement("div")).className="map-marker-container";var o='<div class="marker-container">';""!=n.markerCount&&(o+='<span class="marker-count">'+n.markerCount+"</span>"),"1"!=_townhub_add_ons.hide_mkprice&&null!=n.args.price&&""!=n.args.price&&(o+='<span class="marker-price">'+n.args.price+"</span>"),o+='<div class="marker-card"><div class="marker-holder"><img src="'+n.markerImg+'" alt="'+n.ltitle+'"></div></div></div>',e.innerHTML=o,google.maps.event.addDomListener(e,"click",function(e){x(".map-marker-container").removeClass("clicked infoBox-opened"),google.maps.event.trigger(n,"click"),x(this).addClass("clicked infoBox-opened")}),null!=n.args.ID&&(e.dataset.marker_id=n.args.ID),this.getPanes().overlayImage.appendChild(e)}var t=this.getProjection().fromLatLngToDivPixel(this.latlng);t&&(e.style.left=t.x+"px",e.style.top=t.y+"px")},T.prototype.remove=function(){this.div&&(this.div.parentNode.removeChild(this.div),this.div=null,x(this).removeClass("clicked"))},T.prototype.getPosition=function(){return this.latlng},T.prototype.setAnimation=function(e){},T.prototype.getVisible=function(){return!0},x(".singleMap").length&&null!=window.google&&"yes"==_townhub_add_ons.single_map_init&&google.maps.event.addDomListener(window,"load",t),x(document).on("click",".initSingleMap",function(e){e.preventDefault(),t(),x(this).parent(".singleMap-init-wrap").remove(),x(".singleMap").removeClass("singleMap-init-no")}),x(".lstreet-view").length&&null!=window.google&&google.maps.event.addDomListener(window,"load",function(){x(".lstreet-view").each(function(){var e=x(this),n={lng:parseFloat(e.data("lng")),lat:parseFloat(e.data("lat"))};new google.maps.StreetViewPanorama(e[0],{position:n,pov:{heading:165,pitch:0},zoom:1})})});var a={types:["geocode"]};if(_townhub_add_ons.country_restrictions){var e=_townhub_add_ons.country_restrictions.filter(function(e){return e});e&&(a.componentRestrictions={country:e.map(function(e){return e.toLowerCase()})})}_townhub_add_ons.place_lng&&(a.language=_townhub_add_ons.place_lng),"none"!=_townhub_add_ons.autocomplete_result_type&&(a.types=[_townhub_add_ons.autocomplete_result_type]);var i=null;null!=window.google&&(i=function(){var e=0<arguments.length&&void 0!==arguments[0]?arguments[0]:null;null==e&&(e=x(".auto-place-loc")),e.each(function(){var n=x(this).closest(".nearby-inputs-wrap"),o=new google.maps.places.Autocomplete(this,a);o.addListener("place_changed",function(){var e=o.getPlace();n.find(".nearby-checkbox").length&&n.find(".nearby-checkbox").prop("checked",!0),n.children(".auto-place-nearby").length&&n.children(".auto-place-nearby").val("on"),n.children(".auto-place-lat").length&&n.children(".auto-place-lat").val(e.geometry.location.lat()),n.children(".auto-place-lng").length&&n.children(".auto-place-lng").val(e.geometry.location.lng()),1064<x(window).outerWidth()&&window.doAjaxSearch(document.getElementById("list-search-page-form"))}),this.oninput=function(){n.find(".nearby-checkbox").length&&n.find(".nearby-checkbox").prop("checked",!1),n.children(".auto-place-nearby").length&&n.children(".auto-place-nearby").val("off"),n.children(".auto-place-lat").length&&n.children(".auto-place-lat").val(""),n.children(".auto-place-lng").length&&n.children(".auto-place-lng").val("")}})}),x(".auto-place-loc").length&&document.addEventListener("DOMContentLoaded",function(){null!=i&&i()}),window.addEventListener("filterChanged",function(e){x(".list-search-page-form .auto-place-loc").length&&null!=i&&i(x(".list-search-page-form .auto-place-loc"))})}(jQuery);