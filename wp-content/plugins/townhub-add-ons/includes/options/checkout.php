<?php 
/* add_ons_php */

function townhub_addons_options_get_checkout(){
    return array(
        array(
            "type" => "section",
            'id' => 'membership_checkout_sec',
            "title" => __( 'Checkout Page', 'townhub-add-ons' ),
        ),
        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'ck_hide_tabs',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => __('Hide Tabs', 'townhub-add-ons'),
            'desc'  => __( 'Check this if you want to use single page checkout instead of tabs.', 'townhub-add-ons' ),
        ),

        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'ck_hide_information',
            'args'=> array(
                'default' => 'yes',
                'value' => 'yes',
            ),
            "title" => __('Hide Information Tab', 'townhub-add-ons'),
            'desc'  => '',
        ),
        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'ck_hide_billing',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => _x('Hide Billing Tab', 'TownHub Add-Ons', 'townhub-add-ons'),
            'desc'  => '',
        ),
        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'bil_hide_name',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => _x('Hide First/Last name field', 'TownHub Add-Ons', 'townhub-add-ons'),
            'desc'  => '',
        ),
        
        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'bil_hide_company',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => _x('Hide Company field', 'TownHub Add-Ons', 'townhub-add-ons'),
            'desc'  => '',
        ),
        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'bil_hide_city_country',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => _x('Hide City/Country field', 'TownHub Add-Ons', 'townhub-add-ons'),
            'desc'  => '',
        ),
        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'bil_hide_addresses',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => _x('Hide Addresses field', 'TownHub Add-Ons', 'townhub-add-ons'),
            'desc'  => '',
        ),
        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'bil_hide_state_postcode',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => _x('Hide State/Postcode field', 'TownHub Add-Ons', 'townhub-add-ons'),
            'desc'  => '',
        ),
        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'bil_hide_phone_email',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => _x('Hide Phone/Email field', 'TownHub Add-Ons', 'townhub-add-ons'),
            'desc'  => '',
        ),
        
        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'ck_hide_payments',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => __('Hide Payments Tab', 'townhub-add-ons'),
            'desc'  => '',
        ),

        // array(
        //     "type" => "field",
        //     "field_type" => "checkbox",
        //     'id' => 'ck_show_title',
        //     'args'=> array(
        //         'default' => 'yes',
        //         'value' => 'yes',
        //     ),
        //     "title" => __('Show Checkout Title', 'townhub-add-ons'),
        //     'desc'  => '',
        // ),

        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'ck_agree_terms',
            'args'=> array(
                'default' => 'yes',
                'value' => 'yes',
            ),
            "title" => __('Agree to Terms', 'townhub-add-ons'),
            'desc'  => __('User must agree to terms before puchasing', 'townhub-add-ons'),
        ),

        array(
            "type" => "field",
            "field_type" => "textarea",
            'id' => 'ck_terms',
            "title" => __('Checkout Terms', 'townhub-add-ons'),
            // 'desc'  => __( 'Number of listings to show on a page (-1 for all)', 'townhub-add-ons' ),
            'args' => array(
                'default' => 'I have read and accept the <a target="_blank" href="#"> terms, conditions</a> and <a href="#" target="_blank">Privacy Policy</a>',
            )
        ),


        array(
            "type" => "field",
            "field_type" => "page_select",
            'id' => 'checkout_success',
            "title" => __('Checkout Success Page', 'townhub-add-ons'),
            'desc'  => __('The page display after user complete checkout.', 'townhub-add-ons'),
            'args' => array(
                'default'   => 'default',
                'default_title' => "Checkout Success",
                
            )
        ),

        array(
            "type" => "field",
            "field_type" => "checkbox",
            'id' => 'checkout_success_redirect',
            'args'=> array(
                'default' => 'yes',
                'value' => 'yes',
            ),
            "title" => __('Checkout Success Redirect', 'townhub-add-ons'),
            'desc'  => __('User will redirect to success page instead of showing in tab.', 'townhub-add-ons'),
        ),

        array(
            "type" => "section",
            'id' => 'checkout_invoice_sec',
            "title" => _x( 'Invoice Page', 'TownHub Add-Ons', 'townhub-add-ons' ),
        ),

        array(
                "type" => "field",
                "field_type" => "image",
                'id' => 'invoice_logo',
                "title" => _x( 'Invoice Logo', 'TownHub Add-Ons', 'townhub-add-ons' ),
                // 'args'=> array(
                //     'default'=> array(
                //         'url' => ESB_DIR_URL ."assets/images/marker.png"
                //     )
                // ),
                
                'desc'  => ''
            ),

        array(
            "type" => "field",
            "field_type" => "textarea",
            'id' => 'invoice_from',
            "title" => __('Invoice From text', 'townhub-add-ons'),
            // 'desc'  => __( 'Number of listings to show on a page (-1 for all)', 'townhub-add-ons' ),
            'args' => array(
                'default' => 'TownHub , Inc.<br>
USA 27TH Brooklyn NY<br>
<a href="#" style="color:#666; text-decoration:none">JessieManrty@domain.com</a>
<br>
<a href="#" style="color:#666; text-decoration:none">+4(333)123456</a>',
            )
        ),

    );
}
