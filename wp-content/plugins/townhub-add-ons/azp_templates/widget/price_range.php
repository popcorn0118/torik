<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $title = $images_to_show =  $hide_widget_on = $hide_claimed = $hide_claim = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'wprice_range',
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
$price_from = get_post_meta( get_the_ID(), '_price', true );
$price_to = get_post_meta( get_the_ID(), ESB_META_PREFIX.'price_to', true );
// if(!empty($price_to) || !empty($price_from ) ):
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
            <div class="box-widget-content">
                <div class="claim-price-wdget fl-wrap">
                    <div class="claim-price-wdget-content fl-wrap">
                    	<?php 
	                    if( !empty($price_from) ) :  ?>
	                    <div class="pricerange fl-wrap">
	                    <?php 
	                        _e( '<span class="lpricerange-text">Price : </span>', 'townhub-add-ons' );
	                        echo '<span class="lpricerange-prices"><span class="lpricerange-from">'.townhub_addons_get_price_formated($price_from).'</span>';
	                        if( !empty($price_to) ) echo '<span class="lpricerange-to">'. sprintf( __( ' - %s', 'townhub-add-ons' ), townhub_addons_get_price_formated($price_to) ) .'</span>';
                            echo '</span>';
	                    ?>
	                    </div>
	                    <?php 
	                    endif; ?>
	                    <?php if( $hide_claim != 'yes' && get_post_meta( get_the_ID() , ESB_META_PREFIX.'verified', true ) !== '1' ): ?>
                        <div class="claim-widget-link fl-wrap">
                            <?php _e( '<span>Own or work here?</span>', 'townhub-add-ons' ); ?>
                            <?php if(is_user_logged_in()) : ?>
                            <a class="open-listing-claim" href="#">
                            <?php else : 
                                $logBtnAttrs = townhub_addons_get_login_button_attrs( 'claim', 'current' );
                            ?>
                            <a class="<?php echo esc_attr( $logBtnAttrs['class'] );?>" href="<?php echo esc_url( $logBtnAttrs['url'] );?>" data-message="<?php esc_attr_e( 'You must be logged in to claim listing.', 'townhub-add-ons' ); ?>">
                            <?php endif; ?>
                                <?php _e( 'Claim Now!', 'townhub-add-ons' ); ?>
                            </a>
                            
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--box-widget-item end --> 
</div>
<?php 
    endif;
// endif; 
