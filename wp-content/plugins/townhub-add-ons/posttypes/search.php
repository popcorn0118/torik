<?php
/* add_ons_php */


// for ajax search

add_action('wp_ajax_nopriv_townhub_addons_ajax_search', 'townhub_addons_ajax_search_callback');
add_action('wp_ajax_townhub_addons_ajax_search', 'townhub_addons_ajax_search_callback');

function townhub_addons_ajax_search_callback() { 
    // global $wp_query;
    $json = array(
        'success' => true,
        'data' => array(
            // 'POST'=>$_POST,
        ),
        // '_POST'=>$_POST,
        'debug'     => false
        
    );
    // wp_send_json($json );

    // $nonce = $_POST['_nonce'];
    
    // if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
    //     $json['success'] = false;
    //     $json['data']['error'] = esc_html__( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ;

    //     // die ( '<p class="error">Security checked!, Cheatn huh?</p>' );

    //     wp_send_json($json );
    // }

    // track for ajax request
    $json['data']['ajax_count']     = $_POST['ajax_count'];
    $post_args = array();


    $tax_queries = array();
    $merge_lcats = array();
    if(isset($_POST['lcats'])) $merge_lcats = array_merge( $merge_lcats, array_filter($_POST['lcats']) );
    if(isset($_POST['filter_subcats']) && !empty($_POST['filter_subcats'])) $merge_lcats =  array_filter($_POST['filter_subcats']);
    // if( isset($_POST['lcats']) && !empty( array_filter($_POST['lcats']) ) ){
    if( !empty( $merge_lcats ) ){
        $wpmlCats = [];
        foreach ($merge_lcats as $catID) {
            $wpmlCats[] = $catID;
            $wpmlCats[] = apply_filters( 'wpml_object_id', $catID, 'listing_cat', true, townhub_addons_get_default_language() );
        }
        $tax_queries[] =    array(
                                'taxonomy' => 'listing_cat',
                                'field'    => 'term_id',
                                'terms'    => $wpmlCats,
                                // 'include_children'  => false, // default true
                                // 'operator' => 'AND', // default IN
                            );
    }
    $filtered_cat = 0;
    if( is_tax('listing_cat') ){
        $filtered_cat = get_queried_object_id();
    }elseif( !empty($merge_lcats) ){
        $filtered_cat = reset($merge_lcats);
    }
    if( isset($_POST['lfeas']) && !empty( array_filter($_POST['lfeas']) ) ){
        $tax_queries[] =    array(
                                'taxonomy' => 'listing_feature',
                                'field'    => 'term_id',
                                'terms'    => $_POST['lfeas'],
                                'operator' => 'AND', // default IN
                            );
    }
    if( isset($_POST['llocs']) && !empty($_POST['llocs'] ) ){
        $tax_queries[] =    array(
                                'taxonomy' => 'listing_location',
                                'field'    => 'slug',
                                // 'terms'    => sanitize_title($_POST['llocs']),
                                'terms'    => $_POST['llocs'],
                            );
    }
    if( isset($_POST['ltags']) && !empty( array_filter($_POST['ltags']) ) ){
        $tax_queries[] =    array(
                                'taxonomy' => 'post_tag',
                                'field'    => 'term_id',
                                'terms'    => $_POST['ltags'],
                                'operator' => 'AND', // default IN
                            );
    }

    if( isset($_POST['listing_tags']) && !empty( $_POST['listing_tags'] ) ){
        $tax_queries[] =    array(
                                'taxonomy' => 'listing_tag',
                                'field'    => 'term_id',
                                'terms'    => $_POST['listing_tags'],
                                'operator' => 'AND', // default IN
                            );
    }

    

    if(!empty($tax_queries)){
        if( count($tax_queries) > 1 ) $tax_queries['relation'] = townhub_addons_get_option('search_tax_relation');
        $post_args['tax_query'] = $tax_queries;
    } 

    if( isset($_POST['checkin']) && $_POST['checkin'] != '' ){
        $checkin_arg = $_POST['checkin'];
        if( is_array($_POST['checkin']) ) $checkin_arg = reset($_POST['checkin']);
        $post__in_sum = array();

        if( isset($_POST['checkout']) && $_POST['checkout'] != '' ){
            $checkout_arg = $_POST['checkout'];
            if( is_array($_POST['checkout']) ) $checkout_arg = reset($_POST['checkout']);
            $avai_check_args = array(
                'checkin'   => $checkin_arg,
                'checkout'   => $checkout_arg,
                'listing_id'   => 0,
            );
            $listing_availables = townhub_addons_get_available_listings($avai_check_args);
            if(is_array($listing_availables) && !empty($listing_availables)){
                $post__in = array();
                foreach ($listing_availables as $avai) {
                    if( isset($avai->id) && (int)$avai->id > 0){

                        if(isset($_POST['no_rooms']) && (int)$_POST['no_rooms'] > 1){
                            $avai_check_args['listing_id'] = $avai->id;
                            // check quantity
                            $double_check = townhub_addons_get_available_listings($avai_check_args);
                            if(!empty($double_check)){
                                $room_quans = array_map(function($room){
                                    return ((int)$room->quantities > 0) ? (int)$room->quantities : 0;
                                },$double_check);
                                $room_quans = array_filter($room_quans);
                                if(array_sum($room_quans) >= $_POST['no_rooms']) $post__in[] = $avai->id;
                            }
                        }else{
                            $post__in[] = $avai->id;
                        }
                    }
                }
                $post__in_sum = array_merge($post__in_sum, $post__in);
            }
            // }else{
            //     // do not return any listing if has no rooms
            //     if( townhub_addons_get_option('inout_rooms_only', 'yes') == 'yes' ){
            //         $post_args['s'] = 'donotreturnanylistingifnoroomavailableABCDEFGHIJKLMNOPQRSTUVWXYZ';
            //     }else{
            //         $post__in_sum = array_merge($post__in_sum, townhub_addons_listing_available_date( $checkin_arg ) );
            //     }
            // } 
            if( townhub_addons_get_option('inout_rooms_only', 'yes') == 'yes' ){
                if( empty($post__in_sum) ) $post_args['s'] = 'donotreturnanylistingifnoroomavailableABCDEFGHIJKLMNOPQRSTUVWXYZ';
            }else{
                $post__in_sum = array_merge($post__in_sum, townhub_addons_listing_available_date( $checkin_arg ) );
            }
        }else{
            $post__in_sum = array_merge($post__in_sum, townhub_addons_listing_available_date( $checkin_arg ) );
        }
        $post__in_sum = array_filter($post__in_sum);
        if(!empty($post__in_sum)){
            $post_args['post__in'] = $post__in_sum;
        }else{
            $post_args['s'] = 'donotreturnanylistingifnoroomavailableABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
    }


    // custom field query
    $meta_queries = array();

    if( isset($_POST['ltype']) && !empty($_POST['ltype'] ) && townhub_addons_get_option('use_ltype_filter') == 'yes' ){
        // for cat listing types filter
        if( !empty($filtered_cat) ){
            $cat_ltypes = townhub_addons_custom_tax_ltypes($filtered_cat, 'listing_cat');
        }
        if( isset($cat_ltypes) && !empty($cat_ltypes) ){
            $meta_queries[] =    array(
                                'key'       => ESB_META_PREFIX.'listing_type_id',
                                'value'     => $cat_ltypes,
                                'type'      => 'NUMERIC',
                                'compare'   => 'IN',
                            );
        }else{
            $meta_queries[] =    array(
                                        'key' => ESB_META_PREFIX.'listing_type_id',
                                        'value'    => intval($_POST['ltype']), // (int)apply_filters( 'wpml_object_id', $_POST['ltype'], 'listing_type', true ), // 
                                        'type'      => 'NUMERIC'
                                    );
        }
                
        
            
    }

    if( isset($_POST['rating']) && !empty($_POST['rating'] ) ){
        $meta_queries[] =    array(
                                    'key'           => ESB_META_PREFIX.'rating_average',
                                    'value'         => $_POST['rating'],
                                    'compare'       => '>='
                                );
    }

    // price_range filter
    
    if( isset($_POST['fprice']) && !empty($_POST['fprice'] ) ){
        if(strpos($_POST['fprice'], ";") !== false){
            $range = explode(";", $_POST['fprice']);
            $range = array_map(function($val){
                return townhub_addons_parse_price($val);
            }, $range);
            if(count($range) == 2){

                $meta_queries[] = array(
                    'key'     => '_price',
                    'value'   => $range,
                    'type'    => 'numeric',
                    'compare' => 'BETWEEN',
                );
            }
        }
            
    }

    if( isset($_POST['price_range']) && !empty($_POST['price_range'] ) ){
        $meta_queries[] =    array(
                                        'key' => ESB_META_PREFIX.'price_range',
                                        'value'    => $_POST['price_range'],
                                    );
            
    }

    // query by date
    
    // if( isset($_POST['event_date']) && !empty($_POST['event_date'] ) ){
    //     // for changing event date
    //     $event_date_mysql = date('Y-m-d', strtotime($_POST['event_date']));
    //     $meta_queries[] =    array(
    //                                     'key'       => ESB_META_PREFIX.'levent_date',
    //                                     'value'     => $event_date_mysql,
    //                                     'compare'   => '>=',
    //                                     'type'      => 'DATE'
    //                                 );
    // }
    if( isset($_POST['event_time']) && !empty($_POST['event_time'] ) ){
        $meta_queries[] =    array(
                                        'key'       => ESB_META_PREFIX.'levent_time',
                                        'value'     => $_POST['event_time'],
                                        'compare'   => '>=',
                                        'type'      => 'TIME'
                                    );
    }


    if( townhub_addons_get_option('hide_past_events') == 'yes' ){

        $meta_queries[] =   array(
                                        'relation'      => 'OR',
                                        array(
                                            'key'       => ESB_META_PREFIX.'eventdate_end',
                                            'value'     => 'none',
                                            'compare'   => '=',
                                        ),
                                        array(
                                            'key'       => ESB_META_PREFIX.'eventdate_end',
                                            'value'     => current_time('Y-m-d', 1),
                                            'compare'   => '>=',
                                            'type'      => 'DATE',
                                        ),
                                    );

        // $meta_queries[] =   array(
        //                                     'key'       => ESB_META_PREFIX.'eventdate_start',
        //                                     'value'     => current_time('Y-m-d', 1),
        //                                     'compare'   => '>=',
        //                                     'type'      => 'DATE',
        //                                 );
    }

    if( empty($_POST['checkout']) ){
        $sPers = 0;
        if( isset($_POST['adults']) ){
            $sPers += intval( $_POST['adults'] );
        }
        if( isset($_POST['children']) ){
            $sPers += intval( $_POST['children'] );
        }
        if( isset($_POST['infants']) ){
            $sPers += intval( $_POST['infants'] );
        }
        if( $sPers > 0 ){
            $meta_queries[] =    array(
                                            'key'       => ESB_META_PREFIX.'max_guests',
                                            'value'     => $sPers,
                                            'compare'   => '>=',
                                            'type'      => 'NUMERIC'
                                        );
        }
    }

        
    $meta_queries = (array)apply_filters( 'cth_listing_additional_meta_queries', $meta_queries );

    if(!empty($meta_queries)){
        if(count($meta_queries)> 1) $meta_queries['relation'] = 'AND';
        $post_args['meta_query'] = $meta_queries;
    } 


    // add_filter( 'townhub_addons_ajax_search_args', function($post_args){
    //     if(isset($_POST['max_guests']) && !empty($_POST['max_guests'])){
    //         if(!isset($post_args['meta_query'])){
    //             $post_args['meta_query'] = array(
    //                 'key'       => ESB_META_PREFIX.'max_guests',
    //                 'value'     => $_POST['max_guests'],
    //                 'compare'   => '>=',
    //                 'type'      => 'numeric'
    //             );
    //         }else{
    //             $post_args['meta_query'][] = array(
    //                 'key'       => ESB_META_PREFIX.'max_guests',
    //                 'value'     => $_POST['max_guests'],
    //                 'compare'   => '>=',
    //                 'type'      => 'numeric'
    //             );
    //         }
    //     }
    //     return $post_args;
    // } );

    
    $post_args['post_type'] = 'listing';
    $post_args['post_status'] = 'publish';
    $post_args['posts_per_page'] = townhub_addons_get_option('listings_count');
    $post_args['orderby'] = townhub_addons_get_option('listings_orderby');
    $post_args['order'] = townhub_addons_get_option('listings_order');

    if( isset($_POST['lposts_per_page']) ) $post_args['posts_per_page'] = $_POST['lposts_per_page'];
    if( isset($_POST['lorderby']) ) $post_args['orderby'] = $_POST['lorderby'];
    if( isset($_POST['lorder']) ) $post_args['order'] = $_POST['lorder'];
    
    if( ( isset($_POST['lorderby']) && $_POST['lorderby'] == 'listing_featured' ) || townhub_addons_get_option('listings_orderby') == 'listing_featured'){
        $post_args['meta_key'] = ESB_META_PREFIX.'featured';
        // $post_args['orderby'] = 'meta_value';
        $post_args['orderby'] = 'meta_value_num date';
        $post_args['order'] = 'DESC';
        // https://wordpress.stackexchange.com/questions/45413/using-orderby-and-meta-value-num-to-order-numbers-first-then-strings
    }

    if(townhub_addons_get_option('listings_orderby') == 'event_start_date'){
        $post_args['meta_key'] = ESB_META_PREFIX.'eventdate_start'; // levent_date
        // $post_args['meta_type'] = 'DATE';
        // $post_args['orderby'] = 'meta_value_date';
        $post_args['orderby'] = 'meta_value';
        // $post_args['order'] = 'ASC';
    }


    $post_args['suppress_filters'] = false; // for additional wpdb query
    $post_args['cthqueryid'] = 'ajax-search';
    $post_args['paged'] = 1;
    if( isset($_POST['paged']) && is_numeric($_POST['paged']) ) $post_args['paged'] = $_POST['paged'];

    // meta prder
    if( isset($_POST['morderby']) && !empty($_POST['morderby'] ) ){
        switch ($_POST['morderby']) {
            case 'most_reviewed':
                $post_args['orderby'] = 'comment_count';
                $post_args['order'] = 'DESC';
                break;
            case 'most_viewed':
                $post_args['meta_key'] = ESB_META_PREFIX.'post_views_count';
                $post_args['orderby'] = 'meta_value meta_value_num';
                $post_args['order'] = 'DESC';
                break;
            case 'most_liked':
                $post_args['meta_key'] = ESB_META_PREFIX.'post_like_count';
                $post_args['orderby'] = 'meta_value meta_value_num';
                $post_args['order'] = 'DESC';
                break;
            case 'highest_rated':
                $post_args['meta_key'] = ESB_META_PREFIX.'rating_average';
                $post_args['orderby'] = 'meta_value_num';
                $post_args['order'] = 'DESC';
                break;
            case 'price_high':
                $post_args['meta_key'] = '_price';
                $post_args['orderby'] = 'meta_value meta_value_num';
                $post_args['order'] = 'DESC';
                break;
            case 'price_low':
                $post_args['meta_key'] = '_price';
                $post_args['orderby'] = 'meta_value meta_value_num';
                $post_args['order'] = 'ASC';
                break;
                
        }
    }
    

    // fix search cache result
    // $post_args['cache_results'] = false;
    // $post_args['update_post_meta_cache'] = false;
    // $post_args['update_post_term_cache'] = false;


    // add filter for custom filter field
    $post_args = apply_filters( 'townhub_addons_ajax_search_args', $post_args );
    
    // $json['data']['posts_query_after'] = $post_args;
    

    

    // $json['data']['custom_sql'] = $posts_query->request;

    $json['data']['listings'] = '';

    $ad_posts = array();

    // for search ads
    if(townhub_addons_get_option('ads_search_enable') == 'yes'){ 
        $ad_args = array(
            'post_type'             => 'listing', 
            'orderby'               => townhub_addons_get_option('ads_search_orderby'),
            'order'                 => townhub_addons_get_option('ads_search_order'),
            'posts_per_page'        => townhub_addons_get_option('ads_search_count'),
            'meta_query'            => array(
                'relation' => 'AND',
                array(
                    'key'     => ESB_META_PREFIX.'is_ad',
                    'value'   => 'yes',
                ),
                array(
                    'key'     => ESB_META_PREFIX.'ad_position_search',
                    'value'   => '1',
                    // 'value'   => array('yes','1'),
                    // 'compare' => 'IN',
                ),
                array(
                    'key'     => ESB_META_PREFIX.'ad_expire',
                    'value'   => current_time('mysql', 1),
                    'compare' => '>=',
                    'type'    => 'DATETIME',
                ),
            ),
            // for ads distance
            'suppress_filters'     => false,
            'cthqueryid'           => 'nearby-ads',

        );

        $ads_query = new WP_Query( $ad_args );
        if($ads_query->have_posts()) :
            while($ads_query->have_posts()) : $ads_query->the_post();
                $ad_posts[] = get_the_ID();
                ob_start();
                townhub_addons_get_template_part('template-parts/listing', false, array('is_ad'=>true));
                $json['data']['listings'] .= ob_get_clean();
            endwhile;
        endif;
        wp_reset_postdata();
    }

    if( !empty($ad_posts) ){
        $post_args['post__not_in'] = $ad_posts;
    }

    $posts_query = new WP_Query($post_args);
    // $json['data']['posts'] = array();
    if($posts_query->have_posts()): 
        while($posts_query->have_posts()) : $posts_query->the_post();
            
            ob_start();
            townhub_addons_get_template_part('template-parts/listing');
            $json['data']['listings'] .= ob_get_clean();
        endwhile;
    endif;
    ob_start(); 
    townhub_addons_ajax_pagination( $posts_query->max_num_pages,$range = 2, $posts_query );
    $json['data']['pagination'] = ob_get_clean();
    // $json['WP_Query_request'] = $posts_query->request;
    

    wp_reset_postdata();
    // https://premium.wpmudev.org/blog/load-posts-ajax/
    wp_send_json($json );

}

add_filter( 'posts_clauses', 'townhub_addons_posts_clauses_callback', 999, 2 );

function townhub_addons_posts_clauses_callback($clauses, $query_obj){
    global $wpdb;
    if($query_obj->get('cthqueryid') == 'ajax-search' || $query_obj->get('cthqueryid') == 'main-search') {
        $fields = '';
        $joins = '';
        $having = array();
        $wheres = array();
        $orderByAdd = '';
        if( isset($_REQUEST['nearby']) && $_REQUEST['nearby'] == 'on' && isset($_REQUEST['address_lat']) && !empty($_REQUEST['address_lat'] ) && isset($_REQUEST['address_lng']) && !empty($_REQUEST['address_lng'] ) && ( (isset($_REQUEST['distance']) && $_REQUEST['distance']) || (isset($_POST['ldistance']) && $_POST['ldistance']) ) ){
            $fields .= $wpdb->prepare(
                ", ( 6371 * acos( cos( radians( %s ) ) * cos( radians( distance_lat.meta_value ) ) * cos( radians( distance_lng.meta_value ) - radians( %s ) ) + sin( radians( %s ) ) * sin( radians( distance_lat.meta_value ) ) ) ) AS listing_distance ",
                $_REQUEST['address_lat'], 
                $_REQUEST['address_lng'], 
                $_REQUEST['address_lat']
            );
            $joins .= $wpdb->prepare(
                " INNER JOIN $wpdb->postmeta distance_lat ON distance_lat.post_id = {$wpdb->posts}.ID AND distance_lat.meta_key = %s"
                . " INNER JOIN  $wpdb->postmeta distance_lng ON distance_lng.post_id = {$wpdb->posts}.ID AND distance_lng.meta_key = %s ",
                '_cth_latitude',
                '_cth_longitude'
            );

            if(isset($_REQUEST['distance']) && $_REQUEST['distance']) 
                $distance = $_REQUEST['distance'];
            else 
                $distance = $_POST['ldistance'];

            if( townhub_addons_get_option('distance_miles') == 'yes' ) $distance *= 1.609; // 0.62;
            
            $having[] = "listing_distance < '$distance'";

            if( empty($_REQUEST['morderby']) ) $orderByAdd = " listing_distance ASC";
        }

        if( isset($_REQUEST['event_date']) && $_REQUEST['event_date'] != '' ){
            if( townhub_addons_get_option('fevent_exact') == 'yes' ){
                $joins .= $wpdb->prepare(
                    " INNER JOIN $wpdb->postmeta eventdate_start_mt ON eventdate_start_mt.post_id = {$wpdb->posts}.ID AND eventdate_start_mt.meta_key = %s",
                    '_cth_eventdate_start'
                );
                $sEventDate = Esb_Class_Date::reformat( $_REQUEST['event_date'] , false, 'Y-m-d' );
                if( townhub_addons_get_option('fevent_calendar') == 'yes' ){
                    $sEventDateCal = Esb_Class_Date::reformat( $_REQUEST['event_date'] , false, 'Ymd' );
                    $joins .= $wpdb->prepare(
                        " INNER JOIN $wpdb->postmeta eventdate_calendar ON eventdate_calendar.post_id = {$wpdb->posts}.ID AND eventdate_calendar.meta_key = %s",
                        '_cth_listing_dates'
                    );
                    $wheres[] = $wpdb->prepare( "(eventdate_start_mt.meta_value = %s OR eventdate_calendar.meta_value LIKE %s)", $sEventDate, '%' . $wpdb->esc_like($sEventDateCal) . '%');
                }else{
                    $wheres[] = $wpdb->prepare( "eventdate_start_mt.meta_value = %s", $sEventDate );
                }
                
            }else{
                $joins .= $wpdb->prepare(
                    " INNER JOIN $wpdb->postmeta eventdate_start_mt ON eventdate_start_mt.post_id = {$wpdb->posts}.ID AND eventdate_start_mt.meta_key = %s",
                    '_cth_eventdate_start'
                );
                $joins .= $wpdb->prepare(
                    " INNER JOIN $wpdb->postmeta eventdate_end_mt ON eventdate_end_mt.post_id = {$wpdb->posts}.ID AND eventdate_end_mt.meta_key = %s",
                    '_cth_eventdate_end'
                );
                $sEventDate = Esb_Class_Date::reformat( $_REQUEST['event_date'] , false, 'Y-m-d' );
                if( townhub_addons_get_option('fevent_calendar') == 'yes' ){
                    $sEventDateCal = Esb_Class_Date::reformat( $_REQUEST['event_date'] , false, 'Ymd' );
                    $joins .= $wpdb->prepare(
                        " INNER JOIN $wpdb->postmeta eventdate_calendar ON eventdate_calendar.post_id = {$wpdb->posts}.ID AND eventdate_calendar.meta_key = %s",
                        '_cth_listing_dates'
                    );
                    $wheres[] = $wpdb->prepare( "((eventdate_start_mt.meta_value <= %s AND eventdate_end_mt.meta_value != 'none' AND eventdate_end_mt.meta_value >= %s) OR eventdate_calendar.meta_value LIKE %s)", $sEventDate, $sEventDate, '%' . $wpdb->esc_like($sEventDateCal) . '%');
                }else{
                    $wheres[] = $wpdb->prepare( "eventdate_start_mt.meta_value <= %s AND eventdate_end_mt.meta_value != 'none' AND eventdate_end_mt.meta_value >= %s", $sEventDate, $sEventDate );
                }

                
            }
            // $joins .= $wpdb->prepare(
            //     " INNER JOIN $wpdb->postmeta event_date_mt ON event_date_mt.post_id = {$wpdb->posts}.ID AND event_date_mt.meta_key = %s",
            //     '_cth_eventdate'
            // );

            // $wheres[] = $wpdb->prepare("event_date_mt.meta_value LIKE %s", '%' . $wpdb->esc_like( Esb_Class_Date::reformat( $_REQUEST['event_date'] , false, 'Y-m-d' ) ) . '%');
            // // database working: SELECT * FROM wp_postmeta AS pmeta WHERE pmeta.meta_key="_cth_eventdate" AND pmeta.meta_value LIKE "%2020-09-10%"
        
                
        }

        // https://stackoverflow.com/questions/14950466/how-to-split-the-name-string-in-mysql
        // https://stackoverflow.com/questions/12344795/count-the-number-of-occurrences-of-a-string-in-a-varchar-field
        $search_term_string = '';
        if( isset($_REQUEST['search_term']) ){
            if( is_array($_REQUEST['search_term']) )
                $search_term_string = reset($_REQUEST['search_term']);
            else 
                $search_term_string = $_REQUEST['search_term'];
        }
        // $search_term_string = esc_html( trim($search_term_string) );
        $search_term_string = stripslashes(trim($search_term_string));
        if( $search_term_string != '' ){
            $address_q = explode(",", $search_term_string);
            $address_qr = array();
            foreach ((array)$address_q as $add_r) {
                $address_qr[] =   $wpdb->prepare("laddress_meta.meta_value LIKE %s", '%' . $wpdb->esc_like(trim($add_r)) . '%');
            }
            $address_qr_text = '';
            if(!empty($address_qr)){
                $address_qr_text = "OR ( ".implode(" OR ", $address_qr)." )";
            }
            
            $joins .= $wpdb->prepare(
                " LEFT JOIN $wpdb->postmeta AS laddress_meta ON laddress_meta.post_id = {$wpdb->posts}.ID AND laddress_meta.meta_key = %s",
                '_cth_address'
                
            );
            $search_term_esc = '%' . $wpdb->esc_like($search_term_string) . '%';
            $lcat_like = '';
            if(townhub_addons_get_option('search_include_cat', 'no') == 'yes'){
                $lcat_like = $wpdb->prepare( " OR EXISTS (
                        SELECT 1
                        FROM $wpdb->term_relationships
                        INNER JOIN $wpdb->term_taxonomy
                        ON $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id
                        INNER JOIN $wpdb->terms 
                        ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id AND $wpdb->terms.name LIKE %s 
                        WHERE $wpdb->term_taxonomy.taxonomy = %s
                        AND $wpdb->term_relationships.object_id = {$wpdb->posts}.ID
                    )", $search_term_esc, 'listing_cat' ); //post_tag listing_tag
            }
            $post_tag_like = '';
            if(townhub_addons_get_option('search_include_tag', 'yes') == 'yes'){
                $post_tag_like = $wpdb->prepare( " OR EXISTS (
                        SELECT 1
                        FROM $wpdb->term_relationships
                        INNER JOIN $wpdb->term_taxonomy
                        ON $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id
                        INNER JOIN $wpdb->terms 
                        ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id AND $wpdb->terms.name LIKE %s 
                        WHERE $wpdb->term_taxonomy.taxonomy = %s
                        AND $wpdb->term_relationships.object_id = {$wpdb->posts}.ID
                    )", $search_term_esc, 'listing_tag' ); //post_tag listing_tag
            }
            
            $wheres[] = $wpdb->prepare(
                "(({$wpdb->posts}.post_title LIKE %s OR {$wpdb->posts}.post_content LIKE %s OR {$wpdb->posts}.post_excerpt LIKE %s) $address_qr_text $post_tag_like $lcat_like)", 
                $search_term_esc, 
                $search_term_esc, 
                $search_term_esc
            );

        }

        // for open check
        if(isset($_REQUEST['status']) && $_REQUEST['status'] == 'open'){

            $wkhours_table = $wpdb->prefix . 'cth_wkhours';

            $joins .= $wpdb->prepare(
                " INNER JOIN $wpdb->postmeta wk_tz_offset ON wk_tz_offset.post_id = {$wpdb->posts}.ID AND wk_tz_offset.meta_key = %s"
            // working    // ." INNER JOIN $wkhours_table wkhours_table ON wkhours_table.post_id = {$wpdb->posts}.ID AND wkhours_table.day = DATE_FORMAT( CONVERT_TZ(%s,'+00:00',wk_tz_offset.meta_value), '%a') AND ( wkhours_table.static = %s OR ( wkhours_table.static = %s AND wkhours_table.open <= DATE_FORMAT(CONVERT_TZ(%s,'+00:00',wk_tz_offset.meta_value), '%T') ) )",
                ." INNER JOIN $wkhours_table wkhours_table ON wkhours_table.post_id = {$wpdb->posts}.ID AND wkhours_table.day = DATE_FORMAT( CONVERT_TZ(%s,'+00:00',wk_tz_offset.meta_value), '%a') AND ( 
                    wkhours_table.static = %s OR 
                    ( 
                        wkhours_table.static = %s AND 
                        (
                            CASE WHEN wkhours_table.open <= wkhours_table.close
                                THEN ( 
                                    wkhours_table.open <= DATE_FORMAT(CONVERT_TZ(%s,'+00:00',wk_tz_offset.meta_value), '%T') AND 
                                    wkhours_table.close >= DATE_FORMAT(CONVERT_TZ(%s,'+00:00',wk_tz_offset.meta_value), '%T') 
                                )
                                ELSE (
                                    wkhours_table.open <= DATE_FORMAT(CONVERT_TZ(%s,'+00:00',wk_tz_offset.meta_value), '%T')
                                )
                            END
                        )
                         
                    ) 
                )",
                // ." INNER JOIN $wkhours_table wkhours_table_prev ON wkhours_table_prev.post_id = {$wpdb->posts}.ID AND wkhours_table_prev.day = DATE_FORMAT( DATE_SUB( CONVERT_TZ(%s,'+00:00',wk_tz_offset.meta_value) , INTERVAL 1 DAY ) , '%a')",
                '_cth_wkh_tz_utc_offset',
                current_time('mysql', 1),
                'openAllDay',
                'enterHours',
                current_time('mysql', 1),
                current_time('mysql', 1),
                current_time('mysql', 1) // need to change to utc timezone
            );

            
        }

        // https://stackoverflow.com/questions/14950466/how-to-split-the-name-string-in-mysql
        // https://stackoverflow.com/questions/12344795/count-the-number-of-occurrences-of-a-string-in-a-varchar-field

        $clauses[ 'fields' ] .= $fields ;
        $clauses[ 'join' ] .= $joins ;

        if(!empty($having)){
            $distance_groupby = '';
            if(empty($clauses[ 'groupby' ])) $distance_groupby = "{$wpdb->posts}.ID";

            $clauses[ 'groupby' ] .= " $distance_groupby HAVING ".implode(" AND ", $having);
        }

        if(!empty($wheres)){
            $clauses[ 'where' ] .= " AND ".implode(" AND ", $wheres);
        }

        if( !empty($orderByAdd) ) $clauses[ 'orderby' ] = $orderByAdd;
            
    }

    return $clauses;

    // error
    /*
    ["request"]=>
  string(1761) "SELECT SQL_CALC_FOUND_ROWS  wp_posts.ID FROM wp_posts  LEFT JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id) LEFT JOIN wp_postmeta AS laddress_meta ON laddress_meta.post_id = wp_posts.ID AND laddress_meta.meta_key = '_cth_address' WHERE 1=1  AND wp_posts.ID NOT IN (1882) AND ( 
  wp_term_relationships.term_taxonomy_id IN (51)
) AND wp_posts.post_type = 'listing' AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'private') AND 
    (wp_posts.post_title LIKE 'Hotel' OR wp_posts.post_content LIKE 'Hotel') OR ( laddress_meta.meta_value LIKE '{c5dad3c3108580d7a0b6e7862e4a8255927e23d8868b27873cd3fd39691cf233}Hotel{c5dad3c3108580d7a0b6e7862e4a8255927e23d8868b27873cd3fd39691cf233}' )  OR EXISTS (
                        SELECT 1
                        FROM wp_term_relationships
                        INNER JOIN wp_term_taxonomy
                        ON wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id
                        INNER JOIN wp_terms 
                        ON wp_terms.term_id = wp_term_taxonomy.term_id AND wp_terms.name LIKE '{c5dad3c3108580d7a0b6e7862e4a8255927e23d8868b27873cd3fd39691cf233}Hotel{c5dad3c3108580d7a0b6e7862e4a8255927e23d8868b27873cd3fd39691cf233}' 
                        WHERE wp_term_taxonomy.taxonomy = 'post_tag'
                        AND wp_term_relationships.object_id = wp_posts.ID
                    ) GROUP BY wp_posts.ID ORDER BY wp_posts.post_date DESC LIMIT 0, 8"

    */

}
function townhub_addons_get_available_listings( $available_args = array() /*$checkin = '', $checkout = '', $listing_id = 0*/){ // 63,65,1886
    global $wpdb;
    $checkin = Esb_Class_Date::modify($available_args['checkin'], 0, 'Ymd');
    $checkout = Esb_Class_Date::modify($available_args['checkout'], 0, 'Ymd');

    if(empty($checkin) || empty($checkout)) return array();
    // key1 -> listing_id
    // key2 -> calendar
    // key3 -> calendar
    $post_type      = 'lrooms';
    $post_type_2      = 'product';
    $post_status    = 'publish';
    $meta_key1      = '_cth_for_listing_id';
    $meta_key2      = '_cth_calendar';
    $meta_key3      = '_cth_quantity';

    $booking_table = $wpdb->prefix . 'cth_booking';

    

    $fields = "DISTINCT key1.meta_value AS id";
    $from = "$wpdb->postmeta AS key1";

    
    $bk_statuses = array();
    $bk_count_status = townhub_addons_get_option('bk_count_status');
    $bk_count_status = array_filter($bk_count_status);
    if( !empty($bk_count_status) ){
        
        foreach ($bk_count_status as $sts) {
            if( $sts == 'pending' ){
                $bk_statuses[] = 0;
            }elseif( $sts == 'completed' ){
                $bk_statuses[] = 1;
            }
        }
    }
    if( empty($bk_statuses) ) $bk_statuses = array(1);

    $bk_statuses = implode(',', $bk_statuses);
    

    $join_1 = $wpdb->prepare("INNER JOIN  $wpdb->postmeta AS key2 ON key2.post_id = key1.post_id AND key2.meta_key = %s", $meta_key2);
    // $join_2 = "LEFT JOIN $booking_table bookings ON bookings.room_id = key1.post_id AND bookings.status = 1";
    $join_2 = "LEFT JOIN $booking_table bookings ON bookings.room_id = key1.post_id AND bookings.status IN ($bk_statuses)";
    $join_3 = $wpdb->prepare("INNER JOIN  $wpdb->postmeta AS key3 ON key3.post_id = key1.post_id AND key3.meta_key = %s", $meta_key3);
    $join_4 = $wpdb->prepare("INNER JOIN  $wpdb->posts AS posts ON posts.ID = key1.post_id AND (posts.post_type = %s OR posts.post_type = %s ) AND posts.post_status = %s", $post_type, $post_type_2, $post_status);

    $join_adults = '';
    $join_children = '';
    $where_adults = '';
    $where_children = '';
    if( isset($_REQUEST['adults']) && intval( $_REQUEST['adults'] ) > 0 ){
        $join_adults = $wpdb->prepare("INNER JOIN  $wpdb->postmeta AS room_adults ON room_adults.post_id = key1.post_id AND room_adults.meta_key = %s", '_cth_adults');
        $where_adults =  $wpdb->prepare( " AND CAST(room_adults.meta_value AS SIGNED) >= %d", intval( $_REQUEST['adults'] ) );
    }
    if( isset($_REQUEST['children']) && intval( $_REQUEST['children'] ) > 0 ){
        $join_children = $wpdb->prepare("INNER JOIN  $wpdb->postmeta AS room_children ON room_children.post_id = key1.post_id AND room_children.meta_key = %s", '_cth_children');
        $where_children =  $wpdb->prepare( " AND CAST(room_children.meta_value AS SIGNED) >= %d", intval( $_REQUEST['children'] ) );
    }
    // if( isset($_REQUEST['infants']) ){
    //     $sPers += intval( $_REQUEST['infants'] );
    // }


    $where_1 = $wpdb->prepare("key1.meta_key = %s", $meta_key1);

    $diff = townhub_addons_booking_nights($checkin, $checkout);

    if($diff > 0){
        $date_arr = array();
        for ($i=0; $i <= $diff ; $i++) { 
            $modified_date = Esb_Class_Date::modify($checkin, $i, 'Ymd');
            if($modified_date){
                $date_arr[] = $wpdb->prepare( "key2.meta_value LIKE %s", '%' . $wpdb->esc_like($modified_date) . '%');
            }  
        }
        $where_2 =  " AND ( key2.meta_value = '' OR (". implode(' AND ', $date_arr).") )";
    }else{
        $where_2 =  " AND ( key2.meta_value = '' OR ". $wpdb->prepare( "key2.meta_value LIKE %s", '%' . $wpdb->esc_like($checkin) . '%') .")";
    }

    // $where_3 = $wpdb->prepare("(CASE 
    //                 WHEN bookings.ID IS NULL
    //                     THEN key3.meta_value > 0 
    //                 ELSE 
    //                     (CASE
    //                         WHEN (bookings.date_to >= %s AND bookings.date_from >= %s) OR (bookings.date_to <= %s AND bookings.date_to <= %s)
    //                             THEN key3.meta_value > 0 
    //                         ELSE
    //                             key3.meta_value - (SELECT SUM(quantities.quantity) FROM $booking_table AS quantities WHERE 
    //                                                 quantities.room_id = key1.post_id AND 
    //                                                 ((quantities.date_to >= %s AND quantities.date_to <= %s) OR (quantities.date_from >= %s AND quantities.date_from <= %s) OR (quantities.date_from >= %s AND quantities.date_to <= %s) OR (quantities.date_from <= %s AND quantities.date_to >= %s)) 
    //                                                 ) > 0
    //                     END)
    //             END)", $checkin, $checkout,   $checkin, $checkout,   $checkin, $checkout,   $checkin, $checkout,   $checkin, $checkout,   $checkin, $checkout );

    $where_3 = $wpdb->prepare("(CASE 
                    WHEN bookings.ID IS NULL
                        THEN key3.meta_value > 0 
                    ELSE 
                        (CASE
                            WHEN (SELECT @quantityVar := SUM(quantities.quantity) FROM $booking_table AS quantities WHERE 
                                                    quantities.room_id = key1.post_id AND 
                                                    ((quantities.date_to >= %s AND quantities.date_to <= %s) OR (quantities.date_from >= %s AND quantities.date_from <= %s) OR (quantities.date_from >= %s AND quantities.date_to <= %s) OR (quantities.date_from <= %s AND quantities.date_to >= %s)) 
                                    ) IS NULL THEN key3.meta_value > 0 
                            ELSE
                                key3.meta_value - @quantityVar > 0
                        END)
                END)", $checkin, $checkout,   $checkin, $checkout,   $checkin, $checkout,   $checkin, $checkout );

    


    $groupby = '';
    $orderby = 'ORDER BY id DESC';
    $limits = '';
    $found_rows = '';
    $distinct = '';

    if(isset($available_args['listing_id']) && is_numeric($available_args['listing_id']) && (int)$available_args['listing_id'] > 0){
        $fields = $wpdb->prepare("DISTINCT key1.post_id AS id, (CASE  WHEN (SELECT @quantityVar := SUM(bookings.quantity) FROM $booking_table AS bookings WHERE bookings.room_id = key1.post_id AND bookings.status IN ($bk_statuses) AND ((bookings.date_to >= %s AND bookings.date_to <= %s) OR 
                                    (bookings.date_from >= %s AND bookings.date_from <= %s) OR 
                                    (bookings.date_from >= %s AND bookings.date_to <= %s) OR 
                                    (bookings.date_from <= %s AND bookings.date_to >= %s)) ) IS NULL THEN key3.meta_value ELSE key3.meta_value - @quantityVar  END) AS quantities 
                                    ", $checkin, $checkout,   $checkin, $checkout,   $checkin, $checkout,   $checkin, $checkout
                                );
        $join_2 = '';

        $join_adults = '';
        $join_children = '';
        $where_adults = '';
        $where_children = '';


        $meta_key1 = '_cth_for_listing_id';
        $where_1 = $wpdb->prepare("key1.meta_key = %s AND key1.meta_value = %s", $meta_key1, (int)$available_args['listing_id']);
        $where_3 = "1=1";
    }

    $joins = $join_1 . ' ' . $join_2 . ' ' . $join_3 . ' ' . $join_4 . ' ' . $join_adults . ' ' . $join_children;
    $wheres = $where_1 . $where_2 . ' AND ' . $where_3 . $where_adults . $where_children ;

    $request = "SELECT $found_rows $distinct $fields FROM $from $joins WHERE 1=1 AND $wheres $groupby $orderby $limits";

    $postids = $wpdb->get_results($request);
    // var_dump($postids);

    if ( $postids ) return $postids;
    return array();
}

function townhub_addons_listing_available_date($checkin = ''){
    global $wpdb;
    $checkin = Esb_Class_Date::modify($checkin, 0, 'Ymd');
    if( empty($checkin) ) return array();
    $calendars =    array(
                        // 'house_dates',
                        // 'event_dates',
                        // 'tour_dates',
                        'listing_dates',
                    );

    

    $fields = "DISTINCT key1.post_id AS id";
    $from = "$wpdb->postmeta AS key1";

    $where_keys = array();
    foreach ($calendars as $mtkey) {
        $where_keys[] = $wpdb->prepare("key1.meta_key = %s", ESB_META_PREFIX.$mtkey);
    }

    $where_1 = '('.implode(' OR ', $where_keys).')';

    $where_2 =  $wpdb->prepare( "key1.meta_value LIKE %s", '%' . $wpdb->esc_like($checkin) . '%');


    $groupby = '';
    $orderby = 'ORDER BY id DESC';
    $limits = '';
    $found_rows = '';
    $distinct = '';

    $joins = '';
    $wheres = $where_1 . ' AND ' . $where_2  ;

    $request = "SELECT $found_rows $distinct $fields FROM $from $joins WHERE 1=1 AND $wheres $groupby $orderby $limits";

    // $postids = $wpdb->get_results($request);
    $postids = $wpdb->get_col($request);
    // var_dump($postids);

    if ( $postids ) return $postids;
    return array();
}

add_filter( 'posts_clauses', 'townhub_addons_auto_locate_posts_clauses_callback', 999, 2 );

function townhub_addons_auto_locate_posts_clauses_callback($clauses, $query_obj){
    global $wpdb;
    
    if( $query_obj->get('cthqueryid') == 'auto-locate' || $query_obj->get('cthqueryid') == 'nearby-listings' || $query_obj->get('cthqueryid') == 'nearby-ads' ) {

        $fields = '';
        $joins = '';
        $having = array();
        $wheres = array();
        $orderByAdd = '';
        if( $query_obj->get('cthqueryid') == 'nearby-ads' ){
            $latitude = !empty( $_REQUEST['address_lat'] ) ? $_REQUEST['address_lat'] : 0;
            $longitude = !empty( $_REQUEST['address_lng'] ) ? $_REQUEST['address_lng'] : 0;

        }elseif( $query_obj->get('cthqueryid') == 'nearby-listings' ){
            $latitude = get_post_meta( get_queried_object_id(), ESB_META_PREFIX.'latitude', true );
            $longitude = get_post_meta( get_queried_object_id(), ESB_META_PREFIX.'longitude', true );

        }else{
            if( empty(ESB_ADO()->geo) ) return $clauses;
            
            $latitude = ESB_ADO()->geo->get('lat');
            $longitude = ESB_ADO()->geo->get('lng');
        }
        if( empty($latitude) || empty($longitude) ) return $clauses;
        // if( !empty($latitude) && !empty($longitude) ){
            $fields .= $wpdb->prepare(
                ", ( 6371 * acos( cos( radians( %s ) ) * cos( radians( distance_lat.meta_value ) ) * cos( radians( distance_lng.meta_value ) - radians( %s ) ) + sin( radians( %s ) ) * sin( radians( distance_lat.meta_value ) ) ) ) AS listing_distance ",
                $latitude, 
                $longitude, 
                $latitude
            );
            $joins .= $wpdb->prepare(
                " INNER JOIN $wpdb->postmeta distance_lat ON distance_lat.post_id = {$wpdb->posts}.ID AND distance_lat.meta_key = %s"
                . " INNER JOIN  $wpdb->postmeta distance_lng ON distance_lng.post_id = {$wpdb->posts}.ID AND distance_lng.meta_key = %s ",
                '_cth_latitude',
                '_cth_longitude'
            );

            $distance = apply_filters( 'cth_nearby_distance', 50 );

            if( townhub_addons_get_option('distance_miles') == 'yes' ) $distance *= 1.609; // 0.62;

            $having[] = "listing_distance < '$distance'";

            $orderByAdd = " listing_distance ASC";
        // }

        $clauses[ 'fields' ] .= $fields ;
        $clauses[ 'join' ] .= $joins ;

        if(!empty($having)){
            $distance_groupby = '';
            if(empty($clauses[ 'groupby' ])) $distance_groupby = "{$wpdb->posts}.ID";

            $clauses[ 'groupby' ] .= " $distance_groupby HAVING ".implode(" AND ", $having);
        }

        if(!empty($wheres)){
            $clauses[ 'where' ] .= " AND ".implode(" AND ", $wheres);
        }

        if( !empty($orderByAdd) ) $clauses[ 'orderby' ] = $orderByAdd;
            
    }

    return $clauses;

}




