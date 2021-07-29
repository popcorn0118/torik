<?php 
/* add_ons_php */

defined( 'ABSPATH' ) || exit;
class Esb_Class_Payment_Payfast extends Esb_Class_Payment{ 
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
    	require_once ESB_ABSPATH.'posttypes/payment-payfast.php';
    }
    public function payment_methods_texts(){
		add_filter('esb_payment_method_texts' ,array($this, 'get_method_payment_text')); 
    }
    public function get_method_payment_text($methods){
    	$method = array(
    		'payfast' => __( 'Payfast', 'townhub-add-ons' ),
    	);
    	$result = array_merge($methods, $method);
    	return $result;
    }
    public function payment_methods(){
    	add_filter('esb_payment_methods' ,array($this, 'get_method_payment')); 
    }
    public function get_method_payment($payments){
        if(townhub_addons_get_option('payments_payfast_enable') == 'yes'){
        	$payments['payfast'] = array(
                'title' => __( 'Pay via Payfast', 'townhub-add-ons' ),
                'icon' => ESB_DIR_URL.'assets/images/payfast.png',
                'desc' => townhub_addons_get_option('payments_payfast_desc',''),
                'checkout_text' => __( 'Process to Payfast', 'townhub-add-ons' ),
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
	    $payment_class = new CTH_Payment_Payfast();
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

        // $current_user = wp_get_current_user();
        if(get_post_meta( $inserted_post_first , ESB_META_PREFIX.'is_recurring', true ) == 'on' ){
            $interval = get_post_meta( $inserted_post_first , ESB_META_PREFIX.'interval', true );
            $period = get_post_meta( $inserted_post_first , ESB_META_PREFIX.'period', true );

            $item_name = sprintf(__( 'Payment for %s', 'townhub-add-ons' ), get_the_title( $inserted_post_first ));
            
            $payfast_args = array(
                // 'item_name' => townhub_addons_alphanumeric($item_name) , //plan title,
                'item_name' => $item_name , //plan title,

                // 'amount' => $price_total, // plan price
                //Subscription
                // 'subscription_type' => 1,
                // 'billing_date'  => townhub_add_ons_get_paypal_duration( $interval, $period ),
                // 'frequency'     => townhub_add_ons_get_payfast_duration_unit( $period ),
                // 'cycles'        => $interval,

                'custom_int1'   =>  $data_checkout['inserted_posts_text'], // order id
                'custom_int2'   =>  $item_number, // product id
                'custom_int3'   =>  $puser_id, // user id
                // 'custom_str1'   =>  'renew_no', // can not use _ in custom_str
                // 'custom_str2'   =>  'subscription_yes',

                'merchant_id'   =>  townhub_addons_get_option('payments_payfast_merchant_id'),

                'merchant_key'  =>  townhub_addons_get_option('payments_payfast_merchant_key'),

                'return_url'    =>  home_url(),

                'cancel_url'    =>  home_url(),
                
                'notify_url'    =>  home_url('/?action=cth_pfipn'),

                // 'name_first'    =>  townhub_addons_alphanumeric($current_user->user_firstname),

                // 'name_last'     =>  townhub_addons_alphanumeric($current_user->user_lastname),

                // 'email_address' =>  townhub_addons_alphanumeric($current_user->user_email),

                'item_description'  =>  ''
            );

            $payfast_args['subscription_type'] = 1;
            // The date from which future subscription payments will be made. Eg. 2016-01-01. Defaults to current date if not set.
            // $recurring_args['billing_date'] = '';

            // Future recurring amount for the subscription. Defaults to the ‘amount’ value if not set. A minimum amount of R5.00 should be used as the recurring_amount.
            $payfast_args['amount'] = $price_total; // plan price

            $trial_interval = get_post_meta( $data_checkout['inserted_post_first'] , ESB_META_PREFIX.'trial_interval', true );
            $trial_period = get_post_meta( $data_checkout['inserted_post_first'] , ESB_META_PREFIX.'trial_period', true );
            if(!empty($trial_interval) && !empty($trial_period)){
                // after trial date
                $payfast_args['billing_date'] = townhub_add_ons_cal_next_date('now', $trial_period, $trial_interval, 'Y-m-d');
            }

            $payfast_args['frequency'] = townhub_addons_payfast_frequency( $interval, $period ); // Subscription duration
            // The number of payments/cycles that will occur for this subscription. Set to 0 for infinity.
            $payfast_args['cycles'] = 0;

            // $recurring_args['item_name'] = get_the_title($product_id);
   //          $recurring_args['item_number'] = $product_id;


            
        }else{
            $item_name = sprintf(__( 'Payment for %s', 'townhub-add-ons' ), get_the_title( $inserted_post_first ));
            
            $payfast_args = array(
                // 'item_name' => townhub_addons_alphanumeric($item_name) , //plan title,
                'item_name' => $item_name , //plan title,

                'amount' => $price_total, // plan price

                'custom_int1'   =>  $data_checkout['inserted_posts_text'], // order id
                'custom_int2'   =>  $item_number, // product id
                'custom_int3'   =>  $puser_id, // user id
                // 'custom_str1'   =>  'renew_no',
                // 'custom_str2'   =>  'subscription_no',

                'merchant_id'   =>  townhub_addons_get_option('payments_payfast_merchant_id'),

                'merchant_key'  =>  townhub_addons_get_option('payments_payfast_merchant_key'),

                'return_url'    =>  home_url(),

                'cancel_url'    =>  home_url(),
                
                'notify_url'    =>  home_url('/?action=cth_pfipn'),

                // 'name_first'    =>  $current_user->user_firstname,

                // 'name_last'     =>  $current_user->user_lastname,

                // 'email_address' =>  $current_user->user_email,

                'item_description'  =>  ''
            );
        }
        $process_results = array(
        	'success'   => true,
            'url'       => $payment_class->processBuyNow($payfast_args),
        );
        return $process_results;
    }
    public function check_webhooks($action){
        if( $action === 'cth_pfipn' ){
            $payment_class = new CTH_Payment_Payfast();

            $pm_datas = $payment_class->extractPaymentData();


            if(!isset($pm_datas['order_ids']) || empty($pm_datas['order_ids'])) return;

            foreach ((array)$pm_datas['order_ids'] as $order_id) {
                $pm_datas['order_id'] = $order_id;
                $order_pt = get_post_type($order_id); // (string|false) Post type on success, false on failure.
                if($order_pt == 'lbooking'){
                    // for booking
                    if ($pm_datas['pm_status'] === 'COMPLETE') {
                        $pm_datas['pm_status'] = 'completed';
                        // if($pm_datas['amount_gross'] == get_post_meta( $order_id, ESB_META_PREFIX.'price_total', true )) 
                            Esb_Class_Booking::approve_booking($order_id);   
                    }else{
                        //The payment has other status include false (boolean)
                    }
                }elseif($order_pt == 'cthads'){
                    if ($pm_datas['pm_status'] === 'COMPLETE') {
                        // if($pm_datas['pm_amount'] == get_post_meta( $order_id, ESB_META_PREFIX.'price_total', true )) 
                            Esb_Class_ADs::active_ad($order_id);

                    }
                }elseif($order_pt == 'lorder'){
                    // for membership
                    if ($pm_datas['pm_status'] === 'COMPLETE') {
                        //The payment has been completed, and the funds have been added successfully to your account balance.
                        $pm_datas['pm_status'] = 'completed';
                        // if($pm_datas['amount_gross'] == get_post_meta( $order_id, ESB_META_PREFIX.'price_total', true )) 
                            Esb_Class_Membership::active_membership($pm_datas);
                    }else{
                        //The payment has other status include false (boolean)
                    }

                }
            }

        }
    }

}
Esb_Class_Payment_Payfast::getInstance();

// $class_Payfast = new Esb_Class_Payment_Payfast();
// $class_Payfast->int();
