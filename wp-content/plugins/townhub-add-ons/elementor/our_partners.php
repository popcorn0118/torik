<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Our_Partners extends Widget_Base {

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
        return 'our_partners';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Our Partners', 'townhub-add-ons' );
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
            'section_images',
            [
                'label' => __( 'Content', 'townhub-add-ons' ),
            ]
        );

        $this->add_control(
            'images',
            [
                'label' => __( 'Partners Images', 'townhub-add-ons' ),
                'type' => Controls_Manager::GALLERY,
                'default' => array(
                    array('id' => 6405,'url'=>''),
                    array('id' => 6406,'url'=>''),
                    array('id' => 6407,'url'=>''),
                    array('id' => 6408,'url'=>''),
                    array('id' => 6409,'url'=>''),
                    array('id' => 6410,'url'=>''),
                )
            ]
        );

        $this->add_control(
            'links',
            [
                'label' => __( 'Partner Links', 'townhub-add-ons' ),
                'type' => Controls_Manager::TEXTAREA, // WYSIWYG,
                'default' => 'https://jquery.com/|https://envato.com/|https://wordpress.org/|https://jquery.com/|https://envato.com/|https://wordpress.org/',
                // 'show_label' => false,
                'description' => __( 'Enter links for each partner (Note: divide links with linebreaks (Enter) or | and no spaces).', 'townhub-add-ons' )
            ]
        );

        $this->add_control(
            'is_external',
            [
                'label' => __( 'Is External Links', 'townhub-add-ons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => _x( 'Yes', 'On/Off', 'townhub-add-ons' ),
                'label_off' => _x( 'No', 'On/Off', 'townhub-add-ons' ),
                'return_value' => 'yes',
            ]
        );

        

        $this->end_controls_section();

        // $this->start_controls_section(
        //     'section_layout',
        //     [
        //         'label' => __( 'Posts Layout', 'townhub-add-ons' ),
        //     ]
        // );

       
        // $this->add_control(
        //     'excerpt_length',
        //     [
        //         'label' => __( 'Post Description Length', 'townhub-add-ons' ),
        //         'type' => Controls_Manager::NUMBER,
        //         'default' => '250',
        //         'min'     => 0,
        //         'max'     => 500,
        //         'step'    => 10,
                
                
        //     ]
        // );

        // $this->add_control(
        //     'show_author',
        //     [
        //         'label' => __( 'Show Author', 'townhub-add-ons' ),
        //         'type' => Controls_Manager::SWITCHER,
        //         'default' => 'yes',
        //         'label_on' => __( 'Show', 'townhub-add-ons' ),
        //         'label_off' => __( 'Hide', 'townhub-add-ons' ),
        //         'return_value' => 'yes',
        //     ]
        // );

        // $this->add_control(
        //     'show_date',
        //     [
        //         'label' => __( 'Show Date', 'townhub-add-ons' ),
        //         'type' => Controls_Manager::SWITCHER,
        //         'default' => 'yes',
        //         'label_on' => __( 'Show', 'townhub-add-ons' ),
        //         'label_off' => __( 'Hide', 'townhub-add-ons' ),
        //         'return_value' => 'yes',
        //     ]
        // );

        // $this->add_control(
        //     'show_views',
        //     [
        //         'label' => __( 'Show Views', 'townhub-add-ons' ),
        //         'type' => Controls_Manager::SWITCHER,
        //         'default' => 'yes',
        //         'label_on' => __( 'Show', 'townhub-add-ons' ),
        //         'label_off' => __( 'Hide', 'townhub-add-ons' ),
        //         'return_value' => 'yes',
        //     ]
        // );

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


        // $this->add_control(
        //     'show_pagination',
        //     [
        //         'label' => __( 'Show Pagination', 'townhub-add-ons' ),
        //         'type' => Controls_Manager::SWITCHER,
        //         'default' => 'no',
        //         'label_on' => __( 'Show', 'townhub-add-ons' ),
        //         'label_off' => __( 'Hide', 'townhub-add-ons' ),
        //         'return_value' => 'yes',
        //     ]
        // );


        


        // $this->end_controls_section();

    }

    protected function render( ) {

        $settings = $this->get_settings();

        
        $css_classes = array(
            'clients-carousel-wrap fl-wrap',
            // 'posts-grid-',//.$settings['columns_grid']
        );

        $css_class = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $css_classes ) ) );

        // var_dump($settings['images']);
        if(is_array($settings['images']) && !empty($settings['images'])):

            $seppos = strpos(strip_tags($settings['links']), "|");
            if($seppos !== false){
                $partnerslinks = explode("|", strip_tags($settings['links']));
            }else{
                $partnerslinks = preg_split( '/\r\n|\r|\n/', strip_tags($settings['links']) );//explode("\n", $content);
            }
        ?>
        <div class="<?php echo esc_attr($css_class );?>">

            <div class="cc-btn cc-prev"><i class="fal fa-angle-left"></i></div>
            <div class="cc-btn cc-next"><i class="fal fa-angle-right"></i></div>
            <div class="clients-carousel">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php 
                        foreach ($settings['images'] as $key => $image) {
                            ?>
                            <!--client-item-->
                            <div class="swiper-slide">
                                <?php 
                                if(isset($partnerslinks[$key])){
                                    $target = $settings['is_external'] == 'yes'? ' target="_blank"':'';
                                    echo '<a class="client-item" href="'.esc_url( $partnerslinks[$key] ).'"'.$target.'>';
                                }else{
                                    echo '<a class="client-item" href="javascript:void(0);">';
                                }
                                echo wp_get_attachment_image( $image['id'],  'partner' ); ?>
                                </a>
                            </div>
                            <!--client-item end-->
                        <?php
                        }
                        ?>                                                                                                                                                                                                                                        
                    </div>
                </div>
            </div>
        </div>
        <?php
        endif;
    }
    

}
