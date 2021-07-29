<?php
/* add_ons_php */

class Esb_Class_Listing_CPT extends Esb_Class_CPT {     
    protected $name = 'listing';
    protected $permalinks = array();
    protected function init(){
        $this->permalinks = get_option( 'cthpermalinks', array() );
        parent::init();

        add_action( 'init', array($this, 'taxonomies'), 0 ); 

        add_filter('manage_edit-listing_cat_columns', array($this, 'tax_cat_columns_head') );
        add_filter('manage_listing_cat_custom_column', array($this, 'tax_cat_columns_content'), 10, 3); 

        add_filter('manage_edit-listing_feature_columns', array($this, 'tax_alt_columns_head') );
        add_filter('manage_listing_feature_custom_column', array($this, 'tax_alt_columns_content'), 10, 3); 

        add_filter('manage_edit-listing_location_columns', array($this, 'tax_alt_columns_head') );
        add_filter('manage_listing_location_custom_column', array($this, 'tax_alt_columns_content'), 10, 3); 

        add_filter('manage_edit-listing_tag_columns', array($this, 'tax_alt_columns_head') );
        add_filter('manage_listing_tag_custom_column', array($this, 'tax_alt_columns_content'), 10, 3); 

        $logged_in_ajax_actions = array(
            'submit_listing',
            'admin_lverified', 
            'admin_lfeatured',
            'cats_features',
        );
        foreach ($logged_in_ajax_actions as $action) {
            $funname = str_replace('townhub_addons_', '', $action);   
            add_action('wp_ajax_'.$action, array( $this, $funname ));
        }
        $not_logged_in_ajax_actions = array(
            'townhub_addons_cats_features',
        );
        foreach ($not_logged_in_ajax_actions as $action) {
            $funname = str_replace('townhub_addons_', '', $action);   
            add_action('wp_ajax_'.$action, array( $this, $funname ));
            add_action('wp_ajax_nopriv_'.$action, array( $this, $funname ));
        }

        add_filter('single_template', array($this, 'single_template')); 

        add_filter('use_block_editor_for_post_type', array($this, 'disable_gutenberg'), 10, 2 );

        add_action( 'before_delete_post', array($this, 'before_delete_post' ), 10, 1 ); 

        do_action( $this->name.'_cpt_init_after' );
    }
    public function single_template($tmpl){
        global $post;

        if ($post->post_type == 'listing') {
            $tmpl = townhub_addons_get_template_part('templates/single', '', null, false);
        }
        return $tmpl;
    }

    public function disable_gutenberg( $current_status, $post_type ){
        if ($post_type === 'listing') 
            return false;

        return $current_status;
    }
    public function tax_cat_columns_head($columns) {
        $columns['_thumbnail'] = __('Thumbnail','townhub-add-ons');
        $columns['_id'] = __('ID','townhub-add-ons');
        return $columns;
    }

    public function tax_cat_columns_content($c, $column_name, $term_id) {
        if ($column_name == '_id') {
            echo $term_id;
        }
        if ($column_name == '_thumbnail') {
            $term_meta = get_term_meta( $term_id, ESB_META_PREFIX.'term_meta', true );
            if(isset($term_meta['featured_img']) && !empty($term_meta['featured_img'])){
                echo wp_get_attachment_image( $term_meta['featured_img']['id'], 'thumbnail', false, array('style'=>'width:100px;height:auto;') );
                
            }
        }
    }
    public function tax_alt_columns_head($columns) {
        $columns['_id'] = __('ID','townhub-add-ons');
        return $columns;
    }

    public function tax_alt_columns_content($c, $column_name, $term_id) {
        if ($column_name == '_id') {
            echo $term_id;
        }
    }
    protected function set_meta_boxes(){
        $this->meta_boxes = array(
            'datas'       => array(
                'title'                 => __( 'Listing Datas', 'townhub-add-ons' ),
                'context'               => 'normal', // normal - side - advanced
                'priority'              => 'high', // default - high - low
                'callback_args'         => array(),
            ),
            'expire'       => array(
                'title'                 => __( 'Listing Expire', 'townhub-add-ons' ),
                'context'               => 'side', // normal - side - advanced
                'priority'              => 'high', // default - high - low
                'callback_args'         => array(),
            )
        );
    }
    public function register(){
        $lslug = !empty($this->permalinks['cthlisting_slug']) ? $this->permalinks['cthlisting_slug'] : 'listing';
        if ( false !== strpos( $lslug, '%' ) ) {
            add_rewrite_tag('%lloc%','(.+)');
            add_rewrite_tag('%lcat%','(.+)');
        }

        $labels = array( 
            'name' => __( 'Listing', 'townhub-add-ons' ),
            'singular_name' => __( 'Listing', 'townhub-add-ons' ),
            'add_new' => __( 'Add New Listing', 'townhub-add-ons' ),
            'add_new_item' => __( 'Add New Listing', 'townhub-add-ons' ),
            'edit_item' => __( 'Edit Listing', 'townhub-add-ons' ),
            'new_item' => __( 'New Listing', 'townhub-add-ons' ),
            'view_item' => __( 'View Listing', 'townhub-add-ons' ),
            'search_items' => __( 'Search Listings', 'townhub-add-ons' ),
            'not_found' => __( 'No Listings found', 'townhub-add-ons' ),
            'not_found_in_trash' => __( 'No Listings found in Trash', 'townhub-add-ons' ),
            'parent_item_colon' => __( 'Parent Listing:', 'townhub-add-ons' ),
            'menu_name' => __( 'TownHub Listings', 'townhub-add-ons' ),
        );

        $args = array( 
            'labels' => $labels,
            'hierarchical' => true,
            'description' => __( 'List Listings', 'townhub-add-ons' ),
            'supports' => array( 'title', 'editor',  'author', 'thumbnail','comments','excerpt', 'page-attributes'/*, 'post-formats'*/),
            'taxonomies' => array('listing_cat', 'listing_location', 'listing_feature',  'listing_tag'),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 25,
            'menu_icon' => 'dashicons-location-alt', // plugin_dir_url( __FILE__ ) .'assets/admin_ico_listing.png', 
            'show_in_nav_menus' => true,
            'has_archive' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            
            'query_var' => true,
            'can_export' => true,
            'rewrite' => array( 'slug' => $lslug ),
            'capability_type' => 'post'
        );
        register_post_type( $this->name, $args );
    }

    public function taxonomies(){
        $cslug = !empty($this->permalinks['cthcategory_slug']) ? $this->permalinks['cthcategory_slug'] : 'listing_cat';
        $labels = array(
            'name' => __( 'Listing Categories', 'townhub-add-ons' ),
            'singular_name' => __( 'Category', 'townhub-add-ons' ),
            'search_items' =>  __( 'Search Categories','townhub-add-ons' ),
            'all_items' => __( 'All Categories','townhub-add-ons' ),
            'parent_item' => __( 'Parent Category','townhub-add-ons' ),
            'parent_item_colon' => __( 'Parent Category:','townhub-add-ons' ),
            'edit_item' => __( 'Edit Category','townhub-add-ons' ), 
            'update_item' => __( 'Update Category','townhub-add-ons' ),
            'add_new_item' => __( 'Add New Category','townhub-add-ons' ),
            'new_item_name' => __( 'New Category Name','townhub-add-ons' ),
            'menu_name' => __( 'Listing Categories','townhub-add-ons' ),
        );     
        // Now register the taxonomy
        register_taxonomy('listing_cat',array('listing'), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_in_nav_menus'=> true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => $cslug ),
            // https://codex.wordpress.org/Roles_and_Capabilities
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'edit_posts'
            ),

        ));

        $fslug = !empty($this->permalinks['cthfeature_slug']) ? $this->permalinks['cthfeature_slug'] : 'listing_feature';
        $labels = array(
            'name' => __( 'Listing Features', 'townhub-add-ons' ),
            'singular_name' => __( 'Feature', 'townhub-add-ons' ),
            'search_items' =>  __( 'Search Features','townhub-add-ons' ),
            'all_items' => __( 'All Features','townhub-add-ons' ),
            'parent_item' => __( 'Parent Feature','townhub-add-ons' ),
            'parent_item_colon' => __( 'Parent Feature:','townhub-add-ons' ),
            'edit_item' => __( 'Edit Feature','townhub-add-ons' ), 
            'update_item' => __( 'Update Feature','townhub-add-ons' ),
            'add_new_item' => __( 'Add New Feature','townhub-add-ons' ),
            'new_item_name' => __( 'New Feature Name','townhub-add-ons' ),
            'menu_name' => __( 'Listing Features','townhub-add-ons' ),
        );     

        // Now register the taxonomy

        register_taxonomy('listing_feature',array('listing', 'lrooms'), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_in_nav_menus'=> true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => $fslug ),
            // https://codex.wordpress.org/Roles_and_Capabilities
            // 'capabilities' => array(
            //     'manage_terms' => 'manage_categories',
            //     'edit_terms' => 'manage_categories',
            //     'delete_terms' => 'manage_categories',
            //     'assign_terms' => 'edit_posts'
            // ),

        ));

        $locslug = !empty($this->permalinks['cthlocation_slug']) ? $this->permalinks['cthlocation_slug'] : 'listing_location';
        $labels = array(
            'name' => __( 'Listing Locations', 'townhub-add-ons' ),
            'singular_name' => __( 'Location', 'townhub-add-ons' ),
            'search_items' =>  __( 'Search Locations','townhub-add-ons' ),
            'all_items' => __( 'All Locations','townhub-add-ons' ),
            'parent_item' => __( 'Parent Location','townhub-add-ons' ),
            'parent_item_colon' => __( 'Parent Location:','townhub-add-ons' ),
            'edit_item' => __( 'Edit Location','townhub-add-ons' ), 
            'update_item' => __( 'Update Location','townhub-add-ons' ),
            'add_new_item' => __( 'Add New Location','townhub-add-ons' ),
            'new_item_name' => __( 'New Location Name','townhub-add-ons' ),
            'menu_name' => __( 'Listing Locations','townhub-add-ons' ),
        );     

        // Now register the taxonomy

        register_taxonomy('listing_location',array('listing'), array(
            'hierarchical' => true, // false for insert new tax when inserting post
            'labels' => $labels,
            'show_ui' => true,
            'show_in_nav_menus'=> true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => $locslug ),
            // https://codex.wordpress.org/Roles_and_Capabilities
            // 'capabilities' => array(
            //     'manage_terms' => 'manage_categories',
            //     'edit_terms' => 'manage_categories',
            //     'delete_terms' => 'manage_categories',
            //     'assign_terms' => 'edit_posts'
            // ),

        ));

        $tslug = !empty($this->permalinks['cthtag_slug']) ? $this->permalinks['cthtag_slug'] : 'listing_tag';
        register_taxonomy('listing_tag',array('listing'), array(
            'hierarchical'      => false, // false for insert new tax when inserting post
            'labels'            => array(
                'name' => __( 'Listing Tags', 'townhub-add-ons' ),
                'singular_name' => __( 'Tag', 'townhub-add-ons' ),
                'search_items' =>  __( 'Search Tags','townhub-add-ons' ),
                'all_items' => __( 'All Tags','townhub-add-ons' ),
                'parent_item' => __( 'Parent Tag','townhub-add-ons' ),
                'parent_item_colon' => __( 'Parent Tag:','townhub-add-ons' ),
                'edit_item' => __( 'Edit Tag','townhub-add-ons' ), 
                'update_item' => __( 'Update Tag','townhub-add-ons' ),
                'add_new_item' => __( 'Add New Tag','townhub-add-ons' ),
                'new_item_name' => __( 'New Tag Name','townhub-add-ons' ),
                'menu_name' => __( 'Listing Tags','townhub-add-ons' ),
            ),
            'show_ui'           => true,
            'show_in_nav_menus' => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => $tslug ),
            // https://codex.wordpress.org/Roles_and_Capabilities
            // 'capabilities' => array(
            //     'manage_terms' => 'manage_categories',
            //     'edit_terms' => 'manage_categories',
            //     'delete_terms' => 'manage_categories',
            //     'assign_terms' => 'edit_posts'
            // ),

        ));
    }

    protected function filter_meta_args($args, $post){
        $new_post = false;
        if($post->post_date == $post->post_modified && $post->post_date_gmt == '0000-00-00 00:00:00' && $post->post_modified_gmt == '0000-00-00 00:00:00' ) 
            $new_post = true;
        $args['new_post'] = $new_post;

        return $args;
    }

    public function listing_datas_callback($post, $args){
        wp_nonce_field( 'cth-cpt-fields', '_cth_cpt_nonce', false );
        wp_nonce_field( 'cth-cpt-listing', '_cth_cpt_listing', false );
        $listing_fields = get_post_meta( $post->ID, ESB_META_PREFIX.'listing_fields', true );
        $room_fields = get_post_meta( $post->ID, ESB_META_PREFIX.'room_fields', true );
        $rating_fields = get_post_meta( $post->ID, ESB_META_PREFIX.'rating_fields', true );
        wp_localize_script( 'townhub-react-adminapp', '_townhub_addons_lfields', (array)json_decode($listing_fields) );
        wp_localize_script( 'townhub-react-adminapp', '_townhub_addons_rfields', (array)json_decode($room_fields) );
        wp_localize_script( 'townhub-react-adminapp', '_townhub_addons_frating', (array)json_decode($rating_fields) );
        ?>
        <div id="admin-listing-app"></div>
        <?php
    }
    public function listing_expire_callback($post, $args){
        ?>
        <div class="custom-form">
            <?php 
            $expire = get_post_meta( $post->ID, ESB_META_PREFIX.'expire_date', true );
            if($expire == 'NEVER') $expire = '';
            ?>
            <p><?php _e( 'Set expiration date. Leave <strong>empty</strong> for <strong>never</strong> expire.', 'townhub-add-ons' ); ?></p>
            <input type="text" id="listing_expire_date" name="expire_date" value="<?php echo $expire;?>">
        </div>
        <?php $save_as_pending = get_post_meta( $post->ID, ESB_META_PREFIX.'save_as_pending', true ); 
        if( $save_as_pending == 'yes' ): ?>
        <div class="saved-as-pending">
            <p><strong><?php _ex( 'SAVE AS PENDING', 'Edit listing', 'townhub-add-ons' ); ?></strong><br><?php _ex( 'The author expects to save this listing as pending so he can continue to finalize it before publishing', 'Edit listing', 'townhub-add-ons' ); ?></p>
        </div>
        <?php
        endif;
    }

    /**
     * Save post metadata when a post is saved.
     *
     * @param int $post_id The post ID.
     * @param post $post The post object.
     * @param bool $update Whether this is an existing post being updated or not.
     */
    public function save_post($post_id, $post, $update){
        if(!$this->can_save($post_id)) return;

        // Check if our nonce is set.
        if ( ! isset( $_POST['_cth_cpt_listing'] ) ) {
            return false;
        }
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST['_cth_cpt_listing'], 'cth-cpt-listing' ) ) {
            return false;
        }

        // - Update the post's metadata.
        if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Begin: townhub_addons_save_listing_meta" . PHP_EOL, 3, ESB_LOG_FILE);
        // unhook this function so it doesn't loop infinitely
        remove_action( 'save_post_'.$this->name, array($this, 'save_post'), 10, 3  );
        
            $this->save_post_meta($post_id, true, true);

        // re-hook this function
        add_action( 'save_post_'.$this->name, array($this, 'save_post'), 10, 3  );
    }

    protected function save_post_meta($listing_id = 0, $edit = false, $backend = false){
         // var_dump($_POST['listing_type_id']);
         // die();
        if(isset($_POST['listing_type_id']) && $_POST['listing_type_id']){
            $listing_type_id = $_POST['listing_type_id'];
        }else{
             $listing_type_id = esb_addons_get_wpml_option('default_listing_type', 'listing_type');
        }

        // var_dump($listing_type_id);
        // die();
        update_post_meta( $listing_id, ESB_META_PREFIX.'listing_type_id', $listing_type_id );

        self::do_save_metas($listing_id, $_POST);

        // add listing _price for filter
        self::update_listing_price($listing_id, $_POST);
        
        //if( isset($_POST['_price']) ) update_post_meta( $listing_id, '_price', $_POST['_price'] );
        
        // adding price for woo support in future
        // https://github.com/woocommerce/woocommerce/issues/14212
        // http://reigelgallarde.me/programming/how-to-add-custom-post-type-to-woocommerce/

        // disable update subscription meta from back-end
        if($backend != true){
            // current user sub
            
        }
        // end back-end disable

        // set backend expiration date
        if($backend && isset($_POST['expire_date'])){
            if($_POST['expire_date'] == ''){
                update_post_meta( $listing_id, ESB_META_PREFIX.'expire_date',  'NEVER' );
                
            }else{
                update_post_meta( $listing_id, ESB_META_PREFIX.'expire_date',  $_POST['expire_date'] );
            }
        }

        // if there is no current sub or any user
        // add expire date for submit listing only and not set yet
        // if(get_post_meta( $listing_id, ESB_META_PREFIX.'expire_date', true ) == ''){
        //     $expire_date = townhub_add_ons_cal_next_date('', 'day', townhub_addons_get_option('listing_expire_days') );
        //     update_post_meta( $listing_id, ESB_META_PREFIX.'expire_date', $expire_date  );

        //     $ts = get_gmt_from_date($expire_date,'U');
        //     townhub_addons_scheduleExpireEvent($listing_id,$ts);
        // }

        // // expire listing for specific listing type and on submit listing only - not editing
        // if( $edit === false &&  $listing_type_id == YOUR_LISTING_TYPE_ID ){
        //     townhub_addons_unscheduleExpireEvent($listing_id);

        //     $two_weeks_later = Esb_Class_Date::modify('now', 14, 'Y-m-d');
        //     $ts = get_gmt_from_date( $two_weeks_later,'U' );

        //     townhub_addons_scheduleExpireEvent($listing_id,$ts);
        // }

    }
     public function cats_features() {
        $json = array(
            'success' => true,
            'data' => array(
                'POST'=>$_POST,
            ),
            // '_POST'=>$_POST,
            // 'debug'     => false,
        );
        // wp_send_json($json );

        $nonce = $_POST['_nonce'];
        
        if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
            $json['success'] = false;
            $json['data'] = esc_html__( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ;

            // die ( '<p class="error">Security checked!, Cheatn huh?</p>' );

            wp_send_json($json );
        }

        if(isset($_POST['cats'])) $listing_cats = (array)$_POST['cats'];

        if(!is_array($listing_cats)) {
            $json['success'] = false;
            $json['data'] = esc_html__( 'Invalid listing category list - it must be array [50,51]', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }

        $cats_features = array();
        $cats_add_features = array();

        $cat_desc_id = 0;
        $cats_subcats = array();

        foreach ($listing_cats as $listing_cat) {
            
            if(!is_numeric($listing_cat)) {
                $json['success'] = false;
                $json['data'] = esc_html__( 'Invalid listing category', 'townhub-add-ons' ) ;
                wp_send_json($json );
            }

            if(!$cat_desc_id) $cat_desc_id = $listing_cat;

            // $term_meta = get_option("_cth_tax_listing_cat_$listing_cat");

            $term_meta = get_term_meta( $listing_cat, '_cth_term_meta', true );

            // $cat_features = array();

            if(isset($term_meta['features']) && !empty($term_meta['features'])){
                // $cat_features['features'] = array();
                foreach ($term_meta['features'] as $fea_id) {
                    $feature = get_term( $fea_id, 'listing_feature' );

                    if ( $feature != null && ! is_wp_error( $feature ) ){

                        // $cat_features['features']
                        $cats_features[] = array(
                            'type' => 'feature', // is features field
                            'label' => $feature->name,
                            'value' => $fea_id,
                            'lvalue' => ''
                        );
                        
                    }

                    
                }

            }

            if(isset($term_meta['add-features']) && !empty($term_meta['add-features'])){
                // $cat_features['add-features'] = array();
                foreach ($term_meta['add-features'] as $field) {
                    // $cat_features['add-features'][] = $field;
                    $field['lvalue'] = '';
                    $cats_add_features[] = $field;
                    // display_listing_add_feature_field('add-features',$field);
                }
            }
            if(townhub_addons_get_option('search_load_subcat') == 'yes'){
                $sub_cats = get_terms( array(
                    'taxonomy'      => 'listing_cat',
                    // 'hide_empty'    => false,
                    'parent'        => $listing_cat,
                ) );

                if ( ! empty( $sub_cats ) && ! is_wp_error( $sub_cats ) ){
                    foreach ( $sub_cats as $sub_cat ) {
                        $cats_subcats[] = array(
                            'id'            => $sub_cat->term_id,
                            'slug'          => $sub_cat->slug,
                            'name'          => $sub_cat->name,
                        );
                    }
                }
            }
            
        }
        // end foreach 

        $json['data'] = array(
            'features'      => array_unique($cats_features, SORT_REGULAR),
            'add-features'  => array_unique($cats_add_features,SORT_REGULAR),
            'cat_desc'      => term_description($cat_desc_id, 'listing_cat'),
            'subcats'       => $cats_subcats,
        );
        wp_send_json($json );

    }

    public function submit_listing() {
        $json = array(
            'success' => false,
            'data' => array(
                '_POST'=>$_POST,
                // '_FILE'=>$_FILES
            ),
            'debug'     => false,
        );

        Esb_Class_Ajax_Handler::verify_nonce('townhub-add-ons');
        
        // register new user and log he in
        if(isset($_POST['user_email'])){

            $user_name = isset($_POST['user_login'])? $_POST['user_login'] : substr($_POST['user_email'], 0, strpos($_POST['user_email'], "@") ); // substr(string,start,length)

            $new_user_data = array(
                'user_login' => $user_name,
                // 'user_pass'  => townhub_addons_generate_password(), // // When creating an user, `user_pass` is expected.
                'user_email' => $_POST['user_email'],
                'role'       => townhub_addons_get_option('author_role'),
            );
            $user_id = wp_insert_user( $new_user_data );
            //On success
            if ( ! is_wp_error( $user_id ) ) {
                // echo "User created : ". $user_id;

                $json['data']['user_id'] = $user_id;
                if(townhub_addons_get_option('new_user_email') != 'none') wp_new_user_notification( $user_id, null, townhub_addons_get_option('new_user_email') );
                // auto login user
                if( townhub_addons_get_option('register_auto_login') == 'yes' || townhub_addons_get_option('users_can_submit_listing') == 'yes' ) townhub_addons_auto_login_new_user( $user_id );
                do_action( 'townhub_addons_register_user', $user_id, true /*is when submit lisitng*/ );

            }else{
                $json['error'] = $user_id->get_error_message() ;
                $json['data']['new_user_data'] = $new_user_data ;
                // $json['data']['at_pos'] = strpos("@", $_POST['user_email']);
                // $json['data']['substr'] = substr($_POST['user_email'], 0, strpos($_POST['user_email'], "@") );
                wp_send_json( $json );
            }
        }else{
            // check if logged in user
            if(!is_user_logged_in()){
                $json['error'] = __( 'You must login on submiting a listing.', 'townhub-add-ons' );
                wp_send_json( $json );
            }
            $user_id = get_current_user_id();
        }

        
        $current_member_plan = Esb_Class_Membership::current_plan();
        $current_member_sub = Esb_Class_Membership::current_sub();
        // begin insert listing
        $listing_data = array();
        $edit_listing_id = isset($_POST['lid'])? $_POST['lid'] : 0;
        $is_editing_listing = false;
        if(is_numeric($edit_listing_id) && (int)$edit_listing_id > 0){
            $old_listing_post = get_post( $edit_listing_id );
            if($old_listing_post){
                $is_editing_listing = true;
                // $json['data']['is_editing_listing'] = true;
                if( ! user_can( $user_id, 'edit_post' , $edit_listing_id ) ){
                    $json['error'] = __( "You don't have permission to edit this listing.", 'townhub-add-ons' ) ;
                    // $json['url'] = esc_url( home_url('/') );
                    wp_send_json( $json );
                }
                // don't update post author
                $listing_data['post_author'] = $old_listing_post->post_author;
                $listing_data['post_status'] = townhub_addons_get_option('pending_editing_listing') == 'yes' ? 'pending' : $old_listing_post->post_status;
                $listing_data['post_date'] = $old_listing_post->post_date;

                $current_member_plan = Esb_Class_Membership::current_plan( $old_listing_post->post_author );
                $current_member_sub = Esb_Class_Membership::current_sub( $old_listing_post->post_author );
            }
        }

        if( !$is_editing_listing && Esb_Class_Membership::can_add() == false){
            $json['post']['listing_type_id'] = -1;
            $json['error'] = __( 'You are not allowed to submit listing. Your author subscription has expired or listing limitation exceeded.', 'townhub-add-ons' ) ; 
            $json['url'] = get_post_permalink( esb_addons_get_wpml_option('packages_page' ) );
            
            wp_send_json($json );
        }


        $listing_data['ID'] = $edit_listing_id; // set ID to update
        $listing_data['post_title'] = isset($_POST['title'])? esc_html($_POST['title']) : '';
        $listing_data['tax_input'] = array();

        if(isset($_POST['cats'])&& $_POST['cats']) $listing_data['tax_input']['listing_cat'] = $_POST['cats'];
        if(isset($_POST['features'])&& $_POST['features']) $listing_data['tax_input']['listing_feature'] = $_POST['features'];
        $ltags = array();
        if(isset($_POST['tags']) && $_POST['tags'])
            $ltags = explode( ",", esc_html($_POST['tags']) );
        $ltags = (array) apply_filters( 'ctb_submit_ltags', $ltags );

        $listing_data['tags_input'] = $ltags;

        // new listing tags
        $ltags_names = array();
        if(isset($_POST['ltags_names']) && $_POST['ltags_names'])
            $ltags_names = explode(",", esc_html($_POST['ltags_names']) );
        $ltags_names = (array) apply_filters( 'ctb_submit_listing_tags_names', $ltags_names );
        $listing_data['tax_input']['listing_tag'] = $ltags_names;
        
        $location_terms = array();
        if( isset($_POST['locations']) && !empty($_POST['locations']) ){
            $plocs = explode( "|", esc_html($_POST['locations']) );
            $plocs = array_filter(
                array_map(function($loc){
                    return urldecode($loc);
                }, $plocs)
            );
            $plocs = array_values($plocs);
            
            if(!empty($plocs)){
                $country = $plocs[0];
                $country_slug = strtolower($country);
                // Check if the country exists
                $country_term = term_exists( $country_slug, 'listing_location', 0 );
                // Create country if it doesn't exist
                if( !$country_term ) {
                    $country_term = wp_insert_term( townhub_addons_get_google_contry_codes($country), 'listing_location', array( 'parent' => 0 , 'slug' => $country_slug ) );
                }

                if($country_term && !is_wp_error($country_term)){
                    $location_terms[] = (int)$country_term['term_taxonomy_id'];

                    if(count($plocs) >= 2){
                        $state = $plocs[1];
                        // Check if the state exists
                        $state_term = term_exists( $state, 'listing_location', (int)$country_term['term_taxonomy_id'] );
                        // Create state if it doesn't exist
                        if( !$state_term ) {
                            $state_term = wp_insert_term( $state, 'listing_location', array( 'parent' => (int)$country_term['term_taxonomy_id'] ) );
                        }
                        if($state_term && !is_wp_error($state_term)){
                            $location_terms[] = (int)$state_term['term_taxonomy_id'];
                            if(count($plocs) >= 3){
                                $city = $plocs[2];
                                // Check if the city exists
                                $city_term = term_exists( $city, 'listing_location', (int)$state_term['term_taxonomy_id'] );
                                // Create city if it doesn't exist
                                if( !$city_term ) {
                                    $city_term = wp_insert_term( $city, 'listing_location', array( 'parent' => (int)$state_term['term_taxonomy_id'] ) );
                                }
                                if($city_term && !is_wp_error($city_term)) $location_terms[] = (int)$city_term['term_taxonomy_id'];
                            }
                        }
                    }
                }
            }
            $listing_data['tax_input']['listing_location'] = $location_terms;
        } 

        // if(isset($_POST['locations'])&& $_POST['locations']) $listing_data['tax_input']['listing_location'] = $_POST['locations'];
        
        // new listing location select
        if(isset($_POST['select_locations'])&& !empty( $_POST['select_locations'] ) ){
            if(!is_array($_POST['select_locations'])){
                $location_terms = array( intval($_POST['select_locations']) );
            }else{
                $location_terms = array_map( function($loc){ return intval($loc); }, $_POST['select_locations']);
            }
            $listing_data['tax_input']['listing_location'] = $location_terms;
        } 
        

        // $listing_data['post_author'] = 2;
        if(isset($_POST['post_excerpt'])) $listing_data['post_excerpt'] = wp_kses_post($_POST['post_excerpt']);
        $listing_data['post_content'] = isset($_POST['content'])? wp_kses_post($_POST['content']) : '';
        //$listing_data['post_author'] = '0';// default 0 for no author assigned
        // set status for listing submission only
        if($is_editing_listing == false){
            if(townhub_addons_get_option('auto_publish_paid_listings') == 'yes')
                $listing_data['post_status'] = 'publish';
            else
                $listing_data['post_status'] = 'pending'; // publish, future, draft, pending, private, trash, auto-draft, inherit
        }
        // elseif( $listing_data['post_status'] == 'pending' ){
        //     // update status for new plan - did from active membership 
        // }

        if( isset($_POST['save_as_pending']) ){
            if( $_POST['save_as_pending'] == 'yes' ){
                $listing_data['post_status'] = 'pending';
            }elseif( townhub_addons_get_option('publish_not_pending') == 'yes' ){
                $listing_data['post_status'] = 'publish';
            }
        }

        

        
        $listing_data['post_type'] = 'listing';

        if( isset($_POST['disable_comment']) && !empty($_POST['disable_comment']) ){
            $listing_data['comment_status'] = 'closed'; //'open'; // closed
        }else{
            $listing_data['comment_status'] = get_option('default_comment_status');
        }
        
        $json['data']['post_data'] = $listing_data;
        // wp_send_json( $json );

        do_action( 'townhub_addons_insert_listing_before', $listing_data, $is_editing_listing );

        $listing_id = wp_insert_post($listing_data ,true );
                        

        if (!is_wp_error($listing_id)) {
            $json['success'] = true;
            $json['data']['lid'] = $listing_id;

            // $json['location_terms'] = $location_terms;

            // $json['listing_locs_before'] = get_the_terms($listing_id,  'listing_location');
            // update locs
            // if( !empty($location_terms) ){
                wp_set_object_terms( $listing_id, $location_terms, 'listing_location' );
            // }
            // $json['listing_locs_after'] = get_the_terms($listing_id,  'listing_location');

            // update listing type id
            if(isset($_POST['listing_type_id']) && $_POST['listing_type_id']){
                $listing_type_id = $_POST['listing_type_id'];
            }else{
                $listing_type_id = esb_addons_get_wpml_option('default_listing_type', 'listing_type');
            }

            update_post_meta( $listing_id, ESB_META_PREFIX.'listing_type_id', $listing_type_id );

            // for insert/update listing rooms
            $old_listing_rooms_ids = array_unique((array)get_post_meta( $listing_id, ESB_META_PREFIX.'rooms_ids', true ));
            $listing_rooms_ids = array();
            if(isset($_POST['listing_rooms']) && is_array($_POST['listing_rooms']) && !empty($_POST['listing_rooms'])){
                foreach ($_POST['listing_rooms'] as $room_data) {
                    $room_id = $this->insert_room_post($room_data, $listing_id);
                    if($room_id && is_numeric($room_id)){
                        $listing_rooms_ids[] = $room_id;
                        if(!empty($old_listing_rooms_ids)){
                            $old_key = array_search($room_id, $old_listing_rooms_ids);
                            if($old_key !== false) unset($old_listing_rooms_ids[$old_key]);
                        }
                    } 
                }
            }
            // update room_ids for listing - two ways binding
            update_post_meta( $listing_id, ESB_META_PREFIX.'rooms_ids', $listing_rooms_ids );
            

            // $json['data']['listing_rooms_ids'] = $listing_rooms_ids;
            // delete unused room
            if(!empty($old_listing_rooms_ids)){
                foreach ($old_listing_rooms_ids as $rdid) {
                    wp_delete_post( $rdid, false );
                }
            }
            //for insert/update coupon
            
            $old_lcoupon_ids = array_unique((array)get_post_meta( $listing_id, ESB_META_PREFIX.'coupon_ids', true ));
            $coupon_ids = array();
            if(isset($_POST['lcoupon']) && is_array($_POST['lcoupon']) && !empty($_POST['lcoupon'])){
                foreach ($_POST['lcoupon'] as $coupon_data) {
                    $coupon_id = $this->insert_coupon_code($coupon_data, $listing_id);
                    if($coupon_id && is_numeric($coupon_id)){
                        $coupon_ids[] = $coupon_id;
                        if(!empty($old_lcoupon_ids)){
                            $old_key = array_search($coupon_id, $old_lcoupon_ids);
                            if($old_key !== false) unset($old_lcoupon_ids[$old_key]);
                        }
                    } 
                }
            }
            // update coupon_ids for listing - two ways binding
            update_post_meta( $listing_id, ESB_META_PREFIX.'coupon_ids', $coupon_ids );
            // delete unused room
            if(!empty($old_lcoupon_ids)){
                foreach ($old_lcoupon_ids as $couponid) {
                    wp_delete_post( $couponid, false );
                }
            }
 
            // new update listing meta
            self::do_save_metas($listing_id, $_POST); 

            // add listing _price for filter
            self::update_listing_price($listing_id, $_POST);

            // single listing plan
            if( isset($_POST['single_listing_plan']) && !empty($_POST['single_listing_plan']) ){
                $current_member_plan = $_POST['single_listing_plan'];
            }

            

            // process listing expire for listing submission only
            if($is_editing_listing == false){
                self::update_listing_plan_metas($listing_id, $current_member_sub, $current_member_plan);
            }
            // end if($is_editing_listing == false)

            // if( get_post_meta( $listing_id, ESB_META_PREFIX.'featured', true ) === '' ){
            //     update_post_meta( $listing_id, ESB_META_PREFIX.'featured', '0' );
            // }
                
            // update thumbnail image
            if( ( $is_editing_listing || is_user_logged_in()  ) && isset($_POST['thumbnail']) ){
                $featured = $_POST['thumbnail'];
                if(is_array($featured) && count($featured)){
                    $featured = reset($featured);
                }
                // delete old image
                $old_thumbnail_id = get_post_thumbnail_id($listing_id);
                if( townhub_addons_get_option('submit_remove_deleted_imgs') == 'yes' && $featured != $old_thumbnail_id ){
                    wp_delete_attachment( $old_thumbnail_id, true );
                }
                $json['data']['thumbnail_meta_id'] = set_post_thumbnail( $listing_id, $featured );

            }
            // end update featured
            $submit_redirect = townhub_addons_get_option('submit_redirect');
            if( $submit_redirect == 'single' ){
                $listing_redirect_url = get_post_permalink($listing_id);
            }else{
                $listing_redirect_url = get_post_permalink($submit_redirect);
            }
            
            if(!is_wp_error($listing_redirect_url)) $json['url'] = apply_filters( 'esb_submit_listing_redirect_url', $listing_redirect_url, $listing_id, $is_editing_listing ) ;

            
            // $json['data'] = $_POST['test_var'];

            if( $is_editing_listing == false && Esb_Class_Membership::can_add() == false ){
                Esb_Class_Dashboard::add_notification($user_id, array(
                    'type' => 'listing_limit',
                    'entity_id'     => $listing_id,
                    'actor_id'      => $user_id,
                ));
            }

            do_action( 'townhub_addons_insert_listing_after', $listing_id, $is_editing_listing );
        }else{
            $json['error'] = $listing_id->get_error_message();
        }
        wp_send_json( $json );

        // https://codex.wordpress.org/Function_Reference/wp_handle_upload
        // https://wordpress.org/plugins/radio-buttons-for-taxonomies/

        // https://stackoverflow.com/questions/19949876/how-to-auto-login-after-registration-in-wordpress-with-core-php
        // https://stackoverflow.com/questions/19949876/how-to-auto-login-after-registration-in-wordpress-with-core-php
        // https://codex.wordpress.org/Roles_and_Capabilities
        // https://codex.wordpress.org/Function_Reference/register_taxonomy
        // https://developer.wordpress.org/reference/functions/wp_insert_post/
    }

    public static function update_listing_price($listing_id = 0, $post_data = array() ){
        $room_prices = array();
        $listing_rooms_ides = (array)get_post_meta( $listing_id, ESB_META_PREFIX.'rooms_ids', true );
        if(!empty($listing_rooms_ides)){
            foreach ($listing_rooms_ides as $rid) {
                $room_prices[] = (float)get_post_meta( $rid, '_price', true );
            }
        }
        $room_prices = array_filter($room_prices);

        // set price for event
        $tickets = get_post_meta( $listing_id, ESB_META_PREFIX.'tickets', true );
        $ticket_prices = array();
        if( !empty($tickets) && is_array($tickets) ){
            foreach ($tickets as $ticket) {
                if( isset($ticket['price']) ) $ticket_prices[] = floatval( $ticket['price'] );
            }
        }
        $ticket_prices = array_filter($ticket_prices);
        
        $_price = 0;
        if(!empty($room_prices)){
            // $_price = array_sum($room_prices)/count($room_prices);
            $_price = min( $room_prices );
        }elseif(!empty($ticket_prices)){
            $_price = min( $ticket_prices );
        }elseif( isset($post_data['_price']) ){ // if has _price
            $_price = floatval($post_data['_price']);
        }elseif(isset($post_data['price_from']) && floatval($post_data['price_from']) > 0){ // if has price range
            $_price = floatval($post_data['price_from']);
        }
        update_post_meta( $listing_id, '_price', $_price ); 


        // if( isset($_POST['_price']) ) update_post_meta( $listing_id, '_price', $_POST['_price'] );


    }

    public static function update_listing_plan_metas($listing_id = 0, $order_id = 0, $plan_id = 0 ){
        update_post_meta( $listing_id, ESB_META_PREFIX.'lsubscribe', $order_id );
        update_post_meta( $listing_id, ESB_META_PREFIX.'listing_sub_plan', $plan_id );

        
        $authorID = get_post_field( 'post_author', $listing_id, 'display' );
        $end_date = Esb_Class_Membership::expire_date($authorID);
        if($end_date == 'NEVER'){
            update_post_meta( $listing_id, ESB_META_PREFIX.'expire_date',  'NEVER' );
        }else{
            update_post_meta( $listing_id, ESB_META_PREFIX.'expire_date',  $end_date );
        }
    }

    public static function do_save_metas($post_id = 0, $post_data = array()){
        // new update listing meta
        $meta_fields = townhub_addons_get_listing_type_fields_meta( get_post_meta( $post_id, ESB_META_PREFIX.'listing_type_id', true ) );
        $listing_metas = array();
        foreach($meta_fields as $fname => $ftype){
            if($ftype == 'array'){
                $listing_metas[$fname] = isset($post_data[$fname]) ? $post_data[$fname]  : array();

            }elseif($ftype == 'raw_text'){
                $listing_metas[$fname] = isset($post_data[$fname]) ? $post_data[$fname] : '';
            }else{
                $listing_metas[$fname] = isset($post_data[$fname]) ? wp_kses_post($post_data[$fname]) : '';
            }

            
            // if(isset($post_data[$fname])) $listing_metas[$fname] = esc_html($post_data[$fname]) ;
            // else{
            //     if($ftype == 'array'){
            //         $listing_metas[$fname] = array();
            //     }else{
            //         $listing_metas[$fname] = '';
            //     }
            // } 
        }
        foreach ($listing_metas as $key => $value) {
            $old_val = get_post_meta( $post_id, ESB_META_PREFIX.$key, true );
            if($old_val != $value){

                // delete old images
                if( !empty($old_val) && townhub_addons_get_option('submit_remove_deleted_imgs') == 'yes' && in_array($key, array('llogo','images',) ) ){
                    $old_imgs = explode(',', $old_val);
                    $new_imgs = explode(',', $value);
                    $deleted_imgs = array_diff($old_imgs, $new_imgs);
                    if( !empty($deleted_imgs) ){
                        foreach ($deleted_imgs as $imgID) {
                            wp_delete_attachment( $imgID, true );
                        }
                    }
                }
                update_post_meta( $post_id, ESB_META_PREFIX.$key, $value );
            } 
        }

        if( get_post_meta( $post_id, ESB_META_PREFIX.'featured', true ) === '' ){
            update_post_meta( $post_id, ESB_META_PREFIX.'featured', '0' );
        }

        // oderby event date
        if( get_post_meta( $post_id, ESB_META_PREFIX.'eventdate_start', true ) == '' ){
            update_post_meta( $post_id, ESB_META_PREFIX.'eventdate_start', date_i18n( 'Y-m-d' ) );
        }
        if( get_post_meta( $post_id, ESB_META_PREFIX.'eventdate_end', true ) == '' ){
            update_post_meta( $post_id, ESB_META_PREFIX.'eventdate_end', 'none' );
        }

        

        // for saving working_hours meta
        $working_hours_data = array();
        if(isset($post_data['working_hours'])) $working_hours_data = (array)$post_data['working_hours'];

        update_post_meta( $post_id, ESB_META_PREFIX.'working_hours_meta', $working_hours_data );

        self::update_working_hours( $post_id, $working_hours_data );

    }

    public static function update_working_hours( $l_ID = 0, $meta_value = array() ){

        $l_post = get_post( $l_ID );
        if ( ! $l_post ) {
            return false;
        }
        // $meta_value = array(
        //     'timezone' => 'UTC',
        //     'Mon'    => array(
        //         'static'        => 'closeAllDay', // enterHours, openAllDay
        //         'hours'         => array(
        //             array(
        //                 'open'      => '8.30',
        //                 'close'      => '12.00',
        //             ),
        //             array(
        //                 'open'      => '13.30',
        //                 'close'      => '18.00',
        //             ),
        //         )
        //     ),
        // );

        $ltimezone = Esb_Class_Date::timezone();

        if( is_array($meta_value) && !empty($meta_value) ){
            // update timezone
            if(isset($meta_value['timezone']) && $meta_value['timezone'] != ''){
                $ltimezone = trim($meta_value['timezone']);
            }
            // update working days
            $day_num = 1; // 1 (for Monday) through 7 (for Sunday) - correctly with Esb_Class_Date::week_days() array
            foreach ( Esb_Class_Date::week_days() as $day => $dayLbl ) {
                
                if( isset($meta_value[$day]) && is_array($meta_value[$day]) && !empty($meta_value[$day]) ){
                    $dstatic = 'closeAllDay';
                    $hours = array();
                    if( isset($meta_value[$day]['static']) ) $dstatic = $meta_value[$day]['static'];
                    if( isset($meta_value[$day]['hours']) && is_array($meta_value[$day]['hours']) && !empty($meta_value[$day]['hours']) ){
                        foreach ($meta_value[$day]['hours'] as $hour) {
                            if($hour['open'] != '' && $hour['close'] != '')
                                $hours[] = array('open'=>$hour['open'], 'close'=>$hour['close']);
                        }
                    }

                    self::update_wkhours_data($l_ID, $day, $day_num, $dstatic, $hours);
                    
                }else{
                    self::delete_wkhours_day($l_ID, $day);
                }
                
                // else{
                //     update_post_meta($l_ID, ESB_META_PREFIX."wkh_status_{$day}", 'closeAllDay');
                //     update_post_meta($l_ID, ESB_META_PREFIX."wkh_hours_{$day}", '');
                // }
                $day_num++;
            }
            // end update days
        }else{
            // delete wkhour rows
            self::delete_wkhours_data($l_ID);
        }

        update_post_meta($l_ID, ESB_META_PREFIX."wkh_tz", $ltimezone );
        update_post_meta($l_ID, ESB_META_PREFIX."wkh_tz_utc_offset", Esb_Class_Date::utc_tz_offset($ltimezone) );
                    
    }
    public static function update_wkhours_data($post_id = 0, $day = '', $day_num = '', $static = '', $hours = array() ){
        global $wpdb;
        $_table = $wpdb->prefix . 'cth_wkhours';

        $old_rows = $wpdb->get_results( 
            $wpdb->prepare( 
                "
                SELECT ID 
                FROM $_table
                WHERE post_id = %d AND day = %s
                ORDER BY ID ASC
                ",
                $post_id,
                $day
            )
        );
        // modify - delete old row
        if( $old_rows ){
            if(!empty($hours)){
                foreach ($old_rows as $key => $olrow) {
                    if(isset($hours[$key])){
                        self::update_wkhour_row(
                            array( 
                                'static'        => $static,
                                'open'          => $hours[$key]['open'],
                                'close'         => $hours[$key]['close']
                            ),
                            array( 'ID' => $olrow->ID )
                        );
                        // delete added hour
                        unset( $hours[$key] );
                    }else{
                        $wpdb->delete( $_table, array( 'ID' => $olrow->ID ), array( '%d' ) );
                    }
                }
                // remaining hours
                if(!empty($hours)){
                    foreach ($hours as $hour) {
                        self::insert_wkhour_row(
                            array( 
                                'post_id'       => $post_id, 
                                'day'           => $day, 
                                'day_num'       => $day_num, 
                                'static'        => $static,
                                'open'          => $hour['open'],
                                'close'         => $hour['close']
                            )
                        );
                    }
                }
                    
            }else{
                foreach ($old_rows as $key => $olrow) {
                    if($key == 0){
                        
                        self::update_wkhour_row(
                            array( 
                                'static'        => $static,
                                'open'          => NULL,
                                'close'         => NULL
                            ),
                            array( 'ID' => $olrow->ID )
                        );

                    }else{
                        $wpdb->delete( $_table, array( 'ID' => $olrow->ID ), array( '%d' ) );
                    }
                }
            }
        }else{ // insert new row
            if(!empty($hours)){
                foreach ($hours as $hour) {
                    self::insert_wkhour_row(
                        array( 
                            'post_id'       => $post_id, 
                            'day'           => $day, 
                            'day_num'       => $day_num, 
                            'static'        => $static,
                            'open'          => $hour['open'],
                            'close'         => $hour['close']
                        )
                    );
                }
            }else{
                // only status
                self::insert_wkhour_row(
                    array( 
                        'post_id'       => $post_id, 
                        'day'           => $day, 
                        'day_num'       => $day_num, 
                        'static'        => $static,
                        'open'          => NULL,
                        'close'         => NULL
                    )
                );
            }
        }
        

    }

    public static function delete_wkhours_data($post_id = 0){
        global $wpdb;
        $_table = $wpdb->prefix . 'cth_wkhours';
        $wpdb->delete( $_table, array( 'post_id' => $post_id ), array( '%d' ) );
    }
    public static function delete_wkhours_day($post_id = 0, $day = '' ){
        global $wpdb;
        $_table = $wpdb->prefix . 'cth_wkhours';
        $wpdb->delete( $_table, array( 'post_id' => $post_id, 'day' => $day ), array( '%d', '%s' ) );
    }

    

    public static function insert_wkhour_row( $datas = array(), $formats = array('%d','%s','%d','%s','%s','%s') ){
        global $wpdb;
        $_table = $wpdb->prefix . 'cth_wkhours';
        if(!empty($datas)){
            $wpdb->insert( 
                $_table, 
                $datas, 
                $formats
            );
        }
    }
    public static function update_wkhour_row( $datas = array(), $where = array() , $formats = array('%s','%s','%s'), $where_format = array( '%d' ) ){
        global $wpdb;
        $_table = $wpdb->prefix . 'cth_wkhours';
        if(!empty($datas)){
            $wpdb->update( 
                $_table, 
                $datas, 
                $where, 
                $formats, 
                $where_format 
            );
        }
    }

    protected function set_meta_columns(){
        $this->has_columns = true;
    }
    public function meta_columns_head($columns){
        unset($columns['tags']);
        unset($columns['comments']);
        unset($columns['taxonomy-listing_feature']);
        unset($columns['taxonomy-listing_tag']);
        $columns['_ltype'] = __( 'Listing Type', 'townhub-add-ons' );
        $columns['_thumbnail'] = __( 'Thumbnail', 'townhub-add-ons' );
        $columns['_id'] = __( 'ID', 'townhub-add-ons' );
        $columns['expire_date'] = __( 'Expiration Date', 'townhub-add-ons' );
        $columns['_featured'] = __( 'Featured', 'townhub-add-ons' );
        $columns['_verified'] = __( 'Verified', 'townhub-add-ons' );
        return $columns;
    }
    public function meta_columns_content($column_name, $post_ID){
        if ($column_name == '_id') { 
            echo $post_ID;
        }
        if ($column_name == '_ltype') {
            echo get_the_title( get_post_meta( $post_ID, ESB_META_PREFIX.'listing_type_id', true ) );
        }
        if ($column_name == '_thumbnail') {
            echo get_the_post_thumbnail( $post_ID, 'thumbnail', array('style'=>'width:100px;height:auto;') );
        }

        if ($column_name == 'expire_date') {
            $expire_date = get_post_meta( $post_ID, ESB_META_PREFIX.'expire_date', true );
            if( $expire_date == 'NEVER' || empty($expire_date) ){
                _e( 'Never', 'townhub-add-ons' );
            }elseif( $expire_date < current_time( 'mysql', 1 ) ){
                _e( 'Expired', 'townhub-add-ons' );
                //echo '<br>'.$expire_date;
            }else{
                echo $expire_date;
            }
        }

        if ($column_name == '_featured') {
            echo '<a href="#" class="button set-lfeatured'.( get_post_meta( $post_ID, ESB_META_PREFIX.'featured', true ) == '1'? ' lfeatured' : '' ).'" data-id="'.$post_ID.'"><span class="lfeatured-loading"><i class="fa fa-spinner fa-pulse"></i></span><span class="as-lfeatured">'.__( 'Set as featured', 'townhub-add-ons' ).'</span><span class="lfeatured">'.__( 'Featured', 'townhub-add-ons' ).'</span></a>';
        }
        if ($column_name == '_verified') {
            // var_dump(get_post_meta( $post_ID, ESB_META_PREFIX.'verified', true ));
            echo '<a href="#" class="button set-lverified'.( get_post_meta( $post_ID, ESB_META_PREFIX.'verified', true ) == '1' ? ' lverified' : '' ).'" data-id="'.$post_ID.'"><span class="lverified-loading"><i class="fa fa-spinner fa-pulse"></i></span><span class="as-lverified">'.__( 'Verify', 'townhub-add-ons' ).'</span><span class="lverified">'.__( 'Verified', 'townhub-add-ons' ).'</span></a>';
        }
    }
    protected function insert_room_post($room_post = array(), $listing_id = 0 ){
        // begin insert listing
        $room_datas = array();
        $edit_room_id = isset($room_post['rid'])? $room_post['rid'] : 0;
        $is_editing_room = false;
        if(is_numeric($edit_room_id) && (int)$edit_room_id > 0){
            $old_room_post = get_post( $edit_room_id );
            if($old_room_post){
                $is_editing_room = true; 
                // don't update post author
                $room_datas['post_author'] = $old_room_post->post_author;
                $room_datas['post_status'] = $old_room_post->post_status;
                $room_datas['post_date'] = $old_room_post->post_date;
            }
        }
        $room_datas['ID'] = $edit_room_id; // set ID to update
        $room_datas['post_title'] = isset($room_post['title'])? esc_html($room_post['title']) : '';
        $room_datas['tax_input'] = array();
        if(isset($room_post['features'])&& $room_post['features']) $room_datas['tax_input']['listing_feature'] = $room_post['features'];
        if(isset($room_post['post_excerpt'])) $room_datas['post_excerpt'] = wp_kses_post($room_post['post_excerpt']);
        $room_datas['post_content'] = isset($room_post['content'])? wp_kses_post($room_post['content']) : '';
        //$room_datas['post_author'] = '0';// default 0 for no author assigned
        // set status for listing submission only
        if($is_editing_room == false){
            $room_datas['post_status'] = 'publish'; // publish, future, draft, pending, private, trash, auto-draft, inherit
        }
        $listing_type_id = get_post_meta($listing_id,ESB_META_PREFIX.'listing_type_id',true);
        $child_pt = get_post_meta($listing_type_id,ESB_META_PREFIX.'child_type_meta',true);

        $room_datas['post_type'] = 'lrooms';
        if( $child_pt == 'product' ){
            $room_datas['post_type'] = 'product';

            $room_datas['tax_input'] = array();
            if( isset($room_post['cats']) && !empty($room_post['cats']) ) $room_datas['tax_input']['product_cat'] = $room_post['cats'];
        }
        
        // $room_datas['post_type'] = ($child_pt == 'product')? 'product' : 'lrooms';

        $room_datas['comment_status'] = 'open'; // closed

        do_action( 'townhub_addons_insert_room_before', $room_datas, $is_editing_room );

        $room_id = wp_insert_post($room_datas ,true );
             
        if (!is_wp_error($room_id)) {
            if($child_pt != 'lrooms' && $child_pt != 'none'){
                 $meta_fields = townhub_addons_get_listing_type_fields_meta( get_post_meta( $listing_id, ESB_META_PREFIX.'listing_type_id', true ) , true);
                $woo_metas = array();
                foreach($meta_fields as $fname => $ftype){
                    if($ftype == 'array'){
                        $woo_metas[$fname] = isset($room_post[$fname]) ? $room_post[$fname]  : array();
                    }else{
                        $woo_metas[$fname] = isset($room_post[$fname]) ? wp_kses_post($room_post[$fname]) : '';
                    }


                    // if(isset($room_post[$fname])) 
                    //     $woo_metas[$fname] = $room_post[$fname] ;
                    // else{
                    //     if($ftype == 'array'){
                    //         $woo_metas[$fname] = array();
                    //     }else{
                    //         $woo_metas[$fname] = '';
                    //     }
                    // } 
                }
                foreach ($woo_metas as $key => $value) {
                    $old_val = get_post_meta( $room_id, ESB_META_PREFIX.$key, true );
                    if($old_val != $value){
                        // delete old images
                        if( !empty($old_val) && townhub_addons_get_option('submit_remove_deleted_imgs') == 'yes' && in_array($key, array('images',) ) ){
                            $old_imgs = explode(',', $old_val);
                            $new_imgs = explode(',', $value);
                            $deleted_imgs = array_diff($old_imgs, $new_imgs);
                            if( !empty($deleted_imgs) ){
                                foreach ($deleted_imgs as $imgID) {
                                    wp_delete_attachment( $imgID, true );
                                }
                            }
                        }
                        update_post_meta( $room_id, ESB_META_PREFIX.$key, $value );
                    } 
                }

                // room price
                if( isset($room_post['_price']) && $room_post['_price'] ) update_post_meta( $room_id, '_price', esc_html($room_post['_price']) );
                // for changing listing
                $for_listing_id = get_post_meta( $room_id, ESB_META_PREFIX.'for_listing_id', true );
                if($for_listing_id != $listing_id) 
                    update_post_meta( $room_id, ESB_META_PREFIX.'for_listing_id', $listing_id );
                // update thumbnail image
                // product_image_gallery
                 if( isset($room_post['images']) && $room_post['images'] ){
                    // update_post_meta( $room_id, 'images', $room_post['images'] );
                    update_post_meta( $room_id, '_product_image_gallery', $room_post['images'] );
                 }
                
            }else{

                Esb_Class_LRooms_CPT::do_save_metas($room_id, $room_post, $listing_id);

            };
            // update thumbnail image
            if(isset($room_post['thumbnail'])){
                $featured = $room_post['thumbnail'];
                if(is_array($featured) && count($featured)){
                    $featured = reset($featured);
                }
                // delete old image
                $old_thumbnail_id = get_post_thumbnail_id($room_id);
                if( townhub_addons_get_option('submit_remove_deleted_imgs') == 'yes' && $featured != $old_thumbnail_id ){
                    wp_delete_attachment( $old_thumbnail_id, true );
                }
                set_post_thumbnail( $room_id, $featured );

            }
            do_action( 'townhub_addons_insert_room_after', $room_id, $room_datas, $is_editing_room );

            return $room_id;
        }
        return false;
    }
     protected function insert_coupon_code ($coupon_post = array(), $listing_id = 0){
        // begin insert 
        if(is_numeric($listing_id) && (int)$listing_id > 0){
            $coupon_title = __( '%1$s for %2$s', 'townhub-add-ons' );
            $coupon_datas = array();
            $coupon_datas['post_title'] = sprintf( $coupon_title, (isset($_POST['coupon_code']) ? esc_html($_POST['coupon_code']) : '' ) , get_the_title( $listing_id ));
            $coupon_datas['post_content'] = '';
            $coupon_datas['post_status'] = 'publish';
            $coupon_datas['post_type'] = 'cthcoupons';

            $coupon_id = wp_insert_post($coupon_datas ,true );  
            if (!is_wp_error($coupon_id)) {
                $meta_fields = array(
                    'coupon_code'               => 'text',
                    'discount_type'             => 'text',
                    'dis_amount'                => 'text',
                    'coupon_decs'               => 'text',
                    'coupon_qty'                => 'text',
                    'coupon_expiry_date'        => 'text',
                );
                $coupon_metas = array();
                foreach($meta_fields as $fname => $ftype){
                    if($ftype == 'array'){
                        $coupon_metas[$fname] = isset($coupon_post[$fname]) ? $coupon_post[$fname]  : array();
                    }else{
                        $coupon_metas[$fname] = isset($coupon_post[$fname]) ? esc_html($coupon_post[$fname]) : '';
                    }

            
                    // if(isset($coupon_post[$field])) 
                    //     $coupon_metas[$field] = $coupon_post[$field] ;
                    // else{
                    //     if($ftype == 'array'){
                    //         $coupon_metas[$field] = array();
                    //     }else{
                    //         $coupon_metas[$field] = '';
                    //     }
                    // } 
                }
                foreach ($coupon_metas as $key => $value) {
                    $old_val = get_post_meta( $coupon_id, ESB_META_PREFIX.$key, true );
                    if($old_val != $value) update_post_meta( $coupon_id, ESB_META_PREFIX.$key, $value );
                }

                // for changing listing
                $for_coupon_listing_id = get_post_meta( $coupon_id, ESB_META_PREFIX.'for_coupon_listing_id', true );
                if($for_coupon_listing_id != $listing_id){
                    update_post_meta( $coupon_id, ESB_META_PREFIX.'for_coupon_listing_id', $listing_id );
                }
                update_post_meta( $coupon_id, ESB_META_PREFIX.'for_all_listing', '' );
                
                return $coupon_id;
            }
        }
        return false;

    }
    

    public function admin_lfeatured(){
        $json = array(
            'success' => false,
            // 'data' => array(
            //     'POST'=>$_POST,
            // )
        );

        $lid = isset($_POST['lid'])? $_POST['lid'] : 0;
        if(is_numeric($lid) && (int)$lid > 0){
            $lfeatured = isset($_POST['lfeatured'])? $_POST['lfeatured'] : false;
            if($lfeatured)
                update_post_meta( $lid, ESB_META_PREFIX.'featured', '0' );
            else
                update_post_meta( $lid, ESB_META_PREFIX.'featured', '1' );
            $json['success'] = true;
        }else{
            // $json['success'] = false;
            $json['data']['error'] = esc_html__( 'The post id is incorrect.', 'townhub-add-ons' ) ;
        }

        wp_send_json($json );

    }

    function admin_lverified(){
        $json = array(
            'success' => false,
            'data' => array(
                // 'POST'=>$_POST,
            )
        );
        $lid = isset($_POST['lid'])? $_POST['lid'] : 0;
        if(is_numeric($lid) && (int)$lid > 0){
            $lverified = isset($_POST['lverified']) && $_POST['lverified'] ? '0' : '1';
            if(update_post_meta( $lid, ESB_META_PREFIX.'verified', $lverified ))
            $json['success'] = true;
        }else{
            // $json['success'] = false;
            $json['data'] = esc_html__( 'The post id is incorrect.', 'townhub-add-ons' ) ;
        }

        wp_send_json($json );

    }
    public static function wkhours_add(){
        $data = array();
        $working_days = Esb_Class_Date::week_days();
        $data['timezone'] = Esb_Class_Date::timezone(); // wordpress timezone string
        foreach ($working_days as $day => $dayLbl ) {
            $data[$day] = array(
                'static'    => 'openAllDay',
                'hours'     => array(),
            );
        }
        return $data;

    }
    /* args
    * $for_edit: bool - true to get save data - not display value
    */
    public static function wkhours($post_id = 0, $for_edit = true){
        if( empty($post_id) ) return false;

        $post_obj = get_post($post_id);
        if( null == $post_obj || $post_obj->post_status == 'auto-draft' ){
            return false;
        } 

        global $wpdb;
        $_table = $wpdb->prefix . 'cth_wkhours';
        $working_hours = $wpdb->get_results( 
            $wpdb->prepare( 
                "
                SELECT * 
                FROM $_table
                WHERE post_id = %d 
                ORDER BY ID,day_num ASC
                ",
                $post_id
            )
        );

        $data = array();


        $data['timezone'] = get_post_meta( $post_id, ESB_META_PREFIX."wkh_tz", true );
        if( !empty($working_hours) ){
            foreach ($working_hours as $day_val) {
                $dhours = false;
                if( !empty($day_val->open) && !empty($day_val->close) ) $dhours = array( 'open'=>$day_val->open,'close'=>$day_val->close );
                if( isset($data[$day_val->day]) && $dhours ){
                    $data[$day_val->day]['static'] = $day_val->static;
                    $data[$day_val->day]['hours'][] = $dhours;
                    
                }else{
                    $data[$day_val->day] = array(
                        'static' => $day_val->static,
                        'hours'     => $dhours? array( $dhours ) : false,
                    );
                }
            }
        }elseif( false == $for_edit ){
            $working_days = Esb_Class_Date::week_days();
            foreach( $working_days as $day => $dayLbl ) {
                // open by default
                $data[$day] = array('static'=>'openAllDay');
            }
        }

        return $data;

    }

    public static function day_wkhours($post_id = 0, $day = ''){
        if( empty($post_id) ) return false;

        $post_obj = get_post($post_id);
        if( null == $post_obj || $post_obj->post_status == 'auto-draft' ){
            return false;
        } 

        $dateObj = new DateTime($day);
        if(!$dateObj){
            $dateObj = new DateTime('now');
        }

        $day_num = $dateObj->format("N"); // D for Mon - Sun

        global $wpdb;
        $_table = $wpdb->prefix . 'cth_wkhours';
        $working_hours = $wpdb->get_results( 
            $wpdb->prepare( 
                "
                SELECT * 
                FROM $_table
                WHERE post_id = %d AND day_num = $day_num 
                ORDER BY ID ASC
                ",
                $post_id
            )
        );
        $data = array();
        if( !empty($working_hours) ){
            foreach ($working_hours as $day_val) {
                $dhours = false;
                if( !empty($day_val->open) && !empty($day_val->close) ) $dhours = array( 'open'=>$day_val->open,'close'=>$day_val->close );
                if( isset($data['hours']) && $dhours ){
                    $data['hours'][] = $dhours;
                }else{
                    $data = array(
                        'static' => $day_val->static,
                        'hours'  => $dhours? array( $dhours ) : false,
                    );
                }
            }
        }
        return $data;
    }

    public static function parse_wkhours($post_ID = 0){

        $open_text = _x( 'Now Open', 'Open text', 'townhub-add-ons' );
        

        $return = array(
            'timezone' => 'UTC + 0',
            'status' => 'closed',
            'statusText' => _x( 'Now Closed', 'Close text', 'townhub-add-ons' ),
            'days_hours' => array()
        );

        if( !is_numeric($post_ID) || !$post_ID ) return $return;

        // check for eventdate
        $leventdate_datas = array();
        $leventdate = get_post_meta( $post_ID, ESB_META_PREFIX.'eventdate', true );
        if( $leventdate != '' ){
            $eventdates = explode("|", $leventdate);
            // $evdate_parts = array( 'sdate' => '', 'stime' => '', 'edate' => '', 'etime' => '' );
            if( count($eventdates) === 4 && $eventdates[0] != '' ){
                $leventdate_datas['for_event'] = true;
                $start_date = trim($eventdates[0] . ' ' . $eventdates[1]);
                $end_date = trim($eventdates[2] . ' ' . $eventdates[3]);
                $leventdate_datas['start_date'] = $start_date;
                $leventdate_datas['event_dates'] = array( 
                    'start_date' => $start_date, 
                    'end_date' => $end_date, 
                    'sdate' => $eventdates[0], 
                    'stime' => $eventdates[1], 
                    'edate' => $eventdates[2], 
                    'etime' => $eventdates[3] 
                );
                $leventdate_datas['statusText'] = Esb_Class_Date::i18n( $start_date , true );
                if( Esb_Class_Date::compare( $end_date, 'now', '<') ){
                    $leventdate_datas['status'] = 'closed';
                }elseif( Esb_Class_Date::compare( $start_date, 'now', '>') ){
                    $leventdate_datas['status'] = 'opening';
                    // $leventdate_datas['status'] = 'started';
                }else{
                    $leventdate_datas['status'] = 'started';
                    // $leventdate_datas['status'] = 'opening';
                }
                
            }
        }

        // check for repeat event dates
        $event_dates = townhub_addons_get_calendar_type_dates($post_ID);
        if( !empty($event_dates) ){
            // only check if there are date meta
            $dates_metas    = get_post_meta($post_ID, ESB_META_PREFIX . 'listing_dates_metas', true);

            $new_date_meta  = array();
            $old_date_meta  = array();
            $curr           = current_time('Ymd');
            foreach ($event_dates as $date) {
                $metas = array();
                $fdate = townhub_addons_format_cal_date($date);
                if (isset($dates_metas[$date])) {
                    if (isset($dates_metas[$date]['start_time'])) {
                        $metas['sdate'] = $fdate;
                        $metas['stime'] = $dates_metas[$date]['start_time'];
                        $metas['start_date'] =  $fdate . ' ' . $metas['stime'];
                    }
                    if (isset($dates_metas[$date]['end_date'])) {
                        $metas['end_date'] = $dates_metas[$date]['end_date'];
                    }
                    
                }
                // date has event times only

                if ( $curr <= $date ) {
                    $new_date_meta = $metas;
                    break;
                }else{
                    $old_date_meta = $metas;
                }
                // future date only
            }
            if( !empty($new_date_meta) ){
                $return['for_event'] = true;
                $return['event_dates'] = array( 
                    'start_date' => $new_date_meta['start_date'], 
                    'end_date' => $new_date_meta['end_date'], 
                    'sdate' => $new_date_meta['sdate'], 
                    'stime' => $new_date_meta['stime'], 
                    'edate' => $new_date_meta['end_date'], 
                    'etime' => '' 
                );
                $return['statusText'] = Esb_Class_Date::i18n( $new_date_meta['start_date'] , true );
                $return['status'] = 'opening';
                if( Esb_Class_Date::compare( $new_date_meta['end_date'], 'now', '<') ){
                    $return['status'] = 'closed';
                }elseif( Esb_Class_Date::compare( $new_date_meta['start_date'], 'now', '>') ){
                    $return['status'] = 'opening';
                }else{
                    $return['status'] = 'started';
                }

                return $return;
            }elseif( !empty($old_date_meta) ){
                $return['for_event'] = true;
                // $return['start_date'] = true;
                $return['event_dates'] = array( 
                    'start_date' => $old_date_meta['start_date'], 
                    'end_date' => $old_date_meta['end_date'], 
                    'sdate' => $old_date_meta['sdate'], 
                    'stime' => $old_date_meta['stime'], 
                    'edate' => $old_date_meta['end_date'], 
                    'etime' => '' 
                );
                $return['statusText'] = Esb_Class_Date::i18n( $old_date_meta['start_date'] , true );
                $return['status'] = 'closed';
                if( !empty($leventdate_datas) && Esb_Class_Date::compare( $old_date_meta['start_date'], $leventdate_datas['start_date'], '>=') ){
                    return $return;
                }
                
            }
        }

        // // check for eventdate
        // $leventdate = get_post_meta( $post_ID, ESB_META_PREFIX.'eventdate', true );
        // if( $leventdate != '' ){
        //     $eventdates = explode("|", $leventdate);
        //     // $evdate_parts = array( 'sdate' => '', 'stime' => '', 'edate' => '', 'etime' => '' );
        //     if( count($eventdates) === 4 && $eventdates[0] != '' ){
        //         $return['for_event'] = true;
        //         $start_date = trim($eventdates[0] . ' ' . $eventdates[1]);
        //         $end_date = trim($eventdates[2] . ' ' . $eventdates[3]);
        //         $return['event_dates'] = array( 
        //             'start_date' => $start_date, 
        //             'end_date' => $end_date, 
        //             'sdate' => $eventdates[0], 
        //             'stime' => $eventdates[1], 
        //             'edate' => $eventdates[2], 
        //             'etime' => $eventdates[3] 
        //         );
        //         $return['statusText'] = Esb_Class_Date::i18n( $start_date , true );
                
        //         if( Esb_Class_Date::compare( $start_date, 'now', $compare = '>=') ) $return['status'] = 'opening';

        //         return $return;
        //     }
        // }
        if( !empty($leventdate_datas) ){
            return array_merge($return, $leventdate_datas);
        } 

        // normal type
        $post_working_hours = self::wkhours($post_ID, false);

        if( !empty($post_working_hours ) ){
            // var_dump($post_working_hours);
            $working_days = Esb_Class_Date::week_days();
            $working_hours_arr = Esb_Class_Date::wkhours_select();
            $current_time_details = Esb_Class_Date::tz_details( $post_working_hours['timezone'] );
            // var_dump($current_time_details);
            $tz_offset = $current_time_details['tz_offset']/3600;
            if($tz_offset < 0){
                $return['timezone'] = __( 'UTC - ', 'townhub-add-ons' ) .(string)(-1*$tz_offset);
            }else{
                $return['timezone'] = __( 'UTC + ', 'townhub-add-ons' ) ."{$tz_offset}";
            }

            $curDy = $current_time_details['day']; //date('D');
            $prevDy = $current_time_details['prev_day'];

            foreach ($working_days as $day => $dayLbl) {
                if( !isset($post_working_hours[$day]) ) continue;
                $dayVals = $post_working_hours[$day];
                if( isset($dayVals['static']) ){
                    if( $dayVals['static'] == 'closeAllDay' ){
                        $return['days_hours'][$dayLbl] = array( __( 'Day Off', 'townhub-add-ons' ) );
                    }elseif($dayVals['static'] == 'openAllDay'){
                        $return['days_hours'][$dayLbl] = array(__( 'Open all day', 'townhub-add-ons' ));
                        if($day == $curDy){
                            $return['statusText'] = $open_text;
                            $return['status'] = 'opening';
                        }
                    }elseif( $dayVals['static'] == 'enterHours' && isset($dayVals['hours']) && !empty($dayVals['hours']) ){
                        $return['days_hours'][$dayLbl] = array();
                        $curHr = intval( $current_time_details['hour'] ); //date('His'); // 063000

                        foreach ($dayVals['hours'] as $hr) {
                            $ophrAfter = intval( str_replace(":", "", $hr['open']) );
                            $clhrAfter = intval( str_replace(":", "", $hr['close']) );
                            // check for prev day hour if current day closed
                            if($day == $prevDy){
                                if( $ophrAfter > $clhrAfter && $curHr < $clhrAfter ){
                                    $return['statusText'] = $open_text;
                                    $return['status'] = 'opening';
                                }
                            }
                            if($day == $curDy){
                                if( $ophrAfter <= $clhrAfter ){
                                    if( $curHr >= $ophrAfter && $curHr <= $clhrAfter ){
                                        $return['statusText'] = $open_text;
                                        $return['status'] = 'opening';
                                    }
                                }else{
                                    if( $curHr >= $ophrAfter ){
                                        $return['statusText'] = $open_text;
                                        $return['status'] = 'opening';
                                    }
                                }
                            }
                            // only check status for current day

                            $return['days_hours'][$dayLbl][] = '<span>' .(isset($working_hours_arr[$hr['open']])? $working_hours_arr[$hr['open']] : $hr['open'] ). ' - ' . (isset($working_hours_arr[$hr['close']])? $working_hours_arr[$hr['close']] : $hr['close'] ) .'</span>' ;

                        }

                        // check for prev day hour if current day closed
                        // if( $return['status'] == 'closed' ){
                        //     $prevDayVals = $post_working_hours[$prevDy];
                        //     if($prevDayVals['static'] == 'enterHours' && isset($prevDayVals['hours']) && count($prevDayVals['hours'])){
                        //         foreach ($prevDayVals['hours'] as $hr) {
                        //             $ophrAfter = floatval( str_replace(":", ".", $hr['open']) );
                        //             $clhrAfter = floatval( str_replace(":", ".", $hr['close']) );
                        //             if( $ophrAfter > $clhrAfter && $curHr < $clhrAfter ){
                        //                 $return['statusText'] = __( 'Now Open',  'townhub-add-ons' );
                        //                 $return['status'] = 'opening';
                        //             }
                        //         }
                        //     }
                        // }

                    }
                    // end if $dayVals['static']
                }
                // end if isset($dayVals['static'])
            } 
            // end foreach
            // set back to default timezone
            // date_default_timezone_set($default_timezone);

            // check for listing event
            // $levent_date = trim( get_post_meta( $post_ID, ESB_META_PREFIX.'levent_date', true ) .' '. get_post_meta( $post_ID, ESB_META_PREFIX.'levent_time', true ) );
            // $levent_end_date = trim( get_post_meta( $post_ID, ESB_META_PREFIX.'levent_end_date', true ) .' '. get_post_meta( $post_ID, ESB_META_PREFIX.'levent_end_time', true ) );
            // if( $levent_date != '' && $levent_date > $current_time_details['date'] ){
            //     $return['status'] = 'closed';
            //     $return['statusText'] = __( 'Event not start',  'townhub-add-ons' );
            // }
            // if( $levent_end_date != '' && $levent_end_date < $current_time_details['date'] ){
            //     $return['status'] = 'closed';
            //     $return['statusText'] = __( 'Event Ended',  'townhub-add-ons' );
            // }


        }
        // end if $post_working_hours

        return $return;

            
    }

    public static function update_bookmark_count($listing_id = 0, $decrease = false ){
        if(is_numeric($listing_id) && (int)$listing_id > 0){
            $bookmark_count = intval( get_post_meta($listing_id, ESB_META_PREFIX . 'bookmark_count', true) ) ;
            if( $decrease ){
                if( $bookmark_count > 1){
                    update_post_meta( $listing_id, ESB_META_PREFIX . 'bookmark_count', ($bookmark_count - 1) );
                }else{
                    update_post_meta( $listing_id, ESB_META_PREFIX . 'bookmark_count', 0 );
                }
            }else{
                update_post_meta( $listing_id, ESB_META_PREFIX . 'bookmark_count', ($bookmark_count + 1) );
            }
        }
    }
    public static function get_bookmark_count($listing_id = 0){
        return (int)get_post_meta($listing_id, ESB_META_PREFIX . 'bookmark_count', true);
    }

    public static function before_delete_post($postid = 0){
        $post_type = get_post_type($postid);
        if( $post_type === 'listing' ){
            self::delete_wkhours_data($postid);
            // delete listing will delete booking also
            self::delete_bookings($postid);
        }
    }
    public static function delete_bookings($postid = 0){
        if( !empty($postid) ){
            $bk_posts = get_posts( array(
                'fields'                => 'ids',
                'post_type'             => 'lbooking', 
                'orderby'               => 'date',
                'order'                 => 'DESC',
                'post_status'           => 'publish',
                'posts_per_page'        => -1, // no limit 
                'meta_query'            => array(
                    array(
                        'key'       => ESB_META_PREFIX.'listing_id',
                        'value'     => $postid,
                        'type'      => 'NUMERIC',
                    ),
                ),
            ) );
            if( !empty($bk_posts) ){
                foreach ($bk_posts as $bkid) {
                    wp_delete_post( $bkid, true );
                }
            }
        }
    }
}

new Esb_Class_Listing_CPT();



/**
 * Taxonomy meta box
 *
 * @since TownHub 1.0
 */
require_once ESB_ABSPATH . 'inc/cth_taxonomy_fields.php';
require_once ESB_ABSPATH . 'inc/listing_cat_metabox_fields.php';
require_once ESB_ABSPATH . 'inc/listing_feature_metabox_fields.php';


