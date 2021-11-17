<?php
/* add_ons_php */
azp_add_element(
    'mobile_btns',
    array(
        'name'                    => __('Mobile Buttons', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => "Listing",
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'template_folder'         => 'single/',
        'attrs'                   => array(
            array(
                'type'                  => 'switch',
                'param_name'            => 'show_phone',
                'show_in_admin'         => true,
                'label'                 => __('Show phone','townhub-add-ons'),
                'desc'                  => '',
                'default'               => 'yes',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),
            array(
                'type'                  => 'switch',
                'param_name'            => 'show_email',
                'show_in_admin'         => true,
                'label'                 => __('Show email','townhub-add-ons'),
                'desc'                  => '',
                'default'               => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),
            array(
                'type'                  => 'switch',
                'param_name'            => 'show_direction',
                'show_in_admin'         => true,
                'label'                 => __('Show get direction','townhub-add-ons'),
                'desc'                  => '',
                'default'               => 'yes',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'el_id',
                'label'                 => __('Element ID','townhub-add-ons'),
                // 'desc'                  => '',
                'default'               => ''
            ),
            
            array(
                'type'                  => 'text',
                'param_name'            => 'el_class',
                'label'                 => __('Extra Class','townhub-add-ons'),
                'desc'                  => __("Use this field to add a class name and then refer to it in your CSS." ,'townhub-add-ons'),
                'default'               => ''
            ),
        ),
    )
);
