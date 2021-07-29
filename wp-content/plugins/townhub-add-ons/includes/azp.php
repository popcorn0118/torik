<?php 
/* add_ons_php */

function townhub_addons_azp_elements(){ 
	$elements = array();


    $elements['azp_row'] = array(
        'name'                  => __('Row','townhub-add-ons'), 
        'category'              => __("structure",'townhub-add-ons'), 
        'desc'                  => __('Create Row/Column layout grid','townhub-add-ons'),
        'icon'                  => ESB_DIR_URL .'assets/azp-eles-icon/row.png',

        'hasownchild'=>'yes',
        'childtypename'=>'AzuraColumn',
        'childname'=>'Column',
        'showStyleTab'=> true,
        'showTypographyTab'=> true,
        'showAnimationTab'=> true,
        'attrs' => array (
            
            array(
                'type'                  => 'text',
                'param_name'            => 'section_title',
                'label'                 => __('Section Title','townhub-add-ons'), 
                'desc'                  => '',
                'default'               => '',
                
            ),
            array(
                'type'                  => 'textarea',
                'param_name'            => 'content',
                'label'                 => __('Section Introtext (Can be use with html tags.)','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '',
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'title_align',
                'label'                 => __('Section Title Alignment','townhub-add-ons'),
                'desc'                  => '',
                'default'               => 'setleft',
                'value'                 => array(   
                    'setleft'                       => __('Left', 'townhub-add-ons'),
                    'setcenter'                     => __('Center', 'townhub-add-ons'),
                    'setright'                      => __('Right', 'townhub-add-ons'),                                                                              
                ),
            ),
            array(
                'type'                  => 'switch',
                'param_name'            => 'fullwidth',
                'label'                 => __('Content width','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '0',
                'value'                 => array(   
                    '1'                     => __('Fluid width', 'townhub-add-ons'),
                    '0'                     => __('Fixed width', 'townhub-add-ons'),                                                                             
                ),
            ),

            array(
                'type'                  => 'select',
                'param_name'            => 'sec_width',
                'label'                 => __('Section Width','townhub-add-ons'),
                'desc'                  => __("Use Default for template content width" ,'townhub-add-ons'),
                'default'               => 'default',
                'value'                 => array(   
                    'default'                       => __('Default', 'townhub-add-ons'),
                    'fullscreen'                        => __('Fullscreen width', 'townhub-add-ons'),                                                                           
                ),
            ),

            array(
                'type'                  => 'switch',
                'param_name'            => 'is_fullheight',
                'label'                 => __('Is Fullscreen height','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '0',
                'value'                 => array(   
                    '1'                     => __('Yes', 'townhub-add-ons'),
                    '0'                     => __('No', 'townhub-add-ons'),                                                                              
                ),
            ),

            array(
                'type'                  => 'switch',
                'param_name'            => 'equal_height',
                'label'                 => __('Equal height','townhub-add-ons'),
                'desc'                  => __("Set this option to Yes if you want to set columns equal height." ,'townhub-add-ons'),
                'default'               => '0',
                'value'                 => array(   
                    '1'                     => __('Yes', 'townhub-add-ons'),
                    '0'                     => __('No', 'townhub-add-ons'),                                                                             
                ),
            ),
            array(
                'type'                  => 'switch',
                'param_name'            => 'equal_height',
                'label'                 => __('Equal height','townhub-add-ons'),
                'desc'                  => __("Set this option to Yes if you want to set columns equal height." ,'townhub-add-ons'),
                'default'               => '0',
                'value'                 => array(   
                    '1'                     => __('Yes', 'townhub-add-ons'),
                    '0'                     => __('No', 'townhub-add-ons'),                                                                             
                ),
            ),

            array(
                'type'                  => 'switch',
                'param_name'            => 'use_parallax',
                'label'                 => __('Use Parallax','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '0',
                'value'                 => array(   
                    '1'                     => __('Yes', 'townhub-add-ons'),
                    '0'                     => __('No', 'townhub-add-ons'),                                                                               
                ),
            ),

            array(
                'type'                  => 'image',
                'param_name'            => 'parallax_image',
                'label'                 => __('Parallax Image','townhub-add-ons'),
                'desc'                  => __("If no image is selected, parallax will use background image from Style tab.", 'townhub-add-ons'),
                'default'               =>'',
                "depends_on"         => array(   
                    'element'   => 'use_parallax',  
                    'value'                 => array('1'),                                                                                
                    'has_value' => false,                                                                                
                ),
            ),

            array(
                'type'                  => 'text',
                'param_name'            => 'parallax_value',
                'label'                 => __('Parallax Value','townhub-add-ons'),
                'desc'                  => __("Pixel number. Which we are telling the browser is to move Parallax Image down every time we scroll down 100% of the viewport height and move Parallax Image up every time we scroll up 100% of the viewport height. Ex: 300 or -300 for reverse direction." ,'townhub-add-ons'),
                'default'               => '300',
                'rgba'          => true,
                "depends_on"         => array(   
                    'element'   => 'use_parallax',  
                    'value'                 => array('1'),                                                                                
                    'has_value' => false,                                                                                
                ),
            ),

            array(
                'type'                  => 'color',
                'param_name'            => 'overlay_color',
                'label'                 => __('Overlay Color','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '',
                'rgba'          => true,
                "depends_on"         => array(   
                    'element'   => 'use_parallax',  
                    'value'                 => array('1'),                                                                                
                    'has_value' => false,                                                                                
                ),
            ),
            
            array(
                'type'                  => 'select',
                'param_name'            => 'column_gap',
                'label'                 => __('Columns gap','townhub-add-ons'),
                'desc'                  => __("Gap between columns." ,'townhub-add-ons'),
                'default'               => '15',
                'value'                 => array(   
                    '0'                     => __('0px', 'townhub-add-ons'),
                    '1'                     => __('1px', 'townhub-add-ons'),
                    '2'                     => __('2px', 'townhub-add-ons'),
                    '3'                     => __('3px', 'townhub-add-ons'),
                    '4'                     => __('4px', 'townhub-add-ons'),
                    '5'                     => __('5px', 'townhub-add-ons'),
                    '10'                        => __('10px', 'townhub-add-ons'),
                    '15'                        => __('15px', 'townhub-add-ons'),
                    '20'                        => __('20px', 'townhub-add-ons'),
                    '25'                        => __('25px', 'townhub-add-ons'),
                    '30'                        => __('30px', 'townhub-add-ons'),
                    '35'                        => __('35px', 'townhub-add-ons'),
                    '40'                        => __('40px', 'townhub-add-ons'),
                    '45'                        => __('45px', 'townhub-add-ons'),
                    '50'                        => __('50px', 'townhub-add-ons'),                                                                           
                ),
            ),

            array(
                'type'                  => 'text',
                'param_name'            => 'el_id',
                'label'                 => __('Section ID','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '',
                
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'el_class',
                'label'                 => __('Extra Class','townhub-add-ons'),
                'desc'                  => __("Use this field to add a class name and then refer to it in your CSS." ,'townhub-add-ons'),
                'default'               => '',
            ),

            array(
                'type'                  => 'colslayout', // this type for row element only
                'param_name'            => 'cols_layout',
                'label'                 => __('Columns Layout','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '100',
                'value'                 => array(   
                    '100'                     => __('100', 'townhub-add-ons'),
                    '50,50'                   => __('50-50', 'townhub-add-ons'),
                    '33,66'                   => __('33-66', 'townhub-add-ons'),
                    '66,33'                   => __('66-33', 'townhub-add-ons'),                                                                       
                    '33,33,33'                => __('33-33-33', 'townhub-add-ons'),                                                                       
                    '25,25,25,25'             => __('25-25-25-25', 'townhub-add-ons'),                                                                       
                    '20,20,20,20,20'          => __('20-20-20-20-20', 'townhub-add-ons'),                                                                       
                ),
            ),

        )
    );

    $elements['azp_rowinner'] = array(
        'name'                  => __('Row Inner','townhub-add-ons'),
        'desc'                  => __('Create Row/Column layout grid in parent column element','townhub-add-ons'),
        'category'              => __("structure",'townhub-add-ons'),
        'icon'                  => ESB_DIR_URL .'assets/azp-eles-icon/row.png',
        'hasownchild'=>'yes',
        'childtypename'=>'AzuraColumnInner',
        'childname'=>'Column Inner',
        'showStyleTab'=> true,
        'showTypographyTab'=> true,
        'showAnimationTab'=> true,
        'attrs' => array (
            array(
                'type'                  => 'select',
                'param_name'            => 'column_gap',
                'label'                 => __('Columns gap','townhub-add-ons'),
                'desc'                  => __("Gap between columns." ,'townhub-add-ons'),
                'default'               => '15',
                'value'                 => array(   
                    '0'                     => __('0px', 'townhub-add-ons'),
                    '1'                     => __('1px', 'townhub-add-ons'),
                    '2'                     => __('2px', 'townhub-add-ons'),
                    '3'                     => __('3px', 'townhub-add-ons'),
                    '4'                     => __('4px', 'townhub-add-ons'),
                    '5'                     => __('5px', 'townhub-add-ons'),
                    '10'                        => __('10px', 'townhub-add-ons'),
                    '15'                        => __('15px', 'townhub-add-ons'),
                    '20'                        => __('20px', 'townhub-add-ons'),
                    '25'                        => __('25px', 'townhub-add-ons'),
                    '30'                        => __('30px', 'townhub-add-ons'),
                    '35'                        => __('35px', 'townhub-add-ons'),
                    '40'                        => __('40px', 'townhub-add-ons'),
                    '45'                        => __('45px', 'townhub-add-ons'),
                    '50'                        => __('50px', 'townhub-add-ons'),                                                                             
                ),
            ),
            array(
                'type'                  => 'switch',
                'param_name'            => 'equal_height',
                'label'                 => __('Equal height','townhub-add-ons'),
                'desc'                  => __("Set this option to Yes if you want to set columns equal height." ,'townhub-add-ons'),
                'default'               => '0',
                'value'                 => array(   
                    '1'                     => __('Yes', 'townhub-add-ons'),
                    '0'                     => __('No', 'townhub-add-ons'),                                                                             
                ),
            ),
            
            
            array(
                'type'                  => 'text',
                'param_name'            => 'el_id',
                'label'                 => __('Row ID','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '',
                
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'el_class',
                'label'                 => __('Extra Class','townhub-add-ons'),
                'desc'                  => __("Use this field to add a class name and then refer to it in your CSS." ,'townhub-add-ons'),
                'default'               => '',
            ),

            array(
                'type'                  => 'colslayout', // this type for row element only
                'param_name'            => 'cols_layout',
                'label'                 => __('Columns Layout','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '100',
                'value'                 => array(   
                    '100'                     => __('100', 'townhub-add-ons'),
                    '50,50'                   => __('50-50', 'townhub-add-ons'),
                    '33,66'                   => __('33-66', 'townhub-add-ons'),
                    '66,33'                   => __('66-33', 'townhub-add-ons'),                                                                       
                    '33,33,33'                => __('33-33-33', 'townhub-add-ons'),                                                                       
                    '25,25,25,25'             => __('25-25-25-25', 'townhub-add-ons'),                                                                       
                    '20,20,20,20,20'          => __('20-20-20-20-20', 'townhub-add-ons'),                                                                       
                ),
            ),
            
        )
    );

	$elements['azp_column'] = array(
        'name'                          =>__( 'Column', 'townhub-add-ons' ),
        'category'                      => __("forrow",'townhub-add-ons'),
        'icon'                          => ESB_DIR_URL .'assets/azp-eles-icon/row.png',
        'showStyleTab'=> true,
        'showTypographyTab'=> true,
        'showAnimationTab'=> true,
        // 'showResponsiveTab'=> true,
        'attrs' => array (

            array(
                'type'                  =>'width',
                'param_name'            =>'azp_rwid',
                'label'                 =>__( 'Column Width (%)', 'townhub-add-ons' ),
                'desc'                  => "",
                'default'               => ''
            ),

            // array(
            //     'type'                  =>'checkbox',
            //     'param_name'            =>'test_checkbox',
            //     'label'                 =>__( 'Test Checkbox', 'townhub-add-ons' ),
            //     'desc'                  => "" ,
            //     'value'                  => 'yes' ,
            //     'unchecked'                  => 'no' ,
            //     'default'               => 'yes'
            // ),

            // array(
            //     'type'                  =>'radio',
            //     'param_name'            =>'test_radio',
            //     'label'                 =>__( 'Test Radio', 'townhub-add-ons' ),
            //     'value'                  => array(
            //         'no'                    => __( 'No', 'townhub-add-ons' ),
            //         'yes'                    => __( 'Yes', 'townhub-add-ons' ),
            //         'test'                    => __( 'Test', 'townhub-add-ons' ),
            //     ),
            //     'desc'                  => "" ,
            //     'default'               => 'yes'
            // ),

            array(
                'type'					=>'text',
                'param_name'			=>'el_id',
                'label'					=>__( 'ID', 'townhub-add-ons' ),
                'desc' 					=> "" ,
                'default'				=>''
            ),
            
            array(
                'type'					=>'text',
                'param_name'			=>'el_class',
                'label'					=> __( 'Extra Class', 'townhub-add-ons' ),
                'desc' 					=> __( 'Use this field to add a class name and then refer to it in your CSS.', 'townhub-add-ons' ) ,
                'default' 				=>''
            ),
            
        )
    );

    $elements['azp_columninner'] = array(
        'name'                  => __('Column Inner','townhub-add-ons'),
        'category'              => __("forrowinner",'townhub-add-ons'),
        'icon'                  => ESB_DIR_URL .'assets/azp-eles-icon/row.png',
        'showStyleTab'=> true,
        'showTypographyTab'=> true,
        'showAnimationTab'=> true,
        // 'showResponsiveTab'=> true,
        'attrs' => array (
            array(
                'type'                  =>'width',
                'param_name'            =>'azp_rwid',
                'label'                 =>__( 'Column Width (%)', 'townhub-add-ons' ),
                'desc'                  => "",
                'default'               => ''
            ),

            array(
                'type'                  => 'text',
                'param_name'            => 'el_id',
                'label'                 => __('ID','townhub-add-ons'),
                'desc'                  => '',
                'default'               => ''
            ),
            
            array(
                'type'                  => 'text',
                'param_name'            => 'el_class',
                'label'                 => __('Extra Class','townhub-add-ons'),
                'desc'                  => __("Use this field to add a class name and then refer to it in your CSS." ,'townhub-add-ons'),
                'default'               => ''
            ),
            
        )
    );

    $elements['azp_container'] = array(
        'name'                  => __('Container','townhub-add-ons'),
        'desc'                  => __('Create wrapper in parent column element','townhub-add-ons'),
        'category'              => __("structure",'townhub-add-ons'),
        'icon'                  => ESB_DIR_URL .'assets/azp-eles-icon/container.png',
        'open_settings_on_create'=>true,
        'showStyleTab'=> true,
        'showTypographyTab'=> true,
        'showAnimationTab'=> true,
        'attrs' => array (
            array(
                'type'                  => 'text',
                'param_name'            => 'el_id',
                'label'                 => __('Element ID','townhub-add-ons'),
                'desc'                  => '',
                'default'               => ''
            ),
            
            array(
                'type'                  => 'text',
                'param_name'            => 'el_class',
                'label'                 => __('Extra Class','townhub-add-ons'),
                'desc'                  => __("Use this field to add a class name and then refer to it in your CSS." ,'townhub-add-ons'),
                'default'               => ''
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'wraptag',
                'label'                 => __('Wrapper Tag','townhub-add-ons'),
                'desc'                  => '',
                'default'               => 'div',
                'value'                 => array(
                    'div'                       => __('div', 'townhub-add-ons'),
                    'section'                       => __('section', 'townhub-add-ons'),
                    'article'                       => __('article', 'townhub-add-ons'),
                    'aside'                     => __('aside', 'townhub-add-ons'),
                    'ul'                        => __('ul', 'townhub-add-ons'),
                )
            ),
            
        )
    );

    $elements['azp_text'] = array(
        'name'                  => __('Text Block','townhub-add-ons'),
        'desc'                  => __('A block of text with WYSIWYG editor','townhub-add-ons'),
        'category'              => __("content",'townhub-add-ons'),
        'icon'                  => ESB_DIR_URL .'assets/azp-eles-icon/text-block.png',
        'open_settings_on_create'=>true,
        'showStyleTab'=> true,
        'showTypographyTab'=> true,
        'showAnimationTab'=> true,
        'attrs' => array (
            // array(
            //     'type'                  => 'icon',
            //     'param_name'            => 'el_icon',
            //     'show_in_admin'         => true,
            //     'label'                 => __('Icon Selector','townhub-add-ons'),
            //     'desc'                  => '',
            //     'default'               => ''
            // ),

            // array(
            //     'type'                  => 'repeater',
            //     'param_name'            => 'el_repeater',
            //     'show_in_admin'         => true,
            //     'label'                 => __('Repeater Field','townhub-add-ons'),
            //     'desc'                  => '',
            //     'title_field'           => 'rp_text',
            //     'fields'                => array(
            //         array(
            //             'type'                  => 'text',
            //             'param_name'            => 'rp_text',
            //             'show_in_admin'         => true,
            //             'label'                 => __('Repeater Field Text','townhub-add-ons'),
            //             'desc'                  => '',
            //             'default'               => ''
            //         ),
            //         array(
            //             'type'                  => 'textarea',
            //             'param_name'            => 'rp_textarea',
            //             'show_in_admin'         => true,
            //             'label'                 => __('Repeater Field Textarea','townhub-add-ons'),
            //             'desc'                  => '',
            //             'default'               => ''
            //         ),
            //         array(
            //             'type'                  => 'image',
            //             'param_name'            => 'rp_img',
            //             'show_in_admin'         => true,
            //             'label'                 => __('Repeater Field Image','townhub-add-ons'),
            //             'desc'                  => '',
            //             'default'               => ''
            //         ),
            //     ),
            //     'default'               => array(
            //         // array('rp_text'=>'rp_text','rp_textarea'=>'rp_textarea') -> Objects are not valid as a React child (found: object with keys {rp_text, rp_textarea}).
            //     )
            // ),

            array(
                'type'                  => 'editor',
                
                'param_name'            => 'content',
                'label'                 => __('Content','townhub-add-ons'),
                'show_in_admin'         => true,
                'desc'                  => __("Text Content (Can be used with HTML tags)" ,'townhub-add-ons'),
                'default'               => '<h3>Back End Page Builder</h3><p>Build a responsive website and manage your content easily with super fast back-end builder. No programming knowledge required – create stunning and beautiful pages with drag and drop builder.</p>',
                'iscontent'             =>'yes'
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'el_id',
                'show_in_admin'         => true,
                'label'                 => __('Element ID','townhub-add-ons'),
                'desc'                  => '',
                'default'               => ''
            ),
            
            array(
                'type'                  => 'text',
                'param_name'            => 'el_class',
                'show_in_admin'         => true,
                'label'                 => __('Extra Class','townhub-add-ons'),
                'desc'                  => __("Use this field to add a class name and then refer to it in your CSS." ,'townhub-add-ons'),
                'default'               => ''
            ),
            
        )
    );

    $elements['azp_image'] = array(
        'name'                  => __('Single Image','townhub-add-ons'),
        'desc'                  => __('','townhub-add-ons'),
        'category'              => __("content",'townhub-add-ons'),
        'icon'                  => ESB_DIR_URL .'assets/azp-eles-icon/image.png',
        'open_settings_on_create'=>true,
        'showStyleTab'=>true,
        'showAnimationTab'=>true,
        'attrs' => array (
            array(
                'type'                  => 'images',
                'param_name'            => 'image_url',
                'label'                 => __('Image Source','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '',
                'show_in_admin'         => true,
            ),
            // array(
            //     'type'                  => 'text',
            //     'param_name'            => 'alttext',
            //     'label'                 => __('Alt Text','townhub-add-ons'),
            //     'desc'                  => '',
            //     'default'               => '',
            // ),
            array(
                'type'                  => 'select',
                'param_name'            => 'image_style',
                'label'                 => __("Image Style",'townhub-add-ons'),
                'desc'                  => '',
                'default'               => 'default',
                'value'                 => array(
                    'default'                       => __('Default', 'townhub-add-ons'),
                    'circle'                        => __('Circle', 'townhub-add-ons'),
                    'thumbnail'                     => __('Thumbnail', 'townhub-add-ons'),
                    // 'withcontent'                        => __('Thumbnail with content', 'default'),
                )
                
            ),
            // array(
            //     'type'                  => 'select',
            //     'param_name'            => 'click_action',
            //     'label'                 => __("Click action",'townhub-add-ons'),
            //     'desc'                  => __("Select action for user click." ,'townhub-add-ons'),
            //     'default'               => 'none',
            //     'value'                 => array(
            //         'none'                      => __('None', 'townhub-add-ons'),
            //         'lightbox'                      => __('Open popup', 'townhub-add-ons'),
            //         'modal'                     => __('Open modal', 'townhub-add-ons'),
            //         'link'                      => __('Open link', 'townhub-add-ons'),
            //     )
                
            // ),
            // array(
            //     'type'                  => 'text',
            //     'param_name'            => 'modal_id',
            //     'label'                 => __('Modal ID','townhub-add-ons'),
            //     'desc'                  => __("Enter your modal ID here to open it." ,'townhub-add-ons'),
            //     'default'               => '',
            //     'depends_on' => array(
            //         'element' => 'click_action',
            //         'value'                 => array('modal'),
            //         'has_value' => false,
            //     ),
            // ),
            // array(
            //     'type'                  => 'image',
            //     'param_name'            => 'large_image',
            //     'label'                 => __('Popup image or video','townhub-add-ons'),
            //     'desc'                  => __("Large Image or Youtube, Vimeo, Soundcloud link for light box. Leave empty to use default." ,'townhub-add-ons'),
            //     'default'               => '',
            //     'depends_on'=> array(
            //         'element'=> 'click_action',
            //         'value'                 => array('lightbox'),
            //         'has_value' => false,
            //     ),
            // ),
            array(
                'type'                  => 'text',
                'param_name'            => 'video_link',
                'label'                 => __('Video url','townhub-add-ons'),
                'desc'                  => 'EX:https://www.youtube.com/watch?v=d9Q3vKl40Y8',
                'default'               => '',
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'name_video_link',
                'label'                 => __('Describes the video path.','townhub-add-ons'),
                'desc'                  => '',
                'default'               => 'Promo Video',
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'image_link',
                'label'                 => __('Image Link','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '#',
                'depends_on'=> array(
                    'element'=> 'click_action',
                    'value'                 => array('link'),
                    'has_value' => false,
                ),
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'link_target',
                'label'                 => __("Open link in",'townhub-add-ons'),
                'desc'                  => '',
                'default'               => '_blank',
                'value'                 => array(
                    '_blank'                        => __('New tab', 'townhub-add-ons'),
                    '_self'                     => __('Current tab', 'townhub-add-ons'),
                ),
                'depends_on'=> array(
                    'element'=> 'click_action',
                    'value'                 => array('link'),
                    'has_value' => false,
                ),
                
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'el_id',
                'label'                 => __('Element ID','townhub-add-ons'),
                'desc'                  => '',
                'default'               => ''
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'el_class',
                'label'                 => __('Extra Class','townhub-add-ons'),
                'desc'                  => __("Use this field to add a class name and then refer to it in your CSS." ,'townhub-add-ons'),
                'default'               => '',
                
            ),

            
        )
    );
    
    $elements['azp_accordion'] = array(
        'name'                  => __('Accordion','townhub-add-ons'),
        'desc'                  => __('','townhub-add-ons'),
        'category'              => __("content",'townhub-add-ons'),
        'icon'                  => ESB_DIR_URL .'assets/azp-eles-icon/accordion.png',
        'open_settings_on_create'=>true,
        'showStyleTab'=>true,
        'showAnimationTab'=>true,
        'attrs' => array (
             array(
                'type'                  => 'repeater',
                'param_name'            => 'contents_order',
                'show_in_admin'         => true,
                'label'                 => __('Accordion Items','townhub-add-ons'),
                'desc'                  => '',
                'title_field'           => 'rp_text',
                'fields'                => array(
                    array(
                        'type'                  => 'text',
                        'param_name'            => 'title',
                        'show_in_admin'         => true,
                        'label'                 => __('Title','townhub-add-ons'),
                        'desc'                  => '',
                        'default'               => 'Accordion #1'
                    ),
                    array(
                        'type'                  => 'textarea',
                        'param_name'            => 'content',
                        'show_in_admin'         => true,
                        'label'                 => __('Content','townhub-add-ons'),
                        'desc'                  => '',
                        'default'               => 'I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.'
                    ),
                    // array(
                    //     'type'                  => 'icon',
                    //     'param_name'            => 'icon',
                    //     'show_in_admin'         => true,
                    //     'label'                 => __('Icon','townhub-add-ons'),
                    //     'desc'                  => '',
                    //     'default'               => ''
                    // ),
                    // array(
                    //     'type'                  => 'icon',
                    //     'param_name'            => 'active_icon',
                    //     'show_in_admin'         => true,
                    //     'label'                 => __('Active Icon','townhub-add-ons'),
                    //     'desc'                  => '',
                    //     'default'               => ''
                    // ),
                ),
                'default'               => array(
                )
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'el_id',
                'label'                 => __('Element ID','townhub-add-ons'),
                'desc'                  => '',
                'default'               => ''
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'el_class',
                'label'                 => __('Extra Class','townhub-add-ons'),
                'desc'                  => __("Use this field to add a class name and then refer to it in your CSS." ,'townhub-add-ons'),
                'default'               => '',
                
            ),

            
        )
    );
    $elements['azp_gallery'] = array(
        'name'                  => __('Image Gallery','townhub-add-ons'),
        'desc'                  => __('','townhub-add-ons'),
        'category'              => __("content",'townhub-add-ons'),
        'icon'                  => ESB_DIR_URL .'assets/azp-eles-icon/gallery.png',
        'open_settings_on_create'=>true,
        'showStyleTab'=>true,
        'showAnimationTab'=>true,
        'attrs' => array (
            array(
                'type'                  => 'images',
                'param_name'            => 'image_url',
                'label'                 => __('Image Source','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '',
                'show_in_admin'         => true,
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'image_size',
                 'show_in_admin'         => true,
                'label'                 => __("Image Size",'townhub-add-ons'),
                'desc'                  => __("" ,'townhub-add-ons'),
                'default'               => 'thumbnail',
                'value'=> array(
                    'thumbnail'                  => __('Thumbnail - 150 x 150', 'townhub-add-ons'),
                    'medium'                     => __('Medium - 300 x 300', 'townhub-add-ons'),
                    'medium_large'               => __('Medium Large - 768 x 0', 'townhub-add-ons'),
                    'large'                      => __('large', 'townhub-add-ons'),
                    'full'                       => __('Full', 'townhub-add-ons'),
                )
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'grid_cols',
                'show_in_admin'         => true,
                'label'                 => __("Columns",'townhub-add-ons'),
                'desc'                  => __("" ,'townhub-add-ons'),
                'default'               => 'three',
                'value'                 => array(
                    'one'                     => __('One Column', 'townhub-add-ons'),
                    'two'                     => __('Two Column', 'townhub-add-ons'),
                    'three'                     => __('Three Columns', 'townhub-add-ons'),
                    'four'                     => __('Four Columns', 'townhub-add-ons'),
                    'five'                     => __('Five Columns', 'townhub-add-ons'),
                    'six'                     => __('Six Columns', 'townhub-add-ons'),
                    'seven'                     => __('Seven Columns', 'townhub-add-ons'),
                    'eight'                     => __('Eight Columns', 'townhub-add-ons'),
                    'nine'                     => __('Nine Columns', 'townhub-add-ons'),
                    'ten'                     => __('Ten Columns', 'townhub-add-ons'),
                )
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'items_width',
                'label'                 => __('Items Width','townhub-add-ons'),
                'desc'                  => '',
                'default'               => 'Defined location width. Available values are x1(default),x2(x2 width),x3(x3 width), and separated by comma. Ex: x1,x1,x2,x1,x1,x1'
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'space',
                'show_in_admin'         => true,
                'label'                 => __("Space",'townhub-add-ons'),
                'desc'                  => __("" ,'townhub-add-ons'),
                'default'               => 'big',
                'value'                 => array(
                    'big'                     => __('Big', 'townhub-add-ons'),
                    'medium'                     => __('Medium', 'townhub-add-ons'),
                    'small'                     => __('Small', 'townhub-add-ons'),
                    'extrasmall'                     => __('Extra small', 'townhub-add-ons'),
                    'no'                     => __('None', 'townhub-add-ons'),
                )
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'el_id',
                'label'                 => __('Element ID','townhub-add-ons'),
                'desc'                  => '',
                'default'               => ''
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'el_class',
                'label'                 => __('Extra Class','townhub-add-ons'),
                'desc'                  => __("Use this field to add a class name and then refer to it in your CSS." ,'townhub-add-ons'),
                'default'               => '',
                
            ),

            
        )
    );

    $elements['azp_carousel'] = array(
        'name'                  => __('Image Carousel','townhub-add-ons'),
        'desc'                  => __('','townhub-add-ons'),
        'category'              => __("content",'townhub-add-ons'),
        'icon'                  => ESB_DIR_URL .'assets/azp-eles-icon/image-carousel.png',
        'open_settings_on_create'=>true,
        'showStyleTab'=>true,
        'showAnimationTab'=>true,
        'attrs' => array (
            array(
                'type'                  => 'images',
                'param_name'            => 'image_url',
                'show_in_admin'         => true,
                'label'                 => __('Image Source','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '',
                'show_in_admin'         => true,
            ),
            array(
                'type'                  => 'textarea',
                'param_name'            => 'link',
                'label'                 => __('link','townhub-add-ons'),
                'desc'                  => 'Enter links for each (Note: divide links with | or linebreaks (Enter) and no spaces).',
                'default'               => '#|#|#|#|#|#'
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'target',
                'show_in_admin'         => true,
                'label'                 => __("Target",'townhub-add-ons'),
                'desc'                  => __("" ,'townhub-add-ons'),
                'default'               => '_blank',
                'value'=> array(
                    '_blank'                  => __('Opens Image link in new window', 'townhub-add-ons'),
                    '_self'                     => __('Opens Image link in the same window', 'townhub-add-ons'),
                )
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'thumbnail_size',
                 'show_in_admin'         => true,
                'label'                 => __("Image Size",'townhub-add-ons'),
                'desc'                  => __("" ,'townhub-add-ons'),
                'default'               => 'thumbnail',
                'value'=> array(
                    'thumbnail'                  => __('Thumbnail - 150 x 150', 'townhub-add-ons'),
                    'medium'                     => __('Medium - 300 x 300', 'townhub-add-ons'),
                    'medium_large'               => __('Medium Large - 768 x 0', 'townhub-add-ons'),
                    'large'                      => __('large', 'townhub-add-ons'),
                    'full'                       => __('Full', 'townhub-add-ons'),
                )
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'spacing',
                'show_in_admin'         => true,
                'label'                 => __("Spacing",'townhub-add-ons'),
                'desc'                  => __("" ,'townhub-add-ons'),
                'default'               => '10',
                'value'                 => array(
                    '0'                     => __('None', 'townhub-add-ons'),
                    '1'                     => __('1px', 'townhub-add-ons'),
                    '2'                     => __('2px', 'townhub-add-ons'),
                    '3'                     => __('3px', 'townhub-add-ons'),
                    '4'                     => __('4px', 'townhub-add-ons'),
                    '5'                     => __('5px', 'townhub-add-ons'),
                    '10'                     => __('10px', 'townhub-add-ons'),
                    '15'                     => __('15px', 'townhub-add-ons'),
                    '20'                     => __('20px', 'townhub-add-ons'),
                    '25'                     => __('25px', 'townhub-add-ons'),
                    '30'                     => __('30px', 'townhub-add-ons'),
                )
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'responsive',
                'label'                 => __('responsive','townhub-add-ons'),
                'desc'                  => 'The format is: screen-size:number-items-display,larger-screen-size:number-items-display. Ex: 320:2,768:2,992:4,1200:5',
                'default'               => '320:2,768:2,992:2,1200:3'
            ),
             array(
                'type'                  => 'text',
                'param_name'            => 'speed',
                'label'                 => __('Speed','townhub-add-ons'),
                'desc'                  => 'Duration of transition between slides (in ms). Default: 1300',
                'default'               => '1300'
            ),
            // array(
            //     'type'                  => 'switch',
            //     'param_name'            => 'show_title',
            //     'show_in_admin'         => true,
            //     'label'                 => __('Show Title/Caption','townhub-add-ons'),
            //     'desc'                  => '',
            //     'default'               => 'yes',
            //     'value'                 => array(   
            //         'yes'          => __('Show', 'townhub-add-ons'), 
            //         'no'            => __('Hidden', 'townhub-add-ons'), 
            //      ),
            // ),
            array(
                'type'                  => 'switch',
                'param_name'            => 'autoplay',
                'show_in_admin'         => true,
                'label'                 => __('Auto Play','townhub-add-ons'),
                'desc'                  => '',
                'default'               => 'no',
                'value'                 => array(   
                    'yes'          => __('Yes', 'townhub-add-ons'), 
                    'no'            => __('No', 'townhub-add-ons'), 
                 ),
            ),
            array(
                'type'                  => 'switch',
                'param_name'            => 'loop',
                'show_in_admin'         => true,
                'label'                 => __('Loop','townhub-add-ons'),
                'desc'                  => '',
                'default'               => 'yes',
                'value'                 => array(   
                    'yes'          => __('Yes', 'townhub-add-ons'), 
                    'no'            => __('No', 'townhub-add-ons'), 
                 ),
            ),
            array(
                'type'                  => 'switch',
                'param_name'            => 'show_navigation',
                'show_in_admin'         => true,
                'label'                 => __('Show Navigation','townhub-add-ons'),
                'desc'                  => '',
                'default'               => 'yes',
                'value'                 => array(   
                    'yes'          => __('Yes', 'townhub-add-ons'), 
                    'no'            => __('No', 'townhub-add-ons'), 
                 ),
            ),
            array(
                'type'                  => 'switch',
                'param_name'            => 'show_dots',
                'show_in_admin'         => true,
                'label'                 => __('Show Dots','townhub-add-ons'),
                'desc'                  => '',
                'default'               => 'yes',
                'value'                 => array(   
                    'yes'          => __('Yes', 'townhub-add-ons'), 
                    'no'            => __('No', 'townhub-add-ons'), 
                 ),
            ),
            //  array(
            //     'type'                  => 'select',
            //     'param_name'            => 'wow_type',
            //     'show_in_admin'         => true,
            //     'label'                 => __('Reveal Animations When You Scroll','townhub-add-ons'),
            //     'desc'                  => '',
            //     'default'               => 'yes',
            //     'value'                 => array(   
            //         ''                      => __('None', 'townhub-add-ons'), 
            //         'bounceIn'              => __('BounceIn', 'townhub-add-ons'), 
            //         'bounceInDown'          => __('BounceInDown', 'townhub-add-ons'), 
            //         'bounceInLeft'          => __('bounceInLeft', 'townhub-add-ons'),
            //         'bounceInRight'         => __('bounceInRight', 'townhub-add-ons'), 
            //         'fadeIn'                => __('fadeIn', 'townhub-add-ons'),
            //         'fadeInUp'              => __('fadeInUp', 'townhub-add-ons'), 
            //         'fadeInDown'            => __('fadeInDown', 'townhub-add-ons'),

            //      ),
            // ),
            // array(
            //     'type'                  => 'text',
            //     'param_name'            => 'ani_time',
            //     'label'                 => __('Animations time','townhub-add-ons'),
            //     'desc'                  => '',
            //     'default'               => '1.0'
            // ),
            array(
                'type'                  => 'text',
                'param_name'            => 'el_id',
                'label'                 => __('Element ID','townhub-add-ons'),
                'desc'                  => '',
                'default'               => ''
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'el_class',
                'label'                 => __('Extra Class','townhub-add-ons'),
                'desc'                  => __("Use this field to add a class name and then refer to it in your CSS." ,'townhub-add-ons'),
                'default'               => '',
                
            ),  
        )
    );
    $elements['azp_button'] = array(
        'name'                  => __('Button','townhub-add-ons'),
        'desc'                  => __('','townhub-add-ons'),
        'category'              => __("content",'townhub-add-ons'),
        'icon'                  => ESB_DIR_URL .'assets/azp-eles-icon/button.png',
        'open_settings_on_create'=>true,
        'showStyleTab'=>true,
        'showAnimationTab'=>true,
        'attrs' => array (
            array(
                'type'                  => 'text',
                'param_name'            => 'name',
                'show_in_admin'         => true,
                'label'                 => __('Text','townhub-add-ons'),
                'desc'                  => '',
                'default'               => 'Text on the button'
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'shape',
                'show_in_admin'         => true,
                'label'                 => __("Shape",'townhub-add-ons'),
                'desc'                  => __("" ,'townhub-add-ons'),
                'default'               => 'square',
                'value'                 => array(
                    'rounded'                     => __('rounded', 'townhub-add-ons'),
                    'square'                     => __('Square', 'townhub-add-ons'),
                )
            ),
            array(
                'type'                  => 'icon',
                'param_name'            => 'icon',
                'show_in_admin'         => true,
                'label'                 => __('Icon','townhub-add-ons'),
                'desc'                  => '',
                'default'               => ''
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'color',
                'show_in_admin'         => true,
                'label'                 => __("Color",'townhub-add-ons'),
                'desc'                  => __("" ,'townhub-add-ons'),
                'default'               => 'default',
                'value'                 => array(
                    'default'                     => __('Color Theme', 'townhub-add-ons'),
                    'primary'                     => __('Classic Blue', 'townhub-add-ons'),
                    'success'                     => __('Classic Green', 'townhub-add-ons'),
                    'warning'                     => __('Classic Orange', 'townhub-add-ons'),
                    'white'                     => __('white', 'townhub-add-ons'),
                    // 'black'                     => __('Clack', 'townhub-add-ons'), 
                )
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'size',
                'show_in_admin'         => true,
                'label'                 => __("Size",'townhub-add-ons'),
                'desc'                  => __("" ,'townhub-add-ons'),
                'default'               => 'md',
                'value'                 => array(
                    'xs'                     => __('Mini', 'townhub-add-ons'),
                    'sm'                     => __('Small', 'townhub-add-ons'),
                    'md'                     => __('Normal', 'townhub-add-ons'),
                    'lg'                     => __('Large', 'townhub-add-ons'),
                    'xl'                     => __('Extra Large', 'townhub-add-ons'),
                )
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'align',
                 'show_in_admin'         => true,
                'label'                 => __("Icon alignment",'townhub-add-ons'),
                'desc'                  => __("" ,'townhub-add-ons'),
                'default'               => 'left',
                'value'                 => array(
                    'left'                     => __('Left', 'townhub-add-ons'),
                    'center'                     => __('Center', 'townhub-add-ons'),
                    'right'                     => __('Right', 'townhub-add-ons'),
                )
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'link',
                'show_in_admin'         => true,
                'label'                 => __('URL (Link)','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '#'
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'el_id',
                'label'                 => __('Element ID','townhub-add-ons'),
                'desc'                  => '',
                'default'               => ''
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'el_class',
                'label'                 => __('Extra Class','townhub-add-ons'),
                'desc'                  => __("Use this field to add a class name and then refer to it in your CSS." ,'townhub-add-ons'),
                'default'               => '',
                
            ),     
        )
    );
    $elements['azp_contact_form'] = array(
        'name'                  => __(' Contact Form 7','townhub-add-ons'),
        'desc'                  => __('','townhub-add-ons'),
        'category'              => __("content",'townhub-add-ons'),
        'icon'                  => ESB_DIR_URL .'assets/azp-eles-icon/contactform.png',
        'open_settings_on_create'=>true,
        'showStyleTab'=>true,
        'showAnimationTab'=>true,
        'attrs' => array (
            array(
                'type'                  => 'select',
                'param_name'            => 'f_id',
                 'show_in_admin'         => true,
                'label'                 => __("Select a form",'townhub-add-ons'),
                'desc'                  => __("" ,'townhub-add-ons'),
                'default'               => '',
                'value'                 => townhub_addons_get_contact_form7_forms(),
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'f_title',
                 'show_in_admin'         => true,
                'label'                 => __('Form Title','townhub-add-ons'),
                'desc'                  => __('(Optional) Title to search if no ID selected or cannot find by ID.','townhub-add-ons'),
                'default'               => ''
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'el_id',
                'label'                 => __('Element ID','townhub-add-ons'),
                'desc'                  => '',
                'default'               => ''
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'el_class',
                'label'                 => __('Extra Class','townhub-add-ons'),
                'desc'                  => __("Use this field to add a class name and then refer to it in your CSS." ,'townhub-add-ons'),
                'default'               => '',
                
            ),  
        )
    );
    $elements['raw_html'] = array(
        'name'                  => __('Raw HTML','townhub-add-ons'),
        'desc'                  => '',
        'category'              => __("content",'townhub-add-ons'),
        'icon'                  => ESB_DIR_URL .'assets/azp-eles-icon/raw-html.png',
        'open_settings_on_create'=>true,
        'showStyleTab'          => true,
        'showTypographyTab'     => false,
        'showAnimationTab'      => true,
        'attrs' => array (
            
            array(
                'type'                  => 'raw_html',
                'param_name'            => 'content',
                'label'                 => __('Content','townhub-add-ons'),
                'show_in_admin'         => false,
                'desc'                  => __("HTML/JS Code" ,'townhub-add-ons'),
                'default'               => '',
                'iscontent'             => 'yes'
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'el_id',
                'show_in_admin'         => true,
                'label'                 => __('Element ID','townhub-add-ons'),
                'desc'                  => '',
                'default'               => ''
            ),
            
            array(
                'type'                  => 'text',
                'param_name'            => 'el_class',
                'show_in_admin'         => true,
                'label'                 => __('Extra Class','townhub-add-ons'),
                'desc'                  => __("Use this field to add a class name and then refer to it in your CSS." ,'townhub-add-ons'),
                'default'               => ''
            ),
            
        )
    );
    $elements['azp_cus_field'] = array(
        'name'                  => __('Custom Field','townhub-add-ons'),
        'desc'                  => __('','townhub-add-ons'),
        'category'              => __("Listings Get Field",'townhub-add-ons'),
        'icon'                  => ESB_DIR_URL .'assets/azp-eles-icon/icon.png',
        'open_settings_on_create'=>true,
        'showStyleTab'=>true,
        'showAnimationTab'=>true,
        'attrs' => array (
            array(
                'type'                  => 'text',
                'param_name'            => 'field_title',
                'show_in_admin'         => true,
                'label'                 => __('Listing Field Title','townhub-add-ons'),
                'desc'                  => '',
                'default'               => ''
            ),
            array(
                'type'                  => 'repeater',
                'param_name'            => 'cus_field',
                // 'show_in_admin'         => true,
                'label'                 => __('Custom Field','townhub-add-ons'),
                'desc'                  => '',
                'title_field'           => 'rp_text',
                'fields'                => array(
                    array(
                        'type'                  => 'text',
                        'param_name'            => 'f_title',
                        'label'                 => __('Field Title','townhub-add-ons'),
                        'desc'                  => '',
                        'default'               => ''
                    ),
                    array(
                        'type'                  => 'text',
                        'param_name'            => 'f_name',
                        'label'                 => __('Field Name','townhub-add-ons'),
                        'desc'                  => '',
                        'default'               => ''
                    ),
                    array(
                        'type'                  => 'text',
                        'param_name'            => 'f_class',
                        'show_in_admin'         => true,
                        'label'                 => __('Field Class','townhub-add-ons'),
                        'desc'                  => '',
                        'default'               => ''
                    ),
                    array(
                        'type'                  => 'select',
                        'param_name'            => 'f_type',
                        'show_in_admin'         => true,
                        'label'                 => __("Type",'townhub-add-ons'),
                        'desc'                  => __("" ,'townhub-add-ons'),
                        'default'               => 'default',
                        'value'                 => array(
                            'image'                     => __('Single image', 'townhub-add-ons'),
                            'default'                     => __('Default', 'townhub-add-ons'),
                            'list'                     => __('List', 'townhub-add-ons'),
                            'gallery'                     => __('Gallery', 'townhub-add-ons'),
                            'currency'                     => __('Currency', 'townhub-add-ons'),
                        )
                    ),
                    array(
                        'type'                  => 'select',
                        'param_name'            => 'f_wid',
                        'label'                 => __('Width','townhub-add-ons'),
                        'desc'                  => '',
                        'default'               => 'cus-wid-12',
                        'value'                 => array( 
                            ''                              => __('None', 'townhub-add-ons'),  
                            'cus-wid-1'                      => __('1 Column - 1/12', 'townhub-add-ons'),
                            'cus-wid-2'                      => __('2 Columns - 1/6', 'townhub-add-ons'),
                            'cus-wid-3'                      => __('3 Columns - 1/4', 'townhub-add-ons'),
                            'cus-wid-4'                      => __('4 Columns - 1/3', 'townhub-add-ons'),
                            'cus-wid-5'                      => __('5 Columns - 5/12', 'townhub-add-ons'),
                            'cus-wid-6'                      => __('6 Columns - 1/2', 'townhub-add-ons'),
                            'cus-wid-7'                      => __('7 Columns - 7/12', 'townhub-add-ons'),
                            'cus-wid-8'                      => __('8 Columns - 2/3', 'townhub-add-ons'),
                            'cus-wid-9'                      => __('9 Columns - 1/4', 'townhub-add-ons'),
                            'cus-wid-10'                     => __('10 Columns - 5/6', 'townhub-add-ons'),
                            'cus-wid-11'                     => __('11 Columns - 11/12', 'townhub-add-ons'),
                            'cus-wid-12'                     => __('12 Columns - 1/1', 'townhub-add-ons'),
                        ),
                    ),
                ),
                'default'               => urlencode(json_encode(array(
                    array(
                        'f_title'   =>  'Title',
                        'f_name'    =>  'field_name',
                        'f_type'    =>  'default',
                        'f_class'   =>  '',
                        'f_wid'     =>  'cus-wid-1',
                    ),

                ))),
            ),
            array(
                'type'          => 'switch',
                'param_name'    => 'use_sec_style',
                // 'show_in_admin' => true,
                'label'         => _x('Use section style', 'Listing type', 'townhub-add-ons'),
                // 'desc'                  => '',
                'default'       => 'no',
                'value'         => array(
                    'yes' => _x('Yes', 'Yes/No option', 'townhub-add-ons'),
                    'no'  => _x('No', 'Yes/No option', 'townhub-add-ons'),
                ),
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'el_id',
                'label'                 => __('Element ID','townhub-add-ons'),
                'desc'                  => '',
                'default'               => ''
            ),
            array(
                'type'                  => 'text',
                'param_name'            => 'el_class',
                'label'                 => __('Extra Class','townhub-add-ons'),
                'desc'                  => __("Use this field to add a class name and then refer to it in your CSS." ,'townhub-add-ons'),
                'default'               => '',
                
            ),

            
        )
    );
    // $elements['azp_cus_field_bk'] = array(
    //     'name'                  => __('Custom Field','townhub-add-ons'),
    //     'desc'                  => __('','townhub-add-ons'),
    //     'category'              => __("Customs Get Field",'townhub-add-ons'),
    //     'icon'                  => ESB_DIR_URL .'assets/azp-eles-icon/icon.png',
    //     'open_settings_on_create'=>true,
    //     'showStyleTab'=>true,
    //     'showAnimationTab'=>true,
    //     'attrs' => array (
    //         array(
    //             'type'                  => 'text',
    //             'param_name'            => 'f_title',
    //             'show_in_admin'         => true,
    //             'label'                 => __('Field Title','townhub-add-ons'),
    //             'desc'                  => '',
    //             'default'               => ''
    //         ),
    //         array(
    //             'type'                  => 'text',
    //             'param_name'            => 'f_name',
    //             'show_in_admin'         => true,
    //             'label'                 => __('Field Name','townhub-add-ons'),
    //             'desc'                  => '',
    //             'default'               => ''
    //         ),
    //         // array(
    //         //     'type'                  => 'text',
    //         //     'param_name'            => 'f_class',
    //         //     'show_in_admin'         => true,
    //         //     'label'                 => __('Field Class','townhub-add-ons'),
    //         //     'desc'                  => '',
    //         //     'default'               => ''
    //         // ),
    //         array(
    //             'type'                  => 'select',
    //             'param_name'            => 'f_type',
    //             'show_in_admin'         => true,
    //             'label'                 => __("Type",'townhub-add-ons'),
    //             'desc'                  => __("" ,'townhub-add-ons'),
    //             'default'               => 'input',
    //             'value'                 => array(
    //                 'input'                    => __('Input', 'townhub-add-ons'),
    //                 'textarea'                  => __('Textarea', 'townhub-add-ons'),
    //                 'select'                     => __('Select', 'townhub-add-ons'),
    //                 'checkbox'                  => __('Checkbox', 'townhub-add-ons'),
    //             )
    //         ),
    //         // array(
    //         //     'type'                  => 'select',
    //         //     'param_name'            => 'f_wid',
    //         //     'show_in_admin'         => true,
    //         //     'label'                 => __('Width','townhub-add-ons'),
    //         //     'desc'                  => '',
    //         //     'default'               => 'col-md-12',
    //         //     'value'                 => array( 
    //         //         ''                              => __('None', 'townhub-add-ons'),  
    //         //         'col-md-1'                      => __('1 Column - 1/12', 'townhub-add-ons'),
    //         //         'col-md-2'                      => __('2 Columns - 1/6', 'townhub-add-ons'),
    //         //         'col-md-3'                      => __('3 Columns - 1/4', 'townhub-add-ons'),
    //         //         'col-md-4'                      => __('4 Columns - 1/3', 'townhub-add-ons'),
    //         //         'col-md-5'                      => __('5 Columns - 5/12', 'townhub-add-ons'),
    //         //         'col-md-6'                      => __('6 Columns - 1/2', 'townhub-add-ons'),
    //         //         'col-md-7'                      => __('7 Columns - 7/12', 'townhub-add-ons'),
    //         //         'col-md-8'                      => __('8 Columns - 2/3', 'townhub-add-ons'),
    //         //         'col-md-9'                      => __('9 Columns - 1/4', 'townhub-add-ons'),
    //         //         'col-md-10'                     => __('10 Columns - 5/6', 'townhub-add-ons'),
    //         //         'col-md-11'                     => __('11 Columns - 11/12', 'townhub-add-ons'),
    //         //         'col-md-12'                     => __('12 Columns - 1/1', 'townhub-add-ons'),
    //         //     ),
    //         // ),
    //         array(
    //             'type'                  => 'text',
    //             'param_name'            => 'el_id',
    //             'label'                 => __('Element ID','townhub-add-ons'),
    //             'desc'                  => '',
    //             'default'               => ''
    //         ),
    //         array(
    //             'type'                  => 'text',
    //             'param_name'            => 'el_class',
    //             'label'                 => __('Extra Class','townhub-add-ons'),
    //             'desc'                  => __("Use this field to add a class name and then refer to it in your CSS." ,'townhub-add-ons'),
    //             'default'               => '',
                
    //         ),

            
    //     )
    // );


    $new_elements = apply_filters( 'azp_register_elements', $elements );
    if(is_array($new_elements)) $elements = array_merge($elements, $new_elements);

    /* For Styles - Animations and Responsive tabs */

    $elements['AZPStyleOptions'] = array(
        'attrs' => array (
            array(
                'type'                  =>'dimension',
                'param_name'            =>'azp_margin',
                'label'                 => __( 'Margin', 'townhub-add-ons' ),
                'desc'                  => "" ,
                'default'               => '',
                'em_unit'               => false,
                // 'per_unit'              => false,
                // 'rem_unit'              => false,
            ),

            array(
                'type'                  =>'dimension',
                'param_name'            =>'azp_padding',
                'label'                 => __( 'Padding', 'townhub-add-ons' ),
                'desc'                  => "" ,
                'default'               => ''
            ),
            array(
                'type'                  =>'dimension',
                'param_name'            =>'azp_border_width',
                'label'                 => __( 'Border Width', 'townhub-add-ons' ),
                'desc'                  => "" ,
                'default'               => '',
                'em_unit'               => false,
            ),

            

            array(
                'type'                  => 'color',
                'param_name'            => 'azp_bd_color',
                'label'                 => __( 'Border Color', 'townhub-add-ons' ),
                'desc'                  => "" ,
                'default'               => '',
                //'rgba'                => true
            ),

            array(
                'type'                  => 'select',
                'param_name'            => 'azp_bd_style',
                'label'                 => __( 'Border Style', 'townhub-add-ons' ),
                'desc'                  => "" ,
                'default'               => '',
                "value"                 => array(   
                    ''                      => __( 'Default', 'townhub-add-ons' ),
                    'solid'                 => __( 'Solid', 'townhub-add-ons' ),
                    'dotted'                => __( 'Dotted', 'townhub-add-ons' ),
                    'dashed'                => __( 'Dashed', 'townhub-add-ons' ),
                    'none'                  => __( 'None', 'townhub-add-ons' ),
                    'hidden'                => __( 'Hidden', 'townhub-add-ons' ),
                    'double'                => __( 'Double', 'townhub-add-ons' ),
                    'groove'                => __( 'Groove', 'townhub-add-ons' ),
                    'ridge'                 => __( 'Ridge', 'townhub-add-ons' ),
                    'inset'                 => __( 'Inset', 'townhub-add-ons' ),
                    'outset'                => __( 'Outset', 'townhub-add-ons' ),
                    'initial'               => __( 'Initial', 'townhub-add-ons' ),
                    'inherit'               => __( 'Inherit', 'townhub-add-ons' ),
                ),
            ),


            array(
                'type'                  => 'color',
                'param_name'            => 'azp_bg_color',
                'label'                 => __('Background Color','townhub-add-ons'),
                'desc'                  => '',
                'default'               => ''
            ),
            array(
                'type'                  => 'image',
                'param_name'            => 'azp_bg_image',
                'label'                 => __('Background Image','townhub-add-ons'),
                'fieldclass'            => 'input-small',
                'desc'                  => '',
                'default'               => ''
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'azp_bg_repeat',
                'label'                 => __('Background Repeat','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '',
                'value'                 => array(   
                    ''                     => __('Default - Repeat', 'townhub-add-ons'),
                    'repeat-x'             => __('Repeat X', 'townhub-add-ons'),
                    'repeat-y'             => __('Repeat Y', 'townhub-add-ons'),
                    'no-repeat'            => __('No Repeat', 'townhub-add-ons'),    

                ),
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'azp_bg_attachment',
                'label'                 => __('Background Attachment','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '',
                'value'                 => array(   
                    ''                     => __('Default - Scroll', 'townhub-add-ons'),
                    'fixed'                => __('Fixed', 'townhub-add-ons'),
                    'local'                => __('Local', 'townhub-add-ons'),
                    'initial'              => __( 'Initial', 'townhub-add-ons' ),
                    'inherit'              => __( 'Inherit', 'townhub-add-ons' ),
                ),
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'azp_bg_size',
                'label'                 => __('Background Size','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '',
                'value'                 => array(   
                    ''                     => __('Default - Auto', 'townhub-add-ons'),
                    'cover'                => __('Cover', 'townhub-add-ons'),
                    'contain'              => __('Contain', 'townhub-add-ons'),  

                ),
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'azp_bg_position',
                'label'                 => __('Background Position','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '',
                'value'                 => array(   
                    ''                     => __('Default', 'townhub-add-ons'),
                    'left top'             => __('Left - Top', 'townhub-add-ons'),
                    'left center'          => __('Left - Center', 'townhub-add-ons'),
                    'left bottom'          => __('Left - Bottom', 'townhub-add-ons'),
                    'right top'            => __('Right - Top', 'townhub-add-ons'),
                    'right center'         => __('Right - Center', 'townhub-add-ons'),
                    'right bottom'         => __('Right - Bottom', 'townhub-add-ons'),
                    'center top'           => __('Center - Top', 'townhub-add-ons'),
                    'center center'        => __('Center - Center', 'townhub-add-ons'),
                    'center bottom'        => __('Center - Bottom', 'townhub-add-ons'),
                ),
            ),
            // array(
            //     'type'                  => 'textarea',
            //     'param_name'            => 'additional_style',
            //     'label'                 => __('Additional Inline Style','townhub-add-ons'),
            //     'fieldclass'            => 'ele-inline-css',
            //     'desc'                  => '',
            //     'default'               => ''
            // ),
            
        )
    );

    
    // $elements['AZPTypoOptions'] = array(
    //     'attrs' => array (
    //         array(
    //             'type'                  => 'switch',
    //             'param_name'            => 'typo_tempfont',
    //             'label'                 => __('Use template font?','townhub-add-ons'),
    //             'desc'                  => __("Use default template font instead of custom Google font." ,'townhub-add-ons'),
    //             'default'               => '1',
    //             'value'                 => array(
    //                 '1'                     => __('Yes', 'townhub-add-ons'),
    //                 '0'                     => __('No', 'townhub-add-ons'),
    //             ),
    //         ),

    //         array(
    //             'type'                  => 'googlefonts',
    //             'param_name'            => 'typo_googlefont',
    //             'label'                 => __('Google Font','townhub-add-ons'),
    //             'desc'                  => '',
    //             'default'               => 'Roboto:regular',
    //             'depends_on'            => array(
    //                 'element'               =>'typo_tempfont',
    //                 'value'                 => array('0'),
    //                 'has_value'             => false
    //             ),
                
    //         ),
    //         array(
    //             'type'                  => 'text',
    //             'param_name'            => 'typo_fontsize',
    //             'label'                 => __('Font Size','townhub-add-ons'),
    //             'desc'                  => __("Unit included. Ex: 14px",'townhub-add-ons'),
    //             'default'               => ''
    //         ),
    //         array(
    //             'type'                  => 'text',
    //             'param_name'            => 'typo_lheight',
    //             'label'                 => __('Line Height','townhub-add-ons'),
    //             'desc'                  => __("Unit included: 28px or 1 for 'Font Size' value" ,'townhub-add-ons'),
    //             'default'               => '',
                
    //         ),
    //         array(
    //             'type'                  => 'color',
    //             'param_name'            => 'typo_textcolor',
    //             'label'                 => __('Text Color','townhub-add-ons'),
    //             'desc'                  => '',
    //             'default'               => ''
    //         ),
    //         array(
    //             'type'                  => 'select',
    //             'param_name'            => 'typo_textalign',
    //             'label'                 => __('Text Align','townhub-add-ons'),
    //             'desc'                  => '',
    //             'default'               => '',
    //             'value'                 => array(
    //                 ''                     => __('Template Default', 'townhub-add-ons'),
    //                 'left'                     => __('Left', 'townhub-add-ons'),
    //                 'right'                        => __('Right', 'townhub-add-ons'),
    //                 'center'                       => __('Center', 'townhub-add-ons'),
    //                 'justify'                       => __('Justify', 'townhub-add-ons'),
    //             ),
                
    //         ),

    //         array(
    //             'type'                  => 'select',
    //             'param_name'            => 'typo_texttransform',
    //             'label'                 => __('Text Transformation','townhub-add-ons'),
    //             'desc'                  => '',
    //             'default'               => '',
    //             'value'                 => array(
    //                 ''                     => __('Template Default', 'townhub-add-ons'),
    //                 'uppercase'                        => __('Uppercase', 'townhub-add-ons'),
    //                 'lowercase'                        => __('Lowercase', 'townhub-add-ons'),
    //                 'capitalize'                       => __('Capitalize', 'townhub-add-ons'),
    //             ),
                
    //         ),
            


            
    //         array(
    //             'type'                  => 'text',
    //             'param_name'            => 'typo_letterspacing',
    //             'label'                 => __('Letter Spacing','townhub-add-ons'),
    //             'desc'                  => __("Unit included. Ex: 3px" ,'townhub-add-ons'),
    //             'default'               => '',
                
    //         ),
            
    //         array(
    //             'type'                  => 'text',
    //             'param_name'            => 'typo_textindent',
    //             'label'                 => __('Text Indentation','townhub-add-ons'),
    //             'desc'                  => __("Specify the indentation of the first line of a text. Unit included. Ex: 50px" ,'townhub-add-ons'),
    //             'default'               => '',
                
    //         ),



            
    //     )
    // );

    
    //new animation from version 3
    $elements['AZPAnimationOptions'] = array(
        'attrs' => array (
            array(
                'type'                  => 'switch',
                'param_name'            => 'animation',
                'label'                 => __('Use Animation','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '0',
                'value'                 => array(   
                    '1'                     => __('Yes', 'townhub-add-ons'),
                    '0'                     => __('No', 'townhub-add-ons'),                                                                               
                ),
            ),
            

            array(
                'type'                  => 'animation',
                'param_name'            => 'animationtype',
                'label'                 => __('Animation Type','townhub-add-ons'),
                'desc'                  => '',
                'default'               => 'fadeIn',
                'value'                 => array(   
                    'bounce'                        => __('bounce', 'townhub-add-ons'),
                    'flash'                     => __('flash', 'townhub-add-ons'),
                    'pulse'                     => __('pulse', 'townhub-add-ons'),
                    'rubberBand'                        => __('rubberBand', 'townhub-add-ons'),
                    'shake'                     => __('shake', 'townhub-add-ons'),
                    'headShake'                     => __('headShake', 'townhub-add-ons'),
                    'swing'                     => __('swing', 'townhub-add-ons'),
                    'tada'                      => __('tada', 'townhub-add-ons'),
                    'jello'                     => __('jello', 'townhub-add-ons'),
                    'bounceIn'                      => __('bounceIn', 'townhub-add-ons'),
                    'bounceInDown'                      => __('bounceInDown', 'townhub-add-ons'),
                    'bounceInLeft'                      => __('bounceInLeft', 'townhub-add-ons'),
                    'bounceInRight'                     => __('bounceInRight', 'townhub-add-ons'),
                    'bounceInUp'                        => __('bounceInUp', 'townhub-add-ons'),
                    // 'bounceOut'                      => __('bounceOut', 'default'),
                    // 'bounceOutDown'                      => __('bounceOutDown', 'default'),
                    // 'bounceOutLeft'                      => __('bounceOutLeft', 'default'),
                    // 'bounceOutRight'                     => __('bounceOutRight', 'default'),
                    // 'bounceOutUp'                        => __('bounceOutUp', 'default'),
                    'fadeIn'                        => __('fadeIn', 'townhub-add-ons'),
                    'fadeInDown'                        => __('fadeInDown', 'townhub-add-ons'),
                    'fadeInDownBig'                     => __('fadeInDownBig', 'townhub-add-ons'),
                    'fadeInLeft'                        => __('fadeInLeft', 'townhub-add-ons'),
                    'fadeInLeftBig'                     => __('fadeInLeftBig', 'townhub-add-ons'),
                    'fadeInRight'                       => __('fadeInRight', 'townhub-add-ons'),
                    'fadeInRightBig'                        => __('fadeInRightBig', 'townhub-add-ons'),
                    'fadeInUp'                      => __('fadeInUp', 'townhub-add-ons'),
                    'fadeInUpBig'                       => __('fadeInUpBig', 'townhub-add-ons'),
                    // 'fadeOut'                        => __('fadeOut', 'default'),
                    // 'fadeOutDown'                        => __('fadeOutDown', 'default'),
                    // 'fadeOutDownBig'                     => __('fadeOutDownBig', 'default'),
                    // 'fadeOutLeft'                        => __('fadeOutLeft', 'default'),
                    // 'fadeOutLeftBig'                     => __('fadeOutLeftBig', 'default'),
                    // 'fadeOutRight'                       => __('fadeOutRight', 'default'),
                    // 'fadeOutRightBig'                        => __('fadeOutRightBig', 'default'),
                    // 'fadeOutUp'                      => __('fadeOutUp', 'default'),
                    // 'fadeOutUpBig'                       => __('fadeOutUpBig', 'default'),
                    'flipInX'                       => __('flipInX', 'townhub-add-ons'),
                    'flipInY'                       => __('flipInY', 'townhub-add-ons'),
                    // 'flipOutX'                       => __('flipOutX', 'default'),
                    // 'flipOutY'                       => __('flipOutY', 'default'),
                    'lightSpeedIn'                      => __('lightSpeedIn', 'townhub-add-ons'),
                    //'lightSpeedOut'                       => __('lightSpeedOut', 'default'),
                    'rotateIn'                      => __('rotateIn', 'townhub-add-ons'),
                    'rotateInDownLeft'                      => __('rotateInDownLeft', 'townhub-add-ons'),
                    'rotateInDownRight'                     => __('rotateInDownRight', 'townhub-add-ons'),
                    'rotateInUpLeft'                        => __('rotateInUpLeft', 'townhub-add-ons'),
                    'rotateInUpRight'                       => __('rotateInUpRight', 'townhub-add-ons'),
                    // 'rotateOut'                      => __('rotateOut', 'default'),
                    // 'rotateOutDownLeft'                      => __('rotateOutDownLeft', 'default'),
                    // 'rotateOutDownRight'                     => __('rotateOutDownRight', 'default'),
                    // 'rotateOutUpLeft'                        => __('rotateOutUpLeft', 'default'),
                    // 'rotateOutUpRight'                       => __('rotateOutUpRight', 'default'),
                    'hinge'                     => __('hinge', 'townhub-add-ons'),
                    'jackInTheBox'                      => __('jackInTheBox', 'townhub-add-ons'),
                    'rollIn'                        => __('rollIn', 'townhub-add-ons'),
                    //'rollOut'                     => __('rollOut', 'default'),
                    'zoomIn'                        => __('zoomIn', 'townhub-add-ons'),
                    'zoomInDown'                        => __('zoomInDown', 'townhub-add-ons'),
                    'zoomInLeft'                        => __('zoomInLeft', 'townhub-add-ons'),
                    'zoomInRight'                       => __('zoomInRight', 'townhub-add-ons'),
                    'zoomInUp'                      => __('zoomInUp', 'townhub-add-ons'),
                    // 'zoomOut'                        => __('zoomOut', 'default'),
                    // 'zoomOutDown'                        => __('zoomOutDown', 'default'),
                    // 'zoomOutLeft'                        => __('zoomOutLeft', 'default'),
                    // 'zoomOutRight'                       => __('zoomOutRight', 'default'),
                    // 'zoomOutUp'                      => __('zoomOutUp', 'default'),
                    'slideInDown'                       => __('slideInDown', 'townhub-add-ons'),
                    'slideInLeft'                       => __('slideInLeft', 'townhub-add-ons'),
                    'slideInRight'                      => __('slideInRight', 'townhub-add-ons'),
                    'slideInUp'                     => __('slideInUp', 'townhub-add-ons'),
                    // 'slideOutDown'                       => __('slideOutDown', 'default'),
                    // 'slideOutLeft'                       => __('slideOutLeft', 'default'),
                    // 'slideOutRight'                      => __('slideOutRight', 'default'),
                    // 'slideOutUp'                        => __('slideOutUp', 'default'),
                ),
            ),

            array(
                'type'                  => 'text',
                'param_name'            => 'animationdelay',
                'label'                 => __('Animation Delay','townhub-add-ons'),
                'desc'                  => __("Animation delay in milisecond" ,'townhub-add-ons'),
                'default'               => '100',
            ),
            
        )
    );

    $elements['AZPRespOptions'] = array(
        'attrs' => array (
            array(
                'type'                  => 'hidden',
                'param_name'            => 'azp_bwid',
                'label'                 => __( 'Base Width', 'townhub-add-ons' ),
                'desc'                  => "" ,
                'default'               => '100'
            ),
            array (
                'type'                  => 'label',
                'param_name'            => 'respdevice',
                'label'                 => __('Device','townhub-add-ons'),
                'desc'                  => '',
            ),
            array (
                'type'                  => 'label',
                'param_name'            => 'respoffset',
                'label'                 => __('Offset','townhub-add-ons'),
                'desc'                  => '',
            ),
            array (
                'type'                  => 'label',
                'param_name'            => 'respwidth',
                'label'                 => __('Width','townhub-add-ons'),
                'desc'                  => '',
            ),
            array (
                'type'                  => 'label',
                'param_name'            => 'resphideondevice',
                'label'                 => __('Hide on device','townhub-add-ons'),
                'desc'                  => '',
            ),
            array(
                'type'                  => 'clearfix',
            ),
            array (
                'type'                  => 'label',
                'param_name'            => 'devicedesktop',
                'label'                 => __('<i class="ti-desktop"></i>','townhub-add-ons'),
                'desc'                  => '',
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'lgoffsetclass',
                'label'                 => __('','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '',
                'value'                 => array(   
                    ''                      => __('Inherit from smaller', 'townhub-add-ons'), 
                    'col-lg-offset-0'                       => __('No offset', 'townhub-add-ons'), 
                    'col-lg-offset-1'                       => __('1 Column - 1/12', 'townhub-add-ons'), 
                    'col-lg-offset-2'                       => __('2 Columns - 1/6', 'townhub-add-ons'),  
                    'col-lg-offset-3'                       => __('3 Columns - 1/4', 'townhub-add-ons'),    
                    'col-lg-offset-4'                       => __('4 Columns - 1/3', 'townhub-add-ons'),    
                    'col-lg-offset-5'                       => __('5 Columns - 5/12', 'townhub-add-ons'),    
                    'col-lg-offset-6'                       => __('6 Columns - 1/2', 'townhub-add-ons'),    
                    'col-lg-offset-7'                       => __('7 Columns - 7/12', 'townhub-add-ons'),    
                    'col-lg-offset-8'                       => __('8 Columns - 2/3', 'townhub-add-ons'),    
                    'col-lg-offset-9'                       => __('9 Columns - 1/4', 'townhub-add-ons'),    
                    'col-lg-offset-10'                      => __('10 Columns - 5/6', 'townhub-add-ons'),    
                    'col-lg-offset-11'                      => __('11 Columns - 11/12', 'townhub-add-ons'),    
                    'col-lg-offset-12'                      => __('12 Columns - 1/1', 'townhub-add-ons'),     
                ),
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'lgwidthclass',
                'label'                 => __('','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '',
                'value'                 => array( 
                    ''                      => __('Inherit from smaller', 'townhub-add-ons'), 
                    'col-lg-1'                      => __('1 Column - 1/12', 'townhub-add-ons'), 
                    'col-lg-2'                      => __('2 Columns - 1/6', 'townhub-add-ons'),  
                    'col-lg-3'                      => __('3 Columns - 1/4', 'townhub-add-ons'),    
                    'col-lg-4'                      => __('4 Columns - 1/3', 'townhub-add-ons'),    
                    'col-lg-5'                      => __('5 Columns - 5/12', 'townhub-add-ons'),    
                    'col-lg-6'                      => __('6 Columns - 1/2', 'townhub-add-ons'),    
                    'col-lg-7'                      => __('7 Columns - 7/12', 'townhub-add-ons'),    
                    'col-lg-8'                      => __('8 Columns - 2/3', 'townhub-add-ons'),    
                    'col-lg-9'                      => __('9 Columns - 1/4', 'townhub-add-ons'),    
                    'col-lg-10'                     => __('10 Columns - 5/6', 'townhub-add-ons'),    
                    'col-lg-11'                     => __('11 Columns - 11/12', 'townhub-add-ons'),    
                    'col-lg-12'                     => __('12 Columns - 1/1', 'townhub-add-ons'),       

                ),
            ),
            array(
                'type'                  => 'switch',
                'param_name'            => 'hidden-lg',
                'label'                 => __("Hide on desktop",'townhub-add-ons'),
                'desc'                  => '',
                'default'               => '0',
                'value'                 => array(
                    '1'                     => __('Yes', 'townhub-add-ons'),
                    '0'                     => __('No', 'townhub-add-ons'),
                )
                
            ),

            array(
                'type'                  => 'clearfix',
            ),

            array(
                'type'                  => 'label',
                'param_name'            => 'devicetablethoz',
                'label'                 => __('<i class="ti-tablet"></i>','townhub-add-ons'),
                'desc'                  => '',
            ),

            array(
                'type'                  => 'select',
                'param_name'            => 'mdoffsetclass',
                'label'                 => __('','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '',
                'value'                 => array(   
                    ''                      => __('Inherit from smaller', 'townhub-add-ons'),
                    'col-md-offset-0'                       => __('No offset', 'townhub-add-ons'),
                    'col-md-offset-1'                       => __('1 Column - 1/12', 'townhub-add-ons'),
                    'col-md-offset-2'                       => __('2 Columns - 1/6', 'townhub-add-ons'),
                    'col-md-offset-3'                       => __('3 Columns - 1/4', 'townhub-add-ons'),
                    'col-md-offset-4'                       => __('4 Columns - 1/3', 'townhub-add-ons'),
                    'col-md-offset-5'                       => __('5 Columns - 5/12', 'townhub-add-ons'),
                    'col-md-offset-6'                       => __('6 Columns - 1/2', 'townhub-add-ons'),
                    'col-md-offset-7'                       => __('7 Columns - 7/12', 'townhub-add-ons'),
                    'col-md-offset-8'                       => __('8 Columns - 2/3', 'townhub-add-ons'),
                    'col-md-offset-9'                       => __('9 Columns - 1/4', 'townhub-add-ons'),
                    'col-md-offset-10'                      => __('10 Columns - 5/6', 'townhub-add-ons'),
                    'col-md-offset-11'                      => __('11 Columns - 11/12', 'townhub-add-ons'),
                    'col-md-offset-12'                      => __('12 Columns - 1/1', 'townhub-add-ons'),

                ),
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'mdwidthclass',
                'label'                 => __('','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '',
                'value'                 => array( 
                    ''                      => __('Inherit from smaller', 'townhub-add-ons'),
                    'col-md-1'                      => __('1 Column - 1/12', 'townhub-add-ons'),
                    'col-md-2'                      => __('2 Columns - 1/6', 'townhub-add-ons'),
                    'col-md-3'                      => __('3 Columns - 1/4', 'townhub-add-ons'),
                    'col-md-4'                      => __('4 Columns - 1/3', 'townhub-add-ons'),
                    'col-md-5'                      => __('5 Columns - 5/12', 'townhub-add-ons'),
                    'col-md-6'                      => __('6 Columns - 1/2', 'townhub-add-ons'),
                    'col-md-7'                      => __('7 Columns - 7/12', 'townhub-add-ons'),
                    'col-md-8'                      => __('8 Columns - 2/3', 'townhub-add-ons'),
                    'col-md-9'                      => __('9 Columns - 1/4', 'townhub-add-ons'),
                    'col-md-10'                     => __('10 Columns - 5/6', 'townhub-add-ons'),
                    'col-md-11'                     => __('11 Columns - 11/12', 'townhub-add-ons'),
                    'col-md-12'                     => __('12 Columns - 1/1', 'townhub-add-ons'),
                ),
            ),
            array(
                'type'                  => 'switch',
                'param_name'            => 'hidden-md',
                'label'                 => __("Hide on laptop",'townhub-add-ons'),
                'desc'                  => '',
                'default'               => '0',
                'value'                 => array(
                    '1'                     => __('Yes', 'townhub-add-ons'),
                    '0'                     => __('No', 'townhub-add-ons'),
                )
                
            ),
            array(
                'type'                  => 'clearfix',
            ),

            array(
                'type'                  => 'label',
                'param_name'            => 'devicetablet',
                'label'                 => __('<i class="ti-tablet"></i>','townhub-add-ons'),
                'desc'                  => '',
            ),

            array(
                'type'                  => 'select',
                'param_name'            => 'smoffsetclass',
                'label'                 => __('','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '',
                'value'                 => array(   
                    ''                      => __('Inherit from smaller', 'townhub-add-ons'),
                    'col-sm-offset-0'                       => __('No offset', 'townhub-add-ons'),
                    'col-sm-offset-1'                       => __('1 Column - 1/12', 'townhub-add-ons'),
                    'col-sm-offset-2'                       => __('2 Columns - 1/6', 'townhub-add-ons'),
                    'col-sm-offset-3'                       => __('3 Columns - 1/4', 'townhub-add-ons'),
                    'col-sm-offset-4'                       => __('4 Columns - 1/3', 'townhub-add-ons'),
                    'col-sm-offset-5'                       => __('5 Columns - 5/12', 'townhub-add-ons'),
                    'col-sm-offset-6'                       => __('6 Columns - 1/2', 'townhub-add-ons'),
                    'col-sm-offset-7'                       => __('7 Columns - 7/12', 'townhub-add-ons'),
                    'col-sm-offset-8'                       => __('8 Columns - 2/3', 'townhub-add-ons'),
                    'col-sm-offset-9'                       => __('9 Columns - 1/4', 'townhub-add-ons'),
                    'col-sm-offset-10'                      => __('10 Columns - 5/6', 'townhub-add-ons'),
                    'col-sm-offset-11'                      => __('11 Columns - 11/12', 'townhub-add-ons'),
                    'col-sm-offset-12'                      => __('12 Columns - 1/1', 'townhub-add-ons'),
                ),
            ),
            array(
                'type'                  => 'select',
                'param_name'            => 'columnwidthclass',
                'label'                 => __('Width','townhub-add-ons'),
                'desc'                  => '',
                'default'               => 'col-md-12',
                'value'                 => array(   
                    'col-md-1'                      => __('1 Column - 1/12', 'townhub-add-ons'),
                    'col-md-2'                      => __('2 Columns - 1/6', 'townhub-add-ons'),
                    'col-md-3'                      => __('3 Columns - 1/4', 'townhub-add-ons'),
                    'col-md-4'                      => __('4 Columns - 1/3', 'townhub-add-ons'),
                    'col-md-5'                      => __('5 Columns - 5/12', 'townhub-add-ons'),
                    'col-md-6'                      => __('6 Columns - 1/2', 'townhub-add-ons'),
                    'col-md-7'                      => __('7 Columns - 7/12', 'townhub-add-ons'),
                    'col-md-8'                      => __('8 Columns - 2/3', 'townhub-add-ons'),
                    'col-md-9'                      => __('9 Columns - 1/4', 'townhub-add-ons'),
                    'col-md-10'                     => __('10 Columns - 5/6', 'townhub-add-ons'),
                    'col-md-11'                     => __('11 Columns - 11/12', 'townhub-add-ons'),
                    'col-md-12'                     => __('12 Columns - 1/1', 'townhub-add-ons'),
                ),
            ),
            
            array(
                'type'                  => 'switch',
                'param_name'            => 'hidden-sm',
                'label'                 => __("Hide on tablet",'townhub-add-ons'),
                'desc'                  => '',
                'default'               => '0',
                'value'                 => array(
                    '1'                     => __('Yes', 'townhub-add-ons'),
                    '0'                     => __('No', 'townhub-add-ons'),
                )
                
            ),

            array(
                'type'                  => 'clearfix',
            ),

            array(
                'type'                  => 'label',
                'param_name'            => 'devicemobile',
                'label'                 => __('<i class="ti-mobile"></i>','townhub-add-ons'),
                'desc'                  => '',
            ),


            array(
                'type'                  => 'select',
                'param_name'            => 'xsoffsetclass',
                'label'                 => __('','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '',
                'value'                 => array(   
                    ''                      => __('No offset', 'townhub-add-ons'),
                    'col-xs-offset-1'                       => __('1 Column - 1/12', 'townhub-add-ons'),
                    'col-xs-offset-2'                       => __('2 Columns - 1/6', 'townhub-add-ons'),
                    'col-xs-offset-3'                       => __('3 Columns - 1/4', 'townhub-add-ons'),
                    'col-xs-offset-4'                       => __('4 Columns - 1/3', 'townhub-add-ons'),
                    'col-xs-offset-5'                       => __('5 Columns - 5/12', 'townhub-add-ons'),
                    'col-xs-offset-6'                       => __('6 Columns - 1/2', 'townhub-add-ons'),
                    'col-xs-offset-7'                       => __('7 Columns - 7/12', 'townhub-add-ons'),
                    'col-xs-offset-8'                       => __('8 Columns - 2/3', 'townhub-add-ons'),
                    'col-xs-offset-9'                       => __('9 Columns - 1/4', 'townhub-add-ons'),
                    'col-xs-offset-10'                      => __('10 Columns - 5/6', 'townhub-add-ons'),
                    'col-xs-offset-11'                      => __('11 Columns - 11/12', 'townhub-add-ons'),
                    'col-xs-offset-12'                      => __('12 Columns - 1/1', 'townhub-add-ons'),
                ),
            ),

            
            array(
                'type'                  => 'select',
                'param_name'            => 'xswidthclass',
                'label'                 => __('','townhub-add-ons'),
                'desc'                  => '',
                'default'               => '',
                'value'                 => array(   
                    ''                      => __('No Select', 'townhub-add-ons'),
                    'col-xs-1'                      => __('1 Column - 1/12', 'townhub-add-ons'),
                    'col-xs-2'                      => __('2 Columns - 1/6', 'townhub-add-ons'),
                    'col-xs-3'                      => __('3 Columns - 1/4', 'townhub-add-ons'),
                    'col-xs-4'                      => __('4 Columns - 1/3', 'townhub-add-ons'),
                    'col-xs-5'                      => __('5 Columns - 5/12', 'townhub-add-ons'),
                    'col-xs-6'                      => __('6 Columns - 1/2', 'townhub-add-ons'),
                    'col-xs-7'                      => __('7 Columns - 7/12', 'townhub-add-ons'),
                    'col-xs-8'                      => __('8 Columns - 2/3', 'townhub-add-ons'),
                    'col-xs-9'                      => __('9 Columns - 1/4', 'townhub-add-ons'),
                    'col-xs-10'                     => __('10 Columns - 5/6', 'townhub-add-ons'),
                    'col-xs-11'                     => __('11 Columns - 11/12', 'townhub-add-ons'),
                    'col-xs-12'                     => __('12 Columns - 1/1', 'townhub-add-ons'),
                ),
            ),
            array(
                'type'                  => 'switch',
                'param_name'            => 'hidden-xs',
                'label'                 => __("Hide on mobile",'townhub-add-ons'),
                'desc'                  => '',
                'default'               => '0',
                'value'                 => array(
                    '1'                     => __('Yes', 'townhub-add-ons'),
                    '0'                     => __('No', 'townhub-add-ons'),
                )
                
            ),

            array(
                'type'                  => 'clearfix',
            ),

        )
    );


    return $elements;

    // https://regex101.com/r/fTLd7P/1
}

function azp_add_element($name = '', $options = array()){
    AZPElements::addEle($name, $options);
}

class AZPElements{
    public static $elements = array();
    public static function init(){
        self::$elements = townhub_addons_azp_elements();
        do_action( 'azp_elements_init' );
    }
    public static function getEles(){
        return self::$elements;
    }
    public static function addEle($name = '', $options = array() ){
        if( $name != '' && !isset(self::$elements[$name]))
            self::$elements[$name] = $options;
    }
}
// init after register post type
add_action( 'init', function(){AZPElements::init();}, 15 );
// add_action( 'wp_loaded', function(){AZPElements::init();}, 15 );





add_action('wp_ajax_nopriv_townhub_addons_azp_fetch_images', 'townhub_addons_azp_fetch_images_callback');
add_action('wp_ajax_townhub_addons_azp_fetch_images', 'townhub_addons_azp_fetch_images_callback');
function townhub_addons_azp_fetch_images_callback(){
    $json = array(
        'success' => false,
        'data' => array(
            // 'POST'=>$_POST,
        ),
        'images'    => array()
    );
    $images = isset($_POST['images'])? $_POST['images'] : '';
    if(!empty($images)){
        $images = explode(",", $images);
        if( is_array($images) && !empty($images) ){
            foreach( $images as $id ){
                $json['images'][] = array(
                    'id'        => $id,
                    'url'       => wp_get_attachment_url( $id )
                );
            }
        }
    }
    $json['success'] = true;
    wp_send_json($json );
}
