<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = '';
extract($azp_attrs);

$classes = array(
	'azp_element',
    'azp_text',
    'azp-element-' . $azp_mID,
    $el_class,
);
// $animation_data = self::buildAnimation($azp_attrs);
// $classes[] = $animation_data['trigger'];
// $classes[] = self::buildTypography($azp_attrs);//will return custom class for the element without dot
// $azptextstyle = self::buildStyle($azp_attrs);

$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) );

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}
 
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
	<?php echo $azp_content;?>
</div>