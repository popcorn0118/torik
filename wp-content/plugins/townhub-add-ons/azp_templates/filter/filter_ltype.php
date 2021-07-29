<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $ltypes = $width = $placeholder = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'filter_ltype',
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
$search_val = '';
if(isset( $_REQUEST['ltype'] ) && !empty( $_REQUEST['ltype'] )){
    $search_val = $_REQUEST['ltype'];
}
$ltypes_options = array();
$ltypes = explode('||', $ltypes);
if( !empty($ltypes) ){               
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="filter-item-inner">
        <select data-placeholder="<?php echo esc_attr($placeholder); ?>"  class="chosen-select" name="ltype">
            <?php if(!empty($placeholder)): ?><option value=""><?php echo esc_attr($placeholder); ?></option><?php endif; ?>
            <?php 
            foreach ($ltypes as $ltid) {
                if( !empty($ltid) ) echo '<option value="'.$ltid.'" '.selected( $search_val, $ltid,false ).'>'.get_the_title( $ltid ).'</option>';
            }
            ?>
        </select>
    </div>
</div>
<?php } 

