<?php
/* banner-php */

Redux::setSection( $opt_name, array(
    'title' => esc_html__('Custom Code', 'townhub'),
    'id'         => 'custom-code',
    'subsection' => false,
    'desc'       => wp_kses_post(__( '<p>You can use Firebug - Firefox add-on to adjust your wordpress site\'s style. For more detail please read this tutorial: <a href="https://www.tipsandtricks-hq.com/how-to-use-firebug-to-modify-your-wordpress-sites-css-video-tutorial-4037" target="_blank">How to Use Firebug to Modify Your WordPress Site\'s CSS (Video Tutorial)</a> by <strong>Tips and Tricks HQ</strong>.</p>', 'townhub' ) ),
    // 'icon'       => 'el-icon-file-new',
    'icon'       => 'el-icon-css',
    'fields' => array(
        array(
            'id' => 'custom-css',
            'type' => 'ace_editor',
            'title' => esc_html__('CSS Code - Large Desktop View', 'townhub'),
            'subtitle' => esc_html__('Paste your CSS code here.', 'townhub'),
            'mode' => 'css',
            //'compiler'=>array('body'),
            'full_width'=>false,
            'theme' => 'monokai',
            // 'desc' => 'Possible modes can be found at <a href="'.esc_url('http://ace.c9.io' ).'" target="_blank">http://ace.c9.io/</a>.',
            'default' => ""
        ),
        array(
            'id' => 'custom-css-medium',
            'type' => 'ace_editor',
            'title' => esc_html__('CSS Code - Medium Desktop View', 'townhub'),
            'subtitle' => esc_html__('Paste your CSS code here.', 'townhub'),
            'mode' => 'css',
            //'compiler'=>array('body'),
            'full_width'=>false,
            'theme' => 'monokai',
            // 'desc' => 'Possible modes can be found at <a href="'.esc_url('http://ace.c9.io' ).'" target="_blank">http://ace.c9.io/</a>.',
            'default' => ""
        ),
        array(
            'id' => 'custom-css-tablet',
            'type' => 'ace_editor',
            'title' => esc_html__('CSS Code - Tablet View', 'townhub'),
            'subtitle' => esc_html__('Paste your CSS code here.', 'townhub'),
            'mode' => 'css',
            //'compiler'=>array('body'),
            'full_width'=>false,
            'theme' => 'monokai',
            // 'desc' => 'Possible modes can be found at <a href="'.esc_url('http://ace.c9.io' ).'" target="_blank">http://ace.c9.io/</a>.',
            'default' => ""
        ),
        array(
            'id' => 'custom-css-mobile',
            'type' => 'ace_editor',
            'title' => esc_html__('CSS Code - Mobile View', 'townhub'),
            'subtitle' => esc_html__('Paste your CSS code here.', 'townhub'),
            'mode' => 'css',
            //'compiler'=>array('body'),
            'full_width'=>false,
            'theme' => 'monokai',
            // 'desc' => 'Possible modes can be found at <a href="'.esc_url('http://ace.c9.io' ).'" target="_blank">http://ace.c9.io/</a>.',
            'default' => ""
        ),
        
    ),
) );