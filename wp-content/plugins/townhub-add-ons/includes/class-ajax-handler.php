<?php 
/* add_ons_php */

defined( 'ABSPATH' ) || exit; 
class Esb_Class_Ajax_Handler{ 

    public static function init(){ 
        $ajax_actions = array(
            'checkout_form',
            'townhub_addons_chat_lauthor_message',  
            'townhub_single_room',  
            'townhub_addons_booking_woo_listing', 
            'townhub_addons_change_ltype', 
            'townhub_change_ltype'

        );
        foreach ($ajax_actions as $action) {
            $funname = str_replace('townhub_addons_', '', $action);
            $funname = str_replace('townhub_', '', $funname);
            add_action('wp_ajax_nopriv_'.$action, array( __CLASS__, $funname ));  
            add_action('wp_ajax_'.$action, array( __CLASS__, $funname ));
        }
        $logged_in_ajax_actions = array(
            'withdrawals_get',
            'withdrawals_save',
            'withdrawals_cancel',
            'earnings_get',
        );
        foreach ($logged_in_ajax_actions as $logged_in_ajax_actions) {
            add_action('wp_ajax_'.$logged_in_ajax_actions, array( __CLASS__, $logged_in_ajax_actions ));
        }

        // $not_logged_in_ajax_actions = array(
        
        // );
        // foreach ($not_logged_in_ajax_actions as $action) {
        //     $funname = str_replace('townhub_addons_', '', $action);
        //     $funname = str_replace('townhub-', '', $funname);
        //     add_action('wp_ajax_nopriv_'.$action, array( __CLASS__, $funname .'_callback' ));
        // }
    }
      public static function checkout_form(){ 

        $json = array(
            'success' => false,
            'data' => array(
                'POST'=>$_POST,

                // 'price_total' => ESB_ADO()->cart->get_total(),
                // 'cart_details' => ESB_ADO()->cart->get_cart_details()
                // get cart data ok
            ),

            'debug'     => false,

        );
        self::verify_nonce('esb-checkout-security');

        // wp_send_json( $json );
        $cart_details = ESB_ADO()->cart->get_cart_details();
        $insert_posts = array();
        if(is_array($cart_details) && !empty($cart_details)){
            // update user billing
            (new Esb_Class_Checkout())->update_user_billing();
            foreach ($cart_details as $c_key => $c_data) {
                $c_data['payment-method'] = !empty($_POST['payment-method']) ? esc_html($_POST['payment-method']) : 'free';
                if(isset($c_data['cart_item_type']) && $c_data['cart_item_type'] == 'plan'){
                    // $c_data['payment-method'] = (isset($_POST['payment-method']) && $_POST['payment-method']) ? $_POST['payment-method'] : 'free';
                    $insert_id = self::insert_membership_post($c_data);
                    if($insert_id) $insert_posts[] = $insert_id;
                }elseif(isset($c_data['cart_item_type']) && $c_data['cart_item_type'] == 'ad'){
                    $insert_posts[] = $c_data['product_id'];
                }else{
                    $quantity = '';
                    $cart_coupon = ESB_ADO()->cart->get_coupon_code();
                    $insert_id = self::insert_booking_post($c_data,$quantity, $cart_coupon);
                    $json['insert_id'] = $insert_id;
                    if($insert_id) $insert_posts[] = $insert_id; 
                }
            }

        }
        // $price_total = ESB_ADO()->cart->get_total();
        // $json['insert_posts'] = $insert_posts;
        // $json['price_total'] = $price_total;
        // $json['success'] = true;
        // wp_send_json( $json );
        // 
        if(!empty($insert_posts)){
            
            $price_total = ESB_ADO()->cart->get_total();
            // get first inserted posts item
            $inserted_post_first = reset($insert_posts);
            $inserted_post_first_pt = get_post_type($inserted_post_first);
            $item_number = 999;
            $payment_method = (isset($_POST['payment-method']) && $_POST['payment-method']) ? esc_html($_POST['payment-method']) : 'free';
            
            if($inserted_post_first_pt == 'lbooking'){
                $item_number = get_post_meta( $inserted_post_first, ESB_META_PREFIX.'listing_id', true );
                do_action('townhub_addons_insert_booking_after',$inserted_post_first);
            }elseif($inserted_post_first_pt == 'lorder'){
                $item_number = get_post_meta( $inserted_post_first, ESB_META_PREFIX.'plan_id', true );
            }elseif($inserted_post_first_pt == 'cthads'){
                $item_number = get_post_meta( $inserted_post_first, ESB_META_PREFIX.'plan_id', true );
                update_post_meta( $inserted_post_first, ESB_META_PREFIX.'price_total', $price_total );
                update_post_meta( $inserted_post_first, ESB_META_PREFIX.'payment_method', $payment_method );
                
            }
            $inserted_posts_text = implode("-", $insert_posts);
            // need to check if allow checkout as guest
            // $current_user = wp_get_current_user();

            $process_results = array(
                'success'   => false,
                'url'       => ''
            );
            
            // where payment method
            if( (float)$price_total > 0 ){
                
                $stripeEmail = (isset($_POST['stripeEmail']) && $_POST['stripeEmail'] !='' ) ? esc_attr($_POST['stripeEmail']) : '';
                $data_checkout = array(
                    'inserted_post_first'       => $inserted_post_first,
                    'stripeEmail'               => $stripeEmail,
                    'inserted_posts_text'       => $inserted_posts_text,
                );
                $esb_payments = ESB_ADO()->payment_methods;
                if(isset($esb_payments[$payment_method])) $process_results = $esb_payments[$payment_method]->process_payment_checkout($data_checkout);
                
            }
            // end payment methods
            if ( $payment_method == 'free' || $payment_method == 'banktransfer' || $payment_method ==  'submitform' || $payment_method ==  'cod' ) {
                $process_results = array(
                    'success'   => true,
                    'url'       => '', // get_permalink(townhub_addons_get_option('checkout_success')),
                );
            }
            // $json['success'] = $process_results['success'];
            // error_log(json_encode($process_results));
            // var_dump($process_results);
            // die;

            $json = array_merge($json, $process_results);

            ESB_ADO()->cart->empty_cart();

            if( !isset($process_results['url']) || $process_results['url'] == ''){
                if(townhub_addons_get_option('checkout_success_redirect') == 'yes' && $_POST['payment-method'] != 'payfast')
                    $json['url'] = get_permalink(townhub_addons_get_option('checkout_success'));
                else
                    $json['result'] = apply_filters( 'the_content', get_post_field('post_content', townhub_addons_get_option('checkout_success')) ); //get_the_content( townhub_addons_get_option('checkout_success') );
            }else{
                $json['url'] = $process_results['url'];
            }
        }
        $json['insert_posts'] = $insert_posts;
        // var_dump($_POST);
        // die;
        wp_send_json( $json );
    }
    public static function verify_nonce($action_name = '', $datas = array() ){
        if (!isset($_REQUEST['_wpnonce']) || $action_name == '' || ! wp_verify_nonce( $_REQUEST['_wpnonce'], $action_name ) ){
            $result = array(
                'success'   => false,
                'error'     => esc_html__( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ),
                'data'      => array(
                    'error'     => esc_html__( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ),
                ),
            );

            if( !empty($datas) && is_array($datas) ){
                $result = array_merge($result, $datas);
            }

            wp_send_json( $result );
        }

    }
    public static function insert_membership_post($cart_data = array(), $user_id = 0){
        if( !$user_id ){
            $user_id = get_current_user_id();
        }
        $current_user = get_userdata( $user_id );
        if( !$current_user ) return false;
        $plan_id = $cart_data['product_id'];
        // add new order to back-end
        $order_datas = array();
        $order_datas['post_title'] = sprintf(__( '%1$s subscription from %2$s', 'townhub-add-ons' ), get_the_title( $plan_id ), $current_user->display_name);
        $order_datas['post_content'] = '';
        //$order_datas['post_author'] = '0';// default 0 for no author assigned
        $order_datas['post_status'] = 'publish';
        $order_datas['post_type'] = 'lorder';

        do_action( 'townhub_addons_insert_order_before', $order_datas );

        $lorder_id = wp_insert_post($order_datas ,true );

        if (!is_wp_error($lorder_id)) {

            Esb_Class_Dashboard::add_notification($current_user->ID, array(
                'type' => 'new_order',
                'entity_id'     => $lorder_id
            ));

            // increase plan pm_count - payment count
            $plan_pm_count = get_post_meta( $plan_id , ESB_META_PREFIX.'pm_count', true );
            $plan_pm_count += 1;
            update_post_meta( $plan_id , ESB_META_PREFIX.'pm_count', $plan_pm_count );
            $order_metas = array(
                'plan_id'                       => $plan_id, // plan id
                'subtotal'                        => $cart_data['subtotal'],
                'subtotal_vat'                        => $cart_data['subtotal_vat'],
                'amount'                        => $cart_data['price_total'],
                'price_total'                   => $cart_data['price_total'],
                'quantity'                      => $cart_data['quantity'],
                'currency_code'                 => 'USD',
                'custom'                        => $lorder_id .'|'. $current_user->ID .'|'. $current_user->user_email .'|renew_no',
                'user_id'                       => $current_user->ID,
                'email'                         => $current_user->user_email,
                'first_name'                    => $current_user->user_firstname,
                'last_name'                     => $current_user->user_lastname,
                'display_name'                  => $current_user->display_name,
                'author_fee'                    => get_post_meta( $plan_id, ESB_META_PREFIX.'author_fee', true ),



                'payment_method'                => $cart_data['payment-method'], // banktransfer - paypal - stripe


                'is_recurring'             => $cart_data['is_recurring'], // is recurring plan



                'is_per_listing_sub'            => 'no', // is per listing subscription

                'end_date'                      => townhub_add_ons_cal_next_date('', 'day', townhub_addons_get_option('listing_expire_days') ),

                'yearly_price'                  => $cart_data['yearly_price'],
            );
            $order_metas['interval'] = $cart_data['interval'];
            $order_metas['period'] = $cart_data['period'];


            $order_metas['status'] = 'pending'; // pending - completed - failed - refunded
            $order_metas['payment_count'] = '0';
            $order_metas['coupon_code'] = ESB_ADO()->cart->get_coupon_code();

            $order_metas['notes'] = '';
            if(isset($_POST['notes'])) $order_metas['notes'] = wp_kses_post($_POST['notes']);

            if(!empty($cart_data['trial_interval']) && !empty($cart_data['trial_period'])){
                $order_metas['trial_interval'] = $cart_data['trial_interval'];
                $order_metas['trial_period'] = $cart_data['trial_period'];
                // update trialling
                // $order_metas['status'] = 'trialing'; // pending - completed - failed - refunded
            }
            foreach ($order_metas as $key => $value) {
                update_post_meta( $lorder_id, ESB_META_PREFIX.$key,  $value  );
            }
            do_action( 'townhub_addons_insert_order_after', $lorder_id, $plan_id, 0 );
            
            return $lorder_id; 
        }
        return false;
    }
    public static function insert_booking_post($cart_data = array(),$quantity = '',$coupon_code = ''){

        $listing_id = $cart_data['listing_id'];
        if(is_numeric($listing_id) && (int)$listing_id > 0){
            $booking_title = __( '%1$s booking by %2$s', 'townhub-add-ons' ); 
            $booking_datas = array();
            $booking_metas_loggedin = array();
            $buser_id = 0;
            $current_user = wp_get_current_user();
            if( $current_user->exists() ){
                $lb_name = $current_user->display_name;
                $lb_email = get_user_meta( $current_user->ID, ESB_META_PREFIX.'email', true);
                $lb_phone = get_user_meta( $current_user->ID, ESB_META_PREFIX.'phone', true);
                $buser_id = $current_user->ID;
            }
            // override user details by booking details
            if( isset($_POST['first_name']) && isset($_POST['last_name']) ){
                $lb_name = esc_html(trim($_POST['first_name'])) . ' '. esc_html(trim($_POST['last_name']));
            }elseif( isset($_POST['billing_first_name']) && isset($_POST['billing_last_name']) ){
                $lb_name = esc_html(trim($_POST['billing_first_name'])) . ' '. esc_html(trim($_POST['billing_last_name']));
            }

            if( !empty($_POST['user_email']) ){
                $lb_email = esc_attr($_POST['user_email']);
            }elseif( !empty($_POST['billing_email']) ){
                $lb_email = esc_attr($_POST['billing_email']);
            }

            if( !empty($_POST['phone']) ){
                $lb_phone = esc_html($_POST['phone']);
            }elseif( !empty($_POST['billing_phone']) ){
                $lb_phone = esc_html($_POST['billing_phone']);
            }

            if( empty($lb_email) && $current_user->exists() ) $lb_email = $current_user->user_email;

            $booking_datas['post_title'] = sprintf( $booking_title, get_the_title( $listing_id ), $lb_name );

            $booking_datas['post_content'] = '';
            //$booking_datas['post_author'] = '0';// default 0 for no author assigned
            $booking_datas['post_status'] = 'publish';
            $booking_datas['post_type'] = 'lbooking';

            do_action( 'townhub_addons_insert_booking_before', $booking_datas );

            $booking_id = wp_insert_post($booking_datas ,true );

            if (!is_wp_error($booking_id)) {
                $listing_author_id = get_post_field( 'post_author', $listing_id );
                Esb_Class_Dashboard::add_notification($listing_author_id, array(
                    'type' => 'new_booking',
                    'entity_id'     => $listing_id,
                    'actor_id'      => $buser_id
                ));

                // insert cth_booking post 
                $cth_booking_datas = array(
                    'booking_id'                => $booking_id,
                    'listing_id'                => $listing_id,
                    'guest_id'                  => $buser_id,
                    'status'                    => 0,
                );
                if(isset($cart_data['checkin'])) $cth_booking_datas['date_from'] = Esb_Class_Date::format($cart_data['checkin']);
                if(isset($cart_data['checkout'])) $cth_booking_datas['date_to'] = Esb_Class_Date::format($cart_data['checkout']);

                if(isset($cart_data['rooms']) && !empty($cart_data['rooms'])){
                    foreach ((array)$cart_data['rooms'] as $cart_room) {
                        if(isset($cart_room['ID']) && isset($cart_room['quantity']) && (int)$cart_room['quantity'] > 0){
                            $cth_booking_datas['room_id'] = $cart_room['ID'];
                            $cth_booking_datas['quantity'] = (int)$cart_room['quantity'];
                            self::insert_cth_booking($cth_booking_datas);
                        }
                        
                    }
                }

                // new rooms
                if(isset($cart_data['rooms_person_data']) && !empty($cart_data['rooms_person_data'])){
                    foreach ((array)$cart_data['rooms_person_data'] as $cart_room) {
                        if( isset($cart_room['ID']) ){
                            $cth_booking_datas['room_id'] = $cart_room['ID'];
                            $cth_booking_datas['quantity'] = 1;
                            self::insert_cth_booking($cth_booking_datas);
                        }
                        
                    }
                }

                // new rooms
                if(isset($cart_data['rooms_old_data']) && !empty($cart_data['rooms_old_data'])){
                    foreach ((array)$cart_data['rooms_old_data'] as $cart_room) {
                        if( isset($cart_room['ID']) && isset($cart_room['quantity']) && (int)$cart_room['quantity'] > 0 ){
                            $cth_booking_datas['room_id'] = $cart_room['ID'];
                            $cth_booking_datas['quantity'] = $cart_room['quantity'];
                            self::insert_cth_booking($cth_booking_datas);
                        }
                        
                    }
                }

                $meta_fields = array(
                    'rooms'                     => 'array',
                    'rooms_person_data'         => 'array',
                    'rooms_old_data'         => 'array',
                    'tickets'               => 'array',
                    'tour_slots'               => 'array',
                    'checkin'              => 'text',
                    'checkout'              => 'text',
                    'nights'              => 'text',
                    'days'              => 'text',
                    'adults'              => 'text',
                    'children'              => 'text',
                    'infants'              => 'text',
                    'subtotal'              => 'text',
                    'subtotal_fee'              => 'text',
                    'subtotal_vat'              => 'text',
                    'price_total'              => 'text',
                    'qty'                   => 'text',
                    'date_event'            => 'text',

                    'booking_type'            => 'text',
                    'price_based'            => 'text',
                    // 'addservices'            => 'array',
                    'adult_price'            => 'text',
                    'children_price'            => 'text',
                    'infant_price'            => 'text',

                    'day_prices'            => 'array',
                    'adult_prices'            => 'array',
                    'children_prices'            => 'array',
                    'infant_prices'            => 'array',

                    // 'payment_method'                => 'text',
                    'book_services'             => 'array',
                    'bk_qtts'                   => 'text',
                    'price'                     => 'text',

                    // res menu
                    'bk_menus'                  => 'array',
                );

                $meta_fields = apply_filters( 'esb_booking_meta_fields', $meta_fields );
                $booking_metas = array();
                foreach($meta_fields as $field => $ftype){
                    if(isset($cart_data[$field])) 
                        $booking_metas[$field] = $cart_data[$field] ;
                    else{
                        if($ftype == 'array'){
                            $booking_metas[$field] = array();
                        }else{
                            $booking_metas[$field] = '';
                        }
                    } 
                }
                $booking_metas['listing_id'] = $listing_id;
                $booking_metas['lb_status'] = 'pending'; // pending - completed - failed - refunded - canceled
                // user id for non logged in user, will be override with loggedin info
                $booking_metas['user_id'] = $buser_id;
                $booking_metas['lb_name'] =  $lb_name;
                $booking_metas['lb_email'] =  $lb_email;
                $booking_metas['lb_phone'] =  $lb_phone;

                $booking_metas['payment_method'] =  $cart_data['payment-method']; // banktransfer - paypal - stripe

                $booking_metas['bk_form_type'] = 'checkout';
                
                // merge with logged in customser data
                $booking_metas = array_merge($booking_metas,$booking_metas_loggedin);

                $booking_metas['notes'] = '';
                if(isset($_POST['notes'])) $booking_metas['notes'] = wp_kses_post($_POST['notes']);

                // woo payment
                // $booking_metas['payment_method'] = 'woo'; // banktransfer - paypal - stripe - woo 

                // $cmb_prefix = '_cth_';
                foreach ($booking_metas as $key => $value) {
                    // https://codex.wordpress.org/Function_Reference/update_post_meta
                    // Returns meta_id if the meta doesn't exist, otherwise returns true on success and false on failure. 
                    // NOTE: If the meta_value passed to this function is the same as the value that is already in the database, this function returns false.
                    if ( !update_post_meta( $booking_id, ESB_META_PREFIX.$key,  $value  ) ) {
                        $json['data'][] = sprintf(__('Insert booking %s meta failure or existing meta value','townhub-add-ons'),$key);
                        // wp_send_json($json );
                    }
                }
                // update billing
                self::update_booking_billing($booking_id);

                if (isset($cart_data['addservices']) && is_array($cart_data['addservices']) && $cart_data['addservices'] != ''){
                     update_post_meta( $booking_id, ESB_META_PREFIX.'addservices', $cart_data['addservices']);     
                }
                // slot booking
                if ( isset($cart_data['slots']) && is_array($cart_data['slots']) && !empty($cart_data['slots']) ){
                    // update_post_meta( $booking_id, ESB_META_PREFIX.'time_slots', $cart_data['slots']);     
                    // update_post_meta( $booking_id, ESB_META_PREFIX.'slots_text', implode("|", $cart_data['slots'] ) ); 

                    // $listing_slots = get_post_meta( $listing_id, ESB_META_PREFIX.'time_slots', true );
                    $listing_slots = Esb_Class_Booking_CPT::listing_time_slots($listing_id, $cart_data['checkin']);
                    $tSlots = array();
                    foreach ($cart_data['slots'] as $bkslot) {
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

                // tpicker booking
                if ( isset($cart_data['times']) && is_array($cart_data['times']) && !empty($cart_data['times']) ){
                    update_post_meta( $booking_id, ESB_META_PREFIX.'times', $cart_data['times']);     
                    update_post_meta( $booking_id, ESB_META_PREFIX.'times_text', implode("|", $cart_data['times'] ) );     
                }
                if (!empty($coupon_code) && $coupon_code != '') {
                    update_post_meta( $booking_id, ESB_META_PREFIX.'bkcoupon',  $coupon_code );
                    self::update_quantity_coupon($coupon_code);
                }
                if(!empty($quantity) && $quantity > 0){
                    update_post_meta( $booking_id, ESB_META_PREFIX.'quantity',  $quantity );

                    $rid = '';
                    $rprice = '';
                    if(isset($cart_data['rooms']) && !empty($cart_data['rooms'])){
                        foreach ((array)$cart_data['rooms'] as $cart_room) {
                            if(isset($cart_room['ID']) && isset($cart_room['quantity']) && (int)$cart_room['quantity'] > 0){
                                $rid = $cart_room['ID'];
                            }  
                        }
                        $rprice = get_post_meta($rid,ESB_META_PREFIX.'_price',true);
                    }
                    $rooms_price = 0;
                    $rooms_price += $quantity * $rprice;
                    $price_total_room = $rooms_price + $cart_data['subtotal_fee'] + $cart_data['subtotal_vat'];
                    update_post_meta( $booking_id, ESB_META_PREFIX.'price_total_room',  $price_total_room);
                } 

                if( get_post_meta( $booking_id, ESB_META_PREFIX.'checkin', true ) == '' ){
                    update_post_meta( $booking_id, ESB_META_PREFIX.'df_checkin', date_i18n( 'Y-m-d' ) );  
                }

                Esb_Class_Booking_CPT::update_bookings_count($listing_author_id);
                
                do_action( 'esb_insert_booking_after', $booking_id , $cart_data);
                return $booking_id;
            }
        }
        return false;
    }

    public static function update_booking_billing( $booking_id = 0 ){
        $billing_fields = array(

            'billing_first_name' => 'text',
            'billing_last_name'  => 'text',
            'billing_company'    => 'text',
            'billing_city'       => 'text',
            'billing_country'    => 'text',
            'billing_address_1'  => 'text',
            'billing_address_2'  => 'text',
            'billing_state'      => 'text',
            'billing_postcode'   => 'text',
            'billing_phone'      => 'text',
            'billing_email'      => 'text',
        );
        $billing_metas = array();
        foreach($billing_fields as $fname => $ftype){
            if($ftype == 'array'){
                $billing_metas[$fname] = isset($_POST[$fname]) ? $_POST[$fname]  : array();
            }else{
                $billing_metas[$fname] = isset($_POST[$fname]) ? esc_html($_POST[$fname]) : '';
            }


            // if(isset($_POST[$field])) 
            //     $billing_metas[$field] = $_POST[$field] ;
            // else{
            //     if($ftype == 'array'){
            //         $billing_metas[$field] = array();
            //     }else{
            //         $billing_metas[$field] = '';
            //     }
            // } 
        }
        update_post_meta( $booking_id, ESB_META_PREFIX.'billing_metas', $billing_metas );
        // foreach ($billing_metas as $key => $value) {
        //     update_post_meta( $booking_id, $key,  $value  );
        // }
    }

    public static function insert_cth_booking($data = array()){
        global $wpdb;
        $booking_table = $wpdb->prefix . 'cth_booking';
        if(is_array($data) && !empty($data)){
            $result = $wpdb->insert( 
                $booking_table, 
                $data
            );
            // end inshert chat
            // https://codex.wordpress.org/Class_Reference/wpdb#INSERT_row
            if($result != false) return $wpdb->insert_id;
        }
        return false;   
    }
    protected static function update_quantity_coupon($coupon_code){
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
                    // array(
                    //     'key'           => ESB_META_PREFIX.'for_coupon_listing_id',
                    //     'value'         => $listing_id,
                    // )
                )
            )
        );
        if(!empty($coupon_post)){
            $coupon_id = reset($coupon_post);
            $coupon_qty = get_post_meta($coupon_id, ESB_META_PREFIX.'coupon_qty', true);
            if ( is_numeric($coupon_qty) && $coupon_qty > 0) {
                update_post_meta( $coupon_id, ESB_META_PREFIX.'coupon_qty',  $coupon_qty - 1 );
            } 
        }
    }
    //Chat single listing message***************************************
    public function chat_lauthor_message() {
        global $wpdb;
        $json = array(
            'success' => false,
            'data' => array(
                'POST'=>$_POST,
            )
        );
        // self::verify_nonce('townhub-add-ons');
        // var_dump($_POST);
        // wp_send_json($json );
        $nonce = $_POST['_nonce'];
        if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
            $json['data']['error'] = __( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }

        $authid = isset($_POST['authid'])? $_POST['authid'] : 0;
        if( is_numeric($authid) && $authid > 0 ){
            $from_user_id = 0;
            if( isset($_POST['lmsg_name']) && isset($_POST['lmsg_email']) ){
                // register new user
                // check for corrent email
                if ( !is_email( $_POST['lmsg_email'] ) ) {
                    $json['data']['error'] = __( 'Invalid email address.', 'townhub-add-ons' ) ;
                    wp_send_json($json );
                }
                $new_user_data = array(
                    'user_login' => $_POST['lmsg_name'],
                    'user_pass'  => wp_generate_password( 12, false ),
                    'user_email' => $_POST['lmsg_email'],
                    // 'role'       => 'l_customer' //'subscriber'
                );

                $from_user_id = wp_insert_user( $new_user_data );

                if ( ! is_wp_error( $from_user_id ) ) {
                    // send login
                    if(townhub_addons_get_option('new_user_email') != 'none') wp_new_user_notification( $from_user_id, null, townhub_addons_get_option('new_user_email') );
                }else{
                    $json['data']['error'] = $from_user_id->get_error_message() ;
                    wp_send_json($json );
                }
            }else{
                if(!is_user_logged_in()){ // no logged in user and invalid form
                    $json['data']['error'] = __( 'Invalid message form without name and email.', 'townhub-add-ons' );
                    wp_send_json($json );
                }
                $from_user_id = get_current_user_id();
            }

            // check for sending user
            if(is_numeric($from_user_id) && $from_user_id ){
                $chat_table = $wpdb->prefix . 'cth_chat';
                $chat_reply_table = $wpdb->prefix . 'cth_chat_reply';

                $chat_id_checked = 0;
                $time = date_i18n('U');
                $ip = $_SERVER['REMOTE_ADDR'];

                $chatids = $wpdb->get_col( "SELECT c_id FROM $chat_table WHERE ((user_one ='$from_user_id' AND user_two ='$authid') OR (user_one ='$authid' AND user_two ='$from_user_id')) ");

                if(!$chatids){
                    // create new chat row

                    $result = $wpdb->insert( 
                        $chat_table, 
                        array( 
                            
                            'user_one'  => $from_user_id, 
                            'user_two'  => $authid, 
                            'ip'        => $ip, 
                            'time'      => $time, 
                        ) 
                    );
                    // end inshert chat
                    // https://codex.wordpress.org/Class_Reference/wpdb#INSERT_row
                    if($result != false){
                        $chat_id_checked = $wpdb->insert_id;
                    }
                }else {
                    $chat_id_checked =  reset($chatids);
                }

                if($chat_id_checked){
                    $result = $wpdb->insert( 
                        $chat_reply_table, 
                        array( 
                            
                            'user_id_fk'    => $from_user_id, 
                            'reply'         => wp_kses_post($_POST['lmsg_message']), 
                            'ip'            => $ip, 
                            'time'          => $time, 
                            'c_id_fk'       => $chat_id_checked
                        ) 
                    );
                    if($result != false){
                        $json['data']['message'] = apply_filters( 'townhub_addons_insert_message_message', __( 'Your message is received. The listing author will contact with you soon.<br>You can also login with your email to manage messages.<br>Thank you.', 'townhub-add-ons' ) );
                        do_action( 'cth_chat_lauthor_message' );
                    }else{
                        $json['data']['error'] = __( 'Can not create chat message.', 'townhub-add-ons' );
                        wp_send_json($json );
                    }
                }else{
                    $json['data']['error'] = __( 'Can not create chat contact.', 'townhub-add-ons' );
                    wp_send_json($json );
                }

            }else{
                $json['data']['error'] = __( 'Invalide user.', 'townhub-add-ons' );
                wp_send_json($json );
            }

        }else{
            $json['data']['error'] = __( 'The author id is incorrect.', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }
        $json['success'] = true;
        wp_send_json($json );

    }
    public static function single_room() {
        // self::verify_nonce('townhub-add-ons');
        ob_start();
        ?>

        <div class="ajax-modal-close"><i class="fa fa-times"></i></div>
        <!--ajax-modal-item-->
        <div class="ajax-modal-item fl-wrap">
            <div class="ajax-modal-item-inner">
                <?php 
                // var_dump($_POST['rid']);
                $room_id = ( isset( $_POST['rid'] ) && is_numeric( $_POST['rid'] ) ) ? (int)$_POST['rid'] : 0;
                if(!$room_id){
                    _e( '<p class="sroom-error">Invalid room id</p>', 'townhub-add-ons' );
                }else{
                    $listing_id = get_post_meta( $room_id, ESB_META_PREFIX.'for_listing_id', true );
                    // var_dump($listing_id);
                    if($listing_id == ''){
                        _e( '<p class="sroom-error">Invalid listing id for room</p>', 'townhub-add-ons' );
                    }else{
                        global $post;
                        $post = get_post($room_id);
                        if(!$post){
                            _e( '<p class="sroom-error">Invalid room post</p>', 'townhub-add-ons' );
                        }else{
                            setup_postdata( $post );
                            echo townhub_addons_azp_parser_listing( get_post_meta( $listing_id, ESB_META_PREFIX.'listing_type_id', true ) , 'single_room', get_the_ID() );
                        }
                        wp_reset_postdata();                        }
                }
                ?>
            </div>
            <!--ajax-modal-item-inner-->
        </div>
        <!--ajax-modal-item-->

        <?php
        // $result = ob_get_clean(); 

        echo ob_get_clean();

        wp_die(); // this is required to terminate immediately and return a proper response

    }
    
    public static function withdrawals_get(){
        $json = array(
            'success' => false,
            'data' => array(
                // 'POST'=>$_POST,
            ),
            'earning' => 0,
            // 'posts' => array(),
            // 'pagi' => array(),

        );
        self::verify_nonce('townhub-add-ons');

        $user_id = isset($_POST['user_id'])? $_POST['user_id'] : 0; 
        if( is_numeric($user_id) && $user_id > 0 ){

            $json['earning'] = Esb_Class_Earning::getBalance($user_id);

            $withdrawals = Esb_Class_Withdrawals::getWithdrawalsPosts($user_id);

            $json['posts'] = $withdrawals['posts'];
            $json['pagi']   = $withdrawals['pagi'];

            $json['success'] = true;

            
            
        }else{
            $json['error'] = __( 'The author id is incorrect.', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }



        wp_send_json( $json );
    }
    public static function withdrawals_save(){
        $json = array(
            'success' => false,
            'data' => array(
                // 'POST'=>$_POST,
            ),

            'earning'   => 0,

        );
        self::verify_nonce('townhub-add-ons');

        $withdrawal_min = (float)townhub_addons_get_option('withdrawal_min');

        $user_id =  get_current_user_id();
        if($user_id == 0 || !isset($_POST['user_id']) || (int)$_POST['user_id'] !== $user_id){
            $json['error'] = __( 'The author id is incorrect.', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }

        // // for email address
        // if (!isset($_POST['withdrawal_email']) || !filter_var($_POST['withdrawal_email'], FILTER_VALIDATE_EMAIL)) {
        //     // invalid emailaddress
        //     $json['error'] = __( 'The email address is invalid.', 'townhub-add-ons' ) ;
        //     wp_send_json($json );
        // }

        // check withdrawal amount
        if(!isset($_POST['amount'])){
            $json['error'] = sprintf(__( 'The minimum withdrawal amount is %s USD', 'townhub-add-ons' ), $withdrawal_min);
            wp_send_json($json );
        }
        $amount = (float) $_POST['amount'];
        $earning = (float) Esb_Class_Earning::getBalance($user_id); 
        

        if($amount > $earning){
            $json['error'] = sprintf(__( 'The maximum withdrawal amount is {amount} USD', 'townhub-add-ons' ), $earning);
            wp_send_json($json );
        }elseif($amount < $withdrawal_min){
            $json['error'] = sprintf(__( 'The minimum withdrawal amount is %s USD', 'townhub-add-ons' ), $withdrawal_min);
            wp_send_json($json );
        }

        $post_datas = array();
        $post_datas['post_status'] = 'publish';
        $post_datas['post_type'] = 'lwithdrawal';
        $post_id = wp_insert_post($post_datas ,true );
        if (!is_wp_error($post_id)) {
            $post_metas = array(
                'payment_method' => esc_html($_POST['payment_method']),
                'withdrawal_email'   => esc_attr($_POST['withdrawal_email']),
                'amount'         => $amount,
                'status'         => 'pending',
                'user_id'         => $user_id,
                'notes'         => isset( $_POST['notes'] ) ? wp_kses_post($_POST['notes']) : '' ,

                'bank_iban'         => isset( $_POST['bank_iban'] ) ? sanitize_text_field( $_POST['bank_iban'] ) : '',
                'bank_account'         => isset( $_POST['bank_account'] ) ? sanitize_text_field( $_POST['bank_account'] ) : '',
                'bank_name'         => isset( $_POST['bank_name'] ) ? sanitize_text_field( $_POST['bank_name'] ) : '',
                'bank_bname'         => isset( $_POST['bank_bname'] ) ? sanitize_text_field( $_POST['bank_bname'] ) : '',
            );
            
            foreach ($post_metas as $key => $value) {
                update_post_meta( $post_id, ESB_META_PREFIX.$key,  $value  );
            }

            Esb_Class_Earning::insert_withdrawal($post_id);

            $json['earning'] = $earning - $amount ;

            $withdrawals = Esb_Class_Withdrawals::getWithdrawalsPosts($user_id);

            $json['posts'] = $withdrawals['posts'];
            $json['pagi']   = $withdrawals['pagi'];

            Esb_Class_Dashboard::add_notification($user_id, array(
                'type' => 'withdrawal_new',
                'entity_id'     => $post_id
            ));

            do_action( 'cth_insert_withdrawal_new', $post_id, $user_id );

            $json['message'] = __( 'Your withdrawal request has been received. We will check it soon.', 'townhub-add-ons' ) ;
            $json['success'] = true;
        }else{
            $json['error'] = esc_html__( 'Can not submit withdrawal post', 'townhub-add-ons' ) ;
        }  
        wp_send_json( $json );
    }

    public static function withdrawals_cancel(){
        $json = array(
            'success' => false,
            'data' => array(
                // 'POST'=>$_POST,
            ),

            'earning'   => 0,

        );
        self::verify_nonce('townhub-add-ons');

        $user_id =  get_current_user_id();
        if($user_id == 0 || !isset($_POST['user_id']) || (int)$_POST['user_id'] !== $user_id){
            $json['error'] = __( 'The author id is incorrect.', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }

        $post_id = isset($_POST['id']) ? $_POST['id'] : 0;

        if( is_numeric($post_id) && $post_id > 0 ){
            $deleted_post = wp_delete_post( $post_id, true );
            if($deleted_post){
                $json['earning'] = Esb_Class_Earning::getBalance($user_id);

                $withdrawals = Esb_Class_Withdrawals::getWithdrawalsPosts($user_id);

                $json['posts'] = $withdrawals['posts'];
                $json['pagi']   = $withdrawals['pagi'];

                Esb_Class_Dashboard::add_notification($user_id, array(
                    'type' => 'withdrawal_canceled',
                    'entity_id'     => $post_id
                ));

                do_action( 'cth_withdrawal_canceled', $post_id, $user_id );

                $json['success'] = true;
            }else{
                $json['error'] = __( 'Can not cancel the withdrawal request', 'townhub-add-ons' );
                wp_send_json($json );
            }
        }else{
            $json['error'] = __( 'Invalid withdrawal post id', 'townhub-add-ons' );
            wp_send_json($json );
        } 
        wp_send_json( $json );
    }

    

    public static function earnings_get(){
        $json = array(
            'success' => false,
            'data' => array(
                'POST'=>$_POST,
            ),
            // 'posts' => array(),
            // 'pagi' => array(),
            'debug'     => false,

        );

        // wp_send_json( $json );
        
        self::verify_nonce('townhub-add-ons');

        $user_id = isset($_POST['user_id'])? $_POST['user_id'] : 0; 
        if( is_numeric($user_id) && $user_id > 0 ){
            $earnings = Esb_Class_Earning::getEarningsPosts($user_id, $_POST);
            // $json['earnings'] = $earnings;
            $json['posts'] = $earnings['posts'];
            $json['pagi']   = $earnings['pagi'];
            $json['success'] = true;
        }else{
            $json['error'] = __( 'The author id is incorrect.', 'townhub-add-ons' ) ;
            
        }
        wp_send_json( $json );
    }
    public function booking_woo_listing() {
        $json = array(
            'success' => true,
            'data' => array(
                'POST'=>$_POST,
            )
        );
        // wp_send_json($json );
        $nonce = $_POST['_nonce'];
        
        if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
            $json['success'] = false;
            $json['data']['error'] = esc_html__( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }


        $listing_id = $_POST['slid'];
        $rid = $_POST['rid'];
        if(is_numeric($listing_id) && (int)$listing_id > 0){
            $rooms_price = 0;
            $cart_item_data = array();
            $current_user   = wp_get_current_user();
            $room_post      = get_post($rid);
            $rooms = array();
            $room       = array(
                'ID' =>  $room_post->ID,
                'title'         => get_the_title( $rid ),
                'price'         => get_post_meta( $rid, '_price', true ),
                'quantity'      => (int)$_POST['quantity'],
            );
            $rooms[] = $room;
            $rooms_price += $room['quantity'] * $room['price'];
            $default_vat = townhub_addons_get_option('vat_tax', 10);
            $subtotal_fee = $rooms_price *  ((float)$default_vat / 100);
            if(townhub_addons_get_option('booking_vat_include_fee') == 'yes'){
                $subtotal_vat = ($rooms_price + $subtotal_fee)* (float)(5 / 100);
            }
            else{
                $subtotal_vat = $rooms_price * (float)($default_vat / 100  );
            }
            
            $price_total = $rooms_price + $subtotal_fee + $subtotal_vat;
            // var_dump( $data['price_total']);
            $checkin     = ($_POST['checkin'] != '') ? $_POST['checkin'] : '';
            $checkout    = ($_POST['checkout'] != '') ? $_POST['checkout'] : '';
            $nights      = townhub_addons_booking_nights($checkin, $checkout);
            $adults      = ($_POST['adults'] != '') ? $_POST['adults'] : '';
            $children    = ($_POST['children'] != '') ? $_POST['children'] : '';

            $cart_item_data = array(
                'rooms'              => $rooms,
                'checkin'           => $checkin ,
                'checkout'          => $checkout,
                'nights'            => $nights,
                'adults'            => $adults,
                'children'          => $children,
                'price_total'       => $price_total,
                'subtotal_fee'      => $subtotal_fee,
                'subtotal_vat'      => $subtotal_vat,
                'user_id'           => $current_user->ID,
                'listing_id'        => $listing_id,
            );

            // add_filter('woocommerce_add_cart_item',$cart_item_data);


            // if(session_id() == '')
            //     session_start();   
            // $_SESSION['esb_user_custom_data'] = $cart_item_data;


            if(isset($_POST['checkin']) && $_POST['checkin'] != '' && isset($_POST['checkout']) && $_POST['checkout'] != ''){
                $available = townhub_addons_get_available_listings(
                    array(
                        'checkin'   => $_POST['checkin'],
                        'checkout'   => $_POST['checkout'],
                        'listing_id'   => $listing_id,
                    )
                );

            }

            //Add product to WooCommerce cart.
            if (!empty( $available) && is_array( $available)) {
                $quantity = (isset($_POST['quantity']) && is_numeric($_POST['quantity']) && $_POST['quantity'] )? $_POST['quantity'] : 1;
                $json['data']['url'] = townhub_addons_get_add_to_cart_url( $rid, $quantity );
            }else{
                $json['data']['message'] = apply_filters( 'townhub_addons_insert_booking_message', __( 'The room is empty.<br>Thank you.', 'townhub-add-ons' ) );
            }
        
            $json['success'] = true;

        }else{
            $json['success'] = false;
            $json['data']['error'] = esc_html__( 'The listing id is incorrect.', 'townhub-add-ons' ) ;
        }

        wp_send_json($json );

    }
    // public static function change_ltype(){
    //     $json = array(
    //         'success' => false,
    //         'data' => array(
    //             'POST'=>$_POST,
    //             'debug'     => true
    //         ),
    //     );

    //     // wp_send_json( $json );
        
    //     self::verify_nonce('townhub-add-ons');

    //     $ltype = isset($_POST['ltype'])? $_POST['ltype'] : 0; 
    //     if( is_numeric($ltype) && $ltype > 0 ){
            
    //         ob_start();

    //         townhub_addons_get_template_part('template-parts/filter/form', '', array('ltype' => $ltype) );

    //         $fform = ob_get_clean();
            
    //         $json['data']['fform']   = $fform;
    //         $json['success'] = true;
    //     }else{
    //         $json['error'] = __( 'The listing type id is incorrect.', 'townhub-add-ons' ) ;
            
    //     }
    //     wp_send_json( $json );
    // }

    public static function change_ltype(){
        // $json = array(
        //     'success' => false,
        //     'data' => array(
        //         'POST'=>$_POST,
        //         'debug'     => true
        //     ),
        //     'error' => 'testing'
        // );

        // wp_send_json( $json );
        
        // self::verify_nonce('townhub-add-ons');

        $ltype = isset($_POST['ltype'])? $_POST['ltype'] : 0; 
        // if( is_numeric($ltype) && $ltype > 0 ){
            
            ob_start();

            townhub_addons_get_template_part('template-parts/filter/form', '', array('ltype' => $ltype) );

            echo ob_get_clean();
            
            wp_die();
        // }else{
        //     $json['error'] = __( 'The listing type id is incorrect.', 'townhub-add-ons' ) ;
            
        // }
        // wp_send_json( $json );
    }
}
Esb_Class_Ajax_Handler::init();