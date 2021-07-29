<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $cats = $max_level = $hide_empty = $width = $placeholder = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'filter_loc',
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
$listing_cats = townhub_addons_filter_cats($cats, $max_level, $hide_empty, 'listing_location');
$search_loc = '';
if(is_tax('listing_location')){
    $loc_term = get_term( get_queried_object_id(), 'listing_location' );
    if ( ! empty( $loc_term ) && ! is_wp_error( $loc_term ) ) $search_loc = $loc_term->slug;
}else{
    if(isset( $_GET['llocs'] ) && !empty( $_GET['llocs'] )){
        $llocs = explode(',',$_GET['llocs']);
        $search_loc = sanitize_title( $llocs[0] );
    }

}
                    
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="filter-item-inner">
        <select data-placeholder="<?php echo esc_attr($placeholder); ?>"  class="chosen-select" name="llocs">
            <?php if(!empty($placeholder)): ?><option value=""><?php echo esc_attr($placeholder); ?></option><?php endif; ?>
            <?php 
            foreach ($listing_cats as $loc) {
                echo '<option value="'.$loc['slug'].'" '.selected( $search_loc, $loc['slug'],false).'>'.str_repeat('-', $loc['level']) .$loc['name'].'</option>';
            }
            ?>
        </select>
</div>
    </div>