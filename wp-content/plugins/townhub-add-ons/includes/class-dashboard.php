<?php
/* add_ons_php */

class Esb_Class_Dashboard
{

    public static function init()
    {
        // $ajax_actions = array(
        //     'checkout_form',
        //     'townhub_addons_chat_lauthor_message',
        //     'townhub_single_room',
        //     'townhub_addons_booking_woo_listing',
        // );
        // foreach ($ajax_actions as $action) {
        //     $funname = str_replace('townhub_addons_', '', $action);
        //     $funname = str_replace('townhub_', '', $funname);
        //     add_action('wp_ajax_nopriv_'.$action, array( __CLASS__, $funname ));
        //     add_action('wp_ajax_'.$action, array( __CLASS__, $funname ));
        // }
        $logged_in_ajax_actions = array(
            'townhub_addons_author_charts',
            'townhub_addons_delete_notification',
            'townhub_addons_edit_profile',
            'townhub_addons_change_pass',

            'townhub_addons_follow_author',
            'townhub_addons_unfollow_author',
            'townhub_addons_iconpicker',

            'townhub_addons_fetch_images',

        );
        foreach ($logged_in_ajax_actions as $action) {
            $funname = str_replace('townhub_addons_', '', $action);
            $funname = str_replace('townhub_', '', $funname);
            add_action('wp_ajax_' . $action, array(__CLASS__, $funname));
        }

        // $not_logged_in_ajax_actions = array(

        // );
        // foreach ($not_logged_in_ajax_actions as $action) {
        //     $funname = str_replace('townhub_addons_', '', $action);
        //     $funname = str_replace('townhub-', '', $funname);
        //     add_action('wp_ajax_nopriv_'.$action, array( __CLASS__, $funname .'_callback' ));
        // }
    }

    public static function verify_nonce($action_name = '', $datas = array())
    {
        if (!isset($_REQUEST['_wpnonce']) || $action_name == '' || !wp_verify_nonce($_REQUEST['_wpnonce'], $action_name)) {
            $result = array(
                'success' => false,
                'error'   => esc_html__('Security checked!, Cheatn huh?', 'townhub-add-ons'),
                'data'      => array(
                    'error'   => esc_html__('Security checked!, Cheatn huh?', 'townhub-add-ons'),
                )
            );

            if (!empty($datas) && is_array($datas)) {
                $result = array_merge($result, $datas);
            }

            wp_send_json($result);
        }

    }

    public static function demo_mode_check($is_ajax = true){
        if( defined('CTH_DEMO') && CTH_DEMO === false ) return;

        $message = __( 'Sorry! This action is not allowed on demo site.', 'townhub-add-ons' );
        wp_send_json( array(
            'success'   => false,
            'error'     =>  $message,
            'message'     =>  $message,
            // for AjaxHelper
            'data'         => array(
                'error'     =>  $message,
            ),
        ) );
    }

    // get dashboard subpages - related with dashboard shortcode page.
    public static function subpage($var = ''){

        $subpages = array(
            'listings'      => __( 'Your Listings', 'townhub-add-ons' ),
            'reviews'       => __( 'Your Reviews', 'townhub-add-ons' ),
            'changepass'    => __( 'Change Password', 'townhub-add-ons' ),
            'chats'         => __( 'Chats', 'townhub-add-ons' ),
            'messages'      => __( 'Your Messages', 'townhub-add-ons' ),
            'bookings'      => __( 'Your Bookings', 'townhub-add-ons' ),
            'bookmarks'     => __( 'Bookmarks', 'townhub-add-ons' ),
            'profile'       => __( 'Edit Profile', 'townhub-add-ons' ),
            'packages'      => __( 'Packages', 'townhub-add-ons' ),
            'invoices'      => __( 'Invoices', 'townhub-add-ons' ),
            'ads'           => __( 'AD Campaigns', 'townhub-add-ons' ),
            'withdrawals'    => __( 'Withdrawals', 'townhub-add-ons' ),
            'feed'          => __( 'Your Feed', 'townhub-add-ons' ),
            'ical'          => isset($_GET['export']) ? _x( 'iCal Export','Page Title', 'townhub-add-ons' ) : _x( 'iCal Import','Page Title', 'townhub-add-ons' ),
        );

        if($var != '' && isset($subpages[$var])){
            return $subpages[$var];
        }

        return get_the_title( esb_addons_get_wpml_option('dashboard_page') );
    }

    public static function menu_item($screen = '', $title = 'Dashboard', $icon = '', $badge = '', $is_add_page = false, $is_edit_page = false)
    {
        $dashboard = get_query_var('dashboard');
        $cl        = 'dashboard-menu-link';
        if ( $screen == $dashboard && false == $is_add_page && false == $is_edit_page ) {
            $cl .= ' user-profile-act';
        }
        if( $is_edit_page && $screen == 'listings' ) $cl .= ' user-profile-act';
        ?>
        <li class="dashboard-menu-li dbscreen-<?php echo sanitize_title($screen); ?>">
            <a href="<?php echo esc_url(self::screen_url($screen)); ?>" class="<?php echo esc_attr($cl); ?>">
                <?php if ($icon != ''): ?>
                    <i class="<?php echo esc_attr($icon); ?>"></i>
                <?php endif;?>
                <?php echo $title; ?>
                <?php if( !empty($badge) ): ?>
                    <span><?php echo esc_attr($badge); ?></span>
                <?php endif;?>
            </a>
        </li>

        <?php
// <li><a href="dashboard-feed.html"><i class="fal fa-rss"></i>Your Feed <span>7</span></a></li>
    }
    public static function screen_url($screen = '')
    {
        if ($screen != '') {
            return add_query_arg('dashboard', $screen, get_permalink(esb_addons_get_wpml_option('dashboard_page')));
        } else {
            return get_permalink(esb_addons_get_wpml_option('dashboard_page'));
        }

    }

    public static function breadcrumbs()
    {
        $dashboard_page_id = esb_addons_get_wpml_option('dashboard_page');

        if (false == is_page($dashboard_page_id) || false == $dashboard_page_id) {
            return;
        }

        $home_title    = __('Home', 'townhub-add-ons');
        $dashboard_var = get_query_var('dashboard');
        echo '<div class="dashboard-breadcrumbs breadcrumbs">';

        echo '<a class="breadcrumb-link breadcrumb-home" href="' . esc_url(home_url('/')) . '" title="' . esc_attr($home_title) . '">' . esc_attr($home_title) . '</a>';

        if ($dashboard_var != '') {
            echo '<a class="breadcrumb-link breadcrumb-dashboard" href="' . esc_url(get_permalink($dashboard_page_id)) . '" title="' . esc_attr(get_the_title($dashboard_page_id)) . '">' . get_the_title($dashboard_page_id) . '</a>';
            // Current page
            echo '<span class="breadcrumb-current breadcrumb-dashboard-subpage" title="' . esc_attr(self::subpage($dashboard_var)) . '">' . self::subpage($dashboard_var) . '</span>';

        } else {
            echo '<span class="breadcrumb-current breadcrumb-dashboard" title="' . esc_attr(get_the_title($dashboard_page_id)) . '">' . get_the_title($dashboard_page_id) . '</span>';
        }

        echo '</div>';

    }

    public static function notification_entity($type = '')
    {
        $entities = array(
            'listing_submitted' => array(
                'entity_type_id' => 1,
                // 'entity_id'            => 1,
                'desc'           => 'This notification is sent when a listing is created.',
                'noti_msg'       => 'User A created a listing.',
            ),
            'edit_profile'      => array(
                'entity_type_id' => 2,
                // 'entity_id'            => 2,
                'desc'           => 'This notification is sent when user profile edited.',
                'noti_msg'       => _x('<i class="far fa-check green-bg"></i> Your profile has been successfully edited.', 'Edit profile activity message template', 'townhub-add-ons'),
            ),

            'role_change'       => array(
                'entity_type_id' => 3,
                // 'entity_id'          => 2,
                'desc'           => 'This notification is sent when user role change by membership.',
                'noti_msg'       => _x('<i class="far fa-check green-bg"></i> Your role is changed to Listing Author so you can now submit listing.', 'Listing author role changed activity message template', 'townhub-add-ons'),
            ),

            'order_completed'   => array(
                'entity_type_id' => 4,
                // 'entity_id'          => 2,
                'desc'           => 'This notification is sent when author subscription order completed.',
                'noti_msg'       => _x('<i class="far fa-check green-bg"></i> Your subscription order has marked as completed. So you can submit listings now.', 'Subscription completed activity message template', 'townhub-add-ons'),
            ),

            'new_order'         => array(
                'entity_type_id' => 8,
                // 'entity_id'          => 2,
                'desc'           => 'New order',
                'noti_msg'       => _x('<i class="far fa-check green-bg"></i> Your subscription order has been received and will be checked soon.', 'New order notification', 'townhub-add-ons'),
            ),

            'new_invoice'       => array(
                'entity_type_id' => 5,
                // 'entity_id'          => 2,
                'desc'           => 'This notification is sent when new invoice received.',
                'noti_msg'       => _x('<i class="far fa-check green-bg"></i> You have a new invoice. ID: {post_id}', 'Subscription completed activity message template', 'townhub-add-ons'),
            ),
            'booking_approved'  => array(
                'entity_type_id' => 6,
                // 'entity_id'          => 2,
                'desc'           => 'Booking approved notification',
                'noti_msg'       => _x('<i class="far fa-check green-bg"></i> Your booking for <strong>{post_title}</strong> listing has been approved.', 'Booking approved notification', 'townhub-add-ons'),
            ),
            'new_booking'       => array(
                'entity_type_id' => 7,
                // 'entity_id'          => 2,
                'desc'           => 'New booking notification',
                'noti_msg'       => _x('<i class="far fa-check green-bg"></i> <strong>{actor}</strong> booked your <a href="{post_link}" target="_blank">{post_title}</a> listing', 'New booking notification', 'townhub-add-ons'),
            ),

            'bookmarked'        => array(
                'entity_type_id' => 9,
                // 'entity_id'            => 2,
                'desc'           => 'This notification is sent to user who bookmark listing.',
                'noti_msg'       => _x('<i class="far fa-heart purp-bg"></i> You have bookmarked <a href="{post_link}" target="_blank">{post_title}</a> listing!', 'User bookmark activity message template to user', 'townhub-add-ons'),
            ),

            'new_bookmark'      => array(
                'entity_type_id' => 10,
                // 'entity_id'            => 2,
                'desc'           => 'This notification is sent to listing author when user bookmark his listing.',
                'noti_msg'       => _x('<i class="far fa-heart purp-bg"></i> <strong>{actor}</strong> bookmarked your <a href="{post_link}" target="_blank">{post_title}</a> listing!', 'User bookmark activity message template to listing author', 'townhub-add-ons'),
            ),

            'withdrawal_new'    => array(
                'entity_type_id' => 11,
                // 'entity_id'          => 2,
                'desc'           => 'New withdrawal notification',
                'noti_msg'       => _x('<i class="far fa-money-check green-bg"></i> Your withdrawal request has been received. It will be proccessed soon.', 'New withdrawal notification', 'townhub-add-ons'),
            ),

            'listing_expired'   => array(
                'entity_type_id' => 12,
                'desc'           => 'Listing expired notification',
                'noti_msg'       => _x('<i class="fal fa-calendar-minus red-bg"></i> Your <a href="{post_link}" target="_blank">{post_title}</a> listing has expired. Please renew membership subscription to get it live back.', 'Listing expired notification template', 'townhub-add-ons'),
            ),

            'new_ad'            => array(
                'entity_type_id' => 13,
                'desc'           => 'new listing ad notification',
                'noti_msg'       => _x('<i class="far fa-money-check green-bg"></i> Your listing AD is added. Please follow the link bellow to complete payment<br /><a href="{post_link}">Pay now</a>', 'New Listing ad notification template', 'townhub-add-ons'),
            ),

            'ad_approved'       => array(
                'entity_type_id' => 14,
                'desc'           => 'Listing ad approved notification',
                'noti_msg'       => _x('<i class="far fa-check green-bg"></i> Ad for your <a href="{post_link}" target="_blank">{post_title}</a> listing is approved.', 'Listing ad approved notification template', 'townhub-add-ons'),
            ),

            'password_changed'  => array(
                'entity_type_id' => 15,
                // 'entity_id'          => 2,
                'desc'           => 'This notification is sent when user change password.',
                'noti_msg'       => _x('<i class="far fa-check green-bg"></i> Your password has been successfully updated.', 'Change pass message template', 'townhub-add-ons'),
            ),

            'membership_will_expired'  => array(
                'entity_type_id'    => 20,
                'desc'              => 'Membership will expire notification',
                'noti_msg'          => _x( '<i class="fal fa-exclamation-triangle yellow-bg"></i> Your membership subscription will expire within 5 days. Please renew it.','Membership expired notification template', 'townhub-add-ons' ),
            ),

            'ad_will_expired'  => array(
                'entity_type_id'    => 21,
                'desc'              => 'AD will expire notification',
                'noti_msg'          => _x( '<i class="fal fa-exclamation-triangle yellow-bg"></i> Your AD for a listing will expire within 5 days.','AD will expire notification template', 'townhub-add-ons' ),
            ),

            'membership_expired'  => array(
                'entity_type_id'    => 22,
                'desc'              => 'Membership expired notification',
                'noti_msg'          => _x( '<i class="fal fa-exclamation-triangle yellow-bg"></i> Your membership subscription has expired. Please renew it.','Membership expired notification template', 'townhub-add-ons' ),
            ),
            'listing_limit'  => array(
                'entity_type_id'    => 25,
                'desc'              => 'Listings limit notification',
                'noti_msg'          => _x( '<i class="fal fa-exclamation-triangle yellow-bg"></i> You hit membership listings limitation. Please upgrade to higher plan to submit more listings.','Listings limit notification template', 'townhub-add-ons' ),
            ),

            'withdrawal_canceled'    => array(
                'entity_type_id' => 26,
                // 'entity_id'          => 2,
                'desc'           => 'Withdrawal canceled notification',
                'noti_msg'       => _x('<i class="far fa-money-check red-bg"></i> You have just canceled your withdrawal request.', 'Withdrawal canceled notification', 'townhub-add-ons'),
            ),
            
            'withdrawal_completed'    => array(
                'entity_type_id' => 27,
                // 'entity_id'          => 2,
                'desc'           => 'Withdrawal completed notification',
                'noti_msg'       => _x('<i class="far fa-money-check green-bg"></i> Your withdrawal request was processed. You will see it on your fund soon.', 'Withdrawal completed notification', 'townhub-add-ons'),
            ),

            'logged_in'    => array(
                'entity_type_id' => 28,
                // 'entity_id'          => 2,
                'desc'           => 'Logged in notification',
                'noti_msg'       => _x('<i class="fal fa-sign-in-alt green-bg"></i> You have logged in to your account.', 'Logged in notification', 'townhub-add-ons'),
            ),
            'booking_cancel'  => array(
                'entity_type_id' => 29,
                // 'entity_id'          => 2,
                'desc'           => 'Booking cancel notification',
                'noti_msg'       => _x('<i class="far fa-check green-bg"></i> You have canceld a booking for <strong>{post_title}</strong> listing.', 'Booking cancel notification', 'townhub-add-ons'),
            ),
            'booking_canceled'  => array(
                'entity_type_id' => 30,
                // 'entity_id'          => 2,
                'desc'           => 'Booking cancel notification',
                'noti_msg'       => _x('<i class="far fa-check green-bg"></i> Booking for <strong>{post_title}</strong> listing was canceled.', 'Booking cancel notification', 'townhub-add-ons'),
            ),
        );
        if ($type != '' && isset($entities[$type])) {
            return $entities[$type];
        }

        $entities_val = array();
        foreach ($entities as $type => $entity) {
            $entity['type_name']                     = $type;
            $entities_val[$entity['entity_type_id']] = $entity;
        }
        return $entities_val;
    }

    public static function add_notification($user_id = 0, $message = array())
    {
        $user = get_user_by('ID', $user_id);
        if (!$user) {
            return;
        }

        // if(!isset($message['type']) || !isset($message['message'])) return;
        if (!isset($message['type'])) {
            return;
        }

        $noti_entity = self::notification_entity($message['type']);
        if (!isset($noti_entity['entity_type_id'])) {
            return;
        }

        if (!isset($message['entity_id'])) {
            $message['entity_id'] = 0;
        }
        // set default object if not exist
        $notifier_id = $actor_id = $user->ID;
        if (isset($message['notifier_id'])) {
            $notifier_id = $message['notifier_id'];
        }
        // set default object if not exist
        if (isset($message['actor_id'])) {
            $actor_id = $message['actor_id'];
        }
        // set default object if not exist

        global $wpdb;

        $notification_object_table = $wpdb->prefix . 'cth_noti_obj';
        $notification_table        = $wpdb->prefix . 'cth_noti';
        $notification_change_table = $wpdb->prefix . 'cth_noti_change';

        $time = date_i18n('U');
        // insert record to notification_object table
        $noti_obj_result = $wpdb->insert(
            $notification_object_table,
            array(
                'entity_type_id' => $noti_entity['entity_type_id'],
                'entity_id'      => $message['entity_id'],
                'time'           => $time,
                'status'         => 1,
            )
        );
        if ($noti_obj_result != false) {
            $newly_created_noti = $wpdb->insert_id;
            // insert record to notification_change table
            $noti_result = $wpdb->insert(
                $notification_table,
                array(
                    'notification_obj_id' => $newly_created_noti,
                    'notifier_id'         => $notifier_id,
                    'status'              => 1,
                )
            );
            // insert record to notification_change table
            $noti_change_result = $wpdb->insert(
                $notification_change_table,
                array(
                    'notification_obj_id' => $newly_created_noti,
                    'actor_id'            => $actor_id,
                    'status'              => 1,
                )
            );

            // update user notis counter
            self::update_notification_count($user->ID);
        }
    }

    public static function update_notification_count($user_id = 0, $decrease = false ){
        if(is_numeric($user_id) && (int)$user_id > 0){
            $count = intval( get_user_meta($user_id, ESB_META_PREFIX . 'notis_count', true) ) ;
            if( $decrease ){
                if( $count > 1){
                    update_user_meta( $user_id, ESB_META_PREFIX . 'notis_count', ($count - 1) );
                }else{
                    update_user_meta( $user_id, ESB_META_PREFIX . 'notis_count', 0 );
                }
            }else{
                update_user_meta( $user_id, ESB_META_PREFIX . 'notis_count', ($count + 1) );
            }
        }
    }

    public static function get_notifications($user_id = 0, $type = '', $items_per_page = '', $paged = '' )
    {
        global $wpdb;

        $notification_object_table = $wpdb->prefix . 'cth_noti_obj';
        $notification_table        = $wpdb->prefix . 'cth_noti';
        $notification_change_table = $wpdb->prefix . 'cth_noti_change';

        
        $paged = !empty($paged) ? $paged : ( (get_query_var('paged')) ? get_query_var('paged') : 1 );
        if(empty($items_per_page)) $items_per_page = get_option( 'posts_per_page' );
        $offset = ($paged - 1) * $items_per_page;
        $notifications = $wpdb->get_results(
            $wpdb->prepare(
                "
                SELECT SQL_CALC_FOUND_ROWS n_o.*, n_o.id, n_o.entity_id, n_o.entity_type_id, n_o.time, n.notifier_id, n_c.actor_id
                FROM $notification_object_table n_o
                INNER JOIN $notification_table n
                INNER JOIN $notification_change_table n_c
                WHERE n.notification_obj_id = n_o.id AND n_c.notification_obj_id = n_o.id AND n.notifier_id = $user_id
                ORDER BY n_o.id DESC LIMIT %d, %d
                ",
                $offset,
                $items_per_page
            )

                
        );

        $noti_count = $wpdb->get_var( "SELECT FOUND_ROWS()" );
        
        
        $notis = array();
        if ($notifications) {
            $entities = self::notification_entity();
            foreach ($notifications as $noti) {
                if (isset($entities[$noti->entity_type_id])) {
                    $entity      = $entities[$noti->entity_type_id];
                    $actor       = get_userdata($noti->actor_id);
                    $entity_post = false;
                    switch ($entity['type_name']) {
                        case 'bookmarked':
                        case 'new_bookmark':
                        case 'new_invoice':
                        case 'booking_approved':
                        case 'booking_cancel':
                        case 'booking_canceled':
                        case 'new_booking':
                        case 'new_order':
                        case 'listing_expired':
                        case 'new_ad':
                        case 'ad_approved':
                            $entity_post = get_post($noti->entity_id);
                            break;
                    }
                    $message_vars = array(
                        'actor'      => $actor ? $actor->display_name : _x('Someone', 'Activity no actor default name', 'townhub-add-ons'),
                        'post_link'  => $entity_post ? get_permalink($entity_post) : null,
                        'post_title' => $entity_post ? $entity_post->post_title : null,
                        'post_id'    => $entity_post ? $entity_post->ID : null,
                    );

                    if ($entity['type_name'] == 'new_ad') {
                        $message_vars['post_link'] = get_permalink(esb_addons_get_wpml_option('checkout_page'));
                        if( 'yes' === townhub_addons_get_option( 'woo_for_ads' ) ){
                            $message_vars['post_link'] = townhub_addons_get_add_to_cart_url( $entity_post->ID );
                        }
                    }

                    $message_vars = apply_filters( 'cth_noti_message_vars', $message_vars, $entity, $entity_post, $actor, $noti  );

                    $noti->message      = Esb_Class_Emails::process_email_template($entity['noti_msg'], $message_vars);
                    $noti->timestamp    = $noti->time;
                    $noti->time         = date_i18n(sprintf(_x('%1$s %2$s', 'Dashboard activity time format', 'townhub-add-ons'), get_option('date_format'), get_option('time_format')), $noti->time, false);
                    $notis[]            = $noti;
                }
            }
            // add pages to end -> need to use array_pop
            $notis[] = ceil($noti_count / $items_per_page);
        }
        return $notis;
    }

    public static function del_notification($notification_id = 0)
    {
        if (is_numeric($notification_id) && $notification_id > 0) {
            global $wpdb;

            $notification_object_table = $wpdb->prefix . 'cth_noti_obj';
            $notification_table        = $wpdb->prefix . 'cth_noti';
            $notification_change_table = $wpdb->prefix . 'cth_noti_change';

            $del_noti_val = $wpdb->query(
                $wpdb->prepare(
                    "
                    DELETE FROM $notification_table
                    WHERE notification_obj_id = %d
                    ",
                    $notification_id
                )
            );
            $del_noti_change_val = $wpdb->query(
                $wpdb->prepare(
                    "
                    DELETE FROM $notification_change_table
                    WHERE notification_obj_id = %d
                    ",
                    $notification_id
                )
            );
            $del_noti_obj_val = $wpdb->query(
                $wpdb->prepare(
                    "
                    DELETE FROM $notification_object_table
                    WHERE id = %d
                    ",
                    $notification_id
                )
            );

            if ($del_noti_val && $del_noti_change_val && $del_noti_obj_val) {
                return $notification_id;
            } else {
                return false;
            }

        }
    }
    // delete notification ajax callback
    public static function delete_notification()
    {
        $json = array(
            'success' => false,
            'data'    => array(
                // 'POST'=>$_POST,
            ),
            'debug'   => false,
        );

        self::demo_mode_check();

        self::verify_nonce('townhub-add-ons');

        $id = $_POST['id'];

        if (is_numeric($id) && $id > 0) {

            $deleted = self::del_notification($id);

            if ($deleted) {
                $json['id'] = $deleted;
            }

        } else {
            $json['data']['error'] = __('Invalid activity id.', 'townhub-add-ons');
            wp_send_json($json);
        }
        $json['success'] = true;
        wp_send_json($json);
    }

    public static function author_charts()
    {

        $json = array(
            'success' => false,
            'data'    => array(
                // 'POST'=>$_POST,
            ),
            'chart'   => array(),
            'debug'   => false,
        );

        self::verify_nonce('townhub-add-ons');

        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 0;
        if (is_numeric($user_id) && $user_id > 0) {
            $date_range = strtotime('-7 day');
            $args       = array(
                'fields'         => 'ids',
                'posts_per_page' => -1,
                'post_type'      => 'listing',
                'author'         => $user_id,
                'post_status'    => 'publish',
                //      'date_query'         => array(
                //       array(
                //           'column' => 'post_date',
                //                 'after' => array(
                //                     // 'year'  => date('Y', $date_range ),
                //                     // 'month' => date('m', $date_range ),
                //                     // 'day'   => date('d', $date_range ),
                //                     'year' => date( 'Y' ),
                //      'week' => date( 'W' ),
                //                 ),
                //             )
                //   //           array(
                //   //          'column' => 'post_date',
                //   //               'before' => array(
                //   //                   // 'year'  => date('Y', $date_range ),
                //   //                   // 'month' => date('m', $date_range ),
                //   //                   // 'day'   => date('d', $date_range ),
                //   //                   'year' => date( 'Y' ),
                //      // 'week' => date( 'W' ),
                //   //               ),
                //   //           )
                // //             array(
                // //   'year' => date( 'Y' ),
                // //   'week' => date( 'W' ),
                // // ),
                //      )
            );
            $listings_ID = get_posts($args);
            // $json['data']['listings_ID'] = $listings_ID;

            $data_period = isset($_POST['period']) ? $_POST['period'] : 'week';

            $chart_datas = array();
            $hide_views = townhub_addons_get_option('chart_hide_views', 'no');
            $hide_bookings = townhub_addons_get_option('chart_hide_booking', 'no');
            $hide_earnings = townhub_addons_get_option('chart_hide_earning', 'no');

            if ($data_period == 'alltime') {
                // for alltime stats
                $listing_views = Esb_Class_LStats::get_datas($listings_ID, $data_period);
                $booking_rows = Esb_Class_Booking::get_datas($listings_ID, $data_period);
                $earning_rows  = Esb_Class_Earning::get_datas($user_id, $data_period);

                $alltime_years = array_merge(array_column($listing_views, 'year'), array_column($earning_rows, 'year'));
                $alltime_years = array_unique($alltime_years);

                asort($alltime_years);

                // $json['earning_rows'] = $earning_rows;
                // $json['alltime_years'] = $alltime_years;

                if (!empty($alltime_years)) {
                    
                    foreach ($alltime_years as $year) {
                        $lview_row = array_search($year, array_column($listing_views, 'year'));
                        if ($lview_row === false) {
                            $lview = 0;
                        } else {
                            $lview = $listing_views[$lview_row]['sum'];
                        }

                        $earning_row = array_search($year, array_column($earning_rows, 'year'));
                        if ($earning_row === false) {
                            $earning = 0;
                        } else {
                            $earning = $earning_rows[$earning_row]['sum'];
                        }
                        $booking_row = array_search($year, array_column($booking_rows, 'year'));
                        if ($booking_row === false) {
                            $booking = 0;
                        } else {
                            $booking = $booking_rows[$booking_row]['sum'];
                        }

                        $cdatas = array();
                        if( $hide_views != 'yes' ) $cdatas[] = $lview;
                        if( $hide_bookings != 'yes' ) $cdatas[] = $booking;
                        if( $hide_earnings != 'yes' ) $cdatas[] = $earning;
                        $chart_datas[] = array(
                            'date_string' => $year,
                            'label'       => $year,
                            'views'       => $lview,
                            'earning'     => $earning,
                            'booking'     => $booking,
                            'datas'        => $cdatas,
                        );

                    }
                }
                // end alltime stats
            } else {
                // for week - month and year stats
                $limit = false;

                if ($data_period == 'week') {
                    $day_in_week = date('N');
                    $cur_day     = date('d');

                    if ($day_in_week == 1) {
                        $start_date = date('Y-m-d');
                    } else {
                        $cur_seconds = date('U');
                        $diff_days   = $day_in_week - 1;
                        $start_date  = date('Y-m-d', $cur_seconds - DAY_IN_SECONDS * $diff_days);
                    }

                    // $json['start_date'] = $start_date;

                    $label_arr = array(
                        __('Monday', 'townhub-add-ons'),
                        __('Tuesday', 'townhub-add-ons'),
                        __('Wednesday', 'townhub-add-ons'),
                        __('Thursday', 'townhub-add-ons'),
                        __('Friday', 'townhub-add-ons'),
                        __('Saturday', 'townhub-add-ons'),
                        __('Sunday', 'townhub-add-ons'),
                    );

                    $limit = 7;

                    $add_param = $start_date;

                } elseif ($data_period == 'month') {
                    $cur_year  = date('Y');
                    $cur_month = date('m');
                    if (isset($_POST['date']) && $_POST['date'] != '' && strlen($_POST['date']) == 7) {
                        $cur_year  = substr($_POST['date'], 0, 4);
                        $cur_month = substr($_POST['date'], -2);
                    }

                    $limit     = cal_days_in_month(CAL_GREGORIAN, $cur_month, $cur_year);
                    $label_arr = range(1, $limit);

                    $add_param = $cur_year . $cur_month;
                } elseif ($data_period == 'year') {
                    $cur_year = date('Y');
                    if (isset($_POST['date']) && $_POST['date'] != '') {
                        $cur_year = $_POST['date'];
                    }

                    // $cur_month = date('m');
                    $limit     = 12;
                    $label_arr = range(1, $limit);

                    $add_param = $cur_year;
                }

                $listing_views = Esb_Class_LStats::get_datas($listings_ID, $data_period, $add_param);
                $booking_rows = Esb_Class_Booking::get_datas($listings_ID, $data_period, $add_param);
                $earning_rows  = Esb_Class_Earning::get_datas($user_id, $data_period, $add_param);

                // $json['listing_views'] = $listing_views;

                for ($i = 0; $i < $limit; $i++) {
                    if ($data_period == 'week') {
                        $date_string = Esb_Class_Date::modify($start_date, $i, 'Y-m-d');
                        $lview_row   = array_search($date_string, array_column($listing_views, 'date'));
                        $earning_row = array_search($date_string, array_column($earning_rows, 'date'));
                        $booking_row = array_search($date_string, array_column($booking_rows, 'date'));
                    } elseif ($data_period == 'month') {
                        $date_string = "$cur_year-$cur_month-" . sprintf('%02d', $i + 1);
                        $lview_row   = array_search($date_string, array_column($listing_views, 'date'));
                        $earning_row = array_search($date_string, array_column($earning_rows, 'date'));
                        $booking_row = array_search($date_string, array_column($booking_rows, 'date'));
                    } elseif ($data_period == 'year') {
                        $date_string = "$cur_year-" . sprintf('%02d', $i + 1);
                        $lview_row   = array_search($date_string, array_map(function ($year_date) {return substr($year_date, 0, 7);}, array_column($listing_views, 'date')));
                        $earning_row = array_search($date_string, array_map(function ($year_date) {return substr($year_date, 0, 7);}, array_column($earning_rows, 'date')));
                        $booking_row = array_search($date_string, array_map(function ($year_date) {return substr($year_date, 0, 7);}, array_column($booking_rows, 'date')));
                    }

                    // $lview_row = array_search($date_string, array_column($listing_views, 'date'));
                    if ($lview_row === false) {
                        $lview = 0;
                    } else {
                        $lview = $listing_views[$lview_row]['sum'];
                    }

                    if ($earning_row === false) {
                        $earning = 0;
                    } else {
                        $earning = $earning_rows[$earning_row]['sum'];
                    }

                    if ($booking_row === false) {
                        $booking = 0;
                    } else {
                        $booking = $booking_rows[$booking_row]['sum'];
                    }

                    // if(isset($listing_views[$i])) $lview = $listing_views[$i]['sum'];
                    $cdatas = array();
                    if( $hide_views != 'yes' ) $cdatas[] = $lview;
                    if( $hide_bookings != 'yes' ) $cdatas[] = $booking;
                    if( $hide_earnings != 'yes' ) $cdatas[] = $earning;
                    $chart_datas[$i] = array(
                        'date_string' => $date_string,
                        'label'       => $label_arr[$i],
                        'views'       => $lview,
                        'earning'     => $earning,
                        'booking'     => $booking,
                        'datas'        => $cdatas,
                    );
                }
                // end week - month and year stats
            }

            // $json['chart'] = array_reverse($chart_datas);
            $json['chart'] = $chart_datas;

            $json['success'] = true;

            //    $lbooking_post =array(
            //        'post_type'     =>  'lbooking',
            //        'post_status'   => 'publish',
            //        // 'meta_query' =>  array(
            //        // // show user bookings
            //        //     array(
            //        //         'relation' => 'AND',
            //        //         array(
            //        //             'key'     => ESB_META_PREFIX.'lb_email',
            //        //             'value'   => $current_user->user_email,
            //        //         ),
            //        //     ),
            //        // )

            // );
            //    $count_lbokking = count($lbooking_post);
            //    $json['data']['count_lbokking']= $count_lbokking;
        } else {
            $json['data']['error'] = __('The author id is incorrect.', 'townhub-add-ons');
        }

        wp_send_json($json);

    }
    // edit user profile ajax callback
    public static function edit_profile()
    {

        $json = array(
            'success' => false,
            'data'    => array(
                // 'POST'=>$_POST,
                // 'FILES'=>$_FILES,
            ),
            'debug'   => false,
        );
        // wp_send_json($json );

        self::demo_mode_check();

        self::verify_nonce('townhub-add-ons');

        $user_data = array(
            'ID'           => get_current_user_id(),
            'first_name'   => $_POST['first_name'],
            'last_name'    => $_POST['last_name'],
            'display_name' => $_POST['display_name'],
            'user_url'     => $_POST['user_url'],
            'description'  => $_POST['description'],
        );

        $user_id = wp_update_user($user_data);

        if (is_wp_error($user_id)) {
            // There was an error, probably that user doesn't exist.
            $json['data']['error'] = $user_id->get_error_message();
        } else {
            // $json['data']['user_id'] = $user_id;

            $meta_fields = array(
                'email'         => 'text',
                'phone'         => 'text',
                'address'       => 'text',
                'socials'       => 'array',
                // for custom avatar upload
                'custom_avatar' => 'array',
                'company'       => 'text',
                'cover_bg'      => 'array',
            );
            $user_metas = array();
            foreach ($meta_fields as $fname => $ftype) {
                if($ftype == 'array'){
                    $user_metas[$fname] = isset($_POST[$fname]) ? $_POST[$fname]  : array();
                }else{
                    $user_metas[$fname] = isset($_POST[$fname]) ? esc_html($_POST[$fname]) : '';
                }


                // if (isset($_POST[$field])) {
                //     $user_metas[$field] = $_POST[$field];
                // } else {
                //     if ($ftype == 'array') {
                //         $user_metas[$field] = array();
                //     } else {
                //         $user_metas[$field] = '';
                //     }
                // }
            }

            // check for custom avatar upload
            if (isset($_FILES['custom_avatar_upload']) && $_FILES['custom_avatar_upload']['error'] === UPLOAD_ERR_OK) {
                $movefile = townhub_addons_handle_image_upload($_FILES['custom_avatar_upload']);

                if (is_array($movefile)) {
                    // https://wordpress.stackexchange.com/questions/40301/how-do-i-set-a-featured-image-thumbnail-by-image-url-when-using-wp-insert-post
                    // https://codex.wordpress.org/Function_Reference/wp_insert_attachment
                    // Prepare an array of post data for the attachment.
                    $attachment = array(
                        // 'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
                        'post_mime_type' => $movefile['type'],
                        'post_title'     => sanitize_file_name(basename($movefile['file'])),
                        'post_content'   => '',
                        'post_status'    => 'inherit',
                    );

                    // Insert the attachment.
                    $attach_id = wp_insert_attachment($attachment, $movefile['file']);

                    if ($attach_id != 0) {
                        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                        require_once ABSPATH . 'wp-admin/includes/image.php';

                        // Generate the metadata for the attachment, and update the database record.
                        $attach_data = wp_generate_attachment_metadata($attach_id, $movefile['file']);
                        // return value from update_post_meta -  https://codex.wordpress.org/Function_Reference/update_post_meta
                        // Returns meta_id if the meta doesn't exist, otherwise returns true on success and false on failure. NOTE: If the meta_value passed to this function is the same as the value that is already in the database, this function returns false.
                        wp_update_attachment_metadata($attach_id, $attach_data);

                        // $user_metas['custom_avatar'] = array( $attach_id => wp_get_attachment_url( $attach_id ) );
                        $user_metas['custom_avatar'] = array($attach_id);
                    } else {
                        $json['data']['avatar_upload_error'] = __("wp_insert_attachment error on custom avatar upload image", 'townhub-add-ons');
                    }
                } else {
                    $json['data']['avatar_upload_error'] = $movefile;
                }
            }
            // end custom avatar upload
            // unset custom avatar if empty
            if (empty($user_metas['custom_avatar'])) {
                unset($user_metas['custom_avatar']);
            }
            if( isset($_POST['custom_avatar_delete']) && $_POST['custom_avatar_delete'] == 'yes' ) $user_metas['custom_avatar'] = '';

            // check for cover bg upload
            if (isset($_FILES['cover_bg_upload']) && $_FILES['cover_bg_upload']['error'] === UPLOAD_ERR_OK) {
                $movefile = townhub_addons_handle_image_upload($_FILES['cover_bg_upload']);

                if (is_array($movefile)) {
                    // https://wordpress.stackexchange.com/questions/40301/how-do-i-set-a-featured-image-thumbnail-by-image-url-when-using-wp-insert-post
                    // https://codex.wordpress.org/Function_Reference/wp_insert_attachment
                    // Prepare an array of post data for the attachment.
                    $attachment = array(
                        // 'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
                        'post_mime_type' => $movefile['type'],
                        'post_title'     => sanitize_file_name(basename($movefile['file'])),
                        'post_content'   => '',
                        'post_status'    => 'inherit',
                    );

                    // Insert the attachment.
                    $attach_id = wp_insert_attachment($attachment, $movefile['file']);

                    if ($attach_id != 0) {
                        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                        require_once ABSPATH . 'wp-admin/includes/image.php';

                        // Generate the metadata for the attachment, and update the database record.
                        $attach_data = wp_generate_attachment_metadata($attach_id, $movefile['file']);
                        // return value from update_post_meta -  https://codex.wordpress.org/Function_Reference/update_post_meta
                        // Returns meta_id if the meta doesn't exist, otherwise returns true on success and false on failure. NOTE: If the meta_value passed to this function is the same as the value that is already in the database, this function returns false.
                        wp_update_attachment_metadata($attach_id, $attach_data);

                        // $user_metas['custom_avatar'] = array( $attach_id => wp_get_attachment_url( $attach_id ) );
                        $user_metas['cover_bg'] = array($attach_id);
                    } else {
                        $json['data']['cover_bg_upload_error'] = __("wp_insert_attachment error on custom avatar upload image", 'townhub-add-ons');
                    }
                } else {
                    $json['data']['cover_bg_upload_error'] = $movefile;
                }
            }
            // end custom avatar upload
            // unset custom avatar if empty
            if (empty($user_metas['cover_bg'])) {
                unset($user_metas['cover_bg']);
            }
            if( isset($_POST['cover_bg_delete']) && $_POST['cover_bg_delete'] == 'yes' ) $user_metas['cover_bg'] = '';

            foreach ($user_metas as $key => $value) {
                update_user_meta($user_id, ESB_META_PREFIX . $key, $value);
            }
            // end update meta field

            $json['success'] = true;

            do_action('cth_author_profile_metas', $user_id);

            // send notification to current user
            Esb_Class_Dashboard::add_notification($user_id, array(
                'type'      => 'edit_profile',
                'entity_id' => $user_id,
            ));
            if (townhub_addons_get_option('edit_profile_redirect', true)) {
                $json['data']['url'] = get_permalink(esb_addons_get_wpml_option('dashboard_page'));
            } else {
                $json['data']['message'] = __('Your profile has been updated.', 'townhub-add-ons');
            }

        }

        wp_send_json($json);

    }

    public static function change_pass()
    {
        $json = array(
            'success' => false,
            'data'    => array(
                // 'POST'=>$_POST,
                // 'FILES'=>$_FILES,
            ),
            'debug'   => false,
        );
        // wp_send_json($json );

        self::demo_mode_check();

        self::verify_nonce('townhub-add-ons');

        $current_user = wp_get_current_user();
        // $json['data']['current_user'] = $current_user;
        if ($current_user->exists()) {
            $old_pass = $_POST['old_pass'];
            if (wp_check_password($old_pass, $current_user->data->user_pass, $current_user->ID)) {
                // $json['data'][] = esc_html__( 'The current password is correct.', 'townhub-add-ons' ) ;
                $new_pass     = $_POST['new_pass'];
                $confirm_pass = $_POST['confirm_pass'];
                if ($new_pass === $confirm_pass) {
                    // wp_set_password( $new_pass, $current_user->ID );
                    $user_id_new = wp_update_user(array('ID' => $current_user->ID, 'user_pass' => $new_pass));
                    if (is_wp_error($user_id_new)) {

                        $json['data']['error'] = $user_id_new->get_error_message();
                    } else {
                        $json['success'] = true;

                        do_action('cth_author_password_changed', $user_id_new);

                        // send notification to current user
                        Esb_Class_Dashboard::add_notification($user_id_new, array(
                            'type'      => 'password_changed',
                            'entity_id' => $user_id_new,
                        ));
                        if (townhub_addons_get_option('change_pass_redirect', true)) {
                            $json['data']['url'] = get_permalink(esb_addons_get_wpml_option('dashboard_page'));
                        } else {
                            $json['data']['message'] = __('Your password has been changed.', 'townhub-add-ons');
                        }

                    }
                } else {

                    $json['data']['error'] = esc_html__('The new password does not match each other.', 'townhub-add-ons');
                }
            } else {

                $json['data']['error'] = esc_html__('The old password is incorrect.', 'townhub-add-ons');
            }
        } else {

            $json['data']['error'] = esc_html__('User does not exists. Can not update password', 'townhub-add-ons');
        }

        wp_send_json($json);
    }
    public static function filter_users($users = array())
    {
        if (is_array($users)) {
            $users = array_filter($users, function ($user) {
                return !empty($user) && is_numeric($user);
            });
            return array_unique($users);
        }
        return array();
    }
    public static function follow_author()
    {
        $json = array(
            'success' => false,
            'data'    => array(
                'POST' => $_POST,
            ),
            'debug'   => false,
        );
        // wp_send_json($json );

        self::verify_nonce('townhub-add-ons');
        $author_id    = isset($_POST['author']) ? intval($_POST['author']) : 0;
        $current_user = wp_get_current_user();
        if ($author_id > 0 && $current_user->exists()) {
            $user_following  = (array) get_user_meta($current_user->ID, ESB_META_PREFIX . 'following', true);
            $author_follower = (array) get_user_meta($author_id, ESB_META_PREFIX . 'follower', true);
            if (!in_array($current_user->ID, $author_follower) || !in_array($author_id, $user_following)) {
                $user_following[]  = $author_id;
                $author_follower[] = $current_user->ID;

                update_user_meta($author_id, ESB_META_PREFIX . 'follower', self::filter_users($author_follower));
                update_user_meta($current_user->ID, ESB_META_PREFIX . 'following', self::filter_users($user_following));

                $json['success']        = true;
                $json['data']['button'] = __('Following', 'townhub-add-ons');
                do_action('cth_follow_author_after', $author_id, $json);
            } else {
                $json['data']['error'] = esc_html__('You have already followed this author.', 'townhub-add-ons');
            }

        } else {
            $json['data']['error'] = esc_html__('User does not exists. Can not follow user', 'townhub-add-ons');
        }
        wp_send_json($json);
    }
    public static function unfollow_author()
    {
        $json = array(
            'success' => false,
            'data'    => array(
                'POST' => $_POST,
            ),
            'debug'   => false,
        );
        // wp_send_json($json );

        self::verify_nonce('townhub-add-ons');
        $author_id    = isset($_POST['author']) ? intval($_POST['author']) : 0;
        $current_user = wp_get_current_user();
        if ($author_id > 0 && $current_user->exists()) {
            $user_following  = (array) get_user_meta($current_user->ID, ESB_META_PREFIX . 'following', true);
            $author_follower = (array) get_user_meta($author_id, ESB_META_PREFIX . 'follower', true);
            if (in_array($current_user->ID, $author_follower) && in_array($author_id, $user_following)) {
                // https://stackoverflow.com/questions/7225070/php-array-delete-by-value-not-key
                if (($key = array_search($author_id, $user_following)) !== false) {
                    unset($user_following[$key]);
                }
                if (($key = array_search($current_user->ID, $author_follower)) !== false) {
                    unset($author_follower[$key]);
                }

                update_user_meta($author_id, ESB_META_PREFIX . 'follower', self::filter_users($author_follower));
                update_user_meta($current_user->ID, ESB_META_PREFIX . 'following', self::filter_users($user_following));

                $json['success'] = true;

                $json['data']['button'] = __('Follow <i class="fal fa-user-plus"></i>', 'townhub-add-ons');

                do_action('cth_unfollow_author_after', $author_id, $json);
            } else {
                $json['data']['error'] = esc_html__('You haven\'t followed this author.', 'townhub-add-ons');
            }

        } else {
            $json['data']['error'] = esc_html__('User does not exists. Can not follow user', 'townhub-add-ons');
        }
        wp_send_json($json);
    }
    public static function iconpicker()
    {
        $json = array(
            // 'success' => false,
            // 'data' => array(
            //     'POST'=>$_POST,
            // ),
            'debug' => false,
        );
        self::verify_nonce('townhub-add-ons');
        $styles_name = array(
            'fas' => __('Solid', 'townhub-add-ons'),
            'far' => __('Regular', 'townhub-add-ons'),
            'fal' => __('Light', 'townhub-add-ons'),
            'fad' => __('Duotone', 'townhub-add-ons'),
            'fab' => __('Brands', 'townhub-add-ons'),
        );
        $styles       = array();
        $icons        = townhub_addons_extract_awesome_pro_icon_array();
        $return_icons = array();
        if (!empty($icons)) {
            foreach ($icons as $icon => $name) {
                $return_icons[] = trim($icon);
                $parts          = explode(" ", trim($icon));
                if (!isset($styles[$parts[0]])) {
                    $styles[$parts[0]] = $styles_name[$parts[0]];
                }

            }
        }

        $json['styles'] = $styles;
        $json['icons']  = $return_icons;

        wp_send_json($json);
    }
    public static function fetch_images()
    {
        $json = array(
            'success' => false,
            'debug'   => false,
            'data' => array(
                'POST'=>$_POST,
            ),
            'images'  => array(),
        );

        self::verify_nonce('townhub-add-ons');

        $images = isset($_POST['images']) ? $_POST['images'] : array();
        if (is_array($images) && !empty($images)) {
            foreach ($images as $id) {
                $json['images'][] = array(
                    'id'    => $id,
                    'url'   => wp_get_attachment_url($id),

                    'title' => get_the_title($id),
                    'type'  => get_post_mime_type($id),
                );

            }

        }
        $json['success'] = true;
        wp_send_json($json);
    }
}

Esb_Class_Dashboard::init();
