<?php
/* add_ons_php */

class Esb_Class_Withdrawal_CPT extends Esb_Class_CPT {
    protected $name = 'lwithdrawal';

    protected function init(){
        parent::init();
        add_action( 'townhub_addons_lwithdrawal_change_status_to_completed', array($this, 'do_completed') );
        add_action( 'townhub_addons_lwithdrawal_change_status_pending_to_completed', array($this, 'do_pending_to_completed') );
        do_action( $this->name.'_cpt_init_after' );
    }

    protected function set_meta_boxes(){
        $this->meta_boxes = array(
            'details'       => array(
                'title'         => __( 'Details', 'townhub-add-ons' ),
                'context'       => 'normal', // normal - side - advanced
                'priority'       => 'high', // default - high - low
                'callback_args'       => array(),
            ),
            'status'       => array(
                'title'         => __( 'Status', 'townhub-add-ons' ),
                'context'       => 'side', // normal - side - advanced
                'priority'       => 'high', // default - high - low
                'callback_args'       => array(),
            )
        );
    }

    public function register(){

        $labels = array( 
            'name' => __( 'Withdrawals', 'townhub-add-ons' ),
            'singular_name' => __( 'Withdrawals', 'townhub-add-ons' ), 
            'add_new' => __( 'Add New Withdrawals', 'townhub-add-ons' ),
            'add_new_item' => __( 'Add New Withdrawals', 'townhub-add-ons' ),
            'edit_item' => __( 'Edit Withdrawals', 'townhub-add-ons' ),
            'new_item' => __( 'New Withdrawals', 'townhub-add-ons' ),
            'view_item' => __( 'View Withdrawals', 'townhub-add-ons' ),
            'search_items' => __( 'Search Withdrawals', 'townhub-add-ons' ),
            'not_found' => __( 'No Withdrawals found', 'townhub-add-ons' ),
            'not_found_in_trash' => __( 'No Withdrawals found in Trash', 'townhub-add-ons' ),
            'parent_item_colon' => __( 'Parent Withdrawals:', 'townhub-add-ons' ), 
            'menu_name' => __( 'Author Withdrawals', 'townhub-add-ons' ),
        );

        $args = array( 
            'labels' => $labels,
            'hierarchical' => false,
            'description' => __( 'List Withdrawals', 'townhub-add-ons' ),
            'supports' => array(''),
            'taxonomies' => array(),
            'public' => townhub_addons_get_option('pt_public_withdrawal') == 'yes' ? true : false,
            'show_ui' => true,
            'show_in_menu' => true,//default from show_ui
            'menu_position' => 25,
            'menu_icon' => 'dashicons-tickets-alt',
            'show_in_nav_menus' => false,
            // 'publicly_queryable' => false,
            'exclude_from_search' => true,
            'has_archive' => false,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => array( 'slug' => 'withdrawals' ),
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
        unset($columns['title']);
        unset($columns['author']);
        unset($columns['comments']);
        $columns['wdraw_id']             = __('Withdrawal','townhub-add-ons');
        $columns['_status']             = __('Status','townhub-add-ons');
        $columns['_amount']   = __('Amount','townhub-add-ons');
        $columns['_gateway']   = __('Payment','townhub-add-ons');
        return $columns;
    }
    public function meta_columns_content($column_name, $post_ID){
        if ($column_name == 'wdraw_id') {
            echo '<div class="tips">';
            echo '<a href="'.admin_url('post.php?post='.$post_ID.'&action=edit' ).'"><strong>#'.$post_ID.'</strong></a>';
            echo __(' by ','townhub-add-ons'). '<strong>'.get_post_meta( $post_ID, ESB_META_PREFIX.'first_name', true ). ' '.get_post_meta( $post_ID, ESB_META_PREFIX.'last_name', true ).'</strong>';
            echo '<br><small class="meta email"><a href="mailto:'.get_post_meta( $post_ID, ESB_META_PREFIX.'withdrawal_email', true ).'">'.get_post_meta( $post_ID, ESB_META_PREFIX.'withdrawal_email', true ).'</a></small>';
            echo '</div>';
        }
        if ($column_name == '_status') {
            echo '<strong>'.townhub_addons_get_booking_status_text(get_post_meta( $post_ID, ESB_META_PREFIX.'status', true )).'</strong>';
            
        }
        if ($column_name == '_gateway') {
            echo '<strong>'.townhub_addons_payment_names(get_post_meta( $post_ID, ESB_META_PREFIX.'payment_method', true )).'</strong>';
            
        }
        if ($column_name == '_amount') {
            echo '<strong>'.townhub_addons_get_price_formated( get_post_meta( $post_ID, ESB_META_PREFIX.'amount', true ) ).'</strong>';
            
        }
    }

    public function lwithdrawal_details_callback($post, $args){
        wp_nonce_field( 'cth-cpt-fields', '_cth_cpt_nonce' );
        $wthEmail = get_post_meta( $post->ID, ESB_META_PREFIX.'withdrawal_email', true );
        if( empty($wthEmail) ){
            $userObj = get_user_by( 'ID', get_post_meta( $post->ID, ESB_META_PREFIX.'user_id', true ) );
            // var_dump($userObj);
            $wthEmail = $userObj->user_email;
        }
        $bank_iban = get_post_meta( $post->ID, ESB_META_PREFIX.'bank_iban', true );
        $bank_account = get_post_meta( $post->ID, ESB_META_PREFIX.'bank_account', true );
        $bank_name = get_post_meta( $post->ID, ESB_META_PREFIX.'bank_name', true );
        $bank_bname = get_post_meta( $post->ID, ESB_META_PREFIX.'bank_bname', true );
        ?>
        <!-- <h2><?php echo sprintf(__( 'Withdrawals #%d details', 'townhub-add-ons' ), $post->ID); ?></h2> -->
        <table class="form-table withdrawals-details">
            <thead>
                <tr>
                    <th class="lod-plan"><?php _e( 'Email', 'townhub-add-ons' );?></th>
                    <th class="lod-price"><?php _e( 'Amount', 'townhub-add-ons' );?></th>
                    <th class="lod-quantity"><?php _e( 'Payment', 'townhub-add-ons' );?></th>
                    <th class="lod-notes"><?php _e( 'Notes', 'townhub-add-ons' );?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="lod-plan"><?php echo $wthEmail; ?></td>
                    <td class="lod-price"><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'amount', true ); ?></td>
                    <td class="lod-quantity"><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'payment_method', true ); ?></td>
                    <td class="lod-notes"><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'notes', true );?></td>
                </tr>
            </tbody>
        </table>
        <?php if( !empty($bank_account) ): ?>
        <table class="form-table banktransfer-details">
            <tbody>
                <tr class="hoz">
                    <th colspan="2"><?php _ex( 'Bank Transfer Details', 'Withdrawal', 'townhub-add-ons' ); ?></th>
                    
                </tr>
                <tr class="hoz">
                    <th class="w20"><?php _ex( 'IBAN', 'Withdrawal', 'townhub-add-ons' ); ?></th>
                    <td><?php echo $bank_iban; ?></td>
                </tr>
                <tr class="hoz">
                    <th class="w20"><?php _ex( 'ACCOUNT', 'Withdrawal', 'townhub-add-ons' ); ?></th>
                    <td><?php echo $bank_account; ?></td>
                </tr>
                <tr class="hoz">
                    <th class="w20"><?php _ex( 'NAME', 'Withdrawal', 'townhub-add-ons' ); ?></th>
                    <td><?php echo $bank_name; ?></td>
                </tr>
                <tr class="hoz">
                    <th class="w20"><?php _ex( 'Bank Name', 'Withdrawal', 'townhub-add-ons' ); ?></th>
                    <td><?php echo $bank_bname; ?></td>
                </tr>
            </tbody>
        </table>
        <?php endif; ?>
        <?php   
    }

    public function lwithdrawal_status_callback($post, $args){
        /*
         * Use get_post_meta() to retrieve an existing value
         * from the database and use the value for the form.
         */
        $value = get_post_meta( $post->ID, ESB_META_PREFIX.'status', true );

        $status = townhub_addons_get_booking_statuses_array();
        ?>
        <table class="form-table lwithdrawal-details">
            <tbody>
                <tr class="hoz">
                    <td>
                        <select name="lo_status" class="w100">
                        <?php 
                        foreach ($status as $sts => $lbl) {
                            echo '<option value="'.$sts.'" '.selected( $value, $sts, false ).'>'.$lbl.'</option>';
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                
            </tbody>
        </table>
        <?php
    }

    public function save_post($post_id, $post, $update){
        if(!$this->can_save($post_id)) return;

        if(isset($_POST['lo_status'])){
            $new_status = sanitize_text_field( $_POST['lo_status'] ) ;
            $origin_status = get_post_meta( $post_id, ESB_META_PREFIX.'status', true );
            if($new_status !== $origin_status){
                // update_post_meta( $post_id, ESB_META_PREFIX.'status', $new_status ); // move to action hook for checking

                // unhook this function so it doesn't loop infinitely
                remove_action( 'save_post_'.$this->name, array($this, 'save_post'), 10, 3  );
                
                    do_action('townhub_addons_lwithdrawal_change_status_'.$origin_status.'_to_'.$new_status, $post_id );
                    do_action('townhub_addons_lwithdrawal_change_status_to_'.$new_status, $post_id );

                // re-hook this function
                add_action( 'save_post_'.$this->name, array($this, 'save_post'), 10, 3  );

                
            }
        }
    }

    public function do_completed($post_id = 0){

    }
    public function do_pending_to_completed($post_id = 0){
        if(is_numeric($post_id)&&(int)$post_id > 0){
            Esb_Class_Earning::update($post_id);
            update_post_meta( $post_id, ESB_META_PREFIX.'status', 'completed' );

            $user_id = get_post_meta( $post_id, ESB_META_PREFIX.'user_id', true );
            Esb_Class_Dashboard::add_notification($user_id, array(
                'type'          => 'withdrawal_completed',
                'entity_id'     => $post_id
            ));

            do_action( 'cth_edit_withdrawal_approved', $post_id, $user_id );
        }
    }
}

new Esb_Class_Withdrawal_CPT();

