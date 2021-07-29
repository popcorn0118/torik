<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Process extends Widget_Base { 

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
        return 'process';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Process', 'townhub-add-ons' );
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
            'protype',
            [
                'label' => __( 'Style', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__('Default', 'townhub-add-ons'), 
                    'timeline' => esc_html__('Time line style', 'townhub-add-ons'), 
                    
                ],
                'default' => 'default',
                'separator' => 'before',
                
            ]
        );

        
        $this->add_control(
            'step',
            [
                'label' => __( 'Step Number', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => '01',
                // 'label_block' => true,
                
            ]
        );

        // $this->add_control(
        //     'icon',
        //     [
        //         'label' => __( 'Icon', 'townhub-add-ons' ),
        //         'type' => Controls_Manager::ICON,
        //         'include' => array_keys( townhub_addons_extract_awesome_pro_icon_array() ),
        //         'default' => 'fal fa-map-marker-alt',
        //     ]
        // );
        $this->add_control(
            'icon',
            [
                'label' => __( 'Icon', 'townhub-add-ons' ),
                'type' => 'cthicon',
                // 'include' => array_keys( townhub_addons_extract_awesome_pro_icon_array() ),
                'default' => 'fal fa-map-marker-alt',
            ]
        );

    
        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Find Interesting Place',
                'label_block' => true,
                
            ]
        );

        $this->add_control(
            'desc',
            [
                'label' => __( 'Description', 'townhub-add-ons' ),
                'type' => Controls_Manager::WYSIWYG,
                'default' => '<p>Proin dapibus nisl ornare diam varius tempus. Aenean a quam luctus, finibus tellus ut, convallis eros sollicitudin turpis.</p>',
                'show_label' => false,
                
            ]
        );

        $this->add_control(
            'show_decor',
            [
                'label' => __( 'Show Decoration', 'townhub-add-ons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => _x( 'Yes', 'On/Off', 'townhub-add-ons' ),
                'label_off' => _x( 'No', 'On/Off', 'townhub-add-ons' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'bot_decor',
            [
                'label' => __( 'Bottom Decoration', 'townhub-add-ons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => _x( 'Yes', 'On/Off', 'townhub-add-ons' ),
                'label_off' => _x( 'No', 'On/Off', 'townhub-add-ons' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'bot_icon',
            [
                'label' => __( 'Bottom Icon', 'townhub-add-ons' ),
                'type' => 'cthicon',
                'default' => '',
                'condition' => [
                    'protype' => ['default'],
                ],
            ]
        );

        $this->end_controls_section();

        

    }

    protected function render( ) {
        $settings = $this->get_settings();
        if($settings['protype'] == 'timeline'):

            
        ?>
        <!--process-item-->
        <div class="process-item-wrap">
            <div class="process-item_time-line bot-decor-<?php echo esc_attr($settings['bot_decor']); ?>">
                <?php if($settings['step']) : ?><div class="pi_head color-bg"><?php echo $settings['step'];?></div><?php endif; ?>
                <div class="pi-text fl-wrap">
                    <?php if($settings['icon']) : ?><div class="time-line-icon"><i class="<?php echo $settings['icon'];?>"></i></div><?php endif; ?>
                    <?php if($settings['title']) : ?><h4><?php echo $settings['title'];?></h4><?php endif; ?>
                    <?php echo $settings['desc'];?>
                </div>
                
            </div>
        </div>
        <!--process-item end-->
        <?php else: ?>

        <div class="process-item-wrap">
            <div class="process-item-inner">
                <div class="process-item">
                    <?php if($settings['step']) : ?><span class="process-count"><?php echo $settings['step'];?></span><?php endif; ?>
                    <?php if($settings['icon']) : ?><div class="time-line-icon"><i class="<?php echo $settings['icon'];?>"></i></div><?php endif; ?>
                    <?php if($settings['title']) : ?><h4><?php echo $settings['title'];?></h4><?php endif; ?>
                    <?php echo $settings['desc'];?>
                </div>
                <?php if($settings['show_decor'] == 'yes') echo '<span class="pr-dec"></span>'; ?>
            </div>
            <?php if($settings['bot_decor'] == 'yes') echo '<div class="process-end"><i class="'.$settings['bot_icon'].'"></i></div>'; ?>
        </div>
        <?php
        endif;
    }

}

