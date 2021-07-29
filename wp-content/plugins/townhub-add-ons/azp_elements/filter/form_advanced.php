<?php
/* add_ons_php */
azp_add_element(
    'filter_form_advanced',
    array(
        'name'                    => __('List Advanced Search', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => 'Filter Form',
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'is_section'              => true,
        'has_children'            => true,
        'template_folder'         => 'filter/',
        'attrs'                   => array(

            array(
                'type'          => 'text',
                'param_name'    => 'title',
                'show_in_admin' => true,
                'label'         => __('Title', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'More Filters',
            ),
            array(
                'type'          => 'text',
                'param_name'    => 'icon',
                'show_in_admin' => true,
                'label'         => __('Icon', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'fal fa-tasks',
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
