<?php
/* add_ons_php */
azp_add_element(
    'ffeatures_select',
    array(
        'name'                    => __('Features Select', 'townhub-add-ons'),
        'desc'                    => '',
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
                'default'       => 'Facilities',
            ),
            array(
                'type'       => 'text',
                'param_name' => 'placeholder',
                'label'      => __('Placeholder text', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'    => 'All Facilities',
            ),

            array(
                'type'        => 'checkbox',
                'param_name'  => 'cats',
                // 'show_in_admin'         => true,
                'label'       => __('Features', 'townhub-add-ons'),
                'desc'        => '',
                'default'     => '',
                'value'       => townhub_addons_lcats_options(false, 'listing_feature'),
                'multiple'    => true,
                'show_toggle' => true,
            ),

            // array(
            //     'type'          => 'select',
            //     'param_name'    => 'feacols',
            //     'show_in_admin' => true,
            //     'label'         => _x('Number of features on a row', 'Listing type', 'townhub-add-ons'),
            //     // 'desc'                  => 'Select how to sort retrieved posts.',
            //     'default'       => 'four',
            //     'value'         => array(
            //         'one'   => _x('One', 'Listing type', 'townhub-add-ons'),
            //         'two'   => _x('Two', 'Listing type', 'townhub-add-ons'),
            //         'three' => _x('Three', 'Listing type', 'townhub-add-ons'),
            //         'four'  => _x('Four', 'Listing type', 'townhub-add-ons'),
            //         'five'  => _x('Five', 'Listing type', 'townhub-add-ons'),
            //         'six'   => _x('Six', 'Listing type', 'townhub-add-ons'),
            //     ),
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
