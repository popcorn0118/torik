<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $icon = $title = $width = $dformat = ''; 

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'azp_filter_date',
    'azp-element-' . $azp_mID, 
    'filter-gid-item', 
    'filter-gid-wid-' . $width,
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
$checkin_get = isset($_GET['checkin']) ? $_GET['checkin'] : '';
$checkout_get = isset($_GET['checkout']) ? $_GET['checkout'] : '';
$default_value = !empty($checkin_get) && !empty($checkout_get) ? esc_attr($checkin_get).';'.esc_attr($checkout_get) : 'current';
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="filter-item-inner">
        <div class="cth-daterange-picker"
            data-name="checkin" 
            data-name2="checkout" 
            data-format="<?php echo esc_attr($dformat); ?>" 
            data-default="<?php echo esc_attr( $default_value ); ?>"
            data-label="<?php echo esc_attr( $title ); ?>" 
            data-icon="<?php echo esc_attr($icon);?>" 
            data-selected="slot_date"
        ></div>
        <!-- <span class="clear-singleinput"><i class="fal fa-times"></i></span> -->
    </div>
</div>