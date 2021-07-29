<?php
/* add_ons_php */

defined('ABSPATH') || exit;

// require_once ESB_ABSPATH . 'includes/class-cart-session.php';  

class Esb_Class_Cart
{

    /**
     * Contains an array of cart items.
     *
     * @var array
     */
    public $cart_data = array();
    private $cart_details = array();  
    private $cart_total = 0;
    private $cart_coupon = '';


    protected $_cookie_expiration;


    public function __construct()
    {
        $this->_cookie_expiration = date_i18n('U') + intval( apply_filters( 'esb_cart_expiration', 60 * 60 * 48 ) ); // 48 hours
        $this->init();
    }

    private function init(){
        add_action( 'wp_loaded', array($this, 'get_cart_cookie') );
        add_action( 'wp_loaded', array($this, 'parse_cart_details'), 20 );
        add_action( 'esb_add_to_cart', array($this, 'set_cart_cookie') );
        add_action( 'esb_add_to_cart', array( $this, 'parse_cart_details' ), 20 );
        add_action( 'esb_set_coupon', array($this, 'esb_set_coupon_cookie') );
        add_action( 'esb_set_coupon', array($this, 'parse_cart_details'), 20 );
    }

    public function get_cart_cookie(){
        do_action( 'esb_get_cart_cookie');
        if(isset($_COOKIE["esb_cart"])){
            $cookie_data = stripslashes($_COOKIE['esb_cart']);
            $cart_data = json_decode($cookie_data, true);
            if($cart_data != null) $this->cart_data = $cart_data; 
        }
        if (isset($_COOKIE["esb_coupon"]) && $_COOKIE["esb_coupon"] != '') {
            // $cookie_coupon_data = stripslashes($_COOKIE['esb_coupon']);
            $this->cart_coupon = stripslashes($_COOKIE['esb_coupon']);
        }

    }
    public function set_cart_cookie(){
        esb_setcookie( 'esb_cart', wp_json_encode( $this->get_cart() ), $this->_cookie_expiration );
        
    }

    public function esb_set_coupon_cookie(){
        esb_setcookie( 'esb_coupon', $this->cart_coupon, $this->_cookie_expiration );
    }

    public function set_cart_coupon($code){
        // $expiration = date_i18n('U') + intval( apply_filters( 'esb_coupon_expiration', 60 * 60 * 48 ) ); // 48 hours
        
        $this->cart_coupon = $code;

        do_action('esb_set_coupon');
    }
    public function is_empty(){
        return 0 == count((array)$this->cart_data);
    }
    public function find_cart_item($cart_id = false){
        if (false !== $cart_id) {
            if (is_array($this->cart_data) && isset($this->cart_data[$cart_id])) {
                return $cart_id;
            }
        }
        return '';
    }

    public function generate_cart_item_id($listing_id, $cart_item_data = array()){
        if(townhub_addons_get_option('checkout_individual') == 'yes') return apply_filters('esb_cart_id', 'checkout_individual', $listing_id, $cart_item_data);
        $keys = array($listing_id);
        if (is_array($cart_item_data) && !empty($cart_item_data)) {
            $item_data_key = '';
            foreach ($cart_item_data as $key => $value) {
                // exclude guest and rooms
                if( !in_array($key, array('adults', 'children', 'infants', 'rooms', 'rooms_new', 'rooms_old', 'tickets', 'tour_slots')) ){
                    if (is_array($value) || is_object($value)) {
                        $value = http_build_query($value);
                    }
                    $item_data_key .= trim($key) . trim($value);
                }
            }
            $keys[] = $item_data_key;
        }
        return apply_filters('esb_cart_id', md5(implode('_', $keys)), $listing_id, $cart_item_data);
    }

    public function add_to_cart($listing_id = 0, $cart_item_data = array()){
        try {
            $listing_id   = absint($listing_id);
            $listing_post = get_post($listing_id);

            if (!$listing_post || 'trash' === $listing_post->post_status) {
                return false;
            }
            $cart_item_data = (array) apply_filters('esb_add_cart_item_data', $cart_item_data, $listing_id);
            $cart_id = $this->generate_cart_item_id($listing_id, $cart_item_data);
            $cart_item_key = $this->find_cart_item($cart_id);
            if ($cart_item_key && townhub_addons_get_option('checkout_individual') != 'yes'){
                // update datas
                $new_item = $this->cart_data[$cart_item_key];
                $new_item['checkin']            = $cart_item_data['checkin'];
                $new_item['checkout']           = $cart_item_data['checkout'];
                $new_item['adults']             = $cart_item_data['adults'];
                $new_item['children']           = $cart_item_data['children'];
                $new_item['infants']            = $cart_item_data['infants'];
                $new_item['addservices']        = $cart_item_data['addservices'];
                $new_item['slots']              = $cart_item_data['slots'];
                $new_item['times']              = $cart_item_data['times'];
                $new_item['booking_type']       = $cart_item_data['booking_type'];
                $new_item['price_based']        = $cart_item_data['price_based'];
                $new_item['bk_services']        = $cart_item_data['bk_services'];
                $new_item['bk_qtts']            = $cart_item_data['bk_qtts'];
                $new_item['bkfmenus']            = $cart_item_data['bkfmenus'];
                
                if( !empty($cart_item_data['rooms']) && is_array($cart_item_data['rooms']) ){
                    $new_item['rooms'] = array();
                    foreach ($cart_item_data['rooms'] as $rid => $rqtt) {
                        $new_item['rooms'][] = array($rid => $rqtt);
                    }
                }
                // if( !empty($cart_item_data['tickets']) && is_array($cart_item_data['tickets']) ){
                //     $new_item['tickets'] = array();
                //     foreach ($cart_item_data['tickets'] as $rid => $rqtt) {
                //         $new_item['tickets'][] = array($rid => $rqtt);
                //     }
                // }
                $new_item['tickets']        = $cart_item_data['tickets'];
                // for custom booking
                $new_item['tour_slots']        = $cart_item_data['tour_slots'];
                $new_item['rooms_new']        = $cart_item_data['rooms_new'];
                $new_item['rooms_old']        = $cart_item_data['rooms_old'];
                $this->cart_data[$cart_item_key] = $new_item;
            } else {
                $cart_item_key = $cart_id;
                $this->cart_data[$cart_item_key] = apply_filters(
                    'esb_add_cart_item', array_merge(
                        $cart_item_data, array(
                            'key'                   => $cart_item_key,
                            'listing_id'            => $listing_id,
                            'cart_item_type'        => 'listing_booking'
                        )
                    ), $cart_item_key
                );
            }
            $this->cart_data = apply_filters('esb_cart_data_changed', $this->cart_data);
            // destroy coupon
            $this->empty_coupon();
            do_action( 'esb_add_to_cart', $cart_item_key, $listing_id, $cart_item_data );
            return $cart_item_key;
        } catch (Exception $e) {
            if ($e->getMessage()) {

            }
            return false;
        }
    }

    public function add_plan_to_cart($product_id = 0, $cart_item_data = array()){
        try {
            $product_id   = absint($product_id);
            $product_post = get_post($product_id);
            if (!$product_post || 'trash' === $product_post->post_status) {
                return false;
            }
            $cart_item_data = (array) apply_filters('esb_add_cart_item_data', $cart_item_data, $product_id);
            $cart_id = $this->generate_cart_item_id($product_id, $cart_item_data);
            $cart_item_key = $this->find_cart_item($cart_id);
            if ($cart_item_key && townhub_addons_get_option('checkout_individual') != 'yes'){
                // update datas
                $new_item = $this->cart_data[$cart_item_key];
                $new_item['cart_item_type'] = 'plan';
                // $new_item['price'] = $cart_item_data['adults'];
                // $new_item['children'] = $cart_item_data['children'];
                // $new_item['infants'] = $cart_item_data['infants'];
                
                $this->cart_data[$cart_item_key] = $new_item;
            } else {
                $cart_item_key = $cart_id;
                $this->cart_data[$cart_item_key] = apply_filters(
                    'esb_add_cart_item', array_merge(
                        $cart_item_data, array(
                            'key'                   => $cart_item_key,
                            'product_id'            => $product_id,
                            'cart_item_type'        => 'plan',
                            
                        )
                    ), $cart_item_key
                );
            }
            $this->cart_data = apply_filters('esb_cart_plan_data_changed', $this->cart_data);
            // destroy coupon
            $this->empty_coupon();
            do_action( 'esb_add_to_cart', $cart_item_key, $product_id, $cart_item_data );
            return $cart_item_key;
        } catch (Exception $e) {
            if ($e->getMessage()) {

            }
            return false;
        }



    }

    public function add_ad_to_cart($product_id = 0, $cart_item_data = array()){
        try {
            $product_id   = absint($product_id);
            $product_post = get_post($product_id);
            if (!$product_post || 'trash' === $product_post->post_status) {
                return false;
            }
            $cart_item_data = (array) apply_filters('esb_add_cart_item_data', $cart_item_data, $product_id);
            $cart_id = $this->generate_cart_item_id($product_id, $cart_item_data);
            $cart_item_key = $this->find_cart_item($cart_id);
            if ($cart_item_key && townhub_addons_get_option('checkout_individual') != 'yes'){
                // update datas
                $new_item = $this->cart_data[$cart_item_key];
                $new_item['cart_item_type'] = 'ad';
                // $new_item['price'] = $cart_item_data['adults'];
                // $new_item['children'] = $cart_item_data['children'];
                // $new_item['infants'] = $cart_item_data['infants'];
                
                $this->cart_data[$cart_item_key] = $new_item;
            } else {
                $cart_item_key = $cart_id;
                $this->cart_data[$cart_item_key] = apply_filters(
                    'esb_add_cart_item', array_merge(
                        $cart_item_data, array(
                            'key'                   => $cart_item_key,
                            'product_id'            => $product_id,
                            'cart_item_type'        => 'ad',
                        )
                    ), $cart_item_key
                );
            }
            $this->cart_data = apply_filters('esb_cart_ad_data_changed', $this->cart_data);
            // destroy coupon
            $this->empty_coupon();
            do_action( 'esb_add_to_cart', $cart_item_key, $product_id, $cart_item_data );
            return $cart_item_key;
        } catch (Exception $e) {
            if ($e->getMessage()) {

            }
            return false;
        }



    }



    public function get_cart_data(){
        return $this->cart_data;
    }

    public function get_cart(){
        if ( ! did_action( 'esb_get_cart_cookie' ) ) {
            $this->get_cart_cookie();
        }
        return array_filter( $this->get_cart_data() );
    }
    public function parse_cart_details(){
        $parsed_data = array();
        if(!empty($this->cart_data)){
            foreach ($this->cart_data as $item_key => $item_data) {
                if(isset($item_data['cart_item_type']) && $item_data['cart_item_type'] == 'plan') 
                    $parsed_data[$item_key] = $this->parse_cart_item_plan_data($item_data);
                elseif(isset($item_data['cart_item_type']) && $item_data['cart_item_type'] == 'ad') 
                    $parsed_data[$item_key] = $this->parse_cart_item_ad_data($item_data);
                else
                    $parsed_data[$item_key] = $this->parse_cart_item_booking_data($item_data);
            }
        }
            
        $this->cart_details = $parsed_data;

        $this->calculate_cart_total();

    }
    protected function parse_cart_item_ad_data($data){
        if(is_array($data) && isset($data['product_id'])){
            $product_id = $data['product_id'];
            $ad_package = get_post_meta( $product_id, ESB_META_PREFIX.'plan_id', true );
            $icon_img = get_term_meta( $ad_package, ESB_META_PREFIX.'icon_img', true );

            $data['thumbnail'] = '';
            if(isset($icon_img['id'])) $data['thumbnail'] = wp_get_attachment_image( $icon_img['id'] ,  'post-thumbnail', false, array('class'=>'respimg') );
            $data['title'] = get_the_title($product_id);
            //checkout event listing
            $data['price'] = get_post_meta($product_id, '_price', true);
            // $data['limit'] = get_post_meta($product_id, ESB_META_PREFIX.'llimit', true);
            // $data['unlimited'] = get_post_meta($product_id, ESB_META_PREFIX.'lunlimited', true);
            $data['interval'] = get_term_meta($ad_package, ESB_META_PREFIX.'ad_interval', true);
            $data['period'] = get_term_meta($ad_package, ESB_META_PREFIX.'ad_period', true);
            

            if($data['interval']){
                $expire = townhub_add_ons_cal_next_date('', $data['period'], $data['interval']) ;
            }else{
                $expire = townhub_add_ons_cal_next_date('', 'day', townhub_addons_get_option('listing_expire_days') );
            }
            

            $data['expired'] = $expire;
            $data['period_text'] = '';
            if(!empty($data['interval']) && !empty($data['period'])){
                $data['period_text'] = townhub_add_ons_get_plan_period_text( $data['interval'], $data['period'] );
            }
            
            $data['subtotal'] = $data['price'] * $data['quantity'];
            $data['subtotal_vat'] = $data['subtotal'] * (float)apply_filters( 'esb_ad_vat', $this->vat_default(), $product_id )/100;
            
            $data['price_total'] = $data['subtotal'] + $data['subtotal_vat'];


            
            $data = (array)apply_filters( 'esb_listing_ad_details', $data, $product_id );
            
        }
        return $data;
    }
    protected function parse_cart_item_booking_data($data){
        // var_dump($data);
        if(is_array($data) && isset($data['listing_id'])){
            $listing_id = $data['listing_id'];
            $data['permalink'] = get_permalink($listing_id);
            $data['thumbnail'] = '';
            if(has_post_thumbnail($listing_id)) $data['thumbnail'] = get_the_post_thumbnail( $listing_id, 'post-thumbnail', array('class'=>'respimg'));
            $data['title'] = get_the_title($listing_id);
            $data['address'] = get_post_meta($listing_id, ESB_META_PREFIX.'address', true);
            // rating + address string
            //checkout event listing
            $listing_price = 0;
            if(isset($data['lprice']) && !empty($data['lprice']) && !empty($data['qty'])){
                $listing_price = $data['lprice'] * $data['qty'];
            }

            // for new event tickets
            $tickets_data = array();
            $tickets_price = 0;
            $tickets = get_post_meta( $listing_id, ESB_META_PREFIX.'tickets', true );
            if( !empty($data['tickets']) && !empty($tickets) ){
                foreach ($data['tickets'] as $tid => $tqtt) {
                    // search for ticket _id
                    $tkkey = array_search($tid, array_column($tickets, '_id'));
                    if( false !== $tkkey ){
                        $tkobj = array(
                            '_id'               => $tid,
                            'title'             => $tickets[$tkkey]['name'],
                            'price'             => floatval( $tickets[$tkkey]['price'] ),
                            'quantity'          => (int)$tqtt,
                        );

                        $tickets_price += $tkobj['quantity'] * $tkobj['price'];
                        $tickets_data[] = $tkobj;
                    }
                        
                }
            }

            // $data['tickets_listing'] = $tickets;
            // $data['tickets_new'] = $tickets_data;
            $data['tickets'] = $tickets_data;

            // for res menu
            $resmenus_data = array();
            $resmenus_price = 0;
            $resmenus = (array)get_post_meta( $listing_id, ESB_META_PREFIX.'resmenus', true );
            if( !empty($data['bkfmenus']) && !empty($resmenus) ){
                foreach ($data['bkfmenus'] as $tid => $tqtt) {
                    // search for ticket _id
                    $tkkey = array_search($tid, array_column($resmenus, '_id'));
                    if( false !== $tkkey ){
                        $tkobj = array(
                            '_id'               => $tid,
                            'title'             => $resmenus[$tkkey]['name'],
                            'price'             => floatval( $resmenus[$tkkey]['price'] ),
                            'quantity'          => (int)$tqtt,
                            'adults'            => (int)$tqtt,
                        );

                        $resmenus_price += $tkobj['quantity'] * $tkobj['price'];
                        $resmenus_data[] = $tkobj;
                    }
                        
                }
            }
            $data['bk_menus'] = $resmenus_data;

            // for new tour_slots
            $tour_slots_data = array();
            $tour_slots_price = 0;
            $tour_slots = townhub_addons_get_tour_slots( $listing_id, $data['checkin'] );
            if( !empty($data['tour_slots']) && !empty($tour_slots) ){
                $ltour_price = get_post_meta( $listing_id, '_price', true );
                $child_price = get_post_meta( $listing_id, ESB_META_PREFIX.'children_price', true );
                if( $child_price === '' ){
                    $child_price = $ltour_price;
                }
                foreach ($data['tour_slots'] as $tid => $tourb) {
                    // search for ticket _id
                    $tkkey = array_search($tid, array_column($tour_slots, '_id'));
                    if( false !== $tkkey && ( (int)$tourb['adults'] + (int)$tourb['children'] ) > 0 ){
                        $tkobj = array(
                            '_id'               => $tid,
                            'title'             => sprintf( __( '%s - %s', 'townhub-add-ons' ), $tour_slots[$tkkey]['start'], $tour_slots[$tkkey]['end'] ),
                            'price'             => floatval( $ltour_price ),
                            'adults'            => (int)$tourb['adults'],
                            'child_price'       => floatval( $child_price ),
                            'children'          => (int)$tourb['children'],
                            'start'             => $tour_slots[$tkkey]['start'],
                            'end'               => $tour_slots[$tkkey]['end'],
                        );

                        $tour_slots_price += $tkobj['adults'] * $tkobj['price'] + $tkobj['children'] * $tkobj['child_price'];
                        $tour_slots_data[] = $tkobj;
                    }
                        
                }
            }
            $data['tour_slots'] = $tour_slots_data;

            // for room booking
            //end checkout event listing
            $rooms_data = array();
            $rooms_price = 0;
            if(isset($data['rooms']) && !empty($data['rooms'])){
                foreach ($data['rooms'] as $rid => $rqtt) {
                    $rprice = get_post_meta($rid, '_price', true);
                    // get meta price
                    $rckin = Esb_Class_Date::format($data['checkin']);
                    $room_dates_metas = get_post_meta( $rid, ESB_META_PREFIX.'calendar_metas', true );
                    if( isset($room_dates_metas[$rckin]) && isset($room_dates_metas[$rckin]['price']) && $room_dates_metas[$rckin]['price'] !== '' ){
                        $rprice = $room_dates_metas[$rckin]['price'];
                    }
                    $roomobj = array(
                        'IDtitle'         => $rid,
                        ''         => get_the_title( $rid ),
                        'price'         => $rprice,
                        'quantity'      => (int)$rqtt,
                    );

                    $rooms_price += (int)$roomobj['quantity'] * floatval($roomobj['price']);
                    $rooms_data[] = $roomobj;
                }
                // $nights = townhub_addons_booking_nights($data['checkin'], $data['checkout']);
                // // if no checkout -> nights <= 0
                // if((int)$nights < 1) $nights = 1;
                // $data['rooms'] = $rooms_data;
                // $data['nights'] = $nights;

                // $rooms_price *= $nights;
            }
            $nights = townhub_addons_booking_nights($data['checkin'], $data['checkout']);
            // if no checkout -> nights <= 0
            if((int)$nights < 1) $nights = 1;
            $data['rooms'] = $rooms_data;
            $data['nights'] = $nights;

            $rooms_price *= $nights;

            // for new night_person room
            
            $rooms_person_price = 0;
            $rooms_person_data = array();
            if(isset($data['rooms_new']) && !empty($data['rooms_new'])){
                foreach ($data['rooms_new'] as $rid => $rdata) {
                    $roomobj = array(
                        'ID'            => $rid,
                        'title'         => get_the_title( $rid ),
                        // 'adults'        => $rdata['adults'],
                        // 'children'      => $rdata['children'],
                        // 'infant'        => $rdata['infant'],
                    );
                    $rdates = array();
                    foreach ($rdata as $rkey => $rval) {
                        if( $rkey === 'adults') 
                            $roomobj['adults'] = $rval;
                        elseif( $rkey === 'children') 
                            $roomobj['children'] = $rval;
                        elseif( $rkey === 'infant') 
                            $roomobj['infant'] = $rval;
                        elseif( strlen($rkey) == 10 && is_array($rval) ){
                            $rdates[$rkey] = $rval;

                        }
                    }
                    $roomobj['rdates'] = $rdates;
                    // var_dump($rdates);
                    // calculate price
                    if( !empty($rdates) ){
                        foreach ($rdates as $dkey => $dval) {
                            $rooms_person_price += (int)$roomobj['adults'] * floatval($dval['adults']) + (int)$roomobj['children'] * floatval($dval['children']) + (int)$roomobj['infant'] * floatval($dval['infant']);
                            // if( $dkey === 'adults' ) 
                            //     $rooms_person_price += (int)$roomobj['adults'] * floatval($dval);
                            // elseif( $dkey === 'children' ) 
                            //     $rooms_person_price += (int)$roomobj['children'] * floatval($dval);
                            // elseif( $dkey === 'infant' ) 
                            //     $rooms_person_price += (int)$roomobj['infant'] * floatval($dval);
                            // var_dump($dval);
                        }
                    }

                    $rooms_person_data[] = $roomobj;
                }
                // $nights = townhub_addons_booking_nights($data['checkin'], $data['checkout']);
                // // if no checkout -> nights <= 0
                // if((int)$nights < 1) $nights = 1;
                // $data['rooms'] = $rooms_data;
                // $data['nights'] = $nights;

                // $rooms_price *= $nights;
            }

            $data['rooms_person_data'] = $rooms_person_data;

            // for rooms with date prices
            $rooms_old_price = 0;
            $rooms_old_data = array();
            if(isset($data['rooms_old']) && !empty($data['rooms_old'])){
                foreach ($data['rooms_old'] as $rid => $rdata) {
                    $roomobj = array(
                        'ID'            => $rid,
                        'title'         => get_the_title( $rid ),
                    );
                    $rdates = array();
                    foreach ($rdata as $rkey => $rval) {
                        if( $rkey === 'qtt') 
                            $roomobj['quantity'] = (int)$rval;
                        elseif( strlen($rkey) == 10 ){
                            $rdates[$rkey] = $rval;

                        }
                    }
                    $roomobj['rdates'] = $rdates;
                    // calculate price
                    if( !empty($rdates) ){
                        foreach ($rdates as $dkey => $dval) {
                            $rooms_old_price += $roomobj['quantity'] * floatval($dval) ;
                        }
                    }

                    $rooms_old_data[] = $roomobj;
                }
            }

            $data['rooms_old_data'] = $rooms_old_data;

            // tour booking prices
            $_price = get_post_meta( $listing_id, '_price', true );
            $_price = floatval($_price);
            $children_price = get_post_meta( $listing_id, ESB_META_PREFIX .'children_price', true );
            $infant_price = get_post_meta( $listing_id, ESB_META_PREFIX .'infant_price', true );

            $price_based = isset($data['price_based']) && $data['price_based'] != '' ? $data['price_based'] : 'per_night';
            if( isset($data['booking_type']) && $data['booking_type'] == 'tour' ){
                $children_price = get_post_meta( $listing_id, ESB_META_PREFIX .'children_price', true );
                $infant_price = get_post_meta( $listing_id, ESB_META_PREFIX .'infant_price', true );

                // price based date
                $checkin_str = townhub_addons_re_format_date($data['checkin'], 'Ymd');
                $tour_dates = get_post_meta( $listing_id, ESB_META_PREFIX .'tour_dates', true );
                $tour_dates_metas = get_post_meta( $listing_id, ESB_META_PREFIX .'tour_dates_metas', true );

                if( !empty($tour_dates) && !empty($tour_dates_metas) && is_array($tour_dates_metas) && $checkin_str != '' && false !== strpos($tour_dates, $checkin_str) && isset($tour_dates_metas[$checkin_str]) ){
                    $booking_date_metas = $tour_dates_metas[$checkin_str];
                    if(isset($booking_date_metas['price_adult']) && $booking_date_metas['price_adult'] !== '') $_price = $booking_date_metas['price_adult'];
                    if(isset($booking_date_metas['price_children']) && $booking_date_metas['price_children'] !== '') $children_price = $booking_date_metas['price_children'];
                    if(isset($booking_date_metas['price_infant']) && $booking_date_metas['price_infant'] !== '') $infant_price = $booking_date_metas['price_infant'];
                }

                $data['adult_price'] = floatval($_price);
                $data['children_price'] = floatval($children_price);
                $data['infant_price'] = floatval($infant_price);

                $listing_price = (int)$data['adults']*$data['adult_price'] + (int)$data['children']*$data['children_price'] + (int)$data['infants']*$data['infant_price'] ;
            }elseif( isset($data['booking_type']) && $data['booking_type'] == 'general' ){
                // $price_based = isset($data['price_based']) && $data['price_based'] != '' ? $data['price_based'] : 'per_night';
                $nights = townhub_addons_booking_nights($data['checkin'], $data['checkout']);
                $days = $nights + 1;

                $data['nights'] = $nights;
                $data['days']   = $days;

                $children_price = get_post_meta( $listing_id, ESB_META_PREFIX .'children_price', true );
                $infant_price = get_post_meta( $listing_id, ESB_META_PREFIX .'infant_price', true );


                if($price_based == 'none'){
                    $listing_price = 0;
                    
                }elseif($price_based == 'per_person'){
                    $data['adult_price'] = floatval($_price);
                    $data['children_price'] = floatval($children_price);
                    $data['infant_price'] = floatval($infant_price);
                    $listing_price = (int)$data['adults']*$data['adult_price'] + (int)$data['children']*$data['children_price'] + (int)$data['infants']*$data['infant_price'] ;
                }elseif($price_based == 'listing'){
                    $listing_price = $_price;
                    $data['price'] = $_price;
                    if( !empty($data['bk_qtts']) ) $listing_price *= $data['bk_qtts'];
                }else{
                    $countTo = $nights;
                    if( $price_based == 'per_day' || $price_based == 'day_person' ) $countTo = $days;

                    $listing_dates = get_post_meta( $listing_id, ESB_META_PREFIX .'listing_dates', true );
                    $listing_dates_metas = get_post_meta( $listing_id, ESB_META_PREFIX .'listing_dates_metas', true );

                    // $new_price = 0;
                    $day_prices = array();
                    $adult_prices = array();
                    $children_prices = array();
                    $infant_prices = array();
                    for ($i = 0; $i < $countTo ; $i++) { 
                        $cal_date = Esb_Class_Date::modify($data['checkin'], $i, 'Ymd');
                        if( !empty($listing_dates) && !empty($listing_dates_metas) && is_array($listing_dates_metas) && $cal_date != '' && false !== strpos($listing_dates, $cal_date) && isset($listing_dates_metas[$cal_date]) ){
                            $cal_date_metas = $listing_dates_metas[$cal_date];

                            if( $price_based == 'per_night' || $price_based == 'per_day' ){
                                if(isset($cal_date_metas['price']) && $cal_date_metas['price'] !== ''){
                                    // $new_price += floatval($cal_date_metas['price']);
                                    $day_prices[$cal_date] = floatval($cal_date_metas['price']);
                                }else{
                                    // $new_price += floatval($_price);
                                    $day_prices[$cal_date] = floatval($_price);
                                }
                            }else{ //if(  $price_based == 'day_person' || $price_based == 'night_person'  ){

                                if(isset($cal_date_metas['price']) && $cal_date_metas['price'] !== ''){
                                    // $new_price += floatval($cal_date_metas['price']);
                                    $adult_prices[$cal_date] = floatval($cal_date_metas['price']);
                                }else{
                                    // $new_price += floatval($_price);
                                    $adult_prices[$cal_date] = floatval($_price);
                                }

                                if(isset($cal_date_metas['price_children']) && $cal_date_metas['price_children'] !== ''){
                                    $children_prices[$cal_date] = floatval($cal_date_metas['price_children']);
                                }else{
                                    $children_prices[$cal_date] = floatval($children_price);
                                }

                                if(isset($cal_date_metas['price_infant']) && $cal_date_metas['price_infant'] !== ''){
                                    $infant_prices[$cal_date] = floatval($cal_date_metas['price_infant']);
                                }else{
                                    $infant_prices[$cal_date] = floatval($infant_price);
                                }

                                // 'per_person'            => __('Per person', 'townhub-add-ons'), 
                                // 'per_night'             => __('Per night', 'townhub-add-ons'), 
                                // 'night_person'          => __('Per person/night', 'townhub-add-ons'), 
                                // 'per_day'               => __('Per day', 'townhub-add-ons'), 
                                // 'day_person'            => __('Per person/day', 'townhub-add-ons'), 
                                // 'none'                  => __('No listing price', 'townhub-add-ons'), 

                            }
                            // end check for per_day per_night else

                        }else{
                            if( $price_based == 'per_night' || $price_based == 'per_day' ){
                                $day_prices[$cal_date] = floatval($_price);
                            }else{
                                $adult_prices[$cal_date] = floatval($_price);
                                $children_prices[$cal_date] = floatval($children_price);
                                $infant_prices[$cal_date] = floatval($infant_price);
                            }
                        }
                        // end has date metas else
                    }
                    // end for loop
                    if(!empty($day_prices)){
                        $data['day_prices'] = $day_prices;
                        $listing_price = array_sum($day_prices);
                    }elseif( !empty($adult_prices) && !empty($children_prices) && !empty($infant_prices) ){
                        $data['adult_prices'] = $adult_prices;
                        $data['children_prices'] = $children_prices;
                        $data['infant_prices'] = $infant_prices;
                        $adult_sum = array_sum($adult_prices);
                        $children_sum = array_sum($children_prices);
                        $infant_sum = array_sum($infant_prices);
                        $listing_price = $adult_sum * (int)$data['adults'] + $children_sum * (int)$data['children'] + $infant_sum * (int)$data['infants'];
                    }
                    // $listing_price = $new_price;
                }
                // end price_based = none else

            }else{
                $listing_price = floatval($_price);
                
            }

            // slots data
            // $slots = get_post_meta($listing_id, ESB_META_PREFIX.'slots', true);
            $listing_slots = Esb_Class_Booking_CPT::listing_time_slots($listing_id, $data['checkin']);
            $new_slots  = array();
            if( is_array($listing_slots) && !empty($listing_slots) ){
                
                $selected_slots = (isset($data['slots']) && !empty($data['slots']) ) ? (array)$data['slots'] : array();
                foreach ($selected_slots  as $selected_slot) {
                    $slkey = array_search($selected_slot, array_column($listing_slots,  'slot_id'));
                    if( false !== $slkey ){
                        $new_slots[] = $listing_slots[$slkey]['slot_id'];
                    }
                }
                $data['slots'] = $new_slots;
            }

            //end checkout event listing
            // $rooms_data = array();
            // $rooms_price = 0;
            // if(isset($data['rooms']) && !empty($data['rooms'])){
            //     foreach ($data['rooms'] as $rid => $rqtt) {
            //         $roomobj = array(
            //             'ID'         => $rid,
            //             'title'         => get_the_title( $rid ),
            //             'price'         => get_post_meta( $rid, '_price', true ),
            //             'quantity'      => (int)$rqtt,
            //         );

            //         $rooms_price += $roomobj['quantity'] * $roomobj['price'];
            //         $rooms_data[] = $roomobj;
            //     }
            // }
            // $nights = townhub_addons_booking_nights($data['checkin'], $data['checkout']);
            // // if no checkout -> nights <= 0
            // if((int)$nights < 1) $nights = 1;

            // $data['rooms'] = $rooms_data;
            // $data['nights'] = $nights;

            // $rooms_price *= $nights;

            $services = get_post_meta($listing_id, ESB_META_PREFIX.'lservices', true);
            // var_dump($services);
            $total_services = 0;
            // new services
            $bk_services_data = array();
            $new_ser_total = 0;
            if(isset($services) && is_array($services) && !empty($services)) {
                $addservices = (isset($data['addservices']) && !empty($data['addservices']) ) ? (array)$data['addservices'] : array();
                foreach ($addservices  as $key => $item_serv) {
                    $lserid = array_search($item_serv, array_column($services,  'service_id'));

                    if( $lserid !== false ){
                        $bkedSer = $services[$lserid];
                        $total_services += (float)$bkedSer['service_price'];
                    }
                }

                // new services
                if( !empty($data['bk_services']) ){
                    foreach ($data['bk_services']  as $sid => $ser_qtt) {
                        if( empty($ser_qtt) ) continue;
                        $lserid = array_search($sid, array_column($services,  'service_id'));
                        if( $lserid !== false ){
                            $bkedSer = $services[$lserid];
                            $bk_services_data[] = array(
                                '_id' => $bkedSer['service_id'],
                                'service_id' => $bkedSer['service_id'],
                                'quantity' => $ser_qtt,
                                'title' => $bkedSer['service_name'],
                                'price' => $bkedSer['service_price'],
                            );
                            $new_ser_total += floatval($bkedSer['service_price']) * $ser_qtt;
                        }
                    }
                }
            }

            $data['book_services'] = $bk_services_data;

            // $rooms_price *= $nights;
            if ( $rooms_price != 0 ) {
                $listing_price = $rooms_price;
                // $data['subtotal'] = $listing_price;
                // var_dump( $data['subtotal']);
            }

            // new rooms per person
            if( !empty($rooms_person_price) ){
                $listing_price = $rooms_person_price;
            }
            if( !empty($rooms_old_price) ){
                $listing_price = $rooms_old_price;
            }
            // restaurant menus
            if( !empty($resmenus_price) ){
                $listing_price = $resmenus_price;
            }
            if( !empty($tickets_price) ){
                $listing_price = $tickets_price;
            }

            if( !empty($tour_slots_price) ){
                $listing_price = $tour_slots_price;
            }
            if( $price_based == 'hour_person' ){
                if( !empty($new_slots) ){
                    $slots_count = count($new_slots);
                    $data['adult_price'] = floatval($_price);
                    $data['children_price'] = floatval($children_price);
                    $data['infant_price'] = floatval($infant_price);

                    $listing_price = (int)$data['adults'] * $slots_count  * $data['adult_price'] + (int)$data['children'] * $slots_count  * $data['children_price'] + (int)$data['infants'] * $slots_count  * $data['infant_price'];
                    
                }else{
                    $listing_price = 0;
                }
            }elseif( !empty($new_slots) ){
                $listing_price = count($new_slots) * $_price;
                if( !empty($tickets_price) ){
                    $listing_price += $tickets_price;
                }
                // for menus
                if( !empty($resmenus_price) ){
                    $listing_price += $resmenus_price;
                }
            }
            // else{
            //     $listing_price = $rooms_price;
            // }

            // no price for time slot
            if( isset($data['booking_type']) && $data['booking_type'] == 'slot' )
                $listing_price = 0;

            $listing_price += $total_services + $new_ser_total;

            $data['subtotal'] = apply_filters( 'esb_booking_subtotal', $listing_price, $listing_id, $data );


            // check for discount
            $cart_discount_percent = 0;
            $cart_discount_amount = 0;
            if( isset($this->cart_coupon) && $this->cart_coupon != '' ){
                $coupon_post = get_posts(
                    array(
                        'post_type'         => 'cthcoupons',
                        'posts_per_page'    => 1,
                        'post_status'       => 'publish',
                        'fields'            => 'ids',
                        'meta_query'        => array(
                            'relation' => 'AND',
                            array(
                                'key'           => ESB_META_PREFIX.'coupon_code',
                                'value'         => $this->cart_coupon,
                            ),
                            array(
                                'relation' => 'OR',
                                array(
                                    'key'           => ESB_META_PREFIX.'for_coupon_listing_id',
                                    'value'         => $listing_id,
                                ),
                                array(
                                    'key'           => ESB_META_PREFIX.'for_all_listing',
                                    'value'         => 'yes',
                                ),
                            ),
                                
                        )
                    )
                );


                if(!empty($coupon_post)){
                    $coupon_id = reset($coupon_post);
                    // double check for listing coupon
                    // $listing_coupon_ids = get_post_meta($listing_id, ESB_META_PREFIX.'coupon_ids', true);
                    // if(is_array($listing_coupon_ids) && in_array($coupon_id, $listing_coupon_ids)){

                        $expire_date = get_post_meta($coupon_id, ESB_META_PREFIX.'coupon_expiry_date', true);
                        $not_expired_yet = townhub_addons_compare_dates($expire_date ,'now','>=');
                        $coupon_qty = get_post_meta($coupon_id, ESB_META_PREFIX.'coupon_qty', true);
                        
                        if( $coupon_id != '' && (int)$coupon_qty > 0 && $not_expired_yet ){
                            $discount_type = get_post_meta($coupon_id, ESB_META_PREFIX.'discount_type', true);
                            $discount_amount = get_post_meta($coupon_id, ESB_META_PREFIX.'dis_amount', true);
                            if($discount_type == 'percent'){
                                $cart_discount_percent = (float)$discount_amount;
                                $cart_discount_amount = 0;
                                
                            }else if($discount_type == 'fixed_cart'){
                                $cart_discount_percent = 0;
                                $cart_discount_amount = (float)$discount_amount;
                            }
                        }

                    // }
                    // // end check coupon in listing meta
                }
                // is coupon post exists
            }
            // if cart coupon code exists

            // add_filter( 'esb_listing_fees', function($fee){
            //     return 0; // percent
            // } );
            // add_filter( 'esb_listing_vat', function($vat){
            //     return 10; // percent
            // } );

            $data['subtotal_fee'] = $data['subtotal'] * (float)apply_filters( 'esb_listing_fees', townhub_addons_get_option('service_fee'), $listing_id )/100;
            if(townhub_addons_get_option('booking_vat_include_fee') == 'yes'){
                $data['subtotal_vat'] = ($data['subtotal'] + $data['subtotal_fee'])* (float)apply_filters( 'esb_listing_vat', $this->vat_default(), $listing_id )/100;
            }
            else{
                $data['subtotal_vat'] = $data['subtotal'] * (float)apply_filters( 'esb_listing_vat', $this->vat_default(), $listing_id )/100;
            }

            $data['price_total'] = $data['subtotal'] + $data['subtotal_fee'] + $data['subtotal_vat'] ;

            // NOTE: services is without vat

            if($cart_discount_percent > 0){
                
                $data['amount_of_discount'] = floatval( $data['price_total'] ) * ($cart_discount_percent/100);

                $data['price_total'] = floatval( $data['price_total'] ) * (100 - $cart_discount_percent)/100;
            }elseif($cart_discount_amount > 0){
                $data['amount_of_discount'] = $cart_discount_amount;

                $data['price_total'] = floatval( $data['price_total'] ) - $cart_discount_amount ;
            }
            // end apply discount

            $data = (array)apply_filters( 'esb_listing_booking_details', $data, $listing_id );
            
        }
        return $data;
    }
    protected function parse_cart_item_plan_data($data){
        if(is_array($data) && isset($data['product_id'])){
            $product_id = $data['product_id'];
            $data['permalink'] = get_permalink($product_id);
            $data['thumbnail'] = '';
            if(has_post_thumbnail($product_id)) $data['thumbnail'] = get_the_post_thumbnail( $product_id, 'post-thumbnail', array('class'=>'respimg'));
            $data['title'] = get_the_title($product_id);


            // $data['address'] = get_post_meta($product_id, ESB_META_PREFIX.'address', true);
            // plan post meta
            $data['price'] = get_post_meta($product_id, '_price', true);
            $data['limit'] = get_post_meta($product_id, ESB_META_PREFIX.'llimit', true);
            $data['unlimited'] = get_post_meta($product_id, ESB_META_PREFIX.'lunlimited', true);
            $data['interval'] = get_post_meta($product_id, ESB_META_PREFIX.'interval', true);
            $data['period'] = get_post_meta($product_id, ESB_META_PREFIX.'period', true);
            $data['never_expire'] = get_post_meta($product_id, ESB_META_PREFIX.'lnever_expire', true);
            $data['is_recurring'] = get_post_meta($product_id, ESB_META_PREFIX.'is_recurring', true);
            $data['trial_interval'] = get_post_meta($product_id, ESB_META_PREFIX.'trial_interval', true);
            $data['trial_period'] = get_post_meta($product_id, ESB_META_PREFIX.'trial_period', true);
            $data['author_fee'] = get_post_meta($product_id, ESB_META_PREFIX.'author_fee', true);

            if(isset($data['yearly_price']) && $data['yearly_price'] === '1'){
                $yearly_sale = get_post_meta( $product_id, ESB_META_PREFIX.'yearly_sale', true );

                $data['price'] = townhub_addons_calculate_yearly_price($data['price'], $data['period'], $data['interval'], $yearly_sale);

                $data['interval'] = '1';
                $data['period'] = 'year';
                // $data['yearly_price'] = '1';
            }

            $data['limit_text'] = $data['unlimited'] ? __( 'Unlimited', 'townhub-add-ons' ) : $data['limit'] ;

            if($data['interval']){
                $expire = townhub_add_ons_cal_next_date('', $data['period'], $data['interval']) ;
            }else{
                $expire = townhub_add_ons_cal_next_date('', 'day', townhub_addons_get_option('listing_expire_days') );
            }
            if(  $data['never_expire'] ) $expire = 'NEVER';

            $data['expired'] = $expire;
            $data['period_text'] = '';
            if(!empty($data['interval']) && !empty($data['period'])){
                $data['period_text'] = townhub_add_ons_get_plan_period_text( $data['interval'], $data['period'] );
            }
            
            $data['trial_text'] = '';
            if(!empty($data['trial_interval']) && !empty($data['trial_period'])){
                $data['trial_text'] = townhub_add_ons_get_plan_trial_text( $data['trial_interval'], $data['trial_period'] );
            }

            $data['subtotal'] = $data['price'] * $data['quantity'];

            // check for discount
            $cart_discount_percent = 0;
            $cart_discount_amount = 0;
            if( isset($this->cart_coupon) && $this->cart_coupon != '' ){
                $coupon_post_ids = get_posts(
                    array(
                        'post_type'         => 'cthcoupons',
                        'posts_per_page'    => 1,
                        'post_status'       => 'publish',
                        'fields'            => 'ids',
                        'meta_query'        => array(
                            array(
                                'key'           => ESB_META_PREFIX.'coupon_code',
                                'value'         => $this->cart_coupon,
                            ),
                            array(
                                'key'           => ESB_META_PREFIX.'plan_id',
                                'value'         => $product_id,
                            )
                        )
                    )
                );
                if(!empty($coupon_post_ids)){
                    $coupon_id = reset($coupon_post_ids);
                    // double check for listing coupon
                    $coupon_ids = get_post_meta($product_id, ESB_META_PREFIX.'coupon_ids', true);
                    if(is_array($coupon_ids) && in_array($coupon_id, $coupon_ids)){

                        $expire_date = get_post_meta($coupon_id, ESB_META_PREFIX.'coupon_expiry_date', true);
                        $not_expired_yet = townhub_addons_compare_dates($expire_date ,'now','>=');
                        $coupon_qty = get_post_meta($coupon_id, ESB_META_PREFIX.'coupon_qty', true);
                        
                        if( $coupon_id != '' && (int)$coupon_qty > 0 && $not_expired_yet ){
                            $discount_type = get_post_meta($coupon_id, ESB_META_PREFIX.'discount_type', true);
                            $discount_amount = get_post_meta($coupon_id, ESB_META_PREFIX.'dis_amount', true);
                            if($discount_type == 'percent'){
                                $cart_discount_percent = (float)$discount_amount;
                                $cart_discount_amount = 0;
                                
                            }else if($discount_type == 'fixed_cart'){
                                $cart_discount_percent = 0;
                                $cart_discount_amount = (float)$discount_amount;
                            }
                        }

                    }
                    // end check coupon in listing meta
                }
                // is coupon post exists
            }
            // if cart coupon code exists


            $data['subtotal_vat'] = $data['subtotal'] * (float)apply_filters( 'esb_plan_vat', $this->vat_default(), $product_id )/100;
            
            $data['price_total'] = $data['subtotal'] + $data['subtotal_vat'];

            if($cart_discount_percent > 0){
                
                $data['amount_of_discount'] = floatval( $data['price_total'] ) * ($cart_discount_percent/100);

                $data['price_total'] = floatval( $data['price_total'] ) * (100 - $cart_discount_percent)/100;
            }elseif($cart_discount_amount > 0){
                $data['amount_of_discount'] = $cart_discount_amount;

                $data['price_total'] = floatval( $data['price_total'] ) - $cart_discount_amount ;
            }

            $data = (array)apply_filters( 'esb_member_subscribe_details', $data, $product_id );
        }
        return $data;
    }

    private function vat_default(){
        return townhub_addons_get_option('vat_tax', 10);
    }

    public function get_cart_details(){
        return $this->cart_details;
    }

    public function calculate_cart_total(){
        $totals = 0;
        if(is_array($this->cart_details) && !empty($this->cart_details)){
            foreach ($this->cart_details as $cart_item) {
                if(isset($cart_item['price_total']) && is_numeric($cart_item['price_total'])) $totals += $cart_item['price_total'];
            }
        }
        $totals = round($totals, 2);
        $this->cart_total = $totals;
        return $totals;
    }

    public function get_total(){
        return $this->cart_total;
    }

    public function destroy_cookie($name, $value){
        if(isset($_COOKIE[$name])){
            unset($_COOKIE[$name]);
            esb_setcookie( $name, $value, date_i18n('U') - HOUR_IN_SECONDS );
        }
    }

    public function empty_cart(){
        $this->cart_data = array();
        $this->cart_details = array();
        $this->cart_total = 0;

        $default_cookies = array(
            'esb_cart' => '',
            'esb_adults' => '',
            'esb_children' => '',
            'esb_no_rooms' => '',
            'esb_checkin' => '',
            'esb_checkout' => '',
            'esb_coupon' => '',
        );

        foreach ($default_cookies as $name => $value) {
            $this->destroy_cookie($name, $value);
        }
        
    }

    public function get_coupon_code(){
        return $this->cart_coupon;
    }

    public function empty_coupon(){
        $this->cart_coupon = '';
        $this->destroy_cookie( 'esb_coupon', '' );
    }
}
