<?php 
/* add_ons_php */

require_once ESB_ABSPATH . 'includes/options/general.php';
require_once ESB_ABSPATH . 'includes/options/membership.php';
require_once ESB_ABSPATH . 'includes/options/woo.php';
require_once ESB_ABSPATH . 'includes/options/emails.php';
require_once ESB_ABSPATH . 'includes/options/listings.php';
require_once ESB_ABSPATH . 'includes/options/search.php';
require_once ESB_ABSPATH . 'includes/options/register.php';
require_once ESB_ABSPATH . 'includes/options/gmap.php';
require_once ESB_ABSPATH . 'includes/options/advanced.php';
require_once ESB_ABSPATH . 'includes/options/submit.php';
require_once ESB_ABSPATH . 'includes/options/checkout.php';
require_once ESB_ABSPATH . 'includes/options/payments.php';
require_once ESB_ABSPATH . 'includes/options/dashboard.php';

function townhub_addons_get_plugin_options(){ 
    return array(
        'advanced' => townhub_addons_options_get_advanced(), 
        'general' => townhub_addons_options_get_general(),
        // end tab array
        'register'      => townhub_addons_options_get_register(),
        // end tab array
        'membership' => townhub_addons_options_get_membership(),
        // end tab array
        'checkout'      => townhub_addons_options_get_checkout(),
        // end tab array
        'submit_listing' => townhub_addons_options_get_submit(),
        // end tab array
        'search' => townhub_addons_options_get_search(), 
        // end tab array
        'listings' => townhub_addons_options_get_listings(),
        'dashboard' => townhub_addons_options_get_dashboard(),
        // end tab array
        'ads' => array(
            // sidebar
            // archive
            // category
            // search
            // home
            // custom_grid

            array(
                "type" => "section",
                'id' => 'ads_sec_archive',
                "title" => __( 'Listings Archive Page AD', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'ads_archive_enable',
                "title" => __('Enable/Disable', 'townhub-add-ons'),
                'desc'  => __( 'ADs on the page', 'townhub-add-ons' ),
                'args' => array(
                    'value' => 'yes',
                    'default' => 'yes',
                )
            ),
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'ads_archive_count',
                "title" => __('Count', 'townhub-add-ons'),
                'desc'  => __( 'Number of ads to show', 'townhub-add-ons' ),
                'args' => array(
                    'default' => '2',
                )
            ),
            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'ads_archive_orderby',
                "title" => __('Order AD by', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'date',
                    'options'=> townhub_addons_get_post_orderby(),
                ),
                'desc' => '', 
            ),
            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'ads_archive_order',
                "title" => __('Sort Order', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'DESC',
                    'options'=> array(
                        'ASC' => __( 'Ascending order - (1, 2, 3; a, b, c)', 'townhub-add-ons' ),
                        'DESC' => __( 'Descending order - (3, 2, 1; c, b, a)', 'townhub-add-ons' ),
                    ),
                ),
                'desc' => '', 
            ),

            array(
                "type" => "section",
                'id' => 'ads_sec_category',
                "title" => __( 'Listings Category Page AD', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'ads_category_enable',
                "title" => __('Enable/Disable', 'townhub-add-ons'),
                'desc'  => __( 'ADs on the page', 'townhub-add-ons' ),
                'args' => array(
                    'value' => 'yes',
                    'default' => 'yes',
                )
            ),
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'ads_category_count',
                "title" => __('Count', 'townhub-add-ons'),
                'desc'  => __( 'Number of ads to show', 'townhub-add-ons' ),
                'args' => array(
                    'default' => '2',
                )
            ),
            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'ads_category_orderby',
                "title" => __('Order AD by', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'date',
                    'options'=> townhub_addons_get_post_orderby(),
                ),
                'desc' => '', 
            ),
            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'ads_category_order',
                "title" => __('Sort Order', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'DESC',
                    'options'=> array(
                        'ASC' => __( 'Ascending order - (1, 2, 3; a, b, c)', 'townhub-add-ons' ),
                        'DESC' => __( 'Descending order - (3, 2, 1; c, b, a)', 'townhub-add-ons' ),
                    ),
                ),
                'desc' => '', 
            ),

            array(
                "type" => "section",
                'id' => 'ads_sec_search',
                "title" => __( 'Listings Search Page AD', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'ads_search_enable',
                "title" => __('Enable/Disable', 'townhub-add-ons'),
                'desc'  => __( 'ADs on the page', 'townhub-add-ons' ),
                'args' => array(
                    'value' => 'yes',
                    'default' => 'yes',
                )
            ),
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'ads_search_count',
                "title" => __('Count', 'townhub-add-ons'),
                'desc'  => __( 'Number of ads to show', 'townhub-add-ons' ),
                'args' => array(
                    'default' => '2',
                )
            ),
            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'ads_search_orderby',
                "title" => __('Order AD by', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'date',
                    'options'=> townhub_addons_get_post_orderby(),
                ),
                'desc' => '', 
            ),
            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'ads_search_order',
                "title" => __('Sort Order', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'DESC',
                    'options'=> array(
                        'ASC' => __( 'Ascending order - (1, 2, 3; a, b, c)', 'townhub-add-ons' ),
                        'DESC' => __( 'Descending order - (3, 2, 1; c, b, a)', 'townhub-add-ons' ),
                    ),
                ),
                'desc' => '', 
            ),


            array(
                "type" => "section",
                'id' => 'ads_sec_sidebar',
                "title" => __( 'Listing Sidebar Page AD', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'ads_sidebar_enable',
                "title" => __('Enable/Disable', 'townhub-add-ons'),
                'desc'  => __( 'ADs on the page', 'townhub-add-ons' ),
                'args' => array(
                    'value' => 'yes',
                    'default' => 'yes',
                )
            ),
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'ads_sidebar_count',
                "title" => __('Count', 'townhub-add-ons'),
                'desc'  => __( 'Number of ads to show', 'townhub-add-ons' ),
                'args' => array(
                    'default' => '2',
                )
            ),
            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'ads_sidebar_orderby',
                "title" => __('Order AD by', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'date',
                    'options'=> townhub_addons_get_post_orderby(),
                ),
                'desc' => '', 
            ),
            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'ads_sidebar_order',
                "title" => __('Sort Order', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'DESC',
                    'options'=> array(
                        'ASC' => __( 'Ascending order - (1, 2, 3; a, b, c)', 'townhub-add-ons' ),
                        'DESC' => __( 'Descending order - (3, 2, 1; c, b, a)', 'townhub-add-ons' ),
                    ),
                ),
                'desc' => '', 
            ),

            array(
                "type" => "section",
                'id' => 'ads_sec_home',
                "title" => __( 'Elementor Listings Slider AD', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'ads_home_enable',
                "title" => __('Enable/Disable', 'townhub-add-ons'),
                'desc'  => __( 'ADs on Listings Slider', 'townhub-add-ons' ),
                'args' => array(
                    'value' => 'yes',
                    'default' => 'yes',
                )
            ),
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'ads_home_count',
                "title" => __('Count', 'townhub-add-ons'),
                'desc'  => __( 'Number of ads to show', 'townhub-add-ons' ),
                'args' => array(
                    'default' => '2',
                )
            ),
            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'ads_home_orderby',
                "title" => __('Order AD by', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'date',
                    'options'=> townhub_addons_get_post_orderby(),
                ),
                'desc' => '', 
            ),
            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'ads_home_order',
                "title" => __('Sort Order', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'DESC',
                    'options'=> array(
                        'ASC' => __( 'Ascending order - (1, 2, 3; a, b, c)', 'townhub-add-ons' ),
                        'DESC' => __( 'Descending order - (3, 2, 1; c, b, a)', 'townhub-add-ons' ),
                    ),
                ),
                'desc' => '', 
            ),

            




            array(
                "type" => "section",
                'id' => 'ads_sec_custom_grid',
                "title" => __( 'Elementor Listings Grid AD', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'ads_custom_grid_enable',
                "title" => __('Enable/Disable', 'townhub-add-ons'),
                'desc'  => __( 'ADs on Listings Grid', 'townhub-add-ons' ),
                'args' => array(
                    'value' => 'yes',
                    'default' => 'yes',
                )
            ),
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'ads_custom_grid_count',
                "title" => __('Count', 'townhub-add-ons'),
                'desc'  => __( 'Number of ads to show', 'townhub-add-ons' ),
                'args' => array(
                    'default' => '2',
                )
            ),
            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'ads_custom_grid_orderby',
                "title" => __('Order AD by', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'date',
                    'options'=> townhub_addons_get_post_orderby(),
                ),
                'desc' => '', 
            ),
            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'ads_custom_grid_order',
                "title" => __('Sort Order', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'DESC',
                    'options'=> array(
                        'ASC' => __( 'Ascending order - (1, 2, 3; a, b, c)', 'townhub-add-ons' ),
                        'DESC' => __( 'Descending order - (3, 2, 1; c, b, a)', 'townhub-add-ons' ),
                    ),
                ),
                'desc' => '', 
            ),

        ),
        // end tab array
        'single' => array(
            array(
                "type" => "section",
                'id' => 'single_thumbnail',
                "title" => __( 'Thumbnail', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'enable_img_click',
                "title" => __('Enable Thumbnail Click', 'townhub-add-ons'),
                'desc'  => '',
                'args' => array(
                    'value' => 'yes',
                    'default' => 'no',
                )
            ),
            array(
                "type" => "field",
                "field_type" => "image",
                'id' => 'default_thumbnail',
                "title" => __('Default Thumbnail', 'townhub-add-ons'),
                'desc'  => ''
            ),
            array(
                "type" => "section",
                'id' => 'single_section_1',
                "title" => __( 'Rating', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'single_show_rating',
                "title" => __('Show Rating', 'townhub-add-ons'),
                'desc'  => '',
                'args' => array(
                    'value' => '1',
                    'default' => '1',
                )
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'show_score_rating',
                "title" => __('Show Review Score total', 'townhub-add-ons'),
                'desc'  => '',
                'args' => array(
                    'value' => '1',
                    'default' => '1',
                )
            ),



            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'rating_base',
                "title" => __('Rating System', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> '5',
                    'options'=> array(
                        '5' => esc_html__('Five Stars', 'townhub-add-ons'), 
                        '10' => esc_html__('Ten Stars', 'townhub-add-ons'), 
                        
                    ),
                ),
                'desc' => '', 
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'allow_rating_imgs',
                "title" => __('Rating Allow Images', 'townhub-add-ons'),
                'desc'  => '',
                'args' => array(
                    'value' => 'yes',
                    'default' => 'yes',
                )
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'approve_booked_comment',
                "title" => __('Approve comment from users booked listing?', 'townhub-add-ons'),
                'desc'  => '',
                'args' => array(
                    'value' => 'yes',
                    'default' => 'no',
                )
            ),

            array(
                "type" => "section",
                'id' => 'single_feature',
                "title" => __( 'Features', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'feature_parent_group',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => esc_html__('Group by parent', 'townhub-add-ons'),
                'desc' => '', 

            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'single_post_nav',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => esc_html__('Show Next/Prev post Nav', 'townhub-add-ons'),
                'desc' => '', 

            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'single_same_term',
                'args'=> array(
                    'default' => '0',
                    'value' => '1',
                ),
                "title" => esc_html__('Next/Prev posts should be in same category', 'townhub-add-ons'),
                'desc' => '', 

            ),

            // array(
            //     "type" => "section",
            //     'id' => 'single_view_options',
            //     "title" => __( 'Show/Hide Content Widgets', 'townhub-add-ons' ),
            // ),

            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'single_hide_contacts_info',
            //     'args'=> array(
            //         'default' => 'no',
            //         'value' => 'yes',
            //     ),
            //     "title" => esc_html__('Hide Contact Details', 'townhub-add-ons' ),
            //     'desc'          => __('Check this to hide <strong>Contact Details</strong> on header/location widget on listing page.', 'townhub-add-ons' ),
            // ),

            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'single_hide_author_info',
            //     'args'=> array(
            //         'default' => 'no',
            //         'value' => 'yes',
            //     ),
            //     "title" => esc_html__('Hide Author Info', 'townhub-add-ons' ),
            //     'desc'          => __('Check this to hide listing author info on listing page.', 'townhub-add-ons' ),
            // ),


            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'single_hide_wkhour_widget',
            //     'args'=> array(
            //         'default' => 'no',
            //         'value' => 'yes',
            //     ),
            //     "title" => esc_html__('Hide Working Hours', 'townhub-add-ons' ),
            //     'desc'          => __('Check this to hide <strong>Working Hours</strong> widget on listing page.', 'townhub-add-ons' ),
            // ),
            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'single_hide_counter_widget',
            //     'args'=> array(
            //         'default' => 'no',
            //         'value' => 'yes',
            //     ),
            //     "title" => esc_html__('Hide Event Counter', 'townhub-add-ons' ),
            //     'desc'          => __('Check this to hide <strong>Event Counter</strong> widget on listing page.', 'townhub-add-ons' ),
            // ),
            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'single_hide_pricerange_widget',
            //     'args'=> array(
            //         'default' => 'no',
            //         'value' => 'yes',
            //     ),
            //     "title" => esc_html__('Hide Price Range', 'townhub-add-ons' ),
            //     'desc'          => __('Check this to hide <strong>Price Range</strong> widget on listing page.', 'townhub-add-ons' ),
            // ),

            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'single_hide_booking_form_widget',
            //     'args'=> array(
            //         'default' => 'no',
            //         'value' => 'yes',
            //     ),
            //     "title" => esc_html__('Hide Booking Form', 'townhub-add-ons' ),
            //     'desc'          => __('Check this to hide <strong>Booking Form</strong> widget on listing page.', 'townhub-add-ons' ),
            // ),

            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'single_hide_weather_widget',
            //     'args'=> array(
            //         'default' => 'no',
            //         'value' => 'yes',
            //     ),
            //     "title" => esc_html__('Hide Weather', 'townhub-add-ons' ),
            //     'desc'          => __('Check this to hide <strong>Weather</strong> widget on listing page.', 'townhub-add-ons' ),
            // ),

            

            
            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'single_hide_addfeatures_widget',
            //     'args'=> array(
            //         'default' => 'no',
            //         'value' => 'yes',
            //     ),
            //     "title" => esc_html__('Hide Additional Features', 'townhub-add-ons' ),
            //     'desc'          => __('Check this to hide <strong>Additional Features</strong> widget on listing page.', 'townhub-add-ons' ),
            // ),

            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'single_hide_contacts_widget',
            //     'args'=> array(
            //         'default' => 'no',
            //         'value' => 'yes',
            //     ),
            //     "title" => esc_html__('Hide Location / Contacts', 'townhub-add-ons' ),
            //     'desc'          => __('Check this to hide <strong>Location / Contacts</strong> widget on listing page.', 'townhub-add-ons' ),
            // ),

            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'single_hide_author_widget',
            //     'args'=> array(
            //         'default' => 'no',
            //         'value' => 'yes',
            //     ),
            //     "title" => esc_html__('Hide Listing Author', 'townhub-add-ons' ),
            //     'desc'          => __('Check this to hide <strong>Listing Author</strong> widget on listing page.', 'townhub-add-ons' ),
            // ),

            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'single_hide_moreauthor_widget',
            //     'args'=> array(
            //         'default' => 'no',
            //         'value' => 'yes',
            //     ),
            //     "title" => esc_html__('Hide More from Author', 'townhub-add-ons' ),
            //     'desc'          => __('Check this to hide <strong>More from Author</strong> widget on listing page.', 'townhub-add-ons' ),
            // ),

            array(
                "type" => "section",
                'id' => 'single_claim_opts',
                "title" => __( 'Listing Claim', 'townhub-add-ons' ),
                'callback' => function(){
                    echo sprintf(__( '<p>Read <a href="%s" target="_blank">Claim Listing</a> document for more details.</p>', 'townhub-add-ons' ), esc_url('https://docs.cththemes.com/docs/advance-features/claim-listing/'));
                    
                }
            ),

            

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'approve_claim_after_paid',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => esc_html__('Auto Approved', 'townhub-add-ons' ),
                'desc'          => __('Check this to make listing claim auto approved after paid.', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'report_must_login',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title"         => _x('Users must login to report listing?', 'TownHub Add-Ons', 'townhub-add-ons' ),
                'desc'          => '',
            ),

            array(
                "type" => "section",
                'id' => 'single_map_opts',
                "title" => _x('Map Options', 'TownHub Add-Ons', 'townhub-add-ons' ),
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'single_map_init',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title"         => _x('Load single page map on loading listing?', 'TownHub Add-Ons', 'townhub-add-ons' ),
                'desc'          => '',
            ),

        ),
        // end tab array
        'gmap' => townhub_addons_options_get_gmap(),
        // end tab array
        'booking' => array(
            array(
                "type" => "section",
                'id' => 'booking_sec_1',
                "title" => __( 'General', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'booking_clock_24h',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Use 24-hour format', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "color",
                'id' => 'time_picker_color',
                'args'=> array(
                    'default' => '#4DB7FE',
                ),
                "title" => __('Time picker style color', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'ck_book_logged_in',
                'args'=> array(
                    'default' => '0',
                    'value' => '1',
                ),
                "title" => _x('Users need to login to booking listing?', 'Options', 'townhub-add-ons'),
                'desc'  => '',
            ),

            

            array(
                "type" => "section",
                'id' => 'booking_sec_woo',
                "title" => __( 'WooCommerce Integration', 'townhub-add-ons' ),
            ),

            

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'woo_redirect',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Redirect to WooCommerce cart after submit booking?', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'woo_redirect_zero',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Redirect to WooCommerce for free booking ( total price is zero )?', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "number",
                'id' => 'add_cart_delay',
                "title" => __('Add booking to cart delay', 'townhub-add-ons'),
                'args' => array(
                    'default'  => '3000',
                    'min'  => '0',
                    'max'  => '86400000',
                    'step'  => '1000',
                ),
                'desc'  => __('The number of milliseconds to wait before redirecting to cart page when booking success. 0 for immediately redirect.', 'townhub-add-ons'),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'booking_author_woo',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Mark Order as complete', 'townhub-add-ons'),
                'desc'  => __('Whether listing author will also mark WooCommerce order (for selling their booking) as completed when approve booking or not?', 'townhub-add-ons'),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'woo_hide_adults',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Hide cart adults', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'woo_hide_children',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Hide cart children', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'woo_hide_infants',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Hide cart infants', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'woo_hide_ckin',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Hide cart checkin date', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'woo_hide_ckout',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Hide cart checkout date', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'woo_cancel_and_refund',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Cancel Completed/Paid bookings from front-end dashboard will refund orders?', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "section",
                'id' => 'booking_dashboard_sec',
                "title" => __( 'Dashboard Options', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'booking_author_delete',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Author Can Delete Booking', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'booking_del_trash',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Move Deleted Booking to Trash?', 'townhub-add-ons'),
                'desc'  => '',
            ),

            

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'booking_approved_cancel',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Author can canceled Completed/Paid bookings', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'booking_approved_cancel_customer',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Customer can canceled Completed/Paid bookings', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'bk_show_status',
                "title" => __('Status of booking to show on dashboard', 'townhub-add-ons'),
                'desc'  => '',
                'args'=> array(
                    'default'=> array(),
                    'options'=> array(
                        'pending' => __( 'Pending', 'townhub-add-ons' ),
                        'completed' => __( 'Completed', 'townhub-add-ons' ),
                        'canceled' => __( 'Canceled', 'townhub-add-ons' ),
                        
                    ),
                    'multiple' => true,
                    'use-select2' => true
                )
            ),


            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'bk_count_status',
                "title" => __('Count bookings status', 'townhub-add-ons'),
                'desc'  => __( 'Select booking status will deduct when calculating remaining quantity.', 'townhub-add-ons' ),
                'args'=> array(
                    'default'=> array('pending', 'completed'),
                    'options'=> array(
                        'pending' => __( 'Pending', 'townhub-add-ons' ),
                        'completed' => __( 'Completed', 'townhub-add-ons' ),
                        
                    ),
                    'multiple' => true,
                    'use-select2' => true
                )
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'calc_earning_on_author_approve',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Calculate author earning on author approved bookings?', 'townhub-add-ons'),
                'desc'  => '',
            ),

        ),
        // end tab array

        'woocommerce'   => townhub_addons_options_get_woo(),

            


        'payments' => townhub_addons_options_get_payments(),
        // end tab array
            


        // end tab array
        'emails' => townhub_addons_options_get_emails(), 
        // end tab array

        'chat'      => array(
            array(
                "type" => "section",
                'id' => 'user_chat_sec',
                "title" => __( 'Author chat', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox", 
                'id' => 'admin_chat',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Show Chat', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox", 
                'id' => 'show_fchat',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Show Chat front-end', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "number",
                'id' => 'messages_first_load',
                "title" => __('First load replies', 'townhub-add-ons'),
                'args' => array(
                    'default'  => '10',
                    'min'  => '-1',
                    'max'  => '200',
                    'step'  => '1',
                ),
                'desc'  => __('Number of replies loading first', 'townhub-add-ons'),
            ),
            // array(
            //     "type" => "field",
            //     "field_type" => "user_select",
            //     'id' => 'user_id_default_contact',
            //     "title" => __('Set user default contact', 'townhub-add-ons'),
            //     'args' => array(
            //         'default'  => 1,
            //         'default_name' => 'admin'
            //     ),
            //     'desc'  => __('User default contact', 'townhub-add-ons'),
            // ),
            
            array(
                "type" => "field",
                "field_type" => "number",
                'id' => 'messages_prev_load',
                "title" => __('Previous loading replies', 'townhub-add-ons'),
                'args' => array(
                    'default'  => '5',
                    'min'  => '1',
                    'max'  => '100',
                    'step'  => '1',
                ),
                'desc'  => __('Number of previous replies will load when user scrolling to top.', 'townhub-add-ons'),
            ),

            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'chatbox_message',
                "title" => __('Front-End chat message', 'townhub-add-ons'),
                // 'desc'  => __( 'Number of listings to show on a page (-1 for all)', 'townhub-add-ons' ),
                'args' => array(
                    'default' => 'We are here to help. Please ask us anything or share your feedback',
                )
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox", 
                'id' => 'chat_site_owner',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Allow chat to site owner?', 'townhub-add-ons'),   
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "user_select",
                'id' => 'site_owner_id',
                "title" => __('Site owner account', 'townhub-add-ons'),
                'args' => array(
                    'default'  => 1,
                    'default_name' => 'admin'
                ),
                // 'desc'  => __('User default contact', 'townhub-add-ons'),
            ),
        

        ),
        // end chat tab

        'widgets' => array(


            array(
                "type" => "section",
                'id' => 'mailchimp_sub_section',
                "title" => __( 'Mailchimp Section', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'mailchimp_api',
                "title" => __('Mailchimp API key', 'townhub-add-ons'),
                'desc'  => '<a href="'.esc_url('http://kb.mailchimp.com/accounts/management/about-api-keys#Finding-or-generating-your-API-key').'" target="_blank">'.esc_html__('Find your API key','townhub-add-ons' ).'</a>'
            ),
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'mailchimp_list_id',
                "title" => __('Mailchimp List ID', 'townhub-add-ons'),
                'desc'  => '<a href="'.esc_url('http://kb.mailchimp.com/lists/managing-subscribers/find-your-list-id').'" target="_blank">'.esc_html__('Find your list ID','townhub-add-ons' ).'</a>',
            ),
        
            array(
                "type" => "field",
                "field_type" => "info",
                'id' => 'mailchimp_shortcode',
                "title" => __('Subscribe Shortcode', 'townhub-add-ons'),
                'desc'  => wp_kses_post( __('Use the <code><strong>[townhub_subscribe]</strong></code> shortcode  to display subscribe form inside a post, page or text widget.
<br>Available Variables:<br>
<code><strong>message</strong></code> (Optional) - The message above subscription form.<br>
<code><strong>placeholder</strong></code> (Optional) - The form placeholder text.<br>
<code><strong>button</strong></code> (Optional) - The submit button text.<br>
<code><strong>list_id</strong></code> (Optional) - List ID. If you want user subscribe to a different list from the option above.<br>
<code><strong>class</strong></code> (Optional) - Your extraclass used to style the form.<br><br>
Example: <code><strong>[townhub_subscribe list_id="b02fb5f96f" class="your_class_here"]</strong></code>', 'townhub-add-ons') )
                
            ),

            array(
                "type" => "field",
                "field_type" => "page_select",
                'id' => 'sub_policy_page',
                "title" => __('Subscribe Privacy Policy ', 'townhub-add-ons'),
                'desc'  => '',
                'args' => array(
                    'default_title' => "Privacy Policy",
                )
            ),

            array(
                "type" => "section",
                'id' => 'tweets_section',
                "title" => __( 'Twitter Feeds Section', 'townhub-add-ons' ),
                'callback' => function($arg){ 
                    echo '<p>'.esc_html__('Visit ','townhub-add-ons' ).
                        '<a href="'.esc_url('https://apps.twitter.com' ).'" target="_blank">'.esc_html__("Twitter's Application Management",'townhub-add-ons' ).'</a> '
                        .__('page, sign in with your account, click on Create a new application and create your own keys if you haven\'t one.<br> Fill all the fields bellow with those keys.','townhub-add-ons' ).
                        '</p>';  
                }
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'consumer_key',
                "title" => __('Consumer Key', 'townhub-add-ons'),
                'desc'  => ''
            ),
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'consumer_secret',
                "title" => __('Consumer Secret', 'townhub-add-ons'),
                'desc'  => ''
            ),
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'access_token',
                "title" => __('Access Token', 'townhub-add-ons'),
                'desc'  => ''
            ),
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'access_token_secret',
                "title" => __('Access Token Secret', 'townhub-add-ons'),
                'desc'  => ''
            ),
            array(
                "type" => "field",
                "field_type" => "info",
                'id' => 'tweets_shortcode',
                "title" => __('Access Token Secret', 'townhub-add-ons'),
                'desc'  => wp_kses_post( __('You can use <code><strong>TownHub Twitter Feed</strong></code> widget or  <code><strong>[townhub_tweets]</strong></code> shortcode  to display tweets inside a post, page or text widget.
<br>Available Variables:<br>
<code><strong>username</strong></code> (Optional) - Option to load tweets from another account. Leave this empty to load from your own.<br>
<code><strong>list</strong></code> (Optional) - List name to load tweets from. If you define list name you also must define the <strong>username</strong> of the list owner.<br>
<code><strong>hashtag</strong></code> (Optional) - Option to load tweets with a specific hashtag.<br>
<code><strong>count</strong></code> (Required) - Number of tweets you want to display.<br>
<code><strong>list_ticker</strong></code> (Optional) - Display tweets as a list ticker?. Values: <strong>yes</strong> or <strong>no</strong><br>
<code><strong>follow_url</strong></code> (Optional) - Follow us link.<br>
<code><strong>extraclass</strong></code> (Optional) - Your extraclass used to style the form.<br><br>
Example: <code><strong>[townhub_tweets count="3" username="CTHthemes" list_ticker="no" extraclass="your_class_here"]</strong></code>', 'townhub-add-ons') )
                
            ),
            // api weather
            array(
                "type" => "section",
                'id' => 'weather_api_section',
                "title" => __( 'Weather Section', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'weather_api',
                "title" => __('Weather API key', 'townhub-add-ons'),
                'desc'  => '<a href="'.esc_url('https://openweathermap.org/api').'" target="_blank">'.esc_html__('Find your API key','townhub-add-ons' ).'</a>'
            ),
            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'weather_unit',
                "title" => __('Weather Unit', 'townhub-add-ons'),
                'desc'  => '',
                'args'=> array(
                    'default'=> 'metric',
                    'options'=> array(
                        // 'auto' => _x( 'Kelvin', 'TownHub Add-Ons', 'townhub-add-ons' ),
                        'metric' => _x( 'Celsius', 'TownHub Add-Ons', 'townhub-add-ons' ),
                        'imperial' => _x( 'Fahrenheit', 'TownHub Add-Ons', 'townhub-add-ons' ),
                        
                    ),
                    // 'multiple' => true,
                    // 'use-select2' => true
                )
            ),

            // socials share
            array(
                "type" => "section",
                'id' => 'widgets_section_3',
                "title" => __( 'Socials Share', 'townhub-add-ons' ),
            ),
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'widgets_share_names',
                "title" => __('Socials Share', 'townhub-add-ons'),
                'desc'  => __('Enter your social share names separated by a comma.<br> List bellow are available names:<strong><br> facebook,twitter,linkedin,in1,tumblr,digg,googleplus,reddit,pinterest,stumbleupon,email,telegram,instagram,whatsapp,vk and okru for Russia – Odnoklassniki</strong>', 'townhub-add-ons'),
                'args'=> array(
                    'default' => 'facebook, pinterest, googleplus, twitter, linkedin'
                ),
            ),


        ),
        // end tab array

        // end tab array
        'maintenance' => array(
            array(
                "type" => "section",
                'id' => 'maintenance_section_1',
                "title" => __( 'Status', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "radio",
                'id' => 'maintenance_mode',
                "title" => __('Mode', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'disable',
                    'options'=> array(
                        'disable' => __( 'Disable', 'townhub-add-ons' ),
                        'maintenance' => __( 'Maintenance', 'townhub-add-ons' ),
                        'coming_soon' => __( 'Coming Soon', 'townhub-add-ons' ),
                    ),
                    'options_block' => true
                )
            ),
            array(
                "type" => "section",
                'id' => 'maintenance_section_2',
                "title" => __( 'Maintenance Options', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'maintenance_msg',
                "title" => __('Message', 'townhub-add-ons'),
                'args' => array(
                    'default'  => '<h3 class="soon-title">We\'ll be right back!</h3>
<p>We are currently performing some quick updates. Leave us your email and we\'ll let you know as soon as we are back up again.</p>
[townhub_subscribe message=""]
<div class="cs-social fl-wrap">
<ul>
<li><a href="#" target="_blank" ><i class="fa fa-facebook-official"></i></a></li>
<li><a href="#" target="_blank"><i class="fa fa-twitter"></i></a></li>
<li><a href="#" target="_blank" ><i class="fa fa-chrome"></i></a></li>
<li><a href="#" target="_blank" ><i class="fa fa-vk"></i></a></li>
<li><a href="#" target="_blank" ><i class="fa fa-whatsapp"></i></a></li>
</ul>
</div>',
                ),
                'desc'  => ''
            ),

            array(
                "type" => "section",
                'id' => 'maintenance_section_3',
                "title" => __( 'Coming Soon Options', 'townhub-add-ons' ),
            ),
            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'coming_soon_msg',
                "title" => __('Message', 'townhub-add-ons'),
                'args' => array(
                    'default'  => '<h3 class="soon-title">Our website is coming soon!</h3>',
                ),
                'desc'  => ''
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'coming_soon_date',
                "title" => __('Coming Soon Date', 'townhub-add-ons'),
                'args' => array(
                    'default'  => '12/25/2021',
                ),
                'desc'  => __('The date should be MM/DD/YYYY format. Ex: 12/25/2021', 'townhub-add-ons'),
            ),
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'coming_soon_time',
                "title" => __('Coming Soon Time', 'townhub-add-ons'),
                'args' => array(
                    'default'  => '10:30:00',
                ),
                'desc'  => __('The time should be hh:mm:ss format. Ex: 10:30:00', 'townhub-add-ons'),
            ),

            array(
                "type" => "field",
                "field_type" => "number",
                'id' => 'coming_soon_tz',
                "title" => __('Timezone Offset', 'townhub-add-ons'),
                'args' => array(
                    'default'  => '0',
                    'min'  => '-12',
                    'max'  => '14',
                    'step'  => '1',
                ),
                'desc'  => __('Timezone offset value from UTC', 'townhub-add-ons'),
            ),
            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'coming_soon_msg_after',
                "title" => __('Message After', 'townhub-add-ons'),
                'args' => array(
                    'default'  => '[townhub_subscribe]
<div class="cs-social fl-wrap">
<ul>
<li><a href="#" target="_blank" ><i class="fa fa-facebook-official"></i></a></li>
<li><a href="#" target="_blank"><i class="fa fa-twitter"></i></a></li>
<li><a href="#" target="_blank" ><i class="fa fa-chrome"></i></a></li>
<li><a href="#" target="_blank" ><i class="fa fa-vk"></i></a></li>
<li><a href="#" target="_blank" ><i class="fa fa-whatsapp"></i></a></li>
</ul>
</div>',
                ),
                'desc'  => ''
            ),

            array(
                "type" => "field",
                "field_type" => "image",
                'id' => 'coming_soon_bg',
                "title" => __('Background', 'townhub-add-ons'),
                'desc'  => ''
            ),


        ),
        // end tab array



    );
}