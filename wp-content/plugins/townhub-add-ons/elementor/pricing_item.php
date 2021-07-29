<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Pricing_Item extends Widget_Base {

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
        return 'pricing_item';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Pricing Item', 'townhub-add-ons' );
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
            'title',
            [
                'label' => __( 'Title', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Extended',
                'label_block' => true,
                
            ]
        );

        $this->add_control(
            'sub_title',
            [
                'label' => __( 'SubTitle', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Developer',
                'label_block' => true,
                
            ]
        );

        $this->add_control(
            'price',
            [
                'label' => __( 'Price', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => '99',
                'label_block' => true,
                
            ]
        );

        $this->add_control(
            'currency',
            [
                'label' => __( 'Currency', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => '$',
                
            ]
        );

        $this->add_control(
            'period',
            [
                'label' => __( 'Currency', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Per month',
                
            ]
        );

        $this->add_control(
            'features',
            [
                'label' => __( 'Features', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXTAREA, //WYSIWYG,
                'default' => '<ul>
    <li>Ten Listings</li>
    <li>Lifetime Availability</li>
    <li>Featured In Search Results</li>
    <li>24/7 Support</li>
</ul>
<a href="#" class="price-link color-bg">Choose Extended</a>',
                
                'show_label' => false,
            ]
        );

        $this->add_control(
            'btn_text',
            [
                'label'         => __( 'Button Text', 'townhub-add-ons' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => 'Choose Extended',
                'label_block'   => false,
                
            ]
        );
        $this->add_control(
            'btn_link',
            [
                'label'         => __( 'Button URL', 'townhub-add-ons' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => '#',
                'label_block'   => true,
                
            ]
        );


        $this->add_control(
            'is_featured',
            [
                'label' => __( 'Featured Price', 'townhub-add-ons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => _x( 'Yes', 'On/Off', 'townhub-add-ons' ),
                'label_off' => _x( 'No', 'On/Off', 'townhub-add-ons' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            're_icon',
            [
                'label' => __( 'Recommended Icon', 'townhub-add-ons' ),
                'type' => Controls_Manager::ICON,
                'default' => 'fa fa-check',
                'label_block' => true,
            ]
        );
        $this->add_control(
            're_text',
            [
                'label' => __( 'Recommended Text', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Recommended',
                'label_block' => true,
                
            ]
        );

        $this->add_control(
            'plcolor',
            [
                'label' => __( 'Color', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'color' => esc_html__('Theme color', 'townhub-add-ons'),
                    'purp'  => esc_html__('Purple', 'townhub-add-ons'),
                    'green' => esc_html__('Green', 'townhub-add-ons'),
                    'blue'  => esc_html__('Blue', 'townhub-add-ons'),
                ],
                'default' => 'color',
                'separator' => 'before',
                
            ]
        );








        

        

        

        $this->end_controls_section();

        

    }

    protected function render( ) {
        $settings = $this->get_settings();


        $color = get_post_meta( get_the_ID(), ESB_META_PREFIX.'color', true );
        $headbg = $settings['plcolor'] .'-gradient-bg';
        $btncls = $settings['plcolor'] .'-bg';
        

        


        ?>
        <div class="price-item<?php if($settings['is_featured'] == 'yes') echo ' best-price';?>">
            <div class="price-head <?php echo esc_attr($headbg); ?>">
                <?php if($settings['title'] !='') echo '<h3>'.$settings['title'].'</h3>'; ?>
                <?php if($settings['sub_title'] !='') echo '<h4 class="pricing-item-subtitle">'.$settings['sub_title'].'</h4>'; ?>
                <?php 
                $is_free = true;
                if( !empty($settings['price']) ):
                    $is_free = false;
                ?>
                    <div class="price-num fl-wrap">
                        <div class="price-num-item">
                            <span class="mouth-cont">
                                <span class="curen"><?php echo $settings['currency']; ?></span>
                                <span><?php echo $settings['price']; ?></span>
                            </span>
                            
                        </div>
                        <div class="clearfix"></div>
                        <div class="price-num-desc">
                            <span class="month-period-text"><?php echo $settings['period']; ?></span>
                            
                        </div>
                    </div>
                <?php else: ?>
                    <div class="price-num fl-wrap"> 
                        <div class="price-num-item"><?php _e( 'Free', 'townhub-add-ons' ); ?></div>
                        <div class="clearfix"></div>
                        <div class="price-num-desc">
                            <span class="month-period-text"><?php echo $settings['period']; ?></span>
                            
                        </div>
                    </div>
                <?php endif; ?>

                <?php 
                if($settings['is_featured'] == 'yes'){ ?>
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
                    <?php 
                    if($settings['features'] !='') echo $settings['features'];
                    if( $settings['btn_text'] != '' && $settings['btn_link'] != '' ):
                    ?>
                    <div><a class="price-link <?php echo esc_attr($btncls); ?>" href="<?php echo esc_url( $settings['btn_link'] ); ?>"><?php echo esc_html( $settings['btn_text'] ); ?></a></div>
                    <?php
                    endif;
                    if($settings['re_icon'] !='' || $settings['re_text'] != ''){ ?>
                        <div class="recomm-price">
                            <?php if($settings['re_icon'] !='') echo '<i class="'.$settings['re_icon'].'"></i>'; ?>
                            <?php if($settings['re_text'] !='') echo '<span class="recomm-text">'.$settings['re_text'].'</span>'; ?>
                        </div>
                    <?php
                    } 

                     ?>
                </div>
            </div>
        </div>
        <?php
    }

    protected function _content_template() {}

   
    

}



