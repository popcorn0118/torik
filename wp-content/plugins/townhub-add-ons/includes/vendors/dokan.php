<?php 
/* add_ons_php */

add_filter( 'dokan_new_product_popup_args', function($error, $data){
	$is_updating = false;
	if ( ! empty( $data['ID'] ) ) {
        $post_arr['ID'] = absint( $data['ID'] );
        if ( function_exists('dokan_is_product_author') && ! dokan_is_product_author( $post_arr['ID'] ) ) {
            return new WP_Error( 'not-own', __( 'I swear this is not your product!', 'townhub-add-ons' ) );
        }
        $is_updating = true;
    }
    if( false == $is_updating ){
    	$user_id = get_current_user_id();
    	$woo_limit = get_user_meta( $user_id, ESB_META_PREFIX.'woo_limit',  true );
    	if( $woo_limit === '' ) $woo_limit = 10;
    	$ltPosts = get_posts(array(
            'fields'                => 'ids',
            'post_type'             => 'product',
            'author'                => $user_id,
            'posts_per_page'        => -1,
            'post_status'           => array('publish', 'pending', 'private'), // publish, future, draft, pending, private, trash, auto-draft, inherit
            'suppress_filters'      => false,
        ));
        if( count( $ltPosts ) >= (int)$woo_limit ){
        	return new WP_Error( 'plan-limit', __( 'Your products submission limit has been reached. Please upgrade your subscription plan.', 'townhub-add-ons' ) );
        }
    }
    return $error;
}, 10, 2 );