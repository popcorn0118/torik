<?php
/* add_ons_php */


// https://github.com/pojome/elementor/blob/master/docs/content/hooks/php-hooks.md

// Add a custom category for panel widgets
add_action( 'elementor/init', function() {
   
    \Elementor\Plugin::$instance->elements_manager->add_category( 
        'townhub-elements',
        [
            'title' => __( 'TownHub Elements', 'townhub-add-ons' ),
            'icon' => 'fa fa-gmap', //default icon
        ],
        1 // position
    );




} ); 
// enqueue widget script
add_action('elementor/editor/before_enqueue_scripts', function() {
    wp_enqueue_style( 'cth_wids', ESB_DIR_URL . 'assets/elementor/cth_wids.css' );
    wp_enqueue_script('cth_wid_js', ESB_DIR_URL . 'assets/elementor/cth_wid_js.js', array('jquery') );
});

    
add_action( 'elementor/controls/controls_registered', function(){

    class CTHIcon_Control extends \Elementor\Base_Data_Control {
    // class CTHIcon_Control {
        public function get_type() {
            return 'cthicon';
        }
        public static function get_icons($lib = '') {
            return townhub_addons_extract_awesome_pro_icon_array($lib);
        }
        protected function get_default_settings() {
            return [
                // 'options' => self::get_icons('fal'),
                'options' => self::get_icons(),
                'include' => '',
                'exclude' => '',
            ];
        }
        public function content_template() {
            $control_uid = $this->get_control_uid();
            ?>
            <div class="elementor-control-field">
                <label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
                <div class="elementor-control-input-wrapper">
                    <select id="<?php echo $control_uid; ?>" class="elementor-control-icon" data-setting="{{ data.name }}" data-placeholder="<?php echo __( 'Select Icon', 'townhub-add-ons' ); ?>">
                        <option value=""><?php echo __( 'Select Icon', 'townhub-add-ons' ); ?></option>
                        <# 
                        // console.log(data.options);
                        _.each( data.options, function( option_title, option_value ) { #>
                        <option value="{{ option_value }}">{{{ option_title }}}</option>
                        <# } ); #>
                    </select>
                </div>
            </div>
            <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{ data.description }}</div>
            <# } #>
            <?php
        }

        public function enqueue() {
            parent::enqueue();
            // Styles
            wp_register_style( 'fontawesome-pro', ESB_DIR_URL.'/assets/vendors/fontawesome-pro-5.10.0-web/css/all.min.css' );
            wp_enqueue_style( 'fontawesome-pro' );

            // Scripts
            wp_register_script( 'cthicon-control', ESB_DIR_URL.'/assets/elementor/cthicon.min.js', array( 'jquery' ) );
            wp_enqueue_script( 'cthicon-control' );
        }
        
    }
    
    $controls_manager = \Elementor\Plugin::$instance->controls_manager;
    $controls_manager->register_control( 'cthicon', new CTHIcon_Control() );
    
} );


// modify attribute for element before front render
// add_action( 'elementor/frontend/element/before_render', function ( \Elementor\Element_Base $element ) {
//     if ( 'section' === $element->get_name() ) {
//         // $settings = $element->get_settings();


//         // var_dump($settings);
//         // echo '<pre>';
//         // var_dump($element);

//         if ( ! $element->get_settings( 'townhub_layout' ) ) {
//             return;
//         }

//         $element->add_render_attribute( '_wrapper', [
//             'class' => $element->get_settings( 'townhub_layout' ),
//             'data-townhub_layout' => $element->get_settings( 'townhub_layout' ),
//         ] );
//     }
        
// } );

// modify section element
// add_action( 'elementor/element/section/section_layout/before_section_start', function( $element, $args ) {
//     /** @var \Elementor\Element_Base $element */
//     $element->start_controls_section(
//         'townhub_theme',
//         [
//             'tab' => \Elementor\Controls_Manager::TAB_STYLE,
//             'label' => __( 'TownHub Theme', 'townhub-add-ons' ),
//         ]
//     );

//     $element->add_control(
//         'townhub_layout',
//         [
//             // 'type' => \Elementor\Controls_Manager::NUMBER,
//             'label' => __( 'Custom Control', 'townhub-add-ons' ),
//             'type' => \Elementor\Controls_Manager::SELECT,
//             'default' => '',
//             'options' => [
//                 ''  => __( 'Default', 'townhub-add-ons' ),
//                 'townhub_page_sec' => __( 'Page Section', 'townhub-add-ons' ),
//                 'townhub_home_sec' => __( 'Home Section', 'townhub-add-ons' ),
//                 'double' => __( 'Double', 'townhub-add-ons' ),
//                 'none'   => __( 'None', 'townhub-add-ons' ),
//             ],
//             // 'selectors' => [ // You can use the selected value in an auto-generated css rule.
//             //     '{{WRAPPER}} .your-element' => 'border-style: {{VALUE}}',
//             // ],

//         ]
//     );

//     $element->end_controls_section();
// }, 10, 2 );
// render widget
// add_action( 'elementor/widget/render_content', function( $content, $widget ) {
//     // die;
//     if ( 'section' === $widget->get_name() ) {
//         $settings = $widget->get_settings();

//         var_dump($settings);
   
//         if ( ! empty( $settings['townhub_layout']) ) {
//             $content .= '<section class="scroll-con-sec hero-section" data-scrollax-parent="true" id="sec1">
//                             <div class="bg"  data-bg="images/bg/32.jpg" data-scrollax="properties: { translateY: \'200px\' }"></div>
//                             <div class="overlay"></div>
//                             <div class="hero-section-wrap fl-wrap">
//                                 <div class="container">
//                                     <div class="intro-item fl-wrap">
//                                         <h2>We will help you to find all</h2>
//                                         <h3>Find great places , hotels , restourants , shops.</h3>
//                                     </div>
//                                     <div class="main-search-input-wrap">
//                                         <div class="main-search-input fl-wrap">
//                                             <div class="main-search-input-item">
//                                                 <input type="text" placeholder="What are you looking for?" value=""/>
//                                             </div>
//                                             <div class="main-search-input-item location">
//                                                 <input type="text" placeholder="Location" value=""/>
//                                                 <a href="#"><i class="fa fa-dot-circle-o"></i></a>
//                                             </div>
//                                             <div class="main-search-input-item">
//                                                 <select data-placeholder="All Categories" class="chosen-select" >
//                                                     <option>All Categories</option>
//                                                     <option>Shops</option>
//                                                     <option>Hotels</option>
//                                                     <option>Restaurants</option>
//                                                     <option>Fitness</option>
//                                                     <option>Events</option>
//                                                 </select>
//                                             </div>
//                                             <button class="main-search-button">Search</button>
//                                         </div>
//                                     </div>
//                                 </div>
//                             </div>
//                             <div class="bubble-bg"> </div>
//                             <div class="header-sec-link">
//                                 <div class="container"><a href="#sec2" class="custom-scroll-link">Let\'s Start</a></div>
//                             </div>
//                         </section>';
//         }   
//     }
   
//     return $content;

// }, 10, 2 );

add_action( 'elementor/widgets/widgets_registered', function( $widgets_manager ) {
    // $widget_file = 'elementor/header-search-widget.php';
    // $template_file = locate_template($widget_file);
    // if ( !$template_file || !is_readable( $template_file ) ) {
    //     $template_file = ESB_ABSPATH.$widget_file;
    // }
    // if ( $template_file && is_readable( $template_file ) ) {
    //     require_once $template_file;

    //     // $widgets_manager->register_widget_type( new \Elementor\Widget_Header_Search() );

    //     \Elementor\Plugin::$instance->widgets_manager->register_widget_type( new \Elementor\Widget_Header_Search() );

        
    // }

    $elements = array(
        'hero_section',
        'hero_section_map',
        'hero_slider',
        // 'section_title',
        'listing_categories',
        'listing_locations',
        'listings_slider',
        // 'listings_grid',
        'listings_grid_new',
        'posts_grid',
        'our_partners',
        'counter',
        'process',
        'pricing_item',
        'collage_images',

        'on_page_scroll',

        'google_map',
        'contact_form7',
        'cth_accordion',
        'time_line',

        'section_title',
        'section_titleleft',
        'popup_video',
        'feature_box',
        'team_box',
        'parallax_content',
        'testimonials_slider',
        'testimonials',
        'sticks_slider',
        'contact',

        'section_text',
        // 'contact_box',
        // 'step',
        // 'background_video',
        'members_grid',
        'listing_slider_item',
        'button',
        'button_add_listing',
        // 'asked_question',
        'faqs',

        
        'membership_plans',

        'woo_mem_plans',

    );

    foreach ( $elements as $element_name ) {
        $template_file = ESB_ABSPATH.'elementor/'.$element_name.'.php';
        if ( $template_file && is_readable( $template_file ) ) {
            require_once $template_file;
            $class_name = '\Elementor\CTH_' . ucwords($element_name,'_');
            $widgets_manager->register_widget_type( new $class_name() );
        }
            
    }
} );




