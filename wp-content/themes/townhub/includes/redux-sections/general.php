<?php
/* banner-php */
// -> START General Settings

Redux::setSection($opt_name, array(
    'title'      => esc_html__('General', 'townhub'),
    'id'         => 'general-settings',
    'subsection' => false,

    'icon'       => 'el-icon-cogs',
    'fields'     => array(

        array(
            'id'      => 'show_loader',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show Loader', 'townhub'),
            'default' => true,

        ),

        array(
            'id'      => 'loader_icon',
            'type'    => 'image_id',
            'title'   => esc_html__('Loader Icon', 'townhub'),
            'default' => '',
        ),

        array(
            'id'      => 'post_heading_tag',
            'type'    => 'select',
            'title'   => esc_html__('Post heading HTML tag', 'townhub'),
            'options' => array(
                'h1' => esc_html_x('H1', 'Theme option', 'townhub'),
                'h2' => esc_html_x('H2', 'Theme option', 'townhub'),
                'h3' => esc_html_x('H3', 'Theme option', 'townhub'),
                'h4' => esc_html_x('H4', 'Theme option', 'townhub'),
                'h5' => esc_html_x('H5', 'Theme option', 'townhub'),
                'h6' => esc_html_x('H6', 'Theme option', 'townhub'),
                
            ),
            'default' => 'h1',
        ),

        array(
            'id'      => 'enable_auto_update',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Enable Auto Update', 'townhub'),
            'desc'    => esc_html__('Note: auto update feature is not for Envato Elements download.', 'townhub'),
            'default' => false,
        ),



    ),
));
