<?php
/* add_ons_php */

class Esb_Class_Claim_CPT extends Esb_Class_CPT {
    protected $name = 'cthclaim';

    protected function init(){
        parent::init();

        $logged_in_ajax_actions = array(
            // 'claim_listing',
            'townhub_addons_claim_listing',
        );
        foreach ($logged_in_ajax_actions as $action) {
            $funname = str_replace('townhub_addons_', '', $action);
            add_action('wp_ajax_'.$action, array( $this, $funname ));
        }

        add_action( 'townhub_addons_lclaim_change_status_to_approved', array($this, 'claim_approved_callback') );

        do_action( $this->name.'_cpt_init_after' );
    }

    public function register(){

        $labels = array( 
            'name' => __( 'Claims', 'townhub-add-ons' ),
            'singular_name' => __( 'Claim', 'townhub-add-ons' ),
            'add_new' => __( 'Add New Claim', 'townhub-add-ons' ),
            'add_new_item' => __( 'Add New Claim', 'townhub-add-ons' ),
            'edit_item' => __( 'Edit Claim', 'townhub-add-ons' ),
            'new_item' => __( 'New Claim', 'townhub-add-ons' ),
            'view_item' => __( 'View Claim', 'townhub-add-ons' ),
            'search_items' => __( 'Search Claims', 'townhub-add-ons' ),
            'not_found' => __( 'No Claims found', 'townhub-add-ons' ),
            'not_found_in_trash' => __( 'No Claims found in Trash', 'townhub-add-ons' ),
            'parent_item_colon' => __( 'Parent Claim:', 'townhub-add-ons' ),
            'menu_name' => __( 'Listing Claims', 'townhub-add-ons' ),
        );

        $args = array( 
            'labels' => $labels,
            'hierarchical' => false,
            'description' => __( 'Listing author claims', 'townhub-add-ons' ),
            'supports' => array( 'title'),
            'taxonomies' => array(),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,//default from show_ui
            'menu_position' => 25,
            'menu_icon' => 'dashicons-money',
            'show_in_nav_menus' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'has_archive' => false,
            'query_var' => false,
            'can_export' => false,
            'rewrite' => array( 'slug' => __('claim','townhub-add-ons') ),
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
            echo '<strong>'.townhub_add_ons_get_claim_status( get_post_meta( $post_ID, ESB_META_PREFIX.'claim_status', true ) ).'</strong>';
        }

        if ($column_name == '_id') {
            echo '<strong>'.$post_ID.'</strong>';
        }
        if ($column_name == '_listing') {
            $listing = get_post( get_post_meta( $post_ID, ESB_META_PREFIX.'listing_id', true ) );
            if (null != $listing) echo '<a href="'.get_permalink( $listing->ID ).'" target="_blank">'.$listing->post_title.'</a>';
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

    public function cthclaim_details_callback($post, $args){
        wp_nonce_field( 'cth-cpt-fields', '_cth_cpt_nonce' );

        $listing_id             = get_post_meta( $post->ID, ESB_META_PREFIX.'listing_id', true );
        $user_id                = get_post_meta( $post->ID, ESB_META_PREFIX.'user_id', true );
        $listing_post           = get_post($listing_id);

        $user_info = get_userdata($user_id);

        ?>
        <table class="form-table lclaim-details">
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
                    <th class="w20"><?php _e( 'Claim Author', 'townhub-add-ons' ); ?></th>
                    <td>
                    <?php 
                    if(!$user_info){
                        _e( 'No author', 'townhub-add-ons' );
                    }else{
                        echo sprintf(__( '<a href="%s" target="_blank">%s</a>', 'townhub-add-ons' ), esc_url(get_author_posts_url($user_info->ID)), $user_info->display_name );

                    }
                    ?>
                    </td>
                </tr>
                <?php 
                $statuses = townhub_add_ons_get_claim_status('all');
                $selected = get_post_meta( $post->ID, ESB_META_PREFIX.'claim_status', true ); ?>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Status/Actions', 'townhub-add-ons' ); ?></th>
                    <td>
                        <?php 
                        if(count($statuses)){
                            echo '<select id="claim_status" name="claim_status">';
                            foreach ($statuses as $status => $label) {
                                echo '<option value="'.$status.'" '.selected( $selected, $status, false ).'>'.$label.'</option>';
                            }
                            echo '</select>';
                        }
                        ?>

                    </td>
                </tr>
                
                <tr class="hoz claim-price-tr<?php if($selected == 'asked_charge') echo ' claim-fee-asked'; ?>" id="claim_price_tr">
                    <th class="w20"><?php _e( 'Claim Price', 'townhub-add-ons' ); ?></th>
                    <td>
                        <?php echo townhub_addons_get_option('currency_symbol','$'); ?><input type="text" name="_price" value="<?php echo (float) get_post_meta( $post->ID, '_price', true );?>">
                        <p><?php _e( 'Enter listing claim price then save the change. Claimed user will receive an email contains details for paying this.', 'townhub-add-ons' ); ?></p>
                    </td>
                </tr>

                <tr class="hoz">
                    <th class="w20"><?php _e( 'Author Message', 'townhub-add-ons' ); ?></th>
                    <td>
                        <textarea name="claim_msg" id="claim_msg" cols="30" rows="5" class="w100"><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'claim_msg', true );?></textarea>
                    </td>
                </tr>

            </tbody>
        </table>
        <?php   
    }

    public function save_post($post_id, $post, $update){
        if(!$this->can_save($post_id)) return;

        if(isset($_POST['claim_msg'])){
            $new_val = sanitize_textarea_field( $_POST['claim_msg'] ) ;
            $origin_val = get_post_meta( $post_id, ESB_META_PREFIX.'claim_msg', true );
            if($new_val !== $origin_val){
                update_post_meta( $post_id, ESB_META_PREFIX.'claim_msg', $new_val );
            }
            
        }

        if(isset($_POST['_price'])){
            $new_val = (float)$_POST['_price'];
            $origin_val = (float) get_post_meta( $post_id, '_price', true );
            if($new_val !== $origin_val){
                update_post_meta( $post_id, '_price', $new_val );
            }
            
        }

        if(isset($_POST['claim_status'])){
            $new_status = sanitize_text_field( $_POST['claim_status'] ) ;
            $origin_status = get_post_meta( $post_id, ESB_META_PREFIX.'claim_status', true );
            if($new_status !== $origin_status){
                update_post_meta( $post_id, ESB_META_PREFIX.'claim_status', $new_status );

                // unhook this function so it doesn't loop infinitely
                remove_action( 'save_post_'.$this->name, array($this, 'save_post'), 10, 3  );
                
                    do_action('townhub_addons_lclaim_change_status_'.$origin_status.'_to_'.$new_status, $post_id );
                    do_action('townhub_addons_lclaim_change_status_to_'.$new_status, $post_id );  

                // re-hook this function
                add_action( 'save_post_'.$this->name, array($this, 'save_post'), 10, 3  );
            }
        }
    }

    // admin approve claim
    public function claim_approved_callback($claim_id = 0){
        if(is_numeric($claim_id)&&(int)$claim_id > 0){
            $claim_post = get_post($claim_id);
            if (null != $claim_post){
                $listing_id                     = get_post_meta( $claim_post->ID, ESB_META_PREFIX.'listing_id', true );
                $user_id                        = get_post_meta( $claim_post->ID, ESB_META_PREFIX.'user_id', true );
                
                // update user role to listing author - need to check for option
                // update role for subscriber and listing customer only 
                // only update role if lower role
                if(in_array( townhub_addons_get_user_role($user_id) , array( 'author', 'contributor', 'subscriber', 'l_customer' ))){
                    $user_id_new = wp_update_user( array( 'ID' => $user_id, 'role' => townhub_addons_get_option('author_role') ) );
                    if ( is_wp_error( $user_id_new ) ) {
                        if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Can not update user role to listing_author" . PHP_EOL, 3, ESB_LOG_FILE);
                    }else{
                        Esb_Class_Dashboard::add_notification($user_id, array(
                            'type' => 'role_change',
                        ));
                    }
                }
                // update listing author to claimed author
                $lis_args = array(
                    'ID'                => $listing_id,
                    'post_author'       => $user_id,
                );
                $lis_id = wp_update_post( $lis_args, true );    
                if (is_wp_error($lis_id)) {
                    if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Update listing (ID: $lis_id) to claimed author (ID: $user_id) error: " . $lis_id->get_error_message() . PHP_EOL, 3, ESB_LOG_FILE);
                }else{
                    update_post_meta( $listing_id, ESB_META_PREFIX.'verified',  '1'  );
                }

                // update image author
                $thumbID = get_post_thumbnail_id($listing_id);
                if( !empty($thumbID) ){
                    wp_update_post( array(
                        'ID' => $thumbID,
                        'post_author' => $user_id,
                    ));
                }
                $images = get_post_meta( $listing_id, ESB_META_PREFIX.'images', true );
                if( !empty($images) && !is_array($images) ) { 
                    $images = explode(",", $images);
                }
                if( !empty($images) ){
                    foreach ($images as $imgID) {
                        wp_update_post( array(
                            'ID' => $imgID,
                            'post_author' => $user_id,
                        ));
                    }
                }
                // header image
                $headermedia = get_post_meta( $listing_id , ESB_META_PREFIX.'headermedia', true );
                $photos = array();
                if( isset($headermedia['photos']) && !is_array($headermedia['photos']) ) $photos = explode(',', $headermedia['photos']);
                if( !empty($photos) ){
                    foreach ($photos as $imgID) {
                        wp_update_post( array(
                            'ID' => $imgID,
                            'post_author' => $user_id,
                        ));
                    }
                }
                // promote image
                $promo_video = get_post_meta( $listing_id, ESB_META_PREFIX.'promo_video', true );
                if ( !empty($promo_video) && !empty($promo_video['images']) ) {
                    wp_update_post( array(
                        'ID' => $promo_video['images'],
                        'post_author' => $user_id,
                    ));
                }
                // menus
                $resmenus = get_post_meta( $listing_id, ESB_META_PREFIX.'resmenus', true );
                foreach ((array)$resmenus as $child) {
                    $photos = isset($child['photos']) ? $child['photos'] : '';
                    if( !empty($photos) && !is_array($photos) ){
                        $photos = explode(',', $photos);
                    }
                    if( !empty($photos) ){
                        foreach ($photos as $imgID) {
                            wp_update_post( array(
                                'ID' => $imgID,
                                'post_author' => $user_id,
                            ));
                        }
                    }
                }


                $menu_pdf = get_post_meta( $listing_id, ESB_META_PREFIX.'menu_pdf', true );
                if( !empty($menu_pdf) ){
                    wp_update_post( array(
                        'ID' => $menu_pdf,
                        'post_author' => $user_id,
                    ));
                }
                // logo
                $llogo = get_post_meta( $listing_id, ESB_META_PREFIX.'llogo', true );
                if( !empty($llogo) ){
                    wp_update_post( array(
                        'ID' => $llogo,
                        'post_author' => $user_id,
                    ));
                }
                

                // update rooms
                $rooms = get_post_meta( $listing_id, ESB_META_PREFIX.'rooms_ids', true );
                if( !empty($rooms) ){
                    foreach ($rooms as $rid) {
                        wp_update_post( array(
                            'ID' => $rid,
                            'post_author' => $user_id,
                        ));
                        // room thumbnail
                        $thumbID = get_post_thumbnail_id($rid);
                        if( !empty($thumbID) ){
                            wp_update_post( array(
                                'ID' => $thumbID,
                                'post_author' => $user_id,
                            ));
                        }
                        // room photos
                        $images = get_post_meta( $rid, ESB_META_PREFIX.'images', true );
                        if( !empty($images) && !is_array($images) ) { 
                            $images = explode(",", $images);
                        }
                        if( !empty($images) ){
                            foreach ($images as $imgID) {
                                wp_update_post( array(
                                    'ID' => $imgID,
                                    'post_author' => $user_id,
                                ));
                            }
                        }
                        // product images
                        $images = get_post_meta( $rid, '_product_image_gallery', true );
                        if( !empty($images) && !is_array($images) ) { 
                            $images = explode(",", $images);
                        }
                        if( !empty($images) ){
                            foreach ($images as $imgID) {
                                wp_update_post( array(
                                    'ID' => $imgID,
                                    'post_author' => $user_id,
                                ));
                            }
                        }
                        
                    }
                }

                do_action( 'townhub_addons_cthclaim_approved', $claim_id, $listing_id, $user_id );
            }
        }
                    
    }

    public function claim_listing(){
        $json = array(
            'success' => false,
            'data' => array(
                // 'POST'=>$_POST,
            ),
            'debug'     => false
        );

        Esb_Class_Ajax_Handler::verify_nonce('townhub-add-ons');

        $datas = $_POST;
        $datas['user_id'] = get_current_user_id();

        $return = self::do_add_claim_post($datas);

        wp_send_json( array_merge($json,$return) );

    }

    public static function do_add_claim_post($DATAS = array() ){
        $return = array(
            'success' => false,
            'data' => array(
                // 'POST'=>$DATAS,
            ),
        );
        $user_id = isset($DATAS['user_id']) ? $DATAS['user_id'] : get_current_user_id();
        
        $userObject = get_userdata($user_id);
        if( !$userObject ){
            $return['data']['message'] = __( 'Invalid user id', 'townhub-add-ons' );
            return $return;
        }

        $listing_post = get_post($DATAS['listing_id']);
        if(empty($listing_post)){
            $return['data']['message'] = esc_html__( 'Invalid listing ID', 'townhub-add-ons' ) ;
            return $return;
        }
        

        $lclaim_datas = array();
        $lclaim_datas['post_title'] = sprintf( _x( '%s claimed for %s listing', 'listing claim title', 'townhub-add-ons' ), $userObject->display_name, $listing_post->post_title );

        $lclaim_datas['post_content'] = '';
        $lclaim_datas['post_status'] = 'publish';
        $lclaim_datas['post_type'] = 'cthclaim';

        do_action( 'townhub_addons_insert_cthclaim_before', $lclaim_datas );

        $claim_id = wp_insert_post($lclaim_datas ,true );
        if (!is_wp_error($claim_id)) {
            if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Claim inserted: " . $claim_id . PHP_EOL, 3, ESB_LOG_FILE);
            // update claim meta datas
            $claim_metas['listing_id'] = $listing_post->ID;
            $claim_metas['claim_status'] = 'pending';
            $claim_metas['user_id'] = $userObject->ID;
            $claim_metas['claim_msg'] = isset($DATAS['claim_message']) ? $DATAS['claim_message'] : '';
            foreach ($claim_metas as $key => $value) {
                update_post_meta( $claim_id, ESB_META_PREFIX.$key,  $value  );
                
            }
            do_action( 'cth_insert_claim_listing_after', $claim_id, $DATAS );
            
            $return['success'] = true;
            $return['data']['message'] = __( 'Your claim has been submitted.', 'townhub-add-ons' );

            return $return;
        }

        $return['data']['message'] = $claim_id->get_error_message();
        return $return;
    }
}

new Esb_Class_Claim_CPT();