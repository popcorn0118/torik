<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

class CTH_Testimonials extends Widget_Base {

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
        return 'testimonials';
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
            'section_query',
            [
                'label' => __( 'Posts Query', 'townhub-add-ons' ),
            ]
        );

        $this->add_control(
            'ids',
            [
                'label' => __( 'Enter Post IDs', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
                'description' => __("Enter Post ids to show, separated by a comma. Leave empty to show all.", 'townhub-add-ons')
                
            ]
        );
        $this->add_control(
            'ids_not',
            [
                'label' => __( 'Or Post IDs to Exclude', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
                'description' => __("Enter post ids to exclude, separated by a comma (,). Use if the field above is empty.", 'townhub-add-ons')
                
            ]
        );

        $this->add_control(
            'order_by',
            [
                'label' => __( 'Order by', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'date' => esc_html__('Date', 'townhub-add-ons'), 
                    'ID' => esc_html__('ID', 'townhub-add-ons'), 
                    'author' => esc_html__('Author', 'townhub-add-ons'), 
                    'title' => esc_html__('Title', 'townhub-add-ons'), 
                    'modified' => esc_html__('Modified', 'townhub-add-ons'),
                    'rand' => esc_html__('Random', 'townhub-add-ons'),
                    'comment_count' => esc_html__('Comment Count', 'townhub-add-ons'),
                    'menu_order' => esc_html__('Menu Order', 'townhub-add-ons'),
                    'post__in' => esc_html__('ID order given (post__in)', 'townhub-add-ons'),
                ],
                'default' => 'date',
                'separator' => 'before',
                'description' => esc_html__("Select how to sort retrieved posts. More at ", 'townhub-add-ons').'<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex</a>.', 
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __( 'Sort Order', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'ASC' => esc_html__('Ascending', 'townhub-add-ons'), 
                    'DESC' => esc_html__('Descending', 'townhub-add-ons'), 
                ],
                'default' => 'DESC',
                'separator' => 'before',
                'description' => esc_html__("Select Ascending or Descending order. More at", 'townhub-add-ons').'<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex</a>.', 
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __( 'Posts to show', 'townhub-add-ons' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '4',
                'description' => esc_html__("Number of posts to show (-1 for all).", 'townhub-add-ons'),
                
            ]
        );

        

        $this->end_controls_section();

        

    }

    protected function render( ) {

        $settings = $this->get_settings();

        if(is_front_page()) {
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        } else {
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        }

        if(!empty($settings['ids'])){
            $ids = explode(",", $settings['ids']);
            $post_args = array(
                'post_type' => 'cth_testimonial',
                'paged' => $paged,
                'posts_per_page'=> $settings['posts_per_page'],
                'post__in' => $ids,
                'orderby'=> $settings['order_by'],
                'order'=> $settings['order'],
                'post_status' => 'publish'
            );
        }elseif(!empty($settings['ids_not'])){
            $ids_not = explode(",", $settings['ids_not']);
            $post_args = array(
                'post_type' => 'cth_testimonial',
                'paged' => $paged,
                'posts_per_page'=> $settings['posts_per_page'],
                'post__not_in' => $ids_not,
                'orderby'=> $settings['order_by'],
                'order'=> $settings['order'],

                'post_status' => 'publish'
            );
        }else{
            $post_args = array(
                'post_type' => 'cth_testimonial',
                'paged' => $paged,
                'posts_per_page'=> $settings['posts_per_page'],
                'orderby'=> $settings['order_by'],
                'order'=> $settings['order'],

                'post_status' => 'publish'
            );
        }

        $css_classes = array(
            'testimonilas-carousel-wrap fl-wrap',
            // 'posts-grid-',//.$settings['columns_grid']
        );

        $css_class = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $css_classes ) ) );
        ?>
        <!-- carousel -->
        <div class="<?php echo esc_attr( $css_class );?>">
            <?php
            $posts_query = new \WP_Query($post_args);
            if($posts_query->have_posts()) : ?>
            <div class="listing-carousel-button listing-carousel-button-next"><i class="fas fa-caret-right"></i></div>
            <div class="listing-carousel-button listing-carousel-button-prev"><i class="fas fa-caret-left"></i></div>
            <div class="testimonilas-carousel">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                    <?php 
                    // $rating_base = (int)townhub_addons_get_option('rating_base');
                    while($posts_query->have_posts()) : $posts_query->the_post(); ?>
                        <!--testi-item-->
                        <div class="swiper-slide">
                            <div class="testi-item fl-wrap">
                                <?php if(has_post_thumbnail()): ?>
                                <div class="testi-avatar">
                                    <?php the_post_thumbnail( 'thumbnail' ); ?>
                                </div>
                                <?php endif; ?>
                                <div class="testimonilas-text fl-wrap">
                                    <div class="listing-rating card-popup-rainingvis" data-rating="<?php echo esc_attr( get_post_meta( get_the_ID(), ESB_META_PREFIX.'testim_rate', true ) ); ?>" data-stars="5"></div>
                                    <?php the_content(); ?>
                                    <div class="testimonilas-avatar fl-wrap">
                                        <?php the_title('<h3>','</h3>'); ?>
                                        <?php echo get_post_meta( get_the_ID(), ESB_META_PREFIX.'job', true ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--testi-item end-->
                    <?php
                    endwhile; ?>
                    </div>
                </div>
            </div>
            <div class="tc-pagination"></div>

        </div>
        <!--  carousel end-->
        <?php endif; ?>      
        <?php 
        wp_reset_postdata();

    }


}
