<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $images_to_show = $hide_widget_on = $hide_contacts_on = $hide_email = $hide_map = '';
$hide_au_message = $hide_phone = $hide_web = $hide_whatsapp = $hide_address = $auto_whatsapp = $hide_not_claimed = $disable_address_url = '';
// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'lcontacts',
    'azp-element-' . $azp_mID,  
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
if( $hide_not_claimed == 'yes' && get_post_meta( get_the_ID() , ESB_META_PREFIX.'verified', true ) !== '1' ) return;
$address = get_post_meta( get_the_ID(), ESB_META_PREFIX.'address', true );
$latitude = get_post_meta( get_the_ID(), ESB_META_PREFIX.'latitude', true );
$longitude = get_post_meta( get_the_ID(), ESB_META_PREFIX.'longitude', true );
$phone = get_post_meta( get_the_ID(), ESB_META_PREFIX.'phone', true );
$whatsapp = get_post_meta( get_the_ID(), ESB_META_PREFIX.'whatsapp', true );
$email = get_post_meta( get_the_ID(), ESB_META_PREFIX.'email', true );
$website = get_post_meta( get_the_ID(), ESB_META_PREFIX.'website', true );
if( $auto_whatsapp == 'yes' && empty($whatsapp) ) $whatsapp = $phone;
// $listing_author_id = get_the_author_meta('ID');
// Esb_Class_Membership::current_plan( get_post_field( 'post_author', get_the_ID() ) )
if(( $hide_widget_on_check = townhub_addons_is_hide_on_plans($hide_widget_on) ) !== 'true') :
?>
<div class="<?php echo $classes; ?> authplan-hide-<?php echo $hide_widget_on_check;?>" <?php echo $el_id;?>>
    <div class="for-hide-on-author"></div>
    <!--box-widget-item -->
    <div class="box-widget-item fl-wrap block_box">
        <?php if($title != ''): ?>
        <div class="box-widget-item-header">
            <h3><?php echo $title; ?></h3>
        </div>
        <?php endif; ?>
        <div class="box-widget">
            <?php
            if($latitude != '' && $longitude != '' && $hide_map != 'yes' ) : 
                $dataArr = array();
                $map_provider = townhub_addons_get_option('map_provider');
            ?>
            <div class="map-container map-<?php echo esc_attr( $map_provider );?>-container">
                <?php 
                $sinit_map = $map_provider == 'osm' ? 'yes' : townhub_addons_get_option('single_map_init', 'no');
                if( $sinit_map != 'yes' ): $sinit_map = 'no'; ?>
                    <div class="singleMap-init-wrap"><a href="#" class="btn color2-bg initSingleMap"><?php echo esc_html_x( 'View Map', 'Single listing', 'townhub-add-ons' ); ?><i class="fal fa-map"></i></a></div>
                <?php endif; ?>
                <div id="<?php echo uniqid('singleMap'); ?>" 
                    class="singleMap singleMap-<?php echo esc_attr( $map_provider );?> singleMap-init-<?php echo $sinit_map;?>" 
                    data-lat="<?php echo esc_attr( $latitude );?>" 
                    data-lng="<?php echo esc_attr( $longitude );?>" 
                    data-loc="<?php echo esc_attr( $address );?>" 
                    data-zoom="<?php echo townhub_addons_get_option('gmap_single_zoom');?>" 
                    data-marker="<?php echo esc_url( townhub_addons_get_attachment_thumb_link( townhub_addons_get_listing_marker( get_the_ID() ) ) ); ?>"></div>
                
            </div>
            <?php 
            endif; 
            ?>
            <div class="box-widget-content bwc-nopad">
                <div class="list-author-widget-contacts list-item-widget-contacts bwc-padside">
                    <ul class="no-list-style">
                        <?php
                        if( $hide_address != 'yes' && $address != '' && $longitude != '' && $latitude != ''): 
                            $address_url = 'javascript:void(0);';
                            if( $disable_address_url != 'yes' ) $address_url = 'https://www.google.com/maps/search/?api=1&query='.esc_attr($latitude).','.esc_attr($longitude);
                        ?>
                        <li class="aucontact-address"><span><?php _e( '<i class="fal fa-map-marker"></i> Address :', 'townhub-add-ons' );?></span> <a href="<?php echo $address_url;?>" target="_blank"  rel="nofollow"><?php echo esc_html($address);?></a></li>
                        <?php endif;?>
                        <?php 
                        if( $phone != '' && $hide_phone != 'yes' ): ?>
                        <li class="aucontact-phone"><span><?php _e( '<i class="fal fa-phone"></i> Phone :', 'townhub-add-ons' );?></span> <a href="tel:<?php echo esc_attr( $phone );?>"  rel="nofollow"><?php echo esc_html($phone)  ;?></a></li>
                        <?php endif;?>
                        <?php 
                        if($email != '' && $hide_email != 'yes' ): ?>
                        <li class="aucontact-email"><span><?php _e( '<i class="fal fa-envelope"></i> Mail :', 'townhub-add-ons' );?></span> <a href="mailto:<?php echo esc_attr( $email ) ;?>"  rel="nofollow"><?php echo  esc_html($email)  ;?></a></li>
                        <?php endif;?>
                        <?php 
                        if($website != '' && $hide_web != 'yes' ): ?>
                        <li class="aucontact-web"><span><?php _e( '<i class="fal fa-browser"></i> Website :', 'townhub-add-ons' );?></span> <a href="<?php echo $website ;?>" target="_blank"  rel="nofollow"><?php echo  esc_url($website ) ;?></a></li>
                        <?php endif;?>
                        <?php 
                        if($whatsapp != '' && $hide_whatsapp != 'yes' ): ?>
                        <li class="aucontact-whatsapp"><span><?php _e( '<i class="fab fa-whatsapp"></i> Whatsapp: ', 'townhub-add-ons' );?></span> <a href="<?php echo 'https://wa.me/'.$whatsapp; ?>" target="_blank"  rel="nofollow"><?php echo esc_html($whatsapp)  ;?></a></li>
                        <?php endif;?>
                    </ul>
                </div>
                <?php 
                $socials = get_post_meta( get_the_ID(), ESB_META_PREFIX.'socials', true );
                if( ( !empty($socials) ) || $hide_au_message != 'yes' ) : ?>
                <div class="list-widget-social bottom-bcw-box  fl-wrap">
                    <?php if( !empty($socials) ): ?> 
                    <ul class="no-list-style">
                        <?php 
                        foreach( (array)$socials as $social ) {
                            echo '<li><a href="'.esc_url( $social['url'] ).'" target="_blank" rel="nofollow"><i class="fab fa-'.esc_attr( $social['name'] ).'"></i></a></li>';
                        }
                        ?>
                    </ul>
                    <?php endif; ?>
                    <?php if($hide_au_message != 'yes'): ?>
                    <div class="bottom-bcw-box_link"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>#author-single-form" rel="nofollow" class="show-single-contactform tolt" data-microtip-position="top" data-tooltip="<?php esc_attr_e( 'Write Message', 'townhub-add-ons' ); ?>"><i class="fal fa-envelope"></i></a></div>
                    <?php endif; ?>
                </div>
                <?php 
                endif;?>

            </div>
        </div>
    </div>
</div>

<?php endif; 

