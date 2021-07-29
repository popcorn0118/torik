<?php
/* add_ons_php */
/**
 * Handles responses from PayFast IPN.
 *
 * @package WooCommerce/PayFast
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once dirname( __FILE__ ) . '/payfast-response.php';

/**
 * CTH_Woo_Payments_PayFast_IPN class.
 */
class CTH_Woo_Payments_PayFast_IPN extends CTH_Woo_Payments_PayFast_Response {

    /**
     * Receiver email address to validate.
     *
     * @var string Receiver email address.
     */
    protected $payment;

    /**
     * Constructor.
     *
     * @param bool   $sandbox Use sandbox or not.
     * @param string $receiver_email Email to receive IPN from.
     * CTH_Woo_Payments_PayFast woocommerce api - /wc-api/CTH_Woo_Payments_PayFast
     */ 
    public function __construct( $payment ) {
        add_action( 'woocommerce_api_cth_woo_payments_payfast', array( $this, 'check_response' ) );
        add_action( 'valid-cth-payfast-ipn-request', array( $this, 'valid_response' ) );

        $this->payment = $payment;
        $this->sandbox        = $payment->testmode;
    }

    /**
     * Check for PayFast IPN Response.
     */
    public function check_response() {
        if ( ! empty( $_POST ) && $this->validate_ipn() ) { // WPCS: CSRF ok.
            $posted = wp_unslash( $_POST ); // WPCS: CSRF ok, input var ok.

            // @codingStandardsIgnoreStart
            do_action( 'valid-cth-payfast-ipn-request', $posted );
            // @codingStandardsIgnoreEnd
            exit;
        }

        // wp_die( 'PayFast IPN Request Failure', 'PayFast IPN', array( 'response' => 500 ) );
    }

    /**
     * There was a valid response.
     *
     * @param  array $posted Post data after wp_unslash.
     */
    public function valid_response( $posted ) {
        CTH_Woo_Payments_PayFast::log( 'Data Response: ' . wc_print_r( $posted, true ) );
        $custom_data = array();
        if( isset( $posted['custom_int1'] ) && $posted['custom_int1'] != '' ) $custom_data['order_id'] = $posted['custom_int1'] ;
        if( isset( $posted['custom_str1'] ) && $posted['custom_str1'] != '' ) $custom_data['order_key'] = $posted['custom_str1'] ;

        $order = $this->get_payfast_order( $custom_data );

        if ( $order ) {

            // Lowercase returned variables.
            if(!isset($posted['payment_status'])) $posted['payment_status'] = 'trialing';
            $posted['payment_status'] = strtolower( $posted['payment_status'] );

            // $pfData['payment_status'] = 'COMPLETE' : 'CANCEL' : // If unknown status, do nothing (which is the safest course of action)

            CTH_Woo_Payments_PayFast::log( 'Found order #' . $order->get_id() );
            CTH_Woo_Payments_PayFast::log( 'Payment status: ' . $posted['payment_status'] );

            if ( method_exists( $this, 'payment_status_' . $posted['payment_status'] ) ) {
                call_user_func( array( $this, 'payment_status_' . $posted['payment_status'] ), $order, $posted );
            }
        }
    }


    /**
     * Check PayFast IPN validity.
     */
    public function validate_ipn() {

        // Notify PayFast that information has been received
        header( 'HTTP/1.0 200 OK' ); // already sent in woocommerce
        flush();
        
        CTH_Woo_Payments_PayFast::log( 'PayFast Checking IPN response is valid' );

        $pfErrMsg = '';
        $pfError = false;
        $output = ''; // DEBUG
        $pfParamString = '';
        // $pfParamStringNonEmpty = '';
        $pfHost = $this->sandbox ? 'www.payfast.co.za' : 'sandbox.payfast.co.za';
        $pfData = array();
        $pfDataPost = array();
        $output = "ITN Response Received\n\n";

        //// Dump the submitted variables and calculate security signature
        if ( !$pfError )
        {
            $output .= "Posted Variables:\n"; // DEBUG

            $pf_post_datas = wp_unslash( $_POST );

            // Strip any slashes in data
            foreach ( $pf_post_datas as $key => $val )
            {
                $pfData[$key] = stripslashes( $val );
                $output .= "$key = $val\n";
            }

            // Dump the submitted variables and calculate security signature
            foreach ( $pfData as $key => $val )
            {
                if ( $key != 'signature' )
                {
                    $pfParamString .= $key . '=' . urlencode( $val ) . '&';
                    // if($val != '') $pfParamStringNonEmpty .= $key . '=' . urlencode( $val ) . '&';
                    $pfDataPost[$key] = $val;
                }

            }

            // Remove the last '&' from the parameter string
            $pfParamString = substr( $pfParamString, 0, -1 );
            // $pfParamStringNonEmpty = substr( $pfParamStringNonEmpty, 0, -1 );
            $pfTempParamString = $pfParamString;

            // If a passphrase has been set in the PayFast Settings, include it in the signature string.
            $passPhrase = $this->payment->get_option( 'passphrase' ); 
            //You need to get this from a constant or stored in your website/database
            if ( !empty( $passPhrase ) )
            {
                $pfTempParamString .= '&passphrase=' . urlencode( $passPhrase );
            }
            $signature = md5( $pfTempParamString );

            $result = ( $pf_post_datas['signature'] == $signature );

            $output .= "\nSecurity Signature:\n"; // DEBUG
            $output .= "- posted     = " . $pf_post_datas['signature'] . "\n"; // DEBUG
            $output .= "- calculated = " . $signature . "\n"; // DEBUG
            $output .= "- result     = " . ( $result ? 'SUCCESS' : 'FAILURE' ) . "\n"; // DEBUG

            if( !$result ){
                $pfError =  true;
                $pfErrMsg = "Invalid signature";
            }
        }

        //// Verify source IP
        if ( !$pfError )
        {
            $validHosts = array(
                'www.payfast.co.za',
                'sandbox.payfast.co.za',
                'w1w.payfast.co.za',
                'w2w.payfast.co.za',
            );

            $validIps = array();

            foreach ( $validHosts as $pfHostname )
            {
                $ips = gethostbynamel( $pfHostname );

                if ( $ips !== false )
                {
                    $validIps = array_merge( $validIps, $ips );
                }
            }

            // Remove duplicates
            $validIps = array_unique( $validIps );

            if ( !in_array( $_SERVER['REMOTE_ADDR'], $validIps ) )
            {
                $pfError = true;
                $pfErrMsg = "PayFast error bad source ip";
            }
        }

        //// Connect to server to validate data received
        if ( !$pfError )
        {
                

            // Use cURL (If it's available)
            if ( 1 == 2 && function_exists( 'curl_init' ) )
            {
                $output .= "\nUsing cURL\n"; // DEBUG

                // Create default cURL object
                $ch = curl_init();

                // Base settings
                // $curlOpts = array(
                //     // Base options
                //     CURLOPT_USERAGENT => 'WooCommerce/' . WC()->version, // Set user agent
                //     CURLOPT_RETURNTRANSFER => true, // Return output as string rather than outputting it
                //     CURLOPT_HEADER => false, // Don't include header in output
                //     CURLOPT_SSL_VERIFYHOST => 2,
                //     CURLOPT_SSL_VERIFYPEER => 1,

                //     // Standard settings
                //     CURLOPT_URL => 'https://' . $pfHost . '/eng/query/validate',
                //     CURLOPT_POST => true,
                //     CURLOPT_POSTFIELDS => $pfParamString,
                // );
                // curl_setopt_array( $ch, $curlOpts );

                // Base settings
                $url = 'https://'. $pfHost .'/eng/query/validate';
                
                curl_setopt( $ch, CURLOPT_USERAGENT, 'WooCommerce/' . WC()->version );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch, CURLOPT_HEADER, false );      
                curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
                curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 1 );

                // Standard settings
                curl_setopt( $ch, CURLOPT_URL, $url );
                curl_setopt( $ch, CURLOPT_POST, true );
                // curl_setopt( $ch, CURLOPT_POSTFIELDS, $pfParamStringNonEmpty );
                curl_setopt( $ch, CURLOPT_POSTFIELDS, $pfParamString );

                // Execute CURL
                $res = curl_exec( $ch );
                curl_close( $ch );

                if ( $res === false )
                {
                    $pfError = true;
                    $pfErrMsg = "PayFast error curl";
                }
            }else{
                $output .= "\nUsing wp remote post\n"; // DEBUG
                $params = array(
                    'body'        => $pfDataPost,
                    'timeout'     => 70,
                    // 'httpversion' => '1.1',
                    'user-agent'  => 'WooCommerce/' . WC()->version,
                );

                // Post back to get a response.
                $response = wp_remote_post( 'https://' . $pfHost . '/eng/query/validate', $params );

                CTH_Woo_Payments_PayFast::log( 'IPN Response: ' . wc_print_r( $response, true ) );

                if ( is_wp_error( $response ) || empty( $response['body'] ) ) {
                    $pfError = true;
                    $pfErrMsg = "PayFast error wp remote post";
                }
            }
            
        }

        //// Get data from server
        if ( !$pfError )
        {
            if( isset($response) && is_array($response) && isset($response['body']))
                $res = $response['body'];

            // Parse the returned data
            $lines = explode( "\n", $res );

            $output .= "\nValidate response from server:\n"; // DEBUG

            foreach ( $lines as $line ) // DEBUG
            {
                $output .= $line . "\n";
            }
            // DEBUG
        }

        //// Interpret the response from server
        if ( !$pfError )
        {
            // Get the response from PayFast (VALID or INVALID)
            $result = trim( $lines[0] );

            $output .= "\nResult = " . $result; // DEBUG

            // If the transaction was valid
            if ( strcmp( $result, 'VALID' ) == 0 )
            {
                // Process as required
                CTH_Woo_Payments_PayFast::log( $output );
                return true;
            }
            // If the transaction was NOT valid
            else
            {
                // Log for investigation
                $pfError = true;
                $pfErrMsg = "PayFast error invalid data";
            }
        }

        // If an error occurred
        if ( $pfError )
        {
            $output .= "\n\nAn error occurred!";
            $output .= "\nError = " . $pfErrMsg;
        }

        CTH_Woo_Payments_PayFast::log( $output );

        return false;
    }


    /**
     * Handle a completed payment.
     *
     * @param WC_Order $order  Order object.
     * @param array    $posted Posted data.
     */
    protected function payment_status_complete( $order, $posted ) {
        update_post_meta( $order->get_id(), '_cth_recurring', 'yes' );
        // check for free trial
        if( isset($posted['mc_amount1']) && 0 == (float)$posted['mc_amount1'] ){
            // Log payfast transaction fee.
            update_post_meta( $order->get_id(), '_cth_trialing', 'yes' );
        }else{
            update_post_meta( $order->get_id(), '_cth_trialing', 'no' );
        }
        
        if ( $order->has_status( wc_get_is_paid_statuses() ) ) {
            CTH_Woo_Payments_PayFast::log( 'Next payment, Proces when order #' . $order->get_id() . ' is already complete.' );

            $this->process_next_payment($order, $posted);
            exit;
        }

        $this->validate_transaction_type( $posted['txn_type'] );
        $this->validate_currency( $order, $posted['mc_currency'] );
        // $this->validate_amount( $order, $posted['mc_gross'] ); // do not verify amount because of trial period has 0 amount
        $this->validate_receiver_email( $order, $posted['receiver_email'] );
        $this->save_payfast_meta_data( $order, $posted );

        if ( 'complete' === $posted['payment_status'] || 'trialing' === $posted['payment_status'] ) {
            if ( $order->has_status( 'cancelled' ) ) {
                $this->payment_status_paid_cancelled_order( $order, $posted );
            }

            $this->payment_complete( $order, ( ! empty( $posted['txn_id'] ) ? wc_clean( $posted['txn_id'] ) : '' ), __( 'IPN payment completed', 'townhub-woo-payments' ) );

            if ( ! empty( $posted['mc_fee'] ) ) {
                // Log payfast transaction fee.
                update_post_meta( $order->get_id(), 'PayFast Transaction Fee', wc_clean( $posted['mc_fee'] ) );
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
            'item_number'               => $posted['custom_str2'], // this is listing plan id
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

        // townhub_add_ons_active_membership($data, 'utc');

    }

    /**
     * Handle a pending payment.
     *
     * @param WC_Order $order  Order object.
     * @param array    $posted Posted data.
     */
    protected function payment_status_cancel( $order, $posted ) {
        $this->payment_status_completed( $order, $posted );
    }

    

    /**
     * Check for a valid transaction type.
     *
     * @param string $txn_type Transaction type.
     */
    protected function validate_transaction_type( $txn_type ) {
        $accepted_types = array( 'subscr_signup', 'subscr_payment', /* 'cart', 'instant', 'express_checkout', 'web_accept', 'masspay', 'send_money', 'payfast_here' */);

        if ( ! in_array( strtolower( $txn_type ), $accepted_types, true ) ) {
            CTH_Woo_Payments_PayFast::log( 'Aborting, Invalid type:' . $txn_type );
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
            CTH_Woo_Payments_PayFast::log( 'Payment error: Currencies do not match (sent "' . $order->get_currency() . '" | returned "' . $currency . '")' );

            /* translators: %s: currency code. */
            $order->update_status( 'on-hold', sprintf( __( 'Validation error: PayFast currencies do not match (code %s).', 'townhub-woo-payments' ), $currency ) );
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
            CTH_Woo_Payments_PayFast::log( 'Payment error: Amounts do not match (gross ' . $amount . ')' );

            /* translators: %s: Amount. */
            $order->update_status( 'on-hold', sprintf( __( 'Validation error: PayFast amounts do not match (gross %s).', 'townhub-woo-payments' ), $amount ) );
            exit;
        }
    }

    /**
     * Check receiver email from PayFast. If the receiver email in the IPN is different than what is stored in.
     * WooCommerce -> Settings -> Checkout -> PayFast, it will log an error about it.
     *
     * @param WC_Order $order          Order object.
     * @param string   $receiver_email Email to validate.
     */
    protected function validate_receiver_email( $order, $receiver_email ) {
        if ( strcasecmp( trim( $receiver_email ), trim( $this->receiver_email ) ) !== 0 ) {
            CTH_Woo_Payments_PayFast::log( "IPN Response is for another account: {$receiver_email}. Your email is {$this->receiver_email}" );

            /* translators: %s: email address . */
            $order->update_status( 'on-hold', sprintf( __( 'Validation error: PayFast IPN response from a different email address (%s).', 'townhub-woo-payments' ), $receiver_email ) );
            exit;
        }
    }

    /**
     * Save important data from the IPN to the order.
     *
     * @param WC_Order $order  Order object.
     * @param array    $posted Posted data.
     */
    protected function save_payfast_meta_data( $order, $posted ) {
        if ( ! empty( $posted['payment_type'] ) ) {
            update_post_meta( $order->get_id(), 'Payment type', wc_clean( $posted['payment_type'] ) );
        }
        if ( ! empty( $posted['txn_id'] ) ) {
            update_post_meta( $order->get_id(), '_transaction_id', wc_clean( $posted['txn_id'] ) );
        }
        if ( ! empty( $posted['payment_status'] ) ) {
            update_post_meta( $order->get_id(), '_payfast_status', wc_clean( $posted['payment_status'] ) );
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
            sprintf( __( 'Order #%s has been marked paid by PayFast IPN, but was previously cancelled. Admin handling required.', 'townhub-woo-payments' ), $order->get_order_number() )
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

        $woocommerce_cth_payfast_settings = get_option( 'woocommerce_cth_payfast_settings' );
        if ( ! empty( $woocommerce_cth_payfast_settings['ipn_notification'] ) && 'no' === $woocommerce_cth_payfast_settings['ipn_notification'] ) {
            return;
        }

        $mailer->send( ! empty( $new_order_settings['recipient'] ) ? $new_order_settings['recipient'] : get_option( 'admin_email' ), strip_tags( $subject ), $message );
    }


}