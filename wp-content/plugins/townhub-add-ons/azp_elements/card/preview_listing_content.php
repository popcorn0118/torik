<?php
/* add_ons_php */
azp_add_element(
    'preview_listing_content',
    array(
        'name'                    => __('Listing Card Content', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Preview Template", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'is_section'              => true,
        'has_children'            => true,
        'attrs'                   => array(
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
                'param_name'    => 'disable_address_url',
                'show_in_admin' => true,
                'label'         => _x('Disable address link', 'Listing type', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),
            array(
                'type'          => 'switch',
                'param_name'    => 'hide_excerpt',
                'show_in_admin' => true,
                'label'         => __('Hide Description', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),
            array(
                'type'          => 'switch',
                'param_name'    => 'hide_features',
                'show_in_admin' => true,
                'label'         => __('Hide Features', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'       => 'text',
                'param_name' => 'num_feature',
                'label'      => __('Number of Feature to show', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'    => '4',
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'show_locations',
                // 'show_in_admin' => true,
                'label'         => __('Show Locations', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'show_price',
                // 'show_in_admin' => true,
                'label'         => __('Show Price', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'yes',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),
            array(
                'type'          => 'switch',
                'param_name'    => 'show_pricerange',
                // 'show_in_admin' => true,
                'label'         => _x('Show Price range', 'Listing type', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'hide_footer',
                'show_in_admin' => true,
                'label'         => __('Hide bottom infos ( Category - Price range - Contact - Gallery )', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),
            array(
                'type'          => 'switch',
                'param_name'    => 'hide_cats',
                'show_in_admin' => true,
                'label'         => __('Hide Category', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'hide_price_range',
                'show_in_admin' => true,
                'label'         => __('Hide price range', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'hide_contacts',
                'show_in_admin' => true,
                'label'         => __('Hide contact infos', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'hide_view_map',
                'show_in_admin' => true,
                'label'         => __('Hide view on map', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'hide_gallery',
                'show_in_admin' => true,
                'label'         => __('Hide gallery', 'townhub-add-ons'),
                'desc'          => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),

            array(
                'type'          => 'switch',
                'param_name'    => 'show_web',
                // 'show_in_admin' => true,
                'label'         => _x('Show Website', 'Listing type', 'townhub-add-ons'),
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
