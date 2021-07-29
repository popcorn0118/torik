<?php
/* add_ons_php */
// global $wp_query;
// echo '<pre>';
// var_dump($wp_query);
// global $query_string;
// echo '<pre>';
// var_dump($query_string);
// wp_parse_str( $query_string, $search_query );
// // $search = new WP_Query( $search_query );

// var_dump($search_query);

do_action( 'townhub_addons_listings_loop_before'); 

?>
<div class="listing-item-container init-grid-items fl-wrap" id="lisconfw">
<div id="listing-items" class="listing-items listing-items-wrapper">
<?php
$action_args = array(
    'listings' => array()
);
// https://codex.wordpress.org/Function_Reference/do_action_ref_array
do_action_ref_array( 'townhub_addons_listing_loop_before', array(&$action_args) );

if ( have_posts() ) :
    /* Start the Loop */
    while ( have_posts() ) : the_post();
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
    // echo $wp_query->found_posts;
	townhub_addons_ajax_pagination();
	// townhub_addons_pagination();
	// echo '<div><a class="load-more-button" href="#">Load more <i class="fa fa-circle-o-notch"></i> </a></div>';
	?>
</div>
</div>
<?php
// end if has_posts
// wp_localize_script( 'townhub-addons', '_townhub_add_ons_locs', $action_args['gmap_listings']);
        
do_action( 'townhub_addons_listings_loop_after'); 
