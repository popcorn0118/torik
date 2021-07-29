<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Listing_Locations extends Widget_Base {

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
        return 'listing_locations'; 
    } 

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Listing Locations', 'townhub-add-ons' );
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
                'label' => __( 'Locations Query', 'townhub-add-ons' ),
            ]
        );

        $this->add_control(
            'cat_ids',
            [
                'label' => __( 'Select Locations to include (Leave empty for ALL)', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT2,
                'options' => townhub_addons_get_listing_locations_select2(),
                'multiple' => true,
                'label_block' => true,
                // 'default' => 'date',
                // 'separator' => 'before',
                // 'description' => esc_html__("Select how to sort retrieved posts. More at ", 'townhub-add-ons').'<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex</a>.', 
            ]
        );

        $this->add_control(
            'cat_ids_not',
            [
                'label' => __( 'Or Locations to exclude (Leave empty for ALL)', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT2,
                'options' => townhub_addons_get_listing_locations_select2(),
                'multiple' => true,
                'label_block' => true,
                // 'default' => 'date',
                // 'separator' => 'before',
                // 'description' => esc_html__("Select how to sort retrieved posts. More at ", 'townhub-add-ons').'<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex</a>.', 
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __( 'Order by', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'name' => esc_html__('Name', 'townhub-add-ons'), 
                    'slug' => esc_html__('Slug', 'townhub-add-ons'), 
                    'term_group' => esc_html__('Term Group', 'townhub-add-ons'), 
                    'term_id' => esc_html__('Term ID', 'townhub-add-ons'), 
                    'description' => esc_html__('Description', 'townhub-add-ons'),
                    'parent' => esc_html__('Parent', 'townhub-add-ons'),
                    'count' => esc_html__('Term Count', 'townhub-add-ons'),
                    'include' => esc_html__('For Include above', 'townhub-add-ons'),
                ],
                'default' => 'name',
                'separator' => 'before',
                'description' => esc_html__("Select how to sort retrieved categories. More at ", 'townhub-add-ons').'<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex</a>.', 
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
            'hide_empty',
            [
                'label' => __( 'Hide Empty', 'townhub-add-ons' ),
                'description' => esc_html__('Whether to hide categories not assigned to any listings', 'townhub-add-ons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '1',
                'label_on' => __( 'Yes', 'townhub-add-ons' ),
                'label_off' => __( 'No', 'townhub-add-ons' ),
                'return_value' => '1',
            ]
        );


        $this->add_control(
            'number',
            [
                'label' => __( 'Number of Locations to show', 'townhub-add-ons' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '6',
                'description' => esc_html__("Number of Locations to show (0 for all).", 'townhub-add-ons'),
                'min'     => 0,
                'step'     => 1,
                
            ]
        );

        

        $this->end_controls_section();

        $this->start_controls_section(
            'section_layout',
            [
                'label' => __( 'Locations Layout', 'townhub-add-ons' ),
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
            'items_width',
            [
                'label' => __( 'Locations Items Width', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,

                'label_block' => true,
                // 'default' => 'date',
                // 'separator' => 'before',
                'description' => esc_html__('Defined location width. Available values are x1(default),x2(x2 width),x3(x3 width), and separated by comma. Ex: x1,x1,x2,x1,x1,x1', 'townhub-add-ons')
            ]
        );

        $this->add_control(
            'space',
            [
                'label' => __( 'Space', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
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
            'view_all_link',
            [
                'label' => __( 'View All Link', 'townhub-add-ons' ),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '',
                    'is_external' => '',
                ],
                'description' => __( 'Listing archive page: ', 'townhub-add-ons' ). get_post_type_archive_link( 'listing' ),
                'show_external' => true, // Show the 'open in new tab' button.
            ]
        );

        $this->add_control(
            'view_all_text',
            [
                'label' => __( 'View all Text', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,

                'label_block' => true,
                'default' => 'View All Cities',
                // 'separator' => 'before',
                'description' =>''
            ]
        );

       
        $this->add_control(
            'count_child',
            [
                'label' => __( 'Count listings from child locations', 'townhub-add-ons' ),
                'description' => '',
                'type' => Controls_Manager::SWITCHER,
                'default' => '0',
                'label_on' => _x( 'Yes', 'On/Off', 'townhub-add-ons' ),
                'label_off' => _x( 'No', 'On/Off', 'townhub-add-ons' ),
                'return_value' => '1',
            ]
        );


        


        


        $this->end_controls_section();

    }

    protected function render( ) {
        $settings = $this->get_settings();
        $term_args = array(
            'taxonomy' => 'listing_location',
            'hide_empty' => (bool)$settings['hide_empty'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'number' => $settings['number'],
        );

        if(!empty($settings['cat_ids'])) $term_args['include']  = $settings['cat_ids'];
        elseif(!empty($settings['cat_ids_not'])) $term_args['exclude']  = $settings['cat_ids_not'];
        
        $listing_terms = get_terms( $term_args );

        

        if ( ! empty( $listing_terms ) && ! is_wp_error( $listing_terms ) ){
            
        

            $css_classes = array(
                'cthiso-items',
                'cthiso-'.$settings['columns_grid'] .'-cols tablet-three',
                'cthiso-'.$settings['space'] .'-pad'
            );
            $css_class = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $css_classes ) ) );

            ?>
            <div class="cthiso-isotope-wrapper">
                <div class="<?php echo esc_attr( $css_class );?>">
                    <div class="cthiso-sizer"></div>
                    <?php 
                    $items_width = explode(',',$settings['items_width']);
                    // $items_width = array_filter($items_width);
                    $key = 0;

                    $dfthumb = '';
                    $default_thumbnail = townhub_addons_get_option('default_thumbnail');
                    if( $default_thumbnail && !empty($default_thumbnail['id']) ){
                        $dfthumb = $default_thumbnail['id'];
                    }
                
                    foreach ($listing_terms as $term) { 

                        $lcount = $term->count;
                        // https://wordpress.stackexchange.com/questions/207923/count-posts-in-category-including-child-categories
                        if( (bool)$settings['count_child'] ){
                            $termPosts = new \WP_Query( array(
                                'post_type'         => 'listing',
                                'post_status'       => 'publish',
                                'posts_per_page'    => 1,
                                'fields'            => 'ids',
                                'tax_query'         => array(
                                    array(
                                        'taxonomy' => 'listing_location',
                                        'terms'    => array( $term->term_id ),
                                    )
                                ),
                                    
                            ) );

                            $lcount = $termPosts->found_posts;

                            wp_reset_postdata();
                        }
                        
                        $imgid = '';
                        $lat = get_post_meta( $term->term_id ,ESB_META_PREFIX.'latitude', true );
                        $lng = get_post_meta( $term->term_id, ESB_META_PREFIX.'longitude', true );
                        $term_meta = get_term_meta( $term->term_id, ESB_META_PREFIX.'term_meta', true );
                        if(isset($term_meta['featured_img']) && !empty($term_meta['featured_img'])){
                           $imgid = $term_meta['featured_img']['id'];
                        }
                        if( empty($imgid) ) $imgid = $dfthumb;
                        $tnsize = 'townhub-lcat-one';

                        $item_cls = 'cthiso-item';
                        if(isset($items_width[$key])){
                            switch ($items_width[$key]) {
                                case 'x2':
                                    $item_cls .= ' cthiso-item-second';
                                    $tnsize = 'townhub-lcat-two';
                                    break;
                                case 'x3':
                                    $item_cls .= ' cthiso-item-three';
                                    $tnsize = 'townhub-lcat-three';
                                    break;
                            }
                        }
                        ?>
                        <!-- cthiso-item-->
                        <div id="listing_location-<?php echo esc_attr( $term->term_id );?>" class="<?php echo esc_attr( $item_cls ); ?>">
                            <div class="grid-tax-holder">
                                <div class="grid-tax-inner">
                                    <div class="bg"  data-bg="<?php echo esc_url( townhub_addons_get_attachment_thumb_link( $imgid, $tnsize ) ); ?>"></div>
                                    <a href="<?php echo townhub_addons_get_term_link( $term->term_id, 'listing_location' ); ?>" class="d-gr-sec lcat-card-link"></a>
                                    <div class="listing-counter color2-bg"><?php echo sprintf( _n( '<span>%s </span> Location', '<span>%s </span> Locations', $lcount, 'townhub-add-ons' ), $lcount ); ?></div>
                                    <div class="listing-item-grid_title">
                                        <h3><a href="<?php echo townhub_addons_get_term_link( $term->term_id, 'listing_location' ); ?>"><?php echo esc_html($term->name); ?></a></h3>
                                        <?php echo term_description( $term->term_id, 'listing_location' ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- cthiso-item end-->
                    <?php
                        $key++;
                    }
                    // end foreach

                    ?>
                </div>
            </div>
            <?php
                $url = $settings['view_all_link']['url'];
                $target = $settings['view_all_link']['is_external'] ? 'target="_blank"' : '';
                if($url != '') echo '<div class="view-all-taxs"><a href="' . $url . '" ' . $target .' class="btn dec_btn   color2-bg">'. $settings['view_all_text'].'<i class="fal fa-arrow-alt-right"></i></a></div>';
            ?>
            
        <?php
        }
        // end if  ! empty( $listing_terms ) && ! is_wp_error( $listing_terms )


        

    }

    protected function _content_template() {}

   
    

}

