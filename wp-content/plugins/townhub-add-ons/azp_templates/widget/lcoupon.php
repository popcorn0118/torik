<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $dec_banner = $content_baner = $book_url = $dis_coupon = $title = $hide_widget_on = '';    

// var_dump($azp_attrs);
extract($azp_attrs);
// var_dump($hide_widget_on);
$classes = array(
    'azp_element',
    'lcoupon-widget',
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
$coupon_ids = get_post_meta( get_the_ID(), ESB_META_PREFIX.'coupon_ids', true );
if( empty($coupon_ids) || !is_array($coupon_ids) ) return;
$key = 1;
$key_coupon = '';
foreach ($coupon_ids as $coupon) {
    if($dis_coupon == $key && $coupon != ''){
        $key_coupon = $coupon;
        break;
    } 
    $key++;
}
if( empty($key_coupon) ) return ;

$coupon_expiry_date =  get_post_meta( $key_coupon, ESB_META_PREFIX.'coupon_expiry_date', true );
$coupon_decs = get_post_meta( $key_coupon, ESB_META_PREFIX.'coupon_decs', true );
$timezone = get_post_meta( get_the_ID(), ESB_META_PREFIX."wkh_tz", true );  
$counter_date = '';
if( $coupon_expiry_date != '' ){
    $counter_date = townhub_addons_get_gmt_from_date($coupon_expiry_date, $timezone, 'm/d/Y H:i:s' );
}
if( Esb_Class_Date::compare( $counter_date, date_i18n( 'm/d/Y H:i:s', false, true ) ) ) return;
if(( $hide_widget_on_check = townhub_addons_is_hide_on_plans($hide_widget_on) ) !== 'true') :
?>

<div class="<?php echo $classes; ?> authplan-hide-<?php echo $hide_widget_on_check;?>" <?php echo $el_id;?>>
    <div class="for-hide-on-author"></div>
    <?php if( !empty($title) ): ?>
    <div class="box-widget-item-header">
        <h3><?php echo $title; ?></h3>
    </div>
    <?php endif; ?>
    <div class="box-widget counter-widget countdown-widget lcoupon-wrap" data-countdate="<?php if($counter_date != '') echo $counter_date; ?>">
        <div class="banner-wdget fl-wrap">
            <div class="bg"  data-bg="<?php echo get_the_post_thumbnail_url(get_the_ID(),'full'); ?>"></div>
            <div class="overlay"></div>
            <div class="banner-wdget-content fl-wrap">
                <h4><?php echo $coupon_decs ;?></h4>
                <div class="dis-flex lcoupon-countdown">
                    <div class="countdown fl-wrap dis-flex">
                        <div class="countdown-item">
                            <span class="days rot">00</span>
                            <p>days</p>
                        </div>
                        <div class="countdown-item">
                            <span class="hours rot">00</span>
                            <p>hours </p>
                        </div>
                        <div class="countdown-item">
                            <span class="minutes rot">00</span>
                            <p>minutes </p>
                        </div>
                        <div class="countdown-item">
                            <span class="seconds rot">00</span>
                            <p>seconds</p>
                        </div>
                    </div>
                </div>
                
                <div class="coupon-code-text"><?php echo esc_attr( get_post_meta( $key_coupon, ESB_META_PREFIX.'coupon_code', true ) ); ?></div>
                
                
            </div>
        </div>
    </div>

</div>
<?php 
endif;// check hide on plans 

