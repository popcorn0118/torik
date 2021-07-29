<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Testimonials_Slider extends Widget_Base {

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
        return 'testimonials_slider'; 
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Testimonials Slider', 'townhub-add-ons' );
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
            'testimonials',
            [
                    'label' => __( 'Repeater List', 'townhub-add-ons' ),
                    'type' => Controls_Manager::REPEATER,
                    'default' => [
                            [
                                'name' => 'Lisa Noory',
                                'job' => 'Restaurant Owner',
                                'rating' => '5',
                                'comment' => 'Qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram seacula quarta decima et quinta decima.',
                                'name_face' => 'Via Facebook',
                                'link' => 'http://facebook.com',
                            ],
                            [
                                'name' =>'Antony Moore',
                                'job' => 'Restaurant Owner',
                                'rating' => '4',
                                'comment' => 'Qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram seacula quarta decima et quinta decima.',
                                'name_face' => 'Via Facebook',
                                'link' => 'http://facebook.com',
                            ],
                            [
                                'name' => 'Austin Harisson',
                                'job' => 'Restaurant Owner',
                                'rating' => '5',
                                'comment' => 'Qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram seacula quarta decima et quinta decima.',
                                'name_face' => 'Via Facebook',
                                'link' => 'http://facebook.com',
                            ],
                            [
                                'name' =>  'Garry Colonsi',
                                'job' => 'Restaurant Owner',
                                'rating' => '3',
                                'comment' => 'Qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram seacula quarta decima et quinta decima.',
                                'name_face' => 'Via Facebook',
                                'link' => 'http://facebook.com',
                            ],
                    ],
                    'fields' => [
                            [
                                'name' => 'name',
                                'label' => __( 'Name', 'townhub-add-ons' ),
                                'type' => Controls_Manager::TEXT,
                                'default' => __( 'Lisa Noory' , 'townhub-add-ons' ),
                                'label_block' => true,
                            ],
                            [
                                'name' => 'job',
                                'label' => __( 'Job', 'townhub-add-ons' ),
                                'type' => Controls_Manager::TEXT,
                                'default' => __( 'Restaurant Owner' , 'townhub-add-ons' ),
                                'label_block' => true,
                            ],
                            [
                                'name' => 'comment',
                                'label' => __( 'Comment', 'townhub-add-ons' ),
                                'type' => Controls_Manager::WYSIWYG,
                                'default' => 'Qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram seacula quarta decima et quinta decima.',
                                'show_label' => false,
                            ],
                            [
                                'name' => 'avatar',
                                'label' => __('Avatar Image' , 'townhub-add-ons'),
                                'type' =>Controls_Manager::MEDIA,
                                'default' =>[
                                                'url'=> Utils::get_placeholder_image_src(),
                                            ]
                            ],
                            [
                                'name' => 'rating',
                                'label' => __( 'Rating', 'townhub-add-ons' ),
                                'type' => Controls_Manager::SELECT,
                                'default' => '5',
                                'options' => [
                                    '1'  => __( '1 Star', 'townhub-add-ons' ),
                                    '2' => __( '2 Stars', 'townhub-add-ons' ),
                                    '3' => __( '3 Stars', 'townhub-add-ons' ),
                                    '4' => __( '4 Stars', 'townhub-add-ons' ),
                                    '5'   => __( '5 Stars', 'townhub-add-ons' ),
                                 ],
                                'show_label' => false,
                            ],
                            [
                                'name' => 'name_face',
                                'label' => __( 'Name Facebook', 'townhub-add-ons' ),
                                'type' => Controls_Manager::TEXT,
                                'default' => __( 'Via Facebook' , 'townhub-add-ons' ),
                                'label_block' => true,
                            ],
                            [
                                'name' => 'link',
                                'label' => __( 'Link', 'townhub-add-ons' ),
                                'type' => Controls_Manager::URL,
                                'default' => [
                                            'url' => '',
                                            'is_external' => '',
                                        ],
                            ]
                    ],
                    'title_field' => '{{{ name }}}',
            ]
        );

        

        

        $this->end_controls_section();

    }

    protected function render( ) {

        $settings = $this->get_settings();
        $testimonials = $settings['testimonials'];
        if(!empty($testimonials)) :
        ?> 
        <div class="testimonilas-carousel-wrap fl-wrap">
            <div class="listing-carousel-button listing-carousel-button-next"><i class="fas fa-caret-right"></i></div>
            <div class="listing-carousel-button listing-carousel-button-prev"><i class="fas fa-caret-left"></i></div>
            <div class="testimonilas-carousel">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php 
                        foreach ($testimonials as $key => $testi ) { 
                            $rating_base = (int)townhub_addons_get_option('rating_base');
                        ?>
                            <!--testi-item-->
                            <div class="swiper-slide">
                                <div class="testi-item fl-wrap">
                                    <?php 
                                        $avatar = $testi['avatar'];
                                        if($avatar['id'] != '') echo '<div class="testi-avatar">'.wp_get_attachment_image( $avatar['id'], 'thumbnail' ).'</div>';
                                        ?>
                                    <div class="testimonilas-text fl-wrap">
                                        <div class="listing-rating card-popup-rainingvis" data-rating="<?php echo esc_attr( $testi['rating'] );?>" data-stars="<?php echo $rating_base;?>"></div>
                                        
                                        <?php echo $testi['comment'] ?>
                                        <div class="testimonilas-avatar fl-wrap">
                                            <?php if($testi['name']!= '') echo '<h3>'.$testi['name'].'</h3>'; ?>
                                            <?php if($testi['job']!= '') echo '<h4>'.$testi['job'].'</h4>'; ?>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--testi-item end-->
                        <?php
                        } ?>
                    </div>
                </div>
            </div>
            <div class="tc-pagination"></div>

        </div>
        <?php
        endif;
        // end if if(!empty($testimonials))

    }

    // protected function _content_template() {
    //     
    //     <div class="section-title">
    //         <# if(settings.title){ #><h2>{{{settings.title}}}</h2><# } #>
    //         <# if(settings.over_title){ #><div class="section-subtitle">{{{settings.over_title}}}</div><# } #>
    //         <# if(settings.show_sep == 'yes'){ #><span class="section-separator"></span><# } #>
    //         {{{settings.sub_title}}}
    //     </div>
    //     <?php

    }


// Plugin::instance()->widgets_manager->register_widget( 'Elementor\Widget_Header_Search' );

// Plugin::$instance->elements_manager->create_element_instance

