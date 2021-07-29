<?php
/* add_ons_php */
/**
 * Class CTH_Woo_Payments_PayFast_Request file.
 *
 * @package WooCommerce\Gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generates requests to send to PayPal.
 */
class CTH_Woo_Payments_PayFast_Request {

	/**
	 * Stores line items to send to PayPal.
	 *
	 * @var array
	 */
	protected $line_items = array();

	/**
	 * Pointer to gateway making the request.
	 *
	 * @var WC_Gateway_PayFast
	 */
	protected $gateway;

	/**
	 * Endpoint for requests from PayPal.
	 *
	 * @var string
	 */
	protected $notify_url;

	/**
	 * Endpoint for requests to PayPal.
	 *
	 * @var string
	 */
	protected $endpoint;


	/**
	 * Constructor.
	 *
	 * @param WC_Gateway_PayFast $gateway PayFast gateway object.
	 */
	public function __construct( $gateway ) {
		$this->gateway    = $gateway;
		$this->notify_url = WC()->api_request_url( 'cth_woo_payments_payfast' );
	}

	/**
	 * Get the PayPal request URL for an order.
	 *
	 * @param  WC_Order $order Order object.
	 * @param  bool     $sandbox Whether to use sandbox mode or not.
	 * @return string
	 */
	public function get_request_url( $order, $sandbox = false ) {
		$this->endpoint = $sandbox ? 'https://sandbox.payfast.co.za/eng/process/?' : 'https://www.payfast.co.za/eng/process/?';
		$data    = $this->get_payfast_args( $order );

        // $payfast_args = array_filter($payfast_args, function($v, $k){
        //     return str_replace("http://localhost:8888/townhub/", 'https://townhub2.cththemes.com/', $v);
        // }, ARRAY_FILTER_USE_BOTH);

        // $queryString = http_build_query( $payfast_args, '', '&' );

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
        $passPhrase = $this->gateway->get_option( 'passphrase' );
        if( $passPhrase != '' )
        {
            $getString .= '&passphrase='. urlencode( trim( $passPhrase ) );
        }   
        $data['signature'] = md5( $getString );

        // return $this->payfast_url . $getString;
        return $this->endpoint . $pfOutput .'signature='. $data['signature'];

	}

    // protected function proccess_request_url($order){
    //     $queryString = http_build_query( $payfast_args, '', '&' );
    // }

	/**
	 * Limit length of an arg.
	 *
	 * @param  string  $string Argument to limit.
	 * @param  integer $limit Limit size in characters.
	 * @return string
	 */
	protected function limit_length( $string, $limit = 127 ) {
		// As the output is to be used in http_build_query which applies URL encoding, the string needs to be
		// cut as if it was URL-encoded, but returned non-encoded (it will be encoded by http_build_query later).
		$url_encoded_str = rawurlencode( $string );

		if ( strlen( $url_encoded_str ) > $limit ) {
			$string = rawurldecode( substr( $url_encoded_str, 0, $limit - 3 ) . '...' );
		}
		return $string;
	}

	/**
	 * Get phone number args for payfast request.
	 *
	 * @param  WC_Order $order Order object.
	 * @return array
	 */
	protected function get_phone_number_args( $order ) {
		$phone_args = array(
            'cell_number' => $order->get_billing_phone(),
        );
		return $phone_args;
        // cell_number : cell_number must be a valid email address or cell number
	}

	/**
	 * Get the state to send to payfast.
	 *
	 * @param  string $cc Country two letter code.
	 * @param  string $state State code.
	 * @return string
	 */
	protected function get_payfast_state( $cc, $state ) {
		if ( 'US' === $cc ) {
			return $state;
		}

		$states = WC()->countries->get_states( $cc );

		if ( isset( $states[ $state ] ) ) {
			return $states[ $state ];
		}

		return $state;
	}

	/**
	 * Get order item names as a string.
	 *
	 * @param  WC_Order $order Order object.
	 * @return string
	 */
	protected function get_order_item_names( $order ) {
		$item_names = array();

		foreach ( $order->get_items() as $item ) {
			$item_name = $item->get_name();
			$item_meta = strip_tags(
				wc_display_item_meta(
					$item, array(
						'before'    => '',
						'separator' => ', ',
						'after'     => '',
						'echo'      => false,
						'autop'     => false,
					)
				)
			);

			if ( $item_meta ) {
				$item_name .= ' (' . $item_meta . ')';
			}

			$item_names[] = $item_name . ' x ' . $item->get_quantity();
		}

		return apply_filters( 'cth_woo_payfast_get_order_item_names', implode( ', ', $item_names ), $order );
	}

	protected function get_order_item_recurring($order){
		$recurring_args = array();
        $products = array();
		foreach ( $order->get_items() as $item ) {
			$product_id = $item->get_product_id();

            $recurring_args['subscription_type'] = 1;

            $trial_period = get_post_meta( $product_id, ESB_META_PREFIX.'trial_period', true );
            $trial_interval = get_post_meta( $product_id, ESB_META_PREFIX.'trial_interval', true );
            if(!empty($trial_interval) && !empty($trial_period)){

                // after trial date
                $recurring_args['billing_date'] = townhub_add_ons_cal_next_date('now', $trial_period, $trial_interval, 'Y-m-d');
            }

            // The date from which future subscription payments will be made. Eg. 2016-01-01. Defaults to current date if not set.
            // $recurring_args['billing_date'] = '';

            // Future recurring amount for the subscription. Defaults to the ‘amount’ value if not set. A minimum amount of R5.00 should be used as the recurring_amount.
            // $recurring_args['amount'] = $this->number_format( $order->get_total() - $this->round( $order->get_shipping_total() + $order->get_shipping_tax(), $order ), $order );

            $recurring_args['frequency'] = townhub_addons_payfast_frequency( get_post_meta( $product_id , ESB_META_PREFIX.'interval', true ), get_post_meta( $product_id , ESB_META_PREFIX.'period', true ) ); // Subscription duration
            // The number of payments/cycles that will occur for this subscription. Set to 0 for infinity.
            // $recurring_args['cycles'] = 0;

            // add to products custom string
            $products[] = $product_id;

			// $recurring_args['item_name'] = get_the_title($product_id);
   //          $recurring_args['item_number'] = $product_id;

			/*
            D. Days. Valid range for p3 is 1 to 90.
            W. Weeks. Valid range for p3 is 1 to 52.
            M. Months. Valid range for p3 is 1 to 24.
            Y. Years. Valid range for p3 is 1 to 5.
            */
            

		}

        $recurring_args['custom_str2'] = implode("PRD", $products);

        

		return $recurring_args;
	}

	/**
	 * Get transaction args for payfast request, except for line item args.
	 *
	 * @param WC_Order $order Order object.
	 * @return array
	 */
	protected function get_transaction_args( $order ) {
		return array_merge(
			array(
                'merchant_id'      => $this->gateway->get_option( 'merchant_id' ),
				'merchant_key'      => $this->gateway->get_option( 'merchant_key' ),

                'return_url'        => esc_url_raw( add_query_arg( 'utm_nooverride', '1', $this->gateway->get_return_url( $order ) ) ),
                'cancel_url'    => esc_url_raw( $order->get_cancel_order_url_raw() ),
                'notify_url'    => $this->limit_length( $this->notify_url, 255 ),

                'amount'        => $this->number_format( $order->get_total() - $this->round( $order->get_shipping_total() + $order->get_shipping_tax(), $order ), $order ),
                
                'item_name'     => $this->get_order_item_names($order) ? $this->get_order_item_names($order) : __( 'Order', 'townhub-woo-payments' ),
                
                'item_description'  =>  '',

                'custom_int1'   => $order->get_id(),
                'custom_int2'   => '',
                'custom_int3'   => '',
                'custom_int4'   => '',
                'custom_int5'   => '',
                'custom_str1'   => $order->get_order_key(),
                'custom_str2'   => '',
                'custom_str3'   => '',
                'custom_str4'   => '',
                'custom_str5'   => '',

                'email_confirmation'   => '',
                'confirmation_address'   => '',


				
				// 'name_first'    => $this->limit_length( $order->get_billing_first_name(), 32 ),
				// 'name_last'     => $this->limit_length( $order->get_billing_last_name(), 64 ),
				// 'email_address'         => $this->limit_length( $order->get_billing_email() ),
			),

            $this->get_transaction_options( $order ),

            $this->get_order_item_recurring( $order )
			

			// , $this->get_phone_number_args( $order )
		);
	}

    protected function get_transaction_options($order){
        $args = array();
        if($this->gateway->get_option( 'email_confirmation' ) == 'yes' && $this->gateway->get_option( 'confirmation_address' ) != ''){
            $args['email_confirmation'] = 1;
            $args['confirmation_address'] = $this->gateway->get_option( 'confirmation_address' );
        }

        return $args;
    }

	/**
	 * Get PayPal Args for passing to PP.
	 *
	 * @param  WC_Order $order Order object.
	 * @return array
	 */
	protected function get_payfast_args( $order ) {
		
		$payfast_args = apply_filters('cth_woo_payfast_args', $this->get_transaction_args( $order ), $order);

		// return $this->fix_request_length( $order, $payfast_args );
		return $payfast_args;
	}

	/**
	 * Check if currency has decimals.
	 *
	 * @param  string $currency Currency to check.
	 * @return bool
	 */
	protected function currency_has_decimals( $currency ) {
		
		return true;
	}

	/**
	 * Round prices.
	 *
	 * @param  double   $price Price to round.
	 * @param  WC_Order $order Order object.
	 * @return double
	 */
	protected function round( $price, $order ) {
		$precision = 2;

		if ( ! $this->currency_has_decimals( $order->get_currency() ) ) {
			$precision = 0;
		}

		return round( $price, $precision );
	}


	/**
	 * Format prices.
	 *
	 * @param  float|int $price Price to format.
	 * @param  WC_Order  $order Order object.
	 * @return string
	 */
	protected function number_format( $price, $order ) {
		$decimals = 2;

		if ( ! $this->currency_has_decimals( $order->get_currency() ) ) {
			$decimals = 0;
		}

        $price *= (float)townhub_addons_get_option('payfast_rate');

		return number_format( $price, $decimals, '.', '' );
	}

}