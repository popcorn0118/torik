<?php
/* add_ons_php */

class Esb_Class_Invoice_CPT extends Esb_Class_CPT {
    protected $name = 'cthinvoice';

    protected function init(){
        parent::init();

        $logged_in_ajax_actions = array(
            'view_invoice',
        );
        foreach ($logged_in_ajax_actions as $action) {
            $funname = str_replace('townhub_addons_', '', $action);
            add_action('wp_ajax_'.$action, array( $this, $funname ));
        }
        do_action( $this->name.'_cpt_init_after' );
    }

    public function register(){

        $labels = array( 
            'name' => __( 'Invoice', 'townhub-add-ons' ),
            'singular_name' => __( 'Invoice', 'townhub-add-ons' ),
            'add_new' => __( 'Add New Invoice', 'townhub-add-ons' ),
            'add_new_item' => __( 'Add New Invoice', 'townhub-add-ons' ),
            'edit_item' => __( 'Edit Invoice', 'townhub-add-ons' ),
            'new_item' => __( 'New Invoice', 'townhub-add-ons' ),
            'view_item' => __( 'View Invoice', 'townhub-add-ons' ),
            'search_items' => __( 'Search Invoices', 'townhub-add-ons' ),
            'not_found' => __( 'No Invoices found', 'townhub-add-ons' ),
            'not_found_in_trash' => __( 'No Invoices found in Trash', 'townhub-add-ons' ),
            'parent_item_colon' => __( 'Parent Invoice:', 'townhub-add-ons' ),
            'menu_name' => __( 'Listing Invoices', 'townhub-add-ons' ),
        );

        $args = array( 
            'labels' => $labels,
            'hierarchical' => false,
            'description' => __( 'Subscription invoice', 'townhub-add-ons' ),
            'supports' => array( '' ),
            'taxonomies' => array(),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,//default from show_ui
            'menu_position' => 25,
            'menu_icon' => 'dashicons-media-text',
            'show_in_nav_menus' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'has_archive' => false,
            'query_var' => false,
            'can_export' => false,
            'rewrite' => array( 'slug' => __('cthinvoice','townhub-add-ons') ),
            'capability_type' => 'post',

            'capabilities' => array(
                'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout
            ),
            'map_meta_cap' => true, // Set to `false`, if users are not allowed to edit/delete existing posts
        );


        register_post_type( $this->name, $args );
    }
    protected function set_meta_columns(){
        $this->has_columns = true;
    }
    public function meta_columns_head($columns){
        unset($columns['comments']);
        $columns['_plan']   = __('Plan','townhub-add-ons');
        $columns['_end_date']   = __('Expire Date','townhub-add-ons');
        $columns['_payment']       =  __('Payment','townhub-add-ons');
        $columns['_amount']   = __('Total','townhub-add-ons');
    
        return $columns;
    }
    public function meta_columns_content($column_name, $post_ID){
        if ($column_name == '_payment') {
            echo '<strong>'.townhub_addons_payment_names(get_post_meta( $post_ID, ESB_META_PREFIX.'payment', true )).'</strong>';
        }
        if ($column_name == '_amount') {
            echo '<strong>'.townhub_addons_get_price_formated( get_post_meta( $post_ID, ESB_META_PREFIX.'price_total', true ) ).'</strong>';
            
        }
        
        if ($column_name == '_plan') {
            echo '<strong>'.get_post_meta( $post_ID, ESB_META_PREFIX.'plan_title', true).'</strong>'; 
        }
        // if ($column_name == 'from_date') {
        //     echo '<strong>'.get_post_meta( $post_ID, ESB_META_PREFIX.'from_date', true ).'</strong>';
            
        // }
        if ($column_name == '_end_date') {
            echo '<strong>'.get_post_meta( $post_ID, ESB_META_PREFIX.'end_date', true ).'</strong>';
        }
    }

    protected function set_meta_boxes(){
        $this->meta_boxes = array(
            'details'       => array(
                'title'         => __( 'Invoice Details', 'townhub-add-ons' ),
                'context'       => 'normal', // normal - side - advanced
                'priority'       => 'core', // default - high - core - low
                'callback_args'       => array(),
            ),
            'inreview'       => array(
                'title'         => __( 'Review Invoice', 'townhub-add-ons' ),
                'context'       => 'side', // normal - side - advanced
                'priority'       => 'high', // default - high - core - low
                'callback_args'       => array(),
            )
        );
    }

    public function cthinvoice_inreview_callback($post, $args){
        $url = add_query_arg( array(
            '_wpnonce'=> wp_create_nonce('cth_view_invoice'),
            'thview' => 'invoice',
            'invid' => $post->ID,
        ), home_url() );
        ?>
        <p><a href="<?php echo $url; ?>" class="button"><?php _ex( 'View', 'Invoice', 'townhub-add-ons' ); ?></a></p>
        <?php   
    }

    public function cthinvoice_details_callback($post, $args){
        wp_nonce_field( 'cth-cpt-fields', '_cth_cpt_nonce' );
        ?>
        <table class="form-table cth-table table-invoice-details">
            <tbody>
                <tr>
                    <td class="w40" colspan="2"><?php _e( 'Date', 'townhub-add-ons' ); ?></td>
                    <td class="w60 text-bold" colspan="3"><?php echo Esb_Class_Date::i18n( $post->post_date ); ?></td>
                </tr>
                <tr>
                    <td class="w40" colspan="2"><?php _e( 'Subscribed with', 'townhub-add-ons' ); ?></td>
                    <td class="w40 text-bold" colspan="3"><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'user_name', true ); ?></td>
                </tr>
                <tr>
                    <td class="w40" colspan="2"><?php _e( 'Charged via', 'townhub-add-ons' ); ?></td>
                    <td class="w40 text-bold" colspan="3"><?php echo townhub_addons_payment_names(get_post_meta( $post->ID, ESB_META_PREFIX.'payment', true )); ?></td>
                </tr>
                <tr>
                    <td class="w40" colspan="2"><?php _e( 'Expiration date', 'townhub-add-ons' ); ?></td>
                    <td class="w40 text-bold" colspan="3"><?php echo Esb_Class_Date::i18n( get_post_meta( $post->ID, ESB_META_PREFIX.'end_date', true ), true ); ?></td>
                </tr>
                <tr>
                    <td class="w80" colspan="4"><?php _e( 'Subscription to', 'townhub-add-ons' ); ?> <?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'plan_title', true ); ?></td>
                    <td class="w20 text-right"><?php echo townhub_addons_get_price_formated(get_post_meta( $post->ID, ESB_META_PREFIX.'subtotal', true )); ?></td>
                </tr>
                <tr>
                    <td class="w40 text-right text-bold text-blur" colspan="2"><?php _e( 'Subtotal', 'townhub-add-ons' ); ?></td>
                    <td class="w40 text-right" colspan="3"><?php echo townhub_addons_get_price_formated(get_post_meta( $post->ID, ESB_META_PREFIX.'subtotal', true )); ?></td>
                </tr>
                <tr>
                    <td class="w40 text-right text-bold text-blur" colspan="2"><?php _e( 'Tax', 'townhub-add-ons' ); ?></td>
                    <td class="w40 text-right" colspan="3"><?php echo townhub_addons_get_price_formated(get_post_meta( $post->ID, ESB_META_PREFIX.'subtotal_vat', true )); ?></td>
                </tr>
                <tr>
                    <td class="w40 text-right text-bold" colspan="2"><?php _e( 'Total', 'townhub-add-ons' ); ?></td>
                    <td class="w60 text-right text-bold" colspan="3"><?php echo townhub_addons_get_price_formated(get_post_meta( $post->ID, ESB_META_PREFIX.'price_total', true )); ?></td>
                </tr>
            </tbody>
        </table>
        <?php   
    }

    public function view_invoice(){
        $json = array(
            'success' => false,
            'data' => array(
                'POST'=>$_POST,
            )
        );
        $nonce = $_POST['_nonce'];
    
        if ( ! wp_verify_nonce( $nonce, 'townhub-add-ons' ) ){
            $json['data']['error'] = esc_html__( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }

        $invoice_post          = get_post($_POST['invoice']);
        if(empty($invoice_post)){
            $json['error'] = esc_html__( 'Invalid invoice', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }
        $json['success'] = true;
        $json['invoice'] = self::get_invoice_datas($invoice_post);
        wp_send_json($json );
    }

    public static function get_invoice_datas($invoice_post){
        return array(
            'author' => get_post_meta( $invoice_post->ID, ESB_META_PREFIX.'user_name', true ),
            'amount' => townhub_addons_get_price_formated(get_post_meta( $invoice_post->ID, ESB_META_PREFIX.'amount', true )),
            
            'method' => townhub_addons_payment_names(get_post_meta( $invoice_post->ID, ESB_META_PREFIX.'payment', true )),
            'title' => $invoice_post->post_title,
            'number' => $invoice_post->ID,
            'date' => Esb_Class_Date::i18n( $invoice_post->post_date ),

            'plan' => get_post_meta( $invoice_post->ID, ESB_META_PREFIX.'plan_title', true ),
            'expire' => Esb_Class_Date::i18n( get_post_meta( $invoice_post->ID, ESB_META_PREFIX.'end_date', true ), true ),
            
        );
    }
}

new Esb_Class_Invoice_CPT();

