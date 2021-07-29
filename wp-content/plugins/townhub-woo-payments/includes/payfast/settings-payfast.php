<?php
/* add_ons_php */
/**
 * Settings for PayFast Gateway.
 *
 * @package WooCommerce/Classes/Payment
 */

defined('ABSPATH') || exit;

return array(
    'enabled'      => array(
        'title'   => __('Enable/Disable', 'townhub-woo-payments'),
        'type'    => 'checkbox',
        'label'   => __('Enable PayFast Recurring', 'townhub-woo-payments'),
        'default' => 'no',
    ),
    'title'        => array(
        'title'       => __('Title', 'townhub-woo-payments'),
        'type'        => 'text',
        'description' => __('This controls the title which the user sees during checkout.', 'townhub-woo-payments'),
        'default'     => 'TownHub PayFast',
        'desc_tip'    => true,
    ),
    'description'  => array(
        'title'       => __('Description', 'townhub-woo-payments'),
        'type'        => 'text',
        'desc_tip'    => true,
        'description' => __('This controls the description which the user sees during checkout.', 'townhub-woo-payments'),
        'default'     => "Pay via PayFast; you can pay with your credit card if you don't have a PayFast account.",
    ),

    'merchant'     => array(
        'title'       => __('Merchant options', 'townhub-woo-payments'),
        'type'        => 'title',
        'description' => '',
    ),

    'merchant_id'  => array(
        'title'       => __('Merchant ID', 'townhub-woo-payments'),
        'type'        => 'text',
        'description' => __('You PayFast merchant id', 'townhub-woo-payments'),
        'default'     => '',
        'desc_tip'    => false,
    ),

    'merchant_key' => array(
        'title'       => __('Merchant Key', 'townhub-woo-payments'),
        'type'        => 'text',
        'description' => __('You PayFast merchant key', 'townhub-woo-payments'),
        'default'     => '',
        'desc_tip'    => false,
    ),

    'passphrase' => array(
        'title'       => __('Merchant passphrase', 'townhub-woo-payments'),
        'type'        => 'text',
        'description' => sprintf( __( 'Enter your PayFast passphrase. Learn how to create your <a href="%s">PayFast passphrase</a>.<br /><a href="%s">WooCommerce PayFast Payment Gateway</a>', 'townhub-woo-payments' ), 'https://support.payfast.co.za/article/120-how-do-i-enable-a-passphrase-on-my-payfast-account', 'https://docs.woocommerce.com/document/payfast-payment-gateway/' ),
        'default'     => '',
        'desc_tip'    => false,
    ),

    'email_confirmation'      => array(
        'title'       => __( 'Email Confirmation?', 'townhub-woo-payments' ),
        'type'        => 'checkbox',
        'label'       => __( 'Enable email confirmation', 'townhub-woo-payments' ),
        'default'     => 'yes',
        'description' => __( 'Whether to send email confirmation to the merchant of the transaction.', 'townhub-woo-payments' ),
    ),
    'confirmation_address'        => array(
        'title'       => __( 'Confirmation Email Address', 'townhub-woo-payments' ),
        'type'        => 'email',
        'description' => __( 'The address to send the confirmation email to.', 'townhub-woo-payments' ),
        'default'     => '',
        'desc_tip'    => true,
        'placeholder' => 'you@youremail.com',
    ),


    'advanced'     => array(
        'title'       => __('Advanced options', 'townhub-woo-payments'),
        'type'        => 'title',
        'description' => '',
    ),
    'testmode'     => array(
        'title'       => __('PayFast sandbox', 'townhub-woo-payments'),
        'type'        => 'checkbox',
        'label'       => __('Enable PayFast sandbox', 'townhub-woo-payments'),
        'default'     => 'no',
        /* translators: %s: URL */
        'description' => sprintf(__('PayFast sandbox can be used to test payments. Sign up for a <a href="%s">developer account</a>.', 'townhub-woo-payments'), 'https://developer.payfast.com/'),
    ),
    'debug'        => array(
        'title'       => __('Debug log', 'townhub-woo-payments'),
        'type'        => 'checkbox',
        'label'       => __('Enable logging', 'townhub-woo-payments'),
        'default'     => 'no',
        /* translators: %s: URL */
        'description' => sprintf(__('Log PayFast events, such as IPN requests, inside %s Note: this may log personal information. We recommend using this for debugging purposes only and deleting the logs when finished.', 'townhub-woo-payments'), '<code>' . WC_Log_Handler_File::get_log_file_path('cth-payfast') . '</code>'),
    ),

);
