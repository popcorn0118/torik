<?php
/* add_ons_php */

defined('ABSPATH') || exit;

class Esb_Class_Admin_Scripts
{

    private static $plugin_url;

    public static function init()
    {
        self::$plugin_url = plugin_dir_url(ESB_PLUGIN_FILE);

        add_action('admin_footer', array(get_called_class(), 'price_templates'));

        add_action('admin_enqueue_scripts', array(get_called_class(), 'enqueue_scripts'));
    }
    public static function price_templates()
    {
        ?>
        <script type="text/template" id="tmpl-content-addwidget">
            <?php townhub_addons_get_template_part('templates-inner/add-widget');?>
        </script>
        <script type="text/template" id="tmpl-content-addwidgetfield">
            <?php townhub_addons_get_template_part('templates-inner/add-widgetfield');?>
        </script>
        <script type="text/template" id="tmpl-currency">
            <?php townhub_addons_get_template_part('templates-inner/add-currency');?>
        </script>
        <?php
// edit listing templates
        townhub_addons_get_template_part('shortcodes/tmpls');
        // socials template
        townhub_addons_get_template_part('shortcodes/tmpls-dashboard');
    }
    private static function enqueue_react_libraries()
    {
        wp_enqueue_script('react', self::$plugin_url . "assets/js/react.production.min.js", array(), null, true);
        wp_enqueue_script('react-dom', self::$plugin_url . "assets/js/react-dom.production.min.js", array(), null, true);
        wp_enqueue_script('react-router-dom', self::$plugin_url . "assets/js/react-router-dom.min.js", array(), null, true);
        wp_enqueue_script('redux', self::$plugin_url . "assets/js/redux.min.js", array(), null, true);
        wp_enqueue_script('react-redux', self::$plugin_url . "assets/js/react-redux.min.js", array(), null, true);
        wp_enqueue_script('redux-thunk', self::$plugin_url . "assets/js/redux-thunk.min.js", array(), null, true);
        wp_enqueue_script('qs', self::$plugin_url . "assets/js/qs.js", array(), null, true);
        wp_enqueue_script('axios', self::$plugin_url . "assets/js/axios.min.js", array(), null, true);
        wp_enqueue_script('Sortable', self::$plugin_url . "assets/js/Sortable.min.js", array(), null, true);
        wp_enqueue_script('react-sortable', self::$plugin_url . "assets/js/react-sortable.min.js", array(), null, true);
        wp_enqueue_script('jquery-scrolltofixed', self::$plugin_url . "assets/js/jquery-scrolltofixed-min.js", array(), null, true);

    }

    public static function enqueue_scripts($hook)
    {
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        wp_enqueue_style('fontawesome-pro', self::$plugin_url . "assets/vendors/fontawesome-pro-5.10.0-web/css/all.min.css", false);
        wp_enqueue_style('select2', self::$plugin_url . 'assets/css/select2.min.css');
        wp_enqueue_style('townhub-add-ons', self::$plugin_url . 'assets/css/admin.css');
        wp_style_add_data( 'townhub-add-ons', 'rtl', 'replace' );

        wp_enqueue_style('datetimepicker.jquery', self::$plugin_url . 'assets/admin/datetimepicker/jquery.datetimepicker.min.css');
        $map_provider = townhub_addons_get_option('map_provider');
        $gmap_api_key = townhub_addons_get_option('gmap_api_key');

        if( $map_provider == 'mapbox' ){
            wp_enqueue_style('mapbox-gl', "https://api.mapbox.com/mapbox-gl-js/v1.11.0/mapbox-gl.css", false);
            wp_enqueue_style('mapbox-gl-geocoder', "https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.css", false);

            wp_enqueue_script('mapbox-gl', "https://api.mapbox.com/mapbox-gl-js/v1.11.0/mapbox-gl.js", array(), null, true);
            wp_enqueue_script('mapbox-gl-geocoder', "https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.min.js", array(), null, true);
            // https://docs.mapbox.com/help/how-mapbox-works/geocoding/
            // https://docs.mapbox.com/mapbox-gl-js/example/mapbox-gl-geocoder
            wp_enqueue_script('es6-promise', "https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.min.js", array(), null, true);
            wp_enqueue_script('es6-promise-auto', "https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.min.js", array(), null, true);

        }else if( $map_provider == 'googlemap' ){
            wp_enqueue_script("googleapis", "https://maps.googleapis.com/maps/api/js?key=$gmap_api_key&libraries=places", array(), false, true);
        }else {
            wp_enqueue_script('openlayers', self::$plugin_url . "assets/js/ol.js", array(), null, true);
        }


        wp_enqueue_script('select2', self::$plugin_url . 'assets/js/select2.min.js', array('jquery'), null, true);
        wp_enqueue_script('datetimepicker.jquery', self::$plugin_url . 'assets/admin/datetimepicker/jquery.datetimepicker.full.min.js', array('jquery'), null, true);

        wp_enqueue_script('townhub-addons-admin', self::$plugin_url . 'assets/js/addons-admin.min.js', array('jquery', 'jquery-ui-sortable', 'select2', 'wp-i18n'), null, true);
        $curr_user_data = array(
            'id'           => 0,
            'display_name' => '',
            'avatar'       => '',
            'can_upload'   => false,
        );

        if (is_user_logged_in()) {
            $current_user   = wp_get_current_user();
            $curr_user_data = array(
                'id'           => $current_user->ID,
                'display_name' => $current_user->display_name,
                'avatar'       => get_avatar($current_user->user_email, '150', 'https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=150', $current_user->display_name),

                'can_upload'   => current_user_can('upload_files'),
            );
        }
        $gmap_marker            = townhub_addons_get_option('gmap_marker');
        $_townhub_add_ons_admin = array(
            'center_lat' => floatval(townhub_addons_get_option('gmap_default_lat')),
            'center_lng' => floatval(townhub_addons_get_option('gmap_default_long')),
            'map_zoom'   => townhub_addons_get_option('gmap_default_zoom'),
            'marker'     => $gmap_marker['url'] ? $gmap_marker['url'] : ESB_DIR_URL . "assets/images/marker.png",
            
        );
        wp_localize_script('townhub-addons-admin', '_townhub_add_ons_admin', $_townhub_add_ons_admin);
        if (function_exists('wp_set_script_translations')) {
            wp_set_script_translations('townhub-addons-admin', 'townhub-add-ons');
        }

        $screen = get_current_screen();
        if ($screen->base == 'post' && ($screen->post_type == 'listing_type' || $screen->post_type == 'listing' || $screen->post_type == 'lrooms' || $screen->post_type == 'product')) {
            wp_enqueue_editor();
            self::enqueue_react_libraries();
            $_townhub_add_ons_adminapp = array(
                'i18n'         => array(
                    'profile'                   => __('Profile', 'townhub-add-ons'),

                    'tab_general'               => __('General', 'townhub-add-ons'),
                    'tab_fields'                => __('Fields', 'townhub-add-ons'),
                    'tab_single'                => __('Single', 'townhub-add-ons'),
                    'tab_cards'                 => __('Cards', 'townhub-add-ons'),
                    'tab_filter'                => __('Filter', 'townhub-add-ons'),
                    'tab_advanced'              => __('Advanced', 'townhub-add-ons'),
                    'tab_advanced_schema'       => __('Schema Markup', 'townhub-add-ons'),
                    'tab_advanced_settings'     => __('Settings', 'townhub-add-ons'),
                    
                    'nav_title_L'               => __('Listing', 'townhub-add-ons'),
                    'nav_title_R'               => __('Child Product', 'townhub-add-ons'),
                    'nav_title_FB'              => __('Form Booking', 'townhub-add-ons'),
                    'nav_title_Ra'              => __('Rating', 'townhub-add-ons'),
                    'nav_title_Fi_L'            => __('Filter Listing', 'townhub-add-ons'),
                    'nav_title_Fi_HS'           => __('Filter Hero Section', 'townhub-add-ons'),
                    'nav_title_Fi_H'            => __('Filter Header', 'townhub-add-ons'),

                    // Comp - inners - AddForm
                    'opt_val_choose'            => __('Chose...', 'townhub-add-ons'),
                    'opt_val_input'             => __('Input', 'townhub-add-ons'),
                    'opt_val_check'             => __('Checkbox', 'townhub-add-ons'),
                    'opt_val_text'              => __('Textarea', 'townhub-add-ons'),
                    'opt_val_Wysiwyg'           => __('Wysiwyg Editor', 'townhub-add-ons'),
                    'opt_val_select'            => __('Select', 'townhub-add-ons'),
                    'opt_val_radio'             => __('Radio', 'townhub-add-ons'),
                    'opt_val_switch'            => __('Switch', 'townhub-add-ons'),
                    'opt_val_img'               => __('Image', 'townhub-add-ons'),
                    'opt_val_muti'              => __('Multiple select', 'townhub-add-ons'),
                    'opt_val_number'            => __('Number', 'townhub-add-ons'),

                    'opt_val_img'               => __('Image', 'townhub-add-ons'),
                    // 'opt_val_img'               => __('Image', 'townhub-add-ons'),
                    // 'opt_val_img'               => __('Image', 'townhub-add-ons'),
                    // 'opt_val_img'               => __('Image', 'townhub-add-ons'),
                    // 'opt_val_img'               => __('Image', 'townhub-add-ons'),
                    // 'opt_val_img'               => __('Image', 'townhub-add-ons'),

                    'lable_loca_Fi_SorG'        => __('Is Single Image', 'townhub-add-ons'),
                    'lable_loca_Fi_C'           => __('Preview Columns', 'townhub-add-ons'),
                    'label_image_limit'         => __( 'Limit', 'townhub-add-ons' ),
                    // 'opt_val_N'        => __( 'None',  'townhub-add-ons' ),
                    'opt_val_Y'                 => __('Yes', 'townhub-add-ons'),
                    'opt_val_N'                 => __('No', 'townhub-add-ons'),

                    // Comp - general
                    'label_title_icon'          => __('Icon', 'townhub-add-ons'),
                    'label_title_singular_name' => __('Singular name (e.g.Business)', 'townhub-add-ons'),
                    'label_title_plural_name'   => __('Plural name (e.g.Business)', 'townhub-add-ons'),
                    'label_title_child_ptype'   => __('Choose custom post type in parent listings type', 'townhub-add-ons'),
                    // Label, H2, Span title
                    'title'                     => __('This is listing type General tab', 'townhub-add-ons'),
                    'lable_title_S'             => __('Select Type', 'townhub-add-ons'),
                    'lable_title_A'             => __('Add Listing Room', 'townhub-add-ons'),
                    'lable_title_A_Fi'          => __('Add Field Rating', 'townhub-add-ons'),

                    'lable_loca_T'              => __('TOP', 'townhub-add-ons'),
                    'lable_loca_R'              => __('RIGHT', 'townhub-add-ons'),
                    'lable_loca_B'              => __('BOTTOM', 'townhub-add-ons'),
                    'lable_loca_L'              => __('LEFT', 'townhub-add-ons'),

                    'lable_title_O'             => __('Option value', 'townhub-add-ons'),

                    'lable_title_Fi_ID'         => __('Field ID', 'townhub-add-ons'),
                    'lable_title_Fi_T'          => __('Field Title', 'townhub-add-ons'),
                    'lable_title_Fi_N'          => __('Field Name', 'townhub-add-ons'),
                    'lable_title_Fi_L'          => __('Field Label', 'townhub-add-ons'),
                    'lable_title_Fi_I'          => __('Field Icon', 'townhub-add-ons'),
                    'lable_title_Fi_D'          => __('Field Description', 'townhub-add-ons'),
                    'lable_title_Fi_de'         => __('Default Value', 'townhub-add-ons'),
                    'lable_title_Fi_ra_sy'      => __('Field Rating System', 'townhub-add-ons'),
                    'lable_title_Fi_V'          => __('Field Value', 'townhub-add-ons'),
                    'lable_title_Fi_Df_V'       => __('Default Value', 'townhub-add-ons'),
                    'lable_title_Fi_W'          => __('Field Width', 'townhub-add-ons'),
                    'lable_title_Fi_C'          => __('Field column', 'townhub-add-ons'),
                    'lable_title_Fi_SorG'       => __('Field single or Gallery', 'townhub-add-ons'),
                    'lable_title_Fi_Re'         => __('Required', 'townhub-add-ons'),
                    'lable_title_Fi_admin'      => __('Show Dashboard Admin', 'townhub-add-ons'),
                    'lable_title_Fi_dec_op'     => __('Hidden Description', 'townhub-add-ons'),

                    'lable_title_TT'            => __('Title Text', 'townhub-add-ons'),
                    'lable_title_TD'            => __('Title Description', 'townhub-add-ons'),
                    'label_resmenu_limit'       => _x('Menus Limit', 'Listing Fields', 'townhub-add-ons'),



                    'span_title_C'              => __(' Click here or drop files to upload', 'townhub-add-ons'),
                    'span_title_Cl'             => __(' Click here to upload', 'townhub-add-ons'),
                    'span_title_P'              => __('PX', 'townhub-add-ons'),
                    'span_title_E'              => __('EM', 'townhub-add-ons'),
                    'span_title_R'              => __('REM', 'townhub-add-ons'),
                    'span_title_S'              => __(' Select images', 'townhub-add-ons'),
                    'span_title'                => __('%', 'townhub-add-ons'),

                    // Button
                    'btn_s'                     => __('Save', 'townhub-add-ons'),
                    'btn_save'                  => __('Save Change', 'townhub-add-ons'),
                    'btn_add_Fi'                => __('Add Field ', 'townhub-add-ons'),
                    'btn_add_F'                 => __('Add Facts  ', 'townhub-add-ons'),
                    'btn_add_R'                 => __('Add Room', 'townhub-add-ons'),
                    'btn_add_S'                 => __('Add Social', 'townhub-add-ons'),
                    'btn_add_O'                 => __('Add Option', 'townhub-add-ons'),
                    'btn_link_V'                => __('Link values', 'townhub-add-ons'),

                    'validate_error'            => __('was used by another field. Please try with other value.', 'townhub-add-ons'),
                    'max_room'                  => __('Max Rooms', 'townhub-add-ons'),
                    'template_erro'             => __('Template name must not be empty.', 'townhub-add-ons'),
                    'template_save'             => __('Saved Page Templates', 'townhub-add-ons'),
                    'template_append'           => __('Append previosly saved template to the current layout', 'townhub-add-ons'),

                    'opt_lbl'                   => __('Label', 'townhub-add-ons'),
                    'opt_val'                   => __('Value', 'townhub-add-ons'),

                    'schema_name'               => __('Schema Name', 'townhub-add-ons'),
                    'schema_value'              => __('Schema Value', 'townhub-add-ons'),
                    'schema_use_listing'        => __('Use listing info', 'townhub-add-ons'),
                    'room_add_text'             => __('Add Room text', 'townhub-add-ons'),
                    'add_ticket_btn'           => __('Add button text', 'townhub-add-ons'),
                    'ticket_name_title'           => __('Name field title', 'townhub-add-ons'),
                    'ticket_price_title'           => __('Price field title', 'townhub-add-ons'),
                    'ticket_desc_title'           => __('Description field title', 'townhub-add-ons'),
                    'ticket_available_title'           => __('Available field title', 'townhub-add-ons'),


                ),
                // azp elements
                'azp_elements' => AZPElements::getEles(),
                'schema'       => townhub_addons_schema_listing_metas(),
            );
            wp_enqueue_script('townhub-react-adminapp', self::$plugin_url . "assets/js/townhub-react-adminapp.min.js", array('underscore', 'townhub-addons-admin'), null, true);
            wp_localize_script('townhub-react-adminapp', '_townhub_add_ons_adminapp', $_townhub_add_ons_adminapp);
            //======================
            $listing_type_opts   = array();
            $listing_types_posts = get_posts(array(
                'post_type'      => 'listing_type',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'fields'         => 'ids',

                // 'suppress_filters'  => false,
            ));
            if ($listing_types_posts) {
                foreach ($listing_types_posts as $ltid) {
                    $listing_type_opts[] = array(
                        'ID'          => $ltid,
                        'title'       => get_the_title($ltid),
                        'icon'        => '',
                        'description' => '',
                    );
                }
            }
            $room_type_opts = array(
                array(
                    'ID'    => '0',
                    'title' => __('Select a listing', 'townhub-add-ons'),
                ),
            );
            $room_types_posts = get_posts(array(
                'post_type'      => 'listing',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'fields'         => 'ids',

                // 'suppress_filters'  => false,
            ));
            if ($room_types_posts) {
                foreach ($room_types_posts as $ltid) {
                    $room_type_opts[] = array(
                        'ID'          => $ltid,
                        'title'       => get_the_title($ltid),
                        'icon'        => '',
                        'description' => '',
                    );
                }
            }
            $type_child_nth = array(array(
                'none' => 'None',
            ));
            // $room_pt          = get_post_type_object('lrooms');
            $type_child_nth[] = array(
                'lrooms' => __( 'Hotel Room', 'townhub-add-ons' ), // $room_pt->labels->singular_name,
            );
            if (post_type_exists('product')) {
                // $product_pt       = get_post_type_object('product');
                $type_child_nth[] = array(
                    'product' => __( 'WooCommerce Product', 'townhub-add-ons' ),  // $product_pt->labels->singular_name,
                );
            }
            $child_ptype = '';
            $child_pty   = get_post_meta(get_queried_object_id(), ESB_META_PREFIX . 'general_field_meta', true);
            if (!empty($child_pty) && $child_pty != '') {
                $child_ptype = $child_pty;
            }
            $gmap_marker      = townhub_addons_get_option('gmap_marker');
            $_townhub_add_ons = array(
                'is_rtl'               => is_rtl(),
                'url'                  => esc_url(admin_url('admin-ajax.php')),
                'nonce'                => wp_create_nonce('townhub-add-ons'),
                // 'listing_types'                 => $listing_type_ids,
                'listing_type_opts'    => $listing_type_opts,
                'room_type_opts'       => $room_type_opts,
                'id_post'              => get_the_ID(),
                'curr_user'            => $curr_user_data,
                'socials'              => townhub_addons_get_socials_list(),
                'marker'               => $gmap_marker['url'] ? $gmap_marker['url'] : self::$plugin_url . "assets/images/marker2.png",
                'center_lat'           => floatval(townhub_addons_get_option('gmap_default_lat')),
                'center_lng'           => floatval(townhub_addons_get_option('gmap_default_long')),
                'map_zoom'             => townhub_addons_get_option('gmap_default_zoom'),
                
                // 'files'                => townhub_addons_cont_fiels_select(),
                // 'features'             => townhub_addons_get_listing_features(),
                'cats'                 => townhub_addons_get_listing_cats(),
                'locs'                 => townhub_addons_get_listing_locs(),
                'gmap_type'            => townhub_addons_get_option('gmap_type'),
                // 'submit_timezone_hide' => townhub_addons_get_option('submit_timezone_hide'),
                // 'working_days'         => Esb_Class_Date::week_days(),
                // 'working_hours'        => Esb_Class_Date::wkhours_select(),
                // 'timezones'            => townhub_addons_generate_timezone_list(),
                // 'timezone'             => get_option('timezone_string', 'Europe/London'),
                'child_pt_title'       => $type_child_nth,
                'child_ptype'          => $child_ptype,
                'i18n'                 => array(
                    'btn_add_F'             => __('Add Fact', 'townhub-add-ons'),
                    'fact_title'            => __('Fact Title', 'townhub-add-ons'),
                    'fact_number'           => __('Fact Number', 'townhub-add-ons'),
                    'fact_icon'             => __('Fact Icon', 'townhub-add-ons'),
                    'slect_rtype'           => __('Select a listing', 'townhub-add-ons'),

                    'faq_title'             => __('Question', 'townhub-add-ons'),
                    'faq_content'           => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'townhub-add-ons'),
                    'btn_add_Faq'           => __('Add FAQ', 'townhub-add-ons'),
                    'btn_save'              => __('Save Change', 'townhub-add-ons'),
                    
                    'btn_add_R'             => __('Add Room', 'townhub-add-ons'),
                    'btn_add_Ame'           => __('Add Amenitie', 'townhub-add-ons'),
                    'add_hour'              => __('Add Hour', 'townhub-add-ons'),
                    // 'timezone'              => __('Timezone', 'townhub-add-ons'),
                    'image_upload'          => __(' Click here to upload', 'townhub-add-ons'),
                    // 'select_images'  => __( 'Select Images',  'townhub-add-ons' ),
                    // 'select'  => __( 'Select',  'townhub-add-ons' ),
                    
                    'btn_add_S'             => __('Add Social', 'townhub-add-ons'),
                    'btn_add_N'             => __('Add New', 'townhub-add-ons'),
                    'btn_save_c'            => __('Save Changes', 'townhub-add-ons'),
                    'btn_close'             => __('Close me', 'townhub-add-ons'),
                    'btn_send'              => __('Send Listing', 'townhub-add-ons'),
                    'calendar_dis_number'   => __('Select the number of months displayed.', 'townhub-add-ons'),
                    'calendar_number_one'   => __('One Months', 'townhub-add-ons'),
                    'calendar_number_two'   => __('Two Months', 'townhub-add-ons'),
                    'calendar_number_three' => __('Three Months', 'townhub-add-ons'),
                    'calendar_number_four'  => __('Four Months', 'townhub-add-ons'),
                    'calendar_number_five'  => __('Five Months', 'townhub-add-ons'),
                    'calendar_number_six'   => __('Six Months', 'townhub-add-ons'),
                    'calendar_number_seven' => __('Seven Months', 'townhub-add-ons'),

                    'coupon_code'           => __('Coupon code', 'townhub-add-ons'),
                    'coupon_discount'       => __('Discount type', 'townhub-add-ons'),
                    'coupon_percentage'     => __('Percentage discount', 'townhub-add-ons'),
                    'coupon_fix_cart'       => __('Fixed cart discount', 'townhub-add-ons'),
                    'coupon_desc'           => __('Description', 'townhub-add-ons'),
                    'coupon_show'           => __('Display content in widget banner?', 'townhub-add-ons'),
                    'coupon_amount'         => __('Discount amount', 'townhub-add-ons'),
                    'coupon_qtt'            => __('Coupon quantity', 'townhub-add-ons'),
                    'coupon_expiry'         => __('Coupon expiry date', 'townhub-add-ons'),
                    'coupon_format'         => __('Format:YY-mm-dd HH:ii:ss', 'townhub-add-ons'),

                    'bt_coupon'             => __('Add Coupon', 'townhub-add-ons'),
                    'bt_services'           => __('Add Service', 'townhub-add-ons'),
                    'services_name'         => __('Service Name', 'townhub-add-ons'),
                    'services_desc'         => __('Description', 'townhub-add-ons'),
                    'services_price'        => __('Service Price', 'townhub-add-ons'),
                    'bt_member'             => __('Add Member', 'townhub-add-ons'),
                    'member_name'           => __('Name: ', 'townhub-add-ons'),
                    'member_job'            => __('Job or Position: ', 'townhub-add-ons'),
                    'member_desc'           => __('Description', 'townhub-add-ons'),
                    'member_img'            => __('Image', 'townhub-add-ons'),
                    'memeber_social'        => __('Socials', 'townhub-add-ons'),
                    'member_url'            => __('Website', 'townhub-add-ons'),

                    'days'                  => cth_get_week_days(),
                    'months'                => array(
                        _x('{YEAR} January', 'calendar', 'townhub-add-ons'),
                        _x('{YEAR} February', 'calendar', 'townhub-add-ons'),
                        _x('{YEAR} March', 'calendar', 'townhub-add-ons'),
                        _x('{YEAR} April', 'calendar', 'townhub-add-ons'),
                        _x('{YEAR} May', 'calendar', 'townhub-add-ons'),
                        _x('{YEAR} June', 'calendar', 'townhub-add-ons'),
                        _x('{YEAR} July', 'calendar', 'townhub-add-ons'),
                        _x('{YEAR} August', 'calendar', 'townhub-add-ons'),
                        _x('{YEAR} September', 'calendar', 'townhub-add-ons'),
                        _x('{YEAR} October', 'calendar', 'townhub-add-ons'),
                        _x('{YEAR} November', 'calendar', 'townhub-add-ons'),
                        _x('{YEAR} December', 'calendar', 'townhub-add-ons'),
                    ),
                    'ltype_title'           => _x('Listing type', 'Listing type', 'townhub-add-ons'),
                    'ltype_desc'            => _x('Listing type description', 'Listing type', 'townhub-add-ons'),
                    'wkh_enter'             => _x('Enter Hours', 'Working hour', 'townhub-add-ons'),
                    'wkh_open'              => _x('Open all day', 'Working hour', 'townhub-add-ons'),
                    'wkh_close'             => _x('Close all day', 'Working hour', 'townhub-add-ons'),
                    'calen_lock'            => _x('Lock this month', 'Calendar', 'townhub-add-ons'),
                    'calen_unlock'          => _x('Unlock this month', 'Calendar', 'townhub-add-ons'),

                    'cancel'                => __('Cancel', 'townhub-add-ons'),
                    'submit'                => __('Submit', 'townhub-add-ons'),

                    'save'                  => __('Save', 'townhub-add-ons'),
                    'cal_event_start'       => __('Event start time: ', 'townhub-add-ons'),
                    'cal_event_end'         => __('Event end date: ', 'townhub-add-ons'),
                    'cal_opts'              => __('Options', 'townhub-add-ons'),

                    'bt_slots'              => __('Add Time Slot', 'townhub-add-ons'),
                    'slot_time'             => __('Time', 'townhub-add-ons'),
                    'slot_guests'           => __('Guests', 'townhub-add-ons'),
                    'slot_available'        => __('Available slots', 'townhub-add-ons'),
                    'ltype_select_guide'    => __('Click to change listing type', 'townhub-add-ons'),

                    'bt_add_menu'    => __('Add Menu', 'townhub-add-ons'),
                    'menu_name'    => __('Menu Name', 'townhub-add-ons'),
                    'menu_cats'    => __('Menu Types (comma separated)', 'townhub-add-ons'),
                    'menu_desc'    => __('Menu Description', 'townhub-add-ons'),
                    'menu_price'    => __('Menu Price', 'townhub-add-ons'),
                    'menu_url'    => __('Menu Link', 'townhub-add-ons'),
                    'menu_photos'    => __('Menu Photos', 'townhub-add-ons'),

                    'headm_iframe'          => _x('iFrame Source', 'Submit page', 'townhub-add-ons'),
                    'headm_mp4'    => __('MP4 Video', 'townhub-add-ons'),
                    'headm_youtube'    => __('Youtube Video ID', 'townhub-add-ons'),
                    'headm_vimeo'    => __('Vimeo Video ID', 'townhub-add-ons'),
                    'headm_bgimg'    => __('Background Image', 'townhub-add-ons'),
                    'preview_btn'           => __('Preview', 'townhub-add-ons'),
                    'slots_add'             => __( 'Add Slot', 'townhub-add-ons' ),
                    'slots_guests'             => __( 'Max Guests', 'townhub-add-ons' ),
                    'slots_start'             => __( 'Start time', 'townhub-add-ons' ),
                    'slots_end'             => __( 'End time', 'townhub-add-ons' ),
                    'slots_price'             => __( 'Price', 'townhub-add-ons' ),

                    'raselect_placeholder'             => __( 'Select', 'townhub-add-ons' ),
                    'raselect_nooptions'             => __( 'No options', 'townhub-add-ons' ),
                    
                    'cal_bulkedit'             => __( 'Bulk Edit', 'townhub-add-ons' ),
                    'save_bulkedit'            => __( 'Save', 'townhub-add-ons' ),
                    'cancel_bulkedit'            => __( 'Cancel', 'townhub-add-ons' ),
                    'AM'                            => _x( 'AM', 'Time picker AM', 'townhub-add-ons' ),
                    'PM'                            => _x( 'PM', 'Time picker PM', 'townhub-add-ons' ),
                    'evt_start'                            => _x( 'Start', 'Submit page', 'townhub-add-ons' ),
                    'evt_end'                            => _x( 'End', 'Submit page', 'townhub-add-ons' ),
                    'cal_clear_past'                      => _x( 'Clear old dates', 'Booking form', 'townhub-add-ons' ),
                ),
                'currency'             => townhub_addons_get_currency_attrs(),
                'base_currency'        => townhub_addons_get_base_currency(),
                'wpml'                          => apply_filters( 'wpml_current_language', null ),

                'unfill_address'             => townhub_addons_get_option('unfill_address'),
                'unfill_state'             => townhub_addons_get_option('unfill_state'),
                'unfill_city'             => townhub_addons_get_option('unfill_city'),
                'woocats'                   => townhub_addons_get_woo_cats(),

                'map_provider'              => $map_provider,
                'mbtoken'                   => townhub_addons_get_option('mapbox_token'),
                'autocomplete_result_type' => townhub_addons_get_option('autocomplete_result_type', 'none'),
                'week_starts_monday'                => townhub_addons_get_option('week_starts_monday'),
                'tag_dis_comma'                     => is_rtl() ? 'yes': 'no',
            );

            wp_localize_script('townhub-react-adminapp', '_townhub_add_ons', $_townhub_add_ons);



            // for submit listing
            $_townhub_submit = array(
                'submit_timezone_hide' => townhub_addons_get_option('submit_timezone_hide'),
                'timezones'            => townhub_addons_generate_timezone_list(),
                'timezone'             => get_option('timezone_string', 'Europe/London'),
                'working_days'         => Esb_Class_Date::week_days(),
                'working_hours'        => Esb_Class_Date::wkhours_select(),

                'features'             => townhub_addons_get_listing_features(),
                
                'i18n'                 => array(
                    'timezone'              => __('Timezone', 'townhub-add-ons'),
                ),
            );
            wp_localize_script('townhub-react-adminapp', '_townhub_submit', $_townhub_submit);
            //========================

            if (function_exists('wp_set_script_translations')) {
                wp_set_script_translations('townhub-react-adminapp', 'townhub-add-ons');
            }

        }

        // if ($hook != 'settings_page_townhub-addons') {
        //     return;
        // }

        // wp_enqueue_script('townhub_addons_image', ESB_DIR_URL . 'inc/assets/upload_file.js', array('jquery'), null, true);
        // wp_enqueue_style( 'custom_wp_admin_css', plugins_url('admin-style.css', __FILE__) );
        // wp_enqueue_script('select2', ESB_DIR_URL . 'assets/js/select2.min.js', array('jquery'), null, true);
        // wp_enqueue_script('townhub-add-ons-options', ESB_DIR_URL . 'assets/js/addons-options.js', array('select2'), null, true);
    }
}

Esb_Class_Admin_Scripts::init();