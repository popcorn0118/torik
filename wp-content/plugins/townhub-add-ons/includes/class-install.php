<?php 
/* add_ons_php */

defined( 'ABSPATH' ) || exit;


class Esb_Class_Install{
    public static function install(){
        $result = add_role( 
                        'l_customer', 
                        // __( 'Listing Customer', 'townhub-add-ons'), 
                        _x( 'Listing Customer', 'User role', 'townhub-add-ons' ),
                        array(
                            'level_0'                => true, // Subscriber
                            'read' => true, 
                        )
                        
                    );
        if($result === null) echo  __('Oh... the l_customer role already exists.','townhub-add-ons'); 
       

        $result =   add_role( 
                        'listing_author', 
                        _x( 'Listing Author', 'User role', 'townhub-add-ons' ),
                        // __( 'Listing Author', 'townhub-add-ons'),
                        array(
                            'level_2'                => true, // Author
                            'level_1'                => true, // Contributor
                            'level_0'                => true, // Subscriber

                            'delete_posts'         => true,  // true allows this capability // Use false to explicitly deny
                            'delete_published_posts'   => true,
                            'edit_posts' => true, 
                            'edit_published_posts' => true, 
                            // 'edit_private_posts' => true,
                            'publish_posts' => false, 
                            'read' => true, 
                            'upload_files' => true, 
                        )
                        
                    );
        if($result === null) echo  __('Oh... the listing_author role already exists.','townhub-add-ons'); 
        // add submit_listing cap to administrator and listing_author role
        global $wp_roles;
        if ( ! isset( $wp_roles ) ) {
            $wp_roles = new WP_Roles();
        }
        $wp_roles->add_cap( 'listing_author', 'submit_listing' );
        $wp_roles->add_cap( 'administrator', 'submit_listing' );
        // if need admin can edit each other posts
        // $wp_roles->add_cap( 'administrator', 'edit_others_posts' );

        

        // http://www.wpexplorer.com/wordpress-page-templates-plugin/
        $exists_options = get_option( 'townhub-addons-options', array() );
        // add new pages
        // - page args
        $_p = array();
        // $_p['post_content']   = '';
        $_p['post_status']    = 'publish';
        $_p['post_type']      = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status']    = 'closed';

        $_p['page_template']    = 'home-page.php';

        // - dashboard page
        $dashboard_page_title = __('Dashboard','townhub-add-ons');
        $dashboard_page = get_page_by_title($dashboard_page_title);
        if (!$dashboard_page){
            $_p['post_title']     = $dashboard_page_title;

            $_p['post_content']   = '[listing_dashboard_page]';
            // Insert the post into the database
            $exists_options['dashboard_page'] = wp_insert_post($_p); // return post Id or 0 or WP_Error if not success
            // if(!is_wp_error($dashboard_page_id)){
            //   //the post is valid
            // }else{
            //   //there was an error in the post insertion, 
            //   echo $dashboard_page_id->get_error_message();
            // }

        }else{
            // the plugin may have been previously active and the page may just be trashed...
            // $dashboard_page_id = $dashboard_page->ID;

            //make sure the page is not trashed...
            $dashboard_page->post_status = 'publish';
            $dashboard_page->post_content = '[listing_dashboard_page]';
            // $dashboard_page->page_template = 'home-page.php';

            $exists_options['dashboard_page'] = wp_update_post($dashboard_page); // return post Id or 0 if not success
        }

        // - submit page
        $submit_page_title = __('Submit Listing','townhub-add-ons');
        $submit_page = get_page_by_title($submit_page_title);
        if (!$submit_page){
            $_p['post_title']     = $submit_page_title;
            $_p['post_content']   = '[listing_submit_page]';
            // Insert the post into the database
            $exists_options['submit_page'] = wp_insert_post($_p); // return post Id or 0 or WP_Error if not success
        }else{
            //make sure the page is not trashed...
            $submit_page->post_status = 'publish';
            $submit_page->post_content = '[listing_submit_page]';
            // $submit_page->page_template = 'home-page.php';
            $exists_options['submit_page'] = wp_update_post($submit_page); // return post Id or 0 if not success
        }
        // - edit listing page
        $page_title = __('Edit Listing','townhub-add-ons');
        $page_post = get_page_by_title($page_title);
        if (!$page_post){
            $_p['post_title']     = $page_title;
            $_p['post_content']   = '[listing_edit_page]';
            // Insert the post into the database
            $exists_options['edit_page'] = wp_insert_post($_p); // return post Id or 0 or WP_Error if not success
        }else{
            //make sure the page is not trashed...
            $page_post->post_status = 'publish';
            $page_post->post_content = '[listing_edit_page]';
            // $edit_page->page_template = 'home-page.php';
            $exists_options['edit_page'] = wp_update_post($page_post); // return post Id or 0 if not success
        }

        // - edit payment page
        // $page_title = __('Listing Payment','townhub-add-ons');
        // $page_post = get_page_by_title($page_title);
        // if (!$page_post){
        //     $_p['post_title']     = $page_title;
        //     // Insert the post into the database
        //     $exists_options['payment_page'] = wp_insert_post($_p); // return post Id or 0 or WP_Error if not success
        // }else{
        //     //make sure the page is not trashed...
        //     $page_post->post_status = 'publish';
        //     // $edit_page->page_template = 'home-page.php';
        //     $exists_options['payment_page'] = wp_update_post($page_post); // return post Id or 0 if not success
        // }

        // - checkout page
        $page_title = __('Listing Checkout','townhub-add-ons');
        $page_post = get_page_by_title($page_title);
        if (!$page_post){
            $_p['post_title']     = $page_title;
            $_p['post_content']   = '[listing_checkout_page]';
            // Insert the post into the database
            $exists_options['checkout_page'] = wp_insert_post($_p); // return post Id or 0 or WP_Error if not success
        }else{
            //make sure the page is not trashed...
            $page_post->post_status = 'publish';
            $page_post->post_content = '[listing_checkout_page]';
            $exists_options['checkout_page'] = wp_update_post($page_post); // return post Id or 0 if not success
        }
        // update plugin options
        $return = update_option( 'townhub-addons-options', $exists_options );

        

        $fresh_installed = false;

        $demo_azp_css = '{}';
        $demo_azp_css = json_decode($demo_azp_css, true);
        if( get_option( 'townhub-addons-version', 'fresh_installed' ) == 'fresh_installed' ){
            $townhub_addons_version = '1.0.0'; 
            $fresh_installed = true;

            $upload_path = townhub_addons_upload_dirs('azp', 'css');
            // $demo_azp_css = json_decode($demo_azp_css, true);
            if( null != $demo_azp_css){
                // set default css
                update_option( 'azp_csses', $demo_azp_css );
                $css_file = $upload_path . DIRECTORY_SEPARATOR . "listing_types.css";
                if(!file_exists($css_file))
                    @file_put_contents($css_file, Esb_Class_Listing_Type_CPT::get_azp_css() );
            }

            self::chat_system();
            self::notification_system();
            self::booking_system();
            self::listing_stats();
            self::author_earning();

            self::working_hours();

            self::custom_logreg();

        }else{
            
            // $demo_azp_css = json_decode($demo_azp_css, true);
            if( get_option('azp_csses') == '' && null != $demo_azp_css){
                // set default css
                update_option( 'azp_csses', $demo_azp_css );
                $upload_path = townhub_addons_upload_dirs('azp', 'css');
                $css_file = $upload_path . DIRECTORY_SEPARATOR . "listing_types.css";
                if(!file_exists($css_file))
                    @file_put_contents($css_file, Esb_Class_Listing_Type_CPT::get_azp_css() );
            }

            self::notification_system();
            self::booking_system();
            self::listing_stats();
            self::author_earning();

            self::working_hours();

            self::custom_logreg();

            self::update_invoices();
            self::leventdate_start();
            self::lmenu_id();

        }
        // else{
        //     $townhub_addons_version = get_option( 'townhub-addons-version');
        // }
        // end message
        update_option( 'townhub-addons-version', ESB_VERSION );
            
    }

    public static function update(){

        if(!wp_doing_ajax()){
            if( version_compare(get_option( 'townhub-addons-version' ), '1.0.3', '<') ){
                self::listing_sub();
                update_option( 'townhub-addons-version', '1.0.3' );
            }
            if( version_compare(get_option( 'townhub-addons-version' ), '1.0.6', '<') ){
                self::esc_chat_reply();
                update_option( 'townhub-addons-version', '1.0.6' );
            }
            
            
            
            if( version_compare(get_option( 'townhub-addons-version' ), '1.2.9', '<') ){
                self::custom_logreg();
                update_option( 'townhub-addons-version', '1.2.9' );
            }
            if( version_compare(get_option( 'townhub-addons-version' ), '1.4.7.3', '<') ){
                self::lmenu_id();
                update_option( 'townhub-addons-version', '1.4.7.3' );
            }

            if( version_compare(get_option( 'townhub-addons-version' ), '1.4.2', '<') ){
                
                self::leventdate_start();
                self::update_bookings();
                update_option( 'townhub-addons-version', '1.4.2' );
            }
            if( version_compare(get_option( 'townhub-addons-version' ), '1.3.9', '<') ){
                
                self::update_invoices();
                update_option( 'townhub-addons-version', '1.3.9' );
            }
        }
    }

    public static function custom_logreg(){
        // http://www.wpexplorer.com/wordpress-page-templates-plugin/
        $exists_options = get_option( 'townhub-addons-options', array() );
        // add new pages
        // - page args
        $_p = array();
        // $_p['post_content']   = '';
        $_p['post_status']    = 'publish';
        $_p['post_type']      = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status']    = 'closed';

        $_p['page_template']    = 'page-sidebar-no.php';

        // - dashboard page
        $dashboard_page_title = 'Login Page';
        $dashboard_page = get_page_by_title($dashboard_page_title);
        if (!$dashboard_page){
            $_p['post_title']     = $dashboard_page_title;

            $_p['post_content']   = '[cthlogin_page]';
            // Insert the post into the database
            $login_page_id = wp_insert_post($_p);
        }else{
            // the plugin may have been previously active and the page may just be trashed...
            // $dashboard_page_id = $dashboard_page->ID;

            //make sure the page is not trashed...
            $dashboard_page->post_status = 'publish';
            $dashboard_page->post_content = '[cthlogin_page]';
            // $dashboard_page->page_template = 'home-page.php';

            $login_page_id = wp_update_post($dashboard_page); // return post Id or 0 if not success
        }
        update_post_meta( $login_page_id, '_cth_show_page_title', 'yes' );
        update_post_meta( $login_page_id, '_cth_show_page_header', 'yes' );
        update_post_meta( $login_page_id, '_cth_page_header_bg', 'https://townhub.cththemes.com/wp-content/uploads/2019/08/11.jpg' );

        
        $exists_options['login_page'] = $login_page_id;

        // - submit page
        $submit_page_title = 'Register Page';
        $submit_page = get_page_by_title($submit_page_title);
        if (!$submit_page){
            $_p['post_title']     = $submit_page_title;
            $_p['post_content']   = '[cthregister_page]';
            // Insert the post into the database
            $register_page_id = wp_insert_post($_p); // return post Id or 0 or WP_Error if not success
        }else{
            //make sure the page is not trashed...
            $submit_page->post_status = 'publish';
            $submit_page->post_content = '[cthregister_page]';
            // $submit_page->page_template = 'home-page.php';
            $register_page_id = wp_update_post($submit_page); // return post Id or 0 if not success
        }
        update_post_meta( $register_page_id, '_cth_show_page_title', 'yes' );
        update_post_meta( $register_page_id, '_cth_show_page_header', 'yes' );
        update_post_meta( $register_page_id, '_cth_page_header_bg', 'https://townhub.cththemes.com/wp-content/uploads/2019/08/11.jpg' );
        $exists_options['register_page'] = $register_page_id;
        // - edit listing page
        $page_title = 'Forget Password Page';
        $page_post = get_page_by_title($page_title);
        if (!$page_post){
            $_p['post_title']     = $page_title;
            $_p['post_content']   = '[cthforget_pwd_page]';
            // Insert the post into the database
            $forget_pwd_page_id = wp_insert_post($_p); // return post Id or 0 or WP_Error if not success
        }else{
            //make sure the page is not trashed...
            $page_post->post_status = 'publish';
            $page_post->post_content = '[cthforget_pwd_page]';
            // $edit_page->page_template = 'home-page.php';
            $forget_pwd_page_id = wp_update_post($page_post); // return post Id or 0 if not success
        }
        update_post_meta( $forget_pwd_page_id, '_cth_show_page_title', 'yes' );
        update_post_meta( $forget_pwd_page_id, '_cth_show_page_header', 'yes' );
        update_post_meta( $forget_pwd_page_id, '_cth_page_header_bg', 'https://townhub.cththemes.com/wp-content/uploads/2019/08/11.jpg' );
        $exists_options['forget_pwd_page'] = $forget_pwd_page_id;

        // - checkout page
        $page_title = 'Reset Password Page';
        $page_post = get_page_by_title($page_title);
        if (!$page_post){
            $_p['post_title']     = $page_title;
            $_p['post_content']   = '[cthreset_pwd_page]';
            // Insert the post into the database
            $reset_pwd_page_id = wp_insert_post($_p); // return post Id or 0 or WP_Error if not success
        }else{
            //make sure the page is not trashed...
            $page_post->post_status = 'publish';
            $page_post->post_content = '[cthreset_pwd_page]';
            $reset_pwd_page_id = wp_update_post($page_post); // return post Id or 0 if not success
        }
        update_post_meta( $reset_pwd_page_id, '_cth_show_page_title', 'yes' );
        update_post_meta( $reset_pwd_page_id, '_cth_show_page_header', 'yes' );
        update_post_meta( $reset_pwd_page_id, '_cth_page_header_bg', 'https://townhub.cththemes.com/wp-content/uploads/2019/08/11.jpg' );
        $exists_options['reset_pwd_page'] = $reset_pwd_page_id;
        // update plugin options
        $return = update_option( 'townhub-addons-options', $exists_options );

    }

    public static function esc_chat_reply(){
        global $wpdb;

        $chat_reply_table = $wpdb->prefix . 'cth_chat_reply';

        $replies = $wpdb->get_results( 
            "
            SELECT      cr_id, reply
            FROM        $chat_reply_table
            "
        ); 

        if ( $replies ) 
        {

            foreach ( $replies as $rep ) 
            { 
                $wpdb->update( 
                    $chat_reply_table, 
                    array( 
                        'reply' => esc_html( $rep->reply )
                    ), 
                    array( 'cr_id' => $rep->cr_id ), 
                    array( 
                        '%s',
                    ), 
                    array( '%d' ) 
                );
            } 
        }
    }

    public static function listing_sub(){
        $listings = get_posts( array(
            'fields'            => 'ids', 
            'posts_per_page'    => -1, 
            'post_type'         => 'listing',
            'post_status'       => array( 'publish', 'pending', 'draft', 'future' ),
        ) );
        if ( $listings ) {
            foreach ( $listings as $lid ) {
                $lauthor_id = get_post_field( 'post_author', $lid );
                $author_role = townhub_addons_get_user_role( $lauthor_id );
                if($author_role == 'listing_author'){
                    $lauthor_plan = Esb_Class_Membership::current_plan($lauthor_id);
                    $lauthor_sub = Esb_Class_Membership::current_sub($lauthor_id);
                    Esb_Class_Listing_CPT::update_listing_plan_metas($lid, $lauthor_sub, $lauthor_plan );
                }
                
            }
        }
    }

    public static function update_bookings(){
        $posts = get_posts( array(
            'fields'            => 'ids', 
            'posts_per_page'    => -1, 
            'post_type'         => 'lbooking',
            'post_status'       => array( 'publish', 'pending', 'draft', 'future' ),
        ) );
        if ( $posts ) {
            foreach ( $posts as $pid ) {
                $payment_method = get_post_meta( $pid, ESB_META_PREFIX.'payment_method', true );
                if( $payment_method == 'request' ){
                    update_post_meta( $pid, ESB_META_PREFIX.'bk_form_type', 'inquiry' );
                }else{
                    update_post_meta( $pid, ESB_META_PREFIX.'bk_form_type', 'checkout' );
                }
            }
        }
    }

    public static function update_invoices(){
        $posts = get_posts( array(
            'fields'            => 'ids', 
            'posts_per_page'    => -1, 
            'post_type'         => 'cthinvoice',
            'post_status'       => array( 'publish', 'pending', 'draft', 'future' ),
        ) );
        if ( $posts ) {
            foreach ( $posts as $pid ) {
                $order_id = get_post_meta( $pid, ESB_META_PREFIX.'order_id', true );
                $for_listing_ad = get_post_meta( $pid, ESB_META_PREFIX.'for_listing_ad', true );
                if( $for_listing_ad !== 'yes' ){
                    update_post_meta( $pid, ESB_META_PREFIX.'subtotal', get_post_meta( $order_id, ESB_META_PREFIX.'subtotal', true ) );
                    update_post_meta( $pid, ESB_META_PREFIX.'subtotal_vat', get_post_meta( $order_id, ESB_META_PREFIX.'subtotal_vat', true ) );
                    update_post_meta( $pid, ESB_META_PREFIX.'tax', get_post_meta( $order_id, ESB_META_PREFIX.'subtotal_vat', true ) );
                    update_post_meta( $pid, ESB_META_PREFIX.'price_total', get_post_meta( $order_id, ESB_META_PREFIX.'price_total', true ) );
                }else{
                    $ad_package_id = get_post_meta( $order_id, ESB_META_PREFIX.'plan_id', true );
                    $prices             = townhub_addons_get_plan_prices($ad_package_id);
        
                    $ad_package = get_term( $ad_package_id, 'cthads_package' );
                    if ( !empty( $ad_package ) && !is_wp_error( $ad_package ) ){
                        $prices             = townhub_addons_get_plan_prices(0, get_term_meta( $ad_package->term_id, ESB_META_PREFIX.'ad_price', true ) );
                    } 
                    update_post_meta( $pid, ESB_META_PREFIX.'subtotal', $prices['price'] );
                    update_post_meta( $pid, ESB_META_PREFIX.'subtotal_vat', $prices['tax'] );
                    update_post_meta( $pid, ESB_META_PREFIX.'tax', $prices['tax'] );
                    update_post_meta( $pid, ESB_META_PREFIX.'price_total', $prices['total'] );
                }
            }
        }
    }
    public static function lmenu_id(){
        $listings = get_posts( array(
            'fields'            => 'ids', 
            'posts_per_page'    => -1, 
            'post_type'         => 'listing',
            'post_status'       => array( 'publish', 'pending', 'draft', 'future' ),
            // 'meta_query' => array(
            //     array(
            //         'key'     => '_cth_resmenus',
            //         'value'   => '',
            //         'compare' => '!=',
            //     ),
            // ),
        ) );
        if ( $listings ) {
            
            foreach ( $listings as $lid ) {
                $old_menus = get_post_meta( $lid, '_cth_resmenus', true );
                $new_menus = array();
                if( !empty($old_menus) && is_array($old_menus) ){
                    
                    foreach ($old_menus as $menu) {
                        if( !isset($menu['_id']) || empty($menu['_id'])) $menu['_id'] = 'lmenu_' .substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(11/strlen($x)) )),1,11);
                        $new_menus[] = $menu;
                        
                    }
                }
                update_post_meta( $lid, '_cth_resmenus', $new_menus );
            }
        }
    }
    public static function leventdate_start(){
        $listings = get_posts( array(
            'fields'            => 'ids', 
            'posts_per_page'    => -1, 
            'post_type'         => 'listing',
            'post_status'       => array( 'publish', 'pending', 'draft', 'future' ),
            // 'meta_query' => array(
            //     array(
            //         'key'     => '_cth_eventdate',
            //         'value'   => '',
            //         'compare' => '!=',
            //     ),
            // ),
        ) );
        if ( $listings ) {
            
            foreach ( $listings as $lid ) {
                $old_val = get_post_meta( $lid, '_cth_eventdate', true );
                if( !empty($old_val) ){
                    $eventdates = explode("|", $old_val);
                    if( count($eventdates) === 4 && $eventdates[0] != '' ){
                        update_post_meta( $lid, '_cth_eventdate_start', $eventdates[0] );
                    }
                    if( count($eventdates) === 4 && $eventdates[2] != '' ){
                        update_post_meta( $lid, '_cth_eventdate_end', $eventdates[2] );
                    }
                }else{
                    update_post_meta( $lid, '_cth_eventdate_start', date_i18n( 'Y-m-d' ) );
                    update_post_meta( $lid, '_cth_eventdate_end', 'none' );
                    
                }
                
            }
        }
    }



    private static function chat_system(){
        global $wpdb;

        $chat_table = $wpdb->prefix . 'cth_chat';
        $chat_reply_table = $wpdb->prefix . 'cth_chat_reply';
        $charset_collate = $wpdb->get_charset_collate();

        $chat_sql = "CREATE TABLE IF NOT EXISTS $chat_table (
            c_id int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            user_one bigint(20) UNSIGNED NOT NULL,
            user_two bigint(20) UNSIGNED NOT NULL,
            ip varchar(30) DEFAULT NULL,
            time int(11) DEFAULT NULL
        ) $charset_collate;";
        // ,
        //     FOREIGN KEY (user_one) REFERENCES $wpdb->users(ID),
        //     FOREIGN KEY (user_two) REFERENCES $wpdb->users(ID)

        $chat_reply_sql = "CREATE TABLE IF NOT EXISTS $chat_reply_table (
            cr_id bigint(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            reply text,
            user_id_fk bigint(20) UNSIGNED NOT NULL,
            ip varchar(30) DEFAULT NULL,
            time int(11) DEFAULT NULL,
            c_id_fk int(11) UNSIGNED NOT NULL,
            status TINYINT(1) DEFAULT 0
        ) $charset_collate;";

        // ,
        //     FOREIGN KEY (user_id_fk) REFERENCES $wpdb->users(ID),
        //     FOREIGN KEY (c_id_fk) REFERENCES {$chat_table}(c_id)


        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $chat_sql );
        dbDelta( $chat_reply_sql );
    }

    private static function notification_system(){
        global $wpdb;

        $notification_object_table = $wpdb->prefix . 'cth_noti_obj';
        $notification_table = $wpdb->prefix . 'cth_noti';
        $notification_change_table = $wpdb->prefix . 'cth_noti_change';
        $charset_collate = $wpdb->get_charset_collate();

        $noti_obj_sql = "CREATE TABLE IF NOT EXISTS $notification_object_table (
            id int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            entity_type_id int(11) UNSIGNED NOT NULL,
            entity_id int(11) UNSIGNED NOT NULL,
            time int(11) DEFAULT NULL,
            status TINYINT(1) NOT NULL
        ) $charset_collate;";

        $noti_sql = "CREATE TABLE IF NOT EXISTS $notification_table (
            id int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            notification_obj_id int(11) UNSIGNED NOT NULL,
            notifier_id bigint(20) UNSIGNED NOT NULL,
            status TINYINT(1) NOT NULL
        ) $charset_collate;";
        // ,
        //     FOREIGN KEY (notification_obj_id) REFERENCES {$notification_object_table}(id)

        $noti_change_sql = "CREATE TABLE IF NOT EXISTS $notification_change_table (
            id int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            notification_obj_id int(11) UNSIGNED NOT NULL,
            actor_id bigint(20) UNSIGNED NOT NULL,
            status TINYINT(1) NOT NULL
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $noti_obj_sql );
        dbDelta( $noti_sql );
        dbDelta( $noti_change_sql );
        // ,
        //     FOREIGN KEY (notification_obj_id) REFERENCES {$notification_object_table}(id)
    }


    private static function booking_system(){
        global $wpdb;

        $booking_table = $wpdb->prefix . 'cth_booking';
        $charset_collate = $wpdb->get_charset_collate();

        $booking_table_sql = "CREATE TABLE IF NOT EXISTS $booking_table (
            ID bigint(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            booking_id bigint(20) UNSIGNED NOT NULL,
            room_id bigint(20) UNSIGNED NOT NULL,
            guest_id bigint(20) UNSIGNED NOT NULL,
            listing_id bigint(20) UNSIGNED NOT NULL,
            status TINYINT(1) NOT NULL,
            date_from bigint(20) UNSIGNED DEFAULT NULL,
            date_to bigint(20) UNSIGNED DEFAULT NULL,
            quantity TINYINT NOT NULL
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $booking_table_sql );

        // ,
        //     FOREIGN KEY (booking_id) REFERENCES $wpdb->posts(ID),
        //     FOREIGN KEY (room_id) REFERENCES $wpdb->posts(ID)
    }

    private static function listing_stats(){
        global $wpdb;

        $lstats_table = $wpdb->prefix . 'cth_lstats';
        $charset_collate = $wpdb->get_charset_collate();

        $lstats_table_sql = "CREATE TABLE IF NOT EXISTS $lstats_table (
            ID BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            post_id BIGINT(20) UNSIGNED NOT NULL,
            child_post_id BIGINT(20) UNSIGNED DEFAULT NULL,
            type VARCHAR(100) DEFAULT NULL,
            value INT(11) NOT NULL,
            meta LONGTEXT DEFAULT NULL,
            year VARCHAR(4) DEFAULT NULL,
            month VARCHAR(2) DEFAULT NULL,
            date DATE DEFAULT NULL,
            time INT(11) DEFAULT NULL,
            ip VARCHAR(30) DEFAULT NULL,
            guest_id BIGINT(20) UNSIGNED DEFAULT NULL
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $lstats_table_sql );
    }

    private static function author_earning(){
        global $wpdb;

        $earning_table = $wpdb->prefix . 'cth_austats';
        $charset_collate = $wpdb->get_charset_collate();

        $earning_table_sql = "CREATE TABLE IF NOT EXISTS $earning_table (
            ID BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            author_id BIGINT(20) UNSIGNED NOT NULL,
            order_id BIGINT(20) UNSIGNED NOT NULL,
            child_post_id BIGINT(20) UNSIGNED DEFAULT NULL,
            type VARCHAR(100) DEFAULT NULL,
            total DECIMAL(13, 4) NOT NULL,
            fee_rate DECIMAL(5, 2) NOT NULL,
            fee DECIMAL(13, 4) NOT NULL,
            earning DECIMAL(13, 4) NOT NULL,
            meta LONGTEXT DEFAULT NULL,
            year VARCHAR(4) DEFAULT NULL,
            month VARCHAR(2) DEFAULT NULL,
            date DATE DEFAULT NULL,
            time INT(11) DEFAULT NULL,
            status TINYINT(1) NOT NULL
        ) $charset_collate;";

        // DECIMAL(13, 4) - money type

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $earning_table_sql );
    }

    private static function working_hours(){
        global $wpdb;

        $_table = $wpdb->prefix . 'cth_wkhours';
        $charset_collate = $wpdb->get_charset_collate();

        $_table_sql = "CREATE TABLE IF NOT EXISTS $_table (
            ID BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            post_id BIGINT(20) UNSIGNED NOT NULL,
            day VARCHAR(50) NOT NULL,
            day_num TINYINT(1) NOT NULL,
            static VARCHAR(100) NOT NULL,
            open TIME DEFAULT NULL,
            close TIME DEFAULT NULL
        ) $charset_collate;";

        // DECIMAL(13, 4) - money type

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $_table_sql );
    }

    public static function uninstall(){
        // remove submit_listing cap to administrator and listing_author role
        global $wp_roles;
        if ( ! isset( $wp_roles ) ) {
            $wp_roles = new WP_Roles();
        }
        $wp_roles->remove_cap( 'listing_author', 'submit_listing' );
        $wp_roles->remove_cap( 'administrator', 'submit_listing' );

        if( get_role('listing_author') ){
            remove_role( 'listing_author' );
        }
        if( get_role('l_customer') ){
            remove_role( 'l_customer' );
        }
        
        // move pages to trash
        $force_delete = false;
        // trash listing pages
        $exists_options = get_option( 'townhub-addons-options', array() );
        if(isset($exists_options['dashboard_page'])){
            wp_delete_post($exists_options['dashboard_page'], $force_delete);
        }
        if(isset($exists_options['checkout_page'])){
            wp_delete_post($exists_options['checkout_page'], $force_delete);
        }

        

    }

     private static function update_listings(){
        global $wpdb;

        $tb_name = $wpdb->prefix . 'cth_wkhours';

        $listings = get_posts( array(
            'fields'            => 'ids', 
            'posts_per_page'    => -1, 
            'post_type'         => 'listing',
            'post_status'       => 'publish',
            // 'meta_query' => array(
            //     'relation' => 'OR',
            //     array(
            //         'key'     => '_cth_gallery_imgs',
            //         'value'   => '',
            //         'compare' => '!=',
            //     ),
            // ),

        ) );
        if ( $listings ) {
            foreach ( $listings as $lid ) {
                // if ( metadata_exists( 'post', $lid, '_cth_gallery_imgs' ) ) {
                //     $old_val = get_post_meta( $lid, '_cth_gallery_imgs', true );
                //     if($old_val != ''){
                //         update_post_meta( $lid, '_cth_images', $old_val );
                //         delete_post_meta( $lid, '_cth_gallery_imgs');
                //     }
        

                // }
                // update_post_meta( $lid, '_cth_phone', '+7(123)987654' );
                // update_post_meta( $lid, '_cth_email', 'yourmail@domain.com' );
                // update_post_meta( $lid, '_cth_website', 'https://themeforest.net/user/cththemes/portfolio' );

                // $my_post = array(
                //     'ID'           => $lid,
                //     'post_excerpt' => 'Sed interdum metus at nisi tempor laoreet. Integer gravida orci a justo sodales.',
                // );

                // // Update the post into the database
                // wp_update_post( $my_post );

                update_post_meta( $lid, '_cth_wkh_tz', 'UTC' );
                update_post_meta( $lid, '_cth_wkh_tz_utc_offset', '+00:00' );

                // inser working hours data
                // $inserted = $wpdb->insert( 
                //     $tb_name, 
                //     array( 
                //         'post_id'                   => $lid,
                //         'day'                       => 'Mon', 
                //         'day_num'                   => 1, 
                //         'static'                    => 'enterHours', 
                //         'open'                      => '09:00:00', 
                //         'close'                     => '17:00:00', 
                        
                //     ) 
                // );
                // $inserted = $wpdb->insert( 
                //     $tb_name, 
                //     array( 
                //         'post_id'                   => $lid,
                //         'day'                       => 'Tue', 
                //         'day_num'                   => 2, 
                //         'static'                    => 'enterHours', 
                //         'open'                      => '08:00:00', 
                //         'close'                     => '12:00:00', 
                        
                //     ) 
                // );
                // $inserted = $wpdb->insert( 
                //     $tb_name, 
                //     array( 
                //         'post_id'                   => $lid,
                //         'day'                       => 'Tue', 
                //         'day_num'                   => 2, 
                //         'static'                    => 'enterHours', 
                //         'open'                      => '13:00:00', 
                //         'close'                     => '18:00:00', 
                        
                //     ) 
                // );
                // $inserted = $wpdb->insert( 
                //     $tb_name, 
                //     array( 
                //         'post_id'                   => $lid,
                //         'day'                       => 'Wed', 
                //         'day_num'                   => 3, 
                //         'static'                    => 'enterHours', 
                //         'open'                      => '09:00:00', 
                //         'close'                     => '19:00:00', 
                        
                //     ) 
                // );
                // $inserted = $wpdb->insert( 
                //     $tb_name, 
                //     array( 
                //         'post_id'                   => $lid,
                //         'day'                       => 'Thu', 
                //         'day_num'                   => 4, 
                //         'static'                    => 'enterHours', 
                //         'open'                      => '09:00:00', 
                //         'close'                     => '20:00:00', 
                        
                //     ) 
                // );
                // $inserted = $wpdb->insert( 
                //     $tb_name, 
                //     array( 
                //         'post_id'                   => $lid,
                //         'day'                       => 'Fri', 
                //         'day_num'                   => 5, 
                //         'static'                    => 'enterHours', 
                //         'open'                      => '09:00:00', 
                //         'close'                     => '21:00:00', 
                        
                //     ) 
                // );
                // $inserted = $wpdb->insert( 
                //     $tb_name, 
                //     array( 
                //         'post_id'                   => $lid,
                //         'day'                       => 'Sat', 
                //         'day_num'                   => 6, 
                //         'static'                    => 'enterHours', 
                //         'open'                      => '09:00:00', 
                //         'close'                     => '22:00:00', 
                        
                //     ) 
                // );

                // $inserted = $wpdb->insert( 
                //     $tb_name, 
                //     array( 
                //         'post_id'                   => $lid,
                //         'day'                       => 'Sun', 
                //         'day_num'                   => 7, 
                //         'static'                    => 'closeAllDay', 
                        
                //     ) 
                // );


                // static VARCHAR(100) DEFAULT NULL,
                // open TIME DEFAULT NULL,
                // close TIME DEFAULT NULL


            }
        }
    }

    

    public static function drop_foreign_keys(){
        global $wpdb;



        $booking_table = $wpdb->prefix . 'cth_booking';
        $wpdb->query( "ALTER TABLE $booking_table ADD CONSTRAINT FK_booking_id FOREIGN KEY (booking_id) REFERENCES $wpdb->posts(ID) ON DELETE CASCADE" );
        $wpdb->query( "ALTER TABLE $booking_table DROP FOREIGN KEY FK_booking_id" );
        // $wpdb->query( $wpdb->prepare( "ALTER TABLE $booking_table DROP FOREIGN KEY %s", 'booking_id' ) );
        $wpdb->query( "ALTER TABLE $booking_table ADD CONSTRAINT FK_room_id FOREIGN KEY (room_id) REFERENCES $wpdb->posts(ID) ON DELETE CASCADE" );
        $wpdb->query( "ALTER TABLE $booking_table DROP FOREIGN KEY FK_room_id" ) ;
        // $wpdb->query( $wpdb->prepare( "ALTER TABLE $booking_table DROP FOREIGN KEY %s", 'room_id' ) );

        $notification_object_table = $wpdb->prefix . 'cth_noti_obj';
        $notification_table = $wpdb->prefix . 'cth_noti';
        $wpdb->query( "ALTER TABLE $notification_table ADD CONSTRAINT FK_notification_obj_id FOREIGN KEY (notification_obj_id) REFERENCES {$notification_object_table}(id) ON DELETE CASCADE" );
        $wpdb->query( "ALTER TABLE $notification_table DROP FOREIGN KEY FK_notification_obj_id" ) ;
        // $wpdb->query( $wpdb->prepare( "ALTER TABLE $notification_table DROP FOREIGN KEY %s", 'notification_obj_id' ) );

        $notification_change_table = $wpdb->prefix . 'cth_noti_change';
        $wpdb->query( "ALTER TABLE $notification_change_table ADD CONSTRAINT FK_change_notification_obj_id FOREIGN KEY (notification_obj_id) REFERENCES {$notification_object_table}(id) ON DELETE CASCADE" );
        $wpdb->query( "ALTER TABLE $notification_change_table DROP FOREIGN KEY FK_change_notification_obj_id" ) ;
        // $wpdb->query( $wpdb->prepare( "ALTER TABLE $notification_change_table DROP FOREIGN KEY %s", 'notification_obj_id' ) );

        $chat_table = $wpdb->prefix . 'cth_chat';
        $wpdb->query( "ALTER TABLE $chat_table ADD CONSTRAINT FK_user_one FOREIGN KEY (user_one) REFERENCES $wpdb->users(ID) ON DELETE CASCADE" );
        $wpdb->query( "ALTER TABLE $chat_table DROP FOREIGN KEY FK_user_one" ) ;
        // $wpdb->query( $wpdb->prepare( "ALTER TABLE $chat_table DROP FOREIGN KEY %s", 'user_one' ) );
        $wpdb->query( "ALTER TABLE $chat_table ADD CONSTRAINT FK_user_two FOREIGN KEY (user_two) REFERENCES $wpdb->users(ID) ON DELETE CASCADE" );
        $wpdb->query( "ALTER TABLE $chat_table DROP FOREIGN KEY FK_user_two" ) ;
        // $wpdb->query( $wpdb->prepare( "ALTER TABLE $chat_table DROP FOREIGN KEY %s", 'user_two' ) );

        $chat_reply_table = $wpdb->prefix . 'cth_chat_reply';
        $wpdb->query( "ALTER TABLE $chat_reply_table ADD CONSTRAINT FK_user_id_fk FOREIGN KEY (user_id_fk) REFERENCES $wpdb->users(ID) ON DELETE CASCADE" );
        $wpdb->query( "ALTER TABLE $chat_reply_table DROP FOREIGN KEY FK_user_id_fk" ) ;
        // $wpdb->query( $wpdb->prepare( "ALTER TABLE $chat_reply_table DROP FOREIGN KEY %s", 'user_id_fk' ) );
        $wpdb->query( "ALTER TABLE $chat_reply_table ADD CONSTRAINT FK_c_id_fk FOREIGN KEY (c_id_fk) REFERENCES {$chat_table}(c_id) ON DELETE CASCADE" );
        $wpdb->query( "ALTER TABLE $chat_reply_table DROP FOREIGN KEY FK_c_id_fk" ) ;
        // $wpdb->query( $wpdb->prepare( "ALTER TABLE $chat_reply_table DROP FOREIGN KEY %s", 'c_id_fk' ) );
    }
}