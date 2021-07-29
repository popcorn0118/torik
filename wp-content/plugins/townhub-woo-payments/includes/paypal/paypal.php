<?php 
/* add_ons_php */

class CTH_Woo_Payments_Paypal extends WC_Payment_Gateway {
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
        $this->id                = 'cth_paypal';
        $this->has_fields        = false;
        $this->order_button_text = __( 'Proceed to PayPal', 'townhub-woo-payments' );
        $this->method_title      = __( 'TownHub PayPal', 'townhub-woo-payments' );
        /* translators: %s: Link to WC system status page */
        $this->method_description = __( 'This is currently needed for selling RECURRING membership package. It redirects customers to PayPal to enter their payment information.', 'townhub-woo-payments' );
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
        $this->email          = $this->get_option( 'email' );
        $this->receiver_email = $this->get_option( 'receiver_email', $this->email );
        $this->identity_token = $this->get_option( 'identity_token' );
        self::$log_enabled    = $this->debug;

        if ( $this->testmode ) {
            /* translators: %s: Link to PayPal sandbox testing guide page */
            $this->description .= ' ' . sprintf( __( 'SANDBOX ENABLED. You can use sandbox testing accounts only. See the <a href="%s">PayPal Sandbox Testing Guide</a> for more details.', 'townhub-woo-payments' ), 'https://developer.paypal.com/docs/classic/lifecycle/ug_sandbox/' );
            $this->description  = trim( $this->description );
        }

        // add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        // add_action( 'woocommerce_order_status_on-hold_to_processing', array( $this, 'capture_payment' ) );
        // add_action( 'woocommerce_order_status_on-hold_to_completed', array( $this, 'capture_payment' ) );

        if ( ! $this->is_valid_for_use() ) {
            $this->enabled = 'no';
        } else {
            include_once dirname( __FILE__ ) . '/paypal-ipn.php';
            new CTH_Woo_Payments_Paypal_IPN( $this->testmode, $this->receiver_email );
        }
    }

    /**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
		$this->form_fields = include 'settings-paypal.php';
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
                'woocommerce_paypal_supported_currencies',
                array( 'AUD', 'BRL', 'CAD', 'MXN', 'NZD', 'HKD', 'SGD', 'USD', 'EUR', 'JPY', 'TRY', 'NOK', 'CZK', 'DKK', 'HUF', 'ILS', 'MYR', 'PHP', 'PLN', 'SEK', 'CHF', 'TWD', 'THB', 'GBP', 'RMB', 'RUB', 'INR' )
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
                    <strong><?php esc_html_e( 'Gateway disabled', 'townhub-woo-payments' ); ?></strong>: <?php esc_html_e( 'PayPal does not support your store currency.', 'townhub-woo-payments' ); ?>
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
        $icon      = (array) $this->get_icon_image( WC()->countries->get_base_country() );

        foreach ( $icon as $i ) {
            $icon_html .= '<img src="' . esc_attr( $i ) . '" alt="' . esc_attr__( 'PayPal acceptance mark', 'townhub-woo-payments' ) . '" />';
        }

        $icon_html .= sprintf( '<a href="%1$s" class="about_paypal" onclick="javascript:window.open(\'%1$s\',\'WIPaypal\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700\'); return false;">' . esc_attr__( 'What is PayPal?', 'townhub-woo-payments' ) . '</a>', esc_url( $this->get_icon_url( WC()->countries->get_base_country() ) ) );

        return apply_filters( 'woocommerce_gateway_icon', $icon_html, $this->id );
    }

    /**
     * Get the link for an icon based on country.
     *
     * @param  string $country Country two letter code.
     * @return string
     */
    protected function get_icon_url( $country ) {
        $url           = 'https://www.paypal.com/' . strtolower( $country );
        $home_counties = array( 'BE', 'CZ', 'DK', 'HU', 'IT', 'JP', 'NL', 'NO', 'ES', 'SE', 'TR', 'IN' );
        $countries     = array( 'DZ', 'AU', 'BH', 'BQ', 'BW', 'CA', 'CN', 'CW', 'FI', 'FR', 'DE', 'GR', 'HK', 'ID', 'JO', 'KE', 'KW', 'LU', 'MY', 'MA', 'OM', 'PH', 'PL', 'PT', 'QA', 'IE', 'RU', 'BL', 'SX', 'MF', 'SA', 'SG', 'SK', 'KR', 'SS', 'TW', 'TH', 'AE', 'GB', 'US', 'VN' );

        if ( in_array( $country, $home_counties, true ) ) {
            return $url . '/webapps/mpp/home';
        } elseif ( in_array( $country, $countries, true ) ) {
            return $url . '/webapps/mpp/paypal-popup';
        } else {
            return $url . '/cgi-bin/webscr?cmd=xpt/Marketing/general/WIPaypal-outside';
        }
    }

    /**
     * Get PayPal images for a country.
     *
     * @param string $country Country code.
     * @return array of image URLs
     */
    protected function get_icon_image( $country ) {
        switch ( $country ) {
            case 'US':
            case 'NZ':
            case 'CZ':
            case 'HU':
            case 'MY':
                $icon = 'https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg';
                break;
            case 'TR':
                $icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_paypal_odeme_secenekleri.jpg';
                break;
            case 'GB':
                $icon = 'https://www.paypalobjects.com/webstatic/mktg/Logo/AM_mc_vs_ms_ae_UK.png';
                break;
            case 'MX':
                $icon = array(
                    'https://www.paypal.com/es_XC/Marketing/i/banner/paypal_visa_mastercard_amex.png',
                    'https://www.paypal.com/es_XC/Marketing/i/banner/paypal_debit_card_275x60.gif',
                );
                break;
            case 'FR':
                $icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_paypal_moyens_paiement_fr.jpg';
                break;
            case 'AU':
                $icon = 'https://www.paypalobjects.com/webstatic/en_AU/mktg/logo/Solutions-graphics-1-184x80.jpg';
                break;
            case 'DK':
                $icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_PayPal_betalingsmuligheder_dk.jpg';
                break;
            case 'RU':
                $icon = 'https://www.paypalobjects.com/webstatic/ru_RU/mktg/business/pages/logo-center/AM_mc_vs_dc_ae.jpg';
                break;
            case 'NO':
                $icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/banner_pl_just_pp_319x110.jpg';
                break;
            case 'CA':
                $icon = 'https://www.paypalobjects.com/webstatic/en_CA/mktg/logo-image/AM_mc_vs_dc_ae.jpg';
                break;
            case 'HK':
                $icon = 'https://www.paypalobjects.com/webstatic/en_HK/mktg/logo/AM_mc_vs_dc_ae.jpg';
                break;
            case 'SG':
                $icon = 'https://www.paypalobjects.com/webstatic/en_SG/mktg/Logos/AM_mc_vs_dc_ae.jpg';
                break;
            case 'TW':
                $icon = 'https://www.paypalobjects.com/webstatic/en_TW/mktg/logos/AM_mc_vs_dc_ae.jpg';
                break;
            case 'TH':
                $icon = 'https://www.paypalobjects.com/webstatic/en_TH/mktg/Logos/AM_mc_vs_dc_ae.jpg';
                break;
            case 'JP':
                $icon = 'https://www.paypal.com/ja_JP/JP/i/bnr/horizontal_solution_4_jcb.gif';
                break;
            case 'IN':
                $icon = 'https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg';
                break;
            default:
                $icon = WC_HTTPS::force_https_url( WC()->plugin_url() . '/includes/gateways/paypal/assets/images/paypal.png' );
                break;
        }
        return apply_filters( 'cththemes_paypal_icon', $icon );
    }


    /**
     * Process the payment and return the result.
     *
     * @param  int $order_id Order ID.
     * @return array
     */
    public function process_payment( $order_id ) {
        include_once dirname( __FILE__ ) . '/paypal-request.php';

        $order          = wc_get_order( $order_id );
        $paypal_request = new CTH_Woo_Payments_Paypal_Request( $this );

        return array(
            'result'   => 'success',
            'redirect' => $paypal_request->get_request_url( $order, $this->testmode ),
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
            self::$log->log( $level, $message, array( 'source' => 'cth-paypal' ) );
        }
    }


}