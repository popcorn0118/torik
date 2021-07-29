<?php
/* add_ons_php */
azp_add_element(
    'linquiry_general',
    array(
        'name'                    => __('General Inquiry', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Booking Inquiry", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'template_folder'         => 'inquiry/',
        'attrs'                   => array(
            array(
                'type'          => 'text',
                'param_name'    => 'title',
                'show_in_admin' => true,
                'label'         => __('Title', 'townhub-add-ons'),
                'default'       => 'Booking Inquiry',
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
                'param_name'    => 'hide_if_no_price',
                // 'show_in_admin' => true,
                'label'         => _x('Hide when there is no price set?', 'Listing Type', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),



            // array(
            //     'type'       => 'select',
            //     'param_name' => 'bprice',
            //     // 'show_in_admin'         => true,
            //     'label'      => __('Price Based', 'townhub-add-ons'),
            //     'desc'       => '',
            //     'default'    => 'per_night',
            //     'value'      => array(
            //         'per_person'   => __('Per person', 'townhub-add-ons'),
            //         'per_night'    => __('Per night', 'townhub-add-ons'),
            //         'night_person' => __('Per person/night', 'townhub-add-ons'),
            //         'per_day'      => __('Per day', 'townhub-add-ons'),
            //         'day_person'   => __('Per person/day', 'townhub-add-ons'),
            //         'listing'   => __('Per listing', 'townhub-add-ons'),
            //         'none'         => __('No listing price', 'townhub-add-ons'),

            //     ),
            // ),

            array(
                'type'          => 'switch',
                'param_name'    => 'show_name',
                'show_in_admin' => true,
                'label'         => __('Show name on logged in user?', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'show_email',
                'show_in_admin' => true,
                'label'         => __('Show email on logged in user?', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'show_phone',
                'show_in_admin' => true,
                'label'         => __('Show phone on logged in user?', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'yes',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'       => 'switch',
                'param_name' => 'checkin_show',
                // 'show_in_admin'         => true,
                'label'      => __('Show Checkin?', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => '1',
                'value'      => array(
                    '1' => __('Yes', 'townhub-add-ons'),
                    '0' => __('No', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'hide_dfdate',
                // 'show_in_admin' => true,
                'label'         => _x('Hide current checkin date value?', 'Listing Type', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'ckin_required',
                // 'show_in_admin' => true,
                'label'         => _x('Require checkin for booking?', 'Listing Type', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'hide_dmetas',
                // 'show_in_admin' => true,
                'label'         => _x('Hide date metas?', 'Listing Type', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'       => 'switch',
                'param_name' => 'checkout_show',
                // 'show_in_admin'         => true,
                'label'      => __('Show Checkout?', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => '1',
                'value'      => array(
                    '1' => __('Yes', 'townhub-add-ons'),
                    '0' => __('No', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'select',
                'param_name'    => 'dates_source',
                // 'show_in_admin' => true,
                'label'         => _x('Checkin/out dates from','Listing type', 'townhub-add-ons'),
                // 'desc'                  => 'Select Ascending or Descending order.',
                'default'       => 'listing_dates',
                'value'         => array(
                    ''                  => _x('None','Listing type', 'townhub-add-ons'),
                    'listing_dates'     => _x('Available Dates (NEW)','Listing type', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'       => 'text',
                'param_name' => 'dlabel',
                'label'      => __('Date picker label', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => 'Date',
            ),

            array(
                'type'       => 'icon',
                'param_name' => 'dicon',
                'label'      => __('Date picker icon', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => 'fal fa-calendar-check',
            ),

            array(
                'type'       => 'text',
                'param_name' => 'cin_plhd',
                'label'      => __('Checkin placeholder', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => '',
            ),

            array(
                'type'          => 'select',
                'param_name'    => 'dformat',
                'show_in_admin' => true,
                'label'         => __('Date Format', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'DD/MM/YYYY',
                'value'         => array(
                    'DD-MM-YYYY' => __('28-02-2019', 'townhub-add-ons'),
                    'DD/MM/YYYY' => __('28/02/2019', 'townhub-add-ons'),

                    'MM-DD-YYYY' => __('02-28-2019', 'townhub-add-ons'),
                    'MM/DD/YYYY' => __('02/28/2019', 'townhub-add-ons'),

                    'YYYY-MM-DD' => __('2019-02-28', 'townhub-add-ons'),
                    'YYYY/MM/DD' => __('2019/02/28', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'       => 'switch',
                'param_name' => 'slots_show',
                // 'show_in_admin'         => true,
                'label'      => __('Show Time Slots?', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => '0',
                'value'      => array(
                    '1' => __('Yes', 'townhub-add-ons'),
                    '0' => __('No', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'       => 'text',
                'param_name' => 'sllable',
                'label'      => __('Time slots label', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => 'Time Slots',
            ),

            array(
                'type'       => 'switch',
                'param_name' => 'slots_single',
                // 'show_in_admin'         => true,
                'label'      => __('Single Slot?', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => 'no',
                'value'      => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'       => 'switch',
                'param_name' => 'tpicker_show',
                // 'show_in_admin'         => true,
                'label'      => __('Show Time Picker?', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => '1',
                'value'      => array(
                    '1' => __('Yes', 'townhub-add-ons'),
                    '0' => __('No', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'       => 'text',
                'param_name' => 'tlabel',
                'label'      => __('Time picker label', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => 'Time',
            ),

            array(
                'type'          => 'select',
                'param_name'    => 'tformat',
                'show_in_admin' => true,
                'label'         => __('Time Format', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'H:i:s',
                'value'         => array(
                    'g:i a' => __('8:30 am', 'townhub-add-ons'),
                    'g:i A' => __('8:30 AM', 'townhub-add-ons'),
                    'h:i a' => __('08:30 am', 'townhub-add-ons'),
                    'h:i A' => __('08:30 AM', 'townhub-add-ons'),
                    'G:i:s' => __('8:30:00 (24-hour)', 'townhub-add-ons'),
                    'H:i:s' => __('08:30:00 (24-hour)', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'       => 'icon',
                'param_name' => 'ticon',
                'label'      => __('Time picker icon', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => 'fal fa-clock',
            ),

            array(
                'type'       => 'switch',
                'param_name' => 'adult_show',
                // 'show_in_admin'         => true,
                'label'      => __('Show Adults?', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => '1',
                'value'      => array(
                    '1' => __('Yes', 'townhub-add-ons'),
                    '0' => __('No', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'       => 'text',
                'param_name' => 'adult_lbl',
                'label'      => __('Adults field label', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => 'Adults',
            ),

            array(
                'type'       => 'text',
                'param_name' => 'adult_desc',
                'label'      => __('Adults field description', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => 'Age 18+',
            ),

            array(
                'type'       => 'switch',
                'param_name' => 'child_show',
                // 'show_in_admin'         => true,
                'label'      => __('Show Children?', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => '1',
                'value'      => array(
                    '1' => __('Yes', 'townhub-add-ons'),
                    '0' => __('No', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'       => 'text',
                'param_name' => 'child_lbl',
                'label'      => __('Children field label', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => 'Children',
            ),

            array(
                'type'       => 'text',
                'param_name' => 'child_desc',
                'label'      => __('Children field description', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => 'Age 6-17',
            ),

            array(
                'type'       => 'switch',
                'param_name' => 'infant_show',
                // 'show_in_admin'         => true,
                'label'      => __('Show Infant?', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => '1',
                'value'      => array(
                    '1' => __('Yes', 'townhub-add-ons'),
                    '0' => __('No', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'       => 'text',
                'param_name' => 'infant_lbl',
                'label'      => __('Infant field label', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => 'Infant',
            ),

            array(
                'type'       => 'text',
                'param_name' => 'infant_desc',
                'label'      => __('Infant field description', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => 'Age 0-5',
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'show_notes',
                'show_in_admin' => true,
                'label'         => __('Show Additional Infos', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),
            

            

            array(
                'type'          => 'switch',
                'param_name'    => 'show_quantity',
                'label'         => __('Show Quantity', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'       => 'text',
                'param_name' => 'qtt_lbl',
                'label'      => __('Quantity field label', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => 'Quantity',
            ),

            array(
                'type'       => 'text',
                'param_name' => 'qtt_desc',
                'label'      => __('Quantity field description', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => '',
            ),

            array(
                'type'       => 'text',
                'param_name' => 'qtt_default',
                'label'      => __('Default quantity value', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => '0',
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'show_menu',
                'show_in_admin' => true,
                'label'         => __('Show Menu', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),
            array(
                'type'       => 'text',
                'param_name' => 'menu_lbl',
                'label'      => __('Menu label', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => 'Menu',
            ),

            array(
                'type'       => 'text',
                'param_name' => 'menu_desc',
                'label'      => __('Menu description', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => '',
            ),

            array(
                'type'          => 'select',
                'param_name'    => 'show_tickets',
                // 'show_in_admin' => true,
                'label'         => _x('Show Event Tickets','Listing type', 'townhub-add-ons'),
                // 'desc'                  => 'Select Ascending or Descending order.',
                'default'       => 'single_day',
                'value'         => array(
                    'none'           => _x('None','Listing type', 'townhub-add-ons'),
                    'single_day'     => _x('For single day event','Listing type', 'townhub-add-ons'),
                    'multi_day'      => _x('For multiple days event','Listing type', 'townhub-add-ons'),
                ),
            ),
            array(
                'type'       => 'text',
                'param_name' => 'ticket_lbl',
                'label'      => _x('Tickets label','Listing type', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => 'Select tickets',
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'show_lservices',
                'label'         => __('Show Extra Services?', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'yes',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'quanity_lservices',
                'label'         => __('Allow quantity for Extra Services?', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'yes',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'show_total_cost',
                // 'show_in_admin' => true,
                'label'         => __('Show Total Cost', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'yes',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),
            array(
                'type'          => 'switch',
                'param_name'    => 'show_upload_cv',
                // 'show_in_admin' => true,
                'label'         => __('Show upload CV field', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
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
