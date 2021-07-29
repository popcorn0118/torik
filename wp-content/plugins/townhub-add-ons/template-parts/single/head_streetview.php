<?php
/* add_ons_php */
// $address = get_post_meta( get_the_ID(), ESB_META_PREFIX.'address', true );
$latitude = get_post_meta( get_the_ID(), ESB_META_PREFIX.'latitude', true );
$longitude = get_post_meta( get_the_ID(), ESB_META_PREFIX.'longitude', true );
if($latitude != '' && $longitude != '' ) :
?>
<section class="listing-hero-section hstreetview-section" id="lhead_sec">
    <div class="lsingle-streetview">

        <div id="<?php echo uniqid('street-view'); ?>" class="lstreet-view lstreet-<?php echo esc_attr( townhub_addons_get_option('map_provider') );?>" data-lat="<?php echo esc_attr( $latitude );?>" data-lng="<?php echo esc_attr( $longitude );?>" data-zoom="<?php echo townhub_addons_get_option('gmap_single_zoom');?>"></div>

    </div>
</section>
<?php endif; 


