<?php
/* add_ons_php */
azp_add_element(
    'azp_shortcode',
    array(
        'name'                    => __('Shortcode', 'townhub-add-ons'),
        'desc'                    => __('Custom element for adding third party shortcode', 'townhub-add-ons'),
        'category'                => __("content", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/text-block.png',
        'open_settings_on_create' => true,
        // 'showStyleTab'=> true,
        // 'showTypographyTab'=> true,
        // 'showAnimationTab'=> true,azp_widget_content_info
        'attrs'                   => array(
            array(
                'type'          => 'textarea',
                'param_name'    => 'content',
                'label'         => __('Shortcode Content', 'townhub-add-ons'),
                'show_in_admin' => true,
                'desc'          => '',
                'default'       => '',
                'iscontent'     => 'yes',
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
