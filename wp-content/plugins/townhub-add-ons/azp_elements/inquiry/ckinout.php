<?php
/* add_ons_php */
azp_add_element(
    'linquiry_ckinout',
    array(
        'name'                    => __('Check In-Out', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Booking Inquiry", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'template_folder'         => 'inquiry/',
        // 'is_section'              => true,
        'attrs'                   => array(
            
            array(
                'type'       => 'text',
                'param_name' => 'label',
                'label'      => __('Label', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => 'Check In-Out',
            ),

            array(
                'type'       => 'icon',
                'param_name' => 'icon',
                'label'      => __('Icon', 'townhub-add-ons'),
                'desc'       => '',
                'default'    => 'fal fa-calendar-check',
            ),

            array(
                'type'          => 'select',
                'param_name'    => 'format',
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
