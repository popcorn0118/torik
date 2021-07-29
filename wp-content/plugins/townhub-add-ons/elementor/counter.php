<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Counter extends Widget_Base {

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
        return 'counter';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Counter', 'townhub-add-ons' );
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
            'number',
            [
                'label' => __( 'Counter Number', 'townhub-add-ons' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '1254',
                'min'     => 1,
                // 'max'     => 500,
                'step'    => 1,
            ]
        );

    
        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'New Visiters Every Week',
                'label_block' => true,
                
            ]
        );
        $this->add_control(
            'icon',
            [
                'label' => __( 'Icon', 'townhub-add-ons' ),
                'type' => 'cthicon',
                'default' => '',
            ]
        );

        $this->add_control(
            'fact_style',
            [
                'label' => __( 'Style', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'single-facts_2' => esc_html__('Default', 'townhub-add-ons'), 
                    'single-facts' => esc_html__('Style 2', 'townhub-add-ons'), 
                    'bold-facts' => esc_html__('Bold Style', 'townhub-add-ons'), 
                    
                ],
                'default' => 'single-facts_2',
                
            ]
        );


        $this->add_control(
            'no_decor',
            [
                'label' => __( 'Hide Decor', 'townhub-add-ons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => _x( 'Yes', 'On/Off', 'townhub-add-ons' ),
                'label_off' => _x( 'No', 'On/Off', 'townhub-add-ons' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        

        $this->end_controls_section();

        

    }

    protected function render( ) {
        $settings = $this->get_settings();
        if($settings['number']):
        ?>
        <div class="<?php echo $settings['fact_style'];?> hide-decor-<?php echo $settings['no_decor'];?>">
            <div class="inline-facts-wrap">
                <div class="inline-facts">
                    <?php if( $settings['icon'] != '' ) : ?><i class="<?php echo $settings['icon'];?>"></i><?php endif; ?>
                    <div class="milestone-counter">
                        <div class="stats animaper">
                            <div class="num" data-content="0" data-num="<?php echo $settings['number'];?>"><?php echo $settings['number'];?></div>
                        </div>
                    </div>
                    <?php if( $settings['title'] != '' ) : ?><h6><?php echo $settings['title'];?></h6><?php endif; ?>
                </div>
            </div>
        </div>
        <?php
        endif;
    }

    protected function _content_template() {
        ?>
        <# if(settings.number){ #>
        <div class="{{settings.fact_style}}  hide-decor-{{settings.no_decor}}">
            <div class="inline-facts-wrap">
                <div class="inline-facts">
                    <# if(settings.icon) { #><i class="{{settings.icon}}"></i><# } #>
                    <div class="milestone-counter">
                        <div class="stats animaper">
                            <div class="num" data-content="0" data-num="{{settings.number}}">{{{settings.number}}}</div>
                        </div>
                    </div>
                    <# if(settings.title){ #><h6>{{{settings.title}}}</h6><# } #>
                </div>
            </div>
        </div>
        <# } #>
        <?php
    }

   
    

}



