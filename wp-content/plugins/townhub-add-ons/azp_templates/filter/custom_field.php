<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $title = $fname = $ftype = $icon = $width = $placeholder = $options = '';

// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'filter_custom_field cusfilter-' . $fname,
    'fcus-type-' . $ftype,
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
if( isset($_GET[$fname]) && !empty($_GET[$fname]) ) 
    $search_val = esc_html($_GET[$fname]);



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
        <?php 
        switch ($ftype) {
            case 'number':
                ?>
                <div class="cusfield-number-wrap">
                    <input type="number" name="<?php echo esc_attr( $fname ); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo $search_val; ?>"/>
                </div>
                <?php
                break;
            case 'select':
                $options = json_decode(urldecode($options) , true) ;
                ?>
                <div class="cusfield-select-wrap">
                    <select data-placeholder="<?php echo esc_attr($placeholder); ?>" class="chosen-select" name="<?php echo esc_attr( $fname ); ?>">
                        <?php 
                        if( !empty($options) ){
                            foreach ($options as $opt) {
                                echo '<option value="'.$opt['value'].'" '.selected( $search_val, $opt['value'], false ).'>'.$opt['title'].'</option>';
                            }
                        } ?>
                        
                    </select>
                </div>
                <?php
                break;
            case 'multi':
                $options = json_decode(urldecode($options) , true) ;
                $requestVals = array();
                if( isset($_GET[$fname]) && !empty($_GET[$fname]) ){
                    if( is_array($_GET[$fname]) ){
                        $requestVals = $_GET[$fname];
                        $requestVals = array_map('esc_attr', $requestVals);
                    }else{
                        $requestVals = array( esc_html($_GET[$fname]) );
                    }
                } 
                ?>
                <div class="cusfield-select-wrap">
                    <select data-placeholder="<?php echo esc_attr($placeholder); ?>" multiple="multiple" class="chosen-select fil-select-multi" name="<?php echo esc_attr( $fname ); ?>[]">
                        <?php 
                        if( !empty($options) ){
                            foreach ($options as $ok => $opt) {
                                $selected = '';
                                if( in_array($opt, $requestVals) ) $selected = ' selected="selected"';
                                echo '<option value="'.$opt['value'].'" '.$selected.'>'.$opt['title'].'</option>';
                            }
                        } ?>
                        
                    </select>
                </div>
                <?php
                break;
            case 'radio':
                $options = json_decode(urldecode($options) , true) ;
                ?>
                <div class="cusfield-radio-wrap dis-flex">
                    <?php 
                    if( !empty($options) ){
                        foreach ($options as $opt) {
                            $rdoid = uniqid('cfradio');
                            echo    '<div class="cusfield-radio-item flex-items-center">
                                        <input type="radio" id="'.$rdoid.'" name="'.$fname.'" value="'.$opt['value'].'" '.checked( $search_val, $opt['value'], false ).'>
                                        <label for="'.$rdoid.'">'.$opt['title'].'</label>
                                    </div>';
                        }
                    } ?>
                </div>
                <?php
                break;
            case 'checkbox':
                $options = json_decode(urldecode($options) , true) ;
                ?>
                <div class="cusfield-checkbox-wrap dis-flex">
                    <?php 
                    if( !empty($options) ){
                        foreach ($options as $opt) {
                            $rdoid = uniqid('cfcheckbox');
                            echo    '<div class="cusfield-checkbox-item flex-items-center">
                                        <input type="checkbox" id="'.$rdoid.'" name="'.$fname.'" value="'.$opt['value'].'" '.checked( $search_val, $opt['value'], false ).'>
                                        <label for="'.$rdoid.'">'.$opt['title'].'</label>
                                    </div>';
                        }
                    } ?>
                </div>
                <?php
                break;
            default:
                ?>
                <div class="cusfield-text-wrap">
                    <input type="text" name="<?php echo esc_attr( $fname ); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo $search_val; ?>"/>
                </div>
                <?php
                break;
         } ?>
        
    </div>
        
</div>
