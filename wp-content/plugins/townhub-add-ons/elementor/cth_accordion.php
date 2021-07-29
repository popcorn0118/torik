<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Cth_Accordion extends Widget_Base {

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
        return 'cth_accordion';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Accordion', 'townhub-add-ons' );
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
                'label' => __( 'Accordion', 'townhub-add-ons' ),
            ]
        );

        

        $this->add_control(
            'accordions',
            [
                'label' => __( 'Accordion Item', 'townhub-add-ons' ),
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    [
                        'title' => 'What is the price of posting',
                        'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla. Nulla posuere sapien vitae lectus suscipit, et pulvinar nisi tincidunt. Aliquam erat volutpat. Curabitur convallis fringilla diam sed aliquam. Sed tempor iaculis massa faucibus feugiat. In fermentum facilisis massa, a consequat purus viverra.</p>',
                    ],
                    [
                        'title' => 'Can I upload attachments',
                        'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla. Nulla posuere sapien vitae lectus suscipit, et pulvinar nisi tincidunt. Aliquam erat volutpat. Curabitur convallis fringilla diam sed aliquam. Sed tempor iaculis massa faucibus feugiat. In fermentum facilisis massa, a consequat purus viverra.</p>',
                    ],
                    [
                        'title' => 'Can I create a profile page for business',
                        'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla. Nulla posuere sapien vitae lectus suscipit, et pulvinar nisi tincidunt. Aliquam erat volutpat. Curabitur convallis fringilla diam sed aliquam. Sed tempor iaculis massa faucibus feugiat. In fermentum facilisis massa, a consequat purus viverra.</p>',
                    ],
                ],
                'fields' => [
                    [
                        'name' => 'title',
                        'label' => __( 'Title', 'townhub-add-ons' ),
                        'type' => Controls_Manager::TEXT,
                        'default' => 'Accordion Title',
                        'label_block' => true,
                    ],
                    [
                        'name' => 'content',
                        'label' => __( 'Content', 'townhub-add-ons' ),
                        'type' => Controls_Manager::WYSIWYG,
                        'default' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla. Nulla posuere sapien vitae lectus suscipit, et pulvinar nisi tincidunt. Aliquam erat volutpat. Curabitur convallis fringilla diam sed aliquam. Sed tempor iaculis massa faucibus feugiat. In fermentum facilisis massa, a consequat purus viverra.</p>',
                        'label_block' => true,
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );


        $this->add_control(
            'active',
            [
                'label'   => __( 'Active Item - 0 for first item', 'townhub-add-ons' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 0,
                'min'     => 0,
                'max'     => 100,
                'step'    => 1,
                'label_block' => true,
            ]
        );
        

        $this->end_controls_section();

    }

    protected function render( ) {
        $settings = $this->get_settings();
        if(is_array($settings['accordions']) && !empty($settings['accordions']) ):

        ?>
        <div class="accordion accordion-wrap">
        <?php
            foreach ($settings['accordions'] as $key => $accordion) {
                ?>
            <a class="toggle<?php if($key == $settings['active']) echo ' act-accordion';?>" href="#"> <?php echo esc_html($accordion['title']); ?> <i class="fa fa-angle-down"></i></a>
            <div class="accordion-inner<?php if($key == $settings['active']) echo ' visible';?>">
                <?php echo $accordion['content']; ?>
            </div>
            <?php

            }
            ?>
        </div>
        <?php
        endif;
    }

    // protected function _content_template() {}
    // end _content_template



}
