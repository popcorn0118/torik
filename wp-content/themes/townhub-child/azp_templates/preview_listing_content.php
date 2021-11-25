<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $images_to_show = $hide_address = $hide_excerpt = $hide_features = $hide_cats = $hide_price_range = 
$hide_contacts = $hide_view_map = $hide_gallery = $hide_footer = $show_price = $show_locations = $show_web = $disable_address_url = '';
$num_feature = $show_pricerange = '';
// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'preview_listing_content',
    'azp-element-' . $azp_mID,
    'geodir-category-content',
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
$website = get_post_meta( get_the_ID(), ESB_META_PREFIX.'website', true );
$latitude = get_post_meta( get_the_ID(), ESB_META_PREFIX.'latitude', true );
$longitude = get_post_meta( get_the_ID(), ESB_META_PREFIX.'longitude', true );
$address_url = '#';
if( $disable_address_url != 'yes' && !empty($latitude) && !empty($longitude) ) $address_url = 'https://www.google.com/maps/search/?api=1&query='.esc_attr($latitude).','.esc_attr($longitude);

// if(has_post_thumbnail( )){ ?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>

    
    <div class="geodir-category-content-title fl-wrap">
        <div class="geodir-category-content-title-item">
            <h3 class="title-sin_map">
                <?php if( $GLOBALS['is_lad'] ) echo '<span class="litem-ad">'.__( 'AD', 'townhub-add-ons' ).'</span>'; ?>
                <a href="<?php the_permalink(  ); ?>"><?php 
                    the_title(); 
                    // $title = get_the_title( get_the_ID() );
                    // echo substr($title, 0, 100);
                ?></a>
                <?php if( get_post_meta( get_the_ID(), ESB_META_PREFIX.'verified', true ) == '1' ): ?>
                    <span class="verified-badge"><i class="fal fa-check"></i></span>
                <?php endif; ?>
            </h3>
            <?php if( $hide_address != 'yes'): 
                
            ?><div class="geodir-category-location fl-wrap"><a href="<?php echo $address_url; ?>" class="map-item" target="_blank"><i class="fas fa-map-marker-alt"></i><?php 
                echo townhub_addons_listing_get_address();
            ?></a></div><?php endif; ?>
        </div>
    </div>
    <div class="geodir-category-text fl-wrap">
        <?php 
        // the_excerpt();
        if( $hide_excerpt != 'yes') townhub_addons_the_excerpt_max_charlength( townhub_addons_get_option('excerpt_length','55') );
        ?>
        <?php 
        if( $show_locations == 'yes' ){
            $terms = wp_get_post_terms( get_the_ID(), 'listing_location', array( "fields" => "ids" ) );
            if ( $terms && ! is_wp_error( $terms ) ){ 
                $terms = trim( implode( ',', (array) $terms ), ' ,' );
                wp_list_categories( 'style=flat&separator=&title_li=&taxonomy=' . 'listing_location' . '&include=' . $terms );
            }
        } ?>
        <div class="lcfields-wrap dis-flex"><?php echo wp_kses_post( $azp_content ); ?></div>
        <?php 
        if( $hide_features != 'yes'):
        $terms = get_the_terms(get_the_ID(), 'listing_feature');
        if ( $terms && ! is_wp_error( $terms ) ){  
        ?>
        <div class="facilities-list fl-wrap dis-flex flw-wrap">
            <div class="facilities-list-title"><?php _e( 'Facilities: ', 'townhub-add-ons' ); ?></div>
            <ul class="no-list-style mrg-0 dis-inflex">
                <?php 
                $count = 1;
                foreach ($terms as $term) {
                    if($count > (int)$num_feature) break;
                    $term_metas = townhub_addons_custom_tax_metas($term->term_id, 'listing_feature'); 
                    //get_term_meta( $term->term_id, ESB_META_PREFIX.'term_meta', true );
                    ?>
                    <li class="tolt"  data-microtip-position="top" data-tooltip="<?php echo esc_attr( $term->name ); ?>">
                        <a href="<?php echo townhub_addons_get_term_link( $term->term_id, 'listing_feature' ); ?>"><i class="<?php echo esc_attr( $term_metas['icon'] ); ?>"></i></a>
                    </li>
                    <?php
                    $count++;
                }
                ?>
            </ul>
        </div>
        <?php } 
        endif;
        $price_from = get_post_meta( get_the_ID(), '_price', true );
        if( $show_price == 'yes' && !empty($price_from) ){
            echo '<div class="lcard-price">'.sprintf(_x( 'Price: <strong>%s</strong>', 'Lcard price', 'townhub-add-ons' ), townhub_addons_get_price_formated($price_from) ).'</div>';
        }
        $price_to = get_post_meta( get_the_ID(), ESB_META_PREFIX.'price_to', true );
        if( $show_pricerange == 'yes' && !empty($price_to) ) :  ?>
        <div class="lcard-price lcard-pricerange">
        <?php 
            _ex( 'Price range: ','Listing card', 'townhub-add-ons' );
            echo '<span class="lpricerange-prices"><strong class="lpricerange-from">'.townhub_addons_get_price_formated($price_from).'</strong>';
            if( !empty($price_to) ) echo '<strong class="lpricerange-to">'. sprintf( _x( ' - %s','Listing card', 'townhub-add-ons' ), townhub_addons_get_price_formated($price_to) ) .'</strong>';
            echo '</span>';
        ?>
        </div>
        <?php 
        endif;
        
        global $post;
        if( isset($post->listing_distance) && !empty($post->listing_distance) ){
            echo '<div class="lcard-distance">';
                echo sprintf(_x( 'Distance: <strong>%s km</strong>', 'Lcard distance', 'townhub-add-ons' ), number_format($post->listing_distance,1) );
            echo '</div>';
        }
        do_action( 'cth_listing_card_content' );
        ?>
    </div>
    <?php if( $hide_footer != 'yes' ): ?>
    <div class="geodir-category-footer fl-wrap dis-flex">
        <?php 
        if( $hide_cats != 'yes'){
        $cats = get_the_terms(get_the_ID(), 'listing_cat');
        if ( $cats && ! is_wp_error( $cats ) ){ ?>
            <div class="listing-cats-wrap dis-flex">
                <?php 
                foreach( $cats as $key => $cat){
                    $term_metas = townhub_addons_custom_tax_metas($cat->term_id); 
                    echo sprintf( '<a href="%1$s" class="listing-item-category-wrap flex-items-center">%3$s<span>%2$s</span></a> ',
                        townhub_addons_get_term_link( $cat->term_id, 'listing_cat' ),
                        esc_html( $cat->name ),
                        ($term_metas['icon'] != '' ? '<div class="listing-item-category '.$term_metas['color'].'"><i class="'.$term_metas['icon'].'"></i></div>' : ''),
                        $term_metas['color']
                    );
                }
                ?>
            </div>
        <?php }  
        } ?>
        <?php 
        if( $hide_price_range != 'yes'):
            $price_range = get_post_meta( get_the_ID(), ESB_META_PREFIX.'price_range', true ); 
            $prrrate = townhub_addons_get_price_range_rate($price_range);
            if( $prrrate > 0 ):
        ?>
        <div class="price-level geodir-category_price dis-flex">
            <span class="price-level-item" data-pricerating="<?php echo esc_attr( $prrrate ); ?>"></span>
            <span class="price-name-tooltip"><?php echo townhub_addons_get_listing_price_range($price_range, true); ?></span>
        </div>
        <?php endif; 
        endif; ?>
        <div class="geodir-opt-list dis-flex">
            <ul class="no-list-style">
                <?php 
                    // <li class="lcard-bot-view"><i class="fal fa-eye"></i> echo Esb_Class_LStats::get_stats(get_the_ID()); </li>
                ?>
                <?php if( $show_web == 'yes' && !empty($website) ): ?><li class="lcard-bot-web"><a href="<?php echo esc_url( $website ); ?>" target="_blank"><i class="fal fa-globe"></i></a></li><?php endif; ?>
                <?php if( $hide_contacts != 'yes'): ?><li class="lcard-bot-infos"><a href="#" class="show_gcc"><i class="fal fa-envelope"></i><span class="geodir-opt-tooltip"><?php _e( 'Contact Info', 'townhub-add-ons' ); ?></span></a></li><?php endif; ?>
                <?php if( $hide_view_map != 'yes' && townhub_addons_get_option('map_provider') == 'googlemap' ): ?><li class="lcard-bot-map"><a href="<?php echo $address_url; ?>" class="map-item" target="_blank"><i class="fal fa-map-marker-alt"></i><span class="geodir-opt-tooltip"><?php _e( 'On the map', 'townhub-add-ons' ); ?></span> </a></li><?php endif; ?>
                <?php 
                if( $hide_gallery != 'yes'):
                    $images = get_post_meta( get_the_ID(), ESB_META_PREFIX.'images', true );
                    if( !empty($images) && !is_array($images) ) { 
                        $images = explode(",", $images);
                        $images_gal = array();
                        foreach ($images as $key => $id) {
                            $images_gal[] = array('src'=> wp_get_attachment_url( $id ));
                        }
                ?>
                <li class="lcard-bot-gallery">
                    <div class="dynamic-gal gdop-list-link" data-dynamicPath='<?php echo json_encode($images_gal);?>'>
                        <i class="fal fa-search-plus"></i><span class="geodir-opt-tooltip"><?php _e( 'Gallery', 'townhub-add-ons' ); ?></span>
                    </div>
                </li>
                <?php } 
                endif; ?>
            </ul>
        </div>
        <?php
        if( $hide_contacts != 'yes'): 
            $phone = get_post_meta( get_the_ID(), ESB_META_PREFIX.'phone', true );
            $email = get_post_meta( get_the_ID(), ESB_META_PREFIX.'email', true );
            
            if($phone != '' || $email != '' || $website != '' ):
        ?>
        <div class="geodir-category_contacts">
            <div class="close_gcc"><i class="fal fa-times-circle"></i></div>
            <ul class="no-list-style">
                <?php if($phone != '' ): ?><li><span><i class="fal fa-phone"></i><?php _e( ' Call : ', 'townhub-add-ons' ); ?></span><a href="tel:<?php echo esc_attr( $phone );?>"><?php echo $phone ?></a></li><?php endif; ?>
                <?php if($email != '' ): ?><li><span><i class="fal fa-envelope"></i><?php _e( ' Write : ', 'townhub-add-ons' ); ?></span><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo $email ?></a></li><?php endif; ?>
                <?php if($website != '' ): ?><li><span><i class="fal fa-link"></i></span><a href="<?php echo esc_url( $website ); ?>"><?php _e( 'Visit website', 'townhub-add-ons' ); ?></a></li><?php endif; ?>
            </ul>
        </div>
        <?php endif; 
        endif; 
        ?>
    </div>
    <?php endif; ?>
</div>
<?php // }
