<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class  = $title = $hide_widget_on = ''; 

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'lpromo-video',
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

$promo_video = get_post_meta( get_the_ID(), ESB_META_PREFIX.'promo_video', true );
if (!empty($promo_video)) {
    
    $url = isset($promo_video['url']) ? $promo_video['url'] : '';
    $image = !empty($promo_video['images']) ? $promo_video['images'] : '';
    if( !empty($url) ){

        if(( $hide_widget_on_check = townhub_addons_is_hide_on_plans($hide_widget_on) ) !== 'true') :
?>
<div class="<?php echo $classes; ?> authplan-hide-<?php echo $hide_widget_on_check;?>" <?php echo $el_id;?>>
    <div class="for-hide-on-author"></div>
    <?php 
    if( empty( $image ) ){ ?>
    <!-- lsingle-block-box --> 
    <div class="lsingle-block-box">
        <?php if($title != ''): ?>
        <div class="lsingle-block-title">
            <h3><?php echo $title; ?></h3>
        </div>
        <?php endif; ?>
        <div class="lsingle-block-content">
            <div class="promo-video-wrap">
                <?php echo wp_oembed_get(  $url ); ?>
            </div>
        </div>
    </div>
    <!-- lsingle-block-box end --> 
    <?php 
    }else{ ?>
    <!-- lsingle-block-box --> 
    <div class="lsingle-block-box no-border over-hide">
        <div class="promo-video-bg-wrap popup-video-ele">
            <?php echo wp_get_attachment_image( $image, 'townhub-featured', false, array('class'=>'respimg') );?>
            <a href="<?php echo esc_url( $url ); ?>" class="promo-link image-popup"><i class="fal fa-video"></i><span><?php echo $title; ?></span></a>
        </div>
    </div>
    <!-- lsingle-block-box end --> 
    <?php 
    } ?> 
    
</div>
<?php 
        endif;// check hide on plans
    }
}
