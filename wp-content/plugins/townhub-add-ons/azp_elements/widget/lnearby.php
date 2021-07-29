<?php
/* add_ons_php */
azp_add_element(
    'lnearby',
    array(
        'name'                    => __('Nearby Listings', 'townhub-add-ons'),
        // 'desc'                  => __('Custom element for adding third party shortcode','townhub-add-ons'),
        'category'                => __("Widget", 'townhub-add-ons'),
        'icon'                    => ESB_DIR_URL . 'assets/azp-eles-icon/cththemes-logo.png',
        'open_settings_on_create' => true,
        'showStyleTab'            => true,
        'showTypographyTab'       => true,
        'showAnimationTab'        => true,
        'template_folder'         => 'widget/',
        'attrs'                   => array(
            array(
                'type'          => 'text',
                'param_name'    => 'title',
                'show_in_admin' => true,
                'label'         => __('Title', 'townhub-add-ons'),
                'default'       => 'Nearby Listings',
            ),

            array(
                'type'          => 'select',
                'param_name'    => 'nearby_type',
                'show_in_admin' => true,
                'label'         => __('Related', 'townhub-add-ons'),
                // 'desc'                  => 'Select Ascending or Descending order.',
                'default'       => 'nearby-listings',
                'value'         => array(
                    'nearby-listings'       => __('Nearby listing', 'townhub-add-ons'),
                    'auto-locate'           => __('Nearby customer', 'townhub-add-ons'),
                    
                ),
            ),

            array(
                'type'          => 'text',
                'param_name'    => 'cat_ids',
                'show_in_admin' => true,
                'label'         => _x('Categories', 'Listing type', 'townhub-add-ons'),
                'desc'          => _x("Enter listing category ids to get listings from, separated by a comma. Leave empty to get from all categories.", 'Listing type', 'townhub-add-ons'),
                'default'       => '',
            ),

            array(
                'type'          => 'text',
                'param_name'    => 'posts_per_page',
                'show_in_admin' => true,
                'label'         => __('Listings to show', 'townhub-add-ons'),
                'desc'          => __("Number of listings to show (-1 for all).", 'townhub-add-ons'),
                'default'       => '4',
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
