<?php
/* add_ons_php */
azp_add_element(
    'filter_evtdate',
    array(
        'name'                    => __('Event Date', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Filter", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'template_folder'         => 'filter/',
        'attrs'                   => array(
            array(
                'type'          => 'text',
                'param_name'    => 'title',
                'show_in_admin' => true,
                'label'         => __('Title', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => '',
            ),
            array(
                'type'          => 'text',
                'param_name'    => 'icon',
                'show_in_admin' => false,
                'label'         => __('Icon', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'fal fa-calendar',
            ),
            array(
                'type'          => 'text',
                'param_name'    => 'placeholder',
                'show_in_admin' => false,
                'label'         => __('Placeholder Text', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'Event Date',
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
                'type'          => 'select',
                'param_name'    => 'width',
                'show_in_admin' => true,
                'label'         => __('Width', 'townhub-add-ons'),
                // 'desc'                  => 'Select how to sort retrieved posts.',
                'default'       => '12',
                'value'         => array(
                    '12' => __('1/1', 'townhub-add-ons'),
                    '10' => __('5/6', 'townhub-add-ons'),
                    '9'  => __('3/4', 'townhub-add-ons'),
                    '8'  => __('2/3', 'townhub-add-ons'),
                    '7'  => __('7/12', 'townhub-add-ons'),
                    '6'  => __('1/2', 'townhub-add-ons'),
                    '5'  => __('5/12', 'townhub-add-ons'),
                    '4'  => __('1/3', 'townhub-add-ons'),
                    '3'  => __('1/4', 'townhub-add-ons'),
                    '2'  => __('1/6', 'townhub-add-ons'),
                    '1'  => __('1/12', 'townhub-add-ons'),

                ),
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
    )
);
