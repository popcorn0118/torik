<?php 
/* add_ons_php */

function townhub_addons_options_get_submit(){
    return array(
        array(
            "type" => "section",
            'id' => 'submit_sec_1',
            "title" => __( 'General', 'townhub-add-ons' ),
        ),

        // array(
        //     "type" => "field",
        //     "field_type" => "select",
        //     'id' => 'submit_redirect',
        //     "title" => __('Submit Redirect', 'townhub-add-ons'),
        //     'args'=> array(
        //         'default'=> 'single',
        //         'options'=> array(
        //             'single' => esc_html__('Single Listing', 'townhub-add-ons'), 
        //             'home' => esc_html__('Home', 'townhub-add-ons'), 
        //             'dashboard' => esc_html__('Dashboard', 'townhub-add-ons'), 
                    
        //         ),
        //     ),
        //     'desc' => esc_html__("The page redirect to after submit/edit listing", 'townhub-add-ons'), 
        // ),
        array(
            "type" => "field",
            "field_type" => "select",
            'id' => 'default_listing_type',
            "title" => __('Listing Default Type', 'townhub-add-ons'),
            'args'=> array(
                'options'=> townhub_addons_get_listing_types(),
            )
        ),
        array(
            "type" => "field",
            "field_type" => "page_select",
            'id' => 'submit_redirect',
            "title" => __('Submit Redirect', 'townhub-add-ons'),
            'desc'  => __('The page redirect to after submit/edit listing', 'townhub-add-ons'),
            'args' => array(
                'default'   => 'single',
                // 'default_title' => "Pricing Tables",
                'options' => array(
                    array(
                        'single',
                        __( 'Single Listing', 'townhub-add-ons' ),
                    ),
                )
            )
        ),

        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'must_select_ltype',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => _x('Author must select a listing type to show fields?', 'TownHub Add-Ons', 'townhub-add-ons'),
        ),

        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'hide_sub_cats',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => __('Hide subcategories on submit listing page', 'townhub-add-ons'),
        ),

        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'submit_timezone_hide',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => __('Hide Timezone', 'townhub-add-ons'),
        ),

        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'pending_editing_listing',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => _x('Pending Editing Listing', 'TownHub Add-Ons', 'townhub-add-ons'),
            'desc'  => _x('Check this option will set editing listing to pending review status so that administrators can review changes before publishing.', 'TownHub Add-Ons', 'townhub-add-ons'),
        ),

        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'publish_not_pending',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => _x('Publish Pending Listing', 'TownHub Add-Ons', 'townhub-add-ons'),
            'desc'  => _x('Check this allows authors to make their listings published by turning off Save as pending field.', 'TownHub Add-Ons', 'townhub-add-ons'),
        ),

        // array(
        //     "type" => "section",
        //     'id' => 'submit_hidefields',
        //     "title" => __( 'Hide Fields. These options is for default free account only and be overrided by current author plan options.', 'townhub-add-ons' ),
        // ),

        // array(
        //     "type" => "field",
        //     "field_type" => "checkbox",
        //     'id' => 'submit_hide_tags',
        //     'args'=> array(
        //         'default' => 'no',
        //         'value' => 'yes',
        //     ),
        //     "title" => __('Hide Tags', 'townhub-add-ons'),
        //     'desc'  => __('Check this to hide <strong>Tags</strong> field on submit page.', 'townhub-add-ons' ),
        // ),

        // array(
        //     "type" => "field",
        //     "field_type" => "checkbox",
        //     'id' => 'submit_hide_head_background',
        //     'args'=> array(
        //         'default' => 'no',
        //         'value' => 'yes',
        //     ),
        //     "title" => __('Hide Header Background Image type', 'townhub-add-ons'),
        //     'desc'  => __('Check this to hide header <strong>Background Image</strong> type on submit page.', 'townhub-add-ons' ),
        // ),
        // array(
        //     "type" => "field",
        //     "field_type" => "checkbox",
        //     'id' => 'submit_hide_head_carousel',
        //     'args'=> array(
        //         'default' => 'no',
        //         'value' => 'yes',
        //     ),
        //     "title" => __('Hide Header Carousel type', 'townhub-add-ons'),
        //     'desc'  => __('Check this to hide header <strong>Carousel</strong> type on submit page.', 'townhub-add-ons' ),
        // ),
        // array(
        //     "type" => "field",
        //     "field_type" => "checkbox",
        //     'id' => 'submit_hide_head_video',
        //     'args'=> array(
        //         'default' => 'no',
        //         'value' => 'yes',
        //     ),
        //     "title" => __('Hide Header Video Background type', 'townhub-add-ons'),
        //     'desc'  => __('Check this to hide header <strong>Video Background</strong> type on submit page.', 'townhub-add-ons' ),
        // ),


        // array(
        //     "type" => "field",
        //     "field_type" => "checkbox",
        //     'id' => 'submit_hide_content_video',
        //     'args'=> array(
        //         'default' => 'no',
        //         'value' => 'yes',
        //     ),
        //     "title" => __('Hide Promo Video', 'townhub-add-ons'),
        //     'desc'  => __('Check this to hide <strong>Promo Video</strong> option on submit page.', 'townhub-add-ons' ),
        // ),

        // array(
        //     "type" => "field",
        //     "field_type" => "checkbox",
        //     'id' => 'submit_hide_content_gallery',
        //     'args'=> array(
        //         'default' => 'no',
        //         'value' => 'yes',
        //     ),
        //     "title" => __('Hide Thumbnails Gallery', 'townhub-add-ons'),
        //     'desc'  => __('Check this to hide <strong>Thumbnails Gallery</strong> option on submit page.', 'townhub-add-ons' ),
        // ),

        // array(
        //     "type" => "field",
        //     "field_type" => "checkbox",
        //     'id' => 'submit_hide_content_slider',
        //     'args'=> array(
        //         'default' => 'no',
        //         'value' => 'yes',
        //     ),
        //     "title" => __('Hide Slider', 'townhub-add-ons'),
        //     'desc'  => __('Check this to hide <strong>Slider</strong> option on submit page.', 'townhub-add-ons' ),
        // ),




        

        // array(
        //     "type" => "field",
        //     "field_type" => "checkbox",
        //     'id' => 'submit_hide_price_opt',
        //     'args'=> array(
        //         'default' => 'no',
        //         'value' => 'yes',
        //     ),
        //     "title" => esc_html__('Hide Price Options', 'townhub-add-ons' ),
        //     'desc'  => __('Check this to hide <strong>Price Options</strong> option on submit/listing page.', 'townhub-add-ons' ),
        // ),

        // array(
        //     "type" => "field",
        //     "field_type" => "checkbox",
        //     'id' => 'submit_hide_faqs_opt',
        //     'args'=> array(
        //         'default' => 'no',
        //         'value' => 'yes',
        //     ),
        //     "title" => esc_html__('Hide FAQs', 'townhub-add-ons' ),
        //     'desc'          => __('Check this to hide <strong>Frequently Asked Questions</strong> option on submit/listing page.', 'townhub-add-ons' ),
        // ),

        // array(
        //     "type" => "field",
        //     "field_type" => "checkbox",
        //     'id' => 'submit_hide_counter_opt',
        //     'args'=> array(
        //         'default' => 'no',
        //         'value' => 'yes',
        //     ),
        //     "title" => esc_html__('Hide Event Counter', 'townhub-add-ons' ),
        //     'desc'          => __('Check this to hide <strong>Event Counter</strong> option on submit/listing page.', 'townhub-add-ons' ),
        // ),


        // array(
        //     "type" => "field",
        //     "field_type" => "checkbox",
        //     'id' => 'submit_hide_workinghours_opt',
        //     'args'=> array(
        //         'default' => 'no',
        //         'value' => 'yes',
        //     ),
        //     "title" => esc_html__('Hide Working Hours', 'townhub-add-ons' ),
        //     'desc'          => __('Check this to hide <strong>Working Hours</strong> option on submit/listing page.', 'townhub-add-ons' ),
        // ),

        // array(
        //     "type" => "field",
        //     "field_type" => "checkbox",
        //     'id' => 'submit_hide_socials_opt',
        //     'args'=> array(
        //         'default' => 'no',
        //         'value' => 'yes',
        //     ),
        //     "title" => esc_html__('Hide Socials', 'townhub-add-ons' ),
        //     'desc'          => __('Check this to hide <strong>Socials</strong> option on submit/listing page.', 'townhub-add-ons' ),
        // ),


        array(
            "type" => "section",
            'id' => 'submit_media_upload',
            "title" => __( 'Media Upload', 'townhub-add-ons' ),
        ),

        array(
            "type" => "field",
            "field_type" => "number",
            'id' => 'submit_media_limit',
            "title" => __('Media Limit', 'townhub-add-ons'),
            'args' => array(
                'default'  => '3',
                'min'  => '1',
                'max'  => '200',
                'step'  => '1',
            ),
            'desc'  => __('The maximum number of upload images per field.', 'townhub-add-ons'),
        ),
        array(
            "type" => "field",
            "field_type" => "number",
            'id' => 'submit_media_limit_size',
            "title" => __('File Size Limit', 'townhub-add-ons'),
            'args' => array(
                'default'  => '2',
                'min'  => '0',
                'max'  => '100',
                'step'  => '0.5',
            ),
            'desc'  => __('The maximum upload file size in MB (Megabyte).', 'townhub-add-ons'),
        ),

        array(
            "type" => "field",
            "field_type" => "number",
            'id' => 'media_min_width',
            "title" => _x('Minimum image width', 'TownHub Add-Ons', 'townhub-add-ons'),
            'args' => array(
                'default'  => '480',
                'min'  => '150',
                'max'  => '20000',
                'step'  => '10',
            ),
            'desc'  => _x('Minimum image width in pixel required', 'TownHub Add-Ons', 'townhub-add-ons'),
        ),
        array(
            "type" => "field",
            "field_type" => "number",
            'id' => 'media_min_height',
            "title" => _x('Minimum image height', 'TownHub Add-Ons', 'townhub-add-ons'),
            'args' => array(
                'default'  => '320',
                'min'  => '150',
                'max'  => '20000',
                'step'  => '10',
            ),
            'desc'  => _x('Minimum image height in pixel required', 'TownHub Add-Ons', 'townhub-add-ons'),
        ),

        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'submit_remove_deleted_imgs',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => _x('Remove deleted images from Media library?', 'TownHub Add-Ons', 'townhub-add-ons'),
        ),

        // array(
        //     "type" => "section",
        //     'id' => 'submit_content_addfields',
        //     "title" => __( 'Additional Fields', 'townhub-add-ons' ),
        // ),

        // // array(
        // //     "type" => "field",
        // //     "field_type" => "repeat_content",
        // //     'id' => 'content_addfields',
        // //     'args' => array(
        // //         'default'  => '',
        // //     ),
        // //     "title" => __('Content Field', 'townhub-add-ons'),
        // //     // 'desc'  => __('General', 'townhub-add-ons'),
        // // ),

        // array(
        //     "type" => "field",
        //     "field_type" => "repeat_widget",
        //     'id' => 'content_addwidgets',
        //     'args' => array(
        //         'default'  => '',
        //         'load_tmpl' => true
        //     ),
        //     "title" => __('Content Fields', 'townhub-add-ons'),
        //     'desc'  => __('Your fields will be display in single listing content area.', 'townhub-add-ons'),
        // ),

        // array(
        //     "type" => "field",
        //     "field_type" => "repeat_widget",
        //     'id' => 'widget_addwidgets',
        //     'args' => array(
        //         'default'  => '',

        //     ),
        //     "title" => __('Widget Fields', 'townhub-add-ons'),
        //     'desc'  => __('Your fields will be display in single listing widget area.', 'townhub-add-ons'),
        // ),

        array(
            "type" => "section",
            'id' => 'submit_captcha_sec',
            "title" => __( 'Google reCAPTCHA - Version 2', 'townhub-add-ons' ),
            'callback' => function(){
                echo sprintf(__( '<p>Get <a href="%s" target="_blank">reCAPTCHA Keys</a>. Note: You have to use reCAPTCHA version 2</p>', 'townhub-add-ons' ), esc_url('https://www.google.com/recaptcha'));
                
            }

            

        ),

        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'enable_g_recaptcah',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => __('Enable reCAPTCHA', 'townhub-add-ons'),
        ),

        array(
            "type" => "field",
            "field_type" => "text",
            'id' => 'g_recaptcha_site_key',
            "title" => __('Site Key', 'townhub-add-ons'),
            'desc'  => '',
            'args' => array(
                'default' => '',
            )
        ),

        array(
            "type" => "field",
            "field_type" => "text",
            'id' => 'g_recaptcha_secret_key',
            "title" => __('Secret key', 'townhub-add-ons'),
            'desc'  => '',
            'args' => array(
                'default' => '',
            )
        ),

        array(
            "type" => "section",
            'id' => 'submit_loc_sec',
            "title" => __( 'Listing Location', 'townhub-add-ons' ),
        ),

        array(
            "type" => "field",
            "field_type" => "select",
            'id' => 'default_country',
            "title" => __('Default Country', 'townhub-add-ons'),
            'args'=> array(
                'default'       => 'US',
                'options'       => townhub_addons_get_google_contry_codes(),
                'use-select2'   => true
            ),
            'desc' => __( 'Default country for listing location.', 'townhub-add-ons' )
        ),

        array(
            "type" => "field",
            "field_type" => "checkbox", 
            'id' => 'location_show_state',
            'args'=> array(
                'default' => 'yes',
                'value' => 'yes',
            ),
            "title" => __('Show Listing Location State', 'townhub-add-ons'),  
            'desc'  => '',
        ),
        
        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'subm_subtitle',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => _x('Show Sub Heading on submit/edit listing page', 'TownHub Add-Ons', 'townhub-add-ons'),
            'desc'  => '',
        ),

    );
}
