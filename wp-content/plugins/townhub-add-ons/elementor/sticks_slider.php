<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Sticks_Slider extends Widget_Base {

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
        return 'sticks_slider';
    }

    // public function get_id() {
    //      return 'header-search';
    // }

    public function get_title() {
        return __( 'Sticks Slider', 'townhub-add-ons' );
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
            'list',
            [
                    'label' => __( 'Repeater List', 'townhub-add-ons' ),
                    'type' => Controls_Manager::REPEATER,
                    'default' => [
                            [
                                
                            ],
                    ],
                    'fields' =>[
                                    [
                                        'name' => 'image',
                                        'label' => __('Choose Image' , 'townhub-add-ons'),
                                        'type' =>Controls_Manager::MEDIA,
                                        'default' =>[
                                                        'url'=> Utils::get_placeholder_image_src(),
                                                    ]
                                    ],
                                    [
                                        'name' => 'url',
                                        'label' => __( 'Website URL', 'townhub-add-ons' ),
                                        'type' => Controls_Manager::URL,
                                        'default' => [
                                                        'url' => 'http://',
                                                        'is_external' => '',
                                                     ],
                                        'show_external' => true,
                                    ],
                                ],
                    'title_field' => '{{{ list_title }}}',
            ]
        );

        $this->end_controls_section();

    }

    protected function render( ) {
        $settings = $this->get_settings();
        $list = $settings['list'];
        ?> 
        <section class="gray-section">
            <div class="container">
                <div class="fl-wrap spons-list">
                    <ul class="client-carousel">
                        <?php 
                            foreach ($list as $key ) {
                                $image = $key['image'];
                                $url = $key['url'];
                                $target = $url['is_external'] ? 'target="_blank"' : '';
                                echo '<li><a href="'.$url['url'].'" '.$target.'><img src="'.$image['url'].'" ></a></li>';
                            }
                        ?>
                    </ul>
                </div>
                <div class="sp-cont sp-cont-prev"><i class="fa fa-angle-left"></i></div>
                <div class="sp-cont sp-cont-next"><i class="fa fa-angle-right"></i></div>  
            </div>
        </section>
        <?php

    }

    // protected function _content_template() {
    //     
    //     <div class="section-title">
    //         <# if(settings.title){ #><h2>{{{settings.title}}}</h2><# } #>
    //         <# if(settings.over_title){ #><div class="section-subtitle">{{{settings.over_title}}}</div><# } #>
    //         <# if(settings.show_sep == 'yes'){ #><span class="section-separator"></span><# } #>
    //         {{{settings.sub_title}}}
    //     </div>
    //     <?php

    }


// Plugin::instance()->widgets_manager->register_widget( 'Elementor\Widget_Header_Search' );

// Plugin::$instance->elements_manager->create_element_instance

