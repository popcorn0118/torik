<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Collage_Images extends Widget_Base {

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
        return 'collage_images';
    }

    // public function get_id() {
    //      return 'header-search';
    // }

    public function get_title() {
        return __( 'Collage Images', 'townhub-add-ons' );
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
            'title',
            [
                'label'       => __( 'Title', 'townhub-add-ons' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => '',
                'label_block' => true,
                
            ]
        );

        $this->add_control(
            'logo',
            [
                'label'       => __( 'Logo', 'townhub-add-ons' ),
                'type'        => Controls_Manager::MEDIA,
                'default' => [
                    'id'  => 6459,
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'label_block' => true,
                
            ]
        );

        $this->add_control(
            'images',
            [
                'label' => __( 'Images', 'townhub-add-ons' ),
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    [
                        'order' => '',
                        'title' => 'Main Image - Avatar 1',
                        'image' => array(
                            'id'=>'3409',
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'left_pos' => '',
                        'top_pos' => '',
                        'zindex' => '',
                        'use_animation' => '',
                        'use_content'=> '',
                        'content' => '',
                        'show_icon' => '',
                        'icon'    => '',
                    ],
                    [
                        'order' => '2',
                        'title' => 'Image 2 - Avatar 2',
                        'image' => array(
                            'id'=>'3470',
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'left_pos' => '78',
                        'top_pos' => '35',
                        'zindex' => '2',
                        'use_animation' => 'yes',
                        'use_content'=> '',
                        'content' => '',
                        'show_icon' => '',
                        'icon'    => '',
                    ],
                    [
                        'order' => '1',
                        'title' => 'Image 3 - Avatar 4',
                        'image' => array(
                            'id'=>'3471',
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'left_pos' => '70',
                        'top_pos' => '61',
                        'zindex' => '5',
                        'use_animation' => 'yes',
                        'use_content'=> '',
                        'content' => '',
                        'show_icon' => '',
                        'icon'    => '',
                    ],
                    [
                        'order' => '3',
                        'title' => 'Image 4 - Avatar 6',
                        'image' => array(
                            'id'=>'3472',
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'left_pos' => '26',
                        'top_pos' => '82',
                        'zindex' => '11',
                        'use_animation' => 'yes',
                        'use_content'=> '',
                        'content' => '',
                        'show_icon' => '',
                        'icon'    => '',
                    ],
                    [
                        'order' => '',
                        'title' => 'Search - Avatar 5',
                        'image' => array(
                            'id'=>'',
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'left_pos' => '90',
                        'top_pos' => '10',
                        'zindex' => '11',
                        'use_animation' => '',
                        'use_content'=> 'yes',
                        'content' => 'Search',
                        'show_icon' => 'yes',
                        'icon'    => 'fa fa-search',
                    ],
                    [
                        'order' => '',
                        'title' => 'Booking now - Avatar 7',
                        'image' => array(
                            'id'=>'',
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'left_pos' => '67',
                        'top_pos' => '0',
                        'zindex' => '11',
                        'use_animation' => '',
                        'use_content'=> 'yes',
                        'content' => 'Booking now',
                        'show_icon' => 'no',
                        'icon'    => '',
                    ],
                ],
                'fields' => [ //,1971,1973,1975,1974
                    [
                        'name' => 'title',
                        'label' => __( 'Image Title', 'townhub-add-ons' ),
                        'type' => Controls_Manager::TEXT,
                        'default' => 'Image Title',
                        'label_block' => true,
                        'description' => __( 'For editing only', 'townhub-add-ons' ),
                    ],
                    [
                        'name' => 'image',
                        'label' => __( 'Image', 'townhub-add-ons' ),
                        'type' => Controls_Manager::MEDIA,
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                         ],
                    ],
                    [
                        'name' => 'left_pos',
                        'label' => __( 'Left Position', 'townhub-add-ons' ),
                        'type' => Controls_Manager::NUMBER,
                        'default' => '23',
                        'description' => __( 'Left position (%) related to element (top-left corner) ', 'townhub-add-ons' ),
                    ],
                    [
                        'name' => 'top_pos',
                        'label' => __( 'Top Position', 'townhub-add-ons' ),
                        'type' => Controls_Manager::NUMBER,
                        'default' => '10',
                        'description' => __( 'Left position (%) related to element (top-left corner) ', 'townhub-add-ons' ),
                    ],
                    [
                        'name' => 'zindex',
                        'label' => __( 'Zindex', 'townhub-add-ons' ),
                        'type' => Controls_Manager::NUMBER,
                        'default' => '0',
                        'description' => __( 'Use to control image displaying in Z axis.', 'townhub-add-ons' ),
                    ],
                    [
                        'name' => 'use_animation',
                        'label' => __( 'Animation Image?', 'townhub-add-ons' ),
                        'type' => Controls_Manager::SWITCHER,
                        'default' => 'no',
                        'label_on' => __( 'Yes', 'townhub-add-ons' ),
                        'label_off' => __( 'No', 'townhub-add-ons' ),
                        'return_value' => 'yes',
                    ],
                    [
                        'name' => 'order',
                        'label' => __( 'Animation Duration Order', 'townhub-add-ons' ),
                        'type' => Controls_Manager::NUMBER,
                        'default' => '1',
                        'description' => __( 'Choose from 1 to 5: 1-0s, 2-2.5s, 3-3.5s, 4-4.5s, 5-5.5s', 'townhub-add-ons' ),
                    ],
                    [
                        'name' => 'use_content',
                        'label' => __( 'Display content?', 'townhub-add-ons' ),
                        'type' => Controls_Manager::SWITCHER,
                        'default' => 'no',
                        'label_on' => __( 'Yes', 'townhub-add-ons' ),
                        'label_off' => __( 'No', 'townhub-add-ons' ),
                        'return_value' => 'yes',
                    ],
                    [
                        'name' => 'content',
                        'label' => __( 'Content', 'townhub-add-ons' ),
                        'type' => Controls_Manager::TEXT,
                        'default' => '',
                        'label_block' => true,
                        'description' => __( 'For editing only', 'townhub-add-ons' ),
                    ],
                    [
                        'name' => 'show_icon',
                        'label' => __( 'Display Icon?', 'townhub-add-ons' ),
                        'type' => Controls_Manager::SWITCHER,
                        'default' => 'no',
                        'label_on' => __( 'Yes', 'townhub-add-ons' ),
                        'label_off' => __( 'No', 'townhub-add-ons' ),
                        'return_value' => 'yes',
                    ],
                    [
                        'name' => 'icon',
                        'label' => __( 'Icon', 'townhub-add-ons' ),
                        'type' => 'cthicon',
                        'default' => '',
                    ],
                    
                ],
                'title_field' => '{{{ title }}}',
            ]
        );
        
        $this->add_control(
            'mwidth',
            [
                'label'       => __( 'Width (pixel)', 'townhub-add-ons' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '600',
                'label_block' => false,
                
            ]
        );


        

        $this->end_controls_section();

    }

    protected function render( ) {
        $settings = $this->get_settings();
        if(is_array($settings['images']) && !empty($settings['images']) ):
            
        ?>
        <div class="images-collage fl-wrap">
            <div class="collage-image"  style="width:<?php echo esc_attr( $settings['mwidth'] );?>px;">
                <?php if( $settings['title'] != '' ) : ?><div class="images-collage-title anim-col textdec color-bg"><?php echo $settings['title']; ?></div><?php endif; ?>
                <?php if( !empty($settings['logo']) ) : ?><div class="images-collage-title color2-bg icdec logodec"><?php echo wp_get_attachment_image( $settings['logo']['id'],  'full', false, array('class'=>'no-lazy') ); ?></div><?php endif; ?>

                

                <?php 
                foreach ($settings['images'] as $key => $image) {
                    if ($image['use_content'] == 'yes') { 
                        $img_class = ($image['show_icon'] == 'yes' ? 'collage-image-input hasicon' :'collage-image-btn green-bg');
                        if($image['content'] == '') $img_class .= ' empty-content';
                    }else{
                        $img_class = ($key == 0 ? 'main-collage-image' : 'images-collage-item images-collage-other');
                    }

                    if($image['use_animation'] == 'yes') $img_class .= ' cim-'.$image['order'];
                    $img_datas = '';
                    if($image['left_pos']) $img_datas .= ' data-position-left="'.$image['left_pos'].'"';
                    if($image['top_pos']) $img_datas .= ' data-position-top="'.$image['top_pos'].'"';
                    if($image['zindex']) $img_datas .= ' data-zindex="'.$image['zindex'].'"';

                    $img_size = ($key == 0 ? 'full' : array(90,90));
                    $animation_duration = ($key == 0 ? 'animation-duration-0s' : 'animation-duration-');
                    ?>
                    <div class="<?php echo esc_attr( $img_class ); ?>" <?php echo $img_datas; ?>>
                        <?php 
                        if ($image['use_content'] == 'yes'){
                            echo $image['content'];
                            if ($image['show_icon'] == 'yes'){ ?>
                                <i class="<?php echo $image['icon']; ?>"></i>
                            <?php }
                        }else{
                            // echo '<div class="collage-image-wrap">';
                                echo wp_get_attachment_image( $image['image']['id'],  $img_size, false, array('class'=>'no-lazy') );
                            // echo '</div>';
                        }
                        ?>
                    </div>
                <?php 
                }
                ?>
            </div>
        </div>
        <?php
        endif;
    }

}
