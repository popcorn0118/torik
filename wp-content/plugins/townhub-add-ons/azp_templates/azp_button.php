<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $name = $align = ''; 
extract($azp_attrs);

$classes = array(
	'azp_element',
    'azp_button',
    'azp-element-' . $azp_mID,
    $el_class,
    'bt-algin-'.$align,
);
// $animation_data = self::buildAnimation($azp_attrs); 
// $classes[] = $animation_data['trigger'];
// $classes[] = self::buildTypography($azp_attrs);//will return custom class for the element without dot
// $azptextstyle = self::buildStyle($azp_attrs);

$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) );

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}
$clss= $css_color = $css_shape = '';
switch ($color) {
	case 'default':
		$css_color = 'color-bgs';
		break;
	case 'primary':
		$css_color = 'btn-primary';
		break;
	case 'success':
		$css_color = 'btn-success';
		break;
	case 'warning':
		$css_color = 'btn-warning';
		break;
	case 'white':
		$css_color = 'btn-default';
		break;
};
if($shape == 'rounded') {
	$css_shape = 'bt-rounded';
}else{
	$css_shape = 'btn';
}
$clss =array(
	$css_color,
	$css_shape,
	'bt-size-'.$size,
);
$clss = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $clss ) ) );
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
	<a class="<?php echo $clss;?>" href="<?php echo $link;?>" ><?php echo $name; ?>
		<?php if(!empty($icon)):?>
			<i class="<?php echo $icon; ?>"></i>
		<?php endif;?>
	</a>
</div>