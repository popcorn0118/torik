<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Section_Title extends Widget_Base {

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
        return 'section_title';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Section Title', 'townhub-add-ons' );
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

        // $this->add_control(
        //     'local',
        //     [
        //         'label' => __( 'Section Title Location', 'townhub-add-ons' ),
        //         'type' => Controls_Manager::SELECT,
        //         'options' => [
        //             'left' => esc_html__('Left', 'townhub-add-ons'), 
        //             'center' => esc_html__('Center', 'townhub-add-ons'), 
        //             'right' => esc_html__('Right', 'townhub-add-ons'), 
        //         ],
        //         'default' => 'left',                
        //     ]
        // );

        $this->add_control(
            'sec_title_color',
            [
                'label' => __( 'Section Title Color', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'theme' => esc_html__('Theme', 'townhub-add-ons'), 
                    'white' => esc_html__('White', 'townhub-add-ons'), 
                    'dk-blue' => esc_html__('Dark Blue', 'townhub-add-ons'), 
                ],
                'default' => 'dk-blue',                
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Most Popular Palces',
                'label_block' => true,
                
            ]
        );

        $this->add_control(
            'over_title',
            [
                'label' => __( 'Sub Title', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Best Listings',
                'label_block' => true,
                // 'separator' => 'before'
                
            ]
        );
        // $this->add_control(
        //     'show_stars',
        //     [
        //         'label' => __( 'Show Stars', 'townhub-add-ons' ),
        //         'type' => Controls_Manager::SWITCHER,
        //         'default' => 'yes',
        //         'label_on' => __( 'Show', 'townhub-add-ons' ),
        //         'label_off' => __( 'Hide', 'townhub-add-ons' ),
        //         'return_value' => 'yes',
        //     ]
        // );
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
        $this->add_control(
            'separator_color',
            [
                'label' => __( 'Separator Color', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'theme' => esc_html__('Theme', 'townhub-add-ons'), 
                    'yellow' => esc_html__('Yellow', 'townhub-add-ons'), 
                    'dk-blue' => esc_html__('Dark Blue', 'townhub-add-ons'), 
                ],
                'default' => 'dk-blue',                
            ]
        );
        $this->add_control(
            'sub_title',
            [
                'label' => __( 'Description', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXTAREA, // WYSIWYG,
                'default' => '<p>Proin dapibus nisl ornare diam varius tempus. Aenean a quam luctus, finibus tellus ut, convallis eros sollicitudin turpis.</p>',
                // 'show_label' => false,
            ]
        );

        

        

        $this->end_controls_section();

    }

    protected function render( ) {

        $settings = $this->get_settings();
        ?>
        <div class="section-title section-title-<?php echo $settings['sec_title_color']; ?> fl-wrap">
            <?php //if($settings['show_stars'] == 'yes') echo '<div class="section-title-separator"><span></span></div>'; ?>
            <?php if(!empty($settings['title'])) echo '<h2><span>'.$settings['title'].'</span></h2>'; ?>
            <?php if(!empty($settings['over_title'])) echo '<div class="section-subtitle">'.$settings['over_title'].'</div>'; ?>
            <?php 
            if($settings['show_sep'] == 'yes'): ?>
                <span class="section-separator section-separator-<?php echo $settings['separator_color']; ?>"></span> 
            <?php endif; ?>
            <?php echo $settings['sub_title'];?> 
        </div>
        <?php

    }

    protected function _content_template() {
        ?>
        <div class="section-title">
            <# if(settings.title){ #><h2><span>{{{settings.title}}}</span></h2><# } #>
            <# if(settings.over_title){ #><div class="section-subtitle">{{{settings.over_title}}}</div><# } #>
            <# if(settings.show_sep == 'yes'){ #><span class="section-separator"></span><# } #>
            {{{settings.sub_title}}}
        </div>
        <?php

    }

   
   

}

// Plugin::instance()->widgets_manager->register_widget( 'Elementor\Widget_Header_Search' );

// Plugin::$instance->elements_manager->create_element_instance

