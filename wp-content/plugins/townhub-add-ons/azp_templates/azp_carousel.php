<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = ''; 
extract($azp_attrs);

$classes = array(
	'azp_element',
    'azp_carousel',
    'single-carousel-wrap',
    'azp-element-' . $azp_mID, 
    $el_class,
);
$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) ); 

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}
if (!empty($image_url)) {
	$gallery = $linkimage = array();
	$gallery = explode(",", $image_url);
	 // var_dump($gallery);
	if(!empty($link)){
        $seppos = strpos(strip_tags($link), "|");
        if($seppos !== false){
            $linkimage = explode("|", strip_tags($link));
        }else{
            $linkimage = preg_split( '/\r\n|\r|\n/', strip_tags($link) );
        }
    }
     if( strpos($thumbnail_size, "x") !== false){
        $thumbnail_size = explode("x", $thumbnail_size);
    }

    $dataArr = array();
    $dataArr['smartSpeed'] = (int)$speed;
    if($autoplay == 'yes') $dataArr['autoplay'] = true;
    if($loop == 'yes') 
        $dataArr['loop'] = true;
    else 
        $dataArr['loop'] = false;
    if($show_navigation == 'yes') 
        $dataArr['nav'] = true;
    else 
        $dataArr['nav'] = false;
    if($show_dots == 'yes') 
        $dataArr['dots'] = true;
    else 
        $dataArr['dots'] = false;

    if(!empty($responsive)){
        $classes .=' resp-ena';
        $dataArr['responsive'] = $responsive;
    }
    if(is_numeric($spacing)) $dataArr['margin'] = (int)$spacing;
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="sc-next sc-btn color2-bg"><i class="fas fa-caret-right"></i></div>
    <div class="sc-prev sc-btn color2-bg"><i class="fas fa-caret-left"></i></div>
	<div class="azp-images_carousel single-carousel" data-options='<?php echo json_encode($dataArr);?>'>
        
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php foreach ($gallery as $key => $img) { 
                    $links = isset($linkimage[$key])? esc_url($linkimage[$key] ) : 'javascript:void(0)';
                    ?>
                        <div class="swiper-slide azp_carousel-item">
                            <a class="azp_carousel-item-link" href="<?php echo $links;?>" target="<?php echo esc_attr($target );?>">
                                <?php echo wp_get_attachment_image( $img, $thumbnail_size, false, array('class'=>'resp-img') );?>
                            </a>
                        </div>
                <?php } ?>
            </div>
        </div>

    </div>
</div>
<?php } ?>