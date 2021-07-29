<?php 
/* add_ons_php */

defined( 'ABSPATH' ) || exit;


class Esb_Class_ADs{

    public static function status_text( $status ) {
        $statuses = array(
            'completed' => _x( 'Active', 'Listing AD status', 'townhub-add-ons' ),
            'pending' => _x( 'Pending', 'Listing AD status', 'townhub-add-ons' ),
            'expired' => _x( 'Expired', 'Listing AD status', 'townhub-add-ons' ),
            // 'pending_expired' => __( 'Pending - Expired', 'townhub-add-ons' ),

            // 'trialing_in_time' => __( 'Trialing', 'townhub-add-ons' ),
            // 'trialing_expired' => __( 'Trialing - Expired', 'townhub-add-ons' ),
            
        );
        if(!empty($status) && isset($statuses[$status])) return $statuses[$status];

        return $statuses['pending'];
    }

    public static function getPosts($user_id = 0){
        $json = array(
            'posts' => array(),
            'pagi' => array(
                'range' => 2,
                'paged' => 1,
                'pages' => 1,
            ),
        );
        // The Query
        $paged = 1;

        if(isset($_POST['paged']) && $_POST['paged'] != '' && is_numeric($_POST['paged'])){
            $paged = intval($_POST['paged']);
        }

        $args = array(
            'fields'            => 'ids',
            'post_type'         =>  'cthads', 
            // 'author'            =>  $user_id, 
            'orderby'           =>  'date',
            'order'             =>  'DESC',
            'paged'             => $paged,
            'post_status'       => array( 'publish', 'pending', 'draft', 'future' ),
            'meta_query' => array(
                array(
                    'key'     => ESB_META_PREFIX.'user_id',
                    'value'   => $user_id,
                ),
            ),

        );

        $posts_query = new WP_Query( $args );

        if($posts_query->have_posts()){
            while($posts_query->have_posts()){
                $posts_query->the_post(); 

                // $status = get_post_meta( get_the_ID(), ESB_META_PREFIX.'status', true );
                $time_status = townhub_addons_get_package_time_status(get_the_ID());
                $plan_id = get_post_meta( get_the_ID(), ESB_META_PREFIX.'plan_id', true);

                $package_title = '';
                $ad_package = get_term( $plan_id, 'cthads_package' );
                // check if the ad package is deleted
                if ( ! empty( $ad_package ) && ! is_wp_error( $ad_package ) ){
                    $package_title = $ad_package->name;
                }

                $post_item_data = array(
                    'ID'            => get_the_ID(),
                    'package_title'         => $package_title,
                    'listing_title' => get_the_title( get_post_meta( get_the_ID(), ESB_META_PREFIX.'listing_id', true) ),
                    'end_date'      => get_post_meta( get_the_ID(), ESB_META_PREFIX.'end_date', true),
                    'status_text'        => self::status_text( get_post_meta( get_the_ID(), ESB_META_PREFIX.'status', true ) ),
                );

                $json['posts'][] = (object) $post_item_data;

            }
            $json['pagi']['paged'] = $paged;
            $json['pagi']['pages'] = $posts_query->max_num_pages;

        }
        wp_reset_postdata();
        return $json;
    }

    public static function active_ad($ad_id = 0){
        if(is_numeric($ad_id)&&(int)$ad_id > 0){
            $listing_id = get_post_meta( $ad_id, ESB_META_PREFIX.'listing_id', true );
            $user_id = get_post_meta( $ad_id, ESB_META_PREFIX.'user_id', true );

            $ad_package_id = get_post_meta( $ad_id, ESB_META_PREFIX.'plan_id', true );
            $ad_package_positions = get_term_meta( $ad_package_id, ESB_META_PREFIX.'ad_type', true );
            $from_date = current_time('mysql');

            if ( !update_post_meta( $ad_id, ESB_META_PREFIX.'status',  'completed' ) ) {
                if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Change ad status to completed failure" . PHP_EOL, 3, ESB_LOG_FILE);
            }
            update_post_meta( $ad_id, ESB_META_PREFIX.'from_date',  $from_date );

            // update listin is_ad to yes
            update_post_meta( $listing_id, ESB_META_PREFIX. 'is_ad', 'yes' );
            // update listing ad_position
            if($listing_id != ''){
                if(is_array($ad_package_positions)){

                    // $ad_pos_key = 1;
                    foreach ($ad_package_positions as $pos) {
                        update_post_meta( $listing_id, ESB_META_PREFIX. 'ad_position_'.$pos, '1');
                        // $ad_pos_key++;
                    }
                    
                }else{
                    update_post_meta( $listing_id, ESB_META_PREFIX. 'ad_position', $ad_package_positions);
                }

            }

            // add ad package datas to order/subscription
            $plan_interval = get_term_meta( $ad_package_id, ESB_META_PREFIX.'ad_interval', true );
            $plan_period = get_term_meta( $ad_package_id, ESB_META_PREFIX.'ad_period', true );
            if($plan_period){
                update_post_meta( $ad_id, ESB_META_PREFIX.'plan_period',  $plan_period );
                update_post_meta( $ad_id, ESB_META_PREFIX.'plan_interval',  $plan_interval );
                $end_date = townhub_add_ons_cal_next_date($from_date, $plan_period, $plan_interval);
                update_post_meta( $ad_id, ESB_META_PREFIX.'end_date', $end_date );
                update_post_meta( $listing_id, ESB_META_PREFIX.'ad_expire', $end_date );
            }else{
                $end_date = townhub_add_ons_cal_next_date('', 'day', townhub_addons_get_option('listing_expire_days') );
                update_post_meta( $ad_id, ESB_META_PREFIX.'end_date', $end_date );
                update_post_meta( $listing_id, ESB_META_PREFIX.'ad_expire', $end_date );
            }

            // ad invoice
            $plan_title         = __( 'AD Invoice title', 'townhub-add-ons' );
            $prices             = townhub_addons_get_plan_prices($ad_package_id);
        
            $ad_package = get_term( $ad_package_id, 'cthads_package' );
            if ( !empty( $ad_package ) && !is_wp_error( $ad_package ) ){
                $plan_title         = $ad_package->name;
                $prices             = townhub_addons_get_plan_prices(0, get_term_meta( $ad_package->term_id, ESB_META_PREFIX.'ad_price', true ) );

            } 

            $invoice_data = array(
                'order_id'  => $ad_id,
                'user_id'  => $user_id,
                'user_name'  => __( 'No user', 'townhub-add-ons' ),
                'user_email'  => __( 'No user email', 'townhub-add-ons' ),
                'phone'  => '',
                'from_date'  => get_post_meta( $ad_id, ESB_META_PREFIX.'from_date', true ),
                'end_date'  => get_post_meta( $ad_id, ESB_META_PREFIX.'end_date', true ),
                'payment'  => get_post_meta( $ad_id, ESB_META_PREFIX.'payment_method', true ),
                'txn_id'  => '',

                'plan_title'  => $plan_title,
                'quantity'  => get_post_meta( $ad_id, ESB_META_PREFIX.'quantity', true ),
                'amount'        => $prices['total'] ,
                'tax'           => $prices['tax'], // maybe change in the future

                // new data
                'subtotal'                  => $prices['price'],
                'subtotal_vat'              => $prices['tax'],
                'price_total'               => $prices['total'],


                'charged_to'  => '', // maybe change in the future
                'invoice_type'      => 'AD',
                'for_listing_ad'        => 'yes',
            );
            $user_datas = get_user_by( 'ID', $user_id );
            if( $user_datas ){
                $invoice_data['user_name'] = $user_datas->display_name;
                $invoice_data['user_email'] = $user_datas->user_email;
                $invoice_data['phone'] = get_user_meta( $user_id, ESB_META_PREFIX.'phone', true );
                $invoice_data['charged_to'] = $user_datas->user_email;
            }

            $new_invoice = townhub_addons_create_invoice($invoice_data);
            // end create invoice


            Esb_Class_Dashboard::add_notification($user_id, array(
                'type' => 'ad_approved',
                'entity_id'     => $listing_id
            ));
            

            do_action( 'townhub_addons_ad_approved', $ad_id );
        
        }  
    }

}
// Esb_Class_ADs::init();


