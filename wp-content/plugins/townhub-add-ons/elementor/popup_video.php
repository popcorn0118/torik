<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Popup_Video extends Widget_Base {

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
        return 'popup_video';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Popup Video', 'townhub-add-ons' );
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
            'video_url',
            [
                'label' => __( 'Video URL', 'townhub-add-ons' ),
                'description' => __( 'Your Youtube, Vimeo or hosted video url', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' =>'https://vimeo.com/70851162',
                'label_block' => true,
            ]
        );
        $this->add_control(
            'image',
            [
                'label' => __( 'Image', 'townhub-add-ons' ),
                'type' => Controls_Manager::MEDIA,
                'default' =>[
                                'url' => Utils::get_placeholder_image_src(),
                            ],
            ]

        );
        $this->add_control(
            'video_title',
            [
                'label' => __( 'Video Title', 'townhub-add-ons' ),
                // 'description' => __( '', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' =>'How Townhub Works',
                'label_block' => true,
            ]
        );
        $this->add_control(
            'icon',
            [
                'label' => __( 'Button Icon', 'townhub-add-ons' ),
                'type' => 'cthicon',
                'default'=>'fal fa-video',
            ]
        );


        $this->end_controls_section();

    }

    protected function render( ) {
        $settings = $this->get_settings();
        ?>
        <div class="popup-video-ele">
            <div class="list-single-main-media fl-wrap">
                <?php if($settings['image']['id']) echo wp_get_attachment_image( $settings['image']['id'], 'full' ); ?>
                <?php if($settings['video_url'] != ''): ?>
                    <a href="<?php echo esc_url( $settings['video_url']);?>" class="promo-link image-popup"><i class="<?php echo esc_attr( $settings['icon']);?>" aria-hidden="true"></i><span><?php echo $settings['video_title'];?></span></a>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    protected function _content_template() {
        ?>
        <div class="popup-video-ele">
            <div class="list-single-main-media fl-wrap">
                <# if(settings.image.url){ #><img src="{{settings.image.url}}" alt=""><# } #>
                <# if(settings.video_url != ''){ #><a class="promo-link image-popup" href="{{settings.video_url}}"><i class="{{settings.icon}}" aria-hidden="true"></i><span class="video-box-title">{{{settings.video_title}}}</span></a><# } #>
            </div>
        </div>
        <?php
    }

   
   

}

// Plugin::instance()->widgets_manager->register_widget( 'Elementor\Widget_Header_Search' );

// Plugin::$instance->elements_manager->create_element_instance

