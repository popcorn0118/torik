<?php
/* add_ons_php */
azp_add_element(
    'ladslider',
    array(
        'name'                    => __('Listings AD', 'townhub-add-ons'),
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
                'default'       => 'Listings AD',
            ),
            array(
                'type'          => 'switch',
                'param_name'    => 'hide_bookmark',
                'show_in_admin' => true,
                'label'         => __('Hide Bookmark', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),

                ),
            ),
            array(
                'type'          => 'switch',
                'param_name'    => 'hide_status',
                'show_in_admin' => true,
                'label'         => __('Hide Open/Closed status', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),

                ),
            ),
            array(
                'type'          => 'switch',
                'param_name'    => 'hide_featured',
                'show_in_admin' => true,
                'label'         => __('Hide Featured', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),

                ),
            ),
            array(
                'type'          => 'switch',
                'param_name'    => 'hide_author',
                'show_in_admin' => true,
                'label'         => __('Hide Author', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),

                ),
            ),
            array(
                'type'          => 'switch',
                'param_name'    => 'hide_address',
                'show_in_admin' => true,
                'label'         => __('Hide Address', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),

                ),
            ),
            array(
                'type'          => 'switch',
                'param_name'    => 'hide_rating',
                'show_in_admin' => true,
                'label'         => __('Hide Rating', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),

                ),
            ),
            array(
                'type'          => 'switch',
                'param_name'    => 'hide_pricerange',
                'show_in_admin' => true,
                'label'         => __('Hide Price range', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),

                ),
            ),
            // array(
            //     'type'          => 'select',
            //     'param_name'    => 'order_by',
            //     'show_in_admin' => true,
            //     'label'         => __('Order by', 'townhub-add-ons'),
            //     'desc'          => 'Select how to sort retrieved posts.',
            //     'default'       => 'date',
            //     'value'         => array(
            //         'date'     => __('Date', 'townhub-add-ons'),
            //         'ID'       => __('ID', 'townhub-add-ons'),
            //         'author'   => __('Author', 'townhub-add-ons'),
            //         'title'    => __('Title', 'townhub-add-ons'),
            //         'modified' => __('Modified', 'townhub-add-ons'),
            //         'rand'     => __('Random', 'townhub-add-ons'),
            //         // 'comment_count' => __('Comment Count', 'townhub-add-ons'),
            //         // 'menu_order'    => __('Menu Order', 'townhub-add-ons'),
            //         // 'post__in'      => __('ID order given (post__in)', 'townhub-add-ons')
            //     ),
            // ),
            // array(
            //     'type'          => 'select',
            //     'param_name'    => 'order',
            //     'show_in_admin' => true,
            //     'label'         => __('Sort Order', 'townhub-add-ons'),
            //     'desc'          => 'Select Ascending or Descending order.',
            //     'default'       => 'DESC',
            //     'value'         => array(
            //         'ASC'  => __('Ascending', 'townhub-add-ons'),
            //         'DESC' => __('Descending', 'townhub-add-ons'),
            //     ),
            // ),
            array(
                "type"       => "text",
                "label"      => esc_html__("Responsive", 'townhub-add-ons'),
                "param_name" => "responsive",
                "desc"       => esc_html__("The format is: screen-size:number-items-display,larger-screen-size:number-items-display. Ex: 320:1,768:2,1500:3", 'townhub-add-ons'),
                "default"    => "320:1,768:1,1500:2",
            ),
            array(
                'type'          => 'switch',
                'param_name'    => 'hide_nav',
                'show_in_admin' => true,
                'label'         => __('Hide Navigation', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),

                ),
            ),
            array(
                'type'          => 'switch',
                'param_name'    => 'autoplay',
                'show_in_admin' => true,
                'label'         => __('Autoplay', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),

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
