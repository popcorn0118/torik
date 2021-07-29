<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Posts_Grid extends Widget_Base {

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
        return 'posts_grid';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Posts Grid', 'townhub-add-ons' );
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
            'cat_ids',
            [
                'label' => __( 'Post Category IDs to include', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
                'description' => __("Enter post category ids to include, separated by a comma. Leave empty to get posts from all categories.", 'townhub-add-ons')
                
            ]
        );

        $this->add_control(
            'ids',
            [
                'label' => __( 'Enter Post IDs', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                // 'default' => '437,439,451',
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
                'default' => '3',
                'description' => esc_html__("Number of posts to show (-1 for all).", 'townhub-add-ons'),
                
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
                    // 'exbig' => esc_html__('Extra Big', 'townhub-add-ons'), 
                    // 'mbig' => esc_html__('Bigger', 'townhub-add-ons'), 
                    'big' => esc_html__('Big', 'townhub-add-ons'), 
                    'medium' => esc_html__('Medium', 'townhub-add-ons'), 
                    'small' => esc_html__('Small', 'townhub-add-ons'), 
                    'extrasmall' => esc_html__('Extra Small', 'townhub-add-ons'), 
                    'no' => esc_html__('None', 'townhub-add-ons'), 
                    
                ],
                'default' => 'big',
                // 'description' => esc_html__("Number of posts to show (-1 for all).", 'townhub-add-ons'),
                
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label' => __( 'Post Description Length', 'townhub-add-ons' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '250',
                'min'     => 0,
                'max'     => 500,
                'step'    => 10,
                
                
            ]
        );

        $this->add_control(
            'show_author',
            [
                'label' => __( 'Show Author', 'townhub-add-ons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => _x( 'Yes', 'On/Off', 'townhub-add-ons' ),
                'label_off' => _x( 'No', 'On/Off', 'townhub-add-ons' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label' => __( 'Show Date', 'townhub-add-ons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => _x( 'Yes', 'On/Off', 'townhub-add-ons' ),
                'label_off' => _x( 'No', 'On/Off', 'townhub-add-ons' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'show_views',
            [
                'label' => __( 'Show Views', 'townhub-add-ons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => _x( 'Yes', 'On/Off', 'townhub-add-ons' ),
                'label_off' => _x( 'No', 'On/Off', 'townhub-add-ons' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'show_cats',
            [
                'label' => __( 'Show Categories', 'townhub-add-ons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => _x( 'Yes', 'On/Off', 'townhub-add-ons' ),
                'label_off' => _x( 'No', 'On/Off', 'townhub-add-ons' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'read_all_link',
            [
                'label' => __( 'Read All URL', 'townhub-add-ons' ),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                    'is_external' => '',
                ],
                'show_external' => true, // Show the 'open in new tab' button.
            ]
        );

        $this->add_control(
            'view_all_text',
            [
                'label'       => __('Read all Text', 'townhub-add-ons'),
                'type'        => Controls_Manager::TEXT,

                'label_block' => true,
                'default'     => 'Read All Posts',
                // 'separator' => 'before',
                'description' => '',
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
                'post_type' => 'post',
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
                'post_type' => 'post',
                'paged' => $paged,
                'posts_per_page'=> $settings['posts_per_page'],
                'post__not_in' => $ids_not,
                'orderby'=> $settings['order_by'],
                'order'=> $settings['order'],

                'post_status' => 'publish'
            );
        }else{
            $post_args = array(
                'post_type' => 'post',
                'paged' => $paged,
                'posts_per_page'=> $settings['posts_per_page'],
                'orderby'=> $settings['order_by'],
                'order'=> $settings['order'],

                'post_status' => 'publish'
            );
        }





        if(!empty($settings['cat_ids']))
            $post_args['cat'] = $settings['cat_ids'];


        // $css_classes = array(
        //     'posts-grid-wrapper',
        //     'posts-grid-',//.$settings['columns_grid']
        // );

        $css_classes = array(
            'cthiso-items cthiso-flex',
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

                    <?php 
                        // if(townhub_get_option('blog_show_format', true ))
                        //     get_template_part( 'template-parts/post/content', ( post_type_supports( get_post_type(), 'post-formats' ) ? get_post_format() : get_post_type() ) );
                        // else
                        //    get_template_part( 'template-parts/post/content' ); 
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('cthiso-item post-article ptype-content'); ?>>
                        <?php
                        if(has_post_thumbnail( )){ ?>
                        <div class="list-single-main-media fl-wrap">
                            <?php the_post_thumbnail('townhub-post-grid',array('class'=>'respimg') ); ?>
                        </div>
                        <?php } ?>
                        <div class="list-single-main-item fl-wrap block_box post-content-wrap">
                            <?php
                            the_title( '<h2 class="post-opt-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
                            
                            townhub_addons_the_excerpt_max_charlength( $settings['excerpt_length'] );
                            ?>
                            <span class="fw-separator"></span>
                            <div class="post-metas-wrap flex-items-center">
                                <?php if( $settings['show_author'] == 'yes' ):?>
                                <div class="post-author">
                                    <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>">
                                        <?php 
                                            echo get_avatar(get_the_author_meta('user_email'),'80','https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=80', get_the_author_meta( 'display_name' ) );
                                        ?>
                                        <?php echo sprintf( __( '<span>By, %s</span>', 'townhub-add-ons' ), get_the_author() ); ?>
                                            
                                    </a>
                                </div>
                                <?php endif;?>

                            

                                <?php if( $settings['show_date'] == 'yes' || $settings['show_views'] == 'yes' || $settings['show_cats'] == 'yes' ):?>
                                <div class="post-opt">
                                    <ul class="no-list-style flex-items-center">
                                        <?php if( $settings['show_date'] == 'yes' ):?><li><i class="fal fa-calendar"></i><span><?php the_time(get_option('date_format'));?></span></li><?php endif;?>
                                        <?php if( $settings['show_views'] == 'yes' ):?><li><i class="fal fa-eye"></i> <span><?php echo townhub_addons_get_post_views(get_the_ID());?></span></li><?php endif;?>
                                        <?php if( $settings['show_cats'] == 'yes' ):?>
                                            <?php if(get_the_category( )) { ?>
                                            <li><i class="fal fa-tags"></i><?php the_category( ' , ' ); ?></li>  
                                            <?php } ?>
                                        <?php endif;?>
                                    </ul>
                                </div>
                                <?php endif;?>
                            </div>
                                

                
                        </div>
                    </article>

                <?php endwhile; ?>


                

            <?php endif; ?> 

        </div>
        <?php
        if($settings['show_pagination'] == 'yes') townhub_addons_custom_pagination($posts_query->max_num_pages,$range = 2, $posts_query) ;
        ?>
        <?php
            $url = $settings['read_all_link']['url'];
            $target = $settings['read_all_link']['is_external'] ? 'target="_blank"' : '';
            if($url != '') echo '<div class="view-all-listings"><a href="' . $url . '" ' . $target . ' class="btn  dec_btn  color2-bg">' . $settings['view_all_text'] . '<i class="fal fa-arrow-alt-right"></i></a></div>';
        ?>
        <?php wp_reset_postdata();?>
        <?php

    }

    protected function _content_template() {}

   
    

}

