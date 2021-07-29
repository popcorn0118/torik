<?php
/* add_ons_php */


$cth_listing_types_imported = array();
$cth_listing_posts_imported = array();

add_action( 'wp_import_insert_post', function($post_id, $original_post_ID){
    global $cth_listing_types_imported, $cth_listing_posts_imported;
    // re-maping post id for listing type
    if( get_post_type( $post_id ) == 'listing_type' ){
        $cth_listing_types_imported[$original_post_ID] = $post_id;
    }else if( get_post_type( $post_id ) == 'listing' ){
        $cth_listing_posts_imported[$original_post_ID] = $post_id;
    }

}, 10, 2 );

// do_action( 'wp_import_insert_post', $post_id, $original_post_ID, $postdata, $post );


function townhub_addons_after_import_setup() {
    global $cth_listing_types_imported, $cth_listing_posts_imported;

    // error_log( 'listing types: '. json_encode($cth_listing_types_imported) . PHP_EOL );
    // error_log( 'listings: '. json_encode($cth_listing_posts_imported) . PHP_EOL );

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    if($main_menu){
        set_theme_mod( 'nav_menu_locations', array(
                'top' => $main_menu->term_id,
            )
        );
    }

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home Parallax Image' );
    $blog_page_id  = get_page_by_title( 'News' );

    update_option( 'show_on_front', 'page' );
    if( null !== $front_page_id ) update_option( 'page_on_front', $front_page_id->ID );
    if( null !== $blog_page_id ) update_option( 'page_for_posts', $blog_page_id->ID );

    // update listing's listing type id
    $listings = get_posts( array(
        'fields'            => 'ids', 
        'posts_per_page'    => -1, 
        'post_type'         => 'listing',
        'post_status'       => 'publish',
    ) );
    if ( $listings ) {
        foreach ( $listings as $lid ) {
            $old_ltype = get_post_meta( $lid, '_cth_listing_type_id', true );
            if( isset( $cth_listing_types_imported[$old_ltype] ) ){
                update_post_meta( $lid, '_cth_listing_type_id', $cth_listing_types_imported[$old_ltype] );
            }

            // disable ads
            update_post_meta( $lid, ESB_META_PREFIX. 'is_ad', 'no' );

            $working_hours_meta = get_post_meta( $lid, ESB_META_PREFIX.'working_hours_meta', true );

            Esb_Class_Listing_CPT::update_working_hours( $lid, $working_hours_meta );

        }
    }
    // update lrooms's listing id
    $lrooms = get_posts( array(
        'fields'            => 'ids', 
        'posts_per_page'    => -1, 
        'post_type'         => 'lrooms',
        'post_status'       => 'publish',
    ) );
    if ( $lrooms ) {
        foreach ( $lrooms as $rid ) {
            $old_lid = get_post_meta( $rid, '_cth_for_listing_id', true );
            if( isset( $cth_listing_posts_imported[$old_lid] ) ){
                update_post_meta( $rid, '_cth_for_listing_id', $cth_listing_posts_imported[$old_lid] );
            }
        }
    }
    
    // update listing type css
    // $azp_csses = get_option( 'azp_csses', array() );
    // foreach ($cth_listing_types_imported as $ltold => $ltnew) {
    //     if(isset($azp_csses[$ltold])){
    //         $azp_csses[$ltnew] = $azp_csses[$ltold];
    //         unset($azp_csses[$ltold]);
    //     } 
    // }

    // update_option( 'azp_csses', $azp_csses );
    // $upload_path = townhub_addons_upload_dirs('azp', 'css');
    // $css_file = $upload_path . DIRECTORY_SEPARATOR . "listing_types.css";
    // if(file_exists($css_file))
    //     @file_put_contents($css_file, Esb_Class_Listing_Type_CPT::get_azp_css() );

    // update add-ons option
    $dfltype = get_page_by_title( 'Places', OBJECT, 'listing_type' );
    if( null !== $dfltype ){
        $exists_options = get_option( 'townhub-addons-options', array() );
        $exists_options['default_listing_type'] = $dfltype->ID;
        update_option( 'townhub-addons-options', $exists_options );
    }

    // update term count
    foreach (['listing_cat','listing_location','listing_feature','listing_tag'] as $tax) {
        $get_terms_args = array(
            'taxonomy' => $tax,
            'fields' => 'ids',
            'hide_empty' => false,
        );

        $terms = get_terms($get_terms_args);
        // error_log(json_encode($terms));
        if( ! empty( $terms ) && ! is_wp_error( $terms ) && is_array($terms) ){
            wp_update_term_count_now($terms, $tax);
        }
    }
}
add_action( 'pt-ocdi/after_import', 'townhub_addons_after_import_setup' );
