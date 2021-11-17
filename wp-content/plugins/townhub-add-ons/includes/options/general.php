<?php 
/* add_ons_php */

function townhub_addons_options_get_general(){
    return array(
            array(
                "type" => "section",
                'id' => 'general_design_opts',
                "title" => __( 'General Options', 'townhub-add-ons' ),
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'disable_bubble',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Disable Bubble Animation', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'week_starts_monday',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => _x('Weeks starts on Monday?', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '',
            ),

            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'use_clock_24h',
            //     'args'=> array(
            //         'default' => 'yes',
            //         'value' => 'yes',
            //     ),
            //     "title" => __('Use 24-hour format', 'townhub-add-ons'),
            //     'desc'  => '',
            // ),
            
            
            
            array(
                "type" => "section",
                'id' => 'general_section_5',
                "title" => __( 'Currency Options', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'currency',
                "title" => __('Currency', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'USD',
                    'options'=> townhub_addons_get_currency_array(),
                    'class'     => 'base_currency_select'
                )
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'currency_symbol',
                'args' => array(
                    'default'  => '$',
                ),
                "title" => __('Symbol', 'townhub-add-ons'),
                // 'desc'  => __('General', 'townhub-add-ons'),
            ),


            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'currency_pos',
                "title" => __('Currency position', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'left_space',
                    'options'=> array(
                        'left' => __( 'Left', 'townhub-add-ons' ),
                        'left_space' => __( 'Left with space', 'townhub-add-ons' ),
                        'right' => __( 'Right', 'townhub-add-ons' ),
                        'right_space' => __( 'Right with space', 'townhub-add-ons' ),
                    ),
                )
            ),
            

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'thousand_sep',
                'args' => array(
                    'default'  => ',',
                ),
                "title" => __('Thousand separator', 'townhub-add-ons'),
                // 'desc'  => __('General', 'townhub-add-ons'),
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'decimal_sep',
                'args' => array(
                    'default'  => '.',
                ),
                "title" => __('Decimal separator', 'townhub-add-ons'),
                // 'desc'  => __('General', 'townhub-add-ons'),
            ),

            array(
                "type" => "field",
                "field_type" => "number",
                'id' => 'decimals',
                "title" => __('Number of decimals', 'townhub-add-ons'),
                'args' => array(
                    'default'  => '2',
                    'min'  => '0',
                    'max'  => '14',
                    'step'  => '1',
                ),
                // 'desc'  => __('Timezone offset value from UTC', 'townhub-add-ons'),
            ),

            
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'curr_convert_api',
                'args' => array(
                    'default'  => '39dd0de7891d0b93c9d0',
                ),
                "title" => __('currencyconverterapi.com api key', 'townhub-add-ons'),
                'desc'  => __('Enter your api key here then click to Save button at the bottom before using currency converter button bellow', 'townhub-add-ons'),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'curr_convert_free',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Is free currencyconverterapi.com api key?', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "section",
                'id' => 'general_section_51',
                "title" => __( 'Multiple Currencies', 'townhub-add-ons' ),
            ),

            // townhub_addons_get_option('currencies')

            array(
                "type" => "field",
                "field_type" => "currencies",
                'id' => 'currencies',
                'args' => array(
                    'default'  => '',
                    'load_tmpl' => true
                ),
                "title" => __('Currencies', 'townhub-add-ons'),
                'desc'  => __('Available currencies for front-end show.', 'townhub-add-ons'),
            ),

            array(
                "type" => "section",
                'id' => 'general_tax_sec',
                "title" => __( 'Taxes', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'vat_tax',
                "title" => __('VAT Tax', 'townhub-add-ons'),
                'desc'  => __( 'VAT tax percent. Default: 10%', 'townhub-add-ons' ),
                'args' => array(
                    'default' => '10',
                )
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'service_fee',
                "title" => __('Service Fees (percent)', 'townhub-add-ons'),
                'desc'  => '',
                'args' => array(
                    'default' => '5',
                )
            ),

            
            array(
                "type" => "section",
                'id' => 'general_section_6',
                "title" => __( 'Listing Pages - Important', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "page_select",
                'id' => 'submit_page',
                "title" => __('Submit Listing Page', 'townhub-add-ons'),
                'desc'  => __('The page will be used to display listing submission. The page content should contain <b>[listing_submit_page]</b> shortcode', 'townhub-add-ons'),
                'args' => array(
                    'default_title' => "Submit Listing",
                )
            ),

            array(
                "type" => "field",
                "field_type" => "page_select",
                'id' => 'edit_page',
                "title" => __('Edit Listing Page', 'townhub-add-ons'),
                'desc'  => __('The page will be used to edit listing. The page content should contain <b>[listing_edit_page]</b> shortcode', 'townhub-add-ons'),
                'args' => array(
                    'default_title' => "Edit Listing",
                )
            ),

            array(
                "type" => "field",
                "field_type" => "page_select",
                'id' => 'dashboard_page',
                "title" => __('Listing Author Dashboard Page', 'townhub-add-ons'),
                'desc'  => __('The page will be used for listing author dashboard. The page content should contain <b>[listing_dashboard_page]</b> shortcode', 'townhub-add-ons'),
                'args' => array(
                    'default_title' => "Dashboard",
                )
            ),

            // array(
            //     "type" => "field",
            //     "field_type" => "page_select",
            //     'id' => 'payment_page',
            //     "title" => __('Listing Payment Page', 'townhub-add-ons'),
            //     'desc'  => __('The page will be used for listing/booking checkout', 'townhub-add-ons'),
            //     'args' => array(
            //         'default_title' => "Listing Payment",
            //     )
            // ),

            array(
                "type" => "field",
                "field_type" => "page_select",
                'id' => 'checkout_page',
                "title" => __('Listing Checkout Page', 'townhub-add-ons'),
                'desc'  => __('The page will be used for Membership/Listing checkout. The page content should contain <b>[listing_checkout_page]</b> shortcode', 'townhub-add-ons'),
                'args' => array(
                    'default_title' => "Listing Checkout",
                )
            ),

            array(
                "type" => "field",
                "field_type" => "page_select",
                'id' => 'checkout_success_page',
                "title" => __('Free membership success', 'townhub-add-ons'),
                'desc'  => __('The page user will be redirected to when click to free membership plan.', 'townhub-add-ons'),
                'args' => array(
                    'default'   => 'none',
                    'default_title' => "Checkout Success",
                    'options' => array(
                        array(
                            'none',
                            __( 'Front Page', 'townhub-add-ons' ),
                        ),
                    )
                )
            ),

    );
}
