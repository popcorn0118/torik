<?php 
/* add_ons_php */

function townhub_addons_options_get_payments(){
    return array_merge( 
        array(
            array(
                "type" => "section",
                'id' => 'payments_sec_general',
                "title" => __( 'General Options', 'townhub-add-ons' ),
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'payments_test_mode',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Test mode', 'townhub-add-ons'),
                'desc'  => __('While in test mode no live transactions are processed. To fully use test mode, you must have a sandbox (test) account for the payment gateway you are testing.', 'townhub-add-ons'),
            ),

            array(
                "type" => "section",
                'id' => 'payments_sec_form',
                "title" => __( 'Submit Form', 'townhub-add-ons' ),
            ),

            
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'payments_form_enable',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Enable/Disable', 'townhub-add-ons'),
                'desc'  => __('Enable this payment method', 'townhub-add-ons'),
            ),

            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'payments_form_details',
                'args'=> array(
                    'default' => '<p>Your payment details will be submitted for review.</p>',
                ),
                "title" => __('Payment description', 'townhub-add-ons'),
                // 'desc'  => __( 'Enter your bank account details', 'townhub-add-ons' ) ,
            ),

            array(
                "type" => "section",
                'id' => 'payments_sec_cod',
                "title" => __( 'Cash on delivery', 'townhub-add-ons' ),
            ),

            
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'payments_cod_enable',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Enable/Disable', 'townhub-add-ons'),
                'desc'  => __('Enable this payment method', 'townhub-add-ons'),
            ),

            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'payments_cod_details',
                'args'=> array(
                    'default' => '<p>Your payment details will be submitted. Then pay on delivery.</p>',
                ),
                "title" => __('Payment description', 'townhub-add-ons'),
                // 'desc'  => __( 'Enter your bank account details', 'townhub-add-ons' ) ,
            ),

            array(
                "type" => "section",
                'id' => 'payments_sec_bank',
                "title" => __( 'Bank Transfer', 'townhub-add-ons' ),
            ),

            
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'payments_banktransfer_enable',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Enable/Disable', 'townhub-add-ons'),
                'desc'  => __('Enable this payment method', 'townhub-add-ons'),
            ),

            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'payments_banktransfer_details',
                'args'=> array(
                    'default' => '<p>
    <strong>Bank name</strong>: Bank of America, NA<br />
    <strong>Bank account number</strong>: 0175380000<br />
    <strong>Bank address</strong>:USA 27TH Brooklyn NY<br />
    <strong>Bank SWIFT code</strong>: BOFAUS 3N<br />
    </p>',
                ),
                "title" => __('Bank Account', 'townhub-add-ons'),
                'desc'  => __( 'Enter your bank account details', 'townhub-add-ons' ) ,
            ),

            array(
                "type" => "section",
                'id' => 'payments_sec_paypal',
                "title" => __( 'Paypal Payment', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'payments_paypal_enable',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Enable/Disable', 'townhub-add-ons'),
                'desc'  => __('Enable this payment method', 'townhub-add-ons'),
            ),

            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'payments_paypal_desc',
                'args'=> array(
                    'default' => '<p>Pay via PayPal; you can pay with your credit card if you don’t have a PayPal account.</p>',
                ),
                "title" => __('Payment description', 'townhub-add-ons'),
                // 'desc'  => __( '', 'townhub-add-ons' ) ,
            ),

            array(
                "type"          => "field",
                "field_type"    => "text",
                'id'            => 'payments_paypal_business',
                'args'=> array(
                    'default'=> 'cththemespp-facilitator@gmail.com',
                ),
                "title"         => __('Paypal Business Email', 'townhub-add-ons'),
                'desc'          => ''
            ),

            array(
                "type" => "section",
                'id' => 'payments_sec_stripe',
                "title" => __( 'Stripe Payment', 'townhub-add-ons' ),
            ),

            

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'payments_stripe_enable',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Enable/Disable', 'townhub-add-ons'),
                'desc'  => __('Enable this payment method', 'townhub-add-ons'),
            ),
            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'payments_stripe_desc',
                'args'=> array(
                    'default' => '<p>Pay via Stripe; you can pay with your credit card.</p>',
                ),
                "title" => __('Payment description', 'townhub-add-ons'),
                // 'desc'  => __( '', 'townhub-add-ons' ) ,
            ),
            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'stripe_pm_methods',
                "title" => __('Accept payment methods', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> array('card'),
                    'options'=> array(
                        'alipay'            => 'Alipay',
                        // 'au_becs_debit'     => 'au_becs_debit',
                        'bacs_debit'        => 'bacs_debit',
                        'bancontact'        => 'bancontact',
                        'card'              => 'card',
                        // 'card_present'      => 'card_present',
                        'eps'               => 'eps',
                        'fpx'               => 'fpx',
                        'giropay'           => 'giropay',
                        'grabpay'           => 'grabpay',
                        'ideal'             => 'ideal',
                        // 'interac_present'   => 'interac_present',
                        // 'oxxo'              => 'oxxo',
                        'p24'               => 'p24',
                        'sepa_debit'        => 'sepa_debit',
                        'sofort'            => 'sofort',
                    ),
                    // alipay, card, ideal, fpx, bacs_debit, bancontact, giropay, p24, eps, sofort, sepa_debit, or grabpay
                    'multiple' => true,
                    'use-select2' => true
                ),
                'desc' => esc_html__("Please make sure they are activated in your dashboard ", 'townhub-add-ons'). '(https://dashboard.stripe.com/account/payments/settings)', 
            ),

            

            array(
                "type" => "section",
                'id' => 'payments_stripe_apis',
                "title" => __( 'Stripe API Keys - Settings', 'townhub-add-ons' ),
                'callback' => function(){
                    echo sprintf(__( '<p>You can get api keys in <a href="%s" target="_blank">the Dashboard</a></p>', 'townhub-add-ons' ), esc_url('https://dashboard.stripe.com/account/apikeys'));
                    
                }
            ),

            array(
                "type"          => "field",
                "field_type"    => "text",
                'id'            => 'payments_stripe_live_secret',
                // 'args'=> array(
                //     'default'=> '',
                // ),
                "title"         => __('Live Secret Key', 'townhub-add-ons'),
                'desc'          => ''
            ),

            array(
                "type"          => "field",
                "field_type"    => "text",
                'id'            => 'payments_stripe_live_public',
                // 'args'=> array(
                //     'default'=> '',
                // ),
                "title"         => __('Live Publishable Key', 'townhub-add-ons'),
                'desc'          => ''
            ),

            array(
                "type"          => "field",
                "field_type"    => "text",
                'id'            => 'payments_stripe_test_secret',
                // 'args'=> array(
                //     'default'=> '',
                // ),
                "title"         => __('Test Secret Key', 'townhub-add-ons'),
                'desc'          => __( 'For test mode only', 'townhub-add-ons' ),
            ),

            array(
                "type"          => "field",
                "field_type"    => "text",
                'id'            => 'payments_stripe_test_public',
                // 'args'=> array(
                //     'default'=> '',
                // ),
                "title"         => __('Test Publishable Key', 'townhub-add-ons'),
                'desc'          => __( 'For test mode only', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "info",
                'id' => 'payments_stripe_webhook',
                "title" => __('Webhooks End Point', 'townhub-add-ons'),
                'desc'  => sprintf( __( '<p>Webhooks are configured in the <a href="%1$s" target="_blank">Webhooks setting</a> section of the Dashboard.<br>Clicking <strong>Add endpoint</strong> reveals a form to add this URL <span class="webhooks-url">%2$s</span> for receiving webhooks.</p><p><img src="%3$s" class="webhooks-img"></p>', 'townhub-add-ons' ), esc_url('https://dashboard.stripe.com/account/webhooks'), esc_url(home_url('/?action=cth_stripewebhook' ) ), ESB_DIR_URL.'assets/admin/stripe-webhook.png'), 
            ),

            array(
                "type" => "field",
                "field_type" => "image",
                'id' => 'stripe_logo',
                "title" => __('Logo', 'townhub-add-ons'),
                'desc'  => __( 'A square image of your brand or product. The recommended minimum size is 128x128px. The supported image types are: <b>.gif</b>, <b>.jpeg</b>, and <b>.png</b>.', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'payments_stripe_use_email',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Use User Email', 'townhub-add-ons'),
                'desc'  => __('Enable this option for using current user email as Stripe checkout email form.', 'townhub-add-ons'),
            ),

            array(
                "type" => "section",
                'id' => 'payments_sec_payfast',
                "title" => __( 'Payfast Payment', 'townhub-add-ons' ),
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'payments_payfast_enable',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Enable/Disable', 'townhub-add-ons'),
                'desc'  => __('Enable this payment method', 'townhub-add-ons'),
            ),
            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'payments_payfast_desc',
                'args'=> array(
                    'default' => '<p>Pay via Payfast; you can pay with your credit card.</p>',
                ),
                "title" => __('Payment description', 'townhub-add-ons'),
                // 'desc'  => __( '', 'townhub-add-ons' ) ,
            ),
            array(
                "type"          => "field",
                "field_type"    => "text",
                'id'            => 'payments_payfast_merchant_id',
                // 'args'=> array(
                //     'default'=> '',
                // ),
                "title"         => __('Payfast merchant id', 'townhub-add-ons'),
                'desc'          => ''
            ),
            array(
                "type"          => "field",
                "field_type"    => "text",
                'id'            => 'payments_payfast_merchant_key',
                // 'args'=> array(
                //     'default'=> '',
                // ),
                "title"         => __('Payfast merchant key', 'townhub-add-ons'),
                'desc'          => ''
            ),

            array(
                "type"          => "field",
                "field_type"    => "text",
                'id'            => 'payfast_passphrase',
                "title"         => __('Payfast Merchant passphrase', 'townhub-add-ons'),
                'desc'          => sprintf( __( 'Enter your PayFast passphrase. Learn how to create your <a href="%s">PayFast passphrase</a>.<br /><a href="%s">WooCommerce PayFast Payment Gateway</a>', 'townhub-add-ons' ), 'https://support.payfast.co.za/article/120-how-do-i-enable-a-passphrase-on-my-payfast-account', 'https://docs.woocommerce.com/document/payfast-payment-gateway/' ),
            ),

            array(
                "type"          => "field",
                "field_type"    => "text",
                'id'            => 'payfast_rate',
                'args'=> array(
                    'default'=> '13.9893',
                ),
                "title"         => __('ZAR currency rate', 'townhub-add-ons'),
                'desc'          => __('Exchange rates for your current currency to South African Rand ( ZAR )', 'townhub-add-ons'),
            ),

            array(
                "type"          => "field",
                "field_type"    => "checkbox",
                'id'            => 'email_confirmation',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title"         => __('Email Confirmation?', 'townhub-add-ons'),
                'desc'          => __( 'Whether to send email confirmation to the merchant of the transaction.', 'townhub-add-ons' ),
            ),

            array(
                "type"          => "field",
                "field_type"    => "text",
                'id'            => 'confirmation_address',
                "title"         => __( 'Confirmation Email Address', 'townhub-add-ons' ),
                'desc'          => __( 'The address to send the confirmation email to.', 'townhub-add-ons' ),
            ),

            array(
                "type" => "section",
                'id' => 'payments_sec_skrill',
                "title" => __( 'Skrill Payment', 'townhub-add-ons' ),
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'skrill_enable',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Enable/Disable', 'townhub-add-ons'),
                'desc'  => __('Enable this payment method', 'townhub-add-ons'),
            ),
            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'skrill_desc',
                'args'=> array(
                    'default' => '<p>Pay via Skrill; you can pay with your credit card.</p>',
                ),
                "title" => __('Payment description', 'townhub-add-ons'),
                // 'desc'  => __( '', 'townhub-add-ons' ) ,
            ),
            array(
                "type"          => "field",
                "field_type"    => "text",
                'id'            => 'skrill_merchant_email',
                'args'=> array(
                    'default'=> 'demoqco@sun-fish.com',
                ),
                "title"         => __('Skrill merchant email', 'townhub-add-ons'),
                'desc'          => ''
            ),

            array(
                "type"          => "field",
                "field_type"    => "text",
                'id'            => 'skrill_secret_word',
                'args'=> array(
                    'default'=> 'skrill',
                ),
                "title"         => __('Skrill secret word', 'townhub-add-ons'),
                'desc'          => __( 'Enter your secret word ( added on Merchant Tools section of the Merchant\’s online Skrill account ).', 'townhub-add-ons' ),
            ),

            // paystack payment
            array(
                "type" => "section",
                'id' => 'payments_sec_paystack',
                "title" => __( 'Paystack Payment', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "info",
                'id' => 'payments_paystack_webhook',
                "title" => __('Webhooks End Point', 'townhub-add-ons'),
                'desc'  => sprintf( __( '<p>Webhooks is configured in the <a href="%1$s" target="_blank">API Keys & Webhooks</a> section of Paystack\'s Settings dashboard screen.<br>Use <span class="webhooks-url">%2$s</span> for Webhook URL field.</p>', 'townhub-add-ons' ), esc_url('https://dashboard.paystack.com/#/settings/developer'), home_url('/?action=cth_pstwebhook' ) ), 
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'paystack_enable',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Enable/Disable', 'townhub-add-ons'),
                'desc'  => __('Enable this payment method', 'townhub-add-ons'),
            ),
            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'paystack_desc',
                'args'=> array(
                    'default' => '<p>Pay via Payfast; you can pay with your credit card.</p>',
                ),
                "title" => __('Payment description', 'townhub-add-ons'),
                // 'desc'  => __( '', 'townhub-add-ons' ) ,
            ),
            // array(
            //     "type"          => "field",
            //     "field_type"    => "text",
            //     'id'            => 'paystack_merchant_email',
            //     'args'=> array(
            //         'default'=> 'demoqco@sun-fish.com',
            //     ),
            //     "title"         => __('Skrill merchant email', 'townhub-add-ons'),
            //     'desc'          => ''
            // ),

            array(
                "type"          => "field",
                "field_type"    => "text",
                'id'            => 'paystack_secret_key',
                'args'=> array(
                    'default'=> '',
                ),
                "title"         => __('Paystack Secret Key', 'townhub-add-ons'),
                'desc'          => __( 'Enter your secret key (from Paystack dashboard screen)', 'townhub-add-ons' ),
            ),

            array(
                "type"          => "field",
                "field_type"    => "text",
                'id'            => 'paystack_rate',
                'args'=> array(
                    'default'=> '1',
                ),
                "title"         => __('Currency Rate', 'townhub-add-ons'),
                'desc'          => __('Exchange rates for your current currency to Paystack currency. Set this to 1 if your currency and Paystack currency are the same.', 'townhub-add-ons'),
            ),

        ),
        
        (array)apply_filters( 'cth_addons_add_options_payments_tab', array() )
        
    );
}
