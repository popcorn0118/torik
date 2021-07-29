<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $icon = $title = $width = $dformat = $placeholder = ''; 

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'filter_evtdate',
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
$default_val = 'hide';
if( !empty($_REQUEST['event_date'])) $default_val = Esb_Class_Date::format( $_REQUEST['event_date'], 'Y-m-d');
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="filter-item-inner">
        <div class="cth-date-picker-wrap esb-field has-icon">
            <?php
            if( $title != '' || $icon != '' ): ?>
            <div class="lfield-header">
                <?php if( $icon != '' ): ?><span class="lfield-icon"><i class="<?php echo esc_attr( $icon ); ?>"></i></span><?php endif;?>
                <?php if( $title != '' ): ?><label class="lfield-label"><?php echo esc_html( $title ); ?></label><?php endif;?>
                
            </div>
            <?php endif;?>
            <div class="cth-date-picker" 
                data-name="event_date" 
                data-format="<?php echo esc_attr($dformat); ?>" 
                data-default="<?php echo $default_val; ?>"
                data-action="" 
                data-postid="" 
                data-selected="general_date"
                data-placeholder="<?php echo esc_attr($placeholder); ?>"
            ></div>
        </div>
    </div>
</div>
