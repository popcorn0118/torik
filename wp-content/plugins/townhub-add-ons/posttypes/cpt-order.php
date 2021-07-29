<?php
/* add_ons_php */

class Esb_Class_Order_CPT extends Esb_Class_CPT {
    protected $name = 'lorder';

    protected function init(){
        parent::init();

        add_filter('manage_edit-lorder_sortable_columns', array($this, 'sortable_columns'));
        add_action('pre_get_posts', array($this, 'sort_order'));
        do_action( $this->name.'_cpt_init_after' );
    }

    public function sortable_columns($columns)
    {

        $columns['_status']      = '_status';
        return $columns;
    }
    public function sort_order($query)
    {
        if (!is_admin()) {
            return;
        }

        $orderby = $query->get('orderby');

        if ('_status' == $orderby) {
            $query->set('meta_key', ESB_META_PREFIX.'status');
            $query->set('orderby', 'meta_value');
        }
    }
    public function register(){

        $labels = array( 
            'name' => __( 'Subscription', 'townhub-add-ons' ),
            'singular_name' => __( 'Subscription', 'townhub-add-ons' ), 
            'add_new' => __( 'Add New Subscription', 'townhub-add-ons' ),
            'add_new_item' => __( 'Add New Subscription', 'townhub-add-ons' ),
            'edit_item' => __( 'Edit Subscription', 'townhub-add-ons' ),
            'new_item' => __( 'New Subscription', 'townhub-add-ons' ),
            'view_item' => __( 'View Subscription', 'townhub-add-ons' ),
            'search_items' => __( 'Search Subscriptions', 'townhub-add-ons' ),
            'not_found' => __( 'No Subscriptions found', 'townhub-add-ons' ),
            'not_found_in_trash' => __( 'No Subscriptions found in Trash', 'townhub-add-ons' ),
            'parent_item_colon' => __( 'Parent Subscription:', 'townhub-add-ons' ),
            'menu_name' => __( 'Author Subscriptions', 'townhub-add-ons' ),
        );

        $args = array( 
            'labels' => $labels,
            'hierarchical' => false,
            'description' => __( 'List Subscriptions', 'townhub-add-ons' ),
            'supports' => array( '' ),
            'taxonomies' => array(),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,//default from show_ui
            'menu_position' => 25,
            'menu_icon' => 'dashicons-chart-pie',
            'show_in_nav_menus' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'has_archive' => false,
            'query_var' => false,
            'can_export' => false,
            'rewrite' => array( 'slug' => __('order','townhub-add-ons') ),
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
        $columns['sub_id']             = __('Subscription','townhub-add-ons');
        $columns['_status']             = __('Status','townhub-add-ons');

        $columns['_plan']   = __('Plan','townhub-add-ons');
        $columns['_from_date']   = __('Active Date','townhub-add-ons');
        $columns['_end_date']   = __('Expire Date','townhub-add-ons');

        $columns['_payment_count']   = __('Payment Count','townhub-add-ons');
        return $columns;
    }
    public function meta_columns_content($column_name, $post_ID){
        if ($column_name == 'sub_id') {
            echo '<div class="tips">';
            echo '<a href="'.admin_url('post.php?post='.$post_ID.'&action=edit' ).'"><strong>#'.$post_ID.'</strong></a>';
            echo __(' by ','townhub-add-ons'). '<strong>'.get_post_meta( $post_ID, ESB_META_PREFIX.'first_name', true ). ' '.get_post_meta( $post_ID, ESB_META_PREFIX.'last_name', true ).'</strong>';
            echo '<br><small class="meta email"><a href="mailto:'.get_post_meta( $post_ID, ESB_META_PREFIX.'email', true ).'">'.get_post_meta( $post_ID, ESB_META_PREFIX.'email', true ).'</a></small>';
            echo '</div>';
        }
        if ($column_name == '_status') {
            echo '<strong>'.townhub_addons_get_booking_status_text(get_post_meta( $post_ID, ESB_META_PREFIX.'status', true )).'</strong>';
            
        }


        if ($column_name == '_payment_count') {
            echo '<strong>'.get_post_meta( $post_ID, ESB_META_PREFIX.'payment_count', true ).'</strong>';
            
        }

        if ($column_name == '_plan') {
            $plan_title = __( 'Deleted Plan', 'townhub-add-ons' );
            $plan_id = get_post_meta( $post_ID, ESB_META_PREFIX.'plan_id', true);
            if(get_post_meta( $post_ID, ESB_META_PREFIX.'order_type', true) == 'listing_ad'){
                $ad_package = get_term( $plan_id, 'cthads_package' );
                if ( !empty( $ad_package ) && !is_wp_error( $ad_package ) ) $plan_title = $ad_package->name;
            }else{
                $plan_post = get_post($plan_id);
                if(!empty($plan_post)) $plan_title = $plan_post->post_title;
            }

            echo '<strong>'.$plan_title.'</strong>'; 
        }

        if ($column_name == '_from_date') {
            
            // echo '<strong>'.get_post_meta( $post_ID, ESB_META_PREFIX.'yearly_price', true ).'</strong>';
            echo '<strong>'.get_post_meta( $post_ID, ESB_META_PREFIX.'from_date', true ).'</strong>';
            
        }

        if ($column_name == '_end_date') {
            echo '<strong>'.get_post_meta( $post_ID, ESB_META_PREFIX.'end_date', true ).'</strong>';
            
        }

    }

    protected function set_meta_boxes(){
        $this->meta_boxes = array(
            'details'       => array(
                'title'         => __( 'Plan Details', 'townhub-add-ons' ),
                'context'       => 'normal', // normal - side - advanced
                'priority'       => 'core', // default - high - core - low
                'callback_args'       => array(),
            ),
            'customer'       => array(
                'title'         => __( 'Customer', 'townhub-add-ons' ),
                'context'       => 'normal', // normal - side - advanced
                'priority'       => 'core', // default - high - core - low
                'callback_args'       => array(),
            ),
            'meta'       => array(
                'title'         => __( 'Meta', 'townhub-add-ons' ),
                'context'       => 'normal', // normal - side - advanced
                'priority'       => 'core', // default - high - core - low
                'callback_args'       => array(),
            ),
            'status'       => array(
                'title'         => __( 'Status', 'townhub-add-ons' ),
                'context'       => 'side', // normal - side - advanced
                'priority'       => 'high', // default - high - core - low
                'callback_args'       => array(),
            )
        );
    }

    public function lorder_details_callback($post, $args){
        wp_nonce_field( 'cth-cpt-fields', '_cth_cpt_nonce' );

        $plan_title = __( 'Deleted Plan', 'townhub-add-ons' );
        $plan_id = get_post_meta( $post->ID, ESB_META_PREFIX.'plan_id', true);
        $prices             = townhub_addons_get_plan_prices($plan_id);
        
        $selected = false;
        $plan_title_new = '<select name="lod_plan">';
        foreach (townhub_addons_get_active_plan_ids() as $pl_ID) {
            $plan_title_new .= '<option value="'.$pl_ID.'" '.selected( $plan_id, $pl_ID, false ).'>'.get_the_title( $pl_ID ).'</option>';
            if($plan_id == $pl_ID) $selected = true;
        }
        if(false == $selected) $plan_title_new .= '<option value="'.$plan_id.'" selected="selected">'.$plan_title.'</option>';
        $plan_title_new .= '</select>';

        $plan_title = $plan_title_new;

        ?>
        <h2><?php echo sprintf(__( 'Subscription #%d details', 'townhub-add-ons' ), $post->ID); ?></h2>
        <p class="lorder-desc"><?php echo townhub_addons_payment_names(get_post_meta( $post->ID, ESB_META_PREFIX.'payment_method', true )) . sprintf(__( '. Placed on %s.', 'townhub-add-ons' ), $post->post_date) ; ?></p>
        <table class="form-table lorder-details">
            <thead>
                <tr>
                    <th class="lod-plan"><?php _e( 'Plan', 'townhub-add-ons' );?></th>
                    <th class="lod-price"><?php _e( 'Price', 'townhub-add-ons' );?></th>
                    <th class="lod-quantity"><?php _e( 'Quantity', 'townhub-add-ons' );?></th>
                    <th class="lod-amount"><?php _e( 'Amount', 'townhub-add-ons' );?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="lod-plan"><?php echo $plan_title; ?></td>
                    <td class="lod-price"><?php echo townhub_addons_get_price_formated( get_post_meta( $post->ID, ESB_META_PREFIX.'subtotal', true ) ); ?></td>
                    <td class="lod-quantity"><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'quantity', true ); ?></td>
                    <td class="lod-amount"><?php echo townhub_addons_get_price_formated( get_post_meta( $post->ID, ESB_META_PREFIX.'amount', true ) ); ?></td>
                    
                </tr>
            </tbody>
        </table>
        <?php   
    }
    public function lorder_customer_callback($post, $args){
        ?>
        <h2><?php _e( 'Customer details', 'townhub-add-ons' ); ?></h2>
        <p class="lorder-desc"></p>
        <table class="form-table lorder-details">
            <tbody>

                <tr class="hoz">
                    <th class="w20"><?php _e( 'First Name', 'townhub-add-ons' ); ?></th>
                    <td><a href="<?php echo get_edit_user_link( get_post_meta( $post->ID, ESB_META_PREFIX.'user_id', true ) ); ?>"><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'first_name', true ); ?></a></td>
                </tr>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Email', 'townhub-add-ons' ); ?></th>
                    <td><a href="mailto:<?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'email', true ); ?>"><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'email', true ); ?></a></td>
                </tr>
                <?php 
                    $coupon_code = get_post_meta( $post->ID, ESB_META_PREFIX.'coupon_code', true ); 
                    if( !empty($coupon_code) ){?>
                        <tr class="hoz">
                            <th class="w20"><?php _e( 'Coupon Code', 'townhub-add-ons' ); ?></th>
                            <td><span><?php echo $coupon_code; ?></span></td>
                        </tr> 

                <?php 
                    }
                ?>
            </tbody>
        </table>
        <?php   
    }

    public function lorder_meta_callback($post, $args){
        
        ?>
        <h2><?php _e( 'Subscription Meta', 'townhub-add-ons' ); ?></h2>
        <p class="lorder-desc"></p>
        <table class="form-table lorder-details">
            <tbody>

                <tr class="hoz">
                    <th class="w20"><?php _e( 'Gateway', 'townhub-add-ons' ); ?></th>
                    <td><?php echo townhub_addons_payment_names(get_post_meta( $post->ID, ESB_META_PREFIX.'payment_method', true ));?></td>
                </tr>
                <?php 
                if( 'woo' == get_post_meta( $post->ID, ESB_META_PREFIX.'payment_method', true ) ):
                ?>
                <tr class="hoz">
                    <th class="w20"><?php _e( 'WooCommerce Order', 'townhub-add-ons' ); ?></th>
                    <td><a href="<?php echo get_edit_post_link( get_post_meta( $post->ID, ESB_META_PREFIX.'woo_order', true ) ); ?>"><?php echo sprintf( __( '#%s', 'townhub-add-ons' ), get_post_meta( $post->ID, ESB_META_PREFIX.'woo_order', true ) ); ?></a></td>
                </tr>
                <?php endif; ?>
                
                <tr class="hoz">
                    <th class="w20"><?php _e( 'Purchase Code', 'townhub-add-ons' ); ?></th>
                    <td><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'purchase_code', true );?></td>
                </tr>
                <?php 
                $listings = get_post_meta( $post->ID, ESB_META_PREFIX.'listings', true );
                if(!empty($listings)){
                    $listings_content = '';
                    foreach ((array)$listings as $l_ID) {
                        $listings_content .= '<div class="listing-item"><a href="'.get_the_permalink( $l_ID ).'" target="_blank">'.get_the_title( $l_ID ).'</a></div>';
                    }
                    ?>
                    <tr class="hoz">
                        <th class="w20"><?php _e( 'Listings', 'townhub-add-ons' ); ?></th>
                        <td><?php echo $listings_content;?></td>
                    </tr>
                    <?php
                }
                ?>
                <?php 
                $transactions = get_post_meta( $post->ID, ESB_META_PREFIX.'transactions', true );
                $transactions_content = '';
                if(!empty($transactions)){
                    foreach ((array)$transactions as $iv_ID) {
                        $transactions_content .= sprintf(__( 'Invoice ID: %1$s', 'townhub-add-ons' ), $iv_ID );
                    }
                    ?>
                    <tr class="hoz">
                        <th class="w20"><?php _e( 'Transactions/Invoices IDs', 'townhub-add-ons' ); ?></th>
                        <td><?php echo $transactions_content; ?></td>
                    </tr>
                    <?php
                }
                ?>

                <tr class="hoz">
                    <th class="w20"><?php _e( 'Note', 'townhub-add-ons' ); ?></th>
                    <td>
                        <textarea name="order_notes" id="order_notes" cols="30" rows="5" class="w100"><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'order_notes', true );?></textarea>
                    </td>
                </tr>


            </tbody>
        </table>
        <?php   
    }

    public function lorder_status_callback($post, $args){
        /*
         * Use get_post_meta() to retrieve an existing value
         * from the database and use the value for the form.
         */
        $value = get_post_meta( $post->ID, ESB_META_PREFIX.'status', true );

        $status = townhub_addons_get_booking_statuses_array();
        ?>
        <table class="form-table lorder-details">
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

        if(isset($_POST['order_notes'])){
            $new_val = sanitize_textarea_field( $_POST['order_notes'] ) ;
            $origin_val = get_post_meta( $post_id, ESB_META_PREFIX.'order_notes', true );
            if($new_val !== $origin_val){
                update_post_meta( $post_id, ESB_META_PREFIX.'order_notes', $new_val );
            }
        }
        if(isset($_POST['lod_plan'])){
            $new_val = sanitize_text_field( $_POST['lod_plan'] ) ;
            $origin_val = get_post_meta( $post_id, ESB_META_PREFIX.'plan_id', true );
            if($new_val !== $origin_val){
                update_post_meta( $post_id, ESB_META_PREFIX.'plan_id', $new_val );
            }
        }
        if(isset($_POST['lo_status'])){
            $new_status = sanitize_text_field( $_POST['lo_status'] ) ;
            $origin_status = get_post_meta( $post_id, ESB_META_PREFIX.'status', true );
            if($new_status !== $origin_status){
                update_post_meta( $post_id, ESB_META_PREFIX.'status', $new_status );

                // unhook this function so it doesn't loop infinitely
                remove_action( 'save_post_'.$this->name, array($this, 'save_post'), 10, 3  );
                    do_action('townhub_addons_lorder_change_status_'.$origin_status.'_to_'.$new_status, $post_id );
                    do_action('townhub_addons_lorder_change_status_to_'.$new_status, $post_id );
                // re-hook this function
                add_action( 'save_post_'.$this->name, array($this, 'save_post'), 10, 3  );
                    
            }
        }
    }

}

new Esb_Class_Order_CPT();