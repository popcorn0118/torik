<?php
/* add_ons_php */
azp_add_element(
    'filter_loc',
    array(
        'name'                    => __('Location Select', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Filter", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'template_folder'         => 'filter/',
        'attrs'                   => array(
            // array(
            //     'type'          => 'text',
            //     'param_name'    => 'title',
            //     'show_in_admin' => true,
            //     'label'         => __('Title', 'townhub-add-ons'),
            //     // 'desc'                  => '',
            //     'default'       => 'Location',
            // ),
            array(
                'type'       => 'text',
                'param_name' => 'placeholder',
                'label'      => __('Placeholder text', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'    => 'All Cities',
            ),
            array(
                'type'        => 'checkbox',
                'param_name'  => 'cats',
                // 'show_in_admin'         => true,
                'label'       => __('Locations', 'townhub-add-ons'),
                'desc'        => '',
                'default'     => '',
                'value'       => townhub_addons_lcats_options(false, 'listing_location'),
                'multiple'    => true,
                'show_toggle' => true,
            ),
            array(
                'type'          => 'select',
                'param_name'    => 'max_level',
                'show_in_admin' => true,
                'label'         => __('Child location level', 'townhub-add-ons'),
                // 'desc'                  => 'Select how to sort retrieved posts.',
                'default'       => '0',
                'value'         => array(
                    '0' => __('Hide children', 'townhub-add-ons'),
                    '1' => __('Level 1', 'townhub-add-ons'),
                    '2' => __('Level 2', 'townhub-add-ons'),
                    '3' => __('Level 3', 'townhub-add-ons'),

                ),
            ),
            array(
                'type'          => 'switch',
                'param_name'    => 'hide_empty',
                'show_in_admin' => true,
                'label'         => __('Hide empty child?', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'yes',
                'value'         => array(
                    'yes' => __('Yes', 'townhub-add-ons'),
                    'no'  => __('No', 'townhub-add-ons'),
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
