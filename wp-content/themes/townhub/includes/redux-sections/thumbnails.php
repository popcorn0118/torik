<?php
/* banner-php */

Redux::setSection($opt_name, array(
    'title'      => esc_html__('Thumbnail Sizes', 'townhub'),
    'id'         => 'thumbnail_images',
    'subsection' => false,
    'desc'       => wp_kses(__('<p>These settings affect the display and dimensions of images in your pages.</p>
        <p><em> Enter 9999 as Width value and uncheck Hard Crop to make your thumbnail dynamic width.</em></p>
        <p><em> Enter 9999 as Height value and uncheck Hard Crop to make your thumbnail dynamic height.</em></p>
        <p><em> Enter 9999 as Width and Height values to use full size image.</em></p>
<p>After changing these settings you may need to <a href="http://wordpress.org/extend/plugins/regenerate-thumbnails/" target="_blank">regenerate your thumbnails</a>.</p>', 'townhub'), array('p' => array(), 'a' => array('class' => array(), 'href' => array(), 'target' => array()), 'strong' => array(), 'em' => array())),
    'icon'       => 'el-icon-picture',
    'fields'     => array(
        array(
            'id'      => 'enable_custom_sizes',
            'type'    => 'switch',
            'on'      => esc_html__( 'Yes', 'townhub' ),
            'off'     => esc_html__( 'No', 'townhub' ),
            'title'   => esc_html__('Enable Custom Image Sizes', 'townhub'),
            'default' => true,
        ),
        array(
            'id'      => 'thumb_size_opt_3',
            'type'    => 'thumbnail_size',
            'title'   => esc_html__('Listing Gallery', 'townhub'),
            'desc'    => esc_html__('Demo: Width - 424, Height - 280, Hard crop - checked', 'townhub'),
            'default' => array(
                'width'     => '424',
                'height'    => '280',
                'hard_crop' => 1,
            ),
        ),

        array(
            'id'      => 'thumb_size_opt_4',
            'type'    => 'thumbnail_size',
            'title'   => esc_html__('Listing Grid', 'townhub'),
            'desc'    => esc_html__('Demo: Width - 424, Height - 280, Hard crop - checked', 'townhub'),
            'default' => array(
                'width'     => '424',
                'height'    => '280',
                'hard_crop' => 1,
            ),
        ),

        array(
            'id'      => 'thumb_size_opt_5',
            'type'    => 'thumbnail_size',
            'title'   => esc_html__('Listing Category Size One', 'townhub'),
            'desc'    => esc_html__('Demo: Width - 388, Height - 257, Hard crop - checked', 'townhub'),
            'default' => array(
                'width'     => '388',
                'height'    => '257',
                'hard_crop' => 1,
            ),
        ),
        array(
            'id'      => 'thumb_size_opt_6',
            'type'    => 'thumbnail_size',
            'title'   => esc_html__('Listing Category Size Two', 'townhub'),
            'desc'    => esc_html__('Demo: Width - 795, Height - 257, Hard crop - checked', 'townhub'),
            'default' => array(
                'width'     => '795',
                'height'    => '257',
                'hard_crop' => 1,
            ),
        ),
        array(
            'id'      => 'thumb_size_opt_7',
            'type'    => 'thumbnail_size',
            'title'   => esc_html__('Listing Category Size Three', 'townhub'),
            'desc'    => esc_html__('Demo: Width - 1200, Height - 532, Hard crop - checked', 'townhub'),
            'default' => array(
                'width'     => '1200',
                'height'    => '532',
                'hard_crop' => 1,
            ),
        ),
        array(
            'id'      => 'thumb_size_opt_8',
            'type'    => 'thumbnail_size',
            'title'   => esc_html__('Post Grid Thumbnail', 'townhub'),
            'desc'    => esc_html__('Demo: Width - 381, Height - 240, Hard crop - checked', 'townhub'),
            'default' => array(
                'width'     => '381',
                'height'    => '240',
                'hard_crop' => 1,
            ),
        ),
        array(
            'id'      => 'thumb_size_opt_9',
            'type'    => 'thumbnail_size',
            'title'   => esc_html__('Blog Thumbnail', 'townhub'),
            'desc'    => esc_html__('Demo: Width - 786, Height - 524, Hard crop - checked', 'townhub'),
            'default' => array(
                'width'     => '786',
                'height'    => '524',
                'hard_crop' => 1,
            ),
        ),

        array(
            'id'      => 'thumb_size_opt_10',
            'type'    => 'thumbnail_size',
            'title'   => esc_html__('Blog Single Thumbnail', 'townhub'),
            'desc'    => esc_html__('Demo: Width - 786, Height - 524, Hard crop - checked', 'townhub'),
            'default' => array(
                'width'     => '786',
                'height'    => '524',
                'hard_crop' => 1,
            ),
        ),
        array(
            'id'      => 'thumb_size_opt_11',
            'type'    => 'thumbnail_size',
            'title'   => esc_html__('Recent Post Widget', 'townhub'),
            'desc'    => esc_html__('Demo: Width - 150, Height - 100, Hard crop - checked', 'townhub'),
            'default' => array(
                'width'     => '98',
                'height'    => '65',
                'hard_crop' => 1,
            ),
        ),

    ),
));
