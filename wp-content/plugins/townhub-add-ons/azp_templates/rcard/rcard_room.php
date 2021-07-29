<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = ''; 

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'rcard_room',
    'azp-element-' . $azp_mID,
    $el_class,
);
// $animation_data = self::buildAnimation($azp_attrs);
// $classes[] = $animation_data['trigger'];
// $classes[] = self::buildTypography($azp_attrs);//will return custom class for the element without dot
// $azplgallerystyle = self::buildStyle($azp_attrs);

$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) );

if($el_id != ''){
    $el_id = 'id="'.$el_id.'"';
}
$price = get_post_meta( get_the_ID(), '_price', true );
$adults = get_post_meta( get_the_ID(), ESB_META_PREFIX.'adults', true );
$children = get_post_meta( get_the_ID(), ESB_META_PREFIX.'children', true );
$max_guests = intval($adults) + intval($children);
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="lrooms-item fl-wrap">
        <?php if(has_post_thumbnail()): ?>
        <div class="lrooms-media">
            <?php the_post_thumbnail(); ?>
        </div>
        <?php endif; ?>
        <div class="lrooms-details">
            <div class="lrooms-details-header fl-wrap">
                <?php if( !empty($price) ): ?><span class="lrooms-price"><?php echo sprintf(__( '%s <strong>/ night</strong>', 'townhub-add-ons' ), townhub_addons_get_price_formated($price) ) ?></span><?php endif; ?>
                <h3><?php the_title(); ?></h3>
                <?php if( !empty($max_guests) ): ?><h5><?php echo sprintf(__( 'Max Guests: <span>%d persons</span>', 'townhub-add-ons' ), $max_guests); ?></h5><?php endif; ?>
            </div>
            <?php 
            // the_excerpt();
            townhub_addons_the_excerpt_max_charlength(townhub_addons_get_option('excerpt_length','55'));
            ?>
            <div class="lroom-dbtn mt-30"><button class="btn big-btn color-bg flat-btn room-modal-open" data-roomid="<?php echo esc_attr( get_the_ID() );?>"><?php esc_html_e( 'Details' ,'townhub-add-ons'); ?><i class="fal fa-eye"></i></button></div>
        </div>
    </div>
</div>