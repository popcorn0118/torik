<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $image_url = $image_style = 
$click_action = $modal_id = $large_image = $image_link = 
$link_target = $style ='';  
extract($azp_attrs);

if (!empty($image_style) && $image_style == 'circle' ) {
	$style = 'azp-image-circle';
}
$classes = array(
	'azp_element',
    'azp_image',
    'azp-element-' . $azp_mID,
    $el_class,
    $style
);
// $animation_data = self::buildAnimation($azp_attrs);
// $classes[] = $animation_data['trigger'];
// $classes[] = self::buildTypography($azp_attrs);//will return custom class for the element without dot
// $azptextstyle = self::buildStyle($azp_attrs);

$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) );

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}
if(!empty($image_url)): 
?>
	<div class="<?php echo $classes; ?>" <?php echo $el_id;?>> 
		<?php if(!empty($video_link)): ?>
			<?php echo wp_get_attachment_image( $image_url, 'featured', false, array('class'=>'respimg') );?>
            <a href="<?php echo esc_url($video_link); ?>" class="promo-link gradient-bg image-popup"><i class="fa fa-play"></i><span><?php echo $name_video_link; ?></span></a>
        <?php else: ?>
        	<a href="<?php echo $image_link; ?>" target="<?php echo $link_target;?>"><?php echo wp_get_attachment_image( $image_url, 'featured', false, array('class'=>'respimg') );?></a>
		<?php endif ?>
	</div>
<?php endif; ?>