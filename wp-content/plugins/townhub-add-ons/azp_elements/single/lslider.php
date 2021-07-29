<?php
/* add_ons_php */
azp_add_element(
    'lslider',
    array(
        'name'                    => __('Photos Slider', 'townhub-add-ons'),
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
                'param_name'    => 'images_size',
                'show_in_admin' => true,
                'label'         => _x('Images Size', 'Listing Type', 'townhub-add-ons'),
                'default'       => 'full',
                'value'         => array(
                    'full'                  => _x('Full Size', 'Listing Type', 'townhub-add-ons'),
                    'large'                 => _x('Large size, maximum 1024px', 'Listing Type', 'townhub-add-ons'),
                    'medium'                => _x('Medium size, maximum 300px', 'Listing Type', 'townhub-add-ons'),
                    'townhub-lgal'          => _x('Listing gallery size', 'Listing Type', 'townhub-add-ons'),
                    
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
