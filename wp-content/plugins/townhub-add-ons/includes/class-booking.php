<?php 
/* add_ons_php */

defined( 'ABSPATH' ) || exit; 

class Esb_Class_Booking{

    public static function init(){
        add_action( 'before_delete_post', array( __CLASS__, 'before_delete_post' ), 10, 1 ); 
        add_action( 'townhub_addons_lbooking_change_status_to_completed', array( __CLASS__, 'approve_booking' ), 10, 1 );
        add_action( 'townhub_addons_lbooking_change_status_completed_to_refunded', array( __CLASS__, 'refund_booking' ), 10, 1 );
        add_action( 'townhub_addons_booking_canceled_before', array( __CLASS__, 'refund_canceled_booking' ), 10, 1 );


    }

    public static function approve_booking($booking_id = 0){
        if(is_numeric($booking_id)&&(int)$booking_id > 0){
            $listing_id = get_post_meta( $booking_id, ESB_META_PREFIX.'listing_id', true );
            $booking_user_id = get_post_meta( $booking_id, ESB_META_PREFIX.'user_id', true );
                // not for manual approved
                update_post_meta( $booking_id, ESB_META_PREFIX.'lb_status',  'completed'  );
            // if ( !update_post_meta( $booking_id, ESB_META_PREFIX.'lb_status',  'completed'  ) ) {
            //     if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Update booking status to completed failure" . PHP_EOL, 3, ESB_LOG_FILE);
            // }else{
            if( !empty($booking_user_id) ){
                Esb_Class_Dashboard::add_notification($booking_user_id, array(
                    'type' => 'booking_approved',
                    'entity_id'     => $listing_id
                ));
            }
                
            // update author earning
            $listing_author_id = get_post_field( 'post_author', $listing_id );
            if($listing_author_id){
                $inserted_earning = Esb_Class_Earning::insert($booking_id, $listing_author_id, $listing_id);
                
            }

            // update cth_booking status: 0 - insert - 1 - active
            self::update_cth_booking_status($booking_id, 1);

            do_action( 'townhub_addons_booking_approved', $booking_id );
        // }
        }        

    }

    public static function refund_booking($booking_id = 0){
        if(is_numeric($booking_id)&&(int)$booking_id > 0){
            $listing_id = get_post_meta( $booking_id, ESB_META_PREFIX.'listing_id', true );
            $booking_user_id = get_post_meta( $booking_id, ESB_META_PREFIX.'user_id', true );
                // not for manual approved
                // update_post_meta( $booking_id, ESB_META_PREFIX.'lb_status',  'completed'  );
            // if ( !update_post_meta( $booking_id, ESB_META_PREFIX.'lb_status',  'completed'  ) ) {
            //     if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Update booking status to completed failure" . PHP_EOL, 3, ESB_LOG_FILE);
            // }else{
            // if( !empty($booking_user_id) ){
            //     Esb_Class_Dashboard::add_notification($booking_user_id, array(
            //         'type' => 'booking_refunded',
            //         'entity_id'     => $listing_id
            //     ));
            // }
                
            // update author earning
            $listing_author_id = get_post_field( 'post_author', $listing_id );
            if($listing_author_id){
                $inserted_earning = Esb_Class_Earning::insert_refund($booking_id, $listing_author_id, $listing_id);
                
            }

            // update cth_booking status: 0 - insert - 1 - active
            // self::update_cth_booking_status($booking_id, 1);

            do_action( 'townhub_addons_booking_edit_refunded', $booking_id );
        // }
        }     
    }
    public static function refund_canceled_booking($booking_id = 0){
        if(is_numeric($booking_id)&&(int)$booking_id > 0){
            $listing_id = get_post_meta( $booking_id, ESB_META_PREFIX.'listing_id', true );
            $lb_status = get_post_meta( $booking_id, ESB_META_PREFIX.'lb_status', true );
            
            // update author earning
            $listing_author_id = get_post_field( 'post_author', $listing_id );
            if( $listing_author_id && 'completed' == $lb_status ){
                // check for woo
                $pmMethod = get_post_meta( $booking_id, ESB_META_PREFIX.'payment_method', true  ); // woo
                $order_id = get_post_meta( $booking_id, ESB_META_PREFIX.'woo_order', true  );
                if( $pmMethod == 'woo' && !empty($order_id) && class_exists('WC_Order') ){
                    $order = new WC_Order($order_id);
                    
                    if( townhub_addons_get_option('woo_cancel_and_refund') == 'yes' ){
                        update_post_meta( $order_id, '_cth_check_earning', 'refunded' );

                        $order->update_status('refunded', _x( 'The booking was canceled from front-end dashboard. And refunded to customer', 'Woo note', 'townhub-add-ons' ) );

                        Esb_Class_Earning::insert_refund($booking_id, $listing_author_id, $listing_id);
                    }else{
                        update_post_meta( $order_id, '_cth_check_earning', 'cancelled' );
                        $order->update_status('cancelled', _x( 'The booking was canceled from front-end dashboard', 'Woo note', 'townhub-add-ons' ) );
                    }
                    
                }else{
                    Esb_Class_Earning::insert_refund($booking_id, $listing_author_id, $listing_id);
                }
            }

            // update cth_booking status: 0 - insert - 1 - active
            // self::update_cth_booking_status($booking_id, 1);

            // do_action( 'townhub_addons_booking_edit_refunded', $booking_id );
        // }
        }     
    }


    public static function get_datas($posts = array(), $data_period = 'week', $add_param = '', $type = 'listing_view'){
        global $wpdb;
        $tb_name = $wpdb->prefix . 'posts';
        $meta_tb_name = $wpdb->prefix . 'postmeta';
        if(is_array($posts) && !empty($posts)){

            // Count the number of posts
            $postsCount = count($posts);
            // Prepare the right amount of placeholders, in an array
            // For strings, you would use, ‘%s’
            $postsPlaceholders = array_fill(0, $postsCount, '%d');
            // Put all the placeholders in one string ‘%s, %s, %s, %s, %s,…’
            $placeholdersForPosts = implode(',', $postsPlaceholders);

            $joins = array("INNER JOIN $meta_tb_name AS wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id )");

            $status_where = '';
            $bk_show_status = (array)townhub_addons_get_option('bk_show_status');
            $bk_show_status = array_filter($bk_show_status);
            if( !empty($bk_show_status) ){
                
                $statusPlaceholders = array_fill(0, count($bk_show_status), '%s');
                $statusPlaceholders = implode(',', $statusPlaceholders);
                $joins[] = "INNER JOIN $meta_tb_name AS wp_postmeta_2 ON ( wp_posts.ID = wp_postmeta_2.post_id )";
                $status_where = " AND ( wp_postmeta_2.meta_key = '_cth_lb_status' AND wp_postmeta_2.meta_value IN ($statusPlaceholders) )";
            }
            $joins_str = implode(" ", $joins);
            $add_query = '';
            $add_params = array();
            switch ($data_period) {
                case 'alltime':
                    $add_query = " GROUP BY year ORDER BY year ASC";
                    $add_params = array();
                    break;
                case 'year':
                    $add_query = "AND ( YEAR( wp_posts.post_date ) = %d ) GROUP BY month ORDER BY month ASC LIMIT %d";
                    $add_params = array((int)$add_param , 12);
                    break;
                case 'month':
                    $mparam = substr($add_param, -2);
                    $yparam = substr($add_param, 0, 4);
                    $add_query = "AND ( YEAR( wp_posts.post_date ) = %d AND MONTH( wp_posts.post_date ) = %d ) GROUP BY date ORDER BY date ASC LIMIT %d";
                    $add_params = array((int)$yparam, (int)$mparam , 31);
                    break;
                default:
                    // week
                    $prtime = strtotime($add_param);
                    if( $prtime ){
                        $today = getdate($prtime);
                        $add_query = "AND ( YEAR( wp_posts.post_date ) = %d AND MONTH( wp_posts.post_date ) = %d AND DAYOFMONTH( wp_posts.post_date ) >= %d ) GROUP BY date ORDER BY date ASC LIMIT %d";
                        $add_params = array($today['year'], $today['mon'], $today['mday'], 7);
                    }
                    break;
            }
            $main_query = "SELECT COUNT(wp_posts.ID) AS sum, YEAR( wp_posts.post_date ) AS year, MONTH( wp_posts.post_date ) AS month, DATE(wp_posts.post_date) AS date FROM $tb_name AS wp_posts $joins_str WHERE 1=1 AND ( wp_postmeta.meta_key = '_cth_listing_id' AND CAST(wp_postmeta.meta_value AS SIGNED) IN ($placeholdersForPosts) ) AND wp_posts.post_type = 'lbooking' AND ((wp_posts.post_status = 'publish')) $status_where $add_query";
            $list_stats = $wpdb->get_results( $wpdb->prepare( $main_query, array_merge( $posts, $bk_show_status, $add_params ) ), ARRAY_A );
            return $list_stats;
        }
        return array();
    }

    // public static function paypal_completed_check($payment_data = array(), $booking_id = 0){
    //     // check for amount
    //     // $bk_price = get_post_meta( $booking_id, ESB_META_PREFIX.'price_total', true );
    //     if($payment_data['pm_amount'] == get_post_meta( $booking_id, ESB_META_PREFIX.'price_total', true )) self::approve_booking($booking_id);

    // }

    public static function update_cth_booking_status($booking_id = 0, $status = 0){
        global $wpdb;
        $booking_table = $wpdb->prefix . 'cth_booking';
        $wpdb->update( $booking_table, array( 'status' => $status ), array( 'booking_id' => $booking_id ), array( '%d' ), array( '%d' ) );
    }

    // before delete booking and room post
    public static function before_delete_post($postid = 0){
        global $wpdb;
        $post_type = get_post_type($postid);
        if($post_type === 'lbooking' || $post_type === 'lrooms'){
            $booking_table = $wpdb->prefix . 'cth_booking';
            $wpdb->query( 
                $wpdb->prepare( 
                    "
                    DELETE FROM $booking_table
                    WHERE booking_id = %d OR room_id = %d
                    ",
                    $postid,
                    $postid
                )
            );
        }
    }


}
Esb_Class_Booking::init();
