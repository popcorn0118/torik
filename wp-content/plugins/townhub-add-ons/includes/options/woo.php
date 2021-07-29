<?php 
/* add_ons_php */

function townhub_addons_options_get_woo(){
    return array(
            array(
                "type" => "section",
                'id' => 'woo_sec_ads',
                "title" => __( 'For Listing AD', 'townhub-add-ons' ),     
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox", 
                'id' => 'woo_for_ads',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Enable for purchase AD', 'townhub-add-ons'),   
                'desc'  => __('Author will be redirected to WooCommerce to complete AD payment', 'townhub-add-ons'),   
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'woo_multiple_author',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Allow booking from multiple author?', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '',
            ),

    );
}
