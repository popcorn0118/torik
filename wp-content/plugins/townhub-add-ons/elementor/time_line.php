<?php
/* add_ons_php */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CTH_Time_Line extends Widget_Base {

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
        return 'time_line';
    }

    // public function get_id() {
    //    	return 'header-search';
    // }

    public function get_title() {
        return __( 'Time Line', 'townhub-add-ons' );
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
                'label' => __( 'Time Line', 'townhub-add-ons' ),
            ]
        );

        

        $this->add_control(
            'time_lines',
            [
                'label' => __( 'Time Line Items', 'townhub-add-ons' ),
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    [
                        'step' => 'Step 1',
                        'step_num' => '01 . ',
                        'icon' => 'fa fa-map-o',

                        'title' => 'Find Interesting Place',
                        'content' => '<p>Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>',
                        'image' => array(
                            'id'=> 2144,
                            'url'=> 'http://localhost:8888/townhub/wp-content/uploads/2018/03/15.jpg'
                        ),
                        'video' => ''
                    ],
                    [
                        'step' => 'Step 2',
                        'step_num' => '02 . ',
                        'icon' => 'fa fa-envelope-open-o',

                        'title' => 'Contact a Few Owners',
                        'content' => '<p>Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>',
                        'image' => array(
                            'id'=> 2145,
                            'url'=>'http://localhost:8888/townhub/wp-content/uploads/2018/03/16-1.jpg'
                        ),
                        'video' => ''
                    ],
                    [
                        'step' => 'Step 3',
                        'step_num' => '03 . ',
                        'icon' => 'fa fa-hand-peace-o',

                        'title' => 'Make a Listing',
                        'content' => '<p>Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>',
                        'image' => array(
                            'id'=> 2146,
                            'url'=>'http://localhost:8888/townhub/wp-content/uploads/2018/03/18.jpg'
                        ),
                        'video' => ''
                    ],
                ],
                'fields' => [
                    [
                        'name' => 'step',
                        'label' => __( 'Step', 'townhub-add-ons' ),
                        'type' => Controls_Manager::TEXT,
                        'default' => 'Step 1',
                        'label_block' => true,
                    ],
                    [
                        'name' => 'step_num',
                        'label' => __( 'Step Number', 'townhub-add-ons' ),
                        'type' => Controls_Manager::TEXT,
                        'default' => '01 . ',
                        // 'label_block' => true,
                    ],
                    [
                        'name' => 'title',
                        'label' => __( 'Title', 'townhub-add-ons' ),
                        'type' => Controls_Manager::TEXT,
                        'default' => 'Find Interesting Place',
                        'label_block' => true,
                    ],
                    [
                        'name' => 'icon',
                        'label' => __( 'Title', 'townhub-add-ons' ),
                        'type' => Controls_Manager::ICON,
                        'default' => 'fa fa-map-o',
                        'label_block' => true,
                        
                    ],
                    [
                        'name' => 'content',
                        'label' => __( 'Content', 'townhub-add-ons' ),
                        'type' => Controls_Manager::WYSIWYG,
                        'default' => '<p>Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>',
                        'label_block' => true,
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
                        'name' => 'video',
                        'label' => __( 'Or Video URL', 'townhub-add-ons' ),
                        'type' => Controls_Manager::TEXT,
                        'placeholder' => __( 'https://www.youtube.com/watch?v=xpVfcZ0ZcFM', 'townhub-add-ons' ),
                        'label_block' => true,
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );


        $this->add_control(
            'first_side',
            [
                'label'       => __( 'First Content Side', 'townhub-add-ons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left'  => __( 'Left', 'townhub-add-ons' ),
                    'right' => __( 'Right', 'townhub-add-ons' ),
                ],
                
            ]
        );

        $this->add_control(
            'end_icon',
            [
                'label' => __( 'End Icon', 'townhub-add-ons' ),
                'type' => Controls_Manager::ICON,
                'default'=>'fa fa-check'
            ]
        );

        

        $this->end_controls_section();

    }

    protected function render( ) {
        $settings = $this->get_settings();
        if(is_array($settings['time_lines']) && !empty($settings['time_lines']) ):
            $first_side = $settings['first_side'];


        ?>
        <div class="time-line-wrap fl-wrap">
        <?php
            foreach ($settings['time_lines'] as $key => $timeline) {
                if(($key+1)%2 == 0){
                    $media_cl = $settings['first_side'] == 'left'? 'tl-left' : 'tl-right';
                    $content_cl = $settings['first_side'] == 'left'? 'tl-right' : 'tl-left';
                    $container_cl = $settings['first_side'] == 'left'? 'ct-right' : 'ct-left';

                }else{
                    $content_cl = $settings['first_side'] == 'left'? 'tl-left' : 'tl-right';
                    $media_cl = $settings['first_side'] == 'left'? 'tl-right' : 'tl-left';
                    $container_cl = $settings['first_side'] == 'left'? 'ct-left' : 'ct-right';
                }
        ?>
            <!--  time-line-container  --> 
            <div class="time-line-container <?php echo esc_attr( $container_cl ); ?>">
                <?php if($timeline['step']!='') echo '<div class="step-item">'.esc_html($timeline['step']).'</div>'; ?>
                <div class="time-line-box tl-text <?php echo esc_attr( $content_cl ); ?>">
                    <?php if($timeline['step_num']!='') echo '<span class="process-count">'.esc_html($timeline['step_num']).'</span>'; ?>
                    <?php if($timeline['icon']!=''): ?>
                    <div class="time-line-icon">
                        <i class="<?php echo esc_attr( $timeline['icon'] ); ?>"></i>
                    </div>
                    <?php endif;?>
                    <?php if($timeline['title']!='') echo '<h3>'.esc_html($timeline['title']).'</h3>'; ?>
                    <?php echo $timeline['content']; ?>
                </div>
                <?php 
                if($timeline['image']['id']): ?>
                <div class="time-line-box tl-media <?php echo esc_attr( $media_cl ); ?>">
                    <?php echo wp_get_attachment_image( $timeline['image']['id'], 'full' ); ?>
                </div>
                <?php elseif($timeline['video'] !='') : ?>
                <div class="time-line-box tl-media tl-video <?php echo esc_attr( $media_cl ); ?>">
                    <div class="resp-video">
                        <?php echo wp_oembed_get( esc_url($timeline['video']) ); ?>
                    </div>
                </div>
                <?php endif;?>
            </div>
            <!--  time-line-container -->         
            <?php
            }
            ?>
            <div class="clearfix"></div>
            <?php if($settings['end_icon']!=''): ?>
                <div class="timeline-end"><i class="<?php echo esc_attr( $settings['end_icon'] ); ?>"></i></div>
            <?php endif;?>
            
        </div>
        <?php
        endif;
    }

    // protected function _content_template() {}
    // end _content_template



}
