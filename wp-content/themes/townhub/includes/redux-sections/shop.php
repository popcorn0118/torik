<?php
/* banner-php */

Redux::setSection( $opt_name, array(
    'title' => esc_html__('Shop', 'townhub'),
    'id'         => 'shop-settings',
    'subsection' => false,
    'icon'       => 'el-icon-shopping-cart',
    'fields' => array(
        
        array(
            'id'      => 'show_shop_header',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show Header', 'townhub'),
            'default' => true,

        ),
        array(
            'id'      => 'shop_head_title',
            'type'    => 'text',
            'title'   => esc_html__('Header Title', 'townhub'),
            'default' => 'Our Shop',
        ),
        array(
            'id'      => 'shop_head_intro',
            'type'    => 'textarea',
            'title'   => esc_html__('Header Intro', 'townhub'),
            'default' => '<p class="head-intro">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut nec tincidunt arcu, sit amet fermentum sem.</p>',
        ),

        array(
            'id'      => 'shop_header_image',
            'type'    => 'image_id',
            'title'   => esc_html__('Header Background', 'townhub'),
            'default' => '',
        ),

        array(
            'id'      => 'show_mini_cart',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show Cart', 'townhub'),
            'default' => true,

        ),




        

        array(
            'id'       => 'shop_sidebar',
            'type'     => 'image_select',
            'title'    => esc_html__( 'Shop Sidebar', 'townhub' ),

            'options'  => array(
                'fullwidth' => array(
                    'alt' => 'No Sidebar',
                    'img' => get_template_directory_uri() . '/assets/redux/1col.png'
                ),
                'left_sidebar' => array(
                    'alt' => 'Left Sidebar',
                    'img' => get_template_directory_uri() . '/assets/redux/2cl.png'
                ),
                'right_sidebar' => array(
                    'alt' => 'Right Sidebar',
                    'img' => get_template_directory_uri() . '/assets/redux/2cr.png'
                ),
                
            ),
            'default'  => 'left_sidebar'
        ),

        

        array(
            'id' => 'shop-sidebar-width',
            'type' => 'select',
            'title' => esc_html__('Sidebar Width', 'townhub'),
            'subtitle' => esc_html__( 'Based on Bootstrap 12 columns.', 'townhub' ),
            'options' => array(
                            '2' => esc_html__('2 Columns', 'townhub'),
                            '3' => esc_html__('3 Columns', 'townhub'),
                            '4' => esc_html__('4 Columns', 'townhub'),
                            '5' => esc_html__('5 Columns', 'townhub'),
                            '6' => esc_html__('6 Columns', 'townhub'),
             ), //Must provide key => value pairs for select options
            'default' => '4'
        ),
        

        array(
            'id' => 'shop_columns',
            'type' => 'select',
            'title' => esc_html__('Desktop Columns', 'townhub'),
            'desc' => esc_html__('Number of products per row on desktop view.','townhub'),
            'options' => array(
                            'one' => esc_html__('One column', 'townhub'),
                            'two' => esc_html__('Two columns', 'townhub'),
                            'three' => esc_html__('Three columns', 'townhub'),
                            'four' => esc_html__('Four columns', 'townhub'),
                            'five' => esc_html__('Five columns', 'townhub'),
                            'six' => esc_html__('Six columns', 'townhub'),
                            'seven' => esc_html__('Seven columns', 'townhub'),
                            
                        ),
            'default' => 'two'
        ),


        array(
            'id' => 'shop_columns_tablet',
            'type' => 'select',
            'title' => esc_html__('Horizontal Tablet Columns', 'townhub'),
            'desc' => esc_html__('Number of products per row on tablet horizontal view.','townhub'),
            'options' => array(
                            'one' => esc_html__('One column', 'townhub'),
                            'two' => esc_html__('Two columns', 'townhub'),
                            'three' => esc_html__('Three columns', 'townhub'),
                            'four' => esc_html__('Four columns', 'townhub'),
                            'five' => esc_html__('Five columns', 'townhub'),
                            'six' => esc_html__('Six columns', 'townhub'),
                            'seven' => esc_html__('Seven columns', 'townhub'),
                        ),
            'default' => 'one'
        ),
        

        
    ),
) );
