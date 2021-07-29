<?php
/* add_ons_php */

//For portfolio_cat taxonomy
//https://make.wordpress.org/core/2015/09/04/taxonomy-term-metadata-proposal/
// Add term page
function townhub_addons_listing_cat_add_new_meta_field() {
    
    // this will add the custom meta field to the add new term page
    // wp_enqueue_media();
    // wp_enqueue_script('townhub_tax_meta', ESB_DIR_URL . 'inc/assets/upload_file.js', array('jquery'), null, true);
    // wp_enqueue_script('select2', ESB_DIR_URL . 'assets/js/select2.min.js', array('jquery'), null, true);
    // wp_enqueue_script('townhub_tax_repeat', ESB_DIR_URL . 'inc/assets/repeat_fields.js', array('jquery','jquery-ui-sortable'), null, true);
    
    // townhub_features_select_field(array(
    //                             'id'=>'features',
    //                             'name'=>esc_html__('Available Features','townhub-add-ons'),
    //                             'values' => array(
    //                                 'yes'=> esc_html__('Yes','townhub-add-ons'),
    //                                 'no'=> esc_html__('No','townhub-add-ons'),
    //                             ),
    //                             'required' => false,
    //                             'default'=>'yes'
    // ));

    // townhub_repeat_fields_options_field(array(
    //                             'id'=>'add-features',
    //                             'name'=>esc_html__('Additional Features','townhub-add-ons'),
    //                             'values' => array(
    //                                 'yes'=> esc_html__('Yes','townhub-add-ons'),
    //                                 'no'=> esc_html__('No','townhub-add-ons'),
    //                             ),
    //                             'required' => false,
    //                             'default'=>'yes'
    // ));


    // townhub_radio_options_field(array(
    //                             'id'=>'tax_show_header',
    //                             'name'=>esc_html__('Show Header Section','townhub-add-ons'),
    //                             'values' => array(
    //                                     'yes'=> esc_html__('Yes','townhub-add-ons'),
    //                                     'no'=> esc_html__('No','townhub-add-ons'),
    //                                 ),
    //                             'default'=>'yes'
    // ));
    townhub_select_media_file_field('featured_img',esc_html__('Featured Image','townhub-add-ons'), array());

}
add_action('listing_cat_add_form_fields', 'townhub_addons_listing_cat_add_new_meta_field', 10, 2);

// Edit term page
function townhub_addons_listing_cat_edit_meta_field($term) {
    wp_enqueue_style( 'font-awesome', ESB_DIR_URL . 'inc/assets/font-awesome/font-awesome.min.css');
    wp_enqueue_style( 'cth-backend', ESB_DIR_URL . 'inc/assets/backend.css');
    // wp_enqueue_media();
    // wp_enqueue_script('townhub_tax_meta', ESB_DIR_URL . 'inc/assets/upload_file.js', array('jquery'), null, true);
    wp_enqueue_script('select2', ESB_DIR_URL . 'assets/js/select2.min.js', array('jquery'), null, true);
    wp_enqueue_script('townhub_tax_repeat', ESB_DIR_URL . 'inc/assets/repeat_fields.js', array('jquery','jquery-ui-sortable'), null, true);
    
    // put the term ID into a variable
    // $t_id = $term->term_id;

    $term_meta = get_term_meta( $term->term_id, ESB_META_PREFIX.'term_meta', true );
    
    // retrieve the existing value(s) for this meta field. This returns an array
    // $term_meta = get_option(ESB_META_PREFIX."tax_listing_cat_$t_id");

    townhub_features_select_new_field(array(
                                'id'=>'features',
                                'name'=>esc_html__('Available Features','townhub-add-ons'),
                                'values' => array(
                                    'yes'=> esc_html__('Yes','townhub-add-ons'),
                                    'no'=> esc_html__('No','townhub-add-ons'),
                                ),
                                'required' => false,
                                'default'=>'yes',
                                'desc'  => __( 'Select features for this category available to check when submit/edit listing. These also be used for advanced filter when this category is selected.', 'townhub-add-ons' ),
    ),$term_meta,false);

    townhub_repeat_fields_options_field(array(
                                'id'=>'add-features',
                                'name'=>esc_html__('Additional Features','townhub-add-ons'),
                                'values' => array(
                                    'yes'=> esc_html__('Yes','townhub-add-ons'),
                                    'no'=> esc_html__('No','townhub-add-ons'),
                                ),
                                'required' => false,
                                'default'=>'yes'
    ),$term_meta,false);

    // townhub_addons_content_widgets_order_options_field(array(
    //                             'id'=>'content-widgets-order',
    //                             'id_hide'=>'content-widgets-hide',
    //                             'name'=>esc_html__('Content Widgets Order','townhub-add-ons'),
    //                             'values' => array(
    //                                 'speaker'=> esc_html__('Speaker Widget','townhub-add-ons'),
    //                                 'promo_video'=> esc_html__('Promo Video','townhub-add-ons'),
    //                                 'content'=> esc_html__('Content Widget','townhub-add-ons'),
    //                                 'gallery'=> esc_html__('Gallery Widget','townhub-add-ons'),
    //                                 'slider'=> esc_html__('Slider Widget','townhub-add-ons'),
    //                                 'faqs'=> esc_html__('FAQs Widget','townhub-add-ons'),
                                    
    //                             ),
    //                             'required' => false,
    //                             // 'default'=>array('promo_video','content','gallery','slider','faqs','speaker'),
    //                             'default'=>townhub_addons_get_listing_content_order_default(),

    //                             'id_2'=>'sidebar-widgets-order',
    //                             'id_hide_2'=>'sidebar-widgets-hide',
    //                             'values_2' => array(
    //                                 'wkhour'=> esc_html__('Working Hour Widget','townhub-add-ons'),
    //                                 'countdown'=> esc_html__('Countdown','townhub-add-ons'),
    //                                 'addfeas' => esc_html__('Additional Features','townhub-add-ons'),
    //                                 'price_range'=> esc_html__('Price Range Widget','townhub-add-ons'),
    //                                 'booking'=> esc_html__('Booking Widget','townhub-add-ons'),
    //                                 'weather'=> esc_html__('Weather Widget','townhub-add-ons'),
    //                                 'contacts'=> esc_html__('Contacts Widget','townhub-add-ons'),
    //                                 'author'=> esc_html__('Author Widget','townhub-add-ons'),
    //                                 'moreauthor'=> esc_html__('More From Author Widget','townhub-add-ons'),
                                    
    //                             ),
    //                             // 'default_2'=>array('wkhour','countdown','price_range','booking','contacts','author','moreauthor'),
    //                             'default_2'=>townhub_addons_get_listing_widget_order_default(),
    // ),$term_meta,false);


    
    townhub_radio_options_field(array(
                                'id'=>'tax_show_header',
                                'name'=>esc_html__('Show Header Section','townhub-add-ons'),
                                'values' => array(
                                        'yes'=> esc_html__('Yes','townhub-add-ons'),
                                        'no'=> esc_html__('No','townhub-add-ons'),
                                    ),

                                'default'=>'yes'
    ),$term_meta,false);

    townhub_addons_icon_select_field(array(
                                'id'=>'icon_class',
                                'name'=>esc_html__('Icon','townhub-add-ons'),
                                // 'values' => array(
                                //         'yes'=> esc_html__('Yes','townhub-add-ons'),
                                //         'no'=> esc_html__('No','townhub-add-ons'),
                                //     ),

                                'default'=>'fa fa-cutlery'
    ),$term_meta,false);

    townhub_addons_select_options_field(array(
                                'id'=>'icolor',
                                'name'=>esc_html__('Color','townhub-add-ons'),
                                'values' => array(
                                    'red-bg'=> esc_html__('Red','townhub-add-ons'),
                                    'purp-bg'=> esc_html__('Purple','townhub-add-ons'),
                                    'blue-bg'=> esc_html__('Blue','townhub-add-ons'),
                                    'yellow-bg'=> esc_html__('Yellow','townhub-add-ons'),
                                    
                                    'green-bg'=> esc_html__('Green','townhub-add-ons'),
                                    'custom'=> esc_html__('Custom color','townhub-add-ons'),
                                ),
                                'required' => true,
                                'default'=>'yes'
    ),$term_meta,false);

    townhub_addons_text_options_field(array(
                                'id'=>'cus_color',
                                'name'=>esc_html__('Custom Color','townhub-add-ons'),
                                'desc'      => __('Ex: With <strong>#F75C96</strong> color value, you have to use custom css code: <strong>.cus-F75C96{background-color:#F75C96;}</strong>','townhub-add-ons'),
                                'default'=>''
    ),$term_meta,false);

    townhub_select_media_file_field('img_icon',_x('Image Icon','Listing category','townhub-add-ons'), $term_meta,false);

    townhub_select_media_file_field('featured_img',esc_html__('Featured Image','townhub-add-ons'), $term_meta,false);

    townhub_select_media_file_field('gmap_marker',esc_html__('Google Map Marker','townhub-add-ons'), $term_meta,false);
    

    
    townhub_addons_select_options_field(array(
                                'id'=>'ltype',
                                'name'=>esc_html__('Listing Type','townhub-add-ons'),
                                'values' => array( '' => __( 'Default Value', 'townhub-add-ons' ) ) + townhub_addons_get_listing_type_options(),
                                'required' => true,
                                'default'=>''
    ),$term_meta,false);

    townhub_addons_select2_options_field(array(
                                'id'=>'ltypes_filter',
                                'name'=>_x('Listing types for filter listings','Listing cat','townhub-add-ons'),
                                'desc'=>_x('Select listing types which you want to apply to this category. They will be used instead of Listing Type value above.','Listing cat','townhub-add-ons'),
                                'values' => townhub_addons_get_listing_type_options(),
                                'required' => false,
                                'default'=>[]
    ),$term_meta,false);

}
add_action('listing_cat_edit_form_fields', 'townhub_addons_listing_cat_edit_meta_field', 10, 2);

// Save extra taxonomy fields callback function.
function townhub_addons_save_listing_cat_custom_meta($term_id) {
    if (isset($_POST['term_meta'])) {
        $term_meta = get_term_meta( $term_id, ESB_META_PREFIX.'term_meta', true );
        if(!$term_meta||!is_array($term_meta)) $term_meta = array();
        $cat_keys = array_keys($_POST['term_meta']);
        foreach ($cat_keys as $key) {
            if (isset($_POST['term_meta'][$key])) {
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
        
        // Save the option array.
        update_term_meta($term_id, ESB_META_PREFIX.'term_meta', $term_meta);

    }
}
add_action('create_listing_cat', 'townhub_addons_save_listing_cat_custom_meta', 10, 2);
add_action('edited_listing_cat', 'townhub_addons_save_listing_cat_custom_meta', 10, 2);



// Add term page
function townhub_addons_listing_location_add_new_meta_field() {
    
    // this will add the custom meta field to the add new term page
    // wp_enqueue_media();
    // wp_enqueue_script('townhub_tax_meta', ESB_DIR_URL . 'inc/assets/upload_file.js', array('jquery'), null, true);
    
    
    townhub_select_media_file_field('featured_img',esc_html__('Featured Image','townhub-add-ons'), array());

}
add_action('listing_location_add_form_fields', 'townhub_addons_listing_location_add_new_meta_field', 10, 2);

// Edit term page
function townhub_addons_listing_location_edit_meta_field($term) {
    // wp_enqueue_media();
    // wp_enqueue_script('townhub_tax_meta', ESB_DIR_URL . 'inc/assets/upload_file.js', array('jquery'), null, true);
    
    $term_meta = get_term_meta( $term->term_id, ESB_META_PREFIX.'term_meta', true );
    

    townhub_select_media_file_field('featured_img',esc_html__('Featured Image','townhub-add-ons'), $term_meta,false);
}
add_action('listing_location_edit_form_fields', 'townhub_addons_listing_location_edit_meta_field', 10, 2);

// Save extra taxonomy fields callback function.
function townhub_addons_save_listing_location_custom_meta($term_id) {
    if (isset($_POST['term_meta'])) {
        $term_meta = get_term_meta( $term_id, ESB_META_PREFIX.'term_meta', true );
        if(!$term_meta||!is_array($term_meta)) $term_meta = array();
        $cat_keys = array_keys($_POST['term_meta']);
        foreach ($cat_keys as $key) {
            if (isset($_POST['term_meta'][$key])) {
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
        
        // Save the option array.
        update_term_meta($term_id, ESB_META_PREFIX.'term_meta', $term_meta);

    }
}
add_action('create_listing_location', 'townhub_addons_save_listing_location_custom_meta', 10, 2);
add_action('edited_listing_location', 'townhub_addons_save_listing_location_custom_meta', 10, 2);

function townhub_addons_cthads_package_add_new_meta_field() {
    // wp_enqueue_media();
    // wp_enqueue_script('townhub_tax_meta', ESB_DIR_URL . 'inc/assets/upload_file.js', array('jquery'), null, true);
    wp_enqueue_script('select2', ESB_DIR_URL . 'assets/js/select2.min.js', array('jquery'), null, true);
    wp_enqueue_script('townhub_tax_repeat', ESB_DIR_URL . 'inc/assets/repeat_fields.js', array('jquery','jquery-ui-sortable'), null, true);

    

    townhub_radio_options_field(array(
                                'id'=>'is_active',
                                'name'=>esc_html__('Is Active Package','townhub-add-ons'),
                                'values' => array(
                                        'yes'=> esc_html__('Yes','townhub-add-ons'),
                                        'no'=> esc_html__('No','townhub-add-ons'),
                                    ),

                                'default'=>'yes'
    ));

    townhub_addons_select2_options_field(array(
                                'id'=>'ad_type',
                                'name'=>esc_html__('AD Type','townhub-add-ons'),
                                'values' => townhub_addons_listing_ad_positions(),
                                'default'=>'sidebar'
    ));

    

    townhub_addons_text_options_field(array(
                                'id'=>'ad_price',
                                'name'=>esc_html__('AD Price','townhub-add-ons'),
                                'default'=>'10',
                                'desc' => townhub_addons_get_option('currency_symbol','$'),
    ));

    townhub_addons_select_options_field(array(
                                'id'=>'ad_period',
                                'name'=>esc_html__('AD Period','townhub-add-ons'),
                                'values' => array(
                                        'day'=> esc_html__('Days','townhub-add-ons'),
                                        'week'=> esc_html__('Weeks','townhub-add-ons'),
                                        'month'=> esc_html__('Months','townhub-add-ons'),
                                        'year'=> esc_html__('Years','townhub-add-ons'),
                                    ),

                                'default'=>'day',
                                'desc'  => __( 'AD expiration period', 'townhub-add-ons' ),
    ));

    townhub_addons_text_options_field(array(
                                'id'=>'ad_interval',
                                'name'=>esc_html__('AD Interval','townhub-add-ons'),
                                'default'=>'30',
                                'desc' => __( 'Numbers of PERIOD value the AD will be expired', 'townhub-add-ons' ),
    ));

    

    townhub_select_media_file_field('icon_img',esc_html__('Image Icon','townhub-add-ons'), array() );
}
add_action('cthads_package_add_form_fields', 'townhub_addons_cthads_package_add_new_meta_field', 10, 2);


// Edit term page
function townhub_addons_cthads_package_edit_meta_field($term) {
    // wp_enqueue_media();
    // wp_enqueue_script('townhub_tax_meta', ESB_DIR_URL . 'inc/assets/upload_file.js', array('jquery'), null, true);
    wp_enqueue_script('select2', ESB_DIR_URL . 'assets/js/select2.min.js', array('jquery'), null, true);
    wp_enqueue_script('townhub_tax_repeat', ESB_DIR_URL . 'inc/assets/repeat_fields.js', array('jquery','jquery-ui-sortable'), null, true);

    
    $term_meta = array(
        'is_active' => get_term_meta( $term->term_id, ESB_META_PREFIX.'is_active', true ),
        'ad_type' => get_term_meta( $term->term_id, ESB_META_PREFIX.'ad_type', true ),
        'ad_price' => get_term_meta( $term->term_id, ESB_META_PREFIX.'ad_price', true ),
        'ad_period' => get_term_meta( $term->term_id, ESB_META_PREFIX.'ad_period', true ),
        'ad_interval' => get_term_meta( $term->term_id, ESB_META_PREFIX.'ad_interval', true ),
        'icon_img' => get_term_meta( $term->term_id, ESB_META_PREFIX.'icon_img', true ),
    );

    townhub_radio_options_field(array(
                                'id'=>'is_active',
                                'name'=>esc_html__('Is Active Package','townhub-add-ons'),
                                'values' => array(
                                        'yes'=> esc_html__('Yes','townhub-add-ons'),
                                        'no'=> esc_html__('No','townhub-add-ons'),
                                    ),

                                'default'=>'yes'
    ),$term_meta,false);

    townhub_addons_select2_options_field(array(
                                'id'=>'ad_type',
                                'name'=>esc_html__('AD Type','townhub-add-ons'),
                                'values' => townhub_addons_listing_ad_positions(),
                                'default'=>'sidebar'
    ),$term_meta,false);

    

    townhub_addons_text_options_field(array(
                                'id'=>'ad_price',
                                'name'=>esc_html__('AD Price','townhub-add-ons'),
                                'default'=>'10',
                                'desc' => townhub_addons_get_option('currency_symbol','$'),
    ),$term_meta,false);

    townhub_addons_select_options_field(array(
                                'id'=>'ad_period',
                                'name'=>esc_html__('AD Period','townhub-add-ons'),
                                'values' => array(
                                        'day'=> esc_html__('Days','townhub-add-ons'),
                                        'week'=> esc_html__('Weeks','townhub-add-ons'),
                                        'month'=> esc_html__('Months','townhub-add-ons'),
                                        'year'=> esc_html__('Years','townhub-add-ons'),
                                    ),

                                'default'=>'day',
                                'desc'  => __( 'AD expiration period', 'townhub-add-ons' ),
    ),$term_meta,false);

    townhub_addons_text_options_field(array(
                                'id'=>'ad_interval',
                                'name'=>esc_html__('AD Interval','townhub-add-ons'),
                                'default'=>'30',
                                'desc' => __( 'Numbers of PERIOD value the AD will be expired', 'townhub-add-ons' ),
    ),$term_meta,false);

    

    townhub_select_media_file_field('icon_img',esc_html__('Image Icon','townhub-add-ons'), $term_meta,false);
}
add_action('cthads_package_edit_form_fields', 'townhub_addons_cthads_package_edit_meta_field', 10, 2);

// Save extra taxonomy fields callback function.
function townhub_addons_save_cthads_package_custom_meta($term_id) {
    if (isset($_POST['term_meta'])) {
        foreach ($_POST['term_meta'] as $key => $value) {
            update_term_meta($term_id, ESB_META_PREFIX.$key, $value);
        }


        // $term_meta = get_term_meta( $term_id, ESB_META_PREFIX.'term_meta', true );
        // if(!$term_meta||!is_array($term_meta)) $term_meta = array();
        // $cat_keys = array_keys($_POST['term_meta']);
        // foreach ($cat_keys as $key) {
        //     if (isset($_POST['term_meta'][$key])) {
        //         $term_meta[$key] = $_POST['term_meta'][$key];
        //     }
        // }
        
        // // Save the option array.
        // update_term_meta($term_id, ESB_META_PREFIX.'term_meta', $term_meta);

    }
}
add_action('create_cthads_package', 'townhub_addons_save_cthads_package_custom_meta', 10, 2);
add_action('edited_cthads_package', 'townhub_addons_save_cthads_package_custom_meta', 10, 2);

