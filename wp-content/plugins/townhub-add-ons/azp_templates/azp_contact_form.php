<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element 
$azp_mID = $el_id = $el_class = $f_id = $f_title = $attrs = '';  
extract($azp_attrs);

$classes = array(
	'azp_element',
    'azp_contact_form',
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
if($f_id) $attrs .= ' id="'.$f_id.'"';
elseif($f_title) $attrs .= ' title="'.$f_title.'"';
$shortcode = do_shortcode( '[contact-form-7'.$attrs.']' ) ;
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>> 
	<?php if($f_title != ''): ?>
    <div class="el-contact-title">
        <h3><?php echo $f_title; ?></h3>
    </div>
    <?php endif; ?>
	<div class="contact-form7"><?php echo $shortcode;?></div>
</div>