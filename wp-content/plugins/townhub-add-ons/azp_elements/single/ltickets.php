<?php
/* add_ons_php */
azp_add_element(
    'ltickets',
    array(
        'name'                    => __('Event Tickets', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Listing", 'townhub-add-ons'),
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
                'default'       => '',
            ),
            array(
                'type'          => 'select',
                'param_name'    => 'cols',
                'show_in_admin' => true,
                'label'         => __('Columns Grid', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'two',
                'value'         => array(
                    'one'   => esc_html__('One Column', 'townhub-add-ons'),
                    'two'   => esc_html__('Two Columns', 'townhub-add-ons'),
                    'three' => esc_html__('Three Columns', 'townhub-add-ons'),
                    'four'  => esc_html__('Four Columns', 'townhub-add-ons'),
                    'five'  => esc_html__('Five Columns', 'townhub-add-ons'),
                    'six'   => esc_html__('Six Columns', 'townhub-add-ons'),
                    
                ),
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
