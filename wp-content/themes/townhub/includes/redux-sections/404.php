<?php
/* banner-php */

Redux::setSection( $opt_name, array(
    'title' => esc_html__('404 Page', 'townhub'),
    'id'         => 'error-page-settings',
    'subsection' => false,
    
    'icon'       => 'el-icon-file-edit',
    'fields' => array(
        array(
            'id'      => 'error404_bg',
            'type'    => 'image_id',
            'title'   => esc_html__('Background Image', 'townhub'),
            'default' => '',
        ),

        array(
            'id' => 'error404_msg',
            'type' => 'textarea',
            'title' => esc_html__('Additional Message', 'townhub'),
            'default' => '<p>We\'re sorry, but the Page you were looking for, couldn\'t be found.</p>'
        ),
        array(
            'id'      => 'error404_btn',
            'type'    => 'switch',
            'on'      => esc_html__('Yes', 'townhub'),
            'off'     => esc_html__('No', 'townhub'),
            'title'   => esc_html__('Show back Home', 'townhub'),
            'default' => true,

        ),


        
    ),
) );

