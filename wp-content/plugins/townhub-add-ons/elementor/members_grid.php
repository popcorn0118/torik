<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Members_Grid extends Widget_Base {

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
        return 'members_grid';
    }

    // public function get_id() {
    //      return 'header-search';
    // }

    public function get_title() {
        return __( 'Team Members Grid', 'townhub-add-ons' );
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
                'label' => __( 'Members Query', 'townhub-add-ons' ),
            ]
        );

        $this->add_control(
            'ids',
            [
                'label' => __( 'Enter Member IDs', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
                'description' => __("Enter Member ids to show, separated by a comma. Leave empty to show all.", 'townhub-add-ons')
                
            ]
        );
        $this->add_control(
            'ids_not',
            [
                'label' => __( 'Or Member IDs to Exclude', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
                'description' => __("Enter member ids to exclude, separated by a comma (,). Use if the field above is empty.", 'townhub-add-ons')
                
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
                'label' => __( 'Members to show', 'townhub-add-ons' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '3',
                'description' => esc_html__("Number of members to show (-1 for all).", 'townhub-add-ons'),
                
            ]
        );

        

        $this->end_controls_section();

        $this->start_controls_section(
            'section_layout',
            [
                'label' => __( 'Posts Layout', 'townhub-add-ons' ),
            ]
        );

        $this->add_control(
            'columns_grid',
            [
                'label' => __( 'Columns Grid', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'one' => esc_html__('One Column', 'townhub-add-ons'), 
                    'two' => esc_html__('Two Columns', 'townhub-add-ons'), 
                    'three' => esc_html__('Three Columns', 'townhub-add-ons'), 
                    'four' => esc_html__('Four Columns', 'townhub-add-ons'), 
                    'five' => esc_html__('Five Columns', 'townhub-add-ons'), 
                    'six' => esc_html__('Six Columns', 'townhub-add-ons'), 
                    'seven' => esc_html__('Seven Columns', 'townhub-add-ons'), 
                    'eight' => esc_html__('Eight Columns', 'townhub-add-ons'), 
                    'nine' => esc_html__('Nine Columns', 'townhub-add-ons'), 
                    'ten' => esc_html__('Ten Columns', 'townhub-add-ons'), 
                ],
                'default' => 'three',
                // 'description' => esc_html__("Number of posts to show (-1 for all).", 'townhub-add-ons'),
                
            ]
        );



        $this->add_control(
            'space',
            [
                'label' => __( 'Space', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'exbig' => esc_html__('Extra Big', 'townhub-add-ons'), 
                    'mbig' => esc_html__('Bigger', 'townhub-add-ons'), 
                    'big' => esc_html__('Big', 'townhub-add-ons'), 
                    'medium' => esc_html__('Medium', 'townhub-add-ons'), 
                    'small' => esc_html__('Small', 'townhub-add-ons'), 
                    'extrasmall' => esc_html__('Extra Small', 'townhub-add-ons'), 
                    'no' => esc_html__('None', 'townhub-add-ons'), 
                    
                ],
                'default' => 'medium',
                // 'description' => esc_html__("Number of posts to show (-1 for all).", 'townhub-add-ons'),
                
            ]
        );

        

        $this->add_control(
            'view_all_link',
            [
                'label' => __( 'View All URL', 'townhub-add-ons' ),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '',
                    'is_external' => '',
                ],
                'show_external' => true, // Show the 'open in new tab' button.
            ]
        );


        $this->add_control(
            'show_pagination',
            [
                'label' => __( 'Show Pagination', 'townhub-add-ons' ),
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

        if(is_front_page()) {
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        } else {
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        }

        if(!empty($settings['ids'])){
            $ids = explode(",", $settings['ids']);
            $post_args = array(
                'post_type' => 'member',
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
                'post_type' => 'member',
                'paged' => $paged,
                'posts_per_page'=> $settings['posts_per_page'],
                'post__not_in' => $ids_not,
                'orderby'=> $settings['order_by'],
                'order'=> $settings['order'],

                'post_status' => 'publish'
            );
        }else{
            $post_args = array(
                'post_type' => 'member',
                'paged' => $paged,
                'posts_per_page'=> $settings['posts_per_page'],
                'orderby'=> $settings['order_by'],
                'order'=> $settings['order'],

                'post_status' => 'publish'
            );
        }




        $css_classes = array(
            'cthiso-items cthiso-flex about-wrap team-box2',
            'cthiso-'.$settings['space'].'-pad',
            'cthiso-'.$settings['columns_grid'].'-cols',
        );

        $css_class = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $css_classes ) ) );

        ?>
        <div class="<?php echo esc_attr($css_class );?>">
        <?php 
            $posts_query = new \WP_Query($post_args);
            if($posts_query->have_posts()) : ?>
                <?php while($posts_query->have_posts()) : $posts_query->the_post(); ?>
                    <!-- team-item -->
                    

                    <div id="member-<?php the_ID(); ?>" <?php post_class('cthiso-item team-box'); ?>>
                        <?php
                        if(has_post_thumbnail( )){ ?>
                        <div class="team-photo">
                        <?php the_post_thumbnail('townhub-featured-image',array('class'=>'respimg') ); ?>
                        </div>
                        <?php } ?>

                        <div class="team-info fl-wrap">
                            <?php
                            the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
                            if( $job_pos = get_post_meta( get_the_ID(), 'job_pos', true ) ) echo '<h4>'.$job_pos.'</h4>';
                            ?>
                            <?php the_excerpt(); ?>
                            <?php
                            $socials = get_post_meta( get_the_ID(), ESB_META_PREFIX.'socials' ,true ); 
                            if( !empty($socials) ):
                            ?>
                            <div class="team-social">
                                <ul class="no-list-style">
                                    <?php 
                                    foreach ((array)$socials as $social) {
                                        $iconcs = 'fab fa-'.$social['name'];
                                        if($social['name'] == 'envelope') $iconcs = 'fal fa-'.$social['name'];
                                        echo '<li><a href="'.esc_url($social['url']).'" target="_blank"><i class="'.esc_attr($iconcs).'"></i></a></li>';
                                    } ?>
                                </ul>
                            </div>
                            <?php endif; ?> 
                        </div>
                    </div>
                    <!-- team-item end  -->

                <?php endwhile; ?>
            <?php endif; ?> 

        </div>
        <?php
        if($settings['show_pagination'] == 'yes') townhub_addons_custom_pagination($posts_query->max_num_pages,$range = 2, $posts_query) ;
        ?>
        <?php
            $url = $settings['view_all_link']['url'];
            $target = $settings['view_all_link']['is_external'] ? 'target="_blank"' : '';
            if($url != '') echo '<div class="view-all-listings"><a href="' . $url . '" ' . $target .' class="btn  dec_btn  color2-bg">'.__('View All','townhub-add-ons').'<i class="fal fa-arrow-alt-right"></i></a></div>';
        ?>
        <?php wp_reset_postdata();?>
        <?php

    }

    protected function _content_template() {}

   
    

}



