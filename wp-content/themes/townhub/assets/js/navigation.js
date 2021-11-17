/**
 * Single Page Nav Plugin
 * Copyright (c) 2014 Chris Wojcik <hello@chriswojcik.net>
 * Dual licensed under MIT and GPL.
 * @author Chris Wojcik
 * @version 1.6.5+2+1
 */

// Utility
if(typeof Object.create!=='function'){Object.create=function(obj){function F(){}F.prototype=obj;return new F()}}(function($,window,document,undefined){"use strict";var SinglePageNav={init:function(options,container){this.options=$.extend({},$.fn.singlePageNav.defaults,options);this.container=container;this.$container=$(container);this.$links=this.$container.find('a');if(this.options.filter!==''){this.$links=this.$links.filter(this.options.filter)}this.$window=$(window);this.$htmlbody=$('html, body');this.$links.on('click.singlePageNav',$.proxy(this.handleClick,this));this.didScroll=false;this.checkPosition();this.setTimer()},handleClick:function(e){var self=this,link=e.currentTarget,$elem=$(link.hash);e.preventDefault();if($elem.length){self.clearTimer();if(typeof self.options.beforeStart==='function'){self.options.beforeStart()}self.setActiveLink(link.hash);self.scrollTo($elem,function(){if(self.options.updateHash&&history.pushState){history.pushState(null,null,link.hash)}self.setTimer();if(typeof self.options.onComplete==='function'){self.options.onComplete()}})}},scrollTo:function($elem,callback){var self=this;var target=self.getCoords($elem).top;var called=false;self.$htmlbody.stop().animate({scrollTop:target},{duration:self.options.speed,easing:self.options.easing,complete:function(){if(typeof callback==='function'&&!called){callback()}called=true}})},setTimer:function(){var self=this;self.$window.on('scroll.singlePageNav',function(){self.didScroll=true});self.timer=setInterval(function(){if(self.didScroll){self.didScroll=false;self.checkPosition()}},250)},clearTimer:function(){clearInterval(this.timer);this.$window.off('scroll.singlePageNav');this.didScroll=false},checkPosition:function(){var scrollPos=this.$window.scrollTop();var currentSection=this.getCurrentSection(scrollPos);if(currentSection!==null){this.setActiveLink(currentSection)}},getCoords:function($elem){return{top:Math.round($elem.offset().top)-this.options.offset}},setActiveLink:function(href){var $activeLink=this.$container.find("a[href$='"+href+"']");if(!$activeLink.hasClass(this.options.currentClass)){this.$links.removeClass(this.options.currentClass);$activeLink.addClass(this.options.currentClass);if($(".scroll-nav  a").hasClass("act-link"))$(".scroll-nav  a.act-link").each(function(){var a=$(this).data("bgscr"),b=$(this).data("bgtex");var ua=window.navigator.userAgent;var msie=ua.indexOf("MSIE ");if(msie>0||!!navigator.userAgent.match(/Trident.*rv\:11\./)){$(".bg-title span").html(b)}else{$(".bg-title span").html(b).shuffleLetters({})}$(".column-image").addClass("scrbg");setTimeout(function(){$(".bg-scroll").css("background-image","url("+a+")");$(".column-image").removeClass("scrbg")},700)})}},getCurrentSection:function(scrollPos){var i,hash,coords,section;for(i=0;i<this.$links.length;i++){hash=this.$links[i].hash;if($(hash).length){coords=this.getCoords($(hash));if(scrollPos>=coords.top-this.options.threshold){section=hash}}}return section||((this.$links.length===0)?(null):(this.$links[0].hash))}};$.fn.singlePageNav=function(options){return this.each(function(){var singlePageNav=Object.create(SinglePageNav);singlePageNav.init(options,this)})};$.fn.singlePageNav.defaults={offset:0,threshold:120,speed:400,currentClass:'current',easing:'swing',updateHash:false,filter:'',onComplete:false,beforeStart:false}})(jQuery,window,document);
// ScrollToFixed
(function(a){a.isScrollToFixed=function(b){return !!a(b).data("ScrollToFixed")};a.ScrollToFixed=function(d,i){var l=this;l.$el=a(d);l.el=d;l.$el.data("ScrollToFixed",l);var c=false;var G=l.$el;var H;var E;var e;var y;var D=0;var q=0;var j=-1;var f=-1;var t=null;var z;var g;function u(){G.trigger("preUnfixed.ScrollToFixed");k();G.trigger("unfixed.ScrollToFixed");f=-1;D=G.offset().top;q=G.offset().left;if(l.options.offsets){q+=(G.offset().left-G.position().left)}if(j==-1){j=q}H=G.css("position");c=true;if(l.options.bottom!=-1){G.trigger("preFixed.ScrollToFixed");w();G.trigger("fixed.ScrollToFixed")}}function n(){var I=l.options.limit;if(!I){return 0}if(typeof(I)==="function"){return I.apply(G)}return I}function p(){return H==="fixed"}function x(){return H==="absolute"}function h(){return !(p()||x())}function w(){if(!p()){t.css({display:G.css("display"),width:G.outerWidth(true),height:G.outerHeight(true),"float":G.css("float")});cssOptions={"z-index":l.options.zIndex,position:"fixed",top:l.options.bottom==-1?s():"",bottom:l.options.bottom==-1?"":l.options.bottom,"margin-left":"0px"};if(!l.options.dontSetWidth){cssOptions.width=G.width()}G.css(cssOptions);G.addClass(l.options.baseClassName);if(l.options.className){G.addClass(l.options.className)}H="fixed"}}function b(){var J=n();var I=q;if(l.options.removeOffsets){I="";J=J-D}cssOptions={position:"absolute",top:J,left:I,"margin-left":"0px",bottom:""};if(!l.options.dontSetWidth){cssOptions.width=G.width()}G.css(cssOptions);H="absolute"}function k(){if(!h()){f=-1;t.css("display","none");G.css({"z-index":y,width:"",position:E,left:"",top:e,"margin-left":""});G.removeClass("scroll-to-fixed-fixed");if(l.options.className){G.removeClass(l.options.className)}H=null}}function v(I){if(I!=f){G.css("left",q-I);f=I}}function s(){var I=l.options.marginTop;if(!I){return 0}if(typeof(I)==="function"){return I.apply(G)}return I}function A(){if(!a.isScrollToFixed(G)){return}var K=c;if(!c){u()}else{if(h()){D=G.offset().top;q=G.offset().left}}var I=a(window).scrollLeft();var L=a(window).scrollTop();var J=n();if(l.options.minWidth&&a(window).width()<l.options.minWidth){if(!h()||!K){o();G.trigger("preUnfixed.ScrollToFixed");k();G.trigger("unfixed.ScrollToFixed")}}else{if(l.options.maxWidth&&a(window).width()>l.options.maxWidth){if(!h()||!K){o();G.trigger("preUnfixed.ScrollToFixed");k();G.trigger("unfixed.ScrollToFixed")}}else{if(l.options.bottom==-1){if(J>0&&L>=J-s()){if(!x()||!K){o();G.trigger("preAbsolute.ScrollToFixed");b();G.trigger("unfixed.ScrollToFixed")}}else{if(L>=D-s()){if(!p()||!K){o();G.trigger("preFixed.ScrollToFixed");w();f=-1;G.trigger("fixed.ScrollToFixed")}v(I)}else{if(!h()||!K){o();G.trigger("preUnfixed.ScrollToFixed");k();G.trigger("unfixed.ScrollToFixed")}}}}else{if(J>0){if(L+a(window).height()-G.outerHeight(true)>=J-(s()||-m())){if(p()){o();G.trigger("preUnfixed.ScrollToFixed");if(E==="absolute"){b()}else{k()}G.trigger("unfixed.ScrollToFixed")}}else{if(!p()){o();G.trigger("preFixed.ScrollToFixed");w()}v(I);G.trigger("fixed.ScrollToFixed")}}else{v(I)}}}}}function m(){if(!l.options.bottom){return 0}return l.options.bottom}function o(){var I=G.css("position");if(I=="absolute"){G.trigger("postAbsolute.ScrollToFixed")}else{if(I=="fixed"){G.trigger("postFixed.ScrollToFixed")}else{G.trigger("postUnfixed.ScrollToFixed")}}}var C=function(I){if(G.is(":visible")){c=false;A()}};var F=function(I){(!!window.requestAnimationFrame)?requestAnimationFrame(A):A()};var B=function(){var J=document.body;if(document.createElement&&J&&J.appendChild&&J.removeChild){var L=document.createElement("div");if(!L.getBoundingClientRect){return null}L.innerHTML="x";L.style.cssText="position:fixed;top:100px;";J.appendChild(L);var M=J.style.height,N=J.scrollTop;J.style.height="3000px";J.scrollTop=500;var I=L.getBoundingClientRect().top;J.style.height=M;var K=(I===100);J.removeChild(L);J.scrollTop=N;return K}return null};var r=function(I){I=I||window.event;if(I.preventDefault){I.preventDefault()}I.returnValue=false};l.init=function(){l.options=a.extend({},a.ScrollToFixed.defaultOptions,i);y=G.css("z-index");l.$el.css("z-index",l.options.zIndex);t=a("<div />");H=G.css("position");E=G.css("position");e=G.css("top");if(h()){l.$el.after(t)}a(window).bind("resize.ScrollToFixed",C);a(window).bind("scroll.ScrollToFixed",F);if("ontouchmove" in window){a(window).bind("touchmove.ScrollToFixed",A)}if(l.options.preFixed){G.bind("preFixed.ScrollToFixed",l.options.preFixed)}if(l.options.postFixed){G.bind("postFixed.ScrollToFixed",l.options.postFixed)}if(l.options.preUnfixed){G.bind("preUnfixed.ScrollToFixed",l.options.preUnfixed)}if(l.options.postUnfixed){G.bind("postUnfixed.ScrollToFixed",l.options.postUnfixed)}if(l.options.preAbsolute){G.bind("preAbsolute.ScrollToFixed",l.options.preAbsolute)}if(l.options.postAbsolute){G.bind("postAbsolute.ScrollToFixed",l.options.postAbsolute)}if(l.options.fixed){G.bind("fixed.ScrollToFixed",l.options.fixed)}if(l.options.unfixed){G.bind("unfixed.ScrollToFixed",l.options.unfixed)}if(l.options.spacerClass){t.addClass(l.options.spacerClass)}G.bind("resize.ScrollToFixed",function(){t.height(G.height())});G.bind("scroll.ScrollToFixed",function(){G.trigger("preUnfixed.ScrollToFixed");k();G.trigger("unfixed.ScrollToFixed");A()});G.bind("detach.ScrollToFixed",function(I){r(I);G.trigger("preUnfixed.ScrollToFixed");k();G.trigger("unfixed.ScrollToFixed");a(window).unbind("resize.ScrollToFixed",C);a(window).unbind("scroll.ScrollToFixed",F);G.unbind(".ScrollToFixed");t.remove();l.$el.removeData("ScrollToFixed")});C()};l.init()};a.ScrollToFixed.defaultOptions={marginTop:0,limit:0,bottom:-1,zIndex:1000,baseClassName:"scroll-to-fixed-fixed"};a.fn.scrollToFixed=function(b){return this.each(function(){(new a.ScrollToFixed(this,b))})}})(jQuery);

/**
	/*
	 *
	 *	jQuery Sliding Menu Plugin
	 *	Mobile app list-style navigation in the browser
	 *
	 *	Written by Ali Zahid
	 *	http://alizahid.github.io/jquery-sliding-menu/
	 *
	 */

// TownHub fix change $.fn.menu to $.fn.slidingMenu -> need to be replace with https://alizahid.github.io/slinky/
(function($) {
    var usedIds = [];
    // $.fn.menu = function(options) {
    $.fn.slidingMenu = function(options) {
        var selector = this.selector;
        var settings = $.extend({
            dataJSON: false,
            backLabel: ''
        }, options);
        return this.each(function() {
            var self = this,
                menu = $(self),
                data;
            if (menu.hasClass('sliding-menu')) {
                return
            }
            var menuWidth = menu.width();
            if (settings.dataJSON) {
                data = processJSON(settings.dataJSON)
            } else {
                data = process(menu)
            }
            menu.empty().addClass('sliding-menu');
            var rootPanel;
            if (settings.dataJSON) {
                $(data).each(function(index, item) {
                    var panel = $('<ul></ul>');
                    if (item.root) {
                        rootPanel = '#' + item.id
                    }
                    panel.attr('id', item.id);
                    panel.addClass('menu-panel');
                    panel.width(menuWidth);
                    $(item.children).each(function(index, item) {
                        var link = $('<a></a>');
                        link.attr('class', item.styleClass);
                        link.attr('href', item.href);
                        link.text(item.label);
                        var li = $('<li></li>');
                        li.append(link);
                        panel.append(li)
                    });
                    menu.append(panel)
                })
            } else {
                $(data).each(function(index, item) {
                    var panel = $(item);
                    if (panel.hasClass('menu-panel-root')) {
                        rootPanel = '#' + panel.attr('id')
                    }
                    panel.width(menuWidth);
                    menu.append(item)
                })
            }
            rootPanel = $(rootPanel);
            rootPanel.addClass('menu-panel-root');
            var currentPanel = rootPanel;
            menu.height(rootPanel.height());
            var wrapper = $('<div></div>').addClass('sliding-menu-wrapper').width(data.length * menuWidth);
            menu.wrapInner(wrapper);
            wrapper = $('.sliding-menu-wrapper', menu);
            $('a', self).on('click', function(e) {
                var href = $(this).attr('href'),
                    label = $(this).text();
                if (wrapper.is(':animated')) {
                    e.preventDefault();
                    return
                }
                if (href == '#') {
                    e.preventDefault()
                } else if (href.indexOf('#menu-panel') == 0) {
                    var target = $(href),
                        isBack = $(this).hasClass('back'),
                        marginLeft = parseInt(wrapper.css('margin-left'));
                    if (isBack) {
                        if (href == '#menu-panel-back') {
                            target = currentPanel.prev()
                        }
                        wrapper.stop(true, true).animate({
                            marginLeft: marginLeft + menuWidth
                        }, 'fast')
                    } else {
                        target.insertAfter(currentPanel);
                        if (settings.backLabel === true) {
                            $('.back', target).text(label)
                        } else {
                            $('.back', target).text(settings.backLabel)
                        }
                        wrapper.stop(true, true).animate({
                            marginLeft: marginLeft - menuWidth
                        }, 'fast')
                    }
                    currentPanel = target;
                    menu.stop(true, true).animate({
                        height: target.height()
                    }, 'fast');
                    e.preventDefault()
                }
            });
            return this
        });

        function process(data) {
            var ul = $('ul', data),
                panels = [];
            $(ul).each(function(index, item) {
                var panel = $(item),
                    handler = panel.prev(),
                    id = getNewId();
                if (handler.length == 1) {
                    handler.addClass('nav').attr('href', '#menu-panel-' + id)
                }
                panel.attr('id', 'menu-panel-' + id);
                if (index == 0) {
                    panel.addClass('menu-panel-root')
                } else {
                    panel.addClass('menu-panel');
                    var li = $('<li></li>'),
                        back = $('<a></a>').addClass('back').attr('href', '#menu-panel-back');
                    panel.prepend(back)
                }
                panels.push(item)
            });
            return panels
        }

        function processJSON(data, parent) {
            var root = {
                    id: 'menu-panel-' + getNewId(),
                    children: [],
                    root: (parent ? false : true)
                },
                panels = [];
            if (parent) {
                root.children.push({
                    styleClass: 'back',
                    href: '#' + parent.id
                })
            }
            $(data).each(function(index, item) {
                root.children.push(item);
                if (item.children) {
                    var panel = processJSON(item.children, root);
                    item.href = '#' + panel[0].id;
                    item.styleClass = 'nav';
                    panels = panels.concat(panel)
                }
            });
            return [root].concat(panels)
        }

        function getNewId() {
            var id;
            do {
                id = Math.random().toString(36).substring(3, 8)
            } while (usedIds.indexOf(id) >= 0);
            usedIds.push(id);
            return id
        }
    }
}(jQuery));
