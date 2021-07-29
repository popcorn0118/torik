<?php
/* add_ons_php */
// modify users columns
function townhub_addons_users_columns_head($columns)
{
    $columns['membership'] = __('Membership Plan', 'townhub-add-ons');
    return $columns;
}
// add_filter( 'manage_users_columns', 'townhub_addons_users_columns_head' );

function townhub_addons_users_columns_content($val, $column_name, $user_id)
{
    switch ($column_name) {
        case 'membership':
            $val = implode("<br>", (array) get_user_meta($user_id, ESB_META_PREFIX . 'subscriptions', true));
            break;

        default:
    }
    return $val;
}
// add_filter( 'manage_users_custom_column', 'townhub_addons_users_columns_content', 10, 3 );

// create stripe plan action

// Apply filter - to use custom avatar
add_filter('get_avatar', 'townhub_addons_custom_avatar', 1, 5);

function townhub_addons_custom_avatar($avatar, $id_or_email, $size, $default, $alt)
{
    $user = false;

    if (is_numeric($id_or_email)) {

        $id   = (int) $id_or_email;
        $user = get_user_by('id', $id);

    } elseif (is_object($id_or_email)) {

        if (!empty($id_or_email->user_id)) {
            $id   = (int) $id_or_email->user_id;
            $user = get_user_by('id', $id);
        }

    } else {
        $user = get_user_by('email', $id_or_email);
    }

    if ($user && is_object($user)) {
        if (function_exists('bp_core_fetch_avatar')) {
            $avatar = bp_core_fetch_avatar(array('item_id' => $user->ID, 'type' => 'full'));
        } else {
            $custom_avatar = get_user_meta($user->ID, ESB_META_PREFIX . 'custom_avatar', true);
            if (is_array($custom_avatar) && count($custom_avatar)) {
                $custom_ava_id = reset($custom_avatar);
                if (!is_numeric($custom_ava_id)) {
                    $custom_ava_id = key($custom_avatar);
                }

                $avatar = wp_get_attachment_url($custom_ava_id);

                // $avatar = wp_get_attachment_url( reset($custom_avatar) );
                $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
            }elseif( townhub_addons_get_option('off_avatar') == 'yes' ){
                $avatar = ESB_DIR_URL . 'assets/images/avatar.jpg';
                $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
            }
        }
    }

    return $avatar;
}

add_filter('pre_get_avatar_data', function ($args, $id_or_email) {
    $user = false;

    if (is_numeric($id_or_email)) {

        $id   = (int) $id_or_email;
        $user = get_user_by('id', $id);

    } elseif (is_object($id_or_email)) {

        if (!empty($id_or_email->user_id)) {
            $id   = (int) $id_or_email->user_id;
            $user = get_user_by('id', $id);
        }

    } else {
        $user = get_user_by('email', $id_or_email);
    }
    if ($user && is_object($user)) {

        $custom_avatar = get_user_meta($user->ID, ESB_META_PREFIX . 'custom_avatar', true);
        if (is_array($custom_avatar) && count($custom_avatar)) {
            $custom_ava_id = reset($custom_avatar);
            if (!is_numeric($custom_ava_id)) {
                $custom_ava_id = key($custom_avatar);
            }

            $args['url'] = wp_get_attachment_url($custom_ava_id);

        }elseif( townhub_addons_get_option('off_avatar') == 'yes' ){
            $args['url'] = ESB_DIR_URL . 'assets/images/avatar.jpg';
        }
    }
    return $args;
}, 10, 2);

add_filter('get_avatar_url', function ($url, $id_or_email) {
    $user = false;

    if (is_numeric($id_or_email)) {

        $id   = (int) $id_or_email;
        $user = get_user_by('id', $id);

    } elseif (is_object($id_or_email)) {

        if (!empty($id_or_email->user_id)) {
            $id   = (int) $id_or_email->user_id;
            $user = get_user_by('id', $id);
        }

    } else {
        $user = get_user_by('email', $id_or_email);
    }
    if ($user && is_object($user)) {

        $custom_avatar = get_user_meta($user->ID, ESB_META_PREFIX . 'custom_avatar', true);
        if (is_array($custom_avatar) && count($custom_avatar)) {
            $custom_ava_id = reset($custom_avatar);
            if (!is_numeric($custom_ava_id)) {
                $custom_ava_id = key($custom_avatar);
            }

            $url = wp_get_attachment_url($custom_ava_id);

        }elseif( townhub_addons_get_option('off_avatar') == 'yes' ){
            $url = ESB_DIR_URL . 'assets/images/avatar.jpg';
        }
    }
    return $url;
}, 10, 2);


// for bookmarks listing
add_action('wp_ajax_nopriv_townhub_addons_bookmarks_listing', 'townhub_addons_bookmarks_listing_callback');
add_action('wp_ajax_townhub_addons_bookmarks_listing', 'townhub_addons_bookmarks_listing_callback');

function townhub_addons_bookmarks_listing_callback()
{
    $json = array(
        'success' => false,
        'data'    => array(
            // 'POST'=>$_POST,
        ),
        'debug'     => false
    );

    Esb_Class_Ajax_Handler::verify_nonce('townhub-add-ons');

    $listing_post = get_post($_POST['listing']);

    if (empty($listing_post)) {
        $json['data']['error'] = esc_html__('Invalid listing', 'townhub-add-ons');
        wp_send_json($json);
    }

    if (!is_user_logged_in()) {
        $json['data']['error'] = esc_html__('Not logged in user', 'townhub-add-ons');
        wp_send_json($json);
    }

    if (townhub_addons_already_bookmarked($listing_post->ID)) {
        $json['success'] = true;
        if( isset($_POST['for_map']) &&  $_POST['for_map'] ){
            $json['data']['icon'] = __('<i class="fas fa-heart"></i>', 'townhub-add-ons');
        }else{
            $json['data']['icon'] = __('<i class="fas fa-heart"></i> Saved', 'townhub-add-ons');
        }
        // $json['data']['error'] = esc_html__('User had already bookmarked', 'townhub-add-ons');
        wp_send_json($json);
    }

    $json['success'] = true;

    // increase listing bookmarks
    Esb_Class_Listing_CPT::update_bookmark_count($listing_post->ID);
    //update user bookmarks array
    $user_id = get_current_user_id();

    // send notification to listing author
    if (townhub_addons_get_option('db_hide_bookmarks') != 'yes') {
        Esb_Class_Dashboard::add_notification($listing_post->post_author, array(
            'type'      => 'new_bookmark',
            'entity_id' => $listing_post->ID,
            'actor_id'  => $user_id,
        ));

    }

    

    $listing_bookmarks = get_user_meta($user_id, ESB_META_PREFIX . 'listing_bookmarks', true);
    if ( !empty($listing_bookmarks) && is_array($listing_bookmarks) ) {
        // if (array_search($listing_post->ID, $listing_bookmarks) === false) {
        //     $listing_bookmarks[] = $listing_post->ID;
        // } else {
        //     $json['data']['error'] = esc_html__('User had already bookmarked', 'townhub-add-ons');
        // }
        $listing_bookmarks[] = $listing_post->ID;
    } else {
        $listing_bookmarks = array($listing_post->ID);
    }

    update_user_meta($user_id, ESB_META_PREFIX . 'listing_bookmarks', $listing_bookmarks);

    // send notification to current user
    if (townhub_addons_get_option('db_hide_bookmarks') != 'yes') {
        Esb_Class_Dashboard::add_notification($user_id, array(
            'type'      => 'bookmarked',
            'entity_id' => $listing_post->ID,
            'actor_id'  => $user_id,
        ));
    }

    // get bookmarks html
    ob_start();
    $post_args = array(
        'post_type' => 'listing',
        'post__in'          => $listing_bookmarks,
        'posts_per_page'=> -1,
        'orderby'=> 'post__in',
        'post_status' => 'publish'
    );
    $posts_query = new WP_Query($post_args);
    if($posts_query->have_posts()) : 
        while($posts_query->have_posts()) : $posts_query->the_post(); 
            townhub_addons_get_template_part( 'templates-inner/bookmark', 'item' );
        endwhile; 
    endif;
    $json['data']['bookmarks'] = $posts_query->found_posts;
    $json['data']['bookmarks_html'] = ob_get_clean();
    if( isset($_POST['for_map']) &&  $_POST['for_map'] ){
        $json['data']['icon'] = __('<i class="fas fa-heart"></i>', 'townhub-add-ons');
    }else{
        $json['data']['icon'] = __('<i class="fas fa-heart"></i> Saved', 'townhub-add-ons');
    }
    wp_send_json($json);

}
function townhub_addons_already_bookmarked($post_ID = 0)
{
    if (!is_user_logged_in()) {
        return false;
    }

    $listing_bookmarks = get_user_meta(get_current_user_id(), ESB_META_PREFIX . 'listing_bookmarks', true);

    if (!empty($listing_bookmarks) && is_array($listing_bookmarks)) {
        if (array_search($post_ID, $listing_bookmarks) !== false) {
            return true;
        }

    }

    return false;

}
// for delete bookmark
add_action('wp_ajax_nopriv_townhub_addons_delete_bookmark', 'townhub_addons_delete_bookmark_callback');
add_action('wp_ajax_townhub_addons_delete_bookmark', 'townhub_addons_delete_bookmark_callback');

function townhub_addons_delete_bookmark_callback()
{
    $json = array(
        'success' => false,
        'data'    => array(
            'POST' => $_POST,
        ),
        'debug'     => false
    );

    Esb_Class_Ajax_Handler::verify_nonce('townhub-add-ons');

    $listing_post = get_post($_POST['listing']);

    if (empty($listing_post)) {
        $json['data']['error'] = esc_html__('Invalid listing', 'townhub-add-ons');
        wp_send_json($json);
    }

    if ( !is_user_logged_in() ) {
        $json['data']['error'] = esc_html__('Not logged in user', 'townhub-add-ons');
        wp_send_json($json);
    }

    if (!townhub_addons_already_bookmarked($listing_post->ID)) {
        $json['data']['error'] = esc_html__('You haven\'t bookmarked this listing', 'townhub-add-ons');
        wp_send_json($json);
    }

    $json['success'] = true;

    // decrease listing bookmarks
    Esb_Class_Listing_CPT::update_bookmark_count($listing_post->ID, true);
    //update user bookmarks array
    $user_id = get_current_user_id();

    $listing_bookmarks = (array)get_user_meta($user_id, ESB_META_PREFIX . 'listing_bookmarks', true);
    $json['data']['bookmarks'] = count($listing_bookmarks);
    if ( !empty($listing_bookmarks) ) {
        $key = array_search($listing_post->ID, $listing_bookmarks);
        if ($key !== false) {
            unset($listing_bookmarks[$key]);
            $json['data']['bookmarks'] = count($listing_bookmarks);
            update_user_meta( $user_id, ESB_META_PREFIX . 'listing_bookmarks', $listing_bookmarks );
        }
    }
    
    wp_send_json($json);

}

/**
 * Show custom user profile fields
 *
 * @param  object $profileuser A WP_User object
 * @return void
 */
function townhub_addons_user_add_meta_box($profileuser)
{
    ?>
    <h2><?php _e('TownHub Theme', 'townhub-add-ons');?></h2>
    <table class="form-table">
        <tr>
            <th>
                <label for="user_avatar"><?php esc_html_e('Custom Avatar', 'townhub-add-ons');?></label>
            </th>
            <td>

                <div class="edit-profile-photo fl-wrap">
                    <div class="profile-photo-wrap"><?php
                    // https://wordpress.stackexchange.com/questions/7620/how-to-change-users-avatar
                        echo get_avatar($profileuser->user_email, '150', 'https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150', $profileuser->display_name);
                        ?>
                    </div>
                    <div class="change-photo-btn">
                        <div class="photoUpload">
                            <span><i class="fa fa-upload"></i><?php _e(' Upload Photo', 'townhub-add-ons');?></span>
                            <?php
                            if (current_user_can('upload_files')) {
                                    $avatar_data = get_user_meta($profileuser->ID, ESB_META_PREFIX . 'custom_avatar', true);
                                    
                                    townhub_addons_get_template_part('template-parts/images-select', false, array('is_single' => true, 'name' => 'custom_avatar', 'datas' => $avatar_data));
                                } else {?>
                                <input type="file" class="upload cth-avatar-upload" name="custom_avatar_upload">
                            <?php
                            }?>
                        </div>
                    </div>
                </div>


            </td>
        </tr>

        <tr>
            <th>
                <label for="user_avatar"><?php esc_html_e('Cover Background', 'townhub-add-ons');?></label>
            </th>
            <td>

                <?php 
                $imgs_data = get_user_meta($profileuser->ID,  ESB_META_PREFIX.'cover_bg', true ); 
                $img_id = '';
                if(is_array($imgs_data) && count($imgs_data)){
                    $img_id = reset($imgs_data);
                } ?>

                <div class="edit-profile-photo fl-wrap">
                    <div class="profile-photo-wrap"><?php 
                                if($img_id != '') echo wp_get_attachment_image( $img_id );
                            ?>
                    </div>
                    <div class="change-photo-btn">
                        <div class="photoUpload">
                            <span><i class="fa fa-upload"></i><?php _e(' Upload Photo', 'townhub-add-ons');?></span>
                            <?php
                            if (current_user_can('upload_files')) {
                                    townhub_addons_get_template_part('template-parts/images-select', false, array('is_single' => true, 'name' => 'cover_bg', 'datas' => $imgs_data));
                                } else {?>
                                <input type="file" class="upload cth-avatar-upload" name="cover_bg_upload">
                            <?php
                            }?>
                        </div>
                    </div>
                </div>


            </td>
        </tr>

        <tr>
            <th>
                <label for="author-phone"><?php esc_html_e('Phone', 'townhub-add-ons');?></label>
            </th>
            <td>
                <input type="text" name="author_phone" id="author-phone" value="<?php echo get_user_meta($profileuser->ID, ESB_META_PREFIX . 'phone', true); ?>" class="regular-text">
            </td>
        </tr>

        <tr>
            <th>
                <label for="author-address"><?php esc_html_e('Address', 'townhub-add-ons');?></label>
            </th>
            <td>
                <input type="text" name="author_address" id="author-address" value="<?php echo get_user_meta($profileuser->ID, ESB_META_PREFIX . 'address', true); ?>" class="regular-text">
            </td>
        </tr>

        <tr>
            <th>
                <label for="author-company"><?php esc_html_e('Company', 'townhub-add-ons');?></label>
            </th>
            <td>
                <input type="text" name="author_company" id="author-company" value="<?php echo get_user_meta($profileuser->ID, ESB_META_PREFIX . 'company', true); ?>" class="regular-text">
            </td>
        </tr>

        <tr>
            <th>
                <label for="user_socials"><?php esc_html_e('Socials', 'townhub-add-ons');?></label>
            </th>
            <td>
                <?php
$socials = get_user_meta($profileuser->ID, ESB_META_PREFIX . 'socials', true);
    ?>
                <div class="repeater-fields-wrap"  data-tmpl="tmpl-user-social">
                    <div class="repeater-fields user-socials">
                    <?php
if (!empty($socials)) {
        foreach ($socials as $key => $social) {
            townhub_addons_get_template_part('templates-inner/social', false, array('index' => $key, 'name' => $social['name'], 'url' => $social['url']));
        }
    }
    ?>
                    </div>
                    <button class="btn addfield" data-name="socials" type="button"><?php esc_html_e('Add Social', 'townhub-add-ons');?></button>
                </div>

            </td>
        </tr>
        <tr>
            <th>
                <label for="earning"><?php esc_html_e('Earning', 'townhub-add-ons');?></label>
            </th>
            <td>
                <?php $earning = get_user_meta($profileuser->ID, ESB_META_PREFIX . 'earning', true);?>
                <p ><?php echo townhub_addons_get_price_formated(floatval($earning)); ?></p>

            </td>
        </tr>

        <?php 
        $member_plan = get_user_meta( $profileuser->ID, ESB_META_PREFIX.'member_plan', true );
        ?>
        <tr>
            <th>
                <label for="author-plan"><?php esc_html_e('Author Plan', 'townhub-add-ons');?></label>
            </th>
            <td>
                <select name="member_plan" id="author-plan">
                    <?php foreach (townhub_addons_get_listing_plans() as $plid => $pltitle) {
                        ?>
                        <option value="<?php echo esc_attr( $plid ); ?>" <?php selected( $plid, $member_plan, true ); ?>><?php echo esc_html( $pltitle ); ?></option>
                    <?php
                    } ?>
                </select>
            </td>
        </tr>

        <tr>
            <th>
                <label for="expiry_date"><?php esc_html_e('Expire Date', 'townhub-add-ons');?></label>
            </th>
            <td>
                <input type="text" name="end_date" id="expiry_date" value="<?php echo get_user_meta($profileuser->ID, ESB_META_PREFIX . 'end_date', true); ?>" class="regular-text">
            </td>
        </tr>

        <tr>
            <th>
                <label for="author-fee"><?php esc_html_e('Author Fee (%)', 'townhub-add-ons');?></label>
            </th>
            <td>
                <input type="text" name="author_fee" id="author-fee" value="<?php echo get_user_meta($profileuser->ID, ESB_META_PREFIX . 'author_fee', true); ?>" class="regular-text">
            </td>
        </tr>

        <tr>
            <th>
                <label for="fixed-fee"><?php esc_html_e('Author Fixed Fee (amount)', 'townhub-add-ons');?></label>
            </th>
            <td>
                <input type="text" name="fixed_fee" id="fixed-fee" value="<?php echo get_user_meta($profileuser->ID, ESB_META_PREFIX . 'fixed_fee', true); ?>" class="regular-text">
            </td>
        </tr>

        <tr>
            <th>
                <label for="woo-limit"><?php esc_html_e('Dokan Product Limit', 'townhub-add-ons');?></label>
            </th>
            <td>
                <input type="text" name="woo_limit" id="woo-limit" value="<?php echo get_user_meta($profileuser->ID, ESB_META_PREFIX . 'woo_limit', true); ?>" class="regular-text">
            </td>
        </tr>
        <?php 
        $verified = get_user_meta($profileuser->ID, ESB_META_PREFIX . 'verified', true); ?>
        <tr>
                <th>
                    <label for="user-verified"><?php _ex('Is Verified Author?', 'User', 'townhub-add-ons');?></label>
                </th>
                <td>
                    <input type="checkbox" name="verified" id="user-verified" value="yes" <?php checked( $verified, 'yes', true ); ?>>
                </td>
        </tr>
        
        <?php 
        // delete author stats
        if( Esb_Class_Membership::is_author($profileuser->ID) ): ?>
            <tr>
                <th>
                    <label for="reset-stats"><?php _ex('Reset Author Stats', 'User', 'townhub-add-ons');?></label>
                </th>
                <td>
                    <input type="checkbox" name="reset_stats" id="reset-stats" value="yes">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="delete-posts"><?php _ex('Delete Listing and Booking posts also?', 'User', 'townhub-add-ons');?></label>
                </th>
                <td>
                    <input type="checkbox" name="delete_posts" id="delete-posts" value="yes">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="reset-earning"><?php _ex('Reset Author Earning', 'User', 'townhub-add-ons');?></label>
                </th>
                <td>
                    <input type="checkbox" name="reset_earning" id="reset-earning" value="yes">
                </td>
            </tr>
        <?php endif; ?>

    </table>
<?php
// echo '<pre>';
    // var_dump(get_user_meta ( $profileuser->ID));

    //   ["billing_first_name"]=>
    // array(1) {
    //   [0]=>
    //   string(5) "Cuong"
    // }
    // ["billing_last_name"]=>
    // array(1) {
    //   [0]=>
    //   string(4) "Tran"
    // }
    // ["billing_company"]=>
    // array(1) {
    //   [0]=>
    //   string(9) "CTHthemes"
    // }
    // ["billing_address_1"]=>
    // array(1) {
    //   [0]=>
    //   string(6) "Line 1"
    // }
    // ["billing_address_2"]=>
    // array(1) {
    //   [0]=>
    //   string(6) "line 2"
    // }
    // ["billing_city"]=>
    // array(1) {
    //   [0]=>
    //   string(5) "Hanoi"
    // }
    // ["billing_postcode"]=>
    // array(1) {
    //   [0]=>
    //   string(6) "100000"
    // }
    // ["billing_country"]=>
    // array(1) {
    //   [0]=>
    //   string(2) "VN"
    // }
    // ["billing_state"]=>
    // array(1) {
    //   [0]=>
    //   string(5) "Hanoi"
    // }
    // ["billing_phone"]=>
    // array(1) {
    //   [0]=>
    //   string(5) "55555"
    // }
    // ["billing_email"]=>
    // array(1) {
    //   [0]=>
    //   string(18) "home.cth@gmail.com"
    // }
    // ["shipping_first_name"]=>
    // array(1) {
    //   [0]=>
    //   string(0) ""
    // }
    // ["shipping_last_name"]=>
    // array(1) {
    //   [0]=>
    //   string(0) ""
    // }
    // ["shipping_company"]=>
    // array(1) {
    //   [0]=>
    //   string(0) ""
    // }
    // ["shipping_address_1"]=>
    // array(1) {
    //   [0]=>
    //   string(0) ""
    // }
    // ["shipping_address_2"]=>
    // array(1) {
    //   [0]=>
    //   string(0) ""
    // }
    // ["shipping_city"]=>
    // array(1) {
    //   [0]=>
    //   string(0) ""
    // }
    // ["shipping_postcode"]=>
    // array(1) {
    //   [0]=>
    //   string(0) ""
    // }
    // ["shipping_country"]=>
    // array(1) {
    //   [0]=>
    //   string(0) ""
    // }
    // ["shipping_state"]=>
    // array(1) {
    //   [0]=>
    //   string(0) ""
    // }
}
add_action('show_user_profile', 'townhub_addons_user_add_meta_box', 10, 1);
add_action('edit_user_profile', 'townhub_addons_user_add_meta_box', 10, 1);

add_action ( 'personal_options_update' , 'townhub_addons_update_user_profile_fields' ); 
add_action ( 'edit_user_profile_update' , 'townhub_addons_update_user_profile_fields' );      

function townhub_addons_update_user_profile_fields ( $user_id ) {
    if ( ! current_user_can ( 'edit_user' , $user_id ) ) { return false ; }  
    if(isset($_POST[ 'socials' ])){
        $field_name = ESB_META_PREFIX.'socials';     
        update_user_meta ( $user_id , $field_name , $_POST[ 'socials' ] ); 
    }
    if(isset($_POST[ 'author_phone' ])){   
        update_user_meta ( $user_id , ESB_META_PREFIX.'phone' , $_POST[ 'author_phone' ] ); 
    }
    if(isset($_POST[ 'author_address' ])){   
        update_user_meta ( $user_id , ESB_META_PREFIX.'address' , $_POST[ 'author_address' ] ); 
    }
    if(isset($_POST[ 'author_company' ])){   
        update_user_meta ( $user_id , ESB_META_PREFIX.'company' , $_POST[ 'author_company' ] ); 
    }
    if( isset($_POST[ 'custom_avatar' ]) && $_POST[ 'custom_avatar' ] ){
        update_user_meta ( $user_id , ESB_META_PREFIX.'custom_avatar' , $_POST[ 'custom_avatar' ] ); 
    }
    $custom_avatar_upload = townhub_addons_handle_image_multiple_upload('custom_avatar_upload');
    if(!empty($custom_avatar_upload)){
        reset($custom_avatar_upload);
        update_user_meta ( $user_id , ESB_META_PREFIX.'custom_avatar' , key($custom_avatar_upload) ); 
    } 
    if( isset($_POST[ 'cover_bg' ]) && $_POST[ 'cover_bg' ] ){
        update_user_meta ( $user_id , ESB_META_PREFIX.'cover_bg' , $_POST[ 'cover_bg' ] ); 
    }
    $custom_avatar_upload = townhub_addons_handle_image_multiple_upload('cover_bg_upload');
    if(!empty($custom_avatar_upload)){
        reset($custom_avatar_upload);
        update_user_meta ( $user_id , ESB_META_PREFIX.'cover_bg' , key($custom_avatar_upload) ); 
    } 

    if(isset($_POST[ 'member_plan' ])){   
        update_user_meta ( $user_id , ESB_META_PREFIX.'member_plan' , $_POST[ 'member_plan' ] ); 
    }
    if(isset($_POST[ 'end_date' ])){   
        update_user_meta ( $user_id , ESB_META_PREFIX.'end_date' , $_POST[ 'end_date' ] ); 
    }
    if(isset($_POST[ 'author_fee' ])){   
        update_user_meta ( $user_id , ESB_META_PREFIX.'author_fee' , $_POST[ 'author_fee' ] ); 
    }
    if(isset($_POST[ 'fixed_fee' ])){   
        update_user_meta ( $user_id , ESB_META_PREFIX.'fixed_fee' , $_POST[ 'fixed_fee' ] ); 
    }
    if(isset($_POST[ 'woo_limit' ])){   
        update_user_meta ( $user_id , ESB_META_PREFIX.'woo_limit' , intval($_POST[ 'woo_limit' ]) ); 
    }
    if(isset($_POST[ 'verified' ])){   
        update_user_meta ( $user_id , ESB_META_PREFIX.'verified' , 'yes' ); 
    }else{
        update_user_meta ( $user_id , ESB_META_PREFIX.'verified' , '' ); 
    }
    // reset stats
    if( isset($_POST['reset_stats']) && $_POST['reset_stats'] == 'yes' ){
        $au_listings = get_posts( array(
            'fields'                => 'ids',
            'post_type'             => 'listing', 
            'author'                => $user_id, 
            'orderby'               => 'date',
            'order'                 => 'DESC',
            'post_status'           => 'publish',
            'posts_per_page'        => -1, // no limit 
        ) );
        if( !empty($au_listings) ){
            $delete_posts = isset($_POST['delete_posts']) && $_POST['delete_posts'] == 'yes';
            foreach ($au_listings as $lid) {
                // reset post views
                Esb_Class_LStats::reset_stats($lid);
                update_post_meta( $lid, ESB_META_PREFIX.'post_views_count', 0 );
                update_post_meta( $lid, ESB_META_PREFIX.'bookmark_count', 0 );

                if( $delete_posts ) wp_delete_post( $lid, true );
            }
        }
        update_user_meta( $user_id, ESB_META_PREFIX . 'messages_count', 0 );
        update_user_meta( $user_id, ESB_META_PREFIX . 'bookings_count', 0 );
    }
    // bookings_count
    // reset earning
    if( isset($_POST['reset_earning']) && $_POST['reset_earning'] == 'yes' ){
        Esb_Class_Earning::reset_stats($user_id);
    }
    
} 
