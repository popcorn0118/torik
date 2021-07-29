<?php
/* add_ons_php */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CTH_Payment_Paypal{
	protected $last_error;                 // holds the last error encountered
	
	// protected $test_mode = townhub_addons_get_option('payments_test_mode') == 'yes'? true : false ;                    // bool: log IPN results to text file?
	protected $debug = ESB_DEBUG;                    
	
	protected $debug_file;               // filename of the IPN log
	protected $ipn_response;               // holds the IPN response from paypal   
	protected $ipn_data = array();         // array contains the POST values for IPN
	
	protected $vars = array();           // array holds the fields to submit to paypal

	function __construct($cmd = '_xclick') {
	
		$this->paypal_url = (townhub_addons_get_option('payments_test_mode') == 'yes')? 'https://www.sandbox.paypal.com/cgi-bin/webscr/?' : 'https://www.paypal.com/cgi-bin/webscr/?';
		
		$this->last_error = '';
		
		$this->debug_file = './ipn.log';
		// $this->debug = true; 	// change back to true to enable logging
		$this->ipn_response = '';
		
	
		// Return method will have no affect on donations until Paypal pull their finger out and fix the donations return value
		// $this->addVar('rm','2');           	// Return method 0=GET all transactions  1=GET browser redirect no vars  2=POST browser redirect with vars
		// $this->addVar('cmd',$cmd); 		// Command _xclick = Buy Now, _donations = Donate, _xclick-subscriptions = Subscribe, _oe-gift-certificate = Gift Cert, 
		// 									// _cart = cart functions, _s-xclick = saved/encrypted button

	}

	// public function addVar($var, $value) {
	//   	$this->vars["$var"] = $value;
	// }

	function set($property, $value = null)
	{
		$previous = isset($this->$property) ? $this->$property : null;
		$this->$property = $value;
		return $previous;
	}

	function get($property, $default = null)
	{
		if(isset($this->$property)) return $this->$property;
		
		return $default;
	}

	function processBuyNow($args = array()){
		$paypal_redirect = $this->paypal_url;
		$paypal_args = array(
		    'cmd' => '_xclick',
		    //'amount' => $res_post_datas['price'],
		    //'item_name' => $planobj->get('title'), //plan title,
		    'item_name' => 'Listing Title', //plan title,

		    //'item_number' => $planobj->get('id'), // plan id
		    'item_number' => '1747', // listing id

		    //'amount' => $planobj->get('price'), // plan price
		    'amount' => '22', // plan price


		    'quantity' => 1,

		    'currency_code' => 'USD',

		    // 'custom' => $listing_id.'|'.$user->id.'|'.$user->email ,
		    // 'custom' => '$listing_id.|.$user->id.|.$user->email', // plus renew_yes for renew

		    'invoice' => uniqid('invoice'),

		    'business' => 'cththemespp-facilitator@gmail.com',
		    
		    // 'email' => 'test@gmail.com',

		    // 'first_name'=>'Test User',

		    // 'last_name'=>$res_post_datas['last_name'],
		  
		    'no_shipping' => '1',

		    'no_note' => '1',

		    'charset' => 'UTF-8',
		    
		    'rm' => '2',//return method / 2: mean post

		    'cancel_return'=> home_url('/cancel'),

		    'return' => home_url('/'),

		    'notify_url' => home_url('/?action=cth_ppipn'),
		    
		);
		// merge with $args array
		$paypal_args = array_merge($paypal_args,$args);
		$paypal_redirect .= http_build_query($paypal_args);

		return $paypal_redirect;

	}

	function validateIPN() {

		// Read POST data
		// reading posted data directly from $_POST causes serialization
		// issues with array data in POST. Reading raw POST data from input stream instead.
		$raw_post_data = file_get_contents('php://input');
		$raw_post_data = explode('&', $raw_post_data);

		$myPost = array();
		foreach ($raw_post_data as $keyval) {
			$keyval = explode ('=', $keyval);
			if (count($keyval) === 2) $myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		$get_magic_quotes_exists = false;
		if(function_exists('get_magic_quotes_gpc')) {
			$get_magic_quotes_exists = true;
		}
		foreach ($myPost as $key => $value) {
			if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
				$value = urlencode(stripslashes($value));
			} else {
				$value = urlencode($value);
			}
			$this->ipn_data["$key"] = $value;
			$req .= "&$key=$value";
		}

		$ch = curl_init($this->paypal_url);
		if ($ch == FALSE) {
			$this->last_error = 'Can not initialize a cURL session';
		 	$this->log_ipn_results(false);   
		 	return false;
		}

		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        //curl_setopt($ch, CURLOPT_SSLCERT, dirname(__FILE__).'/cacert.pem');
        //error_log(dirname(__FILE__).'/cacert.pem', 3, LOG_FILE);
		//[CURLOPT_SSL_VERIFYPEER, false] to fix: Can't connect to PayPal to validate IPN message: SSL connect error

		curl_setopt($ch, CURLOPT_SSLVERSION, 6);

		 
		if($this->debug ) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
		}

		// CONFIG: Optional proxy configuration
		//curl_setopt($ch, CURLOPT_PROXY, $proxy);
		//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);

		// Set TCP timeout to 30 seconds
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

		$res = curl_exec($ch);
		if (curl_errno($ch) != 0) // cURL error
		{
				$this->last_error = "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL;
		 		$this->log_ipn_results(false);   

				curl_close($ch);
				exit;

		} else {
				// Log the entire HTTP response if debug is switched on.
				//$this->last_error = "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL . "HTTP response of validation request: $res" . PHP_EOL;
		 		//$this->log_ipn_results(true); 
				curl_close($ch);

		}

		$this->ipn_response = $res;

		// Inspect IPN validation result and act accordingly
		// Split response headers and payload, a better way for strcmp
		$tokens = explode("\r\n\r\n", trim($res));
		$res = trim(end($tokens));

		if (strcmp ($res, "VERIFIED") === 0) {

			// Valid IPN transaction.
		 	$this->log_ipn_results(true);
		 	// new paypal data
		 	if($this->debug){
		 		$debug_text = "New IPN POST Vars from Paypal:\n";
		 		foreach($_POST as $key => $value) {
				    $debug_text .= "$key=$value\n";
				}
				// Write to log
			  	error_log( $debug_text . PHP_EOL, 3, $this->debug_file );
		 	}
		 	return true;  

		} else if (strcmp ($res, "INVALID") === 0) {
			$this->last_error = "Invalid IPN: $req" . PHP_EOL;
			$this->log_ipn_results(false);
		 	return false;  
		}

		return false;
	  
	}

	function extractPaymentData(){
		$return = array('pm_status'=>false);
		if($this->validateIPN()){
			/*
			Buy Now paypal IPN
			*
			*
			[04/21/2018 11:32 AM] - SUCCESS!
			IPN POST Vars from Paypal:
			transaction_subject=
			payment_date=04%3A31%3A57+Apr+21%2C+2018+PDT
			txn_type=web_accept
			last_name=africa
			residence_country=KE
			item_name=Payment+for+Extended+plan
			payment_gross=108.90
			mc_currency=USD
			business=cththemespp-facilitator%40gmail.com
			payment_type=instant
			protection_eligibility=Eligible
			verify_sign=AouWZGBj3fUJCOzvbAqgWzNyxbbVA4wJfBVW1okAwSRPrH6eh7RB0PSK
			payer_status=unverified
			test_ipn=1
			payer_email=kipkongetich45%40gmail.com
			txn_id=7T4407494W946960S
			quantity=1
			receiver_email=cththemespp-facilitator%40gmail.com
			first_name=tender
			invoice=invoice5adb20de70f29
			payer_id=SDL37W555P7HE
			receiver_id=Q4TWE2LK79LAU
			item_number=2319
			payer_business_name=tenderafrica
			payment_status=Completed
			payment_fee=4.55
			mc_fee=4.55
			mc_gross=108.90
			custom=2425%7C0%7C29%7Ckipkongetich45%40gmail.com%7Crenew_no
			charset=windows-1252
			notify_version=3.9
			ipn_track_id=2f3f08e7f18f

			IPN Response from Paypal Server:
			 HTTP/1.1 200 OK
			Date: Sat, 21 Apr 2018 11:32:10 GMT
			Server: Apache
			X-Frame-Options: SAMEORIGIN
			Set-Cookie: c9MWDuvPtT9GIMyPc3jwol1VSlO=QfxshsUYv-KDto5JKIbl93DEh6W5XaSgSSE55ZtwQwZYEq-3S7IVyUcyc_3R1hdWba7FBqVe-pssJTvlyFS45JEUm1Gn-PnjZWx8uVxgGjl5XiJFI5cZMMnjGEikqk9nWti3Xv9A7LKhBVGk_Bcr2XCcs56bs7Lz9i6aWUBhSyk_H7-Xqj5DK2BTX5tn1aGUjcnM4ED7AgAjYDvAPIs99qY_kmBLz2arCpqD2y-6qgCS5CHMk7McUw6zPyrX7Urh2OGOiZ3f3lu-GYw8uaKhKtMcSFinCvqB7WTQ26SUmhUlnUpxRWYYZC9Jn67MNorrsBnQv2kPotrlBWpX1iZ7nt45oaTYJa4NaWf7eu7j1Ai_U7sg8Z9HbrPCMcKzADtBT4hBSL14nL10pPtf4FJVr7PwPNvXNTDtRcZD6WwrVr07_PUvXxbWWZhowgS; domain=.paypal.com; path=/; Secure; HttpOnly
			Set-Cookie: cookie_check=yes; expires=Tue, 18-Apr-2028 11:32:11 GMT; domain=.paypal.com; path=/; Secure; HttpOnly
			Set-Cookie: navcmd=_notify-validate; domain=.paypal.com; path=/; Secure; HttpOnly
			Set-Cookie: navlns=0.0; expires=Mon, 20-Apr-2020 11:32:11 GMT; domain=.paypal.com; path=/; Secure; HttpOnly
			Set-Cookie: Apache=10.72.108.11.1524310330721339; path=/; expires=Mon, 13-Apr-48 11:32:10 GMT
			Vary: Accept-Encoding,User-Agent
			Connection: close
			HTTP_X_PP_AZ_LOCATOR: sandbox.slc
			Paypal-Debug-Id: c60c53ffacfa2
			Set-Cookie: X-PP-SILOVER=name%3DSANDBOX3.WEB.1%26silo_version%3D1880%26app%3Dappdispatcher%26TIME%3D975297370%26HTTP_X_PP_AZ_LOCATOR%3Dsandbox.slc; Expires=Sat, 21 Apr 2018 12:02:11 GMT; domain=.paypal.com; path=/; Secure; HttpOnly
			Set-Cookie: X-PP-SILOVER=; Expires=Thu, 01 Jan 1970 00:00:01 GMT
			Transfer-Encoding: chunked
			Content-Type: text/html; charset=UTF-8
			Strict-Transport-Security: max-age=63072000

			VERIFIED
			*/

			/*
			Recurring paypal IPN
			*
			*
			VERIFIED
			[04/28/2018 3:40 PM] - SUCCESS!
			IPN POST Vars from Paypal:
			txn_type=subscr_signup
			subscr_id=I-N2N03H0KJJ1A
			last_name=Buyer
			residence_country=US
			mc_currency=USD
			item_name=Payment+for+Basic+subscription+plan
			business=cththemespp-facilitator%40gmail.com
			amount3=53.90
			recurring=1
			verify_sign=Ao68DNqlX5gVaPZlGVYk.BmBnaqJAM3G9JMfWXHPv6MUFc7TSoB4DSC0
			payer_status=verified
			test_ipn=1
			payer_email=cththemespp-buyer%40gmail.com
			first_name=Test
			receiver_email=cththemespp-facilitator%40gmail.com
			payer_id=WJ7B29UEC53XU
			invoice=invoice5ae495b00be0a -- use for laster
			reattempt=1
			item_number=2317
			subscr_date=08%3A40%3A27+Apr+28%2C+2018+PDT
			custom=2537%7C0%7C78%7Ctest%40gmail.com%7Crenew_no%7Csubscription_yes
			charset=windows-1252
			notify_version=3.9
			period3=1+D
			mc_amount3=53.90
			ipn_track_id=7a82faaad7c7d

			IPN Response from Paypal Server:
			 HTTP/1.1 200 OK
			Date: Sat, 28 Apr 2018 15:40:39 GMT
			Server: Apache
			X-Frame-Options: SAMEORIGIN
			Set-Cookie: c9MWDuvPtT9GIMyPc3jwol1VSlO=xWuQGcaSUnQCk_HUwWdU2vd3xyqYxXu6eWcRDVKK8kVisXM-pn8q8P60peBMTMazYloo_2bW7N8gvGvSh4dKo06kMMFQhC48bQjulmsWmYX0bhiiGZYzRdGhVZJkBB3dq-zTJD-XqvAfPuNZnVG-DjZfrfJCfk7u15AipaPKba8kMRw3CjSlQDwcs4SXn7UBcEXwrfh1bwyNd1oKl9g9WbHF61wEHaNA8v5yqI3PPrunikLvxMu1eTlFc7lpiAZUCffCbrhYeYHF64oQcRwfngtqLkREbL0Kkm7MEKoifOAo7Hdst-0sIoAotRHJ7wX-56Ufi2zukAIiuMQwL64RZXZezxDW5ex5Mvpqp9tcITqxj6vcUe7BEF88DmZQY_o92sMm4OZO7JyHDY7mAc6tvHP1W-1gbrs9CXALrX0DNYjwFO_RKLxdGtUmHnu; domain=.paypal.com; path=/; Secure; HttpOnly
			Set-Cookie: cookie_check=yes; expires=Tue, 25-Apr-2028 15:40:39 GMT; domain=.paypal.com; path=/; Secure; HttpOnly
			Set-Cookie: navcmd=_notify-validate; domain=.paypal.com; path=/; Secure; HttpOnly
			Set-Cookie: navlns=0.0; expires=Mon, 27-Apr-2020 15:40:39 GMT; domain=.paypal.com; path=/; Secure; HttpOnly
			Set-Cookie: Apache=10.72.108.11.1524930039664674; path=/; expires=Mon, 20-Apr-48 15:40:39 GMT
			Vary: Accept-Encoding,User-Agent
			Connection: close
			HTTP_X_PP_AZ_LOCATOR: sandbox.slc
			Paypal-Debug-Id: c64371439f501
			Set-Cookie: X-PP-SILOVER=name%3DSANDBOX3.WEB.1%26silo_version%3D1880%26app%3Dappdispatcher%26TIME%3D4153795674%26HTTP_X_PP_AZ_LOCATOR%3Dsandbox.slc; Expires=Sat, 28 Apr 2018 16:10:39 GMT; domain=.paypal.com; path=/; Secure; HttpOnly
			Set-Cookie: X-PP-SILOVER=; Expires=Thu, 01 Jan 1970 00:00:01 GMT
			Transfer-Encoding: chunked
			Content-Type: text/html; charset=UTF-8
			Strict-Transport-Security: max-age=63072000

			VERIFIED

			*/

			/*
			Recurring second payment
			*
			[04/29/2018 2:26 PM] - SUCCESS!
			IPN POST Vars from Paypal:
			transaction_subject=Payment+for+Basic+subscription+plan
			payment_date=07%3A25%3A25+Apr+29%2C+2018+PDT
			txn_type=subscr_payment
			subscr_id=I-N2N03H0KJJ1A
			last_name=Buyer
			residence_country=US
			item_name=Payment+for+Basic+subscription+plan
			payment_gross=53.90
			mc_currency=USD
			business=cththemespp-facilitator%40gmail.com
			payment_type=instant
			protection_eligibility=Eligible
			verify_sign=ACJr8dQB6BzCFYnO0jL1htowp1qJA2RDVtZ6k.A3v4KtKTXGtVq9JWAj
			payer_status=verified
			test_ipn=1
			payer_email=cththemespp-buyer%40gmail.com
			txn_id=55P46858BT577224H
			receiver_email=cththemespp-facilitator%40gmail.com
			first_name=Test
			invoice=invoice5ae495b00be0a
			payer_id=WJ7B29UEC53XU
			receiver_id=Q4TWE2LK79LAU
			item_number=2317
			payment_status=Completed
			payment_fee=1.86
			mc_fee=1.86
			mc_gross=53.90
			custom=2537%7C0%7C78%7Ctest%40gmail.com%7Crenew_no%7Csubscription_yes
			charset=windows-1252
			notify_version=3.9
			ipn_track_id=0bf8d8b29437a

			IPN Response from Paypal Server:
			 HTTP/1.1 200 OK
			Date: Sun, 29 Apr 2018 14:26:05 GMT
			Server: Apache
			X-Frame-Options: SAMEORIGIN
			Set-Cookie: c9MWDuvPtT9GIMyPc3jwol1VSlO=tGfvtSj6tAC-8xAo3T86yfwdXqVZbXUQeOHGYe3MV1UR6lcZZfSS1rG3bN3XapoDMS1Seni3sotvL6lKjYoKDFdPaXPn_RKtoNdoEPAhLjKwX-tONNWmnd8HB4Ezc2KmL5B2JqkWb0NuO9Z_U18P5qVqQj_JYT6nsBYGjJbZ5mz9bMA1D0TUWzSAJ2otxmE9sAr4n4pjgfjGg3QCBGXSR_dxKbyXoKS0gM7-nc3A0LGJeaj5T6V7dbBZeWsmkfGu7kvazkGMtFTkklUG8Yc2GF5P53vLA0YHZfJZU9KCjJa24jpo6uAvKqNNR0rUm6Ot0aZC1Aq3DOUdGepTUP3ZV2k1QP3nc48di9BGUMjqV8hHxmS4iiO_c2WCbmMQojBKIoemOkxuveSGBcZlx28yXfwQyITVLe0DH6BsHwFK4fq84dO4iFefP0OWtiG; domain=.paypal.com; path=/; Secure; HttpOnly
			Set-Cookie: cookie_check=yes; expires=Wed, 26-Apr-2028 14:26:06 GMT; domain=.paypal.com; path=/; Secure; HttpOnly
			Set-Cookie: navcmd=_notify-validate; domain=.paypal.com; path=/; Secure; HttpOnly
			Set-Cookie: navlns=0.0; expires=Tue, 28-Apr-2020 14:26:06 GMT; domain=.paypal.com; path=/; Secure; HttpOnly
			Set-Cookie: Apache=10.72.108.11.1525011965742988; path=/; expires=Tue, 21-Apr-48 14:26:05 GMT
			Vary: Accept-Encoding,User-Agent
			Connection: close
			HTTP_X_PP_AZ_LOCATOR: sandbox.slc
			Paypal-Debug-Id: dc11d7ccb228b
			Set-Cookie: X-PP-SILOVER=name%3DSANDBOX3.WEB.1%26silo_version%3D1880%26app%3Dappdispatcher%26TIME%3D4258653530%26HTTP_X_PP_AZ_LOCATOR%3Dsandbox.slc; Expires=Sun, 29 Apr 2018 14:56:06 GMT; domain=.paypal.com; path=/; Secure; HttpOnly
			Set-Cookie: X-PP-SILOVER=; Expires=Thu, 01 Jan 1970 00:00:01 GMT
			Transfer-Encoding: chunked
			Content-Type: text/html; charset=UTF-8
			Strict-Transport-Security: max-age=63072000

			VERIFIED
			*/

			$paypal_data_default = array(
				'custom' => '',
				'payment_status' => '',
				'invoice' => '',
				'first_name' => '',
				'last_name' => '',
				'item_name' => '',
				'item_number' => '',
				'mc_gross' => '',
				'mc_fee' => '',
				'mc_currency' => '',
				'txn_id' => '',
				'receiver_email' => '',
				'payer_email' => '',
				'quantity' => '',
				'payment_date' => '',
			);

			// https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNandPDTVariables/
			// get file id that was just purchased
			$paypal_data 		= $this->ipn_data;
			// related to subscription controller
			// $payment_invoice 	= $paypal_data['invoice'];
			//Customer's first name - Length: 64 characters
			// $first_name 		= urldecode( $paypal_data['first_name'] );
			//Customer's last name - Length: 64 characters
			// $last_name 			= urldecode( $paypal_data['last_name'] );
			//Item name as passed by you, the merchant - plan title
			// $plan_title 		= urldecode( $paypal_data['item_name'] );
			//Pass-through variable for you to track purchases.
			// $plan_id 			= $paypal_data['item_number']; //plan id
			// The status of the payment: Canceled_Reversal | Completed | Created | Pending
			// $payment_status 	= $paypal_data['payment_status'];
			//Full amount of the customer's payment, before transaction fee is subtracted. Equivalent to payment_gross for USD payments. If this amount is negative, it signifies a refund or reversal, and either of those payment statuses can be for the full or partial amount of the original transaction.
			// $payment_amount 	= $paypal_data['mc_gross'];
			//Transaction fee associated with the payment. mc_gross minus mc_fee equals the amount deposited into the receiver_email account. Equivalent to payment_fee for USD payments. If this amount is negative, it signifies a refund or reversal, and either of those payment statuses can be for the full or partial amount of the original transaction fee.
			// $payment_fee 		= $paypal_data['mc_fee'];
			// For payment IPN notifications, this is the currency of the payment.
			// $payment_currency 	= $paypal_data['mc_currency'];
			// The merchant's original transaction identification number for the payment from the buyer, against which the case was registered.
			// $txn_id 			= $paypal_data['txn_id'];
			// Primary email address of the payment recipient (that is, the merchant). If the payment is sent to a non-primary email address on your PayPal account, the receiver_email is still your primary email.
			// $receiver_email 	= urldecode( $paypal_data['receiver_email'] );
			// Customer's primary email address. Use this email to provide any credits. 
			// $payer_email 		= urldecode( $paypal_data['payer_email'] );
			// Quantity as entered by your customer or as passed by you, the merchant. If this is a shopping cart transaction, PayPal appends the number of the item 
			// $quantity 			= $paypal_data['quantity'];

			// merger with default data

			$paypal_data 				= array_merge($paypal_data_default, $paypal_data);

			// Custom value as passed by you, the merchant. These are pass-through variables that are never presented to your customer 
			$custom 					= urldecode( $paypal_data['custom'] );//order_id|user_id|user_email|renew_yes
			$custom 					= explode("|", $custom);
			$order_id 					= isset($custom[0])? $custom[0] : '';
			$listing_id 				= $paypal_data['item_number'];

			$user_id 					= isset($custom[1])? $custom[1] : '';
			$user_email 				= isset($custom[2])? $custom[2] : '';

			$renew_subscription = false;

			if(isset($custom[3]) && $custom[3] === 'renew_yes') $renew_subscription = true;

			// for recurring subscription
			$recurring_subscription = false;

			if(isset($custom[4]) && $custom[4] === 'subscription_yes') $recurring_subscription = true;

			// for new ad campaign
			$for_listing_ad = false;
			if(isset($custom[5]) && $custom[5] === 'ad_yes') $for_listing_ad = true;




			// Time/Date stamp generated by PayPal, in the following format: HH:MM:SS Mmm DD, YYYY PDT
			// $payment_date 		= urldecode( $paypal_data['payment_date'] );



			$return = array(
				'pm_status'   				=> $paypal_data['payment_status'],
				// related to subscription controller
				'pm_invoice'				=> $paypal_data['invoice'],
				//Customer's first name - Length: 64 characters
				'first_name'				=> urldecode( $paypal_data['first_name'] ),
				//Customer's last name - Length: 64 characters
				'last_name'					=> urldecode( $paypal_data['last_name'] ),
				//Item name as passed by you, the merchant - plan title
				'item_name'					=> urldecode( $paypal_data['item_name'] ),
				//Pass-through variable for you to track purchases.
				'item_number'				=> $paypal_data['item_number'], //listing id
				//Full amount of the customer's payment, before transaction fee is subtracted. Equivalent to payment_gross for USD payments. If this amount is negative, it signifies a refund or reversal, and either of those payment statuses can be for the full or partial amount of the original transaction.
				'pm_amount'   				=> $paypal_data['mc_gross'],
				//Transaction fee associated with the payment. mc_gross minus mc_fee equals the amount deposited into the receiver_email account. Equivalent to payment_fee for USD payments. If this amount is negative, it signifies a refund or reversal, and either of those payment statuses can be for the full or partial amount of the original transaction fee.
				'pm_fee'   					=> $paypal_data['mc_fee'],
				// For payment IPN notifications, this is the currency of the payment.
				'pm_currency'   			=> $paypal_data['mc_currency'],
				// The merchant's original transaction identification number for the payment from the buyer, against which the case was registered.
				'txn_id'   					=> $paypal_data['txn_id'],
				// Primary email address of the payment recipient (that is, the merchant). If the payment is sent to a non-primary email address on your PayPal account, the receiver_email is still your primary email.
				'receiver_email'   			=> urldecode( $paypal_data['receiver_email'] ),
				// Customer's primary email address. Use this email to provide any credits. 
				'payer_email'   			=> urldecode( $paypal_data['payer_email'] ),
				// Quantity as entered by your customer or as passed by you, the merchant. If this is a shopping cart transaction, PayPal appends the number of the item 
				'quantity'   				=> $paypal_data['quantity'],
				// Time/Date stamp generated by PayPal, in the following format: HH:MM:SS Mmm DD, YYYY PDT
				'pm_date'   				=> urldecode( $paypal_data['payment_date'] ),

				// Custom value as passed by you, the merchant. These are pass-through variables that are never presented to your customer 
				'order_id' 					=> $order_id,
				'listing_id' 				=> $listing_id,
				'user_id' 					=> $user_id,
				'user_email' 				=> $user_email,
				'renew_subscription' 		=> $renew_subscription,
				// for recurring subscription
				'recurring_subscription' 	=> $recurring_subscription,

				'subscription_id'			=> (isset($paypal_data['subscr_id']) ? $paypal_data['subscr_id'] : ''),
				// https://code.tutsplus.com/tutorials/how-to-set-up-recurring-payments--net-30168

				// for listing ad campaign
				'for_listing_ad' 			=> $for_listing_ad,

				'payment_datas'				=> $paypal_data,

			);

			// check for free trial
			if( isset($paypal_data['mc_amount1']) && 0 == (float)$paypal_data['mc_amount1'] ){
				$return['pm_status'] = 'Trialing';

				$return['pm_amount'] = $paypal_data['mc_amount3'];
				$return['is_trialing'] = true;
				$return['trail_amount'] = 0.00;
				$return['trail_period'] = $paypal_data['period1'];
			}

		}

		return $return;
	}
	
	function log_ipn_results($success) {
	   
	  	if (!$this->debug) return;  // is logging turned off?
	  
	  	// Timestamp
	  	$text = '['.date('m/d/Y g:i A').'] - '; 
	  
	  	// Success or failure being logged?
	  	if ($success) $text .= "SUCCESS!\n";
	  	else $text .= 'FAIL: '.$this->last_error."\n";
	  
	  	// Log the POST variables
	  	$text .= "IPN POST Vars from Paypal:\n";
	  	foreach ($this->ipn_data as $key=>$value) {
		 	$text .= "$key=$value\n";
	  	}
	
	  	// Log the response from the paypal server
	  	$text .= "\nIPN Response from Paypal Server:\n ".$this->ipn_response;
	  
	  	// Write to log
	  	error_log( $text . PHP_EOL, 3, $this->debug_file );
	}


}


		