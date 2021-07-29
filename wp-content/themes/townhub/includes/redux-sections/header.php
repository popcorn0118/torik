<?php
/* banner-php */

Redux::setSection($opt_name, array(
    'title'      => esc_html__('Header Options', 'townhub'),
    'id'         => 'header-settings',
    'subsection' => false,

    'icon'       => 'el-icon-briefcase',
    'fields'     => array(

        array(
            'id'      => 'header_height',
            'type'    => 'text',

            'title'   => esc_html_x('Header height', 'TownHub Options', 'townhub'),
            'desc'    => esc_html_x('Set your site header height. Number in pixels. Default: 80.', 'TownHub Options', 'townhub'),
            'default' => '80',

        ),

        array(
            'id'      => 'header_info',
            'type'    => 'textarea',

            'title'   => esc_html__('Header Contacts Info', 'townhub'),
            'desc'    => esc_html__('Enter header contacts info for your site. Notice: only visible on large screen.', 'townhub'),
            'default' => '',

        ),
        array(
            'id'      => 'show_fixed_search',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show Search?', 'townhub'),
            'default' => true,

        ),

        // array(
        //     'id'      => 'show_addlisting',
        //     'type'    => 'switch',
        //     'on'      => esc_html__('Yes', 'townhub'),
        //     'off'     => esc_html__('No', 'townhub'),
        //     'title'   => esc_html__('Show Add Listing button?', 'townhub'),
        //     'default' => true,

        // ),

        array(
            'id'      => 'show_wishlist',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show Wishlist (Bookmarked listings)?', 'townhub'),
            'default' => true,

        ),

        array(
            'id'      => 'show_userprofile',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show User Profile?', 'townhub'),
            'default' => true,

        ),


        

        array(
            'id'      => 'user_menu_style',
            'type'    => 'select',
            'title'   => esc_html__('Logged In Style', 'townhub'),
            'options' => array(
                'one' => esc_html__('Style One', 'townhub'),
                'two' => esc_html__('Style Two', 'townhub'),
            ),
            'default' => 'two',
        ),
    ),
));
