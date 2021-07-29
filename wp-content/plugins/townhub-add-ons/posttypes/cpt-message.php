<?php
/* add_ons_php */

class Esb_Class_Message_CPT extends Esb_Class_CPT {
    protected $name = 'lmessage';

    protected function init(){
        parent::init();

        $not_logged_in_ajax_actions = array(
            'townhub_addons_del_lmessage',
            'townhub_addons_lauthor_message',
        );
        foreach ($not_logged_in_ajax_actions as $action) {
            $funname = str_replace('townhub_addons_', '', $action); 
            add_action('wp_ajax_'.$action, array( $this, $funname ));
            add_action('wp_ajax_nopriv_'.$action, array( $this, $funname ));
        }
        add_filter('manage_edit-lmessage_sortable_columns', array($this, 'sortable_columns'));
        add_action('pre_get_posts', array($this, 'sort_order'));
        do_action( $this->name.'_cpt_init_after' );
    }

    public function sortable_columns($columns)
    {

        $columns['_to']      = '_to';
        return $columns;
    }
    public function sort_order($query)
    {
        if (!is_admin()) {
            return;
        }

        $orderby = $query->get('orderby');

        if ('_to' == $orderby) {
            $query->set('meta_key', ESB_META_PREFIX.'to_user_id');
            $query->set('orderby', 'meta_value_num');
        }
    }

    public function register(){

        $labels = array( 
            'name' => __( 'Message', 'townhub-add-ons' ),
            'singular_name' => __( 'Message', 'townhub-add-ons' ),
            'add_new' => __( 'Add New Message', 'townhub-add-ons' ),
            'add_new_item' => __( 'Add New Message', 'townhub-add-ons' ),
            'edit_item' => __( 'Edit Message', 'townhub-add-ons' ),
            'new_item' => __( 'New Message', 'townhub-add-ons' ),
            'view_item' => __( 'View Message', 'townhub-add-ons' ),
            'search_items' => __( 'Search Messages', 'townhub-add-ons' ),
            'not_found' => __( 'No Messages found', 'townhub-add-ons' ),
            'not_found_in_trash' => __( 'No Messages found in Trash', 'townhub-add-ons' ),
            'parent_item_colon' => __( 'Parent Message:', 'townhub-add-ons' ),
            'menu_name' => __( 'Author Messages', 'townhub-add-ons' ),
        );

        $args = array( 
            'labels' => $labels,
            'hierarchical' => false,
            'description' => __( 'List Messages', 'townhub-add-ons' ),
            'supports' => array( 'title' /*, 'editor', 'author', 'thumbnail','comments','excerpt', 'post-formats'*/),
            'taxonomies' => array(),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,//default from show_ui
            'menu_position' => 25,
            'menu_icon' => 'dashicons-email-alt',
            'show_in_nav_menus' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'has_archive' => false,
            'query_var' => true,
            'can_export' => false,
            'rewrite' => array( 'slug' => __('lmessage','townhub-add-ons') ),
            'capability_type' => 'post',
            'capabilities' => array(
                'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout
            ),
            'map_meta_cap' => true, // Set to `false`, if users are not allowed to edit/delete existing posts
        );
        register_post_type( $this->name, $args );
    }
    protected function set_meta_columns(){
        $this->has_columns = true;
    }
    public function meta_columns_head($columns){
        unset($columns['date']);
        unset($columns['author']);
        unset($columns['comments']);
        
        $columns['_from']             = __('From','townhub-add-ons');
        $columns['_to']             = __('To','townhub-add-ons');
        $columns['_status']             = __('Status','townhub-add-ons');
        // $columns['_date']   = __('Date','townhub-add-ons');

        $columns['_id']             = __('ID','townhub-add-ons');
        return $columns;
    }
    public function meta_columns_content($column_name, $post_ID){
        if ($column_name == '_from') {
            if(get_post_meta( $post_ID, ESB_META_PREFIX.'from_user_id', true ) == 0){
                $from_name = get_post_meta( $post_ID, ESB_META_PREFIX.'lmsg_name', true );
            }else{
                $user_info = get_userdata( get_post_meta( $post_ID, ESB_META_PREFIX.'from_user_id', true ) );
                $from_name = $user_info->display_name;
            }
            echo '<strong>'.$from_name.'</strong>';
        }
        if ($column_name == '_to') {
            // only send to an user
            $user_info = get_userdata( get_post_meta( $post_ID, ESB_META_PREFIX.'to_user_id', true ) );
            echo '<strong>'.$user_info->display_name.'</strong>';
        }
        if ($column_name == '_status') {
            echo '<strong>'.self::status_text(get_post_meta( $post_ID, ESB_META_PREFIX.'lmsg_status', true )).'</strong>';
            
        }

        if ($column_name == '_id') {
            echo '<strong>'.$post_ID.'</strong>';
        }
    }

    protected function set_meta_boxes(){
        $this->meta_boxes = array(
            'details'       => array(
                'title'         => __( 'Details', 'townhub-add-ons' ),
                'context'       => 'normal', // normal - side - advanced
                'priority'       => 'core', // default - high - core - low
                'callback_args'       => array(),
            )
        );
    }

    public static function statuses(  ) {
        return array(
            // paypal
            'pending'=> __('New','townhub-add-ons'), 
            'unread'=> __('Unread','townhub-add-ons'), 
            'replied'=> __('Replied','townhub-add-ons'), 
            'completed'=> __('Completed','townhub-add-ons'), 
        );
        
    }

    public static function status_text($status = 'pending'){
        $statuses = self::statuses();
        if(isset($statuses[$status])) return $statuses[$status];
        return $statuses['pending'];
    }

    public function lmessage_details_callback($post, $args){
        wp_nonce_field( 'cth-cpt-fields', '_cth_cpt_nonce' );

        $listing_id             = get_post_meta( $post->ID, ESB_META_PREFIX.'listing_id', true );
        $user_id                = get_post_meta( $post->ID, ESB_META_PREFIX.'user_id', true );
        // $listing_post           = get_post($listing_id);

        $user_info = get_userdata($user_id);

        ?>
        <table class="form-table lclaim-details">
            <tbody>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Name:', 'townhub-add-ons' ); ?></th>
                    <td><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'lmsg_name', true ); ?></a></td>
                </tr>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Email:', 'townhub-add-ons' ); ?></th>
                    <td><a href="mailto:<?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'lmsg_email', true ); ?>"><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'lmsg_email', true ); ?></a></td>
                </tr>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Phone:', 'townhub-add-ons' ); ?></th>
                    <td><span><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'lmsg_phone', true ); ?></span></td>
                </tr>
                <?php 
                if( !empty($listing_id) ): ?>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'For listing:', 'townhub-add-ons' ); ?></th>
                    <td><span><?php echo get_the_title( $listing_id ); ?></span></td>
                </tr>
                <?php endif; ?>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Message:', 'townhub-add-ons' ); ?></th>
                    <td>
                        <?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'lmsg_message', true );?>
                    </td>
                </tr>

            </tbody>
        </table>
        <?php   
    }

    public static function lauthor_message() {
        $json = array(
            'success' => false,
            'data' => array(
                // 'POST'=>$_POST,
            ),
            'debug'     => false,
        );

        Esb_Class_Ajax_Handler::verify_nonce('townhub-add-ons');

        $lmessage_datas = array();
        $lmessage_metas_loggedin = array();
        // for author reply
        $current_user = wp_get_current_user();
        if( isset($_POST['lmsg_name']) ){
            $lmessage_datas['post_title'] = sprintf(__( 'Message from %s', 'townhub-add-ons' ), esc_html($_POST['lmsg_name']) );
            // update user meta
            if( $current_user->ID != 0 ){
                if( get_user_meta($current_user->ID,  ESB_META_PREFIX.'email', true ) == '' && isset($_POST['lmsg_email']) ){
                    update_user_meta( $current_user->ID,  ESB_META_PREFIX.'email', esc_html($_POST['lmsg_email']) );
                }
                if( get_user_meta($current_user->ID,  ESB_META_PREFIX.'phone', true ) == '' && isset($_POST['lmsg_phone']) ){
                    update_user_meta( $current_user->ID,  ESB_META_PREFIX.'phone', esc_html($_POST['lmsg_phone']) );
                }
            }
        }else if( (!isset($_POST['lmsg_name']) || !isset($_POST['lmsg_email'])) && $current_user->ID != 0 ){
            $lmessage_datas['post_title'] = sprintf(__( 'Message from %s', 'townhub-add-ons' ), $current_user->display_name ); 
            $lmessage_metas_loggedin['lmsg_name'] = $current_user->display_name;
            $lmessage_metas_loggedin['lmsg_email'] = get_user_meta($current_user->ID,  ESB_META_PREFIX.'email', true );
            $lmessage_metas_loggedin['lmsg_phone'] = get_user_meta($current_user->ID,  ESB_META_PREFIX.'phone', true );
        }else{
            $json['data']['error'] = __( 'Invalid message form without name and email.', 'townhub-add-ons' );
            wp_send_json($json );
        }
        
        if( isset($_POST['authid']) ){
            $authid = (int)$_POST['authid'];
            if(is_numeric($authid) && (int)$authid > 0){

                
                $lmessage_datas['post_content'] = '';
                //$lmessage_datas['post_author'] = '0';// default 0 for no author assigned
                $lmessage_datas['post_status'] = 'publish';
                $lmessage_datas['post_type'] = 'lmessage';

                do_action( 'townhub_addons_insert_message_before', $lmessage_datas );

                $lmessage_id = wp_insert_post($lmessage_datas ,true );

                if (!is_wp_error($lmessage_id)) {
                    //print( $lmessage_id->get_error_message() );
                    // $json['data']['lmessage_id'] = $lmessage_id;
                    $meta_fields = array(
                        'lmsg_name'                 => 'text',
                        'lmsg_email'                => 'text',
                        'lmsg_phone'                => 'text',

                        
                        'lmsg_message'              => 'text',
                        'lmsg_type'                 => 'text',

                        'first_msg'                 => 'text',
                        'reply_to'                 => 'text',

                        'listing_id'                 => 'text',
                    );
                    $lmessage_metas = array();
                    foreach($meta_fields as $fname => $ftype){
                        if($ftype == 'array'){
                            $lmessage_metas[$fname] = isset($_POST[$fname]) ? $_POST[$fname]  : array();
                        }else{
                            $lmessage_metas[$fname] = isset($_POST[$fname]) ? wp_kses_post($_POST[$fname]) : '';
                        }


                        // if(isset($_POST[$field])) $lmessage_metas[$field] = $_POST[$field] ;
                        // else{
                        //     if($ftype == 'array'){
                        //         $lmessage_metas[$field] = array();
                        //     }else{
                        //         $lmessage_metas[$field] = '';
                        //     }
                        // } 
                    }
                    $lmessage_metas['to_user_id'] = $authid;
                    $lmessage_metas['from_user_id'] = $current_user->ID;
                    $lmessage_metas['lmsg_status'] = 'pending'; // pending - completed - failed - refunded - canceled

                    // merge with logged in customser data
                    $lmessage_metas = array_merge($lmessage_metas,$lmessage_metas_loggedin);

                    // $cmb_prefix = '_cth_';
                    foreach ($lmessage_metas as $key => $value) {
                        update_post_meta( $lmessage_id, ESB_META_PREFIX.$key,  $value  );
                    }

                    // $json['data']['lmessage_metas'] = $lmessage_metas;

                    // update replied status
                    // new message
                    if( !isset($_POST['reply_to']) ){
                        // increase count 
                        self::update_messages_count( $authid );
                    }else{
                        $reply_status = get_post_meta( $_POST['reply_to'], ESB_META_PREFIX.'lmsg_status', true );
                        if( $reply_status === 'pending' ){
                            update_post_meta( $_POST['reply_to'], ESB_META_PREFIX.'lmsg_status',  'replied'  );
                            // decrease count
                            self::update_messages_count( $current_user->ID, true );
                        }
                        self::update_messages_count( $authid );
                    }

                    $json['data']['message'] = apply_filters( 'townhub_addons_insert_message_message', __( 'Your message is received. The listing author will contact with you soon.<br>Thank you.', 'townhub-add-ons' ) );

                    $json['success'] = true;

                    do_action( 'townhub_addons_insert_message_after', $lmessage_id, $lmessage_metas );
                }else{
                    
                    $json['data']['error'] = $lmessage_id->get_error_message();

                    if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Insert booking post error: " . $lmessage_id->get_error_message() . PHP_EOL, 3, ESB_LOG_FILE);

                    // throw new Exception($lmessage_id->get_error_message());

                }

            }else{
                $json['data']['error'] = esc_html__( 'The author id is incorrect.', 'townhub-add-ons' ) ;
            }

        }
    
        wp_send_json($json );

    }
    public static function del_lmessage() {
        $json = array(
            'success' => false,
            'data' => array(
                // 'POST'=>$_POST,
            ),
            'debug' => false
        );
        

        $nonce = $_POST['_nonce'];
        
        if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
            $json['success'] = false;
            $json['data']['error'] = esc_html__( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }


        $msgid = $_POST['msgid'];
        if(is_numeric($msgid) && (int)$msgid > 0){
            if(get_current_user_id() != get_post_meta( $msgid, ESB_META_PREFIX.'to_user_id', true ) ){
                $json['data']['error'] = __( "You don't have permission to delete this message", 'townhub-add-ons' );
                // wp_send_json($json );
            }else{
                $deleted_status = get_post_meta( $msgid, ESB_META_PREFIX.'lmsg_status', true );
                $deleted_post = wp_delete_post( $msgid, true );//move to trash
                if($deleted_post){
                    $json['data']['deleted_message'] = $deleted_post;
                    $json['success'] = true;

                    if( $deleted_status === 'pending' ){
                        // decrease count
                        self::update_messages_count( get_current_user_id(), true );
                    }

                }else{
                    $json['data']['error'] = esc_html__( 'Delete message failure', 'townhub-add-ons' ) ;
                }

            }

                
        }else{
            
            $json['data']['error'] = esc_html__( 'The message id is incorrect.', 'townhub-add-ons' ) ;
        }

        wp_send_json($json );

    }

    public static function update_messages_count($user_id = 0, $decrease = false ){
        if(is_numeric($user_id) && (int)$user_id > 0){
            $messages_count = intval( get_user_meta($user_id, ESB_META_PREFIX . 'messages_count', true) ) ;
            if( $decrease ){
                if( $messages_count > 1){
                    update_user_meta( $user_id, ESB_META_PREFIX . 'messages_count', ($messages_count - 1) );
                }else{
                    update_user_meta( $user_id, ESB_META_PREFIX . 'messages_count', 0 );
                }
            }else{
                update_user_meta( $user_id, ESB_META_PREFIX . 'messages_count', ($messages_count + 1) );
            }
        }
    }

    public static function delete_messages_count($user_id = 0){
        if(is_numeric($user_id) && (int)$user_id > 0){
            update_user_meta( $user_id, ESB_META_PREFIX . 'messages_count', 0 );
        }
    }

}

new Esb_Class_Message_CPT();
