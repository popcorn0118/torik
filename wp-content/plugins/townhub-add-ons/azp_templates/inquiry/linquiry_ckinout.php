<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $label = $icon = $format = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
    'azp_element',
    'bkfield_ckinout',
    'azp-element-' . $azp_mID, 
    $el_class,
);

$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) ); 

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}
// if(townhub_get_option('filter_hide_open_now') !== 'yes') return; 

$checkin_get = isset($_GET['checkin']) ? $_GET['checkin'] : '';
$checkout_get = isset($_GET['checkout']) ? $_GET['checkout'] : '';
$default_value = !empty($checkin_get) && !empty($checkout_get) ? esc_attr($checkin_get).';'.esc_attr($checkout_get) : 'current';
           
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="bkfield-inner">    
        <div class="cth-daterange-picker"
            data-name="checkin" 
            data-name2="checkout" 
            data-format="<?php echo $format; ?>" 
            data-default="<?php echo esc_attr( $default_value ); ?>"
            data-label="<?php echo esc_attr( $label ); ?>" 
            data-icon="<?php echo $icon;?>" 
            data-selected="general_daterange"
        ></div>
    </div>
</div>