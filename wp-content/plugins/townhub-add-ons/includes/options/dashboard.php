<?php 
/* add_ons_php */

function townhub_addons_options_get_dashboard(){
    return array(
            array(
                "type" => "section",
                'id' => 'membership_usermenu',
                "title" => _x( 'User Menu', 'TownHub Add-Ons', 'townhub-add-ons' ),
            ),


            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'umenu_earnings',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Hide Earning', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "section",
                'id' => 'membership_package',
                "title" => __( 'Dashboard', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "image",
                'id' => 'dbheader_image',
                "title" => _x('Header Background Image', 'TownHub Add-Ons', 'townhub-add-ons'),
                // 'args'=> array(
                //     'default'=> array(
                //         'url' => ESB_DIR_URL ."assets/images/marker.png"
                //     )
                // ),
                
                'desc'  => ''
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'dbheader_hide_circle',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Hide Header Circles', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "page_select",
                'id' => 'packages_page',
                "title" => __('Membership Packages Page', 'townhub-add-ons'),
                'desc'  => __('The packages page current user can see their current plan details or update plan.', 'townhub-add-ons'),
                'args' => array(
                    'default'   => 'default',
                    'default_title' => "Pricing Tables",
                    
                )
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'show_stats',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Show author statistics', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'db_hide_lviews',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Hide Listing views stats', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'db_hide_lreviews',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Hide Listing reviews stats', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'db_hide_lbookings',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Hide Bookings stats', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'show_chart',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Show author chart', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'chart_hide_views',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide chart Views', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'chart_hide_booking',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Hide chart Bookings', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'chart_hide_earning',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide chart Earnings', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'separate_inquiries',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Separate bookings and inquiries', 'Options', 'townhub-add-ons'),
                'desc'  => '',
            ),
            
            array(
                "type" => "section",
                'id' => 'membership_dashboard',
                "title" => __( 'Dashboard Menu', 'townhub-add-ons' ),
            ),


            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'db_hide_messages',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide Messages', 'townhub-add-ons'),
                'desc'  => '',
            ),
            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'db_hide_products',
            //     'args'=> array(
            //         'default' => 'no',
            //         'value' => 'yes',
            //     ),
            //     "title" => _x('Hide WooCommerce Products', 'TownHub Add-Ons', 'townhub-add-ons'),
            //     'desc'  => '',
            // ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'db_show_dokan',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Show menu to Dokan daskboard', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'db_show_dokan_products',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Show menu to Dokan products', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '',
            ),
            

            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'db_hide_packages',
            //     'args'=> array(
            //         'default' => 'no',
            //         'value' => 'yes',
            //     ),
            //     "title" => __('Hide Packages', 'townhub-add-ons'),
            //     'desc'  => '',
            // ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'db_hide_ads',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide AD Campaigns', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'db_hide_invoices',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide Invoices', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'db_hide_bookings',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide Bookings', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'db_show_woo_orders',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Show WooCommerce Orders?', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => _x('Enable this to show orders for author products. Customer can only buy products from on author per order.', 'TownHub Add-Ons', 'townhub-add-ons'),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'db_hide_bookmarks',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide Bookmarks', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'db_hide_reviews',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide Reviews', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'db_hide_adnew',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide Add New', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'db_hide_withdrawals',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide Withdrawals', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'db_hide_ical',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Hide iCal Sync', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'db_show_inquiries',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Show Inquiries menu', 'Options', 'townhub-add-ons'),
                'desc'  => '',
            ),

            
            array(
                "type" => "section",
                'id' => 'dashboard_listing',
                "title" => __( 'Dashboard Listing', 'townhub-add-ons' ),
            ),
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'listings_per',
                "title" => __('Listings per page', 'townhub-add-ons'),
                'desc'  => __( 'Number of listings to show on a page (-1 for all)', 'townhub-add-ons' ),
                'args' => array(
                    'default' => '5',
                )
            ),
            

            array(
                "type" => "section",
                'id' => 'dashboard_withdrawal',
                "title" => __( 'Withdrawals', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "number",
                'id' => 'withdrawal_min',
                "title" => __('Minimum withdrawal amount', 'townhub-add-ons'),
                'args' => array(
                    'default'  => '10',
                    'min'  => '1',
                    // 'max'  => '200',
                    'step'  => '1',
                ),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "number",
                'id' => 'withdrawal_date',
                "title" => _x('Withdrawal proccess date','Options', 'townhub-add-ons'),
                'args' => array(
                    'default'  => '15',
                    'min'  => '1',
                    'max'  => '31',
                    'step'  => '1',
                ),
                'desc'  => '',
            ),
    );
}
