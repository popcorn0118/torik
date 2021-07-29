<?php
/* add_ons_php */

/* cmb2 dependency field */
function townhub_cmb2_admin_scripts()
{
    // Adding custom admin scripts file
    wp_enqueue_script('townhub_cmb2_admin', ESB_DIR_URL . 'inc/assets/depends_cmb2.js', array('jquery'));

    // Registering and adding custom admin css
    wp_register_style('townhub_cmb2_admin', ESB_DIR_URL . 'inc/assets/depends_cmb2.css', false, '1.0.0');
    wp_enqueue_style('townhub_cmb2_admin');
}

add_action('admin_enqueue_scripts', 'townhub_cmb2_admin_scripts');

add_action('cmb2_admin_init', 'townhub_cmb2_sample_metaboxes');
/**
 * Define the metabox and field configurations.
 */
function townhub_cmb2_sample_metaboxes()
{

    // Start with an underscore to hide fields from custom fields list
    $prefix = '_cth_';

    /**
     * Initiate Post metabox
     */
    $post_cmb = new_cmb2_box(array(
        'id'           => 'post_options',
        'title'        => esc_html__('Post Format Options', 'townhub-add-ons'),
        'object_types' => array('post'), // Post type
        'context'      => 'normal', // normal, side and advanced
        'priority'     => 'high', // default, high and low - core
        'show_names'   => true, // Show field names on the left
    ));

    $post_cmb->add_field(array(
        'name'         => esc_html__('Post Slider and Gallery Images', 'townhub-add-ons'),
        'id'           => $prefix . 'post_slider_images',
        'type'         => 'file_list',
        'preview_size' => array(150, 150), // Default: array( 50, 50 )
    ));

    $post_cmb->add_field(array(
        'name'    => esc_html__('Gallery Columns', 'townhub-add-ons'),
        'desc'    => esc_html__('For Gallery post format only.', 'townhub-add-ons'),
        'id'      => $prefix . 'gallery_cols',
        'type'    => 'select',
        'default' => 'three',
        'options' => array(
            'one'   => esc_html__('One column', 'townhub-add-ons'),
            'two'   => esc_html__('Two columns', 'townhub-add-ons'),
            'three' => esc_html__('Three columns', 'townhub-add-ons'),
            'four'  => esc_html__('Four columns', 'townhub-add-ons'),
            'five'  => esc_html__('Five columns', 'townhub-add-ons'),

        ),
    ));

    $post_cmb->add_field(array(
        'name' => esc_html__('oEmbed for Post Format', 'townhub-add-ons'),
        'desc' => wp_kses(__('Enter a youtube, twitter, or instagram URL. Supports services listed at <a href="http://codex.wordpress.org/Embeds">http://codex.wordpress.org/Embeds</a>.', 'townhub-add-ons'), array('a' => array('href' => array()))),
        'id'   => $prefix . 'embed_video',
        'type' => 'oembed',
    ));

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Initiate Post metabox 2
     */
    $post2_cmb = new_cmb2_box(array(
        'id'           => 'post_layout_options',
        'title'        => esc_html__('Post Layout Options', 'townhub-add-ons'),
        'object_types' => array('post'), // Post type
        'context'      => 'normal', // normal, side and advanced
        'priority'     => 'high', // default, high and low - core
        'show_names'   => true, // Show field names on the left
    ));

    $post2_cmb->add_field(array(
        'name'    => esc_html__('Show Post Header', 'townhub-add-ons'),
        'id'      => $prefix . 'show_page_header',
        'type'    => 'radio_inline',
        'default' => 'yes',
        'options' => array(
            'yes' => esc_html__('Yes', 'townhub-add-ons'),
            'no'  => esc_html__('No', 'townhub-add-ons'),

        ),
    ));

    $post2_cmb->add_field(array(
        'name'    => esc_html__('Header Image Background', 'townhub-add-ons'),
        'id'      => $prefix . 'page_header_bg',
        'type'    => 'file',
        // Optional:
        'options' => array(
            'url' => true, // Hide the text input for the url

        ),
    ));

    $post2_cmb->add_field(array(
        'name'    => esc_html__('Show Post Title in header', 'townhub-add-ons'),
        'id'      => $prefix . 'show_page_title',
        'type'    => 'radio_inline',
        'default' => 'yes',
        'options' => array(
            'yes' => esc_html__('Yes', 'townhub-add-ons'),
            'no'  => esc_html__('No', 'townhub-add-ons'),

        ),
    ));

    // $post2_cmb->add_field( array(
    //     'name' => esc_html__('Header Subtitle', 'townhub-add-ons' ),
    //     'id'   => $prefix . 'page_header_sub',
    //     'type' => 'text'
    // ) );

    $post2_cmb->add_field(array(
        'name' => esc_html__('Header Additional Info', 'townhub-add-ons'),
        'id'   => $prefix . 'page_header_intro',
        'type' => 'textarea_small',
    ));

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Initiate Post featured
     */
    $post3_cmb = new_cmb2_box(array(
        'id'           => 'post_featured_options',
        'title'        => esc_html__('Featured', 'townhub-add-ons'),
        'object_types' => array('post'), // Post type
        'context'      => 'side', // normal, side and advanced
        'priority'     => 'high', // default, high and low - core
        'show_names'   => true, // Show field names on the left
    ));

    $post3_cmb->add_field(array(
        'name'             => esc_html__('Is Featured post', 'townhub-add-ons'),
        'id'               => $prefix . 'is_featured',
        'type'             => 'select',
        'show_option_none' => false,
        'default'          => 'no',
        'options'          => array(
            'no'  => esc_html__('No', 'townhub-add-ons'),
            'yes' => esc_html__('Yes', 'townhub-add-ons'),
        ),
    ));

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Initiate Portfolio metabox
     */
    // $listing_media_cmb = new_cmb2_box( array(
    //     'id'            => 'listing_contacts',
    //     'title'         => esc_html__('Media', 'townhub-add-ons' ),
    //     'object_types'  => array( 'listing'), // Post type
    //     'context'       => 'normal',// normal, side and advanced
    //     'priority'      => 'high',// default, high and low - core
    //     'show_names'    => true, // Show field names on the left
    // ) );
    // $listing_media_cmb->add_field( array(
    //     'name' => 'Images',
    //     'desc' => '',
    //     'id'   => $prefix . 'headerimgs',
    //     'type' => 'file_list', // https://github.com/CMB2/CMB2/wiki/Field-Types#file_list
    //     // 'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
    //     'query_args' => array( 'type' => 'image' ), // Only images attachment
    //     // Optional, override default text strings
    //     // 'text' => array(
    //     //     'add_upload_files_text' => 'Replacement', // default: "Add or Upload Files"
    //     //     'remove_image_text' => 'Replacement', // default: "Remove Image"
    //     //     'file_text' => 'Replacement', // default: "File:"
    //     //     'file_download_text' => 'Replacement', // default: "Download"
    //     //     'remove_text' => 'Replacement', // default: "Remove"
    //     // ),
    // ) );

/////////////////////////////////////////////

    /**
     * Initiate Plan metabox
     */
    // $plan_sub_cmb = new_cmb2_box( array(
    //     'id'            => 'lplan_submit_fields',
    //     'title'         => esc_html__('Show/Hide Submit Fields', 'townhub-add-ons' ),
    //     'object_types'  => array( 'lplan'), // Post type
    //     'context'       => 'normal',// normal, side and advanced
    //     'priority'      => 'high',// default, high and low - core
    //     'show_names'    => true, // Show field names on the left
    // ) );

    // // $plan_sub_cmb->add_field( array(
    // //     'name'          => __('Hide Tags', 'townhub-add-ons'),
    // //     'desc'          => __('Check this to hide <strong>Tags</strong> field on submit page.', 'townhub-add-ons' ),
    // //     'default'       => '0',
    // //     'id'            => $prefix . 'hide_tags',
    // //     'type'          => 'checkbox'
    // // ) );
    // // $plan_sub_cmb->add_field( array(
    // //     'name'          => esc_html__('Hide Header Background Image type', 'townhub-add-ons' ),
    // //     'desc'          => __('Check this to hide header <strong>Background Image</strong> type on submit page.', 'townhub-add-ons' ),
    // //     'default'       => '0',
    // //     'id'            => $prefix . 'hide_head_background',
    // //     'type'          => 'checkbox'
    // // ) );
    // $plan_sub_cmb->add_field( array(
    //     'name'          => esc_html__('Hide Header Carousel type', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide header <strong>Carousel</strong> type on submit page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_head_carousel',
    //     'type'          => 'checkbox'
    // ) );
    // $plan_sub_cmb->add_field( array(
    //     'name'          => esc_html__('Hide Header Video Background type', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide header <strong>Video Background</strong> type on submit page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_head_video',
    //     'type'          => 'checkbox'
    // ) );

    // $plan_sub_cmb->add_field( array(
    //     'name'          => esc_html__('Hide Promo Video', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide <strong>Promo Video</strong> option on submit page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_content_video',
    //     'type'          => 'checkbox'
    // ) );
    // $plan_sub_cmb->add_field( array(
    //     'name'          => esc_html__('Hide Thumbnails Gallery', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide <strong>Thumbnails Gallery</strong> option on submit page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_content_gallery',
    //     'type'          => 'checkbox'
    // ) );
    // $plan_sub_cmb->add_field( array(
    //     'name'          => esc_html__('Hide Slider', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide <strong>Slider</strong> option on submit page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_content_slider',
    //     'type'          => 'checkbox'
    // ) );
    // $plan_sub_cmb->add_field( array(
    //     'name'          => esc_html__('Hide Price Options', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide <strong>Price Options</strong> option on submit/listing page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_price_opt',
    //     'type'          => 'checkbox'
    // ) );
    // $plan_sub_cmb->add_field( array(
    //     'name'          => esc_html__('Hide FAQs', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide <strong>Frequently Asked Questions</strong> option on submit/listing page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_faqs_opt',
    //     'type'          => 'checkbox'
    // ) );
    // $plan_sub_cmb->add_field( array(
    //     'name'          => esc_html__('Hide Event Counter', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide <strong>Event Counter</strong> option on submit/listing page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_counter_opt',
    //     'type'          => 'checkbox'
    // ) );
    // $plan_sub_cmb->add_field( array(
    //     'name'          => esc_html__('Hide Working Hours', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide <strong>Working Hours</strong> option on submit/listing page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_workinghours_opt',
    //     'type'          => 'checkbox'
    // ) );
    // $plan_sub_cmb->add_field( array(
    //     'name'          => esc_html__('Hide Socials', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide <strong>Socials</strong> option on submit/listing page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_socials_opt',
    //     'type'          => 'checkbox'
    // ) );

    // $plan_single_cmb = new_cmb2_box( array(
    //     'id'            => 'lplan_single_fields',
    //     'title'         => esc_html__('Show/Hide Single Content', 'townhub-add-ons' ),
    //     'object_types'  => array( 'lplan'), // Post type
    //     'context'       => 'normal',// normal, side and advanced
    //     'priority'      => 'high',// default, high and low - core
    //     'show_names'    => true, // Show field names on the left
    // ) );

    // for listing single view
    // $plan_single_cmb->add_field( array(
    //     'name'          => esc_html__('Hide Contact Details', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide <strong>Contact Details</strong> on header/location widget on listing page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_contacts_info',
    //     'type'          => 'checkbox'
    // ) );

    // $plan_single_cmb->add_field( array(
    //     'name'          => esc_html__('Hide Author Info', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide listing author info on listing page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_author_info',
    //     'type'          => 'checkbox'
    // ) );

    // // single widgets
    // $plan_single_cmb->add_field( array(
    //     'name'          => esc_html__('Hide Working Hours', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide <strong>Working Hours</strong> widget on listing page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_wkhour_widget',
    //     'type'          => 'checkbox'
    // ) );
    // $plan_single_cmb->add_field( array(
    //     'name'          => esc_html__('Hide Event Counter', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide <strong>Event Counter</strong> widget on listing page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_counter_widget',
    //     'type'          => 'checkbox'
    // ) );

    // $plan_single_cmb->add_field( array(
    //     'name'          => esc_html__('Hide Price Range', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide <strong>Price Range</strong> widget on listing page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_pricerange_widget',
    //     'type'          => 'checkbox'
    // ) );

    // $plan_single_cmb->add_field( array(
    //     'name'          => esc_html__('Hide Booking Form', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide <strong>Booking Form</strong> widget on listing page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_booking_form_widget',
    //     'type'          => 'checkbox'
    // ) );

    // $plan_single_cmb->add_field( array(
    //     'name'          => esc_html__('Hide Weather', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide <strong>Weather</strong> widget on listing page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_weather_widget',
    //     'type'          => 'checkbox'
    // ) );

    // $plan_single_cmb->add_field( array(
    //     'name'          => esc_html__('Hide Additional Features', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide <strong>Additional Features</strong> widget on listing page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_addfeatures_widget',
    //     'type'          => 'checkbox'
    // ) );
    // $plan_single_cmb->add_field( array(
    //     'name'          => esc_html__('Hide Location / Contacts', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide <strong>Location / Contacts</strong> widget on listing page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_contacts_widget',
    //     'type'          => 'checkbox'
    // ) );

    // $plan_single_cmb->add_field( array(
    //     'name'          => esc_html__('Hide Listing Author', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide <strong>Listing Author</strong> widget on listing page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_author_widget',
    //     'type'          => 'checkbox'
    // ) );

    // $plan_single_cmb->add_field( array(
    //     'name'          => esc_html__('Hide More from Author', 'townhub-add-ons' ),
    //     'desc'          => __('Check this to hide <strong>More from Author</strong> widget on listing page.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'hide_moreauthor_widget',
    //     'type'          => 'checkbox'
    // ) );
    // listing type
    $plan_ltype_cmb = new_cmb2_box(array(
        'id'           => 'lplan_ltype_fields',
        'title'        => esc_html__('Listing Types', 'townhub-add-ons'),
        'object_types' => array('lplan'), // Post type
        'context'      => 'normal', // normal, side and advanced
        'priority'     => 'high', // default, high and low - core
        'show_names'   => true, // Show field names on the left
    ));
    $plan_ltype_cmb->add_field(array(
        'name'    => esc_html__('Listing Type', 'townhub-add-ons'),
        'desc'    => __('Select listing types which author subscribed for this plan can submit listings to', 'townhub-add-ons'),
        'id'      => $prefix . 'listing_types',
        'type'    => 'multicheck',
        'options' => townhub_addons_get_listing_type_options(),
    ));

    $plan_ltype_cmb->add_field(array(
        'name'    => _x('Default Listing Type','Plan post', 'townhub-add-ons'),
        'desc'    => _x('Which will be selected on submit listing page','Plan post', 'townhub-add-ons'),
        'id'      => $prefix . 'dfltype',
        'type'    => 'select',
        'show_option_none' => true,
        'options' => townhub_addons_get_listing_type_options(),
    ));

    // $plan_ltype_cmb->add_field( array(
    //     'name'    => esc_html__('Listing Type', 'townhub-add-ons'),
    //     'desc'    => __('Select listing types which author subscribed for this plan can submit listings to', 'townhub-add-ons'),
    //     'id'      => $prefix . 'listing_types',
    //     'type'    => 'pw_multiselect',
    //     'options' => townhub_addons_get_listing_type_options(),
    //     // 'attributes' => array(
    //     //     'data-maximum-selection-length' => '2',
    //     // ),
    // ) );

/////////////////////////////////////////////
    /**
     * Initiate Plan metabox
     */
    $plan_cmb = new_cmb2_box(array(
        'id'           => 'lplan_fields',
        'title'        => esc_html__('Plan Options', 'townhub-add-ons'),
        'object_types' => array('lplan'), // Post type
        'context'      => 'normal', // normal, side and advanced
        'priority'     => 'high', // default, high and low - core
        'show_names'   => true, // Show field names on the left
    ));

    $plan_cmb->add_field(array(
        'name'             => esc_html__('Color', 'townhub-add-ons'),
        'id'               => $prefix . 'color',
        'type'             => 'select',
        'show_option_none' => false,
        'default'          => '',
        'options'          => array(
            'color' => esc_html__('Theme color', 'townhub-add-ons'),
            'purp'  => esc_html__('Purple', 'townhub-add-ons'),
            'green' => esc_html__('Green', 'townhub-add-ons'),
            'blue'  => esc_html__('Blue', 'townhub-add-ons'),

        ),

    ));

    $plan_cmb->add_field(array(
        'name'    => esc_html__('Subtitle', 'townhub-add-ons'),
        // 'desc'          => esc_html__('', 'townhub-add-ons' ),
        'default' => '',
        'id'      => $prefix . 'subtitle',
        'type'    => 'text',
    ));

    $plan_cmb->add_field(array(
        'name'    => esc_html__('Price', 'townhub-add-ons'),
        'desc'    => esc_html__('Value 0 for free.', 'townhub-add-ons'),
        'default' => '49',
        'id'      => $prefix . 'price',
        'type'    => 'text_small',
        // 'before_field'  => '$',
    ));

    $plan_cmb->add_field(array(
        'name'             => esc_html__('Period', 'townhub-add-ons'),
        'desc'             => esc_html__('Expired period', 'townhub-add-ons'),
        'id'               => $prefix . 'period',
        'type'             => 'select',
        'show_option_none' => false,
        'default'          => 'month',
        'options'          => townhub_add_ons_get_subscription_duration_units(),

        // array(
        //     // 'hour'          => esc_html__( 'Hour', 'townhub-add-ons' ),
        //     'day'           => esc_html__( 'Days', 'townhub-add-ons' ),
        //     'week'          => esc_html__( 'Weeks', 'townhub-add-ons' ),
        //     'month'         => esc_html__( 'Months', 'townhub-add-ons' ),
        //     'year'          => esc_html__( 'Years', 'townhub-add-ons' ),
        // ),

    ));

    $plan_cmb->add_field(array(
        'name'    => esc_html__('Interval', 'townhub-add-ons'),
        'desc'    => esc_html__('Numbers of PERIOD value which listing will be expired', 'townhub-add-ons'),
        'default' => '1',
        'id'      => $prefix . 'interval',
        'type'    => 'text_small',
    ));

    $plan_cmb->add_field(array(
        'name'    => esc_html__('Yearly Sale (%)', 'townhub-add-ons'),
        'desc'    => esc_html__('Yearly subscription price will be calculated based on this sale value.', 'townhub-add-ons'),
        'default' => '5',
        'id'      => $prefix . 'yearly_sale',
        'type'    => 'text_small',
    ));




    $plan_cmb->add_field(array(
        'name'    => esc_html__('No Expire', 'townhub-add-ons'),
        'desc'    => esc_html__('Check this if subscription never expire.', 'townhub-add-ons'),
        'default' => '0',
        'id'      => $prefix . 'lnever_expire',
        'type'    => 'checkbox',
    ));
    $plan_cmb->add_field(array(
        'name'    => esc_html__('Author Fee', 'townhub-add-ons'),
        'desc'    => esc_html__('Value 0% to 100%.', 'townhub-add-ons'),
        'default' => '5',
        'id'      => $prefix . 'author_fee',
        'type'    => 'text_small',
    ));

    $plan_cmb->add_field(array(
        'name'    => esc_html__('Listing Submission Limit', 'townhub-add-ons'),
        'desc'    => esc_html__('Numbers of listing who subscribe for this plan can submit.', 'townhub-add-ons'),
        'default' => '1',
        'id'      => $prefix . 'llimit',
        'type'    => 'text_small',
    ));

    $plan_cmb->add_field(array(
        'name'    => esc_html__('Unlimited Listing Submission', 'townhub-add-ons'),
        'desc'    => esc_html__('Check this if this plan has unlimited listing submission.', 'townhub-add-ons'),
        'default' => '0',
        'id'      => $prefix . 'lunlimited',
        'type'    => 'checkbox',
    ));

    $plan_cmb->add_field(array(
        'name'    => esc_html__('Featured Listings', 'townhub-add-ons'),
        'desc'    => esc_html__('Numbers of featured listings for this plan.', 'townhub-add-ons'),
        'default' => '1',
        'id'      => $prefix . 'lfeatured',
        'type'    => 'text_small',
    ));

    $plan_cmb->add_field(array(
        'name'    => esc_html__('Is Recurring', 'townhub-add-ons'),
        'desc'    => esc_html__('Check this if this plan required recurring payment.', 'townhub-add-ons'),
        'default' => '0',
        'id'      => $prefix . 'is_recurring',
        'type'    => 'checkbox',
    ));

    $plan_cmb->add_field(array(
        'name'    => esc_html_x('Can buy again?', 'Plan post', 'townhub-add-ons'),
        'desc'    => esc_html_x('Check this if you want authors can buy this plan again after has this plan subscription expired.', 'Plan post', 'townhub-add-ons'),
        'default' => '0',
        'id'      => $prefix . 'can_buy_again',
        'type'    => 'checkbox',
    ));

    ///////
    $plan_recurring_cmb = new_cmb2_box(array(
        'id'           => 'lplan_recurring_fields',
        'title'        => esc_html__('Recurring Options', 'townhub-add-ons'),
        'object_types' => array('lplan'), // Post type
        'context'      => 'normal', // normal, side and advanced
        'priority'     => 'high', // default, high and low - core
        'show_names'   => true, // Show field names on the left
    ));

    $plan_recurring_cmb->add_field(array(
        'name'    => esc_html__('Trial Interval', 'townhub-add-ons'),
        'desc'    => esc_html__('Value O for disable.', 'townhub-add-ons'),
        'default' => '0',
        'id'      => $prefix . 'trial_interval',
        'type'    => 'text_small',
    ));

    $plan_recurring_cmb->add_field(array(
        'name'             => esc_html__('Trial Period', 'townhub-add-ons'),
        // 'desc'          => esc_html__( 'Trial Expired period', 'townhub-add-ons' ),
        'id'               => $prefix . 'trial_period',
        'type'             => 'select',
        'show_option_none' => false,
        'default'          => 'day',
        'options'          => townhub_add_ons_get_subscription_duration_units(),

    ));

    ///////
    // $plan_woo_cmb = new_cmb2_box( array(
    //     'id'            => 'lplan_woo_fields',
    //     'title'         => esc_html__('WooCommerce Integration', 'townhub-add-ons' ),
    //     'object_types'  => array( 'lplan'), // Post type
    //     'context'       => 'normal',// normal, side and advanced
    //     'priority'      => 'high',// default, high and low - core
    //     'show_names'    => true, // Show field names on the left
    // ) );

    // $plan_woo_cmb->add_field( array(
    //     'name'          => esc_html__('Sell with WooCommerce', 'townhub-add-ons' ),
    //     'desc'          => esc_html__('Check this if you want to sell this membership package with WooCommerce payments. Currently support One-Time payment only.', 'townhub-add-ons' ),
    //     'default'       => '0',
    //     'id'            => $prefix . 'sell_with_woo',
    //     'type'          => 'checkbox'
    // ) );

    $plan_dokan_cmb = new_cmb2_box(array(
        'id'           => 'lplan_dokan_fields',
        'title'        => esc_html__('Dokan Options', 'townhub-add-ons'),
        'object_types' => array('lplan'), // Post type
        'context'      => 'normal', // normal, side and advanced
        'priority'     => 'high', // default, high and low - core
        'show_names'   => true, // Show field names on the left
    ));

    $plan_dokan_cmb->add_field(array(
        'name'    => esc_html__('Product Submission Limit', 'townhub-add-ons'),
        'desc'    => esc_html__('Numbers of products who subscribe for this plan can submit.', 'townhub-add-ons'),
        'default' => '1',
        'id'      => $prefix . 'woo_limit',
        'type'    => 'text_small',
    ));


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $page2_cmb = new_cmb2_box(array(
        'id'                => 'page_ltype',
        'title'             => esc_html_x('Listings page','Page Options', 'townhub-add-ons'),
        'object_types'      => array('page'), // Post type
        'context'           => 'normal', // normal, side and advanced
        'priority'          => 'high', // default, high and low - core
        'show_names'        => true, // Show field names on the left
    ));
    $page2_cmb->add_field(array(
        'name'              => esc_html_x('Listing type', 'Page Options', 'townhub-add-ons'),

        'id'                => $prefix . 'ltype',
        'type'              => 'select',
        'show_option_none'  => true,
        'default'           => 'none',
        'options'           => townhub_addons_get_listing_type_options(),
        'desc'              => esc_html_x('Select a listing type if you want to build listings page of the type.', 'Page Options', 'townhub-add-ons'),
    ));

    /**
     * Initiate Page metabox
     */
    $page_cmb = new_cmb2_box(array(
        'id'                => 'des_header',
        'title'             => esc_html__('Page Layout Options - For normal page template only', 'townhub-add-ons'),
        'object_types'      => array('page'), // Post type
        'context'           => 'normal', // normal, side and advanced
        'priority'          => 'high', // default, high and low - core
        'show_names'        => true, // Show field names on the left
    ));

    $page_cmb->add_field(array(
        'name'              => esc_html__('Show Post Header', 'townhub-add-ons'),
        'id'                => $prefix . 'show_page_header',
        'type'              => 'radio_inline',
        'default'           => 'yes',
        'options'           => array(
            'yes'               => esc_html__('Yes', 'townhub-add-ons'),
            'no'                => esc_html__('No', 'townhub-add-ons'),

        ),
    ));

    $page_cmb->add_field(array(
        'name'    => esc_html__('Header Image Background', 'townhub-add-ons'),
        'id'      => $prefix . 'page_header_bg',
        'type'    => 'file',
        // Optional:
        'options' => array(
            'url' => true, // Hide the text input for the url

        ),
    ));

    $page_cmb->add_field(array(
        'name'    => esc_html__('Show Post Title in header', 'townhub-add-ons'),
        'id'      => $prefix . 'show_page_title',
        'type'    => 'radio_inline',
        'default' => 'yes',
        'options' => array(
            'yes' => esc_html__('Yes', 'townhub-add-ons'),
            'no'  => esc_html__('No', 'townhub-add-ons'),

        ),
    ));

    $page_cmb->add_field(array(
        'name' => esc_html__('Header Additional Info', 'townhub-add-ons'),
        'id'   => $prefix . 'page_header_intro',
        'type' => 'textarea_small',
    ));

//////////////////////////////////////////////////////////////////////////////////////

    /**
     * Initiate Resumes metabox
     */
    $resume_cmb = new_cmb2_box(array(
        'id'           => 'resumes_mtb',
        'title'        => esc_html__('Resume Options', 'townhub-add-ons'),
        'object_types' => array('cth_resume'), // Post type
        'context'      => 'normal', // normal, side and advanced
        'priority'     => 'high', // default, high and low - core
        'show_names'   => true, // Show field names on the left
    ));

    $resume_cmb->add_field(array(
        'name'    => esc_html__('Resume Date', 'townhub-add-ons'),
        'id'      => $prefix . 'resume_date',
        'type'    => 'text',
        'default' => '2017',
    ));

    /**
     * Initiate Testimonials metabox
     */
    $testim_cmb = new_cmb2_box(array(
        'id'           => 'testimonial_mtb',
        'title'        => esc_html__('Testimonial Meta Options', 'townhub-add-ons'),
        'object_types' => array('cth_testimonial'), // Post type
        'context'      => 'normal', // normal, side and advanced
        'priority'     => 'high', // default, high and low - core
        'show_names'   => true, // Show field names on the left
    ));

    $testim_cmb->add_field(array(
        'name'             => esc_html__('Rating Stars', 'townhub-add-ons'),

        'id'               => $prefix . 'testim_rate',
        'type'             => 'select',
        'show_option_none' => false,
        'default'          => 'five',
        'options'          => array(
            'no'  => esc_html__('Not Rate', 'townhub-add-ons'),
            '1'   => esc_html__('1 Star', 'townhub-add-ons'),
            '1.5' => esc_html__('1.5 Stars', 'townhub-add-ons'),
            '2'   => esc_html__('2 Stars', 'townhub-add-ons'),
            '2.5' => esc_html__('2.5 Stars', 'townhub-add-ons'),
            '3'   => esc_html__('3 Stars', 'townhub-add-ons'),
            '3.5' => esc_html__('3.5 Stars', 'townhub-add-ons'),
            '4'   => esc_html__('4 Stars', 'townhub-add-ons'),
            '4.5' => esc_html__('4.5 Stars', 'townhub-add-ons'),
            '5'   => esc_html__('5 Stars', 'townhub-add-ons'),

        ),
    ));

    $testim_cmb->add_field(array(
        'name'    => esc_html__('Additional Info', 'townhub-add-ons'),
        'desc'    => '',
        'default' => '',
        'id'      => $prefix . 'job',
        'type'    => 'textarea',
    ));

    $listing_type_cmb = new_cmb2_box(array(
        'id'           => 'ltype_auth_booking_emails',
        'title'        => _x('Admin/Author Booking Emails Template', 'Listing type', 'townhub-add-ons'),
        'object_types' => array('listing_type'), // Post type
        'context'      => 'normal', // normal, side and advanced
        'priority'     => 'core', // default, high and low - core
        'show_names'   => true, // Show field names on the left
    ));
    $listing_type_cmb->add_field(array(
        'name'    => _x('New Booking Post', 'Listing type', 'townhub-add-ons'),
        'desc'    => _x('Leave this empty to use option from TownHub Add-Ons -> Emails tab', 'Listing type', 'townhub-add-ons'),
        'default' => '',
        'id'      => $prefix . 'auth_lbook_new',
        'type'    => 'textarea_small',
    ));
    $listing_type_cmb->add_field(array(
        'name'    => _x('Approved Booking', 'Listing type', 'townhub-add-ons'),
        'desc'    => _x('Leave this empty to use option from TownHub Add-Ons -> Emails tab', 'Listing type', 'townhub-add-ons'),
        'default' => '',
        'id'      => $prefix . 'auth_lbook_approved',
        'type'    => 'textarea_small',
    ));
    $listing_type_cmb->add_field(array(
        'name'    => _x('Cancel Booking', 'Listing type', 'townhub-add-ons'),
        'desc'    => _x('Leave this empty to use option from TownHub Add-Ons -> Emails tab', 'Listing type', 'townhub-add-ons'),
        'default' => '',
        'id'      => $prefix . 'auth_lbook_cancel',
        'type'    => 'textarea_small',
    ));

    // customer email
    $ltype_customer_lbook = new_cmb2_box(array(
        'id'           => 'ltype_customer_booking_emails',
        'title'        => _x('Customer Booking Emails Template', 'Listing type', 'townhub-add-ons'),
        'object_types' => array('listing_type'), // Post type
        'context'      => 'normal', // normal, side and advanced
        'priority'     => 'core', // default, high and low - core
        'show_names'   => true, // Show field names on the left
    ));
    $ltype_customer_lbook->add_field(array(
        'name'    => _x('New Booking Post', 'Listing type', 'townhub-add-ons'),
        'desc'    => _x('Leave this empty to use option from TownHub Add-Ons -> Emails tab', 'Listing type', 'townhub-add-ons'),
        'default' => '',
        'id'      => $prefix . 'customer_lbook_new',
        'type'    => 'textarea_small',
    ));
    $ltype_customer_lbook->add_field(array(
        'name'    => _x('Approved Booking', 'Listing type', 'townhub-add-ons'),
        'desc'    => _x('Leave this empty to use option from TownHub Add-Ons -> Emails tab', 'Listing type', 'townhub-add-ons'),
        'default' => '',
        'id'      => $prefix . 'customer_lbook_approved',
        'type'    => 'textarea_small',
    ));
    $ltype_customer_lbook->add_field(array(
        'name'    => _x('Cancel Booking', 'Listing type', 'townhub-add-ons'),
        'desc'    => _x('Leave this empty to use option from TownHub Add-Ons -> Emails tab', 'Listing type', 'townhub-add-ons'),
        'default' => '',
        'id'      => $prefix . 'customer_lbook_cancel',
        'type'    => 'textarea_small',
    ));

    
}
