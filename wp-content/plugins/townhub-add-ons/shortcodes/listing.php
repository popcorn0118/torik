<?php
/* add_ons_php */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


// redirect page for dashboard
function townhub_addons_dashboard_page_template_redirect()
{
    $dashboard_page_id = esb_addons_get_wpml_option('dashboard_page');
    $edit_page_id = esb_addons_get_wpml_option('edit_page');
    $submit_page_id = esb_addons_get_wpml_option('submit_page');
    $checkout_page_id = esb_addons_get_wpml_option('checkout_page');
    
    // if( is_page( $dashboard_page_id ) && ! is_user_logged_in() )
    // if( ($dashboard_page_id && is_page( $dashboard_page_id )) || ($checkout_page_id && is_page( $checkout_page_id )) )
    if( ($dashboard_page_id && is_page( $dashboard_page_id ))  )
    {
        if (! is_user_logged_in()) {
            wp_redirect( home_url( '/' ) );
            die;
        }
            
    }elseif($edit_page_id && is_page($edit_page_id)){
        $listing_id = get_query_var('listing_id');
        if( ($listing_id == '' || !is_numeric($listing_id)) || ! current_user_can( 'edit_post' , $listing_id ) ){
            wp_redirect( home_url( '/' ) );
            die;
        }
    }elseif($submit_page_id && is_page($submit_page_id)){
        
        if( Esb_Class_Membership::can_add() == false ){
            // wp_die(__( "You don't have permission to submit a listing. Please consider being a Listing Author or extend your membership subscription.<br>Contact us for more details.", 'townhub-add-ons' ));
            $free_submit_page = esb_addons_get_wpml_option('free_submit_page');
            if( $free_submit_page == 'default' ){
                $redirect_url = home_url( '/' );
            }else{
                $redirect_url = get_permalink( $free_submit_page );
            }
            wp_redirect( $redirect_url );
            die;
        }
    }
    // elseif(is_singular( 'listing' )){ // redirect to home page for expired listing
    //     global $post;
        
    //     wp_redirect( home_url( '/' ) );
    //     die;
    // }
}
add_action( 'template_redirect', 'townhub_addons_dashboard_page_template_redirect' );

add_action( 'wp_footer', function(){
    $dashboard_page_id = esb_addons_get_wpml_option('dashboard_page');
    if( $dashboard_page_id && is_page( $dashboard_page_id ) ){
        $dashboard = get_query_var('dashboard');

        if($dashboard == 'messages') townhub_addons_get_template_part('shortcodes/tmpls-reply-msg-modal');
        if($dashboard == 'invoices') townhub_addons_get_template_part('shortcodes/tmpls-view-invoice-modal');
        if($dashboard == 'ads') townhub_addons_get_template_part('shortcodes/tmpls-new-campaign-modal');
        if($dashboard == 'profile'){
            townhub_addons_get_template_part('shortcodes/tmpls-dashboard');
            ?>
            <script type="text/template" id="tmpl-imageslist">
                <?php townhub_addons_get_template_part('templates-inner/image');?>
            </script>
            <?php
        }


    }
    // if(is_singular('listing')){
    //     townhub_addons_get_template_part('shortcodes/tmpls-reply-msg-modal');
    // }
    if( !is_page( esb_addons_get_wpml_option('dashboard_page') ) && townhub_addons_get_option('admin_chat') == 'yes' && townhub_addons_get_option('show_fchat') == 'yes' ):
    ?>
        <div id="chat-app"></div>
    <?php 
        
    endif;
    townhub_addons_get_template_part('templates/tmpls');

    if( is_singular( 'listing' ) ){
        if( get_post_meta( get_queried_object_id() , ESB_META_PREFIX.'verified', true ) !== '1' ) townhub_addons_get_template_part('template-parts/listing-claim-modal');
        if( is_user_logged_in() || townhub_addons_get_option('report_must_login') != 'yes' ) townhub_addons_get_template_part('template-parts/listing-report-modal');
        // room inquiry
        townhub_addons_get_template_part('template-parts/room-inquiry-field');
    }
} );

/*
https://wordpress.stackexchange.com/questions/192360/current-user-can-edit-post-post-id-does-not-work-for-contributer-but-for
https://codex.wordpress.org/Function_Reference/map_meta_cap
https://developer.wordpress.org/reference/hooks/map_meta_cap/
https://wordpress.stackexchange.com/questions/108338/capabilities-and-custom-post-types
https://wordpress.stackexchange.com/questions/65418/admins-cant-edit-each-others-posts
*/
function townhub_addons_map_meta_cap( $caps, $cap, $user_id, $args ){
    if ( 'edit_post' == $cap ) {
        $post = get_post( $args[0] );
        $post_type = get_post_type_object( $post->post_type );
        $caps = array();
        if ( $user_id == $post->post_author )
            $caps[] = $post_type->cap->edit_posts;
        else
            $caps[] = $post_type->cap->edit_others_posts;
    }
    return $caps;
}
// add_filter( 'map_meta_cap', 'townhub_addons_map_meta_cap', 10, 4 );


// add_filter( 'map_meta_cap', 'townhub_addons_map_meta_cap_new', 10, 4 );

function townhub_addons_map_meta_cap_new( $caps, $cap, $user_id, $args ) {

    /* If editing, deleting, or reading a listing, get the post and post type object. */
    if ( 'edit_listing' == $cap || 'delete_listing' == $cap || 'read_listing' == $cap ) {
        $post = get_post( $args[0] );
        $post_type = get_post_type_object( $post->post_type );

        /* Set an empty array for the caps. */
        $caps = array();
    }

    /* If editing a listing, assign the required capability. */
    if ( 'edit_listing' == $cap ) {
        if ( $user_id == $post->post_author )
            $caps[] = $post_type->cap->edit_posts;
        else
            $caps[] = $post_type->cap->edit_others_posts;
    }

    /* If deleting a listing, assign the required capability. */
    elseif ( 'delete_listing' == $cap ) {
        if ( $user_id == $post->post_author )
            $caps[] = $post_type->cap->delete_posts;
        else
            $caps[] = $post_type->cap->delete_others_posts;
    }

    /* If reading a private listing, assign the required capability. */
    elseif ( 'read_listing' == $cap ) {

        if ( 'private' != $post->post_status )
            $caps[] = 'read';
        elseif ( $user_id == $post->post_author )
            $caps[] = 'read';
        else
            $caps[] = $post_type->cap->read_private_posts;
    }

    /* Return the capabilities required by the user. */
    return $caps;
}



function townhub_addons_maintenance_mode() {
    global $pagenow;
    // var_dump($pagenow);die;
    // var_dump(esb_addons_get_wpml_option('login_page'));
    // var_dump(is_page(esb_addons_get_wpml_option('login_page')));
    // die;
    $mode = townhub_addons_get_option('maintenance_mode');
    
    $demo_mode = isset($_GET['demo_mode'])? $_GET['demo_mode'] : '';
    if ( $pagenow !== 'wp-login.php' && ! current_user_can( 'manage_options' ) && ! is_admin() && ($mode == 'maintenance'||$mode=='coming_soon'||$demo_mode =='maintenance'||$demo_mode =='coming_soon') ) {
        // if( is_page(esb_addons_get_wpml_option('login_page')) ) return;

        // wp_redirect( home_url( ) );// redirect to home page first
        header( $_SERVER["SERVER_PROTOCOL"] . __( ' 503 Service Temporarily Unavailable', 'townhub-add-ons' ), true, 503 );
        header( 'Content-Type: text/html; charset=utf-8' );
        if($mode == 'coming_soon'||$demo_mode =='coming_soon'){
            townhub_addons_get_template_part('templates/coming_soon');
        }else{
            header( 'Retry-After: 3600' );
            townhub_addons_get_template_part('templates/maintenance');
        } 
        die();
    }

    // for view invoice details
    // if( $pagenow == 'index.php' && isset($_GET['thview']) && $_GET['thview'] === 'invoice' ){
    if( $pagenow == 'index.php' && isset($_GET['thview']) && $_GET['thview'] === 'invoice' ){
        $_wpnonce = isset($_GET['_wpnonce']) ? $_GET['_wpnonce'] : '';
        if( wp_verify_nonce( $_wpnonce, 'cth_view_invoice' ) ){
            header( 'Content-Type: text/html; charset=utf-8' );
            townhub_addons_get_template_part('templates/invoice');
            die();
        }
    }
    $current_user = wp_get_current_user();
    if( !is_admin() && townhub_addons_get_option('dis_log_reg_modal') != 'yes' && 0 == $current_user->ID ){
        add_action( 'wp_footer', 'townhub_addons_print_login_modal' );
    }
}

add_action( 'wp_loaded', 'townhub_addons_maintenance_mode' );

if(!function_exists('townhub_addons_login_logout_sc')){
    function townhub_addons_login_logout_sc($atts, $content = ''){

        extract(shortcode_atts(array(
               'show_register' => esc_html_x( 'no', 'Show register button: yes or no', 'townhub-add-ons' ),
               'show_register_when_logged_in'=>'no',
               'style'=>'two',
               'extraclass'=>''
         ), $atts));

        

        ob_start();
        // check if the user already login - the correct way is using is_user_logged_in()
        $current_user = wp_get_current_user();
        if ( 0 == $current_user->ID ) {
            // Not logged in.
            $logBtnAttrs = townhub_addons_get_login_button_attrs( '', 'current' );
            ?>
            <a href="<?php echo esc_url( $logBtnAttrs['url'] );?>" class="show-reg-form avatar-img <?php echo esc_attr( $logBtnAttrs['class'] );?>"><?php _e('<i class="fal fa-user"></i>Sign In','townhub-add-ons');?></a>
        <?php
        } else {
            // Logged in.
            //$dashboard_page_id = esb_addons_get_wpml_option('dashboard_page');
            townhub_addons_get_template_part('template-parts/shortcodes/user', 'menu', array('style' => $style, 'current_user' => $current_user));
        }
        $output = ob_get_clean();

        // if ( townhub_addons_get_option('dis_log_reg_modal') != 'yes' && 0 == $current_user->ID ){
        //     add_action( 'wp_footer', 'townhub_addons_print_login_modal' );
        // }

        // add_action( 'wp_footer', 'townhub_addons_single_modal' );
        
        return $output;

    }

    add_shortcode( 'townhub_login', 'townhub_addons_login_logout_sc' ); 
}

function townhub_addons_print_login_modal(){

    if( false == Esb_Class_User::custom_log_reg() ){
        townhub_addons_get_template_part('template-parts/shortcodes/login-modal');
    }
    
}


if(!function_exists('townhub_addons_submit_button_sc')){
    function townhub_addons_submit_button_sc($atts, $content = ''){
        if( townhub_addons_get_option('always_show_submit') != 'yes' ) return;
        ob_start();
        townhub_addons_get_template_part('template-parts/shortcodes/add-list');
        $output = ob_get_clean();
        return $output;

    }

    add_shortcode( 'townhub_submit_button', 'townhub_addons_submit_button_sc' ); 
}


// submit page shortcode
if(!function_exists('townhub_addons_listing_submit_page_sc')){
    function townhub_addons_listing_submit_page_sc($atts, $content = ''){
        ob_start();

        townhub_addons_get_template_part('templates/submit');
        
        $output = ob_get_clean();

        return $output;
    }

    add_shortcode( 'listing_submit_page', 'townhub_addons_listing_submit_page_sc' ); 
}

// edit page shortcode
if(!function_exists('townhub_addons_listing_edit_page_sc')){
    function townhub_addons_listing_edit_page_sc($atts, $content = ''){
        ob_start();

        townhub_addons_get_template_part('templates/edit');
        
        $output = ob_get_clean();

        return $output;
    }

    add_shortcode( 'listing_edit_page', 'townhub_addons_listing_edit_page_sc' ); 
}


// dashboard page shortcode
if(!function_exists('townhub_addons_listing_dashboard_page_sc')){
    function townhub_addons_listing_dashboard_page_sc($atts, $content = ''){
        ob_start();

        townhub_addons_get_template_part('templates/dashboard');
        
        $output = ob_get_clean();

        return $output;
    }

    add_shortcode( 'listing_dashboard_page', 'townhub_addons_listing_dashboard_page_sc' ); 
}

// dashboard page shortcode
if(!function_exists('townhub_addons_listing_checkout_page_sc')){
    function townhub_addons_listing_checkout_page_sc($atts, $content = ''){
        ob_start();
        $classname = "Esb_Class_Checkout";
        if(isset($_POST['esb-checkout-type']) && $_POST['esb-checkout-type'] != ''){ 
            $classname = "Esb_Class_Checkout_".ucfirst($_POST['esb-checkout-type']);
        }else{
            $classname =  "Esb_Class_Checkout_Listing";
        }
        $checkout = new $classname;
        $checkout->render();
        $output = ob_get_clean();

        return $output;
    }

    add_shortcode( 'listing_checkout_page', 'townhub_addons_listing_checkout_page_sc' ); 
}
add_action( 'wp_footer', 'townhub_addons_single_modal' );
function townhub_addons_single_modal(){
    if( !is_singular( 'listing' ) ) return;
    ?>
    <!--ajax-modal-container-->
    <div class="ajax-modal-overlay"></div>
    <div class="ajax-modal-container">
        <!--ajax-modal -->
        <div class='ajax-loader'>
            <div class='ajax-loader-cirle'></div>
        </div>
        <div id="ajax-modal" class="fl-wrap ajax-modal-wrap"></div><!--#ajax-modal end -->
    </div>
    <!--ajax-modal-container end -->
    <?php
}
// add new field to submit form
function townhub_addons_submit_addfields_callback($listing_id = 0, $is_edit = false){
    $content_addfields = townhub_addons_get_option('content_addwidgets');
    if(empty($content_addfields) || !is_array($content_addfields)) return;
    foreach ($content_addfields as $widget) {
        ?>
        <!-- profile-edit-container--> 
        <div class="profile-edit-container add-list-container listing-additional-content-widget-<?php echo sanitize_title( $widget['widget_title'] );?>">
            <div class="profile-edit-header fl-wrap">
                <h4><?php echo $widget['widget_title'];?></h4>
            </div>
            <div class="custom-form">
                <div class="listing-additional-fields">
                <?php 
                $name_prefix = 'add_fields_';
                $add_fields_arr = array();
                if(!empty($widget['fields'])){
                    foreach ((array)$widget['fields'] as $key => $field) {
                        $add_fields_arr[] = array(
                            'type' => $field['field_type'],
                            'name' => $field['field_name'],
                            'label' => $field['field_label'],
                            'value' => '',
                            'lvalue'    => ($is_edit? get_post_meta( $listing_id, ESB_META_PREFIX.$name_prefix.$field['field_name'], true ) : ''),
                        );
                    }
                }
                foreach ($add_fields_arr as $addfield) {
                    townhub_addons_get_template_part('templates-inner/add-field-frontend',false,array('name_prefix'=> $name_prefix,'addfield'=>$addfield));
                }
                ?>
                </div>
            </div>
        </div>
        <!-- profile-edit-container end-->  
    <?php
    }
    ?>
    
    <?php
}
add_action( 'townhub-addons-submit-addfields', 'townhub_addons_submit_addfields_callback', 10, 2 );

function townhub_addons_submit_widget_addfields_callback($listing_id = 0, $is_edit = false){
    $content_addfields = townhub_addons_get_option('widget_addwidgets');
    if(empty($content_addfields) || !is_array($content_addfields)) return;
    foreach ($content_addfields as $widget) {
        ?>
        <!-- profile-edit-container--> 
        <div class="profile-edit-container add-list-container listing-additional-widget-widget-<?php echo sanitize_title( $widget['widget_title'] );?>">
            <div class="profile-edit-header fl-wrap">
                <h4><?php echo $widget['widget_title'];?></h4>
            </div>
            <div class="custom-form">
                <div class="listing-additional-fields">
                <?php 
                $name_prefix = 'add_fields_';
                $add_fields_arr = array();
                if(!empty($widget['fields'])){
                    foreach ((array)$widget['fields'] as $key => $field) {
                        $add_fields_arr[] = array(
                            'type' => $field['field_type'],
                            'name' => $field['field_name'],
                            'label' => $field['field_label'],
                            'value' => '',
                            'lvalue'    => ($is_edit? get_post_meta( $listing_id, ESB_META_PREFIX.$name_prefix.$field['field_name'], true ) : ''),
                        );
                    }
                }
                foreach ($add_fields_arr as $addfield) {
                    townhub_addons_get_template_part('templates-inner/add-field-frontend',false,array('name_prefix'=> $name_prefix,'addfield'=>$addfield));
                }
                ?>
                </div>
            </div>
        </div>
        <!-- profile-edit-container end-->  
    <?php
    }
    ?>
    
    <?php
}
add_action( 'townhub-addons-submit-widget-addfields', 'townhub_addons_submit_widget_addfields_callback', 10, 2 );



// for saving custom fields
function townhub_addons_save_addfields_callback($listing_id){
    $content_addfields = townhub_addons_get_option('content_addwidgets');
    if(empty($content_addfields) || !is_array($content_addfields)) return;
    foreach ($content_addfields as $widget) {
        $name_prefix = 'add_fields_';
        $add_fields_arr = array();
        if(!empty($widget['fields'])){
            foreach ((array)$widget['fields'] as $key => $field) {
                $add_fields_arr[] = $name_prefix.$field['field_name'];
            }
        }

        foreach ($add_fields_arr as $addfield) {
            if(isset($_POST[$addfield])){
                $old_val = get_post_meta( $listing_id, ESB_META_PREFIX.$addfield, true );
                if($old_val != $_POST[$addfield]) update_post_meta( $listing_id, ESB_META_PREFIX.$addfield, $_POST[$addfield] );
            }
            
        }

    }
}
add_action( 'townhub-addons-save-addfields', 'townhub_addons_save_addfields_callback', 10, 1 );

function townhub_addons_save_addfields_widget_callback($listing_id){
    $content_addfields = townhub_addons_get_option('widget_addwidgets');
    if(empty($content_addfields) || !is_array($content_addfields)) return;
    foreach ($content_addfields as $widget) {
        $name_prefix = 'add_fields_';
        $add_fields_arr = array();
        if(!empty($widget['fields'])){
            foreach ((array)$widget['fields'] as $key => $field) {
                $add_fields_arr[] = $name_prefix.$field['field_name'];
            }
        }

        foreach ($add_fields_arr as $addfield) {
            if(isset($_POST[$addfield])){
                $old_val = get_post_meta( $listing_id, ESB_META_PREFIX.$addfield, true );
                if($old_val != $_POST[$addfield]) update_post_meta( $listing_id, ESB_META_PREFIX.$addfield, $_POST[$addfield] );
            }
            
        }

    }
}
add_action( 'townhub-addons-save-addfields', 'townhub_addons_save_addfields_widget_callback', 10, 1 );

// for display custom content fields
function townhub_addons_single_content_order_callback($listing_id = 0, $order = 0){
    $content_addfields = townhub_addons_get_option('content_addwidgets');
    if(empty($content_addfields) || !is_array($content_addfields)) return;
    foreach ($content_addfields as $widget) {
        $widget_position = isset($widget['widget_position']) ? $widget['widget_position'] : 0;
        $name_prefix = 'add_fields_';
        $add_fields_arr = array();
        if(!empty($widget['fields'])){
            foreach ((array)$widget['fields'] as $key => $field) {
                $add_fields_arr[] = array(
                    'type' => $field['field_type'],
                    'name' => $name_prefix.$field['field_name'],
                    'label' => $field['field_label'],
                );
                // $add_fields_arr[] = $name_prefix.$field['field_name'];
            }
        }

        
        if($order == $widget_position):
        ?>
        <div class="list-single-main-item fl-wrap" id="sec_addfield_<?php echo esc_attr( $order );?>">
            <?php if(!empty($widget['widget_title'])): ?>
            <div class="list-single-main-item-title fl-wrap">
                <h3><?php echo $widget['widget_title']; ?></h3>
            </div>
            <?php endif; ?>
            <!-- gallery-items   -->
            <div class="content_addwidgets-wrap">
                <?php 
                foreach ($add_fields_arr as $addfield) {
                    echo '<div class="content-add-field-label add-field-'.$addfield['type'].'">'.$addfield['label'].'</div>';
                    echo do_shortcode(get_post_meta( $listing_id, ESB_META_PREFIX.$addfield['name'], true ));
                    
                }
                ?>
            </div>
            <!-- end gallery items -->                                 
        </div>
        <!-- list-single-main-item end --> 
        <?php
        endif; // end check correct order
    }
}
add_action( 'townhub-addons-single-content-order', 'townhub_addons_single_content_order_callback', 10, 2 );

// for display custom content fields
function townhub_addons_single_widget_order_callback($listing_id = 0, $order = 0){
    $content_addfields = townhub_addons_get_option('widget_addwidgets');
    if(empty($content_addfields) || !is_array($content_addfields)) return;
    foreach ($content_addfields as $widget) {
        $widget_position = isset($widget['widget_position']) ? $widget['widget_position'] : 0;
        $name_prefix = 'add_fields_';
        $add_fields_arr = array();
        if(!empty($widget['fields'])){
            foreach ((array)$widget['fields'] as $key => $field) {
                $add_fields_arr[] = array(
                    'type' => $field['field_type'],
                    'name' => $name_prefix.$field['field_name'],
                    'label' => $field['field_label'],
                );

                // $add_fields_arr[] = $name_prefix.$field['field_name'];
            }
        }

        
        if($order == $widget_position):
        ?>
        <!--box-widget-item -->
        <div class="box-widget-item fl-wrap" id="widget_addfield_<?php echo esc_attr( $order );?>">
            <?php if(!empty($widget['widget_title'])): ?>
            <div class="box-widget-item-header">
                <h3><?php echo $widget['widget_title']; ?></h3>
            </div>
            <?php endif; ?>
            <div class="box-widget additional-fields">
                <div class="box-widget-content">
                    <?php 
                    foreach ($add_fields_arr as $addfield) {
                        echo '<div class="widget-add-field-label add-field-'.$addfield['type'].'">'.$addfield['label'].'</div>';
                        echo do_shortcode(get_post_meta( $listing_id, ESB_META_PREFIX.$addfield['name'], true ));
                    }
                    ?>
                </div>
            </div>
        </div>
        <!--box-widget-item end -->  
        <?php
        endif; // end check correct order
    }
}
add_action( 'townhub-addons-single-widget-order', 'townhub_addons_single_widget_order_callback', 10, 2 );



// add_action('wp_ajax_nopriv_townhub_addons_cats_subcats', 'townhub_addons_cats_subcats_callback');
// add_action('wp_ajax_townhub_addons_cats_subcats', 'townhub_addons_cats_subcats_callback');

function townhub_addons_cats_subcats_callback() {
    $json = array(
        'success' => false,
        'data' => array(
            // 'POST'=>$_POST,
        )
    );
    // wp_send_json($json );

    $nonce = $_POST['_nonce'];
    
    if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
        $json['data'] = esc_html__( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ;
        wp_send_json($json );
    }

    if(isset($_POST['cats'])) $listing_cats = $_POST['cats'];

    if(!is_array($listing_cats)) {
        $json['data'] = esc_html__( 'Invalid listing category list - it must be array [50,51]', 'townhub-add-ons' ) ;
        wp_send_json($json );
    }

    $cats_subcats = array();

    foreach ($listing_cats as $listing_cat) {
        
        if(!is_numeric($listing_cat)) {
            $json['data'] = esc_html__( 'Invalid listing category', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }

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
    // end foreach 
    $json['success'] = true;
    $json['data']['subcats'] = $cats_subcats;

    wp_send_json($json );

}

// Currency list shortcode
if(!function_exists('townhub_addons_currencies_switcher_sc')){
    function townhub_addons_currencies_switcher_sc($atts, $content = '') {
        ob_start();
        $curr_attrs = townhub_addons_get_currency_attrs();
        $currencies = townhub_addons_get_option('currencies');
        ?>
        <div class="currency-wrap">
            <div class="show-currency-tooltip"><i class="currency-symbol"><?php echo $curr_attrs['symbol']; ?></i><span><?php echo $curr_attrs['currency']; ?><i class="fa fa-caret-down"></i></span></div>
            <ul class="currency-tooltip currency-switcher">
                <?php 
                if(is_array($currencies) && !empty($currencies)){
                    $base_curr = townhub_addons_get_base_currency();
                    $currencies = array_merge($currencies, array( $base_curr ) );
                    foreach ($currencies as $key => $val) { 
                        if(is_array($val) && isset($val['currency']) && $val['currency'] !== $curr_attrs['currency'] ) {
                        ?>
                        <li><a class="currency-item" href="<?php echo add_query_arg( 'currency', $val['currency'] ); ?>"><i class="currency-symbol"><?php echo $val['symbol'] ?></i><?php echo $val['currency'] ?></a></li>
                        <?php
                        }
                    }
                }
                ?>
                
            </ul>
        </div>
        <?php
        $output = ob_get_clean();
        return $output;
    }
    add_shortcode( 'currencies_switcher', 'townhub_addons_currencies_switcher_sc' ); 
}
// search header top shortcode
if(!function_exists('townhub_search_top_sc')){
    function townhub_search_top_sc($atts, $content = ''){
        ob_start();
        $ltid = false;
        if( isset($_GET['ltype']) && !empty($_GET['ltype']) ) $ltid = $_GET['ltype'];
        $op_text = esc_attr__( 'Search', 'townhub-add-ons' ); 
        $cl_text = esc_attr__( 'Close', 'townhub-add-ons' ); 
        ?>
        <!-- header-search_btn-->         
        <div class="header-search_btn show-search-button" data-optext="<?php echo $op_text; ?>" data-cltext="<?php echo $cl_text; ?>"><i class="fal fa-search"></i><span><?php _e( 'Search', 'townhub-add-ons' ); ?></span></div>
        <!-- header-search_btn end-->

        <!-- header-search_container -->                     
        <div class="header-search_container header-search vis-search">
            <div class="container small-container">
                <div class="header-search-input-wrap fl-wrap">
                    <form role="search" method="get" action="<?php echo esc_url(home_url( '/' ) ); ?>" class="list-search-header-form list-search-form-js">
                        <?php 
                            echo townhub_addons_azp_parser_listing( $ltid , 'filter_header');
                        ?> 
                        <?php if( !empty($ltid) && get_post_meta( $ltid, ESB_META_PREFIX.'filter_by_type', true ) ) echo '<input type="hidden" name="ltype" value="'.$ltid.'">'; ?>   
                    </form> 
                </div>
                <div class="header-search_close color-bg"><i class="fal fa-long-arrow-up"></i></div>
            </div>
        </div>
        <!-- header-search_container  end --> 
        <?php
        $output = ob_get_clean();

        
        return $output;

    }

    add_shortcode( 'townhub_search_top', 'townhub_search_top_sc' );  
}

//header wishlist shortcode
if(!function_exists('townhub_addons_header_wishlist_sc')){
    function townhub_addons_header_wishlist_sc($atts, $content = ''){
        ob_start();
        townhub_addons_get_template_part('template-parts/shortcodes/wishlist');
        $output = ob_get_clean();
        return $output;
    }

    add_shortcode( 'townhub_wishlist', 'townhub_addons_header_wishlist_sc' ); 
}

function townhub_shortcode_listings_callback( $atts, $content = "" ) {
        
    extract(shortcode_atts(array(
       'class'              =>'',
       'orderby'            =>'date',
       'order'              =>'DESC',
       'posts_per_page'     => 5,
       'list_id'            => '',
    ), $atts));

    $return = '';

    $post_args = array(
        'post_type'         => 'listing',  
        'orderby'           => $orderby,
        'order'             => $order, 
        'posts_per_page'    => $posts_per_page,
    );
    $posts_query = new \WP_Query($post_args);

    ob_start();
    if($posts_query->have_posts()) :
    ?>
    
    <!--widget-posts-->
    <div class="widget-posts fl-wrap">
        <ul class="no-list-style">
        <?php 
        while($posts_query->have_posts()) : $posts_query->the_post(); 
            $address = get_post_meta( get_the_ID(), ESB_META_PREFIX.'address', true );
            $latitude = get_post_meta( get_the_ID(), ESB_META_PREFIX.'latitude', true );
            $longitude = get_post_meta( get_the_ID(), ESB_META_PREFIX.'longitude', true );

            ?>
            <li class="dis-flex">
                <?php if(has_post_thumbnail()): ?>
                <div class="widget-posts-img">
                    <a href="<?php the_permalink( ); ?>">
                        <?php the_post_thumbnail( 'townhub-recent' ); ?>
                    </a>  
                </div>
                <?php endif; ?>
                <div class="widget-posts-descr">
                    <h4><a href="<?php the_permalink( ); ?>"><?php the_title(); ?></a></h4>
                    <?php
                    if($address != ''): ?>
                    <div class="geodir-category-location fl-wrap"><a href="https://www.google.com/maps/search/?api=1&query=<?php echo $latitude.','.$longitude;?>" target="_blank"><i class="fas fa-map-marker-alt"></i><?php echo $address;?></a></div>
                    <?php endif;?>
                    <?php 
                    $cats = get_the_terms(get_the_ID(), 'listing_cat');
                    if ( $cats && ! is_wp_error( $cats ) ){ ?>
                        <div class="widget-posts-descr-link">
                            <?php 
                            foreach( $cats as $key => $cat){
                                
                                echo sprintf( '<a href="%1$s" class="widget-post-cat">%2$s</a> ',
                                    townhub_addons_get_term_link( $cat->term_id, 'listing_cat' ),
                                    esc_html( $cat->name )
                                );
                            }
                            ?>
                        </div>
                    <?php }  ?>
                    <?php 
                    $rating = townhub_addons_get_average_ratings(get_the_ID());    ?>
                    <?php if( $rating != false && !empty($rating['sum']) ): ?>
                        <div class="widget-posts-descr-score"><?php echo $rating['sum']; ?></div>
                    <?php endif; ?> 


                </div>
            </li>
            <?php
        endwhile;
        ?> 
        </ul>
    </div>
    <!-- widget-posts end-->
    <?php  
    endif; // check have posts
    return ob_get_clean();
        
}
    
add_shortcode( 'listings', 'townhub_shortcode_listings_callback' ); //Mailchimp

function townhub_shortcode_bookmark_btn_callback($atts, $content = ""){
    ob_start();
    if(!is_user_logged_in()): 
        $logBtnAttrs = townhub_addons_get_login_button_attrs( 'savelist', 'current' );
    ?>
    <a href="<?php echo esc_url( $logBtnAttrs['url'] );?>" class="scroll-nav-wrapper-opt-btn scroll-nav-bookmark-btn <?php echo esc_attr( $logBtnAttrs['class'] );?>" data-message="<?php esc_attr_e( 'Logging in first to bookmark this listing.', 'townhub-add-ons' ); ?>"><i class="fal fa-heart"></i><?php esc_html_e( ' Save ', 'townhub-add-ons' ); ?></a>
    <?php elseif( townhub_addons_already_bookmarked( get_the_ID() ) ): ?>
    <a href="javascript:void(0);" class="scroll-nav-wrapper-opt-btn scroll-nav-bookmark-btn" data-id="<?php the_ID(); ?>"><i class="fas fa-heart"></i><?php esc_html_e( ' Saved ', 'townhub-add-ons' ); ?></a>
    <?php else: ?>
    <a href="#" class="scroll-nav-wrapper-opt-btn scroll-nav-bookmark-btn bookmark-listing-btn" data-id="<?php the_ID(); ?>"><i class="fal fa-heart"></i><?php esc_html_e( ' Save ', 'townhub-add-ons' ); ?></a>
    <?php endif;
    return ob_get_clean();
}
add_shortcode( 'bookmark_btn', 'townhub_shortcode_bookmark_btn_callback' ); 

function townhub_shortcode_share_btn_callback($atts, $content = ""){
    ob_start();
    $share_names = townhub_addons_get_option('widgets_share_names','facebook, pinterest, googleplus, twitter, linkedin');
    if($share_names !=''):
    ?>
    <div class="lhead-share-wrap lshare-shortcode">
        <a href="#" class="scroll-nav-wrapper-opt-btn showshare"><i class="fas fa-share"></i><?php _e( 'Share', 'townhub-add-ons' ); ?></a>
        <div class="share-holder hid-share">
            <div class="share-container isShare" data-share="<?php echo esc_attr( trim($share_names, ", \t\n\r\0\x0B") ); ?>"></div>
        </div>
    </div>
    <?php
    endif;
    return ob_get_clean();
}
add_shortcode( 'share_btn', 'townhub_shortcode_share_btn_callback' ); 

function townhub_shortcode_add_to_cal_callback($atts, $content = ""){
    ob_start();
    $post_id = get_the_ID();
    $working_hours = Esb_Class_Listing_CPT::parse_wkhours( $post_id ); 
    if( !empty($working_hours) && isset($working_hours['for_event']) && $working_hours['for_event'] ):
        $timezone = get_post_meta( $post_id, ESB_META_PREFIX."wkh_tz", true );
    ?>
    <!-- Button code -->
    <div title="Add to Calendar" class="addeventatc laddtocal-shortcode">
        <?php _ex( 'Add to Calendar', 'Add to calendar', 'townhub-add-ons' ); ?>
        <span class="start"><?php echo $working_hours['event_dates']['start_date']; ?></span>
        <span class="end"><?php echo $working_hours['event_dates']['end_date']; ?></span>
        <?php if( !empty($timezone) ): ?><span class="timezone"><?php echo $timezone ?></span><?php endif; ?>
        <span class="title"><?php echo get_the_title( $post_id );?></span>
        <span class="description"><?php echo get_the_excerpt( $post_id ); ?></span>
        <span class="location"><?php echo get_post_meta( $post_id, ESB_META_PREFIX."address", true ); ?></span>
    </div>
    <?php
    endif;
    return ob_get_clean();
}
add_shortcode( 'add_to_cal', 'townhub_shortcode_add_to_cal_callback' ); 
function townhub_shortcode_cth_lreviews_callback($atts, $content = ""){
    ob_start();
    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) :
        comments_template();
    endif;
    return ob_get_clean();
}
add_shortcode( 'cth_lreviews', 'townhub_shortcode_cth_lreviews_callback' ); 

function townhub_addons_single_listing_sc($atts, $content = ''){
    extract(shortcode_atts(array(
        'id'=>'',
     ), $atts));
    ob_start();
    if( !empty($id) ){
        global $post;
        $post = get_post( $id );
        if( $post ){
            setup_postdata( $post );

            $listing_type_ID = get_post_meta( get_the_ID(), ESB_META_PREFIX.'listing_type_id', true );
            $listing_type_ID = apply_filters( 'wpml_object_id', $listing_type_ID, 'listing_type', true );
           // set view count
            Esb_Class_LStats::set_stats(get_the_ID());
            $lcontent = townhub_addons_azp_parser_listing( $listing_type_ID , 'single', get_the_ID() );
            
            // $lcontent = apply_filters( 'the_content', $lcontent );
            $lcontent = apply_filters( 'azp_single_content', $lcontent );
            echo $lcontent;


            wp_reset_postdata();
        }
    }
    $output = ob_get_clean();
    return $output;

}

add_shortcode( 'townhub_single_listing', 'townhub_addons_single_listing_sc' ); 
