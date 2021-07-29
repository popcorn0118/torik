<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $title = $cfield_name = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'lfeatured',
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
if( !empty($cfield_name) ){
    $imgID = get_post_meta( get_the_ID(), ESB_META_PREFIX.$cfield_name, true );
}else{
    $imgID = get_post_thumbnail_id();
}
if( !empty($imgID) ){
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>> 
    <!-- lsingle-block-box --> 
    <div class="lsingle-block-box lfeatured-image">
        <?php echo wp_get_attachment_image( $imgID, 'full', false, array('class' => 'resp-img') ); ?>
    </div><!-- lsingle-block-box end -->  
</div>
<?php
    }
    // end features check
?>