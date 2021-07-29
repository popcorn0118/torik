<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Button_Add_Listing extends Widget_Base {

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
        return 'button_add_listing';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Button Add Listing', 'townhub-add-ons' );
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
                'default'       => 'Add your hotel',
                'label_block'   => true,
                
            ]
        );
        // $this->add_control(
        //     'links',
        //     [
        //         'label' => __( 'Button Links', 'townhub-add-ons' ),
        //         'type' => Controls_Manager::TEXTAREA, // WYSIWYG,
        //         'default' => 'https://jquery.com/',
        //         'description' => __( 'Enter links for each partner (Note: divide links with linebreaks (Enter) or | and no spaces).', 'townhub-add-ons' ) 
        //     ]
        // );
        $this->add_control(
            'icon',
            [
                'label'         => __( 'Icon', 'townhub-add-ons' ),
                'type'          => Controls_Manager::ICON,
                'default'       => 'fa fa-plus'
                
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
            'btn color-bg float-btn ',
            $settings['class_css'],
        );

        $css_class = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $css_classes ) ) );
         ?> 

            <?php if(is_user_logged_in()) : ?>
                <a href="<?php echo get_the_permalink(esb_addons_get_wpml_option('submit_page')).'#/addListing';?>" class="<?php echo esc_attr($css_class );?>">
            <?php else : 
                $logBtnAttrs = townhub_addons_get_login_button_attrs( 'addlist', 'current' );
            ?>
                <a class="<?php echo esc_attr( $logBtnAttrs['class'] );?> <?php echo esc_attr($css_class );?>" href="<?php echo esc_url( $logBtnAttrs['url'] );?>" data-message="<?php esc_attr_e( 'You must be logged in to add listing.', 'townhub-add-ons' ); ?>">
            <?php endif; ?>
                <?php echo $settings['name_bt']; ?><i class="<?php echo $settings['icon'];?>"></i>
            </a>
        <?php

    }

    protected function _content_template() {}

   
    

}
