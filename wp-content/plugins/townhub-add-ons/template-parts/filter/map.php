<?php
/* add_ons_php */
$map_provider = townhub_addons_get_option('map_provider');
?>
        <div id="map-main" class="main-map-ele main-map-<?php echo esc_attr( $map_provider );?>"></div>
        <?php if( $map_provider == 'googlemap' ): ?>
        <ul class="mapnavigation no-list-style mrg-0">
            <li><a href="#" class="prevmap-nav mapnavbtn"><span><i class="fas fa-caret-left"></i></span></a></li>
            <li><a href="#" class="nextmap-nav mapnavbtn"><span><i class="fas fa-caret-right"></i></span></a></li>
        </ul>
        <div class="scrollContorl mapnavbtn tolt" data-microtip-position="top-left" data-tooltip="<?php esc_attr_e( 'Enable Scrolling', 'townhub-add-ons' ); ?>"><span><i class="fal fa-unlock"></i></span></div>
        <div class="location-btn geoLocation tolt" data-microtip-position="top-left" data-tooltip="<?php esc_attr_e( 'Your location', 'townhub-add-ons' ); ?>"><span><i class="fal fa-location"></i></span></div>
        <?php endif; ?>
        
        <div class="map-overlay"></div>
        <div class="map-close"><i class="fas fa-times"></i></div>