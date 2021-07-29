<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Feature_Box extends Widget_Base {

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
        return 'feature_box';
    }

    // public function get_id() {
    //      return 'header-search';
    // }

    public function get_title() {
        return __( 'Feature Box', 'townhub-add-ons' );
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
                'label'         => __( 'Content', 'townhub-add-ons' ),
            ]
        );
        $this->add_control(
            'title',
            [
                'label'         => __( 'Title', 'townhub-add-ons' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => '24 Hours Support',
                'label_block'   => true,
                
            ]
        );
        $this->add_control(
            'content',
            [   
                'label'         => __( 'Content', 'townhub-add-ons' ),
                'type'          => Controls_Manager::TEXTAREA,
                'default'       => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar.    </p>',
            ]
        );
        $this->add_control(
            'icon',
            [
                'label' => __( 'Icon', 'townhub-add-ons' ),
                'type' => 'cthicon',
                'default' => 'fal fa-headset',
            ]
        );

        $this->add_control(
            'featured',
            [
                'label' => __( 'Is Featured', 'townhub-add-ons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => _x( 'Yes', 'On/Off', 'townhub-add-ons' ),
                'label_off' => _x( 'No', 'On/Off', 'townhub-add-ons' ),
                'return_value' => 'yes',
            ]
        );


        $this->end_controls_section();

    }

    protected function render( ) {
        $settings = $this->get_settings();
        $cls = 'features-box';
        if($settings['featured'] == 'yes') $cls .= ' gray-bg';
        ?>  
        <!--features-box --> 
        <div class="<?php echo esc_attr( $cls ); ?>">
            <?php if($settings['icon']!=''): ?>
            <div class="time-line-icon">
                <i class="<?php echo $settings['icon']; ?>"></i>
            </div>
            <?php endif; ?>
            <?php if($settings['title']!='') echo '<h3>'.$settings['title'].'</h3>'; ?>
            <?php echo $settings['content']; ?>
        </div>
        <!-- features-box end  -->      
        <?php
    }

    protected function _content_template() {
        ?>
        <!--features-box --> 
        <# if(settings.featured == 'yes'){ #>
        <div class="features-box gray-bg">
        <# }else{ #>
        <div class="features-box">
        <# } #>
            <# if(settings.icon!=''){ #>
            <div class="time-line-icon">
                <i class="{{settings.icon}}"></i>
            </div>
            <# } #>
            <# if(settings.title){ #><h3>{{{settings.title}}}</h3><# } #>
            {{{settings.content}}}
        </div>
        <!-- features-box end  -->      
        <?php
    }

   
   

}

// Plugin::instance()->widgets_manager->register_widget( 'Elementor\Widget_Header_Search' );

// Plugin::$instance->elements_manager->create_element_instance/ Plugin::$instance->elements_manager->create_element_instance/ Plugin::$instance->elements_manager->create_element_instance/ Plugin::$instance->elements_manager->create_element_instance/ Plugin::$instance->elements_manager->create_element_instance/ Plugin::$instance->elements_manager->create_element_instance/ Plugin::$instance->elements_manager->create_element_instance/ Plugin::$instance->elements_manager->create_element_instance