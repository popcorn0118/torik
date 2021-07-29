<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

class CTH_Hero_Section extends Widget_Base {

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
        return 'hero_section';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Hero Section', 'townhub-add-ons' );
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
            'content',
            [
                'label' => __( 'Content', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXTAREA, // WYSIWYG,
                'default' => '<span class="section-separator"></span>
<div class="bubbles">
    <h1>Explore Best Places In City</h1>
</div>
<h3>Find some of the best tips from around the city from our partners and friends.</h3>',
                'show_label' => false,
            ]
        );

        

        // $this->add_control(
        //     'show_search',
        //     [
        //         'label' => __( 'Show Search Form', 'townhub-add-ons' ),
        //         'type' => Controls_Manager::SWITCHER,
        //         'default' => 'yes',
        //         'label_on' => __( 'Show', 'townhub-add-ons' ),
        //         'label_off' => __( 'Hide', 'townhub-add-ons' ),
        //         'return_value' => 'yes',
        //     ]
        // );

        // $this->add_control(
        //     'ltypes',
        //     [
        //         'label' => __( 'Listing Types', 'townhub-add-ons' ),
        //         'description' => __('Select listing type posts to get hero filter form from.', 'townhub-add-ons'),
        //         'type' => Controls_Manager::SELECT2,
        //         'options' => townhub_addons_get_listing_types(),
        //         'multiple' => true,
        //         'label_block' => true,
        //     ]
        // );

        $this->add_control(
            'ltypes',
            [
                'label' => __( 'Listing Types', 'townhub-add-ons' ),
                'description' => __('Comma separated string of listing type post ids to get hero filter form from.', 'townhub-add-ons'),
                'type' => Controls_Manager::TEXT,
                'default' => '6379,5064,5121,5058',
                'label_block' => true,
            ]
        );


        // $this->add_control(
        //     'show_cats',
        //     [
        //         'label' => __( 'Show Categories', 'townhub-add-ons' ),
        //         'type' => Controls_Manager::SWITCHER,
        //         'default' => 'yes',
        //         'label_on' => __( 'Show', 'townhub-add-ons' ),
        //         'label_off' => __( 'Hide', 'townhub-add-ons' ),
        //         'return_value' => 'yes',
        //     ]
        // );


        $this->add_control(
            'content_after',
            [
                'label' => __( 'Content After Search', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXTAREA, // WYSIWYG,
                'default' => '',
                'show_label' => false,
            ]
        );

        $this->add_control(
            'scroll_url',
            [
                'label' => __( 'Scroll button URL', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => '#sec2',
                
            ]
        );

        

        $this->end_controls_section();

        $this->start_controls_section(
            'filter_sec',
            [
                'label' => __('Categories List', 'townhub-add-ons'),
            ]
        );

        $this->add_control(
            'show_filter',
            [
                'label'        => __('Show Categories List', 'townhub-add-ons'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on' => _x( 'Yes', 'On/Off', 'townhub-add-ons' ),
                'label_off' => _x( 'No', 'On/Off', 'townhub-add-ons' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'cats_text',
            [
                'label' => __( 'Categories Description', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXTAREA, // WYSIWYG,
                'default' => 'Just looking around ? Use quick search by category :',
                'show_label' => false,
            ]
        );

        

        $this->add_control(
            'finclude',
            [
                'label'       => __('Cats Include', 'townhub-add-ons'),
                'type'        => Controls_Manager::TEXT,

                'label_block' => true,
                'default'     => '',
                // 'separator' => 'before',
                'description' => __('Comma/space-separated string of term ids to include. Leave empty to use default.', 'townhub-add-ons'),
            ]
        );

        $this->add_control(
            'fnumber',
            [
                'label'       => __('No of Cats', 'townhub-add-ons'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '5',
                'min'         => -1,
                'description' => '',

            ]
        );
        $this->add_control(
            'forderby',
            [
                'label'       => __('Order by', 'townhub-add-ons'),
                'type'        => Controls_Manager::SELECT,
                'options'     => [
                    'name'        => esc_html__('Name', 'townhub-add-ons'),
                    'slug'        => esc_html__('Slug', 'townhub-add-ons'),
                    'term_group'  => esc_html__('Term Group', 'townhub-add-ons'),
                    'term_id'     => esc_html__('Term ID', 'townhub-add-ons'),
                    'id'          => esc_html__('ID', 'townhub-add-ons'),
                    'description' => esc_html__('Description', 'townhub-add-ons'),
                    'parent'      => esc_html__('Parent', 'townhub-add-ons'),
                    'count'       => esc_html__('Count', 'townhub-add-ons'),
                    'include'     => esc_html__('Include', 'townhub-add-ons'),

                ],
                'default'     => 'slug',
                'separator'   => 'before',
                'description' => '',
            ]
        );

        $this->add_control(
            'forder',
            [
                'label'       => __('Sort Order', 'townhub-add-ons'),
                'type'        => Controls_Manager::SELECT,
                'options'     => [
                    'ASC'  => esc_html__('Ascending', 'townhub-add-ons'),
                    'DESC' => esc_html__('Descending', 'townhub-add-ons'),
                ],
                'default'     => 'ASC',
                'separator'   => 'before',
                'description' => '',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_background',
            [
                'label' => __( 'Background', 'townhub-add-ons' ),
            ]
        );

        $this->add_control(
            'bg_type',
            [
                'label' => __( 'Background Type', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'image' => esc_html__('Parallax Image', 'townhub-add-ons'), 
                    'slideshow' => esc_html__('Slideshow Images', 'townhub-add-ons'), 
                    'yt_video' => esc_html__('Youtube Video', 'townhub-add-ons'), 
                    'vm_video' => esc_html__('Vimeo Video', 'townhub-add-ons'), 
                    'ht_video' => esc_html__('Hosted Video', 'townhub-add-ons'), 
                ],
                'default' => 'image',
                'separator' => 'before',
                // 'description' => esc_html__("Select how to sort retrieved posts. More at ", 'townhub-add-ons').'<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex</a>.', 
            ]
        );

        $this->add_control(
            'slideshow_imgs',
            [
                'label' => __( 'Slideshow Images', 'townhub-add-ons' ),
                'type' => Controls_Manager::GALLERY,
                'condition' => [
                    'bg_type' => 'slideshow',
                ],
            ]
        );

        $this->add_control(
            'video_id',
            [
                'label' => __( 'Youtube or Vimeo Video ID', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'bg_type' => ['yt_video','vm_video'],
                ],
                'label_block' => true,
                'description' => __( 'Your Youtube or Vimeo video ID: Hg5iNVSp2z8', 'townhub-add-ons' ),
            ]
        );

        $this->add_control(
            'video_url',
            [
                'label' => __( 'Hosted Video URL', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'bg_type' => ['ht_video'],
                ],
                'label_block' => true,
                'description' => __( 'Your hosted video URL (should be in.mp4 format)', 'townhub-add-ons' ),
            ]
        );


        $this->add_control(
            'bgimage',
            [
                'label' => __( 'Background Image', 'townhub-add-ons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'bg_type' => ['yt_video','vm_video','image','ht_video'],
                ],
                'description' => __( 'Background Image', 'townhub-add-ons' ),
            ]
        );
        $this->add_control(
            'overlay_opa',
            [
                'label' => __( 'Overlay Opacity', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                // 'default' => [
                //     'url' => Utils::get_placeholder_image_src(),
                // ],
                'description' => __( 'Overlay Opacity value 0.0 - 1. Default 0.5', 'townhub-add-ons' ),
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label' => __( 'Overlay Color', 'townhub-add-ons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .overlay' => 'background-color: {{VALUE}};',
                ],
                // Set a value from the active color scheme as the default value returned by the control.
                // 'scheme' => [
                //     'type' => Scheme_Color::get_type(),
                //     'value' => Scheme_Color::COLOR_7,
                // ],
            ]
        );

        $this->end_controls_section();


    }

    protected function render( ) {

        $settings = $this->get_settings();

        // get our input from the widget settings.

        // $custom_text = ! empty( $instance['some_text'] ) ? $instance['some_text'] : ' (no text was entered ) ';
        // $post_count = ! empty( $instance['posts_per_page'] ) ? (int)$instance['posts_per_page'] : 5;

        $bgimage = townhub_addons_get_attachment_thumb_link($settings['bgimage']['id'], 'bg-image');

        ?>
        <section class="scroll-con-sec hero-section elementor-hero-section" data-scrollax-parent="true">
            <?php 
            if($settings['bg_type'] == 'image' && $bgimage != ''){ ?>
                <div class="hero-bg-wrap hero-bg-absolute"><div class="bg" style="background-image:url(<?php echo esc_url( $bgimage );?>);"  data-bg="<?php echo esc_url( $bgimage );?>" data-scrollax="properties: { translateY: '200px' }"></div></div>
            <?php }elseif($settings['bg_type'] == 'slideshow'){ ?>
                <div class="hero-bg-wrap hero-bg-absolute">
                    <div class="bg-parallax-wrap" data-scrollax="properties: { translateY: '200px' }">
                        <!--ms-container-->
                        <div class="slideshow-container" data-scrollax="properties: { translateY: '300px' }" >
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    <?php 
                                    foreach ( $settings['slideshow_imgs'] as $image ) {
                                        ?>
                                        <!--ms_item-->
                                        <div class="swiper-slide">
                                            <div class="ms-item_fs fl-wrap full-height">
                                                <div class="bg" data-bg="<?php echo esc_url( townhub_addons_get_attachment_thumb_link($image['id'], 'full') ); ?>"></div>
                                                <div class="overlay-ex op7"></div>
                                            </div>
                                        </div>
                                        <!--ms_item end-->
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!--ms-container end-->
                    </div>
                </div>
            <?php }else{ ?>
            <div class="media-container-wrap hero-bg-absolute">
                <div class="media-container video-parallax" data-scrollax="properties: { translateY: '200px' }">
                    <div class="bg mob-bg" data-bg="<?php echo esc_url( $bgimage );?>"></div>
                <?php 
                    if($settings['bg_type'] == 'yt_video') : 
                        $vidOpts = array(
                            'videoURL'          => $settings['video_id'],
                            'mute'              => true,
                            'containment'       => 'self',
                            'quality'           => 'highres', // 'default','small','medium','large','hd720','hd1080' - deprecated
                            'autoPlay'          => true,
                            'loop'              => true,
                            'showControls'      => false,
                            // 'ratio'             => 'auto',
                            'optimizeDisplay'   => false,
                        );
                        // Hg5iNVSp2z8
                    ?>
                    <div  class="background-youtube-wrapper" data-property='<?php echo json_encode($vidOpts); ?>'></div>
                <?php 
                    elseif($settings['bg_type'] == 'vm_video') : 
                        $dataArr = array();
                        $dataArr['video'] = $settings['video_id'];
                        $dataArr['quality'] = '1080p'; // '4K','2K','1080p','720p','540p','360p'
                        $dataArr['mute'] = '1';
                        $dataArr['loop'] = '1';
                        // 97871257
                        ?>
                    <div class="video-holder">
                        <div  class="background-vimeo" data-opts='<?php echo json_encode( $dataArr );?>'></div>
                    </div>
                <?php else : 
                    $video_attrs = ' autoplay';
                    $video_attrs .=' muted';
                    $video_attrs .=' loop';

                    // http://localhost:8888/townhub/wp-content/uploads/2018/03/3.mp4
                ?>
                    <div class="video-container">
                        <video<?php echo esc_attr( $video_attrs );?> class="bgvid">
                            <source src="<?php echo esc_url( $settings['video_url'] );?>" type="video/mp4">
                        </video>
                    </div>
                <?php endif; ?>
                </div>
            </div>
            <?php } ?>
            <div class="overlay"<?php if(!empty($settings['overlay_opa'])) echo ' style="opacity:'.$settings['overlay_opa'].';"';?>></div>
            <div class="hero-section-wrap fl-wrap">
                <div class="container small-container">
                    <?php 
                    if(!empty($settings['content'])): ?>
                    <div class="intro-item fl-wrap">
                        <?php echo do_shortcode( $settings['content'] );?>
                    </div>
                    <?php 
                    endif;?>
                    <?php if( !empty($settings['ltypes']) ) townhub_addons_get_template_part('template-parts/hero_search_form', '', array( 'ltypes'=> explode(",", $settings['ltypes']) ) ); ?>
                    <?php // if($settings['show_search'] == 'yes') townhub_addons_get_template_part('template-parts/hero_search_form' ); ?>
                    
                    <?php if($settings['show_filter'] == 'yes') townhub_addons_get_template_part('template-parts/hero_cats' , false , array('settings'=>$settings) ); ?>
                    
                    <?php 
                    if(!empty($settings['content_after'])): ?>
                    <div class="intro-item-after fl-wrap">
                        <?php echo do_shortcode( $settings['content_after'] );?>
                    </div>
                    <?php 
                    endif;?>
                </div>
            </div>
            <!-- <div class="bubble-bg"> </div> -->
            <?php 
            if(!empty($settings['scroll_url'])): ?>
                <div class="header-sec-link">
                    <a href="<?php echo $settings['scroll_url'];?>" class="custom-scroll-link"><i class="fal fa-angle-double-down"></i></a>
                </div>
            <?php 
            endif;?>
        </section>
        <?php



    }

    

}


