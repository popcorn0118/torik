<?php
/* add_ons_php */
$address = get_post_meta( get_the_ID(), ESB_META_PREFIX.'address', true );
$latitude = get_post_meta( get_the_ID(), ESB_META_PREFIX.'latitude', true );
$longitude = get_post_meta( get_the_ID(), ESB_META_PREFIX.'longitude', true );
if($latitude != '' && $longitude != '' ) :
?>
<section class="listing-hero-section hmap-section" id="lhead_sec">
    <div class="map-container">

    	<?php 
        $map_provider = townhub_addons_get_option('map_provider');
        $sinit_map = $map_provider == 'osm' ? 'yes' : townhub_addons_get_option('single_map_init', 'no');
        if( $sinit_map != 'yes' ): $sinit_map = 'no'; ?>
            <div class="singleMap-init-wrap mb-20"><a href="#" class="btn color2-bg initSingleMap"><?php echo esc_html_x( 'View Map', 'Single listing', 'townhub-add-ons' ); ?><i class="fal fa-map"></i></a></div>
        <?php endif; ?>
        <div id="<?php echo uniqid('singleMap'); ?>" class="singleMap singleMap-<?php echo esc_attr( $map_provider );?> singleMap-init-<?php echo $sinit_map;?>" data-lat="<?php echo esc_attr( $latitude );?>" data-lng="<?php echo esc_attr( $longitude );?>" data-loc="<?php echo esc_attr( $address );?>" data-zoom="<?php echo townhub_addons_get_option('gmap_single_zoom');?>"></div>


        
    </div>
</section>
<?php endif; 


