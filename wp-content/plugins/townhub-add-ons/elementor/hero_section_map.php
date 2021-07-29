<?php
/* add_ons_php */

namespace Elementor;



if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Hero_Section_Map extends Widget_Base {

    /**
    * Get widget name.
    *
    * Retrieve alert widget name.
    *
    * 
    * @access public
    *
    * @return string Widget name.
    * 
    */
    public function get_name() {
        return 'hero_section_map';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Hero Section Map', 'townhub-add-ons' );
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
                'default' => '',
                'show_label' => false,
            ]
        );

        

        $this->add_control(
            'ltypes',
            [
                'label' => __( 'Listing Types', 'townhub-add-ons' ),
                'description' => __('Comma separated string of listing type post ids to get hero filter form from.', 'townhub-add-ons'),
                'type' => Controls_Manager::TEXT,
                'default' => '6379',
                'label_block' => true,
            ]
        );

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
            'cats',
            [
                'label' => __( 'Categories List', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT2,
                'options' => townhub_addons_get_listing_categories_select2(),
                'multiple' => true,
                'label_block' => true,
                'default'   => ''
            ]
        );
        

        $this->add_control(
            'scroll_url',
            [
                'label' => __( 'Scroll button URL', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                
            ]
        );

        

        $this->end_controls_section();

        $this->start_controls_section(
            'section_background',
            [
                'label' => __( 'Listings Map Data', 'townhub-add-ons' ),
            ]
        );

        

        $this->add_control(
            'cat_ids',
            [
                'label' => __( 'Categories to get listings', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT2,
                'options' => townhub_addons_get_listing_categories_select2(),
                'multiple' => true,
                'label_block' => true,
                
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
            'use_geolocation',
            [
                'label' => __( 'Or Show listings nearby user location?', 'townhub-add-ons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => _x( 'Yes', 'On/Off', 'townhub-add-ons' ),
                'label_off' => _x( 'No', 'On/Off', 'townhub-add-ons' ),
                'return_value' => 'yes',
                
            ]
        );
        $this->add_control(
            'featured_only',
            [
                'label' => __( 'Show featured listings only?', 'townhub-add-ons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => _x( 'Yes', 'On/Off', 'townhub-add-ons' ),
                'label_off' => _x( 'No', 'On/Off', 'townhub-add-ons' ),
                'return_value' => 'yes',
                
            ]
        );
        $this->add_control(
            'posts_per_page',
            [
                'label' => __( 'Posts to show', 'townhub-add-ons' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '6',
                'min' => -1,
                'description' => esc_html__("Number of posts to show (-1 for all).", 'townhub-add-ons'),
                
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


    }

    protected function render( ) {

        // require_once ESB_ABSPATH . 'includes/classes/geoplugin.class/locate.php';

        // $locate = cth_addons_locate('27.79.151.54');
        
        // if( !empty($locate->latitude) && !empty($locate->longitude) ){
        //     var_dump($locate);
        // }

        $settings = $this->get_settings();

        if(!empty($settings['ids'])){
            $ids = explode(",", $settings['ids']);
            $post_args = array(
                'post_type' => 'listing',
                'posts_per_page'=> $settings['posts_per_page'],
                'post__in' => $ids,
                'post_status' => 'publish'
            );
        }elseif(!empty($settings['ids_not'])){
            $ids_not = explode(",", $settings['ids_not']);
            $post_args = array(
                'post_type' => 'listing',
                'posts_per_page'=> $settings['posts_per_page'],
                'post__not_in' => $ids_not,
                'post_status' => 'publish'
            );
        }else{
            $post_args = array(
                'post_type' => 'listing',
                'posts_per_page'=> $settings['posts_per_page'],
                'post_status' => 'publish'
            );
        }





        if(!empty($settings['cat_ids'])) $post_args['tax_query'] =  array(
                                                                        array(
                                                                            'taxonomy' => 'listing_cat',
                                                                            'field'    => 'term_id',
                                                                            'terms'    => $settings['cat_ids'],
                                                                        ),
                                                                    );

        if( $settings['featured_only'] == 'yes'){
            $post_args['meta_query'] =  array(
                                            array(
                                                'key'     => ESB_META_PREFIX .'featured',
                                                'value'   => '1',
                                                'type'      => 'NUMERIC'
                                            ),
                                        );
        }

        if( $settings['use_geolocation'] == 'yes'){
            $post_args['suppress_filters'] = false; // for additional wpdb query
            $post_args['cthqueryid'] = 'auto-locate';
        }

        $gmap_listing = array();
        $posts_query = new \WP_Query($post_args);
        if(!$posts_query->have_posts()) { 
            $post_args['suppress_filters'] = true;
            $post_args['cthqueryid'] = 'normal';
            $posts_query = new \WP_Query($post_args);
        }
        if($posts_query->have_posts()) { 
            // var_dump($posts_query);
            while($posts_query->have_posts()){ 
                $posts_query->the_post();
                $gmap_listing[] = townhub_addons_get_map_data();
            }
        }
        wp_reset_postdata();
        wp_localize_script( 'townhub-addons', '_townhub_add_ons_map', $gmap_listing);
        ?>
        <!--hero map-->
        <div class="hero-map-wrap">
            <div class="hero-map">
                <!-- Map -->
                <div class="map-container fw-map big_map hero-map-map">
                    <?php townhub_addons_get_template_part('template-parts/filter/map'); ?>
                </div>
                <!-- Map end -->
            </div>
            <div class="clearfix"></div>
            <div class="container small-container">
                <div class="hero-map-search-wrap fl-wrap">
                    <?php 
                    if(!empty($settings['content'])): ?>
                    <div class="intro-item fl-wrap">
                        <?php echo $settings['content'];?>
                    </div>
                    <?php 
                    endif;?>
                    <?php if( !empty($settings['ltypes']) ) townhub_addons_get_template_part('template-parts/hero_search_form', '', array( 'ltypes'=> explode(",", $settings['ltypes']) ) ); ?>
                    <?php // if($settings['show_search'] == 'yes') townhub_addons_get_template_part('template-parts/hero_search_form' ); ?>
                    
                    <?php if($settings['show_filter'] == 'yes') townhub_addons_get_template_part('template-parts/hero_cats' , false , array('settings'=>$settings) ); ?>
                    
                    <?php 
                    if(!empty($settings['content_after'])): ?>
                    <div class="intro-item-after fl-wrap">
                        <?php echo $settings['content_after'];?>
                    </div>
                    <?php 
                    endif;?>
                </div>
            </div>
            <?php 
            if(!empty($settings['scroll_url'])): ?>
                <div class="header-sec-link">
                    <a href="<?php echo $settings['scroll_url'];?>" class="custom-scroll-link"><i class="fal fa-angle-double-down"></i></a>
                </div>
            <?php 
            endif;?>
        </div>
        <!--hero map end-->
        <?php
    }

}


