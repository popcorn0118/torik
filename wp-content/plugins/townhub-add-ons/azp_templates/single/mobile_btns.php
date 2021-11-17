<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $show_phone = $show_email = $show_direction = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
    'azp_element',
    'azp_mobile-btns',
    'mb-btns-wrap',
    'azp-element-' . $azp_mID,  
    $el_class,
);
$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) ); 
if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}
$latitude = get_post_meta( get_the_ID(), '_cth_latitude', true );
$longitude = get_post_meta( get_the_ID(), '_cth_longitude', true );
$phone = get_post_meta( get_the_ID(), '_cth_phone', true );
$email = get_post_meta( get_the_ID(), '_cth_email', true );
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="mb-btns">
        <?php 
        if($show_phone == 'yes' && $phone != ''): ?>
        <a href="tel:<?php echo esc_attr( $phone );?>" class="mb-btn mb-btn-call"><i class="fal fa-phone"></i></a>
        <?php endif;?>

        <?php 
        if($show_email == 'yes' && $email != ''): ?>
        <a href="mailto:<?php echo esc_attr( $email ) ;?>" class="mb-btn mb-btn-booking"><i class="fal fa-envelope"></i></a>
        <?php endif;?>

        
        <?php 
        if($show_direction == 'yes' && $latitude != '' && $longitude != ''): ?>
        <a class="mb-btn mb-btn-direction" href="https://www.google.com/maps/search/?api=1&query=<?php echo $latitude.','.$longitude;?>" target="_blank"><i class="fal fa-map-marker"></i></a>
        <?php endif;?>
    </div>
</div>