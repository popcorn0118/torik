<?php
/* add_ons_php */
azp_add_element(
    'lscrollbar_sec',
    array(
        'name'                    => __('Scroll Bar', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Navbar", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'is_section'              => true,
        'attrs'                   => array(
            array(
                'type'          => 'switch',
                'param_name'    => 'show_mobile',
                'show_in_admin' => true,
                'label'         => _x('Show on mobile?', 'Listing type', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),
            array(
                'type'        => 'repeater',
                'param_name'  => 'menus',
                // 'show_in_admin'         => true,
                'label'       => __('Menu Items', 'townhub-add-ons'),
                'desc'        => '',
                'title_field' => 'rp_text',
                'fields'      => array(
                    array(
                        'type'          => 'text',
                        'param_name'    => 'title',
                        'show_in_admin' => true,
                        'label'         => __('Title', 'townhub-add-ons'),
                        'desc'          => '',
                        'default'       => 'Details',
                    ),
                    array(
                        'type'          => 'text',
                        'param_name'    => 'sec_id',
                        'show_in_admin' => true,
                        'label'         => __('Section ID', 'townhub-add-ons'),
                        'desc'          => '',
                        'default'       => '#details_sec',
                    ),
                    array(
                        'type'          => 'switch',
                        'param_name'    => 'show_mobile',
                        'show_in_admin' => true,
                        'label'         => __('Show on mobile?', 'townhub-add-ons'),
                        // 'desc'                  => '',
                        'default'       => 'yes',
                        'value'         => array(
                            'yes' => __('Yes', 'townhub-add-ons'),
                            'no'  => __('No', 'townhub-add-ons'),
                        ),
                    ),
                    array(
                        'type'          => 'text',
                        'param_name'    => 'icon',
                        'show_in_admin' => true,
                        'label'         => __('Icon', 'townhub-add-ons'),
                        'desc'          => '',
                        'default'       => 'fal fa-info',
                    ),
                ),
                'default'     => urlencode(json_encode(array(
                    array(
                        'title'       => 'Details',
                        'sec_id'      => 'details_sec',
                        'show_mobile' => 'yes',
                        'icon'        => 'fal fa-info',
                    ),
                    array(
                        'title'       => 'Gallery',
                        'sec_id'      => 'gallery_sec',
                        'show_mobile' => 'yes',
                        'icon'        => 'fal fa-image',
                    ),
                    array(
                        'title'       => 'Menu',
                        'sec_id'      => 'menus_sec',
                        'show_mobile' => 'yes',
                        'icon'        => 'fal fa-utensils',
                    ),
                ))),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'show_addtocal',
                'show_in_admin' => true,
                'label'         => _x('Show Add to Calendar?', 'Listing type', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'hide_bookmark',
                'show_in_admin' => true,
                'label'         => __('Hide Bookmark?', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'hide_share',
                'show_in_admin' => true,
                'label'         => __('Hide Socials share?', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'hide_review',
                'show_in_admin' => true,
                'label'         => __('Hide Write a review?', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'hide_report',
                'show_in_admin' => true,
                'label'         => __('Hide Report?', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
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
