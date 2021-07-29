<?php 
/* add_ons_php */

defined( 'ABSPATH' ) || exit;


class Esb_Class_Earning{

    public static function init(){
        add_action( 'before_delete_post', array( __CLASS__, 'before_delete_post' ), 10, 1 );
    }
    public static function getBalance($author_id = 0){
        global $wpdb;
        $tb_name = $wpdb->prefix . 'cth_austats';

        // $type = 'author_earning';

        $author_earning = $wpdb->get_var(  
            $wpdb->prepare( 
                "
                SELECT SUM(earning) FROM $tb_name 
                WHERE author_id = %d AND ((type = %s AND status = 1) OR (type = %s AND status = 1) OR (type = %s AND status IN (0,1)))
                ",
                $author_id,
                'author_earning',
                'author_refund',
                'author_withdrawal'
            )
        );

        if($author_earning === NULL) 
            return 0;

        return $author_earning;
    }

    public static function insert($order_id = 0, $author_id = 0, $listing_id = 0, $woo_product = false){
        global $wpdb;
        $tb_name = $wpdb->prefix . 'cth_austats';
        $year = date_i18n('Y');
        $month = date_i18n('m');
        $date = date_i18n('Y-m-d');
        $time = date_i18n('U');

        $type = 'author_earning';

        if($order_id && $author_id){
            $author_fee = (float)get_user_meta($author_id, ESB_META_PREFIX.'author_fee', true);
            // if( $author_fee === '' ) $author_fee = apply_filters('esb_author_fee_default', 10);

            $fixed_fee = (float)get_user_meta($author_id, ESB_META_PREFIX.'fixed_fee', true);

            if( $woo_product ){
                $order_total = $subtotal = (float)get_post_meta( $order_id, '_price', true );
                $vat_tax = 0;
                $fees = 0;
            }else{
                $order_total = (float)get_post_meta( $order_id, ESB_META_PREFIX.'price_total', true );
                $subtotal = (float)get_post_meta( $order_id, ESB_META_PREFIX.'subtotal', true );
                $vat_tax = (float)get_post_meta( $order_id, ESB_META_PREFIX.'subtotal_vat', true );
                $fees = (float)get_post_meta( $order_id, ESB_META_PREFIX.'subtotal_fee', true );
            }
            // add ability calc author commission based on order total;
            $author_fee = (float)apply_filters( 'esb_calc_author_fee', $author_fee, $author_id, $order_id, $subtotal );
            $fee_amount = ($subtotal * $author_fee)/100;
            // add ability calc author commission based on order total;
            $fee_amount = (float)apply_filters( 'esb_calc_author_fee_amount', $fee_amount, $author_id, $order_id, $subtotal );

            // for fixed fee
            $fixed_fee = (float)apply_filters( 'esb_calc_fixed_fee', $fixed_fee, $author_id, $order_id, $subtotal );

            $earning = $subtotal - $fee_amount - $fixed_fee;

            $earning = (float)apply_filters('esb_booking_author_earning', $earning, $order_id, $listing_id, $author_id);

            $stats_meta = maybe_serialize( array(
                'fee_type'          => 'percent',
                'subtotal'          => $subtotal,
                'vat_tax'           => $vat_tax,
                'fees'              => $fees,
                'fixed_fee'         => $fixed_fee,
            ) );
            // $stats_meta = maybe_serialize( array( array( 'time' => $time, 'ip' => $ip, 'user_id' => $user_id ) ) );
            $inserted = $wpdb->insert( 
                $tb_name, 
                array( 
                    'author_id'                 => $author_id,
                    'order_id'                  => $order_id, 
                    'child_post_id'             => 0, 
                    'type'                      => $type, 
                    'total'                     => $order_total, 
                    'fee_rate'                  => $author_fee, 
                    'fee'                       => $subtotal - $earning, 
                    'earning'                   => $earning, 
                    'meta'                      => $stats_meta, 
                    'year'                      => $year, 
                    'month'                     => $month, 
                    'date'                      => $date, 
                    'time'                      => $time, 
                    'status'                    => 1,
                ) 
            );
            if($inserted !== false) return $earning;
        }
        return false;
    }

    public static function insert_refund($order_id = 0, $author_id = 0, $listing_id = 0, $woo_product = false){
        global $wpdb;
        $tb_name = $wpdb->prefix . 'cth_austats';
        $year = date_i18n('Y');
        $month = date_i18n('m');
        $date = date_i18n('Y-m-d');
        $time = date_i18n('U');

        $type = 'author_refund';

        if($order_id && $author_id){
            $author_fee = (float)get_user_meta($author_id, ESB_META_PREFIX.'author_fee', true);
            // if(empty($author_fee)) $author_fee = apply_filters('esb_author_fee_default', 10);

            $fixed_fee = (float)get_user_meta($author_id, ESB_META_PREFIX.'fixed_fee', true);

            if( $woo_product ){
                $order_total = $subtotal = (float)get_post_meta( $order_id, '_price', true );
                $vat_tax = 0;
                $fees = 0;
            }else{
                $order_total = (float)get_post_meta( $order_id, ESB_META_PREFIX.'price_total', true );
                $subtotal = (float)get_post_meta( $order_id, ESB_META_PREFIX.'subtotal', true );
                $vat_tax = (float)get_post_meta( $order_id, ESB_META_PREFIX.'subtotal_vat', true );
                $fees = (float)get_post_meta( $order_id, ESB_META_PREFIX.'subtotal_fee', true );
            }
            // add ability calc author commission based on order total;
            $author_fee = (float)apply_filters( 'esb_calc_author_fee', $author_fee, $author_id, $order_id, $subtotal );
            $fee_amount = ($subtotal * $author_fee)/100;
            // add ability calc author commission based on order total;
            $fee_amount = (float)apply_filters( 'esb_calc_author_fee_amount', $fee_amount, $author_id, $order_id, $subtotal );

            // for fixed fee
            $fixed_fee = (float)apply_filters( 'esb_calc_fixed_fee', $fixed_fee, $author_id, $order_id, $subtotal );

            $earning = $subtotal - $fee_amount - $fixed_fee;

            $earning = (float)apply_filters('esb_booking_author_earning', $earning, $order_id, $listing_id, $author_id);

            $stats_meta = maybe_serialize( array(
                'fee_type'          => 'percent',
                'subtotal'          => $subtotal,
                'vat_tax'           => $vat_tax,
                'fees'              => $fees,
                'fixed_fee'         => $fixed_fee,
            ) );
            // $stats_meta = maybe_serialize( array( array( 'time' => $time, 'ip' => $ip, 'user_id' => $user_id ) ) );
            $inserted = $wpdb->insert( 
                $tb_name, 
                array( 
                    'author_id'                 => $author_id,
                    'order_id'                  => $order_id, 
                    'child_post_id'             => 0, 
                    'type'                      => $type, 
                    'total'                     => $order_total, 
                    'fee_rate'                  => $author_fee, 
                    'fee'                       => -($subtotal - $earning), 
                    'earning'                   => -$earning, 
                    'meta'                      => $stats_meta, 
                    'year'                      => $year, 
                    'month'                     => $month, 
                    'date'                      => $date, 
                    'time'                      => $time, 
                    'status'                    => 1,
                ) 
            );
            if($inserted !== false){

                update_post_meta( $order_id, ESB_META_PREFIX.'refunded',  $earning  );
                return $earning;
            } 
        }
        return false;
    }
    public static function insert_partial_refund($order_id = 0, $author_id = 0, $listing_id = 0, $amount = 0, $woo_product = false){
        global $wpdb;
        $tb_name = $wpdb->prefix . 'cth_austats';
        $year = date_i18n('Y');
        $month = date_i18n('m');
        $date = date_i18n('Y-m-d');
        $time = date_i18n('U');

        $type = 'author_refund';

        if($order_id && $author_id){
            $author_fee = (float)get_user_meta($author_id, ESB_META_PREFIX.'author_fee', true);
            // if(empty($author_fee)) $author_fee = apply_filters('esb_author_fee_default', 10);

            $fixed_fee = (float)get_user_meta($author_id, ESB_META_PREFIX.'fixed_fee', true);

            if( $woo_product ){
                $order_total = $subtotal = $amount;
                $vat_tax = 0;
                $fees = 0;
            }else{
                $order_total = $amount;
                $subtotal = $amount;
                $vat_tax = 0;
                $fees = 0;
            }
            // add ability calc author commission based on order total;
            $author_fee = (float)apply_filters( 'esb_partial_refund_calc_author_fee', $author_fee, $author_id, $order_id, $subtotal );
            $fee_amount = ($subtotal * $author_fee)/100;
            // add ability calc author commission based on order total;
            $fee_amount = (float)apply_filters( 'esb_partial_refund_calc_author_fee_amount', $fee_amount, $author_id, $order_id, $subtotal );

            // for fixed fee
            $fixed_fee = (float)apply_filters( 'esb_partial_refund_calc_fixed_fee', $fixed_fee, $author_id, $order_id, $subtotal );
            if( 1 == 2 ) $fee_amount = 0; // 
            $fixed_fee = 0;
            $earning = $subtotal - $fee_amount - $fixed_fee; // deduct all from author earning

            $earning = (float)apply_filters('esb_partial_refund_booking_author_earning', $earning, $order_id, $listing_id, $author_id);

            $stats_meta = maybe_serialize( array(
                'fee_type'          => 'percent',
                'subtotal'          => $subtotal,
                'vat_tax'           => $vat_tax,
                'fees'              => $fees,
                'fixed_fee'         => $fixed_fee,
            ) );
            // $stats_meta = maybe_serialize( array( array( 'time' => $time, 'ip' => $ip, 'user_id' => $user_id ) ) );
            $inserted = $wpdb->insert( 
                $tb_name, 
                array( 
                    'author_id'                 => $author_id,
                    'order_id'                  => $order_id, 
                    'child_post_id'             => 0, 
                    'type'                      => $type, 
                    'total'                     => $order_total, 
                    'fee_rate'                  => $author_fee, 
                    'fee'                       => -($subtotal - $earning), 
                    'earning'                   => -$earning, 
                    'meta'                      => $stats_meta, 
                    'year'                      => $year, 
                    'month'                     => $month, 
                    'date'                      => $date, 
                    'time'                      => $time, 
                    'status'                    => 1,
                ) 
            );
            if($inserted !== false){

                update_post_meta( $order_id, ESB_META_PREFIX.'refunded',  $earning  );
                return $earning;
            } 
        }
        return false;
    }
    public static function insert_withdrawal($withdrawal_id = 0){
        global $wpdb;
        $tb_name = $wpdb->prefix . 'cth_austats';
        $year = date_i18n('Y');
        $month = date_i18n('m');
        $date = date_i18n('Y-m-d');
        $time = date_i18n('U');

        $type = 'author_withdrawal';

        if( $withdrawal_id ){
            $author_fee = 0;
            

            $order_total = get_post_meta( $withdrawal_id, ESB_META_PREFIX.'amount', true );
            $author_id = get_post_meta( $withdrawal_id, ESB_META_PREFIX.'user_id', true );
            
            $stats_meta = maybe_serialize( array(
                'fee_type'          => 'percent',
                'subtotal'          => $order_total,
                'vat_tax'           => 0,
                'fees'              => 0,
                'fixed_fee'         => 0,
            ) );
            // $stats_meta = maybe_serialize( array( array( 'time' => $time, 'ip' => $ip, 'user_id' => $user_id ) ) );
            $inserted = $wpdb->insert( 
                $tb_name, 
                array( 
                    'author_id'                 => $author_id,
                    'order_id'                  => $withdrawal_id, 
                    'child_post_id'             => 0, 
                    'type'                      => $type, 
                    'total'                     => $order_total, 
                    'fee_rate'                  => $author_fee, 
                    'fee'                       => $author_fee, 
                    'earning'                   => -$order_total, 
                    'meta'                      => $stats_meta, 
                    'year'                      => $year, 
                    'month'                     => $month, 
                    'date'                      => $date, 
                    'time'                      => $time, 
                    'status'                    => 0,
                ) 
            );

            if($inserted !== false) return $order_total;
        }
        return false;
    }

    public static function getEarningsPosts($user_id = 0, $request = array()){
        global $wpdb;
        $tb_name = $wpdb->prefix . 'cth_austats';
        // $type = 'author_earning';
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
        $limit_sql = '';
        // $found_rows = '';

        if(isset($request['paged']) && $request['paged'] != '' && is_numeric($request['paged'])){
            $paged = intval($request['paged']);
        }
        $posts_per_page = townhub_addons_dashboard_posts_per_page();
        if($posts_per_page > 0){
            $limit_sql = $wpdb->prepare( "LIMIT %d", $posts_per_page);
            if($paged > 1){
                $offset = ( $paged - 1 ) * $posts_per_page;
                $limit_sql = $wpdb->prepare( "LIMIT %d OFFSET %d", $posts_per_page, $offset);
            }
            // $found_rows = 'SQL_CALC_FOUND_ROWS';
        }
        
        $author_earnings = $wpdb->get_results( 
            $wpdb->prepare( 
                "
                SELECT * FROM $tb_name 
                WHERE author_id = %d AND ( type = %s OR type = %s ) AND status = 1 ORDER BY time DESC $limit_sql
                ",
                $user_id,
                'author_earning',
                'author_refund'
            )
        );

        // $json['wpdb'] = $wpdb;
        
        if(!empty($author_earnings)){
            foreach ($author_earnings as $earning) {
                $earning_data = array(
                    // 'ID'                    => $earning->ID,
                    'order_id'              => $earning->order_id,
                    // 'order_data'            => sprintf( __( '# %d', 'townhub-add-ons' ), $earning->order_id ),
                    'total'                 => townhub_addons_get_price_formated($earning->total),
                    'fee_rate'              => sprintf( __( '%d %% - ', 'townhub-add-ons' ), number_format( (float)$earning->fee_rate, 2 ) ),
                    'fee'                   => townhub_addons_get_price_formated($earning->fee),
                    'earning'               => townhub_addons_get_price_formated($earning->earning),
                    // 'date'                  => $earning->date,
                    'time'                  => date_i18n( get_option('date_format'), $earning->time ) ,
                    'meta'                  => maybe_unserialize( $earning->meta ),
                );
                $vatSer = 0;
                $eMetas = maybe_unserialize( $earning->meta );
                if( is_array($eMetas) && !empty($eMetas) ){
                    if( isset($eMetas['vat_tax']) ) $vatSer += $eMetas['vat_tax'];
                    if( isset($eMetas['fees']) ) $vatSer += $eMetas['fees'];

                    if( !empty($eMetas['fixed_fee']) ){
                        $earning_data['fee_rate'] = sprintf( _x( '%s + %d %% - ', 'Author fixed and percent fee', 'townhub-add-ons' ), townhub_addons_get_price_formated( $eMetas['fixed_fee'] ) , number_format( (float)$earning->fee_rate, 2 ) );
                    }
                }
                $earning_data['vatSer'] = townhub_addons_get_price_formated( $vatSer );

                if( (float)$earning->earning < 0 ){
                    $earning_data['order_data'] = '<div class="earn-order-data">'.sprintf( _x( 'Refund for %1$s <br># %2$d', 'Author Earnings', 'townhub-add-ons' ), get_the_title($earning->order_id), $earning->order_id ).'</div>';
                }else{
                    $earning_data['order_data'] = '<div class="earn-order-data">'.sprintf( _x( '%1$s <br># %2$d', 'Author Earnings', 'townhub-add-ons' ), get_the_title($earning->order_id), $earning->order_id ).'</div>';
                }
                
                $earning_data = apply_filters( 'esb_earning_data', $earning_data, $earning->order_id, $earning );

                $json['posts'][] = (object) $earning_data;
            }
            
            // $found_posts = $wpdb->get_var( 'SELECT FOUND_ROWS()' );
            $found_posts = $wpdb->get_var( 
                $wpdb->prepare( 
                    "
                    SELECT COUNT(*) FROM $tb_name 
                    WHERE author_id = %d AND ( type = %s OR type = %s ) AND status = 1 ORDER BY time DESC
                    ",
                    $user_id,
                    'author_earning',
                    'author_refund'
                )
                //"SELECT COUNT(*) FROM {$wpdb->posts} $join WHERE 1=1 $where" 
            );
            // $json['found_posts'] = $found_posts;
            $json['pagi']['found_posts'] = $found_posts;
            $json['pagi']['paged'] = $paged;
            $json['pagi']['pages'] = ceil( $found_posts / $posts_per_page );

        }

        return $json;
    }

    public static function update($order_id = 0, $type = 'author_withdrawal'){
        global $wpdb;
        $tb_name = $wpdb->prefix . 'cth_austats';
        $wpdb->update( 
            $tb_name, 
            array( 
                'status' => 1,  
            ), 
            array( 
                'order_id' => $order_id,  
                'type' => $type,
            ), 
            array( 
                '%d',
            ), 
            array( 
                '%d', 
                '%s', 
            ) 
        );
    }

    public static function get_datas($author_id = 0, $data_period = 'week', $add_param = '', $type = 'author_earning'){
        global $wpdb;
        $tb_name = $wpdb->prefix . 'cth_austats';
        if($author_id && (int)$author_id > 0){
            
            switch ($data_period) {
                case 'alltime':
                    $add_query = " GROUP BY year ORDER BY year ASC";
                    $add_params = array();
                    break;
                case 'year':
                    $add_query = "AND year = %s GROUP BY month ORDER BY month ASC LIMIT %d";
                    $add_params = array($add_param , 12);
                    break;
                case 'month':
                    $mparam = substr($add_param, -2);
                    $yparam = substr($add_param, 0, 4);
                    $add_query = "AND month = %s AND year = %s GROUP BY date ORDER BY date ASC LIMIT %d";
                    $add_params = array($mparam, $yparam, 31);
                    break;
                default:
                    $add_query = "AND date >= %s GROUP BY date ORDER BY date ASC LIMIT %d";
                    $add_params = array($add_param, 7);
                    break;
            }

            $main_query = "SELECT SUM(earning) AS sum, year, month, date FROM $tb_name WHERE author_id = %d AND (type = %s OR type = %s) $add_query";

            $list_stats = $wpdb->get_results( $wpdb->prepare( $main_query, array_merge( array($author_id, $type, 'author_refund'), $add_params ) ), ARRAY_A );

            // var_dump($list_stats);

            return $list_stats;
        }
        return array();
    }

    public static function reset_stats($user_id = 0){
        if( !empty($user_id) ){
            global $wpdb;
            $tb_name = $wpdb->prefix . 'cth_austats';
            $wpdb->query( 
                $wpdb->prepare( 
                    "
                    DELETE FROM $tb_name
                    WHERE author_id = %d
                    ",
                    $user_id
                )
            );
        } 
    }

    // before delete booking and room post
    public static function before_delete_post($postid = 0){
        global $wpdb;
        $post_type = get_post_type($postid);
        $tb_name = $wpdb->prefix . 'cth_austats';
        if($post_type === 'lbooking' ){
            $wpdb->query( 
                $wpdb->prepare( 
                    "
                    DELETE FROM $tb_name
                    WHERE (type = %s OR type = %s) AND (order_id = %d OR child_post_id = %d)
                    ",
                    'author_earning',
                    'author_refund',
                    $postid,
                    $postid
                )
            );
        }elseif($post_type === 'lwithdrawal' ){
            $wpdb->query( 
                $wpdb->prepare( 
                    "
                    DELETE FROM $tb_name
                    WHERE type = %s AND (order_id = %d OR child_post_id = %d)
                    ",
                    'author_withdrawal',
                    $postid,
                    $postid
                )
            );
        }
    }
    public static function total_sales( $user_id ){
        global $wpdb;
        $tb_name = $wpdb->prefix . 'cth_austats';
        // https://www.codeproject.com/Questions/709668/how-to-subtract-value-from-same-column-same-table
        $total_sales = $wpdb->get_var( 
            $wpdb->prepare( 
                "
                SELECT SUM(EARNING) as EARNING, SUM(REFUND) as REFUND, 'DIFFERENCE' = SUM(EARNING)-SUM(REFUND) FROM ( 
                    SELECT total AS EARNING, 0 AS REFUND FROM $tb_name WHERE author_id = %d AND type = %s AND status = 1 
                    UNION ALL
                    SELECT 0 AS EARNING, total AS REFUND FROM $tb_name WHERE author_id = %d AND type = %s AND status = 1
                ) AS SUMTABLE
                ",
                $user_id,
                'author_earning',
                $user_id,
                'author_refund'
            )
        );

        if( $total_sales ){
            return $total_sales;
        }

        return 0;
    }
    public static function withdrawals_count( $user_id ){
        global $wpdb;
        $tb_name = $wpdb->prefix . 'cth_austats';
        $found_posts = $wpdb->get_var( 
            $wpdb->prepare( 
                "
                SELECT COUNT(*) FROM $tb_name 
                WHERE author_id = %d AND type = %s
                ",
                $user_id,
                'author_withdrawal'
            )
            //"SELECT COUNT(*) FROM {$wpdb->posts} $join WHERE 1=1 $where" 
        );
        if( $found_posts ){
            return $found_posts;
        }
        return 0;
    }
    public static function withdrawals_total( $user_id ){
        global $wpdb;
        $tb_name = $wpdb->prefix . 'cth_austats';
        $result = $wpdb->get_var( 
            $wpdb->prepare( 
                "
                SELECT SUM(earning) FROM $tb_name 
                WHERE author_id = %d AND type = %s AND status IN (0,1)
                ",
                $user_id,
                'author_withdrawal'
            )
        );
        if( $result ){
            return $result;
        }
        return 0;
    }
    public static function commissions_paid( $user_id ){
        global $wpdb;
        $tb_name = $wpdb->prefix . 'cth_austats';
        $commissions_paid = $wpdb->get_var( 
            $wpdb->prepare( 
                "
                SELECT SUM(fee) FROM $tb_name 
                WHERE author_id = %d AND ( type = %s OR type = %s ) AND status = 1 ORDER BY time DESC
                ",
                $user_id,
                'author_earning',
                'author_refund'
            )
        );

        if( $commissions_paid ){
            return $commissions_paid;
        }

        return 0;
    }
        

}
Esb_Class_Earning::init();


