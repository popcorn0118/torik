<?php 
/* add_ons_php */

class CTH_Woo_Payments_PayFast extends WC_Payment_Gateway {
    /**
     * Whether or not logging is enabled
     *
     * @var bool
     */
    public static $log_enabled = false;

    /**
     * Logger instance
     *
     * @var WC_Logger
     */
    public static $log = false;

    /**
     * Constructor for the gateway.
     */
    public function __construct() {
        $this->id                = 'cth_payfast';
        $this->has_fields        = false;
        $this->order_button_text = __( 'Proceed to PayFast', 'townhub-woo-payments' );
        $this->method_title      = __( 'TownHub PayFast', 'townhub-woo-payments' );
        /* translators: %s: Link to WC system status page */
        $this->method_description = __( 'This is currently needed for selling RECURRING membership package. It redirects customers to PayFast to enter their payment information.', 'townhub-woo-payments' );
        // $this->supports           = array(
        //     'products',
        //     'refunds',
        // );

        // Load the settings.
        $this->init_form_fields();
        $this->init_settings();

        // Define user set variables.
        $this->title          = $this->get_option( 'title' );
        $this->description    = $this->get_option( 'description' );
        $this->testmode       = 'yes' === $this->get_option( 'testmode', 'no' );
        $this->debug          = 'yes' === $this->get_option( 'debug', 'no' );
        $this->merchant_id = $this->get_option( 'merchant_id' );
        $this->merchant_key = $this->get_option( 'merchant_key' );
        $this->email_confirmation = $this->get_option( 'email_confirmation' );
        $this->confirmation_address = $this->get_option( 'confirmation_address' );



        self::$log_enabled    = $this->debug;

        if ( $this->testmode ) {
            /* translators: %s: Link to PayFast sandbox testing guide page */
            $this->description .= ' ' . sprintf( __( 'SANDBOX ENABLED. You can use sandbox testing accounts only. See the <a href="%s">PayFast Sandbox Testing Guide</a> for more details.', 'townhub-woo-payments' ), 'https://developers.payfast.co.za/documentation/#testing-and-tools' );
            $this->description  = trim( $this->description );
        }

        // add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        // add_action( 'woocommerce_order_status_on-hold_to_processing', array( $this, 'capture_payment' ) );
        // add_action( 'woocommerce_order_status_on-hold_to_completed', array( $this, 'capture_payment' ) );

        if ( ! $this->is_valid_for_use() ) {
            $this->enabled = 'no';
        } else {
            include_once dirname( __FILE__ ) . '/payfast-ipn.php';
            new CTH_Woo_Payments_PayFast_IPN( $this );
        }
    }

    /**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
		$this->form_fields = include 'settings-payfast.php';
	}

    /**
     * Check if this gateway is enabled and available in the user's country.
     *
     * @return bool
     */
    public function is_valid_for_use() {
        return in_array(
            get_woocommerce_currency(),
            apply_filters(
                'woocommerce_payfast_supported_currencies',
                array( 'ZAR', 'AUD', 'BRL', 'CAD', 'MXN', 'NZD', 'HKD', 'SGD', 'USD', 'EUR', 'JPY', 'TRY', 'NOK', 'CZK', 'DKK', 'HUF', 'ILS', 'MYR', 'PHP', 'PLN', 'SEK', 'CHF', 'TWD', 'THB', 'GBP', 'RMB', 'RUB', 'INR' )
            ),
            true
        );
    }

    /**
     * Admin Panel Options.
     * - Options for bits like 'title' and availability on a country-by-country basis.
     *
     */
    public function admin_options() {
        if ( $this->is_valid_for_use() ) {
            parent::admin_options();
        } else {
            ?>
            <div class="inline error">
                <p>
                    <strong><?php esc_html_e( 'Gateway disabled', 'townhub-woo-payments' ); ?></strong>: <?php esc_html_e( 'PayFast does not support your store currency.', 'townhub-woo-payments' ); ?>
                </p>
            </div>
            <?php
        }
    }



    /**
     * Get gateway icon.
     *
     * @return string
     */
    public function get_icon() {
        $icon_html = '';
        
        $icon_html .= '<img class="cth-woo-payment-icon" src="' . trailingslashit( CTH_WOO_PMS_URL ) . 'assets/images/payfast.png' . '" alt="' . esc_attr__( 'PayFast acceptance mark', 'townhub-woo-payments' ) . '" />';
        return apply_filters( 'woocommerce_gateway_icon', $icon_html, $this->id );
    }


    /**
     * Process the payment and return the result.
     *
     * @param  int $order_id Order ID.
     * @return array
     */
    public function process_payment( $order_id ) {
        include_once dirname( __FILE__ ) . '/payfast-request.php';

        $order          = wc_get_order( $order_id );
        $payfast_request = new CTH_Woo_Payments_PayFast_Request( $this );

        return array(
            'result'   => 'success',
            'redirect' => $payfast_request->get_request_url( $order, $this->testmode ),
        );
    }

    /**
     * Check if the gateway is available for use.
     *
     * @return bool
     */
    public function is_available() {
        $is_available = ( 'yes' === $this->enabled );

        if ( WC()->cart && 0 < $this->get_order_total() && 0 < $this->max_amount && $this->max_amount < $this->get_order_total() ) {
            $is_available = false;
        }
        // default is disable
        $is_available = false;

        if(WC()->cart){
            // check if there is exist membership package in cart
            foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
                $product = $values['data'];
                if ( $product ) {
                    if( 'lplan' == get_post_type($product->get_id()) ){
                        if( 'yes' === $this->enabled && get_post_meta( $product->get_id() , ESB_META_PREFIX.'is_recurring', true ) ) $is_available = true;
                    }
                }
            }
        }

        return $is_available;
    }


    /**
     * Logging method.
     *
     * @param string $message Log message.
     * @param string $level Optional. Default 'info'. Possible values:
     *                      emergency|alert|critical|error|warning|notice|info|debug.
     */
    public static function log( $message, $level = 'info' ) {
        if ( self::$log_enabled ) {
            if ( empty( self::$log ) ) {
                self::$log = wc_get_logger();
            }
            self::$log->log( $level, $message, array( 'source' => 'cth-payfast' ) );
        }
    }


}