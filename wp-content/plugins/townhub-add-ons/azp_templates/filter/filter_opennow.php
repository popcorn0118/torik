<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $title = $icon = $width = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'filter_opennow',
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
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="filter-item-inner">
        <div class="switchbtn text-center">
            <input type="checkbox" id="lfilter-opennow" name="status" value="open" class="switchbtn-checkbox">
            <label class="switchbtn-label" for="lfilter-opennow">
                <?php if( $icon != '' ): ?>
                <i class="<?php echo esc_attr($icon); ?>"></i>
                <?php endif;?>
                <?php if( $title != '' ): ?>
                <span><?php echo $title; ?></span>
                <?php endif;?>
            </label>
        </div>
    </div>
</div>