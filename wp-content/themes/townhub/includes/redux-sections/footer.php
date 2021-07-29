<?php
/* banner-php */

Redux::setSection( $opt_name, array(
    'title' => esc_html__('Footer', 'townhub'),
    'id'         => 'footer-settings',
    'subsection' => false,
    
    'icon'       => 'el-icon-pencil',
    'fields' => array(
        array(
            'id'      => 'footer_logo',
            'type'    => 'image_id',
            'title'   => esc_html__('Footer Logo', 'townhub'),
            'default' => '',
        ),

        array(
            'id'      => 'footer_copyright',
            'type'    => 'textarea',
            'title'   => esc_html__('Copyright Text', 'townhub'),
            'default' => '<span class="ft-copy">&#169; <a href="https://themeforest.net/user/cththemes" target="_blank">CTHthemes</a> 2019.  All rights reserved.</span>',
        ),

        // array(
        //     'id'      => 'footer_currencies',
        //     'type'    => 'switch',
        //     'on'      => esc_html__('Yes', 'townhub'),
        //     'off'     => esc_html__('No', 'townhub'),
        //     'title'   => esc_html__('Show currencies switcher?', 'townhub'),
        //     'default' => true,

        // ),

        array(
            'id'      => 'footer_widgets_top',
            'type'    => 'fwidget',
            'title'   => esc_html__('Top Footer Widgets', 'townhub'),
            'default' => townhub_get_footer_widgets_top_default(),

        ),

        array(
            'id'      => 'footer_widgets',
            'type'    => 'fwidget',
            'title'   => esc_html__('Footer Widgets', 'townhub'),
            'default' => townhub_get_footer_widgets_default(),

        ),

        array(
            'id'      => 'hide_totop',
            'type'    => 'switch',
            'on'      => esc_html_x('Yes', 'Yes/No option', 'townhub'),
            'off'     => esc_html_x('No', 'Yes/No option', 'townhub'),
            'title'   => esc_html__('Hide go to Top button?', 'townhub'),
            'default' => false,

        ),

    ),
) );