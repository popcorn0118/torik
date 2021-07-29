<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Parallax_Content extends Widget_Base {

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
        return 'parallax_content';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Parallax Content', 'townhub-add-ons' );
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
                'label' => __( 'Content', 'townhub-add-ons' ),
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Aliquam erat volutpat interdum',
                'label_block' => true,
                
            ]
        );
        $this->add_control(
            'sub_title',
            [
                'label' => __( 'Content', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '<h2>Get ready to start your exciting journey. <br> Our agency will lead you through the amazing digital world</h2>',
                'label_block' => true,
                
            ]
        );

        $this->add_control(
            'show_separator',
            [
                'label' => __( 'Show Separator', 'townhub-add-ons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => _x( 'Yes', 'On/Off', 'townhub-add-ons' ),
                'label_off' => _x( 'No', 'On/Off', 'townhub-add-ons' ),
                'return_value' => 'yes',
            ]
        );
        $this->end_controls_section();

    }

    protected function render( ) {
        $settings = $this->get_settings();
        ?>
        <div class="parallax-content">
            <div class="video_section-title fl-wrap">
                <?php 
                    if($settings['title'] !='') echo '<h4>'.$settings['title'].'</h4>';
                    if($settings['show_separator'] == 'yes'){ echo '<span class="section-separator"></span>'; };
                    echo $settings['sub_title'];
                ?>
            </div>
        </div>
        <?php

    }

    protected function _content_template() {
        ?>
        <div class="parallax-content">
            <div class="video_section-title fl-wrap">
                <# if(settings.title){ #><h4>{{{settings.title}}}</h4><# } #>
                <# if(settings.show_separator == 'yes'){#> <span class="section-separator"></span><# } #>
                {{{settings.sub_title}}}
            </div>
        </div>
        <?php
    }



}
