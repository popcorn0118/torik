<?php 
/* add_ons_php */

defined( 'ABSPATH' ) || exit;

class Esb_Class_Form_Handler{

    public static function init(){

        add_action( 'wp_loaded', array( __CLASS__, 'add_to_cart_action' ), 20 ); 
        add_action( 'wp_loaded', array( __CLASS__, 'add_to_booking' ), 20 ); 
        add_action( 'wp_loaded', array( __CLASS__, 'add_to_cart_link_action' ), 20 ); 
        add_action( 'wp_loaded', array( __CLASS__, 'add_to_cart_event_action' ), 20 );  
        add_action( 'wp_loaded', array( __CLASS__, 'add_free_mem' ), 20 );
        add_action( 'wp_loaded', array( __CLASS__, 'add_ads' ), 20 );
        add_action( 'wp_loaded', array( __CLASS__, 'add_to_coupon_action' ), 20 ); 
        // for ipn
        add_action( 'wp_loaded', array( __CLASS__, 'check_webhooks' ) );

        // add to cart get
        add_action( 'wp_loaded', array( __CLASS__, 'add_to_cart_get' ), 20 ); 

        add_action( 'wp_loaded', array( __CLASS__, 'delete_account' ) );

    }

    public static function verify_nonce($action_name = ''){
        if (!isset($_REQUEST['_wpnonce']) || $action_name == '' || ! wp_verify_nonce( $_REQUEST['_wpnonce'], $action_name ) ){
            return ;
        }

    }

    public static function delete_account(){
        if ( !isset( $_POST['action'] ) || $_POST['action'] !== 'del_account' ) {
            return;
        }

        self::verify_nonce('cth_del_account');

        $user_id =  get_current_user_id();
        if( $user_id == 0 || !isset($_POST['uid']) || (int)$_POST['uid'] !== $user_id ){
            return;
        }

        if( townhub_addons_get_option('delete_user') != 'yes' ){
            return;
        }
        require_once(ABSPATH.'wp-admin/includes/user.php' );
        if( wp_delete_user( $user_id, null ) ){
            wp_safe_redirect( site_url() );
            exit;
        }
        
    }

    // for user add their custom link to add plan to card
    // https://support.cththemes.com/?topic=refer-to-listing-checkout/
    // domain.com/?action=esb_to_cart_get&product_id=123
    public static function add_to_cart_get(){
        if ( !isset( $_GET['action'] ) || $_GET['action'] != 'esb_to_cart_get') {
            return;
        }
        if ( !isset( $_GET['product_id'] ) || $_GET['product_id'] == '') {
            return;
        }

        $product = $_GET['product_id'];
        $adding_post      = get_post( $product );

        if ( ! $adding_post ) {
            return;
        }

        $was_added = false;

        if($adding_post->post_type == 'lplan'){
                
            if((float)get_post_meta( $product, '_price', true ) <= 0) 
                self::insert_free_subscription($product);
            else{
                $cart_item_data = array( 
                    'quantity'          => 1,
                    'yearly_price'      =>  '0',
                );
                $cart_item_data = apply_filters('esb_addons_plan_cart_item_data', $cart_item_data, $product);
                $was_added = self::add_to_cart_handler_plan( $product, $cart_item_data );
            }
        }


        // If we added the listing to the cart we can now optionally do a redirect.
        if ( $was_added && 'yes' === townhub_addons_get_option( 'checkout_redirect_after_add' )) {
            wp_safe_redirect( get_permalink( esb_addons_get_wpml_option('checkout_page') ) );
            exit;
        }
    }


    public static function add_to_booking(){
        if ( !isset( $_POST['action'] ) || $_POST['action'] != 'esb_add_booking') {
            return;
        }

        self::verify_nonce('townhub-add-to-cart');

        $listing_id = isset( $_POST['product_id'] ) ? $_POST['product_id'] : 0;
        if(is_numeric($listing_id) && (int)$listing_id > 0){

            $lb_name = '';
            $lb_email = '';
            $lb_phone = '';

            $booking_title = _x( '%1$s booking request by %2$s', 'Inquiry post title', 'townhub-add-ons' ); 
            $booking_datas = array();
            // $booking_metas_loggedin = array();
            $buser_id = 0;
            $current_user = wp_get_current_user();
            if( $current_user->exists() ){
                $lb_name = $current_user->display_name;
                $lb_email = get_user_meta( $current_user->ID, ESB_META_PREFIX.'email', true);
                $lb_phone = get_user_meta( $current_user->ID, ESB_META_PREFIX.'phone', true);
                $buser_id = $current_user->ID;
            }
            // override user details by booking details
            if( !empty($_POST['lb_name']) ){
                $lb_name = esc_html( $_POST['lb_name'] ) ;
            }

            if( !empty($_POST['lb_email']) ){
                $lb_email = esc_html( $_POST['lb_email'] );
            }

            if( !empty($_POST['lb_phone']) ){
                $lb_phone = esc_html( $_POST['lb_phone'] );
            }

            if( empty($lb_email) && $current_user->exists() ) $lb_email = $current_user->user_email;

            $booking_datas['post_title'] = sprintf( $booking_title, get_the_title( $listing_id ), $lb_name );

            $booking_datas['post_content'] = '';
            //$booking_datas['post_author'] = '0';// default 0 for no author assigned
            $booking_datas['post_status'] = 'publish';
            $booking_datas['post_type'] = 'lbooking';

            do_action( 'townhub_addons_booking_submit_before', $booking_datas );
            $booking_id = wp_insert_post($booking_datas ,true );

            if (!is_wp_error($booking_id)) {

                set_post_thumbnail( $booking_id, get_post_thumbnail_id( $listing_id ) );

                $listing_author_id = get_post_field( 'post_author', $listing_id );
                Esb_Class_Dashboard::add_notification($listing_author_id, array(
                    'type' => 'new_booking',
                    'entity_id'     => $listing_id,
                    'actor_id'      => $buser_id
                ));

                // parse booking price
                $checkin =  (isset($_POST['checkin']) && $_POST['checkin'] != '') ? esc_attr($_POST['checkin']) : ''; // date_i18n( 'Y-m-d' );
                $checkout = (isset($_POST['checkout']) && $_POST['checkout'] != '') ? esc_attr($_POST['checkout']) : '';
                $adults = (isset($_POST['adults']) && $_POST['adults'] != '') ? (int)$_POST['adults'] : '';
                $children = (isset($_POST['children']) && $_POST['children'] != '') ? (int)$_POST['children'] : '';
                $infants = (isset($_POST['infants']) && $_POST['infants'] != '') ? (int)$_POST['infants'] : '';
                $rooms = (isset($_POST['rooms']) && $_POST['rooms'] != '') ? $_POST['rooms'] : array();
                $rooms_new = (isset($_POST['rooms_new']) && !empty($_POST['rooms_new']) ) ? $_POST['rooms_new'] : array();
                $rooms_old = (isset($_POST['rooms_old']) && !empty($_POST['rooms_old']) ) ? $_POST['rooms_old'] : array();
                $tickets = (isset($_POST['tickets']) && $_POST['tickets'] != '') ? (array)$_POST['tickets'] : array();
                $tour_slots = (isset($_POST['tour_slots']) && $_POST['tour_slots'] != '') ? (array)$_POST['tour_slots'] : array();

                
                $addservices= (isset($_POST['addservices']) && !empty($_POST['addservices'])) ? $_POST['addservices'] : array();
                $bk_services= (isset($_POST['bk_services']) && !empty($_POST['bk_services'])) ? $_POST['bk_services'] : array();
                $bk_qtts= (isset($_POST['bk_qtts']) && !empty($_POST['bk_qtts'])) ? $_POST['bk_qtts'] : array();

                $slots = ( isset($_POST['slots']) && !empty($_POST['slots']) ) ? $_POST['slots'] : array();
                $times = ( isset($_POST['times']) && !empty($_POST['times']) ) ? $_POST['times'] : array();
        
                $price_based = (isset($_POST['price_based']) && $_POST['price_based'] != '') ? esc_attr($_POST['price_based']) : 'per_night';
                $booking_type = (isset($_POST['booking_type']) && $_POST['booking_type'] != '') ? esc_attr($_POST['booking_type']) : 'rooms';

                $bkdatas = array(
                    'checkin'               => $checkin,
                    'checkout'              => $checkout,
                    'adults'                => (int)$adults,
                    'children'              => (int)$children,
                    'infants'               => (int)$infants,
                    'rooms'                 => $rooms,
                    'rooms_new'             => $rooms_new,
                    'rooms_old'             => $rooms_old,
                    'tickets'               => array_filter($tickets),
                    'tour_slots'            => array_filter($tour_slots),
                    'addservices'           => $addservices,
                    // 'time_slots'                 => $slots,
                    'times'                 => $times,
                    'booking_type'          => $booking_type,
                    'price_based'           => $price_based,
                    'bk_qtts'               => (int)$bk_qtts,
                );

                
                
                $listing_price = floatval( get_post_meta( $listing_id, '_price', true ) );
                $children_price = (float)get_post_meta( $listing_id, ESB_META_PREFIX .'children_price', true );
                $infant_price = (float)get_post_meta( $listing_id, ESB_META_PREFIX .'infant_price', true );
                // for new tour_slots
                $tour_slots_data = array();
                $tour_slots_price = 0;
                $meta_tslots = townhub_addons_get_tour_slots( $listing_id, $checkin );
                if( !empty($tour_slots) && !empty($meta_tslots) ){
                    $ltour_price = get_post_meta( $listing_id, '_price', true );
                    $child_price = get_post_meta( $listing_id, ESB_META_PREFIX.'children_price', true );
                    if( $child_price === '' ){
                        $child_price = $ltour_price;
                    }
                    foreach ($tour_slots as $tid => $tourb) {
                        // search for ticket _id
                        $tkkey = array_search($tid, array_column($meta_tslots, '_id'));
                        $tchild = isset($tourb['children']) ? (int)$tourb['children'] : 0;
                        if( false !== $tkkey && ( (int)$tourb['adults'] + $tchild ) > 0 ){
                            $tkobj = array(
                                '_id'               => $tid,
                                'title'             => sprintf( __( '%s - %s', 'townhub-add-ons' ), $meta_tslots[$tkkey]['start'], $meta_tslots[$tkkey]['end'] ),
                                'price'             => floatval( $ltour_price ),
                                'adults'            => (int)$tourb['adults'],
                                'child_price'       => floatval( $child_price ),
                                'children'          => $tchild,
                                'start'             => $meta_tslots[$tkkey]['start'],
                                'end'               => $meta_tslots[$tkkey]['end'],
                            );

                            $tour_slots_price += $tkobj['adults'] * $tkobj['price'] + $tkobj['children'] * $tkobj['child_price'];
                            $tour_slots_data[] = $tkobj;
                        }
                            
                    }
                }
                $bkdatas['tour_slots'] = $tour_slots_data;

                // for res menu
                $resmenus_data = array();
                $resmenus_price = 0;
                $resmenus = (array)get_post_meta( $listing_id, ESB_META_PREFIX.'resmenus', true );
                if( isset($_POST['bkfmenus']) && !empty($_POST['bkfmenus']) && !empty($resmenus) ){
                    $bkfmenus = array_filter($_POST['bkfmenus']);
                    foreach ($bkfmenus as $tid => $tqtt) {
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
                $bkdatas['bk_menus'] = $resmenus_data;

                // for new night_person room
            
                $rooms_person_price = 0;
                $rooms_person_data = array();
                if( !empty($rooms_new) ){ 
                    foreach ( $rooms_new as $rid => $rdata ) {
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

                $bkdatas['rooms_person_data'] = $rooms_person_data;
                // new rooms
                $cth_booking_datas = array(
                    'booking_id'                => $booking_id,
                    'listing_id'                => $listing_id,
                    'guest_id'                  => $buser_id,
                    'status'                    => 0,
                );
                if(isset($bkdatas['checkin'])) $cth_booking_datas['date_from'] = Esb_Class_Date::format($bkdatas['checkin']);
                if(isset($bkdatas['checkout'])) $cth_booking_datas['date_to'] = Esb_Class_Date::format($bkdatas['checkout']);

                if( !empty($rooms_person_data) ){
                    foreach ($rooms_person_data as $cart_room) {
                        if( isset($cart_room['ID']) ){
                            $cth_booking_datas['room_id'] = $cart_room['ID'];
                            $cth_booking_datas['quantity'] = 1;
                            Esb_Class_Ajax_Handler::insert_cth_booking($cth_booking_datas);
                        }
                        
                    }
                }

                // if(isset($cart_data['rooms']) && !empty($cart_data['rooms'])){
                //     foreach ((array)$cart_data['rooms'] as $cart_room) {
                //         if(isset($cart_room['ID']) && isset($cart_room['quantity']) && (int)$cart_room['quantity'] > 0){
                //             $cth_booking_datas['room_id'] = $cart_room['ID'];
                //             $cth_booking_datas['quantity'] = (int)$cart_room['quantity'];
                //             Esb_Class_Ajax_Handler::insert_cth_booking($cth_booking_datas);
                //         }
                        
                //     }
                // }

                

                
                // for rooms with date prices
                $rooms_old_price = 0;
                $rooms_old_data = array();
                if( !empty($rooms_old) ){
                    foreach ( $rooms_old as $rid => $rdata ) {
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

                $bkdatas['rooms_old_data'] = $rooms_old_data;
                // new rooms
                if( !empty($rooms_old_data) ){
                    foreach ($rooms_old_data as $cart_room) {
                        if( isset($cart_room['ID']) && isset($cart_room['quantity']) && (int)$cart_room['quantity'] > 0 ){
                            $cth_booking_datas['room_id'] = $cart_room['ID'];
                            $cth_booking_datas['quantity'] = $cart_room['quantity'];
                            Esb_Class_Ajax_Handler::insert_cth_booking($cth_booking_datas);
                        }
                        
                    }
                }

                if( !empty($rooms_person_price) ){
                    $listing_price = $rooms_person_price;
                }elseif( !empty($rooms_old_price) ){
                    $listing_price = $rooms_old_price;
                }elseif( !empty($tour_slots_price) ){
                    $listing_price = $tour_slots_price;
                }elseif( !empty($resmenus_price) ){
                    $listing_price = $resmenus_price; 
                }else{
                    update_post_meta( $booking_id, ESB_META_PREFIX.'price', $listing_price ); 
                    update_post_meta( $booking_id, ESB_META_PREFIX.'children_price', $children_price ); 
                    update_post_meta( $booking_id, ESB_META_PREFIX.'infant_price', $infant_price ); 
                    switch ($price_based) {
                        case 'none':
                            $listing_price = 0;
                            break;
                        case 'listing':
                            // update price for per listing value
                            // update_post_meta( $booking_id, ESB_META_PREFIX.'price', $listing_price );   
                            if( !empty($bkdatas['bk_qtts']) ) $listing_price *= $bkdatas['bk_qtts'];
                            break;
                        case 'per_hour': 
                            $slots_count = is_array($slots) ? count($slots) : 0;
                            $listing_price *= $slots_count;
                            break;
                        case 'hour_person':
                            $slots_count = is_array($slots) ? count($slots) : 0;
                            if( !empty($bkdatas['adults']) ){
                                $listing_price *= (int)$bkdatas['adults'] * $slots_count;
                            }else{
                                $listing_price = 0;
                            }
                            if( !empty($bkdatas['children']) ){
                                
                                $listing_price += $children_price * (int)$bkdatas['children'] * $slots_count;
                            }
                            if( !empty($bkdatas['infants']) ){
                                
                                $listing_price += $infant_price * (int)$bkdatas['infants'] * $slots_count;
                            }
                            break;
                        case 'per_person':
                            if( !empty($bkdatas['adults']) ){
                                $listing_price *= (int)$bkdatas['adults'];
                            }else{
                                $listing_price = 0;
                            }
                            if( !empty($bkdatas['children']) ){
                                
                                $listing_price += $children_price * (int)$bkdatas['children'];
                            }
                            if( !empty($bkdatas['infants']) ){
                                
                                $listing_price += $infant_price * (int)$bkdatas['infants'];
                            }
                            break;
                        case 'night_person':
                            $nights = !empty($_POST['nights']) ? $_POST['nights'] : 1;
                            update_post_meta( $booking_id, ESB_META_PREFIX.'nights', $nights ); 
                            if( !empty($bkdatas['adults']) ){
                                $listing_price *= (int)$bkdatas['adults']*$nights;
                            }else{
                                $listing_price = 0;
                            }
                            if( !empty($bkdatas['children']) ){
                                
                                $listing_price += $children_price * (int)$bkdatas['children']*$nights;
                            }
                            if( !empty($bkdatas['infants']) ){
                                
                                $listing_price += $infant_price * (int)$bkdatas['infants']*$nights;
                            }
                            break;
                        case 'day_person':
                            $days = !empty($_POST['days']) ? $_POST['days'] : 1;
                            update_post_meta( $booking_id, ESB_META_PREFIX.'days', $days ); 
                            if( !empty($bkdatas['adults']) ){
                                $listing_price *= (int)$bkdatas['adults']*$days;
                            }else{
                                $listing_price = 0;
                            }
                            if( !empty($bkdatas['children']) ){
                                
                                $listing_price += $children_price * (int)$bkdatas['children']*$days;
                            }
                            if( !empty($bkdatas['infants']) ){
                                
                                $listing_price += $infant_price * (int)$bkdatas['infants']*$days;
                            }
                            break;
                        case 'per_night':
                            $nights = !empty($_POST['nights']) ? $_POST['nights'] : 1;
                            update_post_meta( $booking_id, ESB_META_PREFIX.'nights', $nights ); 
                            //if( !empty($bkdatas['adults']) ){
                                $listing_price *= $nights;
                            //}
                            
                            break;
                        case 'per_day':
                            $days = !empty($_POST['days']) ? $_POST['days'] : 1;
                            update_post_meta( $booking_id, ESB_META_PREFIX.'days', $days ); 
                            //if( !empty($bkdatas['adults']) ){
                                $listing_price *= $days;
                            //}
                            
                            break;
                    }

                }

                if ( !empty($slots) ){
                    // update_post_meta( $booking_id, ESB_META_PREFIX.'slots', $_POST['slots']);     
                    // update_post_meta( $booking_id, ESB_META_PREFIX.'slots_text', implode("|", $_POST['slots'] ) );    


                    
                    $listing_slots = Esb_Class_Booking_CPT::listing_time_slots($listing_id, $checkin);
                    $tSlots = array();
                    foreach ($slots as $bkslot) {
                        $slkey = array_search($bkslot, array_column($listing_slots, 'slot_id'));
                        if( false !== $slkey ){
                            $tSlots[] = array(
                                '_id'       => $bkslot,
                                'title'     => $listing_slots[$slkey]['time'],
                                'quantity'  => 1,
                                'price'     => get_post_meta( $booking_id, ESB_META_PREFIX.'price', true ),
                            );
                        }
                    }
                    update_post_meta( $booking_id, ESB_META_PREFIX.'time_slots', $tSlots );  

                }

                $services = get_post_meta($listing_id, ESB_META_PREFIX.'lservices', true);
                $total_services = 0;
                // new services
                $bk_services_data = array();
                $new_ser_total = 0;
                if(isset($services) && is_array($services) && !empty($services)) {
                    $addservices = (isset($_POST['addservices']) && !empty($_POST['addservices']) ) ? (array)$_POST['addservices'] : array();
                    foreach ($addservices  as $key => $item_serv) {
                        $lserid = array_search($item_serv, array_column($services,  'service_id'));

                        if( $lserid !== false ){
                            $bkedSer = $services[$lserid];
                            $total_services += (float)$bkedSer['service_price'];
                        }
                    }

                    // new services
                    if( !empty($bk_services) ){
                        foreach ($bk_services  as $sid => $ser_qtt) {
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

                $listing_price += $total_services;
                $listing_price += $new_ser_total;
                // end parse booking price
                $bkdatas['book_services'] = $bk_services_data;
                
                
                $bkdatas['listing_id'] = $listing_id;
                $bkdatas['lb_status'] = 'pending'; // pending - completed - failed - refunded - canceled
                // user id for non logged in user, will be override with loggedin info
                
                $bkdatas['lb_name'] =  $lb_name;
                $bkdatas['lb_email'] =  $lb_email;
                $bkdatas['lb_phone'] =  $lb_phone;

                $bkdatas['user_id'] = $buser_id;
                $bkdatas['payment_method'] = 'request'; // banktransfer - paypal - stripe - woo
                $bkdatas['bk_form_type'] = 'inquiry';
                $bkdatas['subtotal'] = $listing_price;
                $bkdatas['subtotal_fee'] = $bkdatas['subtotal'] * (float)apply_filters( 'esb_listing_fees', townhub_addons_get_option('service_fee'), $listing_id )/100;
                if(townhub_addons_get_option('booking_vat_include_fee') == 'yes'){
                    $bkdatas['subtotal_vat'] = ($bkdatas['subtotal'] + $bkdatas['subtotal_fee'])* (float)apply_filters( 'esb_listing_vat', self::vat_default(), $listing_id )/100;
                }
                else{
                    $bkdatas['subtotal_vat'] = $bkdatas['subtotal'] * (float)apply_filters( 'esb_listing_vat', self::vat_default(), $listing_id )/100;
                }

                $bkdatas['price_total'] = $bkdatas['subtotal'] + $bkdatas['subtotal_fee'] + $bkdatas['subtotal_vat'] ;

                // subtotal_vat
                // subtotal_fee
                // $bkdatas['price_total'] = $listing_price; // will need adding 
                

                // $cmb_prefix = '_cth_';
                foreach ($bkdatas as $key => $value) {
                    update_post_meta( $booking_id, ESB_META_PREFIX.$key,  $value  );
                }
                update_post_meta( $booking_id, '_price',  $bkdatas['price_total']  );
                // update bookings count
                Esb_Class_Booking_CPT::update_bookings_count($listing_author_id);

                do_action( 'townhub_addons_booking_submit_after', $booking_id );

                if( townhub_addons_get_option('woo_redirect') == 'yes' ){
                    $url =  townhub_addons_get_add_to_cart_url( $booking_id, 1 );
                    wp_redirect( $url ); exit;
                }

            }
            // end insert booking
        }
        // end check listing id
        return;
    }

    public static function vat_default(){
        return townhub_addons_get_option('vat_tax', 10);
    }

    public static function add_free_mem(){

        if ( !isset( $_POST['action'] ) || $_POST['action'] != 'esb_add_free_mem') {
            return;
        }

        self::verify_nonce('townhub-add-to-cart');

        self::insert_free_subscription( $_POST['product_id'] );

        // do_action( 'cth_free_membership_added', $_POST['product_id'] );

        // redirect after add free package
        // wp_safe_redirect( get_permalink( esb_addons_get_wpml_option('checkout_success_page') ) );
        // exit;


        // $checkout_page_id = esb_addons_get_wpml_option('checkout_success_page');
        // if($checkout_page_id == 'none') 
        //     $redirect_url = home_url();
        // else
        //     $redirect_url = get_permalink($checkout_page_id);
        // wp_safe_redirect( $redirect_url );
        // exit;

        
        
    }

    public static function insert_free_subscription($plan_id = 0, $user_id = 0, $do_redirect = true ){
        $cart_data = array(
            'product_id'                => $plan_id,
            'subtotal'                  => 0,
            'subtotal_vat'              => 0,
            'price_total'                    => 0,
            'quantity'                  => 1,
            'is_recurring'              => 0,
            'yearly_price'              => 0,
            'interval'                  => get_post_meta( $plan_id, ESB_META_PREFIX.'interval', true ),
            'period'                    => get_post_meta( $plan_id, ESB_META_PREFIX.'period', true ),
            'trial_interval'            => 0,
            'trial_period'              => 'day',

            'payment-method'            => 'free',
        );
        $order_id = Esb_Class_Ajax_Handler::insert_membership_post($cart_data, $user_id);

        do_action( 'cth_free_membership_added', $order_id );

        if($order_id){
            if(townhub_addons_get_option('auto_active_free_sub') == 'yes'){
                Esb_Class_Membership::status_to_completed($order_id);
            }
        }

        if( $do_redirect ){
            $checkout_page_id = esb_addons_get_wpml_option('checkout_success_page');
            if($checkout_page_id == 'none') 
                $redirect_url = home_url();
            else
                $redirect_url = get_permalink($checkout_page_id);

            if( townhub_addons_get_option('auto_active_free_sub') == 'yes' && townhub_addons_get_option('free_redirect_submit') == 'yes' ){
                $redirect_url =  get_permalink( esb_addons_get_wpml_option('submit_page') );
            }

            wp_safe_redirect( $redirect_url );
            exit;
        }

    }

    public static function add_ads(){
        if ( !isset( $_POST['action'] ) || $_POST['action'] !== 'esb_add_ad_camapign') {
            return;
        }
        self::verify_nonce('esb_add_adcampaign');

        $user_id =  get_current_user_id();
        if($user_id == 0 || !isset($_POST['user_id']) || (int)$_POST['user_id'] !== $user_id){
            return;
        }

        $ad_type = isset($_POST['ad-type']) && $_POST['ad-type'] != '' ? $_POST['ad-type'] : 'listing';

        if(!isset($_POST['ad-listing'])) return;

        $listing_id = $_POST['ad-listing'];


        $listing_post = get_post($listing_id);
        // // display none on incorrect listing or not authorize listing item
        if(null == $listing_post ){
            return;
        }

        // ad package term
        $ad_package = get_term( $_POST['ad-package'], 'cthads_package' );
        // display none on incorrect plan
        if ( empty( $ad_package ) || is_wp_error( $ad_package ) ){
            return;
        }

        $var_ad_title = $ad_package->name;
        $var_ad_id = $ad_package->term_id;

        $raw_price = get_term_meta( $var_ad_id, ESB_META_PREFIX.'ad_price', true );

        // add new ad to back-end
        $cthads_datas = array();
        $cthads_datas['post_title'] = sprintf(__( '%1$s for %2$s', 'townhub-add-ons' ), $var_ad_title, $listing_post->post_title);
        $cthads_datas['post_content'] = '';
        $cthads_datas['post_author'] = $user_id;
        $cthads_datas['post_status'] = 'publish';
        $cthads_datas['post_type'] = 'cthads';

        $cthads_datas['tax_input']['cthads_package'] = array($var_ad_id);

        do_action( 'townhub_addons_insert_ad_before', $cthads_datas );

        $cthads_id = wp_insert_post($cthads_datas ,true );

        if (!is_wp_error($cthads_id)) {

            $adPackageImg = get_term_meta( $var_ad_id, ESB_META_PREFIX.'icon_img', true );
            if( !empty($adPackageImg) && !empty($adPackageImg['id']) ){
                set_post_thumbnail( $cthads_id, $adPackageImg['id'] );
            }
            

            $current_user = wp_get_current_user(); 

            // increase ad pacakge pm_count - payment count
            $plan_pm_count = (int)get_term_meta( $var_ad_id , ESB_META_PREFIX.'pm_count', true );
            $plan_pm_count += 1;
            update_term_meta( $var_ad_id , ESB_META_PREFIX.'pm_count', $plan_pm_count );

            $is_recurring_plan = get_term_meta( $var_ad_id , ESB_META_PREFIX.'is_recurring', true );

            // $plan_interval = get_term_meta( $var_ad_id, ESB_META_PREFIX.'ad_interval', true );
            // $plan_period = get_term_meta( $var_ad_id, ESB_META_PREFIX.'ad_period', true );
            // if($plan_interval){
            //     $expire = townhub_add_ons_cal_next_date('', $plan_period, $plan_interval) ;
            // }else{
            //     $expire = townhub_add_ons_cal_next_date('', 'day', townhub_addons_get_option('listing_expire_days') );
            // }

            // $end_date = get_post_meta( $post_ID, ESB_META_PREFIX.'end_date', true );
                    
           
            $cthads_metas = array(
                'listing_id'                    => $listing_id, // listing id
                'plan_id'                       => $var_ad_id, // ad package id
                'amount'                        => $raw_price,
                'quantity'                      => 1,
                'currency_code'                 => townhub_addons_get_option('currency','USD'),
                'custom'                        => $cthads_id .'|'. $listing_id .'|'. $current_user->ID .'|'. $current_user->user_email .'|renew_no|subscription_no|ad_yes',
                'user_id'                       => $current_user->ID,
                'email'                         => $current_user->user_email,
                'first_name'                    => $current_user->user_firstname,
                'last_name'                     => $current_user->user_lastname,
                'display_name'                  => $current_user->display_name,



                'payment_method'                => 'banktransfer', // banktransfer - paypal - stripe


                'is_recurring_plan'             => $is_recurring_plan, // is recurring plan



                'is_per_listing_sub'            => 'yes', // is per listing subscription

                // for ad campaign type
                'order_type'                    => 'listing_ad',

                'ad_type'                       => $ad_type,
            );
            $cthads_metas['status'] = 'pending'; // pending - completed - failed - refunded
            $cthads_metas['payment_count'] = '0';





            // $cmb_prefix = '_cth_';
            foreach ($cthads_metas as $key => $value) {
                update_post_meta( $cthads_id, ESB_META_PREFIX.$key,  $value  );
            }
            update_post_meta( $cthads_id, '_price',  $raw_price  );


            do_action( 'townhub_addons_insert_ad_after', $cthads_id, $listing_id, $var_ad_id );

            Esb_Class_Dashboard::add_notification(
                $user_id,
                array(
                    'type'          => 'new_ad',
                    'entity_id'     => $cthads_id
                )
            );

            if ( 'yes' === townhub_addons_get_option( 'woo_for_ads' ) ) {
                wp_safe_redirect( townhub_addons_get_add_to_cart_url( $cthads_id ) );
                exit;
            }

            $cart_item_data = array( 
                'quantity'          => 1,
                'yearly_price'      => (isset($_POST['yearly_price'])) ? $_POST['yearly_price'] : '0',
            );
            $cart_item_data = apply_filters('esb_addons_ad_cart_item_data', $cart_item_data, $cthads_id);
            $was_added = self::add_to_cart_handler_ad( $cthads_id, $cart_item_data );


            if ( $was_added && 'yes' === townhub_addons_get_option( 'checkout_redirect_after_add' )) {
                wp_safe_redirect( get_permalink( esb_addons_get_wpml_option('checkout_page') ) );
                exit;
            }

            
            // redirect to checkout page
            // wp_redirect( townhub_addons_add_to_cart_link( $cthads_id ) );
            // exit;

        }else{
            return;
        }

    }

    public static function add_to_cart_link_action(){
        if ( !isset( $_GET['esb_add_to_cart'] ) || $_GET['esb_add_to_cart'] == '') {
            return;
        }

        self::verify_nonce('esb_add_to_cart');

        nocache_headers();
        $product_id          = absint( $_GET['esb_add_to_cart'] );
        $was_added   = false;
        $adding_post      = get_post( $product_id );

        if ( ! $adding_post ) {
            return;
        }

        $quantity = isset($_GET['quantity']) ? absint( $_GET['quantity'] ) : 1;
        if(!$quantity) $quantity = 1;

        if($adding_post->post_type == 'cthads'){
            $cart_item_data = array( 
                'quantity'          => $quantity,
                'yearly_price'      => (isset($_POST['yearly_price'])) ? $_POST['yearly_price'] : '0',
            );
            $cart_item_data = apply_filters('esb_addons_ad_cart_item_data', $cart_item_data, $product_id);
            $was_added = self::add_to_cart_handler_ad( $product_id, $cart_item_data );
        }
        
    }

    public static function add_to_cart_action(){

        if ( !isset( $_POST['action'] ) || $_POST['action'] != 'esb_add_to_cart') {
            return;
        }

        self::verify_nonce('townhub-add-to-cart');

        nocache_headers();
        $listing_id         = absint( $_POST['product_id'] );
        $was_added          = false;
        $adding_post        = get_post( $listing_id );

        if ( ! $adding_post ) {
            return;
        }

        $listing_type_id = get_post_meta( $listing_id, ESB_META_PREFIX.'listing_type_id', true );

        if($adding_post->post_type == 'lplan'){
            $cart_item_data = array( 
                'quantity'          => 1,
                'yearly_price'      => (isset($_POST['yearly_price'])) ? $_POST['yearly_price'] : '0',
            );
            $cart_item_data = apply_filters('esb_addons_plan_cart_item_data', $cart_item_data, $listing_id);
            if((float)get_post_meta( $listing_id, '_price', true ) <= 0) 
                self::insert_free_subscription($listing_id);
            else
                $was_added = self::add_to_cart_handler_plan( $listing_id, $cart_item_data );
        }else{
            $checkin =  (isset($_POST['checkin']) && $_POST['checkin'] != '') ? esc_attr($_POST['checkin']) : '';
            $checkout = (isset($_POST['checkout']) && $_POST['checkout'] != '') ? esc_attr($_POST['checkout']) : '';
            $adults = (isset($_POST['adults']) && $_POST['adults'] != '') ? (int)$_POST['adults'] : '';
            $children = (isset($_POST['children']) && $_POST['children'] != '') ? (int)$_POST['children'] : '';
            $infants = (isset($_POST['infants']) && $_POST['infants'] != '') ? (int)$_POST['infants'] : '';
            $rooms = (isset($_POST['rooms']) && $_POST['rooms'] != '') ? $_POST['rooms'] : array();
            $rooms_new = (isset($_POST['rooms_new']) && !empty($_POST['rooms_new']) ) ? $_POST['rooms_new'] : array();
            $rooms_old = (isset($_POST['rooms_old']) && !empty($_POST['rooms_old']) ) ? $_POST['rooms_old'] : array();
            $tickets = (isset($_POST['tickets']) && $_POST['tickets'] != '') ? (array)$_POST['tickets'] : array();
            $tour_slots = (isset($_POST['tour_slots']) && $_POST['tour_slots'] != '') ? (array)$_POST['tour_slots'] : array();

            
            $addservices = (isset($_POST['addservices']) && !empty($_POST['addservices'])) ? $_POST['addservices'] : array();
            $bk_services = (isset($_POST['bk_services']) && !empty($_POST['bk_services'])) ? $_POST['bk_services'] : array();

            $bkfmenus = (isset($_POST['bkfmenus']) && !empty($_POST['bkfmenus'])) ? (array)$_POST['bkfmenus'] : array();
            $bkfmenus = array_filter($bkfmenus);

            $bk_qtts = (isset($_POST['bk_qtts']) && !empty($_POST['bk_qtts'])) ? $_POST['bk_qtts'] : 0;

            $slots = ( isset($_POST['slots']) && !empty($_POST['slots']) ) ? $_POST['slots'] : array();
            $times = ( isset($_POST['times']) && !empty($_POST['times']) ) ? $_POST['times'] : array();
    
            $price_based = (isset($_POST['price_based']) && $_POST['price_based'] != '') ? esc_attr($_POST['price_based']) : 'per_night';
            $booking_type = (isset($_POST['booking_type']) && $_POST['booking_type'] != '') ? esc_attr($_POST['booking_type']) : 'rooms';
            if($booking_type == 'tour'){
                if( ( (int)$adults + (int)$children + (int)$infants ) < 1 ) return;
            }else if($booking_type == 'slot' ){
                // if( empty($slots) ) return;
            }else if($booking_type == 'tpicker' ){
                // if( empty($times) || ( (int)$adults + (int)$children + (int)$infants ) < 1 ) return;
            }else if($booking_type == 'rooms' || $booking_type == 'rental'){
                $nights = townhub_addons_booking_nights($checkin, $checkout);
                if($nights <= 0 || ((int)$adults + (int)$children + (int)$infants) < 1){
                    // expose an error
                    return;
                }
            }


            // disalbe free booking
            if( (bool)get_post_meta( $listing_type_id, ESB_META_PREFIX.'dis_free_booking', true ) ){
                if( $price_based == 'none' && empty($bkfmenus) ) return;
            }
            
            // booking_type
            $cart_item_data = array(
                'checkin'               => $checkin,
                'checkout'              => $checkout,
                'adults'                => (int)$adults,
                'children'              => (int)$children,
                'infants'               => (int)$infants,
                'rooms'                 => $rooms,
                'rooms_new'             => $rooms_new,
                'rooms_old'             => $rooms_old,
                'tickets'               => array_filter($tickets),
                'tour_slots'               => array_filter($tour_slots),
                'addservices'           => $addservices,
                'slots'            => $slots,
                'times'                 => $times,
                'booking_type'          => $booking_type,
                'price_based'           => $price_based,

                'bk_services'           => $bk_services,
                'bk_qtts'               => (int)$bk_qtts,
                'bkfmenus'               => $bkfmenus,
            );

            
            $cart_item_data = apply_filters( 'esb_addons_listing_cart_item_data', $cart_item_data, $listing_id);

            $was_added = self::add_to_cart_handler_listing( $listing_id, $cart_item_data );
        }


        // If we added the listing to the cart we can now optionally do a redirect.
        if ( $was_added && 'yes' === townhub_addons_get_option( 'checkout_redirect_after_add' )) {
            $checkout_page_id = esb_addons_get_wpml_option('checkout_page');
            wp_safe_redirect( get_permalink($checkout_page_id) );
            exit;
        }


        // var_dump($_POST);

//         array(7) {
//   ["checkout"]=>
//   string(10) "2018-12-25"
//   ["checkin"]=>
//   string(10) "2018-12-24"
//   ["adults"]=>
//   string(1) "1"
//   ["children"]=>
//   string(1) "0"
//   ["rooms"]=>
//   array(2) {
//     [5178]=>
//     string(1) "1"
//     [5174]=>
//     string(1) "1"
//   }
//   ["listing_id"]=>
//   string(4) "1886"
//   ["action"]=>
//   string(19) "listing_add_to_cart"
// }

 


    }
    public static function add_to_cart_event_action(){

        if ( !isset( $_POST['action'] ) || $_POST['action'] != 'esb_add_to_cart_event') {
            return;
        }

        self::verify_nonce('townhub-add-to-cart-event');

        nocache_headers();
        $listing_id          = absint( $_POST['product_id'] );
        $was_added   = false;
        $adding_post      = get_post( $listing_id );
         if ( ! $adding_post ) {
            return;
        }
        $qty = (isset($_POST['qty']) && $_POST['qty'] != '') ? (int)$_POST['qty'] : '';
        $lprice = (isset($_POST['lprice']) && $_POST['lprice'] != '') ? (float)$_POST['lprice'] : 0;
        $date_event = (isset($_POST['ldate']) && $_POST['ldate'] != '') ? esc_attr($_POST['ldate']) : '';
        $addservices= (isset($_POST['addservices']) && $_POST['addservices'] != '') ? $_POST['addservices'] : array();
        $cart_item_data = array(
            'checkin'       => '',
            'checkout'      => '',
            'adults'        => '',
            'children'      => '',
            'infants'       => '',
            'rooms'         => array(),
            'qty'           =>  (int)$qty,
            'lprice'        => $lprice,
            'date_event'    => $date_event,
            'addservices'   => $addservices,       

        );
        $cart_item_data = apply_filters( 'esb_addons_listing_cart_item_data', $cart_item_data, $listing_id);
        $was_added = self::add_to_cart_handler_listing( $listing_id, $cart_item_data );
       
        if ( $was_added && 'yes' === townhub_addons_get_option( 'checkout_redirect_after_add' )) {
            $checkout_page_id = esb_addons_get_wpml_option('checkout_page');
            wp_safe_redirect( get_permalink($checkout_page_id) );
            exit;
        }
    }
    public static function add_to_coupon_action(){

        if ( !isset( $_POST['action'] ) || $_POST['action'] != 'esb_add_to_coupon') {
            return;
        }
        self::verify_nonce('townhub-add-to-coupon');
        // need to check if the coupon code is valid?
        $coupon_code = (isset($_POST['coupon_code']) && $_POST['coupon_code'] != '') ? sanitize_text_field($_POST['coupon_code']) : '';

        if(empty($coupon_code)) 
            return;

        $lid = isset($_POST['lid']) ? sanitize_text_field($_POST['lid']) : 0;
        if(empty($lid)) 
            return;

        $coupon_post = get_posts(
            array(
                'post_type'         => 'cthcoupons',
                'posts_per_page'    => 1,
                'post_status'       => 'publish',
                'fields'            => 'ids',
                'meta_query'        => array(
                    array(
                        'key'           => ESB_META_PREFIX.'coupon_code',
                        'value'         => $coupon_code,
                    ),
                    array(
                        'relation' => 'OR',
                        array(
                            'key'           => ESB_META_PREFIX.'for_coupon_listing_id',
                            'value'         => $lid,
                        ),
                        array(
                            'key'           => ESB_META_PREFIX.'plan_id',
                            'value'         => $lid,
                        ),
                    ),
                        
                )
            )
        );
        
        if(empty($coupon_post))
            return;

        $coupon_id = reset($coupon_post);

        // double check for listing coupon
        $listing_coupon_ids = get_post_meta($lid, ESB_META_PREFIX.'coupon_ids', true);
        if( empty($listing_coupon_ids) || !is_array($listing_coupon_ids) || !in_array($coupon_id, $listing_coupon_ids))
            return;

        $expire_date = get_post_meta($coupon_id, ESB_META_PREFIX.'coupon_expiry_date', true);
        $not_expired_yet = townhub_addons_compare_dates($expire_date ,'now','>=');
        $coupon_qty = get_post_meta($coupon_id, ESB_META_PREFIX.'coupon_qty', true);
        
        if( $coupon_id != '' && (int)$coupon_qty > 0 && $not_expired_yet ){
            ESB_ADO()->cart->set_cart_coupon($coupon_code);
        }
          
    }

    private static function add_to_cart_handler_plan( $plan_id, $cart_item_data ) {
        $passed_validation  = apply_filters( 'esb_add_to_cart_validation', true, $plan_id, $cart_item_data );
        if ( $passed_validation && false !== ESB_ADO()->cart->add_plan_to_cart(  $plan_id, $cart_item_data ) ) {
            return true;
        }
        return false;
    }
    

    private static function add_to_cart_handler_listing( $listing_id, $cart_item_data ) {
        $passed_validation  = apply_filters( 'esb_add_to_cart_validation', true, $listing_id, $cart_item_data );
        if ( $passed_validation && false !== ESB_ADO()->cart->add_to_cart(  $listing_id, $cart_item_data ) ) {
            return true;
        }
        return false;
    }

    private static function add_to_cart_handler_ad( $ad_id, $cart_item_data ) {
        $passed_validation  = apply_filters( 'esb_add_to_cart_validation', true, $ad_id, $cart_item_data );
        if ( $passed_validation && false !== ESB_ADO()->cart->add_ad_to_cart(  $ad_id, $cart_item_data ) ) {
            return true;
        }
        return false;
    }

    public static function check_webhooks(){
        if (isset($_GET['action'])) {
            // switch ($_GET['action']) {
            //     case 'cth_ppipn':
            //         $esb_payment_paypal = new Esb_Class_Payment_Paypal();
            //         $esb_payment_paypal->process_payment_check_webhooks();
            //         break;
            //     case 'esb_stripewebhook':
            //         $esb_payment_stripe = new Esb_Class_Payment_Stripe();
            //         $esb_payment_stripe->process_payment_check_webhooks();
            //         break;
            //     case 'cth_pfipn':
            //         $esb_payment_payfast = new Esb_Class_Payment_Payfast();
            //         $esb_payment_payfast->process_payment_check_webhooks();
            //         break;
            // }
            do_action( 'esb_payment_check_webhooks', $_GET['action'] );
        }
        
    }
}

Esb_Class_Form_Handler::init();