<?php
/* add_ons_php */
if(!isset($post_args)) $post_args = array(
    'post_type' => 'listing',
    'paged' => 1,
    'posts_per_page'=> townhub_addons_get_option('listings_count'),
    'orderby'=> townhub_addons_get_option('listings_orderby'),
    'order'=> townhub_addons_get_option('listings_order'),
    'post_status' => 'publish'
);
$past_events_query = array();
if( townhub_addons_get_option('hide_past_events') == 'yes' && ( !isset($_GET['past_events']) || $_GET['past_events'] != 'show' ) ){

    $past_events_query =   array(
                            'relation'      => 'OR',
                            array(
                                'key'       => ESB_META_PREFIX.'eventdate_end',
                                'value'     => 'none',
                                'compare'   => '=',
                            ),
                            array(
                                'key'       => ESB_META_PREFIX.'eventdate_end',
                                'value'     => current_time('Y-m-d', 1),
                                'compare'   => '>=',
                                'type'      => 'DATE',
                            ),
                        );
}
if(townhub_addons_get_option('listings_orderby') == 'event_start_date'){
    $post_args['meta_key'] = ESB_META_PREFIX.'eventdate_start';
    $post_args['meta_type'] = 'DATE';
    $post_args['orderby'] = 'meta_value';
}
if( !empty($past_events_query) ){
    if( !empty($post_args['meta_query']) && is_array($post_args['meta_query']) ){
        $post_args['meta_query'][] = $past_events_query;
    }else{
        $post_args['meta_query'] = array($past_events_query);
    }
}
$post_args = apply_filters( 'townhub_addons_custom_loop_args', $post_args );
?>
<div class="listing-term-desc"></div>
<div class="listing-item-container init-grid-items fl-wrap" id="lisconfw">
    <div id="listing-items" class="listing-items listing-items-wrapper">
    <?php
    $action_args = array(
        'listings' => array()
    );
    // https://codex.wordpress.org/Function_Reference/do_action_ref_array
    do_action_ref_array( 'townhub_addons_listing_loop_before', array(&$action_args) );
    $posts_query = new \WP_Query($post_args);
    if($posts_query->have_posts()) :
        /* Start the Loop */
        while($posts_query->have_posts()) : $posts_query->the_post(); 
            townhub_addons_get_template_part('template-parts/listing');
            $action_args['listings'][] = get_the_ID();
        endwhile;

    elseif(empty($action_args['listings'])):

        townhub_addons_get_template_part('template-parts/search-no');

    endif;
        ?>
    </div>
    <div class="listings-pagination-wrap">
        <?php
        townhub_addons_ajax_pagination($posts_query->max_num_pages,$range = 2, $posts_query);
        ?>
    </div>
</div><!-- end listing-item-container -->

