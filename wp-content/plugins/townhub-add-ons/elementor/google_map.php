<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

class CTH_Google_Map extends Widget_Base {

    /**
    * Get widget name.
    *
    * Retrieve alert widget name.
    *
    * 
    * @access public
    *
    * @return string Widget name.
    */
    public function get_name() {
        return 'google_map';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Google Map', 'townhub-add-ons' );
    }

    public function get_icon() {
        // Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
        return 'cth-elementor-icon';
    }

    /**
    * Get widget categories.
    *
    * Retrieve the widget categories.
    *
    * 
    * @access public
    *
    * @return array Widget categories.
    */
    public function get_categories() {
        return [ 'townhub-elements' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Map Position', 'townhub-add-ons' ),
            ]
        );

        
        $this->add_control(
            'map_lat',
            [
                'label' => __( 'Address Latitude', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => '40.7143528',
                'description' => __('Enter your address latitude. You can get value from: ', 'townhub-add-ons').'<a href="'.esc_url('http://www.gps-coordinates.net/').'" target="_blank">'.esc_url('http://www.gps-coordinates.net/').'</a>',
                'label_block' => true,
                
            ]
        );

        $this->add_control(
            'map_lng',
            [
                'label' => __( 'Address Longtitude', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => '-74.0059731',
                'description' => __('Enter your address longtitude. You can get value from: ', 'townhub-add-ons').'<a href="'.esc_url('http://www.gps-coordinates.net/').'" target="_blank">'.esc_url('http://www.gps-coordinates.net/').'</a>',
                'label_block' => true,
                
            ]
        );

    
        $this->add_control(
            'map_address',
            [
                'label' => __( 'Address String', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Our office - New York City',
                'label_block' => true,
                
            ]
        );

        $this->add_control(
            'zoom',
            [
                'label' => __( 'Zoom Level', 'townhub-add-ons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 14,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
            ]
        );


        $this->add_control(
            'height',
            [
                'label' => __( 'Height', 'townhub-add-ons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 300,
                ],
                'range' => [
                    'px' => [
                        'min' => 40,
                        'max' => 1440,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .singleMap' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        


        
        //         array(
        //             "type"      => "attach_image",
        //             "class"     => "",
        //             "heading"   => esc_html__("Map Marker", 'townhub-add-ons'),
        //             "param_name"=> "map_marker",
        //             "value"     => "",
        //             "description" => esc_html__("Upload google map marker or leave it empty to use default.", 'townhub-add-ons')
        //         ),
        //         array(
        //             "type" => "textfield",
        //             "class"=>"",
        //             // "holder"=>'div',
        //             "heading" => esc_html__('Map Height', 'townhub-add-ons'),
        //             "param_name" => "map_height",
        //             "value" => "500",
        //             "description" => esc_html__("Enter your map height in pixel. Default: 500", 'townhub-add-ons'), 
                    
        //         ),
        //         array(
        //             "type" => "dropdown",
        //             "class"=>"",
        //             "heading" => esc_html__('Use Default Style', 'townhub-add-ons'),
        //             "param_name" => "default_style",
        //             "value" => array(   
        //                             esc_html__('No', 'townhub-add-ons') => 'false',  
        //                             esc_html__('Yes', 'townhub-add-ons') => 'true',                                                                                
        //                         ),
        //             "description" => esc_html__("Set this to Yes to use default Google map style.", 'townhub-add-ons'), 
        //             'std'=>'false'
        //         ),
        //         array(
        //             "type" => "dropdown",
                    
        //             "heading" => esc_html__('Show Zoom Control', 'townhub-add-ons'),
        //             "param_name" => "zoom_control",
        //             "value" => array(   
        //                             esc_html__('Yes', 'townhub-add-ons') => '1',  
        //                             esc_html__('No', 'townhub-add-ons') => '0',                                                                                
        //                         ),
                    
        //             'std'=>'1'
        //         ),
        //         array(
        //             "type" => "dropdown",
                    
        //             "heading" => esc_html__('Show MapType Control', 'townhub-add-ons'),
        //             "param_name" => "maptype_control",
        //             "value" => array(   
        //                             esc_html__('Yes', 'townhub-add-ons') => '1',  
        //                             esc_html__('No', 'townhub-add-ons') => '0',                                                                                
        //                         ),
                    
        //             'std'=>'1'
        //         ),
        //         array(
        //             "type" => "dropdown",
                    
        //             "heading" => esc_html__('Show Scale Control', 'townhub-add-ons'),
        //             "param_name" => "scale_control",
        //             "value" => array(   
        //                             esc_html__('Yes', 'townhub-add-ons') => '1',  
        //                             esc_html__('No', 'townhub-add-ons') => '0',                                                                                
        //                         ),
                    
        //             'std'=>'1'
        //         ),
        //         array(
        //             "type" => "dropdown",
                    
        //             "heading" => esc_html__('Scroll Wheel Control', 'townhub-add-ons'),
        //             "param_name" => "scroll_wheel",
        //             "value" => array(   
        //                             esc_html__('Yes', 'townhub-add-ons') => '1',  
        //                             esc_html__('No', 'townhub-add-ons') => '0',                                                                                
        //                         ),
                    
        //             'std'=>'0'
        //         ),
        //         array(
        //             "type" => "dropdown",
                    
        //             "heading" => esc_html__('TownHub View Control', 'townhub-add-ons'),
        //             "param_name" => "townhub_view",
        //             "value" => array(   
        //                             esc_html__('Yes', 'townhub-add-ons') => '1',  
        //                             esc_html__('No', 'townhub-add-ons') => '0',                                                                                
        //                         ),
                    
        //             'std'=>'1'
        //         ),
        //         array(
        //             "type" => "dropdown",
                    
        //             "heading" => esc_html__('Draggable Control', 'townhub-add-ons'),
        //             "param_name" => "draggable",
        //             "value" => array(   
        //                             esc_html__('Yes', 'townhub-add-ons') => '1',  
        //                             esc_html__('No', 'townhub-add-ons') => '0',                                                                                
        //                         ),
                    
        //             'std'=>'1'
        //         ),

        

        

        $this->end_controls_section();

        

    }

    protected function render( ) {
        $settings = $this->get_settings();
        // $dataArr = array();
        // $dataArr['zoom'] = (int)$settings['zoom'];

        // $dataArr['zoomControl'] = (bool)$zoom_control;
        // $dataArr['mapTypeControl'] = (bool)$maptype_control;
        // $dataArr['scaleControl'] = (bool)$scale_control;
        // $dataArr['scrollwheel'] = (bool)$scroll_wheel;
        // $dataArr['townhubViewControl'] = (bool)$townhub_view;
        // $dataArr['draggable'] = (bool)$draggable;
        ?>
        <div class="map-container">
                <div id="<?php echo uniqid('singleMap'); ?>" class="singleMap singleMap-<?php echo esc_attr( townhub_addons_get_option('map_provider') );?>" data-lat="<?php echo $settings['map_lat'];?>" data-lng="<?php echo $settings['map_lng'];?>" data-loc="<?php echo $settings['map_address'];?>" data-zoom="<?php echo $settings['zoom']['size'];?>"></div>

        </div>
        <?php
    }

    protected function _content_template() {}

   
    

}

