<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

class CTH_Listings_Grid extends Widget_Base {
 
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
        return 'listings_grid';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Listings Search', 'townhub-add-ons' ); 
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
                'label' => __( 'Listings Query', 'townhub-add-ons' ),
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
                // 'default' => 'date',
                // 'separator' => 'before',
                // 'description' => esc_html__("Select how to sort retrieved posts. More at ", 'townhub-add-ons').'<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex</a>.', 
            ]
        );


        $this->add_control(
            'loc_ids',
            [
                'label' => __( 'Locations to get listings', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT2,
                'options' => townhub_addons_get_listing_locations_hierarchy_select2(),
                'multiple' => true,
                'label_block' => true,
                // 'default' => 'date',
                // 'separator' => 'before',
                // 'description' => esc_html__("Select how to sort retrieved posts. More at ", 'townhub-add-ons').'<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex</a>.', 
            ]
        );

        $this->add_control(
            'tag_ids',
            [
                'label' => __( 'Listing Tags', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
                'description' => __("Enter listing tag's ids to get listings from, separated by a comma.", 'townhub-add-ons')
                
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
            'section_layout',
            [
                'label' => __( 'Listings Layout', 'townhub-add-ons' ),
            ]
        );

        // $this->add_control(
        //     'title',
        //     [
        //         'label' => __( 'Title Text', 'townhub-add-ons' ),
        //         'type' => Controls_Manager::TEXT,
        //         'default' => 'Results For : <span>All Listings</span>',
        //         'label_block' => true,
                
        //     ]
        // );

        

        $this->add_control(
            'map_pos',
            [
                'label' => __( 'Map Position', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top' => esc_html__('Top', 'townhub-add-ons'), 
                    'left' => esc_html__('Left', 'townhub-add-ons'), 
                    'right' => esc_html__('Right', 'townhub-add-ons'), 
                    'hide' => esc_html__('Hide', 'townhub-add-ons'), 
                ],
                'default' => 'right',
                'description' => esc_html__("Select Google Map position", 'townhub-add-ons'), 
            ]
        );
        $this->add_control(
            'map_width',
            [
                'label' => __( 'Map Width', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '30' => esc_html__('30%', 'townhub-add-ons'), 
                    '40' => esc_html__('40%', 'townhub-add-ons'), 
                    '50' => esc_html__('50%', 'townhub-add-ons'), 
                    '60' => esc_html__('60%', 'townhub-add-ons'), 
                    '70' => esc_html__('70%', 'townhub-add-ons'), 
                ],
                'default' => '50',
                'description' => esc_html__("Select Google Map width", 'townhub-add-ons'), 
            ]
        );

        $this->add_control(
            'filter_pos',
            [
                'label' => __( 'Filter Position', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top' => esc_html__('Top', 'townhub-add-ons'), 
                    'left' => esc_html__('Left', 'townhub-add-ons'), 
                    'right' => esc_html__('Right', 'townhub-add-ons'), 
                    'left_col' => esc_html__('Column Left', 'townhub-add-ons'), 
                ],
                'default' => 'top',
                // 'condition' => [
                //     'map_pos' => ['top','hide'],
                // ],
                'description' => esc_html__("Select Listing Filter position", 'townhub-add-ons'), 
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
                ],
                'default' => 'three',
                // 'description' => esc_html__("Number of posts to show (-1 for all).", 'townhub-add-ons'),
                
            ]
        );

        

        // $this->add_control(
        //     'read_all_link',
        //     [
        //         'label' => __( 'Read All URL', 'townhub-add-ons' ),
        //         'type' => Controls_Manager::URL,
        //         'default' => [
        //             'url' => 'http://',
        //             'is_external' => '',
        //         ],
        //         'show_external' => true, // Show the 'open in new tab' button.
        //     ]
        // );


        $this->add_control(
            'show_pagination',
            [
                'label' => __( 'Show Pagination', 'townhub-add-ons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => _x( 'Yes', 'On/Off', 'townhub-add-ons' ),
                'label_off' => _x( 'No', 'On/Off', 'townhub-add-ons' ),
                'return_value' => 'yes',
            ]
        );
        // $this->add_control(
        //     'show_load_more',
        //     [
        //         'label' => __( 'Show Load More', 'townhub-add-ons' ),
        //         'type' => Controls_Manager::SWITCHER,
        //         'default' => 'yes',
        //         'label_on' => __( 'Show', 'townhub-add-ons' ),
        //         'label_off' => __( 'Hide', 'townhub-add-ons' ),
        //         'return_value' => 'no',
        //     ]
        // );


        


        $this->end_controls_section();

    }

    protected function render( ) {

        $settings = $this->get_settings();

        // if(is_front_page()) {
        //     $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        // } else {
        //     $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        // }

        if(!empty($settings['ids'])){
            $ids = explode(",", $settings['ids']);
            $post_args = array(
                'post_type' => 'listing',
                'paged' => 1,
                'posts_per_page'=> $settings['posts_per_page'],
                'post__in' => $ids,
                'orderby'=> $settings['order_by'],
                'order'=> $settings['order'],
                'post_status' => 'publish'
            );
        }elseif(!empty($settings['ids_not'])){
            $ids_not = explode(",", $settings['ids_not']);
            $post_args = array(
                'post_type' => 'listing',
                'paged' => 1,
                'posts_per_page'=> $settings['posts_per_page'],
                'post__not_in' => $ids_not,
                'orderby'=> $settings['order_by'],
                'order'=> $settings['order'],

                'post_status' => 'publish'
            );
        }else{
            $post_args = array(
                'post_type' => 'listing',
                'paged' => 1,
                'posts_per_page'=> $settings['posts_per_page'],
                'orderby'=> $settings['order_by'],
                'order'=> $settings['order'],

                'post_status' => 'publish'
            );
        }

        $filter_args = array(
            'posts_per_page'=> $settings['posts_per_page'],
            'orderby'=> $settings['order_by'],
            'order'=> $settings['order'],
        );





        $tax_queries = array();

        
        if(!empty($settings['cat_ids'])){
            $tax_queries[] =    array(
                                    'taxonomy' => 'listing_cat',
                                    'field'    => 'term_id',
                                    'terms'    => $settings['cat_ids'],
                                );
        }
        if(!empty($settings['loc_ids'])){
            $tax_queries[] =    array(
                                    'taxonomy' => 'listing_location',
                                    'field'    => 'term_id',
                                    'terms'    => $settings['loc_ids'],
                                );
        }

        if(!empty($settings['tag_ids'])){
            $tax_queries[] =    array(
                                    'taxonomy' => 'listing_tag',
                                    'field'    => 'term_id',
                                    'terms'    => explode( ",", $settings['tag_ids'] ),
                                );
        }

        if(!empty($tax_queries)){
            // if( count($tax_queries) > 1 ) $tax_queries['relation'] = 'AND';
            $post_args['tax_query'] = $tax_queries;
        } 
        
        $meta_queries = array();

        if( $settings['featured_only'] == 'yes'){
            $meta_queries[] =   array(
                                    'key'     => ESB_META_PREFIX .'featured',
                                    'value'   => '1',
                                    'type'      => 'NUMERIC'
                                );
        }

        if(!empty($meta_queries)) $post_args['meta_query'] = $meta_queries;


        $class_width = ($settings['map_pos'] != 'top' && $settings['map_pos'] != 'hidden') ? $settings['map_width'] : '';
        $css_classes = array(
            'listings-grid-wrap clearfix',
            $settings['columns_grid'].'-cols',
            'map-width-'.$class_width
        );

        $css_class = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $css_classes ) ) );

        ?>
        <!-- carousel -->
        <div class="<?php echo esc_attr( $css_class );?>">
        <?php 
        $filter_wrap_cl = '';
        switch ($settings['map_pos']) {
            case 'left':
                $map_wrap_cl = 'listings-has-map column-map left-pos-map no-fix-scroll-map hid-mob-map fix-map';
                $list_wrap_cl = 'right-list';
                $filter_wrap_cl = 'right-filter';
                break;
            case 'right':
                $map_wrap_cl = 'listings-has-map column-map right-pos-map no-fix-scroll-map hid-mob-map fix-map';
                $list_wrap_cl = 'left-list';
                break;
            case 'top':
                $map_wrap_cl = 'listings-has-map fw-map top-post-map big_map hid-mob-map';
                $list_wrap_cl = 'fh-col-list-wrap left-list';
                break;
            default:
                $map_wrap_cl = 'listings-has-map column-map right-pos-map no-fix-scroll-map hid-mob-map fix-map';
                $list_wrap_cl = 'fh-col-list-wrap left-list fix-mar-map';
                break;
                }
        
        if($settings['filter_pos'] == 'left_col'){
            $map_wrap_cl .= ' map-lcol-filter';
            $list_wrap_cl .= ' list-lcol-filter';
        }
        ?>
        <?php 
        if($settings['map_pos'] != 'hide' && $settings['map_pos'] != 'top'): ?> 

            <div class="map-container <?php echo esc_attr( $map_wrap_cl ); ?>">

                    <div id="map-main" class="main-map-ele main-map-<?php echo esc_attr( townhub_addons_get_option('map_provider') );?>"></div>
                    <ul class="mapnavigation">
                        <li><a href="#" class="prevmap-nav"><i class="fas fa-caret-left"></i><?php esc_html_e( 'Prev', 'townhub-add-ons' ); ?></a></li>
                        <li><a href="#" class="nextmap-nav"><?php esc_html_e( 'Next', 'townhub-add-ons' ); ?><i class="fas fa-caret-right"></i></a></li>
                    </ul>
                    

                <div class="map-close"><i class="fas fa-times"></i></div>
            </div>
            <!-- Map end -->  
        <?php endif; ?>
        <?php if($settings['map_pos'] != 'hide' && $settings['map_pos'] == 'top'): ?>
            <div class="map-container <?php echo esc_attr( $map_wrap_cl ); ?>">

                    <div id="map-main" class="main-map-ele main-map-<?php echo esc_attr( townhub_addons_get_option('map_provider') );?>"></div>
                    <ul class="mapnavigation">
                        <li><a href="#" class="prevmap-nav"><i class="fas fa-caret-left"></i><?php esc_html_e( 'Prev', 'townhub-add-ons' ); ?></a></li>
                        <li><a href="#" class="nextmap-nav"><?php esc_html_e( 'Next', 'townhub-add-ons' ); ?><i class="fas fa-caret-right"></i></a></li>
                    </ul>
                    

                <div class="map-close"><i class="fas fa-times"></i></div>
            </div>
            <!-- Map end -->
            <div class="breadcrumbs-fs fl-wrap">
                <div class="container">
                     <?php townhub_breadcrumbs(); ?>  
                </div>
            </div>
        <?php endif; ?>
        <?php if($settings['filter_pos'] == 'left_col'): ?>
        <div class="col-filter-wrap col-filter <?php echo esc_attr( $filter_wrap_cl ); ?>">
            <div class="container">
                <div class="mobile-list-controls fl-wrap">
                    <div class="container">
                        <div class="mlc show-hidden-column-map schm"><i class="fal fa-map-marked-alt"></i><?php esc_html_e( ' Show Map', 'townhub-add-ons' ); ?></div>
                        <div class="mlc show-list-wrap-search"><i class="fal fa-filter"></i><?php esc_html_e( ' Filter', 'townhub-add-ons' ); ?></div>
                    </div>
                </div>
                <div class="fl-wrap listing-search-sidebar">
                    <?php townhub_addons_get_template_part('templates/filter_form', '', $filter_args); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!--col-list-wrap -->   
            <div class="col-list-wrap col-list-wrap-main <?php echo esc_attr( $list_wrap_cl ); ?> <?php if($settings['map_pos'] == 'top') echo 'col-list-top'?>">
                <?php if($settings['filter_pos'] == 'top'): ?>
                    
                    <!-- list-main-wrap-header-->
                    <div class="list-main-wrap-header fl-wrap fixed-listing-header">
                        <div class="container">
                            <!-- list-main-wrap-title-->
                            <div class="list-main-wrap-title">
                                <h2 id="lsearch-results-title"><?php printf( esc_html__( 'Results for: %s', 'townhub-add-ons' ), '<span>' . __( 'All Listing',  'townhub-add-ons' ) . '</span>' ); ?></h2>

                            </div>
                            <!-- list-main-wrap-title end-->
                            <!-- list-main-wrap-opt-->
                            <div class="list-main-wrap-opt flex-items-center">
                                <?php if( townhub_addons_get_option('filter_hide_sortby') != 'yes' ) townhub_addons_get_template_part('template-parts/filter/sortby'); ?>
                                <?php townhub_addons_get_template_part('template-parts/filter/grid-list'); ?>
                            </div>
                            <!-- list-main-wrap-opt end-->                    
                        </div>
                        <a class="custom-scroll-link back-to-filters clbtg" href="#lisfw"><i class="fal fa-search"></i></a>
                    </div>
                    <!-- list-main-wrap-header end-->  
                    <div class="clearfix"></div>
                    <div class="container dis-flex mob-search-nav-wrap">
                        <div class="mob-nav-content-btn mncb_half color2-bg shsb_btn shsb_btn_act show-list-wrap-search fl-wrap"><i class="fal fa-filter"></i>  Filters</div>
                        <div class="mob-nav-content-btn mncb_half color2-bg schm  fl-wrap"><i class="fal fa-map-marker-alt"></i>  View on map</div>
                    </div>
                    <div class="clearfix"></div>
            
                    <?php townhub_addons_get_template_part('templates/filter_form'); ?>


                <?php elseif($settings['filter_pos'] == 'left_col'): ?>
                <div class="mobile-list-controls fl-wrap">
                    <div class="container">
                        <div class="mlc show-hidden-column-map schm"><i class="fal fa-map-marked-alt"></i><?php esc_html_e( ' Show Map', 'townhub-add-ons' ); ?></div>
                        <div class="mlc show-list-wrap-search"><i class="fal fa-filter"></i><?php esc_html_e( ' Filter', 'townhub-add-ons' ); ?></div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($settings['filter_pos'] == 'left_col'|| $settings['filter_pos'] == 'left' && $settings['map_pos'] == 'top') {?>
                   <div class="mobile-list-controls fl-wrap">
                        <div class="container">
                            <div class="mlc show-hidden-column-map schm"><i class="fal fa-map-marked-alt"></i><?php esc_html_e( ' Show Map', 'townhub-add-ons' ); ?></div>
                            <div class="mlc show-list-wrap-search"><i class="fal fa-filter"></i><?php esc_html_e( ' Filter', 'townhub-add-ons' ); ?></div>
                        </div>
                    </div>
                <?php } ?>
                <!-- list-main-wrap-->
                <div class="<?php if($settings['map_pos'] != 'top' && $settings['filter_pos'] != 'left') echo 'list-main-wrap'?> fl-wrap card-listing">
                    <a class="custom-scroll-link back-to-filters" href="#lisfw"><i class="fas fa-angle-up"></i>
                        <span><?php esc_html_e( 'Back to Filters', 'townhub-add-ons' ); ?></span></a> 
                    <div class="container"> 
                        <div class="row">
                            <?php 
                            if($settings['filter_pos'] == 'left'):?>
                            <div class="mobile-list-controls fl-wrap">
                                <div class="container">
                                    <div class="mlc show-hidden-column-map schm"><i class="fal fa-map-marked-alt"></i> <?php esc_html_e( ' Show Map', 'townhub-add-ons' ); ?></div> 
                                    <div class="mlc show-list-wrap-search"><i class="fal fa-filter"></i><?php esc_html_e( ' Filter', 'townhub-add-ons' ); ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="fl-wrap listing-search-sidebar ">
                                    <?php townhub_addons_get_template_part('templates/filter_form', '', $filter_args ); ?>
                                </div>
                            </div>
                            <?php endif;?>
                            <?php 
                            if($settings['map_pos'] == 'top' && $settings['filter_pos'] == 'left'):?>
                                  <div class="mobile-list-controls fl-wrap" style="margin-bottom: 50px;margin-top:0;">
                                    <div class="container">
                                        <div class="mlc show-hidden-column-map schm"><i class="fal fa-map-marked-alt"></i> <?php esc_html_e( ' Show Map', 'townhub-add-ons' ); ?></div>
                                        <div class="mlc show-list-wrap-search"><i class="fal fa-filter"></i><?php esc_html_e( ' Filter', 'townhub-add-ons' ); ?></div>
                                    </div>
                                </div>
                            <?php 
                               endif; 
                                ?>
                            <?php 
                            if($settings['filter_pos'] == 'left'||$settings['filter_pos'] == 'right'):?>
                            <div class="col-md-8">
                            <?php else : ?>
                            <div class="col-md-12">
                            <?php endif;?>

                                <?php townhub_addons_get_template_part('templates/loop', 'custom', array('post_args'=>$post_args) ); ?>
                                 
                            </div>
                            <!-- end col-md-8 -->
                            <?php 
                            if($settings['filter_pos'] == 'right'):?>
                            <div class="col-md-4">
                                <?php if ($settings['map_pos'] == 'hide'){ ?>
                                    <div class="mobile-list-controls fl-wrap" style="margin-bottom: 50px;margin-top:0;">
                                        <div class="container">
                                            <div class="mlc show-list-wrap-search fl-wrap"><i class="fal fa-filter"></i> Filter</div>
                                        </div>
                                    </div>
                                <?php }else{ ?>
                                    <div class="mobile-list-controls fl-wrap">
                                        <div class="container">
                                            <div class="mlc show-hidden-column-map schm"><i class="fal fa-map-marked-alt"></i><?php esc_html_e( ' Show Map', 'townhub-add-ons' ); ?></div> 
                                            <div class="mlc show-list-wrap-search"><i class="fal fa-filter"></i><?php esc_html_e( ' Filter', 'townhub-add-ons' ); ?></div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="fl-wrap listing-search-sidebar">
                                    <?php townhub_addons_get_template_part('templates/filter_form', '', $filter_args ); ?>
                                </div>
                            </div>
                            <?php endif;?>

                        </div> 
                        <!-- end row -->
                    </div>
                    <!-- end container -->
                </div>
                <!-- list-main-wrap end-->
            </div>
            <!--col-list-wrap -->  
            <!-- <div class="limit-box fl-wrap"></div> -->
        </div>
        <!--  listings-grid-wrap end-->

        <div class="limit-box fl-wrap"></div>
        
        <?php //townhub_addons_get_template_part('templates/tmpls'); ?>

        <?php wp_reset_postdata();?>
        <?php

    }

    // protected function _content_template() {}

   
    

}
