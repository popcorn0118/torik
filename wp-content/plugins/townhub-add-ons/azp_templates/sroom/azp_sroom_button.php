<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $bt_name = $bt_icon  = $bt_url = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'azp_rbutton',
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
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>> 
    <div id="get-ID_listing">
        <button class="btn float-btn color2-bg" type="submit"><?php echo $bt_name; ?><i class="<?php echo $bt_icon ?>"></i></button>
        <input type="hidden" name="esb-checkout-type" value="listing">
        <input type="hidden" name="listing_id" value="0">  
        <input type="hidden" name="lb_room" value="<?php echo esc_attr( get_the_ID() );?>">  
    </div>
	
</div>