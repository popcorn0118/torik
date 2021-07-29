<?php
/* banner-php */

Redux::setSection( $opt_name, array(
    'title' => esc_html__('Blog Single', 'townhub'),
    'id'         => 'blog-single-optons',
    'subsection' => true,
    'fields' => array(

        array(
            'id'      => 'blog-single-sidebar-width',
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
            'id'      => 'single_title_tag',
            'type'    => 'select',
            'title'   => esc_html__('Title HTML tag', 'townhub'),
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
            'id'      => 'single_featured',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show Featured Image', 'townhub'),
            'default' => true,

        ),
        array(
            'id'      => 'single_author',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show Author', 'townhub'),
            'default' => true,

        ),
        
        array(
            'id'      => 'single_date',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show Date', 'townhub'),
            'default' => true,

        ),
        

        array(
            'id'      => 'single_cats',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show Categories', 'townhub'),
            'default' => true,

        ),

        array(
            'id'      => 'single_tags',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show Tags', 'townhub'),
            'default' => true,

        ),

        array(
            'id'      => 'single_comments',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show Comments', 'townhub'),
            'default' => false,

        ),

        array(
            'id'      => 'single_author_block',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show Author Block', 'townhub'),
            'default' => false,

        ),

        array(
            'id'      => 'single_post_nav',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show post navigation', 'townhub'),
            'default' => true,

        ),

        array(
            'id'      => 'single_same_term',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Next/Prev posts should be in same category', 'townhub'),
            'default' => false,

        ),

          
    ),
));
