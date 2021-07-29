<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $cats = $max_level = $hide_empty = $width = $placeholder = $multiple_cats = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'filter_cat',
    'azp-element-' . $azp_mID,
    'filter-gid-item', 
    'filter-gid-wid-' . $width,
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
$listing_cats = townhub_addons_filter_cats($cats, $max_level, $hide_empty);
$search_cats = array();
if(is_tax('listing_cat')){
    $search_cats = array(get_queried_object_id());
}else{
    if(isset($_GET['lcats'])&&is_array($_GET['lcats'])){
        $search_cats = array_filter($_GET['lcats']);
        $search_cats = array_map('esc_attr', $search_cats);
    } 
}
                    
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="filter-item-inner">
        <select<?php if( $multiple_cats == 'yes' ) echo ' multiple="multiple"';?> data-placeholder="<?php echo esc_attr($placeholder); ?>"  class="chosen-select" name="lcats[]">
            <?php if(!empty($placeholder)): ?><option value=""><?php echo esc_attr($placeholder); ?></option><?php endif; ?>
            <?php 
            foreach ($listing_cats as $cat) {
                if(in_array($cat['id'], $search_cats)){
                    echo '<option value="'.$cat['id'].'" selected>'.str_repeat('-', $cat['level']).$cat['name'].'</option>';
                }else{
                    echo '<option value="'.$cat['id'].'">'.str_repeat('-', $cat['level']).$cat['name'].'</option>';
                }
                
            }
            ?>
        </select>
    </div>
</div>