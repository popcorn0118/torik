<?php 
/* add_ons_php */

function townhub_addons_options_get_listings(){
    return array(

            array(
                "type" => "section",
                'id' => 'listings_archive_sec',
                "title" => __( 'Archive Layout', 'townhub-add-ons' ),
                'desc' => __("For listing search, category, location, feature pages.", 'townhub-add-ons'), 
            ),

            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'llayout',
                "title" => __('Layout', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'column-map',
                    'options'=> array(
                        'column-map' => esc_html__('Column Map', 'townhub-add-ons'), 
                        'column-map-filter' => esc_html__('Column Map/Side-Filter', 'townhub-add-ons'), 
                        'full-map' => esc_html__('Fullwidth Map', 'townhub-add-ons'), 
                        'full-map-filter' => esc_html__('Fullwidth Map/Side-Filter', 'townhub-add-ons'), 
                        'no-map' => esc_html__('Without Map', 'townhub-add-ons'), 
                        'no-map-filter' => esc_html__('Without Map/Side-Filter', 'townhub-add-ons'), 
                    ),
                ),
                'desc' => esc_html__("Select listings page layout", 'townhub-add-ons'), 
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'show_lheader',
                'args'=> array(
                    'default' => 'yes',
                    'value' => 'yes',
                ),
                "title" => esc_html__('Show Header', 'townhub-add-ons'),
                'desc' => __('For <strong>Without Map</strong> layouts only', 'townhub-add-ons'),

            ),
            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'lheader_title',
                "title" => __('Listings head title', 'townhub-add-ons'),
                'desc' => __('For <strong>Without Map</strong> layouts only', 'townhub-add-ons'),
                'args' => array(
                    'default' => 'Our Listings',
                )
            ),
            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'lheader_intro',
                "title" => __('Listings head info', 'townhub-add-ons'),
                'desc' => __('For <strong>Without Map</strong> layouts only', 'townhub-add-ons'),
                'args' => array(
                    'default' => '<h4>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut nec tincidunt arcu, sit amet fermentum sem.</h4>',
                )
            ),
            
            array(
                "type" => "field",
                "field_type" => "image",
                'id' => 'lheader_bg',
                "title" => __('Header Background', 'townhub-add-ons'),
                'desc' => __('For <strong>Without Map</strong> layouts only', 'townhub-add-ons'),
            ),


            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'map_pos',
                "title" => __('Map Position', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'right',
                    'options'=> array(
                        'top' => esc_html__('Top', 'townhub-add-ons'), 
                        'left' => esc_html__('Left', 'townhub-add-ons'), 
                        'right' => esc_html__('Right', 'townhub-add-ons'), 
                        'hide' => esc_html__('Hide', 'townhub-add-ons'), 
                    ),
                ),
                'desc' => esc_html__("Select Google Map position", 'townhub-add-ons'), 
            ),

            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'filter_pos',
                "title" => __('Filter Position', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'top',
                    'options'=> array(
                        'top' => esc_html__('Top', 'townhub-add-ons'), 
                        'left' => esc_html__('Left', 'townhub-add-ons'), 
                        'right' => esc_html__('Right', 'townhub-add-ons'), 
                        'left_col' => esc_html__('Column Left', 'townhub-add-ons'), 
                    ),
                ),
                'desc' => esc_html__("Select Listing Filter position", 'townhub-add-ons'), 
            ),

            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'columns_grid',
                "title" => __('Columns Grid', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'two',
                    'options'=> array(
                        'one' => esc_html__('One Column', 'townhub-add-ons'), 
                        'two' => esc_html__('Two Columns', 'townhub-add-ons'), 
                        'three' => esc_html__('Three Columns', 'townhub-add-ons'), 
                        'four' => esc_html__('Four Columns', 'townhub-add-ons'), 
                        'five' => esc_html__('Five Columns', 'townhub-add-ons'), 
                        'six' => esc_html__('Six Columns', 'townhub-add-ons'), 
                    ),
                ),
                'desc' => '', 
            ),


            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'listings_grid_layout',
                "title" => __('Default Layout', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'grid',
                    'options'=> array(
                        'grid' => esc_html__('Grid View', 'townhub-add-ons'), 
                        'list' => esc_html__('List View', 'townhub-add-ons'), 
                        
                    ),
                ),
                'desc' => '', 
            ),

            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'listings_orderby',
                "title" => __('Order Listings by', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'date',
                    'options'=> townhub_addons_get_post_orderby(),
                ),
                'desc' => '', 
            ),
            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'listings_order',
                "title" => __('Sort Order', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> 'DESC',
                    'options'=> array(
                        'ASC' => __( 'Ascending order - (1, 2, 3; a, b, c)', 'townhub-add-ons' ),
                        'DESC' => __( 'Descending order - (3, 2, 1; c, b, a)', 'townhub-add-ons' ),
                    ),
                ),
                'desc' => '', 
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'listings_count',
                "title" => __('Listings per page', 'townhub-add-ons'),
                'desc'  => __( 'Number of listings to show on a page (-1 for all)', 'townhub-add-ons' ),
                'args' => array(
                    'default' => '6',
                )
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'excerpt_length',
                "title" => __('Excerpt Characters Length', 'townhub-add-ons'),
                'desc'  => '',
                'args' => array(
                    'default' => '55',
                )
            ),

            array(
                "type" => "field",
                "field_type" => "text",
                'id' => 'address_length',
                "title" => __('Address Characters Length', 'townhub-add-ons'),
                'desc'  => '',
                'args' => array(
                    'default' => '45',
                )
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'hide_past_events',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => esc_html__('Hide past event listings', 'townhub-add-ons'),
                'desc' => '', 

            ),

            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'listing_event_date',
            //     'args'=> array(
            //         'default' => 'yes',
            //         'value' => 'yes',
            //     ),
            //     "title" => esc_html__('Show Event Date', 'townhub-add-ons'),
            //     'desc' => '', 

            // ),

            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'grid_wkhour',
            //     'args'=> array(
            //         'default' => 'yes',
            //         'value' => 'yes',
            //     ),
            //     "title" => esc_html__('Show Status', 'townhub-add-ons'),
            //     'desc' => '', 

            // ),

            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'grid_price',
            //     'args'=> array(
            //         'default' => 'yes',
            //         'value' => 'yes',
            //     ),
            //     "title" => esc_html__('Show Price', 'townhub-add-ons'),
            //     'desc' => '', 

            // ),

            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'grid_price_range',
            //     'args'=> array(
            //         'default' => 'yes',
            //         'value' => 'yes',
            //     ),
            //     "title" => esc_html__('Show Price Range', 'townhub-add-ons'),
            //     'desc' => '', 

            // ),

            // array(
            //     "type" => "field",
            //     "field_type" => "checkbox",
            //     'id' => 'grid_viewed_count',
            //     'args'=> array(
            //         'default' => 'yes',
            //         'value' => 'yes',
            //     ),
            //     "title" => esc_html__('Show Viewed Count', 'townhub-add-ons'),
            //     'desc' => '', 

            // ),

            

            

            array(
                "type" => "section",
                'id' => 'listings_search_sec',
                "title" => __( 'Listing Search Page', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'search_infor_before',
                "title" => __('Information Before', 'townhub-add-ons'),
                'desc'  => '',
                'args' => array(
                    'default' => '',
                )
            ),
            array(
                "type" => "field",
                "field_type" => "textarea",
                'id' => 'search_infor_after',
                "title" => __('Information After', 'townhub-add-ons'),
                'desc'  => '',
                'args' => array(
                    'default' => '',
                )
            ),

            array(
                "type" => "section",
                'id' => 'author_page_sidebars',
                "title" => _x( 'Author page', 'TownHub Add-Ons', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'lauthor_archive_for',
                "title" => _x('Use listing author layout for?', 'TownHub Add-Ons', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> array('administrator','listing_author','author'),
                    'options'=> townhub_addons_get_author_roles(true),
                    'multiple' => true,
                    'use-select2' => true
                ),
                // 'desc' => esc_html__("The page redirect to after submit/edit listing", 'townhub-add-ons'), 
            ),

            

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'author_show_posts',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => _x('Show Author Posts', 'TownHub Add-Ons option', 'townhub-add-ons'),
                'desc' => '', 

            ),
            


            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'author_hide_about',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => esc_html_x('Hide About Author widget', 'TownHub Add-Ons option', 'townhub-add-ons'),
                'desc' => '', 

            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'author_hide_contacts',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => esc_html_x('Hide User Contacts widget', 'TownHub Add-Ons option', 'townhub-add-ons'),
                'desc' => '', 

            ),
            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'author_hide_form',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => esc_html_x('Hide Contact Form widget', 'TownHub Add-Ons option', 'townhub-add-ons'),
                'desc' => '', 

            ),

            array(
                "type" => "section",
                'id' => 'ical_sync_sec',
                "title" => _x( 'iCal Synchronization Scheduler', 'TownHub Add-Ons', 'townhub-add-ons' ),
            ),

            array(
                "type" => "field",
                "field_type" => "checkbox",
                'id' => 'ical_sync_enable',
                'args'=> array(
                    'default' => 'no',
                    'value' => 'yes',
                ),
                "title" => esc_html_x('Enable iCal Synchronization', 'TownHub Add-Ons option', 'townhub-add-ons'),
                'desc' => 'You site will run a cron job every interval value bellow to sync dates from external iCal added to listing/rooms. Then mark the dates as unavailable for booking.', 

            ),

            array(
                "type" => "field",
                "field_type" => "select",
                'id' => 'ical_sync_interval',
                "title" => esc_html_x('Interval', 'TownHub Add-Ons option', 'townhub-add-ons'),
                'args'=> array(
                    'default'=> '30',
                    'options'=> array(
                        "5" => _x( '5 minutes', 'TownHub Add-Ons', 'townhub-add-ons' ),
                        "10" => _x( '10 minutes', 'TownHub Add-Ons', 'townhub-add-ons' ),
                        // "15" => _x( '15 minutes', 'TownHub Add-Ons', 'townhub-add-ons' ),
                        "20" => _x( '20 minutes', 'TownHub Add-Ons', 'townhub-add-ons' ),
                        // "25" => _x( '25 minutes', 'TownHub Add-Ons', 'townhub-add-ons' ),
                        "30" => _x( '30 minutes', 'TownHub Add-Ons', 'townhub-add-ons' ),
                        "60" => _x( 'One hour', 'TownHub Add-Ons', 'townhub-add-ons' ),
                        "120" => _x( 'Two hours', 'TownHub Add-Ons', 'townhub-add-ons' ),
                        "720" => _x( 'Twice daily', 'TownHub Add-Ons', 'townhub-add-ons' ),
                        "1440" => _x( 'Daily', 'TownHub Add-Ons', 'townhub-add-ons' ),
                        
                        
                    ),
                ),
                'desc' => '', 
            ),

            array(
                "type" => "field",
                "field_type" => "info",
                'id' => 'ical_sync_infos',
                "title" => esc_html_x('NOTE', 'TownHub Add-Ons option', 'townhub-add-ons'),
                'desc'  => __( 'You need to uncheck the Enable iCal Synchronization option to change Interval value. Save changes then enable it again.', 'townhub-add-ons' ),
            ),

    );
}
