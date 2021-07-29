<?php 
/* add_ons_php */

class Esb_Class_Payment_Skrill extends Esb_Class_Payment{
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
        if( townhub_addons_get_option('skrill_enable' ) == 'yes' ){
            $payments['skrill'] = array(
                'title'             => __( 'Skrill', 'townhub-add-ons' ),
                
                'icon'              => 'https://www.skrill.com/fileadmin/content/images/brand_centre/Skrill_Logos/skrill-85x37_en.gif',
                'desc'              => townhub_addons_get_option('skrill_desc' ),

                'checkout_text'     => __( 'Process to Skrill', 'townhub-add-ons' ),
            );
        }
        return $payments;
    }

    public function payment_text($payments){
        $payments['skrill']     = __( 'Skrill', 'townhub-add-ons' );
        return $payments;
    }

    public function check_webhooks($action){
        if( $action === 'cth_skripn' ){
            $payment_class = new CTH_Payment_Skrill();
            $pm_datas = $payment_class->extractPaymentData();

            if(!isset($pm_datas['order_id']) || empty($pm_datas['order_id'])) return;

            if( isset($pm_datas['pm_status']) && $pm_datas['pm_status'] == 2 ){
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
            // on success payment
        }
        if( $action === 'cth_sklrec' ){
            $payment_class = new CTH_Payment_Skrill();
            $pm_datas = $payment_class->extractRecData();
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
                
        $skrl_args = array(

            'pay_to_email'          => townhub_addons_get_option('skrill_merchant_email' ),
            'transaction_id'        => '',
            'return_url'            => $checkout_success_redirect,
            'cancel_url'            => home_url(),
            'status_url'            => home_url('/?action=cth_skripn'),

            // 'language'               => 'EN',

            'merchant_fields'       => 'OrderID,ProductID,UserID,UserEmail', // A comma-separated list of field names that are passed back to your web server when the payment is confirmed (maximum 5 fields).
            'OrderID'               => $inserted_post_first, // order id, // value for merchant field
            'ProductID'             => $item_number, // product id // value for merchant field
            'UserID'                => $puser_id, // buyer id // value for merchant field
            'UserEmail'             => $puser_email, // buyer id // value for merchant field


            'customer_number'       =>  '',

      //       'amount2_description'    => 'Product Price:',
            // 'amount2'                => '29.90',

            // 'amount3_description'    => 'Handling Fees & Charges:',
            // 'amount3'                => '3.10',

            // 'amount4_description'    => 'VAT (20%):',
            // 'amount4'                => '6.60',

            'amount'                =>  $price_total,
            'currency'              =>  townhub_addons_get_option('currency', 'USD'),

            'firstname'             => $puser_firstname,
            'lastname'              => $puser_lastname,
            'address'               => '',
            'postal_code'           => '',
            'city'                  => '',
            'country'               => '',

            'detail1_description'   => _x( 'Product ID:', 'skrill product', 'townhub-add-ons' ),
            'detail1_text'          => $item_number,
            'detail2_description'   => _x( 'Name:', 'skrill product', 'townhub-add-ons' ),
            'detail2_text'          => $item_name,
            // 'detail3_description'   => 'Special Conditions:',
            // 'detail3_text'           => '5-6 days for delivery',

            
        );

        

        if(get_post_meta( $inserted_post_first , ESB_META_PREFIX.'is_recurring', true ) == 'on' ){
            $interval = get_post_meta( $inserted_post_first , ESB_META_PREFIX.'interval', true );
            $period = get_post_meta( $inserted_post_first , ESB_META_PREFIX.'period', true );
            $trial_interval = get_post_meta( $inserted_post_first , ESB_META_PREFIX.'trial_interval', true );
            $trial_period = get_post_meta( $inserted_post_first , ESB_META_PREFIX.'trial_period', true );

            if( !empty($trial_interval) && !empty($trial_period) ){
                $trial_days = $this->get_trial_days( $trial_interval, $trial_period );
                $skrl_args['amount'] = 0;
                $skrl_args['rec_start_date'] = Esb_Class_Date::modify('now', $trial_days, 'd/m/Y');
            }
            $skrl_args['rec_amount'] = $price_total;
            $skrl_args['rec_period'] = $this->get_rec_period( $interval, $period );
            $skrl_args['rec_cycle'] = $period;

            $skrl_args['rec_status_url'] = home_url('/?action=cth_sklrec');
        }
        // end check for recurring membership
        $payment_class = new CTH_Payment_Skrill();
        $process_results = array(
            'success'   => true,
            'url'       => $payment_class->processBuyNow($skrl_args),
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

class CTH_Payment_Skrill{

    protected $ipn_data = array();

    protected $debug_file;    

    protected $debug = true;// ESB_DEBUG;

    function __construct(){

        $this->_url = townhub_addons_get_option('payments_test_mode') == 'yes'? 'https://pay.skrill.com/?' : 'https://pay.skrill.com/?';

        $this->debug_file = './skrill.log';
    }

    function processBuyNow($args = array()){
        $df_args = array(
            'pay_to_email'          => '',
            'transaction_id'        => '',
            'return_url'            => '',
            'cancel_url'            => '',
            'status_url'            => home_url('/?action=cth_skripn'),

            // 'language'           =>  'EN',

            // 'merchant_fields'        => 'Field1,Field2', // A comma-separated list of field names that are passed back to your web server when the payment is confirmed (maximum 5 fields).
            // 'Field1'             => '1000', // value for merchant field
            // 'Field2'             => '1000', // value for merchant field

            'customer_number'       => '',

      //       'amount2_description'    => 'Product Price:',
            // 'amount2'                => '29.90',

            // 'amount3_description'    => 'Handling Fees & Charges:',
            // 'amount3'                => '3.10',

            // 'amount4_description'    => 'VAT (20%):',
            // 'amount4'                => '6.60',

            'amount'                =>  '1',
            'currency'              =>  'USD',

            'firstname'             => '',
            'lastname'              => '',
            'address'               => '',
            'postal_code'           => '',
            'city'                  => '',
            'country'               => '',

            // 'detail1_description'   => 'Product ID:',
            // 'detail1_text'           => '4509334',
            // 'detail2_description'   => 'Description:',
            // 'detail2_text'           => 'Romeo and Juliet (W.Shakespeare)',
            // 'detail3_description'   => 'Special Conditions:',
            // 'detail3_text'           => '5-6 days for delivery',
          
        );

        

        // merge with $args array
        $data = array_merge($df_args, $args);

        // Create parameter string
        $pfOutput = '';
        foreach( $data as $key => $val )
        {
            if(!empty($val))
            {
                $val = trim($val);
                // $encode_val = urlencode( trim( $val ) );
                if(in_array($key, array('cancel_url','return_url','status_url')))
                    $val = str_replace("http://localhost:8888/townhub", 'https://townhub.cththemes.com', $val);

                if($val != '')
                    $pfOutput .= $key .'='. urlencode($val) .'&';
            }
        }
        // Remove last ampersand
        $getString = substr( $pfOutput, 0, -1 );

        // $getString = str_replace("%3A8888", "", $getString);
    
        return $this->_url . $getString ;
        
   

    }
    function validateIPN() {
        header( 'HTTP/1.0 200 OK' );
        flush();
        // step 1
        $orderID = isset($_POST['OrderID']) ? $_POST['OrderID'] : '';
        if( !empty($orderID) ){
            $price_total = get_post_meta( $orderID, ESB_META_PREFIX.'price_total', true );
            // Posted variables from ITN
            $pfData = $_POST;
            // Strip any slashes in data
            foreach( (array)$pfData as $key => $val ){
                $pfData[$key] = stripslashes( $val );
            }
            $this->ipn_data = $pfData;
            if($this->debug){
                $debug_text = "New IPN POST Vars from Skrill:\n";
                foreach($pfData as $key => $value) {
                    $debug_text .= "$key=$value\n";
                }
                // Write to log
                error_log( date('[Y-m-d H:i e] '). 'Price total saved in order: '. $price_total . PHP_EOL, 3, $this->debug_file );
                error_log( date('[Y-m-d H:i e] '). 'payments_test_mode: '. townhub_addons_get_option('payments_test_mode') . PHP_EOL, 3, $this->debug_file );
                error_log( date('[Y-m-d H:i e] '). $debug_text . PHP_EOL, 3, $this->debug_file );
            }
            // end log

            if( isset($_POST['amount']) && $_POST['amount'] == $price_total ){
                // don't check md5 for testing
                if( townhub_addons_get_option('payments_test_mode') == 'yes' && 1 == 2 ){
                    // Valid transaction.
                    return true;
                }else{
                    // Validate the skirll signature
                    $secretWord = townhub_addons_get_option('skrill_secret_word');
                    $concatFields = $_POST['merchant_id']
                        .$_POST['transaction_id']
                        .strtoupper(md5($secretWord))
                        .$_POST['mb_amount']
                        .$_POST['mb_currency']
                        .$_POST['status'];
                    if($this->debug) error_log( date('[Y-m-d H:i e] '). $concatFields . PHP_EOL, 3, $this->debug_file );
                    $MBEmail = townhub_addons_get_option( 'skrill_merchant_email' );
                    if( strtoupper(md5($concatFields)) == $_POST['md5sig'] && $_POST['pay_to_email'] == $MBEmail ){
                        // Valid transaction.
                        //TODO: generate the product keys and
                        //      send them to your customer.
                        return true;
                    }
                }
            }
            // check correct amount
        }
        // check order id

        return false;
      
    }
    function extractPaymentData(){
        $return = array( 'pm_status'=> false );
        if($this->validateIPN()){

            $pmDatas        = $this->ipn_data;
            $return = array(
                'pm_status'                     => $pmDatas['status'],
                // related to subscription controller
                // 'pm_invoice'             => $pmDatas['invoice'],
                // //Customer's first name - Length: 64 characters
                // 'first_name'             => urldecode( $pmDatas['name_first'] ),
                // //Customer's last name - Length: 64 characters
                // 'last_name'                  => urldecode( $pmDatas['name_last'] ),
                // //Item name as passed by you, the merchant - plan title
                // 'item_name'                  => urldecode( $pmDatas['item_name'] ),
                //Pass-through variable for you to track purchases.
                'item_number'                   => $pmDatas['ProductID'], // product id
                //Full amount of the customer's payment, before transaction fee is subtracted. Equivalent to payment_gross for USD payments. If this amount is negative, it signifies a refund or reversal, and either of those payment statuses can be for the full or partial amount of the original transaction.
                'pm_amount'                     => $pmDatas['amount'],
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
                'order_id'                      => $pmDatas['OrderID'], // order id
                // 'listing_id'                 => $pmDatas['custom_int4'],
                'user_id'                       => $pmDatas['UserID'], // user id
                // 'user_email'                 => $pmDatas['email_address'],
                
                // 'renew_subscription'         => $renew_subscription,
                // for recurring subscription
                // 'recurring_subscription'     => $recurring_subscription,

                // 'subscription_id'            => (isset($pmDatas['subscr_id']) ? $pmDatas['subscr_id'] : ''),
                // https://code.tutsplus.com/tutorials/how-to-set-up-recurring-payments--net-30168

                // for listing ad campaign
                // 'for_listing_ad'             => $for_listing_ad,

                'payment_datas'                 => $pmDatas,
            );

        }
        return $return;
    }

    function extractRecData(){
        header( 'HTTP/1.0 200 OK' );
        flush();
        $return = array( 'pm_status'=> false );
        // step 1
        $orderID = isset($_POST['OrderID']) ? $_POST['OrderID'] : '';
        if( !empty($orderID) ){
            $price_total = get_post_meta( $orderID, ESB_META_PREFIX.'price_total', true );
            // Posted variables from ITN
            $pfData = $_POST;
            // Strip any slashes in data
            foreach( (array)$pfData as $key => $val ){
                $pfData[$key] = stripslashes( $val );
            }
            $this->ipn_data = $pfData;
            if($this->debug){
                $debug_text = "Rec IPN POST Vars from Skrill:\n";
                foreach($pfData as $key => $value) {
                    $debug_text .= "$key=$value\n";
                }
                // Write to log
                error_log( date('[Y-m-d H:i e] '). 'Price total saved in order: '. $price_total . PHP_EOL, 3, $this->debug_file );
                error_log( date('[Y-m-d H:i e] '). $debug_text . PHP_EOL, 3, $this->debug_file );
            }
            // end log

        }
        // end check order id

        return $return;
    }
}


Esb_Class_Payment_Skrill::getInstance();
// 327638C253A4637199CEBA 6642371F20

