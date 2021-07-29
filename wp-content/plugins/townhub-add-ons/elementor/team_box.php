<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Team_Box extends Widget_Base {

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
        return 'team_box';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Team Box', 'townhub-add-ons' );
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
            'image',
            [
                'label' => __( 'Choose Image', 'townhub-add-ons' ),
                'type' => Controls_Manager::MEDIA,
                'default' =>[
                            'url' => Utils::get_placeholder_image_src(),
                            ],
            ]
        );
        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Alisa Gray',
                'label_block' => true,
                
            ]
        );
        $this->add_control(
          'title_link',
          [
             'label' => __( 'Title Link', 'townhub-add-ons' ),
             'type' => Controls_Manager::URL,
             'default' =>[
                        'url' => '',
                        'is_external' => '',
                        ],
             'show_external' => true, // Show the 'open in new tab' button.
          ]
        );  
        $this->add_control(
            'sub_title',
            [
                'label' => __( 'Sub Title', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT, // WYSIWYG,
                'default' => 'Business consultant',
                // 'show_label' => false,
                'label_block' => true,
            ]
        );
        
        $this->add_control(
          'content',
            [   
             'label'   => __( 'Content', 'townhub-add-ons' ),
             'type'    => Controls_Manager::TEXTAREA,
             'default' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.  </p>',
            ]
        );
        $this->add_control(
          'follow',
            [   
             'label'   => __( 'Follow', 'townhub-add-ons' ),
             'type'    => Controls_Manager::TEXTAREA,
             'default' => 
'<ul class="team-social">
    <li><a href="
to link to here" target="_blank"><i class="fa fa-facebook"></i></a></li>
    <li><a href="
to link to here" target="_blank"><i class="fa fa-twitter"></i></a></li>
    <li><a href="
to link to here" target="_blank"><i class="fa fa-tumblr"></i></a></li>
    <li><a href="
to link to here" target="_blank"><i class="fa fa-behance"></i></a></li>
</ul>',
            ]
        );


        $this->end_controls_section();

    }

    protected function render( ) {

        $settings = $this->get_settings();
        $image = $settings['image'];
        $title_link = $settings['title_link'];
        $target = $title_link['is_external'] ? ' target="_blank"' : '';
        ?>
            <div class="team-holder section-team fl-wrap">
                <div class="team-box-image">
                    <div class="team-photo">
                        <?php if(!empty($settings['image'])) echo '<img src="'.$image['url'].'" class="respimg">' ; ?>                                    
                    </div>
                    <div class="team-info">
                        <?php if($settings['title']!='') echo '<h3><a href="'.$title_link['url'].'"'.$target.'>'.$settings['title'].'</a></h3>';
                        if($settings['sub_title'] !='') echo '<h4>'.$settings['sub_title'].'</h4>'; 
                        echo $settings['content'];
                        echo $settings['follow'];
                        ?>
                    </div>
                </div>
            </div>
        <?php

    }

    protected function _content_template() {
        ?>
        <div class="team-holder section-team fl-wrap">
            <div class="team-box-image">
                <div class="team-photo">
                    <img src="{{settings.image.url}}" alt="" class="respimg">                                 
                </div>
                <# var target = settings.title_link.is_external ? ' target="_blank"' : ''; #>
                <div class="team-info">
                    <# if(settings.title!=''){ #><h3><a href="#">{{{settings.title}}}</a></h3><# } #>
                    <# if(settings.sub_title!=''){ #><h4>{{{settings.sub_title}}}</h4><# } #>
                    {{{settings.content}}}
                    {{{settings.folow}}}
                    
                </div>
            </div>
        </div>
        <?php
    }

   
   

}

// Plugin::instance()->widgets_manager->register_widget( 'Elementor\Widget_Header_Search' );

// Plugin::$instance->elements_manager->create_element_instance

