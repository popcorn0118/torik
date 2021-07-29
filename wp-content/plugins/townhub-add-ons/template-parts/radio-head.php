<?php
/* add_ons_php */
if(!isset($name)) $name = 'radio_head';
if(!isset($checked)) $checked = 'no';
if(!isset($value)) $value = 'yes';
if(!isset($label)) $label = '';
?>
<div class="add-list-media-header">
    <label class="radio inline"> 
    <input type="radio" name="<?php echo esc_attr( $name );?>" value="<?php echo esc_attr( $value );?>" <?php checked( $checked, $value, true );?>>
    <span><?php echo $label ;?></span> 
    </label>
</div>
<!-- add-list-media-header end -->