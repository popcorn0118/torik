<?php
/* add_ons_php */

add_action( 'townhub_author_contact_form_after', function($author_id){
    if(!is_user_logged_in()):
    ?>
    <div class="terms_wrap">
        <?php if(townhub_addons_get_option('register_term_text') != ''): ?>
        <div class="filter-tags">
            <input id="accept_term_contact" name="accept_term" value="1" type="checkbox" required="required">
            <label for="accept_term_contact"><?php echo townhub_addons_get_option('register_term_text');?></label>
        </div>
        <?php endif; ?>
        <?php if(townhub_addons_get_option('register_consent_data_text') != ''): ?>
        <div class="filter-tags">
            <input id="consent_data_contact" name="consent_data" value="1" type="checkbox" required="required">
            <label for="consent_data_contact"><?php echo townhub_addons_get_option('register_consent_data_text');?></label>
        </div>
        <?php endif; ?>
    </div>
    <div class="clearfix"></div>
    <?php
    endif;
} );

// add_action( 'townhub_author_contact_form_before', function($author_id){
//     if(!is_user_logged_in()){

//         echo _e( '<div class="author-contant-head">Your email will be used to register new user.<br> And your message will be sent under that new user.</div>', 'townhub-add-ons' );

//     }
// } );



// submit chat reply
add_action('wp_ajax_nopriv_townhub_addons_chat_reply', 'townhub_addons_chat_reply_callback');
add_action('wp_ajax_townhub_addons_chat_reply', 'townhub_addons_chat_reply_callback');

function townhub_addons_chat_reply_callback() {
    $json = array(
        'success' => false,
        'data' => array(
            'POST'=>$_POST,
        ),
        'debug'     => false,
    );
    $nonce = $_POST['_nonce'];
    if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
        $json['error'] = __( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ;
        wp_send_json($json );
    }

    $result = townhub_addons_do_post_reply($_POST);

    wp_send_json( array_merge($json, $result) );
}

function townhub_addons_do_post_reply( $POSTED = array(), $checkuser = true ){
    global $wpdb;
    $user_id_checked = 0;
    // if loggined user
    if( isset($POSTED['user_id']) && $POSTED['user_id'] ){
        if( $checkuser && get_current_user_id() != $POSTED['user_id'] ){
            // incorrect logged in user
            return array(
                'success' => false,
                'error' => __( 'Incorrect logged in user.', 'townhub-add-ons' ),
                'reply'     => array(),
            );
        }

        $user_id_checked = (int)$POSTED['user_id'];

    }else{ // for guest message
        return array(
            'success' => false,
            'error' => __( 'Invalid send user', 'townhub-add-ons' ),
            'reply'     => array(),
        );
    }

    // check for to user id (receive user)
    $to_user_id_checked = 0;
    if(isset($POSTED['touid']) && $POSTED['touid']){
        $to_user_id_checked = (int)$POSTED['touid'];
    }else{ // for guest message
        return array(
            'success' => false,
            'error' => __( 'You have no contact to chat.<br>Please use chat form on single listing page to begin chat with author.', 'townhub-add-ons' ),
            'reply'     => array(),
        );
    }

    $chat_table = $wpdb->prefix . 'cth_chat';
    $chat_reply_table = $wpdb->prefix . 'cth_chat_reply';

    $time = date_i18n('U');
    $ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '::2'; // check for app

    $chat_id_checked = 0;
    if($user_id_checked != $to_user_id_checked){
        // check for chat
        $chat_id_checked = isset($POSTED['cid']) ? $POSTED['cid'] : 0;
        
        if( $chat_id_checked == 'New' ){
            $chatids = $wpdb->get_col( "SELECT c_id FROM $chat_table WHERE ((user_one ='$user_id_checked' AND user_two ='$to_user_id_checked') OR (user_one ='$to_user_id_checked' AND user_two ='$user_id_checked')) ");
            $endIDs = $chatids; 
            $chat_id_checked = end( $endIDs );
        }else{
            $chatids = $wpdb->get_col( "SELECT c_id FROM $chat_table WHERE ((user_one ='$user_id_checked' AND user_two ='$to_user_id_checked') OR (user_one ='$to_user_id_checked' AND user_two ='$user_id_checked')) AND c_id = '$chat_id_checked' ");
        }
        if( !$chatids || empty($chat_id_checked) ){
            // create new chat row

            $result = $wpdb->insert( 
                $chat_table, 
                array( 
                    
                    'user_one'  => $user_id_checked, 
                    'user_two'  => $to_user_id_checked, 
                    'ip'        => $ip, 
                    'time'      => $time, 
                ) 
            );
            // end inshert chat
            // https://codex.wordpress.org/Class_Reference/wpdb#INSERT_row
            if($result != false){
                $chat_id_checked = $wpdb->insert_id;
            }
        }
        
    }

    $replyText = isset( $POSTED['reply_text'] ) ? wp_unslash( $POSTED['reply_text'] ) : '';
    // user_id
    // cid
    // touid
    $reply_insert_id = 0;
    if($chat_id_checked){
        $result = $wpdb->insert( 
            $chat_reply_table, 
            array( 
                
                'user_id_fk'    => $user_id_checked, 
                'reply'         => wp_kses_post( $replyText ), 
                'ip'            => $ip, 
                'time'          => $time, 
                'c_id_fk'       => $chat_id_checked
            ) 
        );
        if($result != false){
            $reply_insert_id = $wpdb->insert_id;
        }
    }

    $reply_user = get_userdata( $user_id_checked );

    $reply_obj = array();

    $reply_obj['cid'] = $chat_id_checked;
    $reply_obj['crid'] = $reply_insert_id;
    $reply_obj['crtime'] = $time;
    $reply_obj['reply'] = wp_kses_post( $replyText );
    $reply_obj['uid'] = $user_id_checked;
    $reply_obj['display_name'] = $reply_user->display_name;
    $reply_obj['user_email'] = $reply_user->user_email;
    $reply_obj['user_one'] = 0;
    $reply_obj['user_two'] = 0;


    $reply_obj['avatar'] = get_avatar($reply_user->user_email,'150','https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150', $reply_user->display_name );
    $reply_obj['avatar_url'] = get_avatar_url( $reply_user->user_email, array('size'=>150, 'default'=>'https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150') );
            
    $reply_obj['time'] = sprintf(__( '%s <span>%s</span>', 'townhub-add-ons' ), date_i18n( get_option('date_format'), townhub_addons_gmt_to_local_timestamp($time) ), date_i18n( get_option('time_format'), townhub_addons_gmt_to_local_timestamp($time) ));
    $reply_obj['current_user'] = $user_id_checked;
    $reply_obj['to_user'] = $to_user_id_checked;



    $reply_post = $wpdb->get_results( $wpdb->prepare( 
            "
            SELECT R.cr_id AS crid,R.time AS crtime,R.reply,U.ID AS uid,U.display_name,U.user_email, C.user_one, C.user_two, C.c_id AS cid
            FROM $chat_reply_table R, $wpdb->users U, $chat_table C
            WHERE U.ID = R.user_id_fk AND C.c_id = R.c_id_fk AND R.cr_id = %s
            ",
            $reply_insert_id
        )
    );
    if ( $reply_post ){
        $reply_data  = reset($reply_post);

        $reply_obj['crid'] = $reply_data->crid;
        $reply_obj['crtime'] = $reply_data->crtime;
        $reply_obj['reply'] = $reply_data->reply;
        $reply_obj['uid'] = $reply_data->uid;
        $reply_obj['display_name'] = $reply_data->display_name;
        $reply_obj['user_email'] = $reply_data->user_email;
        $reply_obj['user_one'] = $reply_data->user_one;
        $reply_obj['user_two'] = $reply_data->user_two;


        $reply_obj['avatar'] = get_avatar($reply_data->user_email,'150','https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150', $reply_data->display_name );
        $reply_obj['avatar_url'] = get_avatar_url( $reply_data->user_email, array('size'=>150, 'default'=>'https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150') );
       
        $reply_obj['time'] = sprintf(__( '%s <span>%s</span>', 'townhub-add-ons' ), date_i18n( get_option('date_format'), townhub_addons_gmt_to_local_timestamp($reply_data->crtime) ), date_i18n( get_option('time_format'), townhub_addons_gmt_to_local_timestamp($reply_data->crtime) ));
        $reply_obj['current_user'] = $user_id_checked;

        do_action( 'cth_chat_reply_after', $reply_obj  );

    }
    return array(
        'success'       => true,
        'error'         => '',
        'reply'         => $reply_obj,
    );
}
// get replies
add_action('wp_ajax_nopriv_townhub_addons_chat_replies', 'townhub_addons_chat_replies_callback');
add_action('wp_ajax_townhub_addons_chat_replies', 'townhub_addons_chat_replies_callback');

function townhub_addons_chat_replies_callback() {
    $json = array(
        'success' => false,
        'data' => array(
            'POST'=>$_POST,
        )
    );
    $nonce = $_POST['_nonce'];
    
    if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
        $json['data'] = __( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ;
        wp_send_json( $json );
    }
    
    $return = townhub_addons_get_replies($_POST);
    wp_send_json( $return );
}

function townhub_addons_get_replies($DATAS = array()){
    $return = array(
        'success'=> false,
        'replies' => array(),
    );
    if(isset($DATAS['cid']) && $DATAS['cid']){
        // set first reply for new user chat
        if($DATAS['cid'] == 'new' ){
            // for listing author data 
            if(isset($DATAS['touid']) && isset($DATAS['repliesCount']) && $DATAS['repliesCount'] == 1){
                $listing_author = get_userdata( $DATAS['touid'] );
                $time = date_i18n('U');
                $first_reply = array();
                $first_reply['crid'] = 1;
                $first_reply['crtime'] = $time;
                $first_reply['reply'] = sprintf(__( 'Hello, I am %s.<br>May I help you?', 'townhub-add-ons' ), $listing_author->display_name );
                $first_reply['uid'] = $listing_author->ID;
                $first_reply['display_name'] = $listing_author->display_name;
                $first_reply['user_email'] = $listing_author->user_email;
                $first_reply['user_one'] = get_current_user_id();


                $first_reply['avatar'] = get_avatar($listing_author->user_email,'150','https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150', $listing_author->display_name );
                $first_reply['avatar_url'] = get_avatar_url( $listing_author->user_email, array('size'=>150, 'default'=>'https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150') );

                $first_reply['time'] = sprintf(__( '%s <span>%s</span>', 'townhub-add-ons' ), date_i18n( get_option('date_format'), townhub_addons_gmt_to_local_timestamp($time) ), date_i18n( get_option('time_format'), townhub_addons_gmt_to_local_timestamp($time) ));
                $first_reply['current_user'] = get_current_user_id();
                $return['success'] = true;
                $return['replies'] = array($first_reply);
            }else{
                $return['replies'] = array();
            }
        }else{
            // modify get replies clauses
            add_filter( 'ctb_chat_replies_clauses', function($clauses) use ($DATAS){
                // get new latest replies
                if( isset($DATAS['lastRID']) && $DATAS['lastRID'] ){
                    $clauses['wheres'] .= " AND R.cr_id > {$DATAS['lastRID']}";
                }
                // get 5 prev replies
                if( isset($DATAS['firstRID']) && $DATAS['firstRID'] ){
                    $clauses['wheres'] .= " AND R.cr_id < {$DATAS['firstRID']}";
                    // $clauses['orders'] = " ORDER BY R.cr_id DESC";
                    if(townhub_addons_get_option('messages_prev_load') > 0){
                        $clauses['limits'] = townhub_addons_get_option('messages_prev_load');
                    }
                }
                return $clauses;
            } );
            $return['success'] = true;
            $return['replies'] = townhub_addons_get_chat_replies( $DATAS['cid'] );
        }
            
    }else{
        $return['error'] = __( 'Invalid chat contact.<br>Please use chat form on single listing page to begin chat with author.', 'townhub-add-ons' ) ;
    }

    return $return;
}
function townhub_addons_get_chat_replies($chat_id = 0){
    global $wpdb;

    if(!$chat_id || !is_numeric($chat_id)) return false;

    $chat_table = $wpdb->prefix . 'cth_chat';
    $chat_reply_table = $wpdb->prefix . 'cth_chat_reply';

    $selects = "SELECT R.cr_id AS crid,R.time AS crtime,R.reply,U.ID AS uid,U.display_name,U.user_email, C.user_one,C.c_id AS cid";
    $froms = "FROM $chat_reply_table R, $wpdb->users U, $chat_table C";
    $wheres = $wpdb->prepare( "WHERE R.user_id_fk = U.ID AND C.c_id = R.c_id_fk AND R.c_id_fk = %s", $chat_id);
    $orders = "ORDER BY R.cr_id DESC"; // get last replies then reverse using php
    if(townhub_addons_get_option('messages_first_load') > 0){
        $limits = townhub_addons_get_option('messages_first_load');
    }

    $pieces = array( 'selects', 'froms', 'wheres', 'orders', 'limits' );


    $clauses = (array) apply_filters_ref_array( 'ctb_chat_replies_clauses', array( compact( $pieces ) ) );

    $selects = isset( $clauses[ 'selects' ] ) ? $clauses[ 'selects' ] : '';
    $froms = isset( $clauses[ 'froms' ] ) ? $clauses[ 'froms' ] : '';
    $wheres = isset( $clauses[ 'wheres' ] ) ? $clauses[ 'wheres' ] : '';
    $orders = isset( $clauses[ 'orders' ] ) ? $clauses[ 'orders' ] : '';
    $limits = isset( $clauses[ 'limits' ] ) ? $clauses[ 'limits' ] : '';

    if($limits != '') $limits = "LIMIT {$limits}";

    
    $replies = $wpdb->get_results( $selects . ' ' . $froms . ' ' . $wheres . ' ' . $orders . ' ' . $limits );

    $results = array();

    if($replies){
        // $replies = array_reverse($replies);
        foreach (array_reverse($replies) as $reply) {
            $reply->avatar =  get_avatar($reply->user_email,'150','https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150', $reply->display_name );
            $reply->avatar_url = get_avatar_url( $reply->user_email, array('size'=>150, 'default'=>'https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150') );
                
            $reply->time = sprintf(__( '%s <span>%s</span>', 'townhub-add-ons' ), date_i18n( get_option('date_format'), townhub_addons_gmt_to_local_timestamp($reply->crtime) ), date_i18n( get_option('time_format'), townhub_addons_gmt_to_local_timestamp($reply->crtime) ));
            $reply->current_user = get_current_user_id();
            $results[] = $reply;
        }
        
    }
    return $results;

}

function townhub_addons_delete_chat_user( $user_id ) {
    global $wpdb;

    $chat_table = $wpdb->prefix . 'cth_chat';
    $chat_reply_table = $wpdb->prefix . 'cth_chat_reply';

    $wpdb->query( 
        $wpdb->prepare( 
            "
            DELETE FROM $chat_reply_table
            WHERE c_id_fk IN (SELECT c_id FROM $chat_table WHERE user_one = %d OR user_two = %d)
            ",
            $user_id,
            $user_id
        )
    );

    $wpdb->query( 
        $wpdb->prepare( 
            "
            DELETE FROM $chat_table
            WHERE user_one = %d OR user_two = %d
            ",
            $user_id,
            $user_id
        )
    );

}
add_action( 'delete_user', 'townhub_addons_delete_chat_user' );

function townhub_addons_gmt_to_local_timestamp( $gmt_timestamp ) {
    $iso_date        = date( 'Y-m-d H:i:s', $gmt_timestamp );
    $local_timestamp = get_date_from_gmt( $iso_date, 'U' );

    return $local_timestamp;
}

/*
[12-Nov-2018 11:51:49 UTC] WordPress database error Subquery returns more than 1 row for query 
            DELETE FROM wp_cth_chat_reply
            WHERE c_id_fk = (SELECT c_id FROM wp_cth_chat WHERE user_one = 980 OR user_two = 980)
             made by wp_delete_user, do_action('delete_user'), WP_Hook->do_action, WP_Hook->apply_filters, townhub_addons_delete_chat_user
[12-Nov-2018 11:51:49 UTC] WordPress database error Cannot delete or update a parent row: a foreign key constraint fails (`servers_townhub`.`wp_cth_chat_reply`, CONSTRAINT `wp_cth_chat_reply_ibfk_2` FOREIGN KEY (`c_id_fk`) REFERENCES `wp_cth_chat` (`c_id`)) for query 
            DELETE FROM wp_cth_chat
            WHERE user_one = 980 OR user_two = 980
             made by wp_delete_user, do_action('delete_user'), WP_Hook->do_action, WP_Hook->apply_filters, townhub_addons_delete_chat_user
[12-Nov-2018 11:51:49 UTC] WordPress database error Cannot delete or update a parent row: a foreign key constraint fails (`servers_townhub`.`wp_cth_chat`, CONSTRAINT `wp_cth_chat_ibfk_1` FOREIGN KEY (`user_one`) REFERENCES `wp_users` (`ID`)) for query DELETE FROM `wp_users` WHERE `ID` = 980 made by wp_delete_user
*/


// get chat contact for current user - create new on to site admin if not existing

add_action('wp_ajax_nopriv_townhub_addons_chat_contacts', 'townhub_addons_chat_contacts_callback');
add_action('wp_ajax_townhub_addons_chat_contacts', 'townhub_addons_chat_contacts_callback');
function townhub_addons_chat_contacts_callback(){
    $json = array(
        'success' => false,
        'data' => array(
            'POST'=>$_POST,
        ),
        'debug'     => false
    );

    $nonce = $_POST['_nonce'];
    if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
        $json['data']['error'] = __( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ;
        wp_send_json($json );
    }
    $lauthor_id = null;
    $post_id = isset($_POST['post_id'])? $_POST['post_id'] : 0;
    if(!empty($post_id)){
        $post_obj = get_post( $post_id );
        if(null != $post_obj && 'listing' == $post_obj->post_type ) 
            $lauthor_id = $post_obj->post_author;
    }
    $user_id = isset($_POST['for'])? $_POST['for'] : 0;
    
    $json['chat'] = townhub_addons_get_chats($user_id, $lauthor_id, $post_id);
    $json['lauthor_id'] = $lauthor_id;

    $json['success'] = true;
    wp_send_json( $json );
}

function townhub_addons_get_chats($user_id = 0, $lauthor_id = null, $listing_id = 0){
    global $wpdb;

    if(!$user_id){
        // return false when no user
        if(!is_user_logged_in()) return false;
        $user_id = get_current_user_id();
    }

    $chat_table = $wpdb->prefix . 'cth_chat';
    $chat_reply_table = $wpdb->prefix . 'cth_chat_reply';
    $user_chats = array();
    // $newly_created = false;
    $time = date_i18n('U');
    $ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '::2'; // check for app
    $newly_created_chat = 0;
    $active_chat = 0;
    $touid = 0;
    $fuid = 0;
    $replies = array();
    // check for admin contact
    $admin_user = townhub_addons_get_option('site_owner_id');
    if($admin_user == '0'){
        $add_user = get_users(array(
            'role'      => 'Administrator',
            'number'    => 1,
            // 'fields'    => 'ID'
        ));
        $admin_user = $add_user[0]->ID;
    }
    $test_contact = $wpdb->get_var( $wpdb->prepare( 
        "
            SELECT c_id 
            FROM $chat_table 
            WHERE ( user_one = %d AND user_two = %d ) OR ( user_one = %d AND user_two = %d )
        ", 
        $user_id,
        $admin_user,
        $admin_user,
        $user_id
    ) );

    if( null == $test_contact && townhub_addons_get_option('chat_site_owner') == 'yes' ){
        $result = $wpdb->insert( 
            $chat_table, 
            array( 
                
                'user_one'  => $admin_user, 
                'user_two'  => $user_id, 
                'ip'        => $ip, 
                'time'      => $time, 
            ) 
        );

        if($result != false){
            // $newly_created = true;
            $newly_created_chat = $wpdb->insert_id;

            // add init message
            $result = $wpdb->insert( 
                $chat_reply_table, 
                array( 
                    
                    'user_id_fk'    => $admin_user, 
                    'reply'         => sprintf(__( 'Hello, I am %s.<br>May I help you?', 'townhub-add-ons' ), __( 'the site admin', 'townhub-add-ons' ) ), 
                    'ip'            => '0', 
                    'time'          => $time, 
                    'c_id_fk'       => $newly_created_chat
                ) 
            );
        }
    }
    // end insert admin contact
    
    // check for front-end author contact
    if($lauthor_id !== null && $listing_id != 0){
        $test_contact = $wpdb->get_var( $wpdb->prepare( 
            "
                SELECT c_id 
                FROM $chat_table 
                WHERE ( user_one = %d AND user_two = %d ) OR ( user_one = %d AND user_two = %d )
            ", 
            $user_id,
            $lauthor_id,
            $lauthor_id,
            $user_id
        ) );

        if(null == $test_contact){
            $result = $wpdb->insert( 
                $chat_table, 
                array( 
                    
                    'user_one'  => $lauthor_id, 
                    'user_two'  => $user_id, 
                    'ip'        => $ip, 
                    'time'      => $time, 
                ) 
            );

            if($result != false){
                // $newly_created = true;
                $newly_created_chat = $wpdb->insert_id;

                // add init message
                $result = $wpdb->insert( 
                    $chat_reply_table, 
                    array( 
                        
                        'user_id_fk'    => $lauthor_id, 
                        'reply'         => sprintf(__( 'Hello, I am author of %s listing.<br>May I help you?', 'townhub-add-ons' ), get_the_title( $listing_id ) ), 
                        'ip'            => '0', 
                        'time'          => $time, 
                        'c_id_fk'       => $newly_created_chat
                    ) 
                );
            }
        }
        // end insert author contact

        
    }

    $chats = $wpdb->get_results(
            "
            SELECT u.ID AS uid,c.c_id AS cid,c.time AS ctime,u.display_name,u.user_email,c.user_one
            FROM $chat_table c, $wpdb->users u
            WHERE 
                CASE 
                    WHEN c.user_one = '$user_id'
                        THEN c.user_two = u.ID
                    WHEN c.user_two = '$user_id'
                        THEN c.user_one= u.ID
                END 
            AND ( c.user_one ='$user_id' OR c.user_two ='$user_id' )
            ORDER BY c.c_id DESC
            "
        );

    if ( $chats ){
        foreach ( $chats as $key => $chat ){
            // $chat->uid = $chat->touid;
            $chat->touid = $chat->uid;
            // get active id
            if($key === 0){
                $active_chat = $chat->cid;
                $touid = $chat->touid;
                $fuid = $chat->user_one;

                $replies = townhub_addons_get_chat_replies($chat->cid);
            }
            $last_reply = $wpdb->get_results( $wpdb->prepare( 
                    "
                    SELECT R.cr_id AS crid,R.time AS crtime,R.reply 
                    FROM $chat_reply_table R
                    WHERE R.c_id_fk = %s 
                    ORDER BY R.cr_id DESC LIMIT 1
                    ",
                    $chat->cid
                )
            );

            $chat->date = date_i18n( get_option('date_format'), townhub_addons_gmt_to_local_timestamp($chat->ctime) );

            $last_reply_result = (object) array();

            if ( $last_reply ){
                $last_reply_result = reset($last_reply);
                $last_reply_result->date = date_i18n( get_option('date_format'), townhub_addons_gmt_to_local_timestamp($last_reply_result->crtime) );
            }

            $last_reply_result->avatar = get_avatar($chat->user_email,'150','https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150', $chat->display_name );
            $last_reply_result->avatar_url = get_avatar_url( $chat->user_email, array('size'=>150, 'default'=>'https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150') );
            
            

            $user_chats[] = (object) array_merge((array)$chat, (array)$last_reply_result);

        }   
    }

    return array(
        'active'            => $active_chat,
        'touid'             => $touid,
        'fuid'              => $fuid,
        'contacts'          => $user_chats,
        'replies'           => $replies
    );
    return $user_chats;

}
