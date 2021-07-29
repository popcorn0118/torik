<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $bt_name = $bt_icon  = $bt_url = '';
$title = $showing = $max = $single_select = $show_min_nights = $scroll_ele_id = '';  
// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'azp_sroom_calendar',
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
$min_nights = get_post_meta( get_the_ID(), ESB_META_PREFIX.'min_nights', true );
if( empty($min_nights) ) $min_nights = 2;
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
	<div class="ajax-modal-details-box">
		<?php if($title != ''): ?>
	    <h3 class="rdetails-title"><?php echo $title; ?></h3>
	    <?php endif; ?>
		<div id="cth-availability-room"
			data-show="<?php echo $showing; ?>" 
	        data-max="<?php echo $max; ?>" 
	        data-name="checkin" 
	        data-format="<?php _ex( 'YYYY-MM-DD', 'tour booking date format', 'townhub-add-ons' ); ?>" 
	        data-default=""
	        data-action="sroom_dates" 
	        data-postid="<?php the_ID();?>" 
	        data-selected="availability_dates"
	        data-single="no"
	        min_nights="<?php echo esc_attr( $min_nights ); ?>" 
	        scroll_ele_id="<?php echo esc_attr( $scroll_ele_id ); ?>" 
	        ></div>
	    <?php if( $show_min_nights == 'yes' && $single_select != 'yes' ) echo '<p class="ravaical-min-nights">'.sprintf(_nx( 'Requires a minimum stay of %d night', 'Requires a minimum stay of %d nights', $min_nights, 'minimum nights to book', 'townhub-add-ons' ), $min_nights ).'</p>'; ?>
	</div>
</div>