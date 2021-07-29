<?php
/* banner-php */

Redux::setSection( $opt_name, array(
    'title' => esc_html__('Members', 'townhub'),
    'id'         => 'member-settings',
    'subsection' => false,
    
    'icon'       => 'el-icon-group',
    'fields' => array(
        array(
            'id'       => 'member_fullwidth_nav_menu',
            'type'     => 'switch',
            'title'    => esc_html__( 'Fullwidth Navigation Menu', 'townhub' ),
            // 'subtitle' => esc_html__( '', 'townhub' ),
            'default'  => false,
        ),
        array(
            'id'       => 'show_member_header',
            'type'     => 'switch',
            'title'    => esc_html__( 'Show Header', 'townhub' ),
            // 'subtitle' => esc_html__( '', 'townhub' ),
            'default'  => true,
        ),
        array(
                'id' => 'member_home_text',
                'type' => 'text',
                'title' => esc_html__('Member Heading Text', 'townhub'),
                // 'subtitle' => esc_html__('', 'townhub'),
                // 'desc' => esc_html__('', 'townhub'),
                'default' => 'Our <strong> Team</strong>'
            ),
        array(
                'id' => 'member_home_text_intro',
                'type' => 'textarea',
                'title' => esc_html__('Member Intro Text', 'townhub'),
                // 'subtitle' => esc_html__('', 'townhub'),
                // 'desc' => esc_html__('', 'townhub'),
                'default' => ''
            ),
        array(
                'id' => 'member_header_video',
                'type' => 'text',
                'title' => esc_html__('Header Background Video', 'townhub'),
                // 'subtitle' => esc_html__('', 'townhub'),
                'desc' => esc_html__('Please enter your Youtube video ID (ex: oDpSPNIozt8 ) here to use header background video feature or leave empty to use header background image selected bellow.', 'townhub'),
                'default' => ''
            ),
        array(
            'id' => 'member_header_image',
            'type' => 'media',
            'url' => true,
            'title' => esc_html__('Header Background Image', 'townhub'),
            //'compiler' => 'true',
            //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
            // 'desc' => esc_html__('Upload your team header image', 'townhub'),
            // 'subtitle' => esc_html__('', 'townhub'),
            'default' => array('url' => get_template_directory_uri().'/assets/images/bg/10.jpg'),
        ),
        array(
            'id'       => 'show_member_breadcrumbs',
            'type'     => 'switch',
            'title'    => esc_html__( 'Show Breadcrumbs', 'townhub' ),
            // 'subtitle' => esc_html__( '', 'townhub' ),
            'default'  => true,
        ),

        array(
            'id' => 'member_first_side',
            'type' => 'select',
            'title' => esc_html__('First Member Content Side', 'townhub'),
            // 'subtitle' => esc_html__('', 'townhub'),
            'desc' => '',
            'options' => array('left' => 'Left', 'right' => 'Right'), //Must provide key => value pairs for select options
            'default' => 'left'
        ),
        array(
            'id'       => 'member_parallax_value',
            'type'     => 'text',
            'title'    => esc_html__( 'Parallax Dimension', 'townhub' ),
            'desc' => esc_html__( 'Pixel number. Which we are telling the browser is to move Member Photo down every time we scroll down 100% of the viewport height and move Member Photo up every time we scroll up 100% of the viewport height. Ex: 150  or -150 for reverse direction.', 'townhub' ),
            'default'  => '150',
        ),


        array(
            'id'       => 'member_read_more',
            'type'     => 'switch',
            'title'    => esc_html__( 'Show Read more', 'townhub' ),
            // 'subtitle' => esc_html__( '', 'townhub' ),
            'default'  => true,
        ),


        array(
                'id'       => 'member_layout',
                'type'     => 'image_select',
                //'compiler' => true,
                'title'    => esc_html__( 'Member Sidebar Layout', 'townhub' ),
                'desc' => esc_html__( 'Select main content and sidebar layout. The option 4 is default layout with right parallax image for TownHub theme.', 'townhub' ),
                'options'  => array(
                    'fullwidth' => array(
                        'alt' => 'Fullwidth',
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
                'default'  => 'fullwidth'
            ),

        array(
            'id'       => 'member_posts_per_page',
            'type'     => 'text',
            'title'    => esc_html__( 'Posts per page', 'townhub' ),
            'subtitle' => esc_html__( 'Number of post to show per page, -1 to show all posts.', 'townhub' ),
            'default'  => 3,
        ),
        array(
            'id' => 'member_archive_orderby',
            'type' => 'select',
            'title' => esc_html__('Order Members By', 'townhub'),
            // 'subtitle' => esc_html__('', 'townhub'),
            // 'desc' => '',
            'options' => array(
                            'none' => esc_html__( 'None', 'townhub' ), 
                            'ID' => esc_html__( 'Post ID', 'townhub' ), 
                            'author' => esc_html__( 'Post Author', 'townhub' ),
                            'title' => esc_html__( 'Post title', 'townhub' ), 
                            'name' => esc_html__( 'Post name (post slug)', 'townhub' ),
                            'date' => esc_html__( 'Created Date', 'townhub' ),
                            'modified' => esc_html__( 'Last modified date', 'townhub' ),
                            'parent' => esc_html__( 'Post Parent id', 'townhub' ),
                            'rand' => esc_html__( 'Random', 'townhub' ),
                        ), //Must provide key => value pairs for select options
            'default' => 'date'
        ),
        array(
            'id' => 'member_archive_order',
            'type' => 'select',
            'title' => esc_html__('Order Members', 'townhub'),
            // 'subtitle' => esc_html__('', 'townhub'),
            // 'desc' => '',
            'options' => array(
                            'DESC' => esc_html__( 'Descending', 'townhub' ),
                            'ASC' => esc_html__( 'Ascending', 'townhub' ), 
                            
                        ), //Must provide key => value pairs for select options
            'default' => 'DESC'
        ),

        array(
            'id' => 'member_list_link',
            'type' => 'text',
            'title' => esc_html__('Member List Link', 'townhub'),
            'desc' => esc_html__('Link for member list icon on single member page. Default: your_domain.com/?post_type=townhub_member or your_domain.com/townhub_member/', 'townhub'),
            'default' => ''
        ),

    ),
) );