<?php 
/* add_ons_php */

defined( 'ABSPATH' ) || exit;
class Esb_Class_Payment_Stripe extends Esb_Class_Payment{
    private static $_instance;
    public static function getInstance() {
        if ( ! ( self::$_instance instanceof self ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    private function __construct() {
        $this->includes();
        $this->payment_methods_texts();
        $this->payment_methods();

        add_action( 'esb_payment_check_webhooks', array( $this, 'check_webhooks' ) );

        add_action( 'wp_enqueue_scripts', function(){
            if (is_page(esb_addons_get_wpml_option('checkout_page'))) {
                // wp_enqueue_script('checkout.stripe', 'https://checkout.stripe.com/checkout.js', array(), null, false);
                wp_enqueue_script('js.stripe', 'https://js.stripe.com/v3/', array(), null, false);
            }
        } );
        add_filter( 'cth_payments_deps', function($deps){
            $deps[] = 'js.stripe';
            return $deps;
        } );
    }
    // private $inserted_post_first = '';
    // private $methods = array();
    // protected $payment_url = '';
    // private $payments = array();
    
    // public function int() {
 //         $this->includes();
 //        $this->payment_methods_texts();
 //        $this-> payment_methods();
 //    }
    public function includes() {
        require_once ESB_ABSPATH.'posttypes/payment-stripe.php';    
    }
    public function payment_methods_texts(){
        add_filter('esb_payment_method_texts' ,array($this, 'get_method_payment_text')); 
    }
    public function get_method_payment_text($methods){
        $method = array(
            'stripe' => __( 'Stripe', 'townhub-add-ons' ),
        );
        $result = array_merge($methods, $method);
        return $result;
    }
    public function payment_methods(){
        add_filter('esb_payment_methods' ,array($this, 'get_method_payment'));
    }
    public function get_method_payment($payments){
        if(townhub_addons_get_option('payments_stripe_enable') == 'yes'){
            $payments['stripe'] = array(
                'title' => __( 'Stripe', 'townhub-add-ons' ),
                'icon' => ESB_DIR_URL.'assets/images/stripe.png',
                'desc' => townhub_addons_get_option('payments_stripe_desc',''),
                'checkout_text' => __( 'Pay Now', 'townhub-add-ons' ),
            );
        }
            
        return $payments;
    }
    public function process_payment_checkout($data_checkout){
        $inserted_post_first = $data_checkout['inserted_post_first'];
        // check for rest api
        if( isset($data_checkout['total']) && !empty($data_checkout['total']) ){
            $price_total = $data_checkout['total'];
        }else{
            $price_total = ESB_ADO()->cart->get_total();
        }
        $inserted_post_first_pt = get_post_type($inserted_post_first);
        $item_number = 999;
        if($inserted_post_first_pt == 'lbooking'){
            $item_number = get_post_meta( $inserted_post_first, ESB_META_PREFIX.'listing_id', true );
            // do_action('townhub_addons_insert_booking_after',$inserted_post_first);
        }elseif($inserted_post_first_pt == 'lorder'){
            $item_number = get_post_meta( $inserted_post_first, ESB_META_PREFIX.'plan_id', true );
        }elseif($inserted_post_first_pt == 'cthads'){
            $item_number = get_post_meta( $inserted_post_first, ESB_META_PREFIX.'plan_id', true );
            // update_post_meta( $inserted_post_first, ESB_META_PREFIX.'price_total', $price_total );
            
        }
        $inserted_posts_text = $data_checkout['inserted_posts_text'];
        // need to check if allow checkout as guest
        if( is_user_logged_in() ){
            $current_user = wp_get_current_user();
            $puser_id = $current_user->ID;
            $puser_email = $current_user->user_email;
            $puser_firstname = $current_user->user_firstname;
            $puser_lastname = $current_user->user_lastname;
        }elseif( !empty($data_checkout['user_id']) ){ // check for rest api
            $current_user = get_userdata( $data_checkout['user_id'] );
            $puser_id = $current_user->ID;
            $puser_email = $current_user->user_email;
            $puser_firstname = $current_user->user_firstname;
            $puser_lastname = $current_user->user_lastname;
        }else{
            $puser_id = 0;
            $puser_email = get_post_meta( $inserted_post_first, ESB_META_PREFIX.'lb_email', true ); // get booking email if guest booking
            $puser_firstname = '';
            $puser_lastname = '';
        }

        $stripe_local = ESB_DEBUG && ($_SERVER['SERVER_NAME'] == 'localhost'|| $_SERVER['SERVER_NAME'] == 'local.ser');

        if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Stripe Local: " . $stripe_local . PHP_EOL, 3, ESB_LOG_FILE);
        
        // for stripe payment method
        $payment_class = new CTH_Payment_Stripe();
        $stripeEmail = $data_checkout['stripeEmail'];
        $session = false;
        // for recurring - subscription payment
        if( get_post_meta( $inserted_post_first , ESB_META_PREFIX.'is_recurring', true ) == 'on' && get_post_meta( $item_number , ESB_META_PREFIX.'stripe_plan_id', true ) != '' ){ // recurring package - for membership only

            $subscription_metas = array(
                'esb_plan_id'               => $item_number, // make unique meta key for site identifing
                'order_id'                  => $inserted_post_first,
                'order_post_type'           => $inserted_post_first_pt,    
                'order_ids'                 => $inserted_posts_text,
                'user_id'                   => $puser_id,
                'user_email'                => $puser_email,
                'renew'                     => 'no',
                'subscription'              => 'yes'
            );
            $stripe_args = array(
                'items' => array(
                    array(
                        'plan' => get_post_meta( $item_number , ESB_META_PREFIX.'stripe_plan_id', true ),
                        'quantity'  => 1
                    ),
                ),
                // 'metadata'      => $subscription_metas
                'plan_id'       => get_post_meta( $item_number , ESB_META_PREFIX.'stripe_plan_id', true ),
            );
            
            if(!empty($trial_interval) && !empty($trial_period)){
                $stripe_args['trial_period_days'] = townhub_add_ons_get_stripe_duration( $trial_interval, $trial_period );

                $subscription_metas['trial_interval']   = $trial_interval;
                $subscription_metas['trial_period']     = $trial_period;
            }
            $stripe_args['metadata'] = $subscription_metas;
            // $subscription_obj = $payment_class->processRecurring($stripe_args);

            // if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Insert order post error: " . json_encode($subscription_obj) . PHP_EOL, 3, ESB_LOG_FILE);

            // // create charge success
            // if($subscription_obj && isset($subscription_obj->status)){

            // }
            
            // // for local test only
            // if($stripe_local && isset($subscription_obj->status) /*&& $subscription_obj->status === 'active'*/ ){ // or trialing for in trial period
            //     Esb_Class_Membership::active_membership(array(
            //         'pm_status'                 => $subscription_obj->status,
            //         'user_id'                   => $puser_id,
            //         'item_number'               => $item_number, // this is listing plan id
            //         'pm_date'                   => $subscription_obj->created, // or use start for correction
            //         'order_id'                  => $inserted_post_first,
            //         'recurring_subscription'    => true, // not used

            //         'txn_id'                    => $subscription_obj->id,
            //         'subscription_id'           => $subscription_obj->id,
            //     ), 
            //     true);
            //     // this shortcode should be added in success payment page
            //     // do_shortcode( '[affiliate_conversion_script amount="10" description="My Description" context="townhub-Add-Ons" reference="'.$lorder_id.'" status="pending"]' );
            // }
            $session = $payment_class->createRecurringSession($stripe_args);
            
        } // end stripe recurring subscription
        else{
            
            $charge_metas = array(
                'esb_plan_id'               => $item_number, // make unique meta key for site identifing
                'order_id'                  => $inserted_post_first,
                'order_post_type'           => $inserted_post_first_pt,

                'order_ids'                 => $inserted_posts_text,
                'user_id'                   => $puser_id,
                
                'user_email'                => $puser_email,
                'renew'                     => 'no',
                'subscription'              => 'no'
            );
            $stripe_args = array(
                // 'customer'   => $customer->id, // will be added from the class
                'amount'        => townhub_addons_get_stripe_amount( $price_total ),
                'amount_decimal'=> townhub_addons_get_stripe_amount( $price_total ),
                // 'currency'   => townhub_addons_get_option('currency','USD'), // lowercase will be added from the class
                'description'   => sprintf( __( 'Payment from %s', 'townhub-add-ons' ), $stripeEmail ), 
                'receipt_email' => $stripeEmail,
                'metadata'      => $charge_metas,
                'item_name'     => sprintf(_x( 'Payment for %s', 'Stripe Cart product', 'townhub-add-ons' ), get_the_title( $inserted_post_first )),
            );

            $session = $payment_class->createOneTimeSession($stripe_args);

            // $charge_obj = $payment_class->processOneTime($stripe_args);
            // // create charge success
            // if($charge_obj && isset($charge_obj->status)){

            // }
            // // for local test only
            // if($stripe_local && isset($charge_obj->status) && $charge_obj->status === 'succeeded'){
            //     if($inserted_post_first_pt == 'lbooking')
            //         Esb_Class_Booking::approve_booking($inserted_post_first);
            //     elseif($inserted_post_first_pt == 'cthads')
            //         Esb_Class_ADs::active_ad($inserted_post_first);
            //     elseif($inserted_post_first_pt == 'lorder')
            //         Esb_Class_Membership::active_membership(array(
            //             'pm_status'                 => 'completed',
            //             'user_id'                   => $puser_id,
            //             'item_number'               => $item_number, // this is listing plan id
            //             'pm_date'                   => $charge_obj->created, // or use start for correction
            //             'order_id'                  => $inserted_post_first,
            //             'recurring_subscription'    => false, // not used

            //             // update order transactions
            //             // for one time payment is balance_transaction data
            //             'txn_id'                    => $charge_obj->balance_transaction
            //         ), 
            //         true);

            // }
        }
        // error_log(json_encode($session));
        if( $session == false || is_wp_error( $session ) ){
            $process_results = array(
                'success'   => false,
                'url'       => '',
                'message'   => $session->get_error_message(),
            );
        }else{
            
            $process_results = array(
                'success'           => true,
                'url'               => '',
                'strip_checkout'    => true,
                'sessionId'         => $session->id,
            );
        }
        return $process_results;
    }
    public function check_webhooks($action){
        if( $action === 'cth_stripewebhook' ){
            $payment_class = new CTH_Payment_Stripe();
            $payment_class->checkWebHooksNew();
        }
    }

}
Esb_Class_Payment_Stripe::getInstance();

// $class_Stripe = new Esb_Class_Payment_Stripe();
// $class_Stripe->int();
