<?php
/* add_ons_php */
/**
 * Disable front-end builder
 */
function townhub_vc_remove_frontend_links() {
    vc_disable_frontend(); // this will disable frontend editor
}
//add_action( 'vc_after_init', 'townhub_vc_remove_frontend_links' );

/**
 * Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
 */
add_action( 'vc_before_init', 'townhub_vcSetAsTheme' );
if(!function_exists('townhub_vcSetAsTheme')){
    function townhub_vcSetAsTheme() {
        vc_set_as_theme($disable_updater = true);
    }
}

// Add new Param in Row
function townhub_add_ons_add_vc_param(){
    if(function_exists('vc_add_param')){
        vc_add_param(
            'vc_row',
            array(
                "type" => "dropdown",
                "heading" => esc_html__('TownHub Predefined Section Layout', 'townhub-add-ons'),
                "param_name" => "cth_layout",
                "value" => array(   
                                esc_html__('Default', 'townhub-add-ons') => 'default',  
                                esc_html__('TownHub Home (Fullheight) Section', 'townhub-add-ons') => 'townhub_homefullheight_sec',
                                esc_html__('TownHub Page Header Section', 'townhub-add-ons') => 'townhub_head_sec',
                                esc_html__('TownHub Page Section', 'townhub-add-ons') => 'townhub_page_sec',
                                esc_html__('TownHub Background Video', 'townhub-add-ons') => 'townhub_video_bg_sec',

                ),
                "description" => esc_html__("Select one of the pre made page sections or using default", 'townhub-add-ons'), 
                "group" => "TownHub Theme",
            ) 
        );



        vc_add_param('vc_row',array(
                                
                                "type" => "dropdown",
                                "heading" => esc_html__('Content Width', 'townhub-add-ons'),
                                "param_name" => "is_fullwidth",
                                "value" => array(   
                                                esc_html__('Fullwidth','townhub-add-ons' ) => 'yes',  
                                                esc_html__('Wide Boxed','townhub-add-ons' ) => 'wide',  
                                                esc_html__('Small Boxed','townhub-add-ons' ) => 'no',   
                                                                                                                                
                                            ),
                                "std" => 'no',
                                

                                'dependency' => array(
                                    'element' => 'cth_layout',
                                    'value' => array( 'townhub_homefullheight_sec','townhub_head_sec','townhub_page_sec','townhub_video_bg_sec'),
                                    'not_empty' => false,
                                ),


                                "group" => "TownHub Theme",
                            ) 

        );



        vc_add_param('vc_row',array(
                                
                                "type" => "dropdown",
                                "heading" => esc_html__('No Padding', 'townhub-add-ons'),
                                "param_name" => "no_padding",
                                "value" => array(   
                                                esc_html__('Yes', 'townhub-add-ons') => 'yes',  
                                                esc_html__('No', 'townhub-add-ons') => 'no',   
                                                                                                                                
                                            ),
                                "std" => 'no',
                                'dependency' => array(
                                    'element' => 'cth_layout',
                                    'value' => array( 'townhub_page_sec','townhub_head_sec','townhub_video_bg_sec'),
                                    'not_empty' => false,
                                ),


                                "group" => "TownHub Theme",
                            ) 

        );

        vc_add_param('vc_row',array(
                                
                                "type" => "dropdown",
                                "heading" => esc_html__('Background Color', 'townhub-add-ons'),
                                "param_name" => "townhub_bg_color",
                                "value" => array(   
                                                esc_html__( 'Theme Color','townhub-add-ons' ) => 'color-bg',
                                                esc_html__( 'White Color','townhub-add-ons' ) => 'white-color-bg',
                                                esc_html__( 'Dark Color','townhub-add-ons' ) => 'dark-bg',
                                                esc_html__( 'Gray Color','townhub-add-ons' ) => 'gray-bg',
                                                esc_html__( 'Transparent Color','townhub-add-ons' ) => 'transparent-color-bg',
                                                                                                                                
                                            ),
                                "std" => 'white-color-bg',
                                'dependency' => array(
                                    'element' => 'cth_layout',
                                    'value' => array( 'townhub_homefullheight_sec','townhub_head_sec','townhub_page_sec','townhub_video_bg_sec'),
                                    'not_empty' => false,
                                ),
                                "group" => "TownHub Theme",  
                            ) 

        );

        vc_add_param('vc_row',array(
                                
                                "type" => "dropdown",
                                "heading" => esc_html__('Background Video Type', 'townhub-add-ons'),
                                "param_name" => "bg_video_type",
                                "value" => array(   
                                               esc_html__('Youtube Video','townhub-add-ons' ) => 'youtube',  
                                               esc_html__('Vimeo Video','townhub-add-ons' ) => 'vimeo',  
                                               esc_html__('Hosted Video','townhub-add-ons' ) => 'hosted',  
                                                                                                                                
                                            ),
                                "std" => 'hosted',
                                

                                'dependency' => array(
                                    'element' => 'cth_layout',
                                    'value' => array('townhub_video_bg_sec'),
                                    'not_empty' => false,
                                ),


                                "group" => "TownHub Theme",
                            ) 

        );

        vc_add_param('vc_row',array(
                                "type" => "textfield",
                                "heading" => esc_html__('Video URL', 'townhub-add-ons'),
                                "param_name" => "bg_video",
                                "value" => "",
                                "description" => esc_html__("Enter your Youtube, Vimeo video ID or URL for hosted video.", 'townhub-add-ons'),
                                'dependency' => array(
                                    'element' => 'cth_layout',
                                    'value'     => array('townhub_video_bg_sec'),
                                    'not_empty' => false,
                                ),
                                "group" => "TownHub Theme",
                            ) 

        );

        vc_add_param('vc_row',array(
                                "type"          => "dropdown",
                                "heading"       => esc_html__('Mute', 'townhub-add-ons'),
                                "param_name"    => "bg_video_mute",
                                "value"         => array(   
                                                    esc_html__('Yes', 'townhub-add-ons') => '1',  
                                                    esc_html__('No', 'townhub-add-ons') => '0',                                                                                
                                ),
                                "std"           =>"1",
                                'dependency' => array(
                                    'element' => 'bg_video',
                                    'not_empty' => true,
                                ),


                                "group" => "TownHub Theme",  
                            )

        );

        vc_add_param('vc_row',array(
                                    "type"          => "dropdown",
                                    "heading"       => esc_html__('Loop', 'townhub-add-ons'),
                                    "param_name"    => "bg_video_loop",
                                    "value"         => array(   
                                                        esc_html__('Yes', 'townhub-add-ons') => '1',  
                                                        esc_html__('No', 'townhub-add-ons') => '0',                                                                                
                                    ),
                                    "std"           =>"1",
                                    'dependency' => array(
                                        'element' => 'bg_video',
                                        'not_empty' => true,
                                    ),


                                    "group" => "TownHub Theme",  
                                )

        );

        vc_add_param('vc_row',array(
                                    "type" => "attach_image",
                                    "heading" => esc_html__('Parallax Background Image', 'townhub-add-ons'),
                                    "param_name" => "parallax_inner",
                                    'dependency' => array(
                                        'element' => 'cth_layout',
                                        'value' => array('townhub_homefullheight_sec','townhub_head_sec', 'townhub_page_sec','townhub_video_bg_sec'),
                                        'not_empty' => false,
                                    ),
                                    "group" => "TownHub Theme",
                                )

        );


        vc_add_param('vc_row', array(
                        'type' => 'colorpicker',
                        'heading' => esc_html__( 'Overlay Background Color', 'townhub-add-ons' ),
                        'param_name' => 'overlay_color',
                        'value'=>'rgba(0,0,0,1)',
                        'description' => esc_html__( 'Select custom background color color.', 'townhub-add-ons' ),
                        'dependency' => array(
                            'element' => 'parallax_inner',
                            'not_empty' => true,
                        ),

                        "group" => "TownHub Theme",
            )
        );

        vc_add_param('vc_row',array(
                                "type" => "textfield",
                                "heading" => esc_html__('Background Parallax Value', 'townhub-add-ons'),
                                "param_name" => "parallax_inner_val",
                                "value" => "",
                                "description" => esc_html__("Parallax CSS style values, separated by comma. Ex: 'translateX': '50px','translateY': '250px' ", 'townhub-add-ons').'<a href="'.esc_url('https://github.com/iprodev/Scrollax.js/blob/master/docs/Markup.md' ).'" target="_blank">'.esc_html__('Scrollax Documentation','townhub-add-ons' ).'</a>',
                                'dependency' => array(
                                    'element' => 'parallax_inner',
                                    'not_empty' => true,
                                ),
                                "group" => "TownHub Theme",
                            ) 

        );

        

    }
}

add_action('init','townhub_add_ons_add_vc_param' );