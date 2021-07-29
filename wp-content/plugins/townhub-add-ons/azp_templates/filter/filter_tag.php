<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $title = $tags = $wid_title = $width = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
    'azp_element',
    'azp_filter_tag',
    'azp-element-' . $azp_mID,
    'filter-gid-item', 
    'filter-gid-wid-' . $width,
    $el_class,
);
$classes = preg_replace( '/\s+/', ' ', implode( ' ', array_filter( $classes ) ) ); 

if($el_id!=''){
    $el_id = 'id="'.$el_id.'"';
}
$tags = explode("||", $tags);
$tags = array_filter($tags);
if( empty($tags) ) return;

$search_tag = '';
if(is_tax('listing_tag')){
    $search_tag = get_queried_object_id();
}elseif( isset($_GET['listing_tags']) && is_array($_GET['listing_tags']) && !empty($_GET['listing_tags']) ){
    $search_tag = reset($_GET['listing_tags']);
    $search_tag = esc_attr( $search_tag );
} 


?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="listing-filter-tags">
        <?php if($wid_title != ''): ?>
        <h4 class="field-head"><?php echo $wid_title; ?></h4>
        <?php endif; ?>
        <div class="listing-ftags">
        <?php 
        foreach ($tags as $tag_id) {
            $ltag = get_term( $tag_id, 'listing_tag' );
            if ( $ltag != null && ! is_wp_error( $ltag ) ){
            ?>
            <div class="ltag-filter-wrap">
                <div class="switchbtn text-center">
                    <input id="listing_tags_filter_<?php echo $tag_id;?>" class="switchbtn-checkbox" type="checkbox" value="<?php echo $tag_id;?>" name="listing_tags[]" <?php checked( $search_tag, $tag_id, true ); ?>>
                    <label class="switchbtn-label" for="listing_tags_filter_<?php echo $tag_id;?>"><?php echo $ltag->name;?></label>
                </div>
            </div>
            <?php
            }
        }
        ?>
        </div>
    </div>
</div>


