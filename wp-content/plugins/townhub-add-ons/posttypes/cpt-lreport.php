<?php
/* add_ons_php */

class Esb_Class_LReport_CPT extends Esb_Class_CPT {
    protected $name = 'lreport';

    protected function init(){
        parent::init();

        $not_logged_in_ajax_actions = array(
            'townhub_addons_report_listing',
        );
        // foreach ($logged_in_ajax_actions as $action) {
        //     $funname = str_replace('townhub_addons_', '', $action);
        //     add_action('wp_ajax_'.$action, array( $this, $funname ));
        // }

        foreach ($not_logged_in_ajax_actions as $action) {
            $funname = str_replace('townhub_addons_', '', $action);   
            add_action('wp_ajax_'.$action, array( $this, $funname ));
            add_action('wp_ajax_nopriv_'.$action, array( $this, $funname ));
        }

        add_action( 'townhub_addons_lreport_change_status_to_approved', array($this, 'report_approved_callback') );
        do_action( $this->name.'_cpt_init_after' );
    }

    public function register(){

        $labels = array( 
            'name' => __( 'Reports', 'townhub-add-ons' ),
            'singular_name' => __( 'Report', 'townhub-add-ons' ),
            'add_new' => __( 'Add New Report', 'townhub-add-ons' ),
            'add_new_item' => __( 'Add New Report', 'townhub-add-ons' ),
            'edit_item' => __( 'Edit Report', 'townhub-add-ons' ),
            'new_item' => __( 'New Report', 'townhub-add-ons' ),
            'view_item' => __( 'View Report', 'townhub-add-ons' ),
            'search_items' => __( 'Search Reports', 'townhub-add-ons' ),
            'not_found' => __( 'No Reports found', 'townhub-add-ons' ),
            'not_found_in_trash' => __( 'No Reports found in Trash', 'townhub-add-ons' ),
            'parent_item_colon' => __( 'Parent Report:', 'townhub-add-ons' ),
            'menu_name' => __( 'Listing Reports', 'townhub-add-ons' ),
        );

        $args = array( 
            'labels' => $labels,
            'hierarchical' => false,
            'description' => __( 'Listing reports', 'townhub-add-ons' ),
            'supports' => array( 'title'),
            'taxonomies' => array(),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,//default from show_ui
            'menu_position' => 25,
            'menu_icon' => 'dashicons-forms',
            'show_in_nav_menus' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'has_archive' => false,
            'query_var' => false,
            'can_export' => false,
            'rewrite' => array( 'slug' => 'lreport' ),
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
        $columns['_listing']             = __('Listing','townhub-add-ons');
        $columns['_id']             = __('ID','townhub-add-ons');
        $columns['_status']             = __('Status/Actions','townhub-add-ons');
        return $columns;
    }
    public function meta_columns_content($column_name, $post_ID){
        if ($column_name == '_status') {
            echo '<strong>'.$this->get_status( get_post_meta( $post_ID, ESB_META_PREFIX.'report_status', true ) ).'</strong>';
        }

        if ($column_name == '_id') {
            echo '<strong>'.$post_ID.'</strong>';
        }
        if ($column_name == '_listing') {
            $listing = get_post( get_post_meta( $post_ID, ESB_META_PREFIX.'listing_id', true ) );
            if (null != $listing) echo '<a href="'.get_permalink( $listing->ID ).'" target="_blank">'.$listing->post_title.'</a>';
        }
    }
    protected function get_status($status = ''){
        $defaults = array(
            'pending'                   => esc_html__( 'Pending', 'townhub-add-ons' ),
            'approved'                  => esc_html__( 'Appproved - The listing is going to be pending status.', 'townhub-add-ons' ),
            'decline'                   => esc_html__( 'Decline', 'townhub-add-ons' ),
        );

        if($status != '' && isset($defaults[$status])) return $defaults[$status];

        return $defaults;
        
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

    public function lreport_details_callback($post, $args){
        wp_nonce_field( 'cth-cpt-fields', '_cth_cpt_nonce' );

        $listing_id             = get_post_meta( $post->ID, ESB_META_PREFIX.'listing_id', true );
        $user_id                = get_post_meta( $post->ID, ESB_META_PREFIX.'user_id', true );
        $listing_post           = get_post($listing_id);

        $userObject = get_userdata($user_id);
        if( !$userObject){
            $ruser_id       = 0;
            $ruser_name     = get_post_meta( $post->ID, ESB_META_PREFIX.'user_name', true );
            $ruser_email    = get_post_meta( $post->ID, ESB_META_PREFIX.'user_email', true );
            $ruser_url      = '#';
        }else{
            $ruser_id       = $userObject->ID;
            $ruser_name     = $userObject->display_name;
            $ruser_email    = $userObject->user_email;
            $ruser_url      = get_author_posts_url($userObject->ID);
        }
        ?>
        <table class="form-table lreport-details">
            <tbody>

                <tr class="hoz">
                    <th class="w20"><?php _e( 'For Listing', 'townhub-add-ons' ); ?></th>
                    <td>
                        <?php 
                            echo sprintf(__( '<h3><a href="%s" target="_blank">%s</a></h3>', 'townhub-add-ons' ), esc_url(get_permalink($listing_post->ID)), $listing_post->post_title );
                        ?>
                    </td>
                </tr>

                <tr class="hoz">
                    <th class="w20"><?php _ex( 'Report User', 'Report listing', 'townhub-add-ons' ); ?></th>
                    <td>
                    <?php 
                        echo sprintf(_x( '<a href="%s" target="_blank">%s</a>', 'Report listing', 'townhub-add-ons' ), $ruser_url, $ruser_name );
                    ?>
                    </td>
                </tr>
                <tr class="hoz">
                    <th class="w20"><?php _ex( 'Report email','Report listing', 'townhub-add-ons' ); ?></th>
                    <td>
                    <?php 
                        echo sprintf(_x( '<a href="mailto:%s">%s</a>', 'Report listing', 'townhub-add-ons' ), $ruser_email, $ruser_email );
                    ?>
                    </td>
                </tr>
                <?php 
                $statuses = $this->get_status();
                $selected = get_post_meta( $post->ID, ESB_META_PREFIX.'report_status', true ); ?>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Status/Actions', 'townhub-add-ons' ); ?></th>
                    <td>
                        <?php 
                        if(count($statuses)){
                            echo '<select id="report_status" name="report_status">';
                            foreach ($statuses as $status => $label) {
                                echo '<option value="'.$status.'" '.selected( $selected, $status, false ).'>'.$label.'</option>';
                            }
                            echo '</select>';
                        }
                        ?>

                    </td>
                </tr>
            

                <tr class="hoz">
                    <th class="w20"><?php _e( 'Report Message', 'townhub-add-ons' ); ?></th>
                    <td>
                        <textarea name="report_msg" id="report_msg" cols="30" rows="5" class="w100"><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'report_msg', true );?></textarea>
                    </td>
                </tr>

            </tbody>
        </table>
        <?php   
    }

    public function save_post($post_id, $post, $update){
        if(!$this->can_save($post_id)) return;

        if(isset($_POST['report_msg'])){
            $new_val = sanitize_textarea_field( $_POST['report_msg'] ) ;
            $origin_val = get_post_meta( $post_id, ESB_META_PREFIX.'report_msg', true );
            if($new_val !== $origin_val){
                update_post_meta( $post_id, ESB_META_PREFIX.'report_msg', $new_val );
            }
            
        }

        if(isset($_POST['report_status'])){
            $new_status = sanitize_text_field( $_POST['report_status'] ) ;
            $origin_status = get_post_meta( $post_id, ESB_META_PREFIX.'report_status', true );
            if($new_status !== $origin_status){
                update_post_meta( $post_id, ESB_META_PREFIX.'report_status', $new_status );

                // unhook this function so it doesn't loop infinitely
                remove_action( 'save_post_'.$this->name, array($this, 'save_post'), 10, 3  );
                
                    do_action('townhub_addons_lreport_change_status_'.$origin_status.'_to_'.$new_status, $post_id );
                    do_action('townhub_addons_lreport_change_status_to_'.$new_status, $post_id );  

                // re-hook this function
                add_action( 'save_post_'.$this->name, array($this, 'save_post'), 10, 3  );
            }
        }
    }

    // admin approve report
    public function report_approved_callback($report_id = 0){
        if(is_numeric($report_id)&&(int)$report_id > 0){
            $report_post = get_post($report_id);
            if (null != $report_post){
                $listing_id                     = get_post_meta( $report_post->ID, ESB_META_PREFIX.'listing_id', true );
                // update listing author to reported author
                $lis_args = array(
                    'ID'                => $listing_id,
                    'post_status'       => 'pending',
                );
                wp_update_post( $lis_args, true );    
                
                do_action( 'townhub_addons_lreport_approved', $report_id, $listing_id );
            }
        }
                    
    }

    public function report_listing(){
        $json = array(
            'success' => false,
            'data' => array(
                'POST'=>$_POST,
            ),
            'debug'     => false
        );

        Esb_Class_Ajax_Handler::verify_nonce('townhub-add-ons');

        $datas = $_POST;
        // $datas['user_id'] = get_current_user_id();

        $return = self::do_add_report_post($datas);

        wp_send_json( array_merge($json,$return) );


        // $listing_post = get_post($_POST['listing_id']);

        // if(empty($listing_post)){
        //     $json['data']['error'] = esc_html__( 'Invalid listing ID', 'townhub-add-ons' ) ;
        //     wp_send_json($json );
        // }

        // $lreport_datas = array();
        // $current_user = wp_get_current_user();

        // $lreport_datas['post_title'] = sprintf( _x( '%s has reported %s listing', 'listing report title', 'townhub-add-ons' ), $current_user->display_name, $listing_post->post_title );

        // $lreport_datas['post_content'] = '';
        // $lreport_datas['post_status'] = 'publish';
        // $lreport_datas['post_type'] = 'lreport';

        // do_action( 'townhub_addons_insert_lreport_before', $lreport_datas );

        // $report_id = wp_insert_post($lreport_datas ,true );
        // if (!is_wp_error($report_id)) {
        //     if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Report inserted: " . $report_id . PHP_EOL, 3, ESB_LOG_FILE);
        //     // update report meta datas
        //     $report_metas['listing_id'] = $listing_post->ID;
        //     $report_metas['report_status'] = 'pending';
        //     $report_metas['user_id'] = $current_user->ID;
        //     $report_metas['report_msg'] = isset($_POST['report_message']) ? $_POST['report_message'] : '';
        //     foreach ($report_metas as $key => $value) {
        //         update_post_meta( $report_id, ESB_META_PREFIX.$key,  $value  );
                
        //     }
        //     $json['success'] = true;
        //     $json['data']['message'] = __( 'Your report has been submitted.', 'townhub-add-ons' );
        // }

        // wp_send_json($json );
    }

    public static function do_add_report_post($DATAS = array() ){
        $return = array(
            'success' => false,
            'data' => array(
                // 'POST'=>$DATAS,
            ),
        );
        $user_id = isset($DATAS['user_id']) ? $DATAS['user_id'] : get_current_user_id();
        
        $userObject = get_userdata($user_id);
        if( !$userObject ){
            if( townhub_addons_get_option('report_must_login') == 'yes' ){
                $return['data']['message'] = __( 'Invalid user id', 'townhub-add-ons' );
                return $return;
            }else{
                $ruser_id       = 0;
                $ruser_name     = isset($DATAS['uname']) ? $DATAS['uname'] : '';
                $ruser_email    = isset($DATAS['uemail']) ? $DATAS['uemail'] : '';
            }   
        }else{
            $ruser_id       = $userObject->ID;
            $ruser_name     = $userObject->display_name;
            $ruser_email    = $userObject->user_email;
        }

        $listing_post = get_post($DATAS['listing_id']);
        if(empty($listing_post)){
            $return['data']['message'] = esc_html__( 'Invalid listing ID', 'townhub-add-ons' ) ;
            return $return;
        }
        
        $lreport_datas = array();

        $lreport_datas['post_title'] = sprintf( _x( '%s has reported %s listing', 'listing report title', 'townhub-add-ons' ), $ruser_name, $listing_post->post_title );

        $lreport_datas['post_content'] = '';
        $lreport_datas['post_status'] = 'publish';
        $lreport_datas['post_type'] = 'lreport';

        do_action( 'townhub_addons_insert_lreport_before', $lreport_datas );

        $report_id = wp_insert_post($lreport_datas ,true );
        if (!is_wp_error($report_id)) {
            if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Report inserted: " . $report_id . PHP_EOL, 3, ESB_LOG_FILE);
            // update report meta datas
            $report_metas['listing_id'] = $listing_post->ID;
            $report_metas['report_status'] = 'pending';
            $report_metas['user_id'] = $ruser_id;
            $report_metas['user_name'] = $ruser_name;
            $report_metas['user_email'] = $ruser_email;
            $report_metas['report_msg'] = isset($DATAS['report_message']) ? $DATAS['report_message'] : '';
            foreach ($report_metas as $key => $value) {
                update_post_meta( $report_id, ESB_META_PREFIX.$key,  $value  );
                
            }

            do_action( 'cth_insert_report_listing_after', $report_id, $DATAS );

            $return['success'] = true;
            $return['data']['message'] = __( 'Your report has been submitted.', 'townhub-add-ons' );

            return $return;
        }

        $return['data']['message'] = $report_id->get_error_message();
        return $return;
    }
}

new Esb_Class_LReport_CPT();
