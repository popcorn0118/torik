<?php
/* add_ons_php */
/**
 * Handles responses from PayPal IPN.
 *
 * @package WooCommerce/PayPal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __FILE__ ) . '/paypal-response.php';

/**
 * CTH_Woo_Payments_Paypal_IPN class.
 */
class CTH_Woo_Payments_Paypal_IPN extends CTH_Woo_Payments_Paypal_Response {

	/**
	 * Receiver email address to validate.
	 *
	 * @var string Receiver email address.
	 */
	protected $receiver_email;

	/**
	 * Constructor.
	 *
	 * @param bool   $sandbox Use sandbox or not.
	 * @param string $receiver_email Email to receive IPN from.
	 * CTH_Woo_Payments_Paypal woocommerce api - /wc-api/CTH_Woo_Payments_Paypal
	 */ 
	public function __construct( $sandbox = false, $receiver_email = '' ) {
		add_action( 'woocommerce_api_cth_gateway_paypal', array( $this, 'check_response' ) );
		add_action( 'valid-cth-paypal-ipn-request', array( $this, 'valid_response' ) );

		$this->receiver_email = $receiver_email;
		$this->sandbox        = $sandbox;
	}

	/**
	 * Check for PayPal IPN Response.
	 */
	public function check_response() {
		if ( ! empty( $_POST ) && $this->validate_ipn() ) { // WPCS: CSRF ok.
			$posted = wp_unslash( $_POST ); // WPCS: CSRF ok, input var ok.

			// @codingStandardsIgnoreStart
			do_action( 'valid-cth-paypal-ipn-request', $posted );
			// @codingStandardsIgnoreEnd
			exit;
		}

		wp_die( 'PayPal IPN Request Failure', 'PayPal IPN', array( 'response' => 500 ) );
	}

	/**
	 * There was a valid response.
	 *
	 * @param  array $posted Post data after wp_unslash.
	 */
	public function valid_response( $posted ) {
		CTH_Woo_Payments_Paypal::log( 'Data Response: ' . wc_print_r( $posted, true ) );
		$order = ! empty( $posted['custom'] ) ? $this->get_paypal_order( $posted['custom'] ) : false;

		if ( $order ) {

			// Lowercase returned variables.
			if(!isset($posted['payment_status'])) $posted['payment_status'] = 'trialing';
			$posted['payment_status'] = strtolower( $posted['payment_status'] );

			CTH_Woo_Payments_Paypal::log( 'Found order #' . $order->get_id() );
			CTH_Woo_Payments_Paypal::log( 'Payment status: ' . $posted['payment_status'] );

			if ( method_exists( $this, 'payment_status_' . $posted['payment_status'] ) ) {
				call_user_func( array( $this, 'payment_status_' . $posted['payment_status'] ), $order, $posted );
			}
		}
	}


	/**
	 * Check PayPal IPN validity.
	 */
	public function validate_ipn() {
		CTH_Woo_Payments_Paypal::log( 'Checking IPN response is valid' );

		// Get received values from post data.
		$validate_ipn        = wp_unslash( $_POST ); // WPCS: CSRF ok, input var ok.
		$validate_ipn['cmd'] = '_notify-validate';

		// Send back post vars to paypal.
		$params = array(
			'body'        => $validate_ipn,
			'timeout'     => 60,
			'httpversion' => '1.1',
			'compress'    => false,
			'decompress'  => false,
			'user-agent'  => 'WooCommerce/' . WC()->version,
		);

		// Post back to get a response.
		$response = wp_safe_remote_post( $this->sandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr', $params );

		CTH_Woo_Payments_Paypal::log( 'IPN Response: ' . wc_print_r( $response, true ) );

		// Check to see if the request was valid.
		if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 && strstr( $response['body'], 'VERIFIED' ) ) {
			CTH_Woo_Payments_Paypal::log( 'Received valid response from PayPal IPN' );
			return true;
		}

		CTH_Woo_Payments_Paypal::log( 'Received invalid response from PayPal IPN' );

		if ( is_wp_error( $response ) ) {
			CTH_Woo_Payments_Paypal::log( 'Error response: ' . $response->get_error_message() );
		}

		return false;
	}


	/**
	 * Handle a completed payment.
	 *
	 * @param WC_Order $order  Order object.
	 * @param array    $posted Posted data.
	 */
	protected function payment_status_completed( $order, $posted ) {
		update_post_meta( $order->get_id(), '_cth_recurring', 'yes' );
		// check for free trial
		if( isset($posted['mc_amount1']) && 0 == (float)$posted['mc_amount1'] ){
			// Log paypal transaction fee.
			update_post_meta( $order->get_id(), '_cth_trialing', 'yes' );
		}else{
			update_post_meta( $order->get_id(), '_cth_trialing', 'no' );
		}
		
		if ( $order->has_status( wc_get_is_paid_statuses() ) ) {
			CTH_Woo_Payments_Paypal::log( 'Next payment, Proces when order #' . $order->get_id() . ' is already complete.' );

			$this->process_next_payment($order, $posted);
			exit;
		}

		$this->validate_transaction_type( $posted['txn_type'] );
		$this->validate_currency( $order, $posted['mc_currency'] );
		// $this->validate_amount( $order, $posted['mc_gross'] ); // do not verify amount because of trial period has 0 amount
		$this->validate_receiver_email( $order, $posted['receiver_email'] );
		$this->save_paypal_meta_data( $order, $posted );

		if ( 'completed' === $posted['payment_status'] || 'trialing' === $posted['payment_status'] ) {
			if ( $order->has_status( 'cancelled' ) ) {
				$this->payment_status_paid_cancelled_order( $order, $posted );
			}

			$this->payment_complete( $order, ( ! empty( $posted['txn_id'] ) ? wc_clean( $posted['txn_id'] ) : '' ), __( 'IPN payment completed', 'townhub-woo-payments' ) );

			if ( ! empty( $posted['mc_fee'] ) ) {
				// Log paypal transaction fee.
				update_post_meta( $order->get_id(), 'PayPal Transaction Fee', wc_clean( $posted['mc_fee'] ) );
			}
		} else {
			if ( 'authorization' === $posted['pending_reason'] ) {
				$this->payment_on_hold( $order, __( 'Payment authorized. Change payment status to processing or complete to capture funds.', 'townhub-woo-payments' ) );
			} else {
				/* translators: %s: pending reason. */
				$this->payment_on_hold( $order, sprintf( __( 'Payment pending (%s).', 'townhub-woo-payments' ), $posted['pending_reason'] ) );
			}
		}
	}

	protected function process_next_payment($order, $posted){

		$order_user = $order->get_user(); //wp_get_current_user(); 

		// active membership subscription if order is completed
        $data = array(
            'pm_status'                 => 'completed',
            'user_id'                   => $order_user->ID,
            'item_number'               => $posted['item_number'], // this is listing plan id
            'pm_date'                   => current_time('mysql', 1), // Time at which the object was created. Measured in seconds since the Unix epoch.
            'order_id'                  => get_post_meta( $order->get_id(), ESB_META_PREFIX.'lorder', true ),
            'recurring_subscription'    => true,

            'txn_id'                    => uniqid('woo_integration'), // invoice id

            // for stripe period
            // 'payment_method'            => __( 'Free Subscription', 'townhub-add-ons' ),
            // 'period_start'              => current_time('mysql', 1),
            // 'period_end'                => $expire,

        );
        Esb_Class_Membership::active_membership($data);
        

	}

	/**
	 * Handle a pending payment.
	 *
	 * @param WC_Order $order  Order object.
	 * @param array    $posted Posted data.
	 */
	protected function payment_status_pending( $order, $posted ) {
		$this->payment_status_completed( $order, $posted );
	}

	/**
	 * Handle a trialing payment - for trial subscription
	 *
	 * @param WC_Order $order  Order object.
	 * @param array    $posted Posted data.
	 */
	protected function payment_status_trialing( $order, $posted ) {
		$this->payment_status_completed( $order, $posted );
	}

	/**
	 * Check for a valid transaction type.
	 *
	 * @param string $txn_type Transaction type.
	 */
	protected function validate_transaction_type( $txn_type ) {
		$accepted_types = array( 'subscr_signup', 'subscr_payment', /* 'cart', 'instant', 'express_checkout', 'web_accept', 'masspay', 'send_money', 'paypal_here' */);

		if ( ! in_array( strtolower( $txn_type ), $accepted_types, true ) ) {
			CTH_Woo_Payments_Paypal::log( 'Aborting, Invalid type:' . $txn_type );
			exit;
		}
	}

	/**
	 * Check currency from IPN matches the order.
	 *
	 * @param WC_Order $order    Order object.
	 * @param string   $currency Currency code.
	 */
	protected function validate_currency( $order, $currency ) {
		if ( $order->get_currency() !== $currency ) {
			CTH_Woo_Payments_Paypal::log( 'Payment error: Currencies do not match (sent "' . $order->get_currency() . '" | returned "' . $currency . '")' );

			/* translators: %s: currency code. */
			$order->update_status( 'on-hold', sprintf( __( 'Validation error: PayPal currencies do not match (code %s).', 'townhub-woo-payments' ), $currency ) );
			exit;
		}
	}

	/**
	 * Check payment amount from IPN matches the order.
	 *
	 * @param WC_Order $order  Order object.
	 * @param int      $amount Amount to validate.
	 */
	protected function validate_amount( $order, $amount ) {
		if ( number_format( $order->get_total(), 2, '.', '' ) !== number_format( $amount, 2, '.', '' ) ) {
			CTH_Woo_Payments_Paypal::log( 'Payment error: Amounts do not match (gross ' . $amount . ')' );

			/* translators: %s: Amount. */
			$order->update_status( 'on-hold', sprintf( __( 'Validation error: PayPal amounts do not match (gross %s).', 'townhub-woo-payments' ), $amount ) );
			exit;
		}
	}

	/**
	 * Check receiver email from PayPal. If the receiver email in the IPN is different than what is stored in.
	 * WooCommerce -> Settings -> Checkout -> PayPal, it will log an error about it.
	 *
	 * @param WC_Order $order          Order object.
	 * @param string   $receiver_email Email to validate.
	 */
	protected function validate_receiver_email( $order, $receiver_email ) {
		if ( strcasecmp( trim( $receiver_email ), trim( $this->receiver_email ) ) !== 0 ) {
			CTH_Woo_Payments_Paypal::log( "IPN Response is for another account: {$receiver_email}. Your email is {$this->receiver_email}" );

			/* translators: %s: email address . */
			$order->update_status( 'on-hold', sprintf( __( 'Validation error: PayPal IPN response from a different email address (%s).', 'townhub-woo-payments' ), $receiver_email ) );
			exit;
		}
	}

	/**
	 * Save important data from the IPN to the order.
	 *
	 * @param WC_Order $order  Order object.
	 * @param array    $posted Posted data.
	 */
	protected function save_paypal_meta_data( $order, $posted ) {
		if ( ! empty( $posted['payment_type'] ) ) {
			update_post_meta( $order->get_id(), 'Payment type', wc_clean( $posted['payment_type'] ) );
		}
		if ( ! empty( $posted['txn_id'] ) ) {
			update_post_meta( $order->get_id(), '_transaction_id', wc_clean( $posted['txn_id'] ) );
		}
		if ( ! empty( $posted['payment_status'] ) ) {
			update_post_meta( $order->get_id(), '_paypal_status', wc_clean( $posted['payment_status'] ) );
		}
	}

	/**
	 * When a user cancelled order is marked paid.
	 *
	 * @param WC_Order $order  Order object.
	 * @param array    $posted Posted data.
	 */
	protected function payment_status_paid_cancelled_order( $order, $posted ) {
		$this->send_ipn_email_notification(
			/* translators: %s: order link. */
			sprintf( __( 'Payment for cancelled order %s received', 'townhub-woo-payments' ), '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">' . $order->get_order_number() . '</a>' ),
			/* translators: %s: order ID. */
			sprintf( __( 'Order #%s has been marked paid by PayPal IPN, but was previously cancelled. Admin handling required.', 'townhub-woo-payments' ), $order->get_order_number() )
		);
	}

	/**
	 * Send a notification to the user handling orders.
	 *
	 * @param string $subject Email subject.
	 * @param string $message Email message.
	 */
	protected function send_ipn_email_notification( $subject, $message ) {
		$new_order_settings = get_option( 'woocommerce_new_order_settings', array() );
		$mailer             = WC()->mailer();
		$message            = $mailer->wrap_message( $subject, $message );

		$woocommerce_cth_paypal_settings = get_option( 'woocommerce_cth_paypal_settings' );
		if ( ! empty( $woocommerce_cth_paypal_settings['ipn_notification'] ) && 'no' === $woocommerce_cth_paypal_settings['ipn_notification'] ) {
			return;
		}

		$mailer->send( ! empty( $new_order_settings['recipient'] ) ? $new_order_settings['recipient'] : get_option( 'admin_email' ), strip_tags( $subject ), $message );
	}


}