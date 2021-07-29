<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $title = $icon = $width = $placeholder = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'filter_title',
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
$search_term = '';
if( isset($_GET['search_term']) && $_GET['search_term'] != '' ) 
    $search_term = stripslashes(esc_html($_GET['search_term']));

?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="filter-item-inner">
        <?php
        if( $title != '' || $icon != '' ): ?>
        <label class="flabel-icon">
            <?php if( $title != '' ) echo $title; ?>
            <?php if( $icon != '' ): ?>
            <i class="<?php echo esc_attr($icon); ?>"></i>
            <?php endif;?>
        </label>
        <?php endif;?>
        <input type="text" name="search_term" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo $search_term; ?>"/>
    </div>
        
</div>