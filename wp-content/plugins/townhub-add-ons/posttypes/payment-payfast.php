<?php
/* add_ons_php */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CTH_Payment_Payfast{

	protected $ipn_data = array();

	protected $debug_file;    

	protected $debug = true;// ESB_DEBUG;

	function __construct(){

		$this->payfast_url = townhub_addons_get_option('payments_test_mode') == 'yes'? 'https://sandbox.payfast.co.za/eng/process/?' : 'https://www.payfast.co.za/eng/process/?';

		$this->debug_file = './payfast.log';
	}

	function processBuyNow($args = array()){
		$payfast_redirect = $this->payfast_url;
		$payfast_args = array(
            'merchant_id'   =>  '',

            'merchant_key'  =>  '',

            'return_url'    =>  '',
            'cancel_url'    =>  '',
            'notify_url'    =>  '',

            'name_first'    =>  '',

            'name_last'     =>  '',

            'email_address' =>  '',

            'amount' => '22', // plan price

		    'item_name' => 'Listing Title', //plan title,
		    
		    'item_description'	=>	'',

            'custom_int1'         => '',
            'custom_int2'         => '',
            'custom_int3'         => '',
            'custom_int4'         => '',
            'custom_int5'         => '',

            'custom_str1'         => '',
            'custom_str2'         => '',
            'custom_str3'         => '',
            'custom_str4'         => '',
            'custom_str5'         => '',


            'email_confirmation'   => '',
            'confirmation_address'   => '',
		  
		);

        if(townhub_addons_get_option('email_confirmation') == 'yes' && townhub_addons_get_option('confirmation_address') != ''){
            $payfast_args['email_confirmation'] = 1;
            $payfast_args['confirmation_address'] = townhub_addons_get_option('confirmation_address');
        }

        // merge with $args array
        $data = array_merge($payfast_args, $args);

        // get price in ZAR
        // If multicurrency system its conversion has to be done before building this array
        $data['amount'] = number_format( sprintf( "%.2f", (float)townhub_addons_get_option('payfast_rate') * (float)$data['amount'] ), 2, '.', '' );

        // Create parameter string
        $pfOutput = '';
        foreach( $data as $key => $val )
        {
            if(!empty($val))
            {
                $val = trim($val);
                // $encode_val = urlencode( trim( $val ) );
                if(in_array($key, array('cancel_url','return_url','notify_url')))
                    $val = str_replace("http://localhost:8888/townhub", 'https://townhub.cththemes.com', $val);

                if($val != '')
                    $pfOutput .= $key .'='. urlencode($val) .'&';
            }
        }
        // Remove last ampersand
        $getString = substr( $pfOutput, 0, -1 );

        // $getString = str_replace("%3A8888", "", $getString);
        //Uncomment the next line and add a passphrase if there is one set on the account 
        $passPhrase = townhub_addons_get_option('payfast_passphrase');
        if( $passPhrase != '' )
        {
            $getString .= '&passphrase='. urlencode( trim( $passPhrase ) );
        }   
        $data['signature'] = md5( $getString );

        // return $this->payfast_url . $getString;
        return $this->payfast_url . $pfOutput .'signature='. $data['signature'];
        
        // return $this->payfast_url . http_build_query( $data, '', '&' );

        // https://sandbox.payfast.co.za/eng/process/?merchant_id=13759404&merchant_key=he76da1kinsx5&return_url=http%3A%2F%2Flocalhost%3A8888%2Ftownhub&cancel_url=http%3A%2F%2Flocalhost%3A8888%2Ftownhub&notify_url=http%3A%2F%2Flocalhost%3A8888%2Ftownhub%2F%3Faction%3Dcth_pfipn&name_first=Cuong&name_last=Tran&email_address=contact.cththemes%40gmail.com&amount=163.90&item_name=Payment+for+Professional+%26%238211%3B+Woo+subscription+from+CTHthemes&custom_int1=6370&custom_int2=2320&custom_int3=1&custom_str1=renew_no&custom_str2=subscription_no&passphrase=CTH_passphrase_2019&signature=0dad47513d1e0fb71e58a2bd8d93bed0

        // https://sandbox.payfast.co.za/eng/process/?merchant_id=13759404&merchant_key=he76da1kinsx5&return_url=https%3A%2F%2Ftownhub.cththemes.com&cancel_url=https%3A%2F%2Ftownhub.cththemes.com&notify_url=https%3A%2F%2Ftownhub.cththemes.com%2F%3Faction%3Dcth_pfipn&name_first=Cuong&name_last=Tran&email_address=contact.cththemes%40gmail.com&amount=11.00&item_name=Payment+for+Extended+%26%238211%3B+Recurring+subscription+from+CTHthemes&custom_int1=6375&custom_int2=2319&custom_int3=1&custom_str1=renew_no&custom_str2=subscription_yes&subscription_type=1&frequency=3&billing_date=2019-04-25&passphrase=CTH_passphrase_2019&signature=370cdca65710181a425c61d99a1bf140

        // https://sandbox.payfast.co.za/eng/process/?merchant_id=13759404&merchant_key=he76da1kinsx5&return_url=https%3A%2F%2Ftownhub.cththemes.com&cancel_url=https%3A%2F%2Ftownhub.cththemes.com&notify_url=https%3A%2F%2Ftownhub.cththemes.com%2F%3Faction%3Dcth_pfipn&name_first=Cuong&name_last=Tran&email_address=contact.cththemes%40gmail.com&amount=11.00&item_name=Payment+for+Extended+%26%238211%3B+Recurring+subscription+from+CTHthemes&custom_int1=6376&custom_int2=2319&custom_int3=1&subscription_type=1&frequency=3&billing_date=2019-04-25&passphrase=CTH_passphrase_2019

        // https://sandbox.payfast.co.za/eng/process/?merchant_id=13759404&merchant_key=he76da1kinsx5&return_url=https%3A%2F%2Ftownhub.cththemes.com&cancel_url=https%3A%2F%2Ftownhub.cththemes.com&notify_url=https%3A%2F%2Ftownhub.cththemes.com%2F%3Faction%3Dcth_pfipn&name_first=Cuong&name_last=Tran&email_address=contact.cththemes%40gmail.com&amount=11.00&item_name=Payment+for+Extended+%26%238211%3B+Recurring+subscription+from+CTHthemes&custom_int1=6377&custom_int2=2319&custom_int3=1&subscription_type=1&frequency=3&billing_date=2019-04-25&signature=692b99951cf880906ad7c1b5bb589932

        // --> correct ----                          ?merchant_id=13759404&merchant_key=he76da1kinsx5&return_url=https%3A%2F%2Ftownhub.cththemes.com&cancel_url=https%3A%2F%2Ftownhub.cththemes.com&notify_url=https%3A%2F%2Ftownhub.cththemes.com%2F%3Faction%3Dcth_pfipn&name_first=Cuong&name_last=Tran&email_address=contact.cththemes%40gmail.com&amount=11.00&item_name=Payment+for+Extended+%E2%80%93+Recurring+subscription+from+CTHthemes&custom_int1=6370&custom_int2=2320&custom_int3=1&subscription_type=1&billing_date=2019-04-25&frequency=3
        // https://sandbox.payfast.co.za/eng/process/?merchant_id=13759404&merchant_key=he76da1kinsx5&return_url=https%3A%2F%2Ftownhub.cththemes.com&cancel_url=https%3A%2F%2Ftownhub.cththemes.com&notify_url=https%3A%2F%2Ftownhub.cththemes.com%2F%3Faction%3Dcth_pfipn&amount=11.00&item_name=PaymentforExtended8211RecurringsubscriptionfromCTHthemes&custom_int1=6378&custom_int2=2319&custom_int3=1&subscription_type=1&billing_date=2019-04-25&frequency=3&signature=529b3b1d61266658008b3e1ea0a00460
        // https://sandbox.payfast.co.za/eng/process/?merchant_id=13759404&merchant_key=he76da1kinsx5&return_url=https%3A%2F%2Ftownhub.cththemes.com&cancel_url=https%3A%2F%2Ftownhub.cththemes.com&notify_url=https%3A%2F%2Ftownhub.cththemes.com%2F%3Faction%3Dcth_pfipn&amount=11.00&item_name=PaymentforExtended8211RecurringsubscriptionfromCTHthemes&custom_int1=6379&custom_int2=2319&custom_int3=1&subscription_type=1&billing_date=2019-04-25&frequency=3&passphrase=CTH_passphrase_2019
  //       // Create parameter string
  //       $pfOutput = '';
  //       foreach( $data as $key => $val ){
  //           if(!empty($val)){
  //               $pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
  //           }
  //       }
  //       // Remove last ampersand
  //       $getString = substr( $pfOutput, 0, -1 );
  //       //Uncomment the next line and add a passphrase if there is one set on the account 
  //       //$passPhrase = '';
  //       if( isset( $passPhrase ) ){
  //           $getString .= '&passphrase='. urlencode( trim( $passPhrase ) );
  //       }   
  //       // $data['signature'] = md5( $getString );
  //       $payfast_redirect .= $getString;
		// return $payfast_redirect;

	}

	function validateIPN() {
        // Notify PayFast that information has been received
        header( 'HTTP/1.0 200 OK' );
        flush();
        // step 1
        $pfHost = townhub_addons_get_option('payments_test_mode')=='yes' ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
        // Posted variables from ITN
        $pfData = $_POST;

        // Strip any slashes in data
        foreach( $pfData as $key => $val )
        {
            $pfData[$key] = stripslashes( $val );
        }

        $this->ipn_data = $pfData;

        if($this->debug){
            $debug_text = "New IPN POST Vars from Payfast:\n";
            foreach($pfData as $key => $value) {
                $debug_text .= "$key=$value\n";
            }
            // Write to log
            error_log( $debug_text . PHP_EOL, 3, $this->debug_file );
        }
        // step 2
        // $pfData includes of ALL fields posted through from PayFast, plus the empty strings
        $pfData = $_POST;
        $pfParamString = '';

        // Construct variables 
        foreach( $pfData as $key => $val ){
            if( $key != 'signature' )
            {
                $pfParamString .= $key .'='. urlencode( $val ) .'&';
            }
        }

        // Remove the last '&' from the parameter string
        $pfParamString = substr( $pfParamString, 0, -1 );
        $pfTempParamString = $pfParamString;
        // Passphrase stored in website database
        $passPhrase = townhub_addons_get_option('payfast_passphrase');

        if( !empty( $passPhrase ) ){
            $pfTempParamString .= '&passphrase='.urlencode( $passPhrase );
        }
        $signature = md5( $pfTempParamString );

        if($signature != $pfData['signature']){
            if($this->debug) error_log( 'Invalid Signature' . PHP_EOL, 3, $this->debug_file );
            return false;
        }
        // step 3
        // Variable initialization
        $validHosts = array(
            'www.payfast.co.za',
            'sandbox.payfast.co.za',
            'w1w.payfast.co.za',
            'w2w.payfast.co.za',
        );

        $validIps = array();

        foreach( $validHosts as $pfHostname ){
            $ips = gethostbynamel( $pfHostname );
            if( $ips !== false )
            {
                $validIps = array_merge( $validIps, $ips );
            }
        }

        // Remove duplicates
        $validIps = array_unique( $validIps );

        if( !in_array( $_SERVER['REMOTE_ADDR'], $validIps ) ){
            if($this->debug) error_log( 'Source IP not Valid' . PHP_EOL, 3, $this->debug_file );
            return false;
        }

        // Security step three
        // $cartTotal = xxxx; // This amount needs to be sourced from your application
        // if( abs( floatval( $cartTotal ) - floatval( $pfData['amount_gross'] ) ) > 0.01 ){
        //     error_log( 'Amounts Mismatch' . PHP_EOL, 3, $this->debug_file );
        //     return false;
        // }

        // Security step four
        // Variable initialization
        $url = 'https://'. $pfHost .'/eng/query/validate';

        // Create default cURL object
        $ch = curl_init();

        // Set cURL options - Use curl_setopt for greater PHP compatibility
        // Base settings
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_HEADER, false );      
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 1 );

        // Standard settings
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $pfParamString );

        // Execute CURL
        $response = curl_exec( $ch );
        curl_close( $ch );

        $lines = explode( "\r\n", $response );
        $verifyResult = trim( $lines[0] );

        if( strcasecmp( $verifyResult, 'VALID' ) != 0 ){
            if($this->debug) error_log( 'Payfast Data not valid' . PHP_EOL, 3, $this->debug_file );
            return false;
        }
        // Step three
        // Query your database and compare the pf_payment_id in order to verify that the order hasn’t already been processed on your system.
        $pfPaymentId = $pfData['pf_payment_id'];

        // Step four
        // Once you have completed these tests and the data received is valid, check the payment status and handle appropriately.
        if( $pfData ['payment_status'] == 'COMPLETE' ){
            // If complete, update your application
            if($this->debug) error_log( 'Payfast - One-Time payment status complete' . PHP_EOL, 3, $this->debug_file );
            return true;
        }else{
            // If unknown status, do nothing (which is the safest course of action)
            if($this->debug) error_log( 'unknown status, do nothing (which is the safest course of action)' . PHP_EOL, 3, $this->debug_file );
            return false;
        }

        // For Recurring billing only:
        // switch( $pfData['payment_status'] ){
        //     case 'COMPLETE':
        //     // If complete, update your application
        //        break;
        //     case 'CANCEL':
        //     // If cancel, then cancel subscription
        //        break;
        //     default:
        //     // If unknown status, do nothing (which is the safest course of action)
        //        break;
        // }
	  
	}
	function extractPaymentData(){
		$return = array('pm_status'=>false);
		if($this->validateIPN()){

			// New IPN POST Vars from Payfast:
			// m_payment_id=
			// pf_payment_id=744478
			// payment_status=COMPLETE
			// item_name=Payment for Extended plan
			// item_description=
			// amount_gross=108.90
			// amount_fee=-2.51
			// amount_net=106.39
			// custom_str1=
			// custom_str2=
			// custom_str3=
			// custom_str4=
			// custom_str5=
			// custom_int1=2319
			// custom_int2=
			// custom_int3=
			// custom_int4=
			// custom_int5=
			// name_first=Glue
			// name_last=Down
			// email_address=dev.cththemes@gmail.com
			// merchant_id=10011532
			// signature=970aae3cfa49c1573391ba68a64f9a83
			$payfast_data 		= $this->ipn_data;
			$return = array(
				'pm_status'   				=> $payfast_data['payment_status'],
				// related to subscription controller
				// 'pm_invoice'				=> $payfast_data['invoice'],
				// //Customer's first name - Length: 64 characters
				// 'first_name'				=> urldecode( $payfast_data['name_first'] ),
				// //Customer's last name - Length: 64 characters
				// 'last_name'					=> urldecode( $payfast_data['name_last'] ),
				// //Item name as passed by you, the merchant - plan title
				// 'item_name'					=> urldecode( $payfast_data['item_name'] ),
				//Pass-through variable for you to track purchases.
				'item_number'				=> $payfast_data['custom_int2'], // product id
				//Full amount of the customer's payment, before transaction fee is subtracted. Equivalent to payment_gross for USD payments. If this amount is negative, it signifies a refund or reversal, and either of those payment statuses can be for the full or partial amount of the original transaction.
				'pm_amount'   				=> $payfast_data['amount_gross'],
				//Transaction fee associated with the payment. mc_gross minus mc_fee equals the amount deposited into the receiver_email account. Equivalent to payment_fee for USD payments. If this amount is negative, it signifies a refund or reversal, and either of those payment statuses can be for the full or partial amount of the original transaction fee.
				// 'pm_fee'   					=> $payfast_data['mc_fee'],
				// For payment IPN notifications, this is the currency of the payment.
				// 'pm_currency'   			=> $payfast_data['mc_currency'],
				// The merchant's original transaction identification number for the payment from the buyer, against which the case was registered.
				// 'txn_id'   					=> $payfast_data['txn_id'],
				// Primary email address of the payment recipient (that is, the merchant). If the payment is sent to a non-primary email address on your PayPal account, the receiver_email is still your primary email.
				// 'receiver_email'   			=> urldecode( $payfast_data['receiver_email'] ),
				// Customer's primary email address. Use this email to provide any credits. 
				// 'payer_email'   			=> urldecode( $payfast_data['email_address'] ),
				// Quantity as entered by your customer or as passed by you, the merchant. If this is a shopping cart transaction, PayPal appends the number of the item 
				// 'quantity'   				=> $payfast_data['quantity'],
				// Time/Date stamp generated by PayPal, in the following format: HH:MM:SS Mmm DD, YYYY PDT
				'pm_date'   				=> date_i18n("Y-m-d"),

				// Custom value as passed by you, the merchant. These are pass-through variables that are never presented to your customer 
				'order_ids' 					=> $payfast_data['custom_int1'], // order id
				// 'listing_id' 				=> $payfast_data['custom_int4'],
				'user_id' 					=> $payfast_data['custom_int3'], // user id
				// 'user_email' 				=> $payfast_data['email_address'],
				
				// 'renew_subscription' 		=> $renew_subscription,
				// for recurring subscription
				// 'recurring_subscription' 	=> $recurring_subscription,

				// 'subscription_id'			=> (isset($payfast_data['subscr_id']) ? $payfast_data['subscr_id'] : ''),
				// https://code.tutsplus.com/tutorials/how-to-set-up-recurring-payments--net-30168

				// for listing ad campaign
				// 'for_listing_ad' 			=> $for_listing_ad,

                'payment_datas'             => $payfast_data,
			);
		}
		return $return;
	}
}