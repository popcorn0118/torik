<?php 
/* add_ons_php */

defined( 'ABSPATH' ) || exit;

class Esb_Class_Membership{

    public static function init(){
        add_action( 'townhub_addons_lorder_change_status_to_completed', array( __CLASS__, 'status_to_completed' ), 10, 1 );
    }

    public static function status_to_completed($order_id = 0){
        if(is_numeric($order_id)&&(int)$order_id > 0){
            $order_post = get_post($order_id);
            if (null != $order_post){
                $plan_id = get_post_meta( $order_id, ESB_META_PREFIX.'plan_id', true );
                $plan_period = get_post_meta( $plan_id, ESB_META_PREFIX.'period', true );
                $plan_interval = get_post_meta( $plan_id, ESB_META_PREFIX.'interval', true );
                if($plan_interval){
                    $expire = townhub_add_ons_cal_next_date('', $plan_period, $plan_interval);
                }else{
                    $expire = townhub_add_ons_cal_next_date('', 'day', townhub_addons_get_option('listing_expire_days') );
                }

                $data = array(
                    'pm_status'                 => 'completed',
                    'user_id'                   => get_post_meta( $order_id, ESB_META_PREFIX.'user_id', true ),
                    'item_number'               => $plan_id, // this is listing plan id
                    'pm_date'                   => current_time('mysql', 1), // Time at which the object was created. Measured in seconds since the Unix epoch.
                    'order_id'                  => $order_id,
                    'recurring_subscription'    => false, // not used

                    'txn_id'                    => uniqid('manual_sub'), // invoice id

                    // for stripe period
                    'payment_method'            => __( 'Manual Subscription', 'townhub-add-ons' ),
                    'period_start'              => current_time('mysql', 1),
                    'period_end'                => $expire,

                );
                self::active_membership($data, false);

            }
        }

    }

    public static function active_membership($pm_datas = array(), $stripe_date = false){

        $plan_post = get_post( $pm_datas['item_number'] );
        $order_id = $pm_datas['order_id'];
        // check if the plan is deleted
        if(null == $plan_post || 'trash' == $plan_post->post_status ){
            return;
            // if(get_post_meta( $order_id, ESB_META_PREFIX.'plan_period', true ) == '') return;  // also need check for plan datas attached to order in case of deleted plan post
        }
        $plan_id = $plan_post->ID;
        $from_date = $stripe_date === 'utc' ? $pm_datas['pm_date'] : ( $stripe_date ? townhub_add_ons_charge_date( $pm_datas['pm_date'] ) : townhub_add_ons_payment_date( $pm_datas['pm_date'] ) );

        $plan_period = get_post_meta( $plan_id, ESB_META_PREFIX.'period', true );
        $plan_interval = get_post_meta( $plan_id, ESB_META_PREFIX.'interval', true );

        if(get_post_meta( $order_id, ESB_META_PREFIX.'yearly_price', true ) === '1'){
            $plan_period = 'year';
            $plan_interval = 1;
        }

        $end_date = townhub_add_ons_cal_next_date($from_date, $plan_period, $plan_interval);
        // need to update user to listing author membership
        // with $pm_datas['item_number'] -> plan_id
        // with $pm_datas['listing_id'] -> listing_id
        $user_id = $pm_datas['user_id'];

        // $userObject = new WP_User( $user_id );

        $author_fee = get_post_meta( $plan_id, ESB_META_PREFIX.'author_fee', true );



        // update role for subscriber and listing customer only 
        $current_role = townhub_addons_get_user_role($user_id);
        // only update role if lower role
        if(in_array($current_role, array( 'author', 'contributor', 'subscriber', 'l_customer', 'customer' ))){
            $user_id_new = wp_update_user( array( 'ID' => $user_id, 'role' => townhub_addons_get_option('author_role') ) );
            if ( is_wp_error( $user_id_new ) ) {
                if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Can not update user role to listing_author" . PHP_EOL, 3, ESB_LOG_FILE);
            }else{
                Esb_Class_Dashboard::add_notification($user_id, array(
                    'type' => 'role_change',
                ));
            }
            // Esb_Class_Dashboard::add_notification($user_id, array(
            //     'type' => 'role_change',
            // ));
            // for dokan
            if( townhub_addons_get_option('author_role') == 'seller' && function_exists('dokan_get_option') ){
                if ( dokan_get_option( 'new_seller_enable_selling', 'dokan_selling' ) == 'off' ) {
                    update_user_meta( $user_id, 'dokan_enable_selling', 'no' );
                } else {
                    update_user_meta( $user_id, 'dokan_enable_selling', 'yes' );
                }
                update_user_meta( $user_id, 'dokan_admin_percentage_type',  'percentage' );
                update_user_meta( $user_id, 'dokan_admin_percentage',  $author_fee );
                if(townhub_addons_get_option('auto_publish_paid_listings','no') == 'yes'){
                    update_user_meta( $user_id, 'dokan_publish',  'yes' );
                }
            }
            // WCFM vendor
            if( townhub_addons_get_option('author_role') == 'wcfm_vendor' && function_exists('dokan_get_option') ){

            }
            
        }

        // marketplace plugin
        $woo_limit = get_post_meta( $plan_id, ESB_META_PREFIX.'woo_limit', true );
        if( $woo_limit === '' ){
            $woo_limit = 10;
        }
        update_user_meta( $user_id, ESB_META_PREFIX.'woo_limit',  $woo_limit );
        

        update_user_meta( $user_id, ESB_META_PREFIX.'member_plan',  $plan_id );
        // payment date $pm_datas['pm_date']
        
        update_user_meta( $user_id, ESB_META_PREFIX.'payment_date',  $from_date );

        update_user_meta( $user_id, ESB_META_PREFIX.'end_date',  $end_date );

        // update author free
        update_user_meta( $user_id, ESB_META_PREFIX.'author_fee',  $author_fee );

        update_user_meta( $user_id, ESB_META_PREFIX.'order_id',  $order_id );

        // update user order/subscription ids array
        // $user_orders = get_user_meta($user_id,  ESB_META_PREFIX.'subscriptions', true );
        // if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "User orders before" . json_encode($user_orders). PHP_EOL, 3, ESB_LOG_FILE);
        // if( is_array($user_orders) ){
        //     if( !in_array($order_id, $user_orders) ) $user_orders[] = $order_id;
        // }else{
        //     $user_orders = array($order_id);
        // }
        // if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "User orders after" . json_encode($user_orders). PHP_EOL, 3, ESB_LOG_FILE);
        // if ( !update_user_meta( $user_id, ESB_META_PREFIX.'subscriptions',  $user_orders ) ) {
        //     if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Can not update subscriptions user data" . PHP_EOL, 3, ESB_LOG_FILE);
        // }



        
        

        // update order status
        update_post_meta( $order_id, ESB_META_PREFIX.'status',  $pm_datas['pm_status'] );
        // update author fee
        update_post_meta( $order_id, ESB_META_PREFIX.'author_fee',  $author_fee );
        // update payment count - useful for check recurring payment
        $payment_count = get_post_meta( $order_id, ESB_META_PREFIX.'payment_count', true );
        if(!$payment_count) 
            $payment_count = 1;
        else 
            $payment_count += 1;
        
        update_post_meta( $order_id, ESB_META_PREFIX.'payment_count',  $payment_count );

        /// ALSO USE ORDER AS AUTHOR SUBSCRIPTION RECORD
        update_post_meta( $order_id, ESB_META_PREFIX.'plan_id',  $pm_datas['item_number'] );
        // valid date from - only add active date for newly created order - not for next payment
        if(get_post_meta( $order_id, ESB_META_PREFIX.'from_date', true ) == ''){
            update_post_meta( $order_id, ESB_META_PREFIX.'from_date',  $from_date );
        }
        // add plan datas to order/subscription
        // listing submission limit
        $limit = get_post_meta( $plan_id , ESB_META_PREFIX.'lunlimited', true )? 'unlimited' : get_post_meta( $plan_id , ESB_META_PREFIX.'llimit', true );
        if($plan_period){
            update_post_meta( $order_id, ESB_META_PREFIX.'plan_period',  $plan_period );
            update_post_meta( $order_id, ESB_META_PREFIX.'plan_interval',  $plan_interval );
            
            // calculate expired date
            
            update_post_meta( $order_id, ESB_META_PREFIX.'end_date',  $end_date );

            
            update_post_meta( $order_id, ESB_META_PREFIX.'plan_llimit',  $limit );
            
        }
        if( get_post_meta( $plan_id, ESB_META_PREFIX.'lnever_expire', true ) ){
            update_post_meta( $order_id, ESB_META_PREFIX.'end_date',  'NEVER' );

            update_user_meta( $user_id, ESB_META_PREFIX.'end_date',  'NEVER' );

        }
        // update trial end date 
        if($pm_datas['pm_status'] == 'trialing'){
            $trial_interval = get_post_meta( $plan_id , ESB_META_PREFIX.'trial_interval', true );
            $trial_period = get_post_meta( $plan_id , ESB_META_PREFIX.'trial_period', true );

            if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Order trial_interval: $trial_interval" . PHP_EOL, 3, ESB_LOG_FILE);
            if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Order trial_period: $trial_period" . PHP_EOL, 3, ESB_LOG_FILE);


            if ( !update_post_meta( $order_id, ESB_META_PREFIX.'end_date',  townhub_add_ons_cal_next_date($from_date, $trial_period, $trial_interval) ) ) {
                if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Can not update end_date order/subscription data" . PHP_EOL, 3, ESB_LOG_FILE);
            }
        }
        // check for existing purchase code
        if(get_post_meta( $order_id, ESB_META_PREFIX.'purchase_code', true ) == ''){
            update_post_meta( $order_id, ESB_META_PREFIX.'purchase_code',  townhub_addons_create_purchase_code() );
        }


        // update listing status to publish if enabled
        if(townhub_addons_get_option('auto_publish_paid_listings','no') == 'yes'){
            // $user_end_date = get_user_meta( $user_id, ESB_META_PREFIX.'end_date',  true );
            $user_end_date = self::expire_date( $user_id );
            $listing_posts = get_posts( array(
                'post_type'         => 'listing',
                'author'            => $user_id,
                'fields'            => 'ids',
                // 'meta_query'        => array(
                //     array(
                //         'key'       => ESB_META_PREFIX.'listing_sub_plan',
                //         'value'     => $plan_id,
                //     )
                // ),
                'posts_per_page'    => $limit == 'unlimited' ? -1 : intval($limit),
                'post_status'       => 'pending',
            ) );
            if( !empty($listing_posts) ){
                foreach ($listing_posts as $pid) {
                    wp_update_post( 
                        array(
                            'ID'                => $pid,
                            'post_status'       => 'publish',
                        ),
                        true 
                    );

                    update_post_meta( $listing_id, ESB_META_PREFIX.'expire_date',  $user_end_date );
                    
                }
            }
        }

        // update order/subscription transaction ids array - paypal: txn_id
        if(isset($pm_datas['txn_id']) && $pm_datas['txn_id'] != ''){
            // create new invoice post
            $plan_price = get_post_meta($plan_id, '_price', true);
            if( !empty($plan_price) || townhub_addons_get_option('free_plan_invoice') == 'yes' ){

                $required_data = array(
                    'order_id'  => $order_id,
                    'user_id'  => $user_id,
                    'user_name'  => __( 'No user', 'townhub-add-ons' ),
                    'user_email'  => __( 'No user email', 'townhub-add-ons' ),
                    'phone'  => '',
                    'from_date'  => get_post_meta( $order_id, ESB_META_PREFIX.'from_date', true ),
                    'end_date'  => get_post_meta( $order_id, ESB_META_PREFIX.'end_date', true ),
                    'payment'  => get_post_meta( $order_id, ESB_META_PREFIX.'payment_method', true ),
                    'txn_id'  => $pm_datas['txn_id'],

                    'plan_title'    => get_the_title( $plan_id ),
                    'quantity'      => get_post_meta( $order_id, ESB_META_PREFIX.'quantity', true ),
                    'amount'        => get_post_meta( $order_id, ESB_META_PREFIX.'price_total', true ),
                    'subtotal'      => get_post_meta( $order_id, ESB_META_PREFIX.'subtotal', true ),
                    'subtotal_vat'  => get_post_meta( $order_id, ESB_META_PREFIX.'subtotal_vat', true ),
                    'price_total'   => get_post_meta( $order_id, ESB_META_PREFIX.'price_total', true ),
                    'tax'           => get_post_meta( $order_id, ESB_META_PREFIX.'subtotal_vat', true ), // maybe change in the future
                    'charged_to'    => '', // maybe change in the future
                );
                $user_datas = get_user_by( 'ID', $user_id );
                if( $user_datas ){
                    $required_data['user_name'] = $user_datas->display_name;
                    $required_data['user_email'] = $user_datas->user_email;
                    $required_data['charged_to'] = $user_datas->user_email;
                    $required_data['phone'] = get_user_meta( $user_id, ESB_META_PREFIX.'phone', true );
                }

                $new_invoice = townhub_addons_create_invoice($required_data);
                if($new_invoice != false){
                    $order_transactions = get_post_meta($order_id,  ESB_META_PREFIX.'transactions', true );
                    if( is_array($order_transactions) ){

                        if(!array_search($new_invoice, $order_transactions)){
                            $order_transactions[] = $new_invoice;
                        }

                        // if (!array_key_exists($pm_datas['txn_id'],$order_transactions)){
                        //     $order_transactions[$pm_datas['txn_id']] = array(
                        //         'txn_id' => $pm_datas['txn_id'],
                        //         'quantity' => get_post_meta( $order_id, ESB_META_PREFIX.'quantity', true ),
                        //         'amount' => get_post_meta( $order_id, ESB_META_PREFIX.'amount', true ),
                        //         'plan_id' => get_post_meta( $order_id, ESB_META_PREFIX.'plan_id', true ),
                        //     );
                        // }
                    }else{
                        $order_transactions = array($new_invoice);
                        // $order_transactions = array();
                        // $order_transactions[$pm_datas['txn_id']] = array(
                        //     'txn_id' => $pm_datas['txn_id'],
                        //     'quantity' => get_post_meta( $order_id, ESB_META_PREFIX.'quantity', true ),
                        //     'amount' => get_post_meta( $order_id, ESB_META_PREFIX.'amount', true ),
                        //     'plan_id' => get_post_meta( $order_id, ESB_META_PREFIX.'plan_id', true ),
                            
                        // );
                    }
                    update_post_meta( $order_id, ESB_META_PREFIX.'transactions',  $order_transactions );
                }

            }
            // end check free plan
                 
        }

        // will create new linvoice post type to store user invoices
        if(get_post_meta( $order_id, ESB_META_PREFIX.'is_recurring', true ) == 'on' && isset($pm_datas['subscription_id'])){

            update_post_meta( $order_id, ESB_META_PREFIX.'subscription_id',  $pm_datas['subscription_id'] );
        }

        // for recurring subscription
        if( isset($pm_datas['recurring_subscription']) && $pm_datas['recurring_subscription']){ // for stripe
            // to do tasks
        }

        do_action( 'townhub_addons_order_completed', $order_id );

    }

    public static function deactive_membership($order_id = 0){
        $order_post = get_post( $order_id );
        // check if the subscription post is deleted
        if(null == $order_post || 'trash' == $order_post->post_status ){
            return;
        }
        // update subscribe post
        update_post_meta( $order_id, ESB_META_PREFIX.'status', 'expired' );

        // // author id
        // $user_id = get_post_meta( $order_id, ESB_META_PREFIX.'user_id', true );
        // // update author subscribe datas
        // update_user_meta( $user_id, ESB_META_PREFIX.'member_plan', '' );
        // update_user_meta( $user_id, ESB_META_PREFIX.'end_date', '' );

    }

    public static function get_listing_type_data($posts = array()){
        $listing_types = array();
        if(!empty($posts)){
            foreach ((array)$posts as $ltid) {
                $listing_types[] = array(
                    'ID'            => $ltid,
                    'title'         => get_the_title( $ltid ),
                    'icon'          => '',
                    'description'   => '',
                );
            }
        }
        return $listing_types;
    }

    public static function admin_listing_types(){
        $posts = get_posts( array(
            'fields'            => 'ids',
            'post_type'         => 'listing_type',
            'posts_per_page'    => -1,
            'post_status'       => 'publish',
            
            'suppress_filters'  => false,
        ) );
        return self::get_listing_type_data($posts);
    }

    public static function author_listing_types(){
        $listing_types = array();
        if(is_user_logged_in()){
            // admin is allow adding all types
            if(townhub_addons_get_user_role() == 'administrator'){
                return self::admin_listing_types();
            }
            $member_plan = get_user_meta( get_current_user_id(), ESB_META_PREFIX.'member_plan', true );
            if($member_plan != ''){
                $plan_ltypes = (array)get_post_meta( $member_plan, ESB_META_PREFIX.'listing_types', true  );
                $plan_ltypes = get_posts( array(
                    'fields'            => 'ids',
                    'post_type'         => 'listing_type',
                    'posts_per_page'    => -1,
                    'post_status'       => 'publish',
                    
                    'suppress_filters'  => false,

                    'post__in'          => $plan_ltypes,
                    'orderby'           => 'post__in',
                ) );
                return self::get_listing_type_data($plan_ltypes);
            }
        }
        return $listing_types;
    }

    public static function author_listing_types_ids(){
        $allow_types = self::author_listing_types();
        $allow_types = array_map(function($type){
            return $type['ID'];
        }, $allow_types);

        return $allow_types;
    }

    public static function can_add($user_id = 0){
        if(is_user_logged_in()) $user_id = get_current_user_id();

        if( !$user_id ) return false;

        // admin is allow adding all types
        if(townhub_addons_get_user_role($user_id) == 'administrator'){
            return true;
        }
        $member_plan = get_user_meta( $user_id, ESB_META_PREFIX.'member_plan', true );
        if($member_plan != ''){
            $end_date = get_user_meta( $user_id, ESB_META_PREFIX .'end_date', true );
            if($end_date == 'NEVER' || ($end_date != '' && townhub_addons_compare_dates('now', $end_date, '<=') )){
                if(get_post_meta( $member_plan, ESB_META_PREFIX .'lunlimited', true ) === 'on') 
                    return true;
                
                $limit = get_post_meta( $member_plan, ESB_META_PREFIX .'llimit', true );
                $limit = !empty( $limit ) && intval($limit) > 0 ? $limit : 1;
                // if( count_user_posts( $user_id, 'listing', false ) < (int)$limit ) 
                //     return true;

                $ltPosts = get_posts(array(
                    'fields'                => 'ids',
                    'post_type'             => 'listing',
                    'author'                => $user_id,
                    'posts_per_page'        => -1,
                    'post_status'           => array('publish', 'pending', 'private'), // publish, future, draft, pending, private, trash, auto-draft, inherit
                    'suppress_filters'      => false,
                ));
                if( count( $ltPosts ) < (int)$limit ) return true;
            }
        }

        return false; 
    }

    public static function expire_date($user_id = 0){
        if( empty($user_id) && is_user_logged_in()) $user_id = get_current_user_id();
        // admin is allow adding all types
        if(townhub_addons_get_user_role($user_id) == 'administrator'){
            return 'NEVER';
        }
        $member_plan = get_user_meta( $user_id, ESB_META_PREFIX.'member_plan', true );
        if($member_plan != ''){
            $end_date = get_user_meta( $user_id, ESB_META_PREFIX .'end_date', true );
                return $end_date;
        }
        return townhub_add_ons_cal_next_date('', 'day', townhub_addons_get_option('listing_expire_days') );
    }

    public static function is_author($user_id = 0){
        if( empty($user_id) && is_user_logged_in() ) $user_id = get_current_user_id();

        if( !$user_id ) return false;
        // shop_manager - woo shop manager role
        // seller - Dokan vendor
        $author_roles = apply_filters( 'esb_author_roles', array('administrator','listing_author','seller','shop_manager','wcfm_vendor') );

        if( in_array(townhub_addons_get_user_role($user_id), $author_roles) ) return true;

        return false;
    }

    public static function current_plan($user_id = 0){
        if( empty($user_id) && is_user_logged_in() ) 
            $user_id = get_current_user_id();
        // check for admin
        $user_role = townhub_addons_get_user_role($user_id);
        if( $user_role == 'administrator' ){
            return townhub_addons_get_option('admin_lplan');
        }
        return get_user_meta( $user_id, ESB_META_PREFIX.'member_plan', true );
    }

    public static function current_sub($user_id = 0){
        if( empty($user_id) && is_user_logged_in() ) $user_id = get_current_user_id();

        return get_user_meta( $user_id, ESB_META_PREFIX.'order_id', true );
    }

    public static function plan_expire_date( $plan_id = 0, $from_date = '' ){
        // admin is allow adding all types
        if( is_user_logged_in() && townhub_addons_get_user_role( get_current_user_id() ) == 'administrator' ){
            return 'NEVER';
        } 

        if( is_numeric($plan_id)&&(int)$plan_id > 0 ){
            $plan_period = get_post_meta( $plan_id, ESB_META_PREFIX.'period', true );
            $plan_interval = get_post_meta( $plan_id, ESB_META_PREFIX.'interval', true );

            // will check for listing plan with yearly subscription
            // if(get_post_meta( $plan_id, ESB_META_PREFIX.'yearly_price', true ) === '1'){
            //     $plan_period = 'year';
            //     $plan_interval = 1;
            // }

            if( get_post_meta( $plan_id, ESB_META_PREFIX.'lnever_expire', true ) ){
                return 'NEVER';
            }

            return townhub_add_ons_cal_next_date($from_date, $plan_period, $plan_interval);
        }
        return townhub_add_ons_cal_next_date('', 'day', townhub_addons_get_option('listing_expire_days') );
    }

    public static function can_buy_again($plan_id = 0, $user_id = 0 ){
        if( empty($user_id) && is_user_logged_in() ) $user_id = get_current_user_id();

        if( !$user_id ) return false;
        
        if( get_post_meta( $plan_id, ESB_META_PREFIX.'can_buy_again', true ) == 'on' ){
            return true;
        }else{
            $query_args = array(
                'post_type'         => 'lorder',
                'posts_per_page'    => -1,
                'post_status'       => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key'     => ESB_META_PREFIX.'status',
                        'value'   => array('completed', 'expired'),
                        'compare' => 'IN',
                        'type'    => 'CHAR'
                    ),
                    array(
                        'key'     => ESB_META_PREFIX.'user_id',
                        'value'   => $user_id,
                        'compare' => '=',
                        'type'    => 'NUMERIC'
                    ),
                    array(
                        'key'     => ESB_META_PREFIX.'plan_id',
                        'value'   => $plan_id,
                        'compare' => '=',
                        'type'    => 'NUMERIC'
                    )
                    
                ),
                // 'orderby' => 'date',
                // 'order' => 'DESC'
            );
            $expired_subs = get_posts( $query_args );
            if( empty($expired_subs) ){
                return true;
            }
        }
        return false;
    }

    public static function default_ltype(){
        if(is_user_logged_in()){
            $member_plan = get_user_meta( get_current_user_id(), ESB_META_PREFIX.'member_plan', true );
            if($member_plan != ''){
                $dfltype = get_post_meta( $member_plan, ESB_META_PREFIX.'dfltype', true  );
                if( is_numeric($dfltype) ){
                    return apply_filters( 'wpml_object_id', $dfltype, 'listing_type', true );
                }
            }
        }
        return esb_addons_get_wpml_option('default_listing_type', 'listing_type');
    }
    public static function subscription_status(){
        if( false == self::is_author() ){
            // for customer
            return sprintf( _x( '<div class="substatus-status substatus-not-author"><i class="fal fa-info green-bg"></i> <a href="%s">Become author</a> to submit listing?</div>', 'Subscription status message', 'townhub-add-ons' ), get_permalink( esb_addons_get_wpml_option('packages_page') ) );
        }elseif( false == self::can_add() ){
            $expired = self::expire_date();
            if( $expired == 'NEVER' ){
                // hit submission limit
                return sprintf( _x( '<div class="substatus-status substatus-hit-limit"><i class="fal fa-exclamation red-bg"></i> You have reached your listing submission limit. <a href="%s">Please upgrade to a higher package</a> to continue submitting listings</div>', 'Subscription status message', 'townhub-add-ons' ), get_permalink( esb_addons_get_wpml_option('packages_page') ) );
            }elseif( Esb_Class_Date::compare( $expired, 'now', '<' ) ){
                // subscription expired
                return sprintf( _x( '<div class="substatus-status substatus-expired"><i class="fal fa-exclamation red-bg"></i> Your subscription has expired. <a href="%s">Please renew your subscription</a> to continue submitting listings</div>', 'Subscription status message', 'townhub-add-ons' ), get_permalink( esb_addons_get_wpml_option('packages_page') ) );
            }
        }else{
            // admin
            return _x( '<div class="substatus-status substatus-admin"><i class="fal fa-info green-bg"></i> You are able to submit listings</div>', 'Subscription status message', 'townhub-add-ons' );
        }
    }
}
Esb_Class_Membership::init();

/*

add_filter( 'post_password_required', function($required, $post){
    // check if is your specific page id
    if($post->ID == 100){ // change 100 with your page id
        if(is_user_logged_in() == false){
            // hide page for not logged in users
            return true; // 
        }else{
            $user_plan = get_user_meta( get_current_user_id(), ESB_META_PREFIX.'member_plan', true );
            if($user_plan == 1000){ // change 1000 with plan id allowed for the page id
                return false;
            }

        }
        return true;
    }

    return $required;

}, 10, 2 );

*/