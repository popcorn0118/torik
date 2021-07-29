<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $title = $name = $width = $show_opt_lbl = $ftype = $hide_widget_on = $use_sec_style = '';
$title_block = '';
// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'lcustom_field lcfield-' . $name,
    'ftitle-block-' . $title_block,
    'azp-element-' . $azp_mID,
    'lcfield-wid-' . $width,
    'lcus-field-type-' . $ftype,
    'lcus-sec-style-' . $use_sec_style,
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
$value = get_post_meta( get_the_ID(), ESB_META_PREFIX.$name, true );
$fieldOptions = array();
if(!empty($value)):

    if( $show_opt_lbl == 'yes' ){

        $fields = townhub_addons_get_listing_type_fields_obj( get_post_meta( get_the_ID(), ESB_META_PREFIX.'listing_type_id', true ) );
        $fieldObj = null;
        foreach($fields as $fld) {
            if ($name == $fld->fieldName) {
                $fieldObj = $fld;
                break;
            }
        }
        if( $fieldObj && isset($fieldObj->options) && is_array($fieldObj->options) && !empty($fieldObj->options) ){
            foreach ($fieldObj->options as $fopt ) {
                $fieldOptions[$fopt->value] = $fopt->label;
            }
        }
    }
    if(( $hide_widget_on_check = townhub_addons_is_hide_on_plans($hide_widget_on) ) !== 'true') :
?>
<div class="<?php echo $classes; ?> authplan-hide-<?php echo $hide_widget_on_check;?>" <?php echo $el_id;?>>
    <div class="for-hide-on-author"></div>
    <?php 
    if( $use_sec_style == 'yes' ): ?>
    <div class="lsingle-block-box">
        <?php if( !empty($title) ): ?>
        <div class="lsingle-block-title">
            <h3><?php echo esc_html( $title ); ?></h3>
        </div>
        <?php endif; ?>
        <div class="lsingle-block-content">
    <?php endif; ?>
            <div class="lcfield-inner">
                <?php if( $use_sec_style != 'yes' && !empty($title) ): ?><span class="lcfield-title"><?php echo esc_html( $title ); ?></span><?php endif; ?>
                <?php 
                if( is_array( $value ) ):
                    foreach ( $value as $arval ) {
                        if( isset($fieldOptions[$arval]) ) $arval = $fieldOptions[$arval];
                        ?>
                        <span class="lcfield-value"><?php echo wp_kses_post( $arval ); ?></span>
                        <?php
                    }
                else: 
                    if( isset($fieldOptions[$value]) ) $value = $fieldOptions[$value];
                    switch ($ftype) {
                        case 'oembed':
                        ?>
                            <div class="lcfield-oembed"><?php echo wp_oembed_get( $value ); ?></div>
                        <?php
                            break;
                        case 'image':
                        ?>
                            <span class="lcfield-value"><?php echo wp_get_attachment_image( $value, 'full', false, array('class'=>'cus-field-image') ); ?></span>
                        <?php
                            break;
                        case 'link':
                        ?>
                            <span class="lcfield-value"><a href="<?php echo esc_url( $value ); ?>" target="_blank"><?php echo esc_url( $value ); ?></a></span>
                        <?php
                            break;
                        
                            
                        case 'file':
                        ?>
                            <span class="lcfield-value"><a href="<?php echo wp_get_attachment_url( $value ); ?>" target="_blank"><?php echo wp_get_attachment_url( $value ); ?></a></span>
                        <?php
                            break;
                        case 'dlfile':
                        ?>
                            <span class="lcfield-value"><a href="<?php echo wp_get_attachment_url( $value ); ?>" target="_blank"><?php _ex( 'View file', 'Custom field', 'townhub-add-ons' ); ?></a></span>
                        <?php
                            break;
                        case 'raw_text':
                        ?>
                            <span class="lcfield-value"><?php echo do_shortcode( $value ); ?></span>
                        <?php
                            break;
                        default:
                        ?>
                            <span class="lcfield-value"><?php echo wp_kses_post( $value ); ?></span>
                        <?php
                            break;
                    }
                endif; ?>
            </div>
    <?php 
    if( $use_sec_style == 'yes' ): ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php 
    endif;// check hide on plans
endif;
