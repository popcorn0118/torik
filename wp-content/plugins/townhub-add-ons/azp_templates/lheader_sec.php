<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
// var_dump($azp_attrs);
$azp_mID = $el_id = $el_class = $hstyle = '';
extract($azp_attrs);

$classes = array(
	'azp_element',
    'lheader_sec',
    'lheader_sec-'.$hstyle,
    'azp-element-' . $azp_mID, 
    $el_class,
);
$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) );    
if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}

$headermedia = get_post_meta( get_the_ID() , ESB_META_PREFIX.'headermedia', true );
if( ($hstyle != 'map' && $hstyle != 'streetview') && isset($headermedia['type']) && $headermedia['type'] != '' ) $hstyle = $headermedia['type'];
$photos = array();
if( isset($headermedia['photos']) && !is_array($headermedia['photos']) ) $photos = explode(',', $headermedia['photos']);
if( empty($photos) ){
	$default_thumbnail = townhub_addons_get_option('default_thumbnail');
    if( $default_thumbnail && !empty($default_thumbnail['id']) ) $photos = array($default_thumbnail['id']);
}
$mp4 = '';
if( isset($headermedia['mp4']) ) $mp4 = $headermedia['mp4'];
$youtube = '';
if( isset($headermedia['youtube']) ) $youtube = $headermedia['youtube'];
$vimeo = '';
if( isset($headermedia['vimeo']) ) $vimeo = $headermedia['vimeo'];
$iframe = '';
if( isset($headermedia['iframe']) ) $iframe = $headermedia['iframe'];
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
<?php
switch ($hstyle) {
	case 'bgslideshow':
		townhub_addons_get_template_part( 'template-parts/single/head_bgslideshow', '', array( 'azp_attrs'=>$azp_attrs, 'photos'=> $photos ) );
		break;
	case 'bgslider':
		townhub_addons_get_template_part( 'template-parts/single/head_bgslider', '', array( 'azp_attrs'=>$azp_attrs, 'photos'=> $photos ) );
		break;
	case 'bgvideo':
		townhub_addons_get_template_part( 'template-parts/single/head_bgvideo', '', array( 'azp_attrs'=>$azp_attrs, 'photos'=> $photos, 'mp4'=>$mp4, 'youtube'=> $youtube, 'vimeo'=> $vimeo ) );
		break;
	case 'map':
		townhub_addons_get_template_part( 'template-parts/single/head_map', '' );
		break;
	case 'streetview':
		townhub_addons_get_template_part( 'template-parts/single/head_streetview', '' );
		break;
	case 'iframe':
		townhub_addons_get_template_part( 'template-parts/single/head_iframe', '', array( 'azp_attrs'=>$azp_attrs, 'iframe'=>$iframe ) );
		break;
	default:
		townhub_addons_get_template_part( 'template-parts/single/head_bgimage', '', array( 'azp_attrs'=>$azp_attrs, 'photos'=> $photos ) );
		break;
}
?>
</div>