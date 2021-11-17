<?php 
/*
Plugin Name: TownHub Woo Payments
Plugin URI: https://townhub.cththemes.com
Description: A custom plugin for TownHub - Directory & Listing WordPress Theme
Version: 1.6.5
Author: CTHthemes
Author URI: http://themeforest.net/user/cththemes
Text Domain: townhub-woo-payments
Domain Path: /languages/
Copyright: ( C ) 2014 - 2021 cththemes.com . All rights reserved.
License: GNU General Public License version 3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined('ABSPATH') ) {
    die('Please do not load this file directly!');
}

function townhub_woo_payments_missing_wc_notice() {
    /* translators: 1. URL link. */
    echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'TownHub WooCommerce Payments requires WooCommerce to be installed and active. You can download %s here.', 'townhub-woo-payments' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
}

function townhub_woo_payments_missing_adons() {
    /* translators: 1. URL link. */
    echo '<div class="error"><p><strong>' . esc_html__( 'TownHub WooCommerce Payments requires TownHub Add-Ons to be installed and active. You can install it from Appearance -> Install Plugins screen.', 'townhub-woo-payments' ) . '</strong></p></div>';
}

add_action( 'plugins_loaded', 'townhub_woo_payments_init' );

function townhub_woo_payments_init() {
    load_plugin_textdomain( 'townhub-woo-payments', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

    if ( ! class_exists( 'WooCommerce' ) ) {
        add_action( 'admin_notices', 'townhub_woo_payments_missing_wc_notice' );
        return;
    }

    if ( ! class_exists( 'TownHub_Addons' ) ) {
        add_action( 'admin_notices', 'townhub_woo_payments_missing_adons' );
        return;
    }

    

    if ( ! class_exists( 'CTH_Woo_Payments' ) ) :
        define('CTH_WOO_PMS_PATH', plugin_dir_path( __FILE__ ));
        define('CTH_WOO_PMS_URL', plugin_dir_url( __FILE__ ));
        
        /**
         * 
         */
        class CTH_Woo_Payments
        {
            
            /**
             * @var Singleton The reference the *Singleton* instance of this class
             */
            private static $instance;

            /**
             * Returns the *Singleton* instance of this class.
             *
             * @return Singleton The *Singleton* instance.
             */
            public static function get_instance() {
                if ( null === self::$instance ) {
                    self::$instance = new self();
                }
                return self::$instance;
            }

            /**
             * Private clone method to prevent cloning of the instance of the
             * *Singleton* instance.
             *
             * @return void
             */
            private function __clone() {}

            /**
             * Private unserialize method to prevent unserializing of the *Singleton*
             * instance.
             *
             * @return void
             */
            private function __wakeup() {}

            /**
             * Protected constructor to prevent creating a new instance of the
             * *Singleton* via the `new` operator from outside of this class.
             */
            private function __construct() {
                $this->init();
            }

            private function init(){
                require_once dirname( __FILE__ ) . '/includes/paypal/paypal.php';
                require_once dirname( __FILE__ ) . '/includes/payfast/payfast.php';

                add_filter( 'woocommerce_payment_gateways', array( $this, 'add_gateways' ) );
                // filter woocommerce_available_payment_gateways for membership package recurring
                add_filter( 'woocommerce_available_payment_gateways', array( $this, 'available_gateways' ) );
            }

            public function add_gateways( $methods ) {
                $methods[] = 'CTH_Woo_Payments_Paypal'; 
                $methods[] = 'CTH_Woo_Payments_PayFast'; 
                return $methods;
            }
            public function available_gateways($_available_gateways){
                if(WC()->cart){
                    // check if there is exist membership package in cart
                    foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
                        $product = $values['data'];
                        if ( $product ) {
                            if( 'lplan' == get_post_type($product->get_id()) && get_post_meta( $product->get_id() , ESB_META_PREFIX.'is_recurring', true ) ){
                                $new_gateways = array();
                                if(isset($_available_gateways['cth_paypal'])) $new_gateways['cth_paypal'] = $_available_gateways['cth_paypal'];
                                if(isset($_available_gateways['cth_stripe'])) $new_gateways['cth_stripe'] = $_available_gateways['cth_stripe'];
                                if(isset($_available_gateways['cth_payfast'])) $new_gateways['cth_payfast'] = $_available_gateways['cth_payfast'];
                                return $new_gateways;
                            }
                        }
                    }
                }
                return $_available_gateways;
            }

        }

        CTH_Woo_Payments::get_instance();

    endif;
    // end check if woo payments class exsist

}