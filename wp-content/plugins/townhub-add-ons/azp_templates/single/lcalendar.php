<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $title = $showing = $max = $dates_source = $single_select = $hide_if_empty = $show_min_nights =  $hide_widget_on = $scroll_ele_id = '';  

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'lcalendar',
    'azp-element-' . $azp_mID,
    $el_class,
);

$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) );  

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}
if(( $hide_widget_on_check = townhub_addons_is_hide_on_plans($hide_widget_on) ) !== 'true') :
$listing_dates = get_post_meta( get_the_ID(), ESB_META_PREFIX.'listing_dates', true );
if( $hide_if_empty == 'yes' && empty($listing_dates) ) return;
$min_nights = get_post_meta( get_the_ID(), ESB_META_PREFIX.'min_nights', true );
if( empty($min_nights) ) $min_nights = 2;
?>
<div class="<?php echo $classes; ?> authplan-hide-<?php echo $hide_widget_on_check;?>" <?php echo $el_id;?>>
    <div class="for-hide-on-author"></div>
    <!-- lsingle-block-box --> 
    <div class="lsingle-block-box">
        <?php if($title != ''): ?>
        <div class="lsingle-block-title">
            <h3><?php echo $title; ?></h3>
        </div>
        <?php endif; ?>
        <div class="lsingle-block-content lsingle-block-calendar-content">
            <div class="cth-availability-calendar"
                data-show="<?php echo $showing; ?>" 
                data-max="<?php echo $max; ?>" 
                data-name="checkin" 
                data-format="<?php _ex( 'YYYY-MM-DD', 'tour booking date format', 'townhub-add-ons' ); ?>" 
                data-default=""
                data-action="<?php echo $dates_source; ?>" 
                data-postid="<?php the_ID();?>" 
                data-selected="availability_dates"
                data-single="<?php echo esc_attr( $single_select ); ?>"
                min_nights="<?php echo esc_attr( $min_nights ); ?>" 
                scroll_ele_id="<?php echo esc_attr( $scroll_ele_id ); ?>" 
            ></div>
            <?php if( $show_min_nights == 'yes' && $single_select != 'yes' ) echo '<p class="avaical-min-nights">'.sprintf(_nx( 'Requires a minimum stay of %d night', 'Requires a minimum stay of %d nights', $min_nights, 'minimum nights to book', 'townhub-add-ons' ), $min_nights ).'</p>'; ?>
        </div>
    </div>
    <!-- lsingle-block-box end -->  
</div> 
<?php 
endif;// check hide on plans

