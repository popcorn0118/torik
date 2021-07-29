<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $icon = $title = $width = $cats = $feacols = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'filter_features',
    'azp-element-' . $azp_mID,
    'features-cols-' . $feacols,
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
$features = explode('||', $cats); 
if( is_tax('listing_cat') ){
    $cat_id = get_queried_object_id();
    $term_meta = get_term_meta( $cat_id, '_cth_term_meta', true );
    if( isset($term_meta['features']) && !empty($term_meta['features']) && is_array($term_meta['features']) ){
        $features = $term_meta['features'];
    }
}
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="filter-item-inner">
        <div class="listsearch-input-wrap-header fl-wrap">
            <?php if( $icon != '' ): ?>
            <i class="ffield-icon ffield-icon-before <?php echo esc_attr($icon); ?>"></i>
            <?php endif;?>
            <?php echo $title; ?>
        </div>
        <?php 
        $option_features = array();
        if( !empty($features) ){
            foreach ($features as $fid) {
                $term = get_term( $fid, 'listing_feature' );
                if ( $term != null && ! is_wp_error( $term ) ){
                    $option_features[] = array(
                        'type' => 'feature', // is features field
                        'label' => $term->name,
                        'value' => $term->term_id
                        // 'lvalue' => ''
                    );
                }
            }
        }

        if(!empty($option_features)):
        ?>
        <div class="listing-features-view">
            <div class="listing-features">
            <?php 
            foreach ($option_features as $fea) {
                townhub_addons_get_template_part('templates-inner/feature-search',false,array('fea'=>$fea));
            }
            ?>
            </div>
        </div>
        <?php else: ?>
        <div class="listing-features-view loading-feas">
            <div class="listing-features"></div>
        </div>
        <?php endif; ?>
    </div>
</div>