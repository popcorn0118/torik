<?php
/* add_ons_php */
azp_add_element(
    'event_dates',
    array(
        'name'                    => __('Event Dates (NEW)', 'townhub-add-ons'),
        'category'                => 'Widget',
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'template_folder'         => 'widget/',
        'attrs'                   => array(
            array(
                'type'                  => 'text',
                'param_name'            => 'title',
                'label'                 => __('Widget Title','townhub-add-ons'),
                // 'desc'                  => '',
                'default'               => 'Event Dates'
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'dates_to_show',
                'label'                 => __('Number of first dates to show?','townhub-add-ons'),
                // 'desc'                  => '',
                'default'               => '3'
            ),
            array(
                'type'                  => 'switch',
                'param_name'            => 'show_end',
                'show_in_admin'         => true,
                'label'                 => __('Show end date','townhub-add-ons'),
                'desc'                  => '',
                'default'               => 'yes',
                'value'                 => array(   
                    'yes'          => __('Yes', 'townhub-add-ons'), 
                    'no'            => __('No', 'townhub-add-ons'), 
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
