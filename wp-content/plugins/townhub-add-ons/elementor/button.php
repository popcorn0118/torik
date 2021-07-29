<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Button extends Widget_Base {

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
        return 'cthbutton';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Button', 'townhub-add-ons' );
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
            'section_images',
            [
                'label' => __( 'Content', 'townhub-add-ons' ),
            ]
        );
        $this->add_control(
            'name_bt',
            [
                'label'         => __( 'Name Button', 'townhub-add-ons' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => 'Our Vimeo Chanel',
                'label_block'   => true,
                
            ]
        );
        $this->add_control(
            'btntype',
            [
                'label' => __( 'Click Action', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'external_link' => esc_html__('External link', 'townhub-add-ons'), 
                    'custom-scroll-link' => esc_html__('On page scrolling', 'townhub-add-ons'), 
                    'image-popup' => esc_html__('Popup Link', 'townhub-add-ons'), 
                ],
                'default' => 'external_link',
                'separator' => 'before',
                
            ]
        );
        $this->add_control(
            'links',
            [
                'label' => __( 'Button Links', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXTAREA, // WYSIWYG,
                'default' => '#',
                'description' => __( 'Enter links for each partner (Note: divide links with linebreaks (Enter) or | and no spaces).', 'townhub-add-ons' ) 
            ]
        );
        $this->add_control(
            'icon',
            [
                'label' => __( 'Icon', 'townhub-add-ons' ),
                'type' => 'cthicon',
                'default' => 'fal fa-users',
            ]
        );

        $this->add_control(
            'btnstyle',
            [
                'label' => __( 'Button Style', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'btn' => esc_html__('Default', 'townhub-add-ons'), 
                    'promo-link' => esc_html__('Rounded', 'townhub-add-ons'), 
                ],
                'default' => 'btn',
                'separator' => 'before',
                
            ]
        );

        $this->add_control(
            'btncolor',
            [
                'label' => __( 'Button Color', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'color-bg' => esc_html__('Primary color', 'townhub-add-ons'), 
                    'color2-bg' => esc_html__('Secondary color', 'townhub-add-ons'), 
                    'white-bg' => esc_html__('White color', 'townhub-add-ons'), 
                ],
                'default' => 'color2-bg',
                'separator' => 'before',
                
            ]
        );

        


        $this->add_control(
            'class_css',
            [
                'label' => __( 'Extra class name', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'description' => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'townhub-add-ons'),
            ]
        );
        $this->end_controls_section();
    }

    protected function render( ) {
        $settings = $this->get_settings();
        $css_classes = array(
            $settings['btnstyle'],
            $settings['btncolor'],
            $settings['class_css'],
            $settings['btntype'],
        );
        
        $css_class = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $css_classes ) ) );
        ?> 
            <a href="<?php echo $settings['links']; ?>" class="<?php echo esc_attr($css_class );?>"<?php echo $settings['btntype'] == 'external_link'? ' target="_blank"':'';?>><?php echo $settings['name_bt']; ?><i class="<?php echo $settings['icon'];?>"></i></a>
        <?php

    }
    

}
