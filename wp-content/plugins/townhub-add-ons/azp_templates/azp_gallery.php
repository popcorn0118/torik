<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = '';
extract($azp_attrs);

// $classes = array(
// 	'azp_element',
//     'azp_gallery',
//     'azp-element-' . $azp_mID, 
//     $el_class,
// );
// $animation_data = self::buildAnimation($azp_attrs);
// $classes[] = $animation_data['trigger'];
// $classes[] = self::buildTypography($azp_attrs);//will return custom class for the element without dot
// $azptextstyle = self::buildStyle($azp_attrs);



if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}
if (!empty($image_url)) {
	$gallery = array();
	$gallery = explode(",", $image_url);
	$classes = array(
		'gallery-items fl-wrap mr-bot spad',
		$grid_cols.'-columns',
		$space .'-pad',
		'azp_element',
     	'azp_gallery',
     	'azp-element-' . $azp_mID, 
	    $el_class,
	);
	$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) );
    $items_width = explode(',',$items_width);                        
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="grid-sizer"></div>
    <?php foreach ($gallery as $key => $imgid) {
    		$tnsize = 'townhub-gallery-one';
            $item_cls = 'gallery-item';
            if(isset($items_width[$key])){
                switch ($items_width[$key]) {
                    case 'x2':
                        $item_cls .= ' gallery-item-second';
                        $tnsize = 'townhub-gallery-two';
                        break;
                    case 'x3':
                        $item_cls .= ' gallery-item-three';
                        $tnsize = 'townhub-gallery-three';
                        break;
                }
            }
   	?>
    	<div class="<?php echo esc_attr( $item_cls ); ?>">
	        <div class="grid-item-holder">
	            <div class="listing-item-grid">
	                <?php  echo wp_get_attachment_image( $imgid, $tnsize ); ?>
	            </div>
	        </div>
	    </div>
    <?php  } ?>
</div>
<?php } ?>