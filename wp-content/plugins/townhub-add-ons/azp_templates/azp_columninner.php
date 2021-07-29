<?php
/* add_ons_php */


$azp_mID = $el_id = $columnwidthclass = $el_class = $wrapclass = $azp_bwid = '';
//$azp_attrs,$azp_content,$azp_element
extract($azp_attrs);

$classes = array(
    'azp_element_inner',
    'azp_col',
    'azp-element-' . $azp_mID,
    'azp-col-inner-' . $azp_bwid,
    $el_class,
);
// $animation_data = self::buildAnimation($azp_attrs);
// $classes[] = $animation_data['trigger'];
// $classes[] = self::buildTypography($azp_attrs);//will return custom class for the element without dot
// $colStyle = self::buildStyle($azp_attrs);
// $classes[] = self::parseResponsiveNew($azp_attrs);
// if(empty($columnwidthclass)){
// 	$classes[] = 'azp_col-sm-12';
// }else{
// 	$classes[] =str_replace("col-md-", "azp_col-sm-", $columnwidthclass);
// }
$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) );

$this->toStoreGlobalVar['columninneritems'][] = array(
    
    'el_id'=>$el_id,
    'el_class'=>$classes,
    'wrapclass'=>$wrapclass,
 //    'animationdata'=> $animation_data['data'],
	// 'columnstyle'=> $colStyle,
    'content'=>$azp_content
);
