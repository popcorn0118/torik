<?php
/* banner-php */

Redux::setSection($opt_name, array(
    'title'      => esc_html__('Blog Options', 'townhub'),
    'id'         => 'blog-settings',
    'subsection' => false,

    'icon'       => 'el-icon-website',
    'fields'     => array(
        array(
            'id'      => 'show_blog_header',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show Header', 'townhub'),
            'default' => false,

        ),
        array(
            'id'      => 'blog_head_title',
            'type'    => 'text',
            'title'   => esc_html__('Header Title', 'townhub'),
            'default' => 'Our Last News',
        ),
        array(
            'id'      => 'blog_head_intro',
            'type'    => 'textarea',
            'title'   => esc_html__('Header Intro', 'townhub'),
            'default' => '<p class="head-intro">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut nec tincidunt arcu, sit amet fermentum sem.</p>',
        ),

        array(
            'id'      => 'blog_header_image',
            'type'    => 'image_id',
            'title'   => esc_html__('Header Background', 'townhub'),
            'default' => '',
        ),

        array(
            'id'      => 'blog_layout',
            'type'    => 'image_select',
            'title'   => esc_html__('Blog Sidebar Layout', 'townhub'),

            'options' => array(
                'fullwidth'     => array(
                    'alt' => 'Fullwidth',
                    'img' => get_template_directory_uri() . '/assets/redux/1col.png',
                ),
                'left_sidebar'  => array(
                    'alt' => 'Left Sidebar',
                    'img' => get_template_directory_uri() . '/assets/redux/2cl.png',
                ),
                'right_sidebar' => array(
                    'alt' => 'Right Sidebar',
                    'img' => get_template_directory_uri() . '/assets/redux/2cr.png',
                ),

            ),
            'default' => 'right_sidebar',
        ),

        array(
            'id'      => 'blog-sidebar-width',
            'type'    => 'select',
            'title'   => esc_html__('Sidebar Width', 'townhub'),
            'desc'       => esc_html__( 'Based on Bootstrap 12 columns.', 'townhub' ),
            'options' => array(
                '2' => esc_html__('2 Columns', 'townhub'),
        '3' => esc_html__('3 Columns', 'townhub'),
        '4' => esc_html__('4 Columns', 'townhub'),
        '5' => esc_html__('5 Columns', 'townhub'),
        '6' => esc_html__('6 Columns', 'townhub'),
            ),
            'default' => '4',
        ),

        array(
            'id'      => 'blog_show_format',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show Post Format on posts page', 'townhub'),
            'default' => true,

        ),
        array(
            'id'      => 'blog_date',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show Date', 'townhub'),
            'default' => true,

        ),
        array(
            'id'      => 'blog_author',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show Author', 'townhub'),
            'default' => false,

        ),

        array(
            'id'      => 'blog_cats',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show Categories', 'townhub'),
            'default' => true,

        ),

        array(
            'id'      => 'blog_tags',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show Tags', 'townhub'),
            'default' => true,

        ),

        array(
            'id'      => 'blog_comments',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show Comments', 'townhub'),
            'default' => false,

        ),



    ),
));
