<?php
/* add_ons_php */

add_action( 'azp_elements_init', 'townhub_addons_add_elements' );
function townhub_addons_add_elements(){
    require_once ESB_ABSPATH.'azp_elements/azp_shortcode.php';
    require_once ESB_ABSPATH.'azp_elements/filter/form_hero.php';
    require_once ESB_ABSPATH.'azp_elements/filter/form_listing.php';
    require_once ESB_ABSPATH.'azp_elements/filter/form_advanced.php';

    require_once ESB_ABSPATH.'azp_elements/filter/filter_ltype.php';
    require_once ESB_ABSPATH.'azp_elements/filter/filter_title.php';
    require_once ESB_ABSPATH.'azp_elements/filter/filter_cat.php';
    require_once ESB_ABSPATH.'azp_elements/filter/filter_nearby.php';
    require_once ESB_ABSPATH.'azp_elements/filter/filter_loc.php';
    require_once ESB_ABSPATH.'azp_elements/filter/filter_ckinout.php';
    require_once ESB_ABSPATH.'azp_elements/filter/filter_ckin.php';
    require_once ESB_ABSPATH.'azp_elements/filter/filter_evtdate.php';
    require_once ESB_ABSPATH.'azp_elements/filter/filter_price.php';
    
    require_once ESB_ABSPATH.'azp_elements/filter/filter_persons.php';
    require_once ESB_ABSPATH.'azp_elements/filter/filter_opennow.php';
    require_once ESB_ABSPATH.'azp_elements/filter/filter_features.php';
    require_once ESB_ABSPATH.'azp_elements/filter/ffeatures_select.php';
    require_once ESB_ABSPATH.'azp_elements/filter/filter_tag.php';
    require_once ESB_ABSPATH.'azp_elements/filter/custom_field.php';
    require_once ESB_ABSPATH.'azp_elements/filter/fcus_field.php';
    
    require_once ESB_ABSPATH.'azp_elements/filter/filter_rating.php';
    require_once ESB_ABSPATH.'azp_elements/filter/filter_submit.php';


    require_once ESB_ABSPATH.'azp_elements/card/preview_listing.php';
    require_once ESB_ABSPATH.'azp_elements/card/preview_listing_content.php';

    require_once ESB_ABSPATH.'azp_elements/rcard/room.php';

    require_once ESB_ABSPATH.'azp_elements/single/lcus_field.php';
    require_once ESB_ABSPATH.'azp_elements/single/lfield_group.php';
    require_once ESB_ABSPATH.'azp_elements/single/lheader_sec.php';
    require_once ESB_ABSPATH.'azp_elements/single/lscrollbar_sec.php';
    require_once ESB_ABSPATH.'azp_elements/single/lbreadcrumbs.php';
    require_once ESB_ABSPATH.'azp_elements/single/lheadinfo.php';
    require_once ESB_ABSPATH.'azp_elements/single/lfeatured.php';
    require_once ESB_ABSPATH.'azp_elements/single/lpromovid.php';
    require_once ESB_ABSPATH.'azp_elements/single/ldescription.php';
    require_once ESB_ABSPATH.'azp_elements/single/llocations.php';
    require_once ESB_ABSPATH.'azp_elements/single/lfeatures.php';
    require_once ESB_ABSPATH.'azp_elements/single/lslider.php';
    require_once ESB_ABSPATH.'azp_elements/single/lphotos.php';
    require_once ESB_ABSPATH.'azp_elements/single/lfacts.php';
    require_once ESB_ABSPATH.'azp_elements/single/lfaqs.php';
    require_once ESB_ABSPATH.'azp_elements/single/ltickets.php';
    require_once ESB_ABSPATH.'azp_elements/single/lmembers.php';
    require_once ESB_ABSPATH.'azp_elements/single/lmembers_slider.php';
    require_once ESB_ABSPATH.'azp_elements/single/lresmenu.php';

    require_once ESB_ABSPATH.'azp_elements/single/lcalendar.php';
    require_once ESB_ABSPATH.'azp_elements/single/lmap.php';

    require_once ESB_ABSPATH.'azp_elements/single/lrooms.php';
    require_once ESB_ABSPATH.'azp_elements/single/lrooms_slider.php';

    require_once ESB_ABSPATH.'azp_elements/single/lproducts.php';


    require_once ESB_ABSPATH.'azp_elements/single/lstreetview.php';

    require_once ESB_ABSPATH.'azp_elements/single/lcomments.php';
    require_once ESB_ABSPATH.'azp_elements/single/ladslider.php';
    require_once ESB_ABSPATH.'azp_elements/single/mobile_btns.php';


    require_once ESB_ABSPATH.'azp_elements/widget/lwkhours.php';
    require_once ESB_ABSPATH.'azp_elements/widget/lcontacts.php';
    require_once ESB_ABSPATH.'azp_elements/widget/getintouch.php';
    require_once ESB_ABSPATH.'azp_elements/widget/price_range.php';
    require_once ESB_ABSPATH.'azp_elements/widget/ltags.php';
    require_once ESB_ABSPATH.'azp_elements/widget/lhostedby.php';
    require_once ESB_ABSPATH.'azp_elements/widget/lsimilar.php';
    require_once ESB_ABSPATH.'azp_elements/widget/lsimilar_slider.php';
    require_once ESB_ABSPATH.'azp_elements/widget/lnearby.php';
    require_once ESB_ABSPATH.'azp_elements/widget/weather.php';
    require_once ESB_ABSPATH.'azp_elements/widget/lcoupon.php';
    require_once ESB_ABSPATH.'azp_elements/widget/event_dates.php';


    require_once ESB_ABSPATH.'azp_elements/instant/general.php';
    require_once ESB_ABSPATH.'azp_elements/instant/rooms.php';
    require_once ESB_ABSPATH.'azp_elements/instant/rooms_new.php';

    require_once ESB_ABSPATH.'azp_elements/inquiry/general.php';
    // require_once ESB_ABSPATH.'azp_elements/inquiry/inquiry.php';
    // require_once ESB_ABSPATH.'azp_elements/inquiry/ckinout.php';

    require_once ESB_ABSPATH.'azp_elements/sroom/gallery.php';
    require_once ESB_ABSPATH.'azp_elements/sroom/facts.php';
    require_once ESB_ABSPATH.'azp_elements/sroom/features.php';
    require_once ESB_ABSPATH.'azp_elements/sroom/content.php';
    require_once ESB_ABSPATH.'azp_elements/sroom/calendar.php';
    require_once ESB_ABSPATH.'azp_elements/sroom/quantity.php';
    require_once ESB_ABSPATH.'azp_elements/sroom/button.php';


}


add_filter('azp_register_elements', 'townhub_addons_register_azp_elements');

function townhub_addons_register_azp_elements($elements)
{
    $new_elements                        = array();
    // $new_elements['azp_filter_category'] = array();
    // $new_elements['azp_ptmpl_sale']      = array();
    // $new_elements['azp_ptmpl_price']     = array();
    // $new_elements['azp_ptmpl_image']     = array();
    // $new_elements['azp_widget_contacts']     = array();
    // $new_elements['azp_widget_price_range']     = array();


    // $new_elements['azp_lheader_bgimage'] = array();
    // $new_elements['azp_lheader_bgvideo'] = array();
    // $new_elements['azp_lheader_carousel'] = array();
    // $new_elements['azp_lheader_slideshow'] = array();
    // $new_elements['azp_lscroll_nav'] = array();
    // $new_elements['azp_lgallery'] = array();
    // $new_elements['azp_lslider'] = array();
    // $new_elements['azp_lscroll_column'] = array();
    // $new_elements['azp_lpromo_video'] = [];
    // $new_elements['azp_facts'] = array();
    // $new_elements['azp_lcontent'] = array();
    // $new_elements['azp_lfeatures'] = array();
    // $new_elements['azp_ltags'] = array();
    
    // $new_elements['azp_lFAQuestion'] = array();
    // $new_elements['azp_lRooms'] = array();
    // $new_elements['azp_rooms_slider'] = array( );

    // $new_elements['azp_lcomments'] = array();

    // $new_elements['azp_lcontent_head_info'] = array();
    
    // $new_elements['azp_lbreadcrumbs'] = array();
    // $new_elements['azp_post_nav'] = array();
    // $new_elements['azp_listing_team_memeber'] = array();

    // $new_elements['azp_members_slider'] = array();

    // $new_elements['azp_availability_calendar'] = array();

    // $new_elements['azp_similar_listings'] = array();

    
    
    // $new_elements['filter_price'] = array(
    //     'name'                    => __('Filter Price', 'townhub-add-ons'),
    //     // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
    //     'category'                => __("Filter", 'townhub-add-ons'),
    //     'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
    //     'open_settings_on_create' => true,
    //     'showStyleTab'            => true,
    //     'showTypographyTab'       => true,
    //     'showAnimationTab'        => true,
    //     'attrs'                   => array(
    //         array(
    //             'type'          => 'text',
    //             'param_name'    => 'title',
    //             'show_in_admin' => true,
    //             'label'         => __('Title', 'townhub-add-ons'),
    //             'desc'          => '',
    //             'default'       => 'Price Range',
    //         ),
    //         array(
    //             'type'          => 'text',
    //             'param_name'    => 'icon',
    //             'show_in_admin' => false,
    //             'label'         => __('Icon', 'townhub-add-ons'),
    //             // 'desc'                  => '',
    //             'default'       => 'fal fa-hand-holding-usd',
    //         ),
    //         array(
    //             'type'       => 'text',
    //             'param_name' => 'rmin',
    //             'label'      => __('Min value', 'townhub-add-ons'),
    //             'desc'       => '',
    //             'default'    => '0',
    //         ),
    //         array(
    //             'type'       => 'text',
    //             'param_name' => 'rmax',
    //             'label'      => __('Max value', 'townhub-add-ons'),
    //             'desc'       => '',
    //             'default'    => '200',
    //         ),
    //         array(
    //             'type'       => 'text',
    //             'param_name' => 'rstep',
    //             'label'      => __('Step change', 'townhub-add-ons'),
    //             'desc'       => '',
    //             'default'    => '10',
    //         ),
    //         array(
    //             'type'       => 'text',
    //             'param_name' => 'rfrom',
    //             'label'      => __('Initial from  value', 'townhub-add-ons'),
    //             'desc'       => '',
    //             'default'    => '10',
    //         ),
    //         array(
    //             'type'       => 'text',
    //             'param_name' => 'rto',
    //             'label'      => __('Initial to  value', 'townhub-add-ons'),
    //             'desc'       => '',
    //             'default'    => '100',
    //         ),
    //         array(
    //             'type'          => 'select',
    //             'param_name'    => 'width',
    //             'show_in_admin' => true,
    //             'label'         => __('Width', 'townhub-add-ons'),
    //             // 'desc'                  => 'Select how to sort retrieved posts.',
    //             'default'       => '12',
    //             'value'         => array(
    //                 '12' => __('1/1', 'townhub-add-ons'),
    //                 '10' => __('5/6', 'townhub-add-ons'),
    //                 '9'  => __('3/4', 'townhub-add-ons'),
    //                 '8'  => __('2/3', 'townhub-add-ons'),
    //                 '7'  => __('7/12', 'townhub-add-ons'),
    //                 '6'  => __('1/2', 'townhub-add-ons'),
    //                 '5'  => __('5/12', 'townhub-add-ons'),
    //                 '4'  => __('1/3', 'townhub-add-ons'),
    //                 '3'  => __('1/4', 'townhub-add-ons'),
    //                 '2'  => __('1/6', 'townhub-add-ons'),
    //                 '1'  => __('1/12', 'townhub-add-ons'),

    //             ),
    //         ),
    //         array(
    //             'type'       => 'text',
    //             'param_name' => 'el_id',
    //             'label'      => __('Element ID', 'townhub-add-ons'),
    //             'desc'       => '',
    //             'default'    => '',
    //         ),

    //         array(
    //             'type'       => 'text',
    //             'param_name' => 'el_class',
    //             'label'      => __('Extra Class', 'townhub-add-ons'),
    //             'desc'       => __("Use this field to add a class name and then refer to it in your CSS.", 'townhub-add-ons'),
    //             'default'    => '',
    //         ),
    //     ),
    // );

    

    
    $new_elements['azp_sbook_room'] = array(
        'name'                    => __('Room Type', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Form Booking", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'attrs'                   => array(
            array(
                'type'          => 'text',
                'param_name'    => 'title',
                'show_in_admin' => true,
                'label'         => __('Title', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'Room Type',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'el_id',
                'label'      => __('Element ID', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'    => '',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'el_class',
                'label'      => __('Extra Class', 'townhub-add-ons'),
                'desc'       => __("Use this field to add a class name and then refer to it in your CSS.", 'townhub-add-ons'),
                'default'    => '',
            ),
        ),
    );
    $new_elements['azp_sbook_date'] = array(
        'name'                    => __('Booking Date', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Form Booking", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'attrs'                   => array(
            array(
                'type'          => 'text',
                'param_name'    => 'title',
                'show_in_admin' => true,
                'label'         => __('Title', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'When',
            ),
            array(
                'type'          => 'icon',
                'param_name'    => 'azp_icon',
                'show_in_admin' => true,
                'label'         => __('Icon Selector', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => '',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'el_id',
                'label'      => __('Element ID', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'    => '',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'el_class',
                'label'      => __('Extra Class', 'townhub-add-ons'),
                'desc'       => __("Use this field to add a class name and then refer to it in your CSS.", 'townhub-add-ons'),
                'default'    => '',
            ),
        ),
    );
    $new_elements['azp_sbook_adults'] = array(
        'name'                    => __('Adults', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Form Booking", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'attrs'                   => array(
            array(
                'type'          => 'text',
                'param_name'    => 'title',
                'show_in_admin' => true,
                'label'         => __('Title', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'Adults',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'el_id',
                'label'      => __('Element ID', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'    => '',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'el_class',
                'label'      => __('Extra Class', 'townhub-add-ons'),
                'desc'       => __("Use this field to add a class name and then refer to it in your CSS.", 'townhub-add-ons'),
                'default'    => '',
            ),
        ),
    );
    $new_elements['azp_sbook_children'] = array(
        'name'                    => __('Children', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Form Booking", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'attrs'                   => array(
            array(
                'type'          => 'text',
                'param_name'    => 'title',
                'show_in_admin' => true,
                'label'         => __('Title', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'Children',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'el_id',
                'label'      => __('Element ID', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'    => '',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'el_class',
                'label'      => __('Extra Class', 'townhub-add-ons'),
                'desc'       => __("Use this field to add a class name and then refer to it in your CSS.", 'townhub-add-ons'),
                'default'    => '',
            ),
        ),
    );
    $new_elements['azp_sbook_button'] = array(
        'name'                    => __('Booking Button', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Form Booking", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'attrs'                   => array(
            array(
                'type'          => 'text',
                'param_name'    => 'title',
                'show_in_admin' => true,
                'label'         => __('Title', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'BOOK NOW',
            ),
            array(
                'type'          => 'icon',
                'param_name'    => 'azp_icon',
                'show_in_admin' => true,
                'label'         => __('Icon Selector', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => '',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'el_id',
                'label'      => __('Element ID', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'    => '',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'el_class',
                'label'      => __('Extra Class', 'townhub-add-ons'),
                'desc'       => __("Use this field to add a class name and then refer to it in your CSS.", 'townhub-add-ons'),
                'default'    => '',
            ),
        ),
    );
    $new_elements['azp_proom_gallery'] = array(
        'name'                    => __('Gallery', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Preview Room", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'attrs'                   => array(
            array(
                'type'          => 'icon',
                'param_name'    => 'azp_icon',
                'show_in_admin' => true,
                'label'         => __('Icon Selector', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => '',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'el_id',
                'label'      => __('Element ID', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'    => '',
            ),

            array(
                'type'       => 'text',
                'param_name' => 'el_class',
                'label'      => __('Extra Class', 'townhub-add-ons'),
                'desc'       => __("Use this field to add a class name and then refer to it in your CSS.", 'townhub-add-ons'),
                'default'    => '',
            ),

        ),
    );
    $new_elements['azp_proom_features'] = array(
        'name'                    => __('Features', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Preview Room", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'attrs'                   => array(
            array(
                'type'       => 'text',
                'param_name' => 'num_feature',
                'label'      => __('Number of Features to show', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'    => '5',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'el_id',
                'label'      => __('Element ID', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'    => '',
            ),

            array(
                'type'       => 'text',
                'param_name' => 'el_class',
                'label'      => __('Extra Class', 'townhub-add-ons'),
                'desc'       => __("Use this field to add a class name and then refer to it in your CSS.", 'townhub-add-ons'),
                'default'    => '',
            ),

        ),
    );
    $new_elements['azp_proom_title'] = array(
        'name'                    => __('Title', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Preview Room", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'attrs'                   => array(
            array(
                'type'       => 'text',
                'param_name' => 'el_id',
                'label'      => __('Element ID', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'    => '',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'el_class',
                'label'      => __('Extra Class', 'townhub-add-ons'),
                'desc'       => __("Use this field to add a class name and then refer to it in your CSS.", 'townhub-add-ons'),
                'default'    => '',
            ),

        ),
    );
    $new_elements['azp_proom_content'] = array(
        'name'                    => __('Content', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Preview Room", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'attrs'                   => array(
            array(
                'type'          => 'text',
                'param_name'    => 'title',
                'show_in_admin' => true,
                'label'         => __('Title', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'Details',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'el_id',
                'label'      => __('Element ID', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'    => '',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'el_class',
                'label'      => __('Extra Class', 'townhub-add-ons'),
                'desc'       => __("Use this field to add a class name and then refer to it in your CSS.", 'townhub-add-ons'),
                'default'    => '',
            ),

        ),
    );
    $new_elements['azp_proom_price'] = array(
        'name'                    => __('Price', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Preview Room", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'attrs'                   => array(
            array(
                'type'          => 'text',
                'param_name'    => 'cur_syb',
                'show_in_admin' => true,
                'label'         => __('Currency symbol', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => '$',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'el_id',
                'label'      => __('Element ID', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'    => '',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'el_class',
                'label'      => __('Extra Class', 'townhub-add-ons'),
                'desc'       => __("Use this field to add a class name and then refer to it in your CSS.", 'townhub-add-ons'),
                'default'    => '',
            ),

        ),
    );
    $new_elements['azp_proom_guests'] = array(
        'name'                    => __('Guests', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Preview Room", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'attrs'                   => array(
            array(
                'type'       => 'text',
                'param_name' => 'el_id',
                'label'      => __('Element ID', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'    => '',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'el_class',
                'label'      => __('Extra Class', 'townhub-add-ons'),
                'desc'       => __("Use this field to add a class name and then refer to it in your CSS.", 'townhub-add-ons'),
                'default'    => '',
            ),

        ),
    );
    $new_elements['azp_proom_button'] = array(
        'name'                    => __('Room Button', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Preview Room", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'attrs'                   => array(
            array(
                'type'          => 'text',
                'param_name'    => 'bt_name',
                'show_in_admin' => true,
                'label'         => __('Button Name', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'Details',
            ),
            array(
                'type'          => 'icon',
                'param_name'    => 'bt_icon',
                'show_in_admin' => true,
                'label'         => __('Icon Selector', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => '',
            ),
            // array(
            //     'type'                  => 'text',
            //     'param_name'            => 'bt_url',
            //     'show_in_admin'         => true,
            //     'label'                 => __('Link Button','townhub-add-ons'),
            //     'desc'                  => '',
            //     'default'               => '#'
            // ),
            array(
                'type'       => 'text',
                'param_name' => 'el_id',
                'label'      => __('Element ID', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'    => '',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'el_class',
                'label'      => __('Extra Class', 'townhub-add-ons'),
                'desc'       => __("Use this field to add a class name and then refer to it in your CSS.", 'townhub-add-ons'),
                'default'    => '',
            ),
        ),
    );
    
    
    
    return $new_elements;
}



