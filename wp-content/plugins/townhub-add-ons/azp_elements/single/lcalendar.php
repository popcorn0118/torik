<?php
/* add_ons_php */
azp_add_element(
    'lcalendar',
    array(
        'name'                    => __('Availability Calendar', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Listing", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'template_folder'         => 'single/',
        'attrs'                   => array(
            array(
                'type'          => 'text',
                'param_name'    => 'title',
                'show_in_admin' => true,
                'label'         => __('Title', 'townhub-add-ons'),
                'default'       => 'Available Dates',
            ),
            
            array(
                'type'          => 'select',
                'param_name'    => 'dates_source',
                'show_in_admin' => true,
                'label'         => __('Dates from', 'townhub-add-ons'),
                // 'desc'                  => 'Select Ascending or Descending order.',
                'default'       => 'listing_dates',
                'value'         => array(
                    'listing_dates'    => __('Available Dates (NEW)', 'townhub-add-ons'),
                    // 'tour_calendar_metas'   => __('Tour Dates', 'townhub-add-ons'),
                    'hotel_room_dates' => __('Hotel Room Dates', 'townhub-add-ons'),
                    // 'house_dates'           => __('House Dates', 'townhub-add-ons'),

                    // 'event_dates'           => __('Event Dates', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'single_select',
                'show_in_admin' => true,
                'label'         => __('Single date selection?', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'yes',
                'value'         => array(
                    'yes' => __('Yes', 'townhub-add-ons'),
                    'no'  => __('No', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'hide_if_empty',
                'show_in_admin' => true,
                'label'         => __('Hide widget if empty dates?', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            

            

            array(
                'type'       => 'text',
                'param_name' => 'showing',
                'label'      => __('Months to show', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'    => '2',
            ),

            array(
                'type'       => 'text',
                'param_name' => 'max',
                'label'      => __('Max Months', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'    => '12',
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'show_min_nights',
                // 'show_in_admin' => true,
                'label'         => _x('Show Minimum Nights', 'Listing Type', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'yes',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'                  => 'checkbox',
                'param_name'            => 'hide_widget_on',
                'label'                 => _x('Hide this widget on', 'Listing type', 'townhub-add-ons'),
                'desc'                  => _x('Hide on logout user or based author plan?', 'Listing type', 'townhub-add-ons'),
                'default'               => '',
                'value'                 => townhub_addons_loggedin_plans_options(),
                'multiple'              => true,
                'show_toggle'           => true,
            ),

            array(
                'type'       => 'text',
                'param_name' => 'scroll_ele_id',
                'label'      => _x('Element ID will scroll to when dates selected', 'Listing type', 'townhub-add-ons'),
                'desc'       => '<a href="'.ESB_DIR_URL . 'assets/images/ele-id.jpg'.'" target="_blank">What is the Element ID?<a>',
                'default'    => 'widget-general-booking',
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
