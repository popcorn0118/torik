<?php
/* add_ons_php */
azp_add_element(
    'lcus_field',
    array(
        'name'                    => __('Custom Field', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Custom", 'townhub-add-ons'),
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
                'default'       => 'Title',
            ),
            array(
                'type'          => 'selectcfield',
                'param_name'    => 'name',
                'show_in_admin' => true,
                'label'         => __('Field name', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => '',
                'value'         => array(),
            ),
            array(
                'type'          => 'switch',
                'param_name'    => 'show_opt_lbl',
                'show_in_admin' => true,
                'label'         => __('Display option label for Select/Radio/Checkbox field', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'yes',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'select',
                'param_name'    => 'ftype',
                'show_in_admin' => true,
                'label'         => _x('Field Type', 'Listing Type', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'dflt',
                'value'         => array(
                    'dflt'          => _x('Default field', 'Listing Type', 'townhub-add-ons'),
                    'image'         => _x('Image field', 'Listing Type', 'townhub-add-ons'),
                    'oembed'        => _x('Auto embed video', 'Listing Type', 'townhub-add-ons'),
                    'link'          => _x('Link field', 'Listing Type', 'townhub-add-ons'),
                    'file'          => _x('File url', 'Listing Type', 'townhub-add-ons'),
                    'raw_text'      => _x('HTML Code', 'Listing Type', 'townhub-add-ons'),
                    'dlfile'        => _x('View file button', 'Listing Type', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'title_block',
                'show_in_admin' => true,
                'label'         => __('Separate title and value into two lines', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
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
                'type'          => 'switch',
                'param_name'    => 'use_sec_style',
                // 'show_in_admin' => true,
                'label'         => _x('Use section style', 'Listing type', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'        => 'checkbox',
                'param_name'  => 'hide_widget_on',
                'label'       => _x('Hide this widget on', 'Listing type', 'townhub-add-ons'),
                'desc'        => _x('Hide on logout user or based author plan?', 'Listing type', 'townhub-add-ons'),
                'default'     => '',
                'value'       => townhub_addons_loggedin_plans_options(),
                'multiple'    => true,
                'show_toggle' => true,
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
