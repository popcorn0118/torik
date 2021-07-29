<?php
/* banner-php */

Redux::setSection($opt_name, array(
    'title'      => esc_html__('Colors', 'townhub'),
    'id'         => 'styling-settings',
    'subsection' => false,

    'icon'       => 'el-icon-magic',
    'fields'     => array(
        array(
            'id'      => 'use_custom_color',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Use Custom Colors', 'townhub'),
            'desc'    => wp_kses(__('Set this option to <b>Yes</b> if you want to use color variants bellow.', 'townhub'), array('b' => array(), 'strong' => array(), 'p' => array())),
            'default' => false,

        ),

        array(
            'id'      => 'theme-color',
            'type'    => 'color',
            'title'   => esc_html__('Theme Color', 'townhub'),
            'desc'    => esc_html__('Default: #4DB7FE', 'townhub'),
            'default' => '#4DB7FE',
        ),
        array(
            'id'      => 'theme-color-third',
            'type'    => 'color',
            'title'   => esc_html__('Theme Secondary Color', 'townhub'),
            'desc'    => esc_html__('Default: #2F3B59', 'townhub'),
            'default' => '#2F3B59',
        ),
        array(
            'id'      => 'theme-color-second',
            'type'    => 'color',
            'title'   => esc_html__('Button Hover Color - Theme third color', 'townhub'),
            'desc'    => esc_html__('Default: #5ECFB1', 'townhub'),
            'default' => '#5ECFB1',
        ),
        
        array(
            'id'      => 'main-bg-color',
            'type'    => 'color',
            'title'   => esc_html__('Body Background Color', 'townhub'),
            'desc'    => esc_html__('Default: #2F3B59', 'townhub'),
            'default' => '',
        ),
        array(
            'id'      => 'loader-bg-color',
            'type'    => 'color',
            'title'   => esc_html__('Loader Background Color', 'townhub'),
            'desc'    => esc_html__('Default: #2F3B59', 'townhub'),
            'default' => '',
        ),
        array(
            'id'      => 'body-text-color',
            'type'    => 'color',
            'title'   => esc_html__('Body Text Color', 'townhub'),
            'desc'    => esc_html__('Default: #000', 'townhub'),
            'default' => '#000',
        ),
        array(
            'id'      => 'paragraph-color',
            'type'    => 'color',
            'title'   => esc_html__('Paragraph Color', 'townhub'),
            'desc'    => esc_html__('Default: #878C9F', 'townhub'),
            'default' => '#878C9F',
        ),

        array(
            'id'      => 'link_colors',
            'type'    => 'link_color',
            'title'   => esc_html__('Link Color', 'townhub'),
            'default' => array(
                'regular' => '#000',
                'hover'   => '#000',
                'active'  => '#000',
            ),
            'active'  => true,
            'visited' => false,
        ),

        array(
            'id'      => 'header-bg-color',
            'type'    => 'color_rgba',
            'title'   => esc_html__('Header Bg Color', 'townhub'),
            'desc'    => esc_html__('Default: #2e3f6e', 'townhub'),
            'default' => array(
                'color' => '#2e3f6e',
                'alpha' => 1,
            ),
        ),
        array(
            'id'      => 'header-text-color',
            'type'    => 'color',
            'title'   => esc_html__('Header Color', 'townhub'),
            'desc'    => esc_html__('Default: #ffffff', 'townhub'),
            'default' => '#fff',
        ),
        array(
            'id'      => 'submenu-bg-color',
            'type'    => 'color_rgba',
            'title'   => esc_html__('Submenu Background Color', 'townhub'),
            'desc'    => esc_html__('Default: #fff', 'townhub'),
            'default' => array(
                'color' => '#fff',
                'alpha' => 1,
            ),
        ),
        array(
            'id'      => 'menu_colors',
            'type'    => 'link_color',
            'title'   => esc_html__('Menu Color', 'townhub'),
            'default' => array(
                'regular' => '#fff',
                'hover'   => '#4DB7FE',
                'active'  => '#4DB7FE',
            ),
            'active'  => true,
            'visited' => false,
        ),
        array(
            'id'      => 'submenu_colors',
            'type'    => 'link_color',
            'title'   => esc_html__('Submenu Color', 'townhub'),
            'default' => array(
                'regular' => '#000000',
                'hover'   => '#4DB7FE',
                'active'  => '#4DB7FE',
            ),
            'active'  => true,
            'visited' => false,
        ),
        array(
            'id'      => 'footer-bg-color',
            'type'    => 'color_rgba',
            'title'   => esc_html__('Footer Background Color', 'townhub'),
            'desc'    => esc_html__('Default: #325096', 'townhub'),
            'default' => array(
                'color' => '#325096',
                'alpha' => 1,
            ),
        ),
        array(
            'id'      => 'footer-text-color',
            'type'    => 'color',
            'title'   => esc_html__('Footer Color', 'townhub'),
            'desc'    => esc_html__('Default: #fff', 'townhub'),
            'default' => '#fff',
        ),
        array(
            'id'      => 'subfooter-bg-color',
            'type'    => 'color_rgba',
            'title'   => esc_html__('Footer Copyright Background Color', 'townhub'),
            'desc'    => esc_html__('Default: #253966', 'townhub'),
            'default' => array(
                'color' => '#253966',
                'alpha' => 1,
            ),
        ),

    ),
));
