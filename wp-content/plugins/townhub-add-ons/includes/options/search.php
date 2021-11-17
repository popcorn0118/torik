<?php 
/* add_ons_php */

function townhub_addons_options_get_search(){
    return array(
            array(
                "type" => "section",
                'id' => 'search_category_opts',
                "title" => __( 'Category Options', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'search_cat_level',
                "title" => __('Category Level', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> '0',
                    'options'=> array(
                        '0' => esc_html__('1 Level', 'townhub-add-ons'), 
                        '1' => esc_html__('2 Level', 'townhub-add-ons'), 
                        '2' => esc_html__('3 Level', 'townhub-add-ons'), 
                        '3' => esc_html__('4 Level', 'townhub-add-ons'), 
                        '4' => esc_html__('5 Level', 'townhub-add-ons'), 
                    ),
                ),
                'desc' => esc_html__("Max category level display on search form.", 'townhub-add-ons'), 
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'search_load_subcat',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Load Sub-Cat', 'townhub-add-ons'),
                'desc' => esc_html__("Load sub categories for filter.", 'townhub-add-ons'), 

            ),

            

            array(
                "type" => "section",
                'id' => 'search_taxonomy_opts',
                "title" => __( 'Taxonomy Options', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'search_include_cat',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Include Category', 'townhub-add-ons'),
                'desc' => esc_html__("Include listing category for search value", 'townhub-add-ons'), 

            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'search_include_tag',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Include Tag', 'townhub-add-ons'),
                'desc' => esc_html__("Include listing tag for search value", 'townhub-add-ons'), 

            ),

            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'search_tax_relation',
                "title" => __('Taxonomy Relation', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'AND',
                    'options'=> array(
                        'AND' => esc_html__('AND', 'townhub-add-ons'), 
                        'OR' => esc_html__('OR', 'townhub-add-ons'), 
                        
                    ),
                ),
                'desc' => esc_html__("The logical relationship between each inner taxonomy.", 'townhub-add-ons'), 
            ),

            array(
                "type" => "section",
                'id' => 'search_distance_opts',
                "title" => __( 'Distance Options', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "number",
                'id' => 'distance_min',
                "title" => __('Distance Search Min (kilometer)', 'townhub-add-ons'),
                'args' => array(
                    'default'  => '2',
                    'min'  => '0',
                    'max'  => '40000',
                    'step'  => '1',
                ),
                // 'desc'  => __('Timezone offset value from UTC', 'townhub-add-ons'),
            ),
            array(
                "type" => "field",
                "field_type" => "number",
                'id' => 'distance_max',
                "title" => __('Distance Search Max (kilometer)', 'townhub-add-ons'),
                'args' => array(
                    'default'  => '20',
                    'min'  => '1',
                    'max'  => '40000',
                    'step'  => '1',
                ),
                // 'desc'  => __('Timezone offset value from UTC', 'townhub-add-ons'),
            ),
            array(
                "type" => "field",
                "field_type" => "number",
                'id' => 'distance_df',
                "title" => __('Distance Search Default (kilometer)', 'townhub-add-ons'),
                'args' => array(
                    'default'  => '10',
                    'min'  => '1',
                    'max'  => '40000',
                    'step'  => '1',
                ),
                // 'desc'  => __('Timezone offset value from UTC', 'townhub-add-ons'),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox", 
                'id' => 'distance_miles',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Use miles instead', 'townhub-add-ons'),  
                'desc'  => __( 'You also need translate km text to miles', 'townhub-add-ons' ),
            ),



            array(
                "type" => "section",
                'id' => 'search_filter_opts',
                "title" => __( 'Filter Options', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'filter_hide_string',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide Filter String', 'townhub-add-ons'),
                'desc' => '', 

            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'filter_hide_loc',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide Location', 'townhub-add-ons'),
                'desc' => '', 

            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'filter_hide_cat',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide Category', 'townhub-add-ons'),
                'desc' => '', 

            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'filter_hide_address',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide Address', 'townhub-add-ons'),
                'desc' => '', 

            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'filter_hide_event_date',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide Event Date', 'townhub-add-ons'),
                'desc' => '', 

            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'filter_hide_event_time',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide Event Time', 'townhub-add-ons'),
                'desc' => '', 

            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'filter_hide_open_now',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide Open Now', 'townhub-add-ons'),
                'desc' => '', 

            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'filter_hide_price_range',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide Price Range', 'townhub-add-ons'),
                'desc' => '', 

            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'filter_hide_sortby',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => __('Hide Sort By', 'townhub-add-ons'),
                'desc' => '', 

            ),

            array(
                "type" => "field",
                "field_type" => "lfeatures",
                'id' => 'filter_features',
                'args'=> array(
                    'default' => array(),
                    // 'hide_empty'    => true, // default is true
                ),
                "title" => __('Features', 'townhub-add-ons'),
                'desc' => '', 

            ),

            array(
                "type" => "field",
                "field_type" => "cth_tags",
                'id' => 'filter_ltags',
                'args'=> array(
                    'default' => array(),
                    'hide_empty'    => true,
                ),
                "title" => __('Tags Filter', 'townhub-add-ons'),
                'desc' => '', 

            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'hide_cats_tab',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Hide Categories tab', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc' => '', 

            ),

            array(
                "type" => "field",
                "field_type" => "number",
                'id' => 'cats_num',
                "title" => __('Number of categories showing on search results page', 'townhub-add-ons'),
                'args' => array(
                    'default'  => '5',
                    'min'  => '0',
                    'max'  => '100',
                    'step'  => '1',
                ),
                'desc'  => __('Set to 0 to show all', 'townhub-add-ons'),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'use_ltype_search',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => _x('Allow search by listing type?', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc' => _x('For Hero and Header search forms', 'TownHub Add-Ons', 'townhub-add-ons'),
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'use_ltype_filter',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => _x('Allow filter by listing type?', 'TownHub Add-Ons', 'townhub-add-ons'),
                'desc' => _x('For search results form', 'TownHub Add-Ons', 'townhub-add-ons'),
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'fevent_exact',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Filter events by exact date', 'TownHub Add-Ons', 'townhub-add-ons'),
                // 'desc' => _x('For search results form', 'TownHub Add-Ons', 'townhub-add-ons'),
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'fevent_calendar',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Filter event date from calendar also?', 'TownHub Add-Ons', 'townhub-add-ons'),
                // 'desc' => _x('For search results form', 'TownHub Add-Ons', 'townhub-add-ons'),
            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'hide_cat_features',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Do not load features on selecting category?', 'TownHub Add-Ons', 'townhub-add-ons'),
                // 'desc' => _x('For search results form', 'TownHub Add-Ons', 'townhub-add-ons'),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'inout_rooms_only',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => __('Checkin/Checkout search for rooms only?', 'townhub-add-ons'),
                'desc' => esc_html__("By enabling this option, the check-in/checkout search will only show listings with rooms", 'townhub-add-ons'), 

            ),
    );
}
