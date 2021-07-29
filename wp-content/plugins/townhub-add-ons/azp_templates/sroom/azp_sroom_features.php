<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $images_to_show = $num_feature = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'azp_rfeatures',
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
$features = get_the_terms(get_the_ID(), 'listing_feature');
if ( $features && ! is_wp_error( $features ) ){ 
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <!--ajax-modal-details-box-->
    <div class="ajax-modal-details-box">
        <h3><?php esc_html_e( 'Room Amenities', 'townhub-add-ons' ); ?></h3>   
     <?php
        $feature_group = array();
        foreach( $features as $key => $term){
            if(townhub_addons_get_option('feature_parent_group') == 'yes'){
                if($term->parent){
                    if( !isset($feature_group[$term->parent]) || !is_array($feature_group[$term->parent]) ) $feature_group[$term->parent] = array();
                    $feature_group[$term->parent][$term->term_id] = $term->name;
                }else{
                    if(!isset($feature_group[$term->term_id])) $feature_group[$term->term_id] = $term->name;
                }
            }else{
                if(!isset($feature_group[$term->term_id])) $feature_group[$term->term_id] = $term->name;
            }
                
        }
    ?>
    <div class="listing-features lroom-features fl-wrap">
        <ul class="dis-flex flw-wrap">
            <?php
            $count = 1;
            foreach( $feature_group as $tid => $tvalue){
                if($count <= (int)$num_feature){
                    if( is_array( $tvalue ) && count( $tvalue ) ){
                        $term = get_term_by( 'id', $tid , 'listing_feature' ); 
                        // var_dump($term);
                        if($term){
                            $term_meta = get_term_meta( $term->term_id, ESB_META_PREFIX.'term_meta', true );

                            echo sprintf( '<li class="fea-has-children">%1$s<ul class="fea-children">',
                                isset($term_meta['icon_class'])? '<i class="'.$term_meta['icon_class'].'"></i><span>' . esc_html( $term->name ).'</span>' : esc_html( $term->name )
                            );

                            foreach ($tvalue as $id => $name) {
                                $term_meta = get_term_meta( $id, ESB_META_PREFIX.'term_meta', true );

                                echo sprintf( '<li>%1$s</li>',
                                    isset($term_meta['icon_class'])? '<i class="'.$term_meta['icon_class'].'"></i>' . esc_html( $name ) : esc_html( $name )
                                );
                            }

                            echo '</ul></li>';
                        }
                        
                    }else{
                        $term_meta = get_term_meta( $tid, ESB_META_PREFIX.'term_meta', true );

                        echo sprintf( '<li>%1$s</li>',
                            isset($term_meta['icon_class'])? '<i class="'.$term_meta['icon_class'].'"></i><span>' . esc_html( $tvalue ).'</span>' : esc_html( $tvalue )
                        );
                    }
                }
                $count++;    
            }

        ?>
        </ul>
     </div>
     <!--ajax-modal-details-box end-->
</div>
<?php }; ?>