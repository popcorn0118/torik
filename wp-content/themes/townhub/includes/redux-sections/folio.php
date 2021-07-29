<?php
/* banner-php */

Redux::setSection( $opt_name, array(
    'title' => esc_html__('Portfolio', 'townhub'),
    'id'         => 'portfolio-settings',
    'subsection' => false,
    
    'icon'       => 'el-icon-briefcase',
    'fields' => array(

        array(
            'id'       => 'folio_fullwidth_nav_menu',
            'type'     => 'switch',
            'title'    => esc_html__( 'Fullwidth Navigation Menu', 'townhub' ),
            // 'subtitle' => esc_html__( '', 'townhub' ),
            'default'  => false,
        ),

        array(
            'id'       => 'show_folio_header',
            'type'     => 'switch',
            'title'    => esc_html__( 'Show Portfolio Header', 'townhub' ),
            // 'subtitle' => esc_html__( '', 'townhub' ),
            'default'  => true,
        ),
        array(
                'id' => 'folio_home_text',
                'type' => 'text',
                'title' => esc_html__('Portfolio Heading Text', 'townhub'),
                // 'subtitle' => esc_html__('', 'townhub'),
                // 'desc' => esc_html__('', 'townhub'),
                'default' => 'Our <strong> portfolio </strong>'
            ),
        array(
                'id' => 'folio_home_text_intro',
                'type' => 'textarea',
                'title' => esc_html__('Portfolio Intro Text', 'townhub'),
                // 'subtitle' => esc_html__('', 'townhub'),
                // 'desc' => esc_html__('', 'townhub'),
                'default' => ''
            ),
        array(
                'id' => 'folio_header_video',
                'type' => 'text',
                'title' => esc_html__('Portfolio Header Video', 'townhub'),
                // 'subtitle' => esc_html__('', 'townhub'),
                'desc' => esc_html__('Please enter your Youtube video ID (ex: oDpSPNIozt8 ) here to use header background video feature or leave empty to use header background image selected bellow.', 'townhub'),
                'default' => ''
            ),
        array(
            'id' => 'folio_header_image',
            'type' => 'media',
            'url' => true,
            'title' => esc_html__('Portfolio Header Image', 'townhub'),
            //'compiler' => 'true',
            //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
            // 'desc' => esc_html__('Upload your blog header image', 'townhub'),
            // 'subtitle' => esc_html__('', 'townhub'),
            'default' => array('url' => get_template_directory_uri().'/assets/images/bg/17.jpg'),
        ),
        array(
            'id'       => 'show_folio_breadcrumbs',
            'type'     => 'switch',
            'title'    => esc_html__( 'Show Breadcrumbs', 'townhub' ),
            // 'subtitle' => esc_html__( '', 'townhub' ),
            'default'  => true,
        ),

        array(
            'id'       => 'show_folio_footer_content',
            'type'     => 'switch',
            'title'    => esc_html__( 'Show Content Footer', 'townhub' ),
            // 'subtitle' => esc_html__( '', 'townhub' ),
            'default'  => true,
        ),

        array(
            'id' => 'folio_style',
            'type' => 'select',
            'title' => esc_html__('Portfolio Layout', 'townhub'),
            // 'subtitle' => esc_html__('', 'townhub'),
            'desc' => '',
            'options' => array(
                            'horizontal' => 'Horizontal',
                            'horizontal_boxed' => 'Horizontal Boxed',
                            'vertical' => 'Vertical', 
                            'vertical_fullscreen' => 'Vertical Fullscreen', 
                            'parallax' => 'Parallax', 
                            //'gallery_masonry' => 'Gallery Masonry', 
                            //'gallery_grid' => 'Gallery Grid',
             ), //Must provide key => value pairs for select options
            'default' => 'parallax'
        ),
        array(
            'id' => 'folio_column',
            'type' => 'select',
            'title' => esc_html__('Portfolio Columns', 'townhub'),
            // 'subtitle' => esc_html__('', 'townhub'),
            'desc' => esc_html__('Vertical columns for Horizontal layout', 'townhub'),
            'options' => array('five' => 'Five Columns','four' => 'Four Columns', 'three' => 'Three Columns','two' => 'Two Columns', 'one' => 'One Column'), //Must provide key => value pairs for select options
            'default' => 'two'
        ),
        array(
            'id'       => 'folio_show_filter',
            'type'     => 'switch',
            'title'    => esc_html__( 'Show Filter', 'townhub' ),
            // 'subtitle' => esc_html__( '', 'townhub' ),
            'default'  => true,
        ),
        array(
            'id'       => 'folio_show_counter',
            'type'     => 'switch',
            'title'    => esc_html__( 'Show Counter', 'townhub' ),
            // 'subtitle' => esc_html__( '', 'townhub' ),
            'default'  => true,
        ),
        array(
            'id'       => 'folio_posts_per_page',
            'type'     => 'text',
            'title'    => esc_html__( 'Posts per page', 'townhub' ),
            'subtitle' => esc_html__( 'Number of post to show per page, -1 to show all posts.', 'townhub' ),
            'default'  => 10,
        ),
        array(
            'id' => 'folio_filter_orderby',
            'type' => 'select',
            'title' => esc_html__('Order Filter By', 'townhub'),
            // 'subtitle' => esc_html__('', 'townhub'),
            'desc' => '',
            'options' => array(
                            'id' => esc_html__( 'ID', 'townhub' ), 
                            'count' => esc_html__( 'Count', 'townhub' ),
                            'name' => esc_html__( 'Name', 'townhub' ), 
                            'slug' => esc_html__( 'Slug', 'townhub' ),
                            'none' => esc_html__( 'None', 'townhub' )
                        ), //Must provide key => value pairs for select options
            'default' => 'name'
        ),
        array(
            'id' => 'folio_filter_order',
            'type' => 'select',
            'title' => esc_html__('Order Filter', 'townhub'),
            // 'subtitle' => esc_html__('', 'townhub'),
            'desc' => '',
            'options' => array(
                            'ASC' => esc_html__( 'Ascending', 'townhub' ), 
                            'DESC' => esc_html__( 'Descending', 'townhub' ),
                        ), //Must provide key => value pairs for select options
            'default' => 'ASC'
        ),
        array(
            'id' => 'folio_archive_orderby',
            'type' => 'select',
            'title' => esc_html__('Order Portfolio By', 'townhub'),
            // 'subtitle' => esc_html__('', 'townhub'),
            'desc' => '',
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
            'id' => 'folio_archive_order',
            'type' => 'select',
            'title' => esc_html__('Order Portfolio', 'townhub'),
            // 'subtitle' => esc_html__('', 'townhub'),
            'desc' => '',
            'options' => array(
                            'DESC' => esc_html__( 'Descending', 'townhub' ),
                            'ASC' => esc_html__( 'Ascending', 'townhub' ), 
                            
                        ), //Must provide key => value pairs for select options
            'default' => 'DESC'
        ),
        array(
            'id' => 'folio_show_info_first',
            'type' => 'select',
            'title' => esc_html__('Show Info', 'townhub'),
            // 'subtitle' => esc_html__('', 'townhub'),
            'desc' => '',
            'options' => array(
                            'show_on_hover' => esc_html__( 'Show On Hover', 'townhub' ),
                            'show' => esc_html__( 'Show', 'townhub' ), 
                            'hide' => esc_html__( 'Hide', 'townhub' ), 
                            
                        ), //Must provide key => value pairs for select options
            'default' => 'show_on_hover'
        ),
        
        array(
            'id' => 'folio_pad',
            'type' => 'select',
            'title' => esc_html__('Spacing', 'townhub'),
            'subtitle' => esc_html__('The space between portfolio grid items.', 'townhub'),
            'desc' => '',
            'options' => array(
                            'small' => esc_html__('Small','townhub'), 
                            'big' =>  esc_html__('Big','townhub'),
                            'none' =>  esc_html__('None','townhub'),
                        ), //Must provide key => value pairs for select options
            'default' => 'small'
        ),
        array(
            'id'       => 'folio_enable_gallery',
            'type'     => 'switch',
            'title'    => esc_html__( 'Enable Image Gallery on Portfolio Grid', 'townhub' ),
            // 'subtitle' => esc_html__( '', 'townhub' ),
            'default'  => false,
        ),
        array(
            'id'       => 'folio_disable_overlay',
            'type'     => 'switch',
            'title'    => esc_html__( 'Disbale Image Overlay Effect', 'townhub' ),
            // 'subtitle' => esc_html__( '', 'townhub' ),
            'default'  => false,
        ),
    ),
) );
