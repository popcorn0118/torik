<?php
/* add_ons_php */
azp_add_element(
    'lfield_group',
    array(
        'name'                    => _x('Custom field group', 'Listing type', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => "Listing",
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'has_children'            => true,
        'template_folder'         => 'single/',
        'attrs'                   => array(
            array(
                'type'                  => 'text',
                'param_name'            => 'title',
                'show_in_admin'         => true,
                'label'                 => _x('Title', 'Listing type', 'townhub-add-ons'),
                'default'               => '',
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
                'type'                  => 'text',
                'param_name'            => 'el_id',
                'label'                 => _x('Element ID', 'Listing type', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'               => '',
            ),

            array(
                'type'                  => 'text',
                'param_name'            => 'el_class',
                'label'                 => _x('Extra Class', 'Listing type', 'townhub-add-ons'),
                'desc'                  => _x("Use this field to add a class name and then refer to it in your CSS.", 'Listing type', 'townhub-add-ons'),
                'default'               => '',
            ),

        ),
    )
);
