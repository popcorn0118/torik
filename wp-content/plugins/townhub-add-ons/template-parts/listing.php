<?php
/* add_ons_php */

// $is_ad_meta = get_post_meta( get_the_ID(), ESB_META_PREFIX.'is_ad', true);
// $ad_expire_meta = get_post_meta( get_the_ID(), ESB_META_PREFIX.'ad_expire', true);
// $is_ad = false;
// if($is_ad_meta == 'yes' && $ad_expire_meta >= current_time('mysql', 1) ) $is_ad = true; 
if(!isset($is_ad)) $is_ad = false;
$GLOBALS['is_lad'] = $is_ad;
// for default list layout
if(!isset($for_slider)) $for_slider = false;
if(!isset($for_grid)) $for_grid = false;

$map_datas = array();
$cls = 'listing-item listing-item-loop';
if($for_slider) 
    $cls .= ' slick-slide-item';

if($for_grid){
    $pcls = get_post_class( 'cthiso-item listing-item-loop' );
    $cls = implode(" ", $pcls);
}else{
	if(townhub_addons_get_option('listings_grid_layout')=='list') {
	    $cls .= ' list-layout';  
	}
	$map_datas = townhub_addons_get_map_data();
}
	
?>

<!-- listing-item -->
<div class="<?php echo esc_attr( $cls ); ?>" <?php // post_class($cls); ?> data-postid="<?php echo get_the_ID(); ?>"<?php if(!empty($map_datas)) echo " data-lmap=\"".rawurlencode(json_encode($map_datas))."\""; ?>>
    <article class="geodir-category-listing fl-wrap">
        <?php 
            echo townhub_addons_azp_parser_listing( get_post_meta( get_the_ID(), ESB_META_PREFIX.'listing_type_id', true ) , 'preview' );  
        ?>
    </article>
</div>
<!-- listing-item end-->  