<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $title = $num_feature = $hide_widget_on = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'llocations',
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
$terms = wp_get_post_terms( get_the_ID(), 'listing_location', array( "fields" => "ids" ) );
if ( $terms && ! is_wp_error( $terms ) ){ 
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
        <div class="lsingle-block-content">
            <div class="listing-features fl-wrap">
                <?php 
                $terms = trim( implode( ',', (array) $terms ), ' ,' );
                wp_list_categories( 'style=flat&separator=&title_li=&taxonomy=' . 'listing_location' . '&include=' . $terms );

                ?> 
            </div><!-- listing-features end -->  
        </div><!-- lsingle-block-content end -->  
    </div><!-- lsingle-block-box end -->  
</div>
<?php
    }
    // end features check
endif;// check hide on plans
