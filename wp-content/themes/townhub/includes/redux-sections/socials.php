<?php
/* banner-php */

Redux::setSection( $opt_name, array(
    'title' => esc_html__('Social Share', 'townhub'),
    'id'         => 'social-share-settings',
    'subsection' => false,
    
    'icon'       => 'el-icon-share',
    'fields' => array(
        array(
            'id' => 'share_names',
            'type' => 'text',
            'title' => esc_html__('Share Names', 'townhub'),
            'desc' => esc_html__( 'Enter your social share names separated by a comma. List bellow are available name: facebook,twitter,linkedin,in1,tumblr,digg,googleplus,reddit,pinterest,stumbleupon,email,vk', 'townhub' ),
            //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
            'default' => "facebook,pinterest,googleplus,twitter,linkedin",
        ),
    ),
) );	