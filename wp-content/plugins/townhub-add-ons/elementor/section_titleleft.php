<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Section_Titleleft extends Widget_Base {

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
        return 'section_titleleft';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Section Title Left', 'townhub-add-ons' );
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
                'default' => 'Our Awesome  Team <span>Story</span>',
                'label_block' => true,
                
            ]
        );
        $this->add_control(
            'content',
            [
                'label' => __( 'Sub Title', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '<h4>Check video presentation to find   out more about us .</h4>',
                'label_block' => true,
                
            ]
        );
       

        $this->add_control(
            'show_sep',
            [
                'label' => __( 'Show Separator', 'townhub-add-ons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
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
        <div class="ab_text-title fl-wrap">
            <?php 
                if(!empty($settings['title'])) echo '<h3>'.$settings['title'].'</h3>';
                echo $settings['content'];
                if($settings['show_sep'] == 'yes') echo '<span class="section-separator fl-sec-sep"></span>';
            ?>
        </div>
        <?php
    }

    protected function _content_template() {
        ?>
        <div class="ab_text-title fl-wrap">
            <# if(settings.title){ #><h3>{{{settings.title}}}</h3><# } #>
            {{{settings.content}}}
            <# if(settings.show_sep=='yes'){ #><span class="section-separator fl-sec-sep"></span><# } #>
        </div>
        <?php
    }

   
   

}
