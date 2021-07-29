<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 
class CTH_Woo_Mem_Plans extends Widget_Base { 

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
        return 'woo_mem_plans';
    }

    // public function get_id() {
    //      return 'header-search';
    // }

    public function get_title() {
        return __( 'Membership Plans - Woo', 'townhub-add-ons' );
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
                'label' => __( 'Plans Query', 'townhub-add-ons' ),
            ]
        );

        $this->add_control(
            'ids',
            [
                'label' => __( 'Enter Plan IDs', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
                'description' => __("Enter Plan ids to show, separated by a comma. Leave empty to show all.", 'townhub-add-ons')
                
            ]
        );
        $this->add_control(
            'ids_not',
            [
                'label' => __( 'Or Plan IDs to Exclude', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
                'description' => __("Enter plan ids to exclude, separated by a comma (,). Use if the field above is empty.", 'townhub-add-ons')
                
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
                'default' => 'ASC',
                'separator' => 'before',
                'description' => esc_html__("Select Ascending or Descending order. More at", 'townhub-add-ons').'<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex</a>.', 
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __( 'Plans to show', 'townhub-add-ons' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '3',
                'description' => esc_html__("Number of plans to show (-1 for all).", 'townhub-add-ons'),
                
            ]
        );

        

        $this->end_controls_section();

        $this->start_controls_section(
            'section_layout',
            [
                'label' => __( 'Plans Layout', 'townhub-add-ons' ),
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
                
                
            ]
        );


        $this->add_control(
            'best_price_item',
            [
                'label' => __( 'Best Price Item', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => '2',
                'description' => esc_html__("Best price item number. 1 for first item. Leave empty for none.", 'townhub-add-ons'),
            ]
        );

        $this->add_control(
            'best_price_icon',
            [
                'label' => __( 'Best Price Icon', 'townhub-add-ons' ),
                'type' => 'cthicon',
                'default' => 'fal fa-check',
                'label_block' => true,
            ]
        );
        $this->add_control(
            'best_price_text',
            [
                'label' => __( 'Best Price Recommended Text', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Recommended',
                'label_block' => true,
                
            ]
        );
        // $this->add_control(
        //     'show_pricing_switcher',
        //     [
        //         'label' => __( 'Show Button Switcher Pricing', 'townhub-add-ons' ),
        //         'type' => Controls_Manager::SWITCHER,
        //         'default' => 'no',
        //         'label_on' => __( 'Show', 'townhub-add-ons' ),
        //         'label_off' => __( 'Hide', 'townhub-add-ons' ),
        //         'return_value' => 'yes',
        //     ]
        // );



        


        $this->end_controls_section();

    }

    protected function render( ) {

        $settings = $this->get_settings();

        
        if(!empty($settings['ids'])){
            $ids = explode(",", $settings['ids']);
            $post_args = array(
                'post_type' => 'lplan',
                
                'posts_per_page'=> $settings['posts_per_page'],
                'post__in' => $ids,
                'orderby'=> $settings['order_by'],
                'order'=> $settings['order'],

                'post_status' => 'publish'
            );
        }elseif(!empty($settings['ids_not'])){
            $ids_not = explode(",", $settings['ids_not']);
            $post_args = array(
                'post_type' => 'lplan',
                
                'posts_per_page'=> $settings['posts_per_page'],
                'post__not_in' => $ids_not,
                'orderby'=> $settings['order_by'],
                'order'=> $settings['order'],

                'post_status' => 'publish'
            );
        }else{
            $post_args = array(
                'post_type' => 'lplan',
                
                'posts_per_page'=> $settings['posts_per_page'],
                'orderby'=> $settings['order_by'],
                'order'=> $settings['order'],

                'post_status' => 'publish'
            );
        }



        $currency_attrs = townhub_addons_get_currency_attrs();
        $css_classes = array(
            'membership-plans-wrap clearfix fix-plans-style',
            'curr-pos-'.$currency_attrs['sb_pos'],
            $settings['columns_grid'].'-cols',
        );

        $css_class = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $css_classes ) ) );

        ?>
        <div class="<?php echo esc_attr($css_class );?>">
        <?php 
            // $checkout_page_id = esb_addons_get_wpml_option('checkout_page');

            $posts_query = new \WP_Query($post_args);
            if($posts_query->have_posts()) : ?>
                <div class="pricing-wrap fl-wrap">
                <?php 
                $idx = 0;
                $best_price_item = $settings['best_price_item'];
                while($posts_query->have_posts()) : $posts_query->the_post();
                    $_price = get_post_meta( get_the_ID(), '_price', true );
                    $period = get_post_meta( get_the_ID(), ESB_META_PREFIX.'period', true );
                    $interval = get_post_meta( get_the_ID(), ESB_META_PREFIX.'interval', true );

                    $period_text = townhub_add_ons_get_plan_period_text($interval, $period);
                    
                    $cls = 'price-item';
                    $color = get_post_meta( get_the_ID(), ESB_META_PREFIX.'color', true );
                    $headbg = $color .'-gradient-bg';
                    $btncls = $color .'-bg';
                    if($best_price_item == $idx){
                        // $headbg = 'gradient-bg';
                        // $btncls = 'color-bg';
                        $cls .= ' best-price';
                    }

                 ?>
                    <!-- plan-item -->
                    <div class="<?php echo esc_attr($cls); ?>">
                        <div class="price-head <?php echo esc_attr($headbg); ?>">
                            <?php 
                            the_title( '<h3 class="pricing-item-title">', '</h3>', true ); 
                            if( $subtitle = get_post_meta( get_the_ID(), ESB_META_PREFIX.'subtitle', true ) ) 
                                echo '<h4 class="pricing-item-subtitle">'.$subtitle.'</h4>'; 
                            ?>
                            <?php 
                            $is_free = true;
                            if( (float)$_price > 0 ):
                                $is_free = false;
                            ?>
                                <div class="price-num fl-wrap">
                                    <div class="price-num-item">
                                        <span class="mouth-cont">
                                            <span class="curen"><?php echo $currency_attrs['symbol']; ?></span>
                                            <span><?php echo townhub_addons_get_price_formated($_price, false); ?></span>
                                        </span>
                                        
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="price-num-desc">
                                        <span class="month-period-text"><?php echo $period_text; ?></span>
                                        
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="price-num fl-wrap"> 
                                    <div class="price-num-item"><?php _e( 'Free', 'townhub-add-ons' ); ?></div>
                                    <div class="clearfix"></div>
                                    <div class="price-num-desc">
                                        <span class="month-period-text"><?php echo $period_text; ?></span>
                                        
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php 
                            if($best_price_item == $idx){ ?>
                                <div class="circle-wrap" style="right:20%;top:70px;"  >
                                    <div class="circle_bg-bal circle_bg-bal_versmall"></div>
                                </div>
                                <div class="circle-wrap" style="right:70%;top:40px;"  >
                                    <div class="circle_bg-bal circle_bg-bal_versmall" data-scrollax="properties: { translateY: '-150px' }"></div>
                                </div>
                                <div class="footer-wave">
                                    <svg viewbox="0 0 100 25">
                                        <path fill="#fff" d="M0 60 V2 Q30 17 55 12 T100 11 V30z" />
                                    </svg>
                                </div>
                                <div class="footer-wave footer-wave2">
                                    <svg viewbox="0 0 100 25">
                                        <path fill="#fff" d="M0 90 V16 Q30 7 45 12 T100 5 V30z" />
                                    </svg>
                                </div>
                            <?php }else{ ?>
                                <div class="circle-wrap" style="right:20%;top:50px;">
                                    <div class="circle_bg-bal circle_bg-bal_versmall" data-scrollax="properties: { translateY: '50px' }"></div>
                                </div>
                                <div class="circle-wrap" style="right:75%;top:90px;">
                                    <div class="circle_bg-bal circle_bg-bal_versmall"></div>
                                </div>
                                <div class="footer-wave">
                                    <svg viewbox="0 0 100 25">
                                        <path fill="#fff" d="M0 30 V12 Q30 17 55 12 T100 11 V30z" />
                                    </svg>
                                </div>
                                <div class="footer-wave footer-wave2">
                                    <svg viewbox="0 0 100 25">
                                        <path fill="#fff" d="M0 90 V12 Q30 7 45 12 T100 11 V30z" />
                                    </svg>
                                </div>
                            <?php } ?>           
                            
                        </div>
                        <div class="price-content fl-wrap">
                        
                            <div class="price-desc fl-wrap">
                                <?php the_content(); ?>

                                <?php if(is_user_logged_in()) : ?>
                                <a href="<?php echo townhub_addons_get_add_to_cart_url( get_the_ID() );?>" class="price-link <?php echo esc_attr($btncls); ?>"><?php echo sprintf(__( 'Choose %s', 'townhub-add-ons' ), get_the_title()); ?></a>
                                <?php else : 
                                    $logBtnAttrs = townhub_addons_get_login_button_attrs( 'orderplan', 'current' );
                                ?>
                                <a href="<?php echo esc_url( $logBtnAttrs['url'] );?>" class="price-link <?php echo esc_attr( $logBtnAttrs['class'] );?> <?php echo esc_attr($btncls); ?>" data-message="<?php esc_attr_e( 'You must be logged in to order a membership plan.', 'townhub-add-ons' ); ?>"><?php echo sprintf(__( 'Choose %s', 'townhub-add-ons' ), get_the_title()); ?></a>
                                <?php endif; ?>

                                
                                <?php if($best_price_item == $idx){ ?>
                                <div class="recomm-price">
                                    <?php if($settings['best_price_icon'] !='') echo '<i class="'.$settings['best_price_icon'].'"></i>'; ?>
                                    <?php if($settings['best_price_text'] !='') echo '<span class="recomm-text">'.$settings['best_price_text'].'</span>'; ?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!-- plan-item end  -->


                <?php 
                $idx++;
                endwhile; ?>
            </div><!-- end .pricing-wrap -->
            <?php endif; ?> 

        </div>
        <?php wp_reset_postdata();?>
        <?php

    }

    protected function _content_template() {}

   
    

}



