<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $title = $icon = $width = '';
$rmax = $rmin = $rstep = $rfrom = $rto = '';
// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'filter_price_range',
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

$currency_attrs = townhub_addons_get_currency_attrs();
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="filter-item-inner">
        <div class="price-rage-wrap fl-wrap">
                
            <?php
            if( $title != '' || $icon != '' ): ?>
            <div class="price-rage-wrap-title">
                <?php if( $icon != '' ): ?>
                <i class="ffield-icon ffield-icon-before <?php echo esc_attr($icon); ?>"></i>
                <?php endif;?>
                <?php echo $title; ?>
            </div>
            <?php endif;?>
            <div class="price-rage-item fl-wrap">
                <input type="text" class="price-range" data-min="0" data-max="4"  name="price_range"  data-step="1" value="$$">
            </div>
        </div>
    </div>

</div>
