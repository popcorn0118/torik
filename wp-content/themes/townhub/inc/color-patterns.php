<?php
/* banner-php */
/**
 * TownHub: Color Patterns
 */
// https://qiita.com/nanananamememe/items/6bc01e2e02fff8623331
class CTHColorChanger
{
    private $_r = 0, $_g = 0, $_b = 0, $_h = 0, $_s = 0, $_l = 0;

    public function lighten ($color, $lightness)
    {
        $this->setColor($color);
        $l = $this->_l;
        $l += $lightness;
        $this->_l = (100 < $l)?100:$l;
        $this->_getRgb();
        return $this->_decHex($this->_r) .  $this->_decHex($this->_g) .  $this->_decHex($this->_b);
    }

    public function darken ($color, $darkness)
    {
        $this->setColor($color);
        $l = $this->_l;
        $l -= $darkness;
        $this->_l = (0 > $l)?0:$l;
        $this->_getRgb();
        return $this->_decHex($this->_r) .  $this->_decHex($this->_g) .  $this->_decHex($this->_b);
    }

    public function saturate ($color, $per)
    {
        $this->setColor($color);
        $s = $this->_s;
        $s += $per;
        $this->_s = (100 < $s)?100:$s;
        $this->_getRgb();
        return $this->_decHex($this->_r) .  $this->_decHex($this->_g) .  $this->_decHex($this->_b);
    }

    public function desaturate ($color, $per)
    {
        $this->setColor($color);
        $s = $this->_s;
        $s -= $per;
        $this->_s = (0 > $s)?0:$s;
        $this->_getRgb();
        return $this->_decHex($this->_r) .  $this->_decHex($this->_g) .  $this->_decHex($this->_b);
    }

    public function _decHex ($dec)
    {
        return sprintf('%02s', dechex($dec));
    }

    public function adjust_hue($color, $per)
    {
        $this->setColor($color);
        $h = $this->_h;
        $h += $per;
        $this->_h = (360 < $h)?360:$h;
        $this->_getRgb();
        return $this->_decHex($this->_r) .  $this->_decHex($this->_g) .  $this->_decHex($this->_b);
    }

    private function setColor ($color)
    {
        $color = trim($color, '# ');
        $this->_r = hexdec(substr($color, 0, 2));
        $this->_g = hexdec(substr($color, 2, 2));
        $this->_b = hexdec(substr($color, 4, 2));
        $this->_maxRgb = max($this->_r, $this->_g, $this->_b);
        $this->_minRgb = min($this->_r, $this->_g, $this->_b);
        $this->_getHue();
        $this->_getSaturation();
        $this->_getLuminance();
    }

    private function _getHue ()
    {
        $r = $this->_r;
        $g = $this->_g;
        $b = $this->_b;
        $max = $this->_maxRgb;
        $min = $this->_minRgb;
        if ($r === $g && $r === $b) {
            $h = 0;
        } else {
            $mm = $max - $min;
            switch ($max) {
            case $r :
                $h = 60 * ($mm?($g - $b) / $mm:0);
                break;
            case $g :
                $h = 60 * ($mm?($b - $r) / $mm:0) + 120;
                break;
            case $b :
                $h = 60 * ($mm?($r - $g) / $mm:0) + 240;
                break;
            }
            if (0 > $h) {
                $h += 360;
            }
        }
        $this->_h = $h;
    }

    private function _getSaturation ()
    {
        $max = $this->_maxRgb;
        $min = $this->_minRgb;
        $cnt = round(($max + $min) / 2);
        if (127 >= $cnt) {
            $tmp = ($max + $min);
            $s = $tmp?($max - $min) / $tmp:0;
        } else {
            $tmp = (510 - $max - $min);
            $s = ($tmp)?(($max - $min) / $tmp):0;
        }
        $this->_s = $s * 100;
    }

    private function _getLuminance ()
    {
        $max = $this->_maxRgb;
        $min = $this->_minRgb;
        $this->_l = ($max + $min) / 2 / 255 * 100;
    }

    private function _getMaxMinHsl ()
    {
        $s = $this->_s;
        $l = $this->_l;
        if (49 >= $l) {
            $max = 2.55 * ($l + $l * ($s / 100));
            $min = 2.55 * ($l - $l * ($s / 100));
        } else {
            $max = 2.55 * ($l + (100 - $l) * ($s / 100));
            $min = 2.55 * ($l - (100 - $l) * ($s / 100));
        }
        $this->_maxHsl = $max;
        $this->_minHsl = $min;
    }

    private function _getRGB ()
    {
        $this->_getMaxMinHsl();
        $h = $this->_h;
        $s = $this->_s;
        $l = $this->_l;
        $max = $this->_maxHsl;
        $min = $this->_minHsl;
        if (60 >= $h) {
            $r = $max;
            $g = ($h / 60) * ($max - $min) + $min;
            $b = $min;
        } else if (120 >= $h) {
            $r = ((120 - $h) / 60) * ($max - $min) + $min;
            $g = $max;
            $b = $min;
        } else if (180 >= $h) {
            $r = $min;
            $g = $max;
            $b = (($h - 120) / 60) * ($max - $min) + $min;
        } else if (240 >= $h) {
            $r = $min;
            $g = ((240 - $h) / 60) * ($max - $min) + $min;
            $b = $max;
        } else if (300 >= $h) {
            $r = (($h - 240) / 60) * ($max - $min) + $min;
            $g = $min;
            $b = $max;
        } else {
            $r = $max;
            $g = $min;
            $b = ((360 - $h) / 60) * ($max - $min) + $min;
        }
        $this->_r = round($r);
        $this->_g = round($g);
        $this->_b = round($b);
    }
}


if (!function_exists('townhub_hex2rgb')) {
    function townhub_hex2rgb($hex) {
        
        $hex = str_replace("#", "", $hex);
        
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } 
        else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array($r, $g, $b);
        return $rgb;
    }
}
if (!function_exists('townhub_colourBrightness')) {
    
    /*
     * $hex = '#ae64fe';
     * $percent = 0.5; // 50% brighter
     * $percent = -0.5; // 50% darker
    */
    function townhub_colourBrightness($hex, $percent) {
        
        // Work out if hash given
        $hash = '';
        if (stristr($hex, '#')) {
            $hex = str_replace('#', '', $hex);
            $hash = '#';
        }
        
        /// HEX TO RGB
        $rgb = townhub_hex2rgb($hex);
        
        //// CALCULATE
        for ($i = 0; $i < 3; $i++) {
            
            // See if brighter or darker
            if ($percent > 0) {
                
                // Lighter
                $rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1 - $percent));
            } 
            else {
                
                // Darker
                $positivePercent = $percent - ($percent * 2);
                $rgb[$i] = round($rgb[$i] * $positivePercent) + round(0 * (1 - $positivePercent));
            }
            
            // In case rounding up causes us to go to 256
            if ($rgb[$i] > 255) {
                $rgb[$i] = 255;
            }
        }
        
        //// RBG to Hex
        $hex = '';
        for ($i = 0; $i < 3; $i++) {
            
            // Convert the decimal digit to hex
            $hexDigit = dechex($rgb[$i]);
            
            // Add a leading zero if necessary
            if (strlen($hexDigit) == 1) {
                $hexDigit = "0" . $hexDigit;
            }
            
            // Append to the hex string
            $hex.= $hexDigit;
        }
        return $hash . $hex;
    }
}
// https://www.ofcodeandcolor.com/cuttle/
/**
 * Change the brightness of the passed in color
 *
 * $diff should be negative to go darker, positive to go lighter and
 * is subtracted from the decimal (0-255) value of the color
 * 
 * @param string $hex color to be modified
 * @param string $diff amount to change the color
 * @return string hex color
 */
function townhub_adjust_hue($hex, $diff) {
    $rgb = str_split(trim($hex, '# '), 2);
    foreach ($rgb as &$hex) {
        $dec = hexdec($hex);
        if ($diff >= 0) {
            $dec += $diff;
        }
        else {
            $dec -= abs($diff);         
        }
        $dec = max(0, min(255, $dec));
        $hex = str_pad(dechex($dec), 2, '0', STR_PAD_LEFT);
    }
    return '#'.implode($rgb);
}
if (!function_exists('townhub_bg_png')) {
    function townhub_bg_png($color, $input, $output) {
        $image = imagecreatefrompng($input);
        $rgbs = townhub_hex2rgb($color);
        $background = imagecolorallocate($image, $rgbs[0], $rgbs[1], $rgbs[2]);
        
        imagepng($image, $output);
    }
}

if (!function_exists('townhub_stripWhitespace')) {
    
    /**
     * Strip whitespace.
     *
     * @param  string $content The CSS content to strip the whitespace for.
     * @return string
     */
    function townhub_stripWhitespace($content) {
        
        // remove leading & trailing whitespace
        $content = preg_replace('/^\s*/m', '', $content);
        $content = preg_replace('/\s*$/m', '', $content);
        
        // replace newlines with a single space
        $content = preg_replace('/\s+/', ' ', $content);
        
        // remove whitespace around meta characters
        // inspired by stackoverflow.com/questions/15195750/minify-compress-css-with-regex
        $content = preg_replace('/\s*([\*$~^|]?+=|[{};,>~]|!important\b)\s*/', '$1', $content);
        $content = preg_replace('/([\[(:])\s+/', '$1', $content);
        $content = preg_replace('/\s+([\]\)])/', '$1', $content);
        $content = preg_replace('/\s+(:)(?![^\}]*\{)/', '$1', $content);
        
        // whitespace around + and - can only be stripped in selectors, like
        // :nth-child(3+2n), not in things like calc(3px + 2px) or shorthands
        // like 3px -2px
        $content = preg_replace('/\s*([+-])\s*(?=[^}]*{)/', '$1', $content);
        
        // remove semicolon/whitespace followed by closing bracket
        $content = preg_replace('/;}/', '}', $content);
        
        return trim($content);
    }
}

if (!function_exists('townhub_add_rgba_background_inline_style')) {
    function townhub_add_rgba_background_inline_style($color = '#ed5153', $handle = 'skin') {
        $inline_style = '.testimoni-wrapper,.pricing-wrapper,.da-thumbs li  article,.team-caption,.home-centered{background-color:rgba(' . implode(",", hex2rgb($color)) . ', 0.9);}';
        wp_add_inline_style($handle, $inline_style);
    }
}

if (!function_exists('townhub_overridestyle')) {
    function townhub_overridestyle() {

        $theme_color_opt = townhub_get_option('theme-color');

        $colorChanger = new CTHColorChanger();

        $gradient_light = '#'.$colorChanger->saturate($colorChanger->darken($colorChanger->desaturate($colorChanger->adjust_hue($theme_color_opt,-7.9140), 1.1173), 0.5882), 3);
        
        $gradient_dark = '#'.$colorChanger->darken($colorChanger->desaturate($colorChanger->adjust_hue($theme_color_opt,2.0055), 0.9340), 3.1373);


        $second_color = townhub_get_option('theme-color-second'); 

        $third_color = townhub_get_option('theme-color-third');

        $darker_color = '#'.$colorChanger->darken( $theme_color_opt, 30);
        $lighter_color = '#'.$colorChanger->lighten( $theme_color_opt, 10);
    	$inline_style = '

body{
    background: '.townhub_get_option('main-bg-color').';
    color: '.townhub_get_option('body-text-color', '#000').';
}
p{
    color: '.townhub_get_option('paragraph-color', '#878C9F').';
}
.loader-wrap{
    background: '.townhub_get_option('loader-bg-color').';
}
a{
    color: '.townhub_global_var('link_colors', 'regular', true, '#000').';
}
a:hover{
    color: '.townhub_global_var('link_colors', 'hover', true, '#000').';
}
a:active{
    color: '.townhub_global_var('link_colors', 'active', true, '#000').';
}

#cancel-comment-reply-link,
table a,
.post-page-numbers.current,
a.post-page-numbers:hover,
.post-article > .list-single-main-item.block_box .post-page-numbers.current,
.post-article > .list-single-main-item.block_box a.post-page-numbers:hover,
.post-article > .list-single-main-item.block_box .post-opt li a:hover,
.widget_meta ul a, .widget_rss ul a, .widget_recent_entries ul a, .widget_recent_comments ul a,
.post-article > .list-single-main-item.block_box .post-opt-title a:hover,
.nav-holder nav li a.act-link, .nav-holder nav li a:hover, .header-search_btn i, .show-reg-form i, .nice-select:before, .ctb-modal-title span strong, .lost_password a, .custom-form.dark-form label span, .filter-tags input:checked:after, .custom-form .filter-tags input:checked:after, .custom-form .filter-tags label a, .section-subtitle, .footer-social li a, .subfooter-nav li a, #footer-twiit .timePosted a:before, #subscribe-button i, .nice-select .nice-select-search-box:before, .nav-holder nav li a i, .show-lang i, .lang-tooltip a:hover, .main-register-holder .tabs-menu li a i, .header-modal_btn i, .custom-form .log-submit-btn:hover i, .main-search-input-item label i, .header-search-input label i, .location a, .footer-contacts li i, #footer-twiit p.tweet:after, .subscribe-header h3 span, .footer-link i, .footer-widget-posts .widget-posts-date i, .clear-wishlist, .widget-posts-descr-link a:hover, .geodir-category-location a i, .header-modal-top span strong, .cart-btn:hover i, .to-top, .map-popup-location-info i, .infowindow_wishlist-btn, .infobox-raiting_wrap span strong, .map-popup-footer .main-link i, .infoBox-close, .mapnavbtn, .mapzoom-in, .mapzoom-out, .location-btn, .list-main-wrap-title h2 span, .grid-opt li span.act-grid-opt, .reset-filters i, .avatar-tooltip strong, .facilities-list li i, .geodir-opt-list a:hover i, .geodir-js-favorite_btn:hover i, .geodir-category_contacts li span i, .geodir-category_contacts li a:hover, .close_gcc:hover, .listsearch-input-wrap-header i, .listsearch-input-item span.iconn-dec, .more-filter-option-btn i, .clear-filter-btn i, .back-to-filters, .price-rage-wrap-title i, .listsearch-input-wrap_contrl li a i, .geodir-opt-tooltip strong, .listing-features li i, .gdop-list-link:hover i, .show-hidden-sb i, .filter-sidebar-header .tabs-menu li a i, .datepicker--day-name, .scroll-nav li a.act-scrlink, .scroll-nav-wrapper-opt a.scroll-nav-wrapper-opt-btn i, .show-more-snopt:hover, .show-more-snopt-tooltip a i, .breadcrumbs a:before, .list-single-stats li span i, .list-single-main-item-title h3 i, .box-widget-item-header i, .opening-hours ul li.todaysDay span.opening-hours-day, .listing-carousel-button, .list-single-main-item-title i, .list-single-main-item-title:before, .box-widget-item-header:before, .list-author-widget-contacts li span i, .list-author-widget-contacts li i, .contact-infos i, .btn i, .reviews-comments-item-date i, .rate-review i, .chat-widget_input button, .fchat-header h3 a, .custom-form .review-total span input, .photoUpload span i, .bottom-bcw-box_link a:hover, .custom-form label i, .video-box-btn, .claim-widget-link a, .custom-form .quantity span i, .scroll-nav li a.act-scrlink i, .share-holder.hid-share .share-container .share-icon, .sc-btn, .list-single-main-item-title h3 span, .ss-slider-cont, .team-social li a, .team-info h4, .simple-title span, .back-tofilters i, .breadcrumbs-wrapper.block-breadcrumbs:before, .breadcrumbs-wrapper.top-breadcrumbs a:before, .top-breadcrumbs .container:before, .header-sec-link a i, .map-modal-container h3 a, .map-modal-close, .post-opt li i, .cat-item li span, .cat-item li a:hover, .brd-show-share i, .author-social li a, .post-nav-text strong, .post-nav:before, .faq-nav li a.act-scrlink i, .faq-nav li a.act-scrlink:before, .faq-nav li a:hover i, .log-massage a, .cart-total strong, .action-button i, .dashboard-header-stats-item span, .dashboard-header-stats-item i, .add_new-dashboard i, .tfp-btn strong, .user-profile-menu li a i, .logout_btn i, .dashboard-message-text p a, .dashboard-message-time i, .pass-input-wrap span, .fuzone .fu-text i, .radio input[type="radio"]:checked + span:before, .booking-list-message-text h4 span, .dashboard-message-text h4 a:hover, .chat-contacts-item .chat-contacts-item-text span, .recomm-price i, .time-line-icon i, .testi-link, .testimonilas-avatar h4, .testimonilas-text:before, .testimonilas-text:after, .cc-btn, .single-facts_2 .inline-facts-wrap .inline-facts i, .images-collage-title, .collage-image-input i, .process-count, .listing-counter span, .main-search-input-tabs .tabs-menu li.current a, .hero-categories li a i, .main-search-input-item span.iconn-dec, .main-search-button i, .shb, .follow-btn i, .user-profile-header_stats li span, .follow-user-list li:hover a span, .dashboard-tabs .tabs-menu li a span, .bold-facts .inline-facts-wrap .num, .page-scroll-nav nav li a i, .mob-nav-content-btn i, .map-close, .post-opt-title a:hover, .post-author a:hover span, .post-opt a:hover, .breadcrumbs-wrapper a:hover, .reviews-comments-header h4 a:hover, .listing-item-grid_title h3 a:hover, .geodir-category-content h3 a:hover, .footer-contacts li a:hover, .footer-widget-posts .widget-posts-descr a:hover, .footer-link:hover, .geodir-category-opt h4 a:hover, .header-search-button:hover i, .list-author-widget-contacts li a:hover, .contact-infos a:hover, .list-single-author a:hover, .close_sbfilters, .show-lang:hover i, .show-reg-form:hover, .ctb-modal-close:hover, .pac-icon:before, .pi-text h4,
.hero-inputs-wrap .filter-gid-item .flabel-icon i,
.logo-text,
.lfield-icon i,
.nice-select:before,
.nice-select-search-box:before,
.nearby-input-wrap .get-current-city,
.filter-inputs-row label.flabel-icon i,
.filter-inputs-row input[type=checkbox],
.filter-inputs-row input[type=radio],
.listings-loader,
.notification-list-time i,
.notification-msg a,
.ajax-result-message,
.opening-hours .current-status,
.btn-link,
.mdimgs-wrap ul .fu-text i,
.widget-posts-date i,
.footer-widget .widget-posts-descr a:hover,
.townhub-tweet .timePosted a:before,
.townhub-tweet p.tweet:after,
.cat-item span,
.cat-item a:hover,
.currency-tooltip li a:hover,
.show-currency-tooltip:hover span i,
.subfooter-menu-wrap ul.menu li a,
.subfooter-menu-wrap ul.menu li a:hover,
.copyright a,
.copyright a:hover,
.subscribe-button i,
.subscribe-agree-label a,
.edit-listing-link,
.lsingle-block-title:before,
.comment-reply-title-wrap:before,
.review-total-inner .reviews-total-score,
.lbl-hasIcon i,
.message-input button,
.contact-date,
.dashboard-card-content h4 span,
.dashboard-card-content .entry-title a:hover,
.lcheckout-title h2 span,
.ck-form label i,
.ck-form .ck-form-terms label a,
.ck-form input[type="checkbox"]:checked:after,
.lbl-hasIcon i,
.booking-time-picker .tpick-icon,
.prelog-message,
.breadcrumbs-wrapper .woocommerce-breadcrumb a:before,
section.products.related > h2:after, section.products.upsells > h2:after,
.body-townhub ul.products li.product .woocommerce-loop-category__title:hover, .body-townhub ul.products li.product .woocommerce-loop-product__title:hover,
.body-townhub ul.products li.product .price, .body-townhub div.product p.price, .body-townhub div.product span.price,
.body-townhub ul.cart_list li a:hover,
.body-townhub ul.product_list_widget li .woocommerce-Price-amount,
ul.woocommerce-widget-layered-nav-list li span, ul.product-categories li span,
.widget_archive span, .widget_pages span, .widget_nav_menu span,
.widget_archive a:hover, .widget_pages a:hover, .widget_nav_menu a:hover,
table#wp-calendar a:hover,
.single-page-content-wrap .single-page-title-inside:before,
.show-currency-tooltip .currency-symbol, .evticket-available span,.litem-ad,
.flatWeatherPlugin ul.wiForecasts li.wi, .lcard-price strong,
.scroll-nav-bookmark-btn i,
.lshare-shortcode .showshare i, .card-verified i
{
  color: '.$theme_color_opt.'; }

.nav-holder nav li.current-menu-ancestor > a,
.nav-holder nav li.current-menu-item > a {
  color: '.$theme_color_opt.'; }

.dynamic-footer-widget .search-widget .search-submit,
.color-bg, .nice-select .option.selected, .nice-select .option.selected.focus, .nav-holder nav li a:before, .section-separator:before, .footer-widget h3:before, .cluster div, .pagination a.current-page, .pagination a:hover, .irs-bar, .irs-slider, .irs-bar-edge, .catcar-scrollbar .swiper-scrollbar-drag, .checket-cat:after, .scroll-nav li a.act-scrlink:before, .listing-carousel_pagination .swiper-pagination-bullet.swiper-pagination-bullet-active, .box-media-zoom, .daterangepicker td.active, .list-widget-social li a, .contact-socials a, .btn.border-btn:hover, .chat-message.chat-message_user p, .chat-widget-button, .tags-stylwrap a:hover, .custom-form .quantity input.qty, .listing-hero-section .list-single-header-item h1:before, .box-item a, .menu-filters a.menu-filters-active, .promo-link i, .ss-slider-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active, .accordion a.toggle span, .search-widget .search-submit:hover, .reply-item:hover, .comment-reply-link:hover, #progressbar li.active span, #progressbar li:before, .user-profile-menu li a.user-profile-act:before, .new-dashboard-item, .dashboard-title:before, .dhs-controls div:hover, .message-counter, .chat-contacts-item:after, .tc-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active, .tc-pagination2 .swiper-pagination-bullet.swiper-pagination-bullet-active, .down-btn i, .listing-filters a.gallery-filter-active, .single-facts_2 .inline-facts-wrap:before, .single-social li a, .mob-nav-content-btn.slsw_vis, .infobox-status, .header-search_container .header-search-button:hover, .pac-item:hover, .process-item_time-line:before, .lg-actions .lg-next:hover, .lg-actions .lg-prev:hover,
.pagination .current,
.notification-list-remove,
.listing-type-active,
.listing-type-active:hover,
.tabs-working-hours .tabs-menu .active,
.cthiso-filters a.cthiso-filter-active,
.tagcloud a:hover,
.header-search-input-wrap .hero-inputs-wrap .main-search-button:hover,
.loading-indicator span,
.dashboard-chat-app-header,
.your-reply .reply-text,
.contact-item:after,
.ck-progress-bar li.active span,
.cal-months-footer .btn-cal-cancel,
#chat-app .closechat_btn,
.body-townhub div.product .woocommerce-tabs ul.tabs li.active,
.body-townhub div.product form.cart .button,
.cth-woo-content-bot .woocommerce-loop-product__link,
.woocommerce-mini-cart__buttons.buttons a.button.checkout,
.body-townhub nav.woocommerce-pagination .page-numbers:hover,
.body-townhub nav.woocommerce-pagination .page-numbers.current,
.body-townhub .cth-add-to-cart a.product_type_grouped, 
.body-townhub .cth-add-to-cart a.product_type_variable, 
.body-townhub .cth-add-to-cart a.add_to_cart_button,
.btn-unlock-month,
input.button-primary,
.price-head
{
  background: '.$theme_color_opt.'; }

.submit-sec-title:before,
.header-search-input-wrap .hero-inputs-wrap .filter-gid-item input:focus,
.pin, .nice-select:after, .loader-inner, .ed-btn, blockquote, .main-register-holder .tabs-menu li.current, 
.filter-sidebar-header .tabs-menu li.current, .header-search-input input:focus, .listsearch-input-wrap .tabs-menu li.current, 
.btn.border-btn,.litem-ad 
{
  border-color: '.$theme_color_opt.'; }

.body-townhub ul.cart_list .woocommerce-mini-cart-item a.remove {
  color: '.$theme_color_opt.' !important;
  border-color: '.$theme_color_opt.'; }

.body-townhub.woocommerce-cart .wc-proceed-to-checkout a.checkout-button,
.body-townhub.woocommerce-cart table.cart td.actions button.button,
.body-townhub.woocommerce-checkout .place-order button.button.alt {
  background: '.$theme_color_opt.'; }

.body-townhub.woocommerce-cart .wc-proceed-to-checkout a.checkout-button:hover,
.body-townhub.woocommerce-checkout .place-order button.button.alt:hover {
  background: #1aa2fe; }

.woocommerce-mini-cart__buttons.buttons a,
.body-townhub.woocommerce-cart table.cart td.actions .coupon button.button,
.body-townhub.woocommerce-cart .cart-collaterals .cart_totals button.button,
.scroll-nav-bookmark-btn,
.lshare-shortcode .showshare
 {
  background: '.$second_color.'; }

.body-townhub .woocommerce-info {
  border-top-color: '.$theme_color_opt.'; }

.inline-lsiw .filter-sidebar-header .tabs-menu li.current a,
.color2-bg, .list-widget-social li a:hover, .contact-socials a:hover, .accordion a.toggle.act-accordion, .banner-wdget-content a:hover, .inline-lsiw .listsearch-input-wrap_contrl li.current a, .cc-btn:hover, .down-btn:hover, .new-dashboard-item:hover, .header-search-button:hover, .lg-actions .lg-next, .lg-actions .lg-prev, .box-media-zoom:hover, .main-search-button,
.cth-dropdown-options input[type="checkbox"]:checked + label,
.fchat-header,
.body-townhub #review_form input#submit,
.body-townhub #review_form input#submit:hover,
.body-townhub div.product form.cart .button:hover,
.woocommerce-mini-cart__buttons.buttons a.button,
.body-townhub .woocommerce-product-search button,
.btn-book-now,.listing-rating-count-wrap .review-score,.scroll-nav-wrapper-opt a.scroll-nav-wrapper-opt-btn,
.review-score-total span.review-score-total-item,.reviews-comments-item-text .review-score-user span.review-score-user_item,
.to-top,.main-header,.geodir-js-favorite_btn span,.geodir-js-favorite_btn i,.block_box.box-widget-item .townhub-tweet p.tweet:before,
.body-townhub .cth-add-to-cart a.product_type_grouped:hover, .body-townhub .cth-add-to-cart a.product_type_variable:hover, .body-townhub .cth-add-to-cart a.add_to_cart_button:hover,
.header-modal-top,.widget-posts-descr-score,.clbtg,.toggle-filter-btn.tsb_act, .more-filter-option-btn.active-hidden-opt-btn, .inline-lsiw .more-filter-option-btn,
.marker-count,.location-btn
{
  background: '.$second_color.'; }

.share-holder.hid-share .share-container .share-icon:hover, .images-collage-title.color-bg,
.booking-details .msg-reply-to-link {
  color: '.$second_color.'; }

.green-bg, div.datedropper.primary .pick-submit, .footer-bg-pin, .gsd_open, .lstatus-opening, .verified-badge, .toggle-filter-btn.tsb_act, .status.st_online span, .slide-progress, .reply-item, .comment-reply-link, #progressbar li.active:last-child span, .user-profile-menu li a span, .infobox-status.open, .map-popup-location-category.shop-cat, .process-item_time-line:after,
.switchbtn input:checked + .switchbtn-label,
.collage-image-input.hasicon.empty-content,
.header-search-input-wrap .hero-inputs-wrap .main-search-button,
.body-townhub span.onsale,.process-end i,
.count-select-ser
{
  background: '.$third_color.'; }

.clear-wishlist:hover, .lang-tooltip li a:before, .opening-hours ul li.todaysDay span.opening-hours-time, .pricerange .lpricerange-from, .pricerange .lpricerange-to, .tags-stylwrap .tags-title i, .faq-nav li a i, .tfp-det p a, .tfp-btn:before, .green-bg_color, .testi-link:hover,
.woocommerce-grouped-product-list-item__price .woocommerce-Price-amount,
.product_meta .posted_in a,
.product_meta .tagged_as a,
.ad-status-completed
{
  color: '.$third_color.'; }

.orange-bg {
  background: #E9776D; }

.clear-singleinput,
.cth-cleartime:hover,
.cth-cleardate:hover,
.advanced-filter-close {
  color: #E9776D; }

.blue-bg, .map-popup-location-category.gym-cat {
  background: #4C97FD; }

.blue-bg_color {
  color: #4C97FD; }

.red-bg, .gsd_close, .lstatus-closed, .map-popup-location-category.cafe-cat, .infobox-status.close {
  background: #F75C96; }
.red-bg_color,.withdrawal-cancel, .card-verified.cv_not i {
  color: #F75C96; }
.cancel-wdwal-btn,
.yellow-bg, .map-popup-location-category.hotels-cat {
  background: #F8BD38; }

.yellow-bg_color {
  color: #F8BD38; }

.purp-bg, .map-popup-location-category.event-cat {
  background: #BE31E3; }

.purp-bg_color {
  color: #BE31E3; }

.dark-blue-bg {
  background: #3d528b; }

.purp-gradient-bg {
  background: linear-gradient(to left, #DBA9CB, #9451DA); }

.green-gradient-bg {
  background: linear-gradient(to left, '.$third_color.', #'.$colorChanger->lighten( $third_color, 10) .'); }

.blue-gradient-bg {
  background: linear-gradient(to top, '.$gradient_dark.', '.$gradient_light.'); }

.ctb-modal-title {
  background: #4E65A3; }

/*--
    gradient
--*/
.color-gradient-bg,
.gradient-bg, .header-modal .tabs-menu li.current, .scrollbar-inner2 .simplebar-scrollbar:before {
  background-color: '.$gradient_dark.';
  background: -webkit-gradient(linear, 0% 0%, 0% 100%, from('.$gradient_dark.'), to('.$gradient_light.'));
  background: -webkit-linear-gradient(top, '.$gradient_dark.', '.$gradient_light.');
  background: -moz-linear-gradient(top, '.$gradient_dark.', '.$gradient_light.');
  background: -ms-linear-gradient(top, '.$gradient_dark.', '.$gradient_light.');
  background: -o-linear-gradient(top, '.$gradient_dark.', '.$gradient_light.'); }

/*--
    dark gradient
--*/
.gradient-dark {
  background-color: #325096;
  background: -webkit-gradient(linear, 20% 0%, 0% 10%, from(#4E65A3), to(#325096));
  background: -webkit-linear-gradient(right, #4E65A3, #325096);
  background: -moz-linear-gradient(right, #4E65A3, #325096);
  background: -ms-linear-gradient(right, #4E65A3, #325096);
  background: -o-linear-gradient(right, #4E65A3, #325096); }

.green-bg i,
.color-bg i {
  color: #fff; }

.del-bg {
  background: #F75C96; }

.available-cal-months .cal-date-checked,
.available-cal-months .cal-date-checked:hover {
  background: #4db7fe; }

.available-cal-months .cal-date-inside {
  background: #80ccfe;
  color: #fff; }


.main-header{
    background: '.townhub_global_var('header-bg-color', 'rgba', true, '#2F3B59').'; 
    color: '.townhub_get_option('header-text-color', '#fff').';
}



.header-search_btn, .main-header:before,
.header-search_container{
    background: #'.$colorChanger->lighten( townhub_global_var('header-bg-color', 'color', true, '#3d528b'), 20) .'; 
}


.nav-holder nav li a{
    color: '.townhub_global_var('menu_colors', 'regular', true, '#fff').';
}
.nav-holder nav li a:hover{
    color: '.townhub_global_var('menu_colors', 'hover', true, '#fff').';
}
.nav-holder nav li.current-menu-ancestor>a, .nav-holder nav li.current-menu-item>a{
    color: '.townhub_global_var('menu_colors', 'active', true, '#fff').';
}
.nav-holder nav li ul{
    background: '.townhub_global_var('submenu-bg-color', 'rgba', true, '#fff').';
}
.nav-holder nav li ul a{
    color: '.townhub_global_var('submenu_colors', 'regular', true, '#fff').';
}
.nav-holder nav li ul a:hover{
    color: '.townhub_global_var('submenu_colors', 'hover', true, '#fff').';
}
.nav-holder nav li ul li.current-menu-ancestor>a, .nav-holder nav li ul li.current-menu-item>a{
    color: '.townhub_global_var('submenu_colors', 'active', true, '#fff').';
}




.dark-footer{
    background: '.townhub_global_var('footer-bg-color', 'rgba', true, '#325096').';
    color: '.townhub_get_option('footer-text-color', '#fff').';
}
.dark-footer .footer-contacts li a,
.dark-footer .footer-contacts li span,
.dark-footer .footer-social span,
.footer-widget .widget-posts-descr a,
.footer-widget .widget-posts-date,
.footer-widget .wid-tit,
.dynamic-footer-widget,
.dynamic-footer-widget .footer-link,
.sub-footer .copyright,
.dark-footer .show-currency-tooltip,
.footer-widget .footer-contacts-widget p,
.subscribe-agree-label,
.dark-footer .subscribe-header p,
.dark-footer .subscribe-header h3
{
    color: '.townhub_get_option('footer-text-color', '#fff').';
}

.sub-footer{
    background: '.townhub_global_var('subfooter-bg-color', 'rgba', true, '#253966').';
}

.cancel-bg {
    background: #ccc;
}
';   
        // Remove whitespace
        $inline_style = townhub_stripWhitespace($inline_style);
        
        return $inline_style;
    }
}

if( !function_exists('townhub_headerstyle') ){
    function townhub_headerstyle(){
        $header_height = (float)townhub_get_option('header_height', 80);
        $inline_style = '
.main-header{
    height: '.$header_height.'px;
}
#wrapper{
    padding-top: '.$header_height.'px;
}

.admin-bar #wrapper {
  padding-top: '.($header_height+32).'px;
}

.admin-bar .map-container.column-map {
  top: '.($header_height+32).'px; 
}

.admin-bar .hidden-search-column,
.admin-bar .list-main-wrap-header.anim_clw {
  top: '.($header_height+32).'px; }

@media screen and (max-width: 782px) {
  .admin-bar #wrapper {
    padding-top: '.($header_height+46).'px; } 
}

';
        // Remove whitespace
        $inline_style = townhub_stripWhitespace($inline_style);
        
        return $inline_style;
    }
}