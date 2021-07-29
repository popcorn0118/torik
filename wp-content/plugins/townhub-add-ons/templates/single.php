<?php
/* add_ons_php */


get_header(  );
?>
<div id="ltop_sec"></div>
<?php
/* Start the Loop */
while ( have_posts() ) : the_post();
	$listing_type_ID = get_post_meta( get_the_ID(), ESB_META_PREFIX.'listing_type_id', true );
	$listing_type_ID = apply_filters( 'wpml_object_id', $listing_type_ID, 'listing_type', true );
   // set view count
    Esb_Class_LStats::set_stats(get_the_ID());
    $lcontent = townhub_addons_azp_parser_listing( $listing_type_ID , 'single', get_the_ID() );
    
    // $lcontent = apply_filters( 'the_content', $lcontent );
    $lcontent = apply_filters( 'azp_single_content', $lcontent );
    echo $lcontent;

endwhile;
// end the loop
?>
<div class="limit-box fl-wrap"></div>
<?php
get_footer(  );