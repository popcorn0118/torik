<?php
/* add_ons_php */
azp_add_element(
    'lbook_rooms',
    array(
        'name'                    => __('Hotel Rooms Booking', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Instant Booking", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'template_folder'         => 'instant/',
        'attrs'                   => array(
            array(
                'type'          => 'text',
                'param_name'    => 'title',
                'show_in_admin' => true,
                'label'         => __('Title', 'townhub-add-ons'),
                'default'       => 'Rooms Booking',
            ),
            array(
                'type'          => 'switch',
                'param_name'    => 'hide_not_claimed',
                'show_in_admin' => true,
                'label'         => _x('Hide on not claimed listing?', 'Listing type', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),
            array(
                'type'        => 'checkbox',
                'param_name'  => 'hide_widget_on',
                // 'show_in_admin'         => true,
                'label'       => __('Hide this widget on', 'townhub-add-ons'),
                'desc'        => __('Hide on logout user or based author plan?', 'townhub-add-ons'),
                'default'     => '',
                'value'       => townhub_addons_loggedin_plans_options(),
                'multiple'    => true,
                'show_toggle' => true,
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'quanity_lservices',
                'label'         => _x('Allow quantity for Extra Services?','Listing type', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'yes',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'       => 'textarea',
                'param_name' => 'ad_note',
                'label'      => _x('Additional note','Listing type', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => 'The total cost above doesn\'t include 10% service fee.',
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'as_inquiry',
                'label'         => _x('Use as Inquiry form?','Listing type', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),


            array(
                'type'       => 'text',
                'param_name' => 'el_id',
                'label'      => __('Element ID', 'townhub-add-ons'),
                'desc'       => '',
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
    )
);
