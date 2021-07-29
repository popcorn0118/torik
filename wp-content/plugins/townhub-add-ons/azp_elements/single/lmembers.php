<?php
/* add_ons_php */
azp_add_element(
    'lmembers',
    array(
        'name'                    => __('Trainers/Speakers', 'townhub-add-ons'),
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
                'default'       => 'Event Speakers',
            ),

            

            array(
                'type'          => 'select',
                'param_name'    => 'cols',
                'show_in_admin' => true,
                'label'         => __('Columns Grid', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'three',
                'value'         => array(
                    'one'   => esc_html__('One Column', 'townhub-add-ons'),
                    'two'   => esc_html__('Two Columns', 'townhub-add-ons'),
                    'three' => esc_html__('Three Columns', 'townhub-add-ons'),
                    'four'  => esc_html__('Four Columns', 'townhub-add-ons'),
                    'five'  => esc_html__('Five Columns', 'townhub-add-ons'),
                    'six'   => esc_html__('Six Columns', 'townhub-add-ons'),
                    'seven' => esc_html__('Seven Columns', 'townhub-add-ons'),
                    'eight' => esc_html__('Eight Columns', 'townhub-add-ons'),
                    'nine'  => esc_html__('Nine Columns', 'townhub-add-ons'),
                    'ten'   => esc_html__('Ten Columns', 'townhub-add-ons'),
                ),
            ),
            array(
                'type'          => 'select',
                'param_name'    => 'space',
                'show_in_admin' => true,
                'label'         => __('Space', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'small',
                'value'         => array(
                    'xxbig'      => esc_html__('Extra Big', 'townhub-add-ons'),
                    'xbig'       => esc_html__('Bigger', 'townhub-add-ons'),
                    'big'        => esc_html__('Big', 'townhub-add-ons'),
                    'medium'     => esc_html__('Medium', 'townhub-add-ons'),
                    'small'      => esc_html__('Small', 'townhub-add-ons'),
                    'extrasmall' => esc_html__('Extra Small', 'townhub-add-ons'),
                    'no'         => esc_html__('None', 'townhub-add-ons'),
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
