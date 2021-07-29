<?php 
/* add_ons_php */

function townhub_addons_options_get_advanced(){
    return array(
        array(
            "type" => "section",
            'id' => 'advanced_sec_1',
            "title" => __( 'AZP Builder', 'townhub-add-ons' ),     
        ),
        array(
            "type" => "field",
            "field_type" => "checkbox", 
            'id' => 'azp_cache',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => __('Enable AZP builder cache', 'townhub-add-ons'),   
            'desc'  => '',
        ),

        array(
            "type" => "field",
            "field_type" => "checkbox", 
            'id' => 'azp_css_external',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => __('AZP External CSS', 'townhub-add-ons'),  
            'desc'  => __( 'Builder style will be loaded from external css file instead of adding inline to page.', 'townhub-add-ons' ),
        ),

        array(
            "type" => "field",
            "field_type" => "checkbox", 
            'id' => 'lazy_load',
            'args'=> array(
                'default' => 'yes',
                'value' => 'yes',
            ),
            "title" => __('Enable Lazy Load Images', 'townhub-add-ons'),   
            'desc'  => __( 'For page speed improvement.', 'townhub-add-ons' ),
        ),
        array(
            "type" => "field",
            "field_type" => "image",
            'id' => 'lazy_placeholder',
            "title" => __('Lazy Placeholder', 'townhub-add-ons'),
            'desc'  => __( 'Select placeholder image for lazy load. Leave empty for hide images before load (recommended)', 'townhub-add-ons' ),
        ),

        array(
            "type" => "field",
            "field_type" => "select",
            'id' => 'cookie_provider',
            "title" => _x('GDPR Cookie Plugin', 'TownHub Add-Ons', 'townhub-add-ons'),
            'args'=> array(
                'default'       => 'none',
                'options'       => array(
                    'none'                  => _x('None', 'TownHub Add-Ons', 'townhub-add-ons'),
                    'cookie-notice'         => _x('Cookie Notice for GDPR & CCPA', 'TownHub Add-Ons', 'townhub-add-ons'),
                    // 'cookie-law-info'         => _x('GDPR Cookie Consent', 'TownHub Add-Ons', 'townhub-add-ons'),
                ),
                
            ),
            // 'desc' => __( 'Default country for listing location.', 'townhub-add-ons' )
        ),

        array(
            "type" => "section",
            'id' => 'advanced_posttypes',
            "title" => _x( 'Publish Post Types', 'TownHub Add-Ons', 'townhub-add-ons' ),     
        ),
        array(
            "type" => "field",
            "field_type" => "checkbox", 
            'id' => 'pt_public_plan',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => _x( 'Author Plan posts', 'TownHub Add-Ons', 'townhub-add-ons' ),     
            'desc'  => '',
        ),
        array(
            "type" => "field",
            "field_type" => "checkbox", 
            'id' => 'pt_public_booking',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => _x( 'Booking posts', 'TownHub Add-Ons', 'townhub-add-ons' ),     
            'desc'  => '',
        ),
        
        array(
            "type" => "field",
            "field_type" => "checkbox", 
            'id' => 'pt_public_withdrawal',
            'args'=> array(
                'default' => 'no',
                'value' => 'yes',
            ),
            "title" => _x( 'Withdrawal posts', 'TownHub Add-Ons', 'townhub-add-ons' ),     
            'desc'  => '',
        ),
    );
}
