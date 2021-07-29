<?php
/* add_ons_php */



function townhub_addons_display_listing_sidebar_ads(){

    if(townhub_addons_get_option('ads_sidebar_enable') != 'yes') return;
    // http://prntscr.com/mmjaue
    // if(get_post_meta( get_the_ID(), ESB_META_PREFIX.'plan_id', true ) == '456') return;

    $args = array(
        'post_type'             =>  'listing', 
        'orderby'               => townhub_addons_get_option('ads_sidebar_orderby'),
        'order'                 => townhub_addons_get_option('ads_sidebar_order'),
        'posts_per_page'        => townhub_addons_get_option('ads_sidebar_count'),
        'post__not_in'          => array(get_the_ID()),
        'meta_query'            => array(
            'relation' => 'AND',
            array(
                'key'     => ESB_META_PREFIX.'is_ad',
                'value'   => 'yes',
            ),
            // array(
            //     'key'     => ESB_META_PREFIX.'ad_position',
            //     'value'   => 'sidebar',
            // ),
            array(
                    'key'     => ESB_META_PREFIX.'ad_position_sidebar',
                    'value'   => '1',
                    // 'value'   => array('yes','1'),
                    // 'compare' => 'IN',
            ),
            array(
                'key'     => ESB_META_PREFIX.'ad_expire',
                'value'   => current_time('mysql', 1),
                'compare' => '>=',
                'type'    => 'DATETIME',
            ),
        ),

    );

    // The Query
    $posts_query = new WP_Query( $args );
    if($posts_query->have_posts()) :

    ?>
    <!--box-widget-item -->
    <div class="box-widget-item fl-wrap ads-widget">
        <div class="box-widget-item-header">
            <h3><?php esc_html_e( 'ADs : ', 'townhub-add-ons' );?></h3>
        </div>
        
        <div class="sidebar-ad-widget">
            
                <div class="sidebar-ad-carousel fl-wrap">
                <?php 
                while($posts_query->have_posts()) : $posts_query->the_post();
                    ?>
                    <!--slick-slide-item-->
                    <?php townhub_addons_get_template_part('template-parts/listing', false, array( 'for_slider'=>true,'is_ad'=>true ));?>
                    <!--slick-slide-item-->
                    <?php
                endwhile;
                ?>
                </div>
           
        </div>
    </div>
    <!--box-widget-item end -->
    <?php
    wp_reset_postdata();
    endif;
}
// add_action( 'townhub_addons_listing_widgets_before', 'townhub_addons_display_listing_sidebar_ads' );

function townhub_addons_listing_loop_before_ads(&$action_args){

    if(!empty($GLOBALS['main_ads'])){
        // var_dump($GLOBALS['main_ads']);
        // The Query
        $posts_query = new WP_Query( 
            array(
                'post_type'         => 'listing', 
                'post__in'          => $GLOBALS['main_ads'], 
                'posts_per_page'    => -1,
                'orderby'           => 'post__in',
                // for ads distance
                'suppress_filters'     => false,
                'cthqueryid'           => 'nearby-ads',

            ) 
        );
        
        if($posts_query->have_posts()) :
            while($posts_query->have_posts()) : $posts_query->the_post();
                townhub_addons_get_template_part('template-parts/listing', false, array('is_ad'=>true));
                $action_args['listings'][] = get_the_ID(); // for count listing post only -> not display no listing on ads
            endwhile;
        endif;

        wp_reset_postdata();
    }  
}
add_action( 'townhub_addons_listing_loop_before', 'townhub_addons_listing_loop_before_ads' );

function townhub_addons_elementor_listings_grid_before_ads(&$action_args){
    if(townhub_addons_get_option('ads_custom_grid_enable') != 'yes') return;

    $posts_args = array(
        'post_type'             => 'listing', 
        'orderby'               => townhub_addons_get_option('ads_custom_grid_orderby'),
        'order'                 => townhub_addons_get_option('ads_custom_grid_order'),
        'posts_per_page'        => townhub_addons_get_option('ads_custom_grid_count'),
        // 'post__not_in'          => array(get_the_ID()),

        'meta_query'            => array(
            'relation' => 'AND',
            array(
                'key'     => ESB_META_PREFIX.'is_ad',
                'value'   => 'yes',
            ),
            array(
                'key'     => ESB_META_PREFIX.'ad_position_custom_grid',
                'value'   => '1',
                // 'value'   => array('yes','1'),
                // 'compare' => 'IN',
            ),
            array(
                'key'     => ESB_META_PREFIX.'ad_expire',
                'value'   => current_time('mysql', 1),
                'compare' => '>=',
                'type'    => 'DATETIME',
            ),
        ),

    );

    // The Query
    $posts_query = new WP_Query( $posts_args );
    
    if($posts_query->have_posts()) :
        while($posts_query->have_posts()) : $posts_query->the_post();
            townhub_addons_get_template_part( 'template-parts/listing', false, array( 'for_grid' => true, 'is_ad'=>true ) );
            $action_args['listings'][] = get_the_ID(); // for count listing post only -> not display no listing on ads
        endwhile;
    endif;

    wp_reset_postdata();
        
}
// add_action( 'townhub_addons_elementor_listings_grid_before', 'townhub_addons_elementor_listings_grid_before_ads' );

