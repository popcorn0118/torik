<?php
/* add_ons_php */

class Esb_Class_Coupon_Code_CPT extends Esb_Class_CPT { 
    protected $name = 'cthcoupons';

    protected function init(){
        parent::init();
        // add_action( 'before_delete_post', array( __CLASS__, 'before_delete_post' ), 10, 1 );  
        // add_action( 'init', array($this, 'taxonomies'), 0 );
        do_action( $this->name.'_cpt_init_after' );
    }

    public function register(){

        $labels = array( 
            'name' => __( 'Coupons', 'townhub-add-ons' ),
            'singular_name' => __( 'Coupon', 'townhub-add-ons' ),
            'add_new' => __( 'Add New Coupon', 'townhub-add-ons' ),
            'add_new_item' => __( 'Add New Coupon', 'townhub-add-ons' ),
            'edit_item' => __( 'Edit Coupon', 'townhub-add-ons' ),
            'new_item' => __( 'New Coupon', 'townhub-add-ons' ),
            'view_item' => __( 'View Coupon', 'townhub-add-ons' ),
            'search_items' => __( 'Search Coupons', 'townhub-add-ons' ),
            'not_found' => __( 'No Coupons found', 'townhub-add-ons' ),
            'not_found_in_trash' => __( 'No Coupons found in Trash', 'townhub-add-ons' ),
            'parent_item_colon' => __( 'Parent Coupon:', 'townhub-add-ons' ),
            'menu_name' => __( 'Listing Coupons', 'townhub-add-ons' ),
        );
        $args = array( 
            'labels' => $labels,
            'hierarchical' => false,
            'description' => __( 'Coupons Code', 'townhub-add-ons' ),
            'supports' => array(''),
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
            'rewrite' => array( 'slug' => 'cthcoupons' ),
            'capability_type' => 'post',
            // 'capabilities' => array(
            //     'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout
            // ),
            'map_meta_cap' => true, // Set to `false`, if users are not allowed to edit/delete existing posts
        );

        register_post_type( $this->name, $args );
    }
    public function taxonomies(){
        $labels = array(
            'name' => __( 'Coupons Package', 'townhub-add-ons' ),
            'singular_name' => __( 'Coupons Package', 'townhub-add-ons' ),
            'search_items' =>  __( 'Search Coupons Packages','townhub-add-ons' ),
            'all_items' => __( 'All Coupons Packages','townhub-add-ons' ),
            'parent_item' => __( 'Parent Coupons Package','townhub-add-ons' ),
            'parent_item_colon' => __( 'Parent Coupons Package:','townhub-add-ons' ),
            'edit_item' => __( 'Edit Coupons Package','townhub-add-ons' ), 
            'update_item' => __( 'Update Coupons Package','townhub-add-ons' ),
            'add_new_item' => __( 'Add New Coupons Package','townhub-add-ons' ),
            'new_item_name' => __( 'New Coupons Package Name','townhub-add-ons' ),
            'menu_name' => __( 'Coupons Packages','townhub-add-ons' ),
        );     

        // Now register the taxonomy

        register_taxonomy('cthcoupons_package',array('cthcoupons'), array(
            'hierarchical' => false,
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_nav_menus'=> false,
            'show_admin_column' => true,
            'query_var' => false,
            'rewrite' => array( 'slug' => __('cthcoupons_package','townhub-add-ons') ),
            // https://codex.wordpress.org/Roles_and_Capabilities
            // 'capabilities' => array(
            //     'manage_terms' => 'manage_categories',
            //     'edit_terms' => 'manage_categories',
            //     'delete_terms' => 'manage_categories',
            //     'assign_terms' => 'edit_posts'
            // ),

        ));
    }
    protected function set_meta_columns(){
        $this->has_columns = true;
    }
    public function meta_columns_head($columns){
        unset($columns['title']);
        unset($columns['date']);
        unset($columns['author']);
        unset($columns['comments']);
         $columns['coup_id']             = __('Coupon Code','townhub-add-ons');
        $columns['_quantity']             = __('Quantity','townhub-add-ons');
        // $columns['_ad_pos']   = __('AD Positions','townhub-add-ons');
        $columns['_amount']   = __('Discount amount','townhub-add-ons');
        $columns['_expiry_date']   = __('Expire Date','townhub-add-ons');

        return $columns;
    }
    public function meta_columns_content($column_name, $post_ID){
        if ($column_name == 'coup_id') {
            echo '<strong>'.get_post_meta( $post_ID, ESB_META_PREFIX.'coupon_code', true ).'</strong>';
        }
        if ($column_name == '_quantity') {
            echo '<strong>'.get_post_meta( $post_ID, ESB_META_PREFIX.'coupon_qty', true ).'</strong>';
        }
        if ($column_name == '_amount') {
            echo '<strong>'.get_post_meta( $post_ID, ESB_META_PREFIX.'dis_amount', true ).'</strong>';
        }
        if ($column_name == '_expiry_date') {
            echo '<strong>'.get_post_meta( $post_ID, ESB_META_PREFIX.'coupon_expiry_date', true ).'</strong>';
        }
    }

    protected function set_meta_boxes(){
        $this->meta_boxes = array(
            'details'       => array(
                'title'         => __( 'Coupon data', 'townhub-add-ons' ),
                'context'       => 'normal', // normal - side - advanced
                'priority'       => 'core', // default - high - core - low
                'callback_args'       => array(),
            ),
        );
    }

    public function cthcoupons_details_callback($post, $args){
        wp_nonce_field( 'cth-cpt-fields', '_cth_cpt_nonce' ); 
        ?>
        <table class="form-table cth-table table-coupon_data">
            <tbody>
                <tr class="hoz">
                    <th><label for="coupon_code"><?php  esc_html_e( 'Coupon code', 'townhub-add-ons' );?></label></th>
                    <td> <input type="text" class="short wc_input_price" style="" name="coupon_code" id="coupon_code" value="<?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'coupon_code', true ); ?>" placeholder="code"> </td>
                </tr>
                <tr class="hoz">
                    <th><label for="coupon_decs"><?php  esc_html_e( 'Description', 'townhub-add-ons' );?></label></th>
                    <td><textarea id="coupon_decs" name="coupon_decs" cols="5" rows="2" class="w100" placeholder="Description" ><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'coupon_decs', true ); ?></textarea></td>
                </tr>
                <tr class="hoz">
                    <th><label for="discount_type"><?php  esc_html_e( 'Discount type', 'townhub-add-ons' );?></label></th>
                    <td>
                        <?php 
                            $type_coupon = array(
                                'percent'       => 'Percentage discount',
                                'fixed_cart'    => 'Fixed cart discount',
                            );
                            $selected = get_post_meta( $post->ID, ESB_META_PREFIX.'discount_type', true );
                            echo '<select id="discount_type" name="discount_type">';
                            foreach ($type_coupon as $val => $label) {
                                echo '<option value="'.$val.'" '.selected( $selected, $val, false ).'>'.$label.'</option>';
                            }
                            echo '</select>';

                        ?>
                    </td>
                </tr>
                <tr class="hoz">
                    <th><label for="dis_amount"><?php  esc_html_e( 'Discount amount', 'townhub-add-ons' );?></label></th>
                    <td> <input type="text" class="short wc_input_price" style="" name="dis_amount" id="dis_amount" value="<?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'dis_amount', true ); ?>" placeholder="10"> </td>
                </tr>
                <tr class="hoz">
                    <th><label for="coupon_qty"><?php  esc_html_e( 'Coupon quantity', 'townhub-add-ons' );?></label></th>
                    <td><input type="mumber" class="short wc_input_price" style="" name="coupon_qty" id="coupon_qty" value="<?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'coupon_qty', true ); ?>" placeholder="50"> </td>
                </tr>
                <tr class="hoz">
                    <th><label for="expiry_date"><?php  esc_html_e( 'Coupon expiry date', 'townhub-add-ons' );?></label></th>
                    <td> <input type="text" class="date-picker hasDatepicker" name="coupon_expiry_date" id="expiry_date" value="<?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'coupon_expiry_date', true ); ?>"></td>     
                </tr>
                <tr class="hoz">
                    <th><label for="for_coupon_listing_id"><?php  esc_html_e( 'For Listing', 'townhub-add-ons' );?></label></th>
                    <td>
                        <?php 
                            $listing_post = get_posts( array(
                                'post_type'         => 'listing',
                                'posts_per_page'    => -1,
                                'post_status'       => 'publish',
                                'fields'            => 'ids',
                            ));
                            $selected = get_post_meta( $post->ID, ESB_META_PREFIX.'for_coupon_listing_id', true );
                            echo '<select id="for_coupon_listing_id" name="for_coupon_listing_id">';
                                echo '<option value="">'.__( 'None', 'townhub-add-ons' ).'</option>';
                            foreach ($listing_post as $key => $lid) {
                                
                                echo '<option value="'.$lid.'" '.selected( $selected, $lid, false ).'>'.get_the_title($lid).'</option>';
                                
                            }
                            echo '</select>';

                        ?>
                    </td>
                </tr>
                <tr class="hoz">
                    <th><label for="for_all_listing"><?php  esc_html_e( 'For All Listings?', 'townhub-add-ons' );?></label></th>
                    <td> <input type="checkbox" class="" name="for_all_listing" id="for_all_listing" value="yes" <?php checked( get_post_meta( $post->ID, ESB_META_PREFIX.'for_all_listing', true ), 'yes', true ); ?>></td>     
                </tr>
                <tr class="hoz">
                    <th><label for="for_plan_id"><?php  esc_html_e( 'For Author Plan', 'townhub-add-ons' );?></label></th>
                    <td>
                        <?php 
                            $planPosts = get_posts( array(
                                'post_type'         => 'lplan',
                                'posts_per_page'    => -1,
                                'post_status'       => 'publish',
                                'fields'            => 'ids',
                            ));
                            $selected = get_post_meta( $post->ID, ESB_META_PREFIX.'plan_id', true );
                            echo '<select id="for_plan_id" name="plan_id">';
                                echo '<option value="">'.__( 'None', 'townhub-add-ons' ).'</option>';
                            foreach ($planPosts as $key => $lid) {
                                
                                echo '<option value="'.$lid.'" '.selected( $selected, $lid, false ).'>'.get_the_title($lid).'</option>';
                                
                            }
                            echo '</select>';

                        ?>
                    </td>
                </tr>
             </tbody>
        </table>
       
        <?php   
    }
    public function save_post($post_id, $post, $update){
        if(!$this->can_save($post_id)) return;
        // update metas
        $meta_fields = array(
            'coupon_code'               => 'text',
            'discount_type'             => 'text',
            'dis_amount'                => 'text',
            'coupon_decs'                => 'text',
            'coupon_qty'                => 'text',
            'coupon_expiry_date'        => 'text',
        );
        $coupon_metas = array();
        foreach($meta_fields as $fname => $ftype){
            if($ftype == 'array'){
                $coupon_metas[$fname] = isset($_POST[$fname]) ? $_POST[$fname]  : array();
            }else{
                $coupon_metas[$fname] = isset($_POST[$fname]) ? sanitize_text_field($_POST[$fname]) : '';
            }
        }
        foreach ($coupon_metas as $key => $value) {
            $old_val = get_post_meta( $post_id, ESB_META_PREFIX.$key, true );
            if($old_val != $value) update_post_meta( $post_id, ESB_META_PREFIX.$key, $value );
        }

        // for changing listing
        $listing_id = 0;
        if(isset($_POST['for_coupon_listing_id'])){
            $listing_id = sanitize_text_field($_POST['for_coupon_listing_id']);
        }
        $old_listing_id = get_post_meta( $post_id, ESB_META_PREFIX.'for_coupon_listing_id', true );
        if($old_listing_id != $listing_id){
            update_post_meta( $post_id, ESB_META_PREFIX.'for_coupon_listing_id', $listing_id);
            if( $old_listing_id == '' ){
                update_post_meta( $listing_id, ESB_META_PREFIX.'coupon_ids', array($post_id) );
            }else{
                // remove copoun from old listing
                $old_listing_coupons = array_unique((array)get_post_meta( $old_listing_id, ESB_META_PREFIX.'coupon_ids', true ));
                update_post_meta( $old_listing_id, ESB_META_PREFIX.'coupon_ids', array_diff( $old_listing_coupons, array($post_id) ) );
                if( !empty($listing_id) ){
                    $new_listing_coupons = (array)get_post_meta( $listing_id, ESB_META_PREFIX.'coupon_ids', true );
                    $new_listing_coupons[] = $post_id;

                    update_post_meta( $listing_id, ESB_META_PREFIX.'coupon_ids', array_unique( $new_listing_coupons ) );
                }
                    
            }
        }
        if(isset($_POST['for_all_listing'])){
            update_post_meta( $post_id, ESB_META_PREFIX.'for_all_listing', $_POST['for_all_listing'] );
        }else{
            update_post_meta( $post_id, ESB_META_PREFIX.'for_all_listing', '' );
        }


        
        $plan_id = 0;
        if(isset($_POST['plan_id'])){
            $plan_id = sanitize_text_field($_POST['plan_id']);
        }
        $old_plan_id = get_post_meta( $post_id, ESB_META_PREFIX.'plan_id', true );
        if($old_plan_id != $plan_id){
            update_post_meta( $post_id, ESB_META_PREFIX.'plan_id', $plan_id);
            if( $old_plan_id == '' ){
                update_post_meta( $plan_id, ESB_META_PREFIX.'coupon_ids', array($post_id) );
            }else{
                // remove copoun from old listing
                $old_plan_coupons = array_unique((array)get_post_meta( $old_plan_id, ESB_META_PREFIX.'coupon_ids', true ));
                update_post_meta( $old_plan_id, ESB_META_PREFIX.'coupon_ids', array_diff( $old_plan_coupons, array($post_id) ) );
                if( !empty($plan_id) ){
                    $new_coupons = (array)get_post_meta( $plan_id, ESB_META_PREFIX.'coupon_ids', true );
                    $new_coupons[] = $post_id;

                    update_post_meta( $plan_id, ESB_META_PREFIX.'coupon_ids', array_unique( $new_coupons ) );
                }
            }
        }

        

    }
    // public function before_delete_post($postid = 0){
    //     global $wpdb;
    //     $post_type = get_post_type($postid);
    //     if($post_type === 'cthcoupons'){
    //         $booking_table = $wpdb->prefix . 'cth_for_coupon_listing_id';
    //         $wpdb->query( 
    //             $wpdb->prepare( 
    //                 "
    //                 DELETE FROM $booking_table
    //                 WHERE  coupon_id = %d 
    //                 ",
    //                 $postid
    //             )
    //         );
    //     }
    // }

}

new Esb_Class_Coupon_Code_CPT();