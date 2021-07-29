<?php 
/* add_ons_php */

defined( 'ABSPATH' ) || exit;
if(!isset($townhub_addons_options)) $townhub_addons_options = get_option( 'townhub-addons-options', array() );
function townhub_addons_get_option( $setting, $default = null ) {
    global $townhub_addons_options; 

    $default_options = array(
        'users_can_submit_listing'              => 'no',

        'maintenance_mode'                      => 'disable', 
        'listings_count'                        => '6',

        'map_pos'                               => 'right',
        'filter_pos'                            => 'left_col',
        'columns_grid'                          => 'two',

        'listing_location_result_type'          => 'administrative_area_level_1', 
        'country_restrictions'                  => '',

        'gmap_marker'                           => array(
            'url' => '',
            'id' => ''
        ),

        'payments_stripe_use_email'             => 'yes',

        'membership_package_expired_hide'       => 'no',
        // 'membership_single_expired_hide'        => 'no',

        // default submit listing will expired in days
        'listing_expire_days'                   => 30,

        'submit_redirect'                       => 'single',

        'single_show_rating'                    => '1',
        'rating_base'                           => 5,

        'gmap_default_lat'                      => '40.7',
        'gmap_default_long'                     => '-73.87',

        'enable_img_click'                      => 'no',
        'vat_tax'                               => 10,
        'auto_active_free_sub'                  => 'no',
        'search_include_tag'                    => 'no',
        'search_tax_relation'                   => 'AND',

        'ads_archive_enable'                    => 'yes',
        'ads_archive_count'                     => '2',
        'ads_archive_orderby'                   => 'date',
        'ads_archive_order'                     => 'DESC',

        'ads_category_enable'                   => 'yes',
        'ads_category_count'                    => '2',
        'ads_category_orderby'                  => 'date',
        'ads_category_order'                    => 'DESC',

        'ads_search_enable'                     => 'yes',
        'ads_search_count'                      => '2',
        'ads_search_orderby'                    => 'date',
        'ads_search_order'                      => 'DESC',

        'ads_sidebar_enable'                    => 'yes',
        'ads_sidebar_count'                     => '2',
        'ads_sidebar_orderby'                   => 'date',
        'ads_sidebar_order'                     => 'DESC',

        'ads_home_enable'                       => 'yes',
        'ads_home_count'                        => '2',
        'ads_home_orderby'                      => 'date',
        'ads_home_order'                        => 'DESC',

        'ads_custom_grid_enable'                => 'yes',
        'ads_custom_grid_count'                 => '2',
        'ads_custom_grid_orderby'               => 'date',
        'ads_custom_grid_order'                 => 'DESC',

        'listings_grid_layout'                  => 'grid',
        
        'submit_timezone_hide'                  => 'no',

        'use_clock_24h'                         => 'yes',

        'free_submit_page'                      => 'default',

        'new_user_email'                        => 'both',
        'register_auto_login'                   => 'no',
        'register_role'                         => 'no',   
        'register_term_text'                    => 'By using the website, you accept the terms and conditions',
        'register_consent_data_text'            => 'Consent to processing of personal data',
        
        'search_cat_level'                      => '0',
        'search_load_subcat'                    => 'yes',

        'gmap_default_zoom'                     => 10,
        'always_show_submit'                    => 'yes',

        'emails_section_customer_booking_insert_enable'     => 'yes',
        'emails_section_customer_booking_insert_subject'    => '',
        'emails_section_customer_booking_insert_temp'       => '',

        'emails_section_customer_booking_approved_enable'     => 'yes',
        'emails_section_customer_booking_approved_subject'    => '',
        'emails_section_customer_booking_approved_temp'       => '',

        'booking_clock_24h'                     => 'yes',
        'time_picker_color'                     => '#4DB7FE',
        'add_cart_delay'                        => 3000,

        'booking_author_woo'                    => 'no',

        'submit_media_limit'                    => 3,
        'submit_media_limit_size'               => 2,

        'register_password'                     => 'no',
        'enable_g_recaptcah'                    => 'no',
        'g_recaptcha_site_key'                  => '',
        'g_recaptcha_secret_key'                => '',

        'listings_orderby'                      => 'date',
        'listings_order'                        => 'DESC',

        'db_hide_messages'                      => 'no',
        'db_hide_packages'                      => 'no',
        'db_hide_ads'                           => 'no',
        'db_hide_invoices'                      => 'no',
        'db_hide_bookings'                      => 'no',
        'db_hide_bookmarks'                     => 'no',
        'db_hide_reviews'                       => 'no',
        'db_hide_adnew'                         => 'no',
        'db_hide_withdrawals'                   => 'no',

        'grid_wkhour'                           => 'yes',
        'grid_price'                            => 'yes',
        'grid_price_range'                      => 'yes',
        'grid_viewed_count'                     => 'yes',

        'listing_event_date'                    => 'yes',

        'feature_parent_group'                  => 'yes',

        // 'submit_hide_content_head'              => 'no',
        // 'submit_hide_head_background'           => 'no',
        // 'submit_hide_head_carousel'             => 'no',
        // 'submit_hide_head_video'                => 'no',
        // 'submit_hide_content_video'             => 'no',
        // 'submit_hide_content_gallery'           => 'no',
        // 'submit_hide_content_slider'            => 'no',
        // 'submit_hide_price_opt'                 => 'no',
        // 'submit_hide_faqs_opt'                  => 'no',
        // 'submit_hide_counter_opt'               => 'no',
        // 'submit_hide_workinghours_opt'          => 'no',
        // 'submit_hide_socials_opt'               => 'no',

        'default_thumbnail'                     => ESB_DIR_URL .'assets/images/16.jpg',

        'filter_hide_string'                    => 'no',
        'filter_hide_loc'                       => 'no',
        'filter_hide_cat'                       => 'no',
        'filter_hide_address'                   => 'no',
        'filter_hide_event_date'                => 'no',
        'filter_hide_event_time'                => 'no',
        'filter_hide_open_now'                  => 'no',
        'filter_hide_price_range'               => 'no',
        'filter_hide_sortby'                    => 'no',


        'admin_bar_front'                       => 'no',

        // 'single_hide_contacts_info'             => 'no',
        // 'single_hide_booking_form_widget'       => 'no',
        // 'single_hide_addfeatures_widget'        => 'no',
        // 'single_hide_contacts_widget'           => 'no',
        // 'single_hide_author_widget'             => 'no',
        // 'single_hide_moreauthor_widget'         => 'no',


        'listing_address_format'                => 'formatted_address',
        'google_map_language'                   => '',
        'hide_sub_cats'                         => 'no',
        'allow_rating_imgs'                     => 'yes',
        // 'single_hide_weather_widget'            => 'no',

        'single_post_nav'                       => 'yes',
        'single_same_term'                      => '0',
        'filter_features'                       => array(),

        'register_no_redirect'                  => 'yes',
        'filter_ltags'                          => array(),

        'login_redirect_page'                   => 'cth_current_page',

        'emails_auth_claim_subject'             => '',
        'emails_auth_claim_temp'                => '',

        'approve_claim_after_paid'              => 'yes',

        'distance_min'                          => 2,
        'distance_max'                          => 20,
        'distance_df'                           => 5,

        'booking_approved_cancel'               => 'yes',

        'booking_author_delete'                 => 'no',
        'booking_del_trash'                     => 'no',

        'admin_bar_hide_roles'                  => array('l_customer','listing_author','subscriber','contributor','author'),


        'emails_admin_new_claim_enable'         => 'yes',
        'emails_admin_new_claim_recipients'     => '',
        'emails_admin_new_claim_subject'        => '',
        'emails_admin_new_claim_temp'           => '',

        'emails_auth_new_claim_enable'          => '',
        'emails_auth_new_claim_subject'         => '',
        'emails_auth_new_claim_temp'            => '',
        'messages_first_load'                   => 10,
        'messages_prev_load'                    => 5,

        'all_info_for_admin'                    => 'yes',

        'admin_lplan'                           => 0,
        'free_lplan'                            => 0,

        'use_osm_map'                           => 'no',
        'gmap_single_zoom'                      => 16,
        'gmap_type'                             => 'ROADMAP',

        'logreg_form_before'                    => '',
        'logreg_form_after'                     => '<p>For faster login or register use your social account.</p>[fbl_login_button redirect="" hide_if_logged="" size="large" type="continue_with" show_face="true"]',
        'checkout_success_page'                 => 'none',
        'checkout_redirect_after_add'           => 'yes',
        'booking_vat_include_fee'               => 'no',
        'checkout_individual'                   => 'yes',

        'checkout_success'                      => 'default',
        'checkout_success_redirect'             => 'yes',
        'listings_per'                          => 5,
        'site_owner_id'                         => 0,
        'chat_site_owner'                       => 'yes',

        'ck_hide_tabs'                          => 'no',
        'ck_hide_information'                   => 'yes',
        'ck_hide_billing'                       => 'no',
        'ck_hide_payments'                      => 'no',
        'ck_agree_terms'                        => 'no',
        'ck_terms'                              => '',

        'login_delay'                           => 5000,

        'azp_cache'                             => 'no',
        'azp_css_external'                      => 'yes',
        'lazy_load'                             => 'yes',

        'payfast_rate'                          => '13.9893',
        'log_reg_dis_nonce'                     => 'no',
        'default_country'                       => 'US',
        'currencies'                            => array(),

        'admin_chat'                          => 'yes',

        'currency_symbol'                       => '$',
        'show_fchat'                            => 'yes',

        'llayout'                               => 'column-map',
        'show_lheader'                          => 'yes',
        'lheader_title'                         => 'Our Listings',
        'lheader_bg'                            => '',
        'service_fee'                            => 5,
        'show_score_rating'                     => '1',

        'ck_need_logged_in'                     => true,
        'ck_book_logged_in'                     => false,

        'show_chart'                            => 'yes',
        'show_stats'                            => 'yes',
        'chart_hide_views'                      => 'no',
        'chart_hide_earning'                    => 'no',
        'chart_hide_booking'                    => 'yes',

        'delete_user'                           => 'no',
        'off_avatar'                            => 'yes',
        'df_avatar'                             => '',
        'map_card_hide_status'                  => 'no',
        'invoice_from'                          => 'TownHub , Inc.<br>
USA 27TH Brooklyn NY<br>
<a href="#" style="color:#666; text-decoration:none">JessieManrty@domain.com</a>
<br>
<a href="#" style="color:#666; text-decoration:none">+4(333)123456</a>',
        'approve_booked_comment'                => 'no',
        'use_dfmarker'                          => 'no',
        'register_as_author'                    => 'no',
        'bk_count_status'                       => array('completed'),
        'bk_show_status'                        => array(),
        'woo_redirect'                          => 'yes',
        'woo_redirect_zero'                     => 'no',
        'forget_pwd_email'                      => '',
        'distance_miles'                        => 'no',
        'woo_for_ads'                           => 'no',
        'withdrawal_min'                        => 10,
        'address_length'                        => 45,
        'hide_past_events'                      => 'no',
        'cats_num'                              => 5,
        'logreg_form_title'                     => 'Welcome to <span><strong>Town</strong>Hub<strong>.</strong></span>',
        'login_pattern'                         => '^[A-Za-z\d\.]{6,}$',
        'login_pat_desc'                        => 'You can use letters, numbers and periods and at least 6 characters or more',
        'pwd_pattern'                           => '^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d$@$!%*?&]{8,}$',
        'pwd_pat_desc'                          => 'Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters',
        'dis_log_reg_modal'                     => 'no',
        'map_card_hide_author'                  => 'yes',
        'free_plan_invoice'                     => 'no',
        'map_provider'                          => 'osm',
        'cookie_provider'                       => 'none',
        'umenu_earnings'                        => 'no',
        'pt_public_booking'                     => 'no',
        'pt_public_withdrawal'                  => 'no',
        'free_redirect_submit'                  => 'no',
        'pending_editing_listing'               => 'no',
        'lauthor_archive_for'                   => array('administrator','listing_author','author'),
        'report_must_login'                     => 'yes',
        'must_select_ltype'                     => 'no',
        'author_role'                           => 'listing_author',
        'week_starts_monday'                    => 'yes',
        'separate_inquiries'                    => 'no',
        'publish_not_pending'                   => 'no',
        'use_ltype_search'                      => 'yes',
        'use_ltype_filter'                      => 'yes',
        'withdrawal_date'                       => 15,
        'fevent_exact'                          => 'no',
        'dbheader_hide_circle'                  => 'no',
        'submit_remove_deleted_imgs'            => 'no',
    );
    $value = false;
    if ( isset( $townhub_addons_options[ $setting ] ) ) {
        $value = $townhub_addons_options[ $setting ];
    }else {
        if(isset($default)){
            $value = $default;
        }else if( isset( $default_options[ $setting ] ) ){
            $value = $default_options[ $setting ];
        }
    }

    
    return apply_filters( 'cth_addons_option_value', $value, $setting );
}

function esb_addons_get_wpml_option($name='', $type='page', $default = null){

    $option_value    = townhub_addons_get_option($name, $default);
    if( is_numeric($option_value) ){
        return apply_filters( 'wpml_object_id', $option_value, $type, true );
    }
    return $option_value;
}


function esb_setcookie($name, $value, $expire = 0, $secure = false, $raw = false )
{
    // $ob = ini_get('output_buffering'); 
    // var_dump($ob);
    // die;
    if (!headers_sent()) {
        $path = COOKIEPATH ? COOKIEPATH : '/'; // SITECOOKIEPATH != COOKIEPATH ? SITECOOKIEPATH : COOKIEPATH;
        if( $raw === true ){
            setrawcookie($name, $value, $expire, $path, COOKIE_DOMAIN, $secure);
        }else{
            setcookie($name, $value, $expire, $path, COOKIE_DOMAIN, $secure);
        }
        
    }elseif ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        headers_sent( $file, $line );
        trigger_error( "{$name} cookie cannot be set - headers already sent by {$file} on line {$line}", E_USER_NOTICE );
    }
}

// add_filter( 'cth_addons_option_value', function($value, $name){
//     if( $name == 'map_pos' && is_tax( 'listing_cat' ) &&  get_queried_object_id() == 100 ){
//         $value = 'hide';
//     }
//     return $value;
// }, 10, 2 );

// $GLOBALS['townhub_ads'] = array();

/* add edit listing var */
function townhub_addons_add_query_vars_filter( $vars ){   
  $vars[] = "listing_id";
  $vars[] = "dashboard";
  // $vars[] = "ls_type";
  return $vars;
}
add_filter( 'query_vars', 'townhub_addons_add_query_vars_filter' );  

// filter document_title_parts for dasboard subpages

function townhub_addons_document_title_parts_filter( $title ){
    $dashboard_page_id = esb_addons_get_wpml_option('dashboard_page');       
    $dashboard_var = get_query_var('dashboard');
    if( is_page($dashboard_page_id) && $dashboard_var != '' ){
        $title['title'] = sprintf(_x( '%s - %s', 'Site title separator', 'townhub-add-ons' ), Esb_Class_Dashboard::subpage($dashboard_var), $title['title'] ) ;  
    }
    return $title;
}
add_filter( 'document_title_parts', 'townhub_addons_document_title_parts_filter', 10, 1 );  



function townhub_addons_listing_author_no_admin_access() {
    if(!wp_doing_ajax()){
        // $redirect = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : home_url( '/' );
        // global $current_user;
        // $user_roles = $current_user->roles;
        // $user_role = array_shift($user_roles);

        $user_role = townhub_addons_get_user_role();
        if( $user_role == 'administrator' ){
            return ;
        }
        
        // $azp_csses = get_option( 'azp_csses' );echo json_encode($azp_csses);die;

        if( in_array( $user_role, townhub_addons_get_option('admin_bar_hide_roles') ) ){
            wp_redirect( home_url('/') ); exit;
            wp_die( __( "You don't have permission to access this page.", 'townhub-add-ons' ) );
        }
    }
        
 }

add_action( 'admin_init', 'townhub_addons_listing_author_no_admin_access', 100 );



// custom template for listing search page
add_action( 'parse_request', 'townhub_addons_parse_request_callback' );
function townhub_addons_parse_request_callback( $query ) {
    if (  isset($_GET['search_term']) ) {
        $query->query_vars[ 'post_type' ] = 'listing';
    }
    return $query;
}
// https://wordpress.stackexchange.com/questions/89886/how-to-create-a-custom-search-for-custom-post-type
function townhub_addons_listing_search_template($template)   {    
    global $wp_query; 
    if( is_post_type_archive('listing') || is_tax('listing_cat') || is_tax('listing_feature') || is_tax('listing_tag') ){
        // return locate_template('listing-search.php');  //  redirect to listing-search.php
        $template = townhub_addons_get_template_part('templates/listing', 'search', null, false);
    }elseif( is_tax('listing_location') ){
        $template = townhub_addons_get_template_part('templates/archive', 'loc', null, false);
    }elseif( array_key_exists('author', $wp_query->query_vars) && !empty($wp_query->query_vars['author']) && array_key_exists('author_name', $wp_query->query_vars) && !empty($wp_query->query_vars['author_name']) ){
        global $laumember;
        $laumember = new WP_User( $wp_query->query_vars["author"] );
        if( $laumember->exists() )
        {
            $lauthor_archive_for = townhub_addons_get_option('lauthor_archive_for');
            if( !empty( $lauthor_archive_for ) && in_array( townhub_addons_get_user_role( $laumember->ID ), (array)$lauthor_archive_for ) ){
                $template = townhub_addons_get_template_part('templates/archive', 'author', null, false);
                // $template = ESB_ABSPATH . 'templates/listing-author.php';
            }
        }
    }
    return $template;   
}
add_filter('template_include', 'townhub_addons_listing_search_template');



function townhub_addons_listing_search_result($query) {
    if ( ! is_admin() && $query->is_main_query() ) {
        

        if ( is_post_type_archive('listing') || is_tax('listing_cat') || is_tax('listing_feature') || is_tax('listing_location') || is_tax('listing_tag') || ( 'listing' == $query->get('post_type') && !is_single() ) ) {
            $ad_posts_args = array();
            $ad_posts = array();
            if( 'listing' == $query->get('post_type') ){
                if(townhub_addons_get_option('ads_search_enable') == 'yes'){ 
                    $ad_posts_args = array(
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

                    );
                }
            }elseif(is_tax('listing_cat')){
                if(townhub_addons_get_option('ads_category_enable') == 'yes'){
                    $ad_posts_args = array(
                        'post_type'             => 'listing', 
                        'orderby'               => townhub_addons_get_option('ads_category_orderby'),
                        'order'                 => townhub_addons_get_option('ads_category_order'),
                        'posts_per_page'        => townhub_addons_get_option('ads_category_count'),
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'listing_cat',
                                'field'    => 'term_id',
                                'terms'    => get_queried_object_id(),
                            ),
                        ),

                        'meta_query'            => array(
                            'relation' => 'AND',
                            array(
                                'key'     => ESB_META_PREFIX.'is_ad',
                                'value'   => 'yes',
                            ),
                            array(
                                'key'     => ESB_META_PREFIX.'ad_position_category',
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

                    );
                }
            }elseif(is_post_type_archive('listing')){
                if(townhub_addons_get_option('ads_archive_enable') == 'yes'){
                    $ad_posts_args = array(
                        'post_type'             => 'listing', 
                        'orderby'               => townhub_addons_get_option('ads_archive_orderby'),
                        'order'                 => townhub_addons_get_option('ads_archive_order'),
                        'posts_per_page'        => townhub_addons_get_option('ads_archive_count'),
                        'meta_query'            => array(
                            'relation' => 'AND',
                            array(
                                'key'     => ESB_META_PREFIX.'is_ad',
                                'value'   => 'yes',
                            ),
                            array(
                                'key'     => ESB_META_PREFIX.'ad_position_archive',
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

                    );
                }
            }
            if(!empty($ad_posts_args)){

                if(townhub_addons_get_option('listings_orderby') == 'listing_featured'){
                    $ad_posts_args['meta_key'] = ESB_META_PREFIX.'featured';
                    $ad_posts_args['orderby'] = 'meta_value_num date';
                    $ad_posts_args['order'] = 'DESC';
                }
                
                // The Query
                $posts_query = new WP_Query( $ad_posts_args );
                
                if($posts_query->have_posts()) :
                    
                    while($posts_query->have_posts()) : $posts_query->the_post();
                        $ad_posts[] = get_the_ID();
                        
                    endwhile;
                endif;

                wp_reset_postdata();
            }

            $GLOBALS['main_ads'] = $ad_posts;

            if(!empty($ad_posts)) $query->set('post__not_in', $ad_posts );
            // http://localhost:8888/wpclean/?search_term=&search_term=&checkin=2018-12-17&checkout=2018-12-21&adults=1&children=0&post_type=listing
            if( isset($_GET['checkin']) && $_GET['checkin'] != '' ){

                $post__in_sum = array();

                if( isset($_GET['checkout']) && $_GET['checkout'] != '' ){
                    $avai_check_args = array(
                        'checkin'   => $_GET['checkin'],
                        'checkout'   => $_GET['checkout'],
                        'listing_id'   => 0,
                    );
                    $listing_availables = townhub_addons_get_available_listings($avai_check_args);
                    if(is_array($listing_availables) && !empty($listing_availables)){
                        $post__in = array();
                        foreach ($listing_availables as $avai) {
                            if( isset($avai->id) && (int)$avai->id > 0){
                                
                                if(isset($_GET['no_rooms']) && (int)$_GET['no_rooms'] > 1){
                                    $avai_check_args['listing_id'] = $avai->id;
                                    // check quantity
                                    $double_check = townhub_addons_get_available_listings($avai_check_args);
                                    if(!empty($double_check)){
                                        $room_quans = array_map(function($room){
                                            return ((int)$room->quantities > 0) ? (int)$room->quantities : 0;
                                        },$double_check);
                                        $room_quans = array_filter($room_quans);
                                        if(array_sum($room_quans) >= $_GET['no_rooms']) $post__in[] = $avai->id;
                                    }
                                }else{
                                    $post__in[] = $avai->id;
                                }
                                
                            }
                        }
                        $post__in_sum = array_merge($post__in_sum, $post__in);
                    }else{
                        // do not return any listing if has no rooms
                        $query->set('s', 'donotreturnanylistingifnoroomavailableABCDEFGHIJKLMNOPQRSTUVWXYZ' );
                        
                    } 
                }else{
                    $post__in_sum = array_merge($post__in_sum, townhub_addons_listing_available_date( $_GET['checkin'] ) );
                }
                // end checkout check
                $post__in_sum = array_filter($post__in_sum);
                if(!empty($post__in_sum)){
                    $query->set('post__in', $post__in_sum );
                }else{
                    $query->set('s', 'donotreturnanylistingifnoroomavailableABCDEFGHIJKLMNOPQRSTUVWXYZ' );
                }

            }

            $add_queries = array();
            // http://localhost:8888/townhub/?s=L&lcats%5B%5D=92&post_type=listing&lfeas%5B%5D=200&llocs%5B%5D=10

            
            // $current_language = apply_filters( 'wpml_current_language', NULL );
            // global $sitepress;
            // $sitepress->get_default_language();

            // php 5.5+
            if( isset($_GET['lcats']) ){
                $filtered_cats = array_filter((array)$_GET['lcats']);
                if( !empty($filtered_cats) ){
                    $wpmlCats = [];
                    foreach ($filtered_cats as $catID) {
                        $wpmlCats[] = (int)$catID;
                        $wpmlCats[] = apply_filters( 'wpml_object_id', (int)$catID, 'listing_cat', true, townhub_addons_get_default_language() );
                    }
                    $add_queries[] =    array(
                                        'taxonomy' => 'listing_cat',
                                        'field'    => 'term_id',
                                        'terms'    => $wpmlCats,
                                    );
                }

                

            }
            // $filtered_cat = reset($filtered_cats);
            $filtered_cat = 0;
            if( is_tax('listing_cat') ){
                $filtered_cat = get_queried_object_id();
            }elseif( isset($filtered_cats) && !empty($filtered_cats) ){
                $filtered_cat = reset($filtered_cats);
            }
            // else if($_SERVER['SERVER_NAME'] == 'townhub.cththemes.com' || $_SERVER['SERVER_NAME'] == 'townhub2.cththemes.com'){

            //     $add_queries[] =    array(
            //                             'taxonomy' => 'listing_cat',
            //                             'field'    => 'term_id',
            //                             'terms'    => array( 309 ),
            //                             'operator' => 'NOT IN',
            //                             // 'include_children'  => false, // default true
            //                         );
            // }
            if( isset($_GET['lfeas']) && !empty( array_filter($_GET['lfeas']) ) ){

                $add_queries[] =    array(
                                        'taxonomy' => 'listing_feature',
                                        'field'    => 'term_id',
                                        'terms'    => array_filter($_GET['lfeas']),
                                    );

            }
            if( isset($_GET['llocs']) && !empty($_GET['llocs'] ) ){
                $llocs = explode(',',$_GET['llocs']);
                $add_queries[] =    array(
                                        'taxonomy' => 'listing_location',
                                        'field'    => 'slug',
                                        'terms'    => array_filter( $llocs, function($loc){ return sanitize_title( $loc ); } ),
                                    );

            }

            if( isset($_GET['listing_tags']) && !empty( array_filter($_GET['listing_tags']) ) ){

                $add_queries[] =    array(
                                        'taxonomy' => 'listing_tag',
                                        'field'    => 'term_id',
                                        'terms'    => $_GET['listing_tags'],
                                        'operator' => 'AND', // default IN
                                    );

            }

            if(!empty($add_queries)){
                $add_queries['relation'] = townhub_addons_get_option('search_tax_relation');
                $query->set('tax_query', $add_queries);
            } 

            // listing meta search
            $meta_queries = array();
            if( isset($_GET['ltype']) && !empty($_GET['ltype'] ) && townhub_addons_get_option('use_ltype_search') == 'yes' ){
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
                                        'key'       => ESB_META_PREFIX.'listing_type_id',
                                        'value'     => intval( $_GET['ltype'] ),
                                        'type'      => 'NUMERIC',
                                    );
                }
                
                

            }

            if( townhub_addons_get_option('hide_past_events') == 'yes' && ( !isset($_GET['past_events']) || $_GET['past_events'] != 'show' ) ){

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
                //                             'key'       => ESB_META_PREFIX.'eventdate_start',
                //                             'value'     => current_time('Y-m-d', 1),
                //                             'compare'   => '>=',
                //                             'type'      => 'DATE',
                //                         );
            }

            if( empty($_GET['checkout']) ){
                $sPers = 0;
                if( isset($_GET['adults']) ){
                    $sPers += intval( $_GET['adults'] );
                }
                if( isset($_GET['children']) ){
                    $sPers += intval( $_GET['children'] );
                }
                if( isset($_GET['infants']) ){
                    $sPers += intval( $_GET['infants'] );
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

            if( isset($_GET['fprice']) && !empty($_GET['fprice'] ) ){
                if(strpos($_GET['fprice'], ";") !== false){
                    $range = explode(";", $_GET['fprice']);
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

            $meta_queries = (array)apply_filters( 'cth_listing_additional_meta_queries', $meta_queries );
           

             

            if(!empty($meta_queries)) $query->set('meta_query', $meta_queries);

            $query->set('posts_per_page', townhub_addons_get_option('listings_count'));
            $query->set('orderby', townhub_addons_get_option('listings_orderby'));
            $query->set('order', townhub_addons_get_option('listings_order'));
            // https://wordpress.stackexchange.com/questions/45413/using-orderby-and-meta-value-num-to-order-numbers-first-then-strings
            if(townhub_addons_get_option('listings_orderby') == 'listing_featured'){
                $query->set('meta_key', ESB_META_PREFIX.'featured');
                $query->set('orderby', 'meta_value_num date');
                $query->set('order', 'DESC');
            }

            if(townhub_addons_get_option('listings_orderby') == 'event_start_date'){
                $query->set('meta_key', ESB_META_PREFIX.'eventdate_start');
                // $query->set('meta_type', 'DATE');
                // $query->set('orderby', 'meta_value_date');
                $query->set('orderby', 'meta_value');
            }

            if(townhub_addons_get_option('listings_orderby') == 'most_viewed'){
                $query->set('meta_key', ESB_META_PREFIX.'post_views_count');
                $query->set('orderby', 'meta_value meta_value_num');
                $query->set('order', 'DESC');
            }

            // $query->set('meta_key', ESB_META_PREFIX.'featured');
            // $query->set('orderby', 'meta_value');
            // $query->set('order', 'DESC');

            // for additional search
            $query->set('suppress_filters', false);
            $query->set('cthqueryid', 'main-search');

            // fix search cache result
            $query->set('cache_results', false);
            $query->set('update_post_meta_cache', false);
            $query->set('update_post_term_cache', false);
        }
        
        // if ( $query->is_tag() ) {
        //     $query->set( 'post_type', array( 'post','nav_menu_item','listing' ) );
        // }
        
    }
}

add_action( 'pre_get_posts', 'townhub_addons_listing_search_result' );

function townhub_addons_get_default_language(){
    global $sitepress;
    if( $sitepress ) return $sitepress->get_default_language();

    return null;
}

// add_action( 'pre_get_posts',  function($query) {
//     if ( ! is_admin() && $query->is_main_query() ) {
//         if ( is_post_type_archive('listing') || is_tax('listing_cat') || is_tax('listing_feature') || is_tax('listing_location') || is_tax('listing_tag') || 'listing' == $query->get('post_type') ) {
//             // check custom field value
//             if( !empty($_GET['cut_off']) ){
//                 $meta_queries = $query->get('meta_query');
                
//                 // check for meta
//                 if( !empty($meta_queries) && is_array($meta_queries) ) {
                    
//                     $meta_queries[] = array(
//                         'relation'      => 'AND',
//                         array(
//                             'key'       => ESB_META_PREFIX.'cus_field_924r6t3vr',
//                             'compare'   => 'EXISTS'
//                         ),
//                         array(
//                             'key'       => ESB_META_PREFIX.'cus_field_924r6t3vr',
//                             'value'     =>  $_GET['cut_off'] ,
//                             'type'      => 'NUMERIC',
//                             'compare'   => '>=',
//                         )
//                     );
//                 }else{
//                     $meta_queries = array(
//                         'relation'      => 'AND',
//                         array(
//                             'key'       => ESB_META_PREFIX.'cus_field_924r6t3vr',
//                             'compare'   => 'EXISTS'
//                         ),
//                         array(
//                             'key'       => ESB_META_PREFIX.'cus_field_924r6t3vr',
//                             'value'     =>  $_GET['cut_off'] ,
//                             'type'      => 'NUMERIC',
//                             'compare'   => '>=',
//                         )
//                     );
//                 }

//                 $query->set('meta_query', $meta_queries);
//             }
                
//         }
//     }

// }, 999 );


function townhub_addons_listing_search_where($where, $q){
    global $wpdb;
    if ( ! is_admin() && $q->is_main_query() ) {
        // if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Is search" . PHP_EOL, 3, ESB_LOG_FILE);
        // if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). json_encode($q) . PHP_EOL, 3, ESB_LOG_FILE);
        if (is_search() && 'listing' == get_query_var('post_type') )
            $where .= "OR (t.name LIKE '%".get_search_query()."%' AND {$wpdb->posts}.post_status = 'publish')";
    }
    return $where;
}

function townhub_addons_listing_search_join($join, $q){
    global $wpdb;
    if ( ! is_admin() && $q->is_main_query() ) {
        if ( is_search() && 'listing' == get_query_var('post_type') )
            $join .= "LEFT JOIN {$wpdb->term_relationships} tr ON {$wpdb->posts}.ID = tr.object_id INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id";
    }
    return $join;
}

function townhub_addons_listing_search_groupby($groupby, $q){
    global $wpdb;
    if ( ! is_admin() && $q->is_main_query() ) {
        // we need to group on post ID
        $groupby_id = "{$wpdb->posts}.ID";
        if(!is_search() || strpos($groupby, $groupby_id) !== false || 'listing' != get_query_var('post_type')) return $groupby;

        // groupby was empty, use ours
        if(!strlen(trim($groupby))) return $groupby_id;

        // wasn't empty, append ours
        return $groupby.", ".$groupby_id;
    }

    return $groupby;
}

// add_filter('posts_where','townhub_addons_listing_search_where', 10, 2);
// add_filter('posts_join', 'townhub_addons_listing_search_join', 10, 2);
// add_filter('posts_groupby', 'townhub_addons_listing_search_groupby', 10, 2);

// function townhub_addons_add_tag_custom_type($query){
//     if ( ! is_admin() && $query->is_main_query() ) {
//         // add support post_tag to listing post
//         if( is_tag() && empty($query->query_vars['suppress_filters']) ){
//             $post_types = (array)$query->get('post_type');

//             $query->set('post_type', array('post','nav_menu_item','listing'));
//         }
//     }
    
//     return $query;
// }

// add_filter( 'pre_get_posts', 'townhub_addons_add_tag_custom_type' );

function townhub_addons_auto_login_new_user( $user_id ) {
    
    wp_set_current_user($user_id);

    // Set the global user object
    // $current_user = get_user_by( 'id', $user_id );

    // set the WP login cookie
    $secure_cookie = is_ssl() ? true : false;

    wp_set_auth_cookie( $user_id, true, $secure_cookie ); // This function does not return a value

}
// do not remove the callback function
// add_action( 'user_register', 'townhub_addons_auto_login_new_user' );
// https://codex.wordpress.org/Plugin_API/Action_Reference/user_register

function townhub_addons_generate_password($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-';
    $count = mb_strlen($chars);

    for ($i = 0, $result = ''; $i < $length; $i++) {
        $index = rand(0, $count - 1);
        $result .= mb_substr($chars, $index, 1);
    }

    return $result;
}

function townhub_addons_get_booking_statuses_array($defauls = array() ) {
    if(empty($defauls)){
        $defauls = array(
            // paypal
            'pending'=> __('Pending','townhub-add-ons'), 
            'completed'=> __('Completed','townhub-add-ons'), 
            'failed'=> __('Failed','townhub-add-ons'), 
            'refunded'=> __('Refunded','townhub-add-ons'), 
            'partially_refunded'=> __('Partially Refunded','townhub-add-ons'), 

            // stripe
            'created'=> __('Created','townhub-add-ons'), 
            'trialing'=>__('Trialing','townhub-add-ons'), 
            'active'=>__('Active','townhub-add-ons'), 
            'past_due'=>__('Past Due','townhub-add-ons'), 
            'canceled'=>__('Canceled','townhub-add-ons') ,
            'unpaid'=>__('Unpaid','townhub-add-ons') 
        );
    }

    return $defauls ;
}
function townhub_addons_get_booking_status_text($status = 'pending'){
    $statuses = townhub_addons_get_booking_statuses_array();
    if(isset($statuses[$status])) return $statuses[$status];
    return $statuses['pending'];
}

function townhub_add_ons_get_payfast_duration_unit($unit = ''){

    $default = array(
        'day' => 'D',
        'week' => 'W',
        'month' => 'Monthly',
        'year' => 'Annual',
    );

    if( !empty($unit) && isset( $default[$unit] ) ){
        if($unit == 'day' || $unit == 'week' || $unit == 'month'){
            return $default['month'];
        }else{
            return $default['year'];
        } 
    } 

    return $default['month'];
}
function townhub_addons_upload_dirs($base_name = 'azp', $child = ''){
    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $base_dir = $upload_dir . DIRECTORY_SEPARATOR . $base_name;
    // if (! is_dir($base_dir)) {
    //    mkdir( $base_dir, 0755 );
    // }
    wp_mkdir_p( $base_dir );
    if($child != ''){
        $child_dir = $base_dir . DIRECTORY_SEPARATOR . $child;
        // if (! is_dir($child_dir)) 
        //     mkdir( $child_dir, 0755 );

        wp_mkdir_p( $child_dir );

        return $child_dir;
    }
    return $base_dir;
}
function townhub_addons_azp_parser_listing($listing_type_id = 0, $builder = '', $post_id = 0, $language = '', $currency = ''){
    if( empty($listing_type_id) || null === get_post($listing_type_id) ) 
        $listing_type_id = esb_addons_get_wpml_option('default_listing_type', 'listing_type');
    // else{
        
    // }

    $shortcode = get_post_meta( (int)$listing_type_id, ESB_META_PREFIX . $builder . '_layout', true );

    // if($builder == 'preview_room') var_dump($shortcode);
    
    return (new AZPParser)->doContentShortcode($shortcode);

    
    
    if($language == '') $language = get_locale();
    if($language == '') $language = 'en_US';

    $currency = townhub_addons_get_currency();

    $cache_group = "azp_group_{$listing_type_id}";
    $new_cache_name = "azp_{$builder}_{$language}_{$currency}";
    if( $post_id ){
        $new_cache_name = "azp_{$post_id}_{$builder}_{$language}_{$currency}";
    }
    $azp_store_cache = wp_cache_get( $new_cache_name,  $cache_group);
    if ( false === $azp_store_cache ) {
        $shortcode = get_post_meta( (int)$listing_type_id, ESB_META_PREFIX . $builder . '_layout', true );
        $azp_parser = new AZPParser();
        $azp_store_cache = $azp_parser->doContentShortcode($shortcode);
        // do not cache for single layout
        if ( townhub_addons_get_option('azp_cache') == 'yes' && $builder != 'single' ) wp_cache_set( $new_cache_name, $azp_store_cache, $cache_group, DAY_IN_SECONDS );
    } 
    // Do something with $result;
    return $azp_store_cache;



    $last_modified_ltype_post = get_post_modified_time('G', true, $listing_type_id);

    // // azp caches folder
    // $upload = wp_upload_dir();
    // // $upload_path = $upload['path'];
    // $upload_path = $upload['basedir'] . DIRECTORY_SEPARATOR . 'azp' . DIRECTORY_SEPARATOR;

    $upload_path = townhub_addons_upload_dirs('azp', $builder);
    
    // cache file name
    $cache_name = "{$listing_type_id}";
    if($post_id){
        $cache_name = "{$post_id}";

        $last_modified_post = get_post_modified_time('G', true, $post_id);
        if($last_modified_post && $last_modified_post > $last_modified_ltype_post) $last_modified_ltype_post = $last_modified_post;
    }
    $cache_file = $upload_path . DIRECTORY_SEPARATOR . "{$cache_name}_{$language}_{$currency}";

    $cache_file = apply_filters( 'esb_azp_listing_cache_file', $cache_file, $listing_type_id, $post_id, $language, $currency );

    if( $builder != 'single' && file_exists($cache_file) && (filemtime($cache_file) > ($last_modified_ltype_post - 60 * 1 ))  && townhub_addons_get_option('azp_cache') == 'yes' ) {
        // Cache file is less than five minutes old. 
        // Don't bother refreshing, just use the file as-is.
        $layout = file_get_contents($cache_file);
    }else{
        // Our cache is out-of-date, so load the data from our remote server,
        // and also save it over our cache for next time.
        $shortcode = get_post_meta( (int)$listing_type_id, ESB_META_PREFIX . $builder . '_layout', true );
        $layout = (new AZPParser())->doContentShortcode($shortcode);
        // do not save cache for single listing
        if( $builder != 'single' && townhub_addons_get_option('azp_cache') == 'yes' ) file_put_contents($cache_file, $layout, LOCK_EX); //-> not create file;
        // file_put_contents($cache_file, $layout);
    }

    return $layout;
}
function townhub_addons_get_listing_type_options(){
    $options = array();
    $posts = get_posts( array(
        'fields'            => 'ids',
        'post_type'         => 'listing_type',
        'posts_per_page'    => -1,
        'post_status'       => 'publish',

        // 'suppress_filters'  => false,
    ) );
    if($posts){
        foreach ($posts as $ltid) {
            $options[$ltid] = get_the_title( $ltid );
        }
    }
    return $options;
}
// function townhub_addons_format_pricing_yearly_sale($reduction = 0){
//     if(!empty($reduction)){
//         return sprintf(__( '-%d%%', 'townhub-add-ons' ), $reduction);
//     }
//     return '';
// }

// function townhub_addons_calculate_yearly_price($price = 0, $period = 'month', $interval = 1, $sale = 0){
//     // period - day/week/month/year
//     if($price <= 0 || $interval <= 0) return 0;
//     switch ($period) {
//         case 'day':
//             $yearly_price = 365*($price/$interval);
//             break;
//         case 'week':
//             $yearly_price = 52*($price/$interval);
//             break;
//         case 'month':
//             $yearly_price = 12*($price/$interval);
//             break;
//         default:
//             $yearly_price = 1*($price/$interval);
//             break;
//     }
//     return $yearly_price * (100 - $sale)/100;
// }

function townhub_addons_add_listing_url(){
    if( Esb_Class_Membership::can_add() ){
        return get_permalink(esb_addons_get_wpml_option('submit_page'));
    }else return get_permalink( esb_addons_get_wpml_option('free_submit_page') );
}

function townhub_addons_encodeURIComponent($str) {
    $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
    return strtr(rawurlencode($str), $revert);
}


// // create new invoice
function townhub_addons_create_invoice($data){

    // $required_data = array(
    //     'order_id',
    //     'user_id',
    //     'user_name',
    //     'user_email',
    //     'from_date',
    //     'end_date',
    //     'payment',
    //     'txn_id',

    //     'plan_title',
    //     'quantity',
    //     'amount',
    //     'tax',
    //     'charged_to',
    // );

    $invoice_datas                      = array();
    $invoice_datas['post_title']        = sprintf(__( 'Invoice for package #%s', 'townhub-add-ons' ), $data['order_id']);
    $invoice_datas['post_content']      = '';
    $invoice_datas['post_author']       = $data['user_id'];
    $invoice_datas['post_status']       = 'publish';
    $invoice_datas['post_type']         = 'cthinvoice';

    do_action( 'townhub_addons_insert_invoice_before', $invoice_datas );

    $cthinvoice_id = wp_insert_post($invoice_datas ,true );

    if (!is_wp_error($cthinvoice_id)) {
        

        $meta_datas = array(
            'order_id'                  => $data['order_id'],

            'user_id'                   => $data['user_id'],
            'user_name'                 => $data['user_name'],
            'user_email'                => $data['user_email'],
            'phone'                     => $data['phone'],
            
            'from_date'                 => $data['from_date'],
            'end_date'                  => $data['end_date'],
            'payment'                   => $data['payment'],
            'txn_id'                    => $data['txn_id'],

            'plan_title'                => $data['plan_title'],
            'quantity'                  => $data['quantity'],
            'amount'                    => $data['amount'], // will be order total - 
            'tax'                       => $data['tax'], // maybe not needed
            'charged_to'                => $data['charged_to'], // if want to display card number
            // new data
            'subtotal'                  => isset($data['subtotal']) ? $data['subtotal'] : 0,
            'subtotal_vat'              => isset($data['subtotal_vat']) ? $data['subtotal_vat'] : 0,
            'price_total'               => isset($data['price_total']) ? $data['price_total'] : 0,

            'for_listing_ad'            => (isset($data['for_listing_ad']) && $data['for_listing_ad'] == 'yes') ? 'yes' : 'no',
        );

        foreach ($meta_datas as $key => $value){
            if ( !update_post_meta( $cthinvoice_id, ESB_META_PREFIX.$key, $value ) ) {
                if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). sprintf(__('Insert invoice %s meta failure or existing meta value','townhub-add-ons'),$key) . PHP_EOL, 3, ESB_LOG_FILE);
            }
        }

        do_action( 'townhub_addons_new_invoice', $cthinvoice_id );

        return $cthinvoice_id;

    }

    return false;

}
function townhub_addons_get_google_contry_codes($country = '', $lowercase = false){

    $countries = array(
        'AB' => __("Abkhazia", 'townhub-add-ons'),
        'AF' => __("Afghanistan", 'townhub-add-ons'),
        'AL' => __("Albania", 'townhub-add-ons'),
        'DZ' => __("Algeria", 'townhub-add-ons'),
        'AS' => __("American Samoa", 'townhub-add-ons'),
        'AD' => __("Andorra", 'townhub-add-ons'),
        'AO' => __("Angola", 'townhub-add-ons'),
        'AI' => __("Anguilla", 'townhub-add-ons'),
        'AQ' => __("Antarctica", 'townhub-add-ons'),
        'AG' => __("Antigua and Barbuda", 'townhub-add-ons'),
        'AR' => __("Argentina", 'townhub-add-ons'),
        'AM' => __("Armenia", 'townhub-add-ons'),
        'AW' => __("Aruba", 'townhub-add-ons'),
        'AU' => __("Australia", 'townhub-add-ons'),
        'AT' => __("Austria", 'townhub-add-ons'),
        'AZ' => __("Azerbaijan", 'townhub-add-ons'),
        'BS' => __("Bahamas", 'townhub-add-ons'),
        'BH' => __("Bahrain", 'townhub-add-ons'),
        'BD' => __("Bangladesh", 'townhub-add-ons'),
        'BB' => __("Barbados", 'townhub-add-ons'),
        'BY' => __("Belarus", 'townhub-add-ons'),
        'BE' => __("Belgium", 'townhub-add-ons'),
        'BZ' => __("Belize", 'townhub-add-ons'),
        'BJ' => __("Benin", 'townhub-add-ons'),
        'BM' => __("Bermuda", 'townhub-add-ons'),
        'BT' => __("Bhutan", 'townhub-add-ons'),
        'BO' => __("Bolivia", 'townhub-add-ons'),
        'BA' => __("Bosnia and Herzegovina", 'townhub-add-ons'),
        'BW' => __("Botswana", 'townhub-add-ons'),
        'BV' => __("Bouvet Island", 'townhub-add-ons'),
        'BR' => __("Brazil", 'townhub-add-ons'),
        'IO' => __("British Indian Ocean Territory", 'townhub-add-ons'),
        'BN' => __("Brunei Darussalam", 'townhub-add-ons'),
        'BG' => __("Bulgaria", 'townhub-add-ons'),
        'BF' => __("Burkina Faso", 'townhub-add-ons'),
        'BI' => __("Burundi", 'townhub-add-ons'),
        'KH' => __("Cambodia", 'townhub-add-ons'),
        'CM' => __("Cameroon", 'townhub-add-ons'),
        'CA' => __("Canada", 'townhub-add-ons'),
        'CV' => __("Cape Verde", 'townhub-add-ons'),
        'KY' => __("Cayman Islands", 'townhub-add-ons'),
        'CF' => __("Central African Republic", 'townhub-add-ons'),
        'TD' => __("Chad", 'townhub-add-ons'),
        'CL' => __("Chile", 'townhub-add-ons'),
        'CN' => __("China", 'townhub-add-ons'),
        'CX' => __("Christmas Island", 'townhub-add-ons'),
        'CC' => __("Cocos (Keeling) Islands", 'townhub-add-ons'),
        'CO' => __("Colombia", 'townhub-add-ons'),
        'KM' => __("Comoros", 'townhub-add-ons'),
        'CG' => __("Congo", 'townhub-add-ons'),
        'CD' => __("Congo, the Democratic Republic of the", 'townhub-add-ons'),
        'CK' => __("Cook Islands", 'townhub-add-ons'),
        'CR' => __("Costa Rica", 'townhub-add-ons'),
        'CI' => __("Cote D'ivoire", 'townhub-add-ons'),
        'HR' => __("Croatia", 'townhub-add-ons'),
        'CU' => __("Cuba", 'townhub-add-ons'),
        'CY' => __("Cyprus", 'townhub-add-ons'),
        'CZ' => __("Czech Republic", 'townhub-add-ons'),
        'DK' => __("Denmark", 'townhub-add-ons'),
        'DJ' => __("Djibouti", 'townhub-add-ons'),
        'DM' => __("Dominica", 'townhub-add-ons'),
        'DO' => __("Dominican Republic", 'townhub-add-ons'),
        'EC' => __("Ecuador", 'townhub-add-ons'),
        'EG' => __("Egypt", 'townhub-add-ons'),
        'SV' => __("El Salvador", 'townhub-add-ons'),
        'GQ' => __("Equatorial Guinea", 'townhub-add-ons'),
        'ER' => __("Eritrea", 'townhub-add-ons'),
        'EE' => __("Estonia", 'townhub-add-ons'),
        'ET' => __("Ethiopia", 'townhub-add-ons'),
        'FK' => __("Falkland Islands (Malvinas)", 'townhub-add-ons'),
        'FO' => __("Faroe Islands", 'townhub-add-ons'),
        'FJ' => __("Fiji", 'townhub-add-ons'),
        'FI' => __("Finland", 'townhub-add-ons'),
        'FR' => __("France", 'townhub-add-ons'),
        'GF' => __("French Guiana", 'townhub-add-ons'),
        'PF' => __("French Polynesia", 'townhub-add-ons'),
        'TF' => __("French Southern Territories", 'townhub-add-ons'),
        'GA' => __("Gabon", 'townhub-add-ons'),
        'GM' => __("Gambia", 'townhub-add-ons'),
        'GE' => __("Georgia", 'townhub-add-ons'),
        'DE' => __("Germany", 'townhub-add-ons'),
        'GH' => __("Ghana", 'townhub-add-ons'),
        'GI' => __("Gibraltar", 'townhub-add-ons'),
        'GR' => __("Greece", 'townhub-add-ons'),
        'GL' => __("Greenland", 'townhub-add-ons'),
        'GD' => __("Grenada", 'townhub-add-ons'),
        'GP' => __("Guadeloupe", 'townhub-add-ons'),
        'GU' => __("Guam", 'townhub-add-ons'),
        'GT' => __("Guatemala", 'townhub-add-ons'),
        'GN' => __("Guinea", 'townhub-add-ons'),
        'GW' => __("Guinea-Bissau", 'townhub-add-ons'),
        'GY' => __("Guyana", 'townhub-add-ons'),
        'HT' => __("Haiti", 'townhub-add-ons'),
        'HM' => __("Heard Island and Mcdonald Islands", 'townhub-add-ons'),
        'VA' => __("Holy See (Vatican City State)", 'townhub-add-ons'),
        'HN' => __("Honduras", 'townhub-add-ons'),
        'HK' => __("Hong Kong", 'townhub-add-ons'),
        'HU' => __("Hungary", 'townhub-add-ons'),
        'IS' => __("Iceland", 'townhub-add-ons'),
        'IN' => __("India", 'townhub-add-ons'),
        'ID' => __("Indonesia", 'townhub-add-ons'),
        'IR' => __("Iran, Islamic Republic of", 'townhub-add-ons'),
        'IQ' => __("Iraq", 'townhub-add-ons'),
        'IE' => __("Ireland", 'townhub-add-ons'),
        'IL' => __("Israel", 'townhub-add-ons'),
        'IT' => __("Italy", 'townhub-add-ons'),
        'JM' => __("Jamaica", 'townhub-add-ons'),
        'JP' => __("Japan", 'townhub-add-ons'),
        'JO' => __("Jordan", 'townhub-add-ons'),
        'KZ' => __("Kazakhstan", 'townhub-add-ons'),
        'KE' => __("Kenya", 'townhub-add-ons'),
        'KI' => __("Kiribati", 'townhub-add-ons'),
        'KP' => __("Korea, Democratic People's Republic of", 'townhub-add-ons'),
        'KR' => __("Korea, Republic of", 'townhub-add-ons'),
        'KW' => __("Kuwait", 'townhub-add-ons'),
        'KG' => __("Kyrgyzstan", 'townhub-add-ons'),
        'LA' => __("Lao People's Democratic Republic", 'townhub-add-ons'),
        'LV' => __("Latvia", 'townhub-add-ons'),
        'LB' => __("Lebanon", 'townhub-add-ons'),
        'LS' => __("Lesotho", 'townhub-add-ons'),
        'LR' => __("Liberia", 'townhub-add-ons'),
        'LY' => __("Libyan Arab Jamahiriya", 'townhub-add-ons'),
        'LI' => __("Liechtenstein", 'townhub-add-ons'),
        'LT' => __("Lithuania", 'townhub-add-ons'),
        'LU' => __("Luxembourg", 'townhub-add-ons'),
        'MO' => __("Macao", 'townhub-add-ons'),
        'MK' => __("Macedonia, the Former Yugosalv Republic of", 'townhub-add-ons'),
        'MG' => __("Madagascar", 'townhub-add-ons'),
        'MW' => __("Malawi", 'townhub-add-ons'),
        'MY' => __("Malaysia", 'townhub-add-ons'),
        'MV' => __("Maldives", 'townhub-add-ons'),
        'ML' => __("Mali", 'townhub-add-ons'),
        'MT' => __("Malta", 'townhub-add-ons'),
        'MH' => __("Marshall Islands", 'townhub-add-ons'),
        'MQ' => __("Martinique", 'townhub-add-ons'),
        'MR' => __("Mauritania", 'townhub-add-ons'),
        'MU' => __("Mauritius", 'townhub-add-ons'),
        'YT' => __("Mayotte", 'townhub-add-ons'),
        'MX' => __("Mexico", 'townhub-add-ons'),
        'FM' => __("Micronesia, Federated States of", 'townhub-add-ons'),
        'MD' => __("Moldova, Republic of", 'townhub-add-ons'),
        'MC' => __("Monaco", 'townhub-add-ons'),
        'MN' => __("Mongolia", 'townhub-add-ons'),
        'MS' => __("Montserrat", 'townhub-add-ons'),
        'MA' => __("Morocco", 'townhub-add-ons'),
        'MZ' => __("Mozambique", 'townhub-add-ons'),
        'MM' => __("Myanmar", 'townhub-add-ons'),
        'NA' => __("Namibia", 'townhub-add-ons'),
        'NR' => __("Nauru", 'townhub-add-ons'),
        'NP' => __("Nepal", 'townhub-add-ons'),
        'NL' => __("Netherlands", 'townhub-add-ons'),
        'AN' => __("Netherlands Antilles", 'townhub-add-ons'),
        'NC' => __("New Caledonia", 'townhub-add-ons'),
        'NZ' => __("New Zealand", 'townhub-add-ons'),
        'NI' => __("Nicaragua", 'townhub-add-ons'),
        'NE' => __("Niger", 'townhub-add-ons'),
        'NG' => __("Nigeria", 'townhub-add-ons'),
        'NU' => __("Niue", 'townhub-add-ons'),
        'NF' => __("Norfolk Island", 'townhub-add-ons'),
        'MP' => __("Northern Mariana Islands", 'townhub-add-ons'),
        'NO' => __("Norway", 'townhub-add-ons'),
        'OM' => __("Oman", 'townhub-add-ons'),
        'PK' => __("Pakistan", 'townhub-add-ons'),
        'PW' => __("Palau", 'townhub-add-ons'),
        'PS' => __("Palestinian Territory, Occupied", 'townhub-add-ons'),
        'PA' => __("Panama", 'townhub-add-ons'),
        'PG' => __("Papua New Guinea", 'townhub-add-ons'),
        'PY' => __("Paraguay", 'townhub-add-ons'),
        'PE' => __("Peru", 'townhub-add-ons'),
        'PH' => __("Philippines", 'townhub-add-ons'),
        'PN' => __("Pitcairn", 'townhub-add-ons'),
        'PL' => __("Poland", 'townhub-add-ons'),
        'PT' => __("Portugal", 'townhub-add-ons'),
        'PR' => __("Puerto Rico", 'townhub-add-ons'),
        'QA' => __("Qatar", 'townhub-add-ons'),
        'RE' => __("Reunion", 'townhub-add-ons'),
        'RO' => __("Romania", 'townhub-add-ons'),
        'RU' => __("Russian Federation", 'townhub-add-ons'),
        'RW' => __("Rwanda", 'townhub-add-ons'),
        'SH' => __("Saint Helena", 'townhub-add-ons'),
        'KN' => __("Saint Kitts and Nevis", 'townhub-add-ons'),
        'LC' => __("Saint Lucia", 'townhub-add-ons'),
        'PM' => __("Saint Pierre and Miquelon", 'townhub-add-ons'),
        'VC' => __("Saint Vincent and the Grenadines", 'townhub-add-ons'),
        'WS' => __("Samoa", 'townhub-add-ons'),
        'SM' => __("San Marino", 'townhub-add-ons'),
        'ST' => __("Sao Tome and Principe", 'townhub-add-ons'),
        'SA' => __("Saudi Arabia", 'townhub-add-ons'),
        'SN' => __("Senegal", 'townhub-add-ons'),
        'CS' => __("Serbia and Montenegro", 'townhub-add-ons'),
        'SC' => __("Seychelles", 'townhub-add-ons'),
        'SL' => __("Sierra Leone", 'townhub-add-ons'),
        'SG' => __("Singapore", 'townhub-add-ons'),
        'SK' => __("Slovakia", 'townhub-add-ons'),
        'SI' => __("Slovenia", 'townhub-add-ons'),
        'SB' => __("Solomon Islands", 'townhub-add-ons'),
        'SO' => __("Somalia", 'townhub-add-ons'),
        'ZA' => __("South Africa", 'townhub-add-ons'),
        'GS' => __("South Georgia and the South Sandwich Islands", 'townhub-add-ons'),
        'ES' => __("Spain", 'townhub-add-ons'),
        'LK' => __("Sri Lanka", 'townhub-add-ons'),
        'SD' => __("Sudan", 'townhub-add-ons'),
        'SR' => __("Suriname", 'townhub-add-ons'),
        'SJ' => __("Svalbard and Jan Mayen", 'townhub-add-ons'),
        'SZ' => __("Swaziland", 'townhub-add-ons'),
        'SE' => __("Sweden", 'townhub-add-ons'),
        'CH' => __("Switzerland", 'townhub-add-ons'),
        'SY' => __("Syrian Arab Republic", 'townhub-add-ons'),
        'TW' => __("Taiwan, Province of China", 'townhub-add-ons'),
        'TJ' => __("Tajikistan", 'townhub-add-ons'),
        'TZ' => __("Tanzania, United Republic of", 'townhub-add-ons'),
        'TH' => __("Thailand", 'townhub-add-ons'),
        'TL' => __("Timor-Leste", 'townhub-add-ons'),
        'TG' => __("Togo", 'townhub-add-ons'),
        'TK' => __("Tokelau", 'townhub-add-ons'),
        'TO' => __("Tonga", 'townhub-add-ons'),
        'TT' => __("Trinidad and Tobago", 'townhub-add-ons'),
        'TN' => __("Tunisia", 'townhub-add-ons'),
        'TR' => __("Turkey", 'townhub-add-ons'),
        'TM' => __("Turkmenistan", 'townhub-add-ons'),
        'TC' => __("Turks and Caicos Islands", 'townhub-add-ons'),
        'TV' => __("Tuvalu", 'townhub-add-ons'),
        'UG' => __("Uganda", 'townhub-add-ons'),
        'UA' => __("Ukraine", 'townhub-add-ons'),
        'AE' => __("United Arab Emirates", 'townhub-add-ons'),
        'UK' => __("United Kingdom", 'townhub-add-ons'),
        'US' => __("United States", 'townhub-add-ons'),
        'UM' => __("United States Minor Outlying Islands", 'townhub-add-ons'),
        'UY' => __("Uruguay", 'townhub-add-ons'),
        'UZ' => __("Uzbekistan", 'townhub-add-ons'),
        'VU' => __("Vanuatu", 'townhub-add-ons'),
        'VE' => __("Venezuela", 'townhub-add-ons'),
        'VN' => __("Viet Nam", 'townhub-add-ons'),
        'VG' => __("Virgin Islands, British", 'townhub-add-ons'),
        'VI' => __("Virgin Islands, U.S.", 'townhub-add-ons'),
        'WF' => __("Wallis and Futuna", 'townhub-add-ons'),
        'EH' => __("Western Sahara", 'townhub-add-ons'),
        'YE' => __("Yemen", 'townhub-add-ons'),
        'ZM' => __("Zambia", 'townhub-add-ons'),
        'ZW' => __("Zimbabwe", 'townhub-add-ons'),

    );

    $countries = (array) apply_filters( 'cth_listing_contry_codes', $countries );
    
    if($country != '' && isset($countries[$country])) return $countries[$country];

    if($country != '') return $country;

    if($lowercase){
        $new_countries = array();
        foreach ($countries as $code => $name) {
            $new_countries[strtolower($code)] = $name;
        }

        return $new_countries;
    }

    return $countries;
}
function townhub_addons_get_filter_variable($cookie_name = '', $default = 0){
    if(!empty($cookie_name)){
        if(isset($_COOKIE[$cookie_name]) && !empty($_COOKIE[$cookie_name])) return $_COOKIE[$cookie_name];
    }
    return $default;

    // $checkin_val = 0;
    // if(isset($_COOKIE['esb_checkin']) && $_COOKIE['esb_checkin'] != '' ) $checkin_val = $_COOKIE['esb_checkin'];

}
function townhub_addons_get_filter_checkinout($cookie_name = '', $modify = 0){
    $default = Esb_Class_Date::modify('', $modify, 'Y-m-d');
    if(!empty($cookie_name)){
        if(isset($_COOKIE[$cookie_name]) && !empty($_COOKIE[$cookie_name])) 
            $cookie_val = $_COOKIE[$cookie_name];
        else 
            $cookie_val = $default;
        if(townhub_addons_booking_nights('now', $cookie_val)) return $cookie_val;
    }
    return $default;
}
function townhub_addons_get_url_check_out($postID = 0,$roomID = 0){
    $checkout_page_id = esb_addons_get_wpml_option('checkout_page');
    $args = array(
        'listing_id' => $postID,
        'lb_room'   => $roomID,
    );
    $url = add_query_arg( $args, get_permalink($checkout_page_id));
   
    return $url ;
}

function townhub_addons_get_average_price($listing_id = 0){
    if($listing_id == 0) $listing_id = get_the_ID();
    $rooms_ids = get_post_meta( $listing_id, ESB_META_PREFIX.'rooms_ids', true );
    $rooms_prices = array();
    if( is_array($rooms_ids) && !empty($rooms_ids) ){
        foreach ($rooms_ids as $rid) {
            $rooms_prices[] = (float) get_post_meta( $rid, '_price', true );
        }
    }
    if(!empty($rooms_prices)) 
        $lprice =  round((array_sum($rooms_prices) / count($rooms_prices)), 2, PHP_ROUND_HALF_UP);
    else
        $lprice = get_post_meta( $listing_id, '_price', true );

    return apply_filters( 'esb_listing_average_price', $lprice, $listing_id );
}

function townhub_addons_get_listing_cats(){
    $return = array();
    $taxonomies = get_terms( array(
        'taxonomy'          => 'listing_cat',
        'hide_empty'        => false,
        'parent'            => 0,
    ) );
    if ( $taxonomies && ! is_wp_error( $taxonomies ) ){ 
        foreach ( $taxonomies as $term ) {
            $return[] = array(
                'name' => $term->name,
                'id'   =>  $term->term_id,
            );      
        }
    }
    return $return;
}
function townhub_addons_get_listing_locs(){
    $return = array();
    $taxonomies = get_terms( array(
        'taxonomy'          => 'listing_location',
        'hide_empty'        => false,
        'parent'            => 0,
    ) );
    if ( $taxonomies && ! is_wp_error( $taxonomies ) ){ 
        foreach ( $taxonomies as $term ) {
            $return[] = array(
                'name' => $term->name,
                'id'   =>  $term->term_id,
            );      
        }
    }
    return $return;
}
function townhub_addons_dashboard_posts_per_page(){

    $posts_per_page = townhub_addons_get_option('listings_per');
    if(!is_numeric($posts_per_page)) 
        return 5;
    else 
        return intval($posts_per_page);
}


function townhub_addons_listing_ad_positions($pos = ''){
    $positions = array(
                    'sidebar'=> esc_html__('Single Listing','townhub-add-ons'),
                    'archive'=> esc_html__('Listing Archive','townhub-add-ons'),
                    'category'=> esc_html__('Listing Category','townhub-add-ons'),
                    'search'=> esc_html__('Listing Search','townhub-add-ons'),
                    'home'=> esc_html__('Elementor Listings Slider','townhub-add-ons'),
                    'custom_grid' => esc_html__('Elementor Listings Grid','townhub-add-ons'),
                );
    $positions = apply_filters( 'townhub_addons_ad_positions', $positions );
    if(!empty($pos) && isset($positions[$pos])) return $positions[$pos];

    return $positions;
}

function townhub_addons_add_to_cart_link($product_id = 0, $quantity = 1){
    $checkout_page_id = esb_addons_get_wpml_option('checkout_page');
    // if(empty($quantity)) $quantity = 1;
    $args = array(
        'esb_add_to_cart' => $product_id,
        // 'quantity'   => $quantity,
        // '_wpnonce'          => wp_create_nonce( -1 ),
    );
    if($quantity > 1) $args['quantity'] = $quantity;
    $url = add_query_arg( $args, get_permalink($checkout_page_id));

    return wp_nonce_url($url, 'esb_add_to_cart');
}
function townhub_addons_get_calendar_type_dates($post_id = 0){
    $dates_string = get_post_meta( $post_id, ESB_META_PREFIX.'listing_dates', true );
    if(!empty($dates_string)){
        $event_dates = explode(";", $dates_string);
        $event_dates = array_filter($event_dates);
        // sort date asc
        asort($event_dates);
        return $event_dates;
    }
    return array();
}
function townhub_addons_get_event_dates($post_id = 0, $nexts = true){
    $event_dates = townhub_addons_get_calendar_type_dates( $post_id );
    if( empty($event_dates) ) 
        return array();
    $dates_metas = get_post_meta( $post_id, ESB_META_PREFIX.'listing_dates_metas', true );

    // old version
    $levent_time = get_post_meta( $post_id, ESB_META_PREFIX.'levent_time', true );

    $dates_ad_meta = array();
    $curr = current_time( 'Ymd' );
    foreach ($event_dates as $date) {
        if( $nexts === false || $nexts && $curr <= $date ){
            $metas = array(
                'start_date' => '',
                'start_time' => '',
                'end_date'  => '',
            );
            if(isset($dates_metas[$date])){
                if(isset($dates_metas[$date]['start_time'])){
                    $metas['start_time'] = $dates_metas[$date]['start_time'];
                }else{
                    $metas['start_time'] = $levent_time;
                }
                if(isset($dates_metas[$date]['end_date']) && !empty($dates_metas[$date]['end_date']) )
                    $metas['end_date'] = $dates_metas[$date]['end_date'];
            }else{
                $metas['start_time'] = $levent_time;
                $metas['end_date'] = townhub_addons_format_cal_date( $date );
            }

            $metas['start_date'] = townhub_addons_format_cal_date( $date ) .' ' . $metas['start_time'];
            if( empty($metas['end_date']) ){
                $metas['end_date'] = $metas['start_date'];
            }

            $dates_ad_meta[$date] = $metas;
        }
            
    }
    
    return $dates_ad_meta;
}
function townhub_addons_next_event_date($post_id = 0){
    $event_dates = townhub_addons_get_event_dates($post_id);
    if(empty($event_dates)) 
        return array();

    return reset($event_dates);
}

function townhub_addons_format_cal_date($date){
    if($date != '' && strlen($date) === 8){
        $date = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, -2);
    }
    return $date;
}
function townhub_addons_get_event_time_string($post_id = 0){
    $next_date = townhub_addons_next_event_date($post_id);
    if(empty($next_date)) 
        return '';
    return sprintf( __( 'Next event will begin on <span>%s</span> at <span>%s</span>', 'townhub-add-ons' ), date_i18n( get_option( 'date_format' ), strtotime( $next_date['start_date'] ) ), date_i18n( get_option( 'time_format' ), strtotime( $next_date['start_date'] ) ) );
}
function townhub_addons_loggedin_plans_options(){
    $results = array(
        // ''                      => __( 'None', 'townhub-add-ons' ),
        'logout_user'           => __( 'Logout user', 'townhub-add-ons' ),
        'for_viewers'           => _x( 'For viewers also', 'Listing type', 'townhub-add-ons' ),
        'not_authors'           => _x( 'Not for author', 'Listing type', 'townhub-add-ons' ),
    );

    $post_args = array(
        'post_type'             => 'lplan',
        'posts_per_page'        => -1,
        'orderby'               => 'date',
        'order'                 => 'DESC',
        'post_status'           => 'publish',
        'fields'                => 'ids',

        // 'suppress_filters'  => false,
    );
    $posts = get_posts( $post_args );
    if ( $posts ) {
        foreach ( $posts as $post ) {
            // $results[$post->ID] = apply_filters( 'the_title' , $post->post_title );
            $results[$post] = sprintf( __( '%s plan', 'townhub-add-ons' ), get_the_title( $post ), $post );
        }
    }

    $results = (array) apply_filters( 'esb_hide_on_plans', $results );

    return $results;
}
function townhub_addons_is_hide_on_plans($hide_on_plans = '', $post_id = 0){
    if($hide_on_plans == '') 
        return 'false';

    // $checkfor_views = strpos($hide_on_plans, "for_viewers");
    // if( false !== $checkfor_views ){
    //     if( $checkfor_views == 0 ){
    //         $viewer_plans = explode("for_viewers||", $hide_on_plans);
    //         $viewer_plans = $viewer_plans[1];
    //     }else{

    //     }
    // }
    $hide_on_plans = explode("||", $hide_on_plans);

    if( $post_id == 0 ) 
        $author_id = get_the_author_meta('ID');
    else
        $author_id = get_post_field( 'post_author', $post_id );
    // for admin
    if( 1 == 2 && townhub_addons_get_user_role($author_id) == 'administrator')
        return 'false';


    if( in_array('logout_user', $hide_on_plans) && !is_user_logged_in() ) return 'true';
    if( in_array('for_viewers', $hide_on_plans) ){
        $viewer_plan = Esb_Class_Membership::current_plan();
        if( empty($viewer_plan) || in_array( $viewer_plan , $hide_on_plans ) ){
            return 'true';
        }
    }
    if( false == in_array('not_authors', $hide_on_plans) ){
        $author_plan = Esb_Class_Membership::current_plan($author_id);
        if( in_array( $author_plan , $hide_on_plans ) ){
            if( get_current_user_id() == $author_id )
                return 'on-author';
            else
                return 'true';
        }
    }

    return 'false';
}
function townhub_addons_check_hide_on_logout_user($hide_on_plans = ''){
    if(is_user_logged_in()){
        return false;
    }else{
        $hide_on_plans = explode("||", $hide_on_plans);
        if( in_array('logout_user', $hide_on_plans) ) 
            return true;
    }
    return false;
}
function townhub_addons_payfast_frequency($duration = '', $period = ''){
    if($duration){
        switch ($period) {
            case 'day':
            case 'week':
                return 3;
                break;
            case 'month':
                if($duration > 3)
                    return 5;
                else if($duration > 2)
                    return 4;
                else 
                    return 3;
                break;
            case 'year':
                return 6;
                break;
            
        }
    }
    return 3; 
    // numeric:
    // 3- Monthly
    // 4- Quarterly
    // 5- Biannual
    // 6- Annual
}
function townhub_addons_alphanumeric($str = ''){
    return preg_replace('/[^a-zA-Z0-9]/', "", $str);
}

function townhub_addons_create_purchase_code($suffix = '')
{
    if (function_exists('com_create_guid'))
    {
        if($suffix != '') 
            return trim(com_create_guid(), '{}').'-'.$suffix;
        else
            return trim(com_create_guid(), '{}');
    }

    $format = '%04X%04X-%04X-%04X-%04X-%04X%04X%04X';
    if($suffix != '') $format .= '-'.$suffix;
    return sprintf($format, mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}
function townhub_addons_must_enqueue_media(){
    if( current_user_can( 'upload_files' ) ){
        $submit_page = esb_addons_get_wpml_option('submit_page');
        $edit_page = esb_addons_get_wpml_option('edit_page');
        $dashboard_page = esb_addons_get_wpml_option('dashboard_page');

        

        if( ( $submit_page!='' && is_page($submit_page) ) || ( $edit_page!='' && is_page($edit_page) ) || ( $dashboard_page!='' && is_page($dashboard_page) ) ) return true;
    }
    return false;
}
function townhub_addons_must_enqueue_editor(){
    $submit_page = esb_addons_get_wpml_option('submit_page');
    $edit_page = esb_addons_get_wpml_option('edit_page');
    // $dashboard_page = esb_addons_get_wpml_option('dashboard_page');

    
    if( ( $submit_page!='' && is_page($submit_page) ) || ( $edit_page!='' && is_page($edit_page) ) /*|| ( $dashboard_page!='' && is_page($dashboard_page) )*/ ) 
        return true;

    return false;
}
function townhub_addons_get_map_data($lid = 0){
    if($lid == 0) $lid = get_the_ID();
    $author_id = get_post_field( 'post_author', $lid );
    $rating = townhub_addons_get_average_ratings($lid);
    $wkhour = Esb_Class_Listing_CPT::parse_wkhours($lid);
    $price_from = get_post_meta( $lid, '_price', true );
    // $price_range = get_post_meta( $lid, ESB_META_PREFIX.'price_range', true );
    // $levent_date = get_post_meta( $lid, ESB_META_PREFIX.'levent_date', true );
    // $levent_time = get_post_meta( $lid, ESB_META_PREFIX.'levent_time', true );
    // $typeID      =  get_post_meta( $lid, ESB_META_PREFIX.'listing_type_id', true );
    // if(empty($typeID)) $typeID = esb_addons_get_wpml_option('default_listing_type', 'listing_type'); 
    

    $listing_post = array(
        'url'                       => get_the_permalink($lid),
        'rating'                    => $rating,
        'cat'                      => '',
        'cats'                      => array(),
        'title'                     => get_the_title( $lid ),
        
        // 'thumbnail'                 =>'',
        // 'phone'                     =>'',
        // 'latitude'                  =>'',
        // 'longitude'                 =>'',
        // 'address'                   =>'',
        // 'like_stats'                => townhub_addons_get_likes_stats($lid),
        // 'author_avatar'             => get_avatar_url( get_the_author_meta('user_email'), array('size'=>80, 'default'=>'https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=80') ),
        // get_avatar(get_the_author_meta('user_email'),'80','https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=80', get_the_author_meta( 'display_name' ) ),
        'author_url'                => get_author_posts_url( $author_id ),
        'author_name'               => get_the_author_meta('display_name', $author_id), // get_the_author(),

        'ID'                        => $lid,
        'status'                    => $wkhour['status'],
        'statusText'                => $wkhour['statusText'],
        'bookmarked'                => townhub_addons_already_bookmarked($lid),
        'price'                     => esc_html( townhub_addons_get_price_formated($price_from) ),
        'price_from'                => $price_from,
        'price_to'                  => get_post_meta( $lid, ESB_META_PREFIX.'price_to', true ),
        // 'price_from_formated'       => townhub_addons_get_price_formated(get_post_meta( $lid, ESB_META_PREFIX.'price_from', true )),
        // 'price_to_formated'         => townhub_addons_get_price_formated(get_post_meta( $lid, ESB_META_PREFIX.'price_to', true )),

        'verified'                  => get_post_meta( $lid, ESB_META_PREFIX.'verified', true ),
        

        // 'view'                      => Esb_Class_LStats::get_stats($lid),
        // 'levent_date'               => $levent_date,
        // 'levent_time'               => $levent_time,
        // 'event_date_text'           => $levent_date !='' ? sprintf( __( 'Next event will begin on <span>%s</span> at <span>%s</span>', 'townhub-add-ons' ), date_i18n( get_option( 'date_format' ), strtotime( $levent_date.' '.$levent_time ) ), date_i18n( get_option( 'time_format' ), strtotime( $levent_date.' '.$levent_time ) ) ) : '',
        

        

        'gmap_marker'               => '',
        'featured'                  => get_post_meta( $lid, ESB_META_PREFIX.'featured', true ),
        // 'typeID'                    => $typeID, 
        // 'is_ad'                     => $is_ad,
    );

    $listing_post['thumbnail'] = esc_url( townhub_addons_get_attachment_thumb_link( townhub_addons_get_listing_thumbnail( $lid ) ,'townhub-listing-grid') );

    

    $cats = get_the_terms($lid, 'listing_cat');
    if ( $cats && ! is_wp_error( $cats ) ){
        // get first cat
        $cat = reset($cats);
        $term_metas = townhub_addons_custom_tax_metas($cat->term_id); 
        if($term_metas['icon'] != ''){
            $listing_post['cat'] = '<div class="map-popup-location-category dis-flex '.$term_metas['color'].'"><i class="'.$term_metas['icon'].'"></i></div>';
        }
        // if($term_metas['gicon'] != ''){
        //     $listing_post['gmap_marker'] = wp_get_attachment_url( $term_metas['gicon'] ); // gmap_marker gicon
        // }
        
    }

    $listing_post['gmap_marker'] = esc_url( townhub_addons_get_attachment_thumb_link( townhub_addons_get_listing_marker( $lid ) ) );


    $listing_post['phone'] = get_post_meta( $lid, ESB_META_PREFIX.'phone', true );
    $listing_post['latitude'] = get_post_meta( $lid, ESB_META_PREFIX.'latitude', true );
    $listing_post['longitude'] = get_post_meta( $lid, ESB_META_PREFIX.'longitude', true );
    // $listing_post['address'] = addslashes( get_post_meta( $lid, ESB_META_PREFIX.'address', true ) );
    $listing_post['address'] = get_post_meta( $lid, ESB_META_PREFIX.'address', true );
    $listing_post['email'] = get_post_meta( $lid, ESB_META_PREFIX.'email', true );
    $listing_post['website'] = get_post_meta( $lid, ESB_META_PREFIX.'website', true );


    $listing_post = (array)apply_filters( 'townhub_listing_map_data', $listing_post );

    return $listing_post;
    // return json_decode(json_encode($listing_post));
}

add_action( 'wp_head', function(){
    if( is_singular( 'listing') ){
        global $post;
        townhub_addons_print_schema( $post->ID );
    }
}, 99 );

function townhub_addons_print_schema($lid = 0, $ltype = 0){
    if(empty($ltype)) $ltype = get_post_meta( $lid, ESB_META_PREFIX .'listing_type_id', true );
    $schemas = get_post_meta( $ltype, ESB_META_PREFIX.'schema_markup', true );

    $schemas = json_decode($schemas, true);
    if(empty($schemas)) return ;

    // var_dump($schemas);

    $schema_markup = array();
    foreach ($schemas as $key => $schema) {
        if( !empty($schema['name']) && !empty($schema['value']) ){
            if(is_array($schema['value'])){
                $childs = array();
                foreach ($schema['value'] as $child) {
                    if(is_array($child['value'])){
                        $childs_two = array();
                        foreach ($child['value'] as $child_two) {
                            if(!is_array($child_two['value'])){
                                $childs_two[$child_two['name']] = townhub_addons_parse_schema_value($lid, $child_two['value'], $schema['name'].'/'.$child['name'].'/'.$child_two['name']);
                            }
                        }
                        $childs[$child['name']] = $childs_two;
                    }else{
                        $childs[$child['name']] = townhub_addons_parse_schema_value($lid, $child['value'], $schema['name'].'/'.$child['name']);
                    }
                }
                $schema_markup[$schema['name']] = $childs;
            }else{
                // $value = 
                $schema_markup[$schema['name']] = townhub_addons_parse_schema_value($lid, $schema['value'], $schema['name']);
            }
            
        }
    }
    // var_dump($schema_markup);
    $schema_markup = (array) apply_filters( 'townhub_addons_schema_markup', $schema_markup, $lid );
    ?>
    <script type="application/ld+json"><?php echo json_encode($schema_markup); ?></script>
    <?php
}
function townhub_addons_parse_schema_value($id = 0, $value = '', $name = ''){
    $rating = townhub_addons_get_average_ratings($id);
    switch ($value) {
        case '{{title}}':
            $value = get_the_title( $id );
            break;
        case '{{excerpt}}':
            $value = apply_filters('the_excerpt', get_post_field('post_excerpt', $id)); // get_the_excerpt( $id );
            break;
        case '{{thumbnail}}':
            $value = get_the_post_thumbnail_url( $id, 'post-thumbnail' );
            break;
        case '{{image}}':
            $value = get_the_post_thumbnail_url( $id, 'full' );
            break;
        case '{{url}}':
            $value = get_the_permalink( $id );
            break;

        case '{{price}}':
            $value = get_post_meta( $id, '_price', true );
            break;

        case '{{phone}}':
            $value = get_post_meta( $id, '_cth_phone', true );
            break;
        case '{{website}}':
            $value = get_post_meta( $id, '_cth_website', true );
            break;
        case '{{email}}':
            $value = get_post_meta( $id, '_cth_email', true );
            break;
        case '{{address}}':
            $value = get_post_meta( $id, '_cth_address', true );
            break;
        case '{{latitude}}':
            $value = get_post_meta( $id, '_cth_latitude', true );
            break;
        case '{{longitude}}':
            $value = get_post_meta( $id, '_cth_longitude', true );
            break;
        case '{{reviewValue}}':
            $value = $rating['rating'];
            break;
        case '{{reviewCount}}':
            $value = $rating['count'];
            break;
        case '{{priceRange}}':
            $value = townhub_addons_schema_price_range( $id );
            break;
        case '{{lowPrice}}':
            $value = get_post_meta( $id, ESB_META_PREFIX.'price_from', true );
            break;
        case '{{highPrice}}':
            $value = get_post_meta( $id, ESB_META_PREFIX.'price_to', true );
            break;
        case '{{openingHours}}':
            $value = townhub_addons_schema_working_hours( $id );
            break;
        case '{{startDate}}':
            $value = townhub_addons_schema_startDate( $id );
            break;
        case '{{endDate}}':
            $value = townhub_addons_schema_endDate( $id );
            break;
        case '{{currency}}':
            $value = townhub_addons_get_currency();
            break;
        case '{{speakers/trainers}}':
            $value = townhub_addons_schema_speakers( $id );
            break;
    }
    return apply_filters( 'townhub_schema_value', $value, $id, $name );
}
function townhub_addons_schema_listing_metas(){
    $metas = array(
        '{{title}}'                     => __( 'Listing Title', 'townhub-add-ons' ),
        '{{excerpt}}'                     => __( 'Listing Excerpt', 'townhub-add-ons' ),
        '{{thumbnail}}'                     => __( 'Listing Thumbnail', 'townhub-add-ons' ),
        '{{image}}'                     => __( 'Listing Full Image', 'townhub-add-ons' ),
        '{{url}}'                     => __( 'Listing URL', 'townhub-add-ons' ),
        '{{phone}}'                     => __( 'Phone number', 'townhub-add-ons' ),
        '{{website}}'                     => __( 'Website', 'townhub-add-ons' ),
        '{{email}}'                     => __( 'Email', 'townhub-add-ons' ),
        '{{address}}'                     => __( 'Address', 'townhub-add-ons' ),
        '{{openingHours}}'                     => __( 'Opening Hours', 'townhub-add-ons' ),

        '{{reviewValue}}'                     => __( 'Review Value', 'townhub-add-ons' ),
        '{{reviewCount}}'                     => __( 'Review Count', 'townhub-add-ons' ),

        '{{priceRange}}'                     => __( 'Price Range', 'townhub-add-ons' ),
        '{{lowPrice}}'                     => __( 'Lowest price', 'townhub-add-ons' ),
        '{{highPrice}}'                     => __( 'Highest price', 'townhub-add-ons' ),
        '{{startDate}}'                     => __( 'Evant Start Date', 'townhub-add-ons' ),
        '{{endDate}}'                     => __( 'Evant End Date', 'townhub-add-ons' ),
        '{{price}}'                     => __( 'Listing price', 'townhub-add-ons' ),
        '{{currency}}'                     => __( 'Listing currency', 'townhub-add-ons' ),
        '{{speakers/trainers}}'                     => __( 'Speakers/Trainers', 'townhub-add-ons' ),

    );
    return (array)apply_filters( 'townhub_schema_listing_metas', $metas );
}

function townhub_addons_schema_price_range( $id = 0 ){

    $price_from = get_post_meta( $id, '_price', true );
    if($price_from != ''){
        $price_to = get_post_meta( $id, ESB_META_PREFIX.'price_to', true );
        if($price_to != '') 
            return $price_from.'-'.$price_to;
        else
            return $price_from;
    }else{
        $range = get_post_meta( $id, ESB_META_PREFIX.'price_range', true );
        $ranges = array(
            'none' => '',
            'inexpensive' => '$',
            'moderate' => '$$',
            'pricey' => '$$$',
            'ultrahigh' => '$$$$',
        );
        if(isset($ranges[$range])) 
            return $ranges[$range];
    }
    return '';
}  

function townhub_addons_schema_working_hours($post_ID = 0){

    $return = array(
        'Mo-Su',
    );
    if(!is_numeric($post_ID) || !$post_ID) return $return;
    $post_working_hours = Esb_Class_Listing_CPT::wkhours($post_ID, false);
    if( !empty($post_working_hours) ){
        
        $working_days = array(
            'Mon' => 'Mo',
            'Tue' => 'Tu',
            'Wed' => 'We',
            'Thu' => 'Th',
            'Fri' => 'Fr',
            'Sat' => 'Sa',
            'Sun' => 'Su',
        );
        $working_hours_arr = Esb_Class_Date::wkhours_select();
        $current_time_details = Esb_Class_Date::tz_details($post_working_hours['timezone']);
        // var_dump($current_time_details);
        $tz_offset = $current_time_details['tz_offset']/3600;

        $return['days_hours'] = array();
        
        foreach ($working_days as $day => $dayLbl) {

            $dayVals = $post_working_hours[$day];
            if(isset($dayVals['static'])){
                // if($dayVals['static'] == 'closeAllDay'){
                //     $return['days_hours'][$dayLbl] = 'close';
                // }else
                if($dayVals['static'] == 'openAllDay'){
                    $return['days_hours'][$dayLbl] = '00:00-24:00';
                }elseif($dayVals['static'] == 'enterHours' && isset($dayVals['hours']) && !empty($dayVals['hours'])){
                    $return['days_hours'][$dayLbl] = array();
                    foreach ($dayVals['hours'] as $hr) {
                        $return['days_hours'][$dayLbl][] = $hr['open'] .'-'. $hr['close'];
                    }
                }
                // end if $dayVals['static']
            }
            // end if isset($dayVals['static'])
        } 

        if(!empty($return['days_hours'])){
            $return_str = [];
            foreach ($return['days_hours'] as $lbl => $value) {
                if(!empty($value)){
                    if(is_array($value)){
                        $return_str[] = $lbl . ' ' . implode(',', $value);
                    }else
                        $return_str[] = $lbl . ' ' . $value;
                } 
            }

            return $return_str;
        }

    }
    // end if $post_working_hours

    return $return;

        
}

function townhub_addons_schema_startDate($post_id = 0){
    $next_date = townhub_addons_next_event_date($post_id);
    if(empty($next_date)) 
        return '';
    $timezone = get_post_meta( $post_id, ESB_META_PREFIX."wkh_tz", true );
    return townhub_addons_get_gmt_from_date( $next_date['start_date'], $timezone, 'c' );
}
function townhub_addons_schema_endDate($post_id = 0){
    $next_date = townhub_addons_next_event_date($post_id);
    if(empty($next_date)) 
        return '';

    $timezone = get_post_meta( $post_id, ESB_META_PREFIX."wkh_tz", true );
    return townhub_addons_get_gmt_from_date( $next_date['end_date'], $timezone, 'c' );
}
function townhub_addons_schema_speakers( $post_id = 0 ){
    $lmember = get_post_meta( $post_id, ESB_META_PREFIX.'lmember', true );
    if (!empty($lmember)) {
        $members_schema = array();
        foreach ((array)$lmember as $member) {
            $members_schema[] = array(
                '@type'=>'Person',
                'name'=> $member['name']
            );
        }
        return $members_schema;
    }
    return '';
}

function townhub_addons_get_wp_editor(){
    ob_start();

    wp_editor(
        '%%EDITORCONTENT%%',
        'customwpeditor',
        [
            'editor_class' => 'custom-wpeditor-wrap',
            'editor_height' => 200,
        ]
    );

    $editor = ob_get_clean();

    return $editor;
}


function townhub_addons_listing_ltags_options() {
    $ltags = array();
    $terms = get_terms( 
        array(
            'taxonomy' => 'listing_tag',
            'hide_empty' => false,
            'orderby'       => 'count',
            'order'       => 'DESC',
        ) 
    );
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        foreach ( $terms as $term ) {
            $ltags[$term->term_id] = $term->name;
        }
    }
    $ltags = (array) apply_filters( 'cth_filter_ltags_options', $ltags );

    return $ltags;
}

function townhub_addons_dashboard_screen($screen = ''){
    if($screen != '') 
        return add_query_arg( 'dashboard', $screen, get_permalink( esb_addons_get_wpml_option('dashboard_page') ) );
    else
        return get_permalink( esb_addons_get_wpml_option('dashboard_page') );
}

function townhub_addons_listing_max_guests($listing_id = 0){
    if($listing_id == 0) $listing_id = get_the_ID();
    $max_guest = get_post_meta( $listing_id, ESB_META_PREFIX.'max_guests', true );
    if(empty($max_guest)) $max_guest = 5;
    
    return apply_filters( 'cth_listing_max_guests', $max_guest, $listing_id );
}
function townhub_addons_get_base_currency(){
    $base_curr = townhub_addons_get_option('currency', 'USD');
    return array(
        'currency'          => $base_curr,
        'symbol'            => townhub_addons_get_option('currency_symbol','$'),
        'rate'              => '1.00',
        'sb_pos'            => townhub_addons_get_option('currency_pos','left'),
        'decimal'           => (int)townhub_addons_get_option('decimals','2'),
        'ths_sep'           => townhub_addons_get_option('thousand_sep',','),
        'dec_sep'           => townhub_addons_get_option('decimal_sep','.'),
    );
}
function townhub_addons_lcats_options( $with_child = false, $tax = 'listing_cat' ){
    $args = array(
            'taxonomy' => $tax,
            'hide_empty' => false,
        );
    if(false == $with_child) $args['parent'] = 0;
    // if(false == $with_child) $args['child_of'] = 0;
    
    $terms = get_terms( $args );

    // var_dump($args);
    // var_dump($terms);
    
    $results = array();
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        foreach ( $terms as $term ) {
            $results[$term->term_id] = $term->name;
        }
    }

    

    // $results = (array) apply_filters( 'esb_'.$tax.'_options', $results );

    return $results;
}

function townhub_addons_filter_cats($cats = '', $max_level = 0, $hide_empty = 'yes', $tax = 'listing_cat' ){
    if($cats == '') 
        return townhub_addons_get_listing_categories($max_level , $tax);

    $results = array();
    $terms = explode('||', $cats);
    $level = 0;
    foreach ($terms as $term) {
        $term_obj = get_term($term, $tax);
        if ( ! empty( $term_obj ) && ! is_wp_error( $term_obj ) ){

            $results[] = array( 'id'=>$term_obj->term_id, 'slug' => $term_obj->slug, 'name'=>$term_obj->name, 'level'=> 0 );
            if( $max_level != '' && $level < $max_level ){
                $ccats = townhub_addons_filter_child_cats($term_obj->term_id, $level+1 , $max_level, $hide_empty, $tax);

                $results = array_merge($results, $ccats);
            }
        } 
    }

    return $results;

}
function townhub_addons_filter_child_cats($cat = 0, $level = 0, $max_level = 0, $hide_empty = 'yes', $tax = 'listing_cat'){
    $args = array(
        'taxonomy'          => $tax,
        'hide_empty'        => $hide_empty == 'yes' ? true : false,
        'parent'            => $cat
    );
    $terms = get_terms( $args );
    $results = array();
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        foreach ( $terms as $term ) {
            $results[] = array( 'id'=>$term->term_id, 'slug' => $term->slug, 'name'=>$term->name, 'level'=> $level );
            if( $max_level != '' && $level < $max_level ){
                $ccats = townhub_addons_filter_child_cats($term->term_id, $level+1 , $max_level, $hide_empty, $tax);
                $results = array_merge($results, $ccats);
            }
        }
    }
    return $results;
}
function townhub_addons_custom_tax_metas($tax_id = 0, $tax = 'listing_cat'){
    $term_meta = get_term_meta( $tax_id, ESB_META_PREFIX.'term_meta', true );
    $metas = array(
        'icon'      => '',
        'color'      => '',
        'featured'      => '',
        'featured_url'      => '',
        'gicon'      => '',
        'gicon_url'      => '',
        'imgicon'      => '',
        'imgicon_url'      => '',
    );
    if(isset($term_meta['icon_class'])) $metas['icon'] = $term_meta['icon_class'];
    if(isset($term_meta['icolor'])){
        $metas['color'] = $term_meta['icolor'];
        if( $term_meta['icolor'] == 'custom' && isset($term_meta['cus_color']) ) $metas['color'] = 'cus-' . str_replace( '#', '', $term_meta['cus_color'] );
    }
    if(isset($term_meta['featured_img']) && isset($term_meta['featured_img']['id'])) $metas['featured'] = $term_meta['featured_img']['id'];
    if(isset($term_meta['featured_img']) && isset($term_meta['featured_img']['url'])) $metas['featured_url'] = $term_meta['featured_img']['url'];
    
    if(isset($term_meta['gmap_marker']) && isset($term_meta['gmap_marker']['id'])) $metas['gicon'] = $term_meta['gmap_marker']['id'];
    if(isset($term_meta['gmap_marker']) && isset($term_meta['gmap_marker']['url'])) $metas['gicon_url'] = $term_meta['gmap_marker']['url'];
    if( isset($term_meta['img_icon']) ) {
        if( isset($term_meta['img_icon']['id']) ){
            $metas['imgicon'] = $term_meta['img_icon']['id'];
        }
        if( isset($term_meta['img_icon']['url']) ){
            $metas['imgicon_url'] = $term_meta['img_icon']['url'];
        }
    }
    
    return $metas;

}
function townhub_addons_custom_tax_ltypes($tax_id = 0, $tax = 'listing_cat'){
    $term_meta = get_term_meta( $tax_id, ESB_META_PREFIX.'term_meta', true );
    if( isset($term_meta['ltypes_filter']) && !empty($term_meta['ltypes_filter']) && is_array($term_meta['ltypes_filter']) ){
        return array_filter($term_meta['ltypes_filter']);
    }
    return array();
}


function townhub_addons_get_listing_thumbnail($post_id = 0){
    $post_thumbnail_id = get_post_thumbnail_id( $post_id );
    if( !empty($post_thumbnail_id) ){
        return $post_thumbnail_id;
    }

    $cats = get_the_terms($post_id, 'listing_cat');
    if ( $cats && ! is_wp_error( $cats ) ){
        // get first cat
        $cat = reset($cats);
        $term_metas = townhub_addons_custom_tax_metas($cat->term_id); 
        if($term_metas['featured'] != '')
            $post_thumbnail_id = $term_metas['featured'];
    }
    // $terms = get_the_terms( $post_id, 'listing_cat' );
    // if( $terms && ! is_wp_error( $terms ) ){
    //     $fterm = reset($terms);
    //     $term_meta = get_term_meta( $fterm->term_id, ESB_META_PREFIX.'term_meta', true );
    //     if( isset($term_meta['featured_img']) && !empty($term_meta['featured_img']) && !empty($term_meta['featured_img']['id']) )
    //         $post_thumbnail_id = $term_meta['featured_img']['id'];
    // }
    if( !empty($post_thumbnail_id) ){
        return $post_thumbnail_id;
    }

    $default_thumbnail = townhub_addons_get_option('default_thumbnail');
    if( $default_thumbnail && !empty($default_thumbnail['id']) )
        return $default_thumbnail['id'];
}
function townhub_addons_get_listing_marker($post_id = 0){
    $post_thumbnail_id = '';
    if( townhub_addons_get_option('map_provider') == 'googlemap' && townhub_addons_get_option('use_dfmarker') != 'yes' ){
        $post_thumbnail_id = get_post_thumbnail_id( $post_id );
        if( townhub_addons_get_option('use_logomk') == 'yes'  ){
            $llogo = get_post_meta( $post_id, ESB_META_PREFIX.'llogo', true );
            if( !empty($llogo) ){
                if( !is_array($llogo) ) $llogo = explode(",", $llogo);
                $post_thumbnail_id = $llogo[0];
            }
        }
        if( !empty($post_thumbnail_id) ){
            return apply_filters( 'cth_listing_marker_id', $post_thumbnail_id, $post_id );
        }
    }
    
    $cats = get_the_terms($post_id, 'listing_cat');
    if ( $cats && ! is_wp_error( $cats ) ){
        // get first cat
        $cat = reset($cats);
        $term_metas = townhub_addons_custom_tax_metas($cat->term_id); 
        if($term_metas['gicon'] != '')
            $post_thumbnail_id = $term_metas['gicon'];
    }
    if( !empty($post_thumbnail_id) ){
        return apply_filters( 'cth_listing_marker_id', $post_thumbnail_id, $post_id );
    }

    $default_thumbnail = townhub_addons_get_option('gmap_marker');
    if( $default_thumbnail && !empty($default_thumbnail['id']) )
        return apply_filters( 'cth_listing_marker_id', $default_thumbnail['id'], $post_id );
}
function townhub_addons_format_pricing_yearly_sale($reduction = 0){
    if(!empty($reduction)){
        return sprintf(__( '-%d%%', 'townhub-add-ons' ), $reduction);
    }
    return '';
}
function townhub_addons_calculate_yearly_price($price = 0, $period = 'month', $interval = 1, $sale = 0){
    // period - day/week/month/year
    if( empty($price) ||  empty($interval) ) return 0;
    $price = floatval($price);
    $interval = intval($interval);
    if($price <= 0 || $interval <= 0) return 0;
    switch ($period) {
        case 'day':
            $yearly_price = 365*($price/$interval);
            break;
        case 'week':
            $yearly_price = 52*($price/$interval);
            break;
        case 'month':
            $yearly_price = 12*($price/$interval);
            break;
        default:
            $yearly_price = 1*($price/$interval);
            break;
    }
    return $yearly_price * (100 - $sale)/100;
}

function townhub_addons_get_tour_slots($listing_id = 0, $checkin = ''){
    if( empty($checkin) ) return false;
    $date = Esb_Class_Date::format($checkin, 'Ymd');
    $listing_dates_metas = get_post_meta( $listing_id, ESB_META_PREFIX.'listing_dates_metas', true );

    if( isset($listing_dates_metas[$date]['slots']) && is_array( $listing_dates_metas[$date]['slots'] ) && !empty( $listing_dates_metas[$date]['slots'] ) ){
        return $listing_dates_metas[$date]['slots'];
    }
    return false;
}

function townhub_addons_get_checkinout_dates($checkin, $checkout){
    $checkin = townhub_addons_format_cal_date($checkin);
    $checkout = townhub_addons_format_cal_date($checkout);
    // get calendar dates
    $checkoutObj = new DateTime($checkout);
    $dates = array();
    for ($i=0; $i < 1000 ; $i++) { 
        $temp = Esb_Class_Date::modify( $checkin, $i, 'Y-m-d' );
        $tempObj = new DateTime($temp);
        if( $tempObj > $checkoutObj ) break;
        $dates[] = $temp;
    }
    return $dates;
}
function townhub_addons_escapse_class($str){
    $str = preg_replace('/\s/', '_', $str);
    // $str = preg_replace('/\W/', 'unw', $str);

    
    return $str;
}

function townhub_addons_nofollow($content) {
    $content = preg_replace_callback( '~<(a\s[^>]+)>~isU', "townhub_addons_cb2", $content );
    return $content;
}

function townhub_addons_cb2($match) { 
    list($original, $tag) = $match;   // regex match groups

    // $my_folder =  "/hostgator";       // re-add quirky config here
    // $blog_url = "http://localhost/";

    if (strpos($tag, "nofollow")) {
        return $original;
    }
    // elseif (strpos($tag, $blog_url) && (!$my_folder || !strpos($tag, $my_folder))) {
    //     return $original;
    // }
    else {
        return "<$tag rel='nofollow'>";
    }
}
function townhub_addons_listing_get_address($id = null){
    if( null == $id ) $id = get_the_ID();
    $address = get_post_meta( $id, ESB_META_PREFIX.'address', true );
    $address_length = (int)townhub_addons_get_option('address_length');
    if( empty($address_length) ) return esc_html($address);

    if( strlen($address) <= $address_length ) return esc_html($address);

    $exwords = explode( ',', $address );


    $new_string = '';
    foreach($exwords as $part){
        $part = trim($part);
        if( !empty($part) ){
            if( !empty($new_string) ){
                $tmp = $new_string . ', '.$part;
                if( strlen($tmp) <= $address_length ){
                    $new_string = $tmp;
                }else{
                    break;
                }
                
            }else{
                if( strlen($part) <= $address_length ){
                    $new_string = $part;
                }else{
                    $new_string = substr( $part, 0, $address_length );
                    break;
                }
                
            }
        }
        
    }

    return esc_html($new_string);
    

    $subex = substr( $address, 0, $address_length + 1 );
    $exwords = explode( ',', $subex );
    $excut = - ( strlen( $exwords[ count( $exwords ) - 1 ] ) );
    if ( $excut < 0 ) {
        $return = substr( $subex, 0, $excut - 1  );
    } else {
        $return = $subex;
    }
    // $return .= esc_html_x( '...', 'Listing address suffix', 'townhub-add-ons' );
    return esc_html($return);

    // // if has comma address
    // $comma_pos = strrpos($address, ",");
    // if( $comma_pos === false ){

    //     $subex = substr( $address, 0, $address_length - 5 );
    //     $exwords = explode( ' ', $subex );
    //     $excut = - ( strlen( $exwords[ count( $exwords ) - 1 ] ) );
    //     if ( $excut < 0 ) {
    //         $return = substr( $subex, 0, $excut );
    //     } else {
    //         $return = $subex;
    //     }
    //     $return .= esc_html_x( '...', 'Listing address suffix', 'townhub-add-ons' );
    //     return $return;
    // }else{
    //     if( $comma_pos <= $address_length ){
    //         return substr( $address, 0, $comma_pos - 1 )
    //     }else{
    //         $subex = substr( $address, 0, $comma_pos - 1 );
    //         $comma_pos_new = strrpos($subex, ",");
    //     }
    // }
}

function townhub_addons_get_term_link($term_id = null, $tax = 'listing_cat', $with_ltype = true ){
    $tlink = get_term_link($term_id, $tax);
    if( $with_ltype ){
        $term_meta = get_term_meta( $term_id, ESB_META_PREFIX.'term_meta', true );
        if( isset($term_meta['ltype']) && !empty($term_meta['ltype']) ){
            $ltpost = get_post( $term_meta['ltype'] );
            if( $ltpost && $ltpost->post_type == 'listing_type' ){
                $tlink = add_query_arg( 'ltype', $ltpost->ID, $tlink  );
            }
        }
    }
    
    return esc_url( $tlink );
}

function townhub_addons_get_login_button_attrs( $message = '', $redirect_to = '' ){
    $attrs = array(
        'url'           => '#',
        'class'         => 'logreg-modal-open',
    );
    if( townhub_addons_get_option('dis_log_reg_modal') == 'yes' ){
        $redirect_url = network_site_url( 'wp-login.php', 'login' );

        $redirect_page_id = esb_addons_get_wpml_option( 'login_page', 'page', false );
        if( !empty($redirect_page_id) ){
            $redirect_url = get_permalink( $redirect_page_id );
        }

        if ( ! empty( $message ) ) {
            $redirect_url = add_query_arg( 'login', $message, $redirect_url );
        }
        if ( ! empty( $redirect_to ) ) {
            if( $redirect_to == 'current' ){
                $redirect_url = add_query_arg( 'redirect_to', townhub_addons_get_current_url(), $redirect_url );
            }else{
                $redirect_url = add_query_arg( 'redirect_to', $redirect_to, $redirect_url );
            }
        }

        $attrs['class'] = 'logreg-open_url';
        $attrs['url'] = $redirect_url;
    }
    return $attrs;
}
function townhub_addons_get_woo_cats(){
    $return = array();
    $taxonomies = get_terms( array(
        'taxonomy'          => 'product_cat',
        'hide_empty'        => false,
        'parent'            => 0,
    ) );
    if ( $taxonomies && ! is_wp_error( $taxonomies ) ){ 
        foreach ( $taxonomies as $term ) {
            $return[] = array(
                'name' => $term->name,
                'id'   =>  $term->term_id,
            );      
        }
    }
    return $return;
}

function townhub_addons_get_price_based(){
    $vals = array(
        'listing'       => _x('Per listing', 'Listing type', 'townhub-add-ons'),
        'per_person'       => _x('Per person', 'Listing type', 'townhub-add-ons'),
        'per_night'       => _x('Per night', 'Listing type', 'townhub-add-ons'),
        'night_person'       => _x('Per person/night', 'Listing type', 'townhub-add-ons'),
        'per_day'       => _x('Per day', 'Listing type', 'townhub-add-ons'),
        'day_person'       => _x('Per person/day', 'Listing type', 'townhub-add-ons'),
        'per_hour'       => _x('Per hour-slot', 'Listing type', 'townhub-add-ons'),
        'hour_person'       => _x('Per person/hour-slot', 'Listing type', 'townhub-add-ons'),
        'none'       => _x('No listing price', 'Listing type', 'townhub-add-ons'),
    );
    return (array)apply_filters( 'cth_get_price_based', $vals );
}

function townhub_addons_is_hosted_video($url){
    if( '' != $url ){
        $ext = preg_replace("/(.+)?\.(\w+)(\?.+)?$/m", "$2", $url);
        if( in_array($ext, array('mp4','m4v','webm','ogv','wmv','flv') ) ){
            return true;
        }
    }
    return false;
}

function townhub_addons_cookie_accepted(){
    if( townhub_addons_get_option('cookie_provider') == 'none' ){
        return true;
    }else if( townhub_addons_get_option('cookie_provider') == 'cookie-notice' && function_exists('cn_cookies_accepted') && cn_cookies_accepted() ){
        return true;
    }
    
    return false;
}

add_filter( 'townhub_addons_filter_ltype', function($ltype){
    if( is_singular('page') ){
        $page_ltype = get_post_meta( get_queried_object_id(), '_cth_ltype', true );
        if( !empty($page_ltype) ){
            return $page_ltype;
        }
    }
    return $ltype;
} );
add_action( 'townhub_addons_filter_before', function(){
    if( is_singular('page') ){
        $page_ltype = get_post_meta( get_queried_object_id(), '_cth_ltype', true );
        if( !empty($page_ltype) ){
            echo '<input type="hidden" name="ltype" value="' . $page_ltype . '">';
        }
    }
} );
add_filter( 'townhub_addons_custom_loop_args', function($args){
    if( is_singular('page') ){
        $page_ltype = get_post_meta( get_queried_object_id(), '_cth_ltype', true );
        if( !empty($page_ltype) ){
            if( !empty($args['meta_query']) && is_array($args['meta_query']) ){
                $args['meta_query'][] = array(
                        'key'           => ESB_META_PREFIX.'listing_type_id',
                        'value'         => $page_ltype,
                        'type'          => 'NUMERIC',
                    );
            }else{
                $args['meta_query'] = array(
                    array(
                        'key'           => ESB_META_PREFIX.'listing_type_id',
                        'value'         => $page_ltype,
                        'type'          => 'NUMERIC',
                    ),
                );
            }
        }
    }
    return $args;
} );

