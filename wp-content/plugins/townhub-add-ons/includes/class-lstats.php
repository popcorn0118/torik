<?php 
/* add_ons_php */

defined( 'ABSPATH' ) || exit;

class Esb_Class_LStats{

    public static function init(){
        add_action( 'before_delete_post', array( __CLASS__, 'before_delete_post' ), 10, 1 );
    }

    public static function set_stats($post_id = 0, $type = 'listing_view', $post_type = 'listing'){
        global $wpdb;
        $tb_name = $wpdb->prefix . 'cth_lstats';

        
        $year = date_i18n('Y');
        $month = date_i18n('m');
        $date = date_i18n('Y-m-d');
        $time = date_i18n('U');
        $ip = $_SERVER['REMOTE_ADDR'];
        $user_id = get_current_user_id();

        $exsiting = $wpdb->get_row( $wpdb->prepare( 
            "
                SELECT * 
                FROM $tb_name 
                WHERE post_id = %d AND type = %s AND date = %s
            ", 
            $post_id,
            $type,
            $date
        ) );

       

        if($exsiting !== NULL){
            // update
            // $stats_meta = maybe_unserialize( $exsiting->meta );
            // if(is_array($stats_meta) && !empty($stats_meta)){
            //     $stats_meta[] = array( 'time' => $time, 'ip' => $ip, 'user_id' => $user_id );
            // }else{
            //     $stats_meta =  array( array( 'time' => $time, 'ip' => $ip, 'user_id' => $user_id ) );
            // }

           

            
            $wpdb->update( 
                $tb_name, 
                array( 
                    'value' => ($exsiting->value + 1),  
                    'time' => $time,  
                    'ip' => $ip,  
                    'guest_id' => $user_id,  
                    // 'meta'      => maybe_serialize( $stats_meta ),
                ), 
                array( 
                    'post_id' => $post_id,  
                    'type' => $type,
                    'date' => $date,
                ), 
                array( 
                    '%d',
                    '%d',
                    '%s',
                    '%d',
                    // '%s',
                ), 
                array( 
                    '%d', 
                    '%s', 
                    '%s'
                ) 
            );
        }else{
            // insert new
            $stats_meta = '';
            // $stats_meta = maybe_serialize( array( array( 'time' => $time, 'ip' => $ip, 'user_id' => $user_id ) ) );
            $wpdb->insert( 
                $tb_name, 
                array( 
                    'post_id'           => $post_id, 
                    'child_post_id'         => 0, 
                    'type'            => $type, 
                    'value'           => 1, 
                    'meta'            => $stats_meta, 
                    'year'            => $year, 
                    'month'            => $month, 
                    'date'            => $date, 
                    'time'            => $time, 
                    'ip'              => $ip, 
                    'guest_id'              => $user_id, 
                ) 
            );
        }


        if( $type == 'listing_view' ){
            $meta_value = $wpdb->get_var( 
                $wpdb->prepare( 
                    "
                    SELECT sum(lstats.value) FROM $tb_name AS lstats
                    WHERE lstats.post_id = %d AND lstats.type = %s
                    ",
                    $post_id,
                    $type
                )
            );
            update_post_meta( $post_id, ESB_META_PREFIX.'post_views_count', $meta_value );
        } 

    }

    public static function get_stats($post_id = 0, $type = 'listing_view', $post_type = 'listing'){
        global $wpdb;
        $tb_name = $wpdb->prefix . 'cth_lstats';
        return (float)$wpdb->get_var( 
            $wpdb->prepare( 
                "
                SELECT sum(lstats.value) FROM $tb_name AS lstats
                WHERE lstats.post_id = %d AND lstats.type = %s
                ",
                $post_id,
                $type
            )
        );

        // var_dump($return);
    }

    public static function get_datas($posts = array(), $data_period = 'week', $add_param = '', $type = 'listing_view'){
        global $wpdb;
        $tb_name = $wpdb->prefix . 'cth_lstats';
        if(is_array($posts) && !empty($posts)){
            // Count the number of posts
            $postsCount = count($posts);
            // Prepare the right amount of placeholders, in an array
            // For strings, you would use, ‘%s’
            $postsPlaceholders = array_fill(0, $postsCount, '%d');
            // Put all the placeholders in one string ‘%s, %s, %s, %s, %s,…’
            $placeholdersForPosts = implode(', ', $postsPlaceholders);

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

            $main_query = "SELECT SUM(value) AS sum, year, month, date FROM $tb_name WHERE post_id IN ($placeholdersForPosts) $add_query";

            $list_stats = $wpdb->get_results( $wpdb->prepare( $main_query, array_merge( $posts, $add_params ) ), ARRAY_A );

            // var_dump($list_stats);

            return $list_stats;
        }
        return array();
    }

    private static function escape_array($arr){
        global $wpdb;
        $escaped = array();
        foreach((array)$arr as $k => $v){
            if(is_numeric($v))
                $escaped[] = $wpdb->prepare('%d', $v);
            else
                $escaped[] = $wpdb->prepare('%s', $v);
        }
        return implode(',', $escaped);
    }

    public static function reset_stats($postid = 0){
        global $wpdb;
        if( !empty($postid) ){
            $tb_name = $wpdb->prefix . 'cth_lstats';
            $wpdb->query( 
                $wpdb->prepare( 
                    "
                    DELETE FROM $tb_name
                    WHERE post_id = %d OR child_post_id = %d
                    ",
                    $postid,
                    $postid
                )
            );
        }
            
    }


    // before delete booking and room post
    public static function before_delete_post($postid = 0){
        $post_type = get_post_type($postid);
        if($post_type === 'listing' ){
            self::reset_stats($postid);
        }
    }


}

Esb_Class_LStats::init();

if(!function_exists('townhub_addons_get_post_views')){
    function townhub_addons_get_post_views($postID){
        $count_key = '_cth_post_views_count';
        $count = get_post_meta($postID, $count_key, true);
        if($count==''){
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
            return "0";
        }
        return $count;
    }
}

if(!function_exists('townhub_addons_set_post_views')){
    function townhub_addons_set_post_views($postID) {
        $count_key = '_cth_post_views_count';
        $count = get_post_meta($postID, $count_key, true);
        if($count==''){
            $count = 0;
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
        }else{
            $count++;
            update_post_meta($postID, $count_key, $count);
        }
    }
}
