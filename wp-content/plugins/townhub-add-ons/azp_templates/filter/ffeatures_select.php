<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $cats = $max_level = $hide_empty = $width = $placeholder = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'ffeatures_select',
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
$listing_cats = townhub_addons_filter_cats($cats, $max_level, $hide_empty, 'listing_feature');
$search_cats = array();
// if(is_tax('listing_location')){
//     $loc_term = get_term( get_queried_object_id(), 'listing_location' );
//     if ( ! empty( $loc_term ) && ! is_wp_error( $loc_term ) ) $search_loc = $loc_term->slug;
// }else{
//     if(isset( $_GET['lfeas'] ) && !empty( $_GET['lfeas'] )){
//         $lfeas = explode(',',$_GET['lfeas']);
//         $search_loc = sanitize_title( $llocs[0] );
//     }

// }
if(isset( $_GET['lfeas'] ) && !empty( $_GET['lfeas'] )){
    $search_cats = array_filter((array)$_GET['lfeas']);
}
                    
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="filter-item-inner">
        <select data-placeholder="<?php echo esc_attr($placeholder); ?>"  class="chosen-select" name="lfeas[]" multiple="multiple">
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