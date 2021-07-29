<?php 
/* add_ons_php */
// https://paystack.com/docs/payments/accept-payments/
// https://paystack.com/docs/payments/webhooks/
// https://paystack.com/docs/api/
class Esb_Class_Payment_Paystack extends Esb_Class_Payment{
    private static $_instance;
    public static function getInstance() {
        if ( ! ( self::$_instance instanceof self ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function __construct() {
        add_action( 'esb_payment_methods', array( $this, 'add_payment' ) );
        add_action( 'esb_payment_method_texts', array( $this, 'payment_text' ) );
        add_action( 'esb_payment_check_webhooks', array( $this, 'check_webhooks' ) );
    }

    public function add_payment($payments){
        if( townhub_addons_get_option('paystack_enable' ) == 'yes' ){
            $payments['paystack'] = array(
                'title'             => _x( 'Paystack','Payments', 'townhub-add-ons' ),
                
                'icon'              => ESB_DIR_URL.'assets/images/paystack.png',
                'desc'              => townhub_addons_get_option('paystack_desc' ),

                'checkout_text'     => _x( 'Process to Paystack','Payments', 'townhub-add-ons' ),
            );
        }
        return $payments;
    }

    public function payment_text($payments){
        $payments['paystack']     = _x( 'Paystack','Payments', 'townhub-add-ons' );
        return $payments;
    }

    public function check_webhooks($action){
        // webhook action - cth_pstwebhook
        if( $action === 'cth_pstwebhook' ){

            $payment_class = new CTH_Payment_Paystack();
            $payment_class->checkWebHooks();

        }
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
        //$pmType = 'booking';
        if($inserted_post_first_pt == 'lbooking'){
            $item_number = get_post_meta( $inserted_post_first, ESB_META_PREFIX.'listing_id', true );
            //$pmType = 'booking';
        }elseif($inserted_post_first_pt == 'lorder'){
            $item_number = get_post_meta( $inserted_post_first, ESB_META_PREFIX.'plan_id', true );
            //$pmType = 'membership';
        }elseif($inserted_post_first_pt == 'cthads'){
            $item_number = get_post_meta( $inserted_post_first, ESB_META_PREFIX.'plan_id', true );
            //$pmType = 'ad';
            
        }
        // need to check if allow checkout as guest
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
        $item_name = sprintf(__( 'Payment for %s', 'townhub-add-ons' ), get_the_title( $inserted_post_first ));
        
        $pm_amount = floatval( townhub_addons_get_option('paystack_rate','1') ) * $price_total;
        $pmt_args = array(

            'cart_id'               => 'bk-ref-'.$inserted_post_first, // order id, // We used the cart_id from the form above as our transaction reference. You should use a unique transaction identifier from your system as your reference.
            'amount'                => round($pm_amount, 2) * 100, // The amount field has to be converted to the lowest currency unit by multiplying the value by 100. So if you wanted to charge N50 or $50 or GHS50, you have to multiply 50 * 100 and pass 5000 in the amount field.
            // 'currency'              => townhub_addons_get_option('currency', 'USD'), // default to merchant
            'callback_url'          => $checkout_success_redirect,
            // 'callback_url'          => home_url('/?action=cth_pstipn'), // use webhook instead

            'email'                 => $puser_email, // buyer id // value for merchant field

            'metadata'              => json_encode( array(
                'custom_fields' => array(
                    'order_id'      => $inserted_post_first,
                    'product_id'    => $item_number,
                    'user_id'       => $puser_id,
                    'user_email'    => $puser_email,
                ),
                'order_id'      => $inserted_post_first,
                'product_id'    => $item_number,
                'user_id'       => $puser_id,
                'user_email'    => $puser_email,
            ) ),

      
        );

        

        // if(get_post_meta( $inserted_post_first , ESB_META_PREFIX.'is_recurring', true ) == 'on' ){
        //     $interval = get_post_meta( $inserted_post_first , ESB_META_PREFIX.'interval', true );
        //     $period = get_post_meta( $inserted_post_first , ESB_META_PREFIX.'period', true );
        //     $trial_interval = get_post_meta( $inserted_post_first , ESB_META_PREFIX.'trial_interval', true );
        //     $trial_period = get_post_meta( $inserted_post_first , ESB_META_PREFIX.'trial_period', true );

        //     if( !empty($trial_interval) && !empty($trial_period) ){
        //         $trial_days = $this->get_trial_days( $trial_interval, $trial_period );
        //         $pmt_args['amount'] = 0;
        //         $pmt_args['rec_start_date'] = Esb_Class_Date::modify('now', $trial_days, 'd/m/Y');
        //     }
        //     $pmt_args['rec_amount'] = $price_total;
        //     $pmt_args['rec_period'] = $this->get_rec_period( $interval, $period );
        //     $pmt_args['rec_cycle'] = $period;

        //     $pmt_args['rec_status_url'] = home_url('/?action=cth_pstrec');
        // }
        // end check for recurring membership
        $payment_class = new CTH_Payment_Paystack();
        $process_results = array(
            'success'   => true,
            'url'       => $payment_class->processBuyNow($pmt_args),
        );

        return $process_results;

    }
    function get_rec_period($interval = '', $period = ''){
        if( !empty($interval) ){
            if( $period === 'week' ) return 7 * (int)$interval;
            return (int)$interval;
        }
        return 1;
    }
    function get_trial_days($interval = '', $period = ''){
        switch ($period) {
            case 'week':
                return 7*(int)$interval;
                break;
            case 'month':
                return 30*(int)$interval;
                break;
            case 'year':
                return 365*(int)$interval;
                break;
            default:
                return (int)$interval;
                break;
        }
    }
}

class CTH_Payment_Paystack{

    protected $ipn_data = array();

    protected $debug_file;    

    protected $debug = true;// ESB_DEBUG;

    function __construct(){

        $this->_url = townhub_addons_get_option('payments_test_mode') == 'yes'? 'https://pay.paystack.com/?' : 'https://pay.paystack.com/?';

        $this->debug_file = './paystack.log';
    }

    function processBuyNow($args = array()){


        $df_args = array(
            'cart_id'               => uniqid(),
            'amount'                => '1',
            // 'currency'              => 'USD',
            'callback_url'          => home_url('/'),

      
        );

        

        // merge with $args array
        $data = array_merge($df_args, $args);

        $url = "https://api.paystack.co/transaction/initialize";
        $secret_key = townhub_addons_get_option('paystack_secret_key');
        $fields_string = http_build_query($data);

        // open connection
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Authorization: Bearer $secret_key",
          "Cache-Control: no-cache",
        ));

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        //execute post
        $response = curl_exec($ch);
        curl_close( $ch );

        $decoded = json_decode($response, true);
        if( isset($decoded['status']) && $decoded['status'] ){
            // error_log($decoded['data']['authorization_url']);
            // error_log($decoded['data']['access_code']);
            // error_log($decoded['data']['reference']);
            return $decoded['data']['authorization_url'];
        }

        // error_log( print_r($decoded) );

        return '';
    }
    function checkWebHooks(){
        // Retrieve the request's body and parse it as JSON
        // $input = @file_get_contents("php://input");
        // http_response_code(200); // PHP 5.4 or greater

        // error_log( $input );

        // error_log(json_encode($_SERVER));
        // error_log( $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] );
        // error_log( hash_hmac( 'sha512', $input, townhub_addons_get_option('paystack_secret_key') ) );

        // $event = json_decode($input);

        // // Do something with $event

        // return;


        // only a post with paystack signature header gets our attention
        if( (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST' ) || !array_key_exists('HTTP_X_PAYSTACK_SIGNATURE', $_SERVER) )
            return;

        $ip = $_SERVER['REMOTE_ADDR'];
        if( !in_array($ip, array('52.31.139.75','52.49.173.169','52.214.14.220')) ) return;
        // Retrieve the request's body
        $input = @file_get_contents("php://input");
        
        // validate event do all at once to avoid timing attack
        if( $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] !== hash_hmac( 'sha512', $input, townhub_addons_get_option('paystack_secret_key') ) )
            return;

        http_response_code(200);

        // error_log( $input );
        // parse event (which is json string) as object
        // Do something - that will not take long - with $event
        $webhookEvent = json_decode($input);

        if( $webhookEvent->event == "charge.success"  ){

            $pm_datas = array(
                'pm_status'                     => $webhookEvent->data->status,
                // related to subscription controller
                // 'pm_invoice'             => $pmDatas['invoice'],
                // //Customer's first name - Length: 64 characters
                // 'first_name'             => urldecode( $pmDatas['name_first'] ),
                // //Customer's last name - Length: 64 characters
                // 'last_name'                  => urldecode( $pmDatas['name_last'] ),
                // //Item name as passed by you, the merchant - plan title
                // 'item_name'                  => urldecode( $pmDatas['item_name'] ),
                //Pass-through variable for you to track purchases.
                'item_number'                   => $webhookEvent->data->metadata->product_id, // product id
                //Full amount of the customer's payment, before transaction fee is subtracted. Equivalent to payment_gross for USD payments. If this amount is negative, it signifies a refund or reversal, and either of those payment statuses can be for the full or partial amount of the original transaction.
                'pm_amount'                     => $webhookEvent->data->amount,
                //Transaction fee associated with the payment. mc_gross minus mc_fee equals the amount deposited into the receiver_email account. Equivalent to payment_fee for USD payments. If this amount is negative, it signifies a refund or reversal, and either of those payment statuses can be for the full or partial amount of the original transaction fee.
                // 'pm_fee'                     => $pmDatas['mc_fee'],
                // For payment IPN notifications, this is the currency of the payment.
                // 'pm_currency'            => $pmDatas['mc_currency'],
                // The merchant's original transaction identification number for the payment from the buyer, against which the case was registered.
                // 'txn_id'                     => $pmDatas['txn_id'],
                // Primary email address of the payment recipient (that is, the merchant). If the payment is sent to a non-primary email address on your PayPal account, the receiver_email is still your primary email.
                // 'receiver_email'             => urldecode( $pmDatas['receiver_email'] ),
                // Customer's primary email address. Use this email to provide any credits. 
                // 'payer_email'            => urldecode( $pmDatas['email_address'] ),
                // Quantity as entered by your customer or as passed by you, the merchant. If this is a shopping cart transaction, PayPal appends the number of the item 
                // 'quantity'                   => $pmDatas['quantity'],
                // Time/Date stamp generated by PayPal, in the following format: HH:MM:SS Mmm DD, YYYY PDT
                'pm_date'                       => date_i18n("Y-m-d"),

                // Custom value as passed by you, the merchant. These are pass-through variables that are never presented to your customer 
                'order_id'                      => $webhookEvent->data->metadata->order_id, // order id
                // 'listing_id'                 => $pmDatas['custom_int4'],
                'user_id'                       => $webhookEvent->data->metadata->user_id, // user id
                'user_email'                    => $webhookEvent->data->metadata->user_email,
                
                // 'renew_subscription'         => $renew_subscription,
                // for recurring subscription
                // 'recurring_subscription'     => $recurring_subscription,

                // 'subscription_id'            => (isset($pmDatas['subscr_id']) ? $pmDatas['subscr_id'] : ''),
                // https://code.tutsplus.com/tutorials/how-to-set-up-recurring-payments--net-30168

                // for listing ad campaign
                // 'for_listing_ad'             => $for_listing_ad,

                // 'payment_datas'                 => $pmDatas,
            );


            if(!isset($pm_datas['order_id']) || empty($pm_datas['order_id'])) return;

            if( isset($pm_datas['pm_status']) && $pm_datas['pm_status'] == 'success' ){
                $order_pt = get_post_type( $pm_datas['order_id'] ); // (string|false) Post type on success, false on failure.
                switch ($order_pt) {
                    case 'lbooking':
                        Esb_Class_Booking::approve_booking( $pm_datas['order_id'] ); 
                        break;
                    case 'cthads':
                        Esb_Class_ADs::active_ad( $pm_datas['order_id'] );
                        break;
                    case 'lorder':
                        $pm_datas['pm_status'] = 'completed';
                        Esb_Class_Membership::active_membership( $pm_datas );
                        break;
                }
            }
        }
        // end check transaction success event
    }
    
}


Esb_Class_Payment_Paystack::getInstance();



