<?php
/* add_ons_php */
azp_add_element(
    'filter_tag',
    array(
        'name'                    => __('Listing Tags (NEW)', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => 'Filter',
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'template_folder'         => 'filter/',
        'attrs'                   => array(
            array(
                'type'                  => 'text',
                'param_name'            => 'wid_title',
                'label'                 => __('Title','townhub-add-ons'),
                // 'desc'                  => '',
                'default'               => 'Filter by Tags'
            ),
            array(
                'type'                  => 'checkbox',
                'param_name'            => 'tags',
                // 'show_in_admin'         => true,
                'label'                 => __('Filter tags','townhub-add-ons'),
                // 'desc'                  => __('Hide on logout user or based author plan?','townhub-add-ons'),
                'default'               => '',
                'value'                 => townhub_addons_listing_ltags_options(),
                'multiple'              => true,
                'show_toggle'           => true,
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

$new_elements['azp_filter_ltags'] = array(
        'name'                  => __('Listing Tags (NEW)','townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'              => __("Filter",'townhub-add-ons'),
        'icon'                  => ESB_DIR_URL .'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create'=>true,
        'showStyleTab'=> true,
        'showTypographyTab'=> true,
        'showAnimationTab'=> true,
        'attrs' => array (
            
        )
    );

