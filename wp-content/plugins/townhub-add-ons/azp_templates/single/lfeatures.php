<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $title = $num_feature = $hide_widget_on = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'lfeatures',
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
if(( $hide_widget_on_check = townhub_addons_is_hide_on_plans($hide_widget_on) ) !== 'true') :
$features = get_the_terms(get_the_ID(), 'listing_feature');
if ( $features && ! is_wp_error( $features ) ){ 
?>
<div class="<?php echo $classes; ?> authplan-hide-<?php echo $hide_widget_on_check;?>" <?php echo $el_id;?>> 
    <div class="for-hide-on-author"></div>
    <!-- lsingle-block-box --> 
    <div class="lsingle-block-box">
        <?php if($title != ''): ?>
        <div class="lsingle-block-title">
            <h3><?php echo $title; ?></h3>
        </div>
        <?php endif; ?>
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
            // var_dump($feature_group);

        ?>

        <div class="lsingle-block-content">
            <div class="listing-features fl-wrap">
                <ul class="fea-parent no-list-style">
            <?php
                $count = 1;
                foreach( $feature_group as $tid => $tvalue){
                    if($count <= (int)$num_feature){
                        if( is_array( $tvalue ) && !empty( $tvalue ) ){
                            $term = get_term_by( 'id', $tid , 'listing_feature' );
                            // var_dump($term);
                            if($term){
                                $term_metas = townhub_addons_custom_tax_metas($term->term_id, 'listing_feature'); 
                                echo sprintf( '<li class="fea-has-children"><a href="%2$s">%1$s</a><ul class="fea-children no-list-style">',
                                    $term_metas['icon'] != '' ? '<i class="'.$term_metas['icon'].'"></i>' . esc_html( $term->name ) : esc_html( $term->name ),
                                    townhub_addons_get_term_link( $term->term_id, 'listing_feature' )
                                );

                                foreach ($tvalue as $id => $name) {
                                    $term_metas = townhub_addons_custom_tax_metas($id, 'listing_feature'); 

                                    echo sprintf( '<li><a href="%2$s">%1$s</a></li>',
                                        $term_metas['icon'] != '' ? '<i class="'.$term_metas['icon'].'"></i>' . esc_html( $name ) : esc_html( $name ),
                                        townhub_addons_get_term_link( $id, 'listing_feature' )
                                    );
                                }

                                echo '</ul></li>';
                            }
                            
                        }else{
                            $term_metas = townhub_addons_custom_tax_metas($tid, 'listing_feature'); 
                            echo sprintf( '<li><a href="%2$s">%1$s</a></li>',
                                $term_metas['icon'] != '' ? '<i class="'.$term_metas['icon'].'"></i>' . esc_html( $tvalue ) : esc_html( $tvalue ),
                                townhub_addons_get_term_link( $tid, 'listing_feature' )
                            );

                        }
                    }
                    $count++;    
                }

            ?>
                </ul>
            </div><!-- listing-features end -->  
        </div><!-- lsingle-block-content end -->  
    </div><!-- lsingle-block-box end -->  
</div>
<?php
    }
    // end features check
endif;// check hide on plans
