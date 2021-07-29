<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $cats = $max_level = $fields = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'filter_form_listing',
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
  
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="filter-inputs-row main-inputs">
        <?php 
        echo $azp_content;
        ?>
    </div>
</div>