<?php 
/* add_ons_php */

function townhub_addons_options_get_membership(){
    return array(
            array(
                "type" => "section",
                'id' => 'membership_general_sec',
                "title" => __( 'General', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'author_role',
                "title" => _x('Listing author role','TownHub Add-Ons', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'listing_author',
                    'options'=> townhub_addons_get_author_roles(),
                    'multiple' => false,
                    'use-select2' => false
                ),
                'desc' => _x("The default is <strong>Listing Author</strong>. If you want to use Dokan marketplace plugin and authors can manage Woo products from frontend dashboard, select <strong>Vendor</strong>",'TownHub Add-Ons', 'townhub-add-ons'), 
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'always_show_submit',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Show submit button', 'townhub-add-ons'),
                'desc'  => __('By default <strong>Add Listing</strong> button show with listing author (who have an active membership subscription) only, or with <strong>Anyone can submit listing</strong> option bellow checked. Check this for always showing.', 'townhub-add-ons'),
            ),


            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'users_can_submit_listing',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Membership', 'townhub-add-ons'),
                'desc'  => __(' Anyone can submit listing', 'townhub-add-ons'),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'auto_active_free_sub',
                "title" => __('Auto Active Free Subscription', 'townhub-add-ons'),
                'desc'  => __( 'Active free subscription automatically, so user can submit listings then.', 'townhub-add-ons' ),
                'args' => array(
                    'default' => 'no',
                    'value' => 'yes',
                )
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'free_redirect_submit',
                "title" => _x( 'Redirect free membership subscription to submit page', 'TownHub Add-Ons', 'townhub-add-ons' ),
                'desc'  => _x( 'Auto Active Free Subscription option above is required', 'TownHub Add-Ons', 'townhub-add-ons' ),
                'args' => array(
                    'default' => 'no',
                    'value' => 'yes',
                )
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'listing_expire_days',
                "title" => __('Free Listing Expiration Day', 'townhub-add-ons'),
                'desc'  => __( 'Number of days a free listing will be expired.', 'townhub-add-ons' ),
                'args' => array(
                    'default' => '30',
                )
            ),


            array(
                "type" => "field",
                "field_type" => "page_select",
                'id' => 'free_submit_page',
                "title" => __('Free User Submit Page', 'townhub-add-ons'),
                'desc'  => __('The page free user will be redirected to when click to Add Listing and Add New button. <strong>Default Behavior</strong> for access submit page directly.', 'townhub-add-ons'),
                'args' => array(
                    'default'   => 'default',
                    'default_title' => "Pricing Tables",
                    'options' => array(
                        array(
                            'default',
                            __( 'Default Behavior', 'townhub-add-ons' ),
                        ),
                    )
                )
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox", 
                'id' => 'free_plan_invoice',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Create invoice for free plan', 'townhub-add-ons'),   
                'desc'  => '',
            ),

            array(
                "type" => "section",
                'id' => 'membership_defaults_sec',
                "title" => __( 'Default Plans', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'admin_lplan',
                "title" => __('Admin Plan', 'townhub-add-ons'),
                'args'=> array(
                    'options'=> townhub_addons_get_listing_plans(),
                )
            ),
            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'free_lplan',
                "title" => __('Free Plan', 'townhub-add-ons'),
                'args'=> array(
                    'options'=> townhub_addons_get_listing_plans(),
                )
            ),



            array(
                "type" => "section",
                'id' => 'membership_expired_sec',
                "title" => __( 'Subscription Expired Action', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'membership_package_expired_hide',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide Listings', 'townhub-add-ons'),
                'desc'  => __('Listing will go to pending status when it expired.', 'townhub-add-ons'),
            ),

            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'membership_single_expired_hide',
            //     'args'=> array(
            //         'default' => 'no',
            //         'value' => 'yes',
            //     ),
            //     "title" => __('Hide Listing', 'townhub-add-ons'),
            //     'desc'  => __('Hide listing assigned to the single package when it expired.', 'townhub-add-ons'),
            // ),


            array(
                "type" => "section",
                'id' => 'listings_sec_submit',
                "title" => __( 'Publish Listing', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'auto_publish_paid_listings',
                "title" => __('Publish Listing after paid', 'townhub-add-ons'),
                'desc'  => '',
                'args' => array(
                    'value' => 'yes',
                    'default' => 'no',
                )
            ),
              //=========
            
            
            
            
            

    );
}
