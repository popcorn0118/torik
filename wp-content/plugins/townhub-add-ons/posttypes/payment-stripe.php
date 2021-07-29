<?php
/* add_ons_php */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// load the stripe libraries
require_once ESB_ABSPATH .'inc/classes/stripe-php/init.php';

// IMPORTANT - default user is logged in
class CTH_Payment_Stripe{
	protected $last_error = '';                 // holds the last error encountered
	
	// protected $test_mode = townhub_addons_get_option('payments_test_mode') == 'yes'? true : false ;                    // bool: log IPN results to text file?
	protected $debug = ESB_DEBUG;                    
	
	protected $debug_file = './stripe.log';               // filename of the webhoook log
	// protected $secret_key = '';               // stripe secret key  
	protected $ipn_response = '';               // holds the IPN response from paypal   
	protected $ipn_data = array();         // array contains the POST values for IPN
	       // array holds the fields to submit to paypal

	protected $client;
	protected $pmethods = array('card');

	function __construct($cmd = '') {
	
		// $this->secret_key = townhub_addons_get_option('payments_test_mode') == 'yes'? townhub_addons_get_option('payments_stripe_test_secret') : townhub_addons_get_option('payments_stripe_live_secret');
		$secret_key = townhub_addons_get_option('payments_test_mode') == 'yes'? townhub_addons_get_option('payments_stripe_test_secret') : townhub_addons_get_option('payments_stripe_live_secret');

		// Use Stripe's library to make requests...
		// \Stripe\Stripe::setApiKey( $this->secret_key );
		// \Stripe\Stripe::setApiKey( $secret_key );

		// Initialize the API key
		$this->client = new \Stripe\StripeClient($secret_key);

		$pm_methods = townhub_addons_get_option('stripe_pm_methods');
		if( !empty($pm_methods) ) $this->pmethods = array_values(array_filter($pm_methods));
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
	// https://stripe.com/docs/api/php#create_customer
	function createCustomer($args = array(), $add_meta = true){
		$current_user = wp_get_current_user(); 
		$default = array(
			'email' => $current_user->user_email,
			// 'source' => 'src_18eYalAHEMiOZZp1l9ZTjSU0',
			'metadata' => array( "first_name" => $current_user->user_firstname, "last_name" => $current_user->user_lastname, "display_name" => $current_user->display_name ), // You can have up to 20 keys, with key names up to 40 characters long and values up to 500 characters long.
		);
		$args = array_merge($default, $args);

		$customer = false;

		try {
			error_log(date('[Y-m-d H:i e] - '). "Customer args: " . json_encode($args) . PHP_EOL, 3, $this->debug_file);
			$customer = \Stripe\Customer::create($args);

			if($add_meta && isset($customer->id)){
				// add customer id to use later
				add_user_meta( $current_user->ID , ESB_META_PREFIX.'stripe_customer_id', $customer->id);
			}
		}catch (Exception $e) {
		  	// Something else happened, completely unrelated to Stripe
			if($this->debug) error_log(date('[Y-m-d H:i e] - '). "Stripe create customer error: " . $e->getMessage() . PHP_EOL, 3, $this->debug_file);
		}

		return $customer;
	}
	//
	function getCustomer($id = ''){
		if(!$id) return false;
		$customer = \Stripe\Customer::retrieve($id);
		// check if the customer is deleted
		if(isset($customer->deleted) && $customer->deleted == true) return 'deleted';

		return $customer;
	}

	function getUserCustomerId(){
		$customer_id = false;
		if( is_user_logged_in() ){
			$customer_id = get_user_meta( get_current_user_id(), ESB_META_PREFIX.'stripe_customer_id', true );
		}

		return $customer_id;
	}

	function createOneTimeSession($args = array()){
		try{
			// Create a Checkout Session
			$session = $this->client->checkout->sessions->create(array(
			  
			  'payment_method_types' => $this->pmethods,
			  'line_items' => array(
			  	array(
			  		'price_data'	=> array(
			  			'currency'		=> strtolower(townhub_addons_get_option('currency','USD')),
			  			'product_data' 	=> array(
				          'name' => $args['item_name'],
				        ),
				        // 'unit_amount' 	=> $args['amount'],
				        'unit_amount_decimal' 	=> $args['amount_decimal'],
				        
			  		),
			  		'quantity' 		=> 1,
			  	),
			  ),
			  'metadata'	=> $args['metadata'],
			  'mode' 		=> 'payment',
			  'success_url' => get_permalink(townhub_addons_get_option('checkout_success')),
			  'cancel_url' 	=> home_url('/'),
			));
			return $session;
		}catch(Exception $e){
			return new WP_Error('stripe_session', $e->getMessage() );
		}
			
	}


	function processOneTime($args = array()){
		// check if current user is a Stripe customer
		$customer_id = $this->getUserCustomerId();
		if($this->debug) $customer_id = false ;
		if($customer_id == false){
			// create new customer
			$customer_args = array(
				// 'email' => default is current user
				// retrieve the token generated by stripe.js
				'source' => isset($_POST['stripeToken'])?$_POST['stripeToken']:$args['stripeToken'],
			);

		    $customer = $this->createCustomer($customer_args);

		    $customer_id = $customer->id;
		}

		$default_args = array(
			'customer' => $customer_id,
			'currency' => strtolower(townhub_addons_get_option('currency','USD')),
		);
		unset($args['stripeToken']);
		$args = array_merge($default_args, $args);

		// create charge
		$charge = false;
		try{
			error_log(date('[Y-m-d H:i e] - '). "Charge args: " . json_encode($args) . PHP_EOL, 3, $this->debug_file);
			$charge = \Stripe\Charge::create( $args );
		}catch (Exception $e) {
		  	// Something else happened, completely unrelated to Stripe
		  	$charge = array(
		  		'success'	=> false,
		  		'error'		=> $e->getMessage(),
		  	);
			if($this->debug) error_log(date('[Y-m-d H:i e] - '). "Stripe create charge error: " . $e->getMessage() . PHP_EOL, 3, $this->debug_file);
		}

		return $charge;
	}

	function createRecurringSession($args = array()){
		try{
			// Create a Checkout Session
			$session = $this->client->checkout->sessions->create(array(
			  
			  'payment_method_types' => $this->pmethods,
			  'line_items' => array(
			  	array(
			  		'price'			=> $args['plan_id'],
			  		'quantity' 		=> 1,
			  	),
			  ),
			  'metadata'	=> $args['metadata'],
			  'mode' 		=> 'subscription',
			  'subscription_data'	=> array(
			  		'metadata'	=> $args['metadata'],
			  ),
			  'success_url' => get_permalink(townhub_addons_get_option('checkout_success')),
			  'cancel_url' 	=> home_url('/'),
			));
			return $session;
		}catch(Exception $e){
			return new WP_Error('stripe_session', $e->getMessage() );
		}
			
	}

	function processRecurring($args = array()){
		// check if current user is a Stripe customer
		$customer_id = $this->getUserCustomerId();
		if($this->debug) $customer_id = false ;
		if($customer_id == false){
			// create new customer
			$customer_args = array(
				// 'email' => default is current user
				// retrieve the token generated by stripe.js
				'source' => $_POST['stripeToken'],
			);

		    $customer = $this->createCustomer($customer_args);

		    $customer_id = $customer->id;
		}

		$default_args = array(
			'customer' => $customer_id,
		);

		$args = array_merge($default_args, $args);

		// create subscription
		$subscription = false;
		try{
			error_log(date('[Y-m-d H:i e] - '). "Subscription args: " . json_encode($args) . PHP_EOL, 3, $this->debug_file);
			$subscription = \Stripe\Subscription::create( $args );
		}catch (Exception $e) {
		  	// Something else happened, completely unrelated to Stripe
			if($this->debug) error_log(date('[Y-m-d H:i e] - '). "Stripe create subscription error: " . $e->getMessage() . PHP_EOL, 3, $this->debug_file);
		}

		return $subscription;


	}

	// https://stripe.com/docs/api#events
	function checkWebHooksNew(){
		$payload = @file_get_contents('php://input');
		$event = null;
		try {
		    $event = \Stripe\Event::constructFrom(
		        json_decode($payload, true)
		    );
		} catch(\UnexpectedValueException $e) {
		    // Invalid payload
		    http_response_code(400);
		    exit();
		}	

		$eventdataobj = $event->data->object;
		if($this->debug){
			error_log(date('[Y-m-d H:i e] - '). "Event type: " . $event->type . PHP_EOL, 3, $this->debug_file);
			error_log(date('[Y-m-d H:i e] - '). "Event object: " . json_encode($eventdataobj) . PHP_EOL, 3, $this->debug_file);
		}
		// Handle the event
		// check if event target for TownHub
		if(isset($eventdataobj->metadata['esb_plan_id'])){
			//////////// FOR ONE TIME PAYMENT ///////////////////////
			// successful payment/ or new subscription done
			// if($event->type == 'charge.succeeded') {
			if($event->type == 'checkout.session.completed' && $eventdataobj->payment_status == 'paid' && $eventdataobj->mode != 'subscription' ) {
				// subscription
				// if( $eventdataobj->mode == 'subscription' ){
				// 	$subscription_obj = $this->client->subscriptions->retrieve($eventdataobj->subscription);
				// 	$data = array(
				// 		'pm_status'					=> 'completed',
				// 		'user_id' 					=> $eventdataobj->metadata['user_id'],
				// 		'item_number' 				=> $eventdataobj->metadata['esb_plan_id'], // this is listing plan id
				// 		'pm_date' 					=> $subscription_obj->created, // Time at which the object was created. Measured in seconds since the Unix epoch.
				// 		'order_id' 					=> $eventdataobj->metadata['order_id'],
				// 		'recurring_subscription' 	=> true, // not used

				// 		'txn_id' 					=> $eventdataobj->id, // invoice id

				// 		// for stripe period
				// 		'payment_method' 			=> 'stripe',
				// 		'period_start' 				=> $subscription_obj->current_period_start,
				// 		'period_end' 				=> $subscription_obj->current_period_end,

				// 		'subscription_id' 			=> $subscription_obj->id,

				// 	);
				// 	// check if amount_due == 0 for invalid charge or free trial
				// 	if($subscription_obj->status === 'trialing'){
				// 		$data['pm_status'] = 'trialing';
				// 	}

				// 	if( isset($eventdataobj->metadata['for_listing_ad']) &&  $eventdataobj->metadata['for_listing_ad'] == 'yes' ){
				// 		if($this->debug) error_log(date('[Y-m-d H:i e] - '). "Is listing ad subscription webhook" . PHP_EOL, 3, $this->debug_file);
				// 		$data['for_listing_ad'] = 'yes';
				// 		// townhub_add_ons_active_ad_campaign($data, true);

				// 	}else{
				// 		if($this->debug) error_log(date('[Y-m-d H:i e] - '). "Is membership subscription webhook" . PHP_EOL, 3, $this->debug_file);
						
    //                     Esb_Class_Membership::active_membership($data, true);
				// 	}
					
				// }else{
					$data = array(
						'pm_status'					=> 'completed',
						'user_id' 					=> $eventdataobj->metadata['user_id'],
						'item_number' 				=> $eventdataobj->metadata['esb_plan_id'], // this is listing plan id
						'pm_date' 					=> date_i18n("Y-m-d"), // $eventdataobj->created, // or use start for correction
						'order_id' 					=> $eventdataobj->metadata['order_id'],
						'recurring_subscription' 	=> false, // not used

						// need to update order/subscription transaction for one time payment
						// for one time payment is balance_transaction data
						'payment_method' 			=> 'stripe',
						'txn_id' 					=> '', // $eventdataobj->balance_transaction,

					);

					$order_id = $eventdataobj->metadata['order_id'];
					$order_post_type = $eventdataobj->metadata['order_post_type'];
					if($order_post_type == 'lbooking'){
	                    Esb_Class_Booking::approve_booking($order_id);
					}elseif($order_post_type == 'cthads'){
	                    Esb_Class_ADs::active_ad($order_id);
					}elseif($order_post_type == 'lorder'){
						Esb_Class_Membership::active_membership($data, false);
					}

					
				// }
					
				
			}
			// end charge.succeeded

			// failed payment
			if($event->type == 'charge.failed') {

			}
			// end charge.failed

			//////////// FOR RECURRING PAYMENT ///////////////////////
			//subscription created
			if($event->type == 'customer.subscription.created'){
				
			}
			// end customer.subscription.created

			//update subscription when end trial
			if($event->type == 'customer.subscription.updated'){
				
			}
			// end customer.subscription.updated

			//update subscription when end trial
			if($event->type == 'customer.subscription.trial_will_end'){

			}
			// end customer.subscription.trial_will_end

			//subscription canceled event
			if($event->type == 'customer.subscription.deleted'){

			}
			// end customer.subscription.deleted
		}
		// end check correct event target

		// for event outsite esb_plan_id metadata
		//subscription payment succeeded
		// checkout.session.completed
		if($event->type == 'invoice.payment_succeeded'){
			//update subscription post type
			if(isset($eventdataobj->subscription)){
				// $subscription_obj = \Stripe\Subscription::retrieve($eventdataobj->subscription);
				$subscription_obj = $this->client->subscriptions->retrieve($eventdataobj->subscription);
				// check if event target for TownHub
				if(isset($subscription_obj->metadata['esb_plan_id'])){

					$data = array(
						'pm_status'					=> 'completed',
						'user_id' 					=> $subscription_obj->metadata['user_id'],
						'item_number' 				=> $subscription_obj->metadata['esb_plan_id'], // this is listing plan id
						'pm_date' 					=> $subscription_obj->created, // Time at which the object was created. Measured in seconds since the Unix epoch.
						'order_id' 					=> $subscription_obj->metadata['order_id'],
						'recurring_subscription' 	=> true, // not used

						'txn_id' 					=> $eventdataobj->id, // invoice id

						// for stripe period
						'payment_method' 			=> 'stripe',
						'period_start' 				=> $subscription_obj->current_period_start,
						'period_end' 				=> $subscription_obj->current_period_end,

						'subscription_id' 			=> $subscription_obj->id,

					);
					// check if amount_due == 0 for invalid charge or free trial
					if($subscription_obj->status === 'trialing'){
						$data['pm_status'] = 'trialing';
					}

					if( isset($eventdataobj->metadata['for_listing_ad']) &&  $eventdataobj->metadata['for_listing_ad'] == 'yes' ){
						if($this->debug) error_log(date('[Y-m-d H:i e] - '). "Is listing ad subscription webhook" . PHP_EOL, 3, $this->debug_file);
						$data['for_listing_ad'] = 'yes';
						// townhub_add_ons_active_ad_campaign($data, true);

					}else{
						if($this->debug) error_log(date('[Y-m-d H:i e] - '). "Is membership subscription webhook" . PHP_EOL, 3, $this->debug_file);
						
                        Esb_Class_Membership::active_membership($data, true);
					}

					

				}
				// end check metadata
			}
			// end check invoice is for subscription
		}
		// end invoice.payment_succeeded event

		http_response_code(200); // PHP 5.4 or greater
		exit;
	}
	function checkWebHooks(){
		// Retrieve the request's body and parse it as JSON
		$input = @file_get_contents("php://input");
		$event_json = json_decode($input);
		// check if correct Stripe event
		if(isset($event_json->id)) {
			try{

				// Verify the event by fetching it from Stripe
				$event = \Stripe\Event::retrieve($event_json->id);
				// echo'<pre>';
				// var_dump($event);
				// Do something with $event

				$eventdataobj = $event->data->object;

				if($this->debug){
					error_log(date('[Y-m-d H:i e] - '). "Event type: " . $event->type . PHP_EOL, 3, $this->debug_file);
					error_log(date('[Y-m-d H:i e] - '). "Event object: " . json_encode($eventdataobj) . PHP_EOL, 3, $this->debug_file);
				}
				// check if event target for TownHub
				if(isset($eventdataobj->metadata['esb_plan_id'])){
					//////////// FOR ONE TIME PAYMENT ///////////////////////
					// successful payment/ or new subscription done
					if($event->type == 'charge.succeeded') {
						$data = array(
							'pm_status'					=> 'completed',
							'user_id' 					=> $eventdataobj->metadata['user_id'],
							'item_number' 				=> $eventdataobj->metadata['esb_plan_id'], // this is listing plan id
							'pm_date' 					=> $eventdataobj->created, // or use start for correction
							'order_id' 					=> $eventdataobj->metadata['order_id'],
							'recurring_subscription' 	=> false, // not used

							// need to update order/subscription transaction for one time payment
							// for one time payment is balance_transaction data
							'payment_method' 			=> 'stripe',
							'txn_id' 					=> $eventdataobj->balance_transaction

						);

						$order_id = $eventdataobj->metadata['order_id'];
						$order_post_type = $eventdataobj->metadata['order_post_type'];
						if($order_post_type == 'lbooking'){
		                    Esb_Class_Booking::approve_booking($order_id);
						}elseif($order_post_type == 'cthads'){
		                    Esb_Class_ADs::active_ad($order_id);
						}elseif($order_post_type == 'lorder'){
							Esb_Class_Membership::active_membership($data, true);
						}
						
					}
					// end charge.succeeded

					// failed payment
					if($event->type == 'charge.failed') {

					}
					// end charge.failed

					//////////// FOR RECURRING PAYMENT ///////////////////////
					//subscription created
					if($event->type == 'customer.subscription.created'){
						
					}
					// end customer.subscription.created

					//update subscription when end trial
					if($event->type == 'customer.subscription.updated'){
						
					}
					// end customer.subscription.updated

					//update subscription when end trial
					if($event->type == 'customer.subscription.trial_will_end'){

					}
					// end customer.subscription.trial_will_end

					//subscription canceled event
					if($event->type == 'customer.subscription.deleted'){

					}
					// end customer.subscription.deleted
				}
				// end check correct event target

				// for event outsite esb_plan_id metadata
				//subscription payment succeeded
				if($event->type == 'invoice.payment_succeeded'){
					//update subscription post type
					if(isset($eventdataobj->subscription)){
						$subscription_obj = \Stripe\Subscription::retrieve($eventdataobj->subscription);
						// check if event target for TownHub
						if(isset($subscription_obj->metadata['esb_plan_id'])){

							$data = array(
								'pm_status'					=> 'completed',
								'user_id' 					=> $subscription_obj->metadata['user_id'],
								'item_number' 				=> $subscription_obj->metadata['esb_plan_id'], // this is listing plan id
								'pm_date' 					=> $subscription_obj->created, // Time at which the object was created. Measured in seconds since the Unix epoch.
								'order_id' 					=> $subscription_obj->metadata['order_id'],
								'recurring_subscription' 	=> true, // not used

								'txn_id' 					=> $eventdataobj->id, // invoice id

								// for stripe period
								'payment_method' 			=> 'stripe',
								'period_start' 				=> $subscription_obj->current_period_start,
								'period_end' 				=> $subscription_obj->current_period_end,

								'subscription_id' 			=> $subscription_obj->id,

							);
							// check if amount_due == 0 for invalid charge or free trial
							if($subscription_obj->status === 'trialing'){
								$data['pm_status'] = 'trialing';
							}

							if( isset($eventdataobj->metadata['for_listing_ad']) &&  $eventdataobj->metadata['for_listing_ad'] == 'yes' ){
								if($this->debug) error_log(date('[Y-m-d H:i e] - '). "Is listing ad subscription webhook" . PHP_EOL, 3, $this->debug_file);
								$data['for_listing_ad'] = 'yes';
								// townhub_add_ons_active_ad_campaign($data, true);

							}else{
								if($this->debug) error_log(date('[Y-m-d H:i e] - '). "Is membership subscription webhook" . PHP_EOL, 3, $this->debug_file);
								
                                Esb_Class_Membership::active_membership($data, true);
							}

							

						}
						// end check metadata
					}
					// end check invoice is for subscription
				}
				// end invoice.payment_succeeded event

			} // end process Stripe event
			catch (Exception $e) {
				// something failed, perhaps log a notice or email the site admin
				if($this->debug) error_log(date('[Y-m-d H:i e] - '). "Event process failed: " . $e->getMessage() . PHP_EOL, 3, $this->debug_file);
			}

		}

		http_response_code(200); // PHP 5.4 or greater
		exit;
	}

	function extractPaymentData(){
		$this->checkWebHooks();



		return $this->ipn_data;

	}

	// create plan
	function createPlan($args = array()){
		$current_user = wp_get_current_user(); 
		$default = array(
			'currency' => strtolower(townhub_addons_get_option('currency','USD')),
			// 'metadata' => array( "first_name" => $current_user->user_firstname, "last_name" => $current_user->user_lastname, "display_name" => $current_user->display_name ), // You can have up to 20 keys, with key names up to 40 characters long and values up to 500 characters long.
		);
		$args = array_merge($default, $args);

		// $return = array(
		// 	'success' => false
		// );
		$plan  = false;
		try {
			if($this->debug) error_log(date('[Y-m-d H:i e] - '). "Plan args: " . json_encode($args) . PHP_EOL, 3, $this->debug_file);
			// $plan = \Stripe\Plan::create($args);
			$plan = $this->client->plans->create($args);

			// $return['success'] = true;
			// $return['plan'] = $plan;
		}catch (Exception $e) {
		  	// Something else happened, completely unrelated to Stripe
			if($this->debug) error_log(date('[Y-m-d H:i e] - '). "Stripe create plan error: " . $e->getMessage() . PHP_EOL, 3, $this->debug_file);

			// $return['error'] = $e->getMessage();
		}

		// return $return;

		return $plan;
	}

	
	function log_ipn_results($success) {
	   
	  	if (!$this->debug) return;  // is logging turned off?
	  
	  	// Timestamp
	  	$text = date('[Y-m-d H:i e] - '); 
	  
	  	// Success or failure being logged?
	  	if ($success) $text .= "SUCCESS!\n";
	  	else $text .= 'FAIL: '.$this->last_error."\n";
	  
	  	// Log the POST variables
	  	$text .= "IPN POST Vars from Paypal:\n";
	  	foreach ($this->ipn_data as $key=>$value) {
		 	$text .= "$key=$value\n";
	  	}
	
	  	// Log the response from the paypal server
	  	$text .= "\nIPN Response from Stripe Server:\n ".$this->ipn_response;
	  
	  	// Write to log
	  	error_log( $text . PHP_EOL, 3, $this->debug_file );
	}


}


		