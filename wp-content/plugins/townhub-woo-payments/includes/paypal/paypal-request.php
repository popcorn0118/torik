<?php
/* add_ons_php */
/**
 * Class CTH_Woo_Payments_Paypal_Request file.
 *
 * @package WooCommerce\Gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generates requests to send to PayPal.
 */
class CTH_Woo_Payments_Paypal_Request {

	/**
	 * Stores line items to send to PayPal.
	 *
	 * @var array
	 */
	protected $line_items = array();

	/**
	 * Pointer to gateway making the request.
	 *
	 * @var WC_Gateway_Paypal
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
	 * @param WC_Gateway_Paypal $gateway Paypal gateway object.
	 */
	public function __construct( $gateway ) {
		$this->gateway    = $gateway;
		$this->notify_url = WC()->api_request_url( 'CTH_Gateway_Paypal' );
	}

	/**
	 * Get the PayPal request URL for an order.
	 *
	 * @param  WC_Order $order Order object.
	 * @param  bool     $sandbox Whether to use sandbox mode or not.
	 * @return string
	 */
	public function get_request_url( $order, $sandbox = false ) {
		$this->endpoint = $sandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr?' : 'https://www.paypal.com/cgi-bin/webscr?';
		$paypal_args    = $this->get_paypal_args( $order );
		
		return $this->endpoint . http_build_query( $paypal_args, '', '&' );
	}

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
	 * Get phone number args for paypal request.
	 *
	 * @param  WC_Order $order Order object.
	 * @return array
	 */
	protected function get_phone_number_args( $order ) {
		if ( in_array( $order->get_billing_country(), array( 'US', 'CA' ), true ) ) {
			$phone_number = str_replace( array( '(', '-', ' ', ')', '.' ), '', $order->get_billing_phone() );
			$phone_number = ltrim( $phone_number, '+1' );
			$phone_args   = array(
				'night_phone_a' => substr( $phone_number, 0, 3 ),
				'night_phone_b' => substr( $phone_number, 3, 3 ),
				'night_phone_c' => substr( $phone_number, 6, 4 ),
			);
		} else {
			$phone_args = array(
				'night_phone_b' => $order->get_billing_phone(),
			);
		}
		return $phone_args;
	}

	/**
	 * Get the state to send to paypal.
	 *
	 * @param  string $cc Country two letter code.
	 * @param  string $state State code.
	 * @return string
	 */
	protected function get_paypal_state( $cc, $state ) {
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

		return apply_filters( 'cththemes_paypal_get_order_item_names', implode( ', ', $item_names ), $order );
	}

	protected function get_order_item_recurring($order){
		$recurring_args = array();
		foreach ( $order->get_items() as $item ) {
			$product_id = $item->get_product_id();

			$recurring_args['item_name'] = get_the_title($product_id);
			$recurring_args['item_number'] = $product_id;
			

			$recurring_args['a3'] = $this->number_format( $order->get_total() - $this->round( $order->get_shipping_total() + $order->get_shipping_tax(), $order ), $order );
			$recurring_args['p3'] = townhub_add_ons_get_paypal_duration( get_post_meta( $product_id , ESB_META_PREFIX.'interval', true ), get_post_meta( $product_id , ESB_META_PREFIX.'period', true ) ); // Subscription duration
			$recurring_args['t3'] = townhub_add_ons_get_paypal_duration_unit( get_post_meta( $product_id , ESB_META_PREFIX.'period', true ) ); // Regular subscription units of duration.
			/*
            D. Days. Valid range for p3 is 1 to 90.
            W. Weeks. Valid range for p3 is 1 to 52.
            M. Months. Valid range for p3 is 1 to 24.
            Y. Years. Valid range for p3 is 1 to 5.
            */
            $trial_period = get_post_meta( $product_id, ESB_META_PREFIX.'trial_period', true );
            $trial_interval = get_post_meta( $product_id, ESB_META_PREFIX.'trial_interval', true );
            if(!empty($trial_interval) && !empty($trial_period)){
                $recurring_args['a1'] = 0;// 0 for free trial
                $recurring_args['p1'] = townhub_add_ons_get_paypal_duration( $trial_interval, $trial_period );
                $recurring_args['t1'] = townhub_add_ons_get_paypal_duration_unit( $trial_period );
            }

		}

		return $recurring_args;
	}

	/**
	 * Get transaction args for paypal request, except for line item args.
	 *
	 * @param WC_Order $order Order object.
	 * @return array
	 */
	protected function get_transaction_args( $order ) {
		return array_merge(
			array(
				'cmd'           => '_xclick-subscriptions', // Subscribe button
				'item_name'     => $this->get_order_item_names($order) ? $this->get_order_item_names($order) : __( 'Order', 'townhub-woo-payments' ),
				
                
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


				'business'      => $this->gateway->get_option( 'email' ),
				'currency_code' => get_woocommerce_currency(),
				'charset'       => 'utf-8',
				'rm'            => is_ssl() ? 2 : 1,
				'upload'        => 1,
				'return'        => esc_url_raw( add_query_arg( 'utm_nooverride', '1', $this->gateway->get_return_url( $order ) ) ),
				'cancel_return' => esc_url_raw( $order->get_cancel_order_url_raw() ),
				'page_style'    => $this->gateway->get_option( 'page_style' ),
				'image_url'     => esc_url_raw( $this->gateway->get_option( 'image_url' ) ),
				'paymentaction' => $this->gateway->get_option( 'paymentaction' ),
				'bn'            => 'CTHthemes_Cart',
				'invoice'       => $this->limit_length( $this->gateway->get_option( 'invoice_prefix' ) . $order->get_order_number(), 127 ),
				'custom'        => wp_json_encode(
					array(
						'order_id'  => $order->get_id(),
						'order_key' => $order->get_order_key(),
					)
				),
				'notify_url'    => $this->limit_length( $this->notify_url, 255 ),
				'first_name'    => $this->limit_length( $order->get_billing_first_name(), 32 ),
				'last_name'     => $this->limit_length( $order->get_billing_last_name(), 64 ),
				'address1'      => $this->limit_length( $order->get_billing_address_1(), 100 ),
				'address2'      => $this->limit_length( $order->get_billing_address_2(), 100 ),
				'city'          => $this->limit_length( $order->get_billing_city(), 40 ),
				'state'         => $this->get_paypal_state( $order->get_billing_country(), $order->get_billing_state() ),
				'zip'           => $this->limit_length( wc_format_postcode( $order->get_billing_postcode(), $order->get_billing_country() ), 32 ),
				'country'       => $this->limit_length( $order->get_billing_country(), 2 ),
				'email'         => $this->limit_length( $order->get_billing_email() ),
			),


			$this->get_order_item_recurring( $order ),

			$this->get_phone_number_args( $order ),
			// $this->get_shipping_args( $order )
			array('no_shipping'=> 1)
		);
	}

	/**
	 * Get PayPal Args for passing to PP.
	 *
	 * @param  WC_Order $order Order object.
	 * @return array
	 */
	protected function get_paypal_args( $order ) {
		
		$paypal_args = apply_filters('cththemes_paypal_args', $this->get_transaction_args( $order ), $order);

		// return $this->fix_request_length( $order, $paypal_args );
		return $paypal_args;
	}

	/**
	 * Check if currency has decimals.
	 *
	 * @param  string $currency Currency to check.
	 * @return bool
	 */
	protected function currency_has_decimals( $currency ) {
		if ( in_array( $currency, array( 'HUF', 'JPY', 'TWD' ), true ) ) {
			return false;
		}

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

		return number_format( $price, $decimals, '.', '' );
	}

}