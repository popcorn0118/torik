<?php
/* add_ons_php */

class Esb_Class_Plan_CPT extends Esb_Class_CPT {
    protected $name = 'lplan';

    protected function init(){
        parent::init();

        $logged_in_ajax_actions = array(
            'create_stripe_plan',
        );
        foreach ($logged_in_ajax_actions as $action) {
            $funname = str_replace('townhub_addons_', '', $action);
            add_action('wp_ajax_'.$action, array( $this, $funname )); 
        }
        do_action( $this->name.'_cpt_init_after' );
    }

    public function register(){

        $labels = array( 
            'name' => __( 'Plan', 'townhub-add-ons' ),
            'singular_name' => __( 'Plan', 'townhub-add-ons' ), 
            'add_new' => __( 'Add New Plan', 'townhub-add-ons' ),
            'add_new_item' => __( 'Add New Plan', 'townhub-add-ons' ),
            'edit_item' => __( 'Edit Plan', 'townhub-add-ons' ),
            'new_item' => __( 'New Plan', 'townhub-add-ons' ),
            'view_item' => __( 'View Plan', 'townhub-add-ons' ),
            'search_items' => __( 'Search Plans', 'townhub-add-ons' ),
            'not_found' => __( 'No Plans found', 'townhub-add-ons' ),
            'not_found_in_trash' => __( 'No Plans found in Trash', 'townhub-add-ons' ),
            'parent_item_colon' => __( 'Parent Plan:', 'townhub-add-ons' ), 
            'menu_name' => __( 'Author Plans', 'townhub-add-ons' ),
        );

        $args = array( 
            'labels' => $labels,
            'hierarchical' => false,
            'description' => __( 'Author plans', 'townhub-add-ons' ),
            'supports' => array( 'title', 'editor', 'thumbnail'/*, 'post-formats'*/),
            'taxonomies' => array(),
            'public' => townhub_addons_get_option('pt_public_plan') == 'yes' ? true : false,
            'show_ui' => true,
            'show_in_menu' => true,//default from show_ui
            'menu_position' => 25,
            'menu_icon' => 'dashicons-tickets-alt',
            'show_in_nav_menus' => false,
            // 'publicly_queryable' => false,
            'exclude_from_search' => true,
            'has_archive' => false,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => array( 'slug' => __('plan','townhub-add-ons') ),
            'capability_type' => 'post'
        );
        register_post_type( $this->name, $args );
    }
    protected function set_meta_columns(){
        $this->has_columns = true;
    }
    public function meta_columns_head($columns){
        unset($columns['date']);
        $columns['_id']             = __('ID','townhub-add-ons');
        $columns['_price']          = __('Price','townhub-add-ons');
        $columns['_pm_count']       = __('Subscribers Count','townhub-add-ons');
        return $columns;
    }
    public function meta_columns_content($column_name, $post_ID){
        if ($column_name == '_price') {
            echo '<strong>'.townhub_addons_get_price_formated( get_post_meta( $post_ID, '_price', true ) ).'</strong>';
        }
        if ($column_name == '_id') {
            echo '<strong>'.$post_ID.'</strong>';
        }
        if ($column_name == '_pm_count') {
            echo '<strong>'.get_post_meta( $post_ID, ESB_META_PREFIX.'pm_count', true ).'</strong>';
        }
    }

    protected function set_meta_boxes(){
        $dfBoxes = array(
            'stripe_plan'       => array(
                'title'         => __( 'Stripe Recurring Plan', 'townhub-add-ons' ),
                'context'       => 'normal', // normal - side - advanced
                'priority'       => 'core', // default - high - core - low
                'callback_args'       => array(),
            ),
            'stats'       => array(
                'title'         => _x( 'Stats', 'Author plan', 'townhub-add-ons' ),
                'context'       => 'side', // normal - side - advanced
                'priority'       => 'high', // default - high - core - low
                'callback_args'       => array(),
            )
        );

        $addiBoxes = (array)apply_filters( 'cth_cpt_lplan_meta_boxes', array() );

        $this->meta_boxes = array_merge($addiBoxes, $dfBoxes);
    }

    public function lplan_stats_callback($post, $args){
        
        ?>
        <p><?php _ex('Subscribers Count: ','Author plan','townhub-add-ons'); ?><strong><?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'pm_count', true ); ?></strong></p>
        <label for="reset_stats"><?php _ex( 'Reset stats', 'Author plan', 'townhub-add-ons' ); ?><input type="checkbox" id="reset_stats" name="reset_stats" value="yes"></label>                   
        <?php   
    }

    public function lplan_stripe_plan_callback($post, $args){
        wp_nonce_field( 'cth-cpt-fields', '_cth_cpt_nonce' );

        ?>
        <table class="form-table stripe_plan">
            <tbody>

                <tr class="hoz">
                    <th class="w20 align-left"><?php _e( 'Stripe Plan', 'townhub-add-ons' ); ?></th>
                    <td>
                        <input type="text" class="input-text" name="<?php echo ESB_META_PREFIX.'stripe_plan_id' ?>" id="<?php echo ESB_META_PREFIX.'stripe_plan_id' ?>" value="<?php echo get_post_meta( $post->ID, ESB_META_PREFIX.'stripe_plan_id', true );?>">
                        <p><?php _e( 'Enter your Stripe Plan ID or create <a href="#" class="ctb-modal-open">New Plan</a> using this plan details.', 'townhub-add-ons' ); ?></p>
                        
                    </td>
                </tr>
                
            </tbody>
        </table>
        <?php 

        add_action( 'admin_footer', function()use($post){
            ?>
            <div class="ctb-modal-wrap ctb-modal">
                <div class="ctb-modal-holder">
                    <div class="ctb-modal-inner modal_main vis_mr">
                        <div class="ctb-modal-close"><span class="dashicons dashicons-no-alt"></span></div>
                        <div class="ctb-modal-title"><?php _e( 'Create a ', 'townhub-add-ons' );?><span><?php esc_html_e( 'Stripe Plan', 'townhub-add-ons' ); ?></span></div>
                        <div class="ctb-modal-content">

                            <form id="create-stripe-plan-form" class="create-stripe-plan-form custom-form" action="#" method="POST">
                                

                                <label for="stripe_plan"><?php _e( 'Plan Title *', 'townhub-add-ons' ); ?></label>
                                <input type="text" id="stripe_plan" name="stripe_plan" value="<?php echo $post->post_title; ?>" required="required">
                                
                                <label for="stripe_product"><?php _e( 'Product Title *', 'townhub-add-ons' ); ?></label>
                                <input type="text" id="stripe_product" name="stripe_product" value="<?php echo sprintf(__( '%s Stripe product', 'townhub-add-ons' ), $post->post_title ); ?>" required="required">
                                
                                <input type="hidden" name="lplan_id" value="<?php echo $post->ID; ?>">
                         

                                <?php wp_nonce_field( 'create_stripe_plan', 'stripe_nonce' ); ?>

                                <input class="stripe-plan-submit" type="submit" id="stripe_submit" value="<?php esc_attr_e( 'Submit', 'townhub-add-ons' ); ?>">

                            </form>
                            
                        </div>
                        <!-- end modal-content -->
                    </div>
                </div>
            </div>
            <!-- end modal --> 
            <?php
        } );  
    }

    public function save_post($post_id, $post, $update){
        if(!$this->can_save($post_id)) return;

        if(isset($_POST[ESB_META_PREFIX.'stripe_plan_id'])){
            $new_val = sanitize_text_field( $_POST[ESB_META_PREFIX.'stripe_plan_id'] ) ;
            $origin_val = get_post_meta( $post_id, ESB_META_PREFIX.'stripe_plan_id', true );
            if($new_val !== $origin_val){
                update_post_meta( $post_id, ESB_META_PREFIX.'stripe_plan_id', $new_val );
            }
            
        }
        // add new price meta for woo - first use _cth_price value and will update l
        if(isset($_POST['_cth_price'])){
            $new_val = sanitize_text_field( $_POST['_cth_price'] ) ;
            // if($new_val == '') $new_val = get_post_meta( $post_id, ESB_META_PREFIX.'price', true );
            $origin_val = get_post_meta( $post_id, '_price', true );
            if($new_val !== $origin_val){
                update_post_meta( $post_id, '_price', $new_val );
            }
            
        }
        if( isset($_POST['reset_stats']) && $_POST['reset_stats'] == 'yes' ){
            update_post_meta( $post_id, ESB_META_PREFIX.'pm_count', 0 );
        }

        // new settings
        do_action( 'cth_cpt_lplan_save_meta_boxes', $post_id, $post, $update );

    }

    public function create_stripe_plan(){
        $json = array(
            'success' => false,
            'data' => array(
                // 'POST'=>$_POST,
            )
        );

        if( !isset($_POST['stripe_nonce']) || !isset($_POST['lplan_id']) || !isset($_POST['stripe_plan']) || !isset($_POST['stripe_product']) ){
            $json['data']['error'] = esc_html__( 'Invalid create stripe form', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }
        

        $nonce = $_POST['stripe_nonce'];
        
        if ( ! wp_verify_nonce( $nonce, 'create_stripe_plan' ) ){
            $json['data']['error'] = esc_html__( 'Security checked!, Cheatn huh?', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }


        $plan_post          = get_post($_POST['lplan_id']);

        if(empty($plan_post)){
            $json['data']['error'] = esc_html__( 'Invalid listing plan ID', 'townhub-add-ons' ) ;
            wp_send_json($json );
        }

        $prices = townhub_addons_get_plan_prices($plan_post->ID);

        $stripe_args = array(
            'nickname'      => $_POST['stripe_plan'],
            'product'       => array(
                'name'  => $_POST['stripe_product']
            ),
            // 'amount'        => townhub_addons_get_stripe_amount( $prices['total'] ),
            'amount_decimal'        => townhub_addons_get_stripe_amount( $prices['total'] ),
            
            'interval'      => get_post_meta( $plan_post->ID , ESB_META_PREFIX.'period', true ),
            'interval_count'=> get_post_meta( $plan_post->ID , ESB_META_PREFIX.'interval', true )
        );

        require_once ESB_ABSPATH.'posttypes/payment-stripe.php';
        $payment_class = new CTH_Payment_Stripe();

        $plan = $payment_class->createPlan($stripe_args);

        if($plan){
            $json['success'] = true;
            $json['plan_id'] = $plan->id;

            $update_lplan_field = true;

            if($update_lplan_field){
                update_post_meta( $plan_post->ID, ESB_META_PREFIX.'stripe_plan_id', $plan->id );
            }
        }else{
            $json['data']['error'] = esc_html__( 'There is something wrong. Please try again.', 'townhub-add-ons' ) ;
        }

        wp_send_json($json );

    }

}

new Esb_Class_Plan_CPT();