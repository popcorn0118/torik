<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $title = $responsive = $order_by = $order = $hide_widget_on = ''; 

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'azp_rooms_slider',
    // 'list-single-main-item fl-wrap', 
    'azp-element-' . $azp_mID,
    $el_class,
);

$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) );  

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}
if(( $hide_widget_on_check = townhub_addons_is_hide_on_plans($hide_widget_on) ) !== 'true') :
$lrooms = get_post_meta( get_the_ID(), ESB_META_PREFIX.'rooms_ids', true );  
$listing_type_ID = get_post_meta( get_the_ID(), ESB_META_PREFIX.'listing_type_id', true );
$child_pt = get_post_meta( $listing_type_ID, ESB_META_PREFIX.'child_type_meta', true );
$child_type = ($child_pt == 'product') ? 'product' : 'lrooms';
if (!empty($lrooms)) {
?>
<div class="<?php echo $classes;?> authplan-hide-<?php echo $hide_widget_on_check;?>" <?php echo $el_id;?>>
    <div class="for-hide-on-author"></div>
    <div class="list-single-main-item fl-wrap">
        <?php if($title != ''): ?>
        <div class="list-single-main-item-title fl-wrap">
            <h3><?php echo $title; ?></h3>
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
        $args = array(
            'post_type'         => $child_type,
            'post_status'       => 'publish',
            'post__in'          => $lrooms,
            'posts_per_page'    => -1,
            // 'author'            => $current_user->ID,
            'orderby'           => $order_by,
            'order'             => $order,

        );
        $posts_query = new \WP_Query($args);
        if($posts_query->have_posts()) :
        ?>
        <div class="listing-rooms-slider single-slider-wrap fl-wrap">
            <div class="single-slider fl-wrap">
                <div class="swiper-container" data-options='<?php echo json_encode($slider_args); ?>'>
                    <div class="swiper-wrapper">
                        <?php 
                        while($posts_query->have_posts()) : $posts_query->the_post();  ?>
                            <div class="room-box swiper-slide">
                                <?php 
                                    echo townhub_addons_azp_parser_listing( $listing_type_ID  , 'preview_room', get_the_ID() );
                                ?>
                            </div>
                        <?php 
                        endwhile; ?> 
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
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>     
    </div>
</div>
<?php 
    } 
endif;// check hide on plans
