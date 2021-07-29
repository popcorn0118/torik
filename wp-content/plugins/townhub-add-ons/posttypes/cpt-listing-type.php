<?php
/* add_ons_php */

class Esb_Class_Listing_Type_CPT extends Esb_Class_CPT
{
    protected $name = 'listing_type';

    protected function init()
    {
        parent::init();

        // remove publish box
        add_action('admin_menu', function () {
            remove_meta_box('submitdiv', 'listing_type', 'side');
        });

        // $logged_in_ajax_actions = array(
        //     'submit_listing',
        //     'admin_lverified',
        //     'admin_lfeatured',
        // );
        // foreach ($logged_in_ajax_actions as $action) {
        //     $funname = str_replace('townhub_addons_', '', $action);
        //     add_action('wp_ajax_'.$action, array( $this, $funname ));
        // }

        add_filter( 'cth_listing_additional_meta_queries', array($this, 'add_meta_queries') , 5 );

        // add_action( 'wp_loaded', array($this, 'add_custom_fields_select') );
        add_filter( 'listing_booking_insert_auth_temp', array($this, 'lbook_new_auth_temp'), 10, 3 );
        add_filter( 'listing_booking_insert_customer_temp', array($this, 'lbook_new_customer_temp'), 10, 3 );

        add_filter( 'listing_booking_approved_email_auth_temp', array($this, 'lbook_approved_auth_temp'), 10, 3 );
        add_filter( 'listing_booking_approved_email_temp', array($this, 'lbook_approved_customer_temp'), 10, 3 );

        add_filter( 'listing_booking_cancel_auth_temp', array($this, 'lbook_cancel_auth_temp'), 10, 3 );
        add_filter( 'listing_booking_cancel_customer_temp', array($this, 'lbook_cancel_customer_temp'), 10, 3 );
        do_action( $this->name.'_cpt_init_after' );
    }

    

    // public function add_custom_fields_select(){
    //     global $current_screen;
    //     var_dump($_GLOBAL);
    // }

    // public static function get_custom_fields(){
    //     $return = array();
    //     return (array)apply_filters( 'cth_ltype_get_custom_fields_select', $return );
    // }

    public function add_meta_queries($queries){

        $ltype_ids = get_posts( array(
            'fields'            => 'ids',
            'post_type'         => 'listing_type',
            'posts_per_page'    => -1,
            'post_status'       => 'publish',
            
            'suppress_filters'  => false,
        ) );
        // $addQueries = array();
        $filterFields = array();
        $filterMultiFields = array();
        if( !empty($ltype_ids) ){
            foreach ($ltype_ids as $ltid) {
                $addFields = get_post_meta( $ltid, ESB_META_PREFIX.'customfields', true );
                if(!empty($addFields)){
                    foreach ((array)$addFields as $key => $field) {
                        if( !empty($field['forsearch']) && $field['forsearch'] === 'yes' ){
                            if( $field['field_type'] == 'muti' || $field['field_type'] == 'checkbox' ){
                                $filterMultiFields[$field['field_name']] = array(
                                    'name'      => $field['field_name'],
                                    'compare'   => $field['compare'],
                                    'ctype'     => $field['ctype'],
                                );
                            }else{
                                $filterFields[$field['field_name']] = array(
                                    'name'      => $field['field_name'],
                                    'compare'   => $field['compare'],
                                    'ctype'     => $field['ctype'],
                                );
                            }
                                
                        }
                    }
                }

            }
        }
        if( !empty($filterFields)){
            foreach ($filterFields as $fname => $fargs) {
                if( isset($_REQUEST[$fname]) && !empty($_REQUEST[$fname]) ){
                    $queries[] = array(
                        'key'       => ESB_META_PREFIX.$fname,
                        'value'     => $_REQUEST[$fname],
                        'type'      => $fargs['ctype'],
                        'compare'   => $fargs['compare'],
                    );
                }
            }
        }
        // for multi select
        if( !empty($filterMultiFields)){
            foreach ($filterMultiFields as $fname => $fargs) {
                if( isset($_REQUEST[$fname]) && !empty($_REQUEST[$fname]) ){
                    if( is_array($_REQUEST[$fname]) ){
                        $likeQR = array();
                        foreach ($_REQUEST[$fname] as $sVal) {
                            $likeQR[] = array(
                                'key'       => ESB_META_PREFIX.$fname,
                                'value'     => '"'.$sVal.'"',
                                'type'      => $fargs['ctype'],
                                'compare'   => 'LIKE',
                            );
                        }
                        if( !empty($likeQR) ){
                            if( count( $likeQR ) > 1 ){
                                if( $fargs['compare'] == 'IN' ) 
                                    $likeQR['relation'] = 'OR';
                                else
                                    $likeQR['relation'] = 'AND';
                                $queries[] = $likeQR;
                            }else{
                                $queries[] = $likeQR[0];
                            }
                        }
                    }else{
                        $queries[] = array(
                            'key'       => ESB_META_PREFIX.$fname,
                            'value'     => '"'.$_REQUEST[$fname].'"',
                            'type'      => $fargs['ctype'],
                            'compare'   => 'LIKE',
                        );
                    } 
                }
            }
        }

        return $queries;
    }

    protected function set_meta_boxes()
    {
        $dfBoxes = array(
            'builder' => array(
                'title'         => __('Listing Type Builder', 'townhub-add-ons'),
                'context'       => 'normal', // normal - side - advanced
                'priority'      => 'high', // default - high - low
                'callback_args' => array(),
            ),
            'settings' => array(
                'title'         => __('Advanced Settings', 'townhub-add-ons'),
                'context'       => 'normal', // normal - side - advanced
                'priority'      => 'high', // default - high - low
                'callback_args' => array(),
            ),
            'customfields' => array(
                'title'         => __('Custom Fields', 'townhub-add-ons'),
                'context'       => 'normal', // normal - side - advanced
                'priority'      => 'high', // default - high - low
                'callback_args' => array(),
            ),
        );

        $addiBoxes = (array)apply_filters( 'cth_cpt_listing_type_meta_boxes', array() );

        // var_dump($addiBoxes);

        $this->meta_boxes = array_merge($addiBoxes, $dfBoxes);
    }
    public function register()
    {

        $labels = array(
            'name'               => __('Listing Types', 'townhub-add-ons'),
            'singular_name'      => __('Listing Type', 'townhub-add-ons'),
            'add_new'            => __('Add New Listing Type', 'townhub-add-ons'),
            'add_new_item'       => __('Add New Listing Type', 'townhub-add-ons'),
            'edit_item'          => __('Edit Listing Type', 'townhub-add-ons'),
            'new_item'           => __('New Listing Type', 'townhub-add-ons'),
            'view_item'          => __('View Listing Type', 'townhub-add-ons'),
            'search_items'       => __('Search Listings Types', 'townhub-add-ons'),
            'not_found'          => __('No Listings Types found', 'townhub-add-ons'),
            'not_found_in_trash' => __('No Listings Types found in Trash', 'townhub-add-ons'),
            'parent_item_colon'  => __('Parent Listing Type:', 'townhub-add-ons'),
            'menu_name'          => __('Listing Types', 'townhub-add-ons'),
        );

        $args = array(
            'labels'              => $labels,
            'hierarchical'        => true,
            'description'         => __('Listing Types', 'townhub-add-ons'),
            'supports'            => array('title'),
            'taxonomies'          => array(),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 25,
            'menu_icon'           => 'dashicons-location-alt', // plugin_dir_url( __FILE__ ) .'assets/admin_ico_listing.png',
            'show_in_nav_menus'   => false,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'has_archive'         => false,
            'query_var'           => false,
            'can_export'          => true,
            'rewrite'             => array('slug' => __('listing_type', 'townhub-add-ons')),
            // 'rewrite' => array( 'slug' => 'listing_type', 'with_front' => true ),
            'capability_type'     => 'post',
        );
        register_post_type($this->name, $args);
    }

    protected function filter_meta_args($args, $post)
    {
        $new_post         = false;
        $args['new_post'] = $new_post;

        return $args;
    }

    public function listing_type_settings_callback($post, $args){
        $filter_by_type = get_post_meta( $post->ID, ESB_META_PREFIX.'filter_by_type', true );
        
        if($filter_by_type === '') $filter_by_type = true; // default true
        
        ?>
        <table class="form-table filter_by_type">
            <tbody>

                <tr class="hoz">
                    <th class="w20 align-left"><?php _e( 'Hero/Header filter by type', 'townhub-add-ons' ); ?></th>
                    <td>
                        <input type="checkbox" class="input-text" name="filter_by_type" value="1" <?php checked( $filter_by_type, true, true ); ?>>
                        <p><?php _e( 'Check this if you want hero and header search forms showing listings from this type only.', 'townhub-add-ons' ); ?></p>
                    </td>
                </tr>
                
            </tbody>
        </table>
        <?php 
        $price_based_val = townhub_addons_get_price_based(); 
        if( !empty($price_based_val) ):
            $price_based = get_post_meta( $post->ID, ESB_META_PREFIX.'price_based', true );
        ?>
        <table class="form-table price_based">
            <tbody>

                <tr class="hoz">
                    <th class="w20 align-left"><?php _e( 'Listing price based', 'townhub-add-ons' ); ?></th>
                    <td>
                        <select name="price_based">
                            <?php foreach ($price_based_val as $key => $value) {
                                ?><option value="<?php echo esc_attr($key); ?>" <?php selected($price_based, $key, true ); ?>><?php echo esc_html( $value ); ?></option><?php
                            } ?>
                        </select>
                    </td>
                </tr>
                
            </tbody>
        </table>
        <?php 
        endif; ?>
        <?php 
        $f_id = 'featured_image';
        $featured_image = get_post_meta( $post->ID, ESB_META_PREFIX.$f_id, true );
        ?>
        <table class="form-table featured-image">
            <tbody>

                <tr class="hoz">
                    <th class="w20 align-left"><?php _e( 'Featured Image', 'townhub-add-ons' ); ?></th>
                    <td>
                        <div class="form-field media-field-wrap">
                            <?php 
                            echo '<img class="' . $f_id . '_preview" src="' . (isset($featured_image['url']) ? esc_attr($featured_image['url']) : '') . '" alt="" ' . (isset($featured_image['url']) ? ' style="display:block;width:200px;height=auto;"' : ' style="display:none;width:200px;height=auto;"') . '>';
                            echo '<input type="hidden" name="' . $f_id . '[url]" class="' . $f_id . '_url" value="' . (isset($featured_image['url']) ? esc_attr($featured_image['url']) : '') . '">';
                            echo '<input type="hidden" name="' . $f_id . '[id]" class="' . $f_id . '_id" value="' . (isset($featured_image['id']) ? esc_attr($featured_image['id']) : '') . '">';

                            echo '<p class="description">
                                <a href="#" data-uploader_title="' . esc_attr__( 'Featured Image', 'townhub-add-ons' ) . '" class="button button-primary upload_image_button metakey- fieldkey-' . $f_id . '">' . esc_html__('Upload Image', 'townhub-add-ons') . '</a>  
                                <a href="#" class="button button-secondary remove_image_button metakey- fieldkey-' . $f_id . '">' . esc_html__('Remove', 'townhub-add-ons') . '</a>
                                </p>';
                                ?>
                        </div>
                        
                        <p><?php _e( 'Will be used for changing hero section background.', 'townhub-add-ons' ); ?></p>
                    </td>
                </tr>
                
            </tbody>
        </table>
        <?php
        $dis_free_booking = get_post_meta( $post->ID, ESB_META_PREFIX.'dis_free_booking', true );
        if($dis_free_booking === '') $dis_free_booking = false; // default true
        ?>
        <table class="form-table dis_free_booking">
            <tbody>

                <tr class="hoz">
                    <th class="w20 align-left"><?php _e( 'Disable free booking', 'townhub-add-ons' ); ?></th>
                    <td>
                        <input type="checkbox" class="input-text" name="dis_free_booking" value="1" <?php checked( (bool)$dis_free_booking, true, true ); ?>>
                        <p><?php _e( 'Not redirecting to checkout page on free booking.', 'townhub-add-ons' ); ?></p>
                    </td>
                </tr>
                
            </tbody>
        </table>
        <?php $whour_slots = get_post_meta( $post->ID, ESB_META_PREFIX.'whour_slots', true ); ?>
        <table class="form-table whour-slots">
            <tbody>

                <tr class="hoz">
                    <th class="w20 align-left"><?php _e( 'Working Hour Slots', 'townhub-add-ons' ); ?></th>
                    <td>
                        <select name="whour_slots">
                            <option value="" <?php selected( $whour_slots, '', true ); ?>><?php _ex('None', 'Listing type', 'townhub-add-ons') ?></option>
                            <option value="15" <?php selected( $whour_slots, '15', true ); ?>><?php _ex('Each 15 minutes', 'Listing type', 'townhub-add-ons') ?></option>
                            <option value="30" <?php selected( $whour_slots, '30', true ); ?>><?php _ex('Each 30 minutes', 'Listing type', 'townhub-add-ons') ?></option>
                            <option value="60" <?php selected( $whour_slots, '60', true ); ?>><?php _ex('Each 1 hour', 'Listing type', 'townhub-add-ons') ?></option>
                            <option value="120" <?php selected( $whour_slots, '120', true ); ?>><?php _ex('Each 2 hours', 'Listing type', 'townhub-add-ons') ?></option>
                            <option value="180" <?php selected( $whour_slots, '180', true ); ?>><?php _ex('Each 3 hours', 'Listing type', 'townhub-add-ons') ?></option>
                            <option value="240" <?php selected( $whour_slots, '240', true ); ?>><?php _ex('Each 4 hours', 'Listing type', 'townhub-add-ons') ?></option>
                            <option value="300" <?php selected( $whour_slots, '300', true ); ?>><?php _ex('Each 5 hours', 'Listing type', 'townhub-add-ons') ?></option>
                            <option value="360" <?php selected( $whour_slots, '360', true ); ?>><?php _ex('Each 6 hours', 'Listing type', 'townhub-add-ons') ?></option>
                            
                        </select>
                        
                        <p><?php _ex( 'This will generate availabe time slots for booking every selected time based on working hours of date. You must select working hours and booking form must have date picker to make this work', 'Listing type', 'townhub-add-ons' ); ?></p>
                    </td>
                </tr>
                
            </tbody>
        </table>

        <?php
    }

    public function listing_type_customfields_callback($post, $args){
        $customfields = get_post_meta( $post->ID, ESB_META_PREFIX.'customfields', true );
        ?>
        <h3><?php esc_html_e( 'Custom Fields', 'townhub-add-ons' ); ?></h3>
        <p><?php esc_html_e( 'These fields will appear on submit/edit listing field editor, and used for filter listings if it exists on filter form.', 'townhub-add-ons' ); ?></p>
        <table class="form-table customfields">
            <tbody>

                <tr class="hoz">
                    <th class="w20 align-left"><?php _e( 'Add Fields', 'townhub-add-ons' ); ?></th>
                    <td>
                        <div class="repeater-fields-wrap" data-tmpl="tmpl-ltype-adfield">
                            <div class="repeater-fields">
                                <div class="entry-fields six-cols">
                                    <div><?php esc_html_e( 'Field Type', 'townhub-add-ons' ); ?></div>
                                    <div><?php esc_html_e( 'Field Name', 'townhub-add-ons' ); ?></div>
                                    <div><?php esc_html_e( 'Field Label', 'townhub-add-ons' ); ?></div>
                                    <div><?php esc_html_e( 'Use for filter', 'townhub-add-ons' ); ?></div>
                                    <div><?php esc_html_e( 'Search compare', 'townhub-add-ons' ); ?></div>
                                    <div><?php esc_html_e( 'Search type', 'townhub-add-ons' ); ?></div>
                                </div>
                                <input type="hidden" name="customfields" value="">
                                <?php 
                                if(!empty($customfields)){
                                    $kcount = 0;
                                    foreach ((array)$customfields as $key => $field) {
                                        townhub_addons_get_template_part('templates-inner/add-ltype-field',false, array( 'index'=>$kcount,'name'=>'customfields','field'=>$field ) );
                                        $kcount++;
                                    }
                                }
                                ?>
                            </div>
                            <button class="btn addfield" data-name="customfields" type="button"><?php  esc_html_e( 'Add Field','townhub-add-ons' );?></button>
                        </div>
                        <p><?php echo sprintf( __( 'Read more details about custom field query at: %s', 'townhub-add-ons' ) , '<a href="https://developer.wordpress.org/reference/classes/wp_query/#custom-field-post-meta-parameters" target="_blank">'.esc_html__( 'Custom Field (post meta) Parameters', 'townhub-add-ons' ).'</a>' ); ?></p>
                    </td>
                </tr>
                
            </tbody>
        </table>
        <?php

        add_action( 'admin_footer', function(){
            ?>
            <script type="text/template" id="tmpl-ltype-adfield">
                <?php townhub_addons_get_template_part('templates-inner/add-ltype-field',false, array( 'name'=> 'customfields' ) );?>
            </script>
            <?php
        });
        
    }

    protected function addNewFields($post){

        $customfields = get_post_meta( $post->ID, ESB_META_PREFIX.'customfields', true );
        $cFValues = array();
        // $cSelectOptions = array();
        if( !empty($customfields) && is_array($customfields) ){
            foreach ($customfields as $cfield) {
                $cFValues[] = array(
                    'admin_label' => $cfield['field_label'],
                    'type'        => $cfield['field_type'],
                    'fieldName'   => $cfield['field_name'], //sanitize_title($cfield['field_name']),
                    'label'       => $cfield['field_label'],
                    'desc'        => '',
                    'icon'        => '',
                    'fwidth'      => '6',
                    'removable'   => true,
                    'options'     => array(),
                    'value'       => '',
                    'default'     => '',
                    'required'    => false,
                    'desc_opt'    => false,
                    'noFName'     => true,
                );
                // $cSelectOptions[$cfield['field_name']] = $cfield['field_label'];
            }
        }
        if( !empty($cFValues) ){
            add_filter( 'cth_listing_type_fields', function($val) use ($cFValues){
                return array_merge($val, $cFValues);
            } );
        }
        // if( !empty($cSelectOptions) ){
        //     add_filter( 'cth_ltype_get_custom_fields_select', function($val) use ($cSelectOptions){
        //         return array_merge($val, $cSelectOptions);
        //     } );
        // }
        
    }

    protected function get_custom_fields_select($post){
        $customfields = get_post_meta( $post->ID, ESB_META_PREFIX.'customfields', true );
        $cSelectOptions = array(
            'none'      => _x( 'None', 'Custom field option', 'townhub-add-ons' ),
        );
        if( !empty($customfields) && is_array($customfields) ){
            foreach ($customfields as $cfield) {
                $cSelectOptions[$cfield['field_name']] = $cfield['field_label'];
            }
        }
        return $cSelectOptions;
    }

    public function listing_type_builder_callback($post, $args)
    {
        wp_nonce_field('cth-cpt-fields', '_cth_cpt_nonce', false);
        wp_nonce_field('cth-cpt-ltype', '_cth_cpt_ltype', false);
        $listing_fields = get_post_meta($post->ID, ESB_META_PREFIX . 'listing_fields', true);
        $room_fields    = get_post_meta($post->ID, ESB_META_PREFIX . 'room_fields', true);
        $rating_fields  = get_post_meta($post->ID, ESB_META_PREFIX . 'rating_fields', true);
        $schema_markup  = get_post_meta($post->ID, ESB_META_PREFIX . 'schema_markup', true);

        $this->addNewFields($post);

        wp_localize_script('townhub-react-adminapp', '_townhub_addons_lfields', (array)json_decode($listing_fields));
        wp_localize_script('townhub-react-adminapp', '_townhub_addons_rfields', (array)json_decode($room_fields));
        wp_localize_script('townhub-react-adminapp', '_townhub_addons_frating', (array)json_decode($rating_fields));
        wp_localize_script('townhub-react-adminapp', '_townhub_addons_schema', (array)json_decode($schema_markup));

        wp_localize_script('townhub-react-adminapp', '_townhub_ldefault_fields',  self::default_listing_fields()  );
        wp_localize_script('townhub-react-adminapp', '_townhub_rdefault_fields',  self::default_room_fields()  );
        wp_localize_script('townhub-react-adminapp', '_townhub_calmeta_fields',  self::calendar_field_metas()  );
        // wp_localize_script('townhub-react-adminapp', '_townhub_addons_settings',  self::advanced_settings($post->ID)  );
        wp_localize_script('townhub-react-adminapp', '_townhub_custom_fields',  $this->get_custom_fields_select($post)  );

        
        ?>
        <div id="adminapp"></div>
        <input type="hidden" name="post_status" value="publish">

        <textarea id="listing_type_fields_lfields" name="listing_fields"><?php echo $listing_fields; ?></textarea>
        <textarea id="listing_type_fields_rfields" name="room_fields"><?php echo $room_fields; ?></textarea>

        <textarea id="listing_type_single_layout" name="single_layout"><?php echo get_post_meta($post->ID, ESB_META_PREFIX . 'single_layout', true); ?></textarea>
        <textarea id="listing_type_preview_layout" name="preview_layout"><?php echo get_post_meta($post->ID, ESB_META_PREFIX . 'preview_layout', true); ?></textarea>
        <textarea id="listing_type_filter_layout" name="filter_layout"><?php echo get_post_meta($post->ID, ESB_META_PREFIX . 'filter_layout', true); ?></textarea>

        <textarea id="listing_type_fherosec_layout" name="filter_herosec_layout"><?php echo get_post_meta($post->ID, ESB_META_PREFIX . 'filter_herosec_layout', true); ?></textarea>
        <textarea id="listing_type_fheader_layout" name="filter_header_layout"><?php echo get_post_meta($post->ID, ESB_META_PREFIX . 'filter_header_layout', true); ?></textarea>
        <textarea id="listing_type_sbooking_layout" name="booking_from_layout"><?php echo get_post_meta($post->ID, ESB_META_PREFIX . 'booking_from_layout', true); ?></textarea>
        <textarea id="listing_type_sroom_layout" name="single_room_layout"><?php echo get_post_meta($post->ID, ESB_META_PREFIX . 'single_room_layout', true); ?></textarea>

        <textarea id="listing_type_proom_layout" name="preview_room_layout"><?php echo get_post_meta($post->ID, ESB_META_PREFIX . 'preview_room_layout', true); ?></textarea>

        <textarea id="listing_type_general"><?php echo get_post_meta($post->ID, ESB_META_PREFIX . 'general_field_meta', true); ?></textarea>

        <textarea id="listing_type_schema" name="schema_markup"><?php echo $schema_markup; ?></textarea>
        <?php
$single_css         = '';
        $preview_css        = '';
        $filter_css         = '';
        $filter_hero_css    = '';
        $filter_header_css  = '';
        $single_booking_css = '';
        $single_room_css    = '';
        $preview_room_css   = '';
        $azp_csses          = get_option('azp_csses', false);
        if ($azp_csses !== false && is_array($azp_csses)) {
            if (isset($azp_csses[$post->ID]) && is_array($azp_csses[$post->ID])) {
                if (isset($azp_csses[$post->ID]['single'])) {
                    $single_css = $azp_csses[$post->ID]['single'];
                }

                if (isset($azp_csses[$post->ID]['preview'])) {
                    $preview_css = $azp_csses[$post->ID]['preview'];
                }

                if (isset($azp_csses[$post->ID]['filter'])) {
                    $filter_css = $azp_csses[$post->ID]['filter'];
                }

                if (isset($azp_csses[$post->ID]['fheader'])) {
                    $filter_header_css = $azp_csses[$post->ID]['fheader'];
                }

                if (isset($azp_csses[$post->ID]['fherosec'])) {
                    $filter_hero_css = $azp_csses[$post->ID]['fherosec'];
                }

                if (isset($azp_csses[$post->ID]['sbooking'])) {
                    $single_booking_css = $azp_csses[$post->ID]['sbooking'];
                }

                if (isset($azp_csses[$post->ID]['sroom'])) {
                    $single_room_css = $azp_csses[$post->ID]['sroom'];
                }

                if (isset($azp_csses[$post->ID]['proom'])) {
                    $preview_room_css = $azp_csses[$post->ID]['proom'];
                }

            }
        }

        ?>
        <textarea id="listing_type_single_css" name="ltype_single_css"><?php echo $single_css; ?></textarea>
        <textarea id="listing_type_preview_css" name="ltype_preview_css"><?php echo $preview_css; ?></textarea>
        <textarea id="listing_type_filter_css" name="ltype_filter_css"><?php echo $filter_css; ?></textarea>
        <textarea id="listing_type_fheader_css" name="ltype_fheader_css"><?php echo $filter_header_css; ?></textarea>
        <textarea id="listing_type_fherosec_css" name="ltype_fherosec_css"><?php echo $filter_hero_css; ?></textarea>
        <textarea id="listing_type_sbooking_css" name="ltype_sbooking_css"><?php echo $single_booking_css; ?></textarea>
        <textarea id="listing_type_sroom_css" name="ltype_sroom_css"><?php echo $single_room_css; ?></textarea>
        <textarea id="listing_type_proom_css" name="ltype_proom_css"><?php echo $preview_room_css; ?></textarea>
        <?php
}

    public function save_post($post_id, $post, $update)
    {
        if (!$this->can_save($post_id)) {
            return;
        }

        // Check if our nonce is set.
        if ( ! isset( $_POST['_cth_cpt_ltype'] ) ) {
            return false;
        }
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST['_cth_cpt_ltype'], 'cth-cpt-ltype' ) ) {
            return false;
        }

        if (isset($_POST['listing_fields'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'listing_fields', $_POST['listing_fields']);
        }
        if (isset($_POST['room_fields'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'room_fields', $_POST['room_fields']);
        }
        if (isset($_POST['listing-frating-value'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'rating_fields', $_POST['listing-frating-value']);
        }

        if (isset($_POST['single_layout'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'single_layout', $_POST['single_layout']);
        }
        if (isset($_POST['listing-type-value'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'listing_type', $_POST['listing-type-value']);
        }
        if (isset($_POST['preview_layout'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'preview_layout', $_POST['preview_layout']);
        }
        if (isset($_POST['filter_layout'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'filter_layout', $_POST['filter_layout']);
        }
        if (isset($_POST['filter_herosec_layout'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'filter_herosec_layout', $_POST['filter_herosec_layout']);
        }
        if (isset($_POST['filter_header_layout'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'filter_header_layout', $_POST['filter_header_layout']);
        }
        if (isset($_POST['booking_from_layout'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'booking_from_layout', $_POST['booking_from_layout']);
        }
        if (isset($_POST['single_room_layout'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'single_room_layout', $_POST['single_room_layout']);
        }
        if (isset($_POST['listing-type-id'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'room_type_id', $_POST['listing-type-id']);
        }
        if (isset($_POST['preview_room_layout'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'preview_room_layout', $_POST['preview_room_layout']);
        }
        if (isset($_POST['listing_type_general'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'general_field_meta', $_POST['listing_type_general']);
        }
        if (isset($_POST['listing_type_child_type'])) {
            // var_dump($_POST['listing-type-child-type']);
            // die;
            update_post_meta($post_id, ESB_META_PREFIX . 'child_type_meta', $_POST['listing_type_child_type']);
        }

        if (isset($_POST['schema_markup'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'schema_markup', $_POST['schema_markup']);
        }

        if (isset($_POST['filter_by_type'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'filter_by_type', $_POST['filter_by_type']);
        }else{
            update_post_meta($post_id, ESB_META_PREFIX . 'filter_by_type', '0' );
        }

        if (isset($_POST['dis_free_booking'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'dis_free_booking', $_POST['dis_free_booking']);
        }else{
            update_post_meta($post_id, ESB_META_PREFIX . 'dis_free_booking', '0' );
        }

        if (isset($_POST['price_based'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'price_based', $_POST['price_based'] );
        }
        if (isset($_POST['featured_image'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'featured_image', $_POST['featured_image']);
        }else{
            update_post_meta($post_id, ESB_META_PREFIX . 'featured_image', '' );
        }
        if (isset($_POST['customfields'])) {
            update_post_meta($post_id, ESB_META_PREFIX . 'customfields', $_POST['customfields'] );
        }
        if ( isset($_POST['whour_slots']) ) {
            update_post_meta($post_id, ESB_META_PREFIX . 'whour_slots', $_POST['whour_slots'] );
        }
        

        // new settings
        do_action( 'cth_cpt_listing_type_save_meta_boxes', $post_id, $post, $update );
        

        $azp_csses = get_option('azp_csses', false);
        if ($azp_csses !== false && is_array($azp_csses)) {
            if (!isset($azp_csses[$post_id]) || !is_array($azp_csses[$post_id])) {
                $azp_csses[$post_id] = array();
            }

        } else {
            $azp_csses           = array();
            $azp_csses[$post_id] = array();
        }

        if (isset($_POST['ltype_single_css'])) {
            $azp_csses[$post_id]['single'] = $_POST['ltype_single_css'];
        }
        if (isset($_POST['ltype_preview_css'])) {
            $azp_csses[$post_id]['preview'] = $_POST['ltype_preview_css'];
        }
        if (isset($_POST['ltype_filter_css'])) {
            $azp_csses[$post_id]['filter'] = $_POST['ltype_filter_css'];
        }
        if (isset($_POST['ltype_fheader_css'])) {
            $azp_csses[$post_id]['fheader'] = $_POST['ltype_fheader_css'];
        }
        if (isset($_POST['ltype_fherosec_css'])) {
            $azp_csses[$post_id]['fherosec'] = $_POST['ltype_fherosec_css'];
        }
        if (isset($_POST['ltype_sbooking_css'])) {
            $azp_csses[$post_id]['sbooking'] = $_POST['ltype_sbooking_css'];
        }
        if (isset($_POST['ltype_sroom_css'])) {
            $azp_csses[$post_id]['sroom'] = $_POST['ltype_sroom_css'];
        }
        if (isset($_POST['ltype_proom_css'])) {
            $azp_csses[$post_id]['proom'] = $_POST['ltype_proom_css'];
        }

        update_option('azp_csses', $azp_csses);

        //if(townhub_addons_get_option('azp_css_external') == 'yes'){
        $upload_path = townhub_addons_upload_dirs('azp', 'css');
        $css_file    = $upload_path . DIRECTORY_SEPARATOR . "listing_types.css";
        @file_put_contents($css_file, self::get_azp_css(), LOCK_EX);
        //}
    }

    protected function set_meta_columns()
    {
        $this->has_columns = true;
    }
    public function meta_columns_head($columns)
    {
        $columns['_id'] = __('ID', 'townhub-add-ons');

        return $columns;
    }
    public function meta_columns_content($column_name, $post_ID)
    {
        if ($column_name == '_id') {
            echo $post_ID;
        }

    }

    public static function get_azp_css()
    {
        $csses     = '';
        $azp_csses = get_option('azp_csses', false);
        if ($azp_csses !== false && is_array($azp_csses)) {
            foreach ($azp_csses as $postID => $postCsses) {
                if (is_array($postCsses) && !empty($postCsses)) {
                    $csses .= implode("", self::azp_correct_bgimage(array_values($postCsses)));
                }
            }
        }
        return $csses;
    }
    public static function azp_correct_bgimage($csses = array())
    {
        // $str = 'azpwpreplace_23_full';
        return preg_replace_callback('/azpwpreplace_(\d+)_([a-zA-Z]*)/m',
            function ($matches) {
                // var_dump($matches);
                $size = isset($matches[2]) ? $matches[2] : 'full';
                if (isset($matches[1]) && $matches[1]) {
                    return 'url(' . wp_get_attachment_image_url($matches[1], $size) . ')';
                } else {
                    return '';
                }
            }, $csses);
    }
    public static function advanced_settings($post_id = 0){
        return array(
            'price_based' => get_post_meta( $post_id, ESB_META_PREFIX . 'price_based', true ),
            'filter_by_type' => get_post_meta( $post_id, ESB_META_PREFIX . 'filter_by_type', true ),
        );
    }
    public static function default_fields()
    {

        $fields = array(
            array(
                'admin_label' => _x('Section Title', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'section_title',
                'fieldName'   => 'submit_sec_title',
                'label' => _x('Section Title', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                'show_admin'  => true,
            ),
            array(
                'admin_label' => _x('Title', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'title',
                'label' => _x('Title', 'Listing type field', 'townhub-add-ons'),
                'desc'        => 'Name of your business',
                'icon'        => 'fal fa-briefcase',
                'fwidth'      => '12',
                'removable'   => false,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => true,
                'desc_opt'    => true,
                // 'show_admin'           => true,
            ),
            array(
                'admin_label' => _x('Editor', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'editor',
                'fieldName'   => 'content',
                'label' => _x('Description', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),
            array(
                'admin_label' => _x('Single Image', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'des_image',
                'fieldName'   => 'des_image',
                'label' => _x('Image', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
            ),
            array(
                'admin_label' => _x('Single Text', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'text',
                'fieldName'   => 'des_text',
                'label' => _x('Text', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
            ),
            array(
                'admin_label' => _x('Excerpt', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'textarea',
                'fieldName'   => 'post_excerpt',
                'label' => _x('Excerpt', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),
        );
        return $fields;
    }
    public static function default_listing_fields()
    {

        $fields = array(
            array(
                'admin_label' => _x('Category', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'categories',
                'fieldName'   => 'cats',
                'label' => _x('Type/Category', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '6',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                'max'         => 5,
            ),
            array(
                'admin_label' => _x('Listing Tags', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'taginput',
                'fieldName'   => 'ltags_names',
                'label' => _x('Tags', 'Listing type field', 'townhub-add-ons'),
                'desc'        => 'Enter tags to describe your listing. End each tag with comma or tab key.',
                'icon'        => 'fal fa-tags',
                'fwidth'      => '6',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                'max'     => 15,
            ),
            array(
                'admin_label' => _x('Logo (NEW)', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'image',
                'fieldName'   => 'llogo',
                'label' => _x('Logo', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '6',
                'removable'   => true,
                'options'     => array(
                    'single'    => 'true',
                ),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),
            array(
                'admin_label' => _x('Featured Image', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'image',
                'fieldName'   => 'thumbnail',
                'label' => _x('Featured Image', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '3',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),
            array(
                'admin_label' => _x('Header Media', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'headermedia',
                'fieldName'   => 'headermedia',
                'label' => _x('Header Background', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '6',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),
            array(
                'admin_label' => _x('Images', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'image',
                'fieldName'   => 'images',
                'label' => _x('Images', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '8',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),
            array(
                'admin_label' => _x('Promo Video', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'promovid',
                'fieldName'   => 'promo_video',
                'label' => _x('Promo Video', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '4',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),
            
            array(
                'admin_label' => _x('Address', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'address',
                'label' => _x('Address', 'Listing type field', 'townhub-add-ons'),
                'desc'        => 'Address of your listing',
                'icon'        => 'fal fa-map-marker',
                'fwidth'      => '6',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),
            array(
                'admin_label' => _x('Longitude', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'longitude',
                'label' => _x('Longitude (Drag marker on the map)', 'Listing type field', 'townhub-add-ons'),
                'desc'        => 'Map Longitude',
                'icon'        => 'fal fa-long-arrow-alt-right',
                'fwidth'      => '6',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),
            array(
                'admin_label' => _x('Latitude', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'latitude',
                'label' => _x('Latitude (Drag marker on the map)', 'Listing type field', 'townhub-add-ons'),
                'desc'        => 'Map Latitude',
                'icon'        => 'fal fa-long-arrow-alt-down',
                'fwidth'      => '6',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),
            array(
                'admin_label' => _x('Map', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'gmap',
                'fieldName'   => 'gmap',
                'label' => _x('Map', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),
            array(
                'admin_label' => _x('Country / State / City', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'location',
                'fieldName'   => 'locations',
                'label' => _x('Country / State / City', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),
            array(
                'admin_label' => _x('Locations Select', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'locations',
                'fieldName'   => 'select_locations',
                'label' => _x('Location', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                'multiple'     => true,
                'max'         => 5,
            ),

            array(
                'admin_label' => _x('City', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'lcity',
                'label' => _x('City', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '6',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),

            array(
                'admin_label' => _x('State', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'lstate',
                'label' => _x('State', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '6',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),

            array(
                'admin_label' => _x('Country', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'lcountry',
                'label' => _x('Country', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '6',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),

            array(
                'admin_label' => _x('Address line 2', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'address_line_2',
                'label' => _x('Address line 2', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '6',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),

            array(
                'admin_label' => _x('Zip Code', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'zip_code',
                'label' => _x('Zip Code', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '6',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),

            array(
                'admin_label' => _x('Email', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'email',
                'label' => _x('Email Address', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => 'far fa-envelope',
                'fwidth'      => '4',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),
            array(
                'admin_label' => _x('Phone', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'phone',
                'label' => _x('Phone Number', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => 'far fa-phone',
                'fwidth'      => '4',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),
            array(
                'admin_label' => _x('Whatsapp Number', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'whatsapp',
                'label' => _x('Whatsapp Number', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => 'fab fa-whatsapp',
                'fwidth'      => '4',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),

            array(
                'admin_label' => _x('Website', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'website',
                'label' => _x('Website', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => 'far fa-globe',
                'fwidth'      => '4',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),
            array(
                'admin_label' => _x('Working Hours', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'wkhour',
                'fieldName'   => 'working_hours',
                'label' => _x('Working Hours', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),
            array(
                'admin_label' => _x('Socials', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'socials',
                'fieldName'   => 'socials',
                'label' => _x('Socials', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                'opts'        => array('showLimit'=>true),
                
            ),
            array(
                'admin_label' => _x('Facts', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'facts',
                'fieldName'   => 'facts',
                'label' => _x('Facts', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                'opts'        => array('showLimit'=>true),
                
            ),
            array(
                'admin_label' => _x('Faqs', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'faq',
                'fieldName'   => 'lfaqs',
                'label' => _x('Listing FAQs', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                'opts'        => array('showLimit'=>true),
                
            ),

            array(
                'admin_label' => _x('Event Date', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'eventdate',
                'fieldName'   => 'eventdate',
                'label' => _x('Event Date', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                'is_24h'        => false,
                'dformat'        => 'MM/DD/YYYY',
            ),

            array(
                'admin_label' => _x('Event Tickets', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'evticket',
                'fieldName'   => 'tickets',
                'label' => _x('Event Tickets', 'Listing type field', 'townhub-add-ons'),
                'desc'        => __( 'For creating tickets', 'townhub-add-ons' ),
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                'addtext'           => __( 'Add tickets', 'townhub-add-ons' ),
                'name_title'        => __( 'Ticket name', 'townhub-add-ons' ),
                'price_title'       => __( 'Price', 'townhub-add-ons' ),
                'available_title'   => __( 'Quantity available', 'townhub-add-ons' ),
                'desc_title'        => __( 'Description', 'townhub-add-ons' ),
                'opts'              => array('showLimit'=>true),
            ),

            array(
                'admin_label' => _x('Trainers/Speakers', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'member',
                'fieldName'   => 'lmember',
                'label' => _x('Trainers/Speakers', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                'opts'        => array('showLimit'=>true),
                
            ),

            

            array(
                'admin_label' => _x('Restaurant Menu', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'resmenu',
                'fieldName'   => 'resmenus',
                'label' => _x('Restaurant Menu', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                'opts'        => array('showLimit'=>true),
                
            ),

            

            array(
                'admin_label' => _x('File Uploader', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'file',
                'fieldName'   => 'menu_pdf',
                'label' => _x('Menu PDF', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => false,
                
            ),

            array(
                'admin_label' => _x('Rooms/Products', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'rooms',
                'fieldName'   => 'listing_rooms',
                'label' => _x('Hotel Rooms', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => false,
                
            ),

            // array(
            //     'admin_label' => _x('Price', 'Listing type field', 'townhub-add-ons'),
            //     'type'        => 'input',
            //     'fieldName'   => '_price',
            //     'label' => _x('Price', 'Listing type field', 'townhub-add-ons'),
            //     'desc'        => '',
            //     'icon'        => 'fal fa-users',
            //     'fwidth'      => '12',
            //     'removable'   => true,
            //     'options'     => array(),
            //     'value'       => '',
            //     'default'     => '',
            //     'required'    => false,
            //     'desc_opt'    => false,
            //     // 'show_admin'           => true,
            //     'noFName'     => true,
                
            // ),


            array(
                'admin_label' => _x('Calendar', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'calendar_metas',
                'fieldName'   => 'listing_dates',
                'label' => _x('Available Dates', 'Listing type field', 'townhub-add-ons'),
                'desc'        => __( 'Availability dates', 'townhub-add-ons' ),
                'icon'        => 'fa fa-calendar-alt',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array('showing'=>1, 'max'=>12, 'show_metas'=>true),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                'fields'        => array(
                    array(
                        'title'=> 'Listing/Room Price',
                        'type'=> 'text',
                        'name'=> 'price',
                    ),
                    
                )
            ),

            array(
                'admin_label' => _x('iCal Import Url', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'ical_url',
                'label' => _x('iCal Import Url', 'Listing type field', 'townhub-add-ons'),
                'desc'        => 'Enter external iCal url to automatically sync dates to availability calendar',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),

            

            array(
                'admin_label' => _x('Facilities', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'feature',
                'fieldName'   => 'features',
                'label' => _x('Facilities', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),

            array(
                'admin_label' => _x('Listing Quantities', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'quantities',
                'label' => _x('Listing Quantities', 'Listing type field', 'townhub-add-ons'),
                'desc'        => 'Set available quantity for per listing price based',
                'icon'        => '',
                'fwidth'      => '3',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),

            array(
                'admin_label' => _x('Max Guests', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'max_guests',
                'label' => _x('Guests Capability', 'Listing type field', 'townhub-add-ons'),
                'desc'        => 'Maximum of guests',
                'icon'        => '',
                'fwidth'      => '3',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),

            
            array(
                'admin_label' => _x('Price Range', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'select',
                'fieldName'   => 'price_range',
                'label' => _x('Price Range', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(
                    array('label' => 'None', 'value' => 'none'),
                    array('label' => 'Inexpensive', 'value' => 'inexpensive'),
                    array('label' => 'Moderate', 'value' => 'moderate'),
                    array('label' => 'Pricey', 'value' => 'pricey'),
                    array('label' => 'Ultra High', 'value' => 'ultrahigh'),
                ),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),


            array(
                'admin_label' => _x('Listing Price', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => '_price',
                'label' => _x('Listing Price', 'Listing type field', 'townhub-add-ons'),
                'desc'        => 'Price per listing/adult',
                'icon'        => 'fal fa-dollar-sign',
                'fwidth'      => '3',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),
            array(
                'admin_label' => _x('Children Price', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'children_price',
                'label' => _x('Children Price', 'Listing type field', 'townhub-add-ons'),
                'desc'        => 'Price per children',
                'icon'        => 'fal fa-child',
                'fwidth'      => '3',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),
            array(
                'admin_label' => _x('Infant Price', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'infant_price',
                'label' => _x('Infant Price', 'Listing type field', 'townhub-add-ons'),
                'desc'        => 'Price per Infant',
                'icon'        => 'fal fa-baby',
                'fwidth'      => '3',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),

            array(
                'admin_label' => _x('Max Price', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'price_to',
                'label' => _x('Price To', 'Listing type field', 'townhub-add-ons'),
                'desc'        => __( 'Set max price for listing price range. Used with Listing Price field.', 'townhub-add-ons' ),
                'icon'        => 'fal fa-dollar-sign',
                'fwidth'      => '3',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),

            array(
                'admin_label' => _x('Sale Off', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'sale_off',
                'label' => _x('Sale Off', 'Listing type field', 'townhub-add-ons'),
                'desc'        => __( 'Set sale off percent for showing on listing card.', 'townhub-add-ons' ),
                'icon'        => 'fal fa-dollar-sign',
                'fwidth'      => '3',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),
            

            array(
                'admin_label' => _x('Coupon Codes', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'coupon',
                'fieldName'   => 'lcoupon',
                'label' => _x('Coupon Codes', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),
            array(
                'admin_label' => _x('Extra Fees', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'services',
                'fieldName'   => 'lservices',
                'label' => _x('Additional Services Fees', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),
            array(
                'admin_label' => _x('Time Slots', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'slots',
                'fieldName'   => 'time_slots',
                'label' => _x('Time Slots', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array('showing'=>1, 'max'=>12, 'show_metas'=>true),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                'fields'        => array(
                    array(
                        'title'=> 'Max Guests',
                        'type'=> 'text',
                        'name'=> 'guests',
                    ),
                    
                )
            ),

            array(
                'admin_label' => _x('Minimum nights', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'number',
                'fieldName'   => 'min_nights',
                'label' => _x('Minimum nights required for booking', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '2',
                'default'     => '2',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),

            array(
                'admin_label' => _x('SAVE AS PENDING', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'switch',
                'fieldName'   => 'save_as_pending',
                'label' => _x('Save listing as pending, so admin will not publish your listing', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => 'yes',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                'hideVField'    => true,
                'hideDVField'   => true,
                
            ),

            array(
                'admin_label' => _x('Hide Working Hours', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'switch',
                'fieldName'   => 'hide_wkhours',
                'label' => _x('Hide Working Hours', 'Listing type field', 'townhub-add-ons'),
                'desc'        => 'This field allow author can choose whether to use Working Hours widget or not',
                'icon'        => 'fal fa-clock',
                'fwidth'      => '4',
                'removable'   => true,
                // 'options'     => array(
                //  array(
                //      'value'     => 'yes',
                //      'label'     => 'Check this if your listing is dog friendly',
                //  )
                // ),
                'value'       => 'yes',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
                'hideVField'    => true,
                'hideDVField'   => true,
                
            )

        );


        $custom_fiels = (array)apply_filters( 'cth_listing_type_fields', array() );

        return array_merge( self::default_fields() , $fields, $custom_fiels);


    }
    public static function default_room_fields()
    {
        $fields = array(
            
            array(
                'admin_label' => _x('Featured Image', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'image',
                'fieldName'   => 'thumbnail',
                'label' => _x('Featured Image', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '3',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),
            
            array(
                'admin_label' => _x('Images', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'image',
                'fieldName'   => 'images',
                'label' => _x('Images', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '9',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),
            array(
                'admin_label' => _x('Facts', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'facts',
                'fieldName'   => 'facts',
                'label' => _x('Facts', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),
            array(
                'admin_label' => _x('Price', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => '_price',
                'label' => _x('Price', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => 'fal fa-dollar-sign',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),

            array(
                'admin_label' => _x('Children Price', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'children_price',
                'label' => _x('Children Price', 'Listing type field', 'townhub-add-ons'),
                'desc'        => 'Price per children',
                'icon'        => 'fal fa-child',
                'fwidth'      => '3',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),
            // array(
            //     'admin_label' => _x('Infant Price', 'Listing type field', 'townhub-add-ons'),
            //     'type'        => 'input',
            //     'fieldName'   => 'infant_price',
            //     'label' => _x('Infant Price', 'Listing type field', 'townhub-add-ons'),
            //     'desc'        => 'Price per Infant',
            //     'icon'        => 'fal fa-baby',
            //     'fwidth'      => '3',
            //     'removable'   => true,
            //     'options'     => array(),
            //     'value'       => '',
            //     'default'     => '',
            //     'required'    => false,
            //     'desc_opt'    => true,
            //     // 'show_admin'           => true,
            //     'noFName'     => true,
                
            // ),

            array(
                'admin_label' => _x('Room Quantity', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'quantity',
                'label' => _x('Room Quantity', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => 'fal fa-layer-plus',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),

            array(
                'admin_label' => _x('Adult capacity', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'adults',
                'label' => _x('Adult capacity', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => 'fal fa-user-plus',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),


            array(
                'admin_label' => _x('Children capacity', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'children',
                'label' => _x('Children capacity', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => 'fal fa-child',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),

            array(
                'admin_label' => _x('Calendar', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'calendar_metas',
                'fieldName'   => 'calendar',
                'label' => _x('Available Dates', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array('showing'=>1, 'max'=>12, 'show_metas'=>true),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                'fields'        => array(
                    array(
                        'title'=> 'Price',
                        'type'=> 'text',
                        'name'=> 'price',
                    ),
                    array(
                        'title'=> 'Room quantity',
                        'type'=> 'text',
                        'name'=> 'quantity',
                    )
                )
            ),

            array(
                'admin_label' => _x('iCal Import Url', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'input',
                'fieldName'   => 'ical_url',
                'label' => _x('iCal Import Url', 'Listing type field', 'townhub-add-ons'),
                'desc'        => 'Enter external iCal url to automatically sync dates to availability calendar',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => true,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),

            array(
                'admin_label' => _x('Facilities', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'feature',
                'fieldName'   => 'features',
                'label' => _x('Facilities', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),

            array(
                'admin_label' => _x('Minimum quantities/adults', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'number',
                'fieldName'   => 'min_adults',
                'label' => _x('Minimum quantities/adults required for booking', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '2',
                'default'     => '2',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),

            array(
                'admin_label' => _x('WooCommerce Product Category', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'woocats',
                'fieldName'   => 'cats',
                'label' => _x('Category', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '6',
                'removable'   => true,
                'options'     => array(),
                'value'       => '',
                'default'     => '',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
            ),

            array(
                'admin_label' => _x('Minimum nights', 'Listing type field', 'townhub-add-ons'),
                'type'        => 'number',
                'fieldName'   => 'min_nights',
                'label' => _x('Minimum nights required for booking', 'Listing type field', 'townhub-add-ons'),
                'desc'        => '',
                'icon'        => '',
                'fwidth'      => '12',
                'removable'   => true,
                'options'     => array(),
                'value'       => '2',
                'default'     => '2',
                'required'    => false,
                'desc_opt'    => false,
                // 'show_admin'           => true,
                'noFName'     => true,
                
            ),


        );

        $custom_fiels = (array)apply_filters( 'cth_listing_type_room_fields', array() );

        return array_merge( self::default_fields() , $fields, $custom_fiels);
    }

    public static function calendar_field_metas(){
        $fields = array(
            array(
                'title'     => __( 'Max Guests', 'townhub-add-ons' ),
                'type'      => 'text',
                'name'      => 'guests',
            ),
            array(
                'title'     => __( 'Adult Price - Deprecated', 'townhub-add-ons' ),
                'type'      => 'text',
                'name'      => 'price_adult',
            ),
            array(
                'title'     => __( 'Children Price', 'townhub-add-ons' ),
                'type'      => 'text',
                'name'      => 'price_children',
            ),
            array(
                'title'     => __( 'Infant Price', 'townhub-add-ons' ),
                'type'      => 'text',
                'name'      => 'price_infant',
            ),
            array(
                'title'     => __( 'Event Start Time', 'townhub-add-ons' ),
                'type'      => 'time',
                'name'      => 'start_time',
            ),
            array(
                'title'     => __( 'Event End Date', 'townhub-add-ons' ),
                'type'      => 'datetime',
                'name'      => 'end_date',
            ),
            array(
                'title'     => __( 'Listing/Room Price', 'townhub-add-ons' ),
                'type'      => 'text',
                'name'      => 'price',
            ),
            array(
                'title'     => __( 'Room quantity', 'townhub-add-ons' ),
                'type'      => 'text',
                'name'      => 'quantity',
            ),
        );

        return (array)apply_filters( 'cth_calendar_field_metas', $fields );
    }
    public function lbook_new_auth_temp($templ, $booking_id, $listing_id){
        $ltype_id = get_post_meta( $listing_id, ESB_META_PREFIX.'listing_type_id', true );
        if( !empty($ltype_id) ){
            $ptempl = get_post_meta( $ltype_id, ESB_META_PREFIX.'auth_lbook_new', true );
            if( !empty($ptempl) ){
                $templ = $ptempl;
            }
        }
        return $templ;
    }
    public function lbook_new_customer_temp($templ, $booking_id, $listing_id){
        $ltype_id = get_post_meta( $listing_id, ESB_META_PREFIX.'listing_type_id', true );
        if( !empty($ltype_id) ){
            $ptempl = get_post_meta( $ltype_id, ESB_META_PREFIX.'customer_lbook_new', true );
            if( !empty($ptempl) ){
                $templ = $ptempl;
            }
        }
        return $templ;
    }
    public function lbook_approved_auth_temp($templ, $booking_id, $listing_id){
        $ltype_id = get_post_meta( $listing_id, ESB_META_PREFIX.'listing_type_id', true );
        if( !empty($ltype_id) ){
            $ptempl = get_post_meta( $ltype_id, ESB_META_PREFIX.'auth_lbook_approved', true );
            if( !empty($ptempl) ){
                $templ = $ptempl;
            }
        }
        return $templ;
    }
    public function lbook_approved_customer_temp($templ, $booking_id, $listing_id){
        $ltype_id = get_post_meta( $listing_id, ESB_META_PREFIX.'listing_type_id', true );
        if( !empty($ltype_id) ){
            $ptempl = get_post_meta( $ltype_id, ESB_META_PREFIX.'customer_lbook_approved', true );
            if( !empty($ptempl) ){
                $templ = $ptempl;
            }
        }
        return $templ;
    }
    public function lbook_cancel_auth_temp($templ, $booking_id, $listing_id){
        $ltype_id = get_post_meta( $listing_id, ESB_META_PREFIX.'listing_type_id', true );
        if( !empty($ltype_id) ){
            $ptempl = get_post_meta( $ltype_id, ESB_META_PREFIX.'auth_lbook_cancel', true );
            if( !empty($ptempl) ){
                $templ = $ptempl;
            }
        }
        return $templ;
    }
    public function lbook_cancel_customer_temp($templ, $booking_id, $listing_id){
        $ltype_id = get_post_meta( $listing_id, ESB_META_PREFIX.'listing_type_id', true );
        if( !empty($ltype_id) ){
            $ptempl = get_post_meta( $ltype_id, ESB_META_PREFIX.'customer_lbook_cancel', true );
            if( !empty($ptempl) ){
                $templ = $ptempl;
            }
        }
        return $templ;
    }

}

new Esb_Class_Listing_Type_CPT();

// get active plan for setting selection
function townhub_addons_get_listing_types()
{
    $results = array();

    $post_args = array(
        'post_type'      => 'listing_type',

        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',

        'post_status'    => 'any',
    );

    $posts = get_posts($post_args);
    if ($posts) {
        foreach ($posts as $post) {
            $results[$post->ID] = apply_filters('the_title', $post->post_title, $post->ID);

        }
    }

    return $results;
}

// get active plan for setting selection
// function listing_type_id($listing_type_id = 0)
// {
//     $fields = '[]';
//     if (is_numeric($listing_type_id) && (int) $listing_type_id > 0) {
//         $fields = get_post_meta($listing_type_id, ESB_META_PREFIX . 'listing_fields', true);
//     } else {
//         $fields = get_post_meta(esb_addons_get_wpml_option('default_listing_type', 'listing_type'), ESB_META_PREFIX . 'listing_fields', true);
//     }

//     $fields = json_decode($fields);
//     if (!is_array($fields)) {
//         return array();
//     }

//     return $fields;
// }
// get active plan for setting selection
function townhub_addons_get_listing_type_fields_obj($listing_type_id = 0, $with_countries = false, $with_feature = false, $with_cats = false, $with_locs = false)
{
    $fields = '[]';
    if (is_numeric($listing_type_id) && (int) $listing_type_id > 0) {
        $fields = get_post_meta($listing_type_id, ESB_META_PREFIX . 'listing_fields', true);
    } else {
        $fields = get_post_meta(esb_addons_get_wpml_option('default_listing_type', 'listing_type'), ESB_META_PREFIX . 'listing_fields', true);
    }

    // return $fields;

    $fields = json_decode($fields);
    if (!is_array($fields)) {
        return array();
    }

    // modify country
    if ($with_countries) {
        foreach ($fields as $key => $field) {
            if ('location' == $field->type) {
                $field->countries = townhub_addons_get_google_contry_codes();

                $fields[$key] = $field;
                break;
            }
        }
    }
    if ($with_cats) {
        foreach ($fields as $key => $field) {
            if ('categories' == $field->type || ('select' == $field->type && $field->fieldName == 'cats')) {
                // $field->options =
                if ( isset($field->choises) && is_array($field->choises) && !empty($field->choises) ) {
                    foreach ($field->choises as $fid) {
                        if ($term = get_term($fid, 'listing_cat')) {
                            $term->name = html_entity_decode( $term->name, ENT_QUOTES );
                            $field->cats[] = $term;
                            
                            if( townhub_addons_get_option('hide_sub_cats') == 'yes' ) continue;
                            // get child terms
                            $child_terms = get_terms(array(
                                'taxonomy'   => 'listing_cat',
                                'hide_empty' => false,
                                'parent'     => $term->term_id,
                            ));
                            if ($child_terms && !is_wp_error($child_terms)) {
                                foreach ($child_terms as $cterm) {
                                    $cterm->name = html_entity_decode( $cterm->name, ENT_QUOTES );
                                    $field->cats[] = $cterm;

                                    // get child terms
                                    $child_terms = get_terms(array(
                                        'taxonomy'   => 'listing_cat',
                                        'hide_empty' => false,
                                        'parent'     => $cterm->term_id,
                                    ));
                                    if ($child_terms && !is_wp_error($child_terms)) {
                                        foreach ($child_terms as $cterm) {
                                            $cterm->name = html_entity_decode( $cterm->name, ENT_QUOTES );
                                            $field->cats[] = $cterm;

                                            // get child terms
                                            $child_terms = get_terms(array(
                                                'taxonomy'   => 'listing_cat',
                                                'hide_empty' => false,
                                                'parent'     => $cterm->term_id,
                                            ));
                                            if ($child_terms && !is_wp_error($child_terms)) {
                                                foreach ($child_terms as $cterm) {
                                                    $cterm->name = html_entity_decode( $cterm->name, ENT_QUOTES );
                                                    $field->cats[] = $cterm;
                                                }
                                            }
                                            // end childs 3

                                        }
                                    }
                                    // end childs 2

                                }
                            }
                            // end childs 1

                        }
                        // $field->cats[] = get_term( $fid, 'listing_cat');
                    }
                }
                $fields[$key] = $field;
                break;
            }
        }
    }
    if ($with_locs) {
        foreach ($fields as $key => $field) {
            if ('locations' == $field->type) {
                // $field->options =
                if ( isset($field->choises) && is_array($field->choises) && !empty($field->choises) ) {
                    foreach ($field->choises as $fid) {
                        if ($term = get_term($fid, 'listing_location')) {
                            $term->name = html_entity_decode( $term->name, ENT_QUOTES );
                            $field->locs[] = $term;
                            // get child terms
                            $child_terms = get_terms(array(
                                'taxonomy'   => 'listing_location',
                                'hide_empty' => false,
                                'parent'     => $term->term_id,
                            ));
                            if ($child_terms && !is_wp_error($child_terms)) {
                                foreach ($child_terms as $cterm) {
                                    $cterm->name = html_entity_decode( $cterm->name, ENT_QUOTES );
                                    $field->locs[] = $cterm;

                                    // get child terms
                                    $child_terms = get_terms(array(
                                        'taxonomy'   => 'listing_location',
                                        'hide_empty' => false,
                                        'parent'     => $cterm->term_id,
                                    ));
                                    if ($child_terms && !is_wp_error($child_terms)) {
                                        foreach ($child_terms as $cterm) {
                                            $cterm->name = html_entity_decode( $cterm->name, ENT_QUOTES );
                                            $field->locs[] = $cterm;

                                            // get child terms
                                            $child_terms = get_terms(array(
                                                'taxonomy'   => 'listing_location',
                                                'hide_empty' => false,
                                                'parent'     => $cterm->term_id,
                                            ));
                                            if ($child_terms && !is_wp_error($child_terms)) {
                                                foreach ($child_terms as $cterm) {
                                                    $cterm->name = html_entity_decode( $cterm->name, ENT_QUOTES );
                                                    $field->locs[] = $cterm;
                                                }
                                            }
                                            // end childs 3

                                        }
                                    }
                                    // end childs 2

                                }
                            }
                            // end childs 1
                        }
                    }
                }
                $fields[$key] = $field;
                break;
            }
        }
    }
    if ($with_feature) {
        foreach ($fields as $key => $field) {
            if ('feature' == $field->type) {
                // $field->options =
                if ( isset($field->choises) && is_array($field->choises) && !empty($field->choises) ) {
                    foreach ($field->choises as $fid) {
                        if ($term = get_term($fid, 'listing_feature')) {
                            $term->name = html_entity_decode( $term->name, ENT_QUOTES );
                            $field->features[] = $term;
                        }

                    }
                }
                // $field->feature = townhub_addons_get_listing_features();

                $fields[$key] = $field;
                break;
            }
        }
    }

    return $fields;
}
function townhub_addons_get_rooms_type_fields_obj($listing_type_id = 0, $with_feature = false, $with_cats = false)
{
    $fields = '[]';
    if (is_numeric($listing_type_id) && (int) $listing_type_id > 0) {
        $fields = get_post_meta($listing_type_id, ESB_META_PREFIX . 'room_fields', true);
    } else {
        $fields = get_post_meta(esb_addons_get_wpml_option('default_listing_type', 'listing_type'), ESB_META_PREFIX . 'room_fields', true);
    }

    $fields = json_decode($fields);
    if (!is_array($fields)) {
        return array();
    }

    if ($with_cats) {
        foreach ($fields as $key => $field) {
            if ( 'woocats' == $field->type ) {
                // $field->options =
                if ( isset($field->choises) && is_array($field->choises) && !empty($field->choises) ) {
                    foreach ($field->choises as $fid) {
                        if ($term = get_term($fid, 'product_cat')) {
                            $term->name = html_entity_decode( $term->name, ENT_QUOTES );
                            $field->cats[] = $term;
                            
                            if( townhub_addons_get_option('hide_sub_cats') == 'yes' ) continue;
                            // get child terms
                            $child_terms = get_terms(array(
                                'taxonomy'   => 'product_cat',
                                'hide_empty' => false,
                                'parent'     => $term->term_id,
                            ));
                            if ($child_terms && !is_wp_error($child_terms)) {
                                foreach ($child_terms as $cterm) {
                                    $cterm->name = html_entity_decode( $cterm->name, ENT_QUOTES );
                                    $field->cats[] = $cterm;

                                    // get child terms
                                    $child_terms = get_terms(array(
                                        'taxonomy'   => 'product_cat',
                                        'hide_empty' => false,
                                        'parent'     => $cterm->term_id,
                                    ));
                                    if ($child_terms && !is_wp_error($child_terms)) {
                                        foreach ($child_terms as $cterm) {
                                            $cterm->name = html_entity_decode( $cterm->name, ENT_QUOTES );
                                            $field->cats[] = $cterm;

                                            // get child terms
                                            $child_terms = get_terms(array(
                                                'taxonomy'   => 'product_cat',
                                                'hide_empty' => false,
                                                'parent'     => $cterm->term_id,
                                            ));
                                            if ($child_terms && !is_wp_error($child_terms)) {
                                                foreach ($child_terms as $cterm) {
                                                    $cterm->name = html_entity_decode( $cterm->name, ENT_QUOTES );
                                                    $field->cats[] = $cterm;
                                                }
                                            }
                                            // end childs 3

                                        }
                                    }
                                    // end childs 2

                                }
                            }
                            // end childs 1

                        }
                        // $field->cats[] = get_term( $fid, 'listing_cat');
                    }
                }
                $fields[$key] = $field;
                break;
            }
        }
    }

    if ($with_feature) {
        foreach ($fields as $key => $field) {
            if ('feature' == $field->type) {
                // $field->options =
                if ( isset($field->choises) && is_array($field->choises) && !empty($field->choises)) {
                    foreach ($field->choises as $fid) {
                        if ($term = get_term($fid, 'listing_feature')) {
                            $term->name = html_entity_decode( $term->name, ENT_QUOTES );
                            $field->features[] = $term;
                        }

                        // $field->features[] = get_term( $fid, 'listing_feature');
                    }
                }
                // $field->feature = townhub_addons_get_listing_features();

                $fields[$key] = $field;
                break;
            }
        }
    }
    return $fields;
}

// for saving listing
function townhub_addons_post_object_fields()
{
    return array(
        'title',
        'tags',
        'cats',
        'features',
        'locations',
        'content',
        'thumbnail',
        'working_hours',

        'ltags_names',
        'select_locations',
        'post_excerpt',

        '_price',
    );
}

function townhub_addons_get_listing_type_fields($listing_type_id = 0, $room_fields = false)
{
    if ($room_fields) {
        $fields_obj_arr = townhub_addons_get_rooms_type_fields_obj($listing_type_id);
    } else {
        $fields_obj_arr = townhub_addons_get_listing_type_fields_obj($listing_type_id);
    }

    $fields = array();

    $ignore_types = array('section_title');

    if (is_array($fields_obj_arr) && !empty($fields_obj_arr)) {
        foreach ($fields_obj_arr as $field) {
            if (is_object($field) && !in_array($field->type, $ignore_types)) {
                switch ($field->type) {
                    case 'input':
                    case 'radio':
                    case 'switch':
                    case 'select':
                    case 'textarea':
                    case 'editor':
                    case 'ltype':
                    case 'calendar':
                        $fields[$field->fieldName] = 'text';
                        break;
                    case 'calendar_metas':
                        // $fields[$field->fieldName] = 'calendar_metas';
                        $fields[$field->fieldName]                 = 'text';
                        $fields[$field->fieldName . '_metas']      = 'array';
                        $fields[$field->fieldName . '_show_metas'] = 'text';

                        break;
                    case 'image':
                    case 'socials':
                    case 'facts':
                    case 'add_rooms':
                    case 'checkbox':
                    case 'muti':
                    case 'gallery_imgs':
                    case 'header_imgs':
                    case 'faq':
                    // case 'calendar':
                    case 'feature':
                    case 'wkhour':
                    case 'lcoupon':
                    case 'lservices':
                    case 'lmember':
                    case 'services':
                    case 'member':
                    case 'coupon':
                    case 'slots':
                    case 'promovid':
                    case 'resmenu':
                    case 'headermedia':
                    case 'evticket':

                        $fields[$field->fieldName] = 'array';
                        break;
                    case 'eventdate':

                        $fields[$field->fieldName] = 'text';
                        $fields[$field->fieldName. '_start'] = 'text';
                        $fields[$field->fieldName. '_end'] = 'text';
                        break;
                    case 'raw_html':
                        $fields[$field->fieldName] = 'raw_text';
                        break;
                    default:
                        $fields[$field->fieldName] = 'text';
                        break;
                }
            }
        }
    }

    return $fields;
}

function townhub_addons_get_listing_type_fields_meta($listing_type_id = 0, $room_fields = false)
{
    $fields      = townhub_addons_get_listing_type_fields($listing_type_id, $room_fields);
    $meta_fields = array();
    if (!empty($fields)) {
        $ignore_fields = townhub_addons_post_object_fields();

        foreach ((array) $fields as $fname => $ftype) {
            if (!in_array($fname, $ignore_fields)) {
                $meta_fields[$fname] = $ftype;
            }

        }
    }

    return $meta_fields;

}

function townhub_addons_get_rating_fields($listing_type_id = 0)
{
    // $fields = '[]';
    if (is_numeric($listing_type_id) && (int) $listing_type_id > 0) {
        $fields = get_post_meta($listing_type_id, ESB_META_PREFIX . 'rating_fields', true);
    } else {
        $fields = get_post_meta(esb_addons_get_wpml_option('default_listing_type', 'listing_type'), ESB_META_PREFIX . 'rating_fields', true);
    }

    $fields = json_decode($fields, true);
    if (!is_array($fields)) {
        return array();
    }

    return $fields;
}
