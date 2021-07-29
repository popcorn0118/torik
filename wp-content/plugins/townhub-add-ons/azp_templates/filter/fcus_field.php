<?php
/* add_ons_php */

//$azp_attrs,$azp_content,$azp_element
$azp_mID = $el_id = $el_class = $title = $fname = $ftype = $icon = $width = $placeholder = $options = '';
$add_all = $all_label = $ficon_before = '';
// var_dump($azp_attrs);
extract($azp_attrs);

$classes = array(
	'azp_element',
    'fcus_field fcus-' . $fname,
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
if( isset($_GET[$fname]) && !empty($_GET[$fname]) ){
    $search_val = esc_html($_GET[$fname]);
} 
$fname_opts = isset( ${'fname_opts'} ) ? ${'fname_opts'} : '';
$fname_labels = isset( ${'fname_labels'} ) ? ${'fname_labels'} : '';
?>
<div class="<?php echo $classes; ?>" <?php echo $el_id;?>>
    <div class="filter-item-inner">
        <?php
        if( $title != '' || $icon != '' ): ?>
        <label class="fcus-lbl<?php if( $icon != '' ) echo ' flabel-icon'; ?>">
            <?php if( $icon != '' && $ficon_before == 'yes' ): ?>
            <i class="ffield-icon ffield-icon-before <?php echo esc_attr($icon); ?>"></i>
            <?php endif;?>
            <?php if( $title != '' ) echo $title; ?>
            <?php if( $icon != '' && $ficon_before != 'yes' ): ?>
            <i class="ffield-icon <?php echo esc_attr($icon); ?>"></i>
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
                $options = explode( "||", $fname_opts );
                $optsLbls = explode( "||", $fname_labels );

                
                ?>
                <div class="cusfield-select-wrap">
                    <select data-placeholder="<?php echo esc_attr($placeholder); ?>" class="chosen-select" name="<?php echo esc_attr( $fname ); ?>">
                        <?php 
                        if( $add_all == 'yes' ) echo '<option value="">'.esc_html( $all_label ).'</option>';
                        if( !empty($options) ){
                            foreach ($options as $ok => $opt) {
                                echo '<option value="'.$opt.'" '.selected( $search_val, $opt, false ).'>'. ( isset($optsLbls[$ok]) ? $optsLbls[$ok] : __('Select value','townhub-add-ons') ) .'</option>';
                            }
                        } ?>
                        
                    </select>
                </div>
                <?php
                break;
            case 'multi':
                $options = explode( "||", $fname_opts );
                $optsLbls = explode( "||", $fname_labels );
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
                        if( $add_all == 'yes' ) echo '<option value="">'.esc_html( $all_label ).'</option>';
                        if( !empty($options) ){
                            foreach ($options as $ok => $opt) {
                                $selected = '';
                                if( in_array($opt, $requestVals) ) $selected = ' selected="selected"';
                                echo '<option value="'.$opt.'"'.$selected.'>'. ( isset($optsLbls[$ok]) ? $optsLbls[$ok] : __('Select value','townhub-add-ons') ) .'</option>';
                            }
                        } ?>
                        
                    </select>
                </div>
                <?php
                break;
            case 'radio':
                $options = explode( "||", $fname_opts );
                $optsLbls = explode( "||", $fname_labels );
                ?>
                <div class="cusfield-radio-wrap dis-flex">
                    <?php 
                    if( !empty($options) ){
                        foreach ($options as $ok => $opt) {
                            $rdoid = uniqid('cfradio');
                            echo    '<div class="cusfield-radio-item flex-items-center">
                                        <input type="radio" id="'.$rdoid.'" name="'.$fname.'" value="'.$opt.'" '.checked( $search_val, $opt, false ).'>
                                        <label for="'.$rdoid.'">'.( isset($optsLbls[$ok]) ? $optsLbls[$ok] : __('Select value','townhub-add-ons') ).'</label>
                                    </div>';
                        }
                    } ?>
                </div>
                <?php
                break;
            case 'checkbox':
                $options = explode( "||", $fname_opts );
                $optsLbls = explode( "||", $fname_labels );
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
                <div class="cusfield-checkbox-wrap dis-flex">
                    <?php 
                    if( !empty($options) ){
                        foreach ($options as $ok => $opt) {
                            $rdoid = uniqid('cfcheckbox');
                            $checked = '';
                            if( in_array($opt, $requestVals) ) $checked = ' checked="checked"';
                            echo    '<div class="cusfield-checkbox-item flex-items-center">
                                        <input type="checkbox" id="'.$rdoid.'" name="'.$fname.'[]" value="'.$opt.'" '.$checked.'>
                                        <label for="'.$rdoid.'">'.( isset($optsLbls[$ok]) ? $optsLbls[$ok] : __('Select value','townhub-add-ons') ).'</label>
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
