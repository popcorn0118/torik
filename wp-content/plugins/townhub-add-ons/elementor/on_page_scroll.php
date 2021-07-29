<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_On_Page_Scroll extends Widget_Base {

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
        return 'on_page_scroll';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'On-Page Scrolling', 'townhub-add-ons' );
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
            'repeateritems',
            [
                    'label' => __( 'Scroll Menus', 'townhub-add-ons' ),
                    'type' => Controls_Manager::REPEATER,
                    'default' => [
                            [
                                'title' => 'Our story',
                                'link' => '#main-sec',
                                'icon' => 'fal fa-building',
                            ],
                            [
                                'title' => 'Promo Video',
                                'link' => '#promo-sec',
                                'icon' => 'fal fa-video',
                            ],
                            [
                                'title' => 'Our Team',
                                'link' => '#team-sec',
                                'icon' => 'far fa-users-crown',
                            ],
                            [
                                'title' => 'Why Us',
                                'link' => '#why-choose-sec',
                                'icon' => 'fal fa-user-astronaut',
                            ],
                            [
                                'title' => 'Testimonials',
                                'link' => '#testimonials-sec',
                                'icon' => 'fal fa-comment-alt-smile',
                            ],
                    ],
                    'fields' => [
                            [
                                'name' => 'title',
                                'label' => __( 'Title', 'townhub-add-ons' ),
                                'type' => Controls_Manager::TEXT,
                                'default' => 'Our story',
                                'label_block' => true,
                            ],
                            [
                                'name' => 'link',
                                'label' => __( 'Link URL', 'townhub-add-ons' ),
                                'description' => esc_html__("Section ID for on-page scrolling. Ex: #main-sec", 'townhub-add-ons'), 
                                'type' => Controls_Manager::TEXT,
                                'default' => '#main-sec',
                                'label_block' => true,
                            ],
                            [
                                'name' => 'icon',
                                'label' => __( 'Icon', 'townhub-add-ons' ),
                                'type' => 'cthicon',
                                'default' => 'fal fa-building',
                                'label_block' => true,
                            ],
                            
                    ],
                    'title_field' => '{{{ title }}}',
            ]
        );

        
        

        $this->end_controls_section();

        

    }

    protected function render( ) {
        $settings = $this->get_settings();
        $repeateritems = $settings['repeateritems'];
        if(!empty($repeateritems)) :
        ?> 
        <div class="on-page-scroll-ele">
            <div class="page-scroll-nav">
                <nav class="scroll-init color2-bg">
                    <ul class="no-list-style">
                    <?php 
                    foreach ($repeateritems as $key => $item ) { 
                        $cls = 'tolt';
                        if( strpos($item['link'], 'http') === 0 ) $cls .= ' external';
                        if( $key === 0) $cls .= ' act-scrlink';
                    ?>
                        <li><a href="<?php echo esc_url( $item['link'] ); ?>" class="<?php echo esc_attr($cls); ?>" data-microtip-position="left" data-tooltip="<?php echo esc_attr( $item['title'] ); ?>"><i class="<?php echo esc_attr( $item['icon'] ); ?>"></i></a></li>
                    <?php
                    } ?>
                    </ul>
                </nav>
            </div>
        </div> 
        <?php
        endif;
        // end if if(!empty($repeateritems))
    }

    protected function _content_template() {
        ?>
        <# if(settings.repeateritems){ #>
        <div class="on-page-scroll-ele">
            <div class="page-scroll-nav">
                <nav class="scroll-init color2-bg">
                    <ul class="no-list-style">
                    <# _.each(settings.repeateritems, function(item, index){ 
                        var cls = 'tolt';
                        if(index === 0) cls += ' act-scrlink';
                        if( item.link.indexOf('http') === 0 ) cls += ' external';
                    #>
                        <li><a href="{{item.link}}" class="{{cls}}" data-microtip-position="left" data-tooltip="{{item.title}}"><i class="{{item.icon}}"></i></a></li>
                    <# }); #>
                    </ul>
                </nav>
            </div>
        </div> 
        <# } #>
        <?php
    }

   
    

}



