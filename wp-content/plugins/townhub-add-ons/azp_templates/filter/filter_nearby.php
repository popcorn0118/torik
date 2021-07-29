<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $title = $icon = $placeholder = $use_auto = $hide_label = $label = $enable_distance = $show_distance = $width = ''; 

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'filter_nearby',
    'azp-element-' . $azp_mID,
    'filter-gid-item', 
    'filter-gid-wid-' . $width,
    $el_class,
);
// $animation_data = self::buildAnimation($azp_attrs);
// $classes[] = $animation_data['trigger'];
// $classes[] = self::buildTypography($azp_attrs);//will return custom class for the element without dot
// $azplgallerystyle = self::buildStyle($azp_attrs);

$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) ); 

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}           
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="filter-item-inner show-distance-filter nearby-inputs-wrap">
        <div class="nearby-input-wrap nearby-<?php echo townhub_addons_get_option('map_provider');?>" id="<?php echo esc_attr( uniqid('nearby-wrap') ); ?>" data-placeholder="<?php echo esc_attr( $placeholder ); ?>">
            <?php
            if( $title != '' || $icon != '' ): ?>
            <label class="flabel-icon">
                <?php if( $title != '' ) echo $title; ?>
                <?php if( $icon != '' ): ?>
                <i class="<?php echo esc_attr($icon); ?>"></i>
                <?php endif;?>
            </label>
            <?php endif;?>
            <?php $autoplaceid = uniqid('auto-place-loc'); ?>
            <input id="<?php echo esc_attr( $autoplaceid ); ?>" name="location_search" type="text" placeholder="<?php echo esc_attr( $placeholder ); ?>" class="qodef-archive-places-search location-input auto-place-loc" value="<?php echo isset($_GET['location_search']) ? esc_attr($_GET['location_search']) : ''; ?>"/>
            <button type="button" class="get-current-city"><i class="far fa-dot-circle"></i></button>
            <span class="autoplace-clear-input"><i class="far fa-times"></i></span>
        </div>
        <?php if($enable_distance == 'yes'): 
            $use_nearby = 'off';
            if(isset($_GET['nearby']) && $_GET['nearby'] == 'on') $use_nearby = 'on';

            $dfdistance = townhub_addons_get_option('distance_df');

            if( isset($_GET['distance']) && $_GET['distance'] != '' ) $dfdistance = esc_attr($_GET['distance']);
            
            if( $show_distance === 'yes' ):
                $nbcheckboxid = uniqid('nearby-checkbox');
        ?>
                <div class="nearby-distance-wrap">
                    <?php if( $use_nearby === 'on'): ?>
                    <input type="checkbox" id="<?php echo esc_attr( $nbcheckboxid ); ?>" class="dis-none nearby-checkbox" value="1" checked="checked">
                    <?php else: ?>
                    <input type="checkbox" id="<?php echo esc_attr( $nbcheckboxid ); ?>" class="dis-none nearby-checkbox" value="1">
                    <?php endif; ?>
                    
                    <div class="distance-input fl-wrap distance-filter dis-none">
                        <div class="distance-title"><?php echo sprintf( __( 'Radius around selected destination <span class="distance-value">%s</span> km.', 'townhub-add-ons' ), $dfdistance ); ?></div>
                        <div class="distance-radius-wrap fl-wrap">

                            <!-- <input name="distance" class="distance-radius rangeslider--horizontal" type="range" min="<?php echo townhub_addons_get_option('distance_min'); ?>" max="<?php echo townhub_addons_get_option('distance_max'); ?>" step="<?php _ex( '1', 'Distance search step value in kilometer', 'townhub-add-ons' ); ?>" value="<?php echo esc_attr($dfdistance); ?>" data-title="<?php _e( 'Radius around selected destination', 'townhub-add-ons' ); ?>"> -->
                            <input name="distance" class="single-range" type="text" data-min="<?php echo townhub_addons_get_option('distance_min'); ?>" data-max="<?php echo townhub_addons_get_option('distance_max'); ?>" data-step="<?php _ex( '1', 'Distance search step value in kilometer', 'townhub-add-ons' ); ?>" value="<?php echo esc_attr($dfdistance); ?>" data-title="<?php _e( 'Radius around selected destination', 'townhub-add-ons' ); ?>" data-prefix="<?php echo townhub_addons_get_option('distance_miles') == 'yes' ? __( 'mile', 'townhub-add-ons' ) : __( 'km', 'townhub-add-ons' ); ?>">
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <input type="hidden" name="distance" value="<?php echo esc_attr($dfdistance); ?>">
            <?php endif; ?>

        
            <input type="hidden" class="auto-place-nearby" name="nearby" value="<?php echo esc_attr( $use_nearby ); ?>">
            <input type="hidden" class="address_lat auto-place-lat" name="address_lat" value="<?php echo isset($_GET['address_lat']) ? esc_attr($_GET['address_lat']) : ''; ?>">
            <input type="hidden" class="address_lng auto-place-lng" name="address_lng" value="<?php echo isset($_GET['address_lng']) ? esc_attr($_GET['address_lng']) : ''; ?>">
            
            
        <?php endif; ?>
    </div>
</div>