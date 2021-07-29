<?php 
/* add_ons_php */

function townhub_addons_options_get_gmap(){
    return array(
            array(
                "type" => "section",
                'id' => 'map_apis',
                "title" => __( 'Map Provider API', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'map_provider',
                "title" => __('Map provider', 'townhub-add-ons'),
                'args'=> array(
                    'options'=> array(
                        'osm'       => _x( 'OpenStreetMap', 'Map provider', 'townhub-add-ons' ),
                        'mapbox'       => _x( 'Mapbox', 'Map provider', 'townhub-add-ons' ),
                        'googlemap'       => _x( 'Google Map', 'Map provider', 'townhub-add-ons' ),
                    )
                )
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'mapbox_token',
                "title" => __('Mapbox Token', 'townhub-add-ons'),
                'desc'  => sprintf( __( 'Enter your Mapbox token.<br><a href="%s" target="_blank">Get your token</a>', 'townhub-add-ons' ), esc_url('https://docs.mapbox.com/help/how-mapbox-works/access-tokens' ) ),
            ),

            

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'gmap_api_key',
                "title" => __('Google Map API Key', 'townhub-add-ons'),
                'desc'  => sprintf( __( 'You have to enter your API key to use google map feature. Required services: <b>Google Places API Web Service</b>, <b>Google Maps JavaScript API</b>, <b>Google Maps Geocoding API</b> and <b>Street View Static API</b> for street view.<br><a href="%s" target="_blank">Get Your Key</a>', 'townhub-add-ons' ), esc_url('https://developers.google.com/maps/documentation/javascript/get-api-key' ) ),
            ),

            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'gmap_type',
                "title" => __('Google Map Type', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'ROADMAP',
                    'options'=> array(
                        "ROADMAP" => __('ROADMAP - default 2D map','townhub-add-ons'), 
                        "SATELLITE" => __('SATELLITE - photographic map','townhub-add-ons'), 
                        "HYBRID" => __('HYBRID - photographic map + roads and city names','townhub-add-ons'), 
                        "TERRAIN" => __('TERRAIN - map with mountains, rivers, etc','townhub-add-ons'), 
                        
                    ),
                )
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'google_map_language',
                "title" => __('Google Map Language Code', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> '',
                ),
                'desc'  => sprintf( __( 'Leave this empty for user location or browser settings value. Available value at <a href="%s" target="_blank">Google Supported Languages</a>', 'townhub-add-ons' ), 'https://developers.google.com/maps/faq#languagesupport'),
            ),

            


            array(
                "type" => "section",
                'id' => 'gmap_section_geolocation',
                "title" => __( 'Place Autocomplete', 'townhub-add-ons' ),
            ),
            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'autocomplete_result_type',
                "title" => __('Autocomplete Result Type', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'none',
                    'options'=> array(
                        "none" => _x('All types','TownHub Add-Ons','townhub-add-ons'), 
                        "geocode" => _x('Geocode - return only geocoding results, rather than business results','TownHub Add-Ons','townhub-add-ons'), 
                        "address" => _x('Address - looking for a fully specified address','TownHub Add-Ons','townhub-add-ons'), 
                        "establishment" => _x('Establishment - return only business results','TownHub Add-Ons','townhub-add-ons'), 
                        
                    ),
                )
            ),
            // https://developers.google.com/places/web-service/supported_types#table2
            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'listing_location_result_type',
                "title" => __('Listing Location Type', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'administrative_area_level_1',
                    'options'=> array(
                        "none" => _x('All types','TownHub Add-Ons','townhub-add-ons'), 
                        "locality" => _x('Locality','TownHub Add-Ons','townhub-add-ons'), 
                        "sublocality" => _x('Sublocality','TownHub Add-Ons','townhub-add-ons'), 
                        "postal_code" => _x('Postal Code','TownHub Add-Ons','townhub-add-ons'), 
                        "country" => _x('Country','TownHub Add-Ons','townhub-add-ons'), 
                        "administrative_area_level_1" => _x('City','TownHub Add-Ons','townhub-add-ons'), 
                        "administrative_area_level_2" => _x('District','TownHub Add-Ons','townhub-add-ons'),
                    ),
                )
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'listing_address_format',
                "title" => __('Or Define Your Address Format', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'formatted_address',
                ),
                'desc'  => sprintf( __( 'Define address format will received when user using google autocomplete place service. Address types separated by comma. Available types at <a href="%s" target="_blank">Google Address Types</a>', 'townhub-add-ons' ), 'https://developers.google.com/maps/documentation/geocoding/intro#Types'). '<br>'.__( 'Using <strong>formatted_address</strong> for Google formated address.', 'townhub-add-ons' ),
            ),



            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'country_restrictions',
                "title" => __('Restriction Countries', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> '',
                    'options'=> townhub_addons_get_google_contry_codes(),
                    'multiple' => true,
                    'use-select2' => true
                ),
                'desc' => __( 'Type to search. Restrict the search to a specific countries. Leave empty for all. ', 'townhub-add-ons' )
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'use_autolocate',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Auto locate user location?', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "section",
                'id' => 'gmap_section_listings',
                "title" => __( 'Listings Map', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'gmap_default_lat',
                'args'=> array(
                    'default'=> '40.7',
                ),
                "title" => __('Default Latitude', 'townhub-add-ons'),
                'desc'  => sprintf( __( 'Enter your address latitude - default: 40.7. You can get value from this <a href="%s" target="_blank">website</a>', 'townhub-add-ons' ), esc_url('http://www.gps-coordinates.net/' ) ),
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'gmap_default_long',
                'args'=> array(
                    'default'=> '-73.87',
                ),
                "title" => __('Default Longtitude', 'townhub-add-ons'),
                'desc'  => sprintf( __( 'Enter your address longtitude - default: -73.87. You can get value from this <a href="%s" target="_blank">website</a>', 'townhub-add-ons' ), esc_url('http://www.gps-coordinates.net/' ) ),
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'gmap_default_zoom',
                'args'=> array(
                    'default'=> '10',
                ),
                "title" => __('Default Zoom', 'townhub-add-ons'),
                'desc'  => __('Default map zoom level, max: 18', 'townhub-add-ons'),
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'gmap_single_zoom',
                'args'=> array(
                    'default'=> '16',
                ),
                "title" => __('Single Map Zoom', 'townhub-add-ons'),
                'desc'  => __('Default map zoom level for single page, max: 18', 'townhub-add-ons'),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'use_dfmarker',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Disable Featured marker', 'townhub-add-ons'),
                'desc'  => __('Use bellow marker instead of listing Featured image marker. Can be configured based on category.', 'townhub-add-ons'),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'use_logomk',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Use Logo marker instead of Featured image?', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => _x('Disable Featured marker option above must be unchecked', 'TownHub Add-Ons', 'townhub-add-ons'),
            ),

            

            array(
                "type" => "field",
                "field_type" => "image",
                'id' => 'gmap_marker',
                "title" => __('Map Marker', 'townhub-add-ons'),
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
                'id' => 'hide_mkprice',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Hide price on marker?', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc'  => '',
            ),

            array(
                "type" => "section",
                'id' => 'map_card_opts',
                "title" => __( 'Map Card Options', 'townhub-add-ons' ),
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'map_card_hide_status',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide Open/Closed status', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'map_card_hide_author',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Hide Author', 'townhub-add-ons'),
                'desc'  => '',
            ),
            // get map data on marker
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'unfill_address',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Drag map marker does not fill address', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'unfill_state',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Drag map marker does not fill state', 'townhub-add-ons'),
                'desc'  => '',
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'unfill_city',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Drag map marker does not fill city', 'townhub-add-ons'),
                'desc'  => '',
            ),

    );
}
