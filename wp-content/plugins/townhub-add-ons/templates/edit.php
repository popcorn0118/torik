<?php
/* add_ons_php */

$listing_id = get_query_var('listing_id'); 


$listing_post_obj = get_post($listing_id);

if(null == $listing_post_obj) return;

$cur_cats = array();
$cats = get_the_terms( $listing_id , 'listing_cat' );    
// var_dump($cats);
if ( $cats && ! is_wp_error( $cats ) ){
    foreach ( $cats as $cat ) {
        $cur_cats[] = $cat->term_id;
    }
}
// features
$cur_feas = array();
$feas = get_the_terms( $listing_id , 'listing_feature' );    
// var_dump($cats);
if ( $feas && ! is_wp_error( $feas ) ){
    foreach ( $feas as $fea ) {
        $cur_feas[] = $fea->term_id;
    }
}                
//additional features
$add_feas = get_post_meta( $listing_id, ESB_META_PREFIX.'add-features', true );


wp_localize_script( 'townhub-addons', '_townhub_submit', array(
    'id'        =>  $listing_id,
    'mode'      => 'edit',
    'feas'      => $cur_feas,
    'addfeas'   => $add_feas,

) );



$listing_cats_arr = townhub_addons_get_listing_categories(); 

$get_tags = get_the_tags($listing_id);
$listing_tags = '';
if ( $get_tags && ! is_wp_error( $get_tags ) ){
    foreach ($get_tags as $tag) {
        $listing_tags .= $tag->name.',';
    }
    
}

// get listing location
// features
$cur_locs = '';
$locs = get_the_terms( $listing_id , 'listing_location' );    
// var_dump($cats);
if ( $locs && ! is_wp_error( $locs ) ){
    foreach ( $locs as $loc ) {
        $cur_locs .= $loc->name.',';
    }
}  

?>
<?php
townhub_addons_get_template_part('template-parts/dashboard/headsec');
?>
<!--  section  -->
<section class="gray-bg main-dashboard-sec" id="sec1">
    <div class="container">
        <!--  dashboard-menu-->
        <div class="col-md-3 dashboard-sidebar-col">
            
            <?php townhub_addons_get_template_part('template-parts/dashboard/sidebar', '', array('is_edit_page'=>true));?>

        </div>
        <!-- dashboard-menu  end-->
        <!-- dashboard content-->
        <div class="col-md-9 dashboard-main-col">
            
            <div id="submit-listing-view" class="submit-listing-view">
            <!-- #submit-listing-view -->
                <div id="submit-listing-message"></div>
                
                <div id="ledit-app" class="submit-fields-dflex"></div>
            </div>
            <!-- #submit-listing-view -->
        </div>
        <!-- dashboard content end-->
    </div>
</section>
<!--  section  end-->
<div class="limit-box fl-wrap"></div>
<?php if( townhub_addons_get_option('subm_subtitle') == 'yes' ): ?>
<script type="text/template" id="tmpl-submit-sub-heading">
    <h4><?php _ex( 'This is sub heading for edit listing page', 'Submit listing', 'townhub-add-ons' );?></h4>
</script>
<?php endif; ?>
