<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Hero_Slider extends Widget_Base {

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
        return 'hero_slider';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Hero Slider', 'townhub-add-ons' );
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
                'label' => __( 'Slides', 'townhub-add-ons' ),
            ]
        );

        

        $this->add_control(
            'slides',
            [
                'label' => __( 'Slide Items', 'townhub-add-ons' ),
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    [
                        'title' => 'Slide 1',
                        'image' => array(
                            'id' => '6453',
                            'url' => 'http://localhost:8888/townhub/wp-content/uploads/2019/08/37.jpg'
                        ),
                        'ltypes'            => '6379',
                        'content_after'     => '',
                        'content' => '<span class="section-separator"></span>
<div class="bubbles">
    <h1>Explore Best Places In City</h1>
</div>
<h3>Find some of the best tips from around the city from our partners and friends.</h3>',
                    ],
                    [
                        'title' => 'Slide 2',
                        'image' => array(
                            'id' => '6819',
                            'url' => 'http://localhost:8888/townhub/wp-content/uploads/2019/09/7.jpg'
                        ),
                        'ltypes'            => '5121',
                        'content_after'     => '',
                        'content' => '<span class="section-separator"></span>
<div class="bubbles">
    <h1>Find Best Restaurants and Cafe</h1>
</div>
<h3>Find some of the best tips from around the city from our partners and friends.</h3>',
                    ],
                    [
                        'title' => 'Slide 3',
                        'image' => array(
                            'id' => '6423',
                            'url' => 'http://localhost:8888/townhub/wp-content/uploads/2019/08/5.jpg'
                        ),
                        'ltypes'            => '5064',
                        'content_after'     => '',
                        'content' => '<span class="section-separator"></span>
<div class="bubbles">
    <h1>Visit Events and  Clubs</h1>
</div>
<h3>Find some of the best tips from around the city from our partners and friends.</h3>',
                    ],
                ],
                'fields' => [
                    [
                        'name' => 'title',
                        'label' => __( 'Title (for editing only)', 'townhub-add-ons' ),
                        'type' => Controls_Manager::TEXT,
                        'default' => 'Slide Title',
                        'label_block' => true,
                    ],
                    [
                        'name' => 'image',
                        'label' => __( 'Image', 'townhub-add-ons' ),
                        'type' => Controls_Manager::MEDIA,
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                    [
                        'name' => 'content',
                        'label' => __( 'Content', 'townhub-add-ons' ),
                        'type' => Controls_Manager::TEXTAREA, // WYSIWYG,
                        'default' => '<span class="section-separator"></span>
<div class="bubbles">
    <h1>Explore Best Places In City</h1>
</div>
<h3>Find some of the best tips from around the city from our partners and friends.</h3>',
                        'label_block' => true,
                    ],
                    

                    // [
                    //     'name' => 'ltypes',
                    //     'label' => __( 'Listing Types', 'townhub-add-ons' ),
                    //     'description' => __('Select listing type posts to get hero filter form from.', 'townhub-add-ons'),
                    //     'type' => Controls_Manager::SELECT2,
                    //     'options' => townhub_addons_get_listing_types(),
                    //     'multiple' => true,
                    //     'label_block' => true,
                    // ],

                    [
                        'name' => 'ltypes',
                        'label' => __( 'Listing Types', 'townhub-add-ons' ),
                        'description' => __('Comma separated string of listing type post ids to get hero filter form from.', 'townhub-add-ons'),
                        'type' => Controls_Manager::TEXT,
                        'default' => '6379',
                        'label_block' => true,
                    ],


                    [
                        'name' => 'content_after',
                        'label' => __( 'Additional Infos', 'townhub-add-ons' ),
                        'type' => Controls_Manager::TEXTAREA, // WYSIWYG,
                        'default' => '',
                        'label_block' => true,
                    ],

                    


                    
                ],
                'title_field' => '{{{ title }}}',
            ]
        );


        // $this->add_control(
        //     'active',
        //     [
        //         'label'   => __( 'Active Item - 0 for first item', 'townhub-add-ons' ),
        //         'type'    => Controls_Manager::NUMBER,
        //         'default' => 0,
        //         'min'     => 0,
        //         'max'     => 100,
        //         'step'    => 1,
        //         'label_block' => true,
        //     ]
        // );
        

        $this->end_controls_section();

    }

    protected function render( ) {
        $settings = $this->get_settings();
        if(is_array($settings['slides']) && !empty($settings['slides']) ):
        ?>
        <!--Hero slider-->
        <div class="hero-slider_wrap fl-wrap">
            <div class="hero-slider">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php
                        foreach ($settings['slides'] as $key => $slide) {
                            // var_dump($slide);
                        ?>
                        <!--hero-slider-item-->
                        <div class="swiper-slide">
                            <div class="hero-slider-item fl-wrap">
                                <div class="bg-tabs-wrap">
                                    <div class="bg"  data-bg="<?php echo esc_url( townhub_addons_get_attachment_thumb_link($slide['image']['id'], 'full') ); ?>"></div>
                                    <div class="overlay op7"></div>
                                </div>
                                <div class="container small-container">
                                    <?php 
                                    if(!empty($slide['content'])): ?>
                                    <div class="intro-item fl-wrap">
                                        <?php echo do_shortcode( $slide['content'] );?>
                                    </div>
                                    <?php 
                                    endif;?>
                                    <?php if( !empty($slide['ltypes']) ) townhub_addons_get_template_part('template-parts/hero_search_form', '', array( 'ltypes'=>explode(",", $slide['ltypes']) ) ); ?>
                                    <?php 
                                    if(!empty($slide['content_after'])): ?>
                                    <div class="intro-item-after fl-wrap">
                                        <?php echo do_shortcode( $slide['content_after'] );?>
                                    </div>
                                    <?php 
                                    endif;?>

                                </div>
                            </div>
                        </div>
                        <!--hero-slider-item end-->
                        <?php
                        }
                        ?>                   
                    </div>
                </div>
            </div>
            <div class="listing-carousel_pagination hero_pagination">
                <div class="listing-carousel_pagination-wrap"></div>
            </div>
            <div class="slider-hero-button-prev shb color2-bg"><i class="fas fa-caret-left"></i></div>
            <div class="slider-hero-button-next shb color2-bg"><i class="fas fa-caret-right"></i></div>
        </div>
        <!--Hero slider end-->
        <?php
        endif;
    }

    // protected function _content_template() {}
    // end _content_template



}
