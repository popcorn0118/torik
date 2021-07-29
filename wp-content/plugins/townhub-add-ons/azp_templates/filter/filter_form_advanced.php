<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $cats = $max_level = $icon = $title = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
    'azp_element',
    'filter_form_advanced',
    'azp-element-' . $azp_mID,
    $el_class,
);
// $animation_data = self::buildAnimation($azp_attrs);
// $classes[] = $animation_data['trigger'];
// $classes[] = self::buildTypography($azp_attrs);//will return custom class for the element without dot
// $azplgallerystyle = self::buildStyle($azp_attrs);

$classes = preg_replace('/\s+/', ' ', implode(' ', array_filter($classes)));

if ($el_id != '') {
    $el_id = 'id="' . $el_id . '"';
}

?>

<div class="<?php echo $classes; ?>" <?php echo $el_id; ?>>
    <!-- hidden-listing-filter -->
    <div class="hidden-listing-filter fl-wrap">
        <div class="listsearch-input-wrap-header fl-wrap advanced-filter-head">
            <?php if( $icon != '' ): ?>
            <i class="ffield-icon ffield-icon-before <?php echo esc_attr($icon); ?>"></i>
            <?php endif;?>
            <?php echo $title; ?>
            <div class="advanced-filter-close">
                <span class="fal fa-times"></span>
            </div>
        </div>
        <div class="fl-wrap mar-btoom">
            <div class="filter-inputs-row advanced-inputs">
            <?php
            echo $azp_content;
            ?>
            </div>
        </div>
        
    </div>
    <!-- hidden-listing-filter end -->
    <div class="more-filter-option-wrap">
        <?php 
        $mopttext = __( 'More Options', 'townhub-add-ons' ); 
        $copttext = __( 'Close Options', 'townhub-add-ons' ); 
        ?>
        <div class="more-filter-option-btn act-hiddenpanel" data-ctext="<?php echo $copttext; ?>" data-mtext="<?php echo $mopttext; ?>"><i class="far fa-plus"></i><span><?php echo $mopttext; ?></span></div>
        <div class="clear-filter-btn color"><i class="far fa-redo"></i><?php _e( 'Reset Filters', 'townhub-add-ons' ); ?></div>
    </div>
</div>