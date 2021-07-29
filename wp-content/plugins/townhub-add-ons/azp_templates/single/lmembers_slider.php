<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $wid_title = $responsive = $hide_widget_on = ''; 

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'azp_members_slider',
    // 'list-single-main-item fl-wrap', 
    'azp-element-' . $azp_mID,
    $el_class,
);

$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) );  

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}

$lmembers = get_post_meta( get_the_ID(), '_cth_lmember', true );
if (!empty($lmembers)) {
    if(( $hide_widget_on_check = townhub_addons_is_hide_on_plans($hide_widget_on) ) !== 'true') :
?>
<div class="<?php echo $classes;?> authplan-hide-<?php echo $hide_widget_on_check;?>" <?php echo $el_id;?>>
    <div class="for-hide-on-author"></div>
    <div class="list-single-main-item fl-wrap">
        <?php if($wid_title != ''): ?>
        <div class="list-single-main-item-title fl-wrap">
            <h3><?php echo $wid_title; ?></h3>
        </div>
        <?php endif; ?>

        <?php 
        $slider_args = array();
        $breakpoints = array();
        $slidesPerView = array();
        $responsive = explode( ",", trim($responsive) );
        if( !empty($responsive) ){
            foreach ($responsive as $breakpoint) {
                $breakpoint = explode( ":", trim($breakpoint) );
                if( count($breakpoint) === 2 ){
                    $breakpoints[$breakpoint[0]] = array( 'slidesPerView'=>intval($breakpoint[1]) );
                    $slidesPerView[] = intval($breakpoint[1]);
                }
            }
        }
        if( !empty($breakpoints) ){
            $slider_args['slidesPerView'] = max($slidesPerView);
            $slider_args['breakpoints'] = $breakpoints;
        }
        ?>
        <div class="listing-members-slider single-slider-wrap fl-wrap">
            <div class="single-slider fl-wrap">
                <div class="swiper-container" data-options='<?php echo json_encode($slider_args); ?>'>
                    <div class="swiper-wrapper">
                        <?php 
                        foreach ((array)$lmembers as $key => $member) { ?>
                            <div class="team-box swiper-slide">
                                <?php townhub_addons_get_template_part('template-parts/member', false, array('member'=>$member)); ?>
                            </div>
                        <?php } ?> 
                    </div>
                </div>
            </div>
            <div class="listing-carousel_pagination">
                <div class="listing-carousel_pagination-wrap">
                    <div class="ss-slider-pagination"></div>
                </div>
            </div>
            <div class="ss-slider-cont ss-slider-cont-prev color2-bg"><i class="fal fa-long-arrow-left"></i></div>
            <div class="ss-slider-cont ss-slider-cont-next color2-bg"><i class="fal fa-long-arrow-right"></i></div>
        </div>
    </div>
</div>
<?php 
    endif;// check hide on plans
} 
