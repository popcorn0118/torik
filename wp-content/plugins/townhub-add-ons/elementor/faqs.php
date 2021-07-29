<?php
/* add_ons_php */

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly

class CTH_Faqs extends Widget_Base
{

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
    public function get_name()
    {
        return 'cth_faqs';
    }

    // public function get_id() {
    //        return 'header-search';
    // }

    public function get_title()
    {
        return __('FAQs', 'townhub-add-ons');
    }

    public function get_icon()
    {
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
    public function get_categories()
    {
        return ['townhub-elements'];
    }

    protected function _register_controls()
    {

        $this->start_controls_section(
            'faqs_content',
            [
                'label' => __('Content', 'townhub-add-ons'),
            ]
        );

        $this->add_control(
            'categories',
            [
                'label'       => __('Categories', 'townhub-add-ons'),
                'type'        => Controls_Manager::REPEATER,
                'default'     => [
                    [
                        'title' => 'Getting Started',
                        'icon'  => 'fal fa-space-shuttle',
                    ],
                    [
                        'title' => 'Pricing Plans',
                        'icon'  => 'fal fa-cart-arrow-down',
                    ],
                    [
                        'title' => 'Sales Questions',
                        'icon'  => 'fal fa-barcode-read',
                    ],
                    [
                        'title' => 'Usage Guides',
                        'icon'  => 'fal fa-user-headset',
                    ],
                ],
                'fields'      => [
                    [
                        'name'        => 'title',
                        'label'       => __('Category Title', 'townhub-add-ons'),
                        'type'        => Controls_Manager::TEXT,
                        'default'     => 'Getting Started',
                        'label_block' => true,
                    ],
                    [
                        'name'    => 'icon',
                        'label'   => __('Icon', 'townhub-add-ons'),
                        'type'    => 'cthicon',
                        'default' => 'fal fa-space-shuttle',
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->add_control(
            'questions',
            [
                'label'       => __('Question List', 'townhub-add-ons'),
                'type'        => Controls_Manager::REPEATER,
                'default'     => [
                    [
                        'question' => 'Suggestions',
                        'answer'   => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla. Nulla posuere sapien vitae lectus suscipit, et pulvinar nisi tincidunt. Aliquam erat volutpat. Curabitur convallis fringilla diam sed aliquam. Sed tempor iaculis massa faucibus feugiat. In fermentum facilisis massa, a consequat purus viverra.</p>',
                        'category' => 'Getting Started',
                    ],
                    [
                        'question' => 'Reccomendations',
                        'answer'   => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla. Nulla posuere sapien vitae lectus suscipit, et pulvinar nisi tincidunt. Aliquam erat volutpat. Curabitur convallis fringilla diam sed aliquam. Sed tempor iaculis massa faucibus feugiat. In fermentum facilisis massa, a consequat purus viverra.</p>',
                        'category' => 'Getting Started',
                    ],
                    [
                        'question' => 'Listing',
                        'answer'   => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla. Nulla posuere sapien vitae lectus suscipit, et pulvinar nisi tincidunt. Aliquam erat volutpat. Curabitur convallis fringilla diam sed aliquam. Sed tempor iaculis massa faucibus feugiat. In fermentum facilisis massa, a consequat purus viverra.</p>',
                        'category' => 'Getting Started',
                    ],
                    // Getting Started
                    [
                        'question' => 'Suggestions',
                        'answer'   => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla. Nulla posuere sapien vitae lectus suscipit, et pulvinar nisi tincidunt. Aliquam erat volutpat. Curabitur convallis fringilla diam sed aliquam. Sed tempor iaculis massa faucibus feugiat. In fermentum facilisis massa, a consequat purus viverra.</p>',
                        'category' => 'Pricing Plans',
                    ],
                    [
                        'question' => 'Reccomendations',
                        'answer'   => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla. Nulla posuere sapien vitae lectus suscipit, et pulvinar nisi tincidunt. Aliquam erat volutpat. Curabitur convallis fringilla diam sed aliquam. Sed tempor iaculis massa faucibus feugiat. In fermentum facilisis massa, a consequat purus viverra.</p>',
                        'category' => 'Pricing Plans',
                    ],
                    // Pricing Plans
                    [
                        'question' => 'Suggestions',
                        'answer'   => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla. Nulla posuere sapien vitae lectus suscipit, et pulvinar nisi tincidunt. Aliquam erat volutpat. Curabitur convallis fringilla diam sed aliquam. Sed tempor iaculis massa faucibus feugiat. In fermentum facilisis massa, a consequat purus viverra.</p>',
                        'category' => 'Sales Questions',
                    ],
                    [
                        'question' => 'Reccomendations',
                        'answer'   => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla. Nulla posuere sapien vitae lectus suscipit, et pulvinar nisi tincidunt. Aliquam erat volutpat. Curabitur convallis fringilla diam sed aliquam. Sed tempor iaculis massa faucibus feugiat. In fermentum facilisis massa, a consequat purus viverra.</p>',
                        'category' => 'Sales Questions',
                    ],
                    [
                        'question' => 'Listing',
                        'answer'   => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla. Nulla posuere sapien vitae lectus suscipit, et pulvinar nisi tincidunt. Aliquam erat volutpat. Curabitur convallis fringilla diam sed aliquam. Sed tempor iaculis massa faucibus feugiat. In fermentum facilisis massa, a consequat purus viverra.</p>',
                        'category' => 'Sales Questions',
                    ],
                    // Sales Questions
                    [
                        'question' => 'Suggestions',
                        'answer'   => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla. Nulla posuere sapien vitae lectus suscipit, et pulvinar nisi tincidunt. Aliquam erat volutpat. Curabitur convallis fringilla diam sed aliquam. Sed tempor iaculis massa faucibus feugiat. In fermentum facilisis massa, a consequat purus viverra.</p>',
                        'category' => 'Usage Guides',
                    ],
                    [
                        'question' => 'Reccomendations',
                        'answer'   => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla. Nulla posuere sapien vitae lectus suscipit, et pulvinar nisi tincidunt. Aliquam erat volutpat. Curabitur convallis fringilla diam sed aliquam. Sed tempor iaculis massa faucibus feugiat. In fermentum facilisis massa, a consequat purus viverra.</p>',
                        'category' => 'Usage Guides',
                    ],
                    [
                        'question' => 'Listing',
                        'answer'   => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla. Nulla posuere sapien vitae lectus suscipit, et pulvinar nisi tincidunt. Aliquam erat volutpat. Curabitur convallis fringilla diam sed aliquam. Sed tempor iaculis massa faucibus feugiat. In fermentum facilisis massa, a consequat purus viverra.</p>',
                        'category' => 'Usage Guides',
                    ],
                    [
                        'question' => 'Listing',
                        'answer'   => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla. Nulla posuere sapien vitae lectus suscipit, et pulvinar nisi tincidunt. Aliquam erat volutpat. Curabitur convallis fringilla diam sed aliquam. Sed tempor iaculis massa faucibus feugiat. In fermentum facilisis massa, a consequat purus viverra.</p>',
                        'category' => 'Usage Guides',
                    ],
                    // Usage Guides
                ],
                'fields'      => [
                    [
                        'name'        => 'question',
                        'label'       => __('Question', 'townhub-add-ons'),
                        'type'        => Controls_Manager::TEXT,
                        'default'     => 'Suggestions',
                        'label_block' => true,
                    ],
                    [
                        'name'        => 'category',
                        'label'       => __('Category Name', 'townhub-add-ons'),
                        'description' => __('This value should match <strong>Category Title</strong> above', 'townhub-add-ons'),
                        'type'        => Controls_Manager::TEXT,
                        'default'     => 'Getting Started',
                        'label_block' => true,
                    ],
                    [
                        'name'       => 'answer',
                        'label'      => __('Answer', 'townhub-add-ons'),
                        'type'       => Controls_Manager::WYSIWYG,
                        'default'    => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla. Nulla posuere sapien vitae lectus suscipit, et pulvinar nisi tincidunt. Aliquam erat volutpat. Curabitur convallis fringilla diam sed aliquam. Sed tempor iaculis massa faucibus feugiat. In fermentum facilisis massa, a consequat purus viverra.</p>',
                        'show_label' => false,
                    ],

                ],
                'title_field' => '{{{ question }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'needhelp_sec',
            [
                'label' => __('Contact Infos', 'townhub-add-ons'),
            ]
        );

        $this->add_control(
            'needhelp',
            [
                'label'        => __('Show Contact Infos?', 'townhub-add-ons'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on' => _x( 'Yes', 'On/Off', 'townhub-add-ons' ),
                'label_off' => _x( 'No', 'On/Off', 'townhub-add-ons' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'nhtitle',
            [
                'label'       => __('Title', 'townhub-add-ons'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Still Need Help ?',
                'label_block' => true,

            ]
        );

        $this->add_control(
            'nhphone',
            [
                'label'       => __('Left Side Content', 'townhub-add-ons'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => '<strong>Call us</strong> <br>+7(111)123456789',
                'label_block' => true,

            ]
        );

        $this->add_control(
            'leftimage',
            [
                'label'   => __('Left Side Image', 'townhub-add-ons'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'lefturl',
            [
                'label'       => __('Left Side URL', 'townhub-add-ons'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '#',
                'label_block' => true,

            ]
        );

        $this->add_control(
            'nhemail',
            [
                'label'       => __('Right Side Content', 'townhub-add-ons'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => '<strong>Write to us</strong><br>yourmail@domain.com',
                'label_block' => true,

            ]
        );

        $this->add_control(
            'rightimage',
            [
                'label'   => __('Right Side Image', 'townhub-add-ons'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'righturl',
            [
                'label'       => __('Right Side URL', 'townhub-add-ons'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '#',
                'label_block' => true,

            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings    = $this->get_settings();
        $css_classes = array(
            'faqs-ele-wrap',
        );

        $css_class = preg_replace('/\s+/', ' ', implode(' ', array_filter($css_classes)));

        ?>
        <div class="<?php echo $css_class; ?>">
            <div class="row faqs-row">
            <?php
            $categories = $settings['categories'];
            if (!empty($categories)):
            ?>
                <!-- faqs cats -->
                <div class="col-md-3">
                    <div class="faq-nav help-bar scroll-init">
                        <ul class="no-list-style">
                            <?php 
                            foreach ($categories as $cat) {
                                $slug = sanitize_title_with_dashes($cat['title']);
                            ?>
                            <li>
                                <a href="#faqcat-<?php echo $slug; ?>">
                                <?php if($cat['icon'] != ''): ?><i class="<?php echo esc_attr($cat['icon']); ?>"></i><?php endif; ?>
                                <span><?php echo $cat['title']; ?></span>
                                </a>
                            </li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
                <!-- faqs cats end-->
                <!-- faqs questions -->
                <div class="col-md-9">
            <?php else: ?>
                <!-- faqs questions -->
                <div class="col-md-12">
            <?php endif; //end categories ?>
                    <?php

                    $questions      = $settings['questions'];
                    $questions_cats = array();
                    if (!empty($questions)):
                        foreach ($questions as $key => $question) {
                            $slug = sanitize_title_with_dashes($question['category']);
                            if (!isset($questions_cats[$slug])) {
                                $questions_cats[$slug] = array(
                                    'title'     => '<div class="faq-title' . ($key === 0 ? ' faq-title_first fl-wrap' : ' fl-wrap') . '">' . $question['category'] . '</div>',
                                    'questions' => '<a class="toggle' . ($key === 0 ? ' act-accordion' : '') . '" href="#">' . $question['question'] . '<span></span></a><div class="accordion-inner' . ($key === 0 ? ' visible' : '') . '">' . $question['answer'] . '</div>',

                                );
                            } else {
                                $questions_cats[$slug]['questions'] .= '<a class="toggle" href="#">' . $question['question'] . '<span></span></a><div class="accordion-inner">' . $question['answer'] . '</div>';
                            }
                        }
                    endif;
                    //end !empty($questions) ?>
                
                    <?php
                    foreach ($questions_cats as $slug => $questions_cat) {
                    ?>
                        <!-- faq-section -->
                        <?php echo $questions_cat['title']; ?>
                        <div class="faq-section fl-wrap" id="faqcat-<?php echo $slug; ?>">
                            <!-- accordion-->
                            <div class="accordion">
                                <?php
                                echo $questions_cat['questions'];
                                ?>
                            </div>
                            <!-- accordion end -->
                        </div>
                        <!-- faq-section end -->
                    <?php
                    }?>

                    <?php if ($settings['needhelp'] == 'yes'): ?>
                    <div class="faq-links fl-wrap">
                        <?php if ($settings['nhtitle'] != ''): ?><h3 class="faq-links-title"><?php echo $settings['nhtitle']; ?></h3><?php endif;?>
                        <span class="section-separator"></span>
                        <!-- post nav -->
                        <div class="post-nav-wrap fl-wrap">
                            <?php if ($settings['nhphone'] != ''): ?>
                                <a class="post-nav post-nav-prev<?php if( $settings['leftimage'] != '' ) echo ' post-nav-has-thumb'; ?>" href="<?php echo esc_url( $settings['lefturl'] ); ?>">
                                    <?php if ($settings['leftimage'] != ''): ?><span class="post-nav-img"><?php echo wp_get_attachment_image($settings['leftimage']['id'], 'thumbnail'); ?></span><?php endif;?>
                                    <span class="post-nav-text"><?php echo $settings['nhphone']; ?></span>
                                </a>
                            <?php endif;?>
                            <?php if ($settings['nhemail'] != ''): ?>
                                <a class="post-nav post-nav-next<?php if( $settings['rightimage'] != '' ) echo ' post-nav-has-thumb'; ?>" href="<?php echo esc_url( $settings['righturl'] ); ?>">
                                    <?php if ($settings['rightimage'] != ''): ?><span class="post-nav-img"><?php echo wp_get_attachment_image($settings['rightimage']['id'], 'thumbnail'); ?></span><?php endif;?>
                                    <span class="post-nav-text"><?php echo $settings['nhemail']; ?></span>
                                </a>
                            <?php endif;?>
                        </div>
                        <!-- post nav end -->
                    </div>
                    <?php endif;?>
                </div>
                <!-- faqs questions end -->

            </div><!-- faqs-row end -->
        </div><!-- faqs-ele-wrap end -->
        <div class="limit-box fl-wrap"></div>
        <?php
}

}