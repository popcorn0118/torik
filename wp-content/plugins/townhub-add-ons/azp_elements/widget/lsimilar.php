<?php
/* add_ons_php */
azp_add_element(
    'lsimilar',
    array(
        'name'                    => __('Similar Listings', 'townhub-add-ons'),
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
                'default'       => 'Similar Listings',
            ),

            array(
                'type'          => 'select',
                'param_name'    => 'taxonomy',
                'show_in_admin' => true,
                'label'         => __('Listings related', 'townhub-add-ons'),
                // 'desc'                  => 'Select Ascending or Descending order.',
                'default'       => 'listing_tag',
                'value'         => array(
                    'listing_cat'      => __('Same Categories', 'townhub-add-ons'),
                    'listing_location' => __('Same Locations', 'townhub-add-ons'),
                    'listing_feature'  => __('Same Features', 'townhub-add-ons'),
                    'listing_tag'      => __('Same Tags', 'townhub-add-ons'),
                    'featured'          => _x('Featured only', 'Listing type', 'townhub-add-ons'),
                ),
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
                'type'          => 'select',
                'param_name'    => 'order_by',
                'show_in_admin' => true,
                'label'         => __('Order by', 'townhub-add-ons'),
                // 'desc'                  => 'Select how to sort retrieved posts.',
                'default'       => 'date',
                'value'         => array(
                    'date'          => __('Date', 'townhub-add-ons'),
                    'ID'            => __('ID', 'townhub-add-ons'),
                    'author'        => __('Author', 'townhub-add-ons'),
                    'title'         => __('Title', 'townhub-add-ons'),
                    'modified'      => __('Modified', 'townhub-add-ons'),
                    'rand'          => __('Random', 'townhub-add-ons'),
                    'comment_count' => __('Comment Count', 'townhub-add-ons'),
                    'menu_order'    => __('Menu Order', 'townhub-add-ons'),
                    // 'post__in'      => __('ID order given (post__in)', 'townhub-add-ons')
                ),
            ),
            array(
                'type'          => 'select',
                'param_name'    => 'order',
                'show_in_admin' => true,
                'label'         => __('Sort Order', 'townhub-add-ons'),
                // 'desc'                  => 'Select Ascending or Descending order.',
                'default'       => 'DESC',
                'value'         => array(
                    'ASC'  => __('Ascending', 'townhub-add-ons'),
                    'DESC' => __('Descending', 'townhub-add-ons'),
                ),
            ),

            // array(
            //     "type"       => "text",
            //     "label"      => esc_html__("Responsive", 'townhub-add-ons'),
            //     "param_name" => "responsive",
            //     "desc"       => esc_html__("The format is: screen-size:number-items-display,larger-screen-size:number-items-display. Ex: 528:1,800:2,1224:3,1500:4", 'townhub-add-ons'),
            //     "default"    => "528:1,800:2,1224:2,1500:2",
            // ),
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
