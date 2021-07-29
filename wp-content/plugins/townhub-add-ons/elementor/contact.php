<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Contact extends Widget_Base {

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
        return 'contact';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Contact', 'townhub-add-ons' );
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
                'default' => 'Contact <span>Details</span>',
                'label_block' => true,
                
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
            'content',
            [
                 'label'   => __( 'Content', 'townhub-add-ons' ),
                 'type'    => Controls_Manager::TEXTAREA,
                 'default' =>'<p> Ut euismod ultricies sollicitudin. Curabitur sed dapibus nulla. Nulla eget iaculis lectus. Mauris ac maximus neque. Nam in mauris quis libero sodales eleifend. Morbi varius, nulla sit amet rutrum elementum, est elit finibus tellus, ut tristique elit risus at metus. In ut odio libero, at vulputate urna. Nulla tristique mi a massa convallis cursus. Nulla eu mi magna. Etiam suscipit commodo gravida.</p>
<div class="list-author-widget-contacts">
    <ul >
        <li><span><i class="fa fa-map-marker"></i> Address :</span> <a href="#">USA 27TH Brooklyn NY</a></li>
        <li><span><i class="fa fa-phone"></i> Phone :</span> <a href="#">+7(123)987654</a></li>
        <li><span><i class="fa fa-envelope-o"></i> Mail :</span> <a href="#">AlisaNoory@domain.com</a></li>
        <li><span><i class="fa fa-globe"></i> Website :</span> <a href="#">themeforest.net</a></li>
    </ul>
</div>
                            ',
            ]
        );
        $this->add_control(
            'link',
            [
                 'label'   => __( 'Link', 'townhub-add-ons' ),
                 'type'    => Controls_Manager::TEXTAREA,
                 'default' =>'<div class="list-widget-social">
    <ul>
        <li><a href="add link in here" target="_blank"><i class="fa fa-facebook"></i></a></li>
        <li><a href="add link in here" target="_blank"><i class="fa fa-twitter"></i></a></li>
        <li><a href="add link in here" target="_blank"><i class="fa fa-vk"></i></a></li>
        <li><a href="add link in here" target="_blank"><i class="fa fa-whatsapp"></i></a></li>
    </ul>
</div>',
            ]
        );
        

        $this->end_controls_section();

    }

    protected function render( ) {

        $settings = $this->get_settings();
        $image = $settings['image'];
        ?>
        <div class="list-single-main-item fl-wrap">
            <div class="list-single-main-item-title fl-wrap">
               <?php if($settings['title'] !='') echo '<h3>' .$settings['title'].'</h3>';?>
            </div>
            <div class="list-single-main-media fl-wrap">
               <?php 
                if(!empty($image['url'])) echo '<img src="'.$image['url'].'" class="respimg" >';
               ?>

            </div>
            <?php 
                if($settings['content']!='') echo $settings['content'];
                if($settings['link']!='') echo $settings['link'];
             ?>
            
        </div>
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
