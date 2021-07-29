<?php 
/* add_ons_php */

defined( 'ABSPATH' ) || exit;
class Esb_Class_Payment_Paypal extends Esb_Class_Payment{
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
    }
	// private $inserted_post_first = '';
	// private $methods = array();
	// protected $payment_url = '';
	// private $payments = array();
    
	// public function int() {
 //        $this->includes();
 //        $this->payment_methods_texts();
 //        $this-> payment_methods();
 //    }
    public function includes() {
    	require_once ESB_ABSPATH.'posttypes/payment-paypal.php';
    }
	public function payment_methods_texts(){
		add_filter('esb_payment_method_texts' ,array($this, 'get_method_payment_text')); 
    }
    public function get_method_payment_text($methods){
    	$method = array(
    		'paypal' => __( 'Paypal', 'townhub-add-ons' ),
    	);
    	$result = array_merge($methods, $method);
    	return $result;
    }
    public function payment_methods(){
    	add_filter('esb_payment_methods' ,array($this, 'get_method_payment'));
    }
    public function get_method_payment( $payments){
        if(townhub_addons_get_option('payments_paypal_enable') == 'yes'){
            $payments['paypal'] = array(
                'title' => __( 'Paypal', 'townhub-add-ons' ),
                'icon' => ESB_DIR_URL.'assets/images/ppcom.png',
                'desc' => townhub_addons_get_option('payments_paypal_desc',''),
                'checkout_text' => __( 'Process to Paypal', 'townhub-add-ons' ),
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
    	$payment_class = new CTH_Payment_Paypal(); 

        $checkout_success_redirect = home_url('/');
        if(townhub_addons_get_option('checkout_success') !== 'none') $checkout_success_redirect = get_permalink( townhub_addons_get_option('checkout_success') );  
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
	    if(get_post_meta( $inserted_post_first , ESB_META_PREFIX.'is_recurring', true ) == 'on' ){
            $interval = get_post_meta( $inserted_post_first , ESB_META_PREFIX.'interval', true );
            $period = get_post_meta( $inserted_post_first , ESB_META_PREFIX.'period', true );
            $trial_interval = get_post_meta( $inserted_post_first , ESB_META_PREFIX.'trial_interval', true );
            $trial_period = get_post_meta( $inserted_post_first , ESB_META_PREFIX.'trial_period', true );
            // https://developer.paypal.com/docs/classic/paypal-payments-standard/integration-guide/Appx_websitestandard_htmlvariables/#recurring-payment-variables
            $paypal_args = array(
                'cmd'               => '_xclick-subscriptions', // Subscribe button
                'item_name'         => sprintf(__( 'Payment for %s subscription plan', 'townhub-add-ons' ), get_the_title( $inserted_post_first )),
                'item_number'       => $item_number, // plan id

                // subscribe
                'a3'                => $price_total, // Regular subscription price.
                'p3'                => townhub_add_ons_get_paypal_duration( $interval, $period ), // Subscription duration
                't3'                => townhub_add_ons_get_paypal_duration_unit( $period ), // Regular subscription units of duration.
                /*
                D. Days. Valid range for p3 is 1 to 90.
                W. Weeks. Valid range for p3 is 1 to 52.
                M. Months. Valid range for p3 is 1 to 24.
                Y. Years. Valid range for p3 is 1 to 5.
                */
                'src'               => 1, // Recurring payments. Subscription payments recur unless subscribers cancel their subscriptions before the end of the current billing cycle or you limit the number of times that payments recur with the value that you specify for srt.
                // 'srt'               => 52, //Recurring times.
                // 'sra'               => 1, // Reattempt on failure. If a recurring payment fails, PayPal attempts to collect the payment two more times before canceling the subscription.

                'no_note'           => 1, // Do not prompt buyers to include a note with their payments. Valid value is from Subscribe buttons: For Subscribe buttons, always set no_note to 1.
                'modify'            => 0, // Modification behavior.
                /*
                Valid value is:
                0. Enables subscribers only to sign up for new subscriptions.
                1. Enables subscribers to sign up for new subscriptions and modify their current subscriptions.
                2. Enables subscribers to modify only their current subscriptions.
                The default value is 0.
                */




                // 'amount'            => $prices['total'],
                // 'quantity'          => 1,
                'currency_code'     => townhub_addons_get_option('currency', 'USD'),
                'custom'            => $inserted_posts_text .'|'. $puser_id .'|'. $puser_email .'|renew_no|subscription_yes',
                'business'          => townhub_addons_get_option('payments_paypal_business','cththemespp-facilitator@gmail.com'),
                
                'email'             => $puser_email,
                'first_name'        => $puser_firstname,
                'last_name'         => $puser_lastname,

                'notify_url'        => home_url('/?action=cth_ppipn'),
                'return'            => $checkout_success_redirect,
                'cancel_return'     => home_url('/pp_cancel'),
            );
            
            if(!empty($trial_interval) && !empty($trial_period)){
                $paypal_args['a1'] = 0;// 0 for free trial
                $paypal_args['p1'] = townhub_add_ons_get_paypal_duration( $trial_interval, $trial_period );
                $paypal_args['t1'] = townhub_add_ons_get_paypal_duration_unit( $trial_period );

            }
        }else{
            $paypal_args = array(
                'cmd'               => '_xclick',
                'item_name'         => sprintf( __( 'Payment for %s', 'townhub-add-ons' ), get_the_title( $inserted_post_first ) ),
                'item_number'       => $item_number, // plan id
                'amount'            => $price_total,
                'quantity'          => 1,
                'currency_code'     => townhub_addons_get_option('currency', 'USD'),// townhub_addons_get_option('currency','USD'),
                'custom'            => $inserted_posts_text .'|'. $puser_id .'|'. $puser_email .'|renew_no',
                'business'          => townhub_addons_get_option('payments_paypal_business','cththemespp-facilitator@gmail.com'),
                'email'             => $puser_email,
                'first_name'        => $puser_firstname,
                'last_name'         => $puser_lastname,

                'notify_url'        => home_url('/?action=cth_ppipn'),
                'return'            => $checkout_success_redirect,
                'cancel_return'     => home_url('/pp_cancel'),
            );
        }
        
        $process_results = array(
        	'success'   => true,
            'url'       => $payment_class->processBuyNow($paypal_args),
        );
        return $process_results;
    }
    public function check_webhooks($action){
        if( $action === 'cth_ppipn' ){
            $payment_class = new CTH_Payment_Paypal();
            $pm_datas = $payment_class->extractPaymentData();
            
            if(!isset($pm_datas['order_id']) || empty($pm_datas['order_id'])) return;

            $order_post = get_post( $pm_datas['order_id'] );
            if( !$order_post )  return;

            $order_id = $order_post->ID;
            
            $order_pt = get_post_type($order_id); // (string|false) Post type on success, false on failure.
            if($order_pt == 'lbooking'){
                // for booking
                if( $pm_datas['pm_status'] === 'Trialing' ){
                    // active trial membership
                    $pm_datas['pm_status'] = 'trialing';
                    // townhub_add_ons_active_membership($pm_datas, false);
                }elseif ($pm_datas['pm_status'] === 'Completed') {
                    //The payment has been completed, and the funds have been added successfully to your account balance.
                    $pm_datas['pm_status'] = 'completed';
                    // townhub_add_ons_active_membership($pm_datas, false);
                    // Esb_Class_Booking::paypal_completed_check($pm_datas, $order_id);

                    if($pm_datas['pm_amount'] == get_post_meta( $order_id, ESB_META_PREFIX.'price_total', true )) 
                        Esb_Class_Booking::approve_booking($order_id);

                }elseif($pm_datas['pm_status'] === 'Refunded'){
                    //The payment has been refunded


                    // $order_id = $pm_datas['order_id'];
                    // if ( !update_post_meta( $order_id, ESB_META_PREFIX.'status',  'refunded' ) ) {
                    //     if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Change order status to refunded failure" . PHP_EOL, 3, ESB_LOG_FILE);
                    // }
                    // do_action( 'townhub_addons_order_refunded', $order_id );
                }else{
                    //The payment has other status include false (boolean)
                }

            }elseif($order_pt == 'cthads'){
                if ($pm_datas['pm_status'] === 'Completed') {
                    // if($pm_datas['pm_amount'] == get_post_meta( $order_id, ESB_META_PREFIX.'price_total', true )) 
                        Esb_Class_ADs::active_ad($order_id);

                }
            }elseif($order_pt == 'lorder'){
                // for membership
                if( $pm_datas['pm_status'] === 'Trialing' ){
                    // active trial membership
                    $pm_datas['pm_status'] = 'trialing';
                    $pm_datas['order_id'] = $order_id;
                    // townhub_add_ons_active_membership($pm_datas, false);
                    Esb_Class_Membership::active_membership($pm_datas);
                }elseif ($pm_datas['pm_status'] === 'Completed') {
                    //The payment has been completed, and the funds have been added successfully to your account balance.
                    $pm_datas['pm_status'] = 'completed';
                    // townhub_add_ons_active_membership($pm_datas, false);
                    // Esb_Class_Booking::paypal_completed_check($pm_datas, $order_id);

                    if($pm_datas['pm_amount'] == get_post_meta( $order_id, ESB_META_PREFIX.'price_total', true )) 
                        Esb_Class_Membership::active_membership($pm_datas);

                }elseif($pm_datas['pm_status'] === 'Refunded'){
                    //The payment has been refunded


                    // $order_id = $pm_datas['order_id'];
                    // if ( !update_post_meta( $order_id, ESB_META_PREFIX.'status',  'refunded' ) ) {
                    //     if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Change order status to refunded failure" . PHP_EOL, 3, ESB_LOG_FILE);
                    // }
                    // do_action( 'townhub_addons_order_refunded', $order_id );
                }else{
                    //The payment has other status include false (boolean)
                }

            }



            // foreach ($pm_datas['order_ids'] as $order_id) {
            //     $order_pt = get_post_type($order_id); // (string|false) Post type on success, false on failure.
            //     if($order_pt == 'lbooking'){
            //         // for booking
            //         if( $pm_datas['pm_status'] === 'Trialing' ){
            //             // active trial membership
            //             $pm_datas['pm_status'] = 'trialing';
            //             // townhub_add_ons_active_membership($pm_datas, false);
            //         }elseif ($pm_datas['pm_status'] === 'Completed') {
            //             //The payment has been completed, and the funds have been added successfully to your account balance.
            //             $pm_datas['pm_status'] = 'completed';
            //             // townhub_add_ons_active_membership($pm_datas, false);
            //             // Esb_Class_Booking::paypal_completed_check($pm_datas, $order_id);

            //             if($pm_datas['pm_amount'] == get_post_meta( $order_id, ESB_META_PREFIX.'price_total', true )) 
            //                 Esb_Class_Booking::approve_booking($order_id);

            //         }elseif($pm_datas['pm_status'] === 'Refunded'){
            //             //The payment has been refunded


            //             // $order_id = $pm_datas['order_id'];
            //             // if ( !update_post_meta( $order_id, ESB_META_PREFIX.'status',  'refunded' ) ) {
            //             //     if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Change order status to refunded failure" . PHP_EOL, 3, ESB_LOG_FILE);
            //             // }
            //             // do_action( 'townhub_addons_order_refunded', $order_id );
            //         }else{
            //             //The payment has other status include false (boolean)
            //         }

            //     }elseif($order_pt == 'cthads'){
            //         if ($pm_datas['pm_status'] === 'Completed') {
            //             // if($pm_datas['pm_amount'] == get_post_meta( $order_id, ESB_META_PREFIX.'price_total', true )) 
            //                 Esb_Class_ADs::active_ad($order_id);

            //         }
            //     }elseif($order_pt == 'lorder'){
            //         // for membership
            //         if( $pm_datas['pm_status'] === 'Trialing' ){
            //             // active trial membership
            //             $pm_datas['pm_status'] = 'trialing';
            //             $pm_datas['order_id'] = $order_id;
            //             // townhub_add_ons_active_membership($pm_datas, false);
            //             Esb_Class_Membership::active_membership($pm_datas);
            //         }elseif ($pm_datas['pm_status'] === 'Completed') {
            //             //The payment has been completed, and the funds have been added successfully to your account balance.
            //             $pm_datas['pm_status'] = 'completed';
            //             // townhub_add_ons_active_membership($pm_datas, false);
            //             // Esb_Class_Booking::paypal_completed_check($pm_datas, $order_id);

            //             if($pm_datas['pm_amount'] == get_post_meta( $order_id, ESB_META_PREFIX.'price_total', true )) 
            //                 Esb_Class_Membership::active_membership($pm_datas);

            //         }elseif($pm_datas['pm_status'] === 'Refunded'){
            //             //The payment has been refunded


            //             // $order_id = $pm_datas['order_id'];
            //             // if ( !update_post_meta( $order_id, ESB_META_PREFIX.'status',  'refunded' ) ) {
            //             //     if(ESB_DEBUG) error_log(date('[Y-m-d H:i e] '). "Change order status to refunded failure" . PHP_EOL, 3, ESB_LOG_FILE);
            //             // }
            //             // do_action( 'townhub_addons_order_refunded', $order_id );
            //         }else{
            //             //The payment has other status include false (boolean)
            //         }

            //     }
            // }
        }
    }
    
    // public function process_payment_check_webhooks(){
            
    // }

}
Esb_Class_Payment_Paypal::getInstance();

// $class_Paypal = new Esb_Class_Payment_Paypal();
// $class_Paypal->int();