<?php
/* add_ons_php */

// get edit listing
add_action('wp_ajax_nopriv_townhub_addons_get_edit_listing', 'townhub_addons_get_edit_listing_callback');
add_action('wp_ajax_townhub_addons_get_edit_listing', 'townhub_addons_get_edit_listing_callback');
function townhub_addons_get_edit_listing_callback() {    
    $json = array(
        'success'       => false,
        'data'          => array(
            // 'POST' => $_POST, 
        ),
        'titles'        => array(),
        'post'          => array(),
        'fields'        => array(),
        'rFields'       => array(),
        // 'rpost'      => array(),
        'rooms'         => array(),
        'listing_rooms'         => array(),
        'isEditing'     => false,
        'isAdding'      => false,
        'debug'         => false,
    );
    $nonce = $_POST['_nonce'];
    if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
        $json['data']['error'] = __( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ; 
        wp_send_json($json );
    }
    $lid = isset($_POST['lid'])? $_POST['lid'] : 0;

    $ltype_id = get_post_meta( $lid, ESB_META_PREFIX.'listing_type_id', true );
    // if( $ltype_id == '' ) $ltype_id =  esb_addons_get_wpml_option('default_listing_type', 'listing_type');
    if(isset($_POST['listing_type_id']) && (int)$_POST['listing_type_id'] > 0 ) $ltype_id = $_POST['listing_type_id'];

    $allow_types = Esb_Class_Membership::author_listing_types_ids();
    // if(empty($allow_types) || ( absint($ltype_id) > 0 && !in_array($ltype_id, $allow_types) ) ){
    if( empty($allow_types) ){
        $json['post']['listing_type_id'] = -1;
        $json['error'] = __( 'You are not allowed to submit/edit listing. Please order an author membership to be able edit your listing.', 'townhub-add-ons', 'townhub-add-ons' ) ; 
        wp_send_json($json );
    }elseif($ltype_id == 0 || !in_array($ltype_id, $allow_types) ){
        // $dftype = esb_addons_get_wpml_option('default_listing_type', 'listing_type');
        // if(in_array($dftype, $allow_types)){
        //     $ltype_id = $dftype;
        // }else{
        //     $ltype_id = reset($allow_types);
        // }
        $ltype_id = Esb_Class_Membership::default_ltype();
    }
    
    // get listing fields
    $json['fields'] = townhub_addons_get_listing_type_fields_obj( $ltype_id , true, true,true, true);
    $json['rFields'] = townhub_addons_get_rooms_type_fields_obj( $ltype_id ,true, true );
    $user_id = isset($_POST['user_id'])? $_POST['user_id'] : 0;
    if($user_id == false) $user_id = get_current_user_id();
    if( is_numeric($user_id) && $user_id > 0 ){
        if( ! user_can( $user_id, 'edit_post' , $lid ) ){
            $json['data']['error'] = __( "You don't have permission to edit this listing.", 'townhub-add-ons' ) ;
            wp_send_json( $json );
        }
        // $lpost = get_posts( array(
        //     'post_type'     =>  'listing', 
        //     'p' => $lid,
        //     'post_status'   => array('publish', 'pending'),
        // ) );
        // if(!$lpost){
        //     $json['data']['error'] = __( "The editing listing is incorrect.", 'townhub-add-ons' ) ;
        //     wp_send_json( $json );
        // }else{
            $json['success'] = true;
            $cur_cats = array();
            $cats = get_the_terms( $lid , 'listing_cat' );    
            // var_dump($cats);
            if ( $cats && ! is_wp_error( $cats ) ){
                foreach ( $cats as $cat ) {
                    $cur_cats[] = $cat->term_id;
                }
            }
            $get_tags = get_the_tags($lid);
            $listing_tags = '';
            if ( $get_tags && ! is_wp_error( $get_tags ) ){
                foreach ($get_tags as $tag) {
                    $listing_tags .= $tag->name.',';
                }
                
            }

            // get listing location
            // features
            // $cur_locs = array();
            //  $cur_loc = array();
            // $locs = get_the_terms( $lid , 'listing_location' );    
            // // var_dump($cats);
            // if ( $locs && ! is_wp_error( $locs ) ){
            //     foreach ( $locs as $loc ) {
            //         $cur_locs[] = $loc->term_id;
            //     }
            // }  
            $json['post'] = array(
                'lid'                       => $lid,
                'title'                     => html_entity_decode( get_the_title( $lid ), ENT_QUOTES ), // wp_specialchars_decode // https://www.tipsandtricks-hq.com/how-to-fix-the-character-encoding-problem-in-wordpress-1480
                'content'                   => apply_filters('the_content', get_post_field('post_content', $lid) ),

                // 'address'                   => 'This is listing address',
                // 'latitude'                  => '57',
                // 'longitude'                 => '102',

                'thumbnail'                 => get_post_thumbnail_id($lid),
                'listing_type_id'           => $ltype_id,
                'cats'                      => $cur_cats,
                'tags'                      => $listing_tags, 
                'locations'                 => townhub_addons_get_listings_locations_hierarchy($lid), // $cur_locs
                'select_locations'          => townhub_addons_get_listing_locations_selected($lid), // $cur_locs
                'features'                  => townhub_addons_get_listings_feature_hierarchy($lid),

                'working_hours'             => Esb_Class_Listing_CPT::wkhours($lid, true),

                'ltags_names'               => townhub_addons_get_listing_tags($lid),
                'post_excerpt'              => get_the_excerpt( $lid ),

                // 'select_locations'          => townhub_addons_get_listing_locations_selected( $lid ),
                'preview_url'               => get_permalink( $lid ),
                '_price'                    => get_post_meta( $lid, '_price', true ),
                // 'add_room_title'                    => get_post_meta( $ltype_id, ESB_META_PREFIX.'add_room_title', true ),
                // 'edit_room_title'                    => get_post_meta( $ltype_id, ESB_META_PREFIX.'edit_room_title', true ),
            );
            // check has location selects
            // $locations_select = false;
            foreach((array)townhub_addons_get_listing_type_fields_meta( $ltype_id ) as $fname => $ftype){
                $json['post'][$fname] = get_post_meta( $lid, ESB_META_PREFIX.$fname, true );
                // if($ftype === 'calendar_metas'){
                //     $json['post'][$fname .'_metas'] = get_post_meta( $lid, ESB_META_PREFIX.$fname.'_metas', true );
                // }
                // if($ftype === 'locations') $locations_select = true;
            }
            // if( $locations_select ) $json['post']['select_locations'] = townhub_addons_get_listing_locations_selected( $lid );
            // get rooms data
            $rooms_ids = get_post_meta( $lid, ESB_META_PREFIX.'rooms_ids', true );
            // $json['rooms'] = $rooms_ids;
            if(!empty($rooms_ids) && is_array($rooms_ids)){
                foreach ($rooms_ids as $rid) {
                    $rdatas = townhub_addons_get_room_post_data($rid, $ltype_id);
                    if(!empty($rdatas)){
                        $json['rooms'][] = $rdatas;
                        // $json['listing_rooms'][] = $rdatas;
                    }

                    
                }
            }
            $coupon_ids = get_post_meta( $lid, ESB_META_PREFIX.'coupon_ids', true );
            if(!empty($coupon_ids) && is_array($coupon_ids)){
                foreach ($coupon_ids as $cid) {
                    $cdatas = townhub_addons_get_listing_coupon($cid);
                    if(!empty($cdatas)) $json['coupons'][] = $cdatas;
                }
            }

            $json['titles']['add_room_title'] = get_post_meta( $ltype_id, ESB_META_PREFIX.'add_room_title', true );
            $json['titles']['edit_room_title'] = get_post_meta( $ltype_id, ESB_META_PREFIX.'edit_room_title', true );

            // set isEditing
            $json['isEditing'] = true;
        // }  
    }else{
        $json['data']['error'] = __( 'The author id is incorrect.', 'townhub-add-ons' ) ;
        wp_send_json($json );
    }
    
    wp_send_json($json );

}
function townhub_addons_get_listing_coupon($cid = 0){
    if(is_numeric($cid) && $cid > 0){
        $coupon_data = array(
            'coupon_code'          => get_post_meta( $cid, ESB_META_PREFIX.'coupon_code', true ),
            'discount_type'         => get_post_meta( $cid, ESB_META_PREFIX.'discount_type', true ),
            'dis_amount'            => get_post_meta( $cid, ESB_META_PREFIX.'dis_amount', true ),
            'coupon_decs'           => get_post_meta( $cid, ESB_META_PREFIX.'coupon_decs', true ),
            'coupon_qty'            => get_post_meta( $cid, ESB_META_PREFIX.'coupon_qty', true ),
            'coupon_expiry_date'    => get_post_meta( $cid, ESB_META_PREFIX.'coupon_expiry_date', true ),
        );
        return $coupon_data;
    }
    return false;
          
}
function townhub_addons_get_listings_locations_hierarchy($listing_id = 0) {
    $taxonomy = 'listing_location'; //Put your custom taxonomy term here
    $terms = get_the_terms( $listing_id, $taxonomy );
    $country_term = false;
    $state_term = false;
    $city_term = false;
    if ( !is_wp_error( $terms ) && $terms ) {
        foreach ( $terms as $term ){
            if ($term->parent == 0){
                // this gets the parent of the current post taxonomy
                $country_term = $term;
            } 
        }
        if($country_term){
            foreach ( $terms as $term ){
                if ($term->parent != 0 && $term->parent == $country_term->term_id){
                    // this gets the parent of the current post taxonomy
                    $state_term = $term;
                } 
            }
            if($state_term){
                foreach ( $terms as $term ){
                    if ($term->parent != 0 && $term->parent == $state_term->term_id){ 
                        // this gets the parent of the current post taxonomy
                        $city_term = $term;
                    } 
                }
            }
        }
    }
    $return = '';
    if($country_term) $return .= townhub_addons_encodeURIComponent(strtoupper($country_term->slug));
    if($state_term) $return .= "|" . townhub_addons_encodeURIComponent($state_term->name);
    if($city_term) $return .= "|" . townhub_addons_encodeURIComponent($city_term->name);

    return $return;
}

function townhub_addons_get_listing_locations_selected($listing_id = 0) {
    $taxonomy = 'listing_location'; //Put your custom taxonomy term here
    $terms = get_the_terms( $listing_id, $taxonomy );
    $selected = array();
    if ( !is_wp_error( $terms ) && $terms ) {
        foreach ( $terms as $term ){
            $selected[] = $term->term_id;
        }
    }
    return $selected;
}

// get room post data
function townhub_addons_get_room_post_data($rid = 0, $listing_type_id = 0){
    if(is_numeric($rid) && $rid > 0){
        $rpost = get_post($rid);
        if(!empty($rpost)){

            $cur_cats = array();
            $cats = get_the_terms( $rid , 'product_cat' );    
            // var_dump($cats);
            if ( $cats && ! is_wp_error( $cats ) ){
                foreach ( $cats as $cat ) {
                    $cur_cats[] = $cat->term_id;
                }
            }

            $data = array(
                'rid'                        => $rid,
                'title'                     => html_entity_decode( get_the_title( $rid ), ENT_QUOTES ),
                'content'                   => apply_filters('the_content', get_post_field('post_content', $rid) ),
                'thumbnail'                 =>  get_post_thumbnail_id($rid) ,
                'dbthumb_url'               => get_the_post_thumbnail_url( $rid, 'medium' ),
                'features'                  => townhub_addons_get_listings_feature_hierarchy($rid),
                '_price'                  => get_post_meta( $rid, '_price', true ),
                'post_excerpt'              => get_the_excerpt( $rid ),
                'cats'                      => $cur_cats,
            );
            foreach((array)townhub_addons_get_listing_type_fields_meta( $listing_type_id, true ) as $fname => $ftype){
                $data[$fname] = get_post_meta( $rid, ESB_META_PREFIX.$fname, true );
                // if($ftype === 'calendar_metas'){
                //     $data[$fname .'_metas'] = get_post_meta( $rid, ESB_META_PREFIX.$fname.'_metas', true );
                // }
            }
            // product images
            if( empty($data['images']) ){
                $data['images'] = get_post_meta( $rid, '_product_image_gallery', true );
                // 
            }
            return $data;
        }
        return false;
    }
    return false;
}
function townhub_addons_get_listings_feature_hierarchy($listing_id = 0) {
    $taxonomy = 'listing_feature'; //Put your custom taxonomy term here
    $terms = get_the_terms( $listing_id, $taxonomy );
    $featuresed = array();
    if ( !is_wp_error( $terms ) && $terms ) {
         foreach( $terms as $key => $term){
            $featuresed[] = $term->term_id;
         }
    }
    return $featuresed;
}
function townhub_addons_get_listing_tags($listing_id = 0) {
    $taxonomy = 'listing_tag'; //Put your custom taxonomy term here
    $terms = get_the_terms( $listing_id, $taxonomy );
    $term_names = array();
    if ( !is_wp_error( $terms ) && $terms ) {
        foreach( $terms as $key => $term){
            $term_names[] = $term->name;
        }
    }
    return implode(',', $term_names);
}
// get submit listing fields
add_action('wp_ajax_nopriv_townhub_addons_get_submit_listing_fields', 'townhub_addons_get_submit_listing_fields_callback');
add_action('wp_ajax_townhub_addons_get_submit_listing_fields', 'townhub_addons_get_submit_listing_fields_callback');

function townhub_addons_get_submit_listing_fields_callback() {
    $json = array(
        'success' => false,
        'data' => array(
            // 'POST'=>$_POST,
        ),
        'titles' => array(),
        'post'      => array(),
        'fields'    => array(),
        'rFields'   => array(),
        // 'rpost'     => array(),
        'rooms'     => array(),
        'isEditing'    => false,
        'isAdding'      => false,
        'debug'         => false,
    );
    $nonce = $_POST['_nonce'];
    if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
        $json['error'] = __( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ; 
        wp_send_json($json );
    }
    $ltype_id = isset($_POST['ltype_id'])? $_POST['ltype_id'] : 0;
    // $ltype_id = $ltype_id ? : townhub_addons_get_option('default_listing_type');
    $allow_types = Esb_Class_Membership::author_listing_types_ids();
    
    if(empty($allow_types) || ( absint($ltype_id) > 0 && !in_array($ltype_id, $allow_types) ) ){
        $json['post']['listing_type_id'] = -1;
        $json['error'] = __( 'You are not allowed to submit listing to any type. Please order an author membership to start submit listing.', 'townhub-add-ons' ) ; 
        wp_send_json($json );
    }elseif( $ltype_id == 0 && townhub_addons_get_option('must_select_ltype') != 'yes' ){
        // $dftype = esb_addons_get_wpml_option('default_listing_type', 'listing_type');
        // if(in_array($dftype, $allow_types)){
        //     $ltype_id = $dftype;
        // }else{
        //     $ltype_id = reset($allow_types);
        // }
        $ltype_id = Esb_Class_Membership::default_ltype();
    }
    if(Esb_Class_Membership::can_add() == false){
        $json['post']['listing_type_id'] = -1;
        
        $json['error'] = __( 'You are not allowed to submit listing. Your author subscription has expired or listing limitation exceeded.', 'townhub-add-ons' ) ; 
        wp_send_json($json );
    }

    

    


    // get listing fields
    if( !empty($ltype_id) ){
        $json['post']['listing_type_id'] = $ltype_id;
        $json['fields'] = townhub_addons_get_listing_type_fields_obj( $ltype_id, true , true, true, true);
        $json['rFields'] = townhub_addons_get_rooms_type_fields_obj( $ltype_id, true, true );
    }

    // default listing timezone
    $json['post']['working_hours'] = Esb_Class_Listing_CPT::wkhours_add();
    $json['post']['locations'] = townhub_addons_get_option('default_country');
    
    $json['titles']['add_room_title'] = get_post_meta( $ltype_id, ESB_META_PREFIX.'add_room_title', true );
    $json['titles']['edit_room_title'] = get_post_meta( $ltype_id, ESB_META_PREFIX.'edit_room_title', true );

    // if(isset($_POST['for_editing']) && $_POST['for_editing'])
    //     $json['isEditing'] = true;
    // else
        $json['isAdding'] = true;
    
    $json['success'] = true;
    wp_send_json($json );

}


//delete listing
add_action('wp_ajax_nopriv_townhub_addons_delete_listing', 'townhub_addons_delete_listing_callback');
add_action('wp_ajax_townhub_addons_delete_listing', 'townhub_addons_delete_listing_callback');

function townhub_addons_delete_listing_callback() { 
    $json = array(
        'success' => false,
        'data' => array(
            // 'POST'=>$_POST,
        ),
        'post'	=> array(
        	'ID'	=> 0
        )
    );
    

    $nonce = $_POST['_nonce'];
    
    if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
        $json['data']['error'] = esc_html__( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ;
        wp_send_json($json );
    }



    $lid = isset($_POST['lid'])? $_POST['lid'] : 0;
    if(is_numeric($lid) && (int)$lid > 0){
        if( !current_user_can('delete_post', $lid) ){
            $json['data']['error'] = esc_html__( 'You have no permission to delete this post', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }
        
        $deleted_post = wp_delete_post( $lid, false );//move to trash

        if($deleted_post){
            $json['success'] = true;
            $json['post'] = $deleted_post;
            // update order/subscription listings data
            $listing_order = get_post_meta( $lid,  ESB_META_PREFIX.'order_id', true );
            if(is_numeric($listing_order) && (int)$listing_order > 0){

                // check for existing listings item
                $order_listings = get_post_meta( $listing_order, ESB_META_PREFIX.'listings', true );
                if(is_array($order_listings) && !empty($order_listings)){
                    if (($key = array_search($lid, $order_listings)) !== false) {
                        unset($order_listings[$key]);
                        update_post_meta( $listing_order, ESB_META_PREFIX.'listings', $order_listings );
                    }
                }


                update_post_meta( $lid, ESB_META_PREFIX.'order_id', '' );
            }
            // set expire_date to current date
            update_post_meta( $lid, ESB_META_PREFIX.'expire_date', current_time('mysql', 1) );


        }else{
            // $json['success'] = false;
            $json['data']['error'] = esc_html__( 'Delete listing failure', 'townhub-add-ons' ) ;
        }
    }else{
        // $json['success'] = false;
        $json['data']['error'] = esc_html__( 'The post id is incorrect.', 'townhub-add-ons' ) ;
    }

    wp_send_json($json );

}
//---------------get field room type-----------------//
add_action('wp_ajax_nopriv_townhub_addons_get_field_room_type', 'townhub_addons_get_field_room_type_callback');
add_action('wp_ajax_townhub_addons_get_field_room_type', 'townhub_addons_get_field_room_type_callback');

function townhub_addons_get_field_room_type_callback() { 
    $json = array(
        'success' => false,
        'data' => array(
            // 'POST'=>$_POST,
        ),
        // 'post'      => array(),
        'fields'    => array(),
    );
    $nonce = $_POST['_nonce'];
    if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
        $json['data']['error'] = __( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ; 
        wp_send_json($json );
    }
    $ltype_id = isset($_POST['ltype_id'])? $_POST['ltype_id'] : 0;
    // $json['post']['listing_type_id'] =  $ltype_id ? $ltype_id : esb_addons_get_wpml_option('default_listing_type', 'listing_type');

    // get room fields
    $json['fields'] = townhub_addons_get_rooms_type_fields_obj( $ltype_id );
    $json['success'] = true;
    wp_send_json($json );

}





//============= get single type room ==============//
 
add_action('wp_ajax_nopriv_townhub_addons_get_single_type', 'townhub_addons_get_single_type_callback');
add_action('wp_ajax_townhub_addons_get_single_type', 'townhub_addons_get_single_type_callback');

function townhub_addons_get_single_type_callback() {
    $json = array(
        'success' => false,
        'data' => array(
            // 'POST'=>$_POST,
        ),
        'post' =>array(),
    );

    $nonce = $_POST['_nonce'];
    if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
        $json['data']['error'] = __( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ; 
        wp_send_json($json );
    }
    $id = isset($_POST['user_id'])? $_POST['user_id'] : 0;
    if( is_numeric($id) && $id > 0 ){   
    $listing_type = get_posts( array(
        'post_type' => 'listing_type',
        'posts_per_page' => -1, 
        'author'        =>  $id, 
        'post_status' =>'any', 

    ) );
    $tylisting = array(array(
            'ID'    => '0',
            'title'    => __( 'None', 'townhub-add-ons' ),
        ));
    foreach ($listing_type as $ID) {
        $tylisting[] = array(
            'ID'    => $ID->ID,
            'title'    => get_the_title($ID),
        );
    }

    $json['post'] = $tylisting;


    }else{
         $json['data']['error'] = __( 'The author id is incorrect.', 'townhub-add-ons' ) ;
         wp_send_json($json );
    }
    $json['success'] = true;
    wp_send_json($json );

}
add_action('wp_ajax_nopriv_townhub_addons_get_edit_room', 'townhub_addons_get_edit_room_callback');
add_action('wp_ajax_townhub_addons_get_edit_room', 'townhub_addons_get_edit_room_callback');

function townhub_addons_get_edit_room_callback() {
    $json = array(
        'success' => false,
        'data' => array(
            // 'POST'=>$_POST,
        ),
        'rFields'   => array(),
        'rpost'     => array(),
    );
    $nonce = $_POST['_nonce'];
    if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
        $json['data']['error'] = __( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ; 
        wp_send_json($json );
    }
    $rid = isset($_POST['rid'])? $_POST['rid'] : 0;
    // $lid = isset($_POST['listing_id'])? $_POST['listing_id'] : 0;
    $listing_id = get_post_meta( $rid, ESB_META_PREFIX.'for_listing_id', true );

    $listing_type_id = get_post_meta( $listing_id, ESB_META_PREFIX.'listing_type_id', true );
    // $json['data']['ltid'] =  $listing_type_id ;
    if( $listing_type_id == '' ) $listing_type_id = esb_addons_get_wpml_option('default_listing_type', 'listing_type');


    
    $json['rFields'] = townhub_addons_get_rooms_type_fields_obj( $listing_type_id );
    $user_id = isset($_POST['user_id'])? $_POST['user_id'] : 0;
    if($user_id == false) $user_id = get_current_user_id();
    if( is_numeric($user_id) && $user_id > 0 ){
        if( ! user_can( $user_id, 'edit_post' , $rid ) ){
            $json['data']['error'] = __( "You don't have permission to edit this listing.", 'townhub-add-ons' ) ;
            wp_send_json( $json );
        }
        $lpost = get_posts( array(
            'post_type'     =>  'lrooms', 
            'p' => $rid,
            'post_status'   => array('publish', 'pending'),
        ) );
        if(!$lpost){
            $json['data']['error'] = __( "The editing listing is incorrect.", 'townhub-add-ons' ) ;
            wp_send_json( $json );
        }else{
            $json['success'] = true;

            $json['rpost'] = array(
                'rid'                       => $rid,
                'title'                     => get_the_title( $rid ),
                'content'                   => apply_filters('the_content', get_post_field('post_content', $rid) ),
                'thumbnail'                 => get_post_thumbnail_id($rid),
                'for_listing_id'            => get_post_meta( $rid, ESB_META_PREFIX.'for_listing_id', true ),
                '_price'                    => get_post_meta( $rid, '_price', true ),
                'post_excerpt'              => get_the_excerpt( $rid ),
            );

            foreach((array)townhub_addons_get_listing_type_fields_meta( $listing_type_id , true) as $fname => $ftype){
                $json['rpost'][$fname] = get_post_meta( $rid, ESB_META_PREFIX.$fname, true );
                // if($ftype === 'calendar_metas'){
                //     $json['rpost'][$fname .'_metas'] = get_post_meta( $rid, ESB_META_PREFIX.$fname.'_metas', true );
                // }
            }
        }  
    }else{
        $json['data']['error'] = __( 'The author id is incorrect.', 'townhub-add-ons' ) ;
        wp_send_json($json );
    }
    
    wp_send_json($json );

}
add_action('wp_ajax_nopriv_townhub_addons_get_room_fields', 'townhub_addons_get_room_fieldscallback');
add_action('wp_ajax_townhub_addons_get_room_fields', 'townhub_addons_get_room_fields_callback');

function townhub_addons_get_room_fields_callback() {
    $json = array(
        'success' => false,
        'data' => array(
            // 'POST'=>$_POST,
        ),
        'rFields'   => array(),
        'rpost'     => array(),
    );
    $nonce = $_POST['_nonce'];
    if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
        $json['error'] = __( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ; 
        wp_send_json($json );
    }
    $listing_id = isset($_POST['ltype_id'])? $_POST['ltype_id'] : 0; // get listing id room attached to
    $listing_type_id = get_post_meta( $listing_id, ESB_META_PREFIX.'listing_type_id', true );
    if( $listing_type_id == '' ) $listing_type_id =  esb_addons_get_wpml_option('default_listing_type', 'listing_type');
    // $allow_types = Esb_Class_Membership::author_listing_types();
    // if(!empty($allow_types)){
    //     $allow_types = array_map(function($type){
    //         return $type['ID'];
    //     }, $allow_types);
    // }
    // if(empty($allow_types) || !in_array($listing_type_id, $allow_types)){
    //     $json['post']['listing_type_id'] = -1;
    //     $json['error'] = __( 'You are not allowed to submit listing to any type. Please order an author membership to start submit listing.', 'townhub-add-ons' ) ; 
    //     wp_send_json($json );
    // }
    // $json['post']['listing_type_id'] =  $listing_type_id ? $listing_type_id : esb_addons_get_wpml_option('default_listing_type', 'listing_type');

    // get listing fields
    // $json['fields'] = townhub_addons_get_listing_type_fields_obj( $listing_type_id );
    $json['rFields'] = townhub_addons_get_rooms_type_fields_obj( $listing_type_id );

    $json['success'] = true;
    wp_send_json($json );

}
add_action('wp_ajax_nopriv_townhub_addons_get_edit_woo', 'townhub_addons_get_edit_woo_callback');
add_action('wp_ajax_townhub_addons_get_edit_woo', 'townhub_addons_get_edit_woo_callback');

function townhub_addons_get_edit_woo_callback() {
    $json = array(
        'success' => false,
        'data' => array(
            // 'POST'=>$_POST,
        ),
        'rFields'   => array(),
        'rpost'     => array(),
        'debug'     => false
    );
    $nonce = $_POST['_nonce'];
    if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
        $json['data']['error'] = __( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ; 
        wp_send_json($json );
    }
    $wid = isset($_POST['wid'])? $_POST['wid'] : 0;
    // $lid = isset($_POST['listing_id'])? $_POST['listing_id'] : 0;
    $listing_id = get_post_meta( $wid, ESB_META_PREFIX.'for_listing_id', true );

    $listing_type_id = get_post_meta( $listing_id, ESB_META_PREFIX.'listing_type_id', true );
    // $json['data']['ltid'] =  $listing_type_id ;
    if( $listing_type_id == '' ) $listing_type_id =  esb_addons_get_wpml_option('default_listing_type', 'listing_type');
    $json['rFields'] = townhub_addons_get_rooms_type_fields_obj( $listing_type_id );
    $user_id = isset($_POST['user_id'])? $_POST['user_id'] : 0;
    if($user_id == false) $user_id = get_current_user_id();
    if( is_numeric($user_id) && $user_id > 0 ){
        // if( ! user_can( $user_id, 'edit_post' , $wid ) ){
        //     $json['data']['error'] = __( "You don't have permission to edit this listing.", 'townhub-add-ons' ) ;
        //     wp_send_json( $json );
        // }
        $lpost = get_posts( array(
            'post_type'     =>  'product', 
            'p' => $wid,
            'post_status'   => array('publish', 'pending'),
        ) );
        if(!$lpost){
            $json['data']['error'] = __( "The editing listing is incorrect.", 'townhub-add-ons' ) ;
            wp_send_json( $json );
        }else{
            $json['success'] = true;

            $json['rpost'] = array(
                'wid'                       => $wid,
                'title'                     => get_the_title( $wid ),
                'content'                   => apply_filters('the_content', get_post_field('post_content', $wid) ),
                'thumbnail'                 => get_post_thumbnail_id($wid),
                'for_listing_id'            => get_post_meta( $wid, ESB_META_PREFIX.'for_listing_id', true ),
                'post_excerpt'              => get_the_excerpt( $wid ),
                '_price'                    => get_post_meta( $wid, '_price', true ),
            );

            foreach((array)townhub_addons_get_listing_type_fields_meta( $listing_type_id , true) as $fname => $ftype){
                $json['rpost'][$fname] = get_post_meta( $wid, ESB_META_PREFIX.$fname, true );
                // if($ftype === 'calendar_metas'){
                //     $json['rpost'][$fname .'_metas'] = get_post_meta( $wid, ESB_META_PREFIX.$fname.'_metas', true );
                // }
            }
        }  
    }else{
        $json['data']['error'] = __( 'The author id is incorrect.', 'townhub-add-ons' ) ;
        wp_send_json($json );
    }
    
    wp_send_json($json );

}

// featured listing
add_action('wp_ajax_nopriv_townhub_addons_featured_listing', 'townhub_addons_featured_listing_callback');
add_action('wp_ajax_townhub_addons_featured_listing', 'townhub_addons_featured_listing_callback');

function townhub_addons_featured_listing_callback() {
    $json = array(
        'success' => false,
        'debug' => false,
        // 'data' => array(
        //     'POST'=>$_POST,
        // )
    );
    

    $nonce = $_POST['_nonce'];
    
    if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
        
        $json['data']['error'] = esc_html__( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ;
        wp_send_json($json );
    }


    $lid = isset($_POST['lid'])? $_POST['lid'] : 0;
    if(is_numeric($lid) && (int)$lid > 0){

        if( !current_user_can('delete_post', $lid) ){
            $json['data']['error'] = esc_html__( 'You have no permission to featured this listing', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }

        
        $lfeatured = isset($_POST['lfeatured'])? $_POST['lfeatured'] : false;
        // $listing_order = get_post_meta( $lid,  ESB_META_PREFIX.'order_id', true );

        // $json['listing_order'] = $listing_order;

        if($lfeatured){ // unfeatured listing
            update_post_meta( $lid, ESB_META_PREFIX.'featured', '0' );
            $json['success'] = true;
        }else{ // featured listing
            $author_id = get_current_user_id();
            $plan_id = Esb_Class_Membership::current_plan($author_id);
            $featured_limit = get_post_meta( $plan_id, ESB_META_PREFIX.'lfeatured', true );
            $json['plan_id'] = $plan_id;
            $json['featured_limit'] = $featured_limit;
            if(is_numeric($featured_limit) && $featured_limit > 0){
                $author_featured = get_posts(
                    array(
                        'post_type'         => 'listing',
                        'post_status'       => array( 'publish', 'pending' ),
                        'author'            => $author_id,
                        'meta_key'          => ESB_META_PREFIX.'featured',
                        'meta_value'        => '1', 
                        'posts_per_page'    => -1,
                        'fields'            => 'ids'
                    )
                );
                $json['author_featured'] = $author_featured;
                if(in_array($lid, $author_featured)){
                    $json['data']['error'] = esc_html__( 'Listing was already featured', 'townhub-add-ons' ) ;
                }else{
                    if((int)$featured_limit > count($author_featured)){
                        update_post_meta( $lid, ESB_META_PREFIX.'featured', '1' );
                        $json['success'] = true;
                        $json['data'][] = esc_html__( 'Listing is featured', 'townhub-add-ons' ) ;
                    }else{
                        $json['data']['error'] = esc_html__( 'Your subscription hit featured listing limit', 'townhub-add-ons' ) ;
                    }
                }
            }else{
                $json['data']['error'] = esc_html__( 'Your author subscription has no featured listings or hit the limit', 'townhub-add-ons' ) ;
            }
        }
    }else{
        // $json['success'] = false;
        $json['data']['error'] = esc_html__( 'The post id is incorrect.', 'townhub-add-ons' ) ;
    }

    wp_send_json($json );

}

add_action('wp_ajax_admin_lverified', 'townhub_addons_admin_lverified_callback');
function townhub_addons_admin_lverified_callback(){
    $json = array(
        'success' => false,
        'data' => array(
            'POST'=>$_POST,
        )
    );

    $lid = isset($_POST['lid'])? $_POST['lid'] : 0;
    if(is_numeric($lid) && (int)$lid > 0){
        $lverified = isset($_POST['lverified']) && $_POST['lverified'] ? false : true;
        if(update_post_meta( $lid, ESB_META_PREFIX.'verified', $lverified )) $json['success'] = true;
    }else{
        // $json['success'] = false;
        $json['data']['error'] = esc_html__( 'The post id is incorrect.', 'townhub-add-ons' ) ;
    }

    wp_send_json($json );

}
