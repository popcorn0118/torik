<?php
/* add_ons_php */
azp_add_element(
    'fcus_field',
    array(
        'name'                    => __('Custom Field (NEW)', 'townhub-add-ons'),
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
                'type'          => 'selectcfilter',
                'param_name'    => 'fname',
                'show_in_admin' => true,
                'label'         => __('Field name', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => '',
                'value'         => array(),
            ),

            array(
                'type'          => 'select',
                'param_name'    => 'ftype',
                'show_in_admin' => true,
                'label'         => __('Field Type', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'text',
                'value'         => array(
                    'text'     => __('Text input field', 'townhub-add-ons'),
                    'number'    => __('Number input field', 'townhub-add-ons'),

                    'select' => __('Select field', 'townhub-add-ons'),
                    'multi' => __('Select multiple', 'townhub-add-ons'),
                    'checkbox' => __('Checkbox field', 'townhub-add-ons'),
                    'radio' => __('Radio field', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'add_all',
                // 'show_in_admin' => true,
                'label'         => __('Add empty option?', 'townhub-add-ons'),
                'desc'          => __('For Select and Select multiple field', 'townhub-add-ons'),
                'default'       => 'no',
                'value'         => array(
                    'yes' => __('Yes', 'townhub-add-ons'),
                    'no'  => __('No', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'text',
                'param_name'    => 'all_label',
                // 'show_in_admin' => true,
                'label'         => __('Empty option label', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'Select an option',
            ),

            array(
                'type'          => 'text',
                'param_name'    => 'title',
                'show_in_admin' => true,
                'label'         => __('Title', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'Title',
            ),
            
            array(
                'type'          => 'text',
                'param_name'    => 'icon',
                'show_in_admin' => false,
                'label'         => __('Icon', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'fal fa-map-marker-question',
            ),
            array(
                'type'          => 'switch',
                'param_name'    => 'ficon_before',
                'show_in_admin' => true,
                'label'         => __('Icon before title?', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),
            array(
                'type'          => 'text',
                'param_name'    => 'placeholder',
                'show_in_admin' => false,
                'label'         => __('Placeholder Text', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'Search for?',
            ),
            
            // array(
            //     'type'        => 'repeater',
            //     'param_name'  => 'options',
            //     // 'show_in_admin'         => true,
            //     'label'       => __('Field Options (Select and Checkbox type)', 'townhub-add-ons'),
            //     'desc'        => '',
            //     'title_field' => 'title',
            //     'fields'      => array(
            //         array(
            //             'type'          => 'text',
            //             'param_name'    => 'title',
            //             'show_in_admin' => true,
            //             'label'         => __('Title', 'townhub-add-ons'),
            //             'desc'          => '',
            //             'default'       => 'Option Title',
            //         ),
            //         array(
            //             'type'          => 'text',
            //             'param_name'    => 'value',
            //             'show_in_admin' => true,
            //             'label'         => __('Value', 'townhub-add-ons'),
            //             'desc'          => '',
            //             'default'       => 'value_text',
            //         ),
            //     ),
            //     'default'     => urlencode(json_encode(array())),
            // ),
            
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
