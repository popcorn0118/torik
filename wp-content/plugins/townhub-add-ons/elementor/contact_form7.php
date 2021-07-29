<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Contact_Form7 extends Widget_Base {

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
        return 'contact_form7';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Contact Form 7', 'townhub-add-ons' );
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
            'f_id',
            [
                'label'       => __( 'Select a form', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'default' => '100',
                'options' => townhub_addons_get_contact_form7_forms(),
                
            ]
        );

        $this->add_control(
            'f_title',
            [
                'label'       => __( 'Form Title', 'townhub-add-ons' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'description' => __( '(Optional) Title to search if no ID selected or cannot find by ID.', 'townhub-add-ons' ),
                'label_block' => true
            ]
        );
        

        $this->end_controls_section();

        

    }

    protected function render( ) {
        $settings = $this->get_settings();
        $attrs = '';
        if($settings['f_id']) $attrs .= ' id="'.$settings['f_id'].'"';
        elseif($settings['f_title']) $attrs .= ' title="'.$settings['f_title'].'"';

        $shortcode = do_shortcode( '[contact-form-7'.$attrs.']' ) ;
        ?>
        <div class="contact-form7"><?php echo $shortcode;?></div>
        <?php
    }

    // protected function _content_template() {}

   
    

}

