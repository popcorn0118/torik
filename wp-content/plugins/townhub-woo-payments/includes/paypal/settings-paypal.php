<?php 
/* add_ons_php */
/**
 * Settings for PayPal Gateway.
 *
 * @package WooCommerce/Classes/Payment
 */

defined( 'ABSPATH' ) || exit;

return array(
	'enabled'               => array(
		'title'   => __( 'Enable/Disable', 'townhub-woo-payments' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable PayPal Standard', 'townhub-woo-payments' ),
		'default' => 'no',
	),
	'title'                 => array(
		'title'       => __( 'Title', 'townhub-woo-payments' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'townhub-woo-payments' ),
		'default'     => __( 'TownHub PayPal', 'townhub-woo-payments' ),
		'desc_tip'    => true,
	),
	'description'           => array(
		'title'       => __( 'Description', 'townhub-woo-payments' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'This controls the description which the user sees during checkout.', 'townhub-woo-payments' ),
		'default'     => __( "Pay via PayPal; you can pay with your credit card if you don't have a PayPal account.", 'townhub-woo-payments' ),
	),
	'email'                 => array(
		'title'       => __( 'PayPal email', 'townhub-woo-payments' ),
		'type'        => 'email',
		'description' => __( 'Please enter your PayPal email address; this is needed in order to take payment.', 'townhub-woo-payments' ),
		'default'     => get_option( 'admin_email' ),
		'desc_tip'    => true,
		'placeholder' => 'you@youremail.com',
	),
	'advanced'              => array(
		'title'       => __( 'Advanced options', 'townhub-woo-payments' ),
		'type'        => 'title',
		'description' => '',
	),
	'testmode'              => array(
		'title'       => __( 'PayPal sandbox', 'townhub-woo-payments' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable PayPal sandbox', 'townhub-woo-payments' ),
		'default'     => 'no',
		/* translators: %s: URL */
		'description' => sprintf( __( 'PayPal sandbox can be used to test payments. Sign up for a <a href="%s">developer account</a>.', 'townhub-woo-payments' ), 'https://developer.paypal.com/' ),
	),
	'debug'                 => array(
		'title'       => __( 'Debug log', 'townhub-woo-payments' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable logging', 'townhub-woo-payments' ),
		'default'     => 'no',
		/* translators: %s: URL */
		'description' => sprintf( __( 'Log PayPal events, such as IPN requests, inside %s Note: this may log personal information. We recommend using this for debugging purposes only and deleting the logs when finished.', 'townhub-woo-payments' ), '<code>' . WC_Log_Handler_File::get_log_file_path( 'cth-paypal' ) . '</code>' ),
	),
	'ipn_notification'      => array(
		'title'       => __( 'IPN Email Notifications', 'townhub-woo-payments' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable IPN email notifications', 'townhub-woo-payments' ),
		'default'     => 'yes',
		'description' => __( 'Send notifications when an IPN is received from PayPal indicating refunds, chargebacks and cancellations.', 'townhub-woo-payments' ),
	),
	'receiver_email'        => array(
		'title'       => __( 'Receiver email', 'townhub-woo-payments' ),
		'type'        => 'email',
		'description' => __( 'If your main PayPal email differs from the PayPal email entered above, input your main receiver email for your PayPal account here. This is used to validate IPN requests.', 'townhub-woo-payments' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => 'you@youremail.com',
	),
	'identity_token'        => array(
		'title'       => __( 'PayPal identity token', 'townhub-woo-payments' ),
		'type'        => 'text',
		'description' => __( 'Optionally enable "Payment Data Transfer" (Profile > Profile and Settings > My Selling Tools > Website Preferences) and then copy your identity token here. This will allow payments to be verified without the need for PayPal IPN.', 'townhub-woo-payments' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => '',
	),
	'invoice_prefix'        => array(
		'title'       => __( 'Invoice prefix', 'townhub-woo-payments' ),
		'type'        => 'text',
		'description' => __( 'Please enter a prefix for your invoice numbers. If you use your PayPal account for multiple stores ensure this prefix is unique as PayPal will not allow orders with the same invoice number.', 'townhub-woo-payments' ),
		'default'     => 'WC-',
		'desc_tip'    => true,
	),
	'send_shipping'         => array(
		'title'       => __( 'Shipping details', 'townhub-woo-payments' ),
		'type'        => 'checkbox',
		'label'       => __( 'Send shipping details to PayPal instead of billing.', 'townhub-woo-payments' ),
		'description' => __( 'PayPal allows us to send one address. If you are using PayPal for shipping labels you may prefer to send the shipping address rather than billing. Turning this option off may prevent PayPal Seller protection from applying.', 'townhub-woo-payments' ),
		'default'     => 'yes',
	),
	'address_override'      => array(
		'title'       => __( 'Address override', 'townhub-woo-payments' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable "address_override" to prevent address information from being changed.', 'townhub-woo-payments' ),
		'description' => __( 'PayPal verifies addresses therefore this setting can cause errors (we recommend keeping it disabled).', 'townhub-woo-payments' ),
		'default'     => 'no',
	),
	'paymentaction'         => array(
		'title'       => __( 'Payment action', 'townhub-woo-payments' ),
		'type'        => 'select',
		'class'       => 'wc-enhanced-select',
		'description' => __( 'Choose whether you wish to capture funds immediately or authorize payment only.', 'townhub-woo-payments' ),
		'default'     => 'sale',
		'desc_tip'    => true,
		'options'     => array(
			'sale'          => __( 'Capture', 'townhub-woo-payments' ),
			'authorization' => __( 'Authorize', 'townhub-woo-payments' ),
		),
	),
	'page_style'            => array(
		'title'       => __( 'Page style', 'townhub-woo-payments' ),
		'type'        => 'text',
		'description' => __( 'Optionally enter the name of the page style you wish to use. These are defined within your PayPal account. This affects classic PayPal checkout screens.', 'townhub-woo-payments' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'townhub-woo-payments' ),
	),
	'image_url'             => array(
		'title'       => __( 'Image url', 'townhub-woo-payments' ),
		'type'        => 'text',
		'description' => __( 'Optionally enter the URL to a 150x50px image displayed as your logo in the upper left corner of the PayPal checkout pages.', 'townhub-woo-payments' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'townhub-woo-payments' ),
	),
	// 'api_details'           => array(
	// 	'title'       => __( 'API credentials', 'townhub-add-ons' ),
	// 	'type'        => 'title',
	// 	/* translators: %s: URL */
	// 	'description' => sprintf( __( 'Enter your PayPal API credentials to process refunds via PayPal. Learn how to access your <a href="%s">PayPal API Credentials</a>.', 'townhub-add-ons' ), 'https://developer.paypal.com/webapps/developer/docs/classic/api/apiCredentials/#create-an-api-signature' ),
	// ),
	// 'api_username'          => array(
	// 	'title'       => __( 'Live API username', 'townhub-add-ons' ),
	// 	'type'        => 'text',
	// 	'description' => __( 'Get your API credentials from PayPal.', 'townhub-add-ons' ),
	// 	'default'     => '',
	// 	'desc_tip'    => true,
	// 	'placeholder' => __( 'Optional', 'townhub-add-ons' ),
	// ),
	// 'api_password'          => array(
	// 	'title'       => __( 'Live API password', 'townhub-add-ons' ),
	// 	'type'        => 'password',
	// 	'description' => __( 'Get your API credentials from PayPal.', 'townhub-add-ons' ),
	// 	'default'     => '',
	// 	'desc_tip'    => true,
	// 	'placeholder' => __( 'Optional', 'townhub-add-ons' ),
	// ),
	// 'api_signature'         => array(
	// 	'title'       => __( 'Live API signature', 'townhub-add-ons' ),
	// 	'type'        => 'text',
	// 	'description' => __( 'Get your API credentials from PayPal.', 'townhub-add-ons' ),
	// 	'default'     => '',
	// 	'desc_tip'    => true,
	// 	'placeholder' => __( 'Optional', 'townhub-add-ons' ),
	// ),
	// 'sandbox_api_username'  => array(
	// 	'title'       => __( 'Sandbox API username', 'townhub-add-ons' ),
	// 	'type'        => 'text',
	// 	'description' => __( 'Get your API credentials from PayPal.', 'townhub-add-ons' ),
	// 	'default'     => '',
	// 	'desc_tip'    => true,
	// 	'placeholder' => __( 'Optional', 'townhub-add-ons' ),
	// ),
	// 'sandbox_api_password'  => array(
	// 	'title'       => __( 'Sandbox API password', 'townhub-add-ons' ),
	// 	'type'        => 'password',
	// 	'description' => __( 'Get your API credentials from PayPal.', 'townhub-add-ons' ),
	// 	'default'     => '',
	// 	'desc_tip'    => true,
	// 	'placeholder' => __( 'Optional', 'townhub-add-ons' ),
	// ),
	// 'sandbox_api_signature' => array(
	// 	'title'       => __( 'Sandbox API signature', 'townhub-add-ons' ),
	// 	'type'        => 'text',
	// 	'description' => __( 'Get your API credentials from PayPal.', 'townhub-add-ons' ),
	// 	'default'     => '',
	// 	'desc_tip'    => true,
	// 	'placeholder' => __( 'Optional', 'townhub-add-ons' ),
	// ),
);
