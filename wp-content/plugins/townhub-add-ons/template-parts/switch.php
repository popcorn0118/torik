<?php
/* add_ons_php */
if(!isset($name)) $name = 'onoffswitch';
if(!isset($checked)) $checked = 'no';
if(!isset($value)) $value = 'yes';
?>
<div class="onoffswitch">
    <input type="checkbox" name="<?php echo esc_attr( $name );?>" class="onoffswitch-checkbox" id="onoffswitch_<?php echo esc_attr( $name );?>" value="<?php echo esc_attr( $value );?>" <?php checked( $checked, $value, true );?>>
    <label class="onoffswitch-label" for="onoffswitch_<?php echo esc_attr( $name );?>">
        <span class="onoffswitch-inner"></span>
        <span class="onoffswitch-switch"></span>
    </label>
</div>
<!-- onoffswitch end -->